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
        $command .= "CallerID: {$callerid}\r\n";
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
     * ดึงข้อมูลช่องสัญญาณทั้งหมดแบบดิบๆ (Raw) จาก CoreShowChannels
     */
    public function get_raw_channels() {
        $actionid = $this->get_action_id();
        $command = "Action: CoreShowChannels\r\n";
        $command .= "ActionID: {$actionid}\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลช่องสัญญาณทั้งหมดแบบละเอียดจาก Command: core show channels verbose
     */
    public function get_verbose_channels() {
        $actionid = $this->get_action_id();
        $command = "Action: Command\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Command: core show channels verbose\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลช่องสัญญาณทั้งหมดแบบละเอียดจาก Command: core show channels concise
     */
    public function get_concise_channels() {
        $actionid = $this->get_action_id();
        $command = "Action: Command\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Command: core show channels concise\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลช่องสัญญาณทั้งหมดแบบละเอียดจาก Command: sip show channels
     */
    public function get_sip_channels() {
        $actionid = $this->get_action_id();
        $command = "Action: Command\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Command: sip show channels\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลช่องสัญญาณทั้งหมดแบบละเอียดจาก Command: pjsip show channels
     */
    public function get_pjsip_channels() {
        $actionid = $this->get_action_id();
        $command = "Action: Command\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Command: pjsip show channels\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลการโทรทั้งหมดจาก Command: core show calls
     */
    public function get_calls() {
        $actionid = $this->get_action_id();
        $command = "Action: Command\r\n";
        $command .= "ActionID: {$actionid}\r\n";
        $command .= "Command: core show calls\r\n\r\n";
        
        return $this->send_command($command);
    }
    
    /**
     * ดึงข้อมูลทั้งหมดเกี่ยวกับช่องสัญญาณและการโทร
     * เพื่อใช้ในการดีบัก
     */
    public function debug_all_channels() {
        $debug_info = [];
        
        // ดึงข้อมูลช่องสัญญาณทั้งหมดแบบดิบๆ
        $debug_info['raw_channels'] = $this->get_raw_channels();
        
        // ดึงข้อมูลช่องสัญญาณทั้งหมดแบบละเอียด
        $debug_info['verbose_channels'] = $this->get_verbose_channels();
        
        // ดึงข้อมูลช่องสัญญาณทั้งหมดแบบกระชับ
        $debug_info['concise_channels'] = $this->get_concise_channels();
        
        // ดึงข้อมูลช่องสัญญาณ SIP
        $debug_info['sip_channels'] = $this->get_sip_channels();
        
        // ดึงข้อมูลช่องสัญญาณ PJSIP
        $debug_info['pjsip_channels'] = $this->get_pjsip_channels();
        
        // ดึงข้อมูลการโทรทั้งหมด
        $debug_info['calls'] = $this->get_calls();
        
        return $debug_info;
    }
    
    /**
     * ค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ที่ระบุโดยใช้วิธีการหลายรูปแบบ
     * 
     * @param string $extension เบอร์ภายใน
     * @param string $number เบอร์ปลายทาง (ถ้ามี)
     * @return array รายการช่องสัญญาณที่เกี่ยวข้อง
     */
    public function find_channels_by_number($extension, $number = '') {
        $this->log_debug("กำลังค้นหาช่องสัญญาณที่เกี่ยวข้องกับเบอร์ภายใน {$extension}" . (!empty($number) ? " และเบอร์ปลายทาง {$number}" : ""));
        
        // ดึงข้อมูลทั้งหมดเพื่อใช้ในการดีบัก
        $debug_info = $this->debug_all_channels();
        
        // บันทึกข้อมูลทั้งหมดลงในไฟล์ log
        foreach ($debug_info as $key => $value) {
            $this->log_debug("ข้อมูล {$key}:\n{$value}");
        }
        
        $matched_channels = [];
        
        // วิธีที่ 1: ค้นหาจากข้อมูลดิบของ CoreShowChannels
        $this->log_debug("วิธีที่ 1: ค้นหาจากข้อมูลดิบของ CoreShowChannels");
        $raw_channels = $debug_info['raw_channels'];
        $lines = explode("\r\n", $raw_channels);
        
        $current_channel = null;
        $channel_data = [];
        
        foreach ($lines as $line) {
            // ค้นหาบรรทัดที่มีข้อมูลช่องสัญญาณ
            if (strpos($line, 'Channel: ') === 0) {
                // ถ้ามีข้อมูลช่องสัญญาณก่อนหน้านี้ ให้ตรวจสอบว่าตรงกับเงื่อนไขหรือไม่
                if ($current_channel !== null && !empty($channel_data)) {
                    $this->log_debug("ตรวจสอบช่องสัญญาณ: {$current_channel}");
                    $this->log_debug("ข้อมูลช่องสัญญาณ: " . json_encode($channel_data));
                    
                    // ตรวจสอบว่าช่องสัญญาณกำลังใช้งานอยู่หรือไม่
                    $is_up = isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up';
                    
                    // ตรวจสอบเบอร์ภายใน (CallerIDNum)
                    $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
                    
                    // ตรวจสอบเบอร์ปลายทาง (ConnectedLineNum)
                    $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
                    
                    $this->log_debug("สถานะ: " . ($is_up ? "Up" : "Not Up") . 
                                     ", CallerIDNum Match: " . ($caller_match ? "Yes" : "No") . 
                                     ", ConnectedLineNum Match: " . ($connected_match ? "Yes" : "No"));
                    
                    // ถ้าตรงกับเงื่อนไขทั้งหมด
                    if ($is_up) {
                        if (!empty($extension) && !empty($number)) {
                            // ถ้าระบุทั้งเบอร์ภายในและเบอร์ปลายทาง
                            if ($caller_match && $connected_match) {
                                $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไขทั้งหมด: " . $current_channel);
                                $matched_channels[] = $current_channel;
                            }
                        } else if (!empty($extension) && empty($number)) {
                            // ถ้าระบุเฉพาะเบอร์ภายใน
                            if ($caller_match) {
                                $this->log_debug("พบช่องสัญญาณที่ตรงกับเบอร์ภายใน: " . $current_channel);
                                $matched_channels[] = $current_channel;
                            }
                        } else if (empty($extension) && !empty($number)) {
                            // ถ้าระบุเฉพาะเบอร์ปลายทาง
                            if ($connected_match) {
                                $this->log_debug("พบช่องสัญญาณที่ตรงกับเบอร์ปลายทาง: " . $current_channel);
                                $matched_channels[] = $current_channel;
                            }
                        }
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
        }
        
        // ตรวจสอบช่องสัญญาณสุดท้าย
        if ($current_channel !== null && !empty($channel_data)) {
            $this->log_debug("ตรวจสอบช่องสัญญาณสุดท้าย: {$current_channel}");
            $this->log_debug("ข้อมูลช่องสัญญาณ: " . json_encode($channel_data));
            
            // ตรวจสอบว่าช่องสัญญาณกำลังใช้งานอยู่หรือไม่
            $is_up = isset($channel_data['ChannelStateDesc']) && $channel_data['ChannelStateDesc'] === 'Up';
            
            // ตรวจสอบเบอร์ภายใน (CallerIDNum)
            $caller_match = !empty($extension) && isset($channel_data['CallerIDNum']) && $channel_data['CallerIDNum'] === $extension;
            
            // ตรวจสอบเบอร์ปลายทาง (ConnectedLineNum)
            $connected_match = !empty($number) && isset($channel_data['ConnectedLineNum']) && $channel_data['ConnectedLineNum'] === $number;
            
            $this->log_debug("สถานะ: " . ($is_up ? "Up" : "Not Up") . 
                             ", CallerIDNum Match: " . ($caller_match ? "Yes" : "No") . 
                             ", ConnectedLineNum Match: " . ($connected_match ? "Yes" : "No"));
            
            // ถ้าตรงกับเงื่อนไขทั้งหมด
            if ($is_up) {
                if (!empty($extension) && !empty($number)) {
                    // ถ้าระบุทั้งเบอร์ภายในและเบอร์ปลายทาง
                    if ($caller_match && $connected_match) {
                        $this->log_debug("พบช่องสัญญาณที่ตรงกับเงื่อนไขทั้งหมด: " . $current_channel);
                        $matched_channels[] = $current_channel;
                    }
                } else if (!empty($extension) && empty($number)) {
                    // ถ้าระบุเฉพาะเบอร์ภายใน
                    if ($caller_match) {
                        $this->log_debug("พบช่องสัญญาณที่ตรงกับเบอร์ภายใน: " . $current_channel);
                        $matched_channels[] = $current_channel;
                    }
                } else if (empty($extension) && !empty($number)) {
                    // ถ้าระบุเฉพาะเบอร์ปลายทาง
                    if ($connected_match) {
                        $this->log_debug("พบช่องสัญญาณที่ตรงกับเบอร์ปลายทาง: " . $current_channel);
                        $matched_channels[] = $current_channel;
                    }
                }
            }
        }
        
        // วิธีที่ 2: ค้นหาจากข้อมูลละเอียดของ core show channels verbose
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 2: ค้นหาจากข้อมูลละเอียดของ core show channels verbose");
            $verbose_channels = $debug_info['verbose_channels'];
            $lines = explode("\r\n", $verbose_channels);
            
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    if (preg_match('/^([^\s]+)/', $line, $matches)) {
                        $channel_name = $matches[1];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจากข้อมูลละเอียด: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // วิธีที่ 3: ค้นหาจากข้อมูลกระชับของ core show channels concise
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 3: ค้นหาจากข้อมูลกระชับของ core show channels concise");
            $concise_channels = $debug_info['concise_channels'];
            $lines = explode("\r\n", $concise_channels);
            
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    $parts = explode('!', $line);
                    if (!empty($parts[0])) {
                        $channel_name = $parts[0];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจากข้อมูลกระชับ: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // วิธีที่ 4: ค้นหาจากข้อมูล SIP channels
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 4: ค้นหาจากข้อมูล SIP channels");
            $sip_channels = $debug_info['sip_channels'];
            $lines = explode("\r\n", $sip_channels);
            
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    if (preg_match('/^([^\s]+)/', $line, $matches)) {
                        $channel_name = $matches[1];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจากข้อมูล SIP: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // วิธีที่ 5: ค้นหาจากข้อมูล PJSIP channels
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 5: ค้นหาจากข้อมูล PJSIP channels");
            $pjsip_channels = $debug_info['pjsip_channels'];
            $lines = explode("\r\n", $pjsip_channels);
            
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    if (preg_match('/^([^\s]+)/', $line, $matches)) {
                        $channel_name = $matches[1];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจากข้อมูล PJSIP: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // วิธีที่ 6: ค้นหาจากข้อมูล calls
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 6: ค้นหาจากข้อมูล calls");
            $calls = $debug_info['calls'];
            $lines = explode("\r\n", $calls);
            
            foreach ($lines as $line) {
                // ตรวจสอบเบอร์ภายในและเบอร์ปลายทาง
                if ((!empty($extension) && strpos($line, $extension) !== false) && 
                    (!empty($number) && strpos($line, $number) !== false)) {
                    // ดึงชื่อช่องสัญญาณจากบรรทัด
                    if (preg_match('/^([^\s]+)/', $line, $matches)) {
                        $channel_name = $matches[1];
                        if (!in_array($channel_name, $matched_channels)) {
                            $this->log_debug("พบช่องสัญญาณที่เกี่ยวข้องจากข้อมูล calls: " . $channel_name);
                            $matched_channels[] = $channel_name;
                        }
                    }
                }
            }
        }
        
        // วิธีที่ 7: ค้นหาจากชื่อช่องสัญญาณที่มีเบอร์ภายใน
        if (empty($matched_channels) && !empty($extension)) {
            $this->log_debug("วิธีที่ 7: ค้นหาจากชื่อช่องสัญญาณที่มีเบอร์ภายใน");
            
            // ดึงช่องสัญญาณทั้งหมด
            $all_channels = [];
            $raw_channels = $debug_info['raw_channels'];
            $lines = explode("\r\n", $raw_channels);
            
            foreach ($lines as $line) {
                if (strpos($line, 'Channel: ') === 0) {
                    $channel_name = substr($line, 9);
                    $all_channels[] = $channel_name;
                }
            }
            
            // ค้นหาช่องสัญญาณที่มีเบอร์ภายใน
            foreach ($all_channels as $channel_name) {
                if (strpos($channel_name, "SIP/{$extension}-") === 0 || 
                    strpos($channel_name, "PJSIP/{$extension}-") === 0 || 
                    strpos($channel_name, "Local/{$extension}@") === 0) {
                    $this->log_debug("พบช่องสัญญาณที่มีเบอร์ภายใน {$extension} ในชื่อ: " . $channel_name);
                    $matched_channels[] = $channel_name;
                }
            }
        }
        
        // วิธีที่ 8: ถ้ายังไม่พบช่องสัญญาณ ให้ลองตรวจสอบทุกช่องสัญญาณที่มีสถานะ Up
        if (empty($matched_channels)) {
            $this->log_debug("วิธีที่ 8: ลองตรวจสอบทุกช่องสัญญาณที่มีสถานะ Up");
            
            $raw_channels = $debug_info['raw_channels'];
            $lines = explode("\r\n", $raw_channels);
            
            $current_channel = null;
            $is_up = false;
            
            foreach ($lines as $line) {
                if (strpos($line, 'Channel: ') === 0) {
                    // บันทึกช่องสัญญาณก่อนหน้านี้ถ้ามีสถานะ Up
                    if ($current_channel !== null && $is_up) {
                        $this->log_debug("เพิ่มช่องสัญญาณที่มีสถานะ Up: " . $current_channel);
                        $matched_channels[] = $current_channel;
                    }
                    
                    $current_channel = substr($line, 9);
                    $is_up = false;
                } 
                elseif (strpos($line, 'ChannelStateDesc: Up') === 0) {
                    $is_up = true;
                }
            }
            
            // ตรวจสอบช่องสัญญาณสุดท้าย
            if ($current_channel !== null && $is_up) {
                $this->log_debug("เพิ่มช่องสัญญาณที่มีสถานะ Up: " . $current_channel);
                $matched_channels[] = $current_channel;
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
        
        // ใช้ find_channels_by_number เพื่อค้นหาช่องสัญญาณที่เกี่ยวข้อง
        $channels = $this->find_channels_by_number($extension, $number);
        $results = [];
        
        if (empty($channels)) {
            $this->log_debug("ไม่พบช่องสัญญาณที่เกี่ยวข้อง");
            return ["error" => "ไม่พบช่องสัญญาณที่เกี่ยวข้อง"];
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