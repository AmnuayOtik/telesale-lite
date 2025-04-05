<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

    public function __construct() {

        parent::__construct();
        // Load the AsteriskAMI library
       
    }

    public function index(){

    }

    /*
    public function index()
	{

        $this->session->set_userdata('menu_active', 'customer');

        $data['contents'] = [];
        $data['header_content'] = ['title'=>'งานวันนี้','right_menu'=>'Today Job'];
        $data['content'] = "customer/customer_view";
        $this->load->view('template/main_layout_view', $data);

	}
    */

    public function dial() {
        $this->load->helper('asterisk_ami');

        $result = makeCallHelper("2888", "0955499819");

        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

}
