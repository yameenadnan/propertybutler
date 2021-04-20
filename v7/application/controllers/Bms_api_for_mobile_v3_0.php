<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
#header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

class Bms_api_for_mobile_v3_0 extends CI_Controller {

    function __construct () {
        parent::__construct ();
        header('Content-type: application/json');
        //echo "<pre>".json_encode($_POST);
        $inputs = json_decode(file_get_contents('php://input'));

        /*if($this->uri->segment(2) != 'minorTaskImage' && $_GET['auth_key'] != 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$'){
          if(!isset($inputs->auth_key) || $inputs->auth_key != 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$'){
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Authentication failed!'));
            echo json_encode(array($data));	exit;
          }
        }*/

        if(!((isset($inputs->auth_key) && $inputs->auth_key === 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$') || ($this->uri->segment(2) == 'minorTaskImage' && isset($_GET['auth_key']) && $_GET['auth_key'] === 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$') || ($this->uri->segment(2) == 'defectImage' && isset($_GET['auth_key']) && $_GET['auth_key'] === 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$'))) {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Authentication failed!'));
            echo json_encode(array($data));	exit;
        }

        $this->load->model('bms_masters_model_v3_0','bms_masters_model');
        $this->load->model('bms_task_model_v3_0','bms_task_model');
        $this->load->model('bms_agm_egm_model','bms_agm_egm_model');
        $this->load->model('bms_defect_model');
    }

