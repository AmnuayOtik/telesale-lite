<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        date_default_timezone_set('Asia/Bangkok');
    }


    public function index()
	{	

        $this->session->set_userdata('menu_active', 'users');

        $data['contents'] = [];
        $data['users'] = $this->Users_model->get_all_Users();
        $data['header_content'] = ['title'=>'ผู้ใช้งานในระบบ','right_menu'=>'Users'];
        $data['content'] = "users/users_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcUsersTables(){
        $data['users'] = $this->Users_model->get_all_Users();
        $this->load->view('users/users_table_view', $data);
    }

    public function  FcFetchUsersModal(){
    
        $user_id = $this->input->post('user_id');

        if(!empty($user_id)){
            $data['user'] = $this->Users_model->get_users_by_id($user_id);
        }else{
            $data['user'] = [];
        }

        $data['mode'] = $this->input->post('mode');
        
        $this->load->view('users/users_form_view',$data);

    }

    public function FcSavePasswordChange(){
        
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $user_id = $this->input->post('user_id');        
        $newPassword = $this->input->post('newPassword');

        if(empty($user_id) && empty($newPassword)){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'ข้อมูลไม่ถูกต้อง']));            
        }

        $rResult = $this->Users_model->update_password($user_id,$newPassword);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

    public function FcDelUsersByUID(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $user_id = $this->input->post('user_id');

        if(empty($user_id)){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'ข้อมูลไม่ถูกต้อง']));            
        }

        $rResult = $this->Users_model->delete_user($user_id);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();
        

    }

    public function FcSaveOrEdit(){
        
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $mode = $this->input->post('mode');

        if($mode != 'new' && $mode != 'edit'){            
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Mode Not Allowed.']));            
        }        

        $user_id = $this->input->post('user_id');
        $full_name = $this->input->post('full_name');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $mobile_phone = $this->input->post('mobile_phone');
        $business_phone = $this->input->post('business_phone');
        $email = $this->input->post('email');
        $user_type = $this->input->post('user_type');
        $cstatus = $this->input->post('cstatus');

        if($mode == 'new'){

            $user_id = $this->Users_model->GetNextUserId();
            $username_check = $this->Users_model->GetExistingUsername($username);

            if($username_check){
                exit(json_encode(['rCode' => 200, 'rMsg' => 'ExistingUser' , 'rData' => 'ชื่อผู้ใช้นี้ ('.$username.') มีอยู่แล้วในระบบ กรุณาเปลี่ยนใหม่'])); 
            }

        }else{
            $user_id = $user_id;
        }

        $current_datetime = date('Y-m-d H:i:s');
    
        $Data = [
            'user_id'=> $user_id,
            'full_name'=> $full_name,
            'username'=> $username,
            'password'=> password_hash($password, PASSWORD_DEFAULT),
            'mobile_phone'=> $mobile_phone,
            'email'=> $email,
            'business_phone'=> $business_phone,
            'user_type'=> $user_type,
            'cstatus'=> $cstatus,
            'who_create'=>$this->session->userdata('user_id'),
            'date_create' => $current_datetime,
            'who_update' => $this->session->userdata('user_id'),
            'date_update' => $current_datetime,
        ];

        if($mode == 'new'){
            $rResult = $this->Users_model->add_user($Data);
        }else{
            // ลบฟิลด์ who_create และ date_create ออกเนื่องจากเป็นการอัปเดทข้อมูล
            unset($Data['who_create'], $Data['date_create'],$Data['password']);
            $rResult = $this->Users_model->update_user($user_id,$Data);
        }
        
        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

}