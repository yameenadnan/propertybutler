<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_sfs_model extends CI_Model {

    function __construct () { parent::__construct(); }
    
    function get_sfs_cat_list ($offset = '0', $per_page = '25', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(cat_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT cat_id, cat_name                
                FROM sfs_category 
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `cat_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function get_all_category_list () {
        
        $sql = "SELECT cat_id, cat_name
                FROM sfs_category
                ORDER BY `cat_name` ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_tsp_selected_category_list ( $tsp_id ) {
        $sql = "SELECT cat_id
                FROM sfs_tsp_cat_mapp
                WHERE tsp_id = '$tsp_id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_sfs_cat_details ($cat_id) {
        $sql = "SELECT cat_id, cat_name, picture, cat_type
                FROM sfs_category
                WHERE cat_id = ". $cat_id ;
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insert_sfs_cat ($data) {
        $this->db->insert('sfs_category', $data);
    } 
    
    function update_sfs_cat ($data,$cat_id) {
        $this->db->update('sfs_category', $data, array('cat_id' => $cat_id));
    }
    
    
    function getStates () {
        $query = $this
            ->db
            ->select('state_id, state_name')
            ->order_by('state_name')
            ->get('sfs_tsp_state');
        return $query->result_array();
    }

    function get_tsp_list ($offset = '0', $per_page = '25', $state_id = '', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($state_id != '') {
            $cond .= " AND a.state = ".$state_id;
        }
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND ( LOWER(a.tsp_name) LIKE LOWER('%".$search_txt."%') OR LOWER(a.company_reg_no) LIKE LOWER('%".$search_txt."%') OR LOWER(a.email_addr) LIKE LOWER('%".$search_txt."%') OR LOWER(a.company_phone_1) LIKE LOWER('%".$search_txt."%') )";
        } 
        
        $sql = "SELECT a.tsp_id, a.tsp_name, a.company_reg_no, a.company_phone_1, a.email_addr, a.status                
                FROM sfs_tsp a                 
                WHERE 1=1 ". $cond ;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY a.`tsp_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    }

    function get_all_tsp_list () {
        $sql = "SELECT tsp_id, tsp_name
                FROM sfs_tsp
                WHERE 1=1 ";

        $query = $this->db->query($sql);
        return $data = $query->result_array();
    }
    
    function getCities ($state_id) {
        $query = $this->db->select('city_id,state_id,city_name')->order_by('city_name','ASC')->get_where('sfs_tsp_city',array('state_id'=>$state_id));
        return $query->result_array();
    }
    
     function getTowns ($state_id,$city_id) {
        $query = $this->db->select('town_id, town_name')->order_by('town_name','ASC')->get_where('sfs_tsp_town',array('state_id'=>$state_id,'city_id'=>$city_id));
        return $query->result_array();
    }
    
    function get_tsp_details ($tsp_id) {
        $sql = "SELECT tsp_id, tsp_name, company_reg_no, addr_1, addr_2, postcode, state, town,
                city, company_phone_1, company_phone_2, fax, contact_person, contact_phone_1, contact_phone_2, 
                status, email_addr, password, company_info, attachment, tsp_type
                FROM sfs_tsp
                WHERE tsp_id = ". $tsp_id ;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function checkCity ($city_txt,$state_id) {
        $sql = "SELECT city_id
                FROM sfs_tsp_city a
                WHERE state_id = ". $state_id. " AND LOWER(city_name)='".strtolower($city_txt)."'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function insertCity ($city_txt,$state_id) {
        $data['city_name'] = $city_txt;
        $data['state_id'] = $state_id;
        $this->db->insert('sfs_tsp_city', $data);
        return $insert_id = $this->db->insert_id();  
    }
    
    function checkTown ($town_txt,$city_id,$state_id) {
        $sql = "SELECT town_id
                FROM sfs_tsp_town
                WHERE state_id= ". $state_id. " AND city_id= ". $city_id. " AND LOWER(town_name)='".strtolower($town_txt)."'";
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insertTown ($town_txt,$city_id,$state_id) {
        $data['town_name'] = $town_txt;
        $data['city_id'] = $city_id;
        $data['state_id'] = $state_id;
        $this->db->insert('sfs_tsp_town', $data);
        return $insert_id = $this->db->insert_id();  
    }
    
    function insert_tsp ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('sfs_tsp', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    } 
    
    function update_tsp ($data,$tsp_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('sfs_tsp', $data, array('tsp_id' => $tsp_id));
    }

    function insert_tsp_cat_mapp ($cat_id_array, $tsp_id) {
        $data = array();
        foreach ( $cat_id_array as $cat_id ) {
            $row = array(
                'cat_id' => $cat_id,
                'tsp_id' => $tsp_id
            );
            $data[] = $row;
        }
        $this->db->insert_batch('sfs_tsp_cat_mapp', $data);
    }

    function delete_tsp_cat_mapp ($tsp_id) {
        $this->db->where('tsp_id', $tsp_id);
        $this->db->delete('sfs_tsp_cat_mapp');
    }

    function get_sfs_service_list_of_category ($cat_id, $offset = '0', $per_page = '25', $search_txt ='') {

        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';

        if($cat_id != '') {
            $cond .= " AND b.cat_id = '" . $cat_id . "' ";
        }

        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND ( LOWER(a.service_name) LIKE '%".$search_txt."%' )";
        }

        $group = ' GROUP BY a.service_id ';

        $sql = "SELECT a.service_id, b.cat_id, a.service_name, a.commis_amount, a.commis_percent,
                a.no_of_question, a.quote_type, a.amount
                FROM sfs_services a
                LEFT JOIN sfs_service_cat_mapp b ON b.service_id = a.service_id 
                WHERE 1=1 ". $cond . $group;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY `service_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

    function get_sfs_service_details ($service_id) {
        $sql = "SELECT b.cat_id, a.service_id, a.service_name, a.commis_amount, a.commis_percent, a.picture,
                a.no_of_question, a.quote_type, a.amount, a.service_type, a.general_info, b.service_cat_mapp_id
                FROM sfs_services a
                LEFT JOIN sfs_service_cat_mapp b ON b.service_id = a.service_id
                WHERE a.service_id = ". $service_id ;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function insert_sfs_service ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('sfs_services', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function update_sfs_service ($data,$service_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('sfs_services', $data, array('service_id' => $service_id));
    }

    function insert_sfs_service_cat_mapp ($data) {
        $this->db->insert('sfs_service_cat_mapp', $data);
    }

    function update_sfs_service_cat_mapp ($data,$service_cat_mapp_id) {
        $this->db->update('sfs_service_cat_mapp', $data, array('service_cat_mapp_id' => $service_cat_mapp_id));
    }

    function get_credit_topup_list ( $tsp_id = '', $offset = '0', $per_page = '25', $search_txt ='' ) {

        $limit = '';
        if ($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';

        if ($tsp_id != '') {
            $cond .= " AND a.tsp_id = ". $tsp_id;
        }

        if ($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND ( (LOWER(a.reference) LIKE '%".$search_txt."%') )";
        }

        $sql = "SELECT a.tsp_id, a.topup_id, a.topup_amount, a.topup_date, a.pymt_mode, a.reference, b.tsp_name
                FROM sfs_credit_topup a
                LEFT JOIN sfs_tsp b ON a.tsp_id = b.tsp_id 
                WHERE 1 = 1 ". $cond;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY `topup_date` DESC".$limit;
        $query = $this->db->query($sql.$order_by);

        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

    function insert_sfs_credit_topup ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('sfs_credit_topup', $data);
    }

    function get_tsp_aval_credit ($tsp_id) {
        return $id = $this
            -> db
            -> select('aval_credit')
            -> where('tsp_id', $tsp_id)
            -> limit(1)
            -> get('sfs_tsp')
            -> row()
            ->aval_credit;
    }

    function delete_topup ($topup_id) {
        $this->db->where ('topup_id', $topup_id);
        $this->db->delete('sfs_credit_topup');
    }

    function get_sfs_credit_topup ($topup_id) {
        return $id = $this
            -> db
            -> select('topup_amount')
            -> where('topup_id', $topup_id)
            -> limit(1)
            -> get('sfs_credit_topup')
            -> row()
            ->topup_amount;
    }

    function get_credit_topup_detail ($topup_id) {
        $sql = "SELECT a.topup_id, a.tsp_id, a.topup_amount, a.topup_date, a.pymt_mode, a.reference, b.tsp_name
                FROM sfs_credit_topup a
                LEFT JOIN sfs_tsp b ON b.tsp_id = a.tsp_id
                WHERE a.topup_id = ". $topup_id ;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function update_sfs_credit_topup ($data, $topup_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('sfs_credit_topup', $data, array('topup_id' => $topup_id));
    }

    function get_service_selected_category_list ( $service_id ) {
        $sql = "SELECT cat_id
                FROM sfs_service_cat_mapp
                WHERE service_id = '$service_id'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function delete_service_cat_mapp ($service_id) {
        $this->db->where('service_id', $service_id);
        $this->db->delete('sfs_service_cat_mapp');
    }

    function insert_service_cat_mapp ($cat_id_array, $service_id) {
        $data = array();
        foreach ( $cat_id_array as $cat_id ) {
            $row = array(
                'cat_id' => $cat_id,
                'service_id' => $service_id
            );
            $data[] = $row;
        }
        $this->db->insert_batch('sfs_service_cat_mapp', $data);
    }

    function get_all_service_list () {

        $sql = "SELECT service_id, service_name
                FROM sfs_services
                ORDER BY `service_name` ASC";
        $query = $this->db->query($sql);
        return $query->result_array();

    }

    function get_question_list ($service_id, $offset = '0', $per_page = '25', $search_txt ='') {

        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';

        if($service_id != '') {
            $cond .= " AND a.service_id = ".$service_id;
        }

        if( $search_txt != '' ) {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND ( LOWER(a.question_name) LIKE '%". $search_txt ."%' OR LOWER(a.remarks) LIKE '%".$search_txt."%' )";
        }

        $sql = "SELECT a.service_id, a.question_id, a.question_name, a.remarks, a.amount, b.service_name
                FROM sfs_questions a
                LEFT JOIN sfs_services b ON a.service_id = b.service_id
                WHERE 1 = 1 ". $cond ;

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        if($service_id != '') {
            $order_by = " ORDER BY `sequence_no` ASC".$limit;
        } else {
            $order_by = " ORDER BY `question_name` ASC".$limit;
        }

        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

    function get_tsp_selected_state_list ( $tsp_id ) {
        $sql = "SELECT state_id
                FROM sfs_tsp_state_mapp
                WHERE tsp_id = '$tsp_id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function insert_tsp_state_mapp ($state_id_array, $tsp_id) {
        $data = array();
        foreach ( $state_id_array as $state_id ) {
            $row = array(
                'state_id' => $state_id,
                'tsp_id' => $tsp_id
            );
            $data[] = $row;
        }
        $this->db->insert_batch('sfs_tsp_state_mapp', $data);
    }

    function delete_tsp_state_mapp ($tsp_id) {
        $this->db->where('tsp_id', $tsp_id);
        $this->db->delete('sfs_tsp_state_mapp');
    }

    function insert_sfs_question ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('sfs_questions', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function update_sfs_question ($data, $question_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('sfs_questions', $data, array('question_id' => $question_id));
    }

    function insert_sfs_question_item ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('sfs_question_items', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }

    function update_sfs_question_item ($data, $question_item_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('sfs_question_items', $data, array('question_item_id' => $question_item_id));
    }

    function get_sfs_question_details ($question_id) {
        $sql = "SELECT service_id, question_name, remarks, amount, sequence_no, input_type, is_required
                FROM sfs_questions
                WHERE question_id = ". $question_id ;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function get_sfs_question_items ($question_id) {
        $sql = "SELECT question_item_id, question_id, question_item_detail, input_type, amount, price_guide, item_sequence_no
                FROM sfs_question_items
                WHERE question_id = ". $question_id ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function delete_sfs_question_items ($question_item_id) {
        $this->db->where('question_item_id', $question_item_id);
        return $this->db->delete('sfs_question_items');
    }
    
}
