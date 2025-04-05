<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SaleOrder_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_SaleOrder()
    {
        $this->db->select('*');
        $this->db->from('order_header');
        $this->db->order_by('order_id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }
}