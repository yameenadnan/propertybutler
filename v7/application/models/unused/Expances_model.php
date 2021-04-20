<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Expances_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
   
    
    
     public function selectexp()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('expenses');
    $query=$this->db->get();
    return $query->result();
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
  
    public function exp_id()
  {
        $sql = "SELECT max(exp_id) FROM  expenses";
        $query = $this->db->query($sql);
        
        return $query->result_array();
  }
  
  
   public function record_exp() {
        return $this->db->count_all("expenses");
    }
    public function fetch_exp($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("expenses");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }  
   
  public function insertmore($data = array()){
    $insert = $this->db->insert_batch('bms_property_expance',$data);
    return $insert?true:false;
  }
function getCommonDocs76 () {
        $sql = "SELECT property_id,property_name FROM bms_property  ORDER by property_name ASC;";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    
    //view expance detail
    
         public function selectexpdetail($receiptno)
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_property_expance');
      $this->db->where('invoice_id',$receiptno);
    $query=$this->db->get();
    return $query->result();
  }
  
public function getdata($receiptno)
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('expenses');
      $this->db->where('invoice_no',$receiptno);
    $query=$this->db->get();
    return $query->result();
  }

}