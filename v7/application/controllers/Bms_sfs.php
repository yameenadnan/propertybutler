<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
class Bms_sfs extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();        
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }

        $this->load->model('bms_sfs_model');
        $this->load->library('form_validation');
    }

    public function sfs_cat_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Category List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Category List';
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('sfs/sfs_cat_list_view',$data);
	} 
    
    public function get_sfs_cat_list() {
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_sfs_model->get_sfs_cat_list ($_POST['offset'],$_POST['rows'],$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    public function sfs_cat_form($cat_id = '') {
        
        $type = $cat_id != '' ? 'Edit' : 'Add';
		$data['browser_title'] = 'Property Butler | '.$type.' Category';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Category <i class="fa fa-angle-double-right"></i> '.$type.' Category';
        $data['cat_id'] = $cat_id;
        if($cat_id != '') {
            $data['cat'] = $this->bms_sfs_model->get_sfs_cat_details($cat_id);
        }
        
        $this->load->view('sfs/sfs_cat_form_view',$data);
	}    
       
    function sfs_cat_submit () {
        // echo $_SERVER['PATH_TRANSLATED'];

        $cat = $this->input->post('cat');
        $data['upload_err'] =  array ();

        if ( !empty($_FILES) && !empty($_FILES['upload_picture']['name']) && $_FILES['upload_picture']['size'] > 0 ) {

            $sfs_category_picture_upload = $this->config->item('sfs_category_picture_upload');

            if (!empty($_POST['picture_old'])) {
                unlink($sfs_category_picture_upload['upload_path'].$_POST['picture_old']);
            }

            $this->load->library('upload');

            $sfs_category_picture_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize( $sfs_category_picture_upload );

            if ( ! $this->upload->do_upload('upload_picture')) {
                array_push($data['upload_err'],$this->upload->display_errors());
            }

            if ( empty($data['upload_err']) ) {
                $cat['picture'] = $this->upload->data('file_name');
            }
        }

        if(!empty($cat)) {
            $type = 'add';

            $cat_id = $this->input->post('cat_id');
            if(!empty($cat_id)) {
                $type = 'edit';
                $this->bms_sfs_model->update_sfs_cat($cat,$cat_id);
            } else {
                $this->bms_sfs_model->insert_sfs_cat($cat);
            }
            
            $_SESSION['flash_msg'] = 'Category '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_sfs/sfs_cat_list');
    }
     
     
    public function tsp_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | TSP List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> TSP List';
        
        $data['states'] = $this->bms_sfs_model->getStates ();
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;  
        
        $this->load->view('sfs/tsp_list_view',$data);
	} 
    
    public function get_tsp_list() {
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_sfs_model->get_tsp_list ($_POST['offset'],$_POST['rows'],$this->input->post('state_id'),$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    function get_city () {
        header('Content-type: application/json');
        $state_id = trim($this->input->post('state_id')); 
        $cities = array();
        if($state_id) {
            $cities = $this->bms_sfs_model->getCities ($state_id);
        }
        echo json_encode($cities);
    }
    
    function get_town () {
        header('Content-type: application/json');
        $state_id = trim($this->input->post('state_id')); 
        $city_id = trim($this->input->post('city_id')); 
        $towns = array();
        if($state_id && $city_id) {
            $towns = $this->bms_sfs_model->getTowns ($state_id,$city_id);
        }
        echo json_encode($towns);
    }
    
    public function tsp_form ( $tsp_id = '' ) {
        
        $type = $tsp_id != '' ? 'Edit' : 'Add';
		$data['browser_title'] = 'Property Butler | '.$type.' TSP';
        $data['page_header'] = '<i class="fa fa-certificate"></i> TSP <i class="fa fa-angle-double-right"></i> '.$type.' TSP';
        $data['tsp_id'] = $tsp_id;
        if ($tsp_id != '') {
            $data['tsp'] = $this->bms_sfs_model->get_tsp_details($tsp_id);
            if( !empty($data['tsp']['state']) ) {
                $data['cities'] = $this->bms_sfs_model->getCities ($data['tsp']['state']);
            } 
            if(!empty($data['tsp']['state']) && !empty($data['tsp']['city'])) {
                $data['towns'] = $this->bms_sfs_model->getTowns ($data['tsp']['state'],$data['tsp']['city']);
            }

            $data['tsp_categories'] = $this->bms_sfs_model->get_tsp_selected_category_list ($tsp_id);

            $data['tsp_categories'] = array_map (function($value){
                return $value['cat_id'];
            } , $data['tsp_categories']);


            $data['tsp_states'] = $this->bms_sfs_model->get_tsp_selected_state_list ($tsp_id);

            $data['tsp_states'] = array_map (function($value){
                return $value['state_id'];
            } , $data['tsp_states']);

        }

        $data['states'] = $this->bms_sfs_model->getStates ();
        $data['categories'] = $this->bms_sfs_model->get_all_category_list ();
        
        $this->load->view('sfs/tsp_form_view',$data);
	}
    
    function tsp_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $tsp = $this->input->post('tsp');
        $category = $this->input->post('category');
        $tsp_states = $this->input->post('tsp_states');
        if ( !empty($tsp) ) {
            $type = 'add';
            $city = trim($this->input->post('tsp_city_txt'));
            if (!empty($city)) {
                //
                $tsp_city_info = $this->bms_sfs_model->checkCity ($city,$tsp['state']);
                if(empty($tsp_city_info['city_id'])) {
                    $tsp['city'] = $this->bms_sfs_model->insertCity ($city,$tsp['state']);
                } else {
                    $tsp['city'] = $tsp_city_info['city_id'];
                }
            }
            
            $town = trim($this->input->post('tsp_town_txt'));
            if(!empty($town)) {
                //
                $tsp_town_info = $this->bms_sfs_model->checkTown ($town,$tsp['city'],$tsp['state']);
                if(empty($tsp_town_info['town_id'])) {
                    $tsp['town'] = $this->bms_sfs_model->insertTown ($town,$tsp['city'],$tsp['state']);
                } else {
                    $tsp['town'] = $tsp_town_info['town_id'];
                }
            }

            $data['upload_err'] =  array ();

            if ( !empty($_FILES) && !empty($_FILES['attachment']['name']) && $_FILES['attachment']['size'] > 0 ) {

                $tsp_document_upload = $this->config->item('tsp_document_upload');

                if (!empty($_POST['attachment_old'])) {
                    unlink($tsp_document_upload['upload_path'].$_POST['attachment_old']);
                }

                $this->load->library('upload');

                $tsp_document_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize( $tsp_document_upload );

                if ( ! $this->upload->do_upload('attachment')) {
                    array_push($data['upload_err'],$this->upload->display_errors());
                }

                if ( empty($data['upload_err']) ) {
                    $tsp['attachment'] = $this->upload->data('file_name');
                }
            }

            $tsp_id = $this->input->post('tsp_id');
            if( !empty($tsp_id) ) {
                $type = 'edit';
                if ( !empty($tsp['password']) )
                    $tsp['password'] = md5( $tsp['password'] );
                else
                    unset($tsp['password']);
                $this->bms_sfs_model->update_tsp($tsp,$tsp_id);
            } else {
                $tsp['password'] = md5( $tsp['password'] );
                $tsp_id = $this->bms_sfs_model->insert_tsp($tsp);
            }

            // Assign categories
            // Assign categories
            if ( !empty ($category) ) {
                if ( $type == 'edit' ) {
                    $this->bms_sfs_model->delete_tsp_cat_mapp ($tsp_id);
                    $this->bms_sfs_model->insert_tsp_cat_mapp ($category, $tsp_id);
                } else {
                    $this->bms_sfs_model->insert_tsp_cat_mapp ($category, $tsp_id);
                }
            }

            // Assign states
            // Assign states
            if ( !empty ($tsp_states) ) {
                if ( $type == 'edit' ) {
                    $this->bms_sfs_model->delete_tsp_state_mapp ($tsp_id);
                    $this->bms_sfs_model->insert_tsp_state_mapp ($tsp_states, $tsp_id);
                } else {
                    $this->bms_sfs_model->insert_tsp_state_mapp ($tsp_states, $tsp_id);
                }
            }
            
            $_SESSION['flash_msg'] = 'TSP '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_sfs/tsp_list');
    }

    public function sfs_service_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
        $data['browser_title'] = 'Property Butler | Service List';
        $data['page_header'] = '<i class="fa fa-cog"></i> Service Setup <i class="fa fa-angle-double-right"></i> Service List';

        $data['categories'] = $this->bms_sfs_model->get_all_category_list ();
        $data ['cat_id'] = !empty( $_GET['cat_id'] ) != '' ? trim($_GET['cat_id']) : '';

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;

        $this->load->view('sfs/sfs_service_list_view',$data);
    }

    public function get_sfs_service_list() {

        header('Content-type: application/json');

        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_sfs_model->get_sfs_service_list_of_category ($_POST['cat_id'], $_POST['offset'],$_POST['rows'],$search_txt);
        }
        echo json_encode ($staff);
    }

    public function sfs_service_form($service_id = '') {
        $type = $service_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' Service';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Service Setup <i class="fa fa-angle-double-right"></i> '.$type.' Service';
        $data['categories'] = $this->bms_sfs_model->get_all_category_list ();
        if($service_id != '') {
            $data['service_info'] = $this->bms_sfs_model->get_sfs_service_details($service_id);
            $data['service_id'] = $service_id;
            $data['service_categories'] = $this->bms_sfs_model->get_service_selected_category_list ($service_id);
            $data['service_categories'] = array_map (function($value){
                return $value['cat_id'];
            } , $data['service_categories']);
        }

        $this->load->view('sfs/sfs_service_form_view',$data);
    }

    function sfs_service_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $service_info = $this->input->post('service_info');
        $category = $this->input->post('category');

        if ( !empty($service_info) ) {
            $type = 'add';

            $data['upload_err'] =  array ();

            if ( !empty($_FILES) && !empty($_FILES['upload_picture']['name']) && $_FILES['upload_picture']['size'] > 0 ) {

                $sfs_service_picture_upload = $this->config->item('sfs_service_picture_upload');

                if (!empty($_POST['picture_old'])) {
                    unlink($sfs_service_picture_upload['upload_path'].$_POST['picture_old']);
                }

                $this->load->library('upload');

                $sfs_service_picture_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize( $sfs_service_picture_upload );

                if ( ! $this->upload->do_upload('upload_picture')) {
                    array_push($data['upload_err'],$this->upload->display_errors());
                }

                if ( empty($data['upload_err']) ) {
                    $service_info['picture'] = $this->upload->data('file_name');
                }
            }

            $service_id = $this->input->post('service_id');
            if( !empty($service_id) ) {
                $type = 'edit';
                $this->bms_sfs_model->update_sfs_service($service_info,$service_id);
            } else {
                $service_id = $this->bms_sfs_model->insert_sfs_service($service_info);
            }

            // Assign categories
            // Assign categories
            if ( !empty ($category) ) {
                if ( $type == 'edit' ) {
                    $this->bms_sfs_model->delete_service_cat_mapp ($service_id);
                    $this->bms_sfs_model->insert_service_cat_mapp ($category, $service_id);
                } else {
                    $this->bms_sfs_model->insert_service_cat_mapp ($category, $service_id);
                }
            }

            $_SESSION['flash_msg'] = 'Service '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_sfs/sfs_service_list');
    }

    public function sfs_company_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
        $data['browser_title'] = 'Property Butler | Topup Management';
        $data['page_header'] = '<i class="fa fa-cog"></i> Topup Management <i class="fa fa-angle-double-right"></i> Topup List';

        $data['tsp_list'] = $this->bms_sfs_model->get_all_tsp_list ();
        $data ['tsp_id'] = !empty( $_GET['tsp_id'] ) != '' ? trim($_GET['tsp_id']) : '';

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;

        $this->load->view('sfs/sfs_credit_management_list_view',$data);
    }

    public function get_credit_topup_list () {

        header('Content-type: application/json');

        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_sfs_model->get_credit_topup_list ($_POST['tsp_id'], $_POST['offset'],$_POST['rows'],$search_txt);
        }
        echo json_encode($staff);
    }

    public function add_topup_submit () {

        // echo "<pre>";print_r($_POST);echo "</pre>";exit;

        $topup = $this->input->post('topup');

        if ( !empty($topup['hidd_tsp_id']) &&  !empty($topup['topup_amount']) && !empty($topup['topup_date']) && !empty($topup['topup_time']) ) {

            $topup['tsp_id'] = $topup['hidd_tsp_id'];
            $topup['topup_date'] = date ('Y-m-d', strtotime($topup['topup_date']));
            $topup['topup_time'] = date("H:i:00", strtotime($topup['topup_time']));
            $topup['topup_date'] = $topup['topup_date'] . ' ' . $topup['topup_time'];
            unset ($topup['topup_time']);
            unset ($topup['hidd_tsp_id']);
            $type = 'Add';
            if ( !empty ($topup['topup_id']) ) {
                $type = 'edit';
                $topup_amount_old = $this->input->post('topup_amount_old');

                // tsp NOT changed
                if ( $topup['tsp_id'] == $this->input->post('hidd_tsp_id_old') ) {

                    $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ($topup['tsp_id']);
                    $new_aval_credit = 0;
                    if ( $topup_amount_old == $topup['topup_amount'] ) {
                        $new_aval_credit = $aval_credit;
                    } elseif ( $topup_amount_old > $topup['topup_amount'] ) {
                        $aval_credit = $aval_credit - ( $topup_amount_old - $topup['topup_amount'] );
                        $new_aval_credit = $aval_credit;
                    } elseif ( $topup_amount_old < $topup['topup_amount'] ) {
                        $aval_credit = $aval_credit + ($topup['topup_amount'] - $topup_amount_old);
                        // echo ($topup['topup_amount'] - $topup_amount_old) . "<br>";
                        $new_aval_credit = $aval_credit;
                    }
                    $sfs_tsp_data = array (
                        'aval_credit' => $new_aval_credit
                    );
                    $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $topup['tsp_id']);

                } // tsp changed
                elseif ( $topup['tsp_id'] != $this->input->post('hidd_tsp_id_old') ) {
                    if ( $topup_amount_old == $topup['topup_amount'] ) {
                        $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ($topup['tsp_id']);
                        $new_aval_credit = $aval_credit + $topup['topup_amount'];
                        $sfs_tsp_data = array (
                            'aval_credit' => $new_aval_credit
                        );
                        $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $topup['tsp_id']);

                        $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ( $this->input->post('hidd_tsp_id_old') );
                        $new_aval_credit = $aval_credit - $topup['topup_amount'];
                        $sfs_tsp_data = array (
                            'aval_credit' => $new_aval_credit
                        );
                        $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $this->input->post('hidd_tsp_id_old') );
                    } elseif ( $topup_amount_old != $topup['topup_amount'] ) {
                        $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ($topup['tsp_id']);
                        $new_aval_credit = $aval_credit + $topup['topup_amount'];
                        $sfs_tsp_data = array (
                            'aval_credit' => $new_aval_credit
                        );
                        $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $topup['tsp_id']);

                        $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ( $this->input->post('hidd_tsp_id_old') );
                        $new_aval_credit = $aval_credit - $topup_amount_old;
                        $sfs_tsp_data = array (
                            'aval_credit' => $new_aval_credit
                        );
                        $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $this->input->post('hidd_tsp_id_old') );
                    }
                }
                $topup_id = $topup['topup_id'];
                unset ( $topup['topup_id'] );
                $this->bms_sfs_model->update_sfs_credit_topup ($topup, $topup_id);
            } else {
                $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ($topup['tsp_id']);
                $sfs_tsp_data = array (
                    'aval_credit' => $aval_credit + $topup['topup_amount']
                );
                $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $topup['tsp_id']);
                unset ( $topup['topup_id'] );
                $this->bms_sfs_model->insert_sfs_credit_topup ($topup);
            }

            $_SESSION['flash_msg'] = 'Topup ' . (($type =='Add') ? 'Added':'Updated') . ' Successfully!';
            redirect('index.php/bms_sfs/sfs_company_list/?tsp_id=' . $topup['tsp_id']);

        } else {
            $_SESSION['flash_msg'] = '<div style="background-color:red; width: 100%;">Topup Un-successfully!</div>';
            redirect('index.php/bms_sfs/sfs_company_list/?tsp_id=' . $topup['tsp_id']);
        }
    }

    function get_server_date_time () {
        header('Content-type: application/json');
        $data['date'] = date('d-m-Y');
        $data['time'] = date('h:i A');
        $data['tsp_list'] = $this->bms_sfs_model->get_all_tsp_list ();
        echo json_encode($data);
    }

    function delete_credit_topup () {
        $topup_id = $this->input->get('topup_id');
        $tsp_id = $this->input->get('tsp_id');

        $topup_amount = $this->bms_sfs_model->get_sfs_credit_topup ($topup_id);
        $aval_credit = $this->bms_sfs_model->get_tsp_aval_credit ( $tsp_id );
        $sfs_tsp_data = array (
            'aval_credit' => $aval_credit - $topup_amount
        );
        $this->bms_sfs_model->update_tsp ($sfs_tsp_data, $tsp_id);

        $this->bms_sfs_model->delete_topup ($topup_id);
        redirect ('index.php/bms_sfs/sfs_company_list/?tsp_id=' . $tsp_id);
    }

    function get_credit_topup_detail () {
        header('Content-type: application/json');
        $topup_id = $this->input->post('topup_id');
        $tsp_id = $this->input->post('tsp_id');
        $data['credit_topup'] = $this->bms_sfs_model->get_credit_topup_detail ($topup_id);
        $data['tsp_list'] = $this->bms_sfs_model->get_all_tsp_list ();
        echo json_encode ($data);
    }

    public function sfs_question_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
        $data['browser_title'] = 'Property Butler | Question List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Question List';

        $data['services'] = $this->bms_sfs_model->get_all_service_list ();

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;

        $this->load->view('sfs/sfs_question_list_view',$data);
    }

    public function get_question_list() {

        header('Content-type: application/json');

        $question_list = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = !empty($search_txt) ? strtolower($search_txt) : '';
            $question_list = $this->bms_sfs_model->get_question_list ($_POST['service_id'], $_POST['offset'],$_POST['rows'],$search_txt);
        }

        echo json_encode($question_list);
    }

    public function sfs_question_form ($question_id = '') {
        $type = $question_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' Question';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Question Setup <i class="fa fa-angle-double-right"></i> '.$type.' Question';
        $data['services'] = $this->bms_sfs_model->get_all_service_list ();
        if($question_id != '') {
            $data['question_detail'] = $this->bms_sfs_model->get_sfs_question_details($question_id);
            $data['question_items'] = $this->bms_sfs_model->get_sfs_question_items($question_id);
            $data['question_id'] = $question_id;
        }

        $this->load->view('sfs/sfs_question_form_view',$data);
    }

    public function sfs_question_form_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $question_id = $this->input->post('question_id');
        $question_detail = $this->input->post('question_detail');
        $question_items = $this->input->post('question_items');
        $type = $question_id != '' ? 'Edit' : 'Add';

        if ( !empty ( $question_detail['service_id'] ) && !empty ( $question_detail['question_name'] ) ) {
            if ( $type == 'Add' ) {
                $question_id = $this->bms_sfs_model->insert_sfs_question ($question_detail);
            } else {
                $this->bms_sfs_model->update_sfs_question ($question_detail, $question_id);
            }

            if ( !empty ($question_items) ) {
                foreach ( $question_items['question_item_detail'] as $key => $val ) {
                    $question_items_data = array (
                        'question_id' => $question_id,
                        'question_item_detail' => $question_items['question_item_detail'][$key],
                        'input_type' => $question_items['input_type'][$key],
                        'amount' => $question_items['amount'][$key],
                        'price_guide' => $question_items['price_guide'][$key],
                        'item_sequence_no' => $question_items['item_sequence_no'][$key],
                    );
                    if ( !empty($question_items['question_item_id'][$key]) ) {
                        $this->bms_sfs_model->update_sfs_question_item ($question_items_data, $question_items['question_item_id'][$key]);
                    } else {
                        $this->bms_sfs_model->insert_sfs_question_item ($question_items_data);
                    }
                }
            }
            $_SESSION['flash_msg'] = 'Question ' . (($type =='Add') ? 'Added':'Updated') . ' Successfully!';
            redirect('index.php/bms_sfs/sfs_question_list/?question_id=' . $question_id);
        }
    }


    function delete_question_item () {
        header('Content-type: application/json');
        $question_item_id = $this->input->post('question_item_id');
        $result = $this->bms_sfs_model->delete_sfs_question_items ($question_item_id);
        echo json_encode($result);
    }
}