<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CallInform extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('CallInform_model');
        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
	{

        $menu = ['main'=>'manage','sub'=>'callinform'];
        $this->session->set_userdata('menu',$menu);

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'กำหนดแจ้งผลการโทร','right_menu'=>'แจ้งผลการโทร'];
        $data['content'] = "callinform/callinform_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcFetchCallInformModal(){
        
        $id = $this->input->post('id');

        if(!empty($id)){
            $data['callinform'] = $this->CallInform_model->get_CallInform_by_id($id);
        }else{
            $data['callinform'] = [];
        }

        $data['mode'] = $this->input->post('mode');             
        
        $this->load->view('callinform/callinform_form_view',$data);        
    }

    
    public function FcCallInformTables(){

        $data['callinform'] = $this->CallInform_model->get_all_callinform();
        $this->load->view('callinform/callinform_table_view', $data);

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

        $id = $this->input->post('id');
        $name_th = $this->input->post('name_th');

        $current_datetime = date('Y-m-d H:i:s');

        if($mode == 'new'){
            $id = null;
        }else{
            $id = $id;
        }

        $Data = [
            'id'=> $id,
            'name_th'=> $name_th,        
            'who_create' => $this->session->userdata('user_id'),
            'date_create' => $current_datetime,
            'who_update' => $this->session->userdata('user_id'),
            'date_update' => $current_datetime
        ];

        if($mode == 'new'){
            $rResult = $this->CallInform_model->add_callinform($Data);
        }else{
            // ลบฟิลด์ who_create และ date_create ออกเนื่องจากเป็นการอัปเดทข้อมูล
            unset($Data['who_create'], $Data['date_create']);
            $rResult = $this->CallInform_model->update_callinform($id,$Data);
        }
        
        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

    public function FcDelCallInform(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $id = $this->input->post('id');
        
        if(empty($id)){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Customer id  Not Allow empty.']));            
        }
        
        $rResult = $this->CallInform_model->delete_callinform($id);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();
        
    }

}