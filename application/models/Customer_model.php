<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();

    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function get_all_customers() {
        $this->db->select('*');
        $this->db->from('customers');

        // ดึงข้อมูลจาก session
        $user_id   = $this->session->userdata('user_id');
        $is_admin  = $this->session->userdata('is_admin');
        $filter    = $this->session->userdata('date_filter');

        // กำหนดค่า default วันที่เป็นวันนี้
        $start = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d 23:59:59');

        if ($is_admin) {
            if (!empty($filter)) {
                switch ($filter) {
                    case 'today':
                        break;

                    case 'yesterday':
                        $start = date('Y-m-d 00:00:00', strtotime('-1 day'));
                        $end   = date('Y-m-d 23:59:59', strtotime('-1 day'));
                        break;

                    case 'this_week':
                        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
                        $end   = date('Y-m-d 23:59:59', strtotime('sunday this week'));
                        break;

                    case 'this_month':
                        $start = date('Y-m-01 00:00:00');
                        $end   = date('Y-m-t 23:59:59');
                        break;

                    case 'this_year':
                        $start = date('Y-01-01 00:00:00');
                        $end   = date('Y-12-31 23:59:59');
                        break;

                    case 'date_range':
                        $start = $this->session->userdata('from_date');
                        $end   = $this->session->userdata('to_date');
                        if (empty($start) || empty($end)) {
                            $start = date('Y-m-d 00:00:00');
                            $end   = date('Y-m-d 23:59:59');
                        }
                        break;

                    default:
                        break;
                }

                $this->db->where('date_create >=', $start);
                $this->db->where('date_create <=', $end);
            } else {
                $this->db->where('date_create >=', $start);
                $this->db->where('date_create <=', $end);
                $this->db->where('user_id', $user_id);
            }

        } else {
            $this->db->where('date_create >=', $start);
            $this->db->where('date_create <=', $end);
            $this->db->where('user_id', $user_id);
        }

        // เพิ่มการจัดเรียง cstatus: Waiting มาก่อน Finished
        $this->db->order_by("FIELD(cstatus, 'Waiting', 'Finished','Incomplete','postpone')", NULL, FALSE);
        $this->db->order_by('customer_id', 'DESC');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_all_call_result_master() {
        $this->db->select('*');
        $this->db->from('master_callresult');        
        $query = $this->db->get();

        return $query->result_array(); // ใช้ row_array() เพื่อคืนค่าผลลัพธ์เป็น array
    }

    public function get_all_call_inform_master() {
        $this->db->select('*');
        $this->db->from('master_callinform');        
        $query = $this->db->get();

        return $query->result_array(); // ใช้ row_array() เพื่อคืนค่าผลลัพธ์เป็น array
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


    public function GetNextCustomerId(){
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

        return "{$prefix}{$new_number}";

    }

    // ฟังก์ชั่นเพิ่มลูกค้า
    public function add_customer($data = [])
    {
         // ตรวจสอบว่ารูปแบบ $data ถูกต้องและไม่ว่าง
        if (!is_array($data) || empty($data)) {
            return false;
        }
         // เพิ่มข้อมูลเข้าสู่ฐานข้อมูล
        return $this->db->insert('customers', $data);

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

    public function get_cstatus_today($user_id = '') {
        // ทำการแสดงข้อมูลดังต่อไปนี้
        $this->db->select('
            IFNULL(SUM(CASE WHEN cstatus = "Waiting" THEN 1 ELSE 0 END), 0) AS Waiting,
            IFNULL(SUM(CASE WHEN cstatus = "Finished" THEN 1 ELSE 0 END), 0) AS Finished,
            IFNULL(SUM(CASE WHEN cstatus = "Postpone" THEN 1 ELSE 0 END), 0) AS Postpone,
            IFNULL(SUM(CASE WHEN cstatus = "Incomplete" THEN 1 ELSE 0 END), 0) AS Incomplete
        ');
        // จากตาราง Customer
        $this->db->from('customers');

        // ตรวจสอบว่าเป็น admin หรือไม่
        $is_admin = $this->session->userdata('is_admin');
        // ตรวจสอบว่ามีการกรองวันเวลา ตามที่กำหนดไว้หรือไม่
        $filter = $this->session->userdata('date_filter');

        // เริ่มทำการตรวจสอบว่าเป็น admin หรือไม่
        if ($is_admin == true) {
            // ตรวจสอบว่ามีการกรองข้อมูลหรือไม่ Not Empty
            if (!empty($filter)) {
                switch ($filter) {
                    case 'today':
                        $start = date('Y-m-d 00:00:00');
                        $end   = date('Y-m-d 23:59:59');
                        break;
                    case 'yesterday':
                        $start = date('Y-m-d 00:00:00', strtotime('-1 day'));
                        $end   = date('Y-m-d 23:59:59', strtotime('-1 day'));
                        break;
                    case 'this_week':
                        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
                        $end   = date('Y-m-d 23:59:59', strtotime('sunday this week'));
                        break;
                    case 'this_month':
                        $start = date('Y-m-01 00:00:00');
                        $end   = date('Y-m-t 23:59:59');
                        break;
                    case 'this_year':
                        $start = date('Y-01-01 00:00:00');
                        $end   = date('Y-12-31 23:59:59');
                        break;
                    case 'date_range':
                        $start = $this->session->userdata('from_date');
                        $end   = $this->session->userdata('to_date');
                        break;
                    default:
                        $start = date('Y-m-d 00:00:00');
                        $end   = date('Y-m-d 23:59:59');
                        break;
                }
                // กรองตามวันเวลาที่กำหนดไว้
                $this->db->where('date_create >=', $start);
                $this->db->where('date_create <=', $end);
            } else {
                // ถ้าไม่ได้กำหนดเงื่อนไขการกรองใดๆ ให้ Default เป็นวันที่ปัจจุบัน
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $this->db->where('date_create >=', $start);
                $this->db->where('date_create <=', $end);
                //$this->db->where('user_id', $this->session->userdata('user_id'));
            }

        } else {
            // ไม่ใช่ admin (ผู้ใช้ทั่วไป) ให้แสดงข้อมูลเฉพาะวันปัจจุบันเท่านั้น และกรองตามชื่อผู้ใช้ user_id
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 23:59:59');
            $this->db->where('date_create >=', $start);
            $this->db->where('date_create <=', $end);
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }

        // ถ้า $user_id มีค่า override user_id จาก session
        /*
        if (!empty($user_id)) {
            //$this->db->where('who_update', $user_id);
        }
        */
        
        $query = $this->db->get();

        // แสดง SQL ล่าสุดที่ถูกสร้างขึ้นมา
        //echo $this->db->last_query();
        return $query->result();
    }




}
