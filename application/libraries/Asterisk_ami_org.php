<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asterisk_ami {
    private $socket;
    private $server;
    private $port;
    private $username;
    private $secret;
    private $connected = false;
    private $actionid = 0;
    private $CI;
    private $debug = false;

    public function __construct($params = array()) {
        // ตั้งค่าเริ่มต้น
        $this->server = isset($params['server']) ? $params['server'] : 'localhost';
        $this->port = isset($params['port']) ? $params['port'] : 5038;
        $this->username = isset($params['username']) ? $params['username'] : 'admin';
        $this->secret = isset($params['secret']) ? $params['secret'] : 'password';
        $this->debug = isset($params['debug']) ? $params['debug'] : false;
        
        // โหลด CodeIgniter instance
        $this->CI =& get_instance();
        $this->CI->load->helper('file');
    }

    /**
     * เปิดใช้งานหรือปิดใช้งานโหมด debug
     */
    public function set_debug($debug) {
        $this->debug = $debug;
    }
    
    /**
     * บันทึกข้อความ debug
     */
    private function log_debug($message) {
        if ($this->debug) {
            $log_file = APPPATH . 'logs/asterisk_ami_' . date('Y-m-d') . '.log';
            $timestamp = date('Y-m-d H:i:s');
            $log_message = "[{$timestamp}] {$message}\n";
            
            if (!write_file($log_file, $log_message, 'a')) {
                log_message('error', "ไม่สามารถเขียนไฟล์ log: {$log_file}");
            }
        }
    }

    /**
     * เชื่อมต่อกับ Asterisk AMI
     */
    public function connect() {
        $this->log_debug("กำลังเชื่อมต่อกับ Asterisk AMI ที่ {$this->server}:{$this->port}");
        
        $errno = 0;
        $errstr = '';
        
        // สร้างการเชื่อมต่อ socket
        $this->socket = @fsockopen($this->server, $this->port, $errno, $errstr, 10);
        
        if (!$this->socket) {
            $this->log_debug("การเชื่อมต่อล้มเหลว: {$errstr} ({$errno})");
            log_message('error', "AMI Connection Error: {$errstr} ({$errno})");
            return false;
        }
        
        // ตั้งค่า timeout สำหรับการอ่านข้อมูล
        stream_set_timeout($this->socket, 5);
        
        // อ่านข้อความต้อนรับ
        $welcome = fgets($this->socket, 4096);
        $this->log_debug("ข้อความต้อนรับ: {$welcome}");
        
        // ล็อกอินเข้าสู่ระบบ
        $auth = $this->login();
        
        if ($auth) {
            $this->connected = true;
            $this->log_debug("ล็อกอินสำเร็จ");
            return true;
        }
        
        $this->log_debug("ล็อกอินล้มเหลว");
        return false;
    }
    
    /**
     * ล็อกอินเข้าสู่ระบบ AMI
     */
    private function login() {
        $actionid = $this->get_action_id();
        $command = "Action: Login\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Username: {$this->username}\r\n";
        $command .= "Secret: {$this->secret}\r\n\r\n";
        
        $this->log_debug("ส่งคำสั่งล็อกอิน: \n{$command}");
        fputs($this->socket, $command);
        
        $response = $this->wait_response($actionid);
        $this->log_debug("การตอบกลับจากล็อกอิน: \n{$response}");
        
        return (strpos($response, 'Success') !== false);
    }
    
    /**
     * รอการตอบกลับจาก Asterisk
     */
    private function wait_response($actionid = null, $timeout = 5) {
        $start = time();
        $response = '';
        
        // ตั้งค่า timeout สำหรับการอ่านข้อมูล
        stream_set_timeout($this->socket, $timeout);
        
        while (time() - $start < $timeout) {
            $buffer = fgets($this->socket, 4096);
            
            if ($buffer === false) {
                // ถ้าไม่สามารถอ่านข้อมูลได้
                $info = stream_get_meta_data($this->socket);
                if ($info['timed_out']) {
                    $this->log_debug("การอ่านข้อมูลหมดเวลา");
                    break;
                }
                
                // ถ้าการเชื่อมต่อถูกปิด
                if (feof($this->socket)) {
                    $this->log_debug("การเชื่อมต่อถูกปิด");
                    $this->connected = false;
                    break;
                }
                
                // รอสักครู่แล้วลองอีกครั้ง
                usleep(100000); // 100ms
                continue;
            }
            
            $response .= $buffer;
            
            // ตรวจสอบว่าเป็นการตอบกลับที่สมบูรณ์หรือไม่
            if ($buffer === "\r\n") {
                // ถ้าไม่ได้ระบุ ActionID หรือพบ ActionID ที่ตรงกัน
                if ($actionid === null || strpos($response, "ActionID: {$actionid}") !== false) {
                    break;
                }
                
                // ถ้าเป็นการตอบกลับของคำสั่งอื่น ให้เริ่มรอการตอบกลับใหม่
                $response = '';
            }
        }
        
        return $response;
    }
    
    /**
     * สร้าง ActionID สำหรับคำสั่ง
     */
    private function get_action_id() {
        return 'ami_' . ++$this->actionid . '_' . time();
    }
    
    /**
     * ส่งคำสั่งไปยัง AMI
     */
    public function send_command($command, $wait_response = true) {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        
        // เพิ่ม ActionID ถ้ายังไม่มี
        if (strpos($command, 'ActionID:') === false) {
            $actionid = $this->get_action_id();
            $command = preg_replace('/^Action:/m', "Action:\r\nActionID: {$actionid}", $command, 1);
        } else {
            // ดึง ActionID จากคำสั่ง
            preg_match('/ActionID:\s*([^\r\n]+)/m', $command, $matches);
            $actionid = isset($matches[1]) ? trim($matches[1]) : null;
        }
        
        $this->log_debug("ส่งคำสั่ง: \n{$command}");
        fputs($this->socket, $command);
        
        if ($wait_response) {
            $response = $this->wait_response($actionid);
            $this->log_debug("การตอบกลับ: \n{$response}");
            return $response;
        }
        
        return true;
    }
    
    /**
     * สั่งให้ Asterisk โทรออก
     * 
     * @param string $extension เบอร์ภายในที่จะใช้โทรออก
     * @param string $number เบอร์ปลายทางที่ต้องการโทรไป
     * @param string $context context ที่จะใช้ (ค่าเริ่มต้น: from-internal)
     * @param string $callerid Caller ID ที่จะแสดง
     * @param int $priority ลำดับความสำคัญ (ค่าเริ่มต้น: 1)
     * @param int $timeout ระยะเวลาที่รอ (วินาที) (ค่าเริ่มต้น: 30)
     * @param string $channel_type ประเภทของช่องสัญญาณ (ค่าเริ่มต้น: SIP)
     * @return string ผลลัพธ์จาก AMI
     */
    public function originate_call($extension, $number, $context = 'from-internal', $callerid = '', $priority = 1, $timeout = 30, $channel_type = 'SIP') {
        if (empty($callerid)) {
            $callerid = $extension;
        }
        
        // แปลงเวลาเป็นมิลลิวินาที
        $timeout_ms = $timeout * 1000;
        
        $actionid = $this->get_action_id();
        $command = "Action: Originate\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Channel: {$channel_type}/{$extension}\r\n";
        $command .= "Exten: {$number}\r\n";
        $command .= "Context: {$context}\r\n";
        $command .= "Priority: {$priority}\r\n";
        $command .= "Timeout: {$timeout_ms}\r\n";
        $command .= "CallerID: Private Call\r\n";
        $command .= "Async: yes\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ทดลองโทรออกด้วยรูปแบบช่องสัญญาณต่างๆ
     */
    public function try_originate_call($extension, $number, $context = 'from-internal', $callerid = '', $priority = 1, $timeout = 30) {
        // ทดลองใช้ช่องสัญญาณต่างๆ
        $channel_types = ['SIP', 'PJSIP', 'Local'];
        
        foreach ($channel_types as $channel_type) {
            $this->log_debug("ทดลองโทรออกด้วยช่องสัญญาณ {$channel_type}");
            
            if ($channel_type === 'Local') {
                // สำหรับ Local channel ต้องเพิ่ม context
                $channel = "{$extension}@{$context}";
                $result = $this->originate_call_with_channel("{$channel_type}/{$channel}", $number, $context, $callerid, $priority, $timeout);
            } else {
                $result = $this->originate_call($extension, $number, $context, $callerid, $priority, $timeout, $channel_type);
            }
            
            if (strpos($result, 'Success') !== false) {
                $this->log_debug("โทรออกสำเร็จด้วยช่องสัญญาณ {$channel_type}");
                return $result;
            }
        }
        
        $this->log_debug("ไม่สามารถโทรออกได้ด้วยช่องสัญญาณทั้งหมดที่ลอง");
        return "Error: ไม่สามารถโทรออกได้ด้วยช่องสัญญาณทั้งหมดที่ลอง";
    }
    
    /**
     * สั่งให้ Asterisk โทรออกโดยระบุช่องสัญญาณโดยตรง
     */
    public function originate_call_with_channel($channel, $number, $context = 'from-internal', $callerid = '', $priority = 1, $timeout = 30) {
        if (empty($callerid)) {
            $callerid = $number;
        }
        
        // แปลงเวลาเป็นมิลลิวินาที
        $timeout_ms = $timeout * 1000;
        
        $actionid = $this->get_action_id();
        $command = "Action: Originate\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Channel: {$channel}\r\n";
        $command .= "Exten: {$number}\r\n";
        $command .= "Context: {$context}\r\n";
        $command .= "Priority: {$priority}\r\n";
        $command .= "Timeout: {$timeout_ms}\r\n";
        $command .= "CallerID: {$callerid}\r\n";
        $command .= "Async: yes\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงรายการสายที่กำลังใช้งานอยู่โดยใช้ CoreShowChannels
     * 
     * @return array รายการช่องสัญญาณและข้อมูลที่เกี่ยวข้อง
     */
    public function get_active_channels_detailed() {
        $actionid = $this->get_action_id();
        $command = "Action: CoreShowChannels\r\n";
        $command .= "ActionID: {$actionid}\r\n\r\n";
        
        $response = $this->send_command($command);
        $this->log_debug("ผลลัพธ์ CoreShowChannels: " . $response);
        
        $channels = [];
        $current_channel = null;
        $channel_data = [];
        $lines = explode("\r\n", $response);
        
        foreach ($lines as $line) {
            // ค้นหาบรรทัดที่มีข้อมูลช่องสัญญาณ
            if (strpos($line, 'Channel: ') === 0) {
                // ถ้ามีข้อมูลช่องสัญญาณก่อนหน้านี้ ให้บันทึกลงในอาร์เรย์
                if ($current_channel !== null && !empty($channel_data)) {
                    $channels[$current_channel] = $channel_data;
                }
                
                $current_channel = substr($line, 9);
                $channel_data = ['Channel' => $current_channel];
            } 
            // เก็บรายละเอียดของช่องสัญญาณปัจจุบัน
            elseif ($current_channel !== null && strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line, 2);
                $channel_data[trim($key)] = trim($value);
            }
            // ตรวจสอบว่าจบข้อมูลของช่องสัญญาณนี้หรือยัง
            elseif ($line === '' && $current_channel !== null) {
                // บันทึกข้อมูลช่องสัญญาณปัจจุบัน
                if (!empty($channel_data)) {
                    $channels[$current_channel] = $channel_data;
                }
                $current_channel = null;
                $channel_data = [];
            }
        }
        
        // บันทึกข้อมูลช่องสัญญาณสุดท้าย (ถ้ามี)
        if ($current_channel !== null && !empty($channel_data)) {
            $channels[$current_channel] = $channel_data;
        }
        
        $this->log_debug("พบช่องสัญญาณที่กำลังใช้งาน: " . count($channels));
        foreach ($channels as $channel => $data) {
            $this->log_debug("ช่องสัญญาณ: " . $channel . " ข้อมูล: " . json_encode($data));
        }
        
        return $channels;
    }
    
    /**
     * ดึงรายการสายที่กำลังใช้งานอยู่
     * 
     * @return array รายการสายที่กำลังใช้งาน
     */
    public function get_active_channels() {
        $channels_detailed = $this->get_active_channels_detailed();
        $channels = [];
        
        foreach ($channels_detailed as $channel_name => $channel_data) {
            $channels[] = $channel_name;
        }
        
        return $channels;
    }
    
    /**
     * ค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ที่ระบุโดยใช้ CoreShowChannels
     * ตรวจสอบ ChannelStateDesc: Up, CallerIDNum และ ConnectedLineNum
     * 
     * @param string $extension เบอร์ภายใน
     * @param string $number เบอร์ปลายทาง (ถ้ามี)
     * @return array รายการช่องสัญญาณที่เกี่ยวข้อง
     */
    public function find_channels_by_number($extension, $number = '') {
        $this->log_debug("กำลังค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ภายใน {$extension}" . (!empty($number) ? " และเบอร์ปลายทาง {$number}" : ""));
        
        // ใช้ CoreShowChannels เพื่อดึงข้อมูลช่องสัญญาณทั้งหมด
        $channels_detailed = $this->get_active_channels_detailed();
        $matched_channels = [];
        
        // แสดงข้อมูลช่องสัญญาณทั้งหมดที่พบ
        $this->log_debug("ช่องสัญญาณทั้งหมดที่พบ: " . count($channels_detailed));
        
        foreach ($channels_detailed as $channel_name => $channel_data) {
            $this->log_debug("กำลังตรวจสอบช่องสัญญาณ: " . $channel_name);
            $this->log_debug("ข้อมูลช่องสัญญาณ: " . json_encode($channel_data));
            
            // ตรวจสอบว่าช่องสัญญาณกำลังใช้งานอยู่หรือไม่ (ChannelStateDesc: Up)
            if (isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up') {
                $this->log_debug("ช่องสัญญาณ {$channel_name} มีสถานะ Up");
                
                // ตรวจสอบเบอร์ภายใน (CallerIDNum) และเบอร์ปลายทาง (ConnectedLineNum)
                $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
                $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
                
                if ($caller_match && (!empty($number) ? $connected_match : true)) {
                    $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไข: " . $channel_name);
                    $matched_channels[] = $channel_name;
                    continue;
                }
                
                // ตรวจสอบกรณีที่ไม่ได้ระบุเบอร์ภายใน แต่ระบุเบอร์ปลายทาง
                if (empty($extension) && $connected_match) {
                    $this->log_debug("พบช่องสัญญาณที่ตรงกับเบอร์ปลายทาง {$number}: " . $channel_name);
                    $matched_channels[] = $channel_name;
                    continue;
                }
            } else {
                $this->log_debug("ข้ามช่องสัญญาณ {$channel_name} เนื่องจากสถานะไม่ใช่ Up");
            }
        }
        
        // ถ้าไม่พบช่องสัญญาณ ให้ลองใช้วิธีอื่น
        if (empty($matched_channels)) {
            $this->log_debug("ไม่พบช่องสัญญาณจากการตรวจสอบปกติ ลองใช้วิธีอื่น");
            
            // ลองใช้คำสั่ง core show channels verbose
            $actionid = $this->get_action_id();
            $command = "Action: Command\r\n";
            $command .= "ActionID: {$actionid}\r\n";
            $command .= "Command: core show channels verbose\r\n\r\n";
            
            $response = $this->send_command($command);
            $this->log_debug("ผลลัพธ์ core show channels verbose: " . $response);
            
            // แยกบรรทัดและค้นหาช่องสัญญาณที่เกี่ยวข้อง
            $lines = explode("\r\n", $response);
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    if (preg_match('/^([^\s]+)/', $line, $matches)) {
                        $channel_name = $matches[1];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจาก core show channels: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // ถ้ายังไม่พบช่องสัญญาณ ให้ลองตรวจสอบทุกช่องสัญญาณที่กำลังใช้งาน
        if (empty($matched_channels)) {
            $this->log_debug("ไม่พบช่องสัญญาณที่เกี่ยวข้อง ลองตรวจสอบทุกช่องสัญญาณที่กำลังใช้งาน");
            
            // ดึงทุกช่องสัญญาณที่มีสถานะ Up
            foreach ($channels_detailed as $channel_name => $channel_data) {
                if (isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up') {
                    $this->log_debug("เพิ่มช่องสัญญาณที่มีสถานะ Up: " . $channel_name);
                    $matched_channels[] = $channel_name;
                }
            }
        }
        
        // ลบช่องสัญญาณซ้ำ
        $matched_channels = array_unique($matched_channels);
        
        $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องทั้งหมด: " . count($matched_channels));
        foreach ($matched_channels as $channel) {
            $this->log_debug("- " . $channel);
        }
        
        return $matched_channels;
    }
    
    /**
     * ค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ที่ระบุและแสดงรายละเอียด
     * 
     * @param string $extension เบอร์ภายใน
     * @param string $number เบอร์ปลายทาง (ถ้ามี)
     * @return array รายการช่องสัญญาณที่เกี่ยวข้องพร้อมรายละเอียด
     */
    public function find_channels_by_number_detailed($extension, $number = '') {
        $channels = $this->find_channels_by_number($extension, $number);
        $channels_detailed = [];
        
        foreach ($channels as $channel) {
            $actionid = $this->get_action_id();
            $command = "Action: Status\r\n";
            $command .= "ActionID: {$actionid}\r\n";
            $command .= "Channel: {$channel}\r\n\r\n";
            
            $response = $this->send_command($command);
            $channels_detailed[$channel] = $response;
        }
        
        return $channels_detailed;
    }
    
    /**
     * วางสาย (Hangup) ช่องสัญญาณที่ระบุ
     * 
     * @param string $channel ชื่อช่องสัญญาณที่ต้องการวางสาย
     * @return string ผลลัพธ์จาก AMI
     */
    public function hangup_channel($channel) {
        $this->log_debug("กำลังวางสายช่องสัญญาณ: " . $channel);
        
        $actionid = $this->get_action_id();
        $command = "Action: Hangup\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Channel: {$channel}\r\n\r\n";
        
        $response = $this->send_command($command);
        $this->log_debug("ผลลัพธ์การวางสาย: " . $response);
        
        return $response;
    }
    
    /**
     * วางสายทั้งหมดที่เกี่ยวข้องกับเบอร์ที่ระบุ
     * 
     * @param string $extension เบอร์ภายใน
     * @param string $number เบอร์ปลายทาง (ถ้ามี)
     * @return array ผลลัพธ์การวางสายแต่ละช่องสัญญาณ
     */
    public function hangup_calls_by_number($extension, $number = '') {
        $this->log_debug("กำลังค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ภายใน {$extension}" . (!empty($number) ? " และเบอร์ปลายทาง {$number}" : ""));
        
        // ใช้ CoreShowChannels โดยตรงเพื่อหาช่องสัญญาณที่ตรงกับเงื่อนไข
        $actionid = $this->get_action_id();
        $command = "Action: CoreShowChannels\r\n";
        $command .= "ActionID: {$actionid}\r\n\r\n";
        
        $response = $this->send_command($command);
        $this->log_debug("ผลลัพธ์ CoreShowChannels: " . $response);
        
        $channels = [];
        $current_channel = null;
        $channel_data = [];
        $lines = explode("\r\n", $response);
        
        foreach ($lines as $line) {
            
                // ถ้ามีข้อมูลช่องสัญญาณก่อนหน้านี้ ให้ตรว  {
            // ค้นหาบรรทัดที่มีข้อมูลช่องสัญญาณ
            if (strpos($line, 'Channel: ') === 0) {
                // ถ้ามีข้อมูลช่องสัญญาณก่อนหน้านี้ ให้ตรวจสอบว่าตรงกับเงื่อนไขหรือไม่
                if ($current_channel !== null && !empty($channel_data)) {
                    // ตรวจสอบว่าช่องสัญญาณนี้ตรงกับเงื่อนไขหรือไม่
                    $is_up = isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up';
                    $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
                    $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
                    
                    if ($is_up && ($caller_match && (!empty($number) ? $connected_match : true))) {
                        $channels[] = $current_channel;
                        $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไข: " . $current_channel);
                    }
                }
                
                $current_channel = substr($line, 9);
                $channel_data = ['Channel' => $current_channel];
            } 
            // เก็บรายละเอียดของช่องสัญญาณปัจจุบัน
            elseif ($current_channel !== null && strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line, 2);
                $channel_data[trim($key)] = trim($value);
            }
            // ตรวจสอบว่าจบข้อมูลของช่องสัญญาณนี้หรือยัง
            elseif ($line === '' && $current_channel !== null) {
                // ตรวจสอบช่องสัญญาณสุดท้าย
                $is_up = isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up';
                $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
                $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
                
                if ($is_up && ($caller_match && (!empty($number) ? $connected_match : true))) {
                    $channels[] = $current_channel;
                    $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไข: " . $current_channel);
                }
                
                $current_channel = null;
                $channel_data = [];
            }
        }
        
        // ตรวจสอบช่องสัญญาณสุดท้าย (ถ้ามี)
        if ($current_channel !== null && !empty($channel_data)) {
            $is_up = isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up';
            $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
            $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
            
            if ($is_up && ($caller_match && (!empty($number) ? $connected_match : true))) {
                $channels[] = $current_channel;
                $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไข: " . $current_channel);
            }
        }
        
        // ถ้าไม่พบช่องสัญญาณ ให้ลองใช้ find_channels_by_number
        if (empty($channels)) {
            $this->log_debug("ไม่พบช่องสัญญาณจากการตรวจสอบโดยตรง ลองใช้ find_channels_by_number");
            $channels = $this->find_channels_by_number($extension, $number);
        }
        
        $results = [];
        
        if (empty($channels)) {
            $this->log_debug("ไม่พบช่องสัญญาณที่เกี่ยวข้อง");
            return $results;
        }
        
        foreach ($channels as $channel) {
            $this->log_debug("กำลังวางสายช่องสัญญาณ: " . $channel);
            $results[$channel] = $this->hangup_channel($channel);
        }
        
        return $results;
    }
    
    /**
     * วางสายทั้งหมดที่กำลังโทรไปยังเบอร์ปลายทางที่ระบุ
     * 
     * @param string $number เบอร์ปลายทาง
     * @return array ผลลัพธ์การวางสายแต่ละช่องสัญญาณ
     */

    public function hangup_calls_by_destination($number) {
        $this->log_debug("กำลังค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ปลายทาง {$number}");
        
        // ใช้ hangup_calls_by_number โดยไม่ระบุเบอร์ภายใน
        return $this->hangup_calls_by_number('', $number);
    }
    
    /**
     * ตรวจสอบสถานะของ Asterisk
     * 
     * @return array ข้อมูลสถานะของ Asterisk
     */
    public function check_asterisk_status() {
        $actionid = $this->get_action_id();
        $command = "Action: CoreStatus\r\n";
        $command .= "ActionID: {$actionid}\r\n\r\n";
        
        $response = $this->send_command($command);
        
        $status = [];
        $lines = explode("\r\n", $response);
        
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $status[trim($key)] = trim($value);
            }
        }
        
        return $status;
    }
    
    /**
     * ตรวจสอบสถานะของ SIP Peers
     * 
     * @param string $peer ชื่อของ peer ที่ต้องการตรวจสอบ (ถ้าไม่ระบุจะตรวจสอบทั้งหมด)
     * @return string ข้อมูลสถานะของ SIP Peers
     */
    public function check_sip_peers($peer = '') {
        $actionid = $this->get_action_id();
        
        if (!empty($peer)) {
            $command = "Action: SIPshowpeer\r\n";
            $command .= "ActionID: {$actionid}\r\n";
            $command .= "Peer: {$peer}\r\n\r\n";
        } else {
            $command = "Action: SIPpeers\r\n";
            $command .= "ActionID: {$actionid}\r\n\r\n";
        }
        
        return $this->send_command($command);
    }
    
    /**
     * ตรวจสอบสถานะของ PJSIP Endpoints
     * 
     * @param string $endpoint ชื่อของ endpoint ที่ต้องการตรวจสอบ (ถ้าไม่ระบุจะตรวจสอบทั้งหมด)
     * @return string ข้อมูลสถานะของ PJSIP Endpoints
     */
    public function check_pjsip_endpoints($endpoint = '') {
        $actionid = $this->get_action_id();
        
        if (!empty($endpoint)) {
            $command = "Action: PJSIPShowEndpoint\r\n";
            $command .= "ActionID: {$actionid}\r\n";
            $command .= "Endpoint: {$endpoint}\r\n\r\n";
        } else {
            $command = "Action: PJSIPShowEndpoints\r\n";
            $command .= "ActionID: {$actionid}\r\n\r\n";
        }
        
        return $this->send_command($command);
    }
    
    /**
     * ปิดการเชื่อมต่อ
     */
    public function disconnect() {
        if ($this->connected && $this->socket) {
            $this->log_debug("กำลังปิดการเชื่อมต่อ");
            $command = "Action: Logoff\r\n\r\n";
            fputs($this->socket, $command);
            fclose($this->socket);
            $this->connected = false;
        }
    }
    
    /**
     * ทำความสะอาดเมื่อ object ถูกทำลาย
     */
    public function __destruct() {
        $this->disconnect();
    }
}
