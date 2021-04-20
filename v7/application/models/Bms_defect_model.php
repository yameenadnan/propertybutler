<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_defect_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function defect_insert ($data) {
        $this->db->insert('bms_defect', $data);
        return $insert_id = $this->db->insert_id();//1316;//
    }

    function defect_image_name_insert ($data) {
        $this->db->insert('bms_defect_img', $data);
    }

    function get_defect ($staff_id = '', $offset = '', $per_page = '',$property_id='',$defect_status='',$defect_id='',$sear_txt = '',$sort_by='desc') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond2 = ' AND b.property_status=1';
        if($property_id != '' && $property_id != 'al')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
        if($defect_status != '' && $defect_status != 'all')
            $cond2 .= ' AND a.defect_status=\''.$defect_status.'\' ';
        if($defect_id != '')
            $cond2 .= ' AND a.defect_id=\''.ltrim($defect_id,'0').'\' ';
        
        if($sear_txt != '')
            $cond2 .= ' AND (a.defect_name LIKE \'%'.$sear_txt.'%\' OR a.defect_location LIKE \'%'.$sear_txt.'%\' OR  a.defect_detail LIKE \'%'.$sear_txt.'%\' ) ';
            
        $sort_by = $sort_by == 'asc' ? ' ORDER BY created_date ASC ' :  ' ORDER BY created_date DESC ';
        
        $num_rows = '';

        $sql = "SELECT d.block_name, c.unit_no, a.defect_id, a.defect_name, a.defect_location, a.created_date, a.defect_status, a.property_id, a.defect_id
            FROM bms_defect a 
            LEFT JOIN bms_property b ON b.property_id = a.property_id  
            LEFT JOIN bms_property_units c ON a.unit_id = c.unit_id  
            LEFT JOIN bms_property_block d ON d.block_id = c.block_id  
            WHERE 1=1" . $cond2." 
            AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.")" . $sort_by;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $query = $this->db->query($sql . $limit);
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_defect_details ($defect_id,$staff_id) {

        $sql = "SELECT a.defect_id, a.defect_name, a.created_date,defect_status,defect_location, defect_detail, 
                a.property_id,b.property_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by,first_name,last_name, defect_close_remarks, g.unit_status_name
                FROM bms_defect a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id  
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_staff f ON f.`staff_id` = a.created_by
                LEFT JOIN bms_property_unit_status g ON g.unit_status_id = d.unit_status
                WHERE a.defect_id=". $defect_id." AND 
                a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") 
                ";

        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_defect_details_jmb_mc ($defect_id, $property_id) {

        $sql = "SELECT a.defect_id, a.defect_name, a.created_date, a.defect_status, a.defect_location, a.defect_detail,
                a.property_id, b.property_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by, a.defect_close_remarks, g.unit_status_name
                FROM bms_defect a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id  
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_property_unit_status g ON g.unit_status_id = d.unit_status
                WHERE a.defect_id = '". $defect_id."' AND 
                a.property_id = '".$property_id . "'";

        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function get_defect_images ($defect_id) {
        $query = $this->db->select('id,defect_id,img_name')->get_where('bms_defect_img',array('defect_id'=>$defect_id));
        return $query->result_array();        
    }

    function set_defect_forum ($defect_id,$chat_text,$img_name,$staff_id) {
        $comment_data['defect_id'] = $defect_id;
        $comment_data['comment_date'] = date("Y-m-d H:i:s");
        $comment_data['comment_by'] = $staff_id;
        $comment_data['comment'] = $chat_text;
        $comment_data['img_name'] = $img_name;
        return $this->db->insert('bms_defect_forum', $comment_data);
    }

    function getDefectForum($defect_id,$type = '') {
        $sql = "SELECT a.defect_id, a.entry_date AS comment_date, a.staff_id AS comment_by, a.description AS comment,a.img_name, b.first_name,b.last_name 
                FROM
                (SELECT defect_id,log_date AS entry_date,log_by AS staff_id,log_remarks AS description, '' AS img_name
                FROM bms_defect_log WHERE defect_id = $defect_id UNION
                SELECT defect_id,comment_date AS entry_date,comment_by AS staff_id,`comment` AS description, img_name AS img_name
                FROM bms_defect_forum WHERE defect_id = $defect_id) a                
                LEFT JOIN bms_staff b ON b.staff_id = a.staff_id
                ORDER BY entry_date";
        $query = $this->db->query($sql);
        return $type == 'cnt' ? $query->num_rows() : $query->result_array();
    }

    function set_defect_update_with_log ($defect_id,$data,$staff_id) {
        $this->db->update('bms_defect', $data, array('defect_id' => $defect_id));
        if(isset($data['defect_status']) && $data['defect_status'] == 'C') {
            $log_data['log_remarks'] = 'updated as Defect is Closed';
        }
        $log_data['defect_id'] = $defect_id;
        $log_data['log_date'] = date("Y-m-d H:i:s");
        $log_data['log_by'] = $staff_id;

        return $this->db->insert('bms_defect_log', $log_data);
    }

    function get_defect_details_for_email ($defect_id) {

        $sql = "SELECT defect_id, a.defect_name, a.defect_location, a.defect_detail, a.defect_close_remarks, b.property_name, b.email_addr, d.owner_name, d.email_addr, d.gender
                FROM bms_defect a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                WHERE a.defect_id=". $defect_id;

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function set_defect_create_log ($defect_id, $staff_id) {

        $log_data['log_remarks'] = 'Defect is created';
        $log_data['defect_id'] = $defect_id;
        $log_data['log_date'] = date("Y-m-d H:i:s");
        $log_data['log_by'] = $staff_id;

        return $this->db->insert('bms_defect_log', $log_data);
    }

    function getDefectLog ($defect_id) {
        $sql = "SELECT a.defect_id, a.entry_date, a.staff_id, a.description, a.img_name, b.first_name,b.last_name 
                FROM
                (SELECT defect_id,log_date AS entry_date,log_by AS staff_id,log_remarks AS description, 'defect_update' AS img_name
                FROM bms_defect_log WHERE defect_id = $defect_id UNION
                SELECT defect_id, comment_date AS entry_date, comment_by AS staff_id, `comment` AS description, img_name AS img_name
                FROM bms_defect_forum WHERE defect_id = $defect_id) a                
                LEFT JOIN bms_staff b ON b.staff_id = a.staff_id
                ORDER BY entry_date";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_defect_with_num_rows_jmb ( $property_id, $defect_status='', $offset = '', $per_page = '',$defect_id='',$sear_txt = '',$sort_by='due_date', $unit_id = "") {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond2 = '';

        if($unit_id != '')
            $cond2 .= ' AND a.unit_id=\''.$unit_id.'\' ';

        if($defect_status != '' && $defect_status != 'all')
            $cond2 .= ' AND a.defect_status=\''.$defect_status.'\' ';

        if($defect_id != '')
            $cond2 .= ' AND a.defect_id=\''.ltrim($defect_id,'0').'\' ';

        if($sear_txt != '')
            $cond2 .= ' AND a.defect_name LIKE \'%'.$sear_txt.'%\' ';

        $sort_by = $sort_by == 'asc' ? ' ORDER BY created_date ASC ' :  ' ORDER BY created_date DESC ';

        if ($limit != '') {
            $sql = "SELECT defect_id, property_name, defect_name, defect_location, a.created_date, defect_status, defect_close_remarks
                    FROM bms_defect a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id
                    WHERE a.property_id = ".$property_id.$cond2 . $sort_by;
            $query = $this->db->query($sql);
            $num_rows = $query->num_rows();
        }

        $sql = "SELECT defect_id, property_name, defect_name, defect_location, a.created_date, defect_status, defect_close_remarks
                    FROM bms_defect a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id
                    WHERE a.property_id = ".$property_id.$cond2. $sort_by.$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if(empty($num_rows)) $num_rows = $query->num_rows();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_defect_developer ($staff_id = '', $offset = '', $per_page = '',$property_id='',$defect_status='',$defect_id='',$sear_txt = '',$sort_by='desc') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond2 = ' AND b.property_status=1';
        if($property_id != '' && $property_id != 'al')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
        if($defect_status != '' && $defect_status != 'all')
            $cond2 .= ' AND a.defect_status=\''.$defect_status.'\' ';
        if($defect_id != '')
            $cond2 .= ' AND a.defect_id=\''.ltrim($defect_id,'0').'\' ';

        if($sear_txt != '')
            $cond2 .= ' AND (a.defect_name LIKE \'%'.$sear_txt.'%\' OR a.defect_location LIKE \'%'.$sear_txt.'%\' OR  a.defect_detail LIKE \'%'.$sear_txt.'%\' ) ';

        $sort_by = $sort_by == 'asc' ? ' ORDER BY created_date ASC ' :  ' ORDER BY created_date DESC ';

        $num_rows = '';

        $sql = "SELECT d.block_name, c.unit_no, a.defect_id, a.defect_name, a.defect_location, a.created_date, a.defect_status, a.property_id, a.defect_id
            FROM bms_defect a 
            LEFT JOIN bms_property b ON b.property_id = a.property_id  
            LEFT JOIN bms_property_units c ON a.unit_id = c.unit_id  
            LEFT JOIN bms_property_block d ON d.block_id = c.block_id  
            WHERE 1=1" . $cond2. $sort_by;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $query = $this->db->query($sql . $limit);
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_defect_details_developer ($defect_id,$property_id) {

        $sql = "SELECT defect_id, defect_name, a.created_date, defect_status, defect_location, 
                a.property_id, b.property_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by,first_name,last_name, defect_close_remarks, g.unit_status_name
                FROM bms_defect a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_staff f ON f.`staff_id` = a.created_by
                LEFT JOIN bms_property_unit_status g ON g.unit_status_id = d.unit_status
                WHERE a.defect_id=". $defect_id." AND 
                a.property_id =".$property_id;

        $query = $this->db->query($sql);
        return $query->row();
    }
}