    public function checkMemberLogin() {

        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && isset($inputs->username) && trim($inputs->username) != '' && isset($inputs->password) && trim($inputs->password) != '') {

            $username = trim($inputs->username);//trim($this->input->post('username'));
            $pass = trim($inputs->password);//trim($this->input->post('password'));
            $result = $this->bms_masters_model->auth($username,$pass);
            $user_info = array();
            if(count($result) == 1 ) {

                $user_info['user_type'] = 'staff';
                $user_info['full_name'] = trim($result[0]['first_name']).' '.  trim($result[0]['last_name']);
                $user_info['first_name'] = isset($result[0]['first_name']) && $result[0]['first_name'] != '' ? $result[0]['first_name'] : '';
                $user_info['email'] = $result[0]['email_addr'];
                $user_info['staff_id'] = $result[0]['staff_id'];
                $user_info['designation_id'] = $result[0]['designation_id'];
                $user_info['mobile_no'] = $result[0]['mobile_no'];

                $data['Data'] = array('user_info'=>array($user_info));
                $data['Status'] = true;
                $data['ErrorLog'] = array();

            } else {
                $result = $this->bms_masters_model->auth_jmb($username,$pass);
                if(count($result) > 0 ) {
                    $user_info['first_name'] = $user_info['full_name'] = trim($result[0]['owner_name']);
                    $user_info['email'] = $result[0]['email_addr'];
                    $user_info['staff_id'] = 0;
                    $user_info['property_id'] = $result[0]['property_id'];
                    $user_info['unit_id'] = $result[0]['unit_id'];
                    $user_info['unit_no'] = $result[0]['unit_no'];
                    $user_info['member_id'] = $result[0]['member_id'];
                    $user_info['designation_id'] = $user_info['user_type']  = 'jmb';
                    $user_info['mobile_no'] = $result[0]['contact_1'];
                    $data['Data'] = array('user_info'=>array($user_info));
                    $data['Status'] = true;
                    $data['ErrorLog'] = array();
                } else {
                    $result = $this->bms_masters_model->auth_owner($username,$pass);
                    if(count($result) > 0 ) {
                        $user_info['first_name'] = $user_info['full_name'] = trim($result[0]['owner_name']);
                        $user_info['email'] = $result[0]['email_addr'];
                        $user_info['staff_id'] = -1;
                        $user_info['property_id'] = $result[0]['property_id'];
                        $user_info['unit_id'] = $result[0]['unit_id'];
                        $user_info['unit_no'] = $result[0]['unit_no'];
                        //$user_info['member_id'] = $result[0]['member_id'];
                        $user_info['designation_id'] = $user_info['user_type']  = 'owner';
                        $user_info['mobile_no'] = $result[0]['contact_1'];
                        $data['Data'] = array('user_info'=>array($user_info));
                        $data['Status'] = true;
                        $data['ErrorLog'] = array();
                    } else {
                        $result = $this->bms_masters_model->auth_resident($username,$pass);
                        if(count($result) > 0 ) {
                            $user_info['first_name'] = $user_info['full_name'] = trim($result[0]['tenant_name']);
                            $user_info['email'] = $result[0]['email_addr'];
                            $user_info['staff_id'] = -1;
                            $user_info['property_id'] = $result[0]['property_id'];
                            $user_info['unit_id'] = $result[0]['unit_id'];
                            $user_info['unit_no'] = $result[0]['unit_no'];
                            //$user_info['member_id'] = $result[0]['member_id'];
                            $user_info['designation_id'] = $user_info['user_type']  = 'tenant';
                            $user_info['mobile_no'] = $result[0]['contact_1'];
                            $data['Data'] = array('user_info'=>array($user_info));
                            $data['Status'] = true;
                            $data['ErrorLog'] = array();
                        } else {
                            $result = $this->bms_masters_model->auth_vms_ma_users($username,$pass);
                            if(count($result) > 0 ) {
                                $user_info['first_name'] = $user_info['full_name'] = trim($result[0]['ma_user_name']);
                                $user_info['email'] = $result[0]['ma_user_email'];
                                $user_info['staff_id'] = -1;
                                $user_info['property_id'] = $result[0]['property_id'];
                                $user_info['unit_id'] = $result[0]['unit_id'];
                                $user_info['unit_no'] = $result[0]['unit_no'];
                                //$user_info['member_id'] = $result[0]['member_id'];
                                $user_info['designation_id'] = $user_info['user_type']  = 'tenant';
                                $user_info['mobile_no'] = $result[0]['ma_user_contact'];
                                $data['Data'] = array('user_info'=>array($user_info));
                                $data['Status'] = true;
                                $data['ErrorLog'] = array();
                                //$user_info['user_type']  = 'vms_ma_user';
                            } else {
                                $data['Data'] = array();
                                $data['Status'] = false;
                                $data['ErrorLog'] = array(array('message'=>'Invalid Email or Password!'));
                            }
                        }
                    }
                }
            }
            if(!empty($user_info) && !empty($inputs->push_token)) {
                $user_info['push_token'] = $insert_data['push_token'] = $inputs->push_token;
                $tbl_name = '';
                switch ($user_info['user_type']) {
                    case 'staff':
                        $tbl_name = 'bms_staff';
                        $update_id['staff_id'] = $user_info['staff_id'];
                        break;
                    case 'jmb':
                    case 'owner':
                        $tbl_name = 'bms_property_units';
                        $update_id['email_addr'] = $user_info['email'];
                        break;
                    case 'tenant':
                        $tbl_name = 'bms_property_unit_tenants';
                        $update_id['email_addr'] = $user_info['email'];
                        break;
                    case 'vms_ma_user':
                        $tbl_name = 'bms_property_unit_ma_users';
                        $update_id['ma_user_email'] = $user_info['email'];
                        break;
                }
                $this->bms_masters_model->set_push_token ($tbl_name,$insert_data,$update_id);
            }

        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Please enter Email and Password!'));
        }
        echo json_encode(array($data));
        //echo json_encode();
    }

    public function checkActiveUser() {

        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && isset($inputs->username) && trim($inputs->username) != '' && isset($inputs->user_type) && trim($inputs->user_type) != '') {

            $username = trim($inputs->username);//trim($this->input->post('username'));
            $user_type = trim($inputs->user_type);//trim($this->input->post('password'));
            $result = array();

            switch ($user_type) {
                case 'staff':
                    $result = $this->bms_masters_model->staffActive ($username);
                    break;
                case 'jmb':
                    $result = $this->bms_masters_model->jmbActive ($username);
                    break;
                case 'owner':
                    $result = $this->bms_masters_model->ownerActive ($username);
                    break;
                case 'tenant':
                    $result = $this->bms_masters_model->residentActive ($username);
                    if(count($result) == 0) {
                        $result = $this->bms_masters_model->vms_ma_usersActive ($username);
                    }
                    break;
            }
            if(!empty($result)) {
                $data['Data'] = array(array('message'=>'Success!'));
                $data['Status'] = true;
                $data['ErrorLog'] = array();
            } else {
                $data['Data'] = array(array('message'=>'Failure!'));
                $data['Status'] = false;
                $data['ErrorLog'] = array();
            }

        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Mandatory Field Missing!'));
        }
        echo json_encode(array($data));
        //echo json_encode();
    }

    public function getAllProperty() {

        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getProperties ());
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
        //echo json_encode();
    }

    public function getMyProperty() {
        $inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getMyProperties ($inputs->staff_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getMyPropertyNew() {
        $inputs = json_decode(file_get_contents('php://input'));
        $result = $this->bms_masters_model->getMyProperties ($inputs->staff_id);

        for ($i=0; $i < count($result); $i++) {
            $result[$i]['Name'] = $result[$i]['property_name'];
            $result[$i]['Value'] = $result[$i]['property_id'];
            $result[$i]['Id'] = $result[$i]['property_id'];
            $result[$i]['Code'] = $result[$i]['property_id'];
        }

        $data['Data'] = array('bms_property'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getJmbProperties() {
        $inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getJmbProperties ($inputs->property_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getJmbPropertiesNew() {
        $inputs = json_decode(file_get_contents('php://input'));
        $result = $this->bms_masters_model->getJmbProperties ($inputs->property_id);

        for ($i=0; $i < count($result); $i++) {
            $result[$i]['Name'] = $result[$i]['property_name'];
            $result[$i]['Value'] = $result[$i]['property_id'];
            $result[$i]['Id'] = $result[$i]['property_id'];
            $result[$i]['Code'] = $result[$i]['property_id'];
            $result[$i]['property_under'] = $result[$i]['property_under'];
            $result[$i]['jmb_mc_name'] = $result[$i]['jmb_mc_name'];
            $result[$i]['address_1'] = $result[$i]['address_1'];
            $result[$i]['address_2'] = $result[$i]['address_2'];
            $result[$i]['city'] = $result[$i]['city'];
            $result[$i]['pin_code'] = $result[$i]['pin_code'];
            $result[$i]['state_name'] = $result[$i]['state_name'];
            $result[$i]['phone_no'] = $result[$i]['phone_no'];
            $result[$i]['fax'] = $result[$i]['fax'];
            $result[$i]['email_addr'] = $result[$i]['email_addr'];
            
        }

        $data['Data'] = array('bms_property'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAvailbleUnit () {
        $result = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && isset($inputs->email) && trim($inputs->email) != '') {
            $result = $this->bms_masters_model->getAvailbleUnits ($inputs->email);
        }
        $data['Data'] = array('availble_units'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAllBlockRStreet() {
        $inputs = json_decode(file_get_contents('php://input'));
        $result = $this->bms_masters_model->getBlocks ($inputs->propertyID);

        for ($i=0; $i < count($result); $i++) {
            $result[$i]['Name'] = $result[$i]['block_name'];
            $result[$i]['Value'] = $result[$i]['block_id'];
            $result[$i]['Id'] = $result[$i]['block_id'];
            $result[$i]['Code'] = $result[$i]['block_id'];
        }

        $data['Data'] = array('bms_property_block'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAllUnitNumber() {
        $inputs = json_decode(file_get_contents('php://input'));
        $result = $this->bms_masters_model->getUnit ($inputs->propertyID,$inputs->blockID);

        for ($i=0; $i < count($result); $i++) {
            $result[$i]['Name'] = $result[$i]['unit_no'];
            $result[$i]['Value'] = $result[$i]['unit_id'];
            $result[$i]['Id'] = $result[$i]['unit_id'];
            $result[$i]['Code'] = $result[$i]['unit_id'];
        }

        $data['Data'] = array('bms_property_units'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAllTaskCategories() {

        $task_cat = $this->config->item('task_cat');
        foreach ($task_cat as $key=>$val) {
            $task_cat_arr[$key-1]['task_cat_id'] = $key;
            $task_cat_arr[$key-1]['task_cat_name'] = $val;
            $task_cat_arr[$key-1]['Id'] = $key;
            $task_cat_arr[$key-1]['Value'] = $key;
            $task_cat_arr[$key-1]['Name'] = $val;
        }

        $data['Data'] = array('bms_task_category'=>$task_cat_arr);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAllSourceofAssignment() {
        $source_assign = $this->config->item('source_assign');
        foreach ($source_assign as $key=>$val) {
            $source_assign_arr[$key-1]['sa_id'] = $key;
            $source_assign_arr[$key-1]['sa_name'] = $val;
            $source_assign_arr[$key-1]['Name'] = $val;
            $source_assign_arr[$key-1]['Value'] = $key;
            $source_assign_arr[$key-1]['Id'] = $key;
            $source_assign_arr[$key-1]['Code'] = $key;
        }
        $data['Data'] = array('bms_source_assign'=>$source_assign_arr);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getAllAssignToDesignation() {
        $inputs = json_decode(file_get_contents('php://input'));
        $result = $this->bms_masters_model->getAssignTo ($inputs->propertyID);

        for ($i=0; $i < count($result); $i++) {
            $result[$i]['Name'] = $result[$i]['desi_name'];
            $result[$i]['Value'] = $result[$i]['desi_id'];
            $result[$i]['Id'] = $result[$i]['desi_id'];
            $result[$i]['Code'] = $result[$i]['desi_id'];
        }

        $data['Data'] = array('bms_designation'=>$result);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    public function getTaskUpdateDd() {
        $task_update = $this->config->item('task_update');
        foreach ($task_update as $key=>$val) {
            $task_update_arr[$key-1]['tu_id'] = $key;
            $task_update_arr[$key-1]['tu_name'] = $val;
            $task_update_arr[$key-1]['Name'] = $val;
            $task_update_arr[$key-1]['Value'] = $key;
            $task_update_arr[$key-1]['Id'] = $key;
            $task_update_arr[$key-1]['Code'] = $key;
        }
        $data['Data'] = array('bms_task_update'=>$task_update_arr);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    function task_image_submit () {

        $data = array ();
        if(!empty($_FILES)){
            $file_ifo_at_start = $_FILES;
            $task_file_upload_temp = $this->config->item('task_file_upload_temp');

            $this->load->library('upload');

            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $task_file_upload_temp['file_name'] = $date->format("YmdHisu");

            /*$file_ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            if($file_ext == "") {
                //$exten = array_pop(explode('/',$_FILES["file"]["type"]));
                $_FILES["file"]["name"] = $_FILES["file"]["name"].".jpg";
            } else if(strtolower($file_ext) == 'png') {
                $_FILES["file"]["type"] = 'image/png';
            }*/
            /*$file_name_arr = explode('?',$_FILES["file"]["name"]);
            $_FILES["file"]["name"] = $file_name_arr[0];
            $file_ext = end(explode('.',$file_name_arr[0]));
            if(strtolower($file_ext) == 'png') {
                $_FILES["file"]["type"] = 'image/png';
            }*/
            //$data['debug'] = array('debug_msg'=>$file_ext . ' - '. $_FILES["file"]["name"] ." - ".$_FILES["file"]["type"]);

            $this->upload->initialize($task_file_upload_temp);

            if ( ! $this->upload->do_upload('file') ) {
                $data['Data'] = array();
                $data['Status'] = false;
                $data['ErrorLog'] = array(array('message'=>$this->upload->display_errors(),'file_info_init'=>$file_ifo_at_start,'file_info_end'=>$_FILES));
            } else {
                $data['Data'] = array('file_name'=>$this->upload->data('file_name'));
                $data['Status'] = true;
                $data['ErrorLog'] = array();
            }

        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'File is not posted'));

        }
        echo json_encode(array($data));
    }

    function getAllMyMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $desi_id = isset($inputs->desiID) && trim($inputs->desiID) != '' ? trim ($inputs->desiID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $task_id = isset($inputs->task_id) && trim($inputs->task_id) != '' ? trim ($inputs->task_id) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 20;
        $search_txt = isset($inputs->search_text) && trim($inputs->search_text) != '' ? trim ($inputs->search_text) : '';
        $sort_by = isset($inputs->sort_by) && trim($inputs->sort_by) != '' ? trim ($inputs->sort_by) : 'due_date';

        $data['Data'] = array('bms_task'=>$this->bms_task_model->get_task ('own',$desi_id,$staff_id,$offset,$rows,$property_id,$task_status,$task_id,$search_txt,$sort_by));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    function getAllOverseeingMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $desi_id = isset($inputs->desiID) && trim($inputs->desiID) != '' ? trim ($inputs->desiID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $task_id = isset($inputs->task_id) && trim($inputs->task_id) != '' ? trim ($inputs->task_id) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 10;
        $sort_by = isset($inputs->sort_by) && trim($inputs->sort_by) != '' ? trim ($inputs->sort_by) : 'due_date';
        $search_txt = isset($inputs->search_text) && trim($inputs->search_text) != '' ? trim ($inputs->search_text) : '';
        $data['Data'] = array('bms_task_os'=>$this->bms_task_model->get_task_with_num_rows ('oversee',$desi_id,$staff_id,$offset,$rows,$property_id,$task_status,$task_id,$search_txt,$sort_by));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    function getAllJmbMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $task_id = isset($inputs->task_id) && trim($inputs->task_id) != '' ? trim ($inputs->task_id) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 10;
        $sort_by = isset($inputs->sort_by) && trim($inputs->sort_by) != '' ? trim ($inputs->sort_by) : 'due_date';
        $search_txt = isset($inputs->search_text) && trim($inputs->search_text) != '' ? trim ($inputs->search_text) : '';
        $unit_id = isset($inputs->unit_id) && trim($inputs->unit_id) != '' ? trim ($inputs->unit_id) : '';

        $data['Data'] = array('bms_task_jmb'=>$this->bms_task_model->get_task_with_num_rows_jmb ($property_id,$task_status,$offset,$rows,$task_id,$search_txt,$sort_by,$unit_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    function createMinorTask () {

        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && !empty($inputs)) {
            //$this->load->model('bms_task_model');
            //$task = $this->input->post('task');

            $task['created_date'] = date('Y-m-d');
            $task['property_id'] = $inputs->property_id;
            $task['unit_id'] = $inputs->unit_id;
            $task['task_name'] = $inputs->task_name;
            $task['task_location'] = $inputs->task_location;
            $task['task_details'] = $inputs->task_details;
            $task['task_category'] = $inputs->task_category;
            $task['task_source'] = $inputs->task_source;
            $task['assign_to'] = $inputs->assign_to;
            $task['created_by'] = $inputs->created_by;
            $task['created_by_type'] = $task['created_by'] == '0' ? 'J' : ($task['created_by'] == '-1' ? 'R' : 'S');

            if ($task['created_by'] == '-1') {
                $assign_to = $this->bms_masters_model->getAssignTo ($task['property_id']);

                $desi_arr = !empty($assign_to) ? array_column($assign_to,'desi_id') : array ();

                if(in_array(31,$desi_arr)) {
                    $task['assign_to'] = 31; // Customer Service Manager
                } else if(empty($desi_arr)) {
                    $task['assign_to'] = 14; // Director
                } else if(in_array(19,$desi_arr)) {
                    $task['assign_to'] = 19; // Admin Cleark
                } else if(in_array(9,$desi_arr)) {
                    $task['assign_to'] = 9; // BE
                } else if(in_array(3,$desi_arr)) {
                    $task['assign_to'] = 3; // BM
                } else if(in_array(2,$desi_arr)) {
                    $task['assign_to'] = 2; // AM
                } else {
                    $task['assign_to'] = 14; // Director
                }
                /*$task['task_details'] .= ' designation array => ';
                foreach ($desi_arr as $key=>$val)
                $task['task_details'] .= $key . ' => '.$val . ' '; */
            }

            if(isset($inputs->due_date) && $inputs->due_date != ''){
                $task['due_date'] = date('Y-m-d', strtotime($inputs->due_date));

            }else{
                $date = strtotime("+7 day");
                $task['due_date'] = date('Y-m-d', $date);
            }

            $task['task_status'] = 'O';

            /*$task['task_details'] .= ' resident_email_hidd => '.$inputs->resident_email_hidd;
            $task['task_details'] .= ' resident_gender_hidd => '.$inputs->resident_gender_hidd;
            $task['task_details'] .= ' resident_name_hidd => '.$inputs->resident_name_hidd;
            $task['task_details'] .= ' Task array => ';
            foreach ($task as $key=>$val)
                $task['task_details'] .= $key . ' => '.$val;  */
            $insert_id = $this->bms_task_model->task_insert($task);

            if(!empty($inputs->files)){

                $task_file_upload = $this->config->item('task_file_upload');
                $task_file_upload['upload_path'] = $task_file_upload['upload_path'].$insert_id.'/';
                if(!is_dir($task_file_upload['upload_path']));
                @mkdir($task_file_upload['upload_path'], 0777);

                $task_file_upload_temp = $this->config->item('task_file_upload_temp');

                foreach ($inputs->files as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $task_file_upload['upload_path'].$val);
                    $img_data['task_id'] = $insert_id;
                    $img_data['img_name'] = $val;
                    $this->bms_task_model->task_image_name_insert ($img_data);
                }

            }

            if(!empty($inputs->images)){

                foreach ($inputs->images as $value) {

                    $image = base64_decode($value->data);
                    $image_name = md5(uniqid(rand(), true));
                    $filename = $image_name . '.' . 'jpg';

                    $task_file_upload = $this->config->item('task_file_upload');
                    $task_file_upload['upload_path'] = $task_file_upload['upload_path'].$insert_id.'/';
                    if(!is_dir($task_file_upload['upload_path']));
                    @mkdir($task_file_upload['upload_path'], 0777);

                    $path = $task_file_upload['upload_path'];
                    file_put_contents($path.$filename, $image);

                    $filePath = $path.$filename;
                    $size_arr = getimagesize($filePath);
                    list($width_orig, $height_orig) = $size_arr;
                    $width = 1024;
                    $height = 768;
                    $ratio_orig = $width_orig / $height_orig;

                    if ($width / $height > $ratio_orig) {
                        $width = floor($height * $ratio_orig);
                    } else {
                        $height = floor($width / $ratio_orig);
                    }
                    $im = ImageCreateFromJpeg($filePath);
                    $tempimg = imagecreatetruecolor($width, $height);
                    imagecopyresampled($tempimg, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    ob_start();
                    imagejpeg($tempimg, $path.$filename, 90);
                    ob_end_clean();

                    $img_data['task_id'] = $insert_id;
                    $img_data['img_name'] = $filename;
                    $this->bms_task_model->task_image_name_insert ($img_data);

                }

            }

            // for task log
            $this->bms_task_model->set_task_create_log($insert_id,$task['created_by'],$task['due_date']);

            // push notification
            $this->notifications->sendPushNotification($task['property_id'],$inputs->property_name,$task['task_name'],$insert_id,$task['unit_id'],'task_created');

            // for notification
            $this->bms_task_model->task_alert_insert ($insert_id,$task['property_id'],'1');

            if ($task['created_by'] == '-1' && !empty($task['unit_id'])) {
                $unit_info = $this->bms_masters_model->getUnitDetails ($task['unit_id']);
                if(!empty($unit_info)) {
                    $inputs->resident_email_hidd = $unit_info->email_addr;
                    $inputs->resident_name_hidd = $unit_info->owner_name;
                    $inputs->resident_gender_hidd = $unit_info->gender;
                }
            }

            // Notification emil to resident for task creation
            if(!empty($inputs->resident_email_hidd)) {
                $property_info = $this->bms_masters_model->getPropertyInfo ($task['property_id']);

                $to = $inputs->resident_email_hidd;
                $r_name = !empty($inputs->resident_name_hidd) ? $inputs->resident_name_hidd : '';

                $task_cat = $this->config->item('task_cat');
                $source_assign = $this->config->item('source_assign');

                $this->load->library('email');

                $subject = $inputs->task_name .' | '. $inputs->property_name;
                $message = '<p>To <b>';
                if(!empty($inputs->resident_gender_hidd)) {
                    $message .= $inputs->resident_gender_hidd == 'Male' ? 'Mr ' : ($inputs->resident_gender_hidd == 'Female' ? 'Ms ' : '');
                }
                $message .= $r_name;
                $message .= ',</b><br /><br />';

                $message .= 'We thank you for your '.$source_assign[$inputs->task_source].' highlighting the ';
                $message .= $task_cat[$inputs->task_category] . '. A task has been created as per below description. We keep this task as our highest priority and looking forward to solve as soon as possible. We will notify you when the task is solved for your kind reference.<br /><br />';

                $message .= 'Task Id: '.str_pad($insert_id, 5, '0', STR_PAD_LEFT) .'<br />';
                $message .= 'Task Name: '.$inputs->task_name .'<br />';
                $message .= 'Task Location : '.(!empty($inputs->task_location) ? $inputs->task_location : ' - ' ) .'<br />';
                $message .= 'Task Details : '.(!empty($inputs->task_details) ? $inputs->task_details : ' - ' ) .'<br /><br />';

                $message .= 'Thank you,<br />Transpacc <br />'.$inputs->property_name;

                $message .= '</p>';

                // Get full html:
                $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                    <title>' . html_escape($subject) . '</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, Verdana, Helvetica, sans-serif;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                ' . $message . '
                <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;">
                This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office.
                </div>
                </body>
                </html>';
                // Also, for getting full html you may use the following internal method:
                //$body = $this->email->full_html($subject, $message);

                $result = $this->email
                    ->from('noreply@propertybutler.my','Propertybutler')
                    //->reply_to($property_info['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                    ->to($to,$r_name)
                    ->bcc('naguwin@gmail.com','Nagarajan')
                    //->bcc('tanbenghwa@gmail.com')
                    //->bcc('email@transpacc.com.my','Transpacc Emails')
                    ->subject($subject)
                    ->message($body)
                    ->send();

            }

            $data['Data'] = array('message'=>'Task Created Successfully!');
            $data['Status'] = true;
            $data['ErrorLog'] = array();
            echo json_encode(array($data));
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task Creation error!'));
            echo json_encode($data);
        }
    }

    function minorTaskImage() {

        $task_file_upload_temp = $this->config->item('task_file_upload_temp');
        $target_dir = $task_file_upload_temp['upload_path'];

        if(!file_exists($target_dir)){
            mkdir($target_dir, 0777,true);
        }

        $time =microtime(true);
        $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
        $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
        $task_file_upload_temp['file_name'] = $date->format("YmdHisu");

        $target_dir = $target_dir."/".$task_file_upload_temp['file_name'].".jpeg";

        $filePath = $_FILES['image']['tmp_name'];
        $size_arr = getimagesize($filePath);
        list($width_orig, $height_orig) = $size_arr;
        $width = 768;
        $height = 432;
        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = floor($height * $ratio_orig);
        } else {
            $height = floor($width / $ratio_orig);
        }
        $im = ImageCreateFromJpeg($filePath);
        $tempimg = imagecreatetruecolor($width, $height);
        imagecopyresampled($tempimg, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        ob_start();
        imagejpeg($tempimg, $filePath, 90);
        ob_end_clean();

        if(move_uploaded_file($filePath, $target_dir)) {
            echo json_encode($task_file_upload_temp['file_name']);
        }else{
            echo json_encode(array('message'=>'Upload Failed!'));
        }

    }

    function getTaskDetails () {
        $inputs = json_decode(file_get_contents('php://input'));
        $task_id = isset($inputs->taskID) && trim($inputs->taskID) != '' ? trim ($inputs->taskID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $user_type = isset($inputs->userType) && trim($inputs->userType) != '' ? trim ($inputs->userType) : '';

        if(isset($user_type) && ($user_type == 'jmb' || $user_type == 'owner' || $user_type == 'tenant')) {
            $data['task_details'] = $this->bms_task_model->get_task_details_jmb_mc ($task_id,$property_id);
        } else {
            $data['task_details'] = $this->bms_task_model->get_task_details($task_id,$staff_id);
        }
        if(!empty($data['task_details'])) {
            $task_cat = $this->config->item('task_cat');
            $source_assign = $this->config->item('source_assign');
            $task_update = $this->config->item('task_update');
            $data['task_details']->task_category_name = !empty($data['task_details']->task_category) ? $task_cat[$data['task_details']->task_category] : '';
            $data['task_details']->task_source_name = !empty($data['task_details']->task_source) ? $source_assign[$data['task_details']->task_source] : '';
            $data['task_details']->task_update_name = !empty($data['task_details']->task_update) ? $task_update[$data['task_details']->task_update] : '';
            if(empty($data['task_details']->first_name)) {
                $data['task_details']->first_name = !empty($data['task_details']->created_by) && $data['task_details']->created_by == '0' ? 'JMB / MC' : ($data['task_details']->created_by == '-1' ? 'Resident' : '');
                $data['task_details']->last_name = !empty($data['task_details']->last_name) ? $data['task_details']->last_name : '';
            }

            //$data['task_details']->first_name = !empty($data['task_details']->created_by) ? $source_assign[$data['task_details']->task_source] : '';
        }

        if($data['task_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['task_details']->block_id);
        $data['task_images'] = $this->bms_task_model->get_task_images($task_id);
        $data['task_forum_cnt'] = $this->bms_task_model->getTaskForum($task_id,'cnt');
        // fetch task forum new entry count


        // remove from notification
        $this->bms_task_model->task_alert_delete ($task_id,$user_type,$staff_id);
        $data_out['Data'] = array('task_data'=>array($data));
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        echo json_encode(array($data_out));
    }

    function setTaskStatus () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->taskID) && $inputs->taskID != '') {
            $task_id = $inputs->taskID;
            if(isset($inputs->task_update) && $inputs->task_update == 'Closed') {
                $data['task_status'] = 'C';
                $data['task_close_remarks'] = isset($inputs->close_remarks) && trim($inputs->close_remarks) != '' ? trim ($inputs->close_remarks) : '';
                // for notification
                //$this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'4');
            } else if (isset($inputs->task_update) && $inputs->task_update != '') {
                $data['task_update'] = trim($inputs->task_update);
                if(isset($inputs->due_date) && $inputs->due_date != '') {
                    $data['due_date'] = date('Y-m-d', strtotime($inputs->due_date));
                }
                // for notification
                //$this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'2');
            }
            $data['updated_by'] = $inputs->staff_id;
            $data['updated_date'] = date("Y-m-d");
            $this->bms_task_model->set_task_update_with_log($task_id,$data,$inputs->staff_id);

            // Notification emil to resident for task close
            if(isset($inputs->task_update) && $inputs->task_update == 'Closed') {
                $unit_details = $this->bms_task_model->get_task_details_for_email($task_id);
                //echo "<pre>";print_r($unit_details);echo "</pre>";
                if(!empty($unit_details) && !empty($unit_details['email_addr'])) {

                    //$property_info = $this->bms_masters_model->getPropertyInfo ($task['property_id']);

                    $to = $unit_details['email_addr'];
                    $r_name = !empty($unit_details['owner_name']) ? $unit_details['owner_name'] : '';

                    //$task_cat = $this->config->item('task_cat');
                    //$source_assign = $this->config->item('source_assign');


                    $this->load->library('email');

                    $subject = $unit_details['task_name'] .' | '. $unit_details['property_name'];
                    $message = '<p>To <b>';
                    if(!empty($unit_details['gender'])) {
                        $message .= $unit_details['gender'] == 'Male' ? 'Mr ' : ($unit_details['gender'] == 'Female' ? 'Ms ' : '');
                    }
                    $message .= $r_name;
                    $message .= ',</b><br /><br />';

                    $message .= 'We are pleased to inform you that your complaint has been resolved. Please refer to below task details and remarks.<br /><br />';

                    $message .= '<b>Task Id:</b> '.str_pad($unit_details['task_id'], 5, '0', STR_PAD_LEFT) .'<br />';
                    $message .= '<b>Task Name:</b> '.$unit_details['task_name'] .'<br />';
                    $message .= '<b>Task Location:</b> '.(!empty($unit_details['task_location']) ? $unit_details['task_location'] : ' - ' ) .'<br />';
                    $message .= '<b>Task Details:</b> '.(!empty($unit_details['task_details']) ? $unit_details['task_details'] : ' - ' ) .'<br />';
                    $message .= '<b>Close Remarks:</b> '.(!empty($data['task_close_remarks']) ? $data['task_close_remarks'] : ' - ' ) .'<br /><br />';

                    $message .= 'We hope you are pleased with our service. Should you have any other comments pertaining to this task, please do contact our management office. ';
                    $message .= 'We are here to serve you better.<br /><br />';
                    $message .= 'Thank you,<br />Transpacc <br />'.$unit_details['property_name'];

                    $message .= '</p>';

                    // Get full html:
                    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                        <title>' . html_escape($subject) . '</title>
                        <style type="text/css">
                            body {
                                font-family: Arial, Verdana, Helvetica, sans-serif;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                    ' . $message . '
                    <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;">
                This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office.
                </div>
                    </body>
                    </html>';
                    // Also, for getting full html you may use the following internal method:
                    //$body = $this->email->full_html($subject, $message);

                    $result = $this->email
                        ->from('noreply@propertybutler.my','Propertybutler')
                        //->reply_to($unit_details['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                        ->to($to,$r_name)
                        ->bcc('naguwin@gmail.com')
                        //->bcc('tanbenghwa@gmail.com')
                        //->bcc('email@transpacc.com.my','Transpacc Emails')
                        ->subject($subject)
                        ->message($body)
                        ->send();
                }
            }
            $data_out['Data'] = array('message'=>'Task status updated Successfully!');
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Task status update error!'));
            echo json_encode($data_out);
        }
    }

    function getTaskForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->taskID) {
            $data['Data'] = array('bms_task_forum'=>$this->bms_task_model->getTaskForum($inputs->taskID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    function setTaskForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->task_id) && $inputs->task_id != '' && ((isset($inputs->chat_text) && $inputs->chat_text != '') || !empty($inputs->file))) {
            $task_id = $inputs->task_id;
            $chat_text = isset($inputs->chat_text) ? trim($inputs->chat_text) : '';
            $img_name = '';
            if(!empty($inputs->file)){

                $task_forum_upload = $this->config->item('task_forum_upload');
                $task_forum_upload['upload_path'] = $task_forum_upload['upload_path'].$task_id.'/';
                if(!is_dir($task_forum_upload['upload_path']));
                @mkdir($task_forum_upload['upload_path'], 0777);

                $task_file_upload_temp = $this->config->item('task_file_upload_temp');

                rename($task_file_upload_temp['upload_path'].$inputs->file, $task_forum_upload['upload_path'].$inputs->file);
                $img_name = $inputs->file;

            }

            $this->bms_task_model->set_task_forum($task_id,$chat_text,$img_name,$inputs->staff_id);
            // for notification
            //$this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'3');
            $data['Data'] = array('bms_task_forum'=>$this->bms_task_model->getTaskForum($inputs->task_id));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
            echo json_encode(array($data));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Task forum update error!'));
            echo json_encode($data_out);
        }
    }

    function getTaskLog () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->taskID) {
            $data['Data'] = array('bms_task_log'=>$this->bms_task_model->getTaskLog($inputs->taskID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    // Property Docs

    function getPropertyDocCategory () {
        $this->load->model('bms_property_model');
        $data['Data'] = array('bms_property_doc_cat'=>$this->bms_property_model->getPropertyDocCategory ());
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));

    }

    function getMyPropertiesDocsJmb () {
        $this->load->model('bms_property_model');
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($property_id) {
            $doc_cat_id = isset($inputs->doc_cat_id) && trim($inputs->doc_cat_id) != '' ? trim ($inputs->doc_cat_id) : '';
            $data['Data'] = array('bms_task_log'=>$this->bms_property_model->getMyPropertiesDocsJmb('','',$inputs->property_id,$doc_cat_id));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Property id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    function getNoficationCount() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        $n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';

        $data['Data'] = array('bms_notify_count'=>$this->notifications->get_notification_count($user_type,$user_id,$n_type));
        $data['Status'] = true;
        $data['ErrorLog'] = array();

        echo json_encode(array($data));
    }

    function getNoficationContent() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        $n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';

        if($n_type == 'sop') {
            $data['Data'] = array('bms_notify_count'=>$this->notifications->get_sop_notification_details($user_id));
        } else {
            $data['Data'] = array('bms_notify_count'=>$this->notifications->get_notification_details($user_type,$user_id,$n_type));
        }

        $data['Status'] = true;
        $data['ErrorLog'] = array();

        echo json_encode(array($data));
    }

    function getNoficationCountByCat() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        //$n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';
        $bms_notify_count['create'] = $this->notifications->get_notification_count($user_type,$user_id,'create');
        $bms_notify_count['update'] = $this->notifications->get_notification_count($user_type,$user_id,'update');
        $bms_notify_count['close'] = $this->notifications->get_notification_count($user_type,$user_id,'close');
        if(!empty($user_type) && $user_type == 'staff') {
            $bms_notify_count['sop'] = $this->notifications->get_sop_notification_count($user_id);
        }
        //$data['Data'] = array(array($bms_notify_count)); //
        $data['Data'] = array(array('bms_notify_count_by_cat'=>$bms_notify_count));
        $data['Status'] = true;
        $data['ErrorLog'] = array();

        echo json_encode(array($data));
    }

    function getDailyReport() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $report_date = isset($inputs->report_date) && trim($inputs->report_date) != '' ? trim ($inputs->report_date) : '';
        if($property_id && $report_date) {
            $report_year = date('Y',strtotime($report_date));
            $report_mon = date('m',strtotime($report_date));
            $file_name = "DR_".$property_id."_".date('Y-m-d', strtotime($report_date)).".pdf";
            $daily_report_path = $this->config->item('daily_report_path');
            $result = array ();

            if(file_exists($daily_report_path.$report_year.'/'.$report_mon.'/'.$file_name)) {
                $result['file_name'] = $file_name;
                $result['relative_path'] = $daily_report_path.$report_year.'/'.$report_mon.'/';
                $result['absoulte_path'] = 'https://www.propertybutler.my/bms_uploads/daily_reports/'.$report_year.'/'.$report_mon.'/'.$file_name;
                $result = array($result);
            }
            $data['Data'] = array('bms_report'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Property id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    function rTaskEntryList () {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $desi_id = $inputs->desi_id;
        $staff_id = $inputs->staff_id;
        if($property_id) {
            $this->load->model('bms_sop_model');
            $result['sop_main'] = $this->bms_sop_model->get_sop_entry_list ('','',$property_id,$desi_id,$staff_id);

            $data['Data'] = array('routine_task_entry_list'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Missing mandatory inputs!'));
        }
        echo json_encode(array($data));
    }

    function rTaskKeyIn () {
        $inputs = json_decode(file_get_contents('php://input'));
        $sop_id = $inputs->sop_id;
        if($sop_id) {
            $this->load->model('bms_sop_model');
            $result['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id);
            $today_date = new DateTime(date('Y-m-d'));
            $start_date = new DateTime($result['sop'][0]['start_date']);
            $due_date = isset($result['sop'][0]['due_date']) && $result['sop'][0]['due_date'] != '' && $result['sop'][0]['due_date'] != '0000-00-00' && $result['sop'][0]['due_date'] != '1970-01-01' ? new DateTime($result['sop'][0]['due_date']) : '';

            /*if(!empty($result['sop']) && isset($result['sop'][0][strtolower(date('D'))]) && $result['sop'][0][strtolower(date('D'))] == 1 && $today_date >= $start_date && ((isset($result['sop'][0]['no_due_date']) && $result['sop'][0]['no_due_date'] == 1) || ($due_date != '' && $today_date <= $due_date))) {
                $result['start_date'] = $result['end_date'] = date('Y-m-d');
                $result['sop_entry'] =  $this->bms_sop_model->get_sop_entry ($result['sop'][0]['sop_id'],$result['start_date'],$result['end_date']);
                if(!empty($result['sop_entry'])){
                    redirect('index.php/bms_sop/entry_list?property_id='.$result['sop'][0]['property_id']);
                }
            } */
            if(!empty($result['sop'])) {
                foreach($result['sop'] as $key=>$val) {
                    $result['sub_sop'] = $this->bms_sop_model->get_subsop ($val['sop_id']);
                }
            }
            $data['Data'] = array('routine_task_keyin'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Missing mandatory inputs!'));
        }
        echo json_encode(array($data));

    }

    function rTaskKeyInSubmit () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && !empty($inputs)) {

            $this->load->model('bms_sop_model');

            if(!empty($inputs->sop_condi)) {
                $data['requirement_type'] = 'C';
                $data['requirement_val'] = $inputs->sop_condi;
            }
            if(!empty($inputs->sop_reading)) {
                $data['requirement_type'] = 'R';
                $data['requirement_val'] = $inputs->sop_reading;
            }
            $data['sop_id'] = $inputs->sop_id;
            $data['remarks'] = isset($inputs->remarks) ? trim($inputs->remarks) : '';
            $data['entry_date'] = $data['entered_date'] = date("Y-m-d H:i:s");

            $data['entered_by'] = $inputs->entered_by;
            $sop_entry_id = $this->bms_sop_model->set_sop_entry ($data);

            if(!empty($inputs->files)) {

                $sop_entry_upload = $this->config->item('sop_entry_upload');
                $sop_entry_upload['upload_path'] = $sop_entry_upload['upload_path'].'/'.$sop_entry_id.'/';
                if(!is_dir($sop_entry_upload['upload_path']));
                @mkdir($sop_entry_upload['upload_path'], 0777);

                $task_file_upload_temp = $this->config->item('task_file_upload_temp');

                foreach ($inputs->files as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $sop_entry_upload['upload_path'].$val);
                    $img_data['sop_entry_id'] = $sop_entry_id;
                    $img_data['img_name'] = $val;
                    $this->bms_sop_model->set_sop_entry_image_name ($img_data);
                }
            }

            if(!empty($inputs->sub_sop)){
                foreach($inputs->sub_sop as $key=>$val) {
                    $data_sub = array();
                    $data_sub['sop_sub_id'] = $val->sop_sub_id;
                    $data_sub['sop_id'] = $inputs->sop_id;
                    if(!empty($val->condi)) {
                        $data_sub['requirement_type'] = 'C';
                        $data_sub['requirement_val'] = isset($val->condi) ? $val->condi : '';
                    }
                    if(!empty($val->reading)) {
                        $data_sub['requirement_type'] = 'R';
                        $data_sub['requirement_val'] = isset($val->reading) ? $val->reading : '';
                    }
                    $data_sub['remarks'] = isset($val->remarks) ? trim($val->remarks) : '';

                    $data_sub['entry_date'] = $data_sub['entered_date'] = date("Y-m-d H:i:s");

                    $data_sub['entered_by'] = $inputs->entered_by;
                    $sop_sub_entry_id = $this->bms_sop_model->set_sop_sub_entry ($data_sub);

                    if(!empty($val->files)) {

                        $img_data = array();
                        $sop_sub_entry_upload = $this->config->item('sop_sub_entry_upload');

                        $sop_sub_entry_upload['upload_path'] = $sop_sub_entry_upload['upload_path'].'/'.$sop_sub_entry_id.'/';
                        if(!is_dir($sop_sub_entry_upload['upload_path']));
                        @mkdir($sop_sub_entry_upload['upload_path'], 0777);

                        $task_file_upload_temp = $this->config->item('task_file_upload_temp');

                        foreach ($val->files as $key2=>$val2) {
                            rename($task_file_upload_temp['upload_path'].$val2, $sop_sub_entry_upload['upload_path'].$val2);
                            $img_data['sop_sub_entry_id'] = $sop_sub_entry_id;
                            $img_data['img_name'] = $val2;
                            $this->bms_sop_model->set_sop_sub_entry_image_name ($img_data);
                        }
                    }

                } // end of each sub sop
            } // end of sop
            $data_r['Data'] = array('message'=>'Routine Task Entry Submitted Successfully!');
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();
            echo json_encode(array($data_r));

        } // end of inputs
        else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Routine Task Entry Submision error!'));
            echo json_encode($data_r);
        }

    } // end of function

    function getDashboard () {

        $inputs = json_decode(file_get_contents('php://input'));
        //$task_id = isset($inputs->taskID) && trim($inputs->taskID) != '' ? trim ($inputs->taskID) : '';
        //$staff_id = isset($inputs->staff_idID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $unit_id = isset($inputs->unit_id) && trim($inputs->unit_id) != '' ? trim ($inputs->unit_id) : '';
        $user_type = isset($inputs->user_type) && trim($inputs->user_type) != '' ? trim ($inputs->user_type) : '';
        $desi_id = isset($inputs->desi_id) && trim($inputs->desi_id) != '' ? trim ($inputs->desi_id) : '';
        if($property_id && $user_type) {
            if($user_type == 'jmb'){
                $data['notice_board_array'] = $this->bms_masters_model->getResiNoticeBoard($inputs->property_id);
                $data['notice_board'] = '';
            } else {
                $data['notice_board_array'] = array();
                $data['notice_board'] = $this->bms_masters_model->getNoticeBoard();
            }

            if($user_type == 'owner' || $user_type == 'tenant'){
                $data['open_task_count'] = $this->bms_task_model->getMinorTaskCountForOwner($property_id,$unit_id);
                $data['closed_task_count'] = $this->bms_task_model->getMinorTaskCountForOwner($property_id,$unit_id,'C');
                $data['total_task_count'] = $data['open_task_count'] + $data['closed_task_count'];
                $data['prebooked_visitor_count'] = count($this->bms_masters_model->getPrebookVisitor($property_id,$unit_id));
                $data['frequent_visitor_count'] = count($this->bms_masters_model->getFrequentVisitor($property_id,$unit_id));
                $data['eBulletin_count'] = count($this->bms_masters_model->getResiNoticeBoard($inputs->property_id));
            }else{
                $data['open_task_count'] = $this->bms_task_model->getMinorTaskCount($property_id);
                $data['closed_task_count'] = $this->bms_task_model->getMinorTaskCount($property_id,'C');
                $data['total_task_count'] = $data['open_task_count'] + $data['closed_task_count'];
                $data['prebooked_visitor_count'] = 0;
                $data['frequent_visitor_count'] = 0;
                $data['eBulletin_count'] = 0;
            }
            // 'bms_task_jmb'=>$this->bms_task_model->get_task_with_num_rows_jmb ($property_id,$task_status,$offset,$rows,$task_id,$search_txt,$sort_by,$unit_id)

            // $data['chart_data'] = '';
            // for($i =1; $i <=5 ; $i++) {
            //     $data['chart_data'] .= $data['chart_data'] != '' ? ',' : '';
            //     $data['chart_data'] .= $this->bms_task_model->getOverDueTaskForDaysInter($property_id, $i);
            // }
            if($user_type == 'jmb' || ($user_type == 'staff' && in_array($desi_id,$this->config->item('prop_doc_download_desi_id')))) {

                $collection_type = $this->config->item('collec_type');
                $start_date = $end_date = date('Y-m-d');
                foreach ($collection_type as $key=>$val) {
                    $data['today_collec'][$key] = $this->bms_task_model->getCollection($property_id, $val,$start_date,$end_date);
                }

                $start_date = date('Y-m-01');
                //$till_date_collec_sum = 0;
                foreach ($collection_type as $key=>$val) {
                    $data['till_collec'][$key] = $this->bms_task_model->getCollection($property_id, $val,$start_date,$end_date);
                    //$till_date_collec_sum += $data['till_collec'][$key];
                }

                $data['monthly_collec'] = $this->bms_masters_model->getPropertyMonthlyCollec($property_id);

                $data['collec_percentage'] = !empty($data['till_collec']['sc_sf_collec']) && $data['till_collec']['sc_sf_collec'] > 0 && !empty($data['monthly_collec']) && $data['monthly_collec'] > 0 ? round((($data['till_collec']['sc_sf_collec'] * 100) / $data['monthly_collec']),2) : 0;
            }
            $data_r['Data'] = array('bms_dashboard'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();
            echo json_encode(array($data_r));

        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));
            echo json_encode($data_r);
        }
        //echo "<pre>";print_r($data);echo "</pre>";exit;
    }

    function get_resident_dashboard () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(!empty($inputs->property_id)) {
            $data['notice_board'] = $this->bms_masters_model->getResiNoticeBoard($inputs->property_id);
            $data_r['Data'] = array('bms_resi_dashboard'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();
            echo json_encode(array($data_r));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Mandatory fields missing!'));
            echo json_encode($data_out);
        }
    }

    function change_password () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(!empty($inputs->user_type) && !empty($inputs->email) && !empty($inputs->old_password) &&  !empty($inputs->new_password)) {
            if($inputs->user_type == 'staff') {
                $result = $this->bms_masters_model->auth($inputs->email,$inputs->old_password);
                if(count($result) > 0) {
                    $result = $this->bms_masters_model->update_pass($inputs->email,$inputs->new_password);
                    $data_r['Data'] = array('message'=>array('Password Changed Successfully!'));
                    $data_r['Status'] = true;
                    $data_r['ErrorLog'] = array();
                    echo json_encode(array($data_r));
                } else {
                    $data_r['Data'] = array('message'=>array('Old Password Wrong!'));
                    $data_r['Status'] = true;
                    $data_r['ErrorLog'] = array();
                    echo json_encode(array($data_r));
                }
            } else if($inputs->user_type == 'owner' || $inputs->user_type == 'jmb') {
                $result = $this->bms_masters_model->auth_owner($inputs->email,$inputs->old_password);
                if(count($result) > 0) {
                    $result = $this->bms_masters_model->update_pass_jmb($inputs->email,$inputs->new_password);
                    $data_r['Data'] = array('message'=>array('Password Changed Successfully!'));
                    $data_r['Status'] = true;
                    $data_r['ErrorLog'] = array();
                    echo json_encode(array($data_r));
                } else {
                    $data_r['Data'] = array('message'=>array('Old Password Wrong!'));
                    $data_r['Status'] = true;
                    $data_r['ErrorLog'] = array();
                    echo json_encode(array($data_r));
                }
            } else if($inputs->user_type == 'tenant') {
                $result = $this->bms_masters_model->auth_resident($inputs->email,$inputs->old_password);
                if(count($result) > 0) {
                    $result = $this->bms_masters_model->update_pass_tenant($inputs->email,$inputs->new_password);
                    $data_r['Data'] = array('message'=>array('Password Changed Successfully!'));
                    $data_r['Status'] = true;
                    $data_r['ErrorLog'] = array();
                    echo json_encode(array($data_r));
                } else {

                    $result = $this->bms_masters_model->auth_vms_ma_users($inputs->email,$inputs->old_password);
                    if(count($result) > 0) {
                        $result = $this->bms_masters_model->update_pass_vms_ma_user($inputs->email,$inputs->new_password);
                        $data_r['Data'] = array('message'=>array('Password Changed Successfully!'));
                        $data_r['Status'] = true;
                        $data_r['ErrorLog'] = array();
                        echo json_encode(array($data_r));
                    } else {
                        $data_r['Data'] = array('message'=>array('Old Password is Wrong!'));
                        $data_r['Status'] = true;
                        $data_r['ErrorLog'] = array();
                        echo json_encode(array($data_r));
                    }


                }
            }
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Mandatory fields missing!'));
            echo json_encode($data_out);
        }
    }

    function set_logout () {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_info['push_token'] = $insert_data['push_token'] = '';
        $tbl_name = '';
        switch ($inputs->user_type) {
            case 'staff':
                $tbl_name = 'bms_staff';
                $update_id['email_addr'] = $inputs->email;
                break;
            case 'jmb':
            case 'owner':
                $tbl_name = 'bms_property_units';
                $update_id['email_addr'] = $inputs->email;
                break;
            case 'tenant':
                $tbl_name = 'bms_property_unit_tenants';
                $update_id['email_addr'] = $inputs->email;
                break;
        }
        $this->bms_masters_model->set_push_token ($tbl_name,$insert_data,$update_id);
        $data_out['Data'] = array('message'=>'Push token cleared Successfully!');
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        echo json_encode(array($data_out));
    }


    /*function getChatList () {

        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $last_list_sync = isset($inputs->last_list_sync) && trim($inputs->last_list_sync) != '' ? trim ($inputs->last_list_sync) : '';
        $staff_id = isset($inputs->staff_id) && trim($inputs->staff_id) != '' ? trim ($inputs->staff_id) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($user_type) {

            $data['chat_list'] = $this->bms_masters_model->getChatList($user_type,$last_list_sync,$staff_id,$property_id);

            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_synced'] =$date->format("Y-m-d H:i:s.u");

            $data_r['Data'] = array('bms_chat_list'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();
            echo json_encode(array($data_r));

        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));
            echo json_encode($data_r);
        }

    }

    function getChatMessages () {

        $inputs = json_decode(file_get_contents('php://input'));
        $last_sync = isset($inputs->last_sync) && trim($inputs->last_sync) != '' ? trim ($inputs->last_sync) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($property_id) {

            $data['chat_messages'] = $this->bms_masters_model->getChatMessages($property_id,$last_sync);

            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_synced'] =$date->format("Y-m-d H:i:s.u");

            $data_r['Data'] = array('bms_chat_list'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();
            echo json_encode(array($data_r));

        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));
            echo json_encode($data_r);
        }

    }

    function setChat () {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = isset($inputs->user_id) && trim($inputs->user_id) != '' ? trim ($inputs->user_id) : '';
        //$last_sync = isset($inputs->last_sync) && trim($inputs->last_sync) != '' ? trim ($inputs->last_sync) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $chat_text = isset($inputs->chat_text) ? trim($inputs->chat_text) : '';

        if($user_type && $property_id && $user_id) {
            $data['user_type'] = $user_type == 'jmb' ? 2 : 1;
            $data['property_id'] = $property_id;
            $data['user_id'] = $user_id;
            $data['chat_txt'] = $chat_text;
            $data['file_name'] = isset($inputs->file) ? $inputs->file : '';

            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_date'] =$date->format("Y-m-d H:i:s.u");
            $chat_id = $this->bms_masters_model->set_chat($data);
            if(!empty($inputs->file)){

                $chat_file_upload = $this->config->item('chat_file_upload');
                $chat_file_upload['upload_path'] = $chat_file_upload['upload_path'].$property_id.'/';
                if(!is_dir($chat_file_upload['upload_path']));
                    @mkdir($chat_file_upload['upload_path'], 0777);

                $task_file_upload_temp = $this->config->item('task_file_upload_temp');

                rename($task_file_upload_temp['upload_path'].$inputs->file, $chat_file_upload['upload_path'].$inputs->file);

            }

            $data_out['Data'] = array('message'=>'Chat Submitted Successfully!');
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Mandatory fields missing!'));
            echo json_encode($data_out);
        }
    }*/

    /*  VMS related start here   */
    /*  VMS related start here   */
    /*  VMS related start here   */
    /*  VMS related start here   */
    public function addPrebookVisitor() {
        $data = array();
        $inputs = json_decode(file_get_contents('php://input'));
        if (isset($inputs->visitType) && isset($inputs->vehicleNo)) {
            $data['property_id'] = $inputs->propertyID;
            $data['unit_id'] = $inputs->unitID;
            $data['vehicle_no'] = $inputs->vehicleNo;
            $data['visit_type'] = $inputs->visitType;
            $data['booking_date'] = date("Y-m-d 00:00:00");
            $data['booking_time'] = date("H:i:s");
            $data['mobile_no'] = $inputs->mobileNo;
            $data['flag'] = "1";
            $data['status'] = "PBNEW";
            $data['tstamp'] = date("Y-m-d H:i:s");

            $result = '';
            foreach ($data['vehicle_no'] as $key => $value) {
                $data['vehicle_no'] = preg_replace('/\s+/', '', $value);
                $result = $this->bms_masters_model->insertPrebookVisitor($data);
            }

            if ( $result['result'] == 1 ) {
                $data_out['Data'] = array('message' => 'Added Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array(array('message' => 'Failed to add Prebook Visitor!'));
            }
            echo json_encode(array($data_out));
        }
    }

    public function prebookvisitor ( $id = 0 ) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];
        //Switch statement
        switch ($requestType) {
            case 'GET':
                if (!empty($id)) {
                    $output = $this->bms_masters_model->getPrebookVisitorById($id);
                    echo !empty($output) ? json_encode($output):json_encode("No record found");
                } else {
                    $inputs = json_decode(file_get_contents('php://input'));
                    if (isset($inputs->property_id) && isset($inputs->unit_id)) {
                        $output = $this->bms_masters_model->getPrebookVisitorByProperty($inputs->property_id, $inputs->unit_id);
                        echo !empty($output) ? json_encode($output) : json_encode("No record found");
                    } else {
                        echo json_encode("Please provide property Id and Unit Id");
                    }
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if (isset($inputs->property_id))
                    $date['property_id'] = $inputs->property_id;
                if (isset($inputs->unit_id))
                    $date['unit_id'] = $inputs->unit_id;
                if (isset($inputs->vehicle_no))
                    $date['vehicle_no'] = $inputs->vehicle_no;
                if (isset($inputs->visit_type))
                    $date['visit_type'] = $inputs->visit_type;
                if (isset($inputs->booking_date))
                    $date['booking_date'] = $inputs->booking_date;
                if (isset($inputs->mobile_no))
                    $date['mobile_no'] = $inputs->mobile_no;
                if (isset($inputs->flag))
                    $date['flag'] = $inputs->flag;
                if (isset($inputs->status))
                    $date['status'] = $inputs->status;
                if (isset($inputs->tstamp))
                    $date['tstamp'] = $inputs->tstamp;
                $output = $this->bms_masters_model->updatePrebookVisitor($id, $date);
                echo json_encode($output);
                break;
            case 'DELETE':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($inputs->prebook_visitor_id) ) {
                    $output = $this->bms_masters_model->deletePrebookVisitor($inputs->prebook_visitor_id);
                    if ( $output == 1 ) {
                        $data_out['Data'] = array( 'message' => 'Prebook Visitor Deleted Successfully!' );
                        $data_out['Status'] = true;
                        $data_out['ErrorLog'] = array();
                        echo json_encode(array ($data_out));
                    } else {
                        $data_out['Data'] = array();
                        $data_out['Status'] = false;
                        $data_out['ErrorLog'] = array('message' => 'Can not delete Prebook Visitor');
                        echo json_encode(array ($data_out));
                    }
                } else {
                    $data_out['Data'] = array();
                    $data_out['Status'] = false;
                    $data_out['ErrorLog'] = array('message' => 'prebook_visitor_id Missing!');
                    echo json_encode(array ($data_out));
                }
                break;
            default:
                //request type that isn't being handled.
                break;
        }
    }

    public function getFrequentVisitor() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getFrequentVisitor($inputs->propertyID,$inputs->unitID);
            $data_out['Data'] = array('frequent_visitor_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function addFrequentVisitor() {
        $data = array ();
        $dataMaster = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->vehicle_no) && isset($inputs->verify_req)) {
            $data['property_id'] = $inputs->propertyID;
            $data['unit_id'] = $inputs->unitID;
            $data['vehicle_no'] = preg_replace('/\s+/', '', $inputs->vehicle_no);
            $data['mobile_no'] = '123456';
            $data['verify_req'] = $inputs->verify_req;
            $data['reg_date'] = date("Y-m-d H:i:s");
            $data['flag'] = "1";
            $data['status'] = "PBNEW";
            $data['tstamp'] = date("Y-m-d H:i:s");
            $result =  $this->bms_masters_model->insertFrequentVisitor($data);

            if ( $result['result'] == 1 ) {
                $data_out['Data'] = array('message' => 'Added Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array(array('message' => 'Failed to add Prebook Visitor!'));
            }
            echo json_encode(array($data_out));
        }
    }

    public function deleteFrequentVisitor() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->deleteVehicle)) {
            $result =  $this->bms_masters_model->deleteFrequentVisitor($inputs->deleteVehicle);

            if( $result == 1  ) {
                $data_out['Data'] = array('message'=>'Deleted Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array(array('message'=>'Failed to delete Frequent Visitor!'));
            }

            echo json_encode(array($data_out));
        }
    }

    public function frequentvisitor ( $id = 0 ) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];

        //Switch statement
        switch ($requestType) {
            case 'GET':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->getFrequentVisitorById($id);
                    echo json_encode($output);
                } else {
                    $inputs = json_decode(file_get_contents('php://input'));
                    if ( isset($inputs->property_id) && isset($inputs->unit_id) ) {
                        $output = $this->bms_masters_model->getFrequentVisitorByProperty($inputs->property_id, $inputs->unit_id);
                        echo ( !empty($output) )? json_encode($output):json_encode("No record found");
                    } else {
                        echo json_encode("Please provide property_id and unit_id");
                    }
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id) ) {
                    if ( isset($inputs->property_id) )
                        $date['property_id'] = $inputs->property_id;
                    if ( isset($inputs->unit_id) )
                        $date['unit_id'] = $inputs->unit_id;
                    if ( isset($inputs->vehicle_no) )
                        $date['vehicle_no'] = $inputs->vehicle_no;
                    if ( isset($inputs->mobile_no) )
                        $date['mobile_no'] = $inputs->mobile_no;
                    if ( isset($inputs->verify_req) )
                        $date['verify_req'] = $inputs->verify_req;
                    if ( isset($inputs->reg_date) )
                        $date['reg_date'] = $inputs->reg_date;
                    if ( isset($inputs->flag) )
                        $date['flag'] = $inputs->flag;
                    if ( isset($inputs->status) )
                        $date['status'] = $inputs->status;
                    if ( isset($inputs->tstamp) )
                        $date['tstamp'] = $inputs->tstamp;
                    $output = $this->bms_masters_model->updateFrequentVisitor($id, $date);
                    echo json_encode($output);
                }
                break;
            case 'DELETE':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->deleteFrequentVisitorById($id);
                    echo json_encode($output);
                }
                break;
            default:
                //request type that isn't being handled.
                break;
        }
    }

    public function addNoteToGuard() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $date = $inputs->start;
        $start = date('Y-m-d H:i:s', strtotime($date));
        $date = $inputs->end;
        $end = date('Y-m-d H:i:s', strtotime($date));

        if (isset($inputs->propertyID) && isset($inputs->unitID) && isset($inputs->eventType) && isset($inputs->start) && isset($inputs->end)) {
            $data['property_id'] = $inputs->propertyID;
            $data['unit_id'] = $inputs->unitID;
            $data['event_type'] = $inputs->eventType;
            $data['start'] = $start;
            $data['end'] = $end;
            $data['mobile_no'] = $inputs->mobileNo;
            $data['notes'] = $inputs->notes;
            $data['flag'] = "1";
            $data['status'] = "PBNEW";
            $data['tstamp'] = date("Y-m-d H:i:s");

            $result =  $this->bms_masters_model->insertNoteToGuard($data);

            if ( $result['result'] == 1 ) {
                $data_out['Data'] = array('message'=>'Added Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array(array('message'=>'Failed to add Note to Guard!'));
            }

            echo json_encode(array($data_out));
        }
    }

    public function notetoguard ( $id = 0 ) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];
        //Switch statement
        switch ($requestType) {
            case 'GET':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->getNoteToGuardById($id);
                    echo json_encode($output);
                } else {
                    $inputs = json_decode(file_get_contents('php://input'));
                    if ( isset($inputs->property_id) && isset($inputs->unit_id) ) {
                        $output = $this->bms_masters_model->getNoteToGuard($inputs->property_id, $inputs->unit_id);
                        echo json_encode($output);
                    } else {
                        echo json_encode("Please provide property_id and unit_id");
                    }
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id) ) {
                    if ( isset($inputs->property_id) )
                        $date['property_id'] = $inputs->property_id;
                    if ( isset($inputs->unit_id) )
                        $date['unit_id'] = $inputs->unit_id;
                    if ( isset($inputs->event_type) )
                        $date['event_type'] = $inputs->event_type;
                    if ( isset($inputs->start) )
                        $date['start'] = $inputs->start;
                    if ( isset($inputs->end) )
                        $date['end'] = $inputs->end;
                    if ( isset($inputs->mobile_no) )
                        $date['mobile_no'] = $inputs->mobile_no;
                    if ( isset($inputs->notes) )
                        $date['notes'] = $inputs->notes;
                    if ( isset($inputs->flag) )
                        $date['flag'] = $inputs->flag;
                    if ( isset($inputs->status) )
                        $date['status'] = $inputs->status;
                    if ( isset($inputs->tstamp) )
                        $date['tstamp'] = $inputs->tstamp;
                    $output = $this->bms_masters_model->updateNoteToGuard($id, $date);
                    echo json_encode($output);
                }
                break;
            case 'DELETE':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->deleteNoteToGuard($id);
                    echo json_encode($output);
                }
                break;
            default:
                //request type that isn't being handled.
                break;
        }
    }

    public function addPanicAlert() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if (isset($inputs->propertyID) && isset($inputs->unitID) && isset($inputs->mobileNo)) {
            $data['property_id'] = $inputs->propertyID;
            $data['unit_id'] = $inputs->unitID;
            $data['mobile_no'] = $inputs->mobileNo;
            $data['received'] = date("Y-m-d H:i:s");
            $data['alert_type'] = 0;
            $data['notes'] = "PB-PANIC";
            $data['flag'] = "1";
            $data['status'] = "PBNEW";
            $data['tstamp'] = date("Y-m-d H:i:s");

            $result =  $this->bms_masters_model->insertPanicAlert($data);

            if ( $result['result'] == 1 ) {
                $data_out['Data'] = array('message'=>'Added Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Failed to add Panic!');
            }
            echo json_encode(array($data_out));
        }
    }

    public function panicalert ( $id = 0 ) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];

        //Switch statement
        switch ($requestType) {
            case 'GET':
                if (!empty($id)) {
                    $output = $this->bms_masters_model->getPanicAlertById($id);
                    echo json_encode($output);
                } else {
                    $inputs = json_decode(file_get_contents('php://input'));
                    if (isset($inputs->property_id) && isset($inputs->unit_id)) {
                        $output = $this->bms_masters_model->getPanicAlert($inputs->property_id, $inputs->unit_id);
                        echo json_encode($output);
                    } else {
                        echo json_encode("Please provide property_id and unit_id");
                    }
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if (!empty($id) && isset($inputs->property_id) && isset($inputs->unit_id)) {
                    if (isset($inputs->property_id))
                        $date['property_id'] = $inputs->property_id;
                    if (isset($inputs->unit_id))
                        $date['unit_id'] = $inputs->unit_id;
                    if (isset($inputs->mobile_no))
                        $date['mobile_no'] = $inputs->mobile_no;
                    if (isset($inputs->received))
                        $date['received'] = $inputs->received;
                    if (isset($inputs->response))
                        $date['response'] = $inputs->response;
                    if (isset($inputs->alert_type))
                        $date['alert_type'] = $inputs->alert_type;
                    if (isset($inputs->notes))
                        $date['notes'] = $inputs->notes;
                    if (isset($inputs->flag))
                        $date['flag'] = $inputs->flag;
                    if (isset($inputs->status))
                        $date['status'] = $inputs->status;
                    if (isset($inputs->tstamp))
                        $date['tstamp'] = $inputs->tstamp;
                    $output = $this->bms_masters_model->updatePanicAlert($id, $date);
                    echo json_encode($output);
                }
                break;
            case 'DELETE':
                if (!empty($id)) {
                    $output = $this->bms_masters_model->deletePanicAlert($id);
                    echo json_encode($output);
                }
                break;
            default:
                //request type that isn't being handled.
                break;
        }
    }

    public function getVisitorList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getVisitorList($inputs->propertyID,$inputs->unitID);
            $data_out['Data'] = array('visitor_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function getAllVisitorList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getAllVisitorList($inputs->propertyID,$inputs->unitID);
            $data_out['Data'] = array('visitor_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        }
    }

    public function getPrebookVisitorList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getPrebookVisitorList($inputs->propertyID,$inputs->unitID);
            $data_out['Data'] = array('visitor_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        }
    }

    public function addVisitor () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if( isset($inputs->propertyID) && isset($inputs->unitID) && isset($inputs->mobileNo) ) {
            $data['property_id'] = $inputs->propertyID;
            $data['unit_id'] = $inputs->unitID;
            $data['mobile_no'] = $inputs->mobileNo;
            $data['received'] = date("Y-m-d H:i:s");
            $data['response'] = date("Y-m-d H:i:s");
            $data['alert_type'] = 0;
            $data['notes'] = "PB-PANIC";

            $result =  $this->bms_masters_model->insertPanicAlert($data);

            if ( $result['result'] == 1 ) {
                $response = $this->addPanicAlertToVCC($result['insert_id'], $data);
                // $this->addVisitorMaster ($dataMaster);
                // $this->addVisitorDetails($dataDetail);
                if ($response == 0) {
                    for ($i = 1; $i < 3; $i++) {
                        $response = $this->addPanicAlertToVCC($result['insert_id'], $data);
                        if ($response == 1) {
                            $vcc_inserted = 1;
                            break;
                        }
                    }
                } elseif ($response == 1) {
                    $vcc_inserted = 1;
                }
            }

            if ( $result['result'] == 1 && $vcc_inserted = 1) {
                $data_out['Data'] = array('message'=>'Added Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Failed to add Panic!');
            }
            echo json_encode(array($data_out));
        }
    }

    public function visitor ($id = 0) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];
        //Switch statement
        switch ($requestType) {
            case 'POST':
                $data = array ();
                $inputs = json_decode(file_get_contents('php://input'));
                if ( isset($inputs->visitor_id) && isset($inputs->property_id) && isset($inputs->unit_id)  ) {
                    $data_visitor_master = array (
                        'visitor_id' => $inputs->visitor_id,
                        'property_id' => $inputs->property_id,
                        'unit_id' => $inputs->unit_id,
                        'visitor_name' => $inputs->visitor_name,
                        'gender' => $inputs->gender,
                        'visitor_addr' => $inputs->visitor_addr,
                        'visitor_img' => $inputs->visitor_img,
                        'ic_no' => $inputs->ic_no,
                        'reg_date' => $inputs->reg_date,
                        'flag' => $inputs->flag,
                        'status' => $inputs->status,
                        'tstamp' => $inputs->tstamp
                    );
                    $result =  $this->bms_masters_model->insertVisitorMaster($data_visitor_master);
                    echo json_encode($result);
                } else {
                    echo json_encode(0);
                }
                break;
            case 'GET':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->getVisitorById($id);
                    echo json_encode($output);
                } else {
                    $inputs = json_decode(file_get_contents('php://input'));
                    if ( isset($inputs->property_id) && isset($inputs->unit_id) ) {
                        $output = $this->bms_masters_model->getUnitVisitorList($inputs->property_id, $inputs->unit_id);
                        echo json_encode($output);
                    } else {
                        echo json_encode("Please provide property_id and unit_id");
                    }
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id)  ) {
                    if ( isset($inputs->property_id) )
                        $date['property_id'] = $inputs->property_id;
                    if ( isset($inputs->unit_id) )
                        $date['unit_id'] = $inputs->unit_id;
                    if ( isset($inputs->visitor_name) )
                        $date['visitor_name'] = $inputs->visitor_name;
                    if ( isset($inputs->gender) )
                        $date['gender'] = $inputs->gender;
                    if ( isset($inputs->visitor_addr) )
                        $date['visitor_addr'] = $inputs->visitor_addr;
                    if ( isset($inputs->visitor_img) )
                        $date['visitor_img'] = $inputs->visitor_img;
                    if ( isset($inputs->ic_no) )
                        $date['ic_no'] = $inputs->ic_no;
                    if ( isset($inputs->reg_date) )
                        $date['reg_date'] = $inputs->reg_date;
                    if ( isset($inputs->flag) )
                        $date['flag'] = $inputs->flag;
                    if ( isset($inputs->status) )
                        $date['status'] = $inputs->status;
                    if ( isset($inputs->tstamp) )
                        $date['tstamp'] = $inputs->tstamp;
                    $output = $this->bms_masters_model->updateVisitor($id, $date);
                    echo json_encode($output);
                }
                break;
            case 'DELETE':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->deleteVisitor($id);
                    echo json_encode($output);
                }
                break;
            default:
                break;
        }
    }

    public function visitLog($id = 0) {
        //Get the request method.
        $requestType = $_SERVER['REQUEST_METHOD'];
        //Switch statement
        switch ($requestType) {
            case 'POST':
                $data = array ();
                $inputs = json_decode(file_get_contents('php://input'));
                if ( isset($inputs->visit_detail_id) && isset($inputs->visitor_id) && isset($inputs->vehicle_no) ) {
                    $date = array (
                        "visit_detail_id" => $inputs->visit_detail_id,
                        'visitor_id' => $inputs->visitor_id,
                        'vehicle_no' => $inputs->vehicle_no
                    );
                    if ( isset($inputs->col_make_model) )
                        $date['col_make_model'] = $inputs->col_make_model;
                    if ( isset($inputs->mobile_no) )
                        $date['mobile_no'] = $inputs->mobile_no;
                    if ( isset($inputs->visit_type) )
                        $date['visit_type'] = $inputs->visit_type;
                    if ( isset($inputs->visit_status) )
                        $date['visit_status'] = $inputs->visit_status;
                    if ( isset($inputs->visit_date) )
                        $date['visit_date'] = $inputs->visit_date;
                    if ( isset($inputs->group_size) )
                        $date['group_size'] = $inputs->group_size;
                    if ( isset($inputs->exit_date) )
                        $date['exit_date'] = $inputs->exit_date;
                    if ( isset($inputs->flag) )
                        $date['flag'] = $inputs->flag;
                    if ( isset($inputs->status) )
                        $date['status'] = $inputs->status;
                    if ( isset($inputs->tstamp) )
                        $date['tstamp'] = $inputs->tstamp;
                    if ( isset($inputs->notes) )
                        $date['notes'] = $inputs->notes;

                    $prebook_visitor_id = $this->bms_masters_model->deletePrebookVisitorByVehicleNo( $inputs->vehicle_no );
                    if ( !empty ($prebook_visitor_id) )
                        $this->deletePrebookVisitorFromVCC($prebook_visitor_id);
                    $result =  $this->bms_masters_model->insertVisitorDetails($date);
                    echo json_encode($result);
                } else {
                    echo json_encode(0);
                }
                break;
            case 'GET':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->getVisiLogById($id);
                    echo !empty($output)? json_encode($output):json_encode("No record found");
                } else if ( isset($inputs->visitor_id) ) {
                    $output = $this->bms_masters_model->getVisitLogByVisitor($inputs->visitor_id);
                    echo !empty($output)? json_encode($output):json_encode("No record found");
                } else {
                    echo json_encode("Please provide property_id, unit_id and visitor_id");
                }
                break;
            case 'PUT':
                $inputs = json_decode(file_get_contents('php://input'));
                if ( !empty($id) ) {
                    if ( isset($inputs->visitor_id) )
                        $date['visitor_id'] = $inputs->visitor_id;
                    if ( isset($inputs->vehicle_no) )
                        $date['vehicle_no'] = $inputs->vehicle_no;
                    if ( isset($inputs->col_make_model) )
                        $date['col_make_model'] = $inputs->col_make_model;
                    if ( isset($inputs->mobile_no) )
                        $date['mobile_no'] = $inputs->mobile_no;
                    if ( isset($inputs->visit_type) )
                        $date['visit_type'] = $inputs->visit_type;
                    if ( isset($inputs->visit_status) )
                        $date['visit_status'] = $inputs->visit_status;
                    if ( isset($inputs->visit_date) )
                        $date['visit_date'] = $inputs->visit_date;
                    if ( isset($inputs->group_size) )
                        $date['group_size'] = $inputs->group_size;
                    if ( isset($inputs->exit_date) )
                        $date['exit_date'] = $inputs->exit_date;
                    if ( isset($inputs->flag) )
                        $date['flag'] = $inputs->flag;
                    if ( isset($inputs->status) )
                        $date['status'] = $inputs->status;
                    if ( isset($inputs->tstamp) )
                        $date['tstamp'] = $inputs->tstamp;
                    if ( isset($inputs->notes) )
                        $date['notes'] = $inputs->notes;
                    $output = $this->bms_masters_model->updateVisitorDetails($id, $date);
                    echo json_encode($output);
                } else {
                    echo json_encode('Please provide Visitor Detail ID');
                }
                break;
            case 'DELETE':
                if ( !empty($id) ) {
                    $output = $this->bms_masters_model->deleteVisitLog($id);
                    echo json_encode($output);
                }
                break;
            default:
                //request type that isn't being handled.
                break;
        }
    }
    /*  VMS related ends here   */
    /*  VMS related ends here   */
    /*  VMS related ends here   */
    /*  VMS related ends here   */


    public function getInvoiceList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getInvoiceList($inputs->propertyID,$inputs->unitID);

            for ($i=0; $i < count($result); $i++) {
                $result[$i]['invoice_item'] = $this->bms_masters_model->getInvoiceDetail($result[$i]['bill_id']);
                $total_bal = 0;
                foreach ($result[$i]['invoice_item'] as $key => $value) {
                    $total_bal = $total_bal + $value['bal_amount'];
                }
                $result[$i]['invoice_bal'] = $total_bal;
            }

            $data_out['Data'] = array('invoice_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function getInvoiceDetail() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->billID)) {
            $result =  $this->bms_masters_model->getInvoiceDetail($inputs->billID);
            $data_out['Data'] = array('invoice_detail'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function getPaymentCharge() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID)) {
            $result =  $this->bms_masters_model->getPaymentChargeDetail($inputs->propertyID);
            $data_out['Data'] = array('charge_detail'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        }
    }

    public function submitPayment() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $merchantID = $inputs->merchantID;
        $paymentID = $inputs->paymentID;
        $transID = $inputs->transID;
        $transDesc = $inputs->transDesc;
        $refNo = $inputs->refNo;
        $keyIndex = $inputs->keyIndex;
        $signature = $inputs->signature;
        $bankCode = $inputs->bankCode;
        $requestDatetime = $inputs->requestDatetime;
        $responseDatetime = $inputs->responseDatetime;

        $result = $this->bms_masters_model->insertReceipt($merchantID,$paymentID,$transID,$transDesc,$refNo,$keyIndex,$signature,$bankCode,$requestDatetime,$responseDatetime);

        $data_out['Data'] = array('Result'=>$result);
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        return json_encode(array($data_out));

    }

    public function submitDirectPayment() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $refNo = $inputs->refNo;

        $data['Revpay_Merchant_ID'] = $inputs->merchantID;
        $data['Payment_ID'] = $inputs->paymentID;
        $data['Bank_Code'] = $inputs->bankCode;
        $data['Transaction_ID'] = $inputs->transID;
        $data['Amount'] = $inputs->amount;
        $data['Currency'] = $inputs->currency;
        $data['Transaction_Description'] = $inputs->transDesc;
        $data['Response_Code'] = $inputs->requestCode;
        $data['Settlement_Amount'] = $inputs->settlementAmount;
        $data['Settlement_Currency'] = $inputs->settlementCurrency;
        $data['Settlement_FX_Rate'] = $inputs->settlementFxRate;
        $data['Error_Description'] = $inputs->errorDesc;
        $data['Key_Index'] = $inputs->keyIndex;
        $data['Signature'] = $inputs->signature;
        $data['Request_Datetime'] = $inputs->requestDatetime;
        $data['Response_Datetime'] = $inputs->responseDatetime;

        $result = $this->bms_masters_model->submitDirectPayment($refNo, $data);

        $data_out['Data'] = $result;
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();

        return json_encode(array($data_out));

    }

    public function getReceiptList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getReceiptList($inputs->propertyID,$inputs->unitID);
            $data_out['Data'] = array('receipt_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function getReceiptDetail() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->receiptID)) {
            $result =  $this->bms_masters_model->getReceiptDetail($inputs->receiptID);
            $data_out['Data'] = array('receipt_detail'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }

    public function getVoting() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID)) {
            $now = date('Y-m-d');
            $result = $this->bms_agm_egm_model->check_available_voting($inputs->propertyID,$now);

            if(count($result) == 1){
                $data_out['Data'] = array('Data'=>$result);
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'There is no voting today.');
            }

            echo json_encode(array($data_out));

        }
    }

    public function getPinNo() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $result = $this->bms_agm_egm_model->check_eligible_voters($inputs->unitID,$inputs->agmID);
        if(count($result) == 1){
            $result = $this->bms_agm_egm_model->check_pin($inputs->pinNo,$inputs->agmID);
            if(count($result) == 1){
                $result[0]['agenda_resol'] = !empty ($result[0]['agenda_resol']) ? str_replace('<br />',"",$result[0]['agenda_resol']) : '';
                $data_out['Data'] = array('Data'=>$result);
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Invalid Pin No.');
            }
        }else{
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'You are no right to vote.');
        }

        echo json_encode(array($data_out));

    }

    public function getNomiee() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $data_out['Data'] = array();
        $data_out['Status'] = false;
        $data_out['ErrorLog'] = array('message'=>'No data available.');

        if($inputs->resoluType == 1){

            $result = $this->bms_agm_egm_model->getPCVote($inputs->agendaID);
            $data_out['Data'] = array('Data'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

        }else if($inputs->resoluType == 7){

            $result = $this->bms_agm_egm_model->getNoCommittee($inputs->agendaID);
            $data_out['Data'] = array('Data'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

        }else if($inputs->resoluType == 8){

            $result = $this->bms_agm_egm_model->getMCNomination($inputs->agendaID);
            $data_out['Data'] = array('Data'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

        }

        echo json_encode(array($data_out));

    }

    public function insertPCVote() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $result = $this->bms_agm_egm_model->check_pin_agenda($inputs->pinNo,$inputs->agendaID);

        if(count($result) == 1){

            $result = $this->bms_agm_egm_model->checkPCVote($inputs->agendaID,$inputs->unitID);

            if(count($result) > 0){

                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'You are unable to vote again.');

            }else{

                $data['agenda_id'] = $inputs->agendaID;
                $data['pc_id'] = $inputs->pcID;
                $data['unit_id'] = $inputs->unitID;
                $result =  $this->bms_agm_egm_model->insertPCVote($data);

                if(count($result) == 1){
                    $data_out['Data'] = array('message'=>'Voted Successfully!');
                    $data_out['Status'] = true;
                    $data_out['ErrorLog'] = array();
                }else{
                    $data_out['Data'] = array();
                    $data_out['Status'] = false;
                    $data_out['ErrorLog'] = array('message'=>'Failed to vote!');
                }

            }

        }else{
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Vote has been ended.');
        }

        echo json_encode(array($data_out));

    }

    public function insertResolVote() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $result = $this->bms_agm_egm_model->check_pin_agenda($inputs->pinNo,$inputs->agendaID);
        if(count($result) == 1){

            $result = $this->bms_agm_egm_model->checkResolVote($inputs->agendaID,$inputs->unitID);

            if(count($result) > 0){

                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'You are unable to vote again.');

            }else{

                $data['agenda_id'] = $inputs->agendaID;
                $data['vote_for'] = $inputs->voteFor;
                $data['unit_id'] = $inputs->unitID;
                $result =  $this->bms_agm_egm_model->insertResolVote($data);

                if(count($result) == 1){
                    $data_out['Data'] = array('message'=>'Voted Successfully!');
                    $data_out['Status'] = true;
                    $data_out['ErrorLog'] = array();
                }else{
                    $data_out['Data'] = array();
                    $data_out['Status'] = false;
                    $data_out['ErrorLog'] = array('message'=>'Failed to vote!');
                }

            }

        }else{
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Vote has been ended.');
        }

        echo json_encode(array($data_out));

    }

    public function insertNoCommVote() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $result = $this->bms_agm_egm_model->check_pin_agenda($inputs->pinNo,$inputs->agendaID);
        if(count($result) == 1){

            $result = $this->bms_agm_egm_model->checkNoCommVote($inputs->agendaID,$inputs->unitID);

            if(count($result) > 0){

                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'You are unable to vote again.');

            }else{

                $data['agenda_id'] = $inputs->agendaID;
                $data['propose_id'] = $inputs->proposeID;
                $data['unit_id'] = $inputs->unitID;
                $result =  $this->bms_agm_egm_model->insertNoCommVote($data);

                if(count($result) == 1){
                    $data_out['Data'] = array('message'=>'Voted Successfully!');
                    $data_out['Status'] = true;
                    $data_out['ErrorLog'] = array();
                }else{
                    $data_out['Data'] = array();
                    $data_out['Status'] = false;
                    $data_out['ErrorLog'] = array('message'=>'Failed to vote!');
                }

            }

        }else{
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Vote has been ended.');
        }

        echo json_encode(array($data_out));

    }

    public function insertMCVote() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        $result = $this->bms_agm_egm_model->check_pin_agenda($inputs->pinNo,$inputs->agendaID);
        if(count($result) == 1){

            $result = $this->bms_agm_egm_model->checkMCVote($inputs->agendaID,$inputs->unitID);

            if(count($result) > 0){

                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'You are unable to vote again.');

            }else{

                $data['agenda_id'] = $inputs->agendaID;
                $data['mc_nomin_id'] = $inputs->mcID;
                $data['unit_id'] = $inputs->unitID;

                foreach ($data['mc_nomin_id'] as $key => $value) {
                    $data['mc_nomin_id'] = $value;
                    $result =  $this->bms_agm_egm_model->insertMCVote($data);
                }

            }

            if(count($result) == 1){
                $data_out['Data'] = array('message'=>'Voted Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Failed to vote!');
            }

        }else{
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Vote has been ended.');
        }

        echo json_encode(array($data_out));

    }

    public function propertyDocAccessControl() {
        $inputs = json_decode(file_get_contents('php://input'));

        if (isset($inputs->desiID)) {

            $desiIDArr = $this->config->item('prop_doc_download_desi_id');
            $desiID = $inputs->desiID;

            if (in_array($desiID, $desiIDArr)){
                $data_out['Data'] = array('message'=>'You have access.');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'You have no access.');
            }

        }

        echo json_encode(array($data_out));

    }

    public function getSignature() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID)) {

            $propertyID = $inputs->propertyID;
            $unitID = $inputs->unitID;
            $merchantKey = $inputs->merchantKey;
            $merchantID = $inputs->merchantID;
            $amount = $inputs->amount;
            $checkedBill = $inputs->checkedBill;

            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $date_time =$date->format("YmdHisu");
            $refNo = $propertyID . '-' . $date_time;

            if(count($checkedBill) >= 1){
              $result =  $this->bms_masters_model->updateRefNo($refNo,$checkedBill);
            }else{
              $data = array();
              $data['property_id'] = $propertyID;
              $data['unit_id'] = $unitID;
              $data['Reference_Number'] = $refNo;
              $result =  $this->bms_masters_model->insertRefNo($data);
            }

            if(count($result) == 1){
                $signature_original = $merchantKey.$merchantID.$refNo.$amount.'MYR';
                $signature = hash('sha512', $signature_original);

                $data_out['Data'] = array('signature'=>$signature,'reference_number'=>$refNo);
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            }else{
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Failed to get signature.');
            }

            echo json_encode(array($data_out));
        }

    }

    public function getBankCode() {

        $bankCode = $this->config->item('pg_bank_code');

        $i = 0;
        foreach ($bankCode as $key => $value) {
            $result[$i]['label'] = $value;
            $result[$i]['value'] = $key;
            $i++;
        }

        $data_out['Data'] = array('bank_code'=>$result);
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();

        echo json_encode(array($data_out));

    }

    public function getResidentUserInfo () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        $result =  $this->bms_masters_model->getResidentUserInfo($inputs->propertyID,$inputs->unitID);
        $data_out['Data'] = array('visitor_list'=>$result);
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        echo json_encode(array($data_out));
    }

    function getButtonStatusByProperty () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $output = $this->bms_masters_model->getButtonStatusByProperty($inputs->property_id);
            $data_out['Data'] = array ('button_status'=>$output);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array ();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>"propertyID missing!");
            echo json_encode(array($data_out));
        }
    }


    /*  Facility booking related start here   */
    /*  Facility booking related start here   */
    /*  Facility booking related start here   */
    /*  Facility booking related start here   */
    function getBookingSlots () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty ($inputs->facility_id) && !empty ($inputs->booking_date) ) {
            $output = $this->bms_masters_model->getFacilityDetail( $inputs->facility_id );

            $disclaimer = !empty( $output['disclaimer'] ) ? $output['disclaimer']:'';

            $facility_picture_upload = $this->config->item('facility_picture_upload');
            $picture = !empty( $output['picture'] ) ? base_url() . $facility_picture_upload['upload_path'].$output['picture']:'';

            $slot_array = array ();
            if ( !empty ($output['number_of_slots']) ) {
                $slot_start_time = date('h:ia',strtotime($output['start_time']));
                for ($i = 1; $i < ($output['number_of_slots'] + 1); $i++ ) {
                    $secs = strtotime($output['booking_slot'])-strtotime("00:00:00");
                    $slot_end_time = date("h:ia",strtotime($slot_start_time)+$secs);
                    $slot_array[]['slot'] = $slot_start_time . ' - ' . $slot_end_time;
                    $slot_start_time = $slot_end_time;
                }
            }

            $result = $this->bms_masters_model->getSlotOccupied ($inputs->facility_id, $inputs->booking_date);
            foreach ($slot_array as $key => $val) {
                $slot_array[$key]['available'] = "Y";
                foreach ( $result as $key_slot => $val_slot ) {
                    if ( $val_slot['booking_slot'] == $val['slot'] )
                        $slot_array[$key]['available'] = "N";
                }
            }

            // echo json_encode($slot_array);
            $data_out['Data'] = array('booking_slots'=>$slot_array, 'disclaimer' => $disclaimer, 'picture' => $picture );
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Facility Id or Booking Date is Missing');
            echo json_encode($data_out);
        }
    }

    function addBookingSlot () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) && !empty($inputs->facility_id) &&  !empty($inputs->unit_id) && !empty($inputs->booking_date) && !empty($inputs->booking_slot) ) {
            $property_id = $inputs->property_id;
            $facility_id = $inputs->facility_id;
            $unit_id = $inputs->unit_id;
            $booking_date = $inputs->booking_date;
            $booking_slot = $inputs->booking_slot;
            $created_by = $inputs->created_by;

            $booking_slot_data = array (
                'property_id' => $property_id,
                'facility_id' => $facility_id,
                'unit_id' => $unit_id,
                'booking_date' => $booking_date,
                'booking_slot' => $booking_slot,
                'created_by' => $created_by
            );

            $result = $this->bms_masters_model->insertBookingSlot($booking_slot_data);

            if ( $result == 1 ) {
                $data_out['Data'] = array('message'=>'Slot Booked Successfully!');
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Failed to Add Booking Slot!');
            }
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Fail to Add Booking Slot! property_id OR facility_id OR unit_id OR booking_date OR booking_slot Missing');
            echo json_encode(array($data_out));
        }
    }

    function getBookedSlotsOfUnit () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->unit_id) ) {
            $output = $this->bms_masters_model->getBookingSlotListByFacility ( $inputs->unit_id );

            $data_out['Data'] = array('booked_slots'=>$output);;
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array ($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'Unit Id is Missing');
            echo json_encode($data_out);
        }
    }

    function cancelBookedSlot () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->facility_booking_id) ) {
            $result = $this->bms_masters_model->deleteFacilityBookingSlot ($inputs->facility_booking_id);
            if ( $result == 1 ) {
                $data_out['Data'] = array('message'=>'Slot Removed Successfully!');;
                $data_out['Status'] = true;
                $data_out['ErrorLog'] = array();
            } else {
                $data_out['Data'] = array();
                $data_out['Status'] = false;
                $data_out['ErrorLog'] = array('message'=>'Fail to Remove Booking Slot!');
            }
            echo json_encode(array ($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'facility_booking_id is Missing!');
            echo json_encode(array ($data_out));
        }
    }

    function getFacilitiesByProperty () {
        $data = array ();
        $output = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result = $this->bms_masters_model->getFacilitiesByProperty( $inputs->property_id );
            if ( !empty($result) ) {
                foreach ( $result as $key => $val ) {
                    $output[$key]['facility_id'] = $val['facility_id'];
                    $output[$key]['facility_name'] = $val['facility_name'];
                    $output[$key]['Id'] = $val['facility_id'];
                    $output[$key]['Value'] = $val['facility_id'];
                    $output[$key]['Name'] = $val['facility_name'];
                }
            }
            $data_out['Data'] = array( 'facilities' => $output );
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array ($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'property_id is Missing!');
            echo json_encode(array ($data_out));
        }
    }
    /*  Facility booking related end here   */
    /*  Facility booking related end here   */
    /*  Facility booking related end here   */
    /*  Facility booking related end here   */


    function getUnitDefaulterStatus () {
        $data = array ();
        $output_data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) && !empty($inputs->unit_id) ) {
            $result = $this->bms_masters_model->getUnitDefaulterStatus( $inputs->property_id, $inputs->unit_id );
            $output_data['is_defaulter'] = (int) $result['is_defaulter'];
            $data_out['Data'] = array( 'defaulter' => array ($result) );
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array ($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array('message'=>'property_id OR unit_id is Missing!');
            echo json_encode(array ($data_out));
        }
    }

    public function getDirectPaymentList() {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if(isset($inputs->propertyID) && isset($inputs->unitID)) {
            $result =  $this->bms_masters_model->getDirectPaymentList($inputs->propertyID,$inputs->unitID,$inputs->rows);
            $data_out['Data'] = array('direct_pymnt_list'=>$result);
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();

            echo json_encode(array($data_out));

        }
    }


    /*  Defect related start here   */
    /*  Defect related start here   */
    /*  Defect related start here   */
    /*  Defect related start here   */
    function getAllMyDefectss() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $defect_status = isset($inputs->defectStatus) && trim($inputs->defectStatus) != '' ? trim ($inputs->defectStatus) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $defect_id = isset($inputs->defect_id) && trim($inputs->defect_id) != '' ? trim ($inputs->defect_id) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 20;
        $search_txt = isset($inputs->search_text) && trim($inputs->search_text) != '' ? trim ($inputs->search_text) : '';
        $sort_by = isset($inputs->sort_by) && trim($inputs->sort_by) != '' ? trim ($inputs->sort_by) : 'due_date';

        $data['Data'] = array('bms_defect'=>$this->bms_task_model->get_defect ($staff_id,$offset,$rows,$property_id,$defect_status,$defect_id,$search_txt,$sort_by));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }

    function createDefect () {

        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && !empty($inputs)) {

            $defect['created_date'] = date('Y-m-d');
            $defect['property_id'] = $inputs->property_id;
            $defect['unit_id'] = $inputs->unit_id;
            $defect['defect_name'] = $inputs->defect_name;
            $defect['defect_location'] = $inputs->defect_location;
            $defect['defect_detail'] = $inputs->defect_detail;
            $defect['created_by'] = $inputs->created_by;
            $defect['created_by_type'] = $defect['created_by'] == '0' ? 'J' : ($defect['created_by'] == '-1' ? 'R' : 'S');

            $defect['defect_status'] = 'O';

            $insert_id = $this->bms_defect_model->defect_insert( $defect );

            if (!empty($inputs->files)) {

                $defect_file_upload = $this->config->item('defect_file_upload');
                $defect_file_upload['upload_path'] = $defect_file_upload['upload_path'].$insert_id.'/';
                if(!is_dir($defect_file_upload['upload_path']));
                @mkdir($defect_file_upload['upload_path'], 0777);

                $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');

                foreach ($inputs->files as $key=>$val) {
                    rename($defect_file_upload_temp['upload_path'].$val, $defect_file_upload['upload_path'].$val);
                    $img_data['defect_id'] = $insert_id;
                    $img_data['img_name'] = $val;
                    $this->bms_defect_model->defect_image_name_insert ($img_data);
                }
            }

            if (!empty($inputs->images)) {

                foreach ($inputs->images as $value) {

                    $image = base64_decode($value->data);
                    $image_name = md5(uniqid(rand(), true));
                    $filename = $image_name . '.' . 'jpg';

                    $defect_file_upload = $this->config->item('defect_file_upload');
                    $defect_file_upload['upload_path'] = $defect_file_upload['upload_path'].$insert_id.'/';
                    if(!is_dir($defect_file_upload['upload_path']));
                    @mkdir($defect_file_upload['upload_path'], 0777);

                    $path = $defect_file_upload['upload_path'];
                    file_put_contents($path.$filename, $image);

                    $filePath = $path.$filename;
                    $size_arr = getimagesize($filePath);
                    list($width_orig, $height_orig) = $size_arr;
                    $width = 1024;
                    $height = 768;
                    $ratio_orig = $width_orig / $height_orig;

                    if ($width / $height > $ratio_orig) {
                        $width = floor($height * $ratio_orig);
                    } else {
                        $height = floor($width / $ratio_orig);
                    }
                    $im = ImageCreateFromJpeg($filePath);
                    $tempimg = imagecreatetruecolor($width, $height);
                    imagecopyresampled($tempimg, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    ob_start();
                    imagejpeg($tempimg, $path.$filename, 90);
                    ob_end_clean();

                    $img_data['defect_id'] = $insert_id;
                    $img_data['img_name'] = $filename;
                    $this->bms_defect_model->defect_image_name_insert ($img_data);

                }

            }

            // for task log
            $this->bms_defect_model->set_defect_create_log ($insert_id, $defect['created_by']);

            if ($defect['created_by'] == '-1' && !empty($defect['unit_id'])) {
                $unit_info = $this->bms_masters_model->getUnitDetails ($defect['unit_id']);
                if ( !empty($unit_info) ) {
                    $inputs->resident_email_hidd = $unit_info->email_addr;
                    $inputs->resident_name_hidd = $unit_info->owner_name;
                    $inputs->resident_gender_hidd = $unit_info->gender;
                }
            }

            // Notification email to resident for task creation
            if ( !empty($inputs->resident_email_hidd) ) {
                $property_info = $this->bms_masters_model->getPropertyInfo ($defect['property_id']);

                $property_dev_info = $this->bms_defect_model->getDeveloperCredentials ($defect['property_id']);

                $to = $inputs->resident_email_hidd;
                $r_name = !empty($inputs->resident_name_hidd) ? $inputs->resident_name_hidd : '';

                $this->load->library('email');

                $subject = $inputs->defect_name .' | '. $property_info['property_name'];
                $message = '<p>To <b>';
                if(!empty($inputs->resident_gender_hidd)) {
                    $message .= $inputs->resident_gender_hidd == 'Male' ? 'Mr ' : ($inputs->resident_gender_hidd == 'Female' ? 'Ms ' : '');
                }
                $message .= $r_name;
                $message .= ',</b><br /><br />';

                $message .= 'A defect has been created as per below description. We keep this defect as our highest priority and looking forward to solve as soon as possible. We will notify you when the defect is solved for your kind reference.<br /><br />';

                $message .= 'Defect Id: '.str_pad($insert_id, 5, '0', STR_PAD_LEFT) .'<br />';
                $message .= 'Defect Name: '.$inputs->defect_name .'<br />';
                $message .= 'Defect Location : '.(!empty($inputs->defect_location) ? $inputs->defect_location : ' - ' ) .'<br />';
                $message .= 'Defect Detail : '.(!empty($inputs->defect_detail) ? $inputs->defect_detail : ' - ' ) .'<br />';

                $message .= 'Thank you,<br />Transpacc <br />'.$property_info['property_name'];

                $message .= '</p>';

                // Get full html:
                $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                    <title>' . html_escape($subject) . '</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, Verdana, Helvetica, sans-serif;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                ' . $message . '
                <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;">
                This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office.
                </div>
                </body>
                </html>';
                // Also, for getting full html you may use the following internal method:
                //$body = $this->email->full_html($subject, $message);

                $result = $this->email
                    ->from('noreply@propertybutler.my','Propertybutler')
                    //->reply_to($property_info['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                    ->to($to,$r_name)
                    ->bcc('naguwin@gmail.com','Nagarajan');
                    //->bcc('tanbenghwa@gmail.com')
                    //->bcc('email@transpacc.com.my','Transpacc Emails')
                    if ( !empty ( $property_dev_info ) ) {
                        foreach ( $property_dev_info as $key => $val ) {
                            $this->email->bcc( $val['email_addr'], 'Propertybutler' );
                        }
                    }
                    $this->email
                    ->subject($subject)
                    ->message($body)
                    ->send();
            }

            $data['Data'] = array('message'=>'Defect Created Successfully!');
            $data['Status'] = true;
            $data['ErrorLog'] = array();
            echo json_encode(array($data));
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Defect Creation error!'));
            echo json_encode($data);
        }
    }

    function defectImage() {

        $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');
        $target_dir = $defect_file_upload_temp['upload_path'];

        if(!file_exists($target_dir)){
            mkdir($target_dir, 0777,true);
        }

        $time = microtime(true);
        $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
        $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
        $defect_file_upload_temp['file_name'] = $date->format("YmdHisu");

        $target_dir = $target_dir."/".$defect_file_upload_temp['file_name'].".jpeg";

        $filePath = $_FILES['image']['tmp_name'];
        $size_arr = getimagesize($filePath);
        list($width_orig, $height_orig) = $size_arr;
        $width = 768;
        $height = 432;
        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = floor($height * $ratio_orig);
        } else {
            $height = floor($width / $ratio_orig);
        }
        $im = ImageCreateFromJpeg($filePath);
        $tempimg = imagecreatetruecolor($width, $height);
        imagecopyresampled($tempimg, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        ob_start();
        imagejpeg($tempimg, $filePath, 90);
        ob_end_clean();

        if(move_uploaded_file($filePath, $target_dir)) {
            echo json_encode($defect_file_upload_temp['file_name']);
        }else{
            echo json_encode(array('message'=>'Upload Failed!'));
        }
    }

    function getDefectDetails () {
        $inputs = json_decode(file_get_contents('php://input'));
        $defect_id = isset($inputs->defectID) && trim($inputs->defectID) != '' ? trim ($inputs->defectID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $user_type = isset($inputs->userType) && trim($inputs->userType) != '' ? trim ($inputs->userType) : '';



        if(isset($user_type) && ($user_type == 'jmb' || $user_type == 'owner' || $user_type == 'tenant')) {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details_jmb_mc ($defect_id,$property_id);
        } else {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details($defect_id,$staff_id);
        }

        if (!empty($data['defect_details'])) {
            if(empty($data['defect_details']->first_name)) {
                $data['defect_details']->first_name = !empty($data['defect_details']->created_by) && $data['defect_details']->created_by == '0' ? 'JMB / MC' : ($data['defect_details']->created_by == '-1' ? 'Resident' : '');
                $data['defect_details']->last_name = !empty($data['defect_details']->last_name) ? $data['defect_details']->last_name : '';
            }
        }

        if($data['defect_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['defect_details']->block_id);
        $data['defect_images'] = $this->bms_defect_model->get_defect_images($defect_id);
        $data['defect_forum_cnt'] = $this->bms_defect_model->getDefectForum($defect_id,'cnt');

        $data_out['Data'] = array('defect_data'=>array($data));
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        echo json_encode(array($data_out));
    }

    function setDefectStatus () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->defectID) && $inputs->defectID != '') {
            $defect_id = $inputs->defectID;
            if(isset($inputs->defect_update) && $inputs->defect_update == 'Closed') {
                $data['defect_status'] = 'C';
                $data['defect_close_remarks'] = isset($inputs->close_remarks) && trim($inputs->close_remarks) != '' ? trim ($inputs->close_remarks) : '';
            }
            $data['updated_by'] = $inputs->staff_id;
            $data['updated_date'] = date("Y-m-d");
            $this->bms_defect_model->set_defect_update_with_log($defect_id,$data,$inputs->staff_id);

            // Notification emil to resident for task close
            if(isset($inputs->defect_update) && $inputs->defect_update == 'Closed') {
                $unit_details = $this->bms_defect_model->get_defect_details_for_email($defect_id);

                if(!empty($unit_details) && !empty($unit_details['email_addr'])) {

                    $to = $unit_details['email_addr'];
                    $r_name = !empty($unit_details['owner_name']) ? $unit_details['owner_name'] : '';

                    $this->load->library('email');

                    $subject = $unit_details['defect_name'] .' | '. $unit_details['property_name'];
                    $message = '<p>To <b>';
                    if(!empty($unit_details['gender'])) {
                        $message .= $unit_details['gender'] == 'Male' ? 'Mr ' : ($unit_details['gender'] == 'Female' ? 'Ms ' : '');
                    }
                    $message .= $r_name;
                    $message .= ',</b><br /><br />';

                    $message .= 'We are pleased to inform you that your complaint has been resolved. Please refer to below defect details and remarks.<br /><br />';

                    $message .= '<b>Defect Id:</b> '.str_pad($unit_details['defect_id'], 5, '0', STR_PAD_LEFT) .'<br />';
                    $message .= '<b>Defect Name:</b> '.$unit_details['defect_name'] .'<br />';
                    $message .= '<b>Defect Location:</b> '.(!empty($unit_details['defect_location']) ? $unit_details['defect_location'] : ' - ' ) .'<br />';
                    $message .= '<b>Close Remarks:</b> '.(!empty($data['defect_close_remarks']) ? $data['defect_close_remarks'] : ' - ' ) .'<br /><br />';

                    $message .= 'We hope you are pleased with our service. Should you have any other comments pertaining to this defect, please do contact our management office. ';
                    $message .= 'We are here to serve you better.<br /><br />';
                    $message .= 'Thank you,<br />Transpacc <br />'.$unit_details['property_name'];

                    $message .= '</p>';

                    // Get full html:
                    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                        <title>' . html_escape($subject) . '</title>
                        <style type="text/css">
                            body {
                                font-family: Arial, Verdana, Helvetica, sans-serif;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                    ' . $message . '
                    <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;">
                This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office.
                </div>
                    </body>
                    </html>';
                    // Also, for getting full html you may use the following internal method:
                    //$body = $this->email->full_html($subject, $message);

                    $result = $this->email
                        ->from('noreply@propertybutler.my','Propertybutler')
                        //->reply_to($unit_details['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                        ->to($to,$r_name)
                        ->bcc('naguwin@gmail.com')
                        //->bcc('tanbenghwa@gmail.com')
                        //->bcc('email@transpacc.com.my','Transpacc Emails')
                        ->subject($subject)
                        ->message($body)
                        ->send();
                }
            }
            $data_out['Data'] = array('message'=>'Task status updated Successfully!');
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Task status update error!'));
            echo json_encode($data_out);
        }
    }

    function getDefectForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->defectID) {
            $data['Data'] = array('bms_defect_forum'=>$this->bms_defect_model->getDefectForum($inputs->defectID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Defect id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    function setDefectForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->defect_id) && $inputs->defect_id != '' && ((isset($inputs->chat_text) && $inputs->chat_text != '') || !empty($inputs->file))) {
            $defect_id = $inputs->defect_id;
            $chat_text = isset($inputs->chat_text) ? trim($inputs->chat_text) : '';
            $img_name = '';
            if(!empty($inputs->file)) {

                $defect_forum_upload = $this->config->item('defect_forum_upload');
                $defect_forum_upload['upload_path'] = $defect_forum_upload['upload_path'].$defect_id.'/';
                if(!is_dir($defect_forum_upload['upload_path']));
                @mkdir($defect_forum_upload['upload_path'], 0777);

                $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');

                rename($defect_file_upload_temp['upload_path'].$inputs->file, $defect_forum_upload['upload_path'].$inputs->file);
                $img_name = $inputs->file;

            }

            $this->bms_defect_model->set_defect_forum($defect_id,$chat_text,$img_name,$inputs->staff_id);

            $data['Data'] = array('bms_defect_forum'=>$this->bms_defect_model->getDefectForum($inputs->defect_id));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
            echo json_encode(array($data));
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Defect forum update error!'));
            echo json_encode($data_out);
        }
    }

    function getDefectLog () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->defectID) {
            $data['Data'] = array('bms_defect_log'=>$this->bms_defect_model->getDefectLog($inputs->defectID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Defect id is mandatory!'));
        }
        echo json_encode(array($data));
    }

    function getAllJmbDefects () {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $defect_status = isset($inputs->defectStatus) && trim($inputs->defectStatus) != '' ? trim ($inputs->defectStatus) : '';
        $defect_id = isset($inputs->defect_id) && trim($inputs->defect_id) != '' ? trim ($inputs->defect_id) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 10;
        $sort_by = isset($inputs->sort_by) && trim($inputs->sort_by) != '' ? trim ($inputs->sort_by) : 'asc';
        $search_txt = isset($inputs->search_text) && trim($inputs->search_text) != '' ? trim ($inputs->search_text) : '';
        $unit_id = isset($inputs->unit_id) && trim($inputs->unit_id) != '' ? trim ($inputs->unit_id) : '';

        $data['Data'] = array('bms_defect_jmb'=>$this->bms_defect_model->get_defect_with_num_rows_jmb ($property_id,$defect_status,$offset,$rows,$defect_id,$search_txt,$sort_by,$unit_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
    }
    /*  Defect related start here   */
    /*  Defect related start here   */
    /*  Defect related start here   */
    /*  Defect related start here   */

} // End of class
