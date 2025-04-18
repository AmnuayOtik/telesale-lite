<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CallResult extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('CallResult_model');
        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
	{

        $menu = ['main'=>'manage','sub'=>'callresult'];
        $this->session->set_userdata('menu',$menu);

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'กำหนดผลการโทร','right_menu'=>'ผลการโทร'];
        $data['content'] = "callresult/callresult_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcFetchCallResultModal(){
        
        $id = $this->input->post('id');

        if(!empty($id)){
            $data['callresult'] = $this->CallResult_model->get_CallResult_by_id($id);
        }else{
            $data['callresult'] = [];
        }

        $data['mode'] = $this->input->post('mode');             
        
        $this->load->view('callresult/callresult_form_view',$data);        
    }

    
    public function FcCallResultTables(){

        $data['callresult'] = $this->CallResult_model->get_all_callresult();
        $this->load->view('callresult/callresult_table_view', $data);

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
            $rResult = $this->CallResult_model->add_callresult($Data);
        }else{
            // ลบฟิลด์ who_create และ date_create ออกเนื่องจากเป็นการอัปเดทข้อมูล
            unset($Data['who_create'], $Data['date_create']);
            $rResult = $this->CallResult_model->update_callresult($id,$Data);
        }
        
        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

    public function FcDelCallResult(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $id = $this->input->post('id');
        
        if(empty($id)){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Customer id  Not Allow empty.']));            
        }
        
        $rResult = $this->CallResult_model->delete_callresult($id);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();
        
    }

}