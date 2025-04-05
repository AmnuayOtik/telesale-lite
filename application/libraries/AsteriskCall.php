<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AsteriskCall{

    private $ami_host = "192.168.98.150";    
    private $port = 5038;
    private $username = "otikadmin2025";    
    private $password = "P@ssw0rd##";
    private $context = "from-internal";
    private $socket;

    public function __construct($config = []) {
        if (!empty($config)) {
            $this->initialize($config);
        }
    }

    public function initialize($config = []) {
        foreach ($config as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }

    // ฟังก์ชันสำหรับการเชื่อมต่อไปยัง AMI
    private function connect() {
        $this->socket = @stream_socket_client("tcp://{$this->ami_host}:{$this->port}");

        if (!$this->socket) {
            return ['status' => false, 'message' => "Unable to connect to socket."];
        }

        // เตรียมคำขอ authentication
        $authenticationRequest = "Action: Login\r\n";
        $authenticationRequest .= "Username: {$this->username}\r\n";
        $authenticationRequest .= "Secret: {$this->password}\r\n";
        $authenticationRequest .= "Events: off\r\n\r\n";

        // ส่งคำขอ authentication
        $authenticate = stream_socket_sendto($this->socket, $authenticationRequest);
        
        if ($authenticate <= 0) {
            return ['status' => false, 'message' => "Could not write authentication request to socket."];
        }

        // รอการตอบกลับจาก AMI
        usleep(200000);
        $authenticateResponse = fread($this->socket, 4096);

        // ตรวจสอบว่า authentication สำเร็จหรือไม่
        if (strpos($authenticateResponse, 'Success') === false) {
            return ['status' => false, 'message' => "Authentication failed."];
        }

        return ['status' => true];
    }

    // ฟังก์ชันสำหรับการโทรออก
    public function makeCall($internalPhoneline = null, $target = null) {
        $connectResult = $this->connect();
        if (!$connectResult['status']) {
            return $connectResult; // คืนค่าผลลัพธ์การเชื่อมต่อ
        }

        // เตรียมคำขอ originate
        $originateRequest = "Action: Originate\r\n";
        $originateRequest .= "Channel: PJSIP/{$internalPhoneline}\r\n";
        $originateRequest .= "Callerid: Privacy Number\r\n";
        $originateRequest .= "Exten: {$target}\r\n";
        $originateRequest .= "Context: {$this->context}\r\n";
        $originateRequest .= "variable: PJSIP_HEADER(add,Alert-Info)=\;answer-after=0\r\n";            
        $originateRequest .= "Priority: 1\r\n";
        $originateRequest .= "Async: yes\r\n\r\n";

        // ส่งคำขอ originate
        $originate = stream_socket_sendto($this->socket, $originateRequest);
        if ($originate <= 0) {
            return ['status' => false, 'message' => "Could not write call initiation request to socket."];
        }

        // รอการตอบกลับจาก AMI
        usleep(200000);
        $originateResponse = fread($this->socket, 4096);

        // ตรวจสอบว่า originate สำเร็จหรือไม่
        if (strpos($originateResponse, 'Success') !== false) {
            // ดึง Channel ID จากการตอบกลับ
            $channelId = $this->getChannelIdFromResponse($originateResponse);
            return [
                'status' => true,
                'message' => "Call initiated successfully.",
                'channel_id' => $channelId
            ];
        } else {
            return ['status' => false, 'message' => "Could not initiate call."];
        }
    }

    // ฟังก์ชันสำหรับดึง Channel ID จากการตอบกลับ
    private function getChannelIdFromResponse($response) {
        if (preg_match('/Channel: (\S+)/', $response, $matches)) {
            return $matches[1]; // คืนค่าช่องทาง (Channel ID)
        }
        return null; // ถ้าไม่มี Channel ID
    }

    // ปิดการเชื่อมต่อ
    public function closeConnection() {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
}
