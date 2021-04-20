<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bills_model extends CI_Model {
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
 function insert_open($data){
       
        $this->db->insert('bms_property_openingbalance', $data);
		 //redirect('test/index.php');
               
    } 
    
     public function selectunit($fdate,$sdate,$unitno)
    { 
     
      $this->load->database();
      $this->db->select('*');
    // $this->db->select('description, sum( amount ) as amount ');
     // SELECT description, sum( amount ) as amount FROM`student` GROUP BY description
      $this->db->from('bms_property_credit');
    if($unitno){$this->db->where('unit_no',$unitno);}
    if($fdate){$this->db->where('dates >',$fdate);}
    if($sdate){$this->db->where('dates <',$sdate);}
     
     // $this->db->order_by('game_id', 'DESC');
      $query=$this->db->get();
      return $query->result();
    }
     function selectunitname($unitno){
        $sql = "SELECT unit_no,address_1,owner_name FROM`bms_property_units` where unit_id='$unitno'";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
      public function open()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_property_openingbalance');
   
    $query=$this->db->get();
    return $query->result();
  }	
    
     function water($search_txt) {
        $cond = '';
        if($search_txt != '') {
            $search_txt = strtolower($this->db->escape_str($search_txt));
            $cond = " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.type) LIKE '%".$search_txt."%')";
        }
        $sql = "SELECT id,unit_no,meter_no,date,type,current_reading,previous_reading
                FROM bms_meter_reading AS a
                WHERE type='Water' $cond
                ORDER BY unit_no";
        $query = $this->db->query($sql);
       // print_r($query);exit;
        return $query->result_array();
    } 
    function ten($date1,$date2) {
        
   
    
    
    $this->load->database();
    $this->db->select('*');
    
    $this->db->where('created_date >=',$date1);
    $this->db->where('created_date <=',$date2);
    $this->db->from('bms_property_units');
   
    $query=$this->db->get();
    return $query->result();
    }
    
    
    function unitee($unit) {
        
   
    
    
    $this->load->database();
    $this->db->select('*');
    $this->db->where('unit_no',$unit);
   
    $this->db->from('bms_property_units');
   
    $query=$this->db->get();
    return $query->result();
    }
    
    
    function all() {
        
   
    
    
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_property_units');
   
    $query=$this->db->get();
    return $query->result();
    }
     public function record_open() {
        return $this->db->count_all("bms_property_openingbalance");
    }
    public function fetch_open($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_property_openingbalance");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
     public function record_debit() {
        return $this->db->count_all("bms_property_debit");
    }
    public function fetch_debit($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_property_debit");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
     public function record_count1() {
        return $this->db->count_all("bms_credit_notes");
    }
    
	
	
    // Fetch data according to per_page limit.
    public function fetch_data($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_credit_notes");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
    public function record_charge() {
        return $this->db->count_all("charge_code");
    }
    public function fetch_charge($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("charge_code");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   } 
 
  
  	 function insert_charge($data){
       
        $this->db->insert('charge_code', $data);
		 //redirect('test/index.php');
               
    } 
   
    public function updatacharge($id,$data)
  {
    $this->db->where('id',$id);
    $this->db->update('charge_code',$data);
  }
   
   	 function delete_charge($id)
  {

      $this->db->where('id', $id);
      $this->db->delete('charge_code'); 
  }
   
    function getCommonDocs () {
        $sql = "SELECT property_id,property_name FROM bms_property  ORDER by property_id;";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }  
  /* function getCommonDocs21 ($search_txt) {
        $cond = '';
        if($search_txt != '') {
            $search_txt = strtolower($this->db->escape_str($search_txt));
            $cond = " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.description) LIKE '%".$search_txt."%')";
        }
        $sql = "SELECT id,unit_no,description,tan_date,amount,tex_amount
                FROM bms_credit_notes AS a
                WHERE 1=1 $cond
                ORDER BY unit_no";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }*/
 function getCommonDocs33($search_txt) {
        $cond = '';
        if($search_txt != '') {
            $search_txt = strtolower($this->db->escape_str($search_txt));
            $cond = " AND (LOWER(a.unit_number) LIKE '%".$search_txt."%' OR LOWER(a.name) LIKE '%".$search_txt."%')";
        }
        $sql = "SELECT id,unit_number,name,total_due
                FROM bms_property_debit AS a
                WHERE 1=1 $cond
                ORDER BY unit_number";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    } 

  public function debit_dow()
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_property_debit');
   
    $query=$this->db->get();
    return $query->result();
  }	

	 function insert_unit ($data){
       
        $this->db->insert('bms_property_openingbalance', $data);
		 //redirect('test/index.php');
               
    } 
	 function receipt_select($data){
		// print_r($data['unit_no']);exit;
	 $sql1 = "SELECT monthly_billing FROM bms_property_units  WHERE unit_no='".$data['unit_no']."'";
	 $query = $this->db->query($sql1);
	    $nam = $query->row_array();
	 
	return $nam['monthly_billing'];
		 //redirect('test/index.php');
               
    } 
	
	 function receipt_insert($data){
      // print_r($data);exit;
	    $this->db->insert('bms_property_credit',$data);
		 //redirect('test/index.php');
               
    } 
	
	 function sel(){
      // print_r($data);exit;
	   $sql1 = "SELECT count(*) FROM bms_property_credit";
	   $query = $this->db->query($sql1);
	    $nam = $query->row_array();
	// print_r($nam);exit;
	   return $nam['count(*)'];
       // $this->db->insert('bms_property_credit',$data);
		 //redirect('test/index.php');
               
    } 
	
	 function receipt_insertr($data1){
      // print_r($data);exit;
        $this->db->insert('bms_saving_amount',$data1);
		 //redirect('test/index.php');
               
    } 
	
	
	
		public function record_count() {
        return $this->db->count_all("bms_property_credit");
    }

    public function fetch_countries($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_property_credit");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
		public function collection_count() {
        return $this->db->count_all("bms_property_credit");
    }

    public function fetch_collection($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("bms_property_credit");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
	
	 function insert_reminders($data){
       
        $this->db->insert('bms_set_reminders', $data);
		 //redirect('test/index.php');
               
    } 
	 function insert_credit($data){
       
        $this->db->insert('bms_credit_notes', $data);
		 //redirect('test/index.php');
               
    } 
	
	 function insert_debit($data){
       
        $this->db->insert('bms_property_debit', $data);
		 //redirect('test/index.php');
               
    } 
	
	
	 function insert_paragraph($data){
       
        $this->db->insert('reminders_text', $data);
		 //redirect('test/index.php');
               
    } 
	
	  public function dow_remind($id)
  {
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_set_reminders');
    $this->db->where('id',$id);
    $query=$this->db->get();
    return $query->result();
  }
	
	 function row_delete($id)
  {

      $this->db->where('id', $id);
      $this->db->delete('bms_set_reminders'); 
  }
	
	 public function remind()
  { //$a = 1;
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_set_reminders');
   
     
    $query=$this->db->get();
    return $query->result();
  }
	
	 public function selectdata()
  { //$a = 1;
    $this->load->database();
    $this->db->select('*');
    $this->db->from('bms_property_credit');
   
     
    $query=$this->db->get();
    return $query->result();
  }

	
	
	
	 function getamount($search_txt){
        $cond = '';
        if($search_txt != '') {
            $search_txt = strtolower($this->db->escape_str($search_txt));
            $cond = " AND (LOWER(a.account_name) LIKE '%".$search_txt."%' OR LOWER(a.account_type) LIKE '%".$search_txt."%')";
        }
        $sql = "SELECT sum(debit) FROM bms_property_openingbalance";
        $query = $this->db->query($sql);
        
        return $query->result_array();
		
		
		
		
    } 
	
    function auth_jmb ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.unit_id,owner_name,a.property_id,a.email_addr,forgot_pass_key,b.member_id 
                FROM bms_property_units a, bms_jmb_mc b, bms_property c                
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1 AND status=1 AND a.email_addr=? AND a.password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();         
    } 
    
    function update_pass ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_staff SET password=? WHERE emp_type IN (1,2,3) AND email_addr=?";
        return $query = $this->db->query($sql,array($pass,$username));                  
    } 
    
    function update_pass_jmb ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_property_units SET password=? WHERE status=1 AND email_addr=?";
        return $query = $this->db->query($sql,array($pass,$username));                  
    } 
    
    function get_access_module ($staff_id) {
        $sql = "SELECT GROUP_CONCAT(module_id) AS module_id FROM bms_staff_module a                
                WHERE staff_id=". $staff_id ." GROUP BY staff_id";        
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function getProperties () {                
        $query = $this->db->select('property_id,property_name,property_type')->where('property_status',1)->order_by('property_name','ASC')->get('bms_property');
        /*$sql = "SELECT PropertyId,PropertyName,PropertyType FROM propertysetup WHERE 1=1 ORDER BY PropertyName ASC";
        $query = $this->db->query($sql);*/        
        return $query->result_array();
    }
    
  
    
   
    
    function getPropertyInfo ($property_id) {  
        
        $sql = "SELECT property_id,property_name,property_type,total_units,email_addr,monthly_billing,a.state_id,b.state_name,
                jmb_mc_name,address_1,address_2,phone_no,fax,pin_code
                FROM bms_property a
                LEFT JOIN bms_state b ON b.state_id=a.state_id 
                WHERE property_status=1 AND property_id = '".$property_id."' ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function getPropertyMonthlyCollec($property_id) {  
        
        $sql = "SELECT monthly_billing AS amt
                FROM bms_property WHERE property_status=1 AND property_id = '".$property_id."'";
        $query = $this->db->query($sql);
        $res = $query->row_array();
        return $res['amt'];
    }
    
    
    function getMyPropertiesWitTypeState ($search_txt = '') { 
        
        $cond = $search_txt != '' ?  " AND LOWER(property_name) LIKE '%".strtolower($search_txt)."%' " : '';
        
        $sql = "SELECT property_id,property_name,total_units,email_addr,type_name,state_name 
                FROM bms_property 
                LEFT JOIN bms_property_type ON type_id=property_type
                LEFT JOIN bms_state ON bms_state.state_id = bms_property.state_id
                WHERE property_status=1 AND property_id IN 
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].") 
                ".$cond."
                ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getAllPropertiesWitTypeState ($search_txt = '') { 
        
        $cond = $search_txt != '' ?  " AND LOWER(property_name) LIKE '%".strtolower($search_txt)."%' " : '';
        
        $sql = "SELECT property_id,property_name,total_units,email_addr,type_name,state_name,property_status 
                FROM bms_property 
                LEFT JOIN bms_property_type ON type_id=property_type
                LEFT JOIN bms_state ON bms_state.state_id = bms_property.state_id
                WHERE 1=1
                ".$cond."
                ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
        
    function getBlocks ($property_id) {
        $query = $this->db->select('block_id,property_id,block_name')->order_by('block_name','ASC')->get_where('bms_property_block',array('property_id'=>$property_id));
        return $query->result_array();
    }
    
    function getBlock ($block_id) {
        $query = $this->db->select('block_id,property_id,block_name')->get_where('bms_property_block',array('block_id'=>$block_id));        
        return $query->row();
    }
    
    function getUnit ($property_id,$block_id) {
        $condi = '';
        if($block_id != 0)
            $condi = " AND block_id = '".$block_id."'";
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter 
                FROM bms_property_units 
                WHERE property_id = '".$property_id."' $condi
                ORDER BY unit_no";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function getUnitDetails ($unit_id) {
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,email_addr,contact_1,is_defaulter FROM bms_property_units WHERE unit_id = '".$unit_id."'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row();
    }   
    
    function getAssignTo ($property_id) {
        $sql = "SELECT desi_id,desi_name FROM `bms_designation` 
                WHERE desi_id IN (SELECT DISTINCT designation_id FROM bms_staff WHERE staff_id IN 
                    (SELECT staff_id FROM `bms_staff_property` WHERE property_id=".$property_id."))
                     ORDER BY desi_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }   
    
    function getDesignation ($desi_id = '') {
        $condi = $desi_id != '' ? " AND desi_id=".$desi_id : '';
        $sql = "SELECT desi_id,desi_name FROM `bms_designation` WHERE 1=1 $condi ORDER BY desi_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }
    
    function getEmpType () {
        $sql = "SELECT emp_type_id, emp_type_name FROM  bms_emp_type ORDER BY emp_type_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }
    
    function getModule () {
        $sql = "SELECT module_id, module_name FROM  bms_module ORDER BY module_order_by ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }
    
    
    function getStaffNames ($desi_id = '') {
        $condi ='';
        if($desi_id && $desi_id != 'all')
            $condi = ' AND designation_id='.$desi_id;
        $sql = "SELECT staff_id,first_name,last_name  FROM `bms_staff` WHERE emp_type IN (1,2,3) ".$condi." ORDER BY first_name ASC,last_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }
    
    function getStaffNamesByProperty ($property_id = '',$staff_id = '') {
        $condi ='';
        $sql = "SELECT staff_id,first_name,last_name  
                    FROM `bms_staff` WHERE emp_type IN (1,2,3) 
                    AND staff_id IN(
                        SELECT staff_id from bms_staff_property WHERE property_id IN(
                        SELECT property_id from bms_staff_property WHERE staff_id =".$staff_id;
                        
        if($property_id && $property_id != 'all') {
            $sql .=" AND property_id=".$property_id;
        } 
        $sql .= ")) ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getStaffNamesForSA ($property_id = '',$staff_id = '') {
        $condi ='';
        $sql = "SELECT staff_id,first_name,last_name  
                    FROM `bms_staff` WHERE emp_type IN (1,2,3) 
                    ";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyStaffs ($property_id,$exclude_desi_ids='') {
        $condi = $exclude_desi_ids != '' ? "AND designation_id NOT IN (".$exclude_desi_ids.")" : '';
        $sql = "SELECT staff_id,first_name,last_name,mobile_no,desi_name
                FROM `bms_staff`
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1,2,3) 
                ".$condi."
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY sort_order ASC,desi_name ASC,first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getShiftPattern () {
        $sql = "SELECT shift_id , work_duty_description 
                FROM bms_shift_pattern AS a
                ORDER BY work_duty_description";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    } 
    
    function get_user_access_log ($offset,$rows,$staff_id,$desi_id,$rec_date) {
               
        $where = isset($staff_id) && $staff_id != 'all' ? 'bms_user_access_log.staff_id='.$staff_id : '1=1';
        $where .= isset($desi_id) && $desi_id != 'all' ? ' AND bms_staff.designation_id='.$desi_id : '';
        $where .= isset($rec_date) && $rec_date != '' ? " AND DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($rec_date))."'" : '';
        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')                 
                 ->get_where('bms_user_access_log',$where);
        $num_rows = $query->num_rows();
                 
        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')
                 ->limit($rows,$offset)
                 ->order_by('accessed_date','DESC')                 
                 ->get_where('bms_user_access_log',$where);
        $data = $query->result_array();
        //echo "<pre>".$this->db->last_query();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }
    
    function get_user_accessed_ip ($staff_id,$mat_date) {
        $where = isset($staff_id) && $staff_id != '' ? 'bms_user_access_log.staff_id='.$staff_id : '1=1';
        $where .= isset($mat_date) && $mat_date != '' ? " AND DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($mat_date))."'" : '';
        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')                 
                 ->order_by('accessed_date','DESC') 
                 ->group_by('ip_address')                
                 ->get_where('bms_user_access_log',$where);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }        
    
    
    function get_user_access_log_mat ($offset,$rows,$mat_date) {
        
        $where = isset($mat_date) && $mat_date != '' ? " DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($mat_date))."'" : '1=1';
        //$where .= isset($desi_id) && $desi_id != 'all' ? ' AND bms_staff.designation_id='.$desi_id : '';
        
        $sql = "SELECT tbl.*,COUNT(staff_id) FROM 
                (SELECT `bms_user_access_log`.`staff_id`, COUNT(`bms_user_access_log`.`staff_id`) AS hit_cnt, `first_name`, `last_name`, designation_id, 
                `latitude`, `longitude`, `ip_address`, `accessed_module`, `accessed_method`, DATE_FORMAT(accessed_date, '%Y-%m-%d')  AS a_date
                FROM `bms_user_access_log`
                LEFT JOIN `bms_staff`  ON `bms_staff`.`staff_id` = `bms_user_access_log`.`staff_id`
                WHERE  ".$where."
                GROUP BY staff_id,ip_address,a_date) as tbl group by staff_id HAVING COUNT(staff_id) > 1
                ";
        
        $query = $this->db->query($sql);        
        $num_rows = $query->num_rows();
        
        $sql = "SELECT tbl.*,COUNT(staff_id) FROM 
                (SELECT `bms_user_access_log`.`staff_id`, COUNT(`bms_user_access_log`.`staff_id`) AS hit_cnt, `first_name`, `last_name`, designation_id, 
                `latitude`, `longitude`, `ip_address`, `accessed_module`, `accessed_method`, DATE_FORMAT(accessed_date, '%Y-%m-%d')  AS a_date
                FROM `bms_user_access_log`
                LEFT JOIN `bms_staff`  ON `bms_staff`.`staff_id` = `bms_user_access_log`.`staff_id`
                WHERE  ".$where."
                GROUP BY staff_id,ip_address, a_date
                ORDER BY `first_name`) as tbl group by staff_id HAVING COUNT(staff_id) > 1 
                LIMIT ".$offset.",".$rows;
        
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        $data = $query->result_array();     
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    
    function getChatList ($user_type,$last_list_sync='',$staff_id='',$property_id='') {
        
        if($user_type == 'jmb') {
            $condi = " a.property_id=(SELECT property_id FROM bms_jmb_mc where member_id =".$staff_id.")";
        } else {
            $condi = " a.property_id IN (SELECT c.property_id FROM bms_staff_property c WHERE c.staff_id=$staff_id)";
        }
        $condi2 = $last_list_sync != '' ? " AND chat_date >= '$last_list_sync'" : '';
        $sql = "SELECT a.property_id, b.property_name,COUNT(*) AS chat_cnt FROM bms_chat a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE $condi $condi2                                
                GROUP BY property_id";
        $query = $this->db->query($sql);
        return $query->result_array();   
    }
    
    function getChatMessages ($property_id,$last_sync='') {       
        
        $condi = $last_sync != '' ? " AND chat_date >= '$last_sync'" : '';
        $sql = "SELECT chat_id,`property_id`,`user_id`,
                CASE user_type
	               WHEN 1 THEN (SELECT CONCAT(first_name, ' ',last_name) FROM bms_staff WHERE staff_id=a.user_id) 
	               WHEN 2 THEN (SELECT owner_name FROM bms_property_units p, bms_jmb_mc q WHERE q.unit_id = p.unit_id AND q.member_id=a.user_id ) 
                END AS user_name, 
                `chat_txt`,`file_name`,`chat_date` 
                FROM bms_chat a 
                WHERE property_id=$property_id $condi ";
        $query = $this->db->query($sql);
        return $query->result_array();   
    }
    
    function set_chat ($data) {
        $this->db->insert('bms_chat', $data);
        return $insert_id = $this->db->insert_id();
    }
    
    function getAwardedStaffs ($award_year,$award_month) {
        $sql = "SELECT a.staff_id,first_name,last_name,desi_name,d.property_name,awarded_cat,
                jmb_percentage,am_percentage,hr_percentage,total_percentage,awarded
                FROM bms_staff_awarded a
                LEFT JOIN bms_staff b ON a.staff_id=b.staff_id
                LEFT JOIN bms_designation c ON c.desi_id=b.designation_id
                LEFT JOIN bms_property d ON d.property_id=a.property_id
                WHERE awarded=1 AND award_year=".$award_year." AND award_month=".$award_month." 
                ORDER BY awarded_cat";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getShortListedStaffs ($award_cat,$award_year,$award_month) {
        $sql = "SELECT a.staff_id,first_name,last_name,desi_name,d.property_name,awarded_cat,
                jmb_percentage,am_percentage,hr_percentage,total_percentage,awarded
                FROM bms_staff_awarded a
                LEFT JOIN bms_staff b ON a.staff_id=b.staff_id
                LEFT JOIN bms_designation c ON c.desi_id=b.designation_id
                LEFT JOIN bms_property d ON d.property_id=a.property_id
                WHERE awarded=0 AND awarded_cat=".$award_cat." AND award_year=".$award_year." AND award_month=".$award_month." 
                ORDER BY awarded_cat LIMIT 0,4";
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getNoticeBoard () {
        $sql = "SELECT notice_id,message 
                FROM bms_notice_board AS a";
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }    
    
   
    
    function common_docs_insert ($data) {
        $this->db->insert('bms_doc_center', $data);
    }
}