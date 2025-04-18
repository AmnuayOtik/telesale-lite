<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserInfo extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        date_default_timezone_set('Asia/Bangkok');
    }

    public function index(){

        $menu = ['main'=>'userinfo','sub'=>'userinfo'];
        $this->session->set_userdata('menu',$menu);

        $data['user'] = $this->Users_model->get_users_by_id($this->session->userdata('user_id'));
        
        $data['header_content'] = ['title'=>'ข้อมูลผู้ใช้','right_menu'=>'ข้อมูลผู้ใช้'];
        $data['content'] = "userinfo/userinfo_view";
        $this->load->view('template/main_layout_view', $data);
    }

    public function FcSaveUser(){

        header('Content-Type: application/json');
        

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $business_phone = $this->input->post('business_phone');
        $full_name = $this->input->post('full_name');
        $email = $this->input->post('email');
        $mobile_phone = $this->input->post('mobile_phone');

        if (empty($user_id) || empty($username) || empty($full_name)) {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'ข้อมูลไม่ถูกต้อง']));
        }

        $rData =[
            'user_id' => $user_id,
            'username' => $username,
            'business_phone' => $business_phone,
            'full_name' => $full_name,
            'email' => $email,
            'mobile_phone' => $mobile_phone,
            'who_update' => $this->session->userdata('user_id'),
            'date_update' => date('Y-m-d H:i:s'),
        ];

        $rResult = $this->Users_model->update_user($user_id,$rData);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

    public function FcChangePassword(){

        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        // รับค่าจาก AJAX
        $current_password = $this->input->post('current_password');
        $new_password     = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');

        // user_id หรือ session ที่ login อยู่
        $user_id = $this->session->userdata('user_id'); // ปรับให้ตรงกับ session ของคุณ

        // ตรวจสอบข้อมูลจาก Model
        $user = $this->Users_model->get_users_by_id($user_id);

        if (!$user) {
            echo json_encode(['rCode' => 404, 'rMsg' => 'ไม่พบข้อมูลผู้ใช้งาน']);
            return;
        }

        // เปรียบเทียบรหัสผ่านเดิม (สมมติว่าเก็บแบบเข้ารหัส bcrypt)
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['rCode' => 401, 'rMsg' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
            return;
        }

        // ยืนยันรหัสผ่านใหม่
        if ($new_password !== $confirm_password) {
            echo json_encode(['rCode' => 400, 'rMsg' => 'รหัสผ่านใหม่ไม่ตรงกัน']);
            return;
        }

        // อัปเดตรหัสผ่าน
        $update = $this->Users_model->update_password($user_id, $new_password);

        if ($update) {
            echo json_encode(['rCode' => 200, 'rMsg' => 'เปลี่ยนรหัสผ่านสำเร็จ']);
        } else {
            echo json_encode(['rCode' => 500, 'rMsg' => 'ไม่สามารถเปลี่ยนรหัสผ่านได้']);
        }
    }

}