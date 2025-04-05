<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Products_model');
    }

    public function index(){

    }

    public function ProductModallist(){
        $data['keywords'] = [];
        $data['products'] = $this->Products_model->get_all_products();
        $this->load->view('products/product_modal_list',$data);
    }
}