<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Followups extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('FollowUp_model');
        // Set default Timezone to Bangkok
        date_default_timezone_set('Asia/Bangkok');

        // โหลด config asterisk
        $this->config->load('asterisk');
        // ดึงค่าคอนฟิกมาใช้
        $ami_config = $this->config->item('asterisk');
        // โหลด library โดยใช้ค่าจาก config
        $this->load->library('asterisk_ami', $ami_config);

    }

    public function index()
	{	

        if (empty($_REQUEST['cid'])) exit();

        $menu = ['main'=>'customer','sub'=>'customer'];
        $this->session->set_userdata('menu',$menu);

        $data['contents'] = [];
        $data['customer'] = $this->Customer_model->get_customer_by_id($_REQUEST['cid']);
        $data['call_result_master'] = $this->Customer_model->get_all_call_result_master();
        $data['call_inform_master'] = $this->Customer_model->get_all_call_inform_master();
        $data['header_content'] = ['title'=>'โทรติดต่อลูกค้า','right_menu'=>'Follow Up'];
        $data['content'] = "followups/followups_view";
        $this->load->view('template/main_layout_view', $data);

	}
    
    public function FcSaveOrEdit() {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Not allow method']));
        }

        // รับข้อมูลจาก AJAX
        $customer_id = $this->input->post('customer_id');
        $call_result = $this->input->post('call_result');
        $cstatus = $this->input->post('cstatus');
        $call_result_note = $this->input->post('call_result_note');
        $notified_via_line = $this->input->post('line_account');
        $line_account_note = $this->input->post('line_account_note');
        
        if (empty($customer_id) || empty($call_result) || empty($cstatus)) {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Empty data.']));
        }

        // Create a DateTime object for March 4, 2025
        $current_datetime = date('Y-m-d H:i:s');

        $rData = [
            'customer_id' => $customer_id,
            'call_result' => $call_result,
            'cstatus' => $cstatus,
            'call_result_note' => $call_result_note,
            'notified_via_line' => $notified_via_line,
            'line_account_note' => $line_account_note,
            'who_update' => $this->session->userdata('user_id'),
            'date_update' => $current_datetime
        ];
    
        $rResult = $this->FollowUp_model->update($rData);
        
        if($rResult){
            $rData = ['rCode' => 200, 'rMsg' => 'Success', 'rData' => '']; 
        }else{
            $rData = ['rCode' => 400, 'rMsg' => 'Error', 'rData' => ''];
        }        
        
        echo json_encode($rData);
            
    }

    /***************************************************************
    * ฟังก์ชันสำหรับโทรออก
    /***************************************************************/
    public function Dial() {

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit(json_encode(['rCode' => 405, 'rMsg' => 'Error', 'rData' => 'Not allow method']));
        }

        $cid = $this->input->post('cid');        

        $this->FollowUp_model->update_dial_count($cid);
        $this->FollowUp_model->update_src_exten($this->session->userdata('pbx_exten'),$cid);


        $rResult = $this->Customer_model->get_customer_by_id($cid);
        
        // รับค่าพารามิเตอร์จาก GET หรือ POST
        $extension = $this->session->userdata('pbx_exten');
        $number = $rResult['phone_number'];
        $context = 'DLPN_DialPlan'.$extension;
        $channel_type = 'PJSIP';
        
        // ตรวจสอบว่ามีการส่งค่ามาหรือไม่
        if (empty($extension) || empty($number)) {
            $response = [
                'status' => 'error',
                'message' => 'กรุณาระบุเบอร์ภายในและเบอร์ปลายทาง'
            ];
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }
        
        // สั่งโทรออก
        if (!empty($channel_type)) {
            // ถ้าระบุประเภทช่องสัญญาณ
            $result = $this->asterisk_ami->originate_call($extension, $number, $context, '', 1, 30, $channel_type);
        } else {
            // ถ้าไม่ระบุประเภทช่องสัญญาณ ให้ลองทั้งหมด
            $result = $this->asterisk_ami->try_originate_call($extension, $number, $context);
        }
        
        // ตรวจสอบผลลัพธ์
        if (strpos($result, 'Success') !== false) {
            $response = [
                'rCode' => 200,                
                'status' => 'success',
                'message' => 'กำลังโทรออกจากเบอร์ ' . $extension . ' ไปยัง ' . $number,
                'response' => $result
            ];
        } else {
            $response = [
                'rCode' => 400,
                'status' => 'error',
                'message' => 'ไม่สามารถโทรออกได้',
                'response' => $result
            ];
        }
        
        // ส่งผลลัพธ์กลับเป็น JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ChannelHangup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode([
                    'rCode' => 405,
                    'rMsg' => 'Error',
                    'rData' => 'Not allow method'
                ]));
        }
    
        $customer_id = $this->input->post('customer_id');
    
        if (empty($customer_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'rCode' => 400,
                    'rMsg' => 'Error',
                    'rData' => 'ไม่พบ customer_id'
                ]));
        }
    
        $rCustomer = $this->Customer_model->get_customer_by_id($customer_id);
    
        if (empty($rCustomer)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'rCode' => 400,
                    'rMsg' => 'Error',
                    'rData' => 'ไม่พบข้อมูลลูกค้าที่ระบุ'
                ]));
        }
    
        if (empty($rCustomer['pbx_channel'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode([
                    'rCode' => 400,
                    'rMsg' => 'Error',
                    'rData' => 'ไม่พบ channel ที่ตรงกัน'
                ]));
        }
    
        $result = $this->asterisk_ami->hangup_channel($rCustomer['pbx_channel']);
    
        if (is_string($result) && strpos($result, 'Success') !== false) {
            $response = [
                'rCode' => 200,
                'status' => 'success',
                'message' => 'วางสายเรียบร้อยแล้ว',
                'response' => $result
            ];
        } else {
            $response = [
                'rCode' => 400,
                'status' => 'error',
                'message' => 'ไม่สามารถตัดสัญญาณได้',
                'response' => $result
            ];
        }
    
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    }
    
}