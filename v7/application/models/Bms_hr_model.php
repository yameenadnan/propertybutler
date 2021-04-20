<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_hr_model extends CI_Model {
    function __construct () { parent::__construct(); }
        
    function get_staff_list ($offset = '0', $per_page = '25',$search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.first_name) LIKE '%".$search_txt."%' OR LOWER(a.last_name) LIKE '%".$search_txt."%' OR LOWER(a.email_addr) LIKE '%".$search_txt."%' OR LOWER(b.desi_name) LIKE '%".$search_txt."%' OR LOWER(c.emp_type_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT staff_id,first_name,last_name,email_addr,desi_name,emp_type_name 
                FROM bms_staff a 
                LEFT JOIN bms_designation b ON b.desi_id = a.designation_id  
                LEFT JOIN bms_emp_type c ON c.emp_type_id = a.emp_type
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY first_name, last_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }   
    
    function get_staff_details ($staff_id) {
        $sql = "SELECT staff_id,first_name,last_name,dob,email_addr,password,a.emp_type,designation_id,mobile_no
                FROM bms_staff a                
                WHERE staff_id=". $staff_id ;
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_staff_bms_leave_entitlement ($staff_id) {
        $sql = "SELECT staff_id,al,ml,pl,cpl,mc,mgl
                FROM bms_leave_entitlement a                
                WHERE staff_id=". $staff_id ;
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_staff_duty ($staff_id) {
        $sql = "SELECT staff_id,monday,tuesday,wednessday,thrusday,friday,saturday,sunday
                FROM bms_staff_duty a                
                WHERE staff_id=". $staff_id ;
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_staff_property ($staff_id) {
        $sql = "SELECT GROUP_CONCAT(property_id) AS property_id
                FROM bms_staff_property a                
                WHERE staff_id=". $staff_id ."
                GROUP BY staff_id";
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_staff_module ($staff_id) {
        $sql = "SELECT GROUP_CONCAT(module_id) AS module_id
                FROM bms_staff_module a                
                WHERE staff_id=". $staff_id ."
                GROUP BY staff_id";
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function check_email($email_addr, $staff_id) {
        $cond = $staff_id ? ' AND staff_id <>'.$staff_id : '';
        $sql = "SELECT staff_id,first_name,last_name,designation_id,email_addr,forgot_pass_key 
                FROM bms_staff WHERE email_addr=? ".$cond;
        $query = $this->db->query($sql,array($email_addr));
        //echo $this->db->last_query();exit;
        return $query->result_array();   
    }
    
    function insert_staff ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_staff', $data);
        return $this->db->insert_id();           
    } 
    
    function update_staff ($data,$staff_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_staff', $data, array('staff_id' => $staff_id));       
    } 
    
    function insert_staff_duty ($data) {
        //$data['staff_id'] = $staff_id;
        $this->db->insert('bms_staff_duty', $data);          
    } 
    
    function update_staff_duty ($data,$staff_id) {
        return $this->db->update('bms_staff_duty', $data, array('staff_id' => $staff_id));       
    } 
    
    function insert_staff_leave_ent ($data) {
        //$data['staff_id'] = $staff_id;
        $this->db->insert('bms_leave_entitlement', $data);          
    } 
    
    function update_staff_leave_ent ($data,$staff_id) {
        return $this->db->update('bms_leave_entitlement', $data, array('staff_id' => $staff_id));
        //echo $this->db->last_query();       
    } 
    
    
    function delete_staff_property ($staff_id) {
        $this->db->delete('bms_staff_property',array('staff_id'=>$staff_id));
    }
    
    function delete_staff_module ($staff_id) {
        $this->db->delete('bms_staff_module',array('staff_id'=>$staff_id));
    }
    
    function insert_staff_property ($data) {
        $this->db->insert('bms_staff_property',$data);
    }
    
    function insert_staff_module ($data) {
        $this->db->insert('bms_staff_module',$data);
        //echo "<br />".$this->db->last_query();   
    }
    
    function insert_notice_board ($notice_id,$message) {
        $data['message'] = $message;
        $this->db->update('bms_notice_board', $data, array('notice_id' => $notice_id));        
    } 
    
    function getStates () {
        $query = $this->db->select('state_id,state_name')->order_by('state_name')->get('bms_state');
        return $query->result_array();
    }
    
    function get_holiday_list ($offset = '0', $per_page = '25',$state_id = '', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        if($state_id != '') {
            $cond .= " AND a.state_id IN (10,".$state_id.")";
        }
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.description) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT holiday_id,a.`state_id`,state_name,DATE_FORMAT(`date`, '%d-%m-%Y') as `date`,description                 
                FROM bms_holiday a 
                LEFT JOIN  bms_state b ON b.`state_id` = a.`state_id`
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `date` DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    } 
    
    function get_holiday_details ($holiday_id) {
        $sql = "SELECT holiday_id,a.`state_id`,DATE_FORMAT(`date`, '%d-%m-%Y') as `date`,description                 
                FROM bms_holiday a                
                WHERE holiday_id= ". $holiday_id ;
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insert_holiday ($data) {        
        $this->db->insert('bms_holiday', $data);                   
    } 
    
    function update_holiday ($data,$holiday_id) {        
        $this->db->update('bms_holiday', $data, array('holiday_id' => $holiday_id));       
    }  
    
    function get_designation_list ($offset = '0', $per_page = '25', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.desi_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT desi_id,desi_name, sort_order                
                FROM bms_designation a                 
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `desi_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    } 
    
    function get_designation_details ($desi_id) {
        $sql = "SELECT desi_id,desi_name,sort_order                 
                FROM bms_designation a                
                WHERE desi_id= ". $desi_id ;
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insert_designation ($data) {        
        $this->db->insert('bms_designation', $data);                   
    } 
    
    function update_designation ($data,$desi_id) {        
        $this->db->update('bms_designation', $data, array('desi_id' => $desi_id));       
    }
}