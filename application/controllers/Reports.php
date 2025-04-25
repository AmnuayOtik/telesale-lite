<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Reports_model');        
        date_default_timezone_set('Asia/Bangkok');

    }

    public function index()
	{

        $menu = ['main'=>'manage','sub'=>'reports'];
        $this->session->set_userdata('menu',$menu);

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'รายงานรวม','right_menu'=>'รายงาน'];
        $data['content'] = "reports/reports_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcOpenReportModal()
    {
        $report_id = $this->input->post('report_id');
    
        switch ($report_id) {
            case 1:
                $this->load->view('reports/reports_rpt1_view');
                break;
    
            case 2:
                $this->load->view('reports/reports_rpt2_view');
                break;
    
            default:
                // แสดงหน้าไม่พบรายงาน หรือแจ้งเตือน
                echo "ไม่พบรายงานที่คุณต้องการเปิด";
                break;
        }
    }

    public function Report1(){
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Not allow this method.";
            exit();
        }
    
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $this->session->set_userdata('rFrom_date',$from_date);
        $this->session->set_userdata('rTo_date',$to_date);
    
        if(empty($from_date) || empty($to_date)){
            echo "กรุณาเลือกวันที่ให้ครบถ้วน";
            exit();    
        }
    
        // แปลงวันที่จาก dd-mm-yyyy -> yyyy-mm-dd
        $from_date = DateTime::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
        $to_date   = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
    
        $rData = array('from_date'=>$from_date,'to_date'=>$to_date);
    
        $data['ReportTable'] = $this->Reports_model->Report1_getReportTable($rData);
        $data['ReportChartSummary'] = $this->Reports_model->Report1_getChartSummary($rData);
        
        $data['condition'] = array('from_date'=>$from_date,'to_date'=>$to_date);
    
        $this->load->view('reports/report1_view',$data);
    }
    
    public function Report2() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Not allow this method.";
            exit();
        }
    
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
    
        $this->session->set_userdata('rFrom_date', $from_date);
        $this->session->set_userdata('rTo_date', $to_date);
    
        if (empty($from_date) || empty($to_date)) {
            echo "กรุณาเลือกวันที่ให้ครบถ้วน";
            exit();
        }
    
        // Convert date from dd-mm-yyyy to yyyy-mm-dd
        $from_date = DateTime::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
        $to_date = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
    
        $rData = array('from_date' => $from_date, 'to_date' => $to_date);
    
        $data['ReportTable'] = $this->Reports_model->Report2_getReportTable($rData);
        $data['ReportChartSummary'] = $this->Reports_model->Report2_getChartSummary($rData);
    
        $data['condition'] = array('from_date' => $from_date, 'to_date' => $to_date);
    
        $this->load->view('reports/report2_view', $data);
    }
    

}