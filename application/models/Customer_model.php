<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_customers()
    {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->order_by('customer_id', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าตาม ID
    public function get_customer_by_id($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get();

        return $query->row_array(); // ใช้ row_array() เพื่อคืนค่าผลลัพธ์เป็น array
    }


    // ฟังก์ชั่นเพิ่มลูกค้า
    public function add_customer($data)
    {
        
        // รับปี ค.ศ. 2 หลักท้าย + เดือน 2 หลัก
        $year = date('y'); // ปี ค.ศ. 2 หลักท้าย
        $month = date('m'); // เดือน 2 หลัก
        $prefix = "IC-{$year}{$month}"; // สร้างรหัสนำหน้า

        // ดึงรหัสลูกค้าสูงสุดที่มีอยู่แล้ว
        $this->db->select("customer_id");
        $this->db->like("customer_id", $prefix, "after");
        $this->db->order_by("customer_id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get("customers");
        $row = $query->row();

        if ($row && !empty($row->customer_id)) {
            // ดึงเลขท้ายของรหัสล่าสุด (6 หลักท้าย)
            $last_code = intval(substr($row->customer_id, -6));
            $new_number = str_pad($last_code + 1, 6, "0", STR_PAD_LEFT);
        } else {
            // ถ้ายังไม่มีข้อมูลในเดือนนั้น ให้เริ่มต้นที่ 000001
            $new_number = "000001";
        }

        // สร้างรหัสลูกค้าใหม่
        $data["customer_id"] = "{$prefix}{$new_number}";   

        return $this->db->insert('customers', $data);
    }

    public function insert_data($data)
    {
        // รับปี ค.ศ. 2 หลักท้าย + เดือน 2 หลัก
        $year = date('y'); // ปี ค.ศ. 2 หลักท้าย
        $month = date('m'); // เดือน 2 หลัก
        $prefix = "IC-{$year}{$month}"; // สร้างรหัสนำหน้า

        // ดึงรหัสลูกค้าสูงสุดที่มีอยู่แล้ว
        $this->db->select("customer_id");
        $this->db->like("customer_id", $prefix, "after");
        $this->db->order_by("customer_id", "DESC");
        $this->db->limit(1);
        $query = $this->db->get("customers");
        $row = $query->row();

        if ($row && !empty($row->customer_id)) {
            // ดึงเลขท้ายของรหัสล่าสุด (6 หลักท้าย)
            $last_code = intval(substr($row->customer_id, -6));
            $new_number = str_pad($last_code + 1, 6, "0", STR_PAD_LEFT);
        } else {
            // ถ้ายังไม่มีข้อมูลในเดือนนั้น ให้เริ่มต้นที่ 000001
            $new_number = "000001";
        }

        // สร้างรหัสลูกค้าใหม่
        $data["customer_id"] = "{$prefix}{$new_number}";

        // ทำการเพิ่มข้อมูล
        return $this->db->insert("customers", $data);
    }


    // ฟังก์ชั่นแก้ไขข้อมูลลูกค้า
    public function update_customer($customer_id, $data)
    {
        $this->db->where('customer_id', $customer_id);
        $result = $this->db->update('customers', $data);

        if($result > 0){
            return true;
        }else{
            return false;
        }

    }

    // ฟังก์ชั่นลบลูกค้า
    public function delete_customer($customer_id)
    {
        $this->db->where('customer_id', $customer_id);
        return $this->db->delete('customers');
    }

    public function delete_bulk_customer($customer_ids = null){

        if ($customer_ids === null || empty($customer_ids)) {
            return false;
        }
        
        // ลบลูกค้าจากฐานข้อมูล
        $this->db->where_in('customer_id', $customer_ids);
        $result = $this->db->delete('customers');
        // แสดง query ล่าสุดที่ถูกใช้
        //$sql = $this->db->last_query(); // ใช้เพื่อแสดง query ล่าสุดใน console หรือ log
        return $result;
    }
}
