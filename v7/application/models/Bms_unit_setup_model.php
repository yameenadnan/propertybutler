<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_unit_setup_model extends CI_Model {
    function __construct () { parent::__construct(); }  
    
    function get_unit_status () {
        $query = $this->db->select('unit_status_id,unit_status_name')->order_by('unit_status_id','ASC')->get('bms_property_unit_status'); //
        return $query->result_array();    
    } 
        
    function get_unit_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.owner_name) LIKE '%".$search_txt."%' OR LOWER(a.unit_status) LIKE '%".$search_txt."%' OR LOWER(c.block_name) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT calcul_base,
                (CASE 
                    WHEN e.calcul_base = '1' THEN SUM(square_feet)
                    WHEN e.calcul_base = '2' THEN SUM(share_unit)
                    WHEN e.calcul_base = '3' THEN ''
                END) AS square_feet
                FROM bms_property_units a                   
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                LEFT JOIN bms_property e on a.property_id = e.property_id
                WHERE status=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $total = $query->row()->square_feet;
        $calcul_base = $query->row()->calcul_base;

        $sql = "SELECT e.calcul_base, unit_id,a.property_id,a.block_id,block_name,unit_no,unit_status_name AS unit_status,floor_no,owner_name,
                (CASE 
                    WHEN e.calcul_base = '1' THEN square_feet
                    WHEN e.calcul_base = '2' THEN share_unit
                    WHEN e.calcul_base = '3' THEN ''
                END) AS square_feet
                FROM bms_property_units a                   
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                LEFT JOIN bms_property e on a.property_id = e.property_id
                WHERE status=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        
        $order_by = " ORDER BY unit_no ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data, 'total' => $total, 'calcul_base' => $calcul_base);
    }

    function get_unit_list_to_edit ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.owner_name) LIKE '%".$search_txt."%' OR LOWER(a.unit_status) LIKE '%".$search_txt."%' OR LOWER(c.block_name) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT calcul_base, unit_id, unit_no, owner_name, ic_passport_no, dob,
                (CASE 
                    WHEN calcul_base = '1' THEN square_feet
                    WHEN calcul_base = '2' THEN share_unit
                    WHEN calcul_base = '3' THEN ''
                END) AS square_feet
                , contact_1, contact_2, password, unit_status, a.email_addr, gender, race, religion, a.property_id, a.block_id, block_name, floor_no
                FROM bms_property_units a
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                LEFT JOIN bms_property e ON e.property_id = a.property_id
                WHERE status=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY unit_no ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('num_rows'=>$num_rows,'records'=>$data);
    }
    
    function get_unit_details ($unit_id) {
        $cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT unit_id,a.property_id,block_id,tier_id, unit_no,unit_status,floor_no,square_feet,share_unit,
                  service_charge,sinking_fund,insurance_prem,quit_rent,
                  carpark1,carpark2,carpark3,carpark4,rental_carpark1,rental_carpark2,rental_carpark3,rental_carpark4,
                  owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,password,forgot_pass_key,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2,is_defaulter,status,unit_type,no_of_owners
                FROM  bms_property_units a
                WHERE unit_id=". $unit_id . $cond;

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    
    function check_email($email_addr, $unit_id) {
        $cond = $unit_id ? ' AND unit_id <>'.$unit_id : '';
        $sql = "SELECT unit_id,email_addr 
                FROM bms_property_units WHERE status = 1 AND email_addr=? ".$cond;
        $query = $this->db->query($sql,array($email_addr));
        //echo $this->db->last_query();exit;
        return $query->result_array();   
    }
    
    function insert_unit ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_property_units', $data);
        return $this->db->insert_id();
    } 
    
    function update_unit ($data,$unit_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id));       
    }
    
    function get_owner_current ($unit_id) {
        $sql = "SELECT property_id,unit_no,unit_status,owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,valid_email,password,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2,is_defaulter,coa_id 
                  FROM bms_property_units
                  WHERE unit_id=". $unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    } 
    
    function get_unit_basic_info ($unit_id) {
        $sql = "SELECT property_id,unit_no,coa_id 
                  FROM bms_property_units
                  WHERE unit_id=". $unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    } 
    
    function get_owner_hist ($unit_id) {
        $sql = "SELECT unit_owner_id,owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,password,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2 
                  FROM bms_property_unit_owners
                  WHERE unit_id=". $unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_owner_hist_by_id ($id) {
        $sql = "SELECT unit_owner_id,owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,valid_email,password,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2 
                  FROM bms_property_unit_owners
                  WHERE unit_owner_id=". $id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function copy_curr_owner_to_hist ($unit_id) {
        $sql = "INSERT INTO bms_property_unit_owners (unit_id,owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,password,forgot_pass_key,address_1,address_2,city,postcode,state,country,contact_1,contact_2,start_date,end_date)
         SELECT unit_id,owner_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,password,forgot_pass_key,address_1,address_2,city,postcode,state,country,contact_1,contact_2,start_date,end_date FROM bms_property_units
         WHERE unit_id=".$unit_id. " AND owner_name IS NOT NULL";
         $query = $this->db->query($sql);
         //echo "<br />".$this->db->last_query();exit;
    }
    
    function set_jmb_status ($unit_id) {
        $data['jmb_status'] = 0;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_jmb_mc',$data,array('unit_id'=>$unit_id));
        //$query = $this->db->query($sql);
         //echo "<br />".$this->db->last_query();exit;
    }
    
    function set_owner_hist ($data,$unit_owner_id) {
        
        if($unit_owner_id == 'new') 
            $this->db->insert('bms_property_unit_owners',$data);
        else 
            $this->db->update('bms_property_unit_owners',$data,array('unit_owner_id'=>$unit_owner_id));
    }
    
    
    function get_tenant_hist ($unit_id) {
        $sql = "SELECT unit_tenant_id,tenant_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2 
                  FROM bms_property_unit_tenants
                  WHERE unit_id=". $unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_tenant_hist_by_id ($id) {
        $sql = "SELECT unit_tenant_id,tenant_name,ic_passport_no,dob,race,religion,gender,nationality,email_addr,password,
                  address_1,address_2,city,postcode,state,country,contact_1,contact_2 
                  FROM bms_property_unit_tenants
                  WHERE unit_tenant_id=". $id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_current_tenant ($id) {
        $sql = "SELECT unit_tenant_id,tenant_name,email_addr,password,contact_1,contact_2 
                  FROM bms_property_unit_tenants
                  WHERE unit_id=". $id ." ORDER BY start_date DESC LIMIT 1";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insert_tenant ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_tenants', $data);
        return $this->db->insert_id();           
    }   
    
    function update_tenant ($data,$unit_tenant_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_tenants', $data, array('unit_tenant_id' => $unit_tenant_id));       
    }
    
    function get_ma_users ($unit_id) {
        $sql = "SELECT unit_ma_user_id,property_id,ma_user_name,ma_user_contact,ma_user_email,ma_user_pass 
                FROM bms_property_unit_ma_users WHERE unit_id=".$unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function unset_ma_user ($unit_id,$ids) {
        $sql = "DELETE FROM bms_property_unit_ma_users WHERE unit_id = ".$unit_id." AND unit_ma_user_id NOT IN (".$ids.")";
        $this->db->query($sql);        
    }
    
    function insert_ma_user ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_ma_users', $data);
    }
    
    function update_ma_user ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_ma_users', $data, array('unit_ma_user_id' => $id));       
    }
    
    function get_vehicles ($unit_id) {
        $sql = "SELECT vehicle_id,vehicle_no,vehicle_type,make,model,color 
                  FROM bms_property_unit_vehicle
                  WHERE unit_id=". $unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function get_vehicle_by_id ($id) {
        $sql = "SELECT vehicle_id,vehicle_no,vehicle_type,make,model,color 
                  FROM bms_property_unit_vehicle
                  WHERE vehicle_id=". $id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insert_vehicle ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_vehicle', $data);
        return $this->db->insert_id();           
    }   
    
    function update_vehicle ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_vehicle', $data, array('vehicle_id' => $id));       
    }
    
    function getChargeTypes () {
        $query = $this->db->select('charge_type_id,charge_type_name')->where_not_in('charge_type_id',array(1,2,3,4))->get('bms_charge_type'); //->order_by('charge_type_name','ASC')
        return $query->result_array();
    }
    
    /*function getChargeCodes () {
        $query = $this->db->select('charge_code_id,charge_code')->get('bms_charge_code'); //->order_by('charge_code','ASC')
        return $query->result_array();
    }*/
    
    function get_unit_charg_details ($unit_id) {
        
        $sql = "SELECT unit_id,a.property_id,square_feet,share_unit,
                b.calcul_base,b.tot_sq_feet,b.per_sq_feet,b.tot_share_unit,b.per_share_unit,b.sinking_fund,b.insurance_prem,b.quit_rent
                FROM  bms_property_units a  
                LEFT JOIN bms_property b ON b.property_id=a.property_id             
                WHERE unit_id=". $unit_id ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_charges_mand ($unit_id) {
        $sql = "SELECT charges_id,unit_id,a.charge_type_id,amount,e_billing_start_date,a.charge_code_id,c.charge_code,pay_by,charge_type_name
                FROM bms_property_unit_charges a
                LEFT JOIN bms_charge_type b ON b.charge_type_id =  a.charge_type_id
                LEFT JOIN bms_charge_code c ON c.charge_code_id =  a.charge_code_id
                WHERE unit_id=".$unit_id." AND a.charge_type_id IN (1,2,3,4)
                ORDER BY a.charge_type_id ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_charges ($unit_id) {
        $sql = "SELECT charges_id,unit_id,a.charge_type_id,amount,e_billing_start_date,a.charge_code_id,c.charge_code,pay_by,charge_type_name
                FROM bms_property_unit_charges a
                LEFT JOIN bms_charge_type b ON b.charge_type_id =  a.charge_type_id
                LEFT JOIN bms_charge_code c ON c.charge_code_id =  a.charge_code_id
                WHERE unit_id=".$unit_id." AND a.charge_type_id NOT IN (1,2,3,4)
                ORDER BY a.charge_type_id ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    function removeCharges ($all_ids, $unit_id) {
        $sql = "DELETE FROM bms_property_unit_charges WHERE unit_id =".$unit_id." AND charges_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
    }
    
    function updateCharges ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_charges', $data, array('charges_id' => $id));   
    }
    
    function insertCharges ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_charges', $data);          
    }
      
    function get_parking ($unit_id) {
        $sql = "SELECT parking_id,unit_id,a.parking_no,parking_type,amount,effect_date,a.charge_code_id
                FROM bms_property_unit_parking a                
                WHERE unit_id=".$unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function removeParking ($all_ids, $unit_id) {
        $sql = "DELETE FROM bms_property_unit_parking WHERE unit_id =".$unit_id." AND parking_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
    }
    
    function updateParking ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_parking', $data, array('parking_id' => $id));   
    }
    
    function insertParking ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_parking', $data);          
    } 
    
    function get_access_card ($unit_id) {
        $sql = "SELECT access_card_id,unit_id,a.access_card_no,access_card_type,amount,effect_date,a.charge_code_id
                FROM bms_property_unit_access_card a                
                WHERE unit_id=".$unit_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function removeAccessCard ($all_ids, $unit_id) {
        $sql = "DELETE FROM bms_property_unit_access_card WHERE unit_id =".$unit_id." AND access_card_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
    }
    
    function updateAccessCard ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_unit_access_card', $data, array('access_card_id' => $id));   
    }
    
    function insertAccessCard ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_unit_access_card', $data);          
    }

    function get_invalid_email_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.owner_name) LIKE '%".$search_txt."%' OR LOWER(a.unit_status) LIKE '%".$search_txt."%' OR LOWER(c.block_name) LIKE '%".$search_txt."%')";
        }


        $sql = "SELECT a.unit_id, a.property_id, a.unit_no, a.owner_name, a.email_addr, a.contact_1, a.contact_2
                FROM bms_property_units a
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                WHERE status=1 
                AND a.valid_email = 0 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY unit_no ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

    function get_property_calcul_base ($property_id) {
        $sql = "SELECT calcul_base FROM bms_property WHERE property_id = " . $property_id;
        return $this->db->query($sql)->row()->calcul_base;
    }

    function get_unit_list_to_download ($property_id ='', $search_txt ='') {

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.owner_name) LIKE '%".$search_txt."%' OR LOWER(a.unit_status) LIKE '%".$search_txt."%' OR LOWER(c.block_name) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT calcul_base, unit_no, block_name,
                (CASE 
                    WHEN calcul_base = '1' THEN square_feet
                    WHEN calcul_base = '2' THEN share_unit
                    WHEN calcul_base = '3' THEN ''
                END) AS square_feet_share_unit, owner_name, contact_1, a.email_addr, unit_status_name AS unit_status 
                
                /* , contact_1, a.email_addr, gender, race, religion, a.property_id, a.block_id,  
                unit_status_name AS unit_status, floor_no, ic_passport_no, dob, */
                FROM bms_property_units a
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                LEFT JOIN bms_property e ON e.property_id = a.property_id
                WHERE status=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);

        $order_by = " ORDER BY unit_no ASC";
        $query = $this->db->query($sql.$order_by);
        return $query->result_array();
    }
}