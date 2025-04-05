<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Followups extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('FollowUp_model');
        // Set default Timezone to Bangkok
        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
	{	

        if (empty($_REQUEST['cid'])) exit();

        $this->session->set_userdata('menu_active', 'followups');

        $data['contents'] = [];
        $data['customer'] = $this->Customer_model->get_customer_by_id($_REQUEST['cid']);
        $data['header_content'] = ['title'=>'โทรติดต่อลูกค้า','right_menu'=>'Follow Up'];
        $data['content'] = "followups/followups_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcSaveOrder() {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Not allow method']));
        }

        // รับข้อมูลจาก AJAX
        $order_header = $this->input->post('order_header');
        $order_detail = $this->input->post('order_detail');
        $mode = $this->input->post('mode');

        if (empty($order_header) || empty($order_detail) || empty($mode)) {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Empty data.']));
        }

        // Create a DateTime object for March 4, 2025
        $current_datetime = date('Y-m-d H:i:s');

        // ทำการตรวจสอบรหัสเอกสารต่อไป (ตรวจสอบจาก Mac order_id + 1 = NextOrderId)
        if($mode == 'new'){
            $order_id = $this->FollowUp_model->GetNextOrderId();
        }else{
            $order_id = $order_header['order_id'];
        }
        
        // เพิ่ม order_id เข้าไปใน order_header
        $order_header['order_id'] = $order_id;
    
        if($mode == 'new'){
            $insert_order_header = $this->FollowUp_model->insert_order_header($order_header);
        }else{
            $insert_order_header = $this->FollowUp_model->update_order_header($order_header);
        }
        
        $delete_result = $this->FollowUp_model->delete_order_detail($order_id);

        $i=1;
        foreach ($order_detail as $product) {
            $rData_detail = [
                'order_id'     => $order_id,                
                'seq_number'   => $i++, 
                'product_id'   => $product['product_id'],
                'product_name' => $product['product_name'],
                'price'        => $product['price'],
                'quantity'     => $product['quantity'],
                'discount'     => $product['discount'],
                'total'        => $product['total'],
                'who_create'   => $this->session->userdata('user_id'),
                'date_create'  => $current_datetime,
                'who_update'   => $this->session->userdata('user_id'),
                'last_update'  => $current_datetime
            ];
                
            // บันทึกข้อมูลสินค้า
            $this->FollowUp_model->insert_order_detail($rData_detail);
        }

        $rData = ['rCode' => 200, 'rMsg' => 'Success', 'rData' => ['order_header'=>$order_header, 'order_detail' => $order_detail]];
        echo json_encode($rData);
            
    }

}