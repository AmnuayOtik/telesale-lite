<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

	public function index()
	{		
        $this->session->set_userdata('menu_active', 'dashboard');

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'Dashboard','right_menu'=>'แดชบอร์ด'];
        $data['content'] = "dashboard/dashboard_view";
        $this->load->view('template/main_layout_view', $data);
	}

    public function FcFetchDatatable(){
        header('Content-Type: application/json');
        $rData = [];
        echo json_encode($rData);
        exit();
    }

    public function FcInsert(){
        
        header('Content-Type: application/json');
        $rData = [];
        echo json_encode($rData);
        exit();

    }

    public function FcUpdate(){

        header('Content-Type: application/json');
        $rData = [];
        echo json_encode($rData);
        exit();

    }

    public function FcDelete(){

        header('Content-Type: application/json');
        $rData = [];
        echo json_encode($rData);
        exit();
        
    }

}   