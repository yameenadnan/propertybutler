<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_agm_egm_vote extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!in_array($this->uri->segment(2), array('login','login_action'))) {
            if((!isset($_SESSION['agm']['is_logged_in']) || $_SESSION['agm']['is_logged_in'] == false)) {
    	       redirect('index.php/bms_agm_egm_vote/login');	       
    	    }
        }
        //if(!in_array($this->uri->segment(2), array('get_jmb_mc_list','check_email')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_agm_egm_model');         
    }
    
    function ksa () {  echo true;  }
    
    function login () {
        $data['browser_title'] = 'Property Butler | AGM/EGM Login';
        $this->load->view('agm_egm/agm_voter_login_view',$data);
    }
    
    function login_action ()	{ 
        $data['browser_title'] = 'Property Butler | AGM/EGM | Login Process';
        
        if(isset($_POST) && isset($_POST['username']) && trim($_POST['username']) != '' && isset($_POST['pass']) && trim($_POST['pass']) != '') {
            
            
            $username = trim($this->input->post('username'));
            $pass = trim($this->input->post('pass'));
            $result = $this->bms_agm_egm_model->agm_auth($username,$pass);
            if(count($result) == 1 ) {
                
                $this->setCookies($username,$pass);
                //echo "<pre>";print_r($result);exit;
                
                $_SESSION['agm']['is_logged_in'] = true;
                $_SESSION['agm']['agm_attendance_id'] = $result[0]['agm_attendance_id']; 
                $_SESSION['agm']['username'] = $_SESSION['agm']['full_name'] = trim($result[0]['user_name']);
                $_SESSION['agm']['property_id'] = $result[0]['property_id'];
                $_SESSION['agm']['changed_pass'] = $result[0]['changed_pass']; 
                $_SESSION['agm']['user_type'] = 'resident';
                //$access_mod = $this->bms_masters_model->get_access_module($result[0]['staff_id']);
                //$_SESSION['bms']['access_mod'] = explode(',',$access_mod['module_id']);
                
                /*$_SESSION['bms']['genid'] = $gen_id;              
                $sqls = $this->db->query("insert into url_idvalidation  (user_email,gen_id) values ('$username','$gen_id')");
                $sqls = $this->db->query("update bms_staff  set ipaddress='$gen_id'  where email_addr='$username'");  */
                
                if($_SESSION['agm']['changed_pass'])
                    redirect('index.php/bms_agm_egm_vote/ready_vote');
                else 
                    redirect('index.php/bms_agm_egm_vote/agm_change_password');
            } else {
                //echo "<script>alert('Invalid Login!');</script>";
                redirect('index.php/bms_agm_egm_vote/login?login_err=invalid');//input_empty
            }   
                
        }      
    }
    
    function setCookies ($username,$pass) {
        if(isset($_POST['remember'])  && trim($_POST['remember']) == 'rememberme') {
            setcookie( "username", $username, strtotime( '+30 days' ) );
            setcookie( "password", $pass, strtotime( '+30 days' ) );
        } else {
            setcookie( "username", "", time()-3600);
            setcookie( "password", "", time()-3600);
        }
    }
    
    function agm_change_password () {
        $data['browser_title'] = 'Property Butler | Change Password';
        $this->load->view('agm_egm/agm_voter_cpass_view',$data);
    }
    
    function agm_check_password () {
        if(isset($_POST['current_pass']) && $_POST['current_pass'] != '' ) { 
            $pass = trim($_POST['current_pass']);
            $result = $this->bms_agm_egm_model->agm_check_pass($_SESSION['agm']['username'],$pass);
            
            if(count($result) == 1 ) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
    
    function agm_change_password_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        if(isset($_POST['current_pass']) && $_POST['current_pass'] != '' && isset($_POST['new_pass']) && $_POST['new_pass'] != '' ) {
            
            $pass = trim($_POST['new_pass']);           
            $this->bms_agm_egm_model->agm_update_pass($_SESSION['agm']['username'],$pass);
            $_SESSION['flash_msg'] = 'Password Changed successfully!';      
            
        }
        redirect ('index.php/bms_agm_egm_vote/logout');
    }    
    
    function ready_vote () {
                                
        $data['browser_title'] = 'Property Butler | AGM/EGM Voting';
        // Check is there any AGM/EGM on the particular day for his property
        //$user_info['property_id'], date('Y-m-d')
        $data['avail_vote'] = $this->bms_agm_egm_model->check_available_voting($_SESSION['agm']['property_id'],date('Y-m-d'));
        $_SESSION['agm']['agm_id'] = !empty($data['avail_vote']) ? $data['avail_vote'][0]['agm_id'] : '';
        //echo "<pre>";print_r($data['avail_vote']);echo "</pre>";
        $this->load->view('agm_egm/agm_voting_landing_view',$data);
    }
    
    function voting () {                
        $data['browser_title'] = 'Property Butler | AGM/EGM Voting';
        
        //$data['eligible_voter'] = $this->bms_agm_egm_model->check_eligible_voters($_SESSION['agm']['unit_id'],$_SESSION['agm']['agm_id']);    
        $this->load->view('agm_egm/agm_voting_pin_view',$data);
    }
    
    function start_voting () {
        
        $data['browser_title'] = 'Property Butler | AGM/EGM Voting';
        $pin = $this->input->post('agenda_pin');
        if(empty($pin)) {
            $_SESSION['error_msg'] = "Please wait for our announcement for the voting PIN Number!";
            redirect('index.php/bms_agm_egm_vote/voting');exit;
        }
        
        $data['agenda'] = $this->bms_agm_egm_model->get_agenda_by_pin ($pin);

        if(!empty($data['agenda'])) {
            if(isset($_SESSION['error_msg']))   unset($_SESSION['error_msg']);
            switch ($data['agenda']['resolu_type']) {
                case 1:
                    $check = $this->bms_agm_egm_model->checkPCVote ($data['agenda']['agm_agenda_id'],$_SESSION['agm']['agm_attendance_id']);
                    if(!empty($check)) {
                        $_SESSION['error_msg'] = "1";
                    } else {
                        // to pickup all nominee of specific agenda id
                        $data['nominee'] =  $this->bms_agm_egm_model->get_pc_nominees ($data['agenda']['agm_agenda_id']);
                    }
                    break;
                case 2:
                case 3:
                case 4:
                case 5:
                    $check = $this->bms_agm_egm_model->checkResolVote($data['agenda']['agm_agenda_id'],$_SESSION['agm']['agm_attendance_id']);
                    if(!empty($check)) {
                        $_SESSION['error_msg'] = "1";
                    }
                    break; 
                    
                case 7:
                    // check whether he casted his vote already or not
                    $check = $this->bms_agm_egm_model->checkNoCommVote($data['agenda']['agm_agenda_id'],$_SESSION['agm']['agm_attendance_id']);
                    if(!empty($check)) {
                        $_SESSION['error_msg'] = "1";
                    } else {
                        $data['items'] =  $this->bms_agm_egm_model->get_no_of_comm_nomination ($data['agenda']['agm_agenda_id']);
                    }
                    break; 
                case 8:
                    $check = $this->bms_agm_egm_model->checkMCVote($data['agenda']['agm_agenda_id'],$_SESSION['agm']['agm_attendance_id']);
                    if(!empty($check)) {
                        $_SESSION['error_msg'] = "1";
                    } else { 
                        $data['nominee'] =  $this->bms_agm_egm_model->get_mc_nominees ($data['agenda']['agm_agenda_id']);
                    }
                    break;           
                    
            }
            if(isset($_SESSION['error_msg'])) {
                $_SESSION['error_msg'] = "You have casted your vote already!";
                redirect('index.php/bms_agm_egm_vote/voting');exit;
            }
                
        } else {
            $_SESSION['error_msg'] = "Invalid PIN number!/Please wait for our announcement for the voting PIN Number!";
            redirect('index.php/bms_agm_egm_vote/voting');exit;
        }
        $data['pin'] = $pin;
        //echo "<pre>";print_r($data['agenda']);echo "</pre>";
        $this->load->view('agm_egm/agm_voting_view',$data);
        
    }
    
    function set_voting () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        
        if(isset($_SESSION['error_msg']))   unset($_SESSION['error_msg']);
        
        $resolu_type = $this->input->post('resolu_type');
        if(!empty($resolu_type)) {
            $data['agenda_id'] = $this->input->post('agenda_id');
            $data['agm_attendance_id'] = $this->input->post('agm_attendance_id');
            $cnt = $this->bms_agm_egm_model->check_agenda_vote_active ($data['agenda_id']);
            if(!empty($cnt)) {
                switch ($resolu_type) {
                    case 1:                    
                        $data['pc_id'] = $this->input->post('vote_pc'); 
                        $check = $this->bms_agm_egm_model->checkPCVote($data['agenda_id'],$data['agm_attendance_id']);
                        if(!empty($check)) {
                            $_SESSION['error_msg'] = "You have voted already! You cannot cast your vote 2nd time!";
                        } else {
                            $this->bms_agm_egm_model->setPcVote ($data);
                        }
                        
                        break;
                    case 2:
                    case 3:
                    case 4:
                    case 5:                    
                        $data['vote_for'] = $this->input->post('vote_for');
                        $check = $this->bms_agm_egm_model->checkResolVote($data['agenda_id'],$data['agm_attendance_id']);
                        if(!empty($check)) {
                            $_SESSION['error_msg'] = "You have voted already! You cannot cast your vote 2nd time!";
                        } else {                    
                            $this->bms_agm_egm_model->setVoteResol ($data);
                        }
                        break;
                    case 7:                    
                        $data['propose_id'] = $this->input->post('vote_no_of_comm');
                        $check = $this->bms_agm_egm_model->checkNoCommVote($data['agenda_id'],$data['agm_attendance_id']);
                        if(!empty($check)) {
                            $_SESSION['error_msg'] = "You have voted already! You cannot cast your vote 2nd time!";
                        } else {                    
                            $this->bms_agm_egm_model->setNoOfCommVote ($data);
                        }
                        break;                
                    case 8:                    
                        $check = $this->bms_agm_egm_model->checkMCVote($data['agenda_id'],$data['agm_attendance_id']);
                        if(!empty($check)) {
                            $_SESSION['error_msg'] = "You have voted already! You cannot cast your vote 2nd time!";
                        } else {
                            $vote_mc = $this->input->post('vote_mc');                    
                            foreach($vote_mc as $key=>$val) {
                                $data['mc_nomin_id'] = $val;
                                $this->bms_agm_egm_model->setMcVote ($data);
                            }
                        }             
                        break;
                }
            } else {
                $_SESSION['error_msg'] = "You cannot vote due to Voting closed already!";
            }
        }
        
        if(!isset($_SESSION['error_msg'])) $_SESSION['flash_msg'] = "You have casted your vote succesfully!";
        redirect('index.php/bms_agm_egm_vote/ready_vote');
    }   
    
    
    public function logout() {
		$data['browser_title'] = 'Property Butler | Logout Process';
        $data['page_header'] = '<i class="fa fa-dashboard"></i> Dashboard';
        unset($_SESSION['agm']);
        unset($_SESSION['bms_default_property']);
        redirect('index.php/bms_agm_egm_vote/login');
	}

    function vote_history () {
        $data['browser_title'] = 'Property Butler | AGM/EGM Voting History';
        // get agenda ids
        $data['agenda_resol'] = $this->bms_agm_egm_model->get_agenda_id_from_agm_attendance_id ( $_SESSION['agm']['agm_attendance_id'] );
        //echo "<pre>";print_r($data['avail_vote']);echo "</pre>";
        $this->load->view('agm_egm/vote_history_view',$data);
    }

    function display_history_result () {
        $data['browser_title'] = 'Property Butler | AGM/EGM Voting History';
        $agm_attendance_id = $this->input->get('agm_attendance_id');
        $agm_agenda_id = $this->input->get('agm_agenda_id');
        $resolu_type = $this->input->get('resolu_type');
        switch ( $resolu_type ) {
            case 1:
                $owner_name = $this->bms_agm_egm_model->get_resolu_type_1_result ( $agm_agenda_id, $agm_attendance_id );
                $agenda_resol = $this->bms_agm_egm_model->get_agenda_resol_from_agm_agenda_id ( $agm_agenda_id );
                $data['agenda'] = $owner_name;
                $data['agenda_resol'] = $agenda_resol;
                $data['agm_attendance_id'] = $agm_attendance_id;
                $data['agm_agenda_id'] = $agm_agenda_id;
                $data['resolu_type'] = $resolu_type;
                break;
            case 2:
            case 3:
            case 4:
            case 5:
                $owner_name = $this->bms_agm_egm_model->get_resolu_type_2_5_result ( $agm_agenda_id, $agm_attendance_id );
                $agenda_resol = $this->bms_agm_egm_model->get_agenda_resol_from_agm_agenda_id ( $agm_agenda_id );
                $data['agenda'] = $owner_name;
                $data['agenda_resol'] = $agenda_resol;
                $data['agm_attendance_id'] = $agm_attendance_id;
                $data['agm_agenda_id'] = $agm_agenda_id;
                $data['resolu_type'] = $resolu_type;
                break;
            case 7:
                // check whether he casted his vote already or not
                $owner_name = $this->bms_agm_egm_model->get_resolu_type_7_result ( $agm_agenda_id, $agm_attendance_id );
                $agenda_resol = $this->bms_agm_egm_model->get_agenda_resol_from_agm_agenda_id ( $agm_agenda_id );
                $data['agenda'] = $owner_name;
                $data['agenda_resol'] = $agenda_resol;
                $data['agm_attendance_id'] = $agm_attendance_id;
                $data['agm_agenda_id'] = $agm_agenda_id;
                $data['resolu_type'] = $resolu_type;
                break;
            case 8:
                $owner_name = $this->bms_agm_egm_model->get_resolu_type_8_result ( $agm_agenda_id, $agm_attendance_id );
                $agenda_resol = $this->bms_agm_egm_model->get_agenda_resol_from_agm_agenda_id ( $agm_agenda_id );
                $data['agenda'] = $owner_name;
                $data['agenda_resol'] = $agenda_resol;
                $data['agm_attendance_id'] = $agm_attendance_id;
                $data['agm_agenda_id'] = $agm_agenda_id;
                $data['resolu_type'] = $resolu_type;
                break;
        }
        $this->load->view('agm_egm/agm_display_history_result_view',$data);
    }

    
}