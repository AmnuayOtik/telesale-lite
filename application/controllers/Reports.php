<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Reports_model');
        $this->load->library('Pdf');
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

    public function FcOpenReportModal(){
        $this->load->view('reports/reports_rpt1_view');
    }

    public function Report1(){
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Not allow this method.";
            exit();
        }

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        if(empty($from_date) || empty($to_date)){
            echo "กรุณาเลือกวันที่ให้ครบถ้วน";
            exit();    
        }

        $rData = array('from_date'=>$from_date,'to_date'=>$to_date);

        $data['Report1'] = $this->Reports_model->Report1($rData);
        $data['condition'] = array('from_date'=>$from_date,'to_date'=>$to_date);

		//$this->load->view('reports/report1_view',$data);

        $html = $this->load->view('reports/report1_view', $data, true);

		// สั่งพรีวิวเอกสารเป็น html
		//$this->load->view('Import/print_tt1_view', $data);
		// สั่งพิมพ์ออกมาเป็นไฟล์ PDF
        $this->pdf->createPDF($html, 'mypdf', false);


    }

}