<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_daily_report_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function checkActiveSop ($property_id) {
        $sql = "SELECT count(1) AS cnt
                    FROM bms_sop AS a
                    WHERE a.property_id = ".$property_id."
                    AND sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '".date('Y-m-d')."')                    
                    AND start_date <= '".date('Y-m-d')."' AND (no_due_date = 1 OR (due_date <> '0000-00-00' AND due_date >= '".date('Y-m-d')."'))
                    AND (
                        (task_schedule = 1 AND ".strtolower(date('D'))." = 1) 
                            OR (task_schedule = 2 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),14)=0)
                            OR (task_schedule = 3 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),21)=0)
                            OR (task_schedule = 4 AND DATE_FORMAT(start_date,'%m') < '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."') 
                            OR (task_schedule = 5 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),3) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                            OR (task_schedule = 6 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),6) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                            OR (task_schedule = 7 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),12) = 0 AND DATE_FORMAT(start_date,'%m') = '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        )
                    AND due_by > '".date('H:i:s')."'";
        $query = $this->db->query($sql);
        $res = $query->row_array();
        //echo "<pre>".$this->db->last_query();
        return $res['cnt'];
    }
    
    function getPendingSop ($property_id,$report_date_db_format) {
        $sql = "SELECT sop_id,property_name,sop_name,desi_name,a.created_date
                    FROM bms_sop AS a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id 
                    LEFT JOIN bms_designation ON desi_id=assign_to
                    WHERE a.property_id = ".$property_id."
                    AND sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '$report_date_db_format')
                    AND start_date <= '$report_date_db_format' AND (no_due_date = 1 OR (due_date <> '0000-00-00' AND due_date >= '$report_date_db_format'))
                    AND (
                        (task_schedule = 1 AND ".strtolower(date('D',strtotime($report_date_db_format)))." = 1) 
                            OR (task_schedule = 2 AND MOD(DATEDIFF('".date('Y-m-d',strtotime($report_date_db_format))."',start_date),14)=0)
                            OR (task_schedule = 3 AND MOD(DATEDIFF('".date('Y-m-d',strtotime($report_date_db_format))."',start_date),21)=0)
                            OR (task_schedule = 4 AND DATE_FORMAT(start_date,'%m') < '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d',strtotime($report_date_db_format))."') 
                            OR (task_schedule = 5 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d',strtotime($report_date_db_format))."'),3) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d',strtotime($report_date_db_format))."')
                            OR (task_schedule = 6 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d',strtotime($report_date_db_format))."'),6) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d',strtotime($report_date_db_format))."')
                            OR (task_schedule = 7 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d',strtotime($report_date_db_format))."'),12) = 0 AND DATE_FORMAT(start_date,'%m') = '".date('m',strtotime($report_date_db_format))."' AND DATE_FORMAT(start_date,'%d') = '".date('d',strtotime($report_date_db_format))."')
                        )
                    ORDER BY b.property_name,sop_name";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function getPropertyStaffs ($property_id,$exclude_desi_ids) {
        
        $sql = "SELECT staff_id,first_name,last_name,desi_name
                FROM `bms_staff`
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1,2,3) 
                AND designation_id NOT IN (".$exclude_desi_ids.")
                AND staff_id NOT IN (1335,1336,1337,1342)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getStaffAttendance ($staff_id,$report_date_db_format) {
        $sql = "SELECT a.attn_date, a.atten_time, 
                a.in_out_type, a.remarks, a.img_name 
                FROM bms_staff_attendance a                
                WHERE a.staff_id=$staff_id AND a.attn_date = '$report_date_db_format'                
                ORDER BY attn_date,in_out_type";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function getTasks ($property_id,$report_date_db_format,$type) {
        if($type == 'created') {
            $condi = "a.created_date = '".$report_date_db_format."'";
        } else {
            $condi = "a.updated_date='".$report_date_db_format."' AND a.task_status='C'";
        }
        $sql = "SELECT task_id,task_name,due_date,a.created_date,task_status,task_location,task_details,task_category,
                task_source,task_update,assign_to,
                a.property_id,b.property_name,c.desi_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by,first_name,last_name               
                FROM bms_task a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to 
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_staff f ON f.`staff_id` = a.created_by
                WHERE a.property_id = $property_id 
                AND $condi
                ORDER BY  created_date ASC";
                
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function get_task_images ($task_id) {
        $query = $this->db->select('id,task_id,img_name')->get_where('bms_task_img',array('task_id'=>$task_id));        
        return $query->result_array();        
    }
    
    function getSops ($property_id,$report_date_db_format) {
        $sql = "SELECT *
                    FROM bms_sop AS a
                    WHERE a.property_id = ".$property_id."
                    AND ".strtolower(date('D',strtotime($report_date_db_format)))." = 1
                    AND start_date <= '$report_date_db_format' AND (no_due_date = 1 OR (due_date <> '0000-00-00' AND due_date >= '$report_date_db_format'))
                    AND sop_id IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '$report_date_db_format')
                    ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function getSopEntries ($property_id,$report_date_db_format) {
        $sql = "SELECT a.id,a.sop_id,remarks,requirement_type,requirement_val,entry_date,entered_date,
                is_overwtrite,entered_by,sop_name,assign_to,first_name,last_name,d.desi_name
                FROM bms_sop_entry AS a
                LEFT JOIN bms_sop b ON b.sop_id=a.sop_id
                LEFT JOIN bms_designation d ON d.desi_id=b.assign_to
                LEFT JOIN bms_staff AS c ON c.staff_id = a.entered_by
                WHERE DATE_FORMAT(a.entry_date, '%Y-%m-%d') = '$report_date_db_format'
                AND a.sop_id IN (SELECT bms_sop.sop_id FROM bms_sop WHERE property_id = ".$property_id." AND exclude_print=0)
                ORDER BY desi_name ASC   ";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_sub_entry ($sop_sub_id,$report_date_db_format) {
        $sql = "SELECT id,sop_sub_id,requirement_type,requirement_val, remarks, entry_date,entered_date,entered_by,first_name,last_name
                FROM bms_sop_sub_entry AS a
                LEFT JOIN bms_staff AS b ON b.staff_id = a.entered_by 
                WHERE a.sop_sub_id='".$sop_sub_id."' AND (DATE_FORMAT(a.entry_date, '%Y-%m-%d') = '$report_date_db_format' )  
                ORDER BY a.id DESC";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();exit;
        return $query->row_array();
    }

    function get_service_providers ( $property_id, $date ) {
        $date = date('Y-m-d',strtotime( $date ) );
        $sql = "SELECT a.service_provider_id, a.provider_name, a.head_count
            FROM bms_service_provider AS a
            WHERE a.property_id = ".$property_id."
            AND a.head_count > 0
            AND a.contractual = 1
            AND a.contract_end_date >= '$date'
            AND a.service_provider_id NOT IN ( 
            SELECT service_provider_id FROM bms_service_provider_attendence 
            WHERE property_id = $property_id
            AND date = '$date'
            ) ORDER BY a.provider_name";

        $query = $this->db->query ($sql);
        return $query->result_array();
    }

    function insert_service_provider_attendence ( $data ) {
        $this->db->insert('bms_service_provider_attendence', $data);
        return $insertId = $this->db->insert_id();
    }
    
}