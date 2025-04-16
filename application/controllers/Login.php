<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();        
        $this->load->model('Login_model'); // โหลด Model
        $this->load->library('session'); // โหลด Session
        $this->load->helper(['url', 'security']); // โหลด Helper สำหรับความปลอดภัย
    }

    public function index()
    {
        if($this->session->userdata('user_id')){
            redirect('Dashboard');
        }
        
        $this->load->view('login/login_view.php');

    }

    public function FcHashPassword(){
        // สร้างรหัสผ่านที่ถูก Hash
        $password = "pintong";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        echo $hashed_password;
    }

    public function FcCheckLogin() {
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }
    
        $username = $this->input->post('username', TRUE); // XSS Clean
        $password = $this->input->post('password', TRUE); // แก้ไขพิมพ์ผิด
    
        // รับ IP Address ของผู้ใช้
        $ip_address = $this->input->ip_address();
    
        // ตรวจสอบจำนวนครั้งที่ล็อกอินผิดพลาด
        $login_attempts = $this->Login_model->get_login_attempts($ip_address);
    
        if ($login_attempts >= 5) { // ถ้าเกิน 5 ครั้งให้บล็อก
            exit(json_encode(['rCode' => 429, 'rMsg' => 'Too many login attempts. Please try again later.']));
        }
    
        // ตรวจสอบ username และ password
        $mResult = $this->Login_model->check_login($username, $password);
    
        if ($mResult) {
            // ล้างค่า login_attempts เมื่อเข้าสู่ระบบสำเร็จ
            $this->Login_model->reset_login_attempts($ip_address);
    
            // ตั้งค่า session
            $this->session->set_userdata('user_id', $mResult->user_id);
            $this->session->set_userdata('username', $mResult->username);
            $this->session->set_userdata('full_name', $mResult->full_name);
            $this->session->set_userdata('pbx_exten', $mResult->business_phone);

            if($mResult->user_type == '1'){
                $this->session->set_userdata('is_admin', true);
            }else{
                $this->session->set_userdata('is_admin', false);
            }
    
            $rData = [
                'rCode' => 200,
                'rMsg' => 'Success',
                'rData' => []
            ];
        } else {
            // เพิ่มจำนวน login_attempts
            $this->Login_model->increase_login_attempts($ip_address);
    
            $rData = [
                'rCode' => 401,
                'rMsg' => 'Invalid username or password',
                'rData' => []
            ];
        }
    
        echo json_encode($rData);
        exit;
    }

    public function FcLogout() {

        // ทำลาย Session ของ CodeIgniter
        $this->session->sess_destroy();
    
        // ล้าง Cookies ที่เกี่ยวข้อง
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    
        // หากมีการใช้ native session ด้วย ให้จัดการเพิ่ม
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    
        // เปลี่ยนเส้นทางหลัง logout
        redirect('Login'); // หรือ route ที่ต้องการ
        exit;
    }
    
    

}
