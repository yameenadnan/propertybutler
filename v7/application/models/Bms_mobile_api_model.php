<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_mobile_api_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function staffActive ($username) {
        $username = $this->db->escape_str($username);        
        $sql = "SELECT staff_id,first_name,last_name,designation_id,email_addr,forgot_pass_key,mobile_no
                FROM bms_staff WHERE emp_type IN (1,2,3) AND email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function jmbActive ($username) {
        $username = $this->db->escape_str($username);
        
        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,b.member_id,a.contact_1
                FROM bms_property_units a, bms_jmb_mc b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1 AND status=1 AND jmb_status = 1
                AND a.email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function ownerActive ($username) {
        $username = $this->db->escape_str($username);       
        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,a.contact_1
                FROM bms_property_units a, bms_property c
                WHERE c.property_id=a.property_id AND c.property_status=1 AND a.email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function residentActive ($username) {
        $username = $this->db->escape_str($username);        
        $sql = "SELECT a.unit_id,b.unit_no,a.tenant_name,b.property_id,a.email_addr,a.forgot_pass_key,a.contact_1
                FROM bms_property_unit_tenants a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=b.property_id AND c.property_status=1
                AND a.email_addr=? 
                AND (a.end_date ='0000-00-00' OR a.end_date >= '".date('Y-m-d')."')";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function vms_ma_usersActive ($username) {
        $username = $this->db->escape_str($username);        
        $sql = "SELECT a.unit_id,b.unit_no,a.ma_user_name,b.property_id,a.ma_user_email,a.ma_user_contact
                FROM bms_property_unit_ma_users a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1
                AND a.ma_user_email=?
                ";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    

}
