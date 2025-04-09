<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->model('Customer_model');
        date_default_timezone_set('Asia/Bangkok');

    }

    public function index()
	{

        $this->session->set_userdata('menu_active', 'customer');

        if(empty($this->session->userdata('date_filter'))){
            $this->session->set_userdata('date_filter','today');
        }

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'ข้อมูลลูกค้า','right_menu'=>'ลูกค้า'];
        $data['content'] = "customer/customer_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function Search(){

        $filter = $this->input->post('date_filter');
        $filter_from_date = $this->input->post('from_date');
        $filter_to_date = $this->input->post('to_date');

        $this->session->set_userdata('date_filter',$filter);
        $this->session->set_userdata('from_date',$filter_from_date);
        $this->session->set_userdata('to_date',$filter_to_date);

        echo json_encode(['date_filter'=> $filter]);
        exit();

    }

    public function FcFetchSearchModal(){
        $this->load->view('customer/customer_search_view');
    }

    public function FcFetchCustomerModal(){
        
        $customer_id = $this->input->post('customer_id');

        if(!empty($customer_id)){
            $data['customer'] = $this->Customer_model->get_customer_by_id($customer_id);
        }else{
            $data['customer'] = [];
        }

        $data['mode'] = $this->input->post('mode');
        $data['users'] = $this->Users_model->get_all_Users();        
        
        $this->load->view('customer/customer_form_view',$data);        
    }

    public function FcCsvImportCustomerModal(){        
        $data['users'] = $this->Users_model->get_all_Users();
        $this->load->view('customer/customer_import_view',$data);        

    }

    public function Upload_csv(){        

        $user_id = $this->input->post('user_id');

        // ตรวจสอบว่า user_id ต้องไม่ว่าง
        if (empty($user_id)) {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'User id must not be empty.']));
        }

        // เก็บ user_id ไว้ใน session สำหรับนำไปใช้ตอน import
        $this->session->set_userdata('user_id_for_import', $user_id);

        // กำหนดค่าการอัปโหลดไฟล์
        $config['upload_path']   = './assets/upload/'; // โฟลเดอร์ที่ใช้เก็บไฟล์ CSV
        $config['allowed_types'] = 'csv'; // อนุญาตให้อัปโหลดเฉพาะไฟล์ .csv เท่านั้น
        $config['masize']      = 2048; // กำหนดขนาดไฟล์สูงสุด 2MB (2048 KB)

        // โหลดไลบรารีสำหรับอัปโหลดไฟล์
        $this->load->library('upload', $config);

        header('Content-Type: application/json');
        
        // ตรวจสอบว่าการอัปโหลดไฟล์สำเร็จหรือไม่
        if (!$this->upload->do_upload('csv_file')) {
            // ถ้าอัปโหลดไม่สำเร็จ ส่ง error กลับไป
            echo json_encode(['error' => $this->upload->display_errors()]);
        } else {
            // ดึงข้อมูลไฟล์ที่อัปโหลด
            $fileData = $this->upload->data();

            // ตรวจสอบว่านามสกุลไฟล์ที่อัปโหลดเป็น .csv จริงหรือไม่
            if (strtolower($fileData['file_ext']) !== '.csv') {
                unlink($fileData['full_path']); // ถ้าไม่ใช่ .csv ให้ลบไฟล์ออก
                echo json_encode(['error' => 'อัปโหลดเฉพาะไฟล์ .csv เท่านั้น']);
                return;
            }

            // ส่งค่า URL ของไฟล์ที่อัปโหลดกลับไปให้ Frontend
            echo json_encode(['file_path' => $fileData['file_name']]);
        }

    }

    public function Import_csv(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Not allow method']));
        }

        $csv_file_name = $this->input->post('file_name');

        if (empty($csv_file_name)) {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Not allow empty file.']));
        }
        
        $file_path = './assets/upload/'.$csv_file_name;

        $file = fopen($file_path, "r");
        $header = fgetcsv($file); // อ่านหัวข้อ
        $expected_columns = 6; // คอลัมน์ที่คาดหวัง

        $errors = [];
        $totalRows = 0;
        $valid_rows = 0;

        $current_datetime = date('Y-m-d H:i:s');

        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $totalRows++;

            // ตรวจสอบจำนวนคอลัมน์
            if (count($row) != $expected_columns) {
                $errors[] = "บรรทัดที่ $totalRows: จำนวนคอลัมน์ไม่ถูกต้อง";
                continue;
            }

            // ตรวจสอบค่าว่าง
            foreach ($row as $index => $column) {
                if (empty(trim($column))) {
                    $errors[] = "บรรทัดที่ $totalRows: คอลัมน์ที่ " . ($index + 1) . " ว่าง";
                    break;
                }
            }

            // ตรวจสอบว่าไม่มี Enter หรืออักขระพิเศษ
            foreach ($row as $column) {
                if (preg_match('/[\r\n\t]/', $column)) {
                    $errors[] = "บรรทัดที่ $totalRows: พบอักขระพิเศษในข้อมูล";
                    break;
                }
            }

            if (empty($errors)) {
                // ถ้าไม่มีข้อผิดพลาด ให้นำเข้าข้อมูล
                $data = [
                    'customer_id'       => $this->Customer_model->GetNextCustomerId(),
                    'ref_user_id'       => $row[0],
                    'full_name'         => $row[1],
                    'phone_number'      => $row[2],
                    'line_account'      => $row[3],
                    'missed_deposit'    => $row[4],
                    'last_activity'     => $row[5],
                    'cstatus'           => 'Waiting',
                    'user_id'           => $this->session->userdata('user_id_for_import'),
                    'who_create'        => $this->session->userdata('user_id'),
                    'date_create'       => $current_datetime,
                    'who_update'        => $this->session->userdata('user_id'),
                    'date_update'       => $current_datetime
                ];

                $this->Customer_model->add_customer($data);
                $valid_rows++;
            }
        }

        fclose($file);

        // แจ้งผลลัพธ์
        if (empty($errors)) {
            //echo "<p>นำเข้าข้อมูลทั้งหมด $valid_rows รายการจาก $totalRows รายการ.</p>";
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>'<p style="color:green;">นำเข้าข้อมูลทั้งหมด '.$valid_rows.' รายการจาก '.$totalRows.' รายการ.</p>'];
            echo json_encode($rData);
        } else {
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>'<p style="color:red;">เกิดข้อผิดพลาดในการนำเข้าข้อมูล.</p>'];
            /*
            echo "<p>พบข้อผิดพลาด:</p><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            */
            echo json_encode($rData);
        }
    }

    public function FcCustomerTables(){

        $data['customers'] = $this->Customer_model->get_all_customers();
        $this->load->view('customer/customer_table_view', $data);

    }

    public function FcGetTodayStatByUserID(){

        header('Content-Type: application/json'); 

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $user_id = $this->session->userdata('user_id');

        $rResult = $this->Customer_model->get_cstatus_today($user_id);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>$rResult ];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=> ''];
        }
                
        echo json_encode($rData); 

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

        $customer_id = $this->input->post('customer_id');
        $ref_user_id = $this->input->post('ref_user_id');
        $full_name = $this->input->post('full_name');
        $phone_number = $this->input->post('phone_number');
        $line_account = $this->input->post('line_account');
        $missed_deposit = $this->input->post('missed_deposit');        
        $last_activity = $this->input->post('last_activity');
        $cstatus = $this->input->post('cstatus');

        $user_id = $this->input->post('user_id');

        $current_datetime = date('Y-m-d H:i:s');

        if($mode == 'new'){
            $customer_id = $this->Customer_model->GetNextCustomerId();
        }else{
            $customer_id = $customer_id;
        }

        if(empty($user_id)){
            $user_id = $this->session->userdata('user_id');
        }else{
            $user_id = $this->input->post('user_id');
        }

        $Data = [
            'customer_id'=> $customer_id,
            'ref_user_id'=> $ref_user_id,
            'full_name'=> $full_name,
            'phone_number'=> $phone_number,
            'line_account'=> $line_account,
            'missed_deposit'=> $missed_deposit,            
            'last_activity'=> $last_activity,
            'cstatus'=> $cstatus,
            'user_id' => $user_id,            
            'who_create' => $this->session->userdata('user_id'),
            'date_create' => $current_datetime,
            'who_update' => $this->session->userdata('user_id'),
            'date_update' => $current_datetime
        ];

        if($mode == 'edit' && $this->session->userdata('is_admin') != true){
            unset($Data['phone_number']);
        }

        if($mode == 'new'){
            $rResult = $this->Customer_model->add_customer($Data);
        }else{
            // ลบฟิลด์ who_create และ date_create ออกเนื่องจากเป็นการอัปเดทข้อมูล
            unset($Data['who_create'], $Data['date_create']);
            $rResult = $this->Customer_model->update_customer($customer_id,$Data);
        }
        
        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();

    }

    public function FcDelCustomer(){

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        $customer_id = $this->input->post('customer_id');
        
        if(empty($customer_id)){
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Customer id  Not Allow empty.']));            
        }
        
        $rResult = $this->Customer_model->delete_customer($customer_id);

        if($rResult){
            $rData = ['rCode'=> 200,'rMsg'=>'Success','rData'=>''];
        }else{
            $rData = ['rCode'=> 500,'rMsg'=>'Error','rData'=>''];
        }

        echo json_encode($rData);
        exit();
        
    }

    public function FcDeleteSelectedCustomers() {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Method Not Allowed']));
        }

        // รับข้อมูล customer_ids ที่ถูกส่งมาจาก AJAX
        $customer_ids = $this->input->post('customer_ids');

        if (empty($customer_ids)) {
            exit(json_encode(['rCode' => 400, 'rMsg' => 'ไม่มีข้อมูลลูกค้าที่จะลบ.']));
        }

        $result = $this->Customer_model->delete_bulk_customer($customer_ids);
    
        if ($result) {
            echo json_encode(['rCode' => 200, 'rMsg' => 'Success', 'rData' => 'ลบข้อมูลลูกค้าเรียบร้อย']);
        } else {
            echo json_encode(['rCode' => 500, 'rMsg' => 'Error', 'rData' => 'เกิดข้อผิดพลาดในการลบข้อมูลลูกค้า']);
        }
    }

}
