<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_products()
    {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->order_by('product_id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }
    
}