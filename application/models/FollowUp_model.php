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

    public function insert_order_header($rData = [])
    {
        return $this->db->insert('order_header', $rData);
    }

    public function insert_order_detail($rData = [])
    {
        return $this->db->insert('order_detail', $rData);
    }

    public function update_order_header($rData = [])
    {
        $this->db->where('order_id', $rData['order_id']);
        return $this->db->update('order_header', $rData);
    }

    public function delete_order_detail($order_id = null) {
        // ตรวจสอบว่า 'order_id' มีอยู่ในข้อมูลที่ส่งมา
        if (!isset($order_id) || empty($order_id)) {
            return false;  // หากไม่มี 'order_id' คืนค่าผลลัพธ์เป็น false
        }
    
        // ลบข้อมูลเก่าตาม 'order_id'
        $this->db->where('order_id', $order_id);
        
        // ลบข้อมูลจากตาราง 'order_detail'
        $result = $this->db->delete('order_detail');

        // ตรวจสอบผลลัพธ์ของการลบ
        if ($result) {
            return true;  // ลบสำเร็จ
        } else {
            return false;  // หากลบไม่สำเร็จ
        }
    }

    
}


