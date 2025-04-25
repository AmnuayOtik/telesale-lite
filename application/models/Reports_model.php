<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // ฟังก์ชั่นดึงข้อมูลลูกค้าทั้งหมด
    public function Report1_getReportTable($rData = [])
    {
        $from_date = isset($rData['from_date']) ? $rData['from_date'].' 00:00:00' : '';
        $to_date   = isset($rData['to_date']) ? $rData['to_date'].' 23:59:59' : '';
    
        $tSQL = "
            SELECT 
                c.user_id,
                u.full_name,
                SUM(CASE WHEN LOWER(c.cstatus) = 'finished' THEN 1 ELSE 0 END) AS Finished,
                SUM(CASE WHEN LOWER(c.cstatus) = 'waiting' THEN 1 ELSE 0 END) AS Waiting,
                SUM(CASE WHEN LOWER(c.cstatus) = 'postpone' THEN 1 ELSE 0 END) AS Postpone,
                SUM(CASE WHEN LOWER(c.cstatus) = 'incomplete' THEN 1 ELSE 0 END) AS Incomplete,
                COUNT(*) AS total
            FROM 
                customers c
            INNER JOIN users u ON c.user_id = u.user_id
            WHERE 
                c.date_create >= ? AND c.date_create < ?
            GROUP BY 
                c.user_id, u.full_name
            ORDER BY 
                c.user_id, u.full_name
        ";
    
        $query = $this->db->query($tSQL, [$from_date, $to_date]);
    
        // แสดงหรือบันทึก last query
        //log_message('debug', 'Last Query: ' . $this->db->last_query()); // บันทึกใน application/logs
        //echo $this->db->last_query(); // หรือจะแสดงบนหน้าเลยก็ได้ (เฉพาะตอน debug)
    
        return $query->result();
    }

    public function Report1_getChartSummary($rData =[]) {

        // ตรวจสอบว่า input มีค่าครบหรือไม่
        if (empty($rData['from_date']) || empty($rData['to_date'])) {
            return [];
        }

        $from_date = isset($rData['from_date']) ? $rData['from_date'].' 00:00:00' : '';
        $to_date   = isset($rData['to_date']) ? $rData['to_date'].' 23:59:59' : '';

        $sql = "SELECT 
                    SUM(CASE WHEN cstatus = 'finished' THEN 1 ELSE 0 END) AS Finished,
                    SUM(CASE WHEN cstatus = 'waiting' THEN 1 ELSE 0 END) AS Waiting,
                    SUM(CASE WHEN cstatus = 'postpone' THEN 1 ELSE 0 END) AS Postpone,
                    SUM(CASE WHEN cstatus = 'incomplete' THEN 1 ELSE 0 END) AS Incomplete
                FROM customers
                WHERE date_create >= ? AND date_create < ?";
        return $this->db->query($sql, [$from_date, $to_date])->row_array();
    }

    public function Report2_getReportTable($rData = [])
    {
        // ตรวจสอบว่า input มีค่าครบหรือไม่
        if (empty($rData['from_date']) || empty($rData['to_date'])) {
            return [];
        }
    
        $from_date = isset($rData['from_date']) ? $rData['from_date'].' 00:00:00' : '';
        $to_date   = isset($rData['to_date']) ? $rData['to_date'].' 23:59:59' : '';
    
        // สร้าง Query Builder แบบปลอดภัย (CI จะ escape ให้โดยอัตโนมัติ)
        $this->db->select('customer_id, full_name, ref_user_id, phone_number');
        $this->db->select('line_account, call_datetime, call_result, notified_via_line, cstatus');
        $this->db->from('customers');
        $this->db->where('date_create >=', $from_date);
        $this->db->where('date_create <=', $to_date);
    
        $query = $this->db->get();
        return $query->result(); // หรือ result_array() ถ้าต้องการ array
    }

    public function Report2_getChartSummary($rData = []) {

        // ตรวจสอบว่า input มีค่าครบหรือไม่
        if (empty($rData['from_date']) || empty($rData['to_date'])) {
            return [];
        }
        
        $from_date = isset($rData['from_date']) ? $rData['from_date'].' 00:00:00' : '';
        $to_date   = isset($rData['to_date']) ? $rData['to_date'].' 23:59:59' : '';

        $sql = "SELECT 
                    COALESCE(call_result, 'ไม่กำหนด') AS call_result, 
                    COUNT(customer_id) AS cnt 
                FROM customers
                WHERE date_create >= ? AND date_create < ?
                GROUP BY call_result";
        
        // Return all rows for the call_result and count
        return $this->db->query($sql, [$from_date, $to_date])->result_array();
    }
    
}