<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SaleOrder extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('SaleOrder_model');
    }

    public function index()
	{	

        //if (empty($_REQUEST['cid'])) exit();

        $this->session->set_userdata('menu_active', 'saleorder');

        $data['contents'] = [];
        $data['SaleOrder'] = $this->SaleOrder_model->get_all_SaleOrder();
        $data['header_content'] = ['title'=>'ใบขายสินค้า','right_menu'=>'Sale Order'];
        $data['content'] = "saleorder/saleorder_view";
        $this->load->view('template/main_layout_view', $data);

	}

    public function FcSaleOrderTables(){
        $data['saleorder'] = $this->SaleOrder_model->get_all_SaleOrder();
        $this->load->view('saleorder/saleorder_table_view', $data);
    }

}