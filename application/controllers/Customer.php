<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Customer_model');
    }

    public function index()
	{

        $this->session->set_userdata('menu_active', 'customer');

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'ข้อมูลลูกค้า','right_menu'=>'ลูกค้า'];
        $data['content'] = "customer/customer_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcFetchCustomerModal(){
        
        $customer_id = $this->input->post('customer_id');

        if(!empty($customer_id)){
            $data['customer'] = $this->Customer_model->get_customer_by_id($customer_id);
        }else{
            $data['customer'] = [];
        }

        $data['mode'] = $this->input->post('mode');
        
        $this->load->view('customer/customer_form_view',$data);        
    }

    public function FcCsvImportCustomerModal(){        
        $data['csv'] = [];
        $this->load->view('customer/customer_import_view',$data);        

    }

    public function Upload_csv(){

        // กำหนดค่าการอัปโหลดไฟล์
        $config['upload_path']   = './assets/upload/'; // โฟลเดอร์ที่ใช้เก็บไฟล์ CSV
        $config['allowed_types'] = 'csv'; // อนุญาตให้อัปโหลดเฉพาะไฟล์ .csv เท่านั้น
        $config['max_size']      = 2048; // กำหนดขนาดไฟล์สูงสุด 2MB (2048 KB)

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
        $expected_columns = 8; // คอลัมน์ที่คาดหวัง

        $errors = [];
        $totalRows = 0;
        $valid_rows = 0;

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
                    'full_name' => $row[0],
                    'phone_number'     => $row[1],
                    'email'   => $row[2],
                    'address'     => $row[3],
                    'city'      => $row[4],
                    'state'     => $row[5],
                    'zip_code'  => $row[6],
                    'country'  => $row[7]
                ];

                $this->Customer_model->insert_data($data);
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
        $full_name = $this->input->post('full_name');
        $phone_number = $this->input->post('phone_number');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $city = $this->input->post('city');
        $state = $this->input->post('state');
        $country = $this->input->post('country');
        $zip_code = $this->input->post('zip_code');
        $status = $this->input->post('status');

        $Data = [
            'customer_id'=> $customer_id,
            'full_name'=> $full_name,
            'phone_number'=> $phone_number,
            'email'=> $email,
            'address'=> $address,
            'city'=> $city,
            'state'=> $state,
            'country'=> $country,
            'zip_code'=> $zip_code,
            'status'=> $status
        ];

        if($mode == 'new'){
            $rResult = $this->Customer_model->add_customer($Data);
        }else{
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