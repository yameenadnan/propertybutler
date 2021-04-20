<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Meter_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function auth ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT staff_id,first_name,last_name,designation_id,email_addr,forgot_pass_key 
                FROM bms_staff WHERE emp_type IN (1,2,3) AND email_addr=? AND password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();         
    } 
    
    function getMyProperties ($staff_id ='') {  
        $staff_id = $staff_id == '' ? $_SESSION['bms']['staff_id'] : $staff_id;
        $sql = "SELECT property_id,property_name,property_type,total_units 
                FROM bms_property WHERE property_status=1 AND property_id IN 
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }	
    
   function getCommonDocs76 () {
        $sql = "SELECT property_id,property_name FROM bms_property  ORDER by property_name ASC";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
 
  public function record_meter() {
        return $this->db->count_all("bms_meter_reading");
    }
    public function fetch_meter($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_meter_reading");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
 
   function insert_meter($data){
       
        $this->db->insert('bms_meter_reading', $data);
		 //redirect('test/index.php');
               
    }   
      
    
    function getCommonDocs () {
        $sql = "SELECT unit_id,unit_no FROM bms_property_units  ORDER by unit_id;";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    } 
   public function show()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_meter_reading');
   
    $query=$this->db->get();
    return $query->result();
  }	 
    
}