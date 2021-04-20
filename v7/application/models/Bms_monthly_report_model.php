<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_monthly_report_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function insert ($data)
    {
        $this->db->insert('bms_report', $data);
        return $insertId = $this->db->insert_id();
    }

    function check_report_exists ( $property_id, $report_month, $report_year )
    {
        $sql = "SELECT COUNT(1) as total_record, a.report_id, a.managed_by, a.prepared_by
                FROM bms_report a
                WHERE a.property_id = '$property_id'
                AND a.report_month = '$report_month'
                AND a.report_year = '$report_year'";

        $query = $this->db->query($sql);
        $row = $query->row();
        $data ['total_record'] = $row->total_record;
        $data ['report_id'] = $row->report_id;
        $data ['managed_by'] = $row->managed_by;
        $data ['prepared_by'] = $row->prepared_by;
        return $data;
    }

    function check_if_insert__id_exists_in_service_provider_report_table ($report_id, $table_name) {
        $sql = "SELECT COUNT(1) as total_record
                FROM $table_name
                WHERE report_id = '$report_id'";

        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->total_record;
    }

    function bms_report_data ($report_id) {
        $sql = "SELECT report_id, property_id, report_month, report_year
                FROM bms_report
                WHERE report_id = '$report_id'";

        $query = $this->db->query($sql);
        return $row = $query->row();
    }

    function insert_common_info ($data)
    {
        $this->db->insert('bms_report_common_info', $data);
        return $insertId = $this->db->insert_id();
    }

    function update_common_info ($data, $report_commoon_info_id)
    {
        $this->db->update('bms_report_common_info', $data, array('report_commoon_info_id' => $report_commoon_info_id));
    }

    function insert_major_task ( $data ) {
        $this->db->insert('bms_report_major_task', $data);
        return $insertId = $this->db->insert_id();
    }

    function insert_service_provider ( $data ) {
        $this->db->insert('bms_service_provider_assessment', $data);
        return $insertId = $this->db->insert_id();
    }

    function update_service_provider ( $data, $report_service_provider_id ) {
        $this->db->update('bms_service_provider_assessment', $data, array('report_service_provider_id' => $report_service_provider_id));
    }


    function get_service_provider_data ( $selected_year, $selected_month, $property_id ) {
        $sql = "SELECT a.service_provider_id, a.property_id, a.provider_name, a.annual_payment, a.contract_start_date, a.contract_end_date, b.service_provider_cat_name
        FROM bms_service_provider a 
        LEFT JOIN bms_service_provider_cat b ON b.service_provider_cat_id = a.service_provider_cat_id  
        WHERE a.contractual = 1
        AND a.property_id = $property_id
        AND DATE_FORMAT('$selected_year-$selected_month-01', '%Y-%m') BETWEEN DATE_FORMAT(a.contract_start_date, '%Y-%m') AND  DATE_FORMAT(a.contract_end_date, '%Y-%m')
        GROUP BY a.service_provider_id";
        $sql .= " ORDER BY a.provider_name ASC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_service_provider_data_from_report_table ( $report_id ) {
        $sql = "SELECT a.report_service_provider_id, b.service_provider_id,b.property_id, b.provider_name, b.annual_payment, b.contract_start_date, b.contract_end_date, c.service_provider_cat_name, a.assessment, a.remarks  
        FROM bms_service_provider b   
        LEFT JOIN bms_service_provider_assessment a ON a.service_provider_id = b.service_provider_id
        LEFT JOIN bms_service_provider_cat c ON b.service_provider_cat_id = c.service_provider_cat_id  
        WHERE a.report_id = '$report_id'";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_service_provider_data_PDF ( $property_id, $report_id, $selected_year, $selected_month ) {
        $sql = "SELECT b.service_provider_id,b.property_id, b.provider_name, b.annual_payment, b.contract_start_date, b.contract_end_date, 
        c.service_provider_cat_name, a.assessment, a.remarks, SUM(d.head_count) AS head_count_obtain, 
        b.mon, b.tue, b.wed, b.thu, b.fri, b.sat, b.sun, b.public_holiday
        FROM bms_service_provider b 
        LEFT JOIN bms_service_provider_assessment a ON (a.service_provider_id = b.service_provider_id AND a.report_id = '$report_id')
        LEFT JOIN bms_service_provider_cat c ON b.service_provider_cat_id = c.service_provider_cat_id 
        LEFT JOIN bms_service_provider_attendence d ON (b.service_provider_id = d.service_provider_id AND DATE_FORMAT(d.date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-01', '%Y-%m'))
        WHERE b.property_id = '$property_id' AND b.contractual = 1
        AND DATE_FORMAT('$selected_year-$selected_month-01', '%Y-%m') BETWEEN DATE_FORMAT(b.contract_start_date, '%Y-%m') AND DATE_FORMAT(b.contract_end_date, '%Y-%m') 
        GROUP BY b.service_provider_id 
        ORDER BY b.provider_name";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_service_provider_attendance_PDF ( $property_id, $report_id, $selected_year, $selected_month ) {
        $sql = "SELECT b.service_provider_id, sum(a.head_count) as head_count_obtain, count(1) as total_days, b.provider_name,
        b.mon, b.tue, b.wed, b.thu, b.fri, b.sat, b.sun, b.public_holiday
        FROM bms_service_provider_attendence a  
        LEFT JOIN bms_service_provider b ON a.service_provider_id = b.service_provider_id
        WHERE DATE_FORMAT(a.date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m')
        AND b.property_id = '$property_id'
        GROUP BY b.service_provider_id
        ORDER BY b.provider_name;";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_service_provider_attendance_detail_PDF ( $property_id, $report_id, $selected_year, $selected_month ) {
        $sql = "SELECT b.service_provider_id, b.provider_name, day(a.date) as attendance_day, a.head_count, b.head_count as total_head_count_per_day 
        FROM bms_service_provider_attendence a  
        LEFT JOIN bms_service_provider b ON a.service_provider_id = b.service_provider_id
        LEFT JOIN bms_service_provider_assessment c ON b.service_provider_id = c.service_provider_id
        WHERE DATE_FORMAT(a.date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m')
        AND b.property_id = '$property_id'
        ORDER BY b.provider_name;";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_common_info_data_PDF ( $report_id ) {
        $sql = "SELECT a.report_commoon_info_id, a.date, a.info_base, a.remarks 
        FROM bms_report_common_info a   
        WHERE a.report_id = '$report_id'";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_major_task_data_PDF ( $report_id ) {
        $sql = "SELECT a.report_major_task_id, a.location, a.description, a.action, a.date_report, a.date_line, a.date_approved, a.date_rectified 
        FROM bms_report_major_task a   
        WHERE a.report_id = '$report_id'";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_minor_task_data_PDF ( $property_id, $selected_year, $selected_month ) {
        $sql = "SELECT a.task_name, a.task_id, b.desi_name, a.created_date, a.updated_date, a.task_status 
        FROM bms_task a LEFT JOIN bms_designation b on a.assign_to = b.desi_id
        WHERE a.property_id = $property_id
        AND DATE_FORMAT(a.created_date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m')
        union
        (SELECT a.task_name, a.task_id, b.desi_name, a.created_date, a.updated_date, a.task_status 
        FROM bms_task a LEFT JOIN bms_designation b on a.assign_to = b.desi_id
        WHERE a.property_id = $property_id
        AND a.task_status = 'O'
        AND DATE_FORMAT(a.created_date, '%Y-%m-%d') < DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m-%d'))
        ORDER BY task_status desc, created_date desc";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_incident_data_PDF ( $property_id, $selected_year, $selected_month ) {
        $sql = "SELECT a.incident_date, a.incident_time, a.details, a.incident_location, a.remarks, a.status 
        FROM bms_incident a 
        WHERE a.property_id = $property_id
        AND DATE_FORMAT(a.incident_date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m');";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_renewal_data_PDF ( $property_id ) {
        $sql = "SELECT a.annual_renewal_id,a.item_descrip,a.serial_no,a.location,a.license_no,a.supplier_name,
                DATE_FORMAT(a.license_expiry_date, '%d-%m-%Y') AS license_expiry_date
                FROM bms_annual_renewal a
                WHERE property_id = $property_id
                ORDER BY license_expiry_date DESC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_asset_schedule_chart_data_PDF ( $property_id, $selected_year, $selected_month ) {
        $sql = "SELECT a.asset_id, CONCAT(a.asset_name, ' - ' , a.asset_location) as asset_name, day(b.service_date) as service_date_scheduled, 
                day(c.service_date) as service_date_done, c.asset_service_details_id, c.created_date, d.file_name 
                FROM bms_property_assets a
                LEFT JOIN bms_asset_service_schedule b ON a.asset_id = b.asset_id
                LEFT JOIN bms_asset_service_details c ON b.asset_service_schedule_id = c.service_schedule_id
                LEFT JOIN bms_asset_service_details_att d ON c.asset_service_details_id = d.asset_service_details_id
                WHERE a.property_id = $property_id
                AND DATE_FORMAT(b.service_date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m')
                ORDER BY asset_name;";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_utility_report_data_PDF ( $property_id, $selected_year, $selected_month ) {
        // Get information from MR Sathesh
        // Get information from MR Sathesh
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

    function get_property_data_PDF ( $property_id ) {
        $sql = "SELECT a.property_name, a.jmb_mc_name, a.logo, a.state_id
        FROM bms_property a
        WHERE a.property_id = $property_id;";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->row();
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

    function unset_report_commoon_info_id ($report_commoon_info_id) {
        return $this->db->delete('bms_report_common_info',array('report_commoon_info_id'=>$report_commoon_info_id));
    }

    function unset_report_major_task_id ( $report_major_task_id ) {
        return $this->db->delete('bms_report_major_task',array('report_major_task_id'=>$report_major_task_id));
    }

    function update_major_task ( $data, $report_major_task_id )
    {
        $this->db->update('bms_report_major_task', $data, array('report_major_task_id' => $report_major_task_id));
    }

    function update_report_table ( $data, $report_id )
    {
        $this->db->update ('bms_report', $data, array('report_id' => $report_id ));
    }

    function get_property_staff_detail ( $staff_id, $selected_year, $selected_month ) {
        $sql = "SELECT DISTINCT day(a.attn_date) as attendance_day 
                FROM `bms_staff_attendance` a
                where a.staff_id = '$staff_id'
                AND DATE_FORMAT(a.attn_date, '%Y-%m') = DATE_FORMAT('$selected_year-$selected_month-1', '%Y-%m')
                AND a.in_out_type IN (1, 2)";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();

    }

    function get_property_staff_attendance ($property_id,$exclude_desi_ids='', $selected_year, $selected_month) {
        $condi = $exclude_desi_ids != '' ? "AND designation_id NOT IN (".$exclude_desi_ids.")" : '';
        $sql = "SELECT staff_id,first_name,last_name,mobile_no,desi_name
                FROM `bms_staff`
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1,2,3) 
                ".$condi."
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1363,1443)
                AND created_date < '$selected_year-$selected_month-1'
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY sort_order ASC,desi_name ASC,first_name ASC,last_name ASC";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function get_holiday_dates ($state_id, $year, $month) {
        $first_date = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $year . '-' . $month . '-01' ) ) ));
        $a_date = "$year-$month-01";
        $last_date = date("Y-m-t", strtotime($a_date));
        $sql = "SELECT date as day FROM bms_holiday where state_id in(10, $state_id) and (date BETWEEN '$first_date' AND '$last_date')  ORDER BY DATE;";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}