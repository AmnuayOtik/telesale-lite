<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_Users()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        return $query->result();

    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าตาม ID
    public function get_users_by_id($user_id = null)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        return $query->row_array(); // ใช้ row_array() เพื่อคืนค่าผลลัพธ์เป็น array
    }

    public function GetNextUserId() {

        // รับปี ค.ศ. 2 หลักท้าย + เดือน 2 หลัก
        $year = date('y'); // ปี ค.ศ. 2 หลักท้าย
        $month = date('m'); // เดือน 2 หลัก
        $prefix = "US-{$year}{$month}"; // สร้างรหัสนำหน้า

        // ดึงรหัสลูกค้าสูงสุดที่มีอยู่แล้ว
        $this->db->select("user_id");
        $this->db->like("user_id", $prefix, "after");
        $this->db->order_by("user_id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get("users");
        $row = $query->row();

        if ($row && !empty($row->user_id)) {
            // ดึงเลขท้ายของรหัสล่าสุด (6 หลักท้าย)
            $last_code = intval(substr($row->user_id, -5));
            $new_number = str_pad($last_code + 1, 5, "0", STR_PAD_LEFT);
        } else {
            // ถ้ายังไม่มีข้อมูลในเดือนนั้น ให้เริ่มต้นที่ 000001
            $new_number = "00001";
        }

        return "{$prefix}{$new_number}";

    }

    public function add_user($rData = [])
    {
        if (!is_array($rData) || empty($rData)) {
            return false; // ป้องกันการส่งค่าที่ไม่ถูกต้อง
        }
        
        return $this->db->insert('users', $this->security->xss_clean($rData));
    }

    public function update_user($user_id = null, $rData = [])
    {
        if (empty($user_id) || !is_string($user_id) || empty($rData) || !is_array($rData)) {
            return false; // ป้องกันค่าไม่ถูกต้อง
        }

        $this->db->where('user_id', $this->db->escape_str(trim($user_id))); // ป้องกัน SQL Injection
        return $this->db->update('users', $this->security->xss_clean($rData));
    }

    public function GetExistingUsername($username = null){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function update_password($user_id = null, $newPassword = null){

        if(!empty($user_id) && !empty($newPassword)){
            $this->db->where('user_id', $user_id);
            $rData = [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'who_update' => $this->session->userdata('user_id'),
                'date_update' => date('Y-m-d H:i:s'),
            ];
            return $this->db->update('users', $rData);
        }else{
            return false;
        }  

    }

    public function delete_user($user_id = null){

        if(empty($user_id)){
            return false;
        }

        $this->db->where('user_id', $user_id);
        return $this->db->delete('users');
        
    }

}
