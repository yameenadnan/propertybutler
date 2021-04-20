<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_index extends CI_Controller {	
    
    function __construct () { 
        parent::__construct ();
        $this->load->model('bms_masters_model'); 
    }
    
    
    //new test
    
    function indexnew() {
        $this->load->library('user_agent');
        $data['browser'] = $this->agent->browser();
        $data['browser_version'] = $this->agent->version();
        $data['os'] = $this->agent->platform();
        $data['ip_address'] = $this->input->ip_address();
        $this->load->view('userdetail', $data);
    }
 
    public function login() {
	   if(isset($_SESSION['bms']['is_logged_in']) && $_SESSION['bms']['is_logged_in'] == true) {
	       //$this->user_access_log->user_access_log_insert(); // create user access log
	       redirect('index.php/bms_dashboard/index');
	   } else {
	       $data['browser_title'] = 'Property Butler';
           //$data['page_header'] = '<i class="fa fa-dashboard"></i> Dashboard';
           $this->load->view('login_view',$data);
	   }
	}

    function login_action ()    {
        $data['browser_title'] = 'Property Butler | BMS | Login Process';
        
        if(isset($_POST) && isset($_POST['username']) && trim($_POST['username']) != '' && isset($_POST['pass']) && trim($_POST['pass']) != '') {
            
            
            $username = trim($this->input->post('username'));
            $pass = trim($this->input->post('pass'));
            $result = $this->bms_masters_model->auth($username,$pass);
            
            
          
            //get user server detail
            
            /*$sys=  php_uname('s');
            $ee = $_SERVER['HTTP_USER_AGENT'];
            $user_os; 
            $mainurl =  $ee; 
            $ip = $_SERVER['REMOTE_ADDR'];
            $ipaddrss =  $ip; 
            $hostname =  gethostbyaddr($ip) ;
            
            function generate_random_password($length = 16) {
                $alphabets = range('A','Z');
                $numbers = range('0','9');
                $additional_characters = array('_','.');
                $final_array = array_merge($alphabets,$numbers,$additional_characters);
                $password = '';
                while($length--) {
                $key = array_rand($final_array);
                $password .= $final_array[$key];
                }
                
                return $password;
            }
            $create = "U".$api_no =   generate_random_password(16) ;
            $gen_id = $ipaddrss.$create;*/
            
            if(count($result) == 1 ) {
                
                $this->setCookies($username,$pass);
                //echo "<pre>";print_r($result);exit;
                
                $_SESSION['bms']['is_logged_in'] = true;
                $_SESSION['bms']['full_name'] = trim($result[0]['first_name']).' '.  trim($result[0]['last_name']);
                $_SESSION['bms']['first_name'] = isset($result[0]['first_name']) && $result[0]['first_name'] != '' ? $result[0]['first_name'] : '';                
                $_SESSION['bms']['email'] = $result[0]['email_addr']; 
                $_SESSION['bms']['staff_id'] = $result[0]['staff_id']; 
                $_SESSION['bms']['designation_id'] = $result[0]['designation_id']; 
                $_SESSION['bms']['user_type'] = 'staff';
                $access_mod = $this->bms_masters_model->get_access_module($result[0]['staff_id']);
                $_SESSION['bms']['access_mod'] = explode(',',$access_mod['module_id']);
                
                /*$_SESSION['bms']['genid'] = $gen_id;              
                $sqls = $this->db->query("insert into url_idvalidation  (user_email,gen_id) values ('$username','$gen_id')");
                $sqls = $this->db->query("update bms_staff  set ipaddress='$gen_id'  where email_addr='$username'");  */

                redirect(isset($_GET['return_url']) && $_GET['return_url'] != '' ? $_GET['return_url'] : 'index.php/bms_dashboard/index');
                
            }

            $result_dev = $this->bms_masters_model->auth_dev($username,$pass);

            if ( count($result_dev) > 0 ) {

                $this->setCookies($username,$pass);
                $_SESSION['bms']['is_logged_in'] = true;
                $_SESSION['bms']['first_name'] = $result_dev[0]['email_addr']; //!empty($result_dev[0]['property_name']) ? trim($result_dev[0]['property_name']) : '';
                $_SESSION['bms']['email'] = $result_dev[0]['email_addr'];
                $_SESSION['bms']['staff_id'] = '-2';
                $_SESSION['bms']['property_id'] = $result_dev[0]['property_id'];
                $_SESSION['property_under'] = 3;
                $_SESSION['bms']['designation_id'] = $_SESSION['bms']['user_type']  = 'developer';
                $_SESSION['bms']['property_dev_id'] = $result_dev[0]['property_dev_id'];

                redirect (isset($_GET['return_url']) && $_GET['return_url'] != '' ? $_GET['return_url'] : 'index.php/bms_defect/defect_list/');
            }

            
            $result = $this->bms_masters_model->auth_jmb($username,$pass);
            
            if(count($result) > 0 ) {
                //echo "Valid JMB MC";
                $this->setCookies($username,$pass);
                $_SESSION['bms']['is_logged_in'] = true;
                $_SESSION['bms']['first_name'] = $_SESSION['bms']['full_name'] = trim($result[0]['owner_name']);                                
                $_SESSION['bms']['email'] = $result[0]['email_addr']; 
                $_SESSION['bms']['staff_id'] = 0;
                $_SESSION['bms']['property_id'] = $result[0]['property_id'];
                $_SESSION['bms']['unit_id'] = $result[0]['unit_id'];  
                $_SESSION['bms']['member_id'] = $result[0]['member_id'];
                $_SESSION['bms']['designation_id'] = $_SESSION['bms']['user_type']  = 'jmb'; 
                redirect(isset($_GET['return_url']) && $_GET['return_url'] != '' ? $_GET['return_url'] : 'index.php/bms_dashboard/index');
            } else {
                //echo "<script>alert('Invalid Username or Password!');</script>";
                redirect('index.php/bms_index/login?login_err=invalid'.(isset($_GET['return_url']) && $_GET['return_url'] != '' ? '&return_url='.$_GET['return_url'] : ''));
            }
            
        } else {
            //echo "<script>alert('Invalid Login!');</script>";
            redirect('index.php/bms_index/login?login_err=invalid');//input_empty
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
    
    function change_password () {
        $data['browser_title'] = 'Property Butler | Change Password';
        $data['page_header'] = '<i class="fa fa-key"></i> Change Password';        
        $this->load->view('change_password_view',$data);
    }
    
    function check_password () {
        if(isset($_POST['current_pass']) && $_POST['current_pass'] != '' ) { 
            $pass = trim($_POST['current_pass']);
            if($_SESSION['bms']['user_type'] == 'staff') {
                $result = $this->bms_masters_model->auth($_SESSION['bms']['email'],$pass);
            } elseif ( $_SESSION['bms']['user_type'] == 'jmb' ) {
                $result = $this->bms_masters_model->auth_jmb($_SESSION['bms']['email'],$pass);
            } elseif ( $_SESSION['bms']['user_type'] == 'developer' ) {
                $result = $this->bms_masters_model->auth_dev($_SESSION['bms']['email'],$pass);
            }

            if(count($result) >= 1 ) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
    
    function change_password_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        if(isset($_POST['current_pass']) && $_POST['current_pass'] != '' && isset($_POST['new_pass']) && $_POST['new_pass'] != '' ) {
            $pass = trim($_POST['new_pass']);            
            if($_SESSION['bms']['user_type'] == 'staff') {
                if($this->bms_masters_model->update_pass($_SESSION['bms']['email'],$pass)) {
                    $_SESSION['flash_msg'] = 'Password Changed successfully!';         
                } else {
                    $_SESSION['error_msg'] = 'Password cannot changed!';
                }
            } else if($_SESSION['bms']['user_type'] == 'jmb') {
                if($this->bms_masters_model->update_pass_jmb($_SESSION['bms']['email'],$pass)) {
                    $_SESSION['flash_msg'] = 'Password Changed successfully!';         
                } else {
                    $_SESSION['error_msg'] = 'Password cannot changed!';
                }
            } else if($_SESSION['bms']['user_type'] == 'developer') {
                if($this->bms_masters_model->update_pass_dev($_SESSION['bms']['email'],$pass)) {
                    $_SESSION['flash_msg'] = 'Password Changed successfully!';
                } else {
                    $_SESSION['error_msg'] = 'Password cannot changed!';
                }
            }
        }
        redirect ('index.php/bms_index/change_password?changed=1');
    }
    
    public function logout() {
		$data['browser_title'] = 'Property Butler | Logout Process';
        $data['page_header'] = '<i class="fa fa-dashboard"></i> Dashboard';
        unset($_SESSION['bms']);
        unset($_SESSION['bms_default_property']);
        redirect('index.php/bms_index/login');
	}
}