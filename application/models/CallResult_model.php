<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CallResult_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();

    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_callresult() 
    {

        $this->db->select('*');
        $this->db->from('master_callresult');
        $query = $this->db->get();
        return $query->result();
    }
    
    // ฟังก์ชั่นเพิ่มลูกค้า
    public function add_callresult($data = [])
    {
         // ตรวจสอบว่ารูปแบบ $data ถูกต้องและไม่ว่าง
        if (!is_array($data) || empty($data)) {
            return false;
        }
         // เพิ่มข้อมูลเข้าสู่ฐานข้อมูล
        return $this->db->insert('master_callresult', $data);

    }

    // ฟังก์ชั่นแก้ไขข้อมูลลูกค้า
    public function update_callresult($id, $data)
    {

        $this->db->where('id', $id);
        $result = $this->db->update('master_callresult', $data);

        if($result > 0){
            return true;
        }else{
            return false;
        }

    }

    public function get_CallResult_by_id($id = null)
    {
        $this->db->select('*');
        $this->db->from('master_callresult');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row_array(); // ใช้ row_array() เพื่อคืนค่าผลลัพธ์เป็น array
    }

    // ฟังก์ชั่นลบลูกค้า
    public function delete_callresult($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('master_callresult');
    }
}
