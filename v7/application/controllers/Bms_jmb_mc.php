<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_jmb_mc extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff' || !in_array('4',$_SESSION['bms']['access_mod'])) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //if(!in_array($this->uri->segment(2), array('get_jmb_mc_list','check_email')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_jmb_mc_model');         
    }

    public function jmb_mc_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | JMB / MC List';
        $data['page_header'] = '<i class="fa fa-users"></i> JMB / MC <i class="fa fa-angle-double-right"></i> JMB / MC List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('jmb_mc/jmb_mc_list_view',$data);
	}
    
    public function get_jmb_mc_list() {        
        
        header('Content-type: application/json');        
        
        $jmb_mcs = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $jmb_mcs = $this->bms_jmb_mc_model->get_jmb_mc_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }      
                
        echo json_encode($jmb_mcs);
	}
    
    public function add_jmb_mc($member_id = '') {
        
        $type = $member_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' JMB / MC';
        $data['page_header'] = '<i class="fa fa-info-cog"></i> JMB / MC <i class="fa fa-angle-double-right"></i> '.$type.' JMB / MC';
        $data['member_id'] = $member_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['positions'] = $this->bms_jmb_mc_model->getPositions ();
        if($member_id != '') {
            $data['jmb_mc'] = $this->bms_jmb_mc_model->get_jmb_mc_details($member_id);
            if(empty($data['jmb_mc'])) {
                redirect('index.php/bms_dashboard/index'); 
            }                     
        }        
        //echo "<pre>";print_r($data['jmb_mc']);echo "</pre>"; 
        $this->load->view('jmb_mc/add_jmb_mc_view',$data);
	} 
    
    function get_unit () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        //$block_id = trim($this->input->post('block_id'));
        
        $unit = array();
        if($property_id) {
            $unit = $this->bms_jmb_mc_model->getUnit ($property_id);
        }
        echo json_encode($unit);
    }
    
    function add_jmb_mc_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $jmb_mc_id = $this->input->post('member_id');
        
        $jmb_mc_info = $this->input->post('jmb_mc');
        $jmb_mc_info['elect_date'] = date('Y-m-d', strtotime($jmb_mc_info['elect_date']));
        $type = 'add';
        if(isset($jmb_mc_info['unit_id']) && trim($jmb_mc_info['unit_id']) !='') {
            if($jmb_mc_id) {
                $this->bms_jmb_mc_model->update_jmb_mc($jmb_mc_info,$jmb_mc_id);
                $type = 'edit';                
            } else {
                $jmb_mc_id = $this->bms_jmb_mc_model->insert_jmb_mc($jmb_mc_info);                
            }       
            
            $_SESSION['flash_msg'] = 'JMB / MC '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_jmb_mc/jmb_mc_list/0/25?property_id='.$jmb_mc_info['property_id']);
        
    }
    
}