<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Receipt_report_models extends CI_Model {
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
  
  
   public function record_rece(){
        return $this->db->count_all("payment");
    }
    public function fetch_rece($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("payment");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
    function getCommonDocs76 () {
        $sql = "SELECT property_id,property_name FROM bms_property  ORDER by property_name ASC";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    
     public function selectpar($get_id)
  {
    $this->load->database();
    $this->db->select('*')
   ->from('play_game')
    ->join('users', 'play_game.user_id = users.user_id') ;
     $this->db->where('play_game.game_id',$get_id);
    $query=$this->db->get();
    //echo "<pre>".$this->db->last_query();
    return $query->result();
  }
   function all(){
        
   
    $sql = "SELECT paydate,receiptno,checkno,totalamount FROM payment where paymode ='cheque'";
        $query = $this->db->query($sql);
        
        return $query->result();
   
    }
    
    function squefeet(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',4);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
    
     function squefeet1(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',5);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
     function squefeet2(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',6);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
     function squefeet3(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',7);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
     function squefeet4(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',8);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
     function squefeet5(){
    
    $this->load->database();
    $this->db->select('square_feet,share_unit');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
   $this->db->where('MONTH(created_date)',9);
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
    
    
   function montha61(){
    
    $this->load->database();
     $this->db->select('unit_no');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_credit');
    $this->db->order_by("UPPER(unit_no)", "DESC");
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   }  
    
    function montha6(){
    
    $this->load->database();
     $this->db->select('*');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_credit');
   $this->db->order_by("UPPER(unit_no)", "DESC");
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
    
    
     function car(){
    
    $this->load->database();
     $this->db->select('amount');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_credit');
   $this->db->where('MONTH(dates)',12);
    $this->db->where('subcategory','Car Park');
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
   
    }
   
    
     function tota(){
        
   
    $sql = "SELECT  SUM(totalamount) AS totalamount FROM payment where paymode ='cheque'";
    $query = $this->db->query($sql);
        
        return $query->result();
   
    }
    
    function customer(){
        
    $this->load->database();
    $this->db->select('unit_no,address_1,email_addr,status,owner_name');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_property_units');
    $this->db->limit('200');
    $query=$this->db->get();
    return $query->result();
    }
 function audit() {
        
    $this->load->database();
    $this->db->select('*');
   //  $this->db->order_by('id', 'DESC'); 
    $this->db->from('bms_credit_notes');
   // $this->db->limit('5');
    $query=$this->db->get();
    return $query->result();
    }
}