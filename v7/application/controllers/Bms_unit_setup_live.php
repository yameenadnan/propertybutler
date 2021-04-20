<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_unit_setup extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff' || !in_array('3',$_SESSION['bms']['access_mod'])) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //if(!in_array($this->uri->segment(2), array('get_unit_list','check_email')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_unit_setup_model');
        $this->load->model('bms_property_model');
    }

    public function unit_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Unit List';
        $data['page_header'] = '<i class="fa fa-cog"></i> Unit Setup <i class="fa fa-angle-double-right"></i> Unit List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('unit_setup/unit_list_view',$data);
	}
    
    public function get_unit_list() {
        
        header('Content-type: application/json');        
        
        $units = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $units = $this->bms_unit_setup_model->get_unit_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }       
        echo json_encode($units);
	}

    public function unit_list_edit ($offset=0, $per_page = 25) {

        $data['browser_title'] = 'Property Butler | Edit Units';
        $data['page_header'] = '<i class="fa fa-cog"></i> Unit Setup <i class="fa fa-angle-double-right"></i> Unit List';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;
        $data['rows'] = $per_page;
        $data['unit_list'] = $this->bms_unit_setup_model->get_unit_list_to_edit ($offset, $per_page, $this->input->get('property_id'), (!empty($_GET['search_txt']))?$_GET['search_txt']:'');
        $data['unit_status'] = $this->bms_unit_setup_model->get_unit_status();

        $this->load->view('unit_setup/unit_list_edit_view',$data);
    }

    public function unit_list_download () {
        $property_id = $this->input->get('property_id');
        $property_name = $this->input->get('property_name');

        $data['unit_list'] = $this->bms_unit_setup_model->get_unit_list_to_download ($property_id, (!empty($_GET['search_txt']))?$_GET['search_txt']:'');

        require_once APPPATH.'/third_party/PHPExcel.php';
        require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Create a first sheet, representing sales data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        //$objPHPExcel->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Property Name: ' . $property_name );
        $measurement_unit = '';
        if ($data['unit_list'][0]['calcul_base'] == 1)
            $measurement_unit = 'Sq. Foot';
        elseif ($data['unit_list'][0]['calcul_base'] == 2)
            $measurement_unit = 'Share Unit';
        else
            $measurement_unit = 'N/A';

        $sheet_head = array ('Unit No',
            'Block/Street',
            $measurement_unit,
            'Owner Name',
            'Phone',
            'Email',
            'Status',
        );

        foreach ( $data['unit_list'] as $key => $val ) {
            unset($data['unit_list'][$key]['calcul_base']);
        }

        // Filling headers
        $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A2');
        $objPHPExcel->getActiveSheet()->fromArray($data['unit_list'], NULL, 'A3');

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Unit List');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Unit_list.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        //$this->load->view('unit_setup/unit_list_edit_view',$data);
    }

    public function unit_list_save () {

        // echo "<pre>";print_r($_POST); echo "</pre>";exit;

        $data['browser_title'] = 'Property Butler | Edit Units';
        $data['page_header'] = '<i class="fa fa-cog"></i> Unit Setup <i class="fa fa-angle-double-right"></i> Unit List';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $units = $_POST['unit_list'];

        foreach ( $units['unit_id'] as $key => $val ) {
            $data_unit = array (
                'unit_no' => $units['unit_no'][$key],
                'owner_name' => $units['owner_name'][$key],
                'ic_passport_no' => $units['ic_passport_no'][$key],
                'contact_1' =>  $units['contact_1'][$key],
                'contact_2' =>  $units['contact_2'][$key],
                'password' => $units['password'][$key],
                'unit_status' => $units['unit_status'][$key],
                'gender' => $units['gender'][$key],
                'race' => $units['race'][$key],
                'religion' => $units['religion'][$key],
            );

            if ( $units['email_addr'][$key] == $units['email_addr_old'][$key] ) {
            } else {
                $data_unit['email_addr'] = $units['email_addr'][$key];
                $data_unit['valid_email'] = 1;
            }

            $data_unit['dob'] = !empty ( $units['dob'][$key] ) ? date('Y-m-d',strtotime($units['dob'][$key])) : '0000-00-00';

            $calcul_base = $this->bms_unit_setup_model->get_property_calcul_base ( $data ['property_id'] );


            if ( !empty ($calcul_base) && $calcul_base == 1 ) {
                $data_unit['square_feet'] = $units['square_feet'][$key];
            } elseif ( !empty ($calcul_base) && $calcul_base == 2 ) {
                $data_unit['share_unit'] = $units['square_feet'][$key];
            }
            $this->bms_unit_setup_model->update_unit( $data_unit, $units['unit_id'][$key] );
        }

        $_SESSION['flash_msg'] = 'Units updated Successfully!';
        $sub_url = '';
        if(!empty($_POST['act_type'])) {
            switch($_POST['act_type']) {
                case 'save_pre': $sub_url = $_POST['offset']-$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save_nxt': $sub_url = $_POST['offset']+$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save':
                default:
                    $sub_url = $_POST['offset'].'/'.$_POST['rows']; break;
            }
        }
        redirect ('index.php/bms_unit_setup/unit_list_edit/'.$sub_url.'?property_id='.$_POST['property_id'] );
    }

    public function add_unit ($unit_id = '') {
        $type = $unit_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Unit';
        $data['page_header'] = '<i class="fa fa-cog"></i> Unit <i class="fa fa-angle-double-right"></i> '.$type.' Unit';
        $data['unit_id'] = $unit_id;
        $data['properties'] = $this->bms_masters_model->getMyPropertiesWithCharg ();
        if($unit_id != '') {
            $data['unit_info'] = $this->bms_unit_setup_model->get_unit_details($unit_id);
            if(empty($data['unit_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }
        }
        $data['unit_status'] = $this->bms_unit_setup_model->get_unit_status();
        //echo "<pre>";print_r($data);echo "</pre>"; exit;
        $data['invalid_email'] = $this->input->get('invalid_email');
        $this->load->view('unit_setup/add_unit_view',$data);
	}
    
    public function unit_details($unit_id = '') {
        
        $type = $unit_id != '' ? 'Details' : '';    
		$data['browser_title'] = 'Property Butler | '.$type.' Unit';
        $data['page_header'] = '<i class="fa fa-cog"></i> Unit <i class="fa fa-angle-double-right"></i> Unit '.$type.' ';
        $data['unit_id'] = $unit_id;
        $data['properties'] = $this->bms_masters_model->getMyPropertiesWithCharg ();
        if($unit_id != '') {
            $data['unit_info'] = $this->bms_unit_setup_model->get_unit_details($unit_id);
            if(empty($data['unit_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }                     
        }        
        //echo "<pre>";print_r($data);echo "</pre>"; exit;
        $this->load->view('unit_setup/unit_details_view',$data);
	} 
    
    function check_email () {
        if(isset($_POST['email_addr']) && $_POST['email_addr'] != '' ) { 
            $email_addr = trim($_POST['email_addr']);
            $unit_id = $this->input->post('unit_id');
            $result = $this->bms_unit_setup_model->check_email($email_addr,$unit_id);
            if(count($result) > 0 ) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    } 
    
    function add_unit_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $unit_id = $this->input->post('unit_id');

        $unit_info = $this->input->post('unit');
        $unit_info['dob'] = isset($unit_info['dob']) ? date('Y-m-d', strtotime($unit_info['dob'])) : '';
        $type = 'add';
        if ( isset($unit_info['unit_no']) && trim($unit_info['unit_no']) !='' ) {
            if ( $unit_id ) {
                $this->bms_unit_setup_model->update_unit($unit_info,$unit_id);
                $type = 'edit';                
            } else {
                $unit_id = $this->bms_unit_setup_model->insert_unit($unit_info);               
            }       
            
            // update unit table if the calculation based on Sq. Foot / Share Unit
            /*$charg_mand_base = $this->bms_unit_setup_model->get_unit_charg_details($unit_id);
            if(isset($charg_mand_base['calcul_base']) && in_array ($charg_mand_base['calcul_base'],array(1,2)) && !empty($charg_mand_base['sinking_fund']) && (($charg_mand_base['calcul_base'] == 1 && !empty($charg_mand_base['per_sq_feet'])) || ($charg_mand_base['calcul_base'] == 2 && !empty($charg_mand_base['per_share_unit'])))) {                
                
                $field = $charg_mand_base['calcul_base'] == 1 ? 'square_feet' : 'share_unit';
                $per_field = $charg_mand_base['calcul_base'] == 1 ? 'per_sq_feet' : 'per_share_unit';
                $tot_field = $charg_mand_base['calcul_base'] == 1 ? 'tot_sq_feet' : 'tot_share_unit';
                
                if(!empty($charg_mand_base[$field]) && is_numeric($charg_mand_base[$field])) {
                    $calc_data['service_charge'] = number_format(($charg_mand_base[$field] * $charg_mand_base[$per_field]),2,'.', '');
                    $calc_data['sinking_fund']   = number_format((($calc_data['service_charge']*$charg_mand_base['sinking_fund'])/100),2,'.', '');
                    $calc_data['insurance_prem'] = number_format((($charg_mand_base['insurance_prem']/$charg_mand_base[$tot_field])*$charg_mand_base[$field]),2,'.', '');
                    $calc_data['quit_rent']      = number_format((($charg_mand_base['quit_rent']/$charg_mand_base[$tot_field])*$charg_mand_base[$field]),2,'.', '');
                    //$this->bms_property_model->setUnit($calc_data,$uVal['unit_id']);
                    $charge_id = $this->bms_property_model->getMandatoryCharges ($charg_mand_base['unit_id']);
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
                        $char_data['unit_id'] = $charg_mand_base['unit_id'];
                        if(in_array($j,$charge_id_arr)) {
                            unset($char_data['effect_date']);
                            $charge_id = $this->bms_property_model->updateMandatoryCharges ($char_data,$charg_mand_base['unit_id'],$char_data['charge_type_id']);
                        } else {
                            $char_data['effect_date'] = date('Y-m-d');
                            $charge_id = $this->bms_property_model->insertMandatoryCharges ($char_data);
                        }
                    }                    
                }                
            } */           
            
            $_SESSION['flash_msg'] = 'Unit '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_unit_setup/add_unit/'.$unit_id);
        
    }
    
    function getOwners ($unit_id) {
        $data['owner_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
        $data['owner_hist'] = $this->bms_unit_setup_model->get_owner_hist($unit_id);
        $data['invalid_email'] = $this->input->get('invalid_email');
        $data['unit_id'] = $unit_id;
        $this->load->view('unit_setup/unit_owners_list_view',$data);
        
    }
    
    function getOwner ($id = '',$unit_id='') {
        $data['owner_hist_info'] = array();
        if($id == 'curr_owner')
            $data['owner_hist_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
        else if($id == 'new')
            $data['owner_hist_info'] = $this->bms_unit_setup_model->get_unit_basic_info($unit_id);
        else if($id != 'new')
            $data['owner_hist_info'] = $this->bms_unit_setup_model->get_owner_hist_by_id($id);
        $data['unit_owner_id'] = $id;
        $data['unit_id'] = $unit_id;
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('unit_setup/unit_owners_form_view',$data);
    
    }
    
    function set_owners () {
        
        $this->load->model('bms_fin_coa_model');
        
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $owner = $this->input->post('owner');
        $unit_owner_id = $this->input->post('unit_owner_id');
        $owner['is_defaulter'] = !empty($owner['is_defaulter']) ? $owner['is_defaulter'] : '0';
        if(!empty($owner['dob'])) 
            $owner['dob'] = date('Y-m-d',strtotime($owner['dob']));
            
        if(!empty($owner['start_date'])) 
            $owner['start_date'] = date('Y-m-d',strtotime($owner['start_date']));
            
        if(!empty($owner['end_date'])) 
            $owner['end_date'] = date('Y-m-d',strtotime($owner['end_date']));
        
        if(!empty($unit_owner_id) && in_array ($unit_owner_id,array('curr_owner','new'))) {
            
            if($unit_owner_id == 'new') {
                $this->bms_unit_setup_model->copy_curr_owner_to_hist($owner['unit_id']);
                $this->bms_unit_setup_model->set_jmb_status($owner['unit_id']);
                if(!empty($owner['coa_id'])) {
                    $data_coa['coa_name'] = $owner['unit_no'] . ' - ' . $owner['owner_name'];
                    $this->bms_fin_coa_model->update_coa($data_coa,$owner['coa_id']);                    
                } else {
                    $last_code = $this->bms_fin_coa_model->get_last_unit_code($owner['property_id']);
                    if(!empty($last_code)) {
                        $list = explode('/',$last_code['coa_code']);
                        $last_no = end($list);
                        $data_coa['property_id'] = $owner['property_id'];
                        $data_coa['coa_type_id'] = 3;
                        $data_coa['coa_code'] = '3000/'.str_pad(++$last_no, 3, '0', STR_PAD_LEFT);
                        $data_coa['coa_name'] = $owner['unit_no'] . ' - ' . $owner['owner_name'];                
                        $owner['coa_id'] = $this->bms_fin_coa_model ->insert_coa($data_coa); 
                    }
                }
            } else {
                if(!empty($owner['coa_id'])) {
                    $data_coa['coa_name'] = $owner['unit_no'] . ' - ' . $owner['owner_name'];
                    $this->bms_fin_coa_model->update_coa($data_coa,$owner['coa_id']);                    
                } 
            }

            if ($owner['email_addr_old'] == $owner['email_addr']) {
                unset( $owner['email_addr_old'] );
            } else {
                $owner['valid_email'] = 1;
                unset ( $owner['email_addr_old'] );
            }

            $this->bms_unit_setup_model->update_unit($owner,$owner['unit_id']);
        } else {                      
            $this->bms_unit_setup_model->set_owner_hist($owner,$unit_owner_id);
        }        
        $_SESSION['flash_msg'] = 'Owner '. ($unit_owner_id == 'new' ? 'Added' : 'Updated') .' successfully!';
        redirect('index.php/bms_unit_setup/add_unit/'.$owner['unit_id'].'?tab=home');
    }
    
    function getTenants ($unit_id) {
                
        //$data['owner_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
        $data['tenant_hist'] = $this->bms_unit_setup_model->get_tenant_hist($unit_id);
        $data['unit_id'] = $unit_id;
        
        $this->load->view('unit_setup/unit_tenants_list_view',$data);        
    }
    
    function getTenant ($id = '',$unit_id='') {
        $data['owner_hist_info'] = array();
        if($id != 'new')
            $data['owner_hist_info'] = $this->bms_unit_setup_model->get_tenant_hist_by_id($id);
        $data['unit_owner_id'] = $id;
        $data['unit_id'] = $unit_id;
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('unit_setup/unit_tenant_form_view',$data);
    
    }
    
    function set_tenant () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $owner = $this->input->post('owner');
        $unit_tenant_id = $this->input->post('unit_tenant_id');
        if(!empty($owner['dob'])) 
            $owner['dob'] = date('Y-m-d',strtotime($owner['dob']));
            
        if(!empty($owner['start_date'])) 
            $owner['start_date'] = date('Y-m-d',strtotime($owner['start_date']));
            
        if(!empty($owner['end_date'])) 
            $owner['end_date'] = date('Y-m-d',strtotime($owner['end_date']));
        
        if(!empty($unit_tenant_id) && $unit_tenant_id == 'new') {            
            $this->bms_unit_setup_model->insert_tenant($owner,$owner['unit_id']);
        } else {
            $this->bms_unit_setup_model->update_tenant($owner,$unit_tenant_id);
        }        
        $_SESSION['flash_msg'] = 'Tenant '. ($unit_tenant_id == 'new' ? 'Added' : 'Updated') .' successfully!';
        redirect('index.php/bms_unit_setup/add_unit/'.$owner['unit_id'].'?tab=tenants');
    }
    
    function getMaUsers ($unit_id) {
        
        $data['ma_user'] = $this->bms_unit_setup_model->get_ma_users($unit_id);
        if(empty($data['ma_user'])) {
            $data['owner_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
            if($data['owner_info']['unit_status'] == '2') {
                $data['tenant_info'] = $this->bms_unit_setup_model->get_current_tenant($unit_id);
            }
            //echo "<pre>";print_r($data['tenant_info']);echo "</pre>";
        }
        $data['tenant_hist'] = array ();//$this->bms_unit_setup_model->get_tenant_hist($unit_id);
        $data['unit_id'] = $unit_id;
        
        $this->load->view('unit_setup/unit_ma_users_view',$data);        
    }
    
    function setMaUsers () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $ma_user = $this->input->post('ma_user');
        if(!empty($ma_user['property_id']) && !empty($ma_user['unit_id'])) {
            $ins_data['property_id'] = $ma_user['property_id'];
            $ins_data['unit_id'] = $ma_user['unit_id'];
            
            $ids = implode(',',array_values(array_filter($ma_user['unit_ma_user_id'])));
            if(!empty($ids))
                $this->bms_unit_setup_model->unset_ma_user($ins_data['unit_id'],$ids);
            //echo "<pre>";print_r($ids);echo "</pre>"; exit;
            $i = 1;
            foreach ($ma_user['ma_user_contact'] as $key=>$val) {
                if(!empty($ma_user['ma_user_contact'][$key]) && $i <= 6) {
                    $ins_data['ma_user_contact'] = $ma_user['ma_user_contact'][$key];
                    $ins_data['ma_user_name'] = !empty($ma_user['ma_user_name'][$key]) ? $ma_user['ma_user_name'][$key] : '';
                    $ins_data['ma_user_email'] = !empty($ma_user['ma_user_email'][$key]) ? $ma_user['ma_user_email'][$key] : '';
                    $ins_data['ma_user_pass'] = !empty($ma_user['ma_user_pass'][$key]) ? $ma_user['ma_user_pass'][$key] : ''; 
                    if(empty($ma_user['unit_ma_user_id'][$key])){
                        $this->bms_unit_setup_model->insert_ma_user($ins_data);
                    } else {
                        $this->bms_unit_setup_model->update_ma_user($ins_data,$ma_user['unit_ma_user_id'][$key]);
                    }
                }
                $i++;
            }
                 
            $_SESSION['flash_msg'] = 'VMS/Mobile App Users Updated successfully!';
        }
        
        redirect('index.php/bms_unit_setup/add_unit/'.$ma_user['unit_id'].'?tab=ma_users');
    }
    
    function getVehicles ($unit_id) {
                
        //$data['owner_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
        $data['vehicles'] = $this->bms_unit_setup_model->get_vehicles($unit_id);
        $data['vehicle_type'] = $this->config->item('vehicle_type');
        $data['unit_id'] = $unit_id;
        
        $this->load->view('unit_setup/unit_vehicles_list_view',$data);        
    }
    
    function getVehicle ($id = '',$unit_id='') {
        $data['vehicle_info'] = array();
        if($id != 'new')
            $data['vehicle_info'] = $this->bms_unit_setup_model->get_vehicle_by_id($id);
        $data['vehicle_type'] = $this->config->item('vehicle_type');
        $data['vehicle_id'] = $id;
        $data['unit_id'] = $unit_id;
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('unit_setup/unit_vehicle_form_view',$data);    
    }
    
    function set_vehicle () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $vehicle = $this->input->post('vehicle');
        $vehicle_id = $this->input->post('vehicle_id');
        
        if(!empty($vehicle_id) && $vehicle_id == 'new') {            
            $this->bms_unit_setup_model->insert_vehicle($vehicle,$vehicle['unit_id']);
        } else {
            $this->bms_unit_setup_model->update_vehicle($vehicle,$vehicle_id);
        }        
        $_SESSION['flash_msg'] = 'Vehicle '. ($vehicle_id == 'new' ? 'Added' : 'Updated') .' successfully!';
        redirect('index.php/bms_unit_setup/add_unit/'.$vehicle['unit_id'].'?tab=vehicles');
    }
    
    function getCharges ($unit_id) {
                
        //$data['owner_info'] = $this->bms_unit_setup_model->get_owner_current($unit_id);
        $data['charge_types'] = $this->bms_unit_setup_model->getChargeTypes ();
        $data['charge_codes'] = $this->bms_masters_model->getChargeCodes ();
        $data['charges_mand'] = $this->bms_unit_setup_model->get_charges_mand($unit_id);
        $data['charges'] = $this->bms_unit_setup_model->get_charges($unit_id);
        $data['pay_by'] = $this->config->item('pay_by');
        $data['charg_mand_base'] = $this->bms_unit_setup_model->get_unit_charg_details($unit_id);
        $data['unit_id'] = $unit_id;
        //echo "<pre>";print_r($data['charg_mand_base']);echo "</pre>"; exit;
        $this->load->view('unit_setup/unit_charges_view',$data);        
    }
    
    function set_charges () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $unit_id = $this->input->post('unit_id');
        $charges = $this->input->post('charges');
        if(!empty($charges) && !empty ($unit_id)) {
            $charges_all_id = array_filter(array_values($charges['charges_id']));
            if(!empty($charges_all_id)) {
                $this->bms_unit_setup_model->removeCharges ($charges_all_id,$unit_id);
            }
            $update_data = array ();
            foreach ($charges['charge_type_id'] as $key=>$val) {
                if($charges['charge_type_id'] != '') {
                    $update_data['unit_id'] = $unit_id;
                    $update_data['charge_type_id'] = $charges['charge_type_id'][$key];
                    $update_data['amount'] = $charges['amount'][$key];
                    $update_data['effect_date'] = isset($charges['effect_date'][$key]) && $charges['effect_date'][$key] != '' ? date('Y-m-d',strtotime($charges['effect_date'][$key])) : '0000-00-00';
                    $update_data['charge_code_id'] = $charges['charge_code_id'][$key];
                    $update_data['pay_by'] = $charges['pay_by'][$key];
                    if(empty($charges['charges_id'][$key])) {
                        // insert process
                        $this->bms_unit_setup_model->insertCharges ($update_data);
                    } else {
                        // update process                        
                        $this->bms_unit_setup_model->updateCharges ($update_data,$charges['charges_id'][$key]);
                    }
                }
                        
            }
            $_SESSION['flash_msg'] = 'Charges Added / Updated successfully!';
        }
        redirect('index.php/bms_unit_setup/add_unit/'.$unit_id.'?tab=charges');
        
    }
    
    function getParking ($unit_id) {
        $data['charge_codes'] = $this->bms_masters_model->getChargeCodes ();
        $data['parking_type'] = $this->config->item('parking_type');
        $data['parking'] = $this->bms_unit_setup_model->get_parking($unit_id);
        $data['unit_id'] = $unit_id;
        
        $this->load->view('unit_setup/unit_parking_view',$data);  
    }
    
    function set_parking () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $unit_id = $this->input->post('unit_id');
        $parking = $this->input->post('parking');
        if(!empty($parking) && !empty ($unit_id)) {
            $parking_all_id = array_filter(array_values($parking['parking_id']));
            if(!empty($parking_all_id)) {
                $this->bms_unit_setup_model->removeParking ($parking_all_id,$unit_id);
            }

            $update_data = array ();
            foreach ($parking['parking_no'] as $key=>$val) {
                if($parking['parking_no'][$key] != '') {
                    $update_data['unit_id'] = $unit_id;
                    $update_data['parking_no'] = $parking['parking_no'][$key];  
                    $update_data['amount'] = !empty($parking['amount'][$key]) ? $parking['amount'][$key] : '';
                    $update_data['effect_date'] = isset($parking['effect_date'][$key]) && $parking['effect_date'][$key] != '' ? date('Y-m-d',strtotime($parking['effect_date'][$key])) : '0000-00-00';    
                    $update_data['charge_code_id'] = $parking['charge_code_id'][$key];
                    $update_data['parking_type'] = $parking['parking_type'][$key];
                    if(empty($parking['parking_id'][$key])) {
                        // insert process
                        $this->bms_unit_setup_model->insertParking ($update_data);
                    } else {
                        // update process                        
                        $this->bms_unit_setup_model->updateParking ($update_data,$parking['parking_id'][$key]);
                    }
                }
                        
            }
            $_SESSION['flash_msg'] = 'Parking Added / Updated successfully!';
        }
        redirect('index.php/bms_unit_setup/add_unit/'.$unit_id.'?tab=parking');
        
    }
    
    function getAccessCard ($unit_id) {
        $data['charge_codes'] = $this->bms_masters_model->getChargeCodes ();
        $data['access_card_type'] = $this->config->item('access_card_type');
        $data['access_card'] = $this->bms_unit_setup_model->get_access_card($unit_id);
        $data['unit_id'] = $unit_id;
        
        $this->load->view('unit_setup/unit_access_card_view',$data);  
    }
    
    function set_access_card () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $unit_id = $this->input->post('unit_id');
        $access_card = $this->input->post('access_card');
        if(!empty($access_card) && !empty ($unit_id)) {
            $access_card_all_id = array_filter(array_values($access_card['access_card_id']));
            if(!empty($access_card_all_id)) {
                $this->bms_unit_setup_model->removeAccessCard ($access_card_all_id,$unit_id);
            }
            $update_data = array ();
            foreach ($access_card['access_card_no'] as $key=>$val) {
                if($access_card['access_card_no'][$key] != '') {
                    $update_data['unit_id'] = $unit_id;
                    $update_data['access_card_no'] = $access_card['access_card_no'][$key]; 
                    $update_data['amount'] = !empty($access_card['amount'][$key]) ? $access_card['amount'][$key] : '';
                    $update_data['effect_date'] = isset($access_card['effect_date'][$key]) && $access_card['effect_date'][$key] != '' ? date('Y-m-d',strtotime($access_card['effect_date'][$key])) : '';              
                    $update_data['charge_code_id'] = $access_card['charge_code_id'][$key];
                    $update_data['access_card_type'] = $access_card['access_card_type'][$key];
                    if(empty($access_card['access_card_id'][$key])) {
                        // insert process
                        $this->bms_unit_setup_model->insertAccessCard ($update_data);
                    } else {
                        // update process                        
                        $this->bms_unit_setup_model->updateAccessCard ($update_data,$access_card['access_card_id'][$key]);
                    }
                }
                        
            }
            $_SESSION['flash_msg'] = 'Access Card Added / Updated successfully!';
        }
        redirect('index.php/bms_unit_setup/add_unit/'.$unit_id.'?tab=access_card');
    }

    public function invalid_email_list ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Invalid Emails';
        $data['page_header'] = '<i class="fa fa-cog"></i> Invalid Emails <i class="fa fa-angle-double-right"></i> Invalid Emails List';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;

        $this->load->view('unit_setup/invalid_email_list_view',$data);
    }

    public function get_invalid_email_list () {
        header('Content-type: application/json');
        $units = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $units = $this->bms_unit_setup_model->get_invalid_email_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($units);
    }
    
}