<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FollowUp_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    public function GetNextOrderId() {

        // รับปี ค.ศ. 2 หลักท้าย + เดือน 2 หลัก
        $year = date('y'); // ปี ค.ศ. 2 หลักท้าย
        $month = date('m'); // เดือน 2 หลัก
        $prefix = "SO-{$year}{$month}"; // สร้างรหัสนำหน้า

        // ดึงรหัสลูกค้าสูงสุดที่มีอยู่แล้ว
        $this->db->select("order_id");
        $this->db->like("order_id", $prefix, "after");
        $this->db->order_by("order_id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get("order_header");
        $row = $query->row();

        if ($row && !empty($row->order_id)) {
            // ดึงเลขท้ายของรหัสล่าสุด (6 หลักท้าย)
            $last_code = intval(substr($row->order_id, -6));
            $new_number = str_pad($last_code + 1, 6, "0", STR_PAD_LEFT);
        } else {
            // ถ้ายังไม่มีข้อมูลในเดือนนั้น ให้เริ่มต้นที่ 000001
            $new_number = "000001";
        }

        return "{$prefix}{$new_number}";

    }

    public function update($rData = [])
    {
        $this->db->where('customer_id', $rData['customer_id']);
        return $this->db->update('customers', $rData);
    }

    public function update_dial_count($customer_id) {
        $this->db->set('call_count', 'IFNULL(call_count, 0) + 1', false);
        $this->db->set('date_update', date('Y-m-d H:i:s'));   // ตั้งค่าวันที่อัปเดต
        $this->db->set('call_datetime', date('Y-m-d H:i:s'));   // ตั้งค่าวันที่อัปเดต
        $this->db->set('who_update', $this->session->userdata('user_id')); // ดึงชื่อผู้ใช้งานจาก session
        $this->db->where('customer_id', $customer_id);
        return $this->db->update('customers');
    }
    
    
}


