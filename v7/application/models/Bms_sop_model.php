<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_sop_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    
    function get_sop_main ( $offset = '', $per_page = '',$property_id='',$assignment_id = '') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond2 = '';
        if($property_id != '' && $property_id != 'All')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
            
        if($assignment_id != '')
            $cond2 .= ' AND a.sop_id='.$assignment_id.' ';
            
        $sql = "SELECT a.sop_id, a.created_date, m.property_id, m.property_name,desi_name,a.assign_to,
                GROUP_CONCAT(a.sop_name) AS sop_name
                FROM bms_sop AS a
                LEFT JOIN bms_property AS m ON m.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to                   
                WHERE  a.property_id IN (SELECT property_id from bms_staff_property WHERE staff_id =".$_SESSION['bms']['staff_id'].") ".$cond2 ." 
                GROUP BY m.property_id,a.assign_to ORDER BY m.property_name";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_entry_list ( $offset = '', $per_page = '',$property_id='',$desi_id ='',$staff_id = '') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond2 = '';
        if($property_id != '' && $property_id != 'All')
            $cond2 .= ' AND a.property_id='.$property_id.' ';            
        
            
        $sql = "SELECT a.sop_id, a.created_date, a.start_date,a.due_date, a.no_due_date, m.property_id, m.property_name,desi_name,a.sop_name AS sop_name,
                a.assign_to, a.mon,a.tue,a.wed,a.thu,a.fri,a.sat,a.sun, '1' AS  entry_req
                FROM bms_sop AS a
                LEFT JOIN bms_property AS m ON m.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to                   
                WHERE a.assign_to=". $desi_id ." 
                AND a.property_id IN (SELECT property_id from bms_staff_property WHERE staff_id =".$staff_id.") ".$cond2 ." 
                AND sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '".date('Y-m-d')."')
                AND execute_time <= '".date('H:i:s')."'
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
                ORDER BY m.property_name,sop_name";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_sop_main_jmb_mc ( $offset = '', $per_page = '',$property_id='',$assignment_id = '') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond2 = '';
        if($property_id != '' && $property_id != 'All')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
            
        if($assignment_id != '')
            $cond2 .= ' AND a.sop_id='.$assignment_id.' ';
            
        $sql = "SELECT a.sop_id, a.created_date, m.property_id, m.property_name,desi_name,a.assign_to,
                GROUP_CONCAT(a.sop_name) AS sop_name
                FROM bms_sop AS a
                LEFT JOIN bms_property AS m ON m.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to                   
                WHERE 1=1  ".$cond2 ." 
                GROUP BY m.property_id,a.assign_to ORDER BY m.property_name";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_main_os ( $offset = '', $per_page = '',$property_id='',$assignment_id='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond2 = '';
        if($property_id != '' && $property_id != 'All')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
        if($assignment_id != '')
            $cond2 .= ' AND a.sop_id='.$assignment_id.' ';
        $sql = "SELECT a.sop_id, a.AssignmentTitle, a.AssignDate,m.PropertyName,desi_name,
                GROUP_CONCAT(b.SopName) AS sop_name
                FROM sop_assignmnt AS a
                LEFT JOIN bms_property AS m ON m.property_id = a.property_id
                LEFT JOIN sop_assignmnt_procedure AS b ON b.sop_id = a.sop_id
                LEFT JOIN designationmaster c ON c.designationId = a.assignTo                 
                WHERE a.sop_id IN (SELECT sop_id from sop_overseeing_designation where designation =".$_SESSION['bms']['designation_id'].") 
                ".$cond2."
                GROUP BY a.sop_id";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_overseeing_desi ($sop_id) {
        //->join('designationmaster', 'designationmaster.designationId = sop_assignmnt_subprocedure.assignTo', 'LEFT')
        $sql = "SELECT GROUP_CONCAT(designationName SEPARATOR ', ') as designationName
                FROM sop_overseeing_designation a
                LEFT JOIN designationmaster c ON c.designationId = a.designation                 
                WHERE sop_id=".$sop_id."
                GROUP BY sop_id";
        $query = $this->db->query($sql);        
        return $query->result_array();      
    } 
    function get_sop_overseeing_jmb ($sop_id) {
        //->join('designationmaster', 'designationmaster.designationId = sop_assignmnt_subprocedure.assignTo', 'LEFT')
        $sql = "SELECT GROUP_CONCAT(JMB SEPARATOR ', ') as jmb FROM sop_overseeing_jmb 
                WHERE sop_id=".$sop_id."
                GROUP BY sop_id";
        $query = $this->db->query($sql);        
        return $query->result_array();      
    }             
    
    function get_sop ($property_id,$assign_to) {
        $query = $this->db->select('sop_id, property_id,assign_to, sop_name, start_date, due_date, no_due_date, condition_req, reading_req,
                                    sun, mon, tue, wed, thu, fri, sat, execute_time, due_by, reminder, repeat_rem')
                          ->order_by('sop_name','ASC')     
                          ->get_where('bms_sop',array('property_id'=>$property_id,'assign_to'=>$assign_to));
        return $query->result_array();
    }
    
    function get_sop_by_id ($sop_id) {
        $query = $this->db->select('sop_id,a.sop_name, a.property_id,b.property_name,c.desi_name,assign_to, sop_name, start_date, 
                                    due_date, no_due_date, condition_req, reading_req,task_schedule,exclude_print,
                                    sun, mon, tue, wed, thu, fri, sat, execute_time, due_by, reminder, repeat_rem')
                          ->join('bms_property b','b.property_id=a.property_id','left') 
                          ->join('bms_designation c','c.desi_id=a.assign_to','left') 
                          ->get_where('bms_sop a',array('a.sop_id'=>$sop_id));
        return $query->result_array();
    }
    
    function get_sop_details ($property_id,$assign_to) {
        $query = $this->db->select('sop_id,a.sop_name, a.property_id,b.property_name,c.desi_name,assign_to, sop_name, start_date, 
                                    due_date, no_due_date, condition_req, reading_req,task_schedule,exclude_print,
                                    sun, mon, tue, wed, thu, fri, sat, execute_time, due_by, reminder, repeat_rem')
                          ->join('bms_property b','b.property_id=a.property_id','left') 
                          ->join('bms_designation c','c.desi_id=a.assign_to','left') 
                          ->get_where('bms_sop a',array('a.property_id'=>$property_id,'assign_to'=>$assign_to));
        
        return $query->result_array();
    }
    
    function get_subsop ($sop_id) {
        $query = $this->db->select('sop_sub_id, sop_id, sub_sop_name, condition_req, reading_req')
                      ->order_by('sub_sop_name','ASC')
                      ->get_where('bms_sop_sub',array('sop_id'=>$sop_id));
        return $query->result_array();
        //echo "<br />".$this->db->last_query();        
    }
    
    /*function sop_main_insert ($data) {
        $this->db->insert('sop_assignmnt', $data);
        return $insert_id = $this->db->insert_id();//1316;//
        //return $query->result_array();         
    } 
    
    
    function sop_ov_desi_insert ($data) {
        $this->db->insert_batch('sop_overseeing_designation', $data);
        //echo "<br />".$this->db->last_query();
    }
    
    function sop_ov_jmb_insert ($data) {
        $this->db->insert_batch('sop_overseeing_jmb', $data);
        //echo "<br />".$this->db->last_query();
    }*/
    
    function sop_insert ($data) {
        $this->db->insert('bms_sop', $data);
        $insert_id = $this->db->insert_id(); 
        //echo "<br />".$this->db->last_query();
        return $insert_id;           
    }
    
    function sop_update ($data) {
        $this->db->update('bms_sop', $data,array('sop_id' => $data['sop_id'])); 
        //echo "<br />".$this->db->last_query();       
        //return $insert_id;           
    }
    
    function sub_sop_insert ($data) {
        $this->db->insert('bms_sop_sub', $data);
    } 
    
    function sub_sop_update ($data) {
        $this->db->update('bms_sop_sub', $data,array('sop_sub_id' => $data['sop_sub_id']));        
        //$this->db->insert('bms_sop_sub', $data);
    } 
    
    function set_sop_entry ($data) {
        $this->db->insert('bms_sop_entry', $data);
        $insert_id = $this->db->insert_id(); 
        //echo "<br />".$this->db->last_query();
        return $insert_id;           
    }
    
    function set_sop_entry_image_name ($data) {
        $this->db->insert('bms_sop_entry_img', $data);                 
    } 
    
    function set_sop_sub_entry ($data) {
        $this->db->insert('bms_sop_sub_entry', $data);
        $insert_id = $this->db->insert_id(); 
        //echo "<br />".$this->db->last_query();
        return $insert_id;           
    }
    
    function set_sop_sub_entry_image_name ($data) {
        $this->db->insert('bms_sop_sub_entry_img', $data);                 
    }
    
    // For SOP History 
    
    function getSopTitle ( $property_id) {
            
        $sql = "SELECT desi_name,a.assign_to, GROUP_CONCAT(a.sop_name) AS sop_name
                FROM bms_sop AS a
                LEFT JOIN bms_property AS m ON m.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to                   
                WHERE a.property_id='".$property_id."' AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id =".$_SESSION['bms']['staff_id'].")  
                GROUP BY m.property_id,a.assign_to ORDER BY m.property_name";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_entry ($sop_id,$start_date,$end_date) {
        $sql = "SELECT id,sop_id,requirement_type,requirement_val, remarks, entry_date,entered_by,first_name,last_name
                FROM bms_sop_entry AS a
                LEFT JOIN bms_staff AS b ON b.staff_id = a.entered_by 
                WHERE a.sop_id='".$sop_id."' AND (DATE_FORMAT(a.entry_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' )  
                ORDER BY a.id DESC";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_sub_entry ($sop_sub_id,$start_date,$end_date) {
        $sql = "SELECT id,sop_sub_id,requirement_type,requirement_val, remarks, entry_date,entered_by,first_name,last_name
                FROM bms_sop_sub_entry AS a
                LEFT JOIN bms_staff AS b ON b.staff_id = a.entered_by 
                WHERE a.sop_sub_id='".$sop_sub_id."' AND (DATE_FORMAT(a.entry_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' )  
                ORDER BY a.id DESC";       
        //$sql = "SELECT sop_id,PropertyName,AssignmentNamme,DueDate,Assign_date,TaskStatus FROM assignmentdetails a LEFT JOIN propertysetup b ON b.property_id = a.property_id  WHERE a.AssignTo". $cond . $_SESSION['bms']['designation_id']." ".$cond2." AND a.property_id IN (SELECT property_id FROM staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") ORDER BY TaskStatus DESC, DueDate ASC".$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_sop_sub_entry_all ($sop_sub_id) {
        $sql = "SELECT id,sop_sub_id,requirement_type,requirement_val, remarks, entry_date,entered_by,first_name,last_name
                FROM bms_sop_sub_entry AS a
                LEFT JOIN bms_staff AS b ON b.staff_id = a.entered_by 
                WHERE a.sop_sub_id='".$sop_sub_id."'";       
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function delete_sub_sop ($sop_sub_id) {
        $this->db->delete('bms_sop_sub', array('sop_sub_id' => $sop_sub_id));
    }
    
    function delete_sub_sop_by_sop_id ($sop_id) {
        $this->db->delete('bms_sop_sub', array('sop_id' => $sop_id));
    }
    
    function delete_sop ($sop_id) {
        $this->db->delete('bms_sop', array('sop_id' => $sop_id));
    }
    
    function get_sop_entry_img ($id) {
        $query = $this->db->select('sop_entry_id,img_name')
                 ->get_where('bms_sop_entry_img',array('sop_entry_id'=>$id));
        return $query->result_array();
        //echo "<br />".$this->db->last_query();        
    }
    
    function get_sop_sub_entry_img ($id) {
        $query = $this->db->select('sop_sub_entry_id,img_name')
                 ->get_where('bms_sop_sub_entry_img',array('sop_sub_entry_id'=>$id));
        return $query->result_array();
        //echo "<br />".$this->db->last_query();        
    }
    
    function get_sops_for_copy ($from_property,$designation_ids) {
        
        $sql = "select * from bms_sop where property_id=$from_property AND assign_to IN (".implode(',',$designation_ids).")";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
        
    } 
    
    function set_sops_for_copy ($data) {
        $this->db->insert('bms_sop', $data);
        $insert_id = $this->db->insert_id(); 
        //echo "<br />".$this->db->last_query();
        return $insert_id;           
    }
    
}