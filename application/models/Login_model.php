<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();        
    }

    // ตรวจสอบข้อมูลผู้ใช้จากฐานข้อมูล
    public function check_login($username, $password) {
        $query = $this->db->query("SELECT * FROM users WHERE username = ?", [$username]);
        $user = $query->row();

        if ($user && password_verify($password, $user->password)) {
            return $user; // คืนค่าข้อมูลผู้ใช้
        }
        return false; // ไม่พบผู้ใช้
    }

    // ดึงจำนวนครั้งที่ล็อกอินผิดพลาด
    public function get_login_attempts($ip_address) {
        $this->db->where('ip_address', $ip_address);
        $query = $this->db->get('login_attempts');

        if ($query->num_rows() > 0) {
            return $query->row()->attempts;
        }
        return 0;
    }

    // เพิ่มจำนวนครั้งที่ล็อกอินผิดพลาด
    public function increase_login_attempts($ip_address) {
        $this->db->where('ip_address', $ip_address);
        $query = $this->db->get('login_attempts');

        if ($query->num_rows() > 0) {
            // อัปเดตจำนวนครั้งที่พยายามล็อกอิน
            $this->db->where('ip_address', $ip_address);
            $this->db->set('attempts', 'attempts+1', FALSE);
            $this->db->update('login_attempts');
        } else {
            // เพิ่มข้อมูลใหม่
            $this->db->insert('login_attempts', ['ip_address' => $ip_address, 'attempts' => 1]);
        }
    }

    // รีเซ็ตจำนวนครั้งที่ล็อกอินผิดพลาด
    public function reset_login_attempts($ip_address) {
        $this->db->where('ip_address', $ip_address);
        $this->db->delete('login_attempts');
    }

    // เพิ่มผู้ใช้ใหม่ (เข้ารหัสรหัสผ่านก่อนบันทึก)
    public function register_user($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        return $this->db->query("INSERT INTO users (username, password) VALUES (?, ?)", [$username, $hashed_password]);
    }
}
