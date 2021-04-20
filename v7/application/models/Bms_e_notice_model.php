<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_e_notice_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function get_notice_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.subject) LIKE '%".$search_txt."%' OR LOWER(a.message) LIKE '%".$search_txt."%' OR LOWER(b.property_name) LIKE '%".$search_txt."%') ";
        }


        $sql = "SELECT a.notice_id, a.property_id, b.property_name, a.block_id, c.block_name, subject, DATE_FORMAT(start_date,'%d-%m-%Y') AS start_date
                FROM bms_e_notice a   
                LEFT JOIN bms_property b ON b.property_id = a.property_id                
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY a.created_date DESC ".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

    function getUnitForEnotice ($property_id,$block_id = 0,$unit_id = '') {
        $condi = '';
        if($block_id != 0)
            $condi = " AND a.block_id = '".$block_id."'";
        if($unit_id != 0 && $unit_id != '' )
            $condi .= " AND unit_id = '".$unit_id."'";
        $sql = "SELECT unit_id,unit_no, b.block_name,unit_status,floor_no,
                owner_name,gender,email_addr,contact_1,is_defaulter,a.block_id, valid_email  
                FROM bms_property_units a
                LEFT JOIN bms_property_block b ON b.block_id = a.block_id 
                WHERE a.property_id = '".$property_id."' $condi
                ORDER BY unit_no";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function get_notice_details ($notice_id) {
        $sql = "SELECT a.notice_id, a.property_id, b.property_name, a.block_id, c.block_name, subject,a.unit_ids, message, 
                DATE_FORMAT(start_date,'%d-%m-%Y') AS start_date,DATE_FORMAT(end_date,'%d-%m-%Y') AS end_date, attachment_name,
                first_name,last_name, DATE_FORMAT(a.created_date,'%d-%m-%Y %h:%i %p') AS created_date
                FROM bms_e_notice a   
                LEFT JOIN bms_property b ON b.property_id = a.property_id                
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_staff AS d ON d.staff_id = a.created_by 
                WHERE notice_id=".$notice_id;
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function getUnitsInfo ($unit_ids) {

        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter 
                FROM bms_property_units 
                WHERE unit_id IN(".$unit_ids.")
                ORDER BY unit_no";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function insert_notice ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_e_notice', $data);
        return $this->db->insert_id();
    }

    function insert_notice_queue ( $data ) {
        $this->db->insert('bms_e_notice_queue', $data);
    }

    function update_unit ($data,$unit_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id));
    }

    function getNoticeSubject ($property_id) {
        $sql = "SELECT notice_id, CONCAT(subject,' - ',start_date) AS 'subject'
                FROM bms_e_notice 
                WHERE property_id = $property_id ORDER BY notice_id DESC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function getnoticeQueueList ($offset = '0', $per_page = '25', $property_id, $notice_id='', $search_txt='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        $cond = '';
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(b.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.to_name) LIKE '%".$search_txt."%' OR LOWER(a.to) LIKE '%".$search_txt."%') ";
        }

        $sql = "SELECT b.unit_no,a.to_name,a.to,a.sent_dt,a.is_sent
                FROM bms_e_notice_queue a
                LEFT JOIN bms_property_units b ON b.unit_id = a.unit_id
                LEFT JOIN bms_e_notice c ON c.notice_id = a.notice_id                
                WHERE a.notice_id = $notice_id AND c.property_id = $property_id " . $cond;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $sql .= " ORDER BY b.unit_no ASC".$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

}