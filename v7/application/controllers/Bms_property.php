<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_property extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : '192.168.1.170').$_SERVER['REQUEST_URI']);	       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_property_model');  
    }

    public function properties_list() {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Properties List';
        $data['page_header'] = '<i class="fa fa-building"></i> Properties List';
        $search_txt = '';
        if(isset($_GET['search_txt']) && trim($_GET['search_txt']) != '') {
            $search_txt = trim($_GET['search_txt']);
        }

        $data['hr_access_desi'] = $this->config->item('hr_access_desi');
        if(in_array($_SESSION['bms']['designation_id'],$data['hr_access_desi'])|| in_array($_SESSION['bms']['designation_id'],array(27)))
            $data['properties'] = $this->bms_masters_model->getAllPropertiesWitTypeState ($search_txt);
        else 
            $data['properties'] = $this->bms_masters_model->getMyPropertiesWitTypeState ($search_txt);
        
        //$data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        //$data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
                
        $property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';        
        $this->load->view('properties/properties_list_view',$data);
	}
    
    function add_property ($property_id='') {
        
        $data['browser_title'] = 'Property Butler | Properties';
        $data['page_header'] = '<i class="fa fa-building"></i> Properties <i class="fa fa-angle-double-right"></i> Add Property';
        $data['property_id'] = $property_id;

        //$data['property_chang_hist_arr'] = $data['property_prem_quit_hist_arr'] = array();
        $data['hr_access_desi'] = $this->config->item('hr_access_desi');

        if(in_array($_SESSION['bms']['designation_id'],$data['hr_access_desi'])|| in_array($_SESSION['bms']['designation_id'],array(27))) {
            if(!empty($property_id)) {
                $data['property_info'] = $this->bms_property_model->getPropertyDetails ($property_id);

                $data['blocks'] = $this->bms_masters_model->getBlocks ($property_id);

                $data['tiers'] = $this->bms_masters_model->getTiers ($property_id);

                $data['developer'] = $this->bms_masters_model->getDeveloperCredentials ($property_id);
                // $data['acbs'] = $this->bms_masters_model->getAcbs ($property_id);

                //$data['property_chang_hist'] = $this->bms_property_model->getPropertyChargHist ($property_id);
                //$data['property_chang_hist_arr'] = !empty($data['property_chang_hist']) ? explode(',',$data['property_chang_hist']['charg_hist_cat']) : array();

                //$data['property_prem_quit_hist'] = $this->bms_property_model->getPropertyPremQuitHist ($property_id);
                //$data['property_prem_quit_hist_arr'] = !empty($data['property_chang_hist']) ? explode(',',$data['property_prem_quit_hist']['cat']) : array();

            }
        } else {
            redirect('index.php/bms_property/properties_list');
        }

        //echo "<pre>";print_r($data['blocks']);echo "</pre>";
        $data['property_type'] = $this->bms_property_model->getPropertyType ();
        $data['property_state'] = $this->bms_property_model->getPropertyState ();
        $data['countries_mas'] = $this->bms_masters_model->getCountries ();
        $this->load->view('properties/property_add_view',$data);
    }

    function property_detail_popup () {
        $property_id = $this->input->get('property_id');
        $data['property_info'] = $this->bms_property_model->get_property_profile_details ($property_id);
        $data['blocks'] = $this->bms_masters_model->getBlocks ($property_id);
        $this->load->view('properties/property_detail_popup_view',$data);
    }

    function getPropertyChargHistDetails ($property_id,$cat_id) {
        
        $data['property_chang_hist_det'] = $this->bms_property_model->getPropertyChargHistDetails ($property_id,$cat_id);
        $data['charg_cat'] = $this->config->item('charg_cat');
        if($cat_id == 1)
            $data['calcul_base'] = $this->config->item('calcul_base');
        $data['property_id'] = $property_id;
        $data['cat_id'] = $cat_id;
        $this->load->view('properties/chang_hist_details_view',$data);
    }
    
    function getPropertyPremQuitHistDetails ($property_id,$cat_id) {
        
        $data['property_prem_quit_hist_det'] = $this->bms_property_model->getPropertyPremQuitHistDetails ($property_id,$cat_id);
        $data['prem_quit_cat'] = $this->config->item('prem_quit_cat');
        
        $data['property_id'] = $property_id;
        $data['cat_id'] = $cat_id;
        $this->load->view('properties/prem_quit_hist_details_view',$data);
    }
    
    function add_property_submit () {
        // echo "<pre>";print_r($_POST);print_r($_FILES); exit;
        if(isset($_POST) && !empty($_POST['property'])) {
            //$this->load->model('bms_task_model');
            $property = $this->input->post('property');
            $property['insur_prem_date'] = !empty($property['insur_prem_date']) ? date('Y-m-d',strtotime($property['insur_prem_date'])) : '0000-00-00';
            $property['quit_rent_paid_on'] = !empty($property['quit_rent_paid_on']) ? date('Y-m-d',strtotime($property['quit_rent_paid_on'])) : '0000-00-00';
            $property['e_billing_start_date'] = !empty($property['e_billing_start_date']) ? date('Y-m-d',strtotime($property['e_billing_start_date'])) : '0000-00-00';

            $property['late_payment'] = !empty($property['late_payment']) ? $property['late_payment'] : '0';
            $property['late_pay_percent'] = !empty($property['late_pay_percent']) ? $property['late_pay_percent'] : '';
            $property['late_pay_effect_from'] = !empty($property['late_pay_effect_from']) ? date('Y-m-d',strtotime($property['late_pay_effect_from'])) : '0000-00-00';
            $property['late_pay_grace_type'] = !empty($property['late_pay_grace_type']) ? $property['late_pay_grace_type'] : '0';
            $property['late_pay_grace_value'] = !empty($property['late_pay_grace_value']) ? $property['late_pay_grace_value'] : '';

            $property['acb_grace_value'] = !empty($property['acb_grace_value']) ? $property['acb_grace_value'] : null;
            $property['late_pay_grace_value'] = !empty($property['late_pay_grace_value']) ? $property['late_pay_grace_value'] : null;
            $property['acb_unblock_charges_value'] = !empty($property['acb_unblock_charges_value']) ? $property['acb_unblock_charges_value'] : null;
            $property['payment_fpx'] = !empty($property['payment_fpx']) ? $property['payment_fpx'] : null;
            $property['payment_cc_card'] = !empty($property['payment_cc_card']) ? $property['payment_cc_card'] : null;
            $property['account_status'] = !empty($property['account_status']) ? $property['account_status'] : '0';
            $property['bill_generate_date'] = !empty($property['bill_generate_date']) ? date('Y-m-d',strtotime($property['bill_generate_date'])) : '0000-00-00';;

            $property['mob_app_issue'] = !empty($property['mob_app_issue']) ? $property['mob_app_issue'] : '0';
            $property['mob_app_billing'] = !empty($property['mob_app_billing']) ? $property['mob_app_billing'] : '0';
            $property['mob_app_defect'] = !empty($property['mob_app_defect']) ? $property['mob_app_defect'] : '0';
            $property['mob_app_fasc_book'] = !empty($property['mob_app_fasc_book']) ? $property['mob_app_fasc_book'] : '0';
            $property['mob_app_pro_doc'] = !empty($property['mob_app_pro_doc']) ? $property['mob_app_pro_doc'] : '0';
            $property['mob_app_daily_rpt'] = !empty($property['mob_app_daily_rpt']) ? $property['mob_app_daily_rpt'] : '0';
            $property['mob_app_survey_form'] = !empty($property['mob_app_survey_form']) ? $property['mob_app_survey_form'] : '0';
            $property['mob_app_visit_list'] = !empty($property['mob_app_visit_list']) ? $property['mob_app_visit_list'] : '0';
            $property['mob_app_prebook'] = !empty($property['mob_app_prebook']) ? $property['mob_app_prebook'] : '0';
            $property['mob_app_freq_visit'] = !empty($property['mob_app_freq_visit']) ? $property['mob_app_freq_visit'] : '0';
            $property['mob_app_note_to_guard'] = !empty($property['mob_app_note_to_guard']) ? $property['mob_app_note_to_guard'] : '0';
            $property['mob_app_panic_alert'] = !empty($property['mob_app_panic_alert']) ? $property['mob_app_panic_alert'] : '0';

            // $property['electricity'] = !empty($property['electricity']) ? $property['electricity'] : '0';
            
            if(empty($property['water'])) {
                $property['water'] = '0';
                $property['water_min_charg'] = '';
                $property['water_charge_per_unit_rate_1'] = '';
                $property['water_charge_per_unit_rate_2'] = '';
                $property['water_charge_range'] = '';
            }
            
            if(!empty($property['total_units'])) {
                $property['total_units'] = floatval(preg_replace('/[^\d.]/', '', $property['total_units']));
            }
            
            if(!empty($property['tax_percentage'])) {
                $property['tax_percentage'] = floatval(preg_replace('/[^\d.]/', '', $property['tax_percentage']));
            }
            if(!empty($property['sinking_fund'])) {
                $property['sinking_fund'] = floatval(preg_replace('/[^\d.]/', '', $property['sinking_fund']));
            }
            if(!empty($property['insurance_prem'])) {
                $property['insurance_prem'] = floatval(preg_replace('/[^\d.]/', '', $property['insurance_prem']));
            }            
            if(!empty($property['quit_rent'])) {
                $property['quit_rent'] = floatval(preg_replace('/[^\d.]/', '', $property['quit_rent']));
            }
            if(!empty($property['monthly_billing'])) {
                $property['monthly_billing'] = floatval(preg_replace('/[^\d.]/', '', $property['monthly_billing']));
            }

            $data['upload_err'] =  array ();
            
            if(!empty($_FILES) && !empty($_FILES['prop_logo']['name']) && $_FILES['prop_logo']['size'] > 0) {
                
                $property_logo_upload = $this->config->item('property_logo_upload');
                
                if(!empty($_POST['property_logo_old'])) {
                    unlink($property_logo_upload['upload_path'].$_POST['property_logo_old']);
                }

                $this->load->library('upload');
                                
                $property_logo_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize($property_logo_upload);
                //echo "<pre>";print_r($task_file_upload);exit;
                if ( ! $this->upload->do_upload('prop_logo')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){
                    $property['logo'] = $this->upload->data('file_name');                    
                }
            }



            if(!empty($_POST['property_id'])) {
                $property_id = $_POST['property_id'];
                $this->bms_property_model->update_property($property,$property_id);
                $_SESSION['flash_msg'] = 'Property Updated Successfully!';
            } else {
                $property_id = $this->bms_property_model->insert_property($property);
                $_SESSION['flash_msg'] = 'Property Added Successfully!';
            }

            if ( $property['property_under'] ) {
                // set developer credentials
                $developer = $this->input->post('developer');
                if(!empty($developer)) {
                    $developer_all_id = array_filter(array_values($developer['property_dev_id']));
                    if(!empty($developer_all_id)) {
                        $this->bms_property_model->removeDeveloper ($developer_all_id,$property_id);
                    }

                    foreach ($developer['email_addr'] as $bKey=>$bVal) {
                        if($developer['email_addr'][$bKey] != '') {
                            $update_data_developer['property_id'] = $property_id;
                            $update_data_developer['email_addr'] = $developer['email_addr'][$bKey];
                            $update_data_developer['designation'] = 'Developer';
                            if(empty($developer['property_dev_id'][$bKey])) {
                                // insert process
                                $update_data_developer['password'] = md5($developer['password'][$bKey]);
                                $this->bms_property_model->insertDeveloper ($update_data_developer);
                            } else {
                                // update process
                                if ( !empty ( $developer['password'][$bKey] ) )
                                    $update_data_developer['password'] = md5($developer['password'][$bKey]);
                                $this->bms_property_model->updateDeveloper ($update_data_developer,$developer['property_dev_id'][$bKey]);
                            }
                        }
                    }
                }
            }

            // set tier information
            $tier = $this->input->post('tier');

            if(!empty($tier)) {
                $tier_all_id = array_filter(array_values($tier['tier_id']));
                if(!empty($tier_all_id)) {
                    $this->bms_property_model->removeTier ($tier_all_id,$property_id);
                }

                foreach ($tier['tier_name'] as $bKey=>$bVal) {
                    if($tier['tier_name'][$bKey] != '') {
                        $update_data_tier['property_id'] = $property_id;
                        $update_data_tier['tier_name'] = $tier['tier_name'][$bKey];
                        $update_data_tier['tier_value'] = $tier['tier_value'][$bKey];
                        if(empty($tier['tier_id'][$bKey])) {
                            // insert process
                            $this->bms_property_model->insertTier ($update_data_tier);
                        } else {
                            // update process
                            $this->bms_property_model->updateTier ($update_data_tier,$tier['tier_id'][$bKey]);
                        }
                    }
                }
            }

            // set block information
            $blocks = $this->input->post('blocks');

            if(!empty($blocks)) {
                $blocks_all_id = array_filter(array_values($blocks['block_id']));
                if(!empty($blocks_all_id)) {
                    $this->bms_property_model->removeBlocks ($blocks_all_id,$property_id);
                }
                
                foreach ($blocks['block_name'] as $bKey=>$bVal) {
                    if($blocks['block_name'][$bKey] != '') {
                        $update_data['property_id'] = $property_id;
                        $update_data['block_name'] = $blocks['block_name'][$bKey];                    
                        if(empty($blocks['block_id'][$bKey])) {
                            // insert process
                            $this->bms_property_model->insertBlock ($update_data);
                        } else {
                            // update process                        
                            $this->bms_property_model->updateBlock ($update_data,$blocks['block_id'][$bKey]);
                        }
                    }
                }
            }
            
            // update unit table if the calculation based on Sq. Foot / Share Unit
            if(isset($property['calcul_base']) && in_array ($property['calcul_base'],array(1,2)) ) {
                $units = $this->bms_property_model->getUnits ($property_id,$property['calcul_base']);
                if(!empty($units) && !empty($property['sinking_fund']) && (($property['calcul_base'] == 1 && !empty($property['per_sq_feet'])) || ($property['calcul_base'] == 2 && !empty($property['per_share_unit'])))) {
                    $field = $property['calcul_base'] == 1 ? 'square_feet' : 'share_unit';
                    $per_field = $property['calcul_base'] == 1 ? 'per_sq_feet' : 'per_share_unit';
                    $tot_field = $property['calcul_base'] == 1 ? 'tot_sq_feet' : 'tot_share_unit';
                    foreach($units as $uKey=>$uVal) {
                        if(!empty($uVal[$field]) && is_numeric($uVal[$field])) {
                            $calc_data['service_charge'] = number_format(($uVal[$field] * $property[$per_field]),2,'.', '');
                            $calc_data['sinking_fund']   = number_format((($calc_data['service_charge']*$property['sinking_fund'])/100),2,'.', '');
                            $calc_data['insurance_prem'] = number_format((($property['insurance_prem']/$property[$tot_field])*$uVal[$field]),2,'.', '');
                            $calc_data['quit_rent']      = number_format((($property['quit_rent']/$property[$tot_field])*$uVal[$field]),2,'.', '');
                            //$this->bms_property_model->setUnit($calc_data,$uVal['unit_id']);
                            $charge_id = $this->bms_property_model->getMandatoryCharges ($uVal['unit_id']);
                            $charge_id_arr = explode (',',$charge_id['charge_type']);
                            for($j=1;$j<=4;$j++) {
                                switch($j) {
                                    case '1': $char_data['amount'] = $calc_data['service_charge']; break;
                                    case '2': $char_data['amount'] = $calc_data['sinking_fund']; break;
                                    case '3': $char_data['amount'] = $calc_data['insurance_prem']; break;
                                    case '4': $char_data['amount'] = $calc_data['quit_rent']; break;                                    
                                }
                                $char_data['charge_type_id'] = $char_data['charge_code_id'] = $j;
                                $char_data['pay_by'] = 1;
                                $char_data['unit_id'] = $uVal['unit_id'];
                                $char_data['e_billing_start_date'] = !empty($property['e_billing_start_date']) ? date('Y-m-d',strtotime($property['e_billing_start_date'])) : '0000-00-00';
                                if(in_array($j,$charge_id_arr)) {                                    
                                    $charge_id = $this->bms_property_model->updateMandatoryCharges ($char_data,$uVal['unit_id'],$char_data['charge_type_id']);
                                } else {                                    
                                    $charge_id = $this->bms_property_model->insertMandatoryCharges ($char_data);
                                }
                            }                            
                        }                            
                    }
                }
            }
            
            redirect('index.php/bms_property/properties_list');                
        }
    }
    
    public function docs_list($offset=0, $per_page = 25) {
        
        //if(!empty($_POST)) { echo "<pre>";print_r($_POST);echo "</pre>"; }
        //if(!empty($_FILES)) { echo "<pre>";print_r($_FILES);echo "</pre>"; }    bms_property_docs    
    
		$data['browser_title'] = 'Property Butler | Properties Documents';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Properties Documents';
        $data['property_id'] = $property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        $doc_cat_id = isset($_GET['doc_cat_id']) && trim($_GET['doc_cat_id']) != '' ? trim ($_GET['doc_cat_id']) : '';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['docs_category'] = $this->bms_property_model->getPropertyDocCategory ();
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;   
        
        //$data['properties_docs'] = $this->bms_property_model->getMyPropertiesDocs ($property_id,$doc_cat_id);
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('properties/prop_docs_list_view',$data);
	}
    
    public function docs_list_jmb($offset=0, $per_page = 25) {
        
        //if(!empty($_POST)) { echo "<pre>";print_r($_POST);echo "</pre>"; }
        //if(!empty($_FILES)) { echo "<pre>";print_r($_FILES);echo "</pre>"; }    bms_property_docs    
    
		$data['browser_title'] = 'Property Butler | Properties Documents';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Properties Documents';
        $property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : $_SESSION['bms']['property_id'];
        $doc_cat_id = isset($_GET['doc_cat_id']) && trim($_GET['doc_cat_id']) != '' ? trim ($_GET['doc_cat_id']) : '';
        $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        $data['docs_category'] = $this->bms_property_model->getPropertyDocCategory ();
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;  
         
        //$data['properties_docs'] = $this->bms_property_model->getMyPropertiesDocsJmb ($property_id,$doc_cat_id);
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('properties/prop_docs_list_view',$data);
	}
    
    public function get_doc_list() {        
        
        header('Content-type: application/json');        
        
        $docs = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            if($this->input->post('user_type') == 'jmb') {
                $docs = $this->bms_property_model->getMyPropertiesDocsJmb ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'),$this->input->post('doc_cat_id'), $search_txt);
            } else {
                $docs = $this->bms_property_model->getMyPropertiesDocs ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'),$this->input->post('doc_cat_id'), $search_txt);    
            }
            
        }      
                
        echo json_encode($docs);
	}
    
    public function add_doc() {
        
        //if(!empty($_POST)) { echo "<pre>";print_r($_POST);echo "</pre>"; }
        //if(!empty($_FILES)) { echo "<pre>";print_r($_FILES);echo "</pre>"; }    bms_property_docs    
    
		$data['browser_title'] = 'Property Butler | Properties Documents';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Properties Documents <i class="fa fa-angle-double-right"></i> Add Document';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['docs_category'] = $this->bms_property_model->getPropertyDocCategory ();
        //$data['properties_docs'] = $this->bms_masters_model->getMyPropertiesDocs ();
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('properties/prop_add_doc_view',$data);
	}
    
    function add_doc_submit () {
        //echo "<pre>";print_r($_FILES);echo "</pre>"; exit;
        if(isset($_POST) && !empty($_POST['doc'])) {
            //$this->load->model('bms_task_model');
            $doc = $this->input->post('doc');
            $data['upload_err'] =  array ();
            if(!empty($_FILES) && !empty($_FILES['document']['name']) && $_FILES['document']['size'] > 0) {
                
                $property_docs_upload = $this->config->item('property_docs_upload');                
                $this->load->library('upload');
                $property_docs_upload['upload_path'] = $property_docs_upload['upload_path'].'/'.$doc['property_id']; 
                if(!is_dir($property_docs_upload['upload_path']));
                    @mkdir($property_docs_upload['upload_path'], 0777);
                
                $property_docs_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize($property_docs_upload);
                //echo "<pre>";print_r($task_file_upload);exit;
                if ( ! $this->upload->do_upload('document')) {
                    //if(count($_FILES) > 1)
                    //    echo $task_file_upload_err = 'One or more images are not uploaded!';
                    //else 
                    //    $task_file_upload_err = 'Image is not uploaded!';
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){
                    $doc['doc_file_name'] = $this->upload->data('file_name');
                    $doc['created_date'] = date("Y-m-d H:i:s");        
                    $doc['created_by'] = $_SESSION['bms']['staff_id'];
                    $this->bms_property_model->property_docs_insert($doc);
                    $_SESSION['flash_msg'] = 'Document has been Uploaded Successfully!';
                    redirect('index.php/bms_property/docs_list?property_id='.$doc['property_id'].'&doc_cat_id='.$doc['doc_cat_id']);
                } else {
                    $_SESSION['error_msg'] = 'Document upload Error Message: '.$this->upload->display_errors(); 
                    $this->add_doc();
                }            
                
            }                
        }
        //redirect('index.php/bms_property/docs_list');
    }
    
    public function property_asset_list($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Property Asset';
        $data['page_header'] = '<i class="fa fa-clipboard"></i> Property Asset <i class="fa fa-angle-double-right"></i> Asset List';
                
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('properties/asset_list_view',$data);
	}
    
    public function get_asset_list() {        
        
        header('Content-type: application/json');        
        
        $assets = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_property_model->get_asset_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }      
                
        echo json_encode($assets);
	}
    
    function add_asset ($asset_id = '')  {
        $type = $asset_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' Asset';
        $data['page_header'] = '<i class="fa fa-clipboard"></i> Property Asset <i class="fa fa-angle-double-right"></i> '.$type.' Asset';
        $data['asset_id'] = $asset_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($asset_id != '') {
            $data['asset_info'] = $this->bms_property_model->get_asset_details($asset_id);
            if(empty($data['asset_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            } 
            $data['service'] = $this->bms_property_model->get_asset_service_period($asset_id);
            
            //echo "<pre>";print_r($data['service']);echo "</pre>";                 
        }
        $this->load->view('properties/add_asset_view',$data);
    }
    
    function add_asset_submit () {
        
        $asset_id = $this->input->post('asset_id');
        
        $asset_info = $this->input->post('asset');
        $asset_info['purchase_date'] = !empty($asset_info['purchase_date']) ? date('Y-m-d', strtotime($asset_info['purchase_date'])) : '';
        $asset_info['warranty_start'] = !empty($asset_info['warranty_start']) ? date('Y-m-d', strtotime($asset_info['warranty_start'])) : '';
        $asset_info['warranty_due'] = !empty($asset_info['warranty_due']) ? date('Y-m-d', strtotime($asset_info['warranty_due'])) : '';
        $asset_info['decommission_date'] = !empty($asset_info['decommission_date']) ? date('Y-m-d', strtotime($asset_info['decommission_date'])) : '';
        //$asset_info['remaind_time'] = !empty($asset_info['remaind_time']) ? date('H:i:s', strtotime($asset_info['remaind_time'])) : '';
        $type = 'add';
        if(isset($asset_info['asset_name']) && trim($asset_info['asset_name']) !='') {
            if($asset_id) {
                $this->bms_property_model->update_asset($asset_info,$asset_id);
                $type = 'edit';                
            } else {
                $asset_id = $this->bms_property_model->insert_asset($asset_info);                
            }
            if($asset_info['periodic_service'] == 1) {
                $service = $this->input->post('service');
                foreach($service['service_name'] as $skey=>$sval) {
                    $service_data['asset_id'] = $asset_id;
                    if($sval != '' || $service['service_period'][$skey] != '') {
                        $service_data['service_name'] = $service['service_name'][$skey];
                        $service_data['service_period']= $service['service_period'][$skey];
                        if(!empty($service['service_period_id'][$skey])) {
                            $this->bms_property_model->update_asset_service_period($service_data,$service['service_period_id'][$skey]);    
                        } else {
                            $this->bms_property_model->insert_asset_service_period($service_data);
                        }                             
                    }
                }
            }
            if($type == 'add' && $asset_info['periodic_service'] == 1) {
                $this->load->library('asset_emails');
                $asset_info['property_name'] = $_POST['property_name'];
                if(!empty($asset_info['warranty_due'])) {                    
                    $this->asset_emails->setServiceSchedule ($asset_info,$service);
                } else {
                    $this->asset_emails->setMaintenanceComp ($asset_info);
                }
                //echo "<pre>112";print_r($_POST);exit;
            }                       
            $_SESSION['flash_msg'] = 'Asset '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        
        
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_property/property_asset_list/0/25?property_id='.$asset_info['property_id']);
    }
    
    public function asset_service_schedule_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Asset Service Schedule';
        $data['page_header'] = '<i class="fa fa-calendar"></i> Asset Service Schedule ';
                
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('properties/service_schedule_list_view',$data);
	}
    
    public function get_service_asset_list() {        
        
        header('Content-type: application/json');        
        
        $units = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_property_model->get_service_asset_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }      
                
        echo json_encode($assets);
	}
    
    function asset_service_schedule ($asset_id) {
        $data['browser_title'] = 'Property Butler | Asset Service Schedule';
        $data['page_header'] = '<i class="fa fa-calendar"></i> Asset Service Schedule ';
        $data['asset_id'] = $asset_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($asset_id != '') {
            $data['asset_info'] = $this->bms_property_model->get_asset_details($asset_id);
            if(empty($data['asset_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }
            $data['service_type'] = $this->bms_property_model->get_asset_service_period($data['asset_info']['asset_id']);
            //echo "<pre>";print_r($service_type); echo "</pre>";
            /*$today_date = new DateTime(date('Y-m-d'));
            $warranty_due_date = !empty($data['asset_info']['warranty_due']) && !in_array($data['asset_info']['warranty_due'], array('0000-00-00','1970-01-01')) ? new DateTime($data['asset_info']['warranty_due']) : '';
            if(empty($data['asset_info']['warranty_due']) || ($warranty_due_date != '' && $today_date < $warranty_due_date)) {
                
            }*/
            
            
            $data['asset_maint_comp'] = $this->bms_property_model->get_asset_maintenance_company($asset_id);
            //echo "<pre>";print_r($data['asset_maint_comp']);echo "</pre>";
                            
        }
        $this->load->view('properties/service_schedule_view',$data);
    }
    
    function maintenance_company ($param,$asset_id) {
        //echo $param;
        if($param != 'all') {
            $data['asset_id'] = $asset_id;
            if($param == 'add') {
                $data['maint_comp_id'] = '';
                $data['asset_info'] = array ();
                $data['service_providers'] = $this->bms_property_model->get_service_provider_by_asset ($asset_id);
                //echo "<pre>";print_r($data['service_providers']);echo "</pre>";
            } else {
                $data['maint_comp_id'] = $param;
                $data['mainten_comp'] = $this->bms_property_model->get_maintenance_company($param);                
            }
            $this->load->view('properties/maintenance_comp_details_view',$data);
        } else if ($param == 'all') {
            $data['mainten_comp'] = $this->bms_property_model->get_all_maintenance_company($asset_id);
            $this->load->view('properties/maintenance_comp_list_view',$data);
        }
        
    }
    
    function set_maintenance_company () {
        
        //echo "<pre>";print_r($_POST);exit;
        $maint_comp_id = $this->input->post('maint_comp_id');
        
        $maint_comp_info = $this->input->post('mainten_comp');        
        //$maint_comp_info['warranty_start'] = !empty($maint_comp_info['warranty_start']) ? date('Y-m-d', strtotime($maint_comp_info['warranty_start'])) : '';
        //$maint_comp_info['warranty_due'] = !empty($maint_comp_info['warranty_due']) ? date('Y-m-d', strtotime($maint_comp_info['warranty_due'])) : '';        
        
        $type = 'add';
        //if(isset($maint_comp_info['supplier_name']) && trim($maint_comp_info['supplier_name']) !='') {
            /*if(!empty($_FILES) && !empty($_FILES['document']['name']) && $_FILES['document']['size'] > 0) {
                
                $asset_maint_cont_docs_upload = $this->config->item('asset_maint_cont_docs_upload');  
                $old_attach = $this->input->post('old_attach');
                if(!empty($old_attach)) {
                    unlink($asset_maint_cont_docs_upload['upload_path'].'/'.$old_attach);
                }              
                $this->load->library('upload');
                //$asset_maint_cont_docs_upload['upload_path'] = $asset_maint_cont_docs_upload['upload_path'].'/'.date('Y'); 
                 
                $time =microtime(true);
                $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
                $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
                $asset_maint_cont_docs_upload['file_name'] = $date->format("YmdHisu");
                
                $this->upload->initialize($asset_maint_cont_docs_upload);
                $data['upload_err'] = array();                
                if ( ! $this->upload->do_upload('document')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){ 
                    $maint_comp_info['file_name'] = $this->upload->data('file_name');
                } 
            }*/
            if($maint_comp_id) {
                $this->bms_property_model->update_maintenance_company($maint_comp_info,$maint_comp_id);
                $type = 'edit';                
            } else {
                $maint_comp_id = $this->bms_property_model->insert_maintenance_company($maint_comp_info); 
                if(!$this->bms_property_model->getServiceScheduleCount($maint_comp_info['asset_id'])) {
                    $asset_info = $this->bms_property_model->getAssetDetailsForEmail($maint_comp_info['asset_id']);
                    $service_arr = $this->bms_property_model->get_asset_service_period($maint_comp_info['asset_id']);
                    foreach ($service_arr as $val) {
                        $service['service_name'][] = $val['service_name'];
                    }
                    $this->load->library('asset_emails');
                    $this->asset_emails->setServiceSchedule ($asset_info,$service);
                }               
            }
                        
            $_SESSION['flash_msg'] = 'Maintenance Company '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        //}
        redirect('index.php/bms_property/asset_service_schedule/'.$maint_comp_info['asset_id']); 
    }
    
    function set_service_schedule () {
        if(!empty($_POST['schedule_date']) && !empty($_POST['asset_id'])) {
            
            if(!empty($_POST['service_type'])) {
                $service_periodic = explode('-',$_POST['service_type']);
                $data['service_type'] = $service_periodic[0];
                $data['service_period'] = $service_periodic[1];
            }
            $data['service_date'] = date('Y-m-d',strtotime($_POST['schedule_date']));
            $data['asset_id'] = $_POST['asset_id'];
            $data['service_reminder'] = $_POST['service_reminder'];
            $data['warranty_status'] = $_POST['warranty_status'];

            echo $this->bms_property_model->insert_service_schedule($data);
        }
    }
    
    function update_service_schedule () {
        if(!empty($_POST['schedule_date']) && !empty($_POST['asset_service_schedule_id'])) {            
            $data['service_date'] = date('Y-m-d',strtotime($_POST['schedule_date']));
            $data['asset_service_schedule_id'] = $_POST['asset_service_schedule_id'];                      
            echo $this->bms_property_model->update_service_schedule($data,$_POST['asset_service_schedule_id']);
        }
    }
    
    public function get_service_schedule_list() {        
        
        header('Content-type: application/json');        
        
        $units = array();
        if (!empty($_POST['asset_id']) ) {
            //$search_txt = $this->input->post('search_txt');
            //$search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_property_model->get_service_schedule_list ($this->input->post('asset_id'));
        }      
                
        echo json_encode($assets);
	}
    
    public function asset_service_details_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Asset Service Details';
        $data['page_header'] = '<i class="fa fa-info"></i> Asset Service Details ';
                
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('properties/service_details_list_view',$data);
	}
    
    public function get_service_details_list() {        
        
        header('Content-type: application/json');        
        
        $units = array();
        if (!empty($_POST['property_id']) ) {
            //$search_txt = $this->input->post('search_txt');
            //$search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_property_model->get_asset_service_details_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'));
        }      
                
        echo json_encode($assets);
	}
    
    function asset_service_details_entry ($service_schedule_id) {
        $data['browser_title'] = 'Property Butler | Asset Service Entry';
        $data['page_header'] = '<i class="fa fa-info"></i> Asset Service Entry ';
        $data['asset_id'] = $service_schedule_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($service_schedule_id != '') {
            $data['asset_info'] = $this->bms_property_model->get_asset_service_details_entry($service_schedule_id);
            if(empty($data['asset_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            } 
            $data['asset_maint_comp'] = $this->bms_property_model->get_asset_maintenance_company($data['asset_info']['asset_id']);                
        }
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('properties/service_details_entry_view',$data);
    }
    
    function asset_service_details_entry_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit; 
        
        if(isset($_POST) && !empty($_POST['service_details'])) {
            //$this->load->model('bms_task_model');
            $service_details = $this->input->post('service_details');
            $service_details['service_date'] = date('Y-m-d',strtotime($service_details['service_date']));
            $entry_id = $this->bms_property_model->insert_service_entry($service_details);
            $data['upload_err'] =  array ();
            /*if(!empty($_FILES) && !empty($_FILES['document']['name']) && $_FILES['document']['size'] > 0) {
                
                $asset_service_entry_docs_upload = $this->config->item('asset_service_entry_docs_upload');                
                $this->load->library('upload');
                $asset_service_entry_docs_upload['upload_path'] = $asset_service_entry_docs_upload['upload_path'].'/'.date('Y'); 
                if(!is_dir($asset_service_entry_docs_upload['upload_path']));
                    @mkdir($asset_service_entry_docs_upload['upload_path'], 0777);
                
                $asset_service_entry_docs_upload['upload_path'] = $asset_service_entry_docs_upload['upload_path'].'/'.date('m'); 
                if(!is_dir($asset_service_entry_docs_upload['upload_path']));
                    @mkdir($asset_service_entry_docs_upload['upload_path'], 0777);                    
                
                $time =microtime(true);
                $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
                $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
                $asset_service_entry_docs_upload['file_name'] = $date->format("YmdHisu");
                
                $this->upload->initialize($asset_service_entry_docs_upload);                
                if ( ! $this->upload->do_upload('document')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){                    
                    $att_info['asset_service_details_id'] = $entry_id;
                    $att_info['file_name'] = $this->upload->data('file_name'); 
                    $this->bms_property_model->insert_service_entry_attach($att_info);                    
                } 
            }*/
            if(!empty($_POST['files'])) {

                $asset_service_entry_docs_upload = $this->config->item('asset_service_entry_docs_upload');
                $asset_service_entry_docs_upload['upload_path'] = $asset_service_entry_docs_upload['upload_path'].date('Y').'/'; 
                if(!is_dir($asset_service_entry_docs_upload['upload_path']));
                    @mkdir($asset_service_entry_docs_upload['upload_path'], 0777);
                
                $asset_service_entry_docs_upload['upload_path'] = $asset_service_entry_docs_upload['upload_path'].date('m').'/'; 
                if(!is_dir($asset_service_entry_docs_upload['upload_path']));
                    @mkdir($asset_service_entry_docs_upload['upload_path'], 0777);                    
                 
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                
                foreach ( $_POST['files'] as $key=>$val ) {
                    rename($task_file_upload_temp['upload_path'].$val, $asset_service_entry_docs_upload['upload_path'].$val);
                    /*$img_data['task_id'] = $insert_id;
                    $img_data['img_name'] = $val; 
                    $this->bms_task_model->task_image_name_insert ($img_data); */
                    
                    $att_info['asset_service_details_id'] = $entry_id;
                    $att_info['file_name'] = $val; 
                    $this->bms_property_model->insert_service_entry_attach($att_info);
                }
                
            }
            $_SESSION['flash_msg'] = 'Service Entry has been submitted successfully!';
        } 
        redirect('index.php/bms_property/asset_service_details_list/0/25?property_id='.$_POST['property_id']);      
    }
    
    function asset_service_details ($asset_service_details_id) {
        //echo $asset_service_details_id;
        $data['service_details'] = $this->bms_property_model->get_asset_service_details ($asset_service_details_id);
        $data['service_details_att'] = $this->bms_property_model->get_asset_service_details_att ($asset_service_details_id); 
        
        //echo "<pre>";print_r($data);echo "</pre>"; exit; 
        /*if($data['notice_details']['unit_ids'] != 'All' ) {
            $data['service_details_att'] = $this->bms_e_notice_model->getUnitsInfo ($data['notice_details']['unit_ids']); 
        }*/
        $this->load->view('properties/service_details_view',$data);
    }
    
    function annual_renewal_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Annual Renewal';
        $data['page_header'] = '<i class="fa fa-repeat"></i> Annual Renewal';
                
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('properties/annual_renewal_list_view',$data);
    }
    
    public function get_annual_renewal_list() {        
        
        header('Content-type: application/json');        
        
        $assets = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_property_model->get_annual_renewal_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }      
                
        echo json_encode($assets);
	}
    
    function add_annual_renewal ($annual_renewal_id = '') {
        $type = $annual_renewal_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' Annual Renewal';
        $data['page_header'] = '<i class="fa fa-repeat"></i> Annual Renewal <i class="fa fa-angle-double-right"></i> '.$type.' Annual Renewal';
        $data['annual_renewal_id'] = $annual_renewal_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($annual_renewal_id != '') {
            $data['renewal_info'] = $this->bms_property_model->get_annual_renewal_details($annual_renewal_id);
            if(empty($data['renewal_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            } 
            $data['annual_renewal_att'] = $this->bms_property_model->get_annual_renewal_att($annual_renewal_id);
            
            //echo "<pre>";print_r($data['annual_renewal_att']);echo "</pre>";                 
        }
        $this->load->view('properties/add_annual_renewal_view',$data);
    }
    
    function add_annual_renewal_submit () {
        
        $annual_renewal_id = $this->input->post('annual_renewal_id');
        
        $annual_renew = $this->input->post('annual_renew');
        $annual_renew['license_start_date'] = !empty($annual_renew['license_start_date']) ? date('Y-m-d', strtotime($annual_renew['license_start_date'])) : '';
        $annual_renew['license_expiry_date'] = !empty($annual_renew['license_expiry_date']) ? date('Y-m-d', strtotime($annual_renew['license_expiry_date'])) : '';
        //$annual_renew['remaind_time'] = !empty($annual_renew['remaind_time']) ? date('H:i:s', strtotime($annual_renew['remaind_time'])) : '';
        $type = 'add';
        if(isset($annual_renew['item_descrip']) && trim($annual_renew['item_descrip']) !='') {
            if($annual_renewal_id) {
                $this->bms_property_model->update_annual_renewal($annual_renew,$annual_renewal_id);
                $type = 'edit';                
            } else {
                $annual_renewal_id = $this->bms_property_model->insert_annual_renewal($annual_renew);                
            }
            
            if(!empty($_POST['files'])) {
                
                $annual_renewal_docs_upload = $this->config->item('annual_renewal_docs_upload');                
                                        
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                
                foreach ($_POST['files'] as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $annual_renewal_docs_upload['upload_path'].$val);
                                        
                    $att_info['annual_renewal_id'] = $annual_renewal_id;
                    $att_info['file_name'] = $val; 
                    $this->bms_property_model->insert_sannual_renewal_attach($att_info);
                }                
            }
                              
            $_SESSION['flash_msg'] = 'Annual Renewal '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_property/annual_renewal_list/0/25?property_id='.$annual_renew['property_id']);
    }
    
    /**
     * resident notice 
     * 
     * */
    
    public function resi_notice_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Residence Notice Board';
        $data['page_header'] = '<i class="fa fa-envelope-open"></i> Residence Notice <i class="fa fa-angle-double-right"></i> Residence Notice List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('properties/resi_notice_board/resi_notice_list_view',$data);
	}
    
    public function get_resi_notice_list() {        
        
        header('Content-type: application/json');        
        
        $notices = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $notices = $this->bms_property_model->get_resident_notice_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }    
        echo json_encode($notices);
	}
    
    function resident_notice_details ($resi_notice_id) {
        $data['notice_details'] = $this->bms_property_model->get_resi_notice_details($resi_notice_id); 
        $this->load->view('properties/resi_notice_board/resi_notice_details_view',$data);
    }
    
    public function add_resi_notice($resi_notice_id = '') {
        
        //$type = $unit_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler |  Residence Notice Board';
        $data['page_header'] = '<i class="fa fa-envelope-open"></i>  Residence Notice <i class="fa fa-angle-double-right"></i> Add Residence Notice';
        $data['resi_notice_id'] = $resi_notice_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($resi_notice_id != '') {
            $data['resi_notice'] = $this->bms_property_model->get_resi_notice_details($resi_notice_id);
            if(empty($data['resi_notice'])) {
                redirect('index.php/bms_dashboard/index'); 
            }                     
        }       
        //echo "<pre>";print_r($data);echo "</pre>"; exit;
        $this->load->view('properties/resi_notice_board/add_resi_notice_view',$data);
	}     
        
    function add_resi_notice_submit () {
        //echo "<pre>";print_r($_FILES);echo "</pre>"; exit;
        
        $resi_notice = $this->input->post('resi_notice');
        // rnb stands for resident notice board
        if(isset($resi_notice['property_id']) && trim($resi_notice['notice_title']) !='') {
            
            $resi_notice['start_date'] = !empty($resi_notice['start_date']) ? date('Y-m-d', strtotime($resi_notice['start_date'])) : '';
            $resi_notice['end_date'] = !empty($resi_notice['end_date']) ? date('Y-m-d', strtotime($resi_notice['end_date'])) : '';
            //str_replace ('<br>' , '\r\n', $resi_notice['message']);
            $resi_notice['message'] = $resi_notice['message'];           
            
            // attachment
            $data['upload_err'] =  array ();
            if(!empty($_FILES) && !empty($_FILES['attach']['name']) && $_FILES['attach']['size'] > 0) {
                
                $resident_notice_attach = $this->config->item('resident_notice_attach_upload');
                
                $this->load->library('upload');
                $resident_notice_attach['upload_path'] = $resident_notice_attach['upload_path'].'/'.$resi_notice['property_id']; 
                if(!is_dir($resident_notice_attach['upload_path']));
                    @mkdir($resident_notice_attach['upload_path'], 0777); 
                
                if(!empty($_POST['attachment_name_old'])) {                    
                    unlink($resident_notice_attach['upload_path'].'/'.$_POST['attachment_name_old']);
                }
                
                $time =microtime(true);
                $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
                $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
                $resident_notice_attach['file_name'] = $date->format("YmdHisu");
                
                $this->upload->initialize($resident_notice_attach);                
                if ( ! $this->upload->do_upload('attach')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){
                    $resi_notice['attachment_name'] = $this->upload->data('file_name');                    
                } 
            }
            //echo "<pre>";print_r($data['upload_err']);echo "</pre>"; exit;
            if(!empty($resi_notice['resident_notice_id'])) {
                $notice_id = $resi_notice['resident_notice_id'];
                $this->bms_property_model->update_resident_notice($resi_notice,$resi_notice['resident_notice_id']);
            } else {
                $notice_id = $this->bms_property_model->insert_resident_notice($resi_notice);
            }


            // push notification
            $this->notifications->sendRNBPushNotification($resi_notice['property_id'],$_POST['property_name'],$resi_notice['notice_title'],$notice_id,!empty($resi_notice['resident_notice_id']) ? 'Updated' : 'Created');

            $_SESSION['flash_msg'] = 'Residence Notice '.(!empty($resi_notice['resident_notice_id']) ? 'Updated' : 'Created').' Successfully!';
        }        
        redirect('index.php/bms_property/resi_notice_list/0/25?property_id='.$resi_notice['property_id']);        
    }
    
    public function service_provider_cat_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Service Provider Category List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Service Provider Category List';        
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('properties/service_provider_cat_list_view',$data);
	} 
    
    public function get_service_provider_cat_list() {        
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_property_model->get_service_provider_cat_list ($_POST['offset'],$_POST['rows'],$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    public function service_provider_cat_form($service_provider_cat_id = '') {
        
        $type = $service_provider_cat_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Service Provider Category';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Service Provider Category <i class="fa fa-angle-double-right"></i> '.$type.' Service Provider Category';
        $data['service_provider_cat_id'] = $service_provider_cat_id;
        if($service_provider_cat_id != '') {
            $data['service_provider_cat'] = $this->bms_property_model->get_service_provider_cat_details($service_provider_cat_id);          
        }
        
        $this->load->view('properties/service_provider_cat_form_view',$data);
	}    
       
    function service_provider_cat_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $service_provider_cat = $this->input->post('service_provider_cat');
        if(!empty($service_provider_cat)) {
            $type = 'edit';
            //$service_provider_cat['date'] = date('Y-m-d',strtotime($service_provider_cat['date']));
            $service_provider_cat_id = $this->input->post('service_provider_cat_id');
            if(!empty($service_provider_cat_id)) {
                $type = 'add';
                $this->bms_property_model->update_service_provider_cat($service_provider_cat,$service_provider_cat_id);     
            } else {
                $this->bms_property_model->insert_service_provider_cat($service_provider_cat);     
            }
            
            $_SESSION['flash_msg'] = 'Service Provider Category '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_property/service_provider_cat_list');
    }
    
    function service_provider_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Service Provider';
        $data['page_header'] = '<i class="fa fa-industry"></i> Service Provider List';
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
                
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('properties/service_provider_list_view',$data);
        
        
    }
    
    function get_service_provider ($service_provider_id='') {
        header('Content-type: application/json');        
        
        $service_provider = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $service_provider = $this->bms_property_model->get_service_provider_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }

        echo json_encode($service_provider);
    }
    
    function add_service_provider ($service_provider_id = '') {
        $data['browser_title'] = 'Property Butler | Service Provider';
        $data['page_header'] = '<i class="fa fa-industry"></i> Service Provider <i class="fa fa-angle-double-right"></i> Add Service Provider';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($service_provider_id)
            $data['service_provider'] = $this->bms_property_model->get_service_provider ($service_provider_id);
            
        $data['service_provider_cat'] = $this->bms_property_model->get_all_service_provider_cat_list();
        $data['service_provider_id'] = $service_provider_id;
        
        $data['countries'] = $this->bms_masters_model->getCountries ();
        //echo "<pre>";print_r($data['countries']);echo "</pre>";
        $this->load->view('properties/service_provider_form_view',$data);
    }
    
    function set_service_provider () {
        
        $this->load->model('bms_fin_coa_model');
        
        //echo "<pre>";print_r($_POST);exit;
        $service_provider_id = $this->input->post('service_provider_id');  
        
        $service_provider = $this->input->post('service_provider');   
        if(!empty($service_provider['contractual']) && $service_provider['contractual']  == 1) {
            $service_provider['contract_start_date'] = !empty($service_provider['contract_start_date']) ? date('Y-m-d', strtotime($service_provider['contract_start_date'])) : '';
            $service_provider['contract_end_date'] = !empty($service_provider['contract_end_date']) ? date('Y-m-d', strtotime($service_provider['contract_end_date'])) : '';
        } else {
            $service_provider['contract_start_date'] = '0000-00-00';
            $service_provider['contract_end_date'] = '0000-00-00';        
            $service_provider['remind_before'] = '';
            $service_provider['contractual'] =  0;
        }    
        
        $type = 'add';
        if(isset($service_provider['provider_name']) && trim($service_provider['provider_name']) !='') {
            if(!empty($_FILES) && !empty($_FILES['document']['name']) && $_FILES['document']['size'] > 0) {
                
                $service_provider_attach_upload = $this->config->item('service_provider_attach_upload'); 
                $service_provider_attach_upload['upload_path'] = $service_provider_attach_upload['upload_path'].'/'.$service_provider['property_id']; 
                if(!is_dir($service_provider_attach_upload['upload_path']));
                    @mkdir($service_provider_attach_upload['upload_path'], 0777);
                     
                $old_attach = $this->input->post('old_attach');
                if(!empty($old_attach)) {
                    unlink($service_provider_attach_upload['upload_path'].'/'.$old_attach);
                }              
                $this->load->library('upload');
                //$service_provider_attach_upload['upload_path'] = $service_provider_attach_upload['upload_path'].'/'.date('Y'); 
                 
                $time =microtime(true);
                $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
                $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
                $service_provider_attach_upload['file_name'] = $date->format("YmdHisu");
                
                $this->upload->initialize($service_provider_attach_upload);
                $data['upload_err'] = array();                
                if ( ! $this->upload->do_upload('document')) {                    
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                }
                if(empty($data['upload_err'])){ 
                    $service_provider['file_name'] = $this->upload->data('file_name');
                } 
                //echo "<pre>";print_r($data['upload_err']);
            }
            if($service_provider_id) {
                if(!empty($service_provider['coa_id'])) {
                    $data_coa['coa_name'] = $service_provider['provider_name'];
                    $this->bms_fin_coa_model->update_coa($data_coa,$service_provider['coa_id']);                    
                }
                 
                $this->bms_property_model->update_service_provider($service_provider,$service_provider_id);
                $type = 'edit';                
            } else {
                $last_sp_code = $this->bms_fin_coa_model->get_last_sp_code($service_provider['property_id']);
                if(!empty($last_sp_code)) {
                    $list = explode('/',$last_sp_code['coa_code']);
                    $last_no = end($list);
                    $data_coa['property_id'] = $service_provider['property_id'];
                    $data_coa['coa_type_id'] = 4;
                    $data_coa['coa_code'] = '4100/'.str_pad(++$last_no, 3, '0', STR_PAD_LEFT);
                    $data_coa['coa_name'] = $service_provider['provider_name'];
                    $data_coa['payment_enabled'] = 1;
                    $service_provider['coa_id'] = $this->bms_fin_coa_model ->insert_coa($data_coa); 
                }
                $service_provider_id = $this->bms_property_model->insert_service_provider($service_provider);                               
            }
                        
            $_SESSION['flash_msg'] = 'Service Provider '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        //$_SESSION['flash_msg'] = 'Service Provider Saved successfully!';
        redirect('index.php/bms_property/service_provider_list/?property_id='.$service_provider['property_id']); 
    }
    
    function facility_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Facilities';
        $data['page_header'] = '<i class="fa fa-share-alt"></i> Facilities List';
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
                
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('properties/facility/facility_list_view',$data);
        
        
    }
    
    function get_facility ($facility_id='') {
        header('Content-type: application/json');        
        
        $facility = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $facility = $this->bms_property_model->get_facility_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }      
                
        echo json_encode($facility);
    }
    
    function add_facility ($facility_id = '') {
        $data['browser_title'] = 'Property Butler | Facility';
        $data['page_header'] = '<i class="fa fa-share-alt"></i> Facilities <i class="fa fa-angle-double-right"></i> Add Facility';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if($facility_id)
            $data['facility'] = $this->bms_property_model->get_facility ($facility_id);
        $data['facility_id'] = $facility_id;
        
        $data['charge_codes'] = $this->bms_masters_model->getChargeCodes ();
        
        $this->load->view('properties/facility/facility_form_view',$data);
    }
    
    function set_facility () {
        
 //        echo "<pre>";print_r($_POST);exit;
        $facility_id = $this->input->post('facility_id');

        $facility = $this->input->post('facility');

        $start_time = new DateTime($facility['start_time']);
        $end_time = new DateTime($facility['end_time']);
        $interval = $start_time->diff($end_time);

        $facility['end_time'] = $end_time->format('H:i;s');
        $facility['start_time'] = $start_time->format('H:i;s');

        $hours = $interval->format("%r %H");
        $min = $interval->format("%r %i");

        $difference_min = ($hours * 60) + $min;

        $slot_hour = $facility['slot_hour'];
        $slot_min = $facility['slot_min'];

        $slot = ($slot_hour * 60) + $slot_min;

        if( !empty($facility_id)) {
            $type = 'edit';
        } else {
            $type = 'add';
        }

        $data['upload_err'] =  array ();

        if(!empty($_FILES) && !empty($_FILES['picture']['name']) && $_FILES['picture']['size'] > 0) {

            $facility_picture_upload = $this->config->item('facility_picture_upload');

            if(!empty($_POST['property_logo_old'])) {
                unlink($facility_picture_upload['upload_path'].$_POST['picture_old']);
            }

            $this->load->library('upload');

            $facility_picture_upload['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize($facility_picture_upload);
            //echo "<pre>";print_r($task_file_upload);exit;
            if ( ! $this->upload->do_upload('picture')) {
                array_push($data['upload_err'],$this->upload->display_errors());
            }
            if(empty($data['upload_err'])){
                $facility['picture'] = $this->upload->data('file_name');
            }
        }

        if ( !empty($difference_min) && $difference_min % $slot == 0 ) {

            $facility['number_of_slots'] = $difference_min / $slot;

            if ( $facility['deposit_require'] == 0 ) {
                $facility['amount'] = '';
            }

            if (isset($facility['facility_name']) && trim($facility['facility_name']) !='') {
                unset($facility['slot_hour']);
                unset($facility['slot_min']);
                $facility['booking_slot'] = $slot_hour . ":" . $slot_min . ":" . '00';
                if($facility_id) {
                    $this->bms_property_model->update_facility($facility,$facility_id);
                } else {
                    $facility_id = $this->bms_property_model->insert_facility($facility);
                }
                $_SESSION['flash_msg'] = 'Facility '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
            }
        } else {
            $_SESSION['flash_msg'] = 'Facility '. ($type == 'edit' ? 'Update' : 'Add') .' Unsuccessfull. Please check Booking start from/Booking end to and Booking slot';
            if( !empty($facility_id)) {
                redirect('index.php/bms_property/add_facility/'.$facility_id);
            } else {
                redirect('index.php/bms_property/add_facility/?property_id='.$facility['property_id']);
            }
        }
        redirect('index.php/bms_property/facility_list/?property_id='.$facility['property_id']); 
    }

    function get_tiers () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $blocks = array();
        if($property_id) {
            $blocks = $this->bms_masters_model->getTiers ($property_id);
        }
        echo json_encode($blocks);
    }

    function facility_booking_list ($offset=0, $rows = 25) {
        $data['browser_title'] = 'Property Butler | Facility Booking List';
        $data['page_header'] = '<i class="fa fa-share-alt"></i> Booking List';

        $data['facility_id'] = !empty($this->input->get('facility_id')) ? $this->input->get('facility_id'):'' ;
        $data['booking_status'] = empty ($this->input->get('booking_status')) || $this->input->get('booking_status') === '0' ? '0':'1';

        $data['offset'] = $offset;
        $data['rows'] = $rows;

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        if ( !empty($data ['property_id']) ) {
            $data['facilities'] = $this->bms_property_model->getFscilitiesByProperty ($data ['property_id']);
            if ( !empty($data['booking_status']) || $data['booking_status'] === '0' )
                $data['booking_list'] = $this->bms_property_model->getBookings ($data ['property_id'], $data['facility_id'], $data['booking_status'], $offset, $rows);
        }

        $this->load->view('properties/facility/facility_booking_list_view',$data);
    }

    function set_booking_status_approved () {
        $facility_booking_id = $this->input->post('facility_booking_id');
        $update_data = array (
            'booking_status' => 1
        );
        $result = $this->bms_property_model->updateFascilityBookingStatus ($facility_booking_id, $update_data);
        echo $result;
    }

    function set_booking_status_rejected () {
        $facility_booking_id = $this->input->post('facility_booking_id');
        $booking_desc = $this->input->post('booking_desc');
        $update_data = array (
            'booking_status' => 2,
            'booking_desc' => $booking_desc,
        );
        $result = $this->bms_property_model->updateFascilityBookingStatus ($facility_booking_id, $update_data);
        echo $result;
    }

    function facility_booking_edit ($facility_booking_id = '') {
        $data['browser_title'] = 'Property Butler | Facility Booking Edit';
        $data['page_header'] = '<i class="fa fa-share-alt"></i> Facilitiy Booking <i class="fa fa-angle-double-right"></i> Edit Facility Booking';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        if ($facility_booking_id) {
            $data['facility_booking_detail'] = $this->bms_property_model->get_facility_booking_detail ($facility_booking_id);
            $data['facility_booking_id'] = $facility_booking_id;
            $this->load->view('properties/facility/facility_booking_edit_view',$data);
        } else {
            redirect('index.php/bms_property/facility_booking_list');
        }
    }

    function update_booking_status () {
        $facility_booking_id = $this->input->post('facility_booking_id');
        $booking_status = $this->input->post('booking_status');
        $booking_desc = !empty($this->input->post('booking_desc')) ? $this->input->post('booking_desc') : '';
        $update_data = array (
            'booking_status' => $booking_status,
            'booking_desc' => $booking_desc,
        );
        $this->bms_property_model->updateFascilityBookingStatus ($facility_booking_id, $update_data);
        $_SESSION['flash_msg'] = 'Booking Status Updated Successfully!';
        redirect('index.php/bms_property/facility_booking_edit/' . $facility_booking_id);
    }

    function check_email_addr_exists () {
        $email_addr = $this->input->post('email_addr');
        $property_dev_id = $this->input->post('property_dev_id');
        echo $this->bms_masters_model->checkDeveloperEmailAddrExists ($property_dev_id, $email_addr);
    }
}