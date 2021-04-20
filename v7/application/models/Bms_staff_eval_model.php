<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_staff_eval_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getPropertyStaffsForJmbEval ($property_id,$jmb_id,$staff_eval_ids) {
        
        $sql = "SELECT staff_id,first_name,last_name,desi_name
                FROM bms_staff
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1) 
                AND designation_id IN (".$staff_eval_ids.")
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")
                AND staff_id NOT IN(
                SELECT staff_id from bms_staff_eval_jmb WHERE property_id =".$property_id." AND jmb_id= ".$jmb_id." AND DATE_FORMAT(bms_staff_eval_jmb.eval_date, '%Y-%m')='".date('Y-m')."')";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyStaffsForAmEval ($property_id,$am_id,$staff_eval_ids) {
        
        $sql = "SELECT staff_id,first_name,last_name,desi_name
                FROM bms_staff
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1) 
                AND designation_id IN (".$staff_eval_ids.")
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")
                AND staff_id NOT IN(
                SELECT staff_id from bms_staff_eval_am WHERE property_id =".$property_id." AND am_staff_id= ".$am_id." AND DATE_FORMAT(bms_staff_eval_am.eval_date, '%Y-%m')='".date('Y-m')."')";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyStaffsForHrEval ($property_id,$hr_id,$staff_eval_ids) {
        
        $sql = "SELECT staff_id,first_name,last_name,desi_name
                FROM bms_staff
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1) 
                AND designation_id IN (".$staff_eval_ids.")
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")
                AND staff_id NOT IN(
                SELECT staff_id from bms_staff_eval_hr WHERE property_id =".$property_id." AND DATE_FORMAT(bms_staff_eval_hr.eval_date, '%Y-%m')='".date('Y-m')."')";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function setStaffEval ($data,$for) {
        $this->db->insert('bms_staff_eval_'.$for, $data);                 
    }
    
    function getStaffEvalJmb ($staff_id,$property_id,$ayear,$amonth) {
        $sql = "SELECT proactive,proactive_remarks,communication,communication_remarks,attitude,attitude_remarks,initiative,initiative_remarks,
                resposibility,resposibility_remarks,courtesy,courtesy_remarks,addi_remarks,eval_date
                FROM bms_staff_eval_jmb
                WHERE staff_id=$staff_id AND property_id=$property_id AND award_year=$ayear AND award_month=$amonth";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getStaffEvalAm ($staff_id,$property_id,$ayear,$amonth) {
        $sql = "SELECT teamwork,teamwork_remarks,guest_relation,guest_relation_remarks,attitude,attitude_remarks,dependability,dependability_remarks,
                resposibility,resposibility_remarks,courtesy,courtesy_remarks,proj_knowledge,proj_knowledge_remarks,
                billing_collec,billing_collec_remarks,addi_remarks,eval_date
                FROM bms_staff_eval_am
                WHERE staff_id=$staff_id AND property_id=$property_id AND award_year=$ayear AND award_month=$amonth";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getStaffEvalHr ($staff_id,$property_id,$ayear,$amonth) {
        $sql = "SELECT punctuality,punctuality_remarks,attendance,attendance_remarks,discipline,discipline_remarks,communication,communication_remarks,
                attitude,attitude_remarks,grooming,grooming_remarks,addi_remarks,eval_date
                FROM bms_staff_eval_hr
                WHERE staff_id=$staff_id AND property_id=$property_id AND award_year=$ayear AND award_month=$amonth";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getAwardedStaff ($award_cat,$award_year,$award_month) {
        $sql = "SELECT a.staff_id,first_name,last_name,desi_name,d.property_name,d.property_id,award_year,award_month,
                jmb_percentage,am_percentage,hr_percentage,total_percentage,awarded
                FROM bms_staff_awarded a
                LEFT JOIN bms_staff b ON a.staff_id=b.staff_id
                LEFT JOIN bms_designation c ON c.desi_id=b.designation_id
                LEFT JOIN bms_property d ON d.property_id=a.property_id
                WHERE awarded_cat=".$award_cat." AND award_year=".$award_year." AND award_month=".$award_month." 
                ORDER BY awarded DESC,total_percentage DESC";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getStaffForAward ($award_cat_desi_ids,$award_year,$award_month) {
        $sql = "SELECT p.`property_name`,p.property_id,a.staff_id,first_name,last_name,desi_name,e.award_year,e.award_month,                
                IFNULL(SUM(c.proactive),0) AS proactive_jmb,IFNULL(SUM(c.communication),0) AS communication_jmb,
                IFNULL(SUM(c.attitude),0) AS attitude_jmb,IFNULL(SUM(c.initiative),0) AS initiative_jmb,
                IFNULL(SUM(c.resposibility),0) AS resposibility_jmb,IFNULL(SUM(c.courtesy),0) AS courtesy_jmb,
                IFNULL(d.teamwork,0) AS teamwork_am,IFNULL(d.guest_relation,0) AS guest_relation_am,IFNULL(d.attitude,0) AS attitude_am,
                IFNULL(d.dependability,0) AS dependability_am,IFNULL(d.resposibility,0) AS resposibility_am,
                IFNULL(d.courtesy,0) AS courtesy_am,IFNULL(d.proj_knowledge,0) AS proj_knowledge_am,IFNULL(d.billing_collec,0) AS billing_collec_am,
                IFNULL(e.punctuality,0) AS punctuality_hr,IFNULL(e.attendance,0) AS attendance_hr,IFNULL(e.discipline,0) AS discipline_hr,
                IFNULL(e.communication,0) AS communication_hr,IFNULL(e.attitude,0) AS attitude_hr,IFNULL(e.grooming,0) AS grooming_hr,
                (SELECT COUNT(jmb_id) FROM  bms_staff_eval_jmb h WHERE a.staff_id=h.staff_id AND h.property_id=q.property_id) AS cast_jmb,                
                (SELECT COUNT(member_id) FROM  bms_jmb_mc f WHERE property_id=q.property_id) AS tot_jmb
                FROM bms_staff a,bms_staff_property q, bms_property p,bms_designation b,bms_staff_eval_jmb c,bms_staff_eval_am d,bms_staff_eval_hr e
                WHERE a.staff_id=q.staff_id AND p.`property_id`=q.`property_id` AND b.desi_id=a.designation_id AND a.emp_type IN (1) 
                AND c.staff_id=q.staff_id AND c.property_id =q.`property_id` AND c.award_year=".$award_year." AND c.award_month=".$award_month."
                AND d.staff_id=q.staff_id AND d.property_id =q.`property_id` AND d.award_year=".$award_year." AND d.award_month=".$award_month."
                AND e.staff_id=q.staff_id AND e.property_id =q.`property_id` AND e.award_year=".$award_year." AND e.award_month=".$award_month."
                AND emp_type IN (1) 
                AND designation_id IN (".$award_cat_desi_ids.")
                AND a.staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                GROUP BY q.`property_id`,q.staff_id";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
                
    }
    
    /*function getStaffForAwarded ($award_cat,$award_year,$award_month) {
        
            
    }*/ 
    
    function setAwardedStaff ($data) {
        $data['awarded_by'] = $_SESSION['bms']['staff_id'];
        $data['awarded_date'] = date("Y-m-d");
        $this->db->insert('bms_staff_awarded', $data);                 
    }       
    
}