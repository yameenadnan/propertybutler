<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_document_center extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        //$this->load->model('bms_doc_center_model');  
    }

    public function common_docs_list() {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Common Document Center';
        $data['page_header'] = '<i class="fa fa-file"></i> Common Document Center';
        $search_txt = '';
        if(isset($_GET['search_txt']) && trim($_GET['search_txt']) != '') {
            $search_txt = trim($_GET['search_txt']);
        }
        $data['common_docs'] = $this->bms_masters_model->getCommonDocs ($search_txt);
        //$data['properties'] = $this->bms_masters_model->getMyPropertiesWitTypeState ($search_txt);
        
        //$data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        //$data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
                
        //$property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';        
        $this->load->view('common_docs_list_view',$data);
	}
    
    public function add_doc() {
        
        //if(!empty($_POST)) { echo "<pre>";print_r($_POST);echo "</pre>"; }
        //if(!empty($_FILES)) { echo "<pre>";print_r($_FILES);echo "</pre>"; }    bms_property_docs    
    
		$data['browser_title'] = 'Property Butler | Common Document Center';
        $data['page_header'] = '<i class="fa fa-file"></i> Common Document Center <i class="fa fa-angle-double-right"></i> Add Document';
        //$data['properties'] = $this->bms_masters_model->getMyProperties ();
        //$data['docs_category'] = $this->bms_property_model->getPropertyDocCategory ();
        //$data['properties_docs'] = $this->bms_masters_model->getMyPropertiesDocs ();
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('common_doc_add_view',$data);
	}
    
    function add_doc_submit () {
        //echo "<pre>";print_r($_FILES);echo "</pre>"; exit;
        if(isset($_POST) && !empty($_POST['doc'])) {
            //$this->load->model('bms_task_model');
            $doc = $this->input->post('doc');
            $data['upload_err'] =  array ();
            if(!empty($_FILES)){
                
                $common_docs_upload = $this->config->item('common_docs_upload');                
                $this->load->library('upload');               
                
                
                $common_docs_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize($common_docs_upload);
                //echo "<pre>";print_r($task_file_upload);exit;
                if ( ! $this->upload->do_upload('document')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){
                    $doc['doc_file_name'] = $this->upload->data('file_name');
                    $doc['created_date'] = date("Y-m-d");        
                    $doc['created_by'] = $_SESSION['bms']['staff_id'];
                    $this->bms_masters_model->common_docs_insert($doc);
                    $_SESSION['flash_msg'] = 'Document has been Uploaded Successfully!';
                    redirect('index.php/bms_document_center/common_docs_list');
                } else {
                    $_SESSION['error_msg'] = 'Document upload Error Message: '.$this->upload->display_errors(); 
                    $this->add_doc();
                }            
                
            }                
        }
        //redirect('index.php/bms_property/docs_list');
    }
    
}