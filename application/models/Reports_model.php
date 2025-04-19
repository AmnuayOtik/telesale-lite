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
        $from_date = isset($rData['from_date']) ? $rData['from_date'] : '';
        $to_date   = isset($rData['to_date']) ? $rData['to_date'] : '';
    
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

    public function Report1_getChartSummary($rData) {
        $sql = "SELECT 
                    SUM(CASE WHEN cstatus = 'finished' THEN 1 ELSE 0 END) AS Finished,
                    SUM(CASE WHEN cstatus = 'waiting' THEN 1 ELSE 0 END) AS Waiting,
                    SUM(CASE WHEN cstatus = 'postpone' THEN 1 ELSE 0 END) AS Postpone,
                    SUM(CASE WHEN cstatus = 'incomplete' THEN 1 ELSE 0 END) AS Incomplete
                FROM customers
                WHERE date_create >= ? AND date_create < ?";
        return $this->db->query($sql, [$rData['from_date'], $rData['to_date']])->row_array();
    }
    

}