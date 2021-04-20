<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Expance_item_category_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
   //test
   
    public function selecttest()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('expenseitem_category');
     $this->db->where('keysize > ',0);
    $query=$this->db->get();
    return $query->result();
  }
    
    
     public function selectexp()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('expenseitem_category');
    $query=$this->db->get();
    return $query->result();
  }
   public function insert($data = array()){
     
        $insert = $this->db->insert('expenseitem_category',$data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;    
        }
    }



         public function updatacat($id,$data)
  {
    $this->db->where('excat_id',$id);
    $this->db->update('expenseitem_category',$data);
  }

   public function deletes($services_id) { 
         if ($this->db->delete("expenseitem_category", "excat_id = ".$services_id)) { 
            return true; 
         } 
      }
//subcategory


 public function selectsub()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('expenseitem_subcategory');
    $query=$this->db->get();
    return $query->result();
  }
  
  
   public function insertsub($data = array()){
     
        $insert = $this->db->insert('expenseitem_subcategory',$data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;    
        }
    }



         public function updatasub($id,$data)
  {
    $this->db->where('exsub_id',$id);
    $this->db->update('expenseitem_subcategory',$data);
  }

   public function deletesub($services_id) { 
         if ($this->db->delete("expenseitem_subcategory", "exsub_id = ".$services_id)) { 
            return true; 
         } 
      }
  
}