<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_property_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getPropertyDetails ($property_id) {
        $sql = "SELECT property_name,property_abbrev,total_units,property_type,email_addr,property_under,jmb_mc_name,
                logo,property_status,finance_year_start_month,insurance_prem,quit_rent,
                calcul_base,sinking_fund,billing_cycle,insur_prem_date,quit_rent_paid_on,e_billing_start_date,
                address_1,address_2,phone_no,phone_no2,fax,pin_code,city,state_id,country_id,bill_due_days, 
                late_payment,late_pay_percent,late_pay_effect_from,late_pay_grace_type,late_pay_grace_value,monthly_billing,
                water,water_min_charg,water_charge_per_unit_rate_1,water_charge_per_unit_rate_2, water_charge_range, 
                acb_grace_type, acb_grace_value, acb_unblock_charges_type, acb_unblock_charges_value, 
                pymt_gateway_url,payment_merchant_id,payment_bear_by, payment_fpx, payment_cc_card, payment_merchant_key_index, payment_merchant_key,
                vms_status, vms_access, sc_charge, account_status, acb_reminder1_days, acb_reminder2_days, acb_block_card, bill_generate_date,
                account_title, bank_name, account_no, mob_app_issue, mob_app_billing, mob_app_defect, mob_app_fasc_book, mob_app_pro_doc,
                mob_app_daily_rpt, mob_app_survey_form, mob_app_visit_list, mob_app_prebook, mob_app_freq_visit, mob_app_note_to_guard,
                mob_app_panic_alert, developer_email_addr, managed_by  
                FROM  bms_property a
                WHERE property_id=". $property_id;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getPropertyChargHist ($property_id) {
        $sql = "SELECT group_concat(charg_cat) AS charg_hist_cat 
                FROM bms_property_charg_hist 
                WHERE property_id=".$property_id."                
                HAVING (COUNT(charg_cat)) > 0";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getPropertyPremQuitHist ($property_id) {
        $sql = "SELECT group_concat(prem_quit_cat) AS cat 
                FROM bms_property_prem_quit_hist 
                WHERE property_id=".$property_id."                
                HAVING (COUNT(prem_quit_cat)) > 0";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getPropertyChargHistDetails ($property_id,$cat_id) {
        $sql = "SELECT charg_cat,charg_val,updated_date
                FROM bms_property_charg_hist 
                WHERE property_id=".$property_id." AND charg_cat =".$cat_id."
                ORDER BY updated_date DESC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getPropertyPremQuitHistDetails ($property_id,$cat_id) {
        $sql = "SELECT prem_quit_cat,prem_quit_val,prem_quit_effect_date,updated_date
                FROM bms_property_prem_quit_hist 
                WHERE property_id=".$property_id." AND prem_quit_cat =".$cat_id."
                ORDER BY updated_date DESC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getPropertyType () {
        $query = $this->db->select('type_id,type_name')->order_by('type_name')->get('bms_property_type');
        return $query->result_array();
    }
    
    function getPropertyState () {
        $query = $this->db->select('state_id,state_name')->order_by('state_name')->get('bms_state');
        return $query->result_array();
    } 
    
    function insert_property ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property', $data);
        return $this->db->insert_id();           
    } 
    
    function update_property ($data,$property_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property', $data, array('property_id' => $property_id));       
    }
    
    function removeBlocks($all_ids,$property_id) {
        $sql = "DELETE FROM bms_property_block WHERE property_id =".$property_id." AND block_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
    }
    
    function insertBlock ($data) {
        $this->db->insert('bms_property_block', $data); 
        //echo "<br />".$this->db->last_query();        
    } 
    
    function updateBlock ($data,$block_id) {
        $this->db->update('bms_property_block', $data, array('block_id' => $block_id));  
        //echo "<br />".$this->db->last_query();     
    }
    
    function insert_property_charg_hist ($data) {
        $this->db->insert('bms_property_charg_hist', $data);       
    }     
    
    function insert_property_prem_quit_hist ($data) {
        $this->db->insert('bms_property_prem_quit_hist', $data);       
    } 
    
    function getUnits ($property_id,$calc_base) {
        $cond = '';
        if($calc_base == 1) 
            $cond .= " AND square_feet <>''";
        if($calc_base == 2) 
            $cond .= " AND share_unit <>''";
        $sql = "SELECT unit_id,square_feet,share_unit FROM bms_property_units WHERE property_id=".$property_id . $cond;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function setUnit ($data,$unit_id) {
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id)); 
        //echo "<br />".$this->db->last_query();   
    }
    
    function getMandatoryCharges ($unit_id) {
        $sql = "SELECT group_concat(charge_type_id) AS charge_type 
                FROM bms_property_unit_charges 
                WHERE unit_id=".$unit_id." AND charge_type_id IN (1,2,3,4)";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function updateMandatoryCharges ($data,$unit_id,$type_id) {
        $this->db->update('bms_property_unit_charges', $data, array('unit_id' => $unit_id,'charge_type_id'=>$type_id)); 
    }
    
    function insertMandatoryCharges ($data) {
        $this->db->insert('bms_property_unit_charges', $data); 
    }
    
    function getPropertyDocCategory () {
        $query = $this->db->select('doc_cat_id,doc_cat_name')->order_by('doc_cat_name')->get('bms_property_doc_category');
        return $query->result_array();
    }
    
    function getMyPropertiesDocs ($offset = '0', $per_page = '25',$property_id='',$doc_cat_id='',$search_txt='') { 
        
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;        
        
        $cond = $property_id != '' ? ' AND a.property_id='.$property_id : '';
        $cond .= $doc_cat_id != '' ? ' AND a.doc_cat_id='.$doc_cat_id : '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.doc_name) LIKE '%".$search_txt."%' OR LOWER(a.doc_description) LIKE '%".$search_txt."%') ";
        } 
        
        $sql = "SELECT doc_id,a.property_id,b.doc_cat_name,p_mas.property_name,doc_name,doc_file_name,a.created_date
                FROM bms_property_docs AS a
                LEFT JOIN bms_property AS p_mas ON  p_mas.property_id = a.property_id
                LEFT JOIN bms_property_doc_category AS b ON  b.doc_cat_id = a.doc_cat_id
                WHERE a.property_id IN (SELECT b.property_id FROM bms_staff_property AS b WHERE staff_id=".$_SESSION['bms']['staff_id'].")". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();       
        
        $sql .= " ORDER BY a.created_date DESC".$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function getMyPropertiesDocsJmb ($offset = '0', $per_page = '25',$property_id='',$doc_cat_id='',$search_txt='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;        
        
        $cond = $property_id != '' ? ' AND a.property_id='.$property_id : '';
        $cond .= $doc_cat_id != '' ? ' AND a.doc_cat_id='.$doc_cat_id : '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.doc_name) LIKE '%".$search_txt."%' OR LOWER(a.doc_description) LIKE '%".$search_txt."%') ";
        } 
        
        $sql = "SELECT doc_id,a.property_id,b.doc_cat_name,p_mas.property_name,doc_name,doc_file_name,a.created_date
                FROM bms_property_docs AS a
                LEFT JOIN bms_property AS p_mas ON  p_mas.property_id = a.property_id
                LEFT JOIN bms_property_doc_category AS b ON  b.doc_cat_id = a.doc_cat_id
                WHERE 1=1 $cond";
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();       
        
        $sql .= " ORDER BY a.created_date DESC".$limit;
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function property_docs_insert ($data) {
        $this->db->insert('bms_property_docs', $data);
        return $insert_id = $this->db->insert_id();//1316;//
        //return $query->result_array();         
    } 
    
    function get_asset_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.asset_name) LIKE '%".$search_txt."%' OR LOWER(a.asset_make) LIKE '%".$search_txt."%' OR LOWER(a.serial_no) LIKE '%".$search_txt."%' OR LOWER(a.asset_location) LIKE '%".$search_txt."%' OR LOWER(a.supplier_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT asset_id,a.asset_name,a.asset_make,serial_no,asset_location,supplier_name
                FROM bms_property_assets a                   
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY asset_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }   
    
    function get_service_asset_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND periodic_service=1 AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.asset_name) LIKE '%".$search_txt."%' OR LOWER(a.asset_make) LIKE '%".$search_txt."%' OR LOWER(a.serial_no) LIKE '%".$search_txt."%' OR LOWER(a.asset_location) LIKE '%".$search_txt."%' OR LOWER(a.supplier_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT asset_id,a.asset_name,a.asset_make,serial_no,asset_location,supplier_name
                FROM bms_property_assets a                   
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY asset_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }  
    
    function get_asset_details ($asset_id) {
        $cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT asset_id,property_id,asset_name,asset_descri,supplier_name,address,postcode,city,state,country,
                office_ph_no,person_incharge,person_inc_mobile,person_inc_email,asset_cat,asset_make,asset_location,serial_no,
                purchase_date,price,warranty_start,warranty_due,remind_before,decommission_date,periodic_service,
                created_by,created_date,updated_by,updated_date
                FROM  bms_property_assets a               
                WHERE asset_id=". $asset_id . $cond;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    } 
    
    function get_asset_maintenance_company ($asset_id) {
        $sql = "SELECT a.maint_comp_id,a.asset_id,b.property_id,b.provider_name AS supplier_name,
                b.address,b.postcode,b.city,b.state,b.country,b.office_ph_no,
                b.person_incharge,b.person_inc_mobile,b.person_inc_email,b.contract_start_date AS warranty_start,
                b.contract_end_date AS warranty_due,b.remind_before,b.file_name,
                b.created_by,b.created_date,b.updated_by,b.updated_date
                FROM  bms_prop_asset_maint_comp a
                LEFT JOIN bms_service_provider b ON b.service_provider_id = a.service_provider_id               
                WHERE a.asset_id=". $asset_id . " ORDER BY maint_comp_id DESC LIMIT 0,1";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_maintenance_company ($maint_comp_id) {
        $sql = "SELECT maint_comp_id,asset_id,property_id,supplier_name,address,postcode,city,state,country,office_ph_no,
                person_incharge,person_inc_mobile,person_inc_email,warranty_start,warranty_due,remind_before,file_name,
                created_by,created_date,updated_by,updated_date
                FROM  bms_prop_asset_maint_comp a               
                WHERE maint_comp_id=". $maint_comp_id ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_all_maintenance_company($asset_id) {
        $sql = "SELECT maint_comp_id,a.asset_id,b.property_id,b.provider_name AS supplier_name,
                b.contract_start_date AS warranty_start,b.contract_end_date AS warranty_due,b.office_ph_no,
                b.person_incharge,b.person_inc_mobile,b.person_inc_email,b.remind_before                
                FROM  bms_prop_asset_maint_comp a    
                LEFT JOIN bms_service_provider b ON b.service_provider_id = a.service_provider_id           
                WHERE a.asset_id=". $asset_id . " ORDER BY maint_comp_id DESC" ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_asset_service_period ($asset_id) {
        $sql = "SELECT service_period_id,asset_id,service_name,service_period
                FROM  bms_prop_asset_ser_period a               
                WHERE asset_id=". $asset_id;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }  
    
    function insert_asset ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_property_assets', $data);
        return $this->db->insert_id();           
    } 
    
    function update_asset ($data,$asset_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_property_assets', $data, array('asset_id' => $asset_id));       
    } 
    
    function insert_maintenance_company ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_prop_asset_maint_comp', $data);
        return $this->db->insert_id();           
    } 
    
    function update_maintenance_company ($data,$maint_comp_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_prop_asset_maint_comp', $data, array('maint_comp_id' => $maint_comp_id));       
    } 
    
    function insert_asset_service_period ($data) {
        
        $this->db->insert('bms_prop_asset_ser_period', $data);
        //return $this->db->insert_id();           
    } 
    
    function update_asset_service_period ($data,$service_period_id) {        
        $this->db->update('bms_prop_asset_ser_period', $data, array('service_period_id' => $service_period_id));       
    } 
    
    function insert_service_schedule ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        return $this->db->insert('bms_asset_service_schedule', $data);
        //return $this->db->insert_id();           
    } 
    
    function update_service_schedule ($data,$asset_service_schedule_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        return $this->db->update('bms_asset_service_schedule', $data, array('asset_service_schedule_id' => $asset_service_schedule_id));
        //return $this->db->insert_id();           
    } 
    
    function get_service_schedule_list ($asset_id) {
        $sql = "SELECT asset_service_schedule_id,service_type,service_period,DATE_FORMAT(a.service_date, '%d-%m-%Y') AS service_date,
                DATE_FORMAT(a.created_date, '%d-%m-%Y') AS created_date,warranty_status, service_reminder
                FROM bms_asset_service_schedule a
                WHERE asset_id=". $asset_id." ORDER BY a.service_date DESC";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_asset_service_details_list ($offset = '0', $per_page = '25', $property_id ='') {
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
               
        $sql = "SELECT a.asset_service_schedule_id,a.asset_id,b.asset_name,b.asset_location,
                DATE_FORMAT(a.service_date, '%d-%m-%Y') AS service_schedule_date,c.property_name,
                IFNULL(d.service_date,'') AS service_date,d.asset_service_details_id
                FROM bms_asset_service_schedule a
                LEFT JOIN bms_property_assets b ON b.asset_id=a.asset_id     
                LEFT JOIN bms_property c ON c.property_id = b.property_id      
                LEFT JOIN bms_asset_service_details d ON d.service_schedule_id = a.asset_service_schedule_id         
                WHERE 1=1 AND b.property_id=". $property_id ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY service_schedule_date ASC ".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function get_asset_service_details_entry($service_schedule_id) {
        $sql = "SELECT a.asset_service_schedule_id,a.asset_id,b.asset_name,b.asset_descri,b.serial_no,b.asset_location,
                b.supplier_name, b.address,b.postcode,b.city,b.state,b.country,b.office_ph_no,
                b.person_incharge,b.person_inc_mobile,b.person_inc_email,b.warranty_start,b.warranty_due,b.remind_before,
                DATE_FORMAT(a.service_date, '%d-%m-%Y') AS service_schedule_date,c.property_id,
                IFNULL(d.service_date,'') AS service_date
                FROM bms_asset_service_schedule a
                LEFT JOIN bms_property_assets b ON b.asset_id=a.asset_id     
                LEFT JOIN bms_property c ON c.property_id = b.property_id      
                LEFT JOIN bms_asset_service_details d ON d.service_schedule_id = a.asset_service_schedule_id         
                WHERE 1=1 AND a.asset_service_schedule_id=". $service_schedule_id ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_asset_service_details ($asset_service_details_id) {
        $sql = "SELECT a.asset_service_details_id,DATE_FORMAT(a.service_date, '%d-%m-%Y') AS service_date,
                a.job_sheet_no,a.service_by,a.service_description,a.remarks,
                n.asset_service_schedule_id,a.asset_id,b.asset_name,b.asset_descri,b.serial_no,b.asset_location,
                b.supplier_name,c.property_id,c.property_name,
                first_name,last_name, DATE_FORMAT(a.created_date,'%d-%m-%Y %h:%i %p') AS created_date
                FROM bms_asset_service_details a
                LEFT JOIN bms_asset_service_schedule n ON n.asset_service_schedule_id  = a.service_schedule_id
                LEFT JOIN bms_property_assets b ON b.asset_id=a.asset_id     
                LEFT JOIN bms_property c ON c.property_id = b.property_id
                LEFT JOIN bms_staff AS d ON d.staff_id = a.created_by 
                WHERE 1=1 AND a.asset_service_details_id=". $asset_service_details_id ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_asset_service_details_att ($asset_service_details_id) {
        $query = $this->db->select('asset_service_details_id,file_name')
                 ->get_where('bms_asset_service_details_att',array('asset_service_details_id'=>$asset_service_details_id));
        return $query->result_array();
    }
    
    function insert_service_entry ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_asset_service_details', $data);
        return $this->db->insert_id();        
    }
    
    function insert_service_entry_attach ($data) {        
        $this->db->insert('bms_asset_service_details_att', $data);       
    }
    
    function getServiceScheduleCount ($asset_id) {
        $sql = "SELECT COUNT(1) AS cnt
                FROM  bms_asset_service_schedule a               
                WHERE asset_id=". $asset_id;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        $result = $query->row_array();
        return $result['cnt'];
    }
    
    function getServiceScheduleCountBetweenDate ($asset_id,$service_type,$service_period,$start_date,$end_date) {
        $sql = "SELECT COUNT(1) AS cnt
                FROM  bms_asset_service_schedule a               
                WHERE asset_id=". $asset_id." AND service_type=".$service_type." AND service_period=".$service_period." 
                AND service_date BETWEEN '".$start_date."' AND '".$end_date."'";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        $result = $query->row_array();
        return $result['cnt'];
    }
    
    function getServiceLastScheduleDate ($asset_id,$service_type,$service_period) {
        $sql = "SELECT service_date 
                FROM  bms_asset_service_schedule a               
                WHERE asset_id=". $asset_id ." AND service_type=".$service_type." AND service_period=".$service_period." 
                ORDER BY service_date DESC LIMIT 0,1";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getAssetDetailsForEmail ($asset_id) {
        //$cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT asset_id,a.property_id,b.property_name,asset_name,asset_descri,asset_location,serial_no 
                FROM  bms_property_assets a
                LEFT JOIN bms_property b ON b.property_id = a.property_id             
                WHERE asset_id=". $asset_id ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_annual_renewal_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.item_descrip) LIKE '%".$search_txt."%' OR LOWER(a.serial_no) LIKE '%".$search_txt."%' OR LOWER(a.location) LIKE '%".$search_txt."%' OR LOWER(a.license_no) LIKE '%".$search_txt."%' OR LOWER(a.supplier_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT annual_renewal_id,a.item_descrip,a.serial_no,location,license_no,supplier_name,
                DATE_FORMAT(a.license_start_date, '%d-%m-%Y') AS license_start_date,
                DATE_FORMAT(a.license_expiry_date, '%d-%m-%Y') AS license_expiry_date
                FROM bms_annual_renewal a                   
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY license_expiry_date ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    } 
    
    function get_annual_renewal_details ($annual_renewal_id) {
        $cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT annual_renewal_id,property_id,item_descrip,serial_no,location,license_no,
                DATE_FORMAT(a.license_start_date, '%d-%m-%Y') AS license_start_date,
                DATE_FORMAT(a.license_expiry_date, '%d-%m-%Y') AS license_expiry_date,
                supplier_name,address,office_ph_no,person_incharge,person_inc_mobile,person_inc_email,
                remind_before
                FROM  bms_annual_renewal a               
                WHERE annual_renewal_id=". $annual_renewal_id . $cond;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    } 
    
    function get_annual_renewal_att ($annual_renewal_id) {
        $query = $this->db->select('annual_renewal_id,file_name')
                 ->get_where('bms_annual_renewal_att',array('annual_renewal_id'=>$annual_renewal_id));
        return $query->result_array();
    } 
    
    
     function insert_annual_renewal ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_annual_renewal', $data);
        return $this->db->insert_id();           
    } 
    
    function update_annual_renewal ($data,$annual_renewal_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_annual_renewal', $data, array('annual_renewal_id' => $annual_renewal_id));       
    } 
    
    function insert_sannual_renewal_attach ($data) {        
        $this->db->insert('bms_annual_renewal_att', $data);       
    }
    
    function get_resident_notice_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.notice_title) LIKE '%".$search_txt."%' OR LOWER(a.message) LIKE '%".$search_txt."%' OR LOWER(b.property_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT a.resident_notice_id, a.property_id, b.property_name, notice_title, DATE_FORMAT(start_date,'%d-%m-%Y') AS start_date, DATE_FORMAT(end_date,'%d-%m-%Y') AS end_date
                FROM bms_resident_notice_board a   
                LEFT JOIN bms_property b ON b.property_id = a.property_id               
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY a.created_date DESC ".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    } 
    
    function get_resi_notice_details ($resi_notice_id) {
        $sql = "SELECT a.resident_notice_id, a.property_id, b.property_name, notice_title, message, 
                DATE_FORMAT(start_date,'%d-%m-%Y') AS start_date,DATE_FORMAT(end_date,'%d-%m-%Y') AS end_date, attachment_name                
                FROM bms_resident_notice_board a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id               
                WHERE resident_notice_id=".$resi_notice_id." 
                AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id = ".$_SESSION['bms']['staff_id'].")";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row_array();
    }  
    
    function insert_resident_notice ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_resident_notice_board', $data);
        return $this->db->insert_id();
    }
    
    function update_resident_notice ($data,$resi_notice_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_resident_notice_board', $data, array('resident_notice_id' => $resi_notice_id));       
    }
    
    function get_service_provider_cat_list ($offset = '0', $per_page = '25', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.service_provider_cat_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT service_provider_cat_id,service_provider_cat_name                
                FROM bms_service_provider_cat a                 
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `service_provider_cat_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    } 
    
    function get_all_service_provider_cat_list () {
        
        $sql = "SELECT service_provider_cat_id,service_provider_cat_name                
                FROM bms_service_provider_cat a                 
                ORDER BY `service_provider_cat_name` ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function get_service_provider_cat_details ($desi_id) {
        $sql = "SELECT service_provider_cat_id,service_provider_cat_name                 
                FROM bms_service_provider_cat a                
                WHERE service_provider_cat_id= ". $desi_id ;
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insert_service_provider_cat ($data) {        
        $this->db->insert('bms_service_provider_cat', $data);                   
    } 
    
    function update_service_provider_cat ($data,$desi_id) {        
        $this->db->update('bms_service_provider_cat', $data, array('service_provider_cat_id' => $desi_id));       
    }    
    
    function get_service_provider_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.provider_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT service_provider_id,property_id,provider_name,a.service_provider_cat_id,service_provider_cat_name,contractual,office_ph_no,
                DATE_FORMAT(a.contract_start_date, '%d-%m-%Y') AS contract_start_date,DATE_FORMAT(a.contract_end_date, '%d-%m-%Y') AS contract_end_date,
                remind_before,person_incharge,person_inc_mobile,person_inc_email,head_count,billing_cycle,
                monthly_payment,annual_payment,job_scope,file_name 
                FROM bms_service_provider a 
                LEFT JOIN bms_service_provider_cat b ON b.service_provider_cat_id = a.service_provider_cat_id  WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function get_service_provider ($service_provider_id) {
        $sql = "SELECT service_provider_id,property_id,provider_name,service_provider_cat_id,contractual,
                address,postcode,city,state,country,office_ph_no,contract_start_date,contract_end_date,
                remind_before,person_incharge,person_inc_mobile,person_inc_email,head_count,billing_cycle,
                email_addr,password,monthly_payment,annual_payment,job_scope,file_name,created_by,coa_id,
                created_date,updated_by,updated_date, sun, mon, tue, wed, thu, fri, sat, public_holiday
                FROM bms_service_provider WHERE service_provider_id=".$service_provider_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
        
    }
    
    function insert_service_provider ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_service_provider', $data);
        return $this->db->insert_id();           
    } 
    
    function update_service_provider ($data,$service_provider_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_service_provider', $data, array('service_provider_id' => $service_provider_id));       
    }
    
    function get_service_provider_by_asset ($asset_id) {       
        
        $sql = "SELECT service_provider_id,a.property_id,provider_name
                FROM bms_service_provider a 
                LEFT JOIN bms_property_assets b ON b.property_id = a.property_id                
                WHERE b.asset_id = ". $asset_id ;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
        
    }
    
    function get_facility_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.facility_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT facility_id,property_id,facility_name,deposit_require
                FROM bms_property_facility a WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function get_facility ($facility_id) {
        $sql = "SELECT facility_id, property_id, facility_name, deposit_require,
                amount, charge_code_id, start_time, end_time, booking_slot,
                disclaimer, picture
                FROM bms_property_facility WHERE facility_id = ".$facility_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
        
    }
    
    function insert_facility ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_facility', $data);
        return $this->db->insert_id();           
    } 
    
    function update_facility ($data,$facility_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_facility', $data, array('facility_id' => $facility_id));       
    }

    function removeTier($all_ids,$property_id) {
        $sql = "DELETE FROM bms_property_tier WHERE property_id =".$property_id." AND tier_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
    }

    function insertTier ($data) {
        $this->db->insert('bms_property_tier', $data);
        //echo "<br />".$this->db->last_query();
    }

    function updateTier ($data,$block_id) {
        $this->db->update('bms_property_tier', $data, array('tier_id' => $block_id));
        //echo "<br />".$this->db->last_query();
    }

    function removeAcb ($all_ids,$property_id) {
        $sql = "DELETE FROM bms_property_acb WHERE property_id =".$property_id." AND acb_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
    }

    function insertAcb ($data) {
        $this->db->insert('bms_property_acb', $data);
        //echo "<br />".$this->db->last_query();
    }

    function updateAcb ($data,$block_id) {
        $this->db->update('bms_property_acb', $data, array('acb_id' => $block_id));
        //echo "<br />".$this->db->last_query();
    }

    function get_property_profile_details ($property_id) {
        $sql = "SELECT a.property_name,a.property_abbrev,a.total_units,a.property_type,a.email_addr,a.property_under,a.jmb_mc_name,
                a.logo,a.property_status,
                a.address_1,a.address_2,a.phone_no,a.phone_no2,a.fax,a.pin_code,a.city,a.state_id,a.country_id, b.country_name, c.state_name, d.type_name
                FROM  bms_property a LEFT JOIN bms_countries b on a.country_id = b.country_id
                LEFT JOIN bms_state c ON a.state_id = c.state_id 
                LEFT JOIN bms_property_type d on a.property_type = d.type_id
                WHERE property_id=". $property_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }

    function getFscilitiesByProperty ($property_id) {
        $sql = "SELECT facility_id, facility_name
                FROM  bms_property_facility 
                WHERE property_id=". $property_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function getBookings ( $property_id, $facility_id, $booking_status, $offset, $rows ) {

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if ( !empty($facility_id) )
            $cond .= " AND a.facility_id = '$facility_id'";

        if ( $booking_status === '0' )
            $cond .= " AND a.booking_status = 0 AND a.created_date >= '" . date('Y-m-d') ."'";
        else
            $cond .= " AND a.booking_status != 0";

        $sql = "SELECT a.facility_booking_id, a.created_date, a.booking_date, a.booking_slot, a.booking_status, a.booking_desc, b.facility_name, c.unit_no, d.block_name 
                FROM bms_property_facility_booking a
                LEFT JOIN bms_property_facility b ON a.facility_id = b.facility_id 
                LEFT JOIN bms_property_units c on a.unit_id = c.unit_id 
                LEFT JOIN bms_property_block d on c.block_id = d.block_id 
                WHERE 1=1 " .  $cond;

        $limit = ' LIMIT '.$offset.','.$rows;

        $query = $this->db->query($sql);
        $data['num_rows'] = $query->num_rows();

        $query = $this->db->query($sql . $limit);
        $data['booking_list'] = $query->result_array();
        return $data;

    }

    function updateFascilityBookingStatus ( $facility_booking_id, $data ) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        return $this->db->update('bms_property_facility_booking', $data, array('facility_booking_id' => $facility_booking_id));
    }

    function get_facility_booking_detail ( $facility_booking_id ) {

        $sql = "SELECT a.facility_booking_id, a.created_date, a.booking_date, a.booking_slot, a.booking_status, a.booking_desc, 
            b.facility_name, c.unit_no, d.block_name, e.property_name 
            FROM bms_property_facility_booking a
            LEFT JOIN bms_property_facility b ON a.facility_id = b.facility_id 
            LEFT JOIN bms_property_units c on a.unit_id = c.unit_id 
            LEFT JOIN bms_property_block d on c.block_id = d.block_id 
            LEFT JOIN bms_property e on a.property_id = e.property_id 
            WHERE facility_booking_id = '$facility_booking_id' ";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function removeDeveloper($all_ids,$property_id) {
        $sql = "DELETE FROM bms_property_developer WHERE property_id =".$property_id." AND property_dev_id NOT IN (".implode(',',$all_ids).")";
        $query = $this->db->query($sql);
    }

    function insertDeveloper ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_property_developer', $data);
    }

    function updateDeveloper ($data,$property_dev_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_property_developer', $data, array('property_dev_id' => $property_dev_id));
    }

}