<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//ob_start();
class Bms_defect extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        //unset($_SESSION['bms']);
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        $this->load->model('bms_masters_model');
        $this->load->model('bms_defect_model');
    }

    public function defect_list() {
		$data['browser_title'] = 'Property Butler | Defect List';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Defect List';

        if ( $_SESSION['bms']['user_type'] == 'developer' ) {
            $data['properties'] = $this->bms_masters_model->getMyPropertiesDeveloper ();
        } else {
            $data['properties'] = $this->bms_masters_model->getMyPropertiesForDefects ();
        }


        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
        // His own ask
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $this->load->view('defect/defect_list_view',$data);
	}
    
    function get_defect_list () {
        header('Content-type: application/json');
        $tasks = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $property_id = !empty($_POST['property_id']) ? $_POST['property_id'] : '';
            $defect_status = !empty($_POST['defect_status']) ? $_POST['defect_status'] : '';
            $defect_id = !empty($_POST['defect_id']) ? trim ($_POST['defect_id']) : '';
            $defect_search_txt = !empty($_POST['search_txt']) ? trim ($_POST['search_txt']) : '';
            $sort_by = !empty($_POST['sort_by']) ? trim ($_POST['sort_by']) : 'desc';
            if ( $_SESSION['bms']['user_type'] == 'developer' ) {
                $defects = $this->bms_defect_model->get_defect_developer ($_SESSION['bms']['staff_id'], $_POST['offset'],$_POST['rows'],$property_id,$defect_status,$defect_id,$defect_search_txt,$sort_by);
            } else {
                $defects = $this->bms_defect_model->get_defect ($_SESSION['bms']['staff_id'], $_POST['offset'],$_POST['rows'],$property_id,$defect_status,$defect_id,$defect_search_txt,$sort_by);
            }
        }
        echo json_encode ($defects);
    }

    function print_defect_list () {
        $data['browser_title'] = 'Property Butler | Defect List';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Defect List';
        $property_id = !empty($_GET['property_id']) ? $_GET['property_id'] : '';
        $defect_status = !empty($_GET['defect_status']) ? $_GET['defect_status'] : '';
        $defect_id = !empty($_GET['defect_id']) ? trim ($_GET['defect_id']) : '';
        $task_search_txt = !empty($_GET['search_txt']) ? trim ($_GET['search_txt']) : '';
        $sort_by = !empty($_GET['sort_by']) ? trim ($_GET['sort_by']) : 'desc';
        if ( $_SESSION['bms']['user_type'] == 'developer' ) {
            $data['defects'] = $this->bms_defect_model->get_defect_developer ($_SESSION['bms']['staff_id'], 0,'18446744073709551615',$property_id,$defect_status,$defect_id,$task_search_txt,$sort_by);
        } else {
            $data['defects'] = $this->bms_defect_model->get_defect ($_SESSION['bms']['staff_id'], 0,'18446744073709551615',$property_id,$defect_status,$defect_id,$task_search_txt,$sort_by);
        }

        $this->load->view('defect/defect_list_print_view',$data);
    }

    function defect_details ($defect_id) {
       
        $data['browser_title'] = 'Property Butler | Defect Details';
        $data['page_header'] = '<i class="fa fa-info-circle"></i>  Defect Details';
       
        $data['defect_id'] = $defect_id;
        if ($_SESSION['bms']['user_type'] == 'developer') {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details_developer ($defect_id,$_SESSION['bms']['property_id']);
        } else {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details($defect_id,$_SESSION['bms']['staff_id']);
        }
        if(empty($data['defect_details'])) {
            redirect('index.php/bms_defect/defect_list');
        }
        
        if($data['defect_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['defect_details']->block_id);
        $data['defect_images'] = $this->bms_defect_model->get_defect_images($defect_id);
        
        $this->load->view('defect/defect_details_view',$data);
    }
    
    function print_defect ($defect_id) {
        $data['defect_id'] = $defect_id;
        if ($_SESSION['bms']['user_type'] == 'developer') {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details_developer ($defect_id,$_SESSION['bms']['property_id']);
        } else {
            $data['defect_details'] = $this->bms_defect_model->get_defect_details($defect_id,$_SESSION['bms']['staff_id']);
        }
        if($data['defect_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['defect_details']->block_id);
        $data['defect_images'] = $this->bms_defect_model->get_defect_images($defect_id);
        $this->load->view('defect/defect_details_print_view',$data);
    }

    function set_defect_forum () {
        //echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        if(!empty($_POST['defect_id']) && ((!empty($_POST['chat_text']) ) || (!empty($_FILES) && $_FILES['attach']['error'] == 0))) {
            $defect_id = $_POST['defect_id'];
            $chat_text = trim($_POST['chat_text']);
            $img_name = '';
            if(!empty($_FILES) && $_FILES['attach']['error'] == 0 ){
                $defect_forum_upload = $this->config->item('defect_forum_upload');

                $this->load->library('upload');
                $defect_forum_upload['upload_path'] = $defect_forum_upload['upload_path'].$defect_id.'/';
                if(!is_dir($defect_forum_upload['upload_path']));
                @mkdir($defect_forum_upload['upload_path'], 0777);

                $defect_forum_upload['file_name'] = date('dmYHis');
                $this->upload->initialize($defect_forum_upload);

                if ( ! $this->upload->do_upload('attach')) {
                    $this->upload->display_errors();
                } else {//echo "<pre>";print_r($task_forum_upload);exit;
                    $img_name = $this->upload->data('file_name');
                }
            }

            $this->bms_defect_model->set_defect_forum($defect_id,$chat_text,$img_name,$_SESSION['bms']['staff_id']);
            echo true;
        } else {
            echo false;
        }
    }

    function get_defect_forum ($defect_id) {
        $res = $this->bms_defect_model->getDefectForum($defect_id);
        if(!empty($res)) {
            foreach ($res as $key=>$val) {
                $margin = $key == 0 ? '5px 0 15px 0' : '15px 0';
                echo '<div class="row" style="margin:'.$margin.';">';
                echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.($val['comment_by'] == '-2' ? ' Developer ' : ($val['comment_by'] == '-1' ? 'Resident ' : $val['first_name'] .' '.(!empty($val['last_name']) ? $val['last_name'] : ''))).' </span> on '.date('d-m-Y h:i:s a',strtotime($val['comment_date']));
                if($val['img_name'] != '') {
                    echo '&ensp;&ensp;<a href="../../../bms_uploads/defect_forum_upload/'.$val['defect_id'].'/'.$val['img_name'].'" target="_blank" title="view/Download">Attachment</a>';
                }
                echo '</div>';
                echo '<div class="col-md-12">'.$val['comment'].'</div>';
                echo "</div>";
            }
        }
    }

    function set_defect_status () {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        if ( isset($_POST['defect_id']) && $_POST['defect_id'] != '' ) {
            $defect_id = $_POST['defect_id'];
            if(!empty($_POST['defect_update']) && $_POST['defect_update'] == 'Closed') {
                $data['defect_status'] = 'C';
                $data['defect_close_remarks'] = trim($_POST['close_rem']);
            }
            $data['updated_by'] = $_SESSION['bms']['staff_id'];
            $data['updated_date'] = date("Y-m-d");
            $this->bms_defect_model->set_defect_update_with_log($defect_id,$data,$_SESSION['bms']['staff_id']);

            // Notification emil to resident for task close
            if (isset($_POST['defect_update']) && $_POST['defect_update'] == 'Closed') {
                $unit_details = $this->bms_defect_model->get_defect_details_for_email ( $defect_id );
                if (!empty($unit_details) && !empty($unit_details['email_addr'])) {
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

                    $message .= 'We are pleased to inform you that your defect has been resolved. Please refer to below defect details and remarks.<br /><br />';

                    $message .= '<b>Defect Id:</b> '.str_pad($unit_details['defect_id'], 5, '0', STR_PAD_LEFT) .'<br />';
                    $message .= '<b>Defect Title:</b> '.$unit_details['defect_name'] .'<br />';
                    $message .= '<b>Defect Location:</b> '.(!empty($unit_details['defect_location']) ? $unit_details['defect_location'] : ' - ' ) .'<br />';
                    $message .= '<b>Defect Detail:</b> '.(!empty($unit_details['defect_detail']) ? $unit_details['defect_detail'] : ' - ' ) .'<br />';
                    $message .= '<b>Close Remarks:</b> '.(!empty($unit_details['defect_close_remarks']) ? $unit_details['defect_close_remarks'] : ' - ' ) .'<br /><br />';

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
                    </body>
                    </html>';
                    // Also, for getting full html you may use the following internal method:
                    //$body = $this->email->full_html($subject, $message);

                    $result = $this->email
                        ->from('noreply@propertybutler.my','Propertybutler')
                        ->to($to,$r_name)
                        ->bcc('naguwin@gmail.com','Nagarajan')
                        ->subject($subject)
                        ->message($body)
                        ->send();
                }
            }
            echo true;
        } else {
            echo false;
        }
    }

    public function new_defect ($type = '') {

        $data['browser_title'] = 'Property Butler | Add Defect';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Add Defect';
        if($_SESSION['bms']['user_type'] == 'developer') {
            $data['properties'] = $this->bms_masters_model->getMyPropertiesDeveloper ();
            // $data['properties'] = $this->bms_masters_model->getDevProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyPropertiesForDefects ();
        }

        $this->load->view('defect/defect_new_view',$data);
    }

    function defect_image_submit () {
        $data = array ();
        if(!empty($_FILES)){

            $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');

            $this->load->library('upload');

            $_FILES['temp_img']['name']= $_FILES['addRequestFile1']['name'][0];
            $_FILES['temp_img']['type']= $_FILES['addRequestFile1']['type'][0];
            $_FILES['temp_img']['tmp_name']= $_FILES['addRequestFile1']['tmp_name'][0];
            $_FILES['temp_img']['error']= $_FILES['addRequestFile1']['error'][0];
            $_FILES['temp_img']['size']= $_FILES['addRequestFile1']['size'][0];

            $defect_file_upload_temp['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize($defect_file_upload_temp);
            //echo "<pre>";print_r($task_file_upload_temp);exit;
            if ( ! $this->upload->do_upload('temp_img') ) {
                //if(count($_FILES) > 1)
                //    echo $task_file_upload_temp_err = 'One or more images are not uploaded!';
                //else
                //    $task_file_upload_temp_err = 'Image is not uploaded!';
                $data['upload_err'] = $this->upload->display_errors();
            } else {
                $data['name'] = $this->upload->data('file_name');
            }
        }
        echo json_encode($data);
    }

    function defect_image_remove () {
        if(!empty($_POST['file'])){
            $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');
            if(file_exists($defect_file_upload_temp['upload_path'].$_POST['file']))
                @unlink($defect_file_upload_temp['upload_path'].$_POST['file']);
        }
    }

    function get_blocks () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $blocks = array();
        if($property_id) {
            $blocks = $this->bms_masters_model->getBlocks ($property_id);
        }
        echo json_encode($blocks);
    }

    function get_unit () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $block_id = trim($this->input->post('block_id'));

        $unit = array();
        if($property_id) {
            $unit = $this->bms_masters_model->getUnit ($property_id,$block_id);
        }
        echo json_encode($unit);
    }

    function new_defect_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        if(isset($_POST) && !empty($_POST['defect'])) {
            //$this->load->model('bms_task_model');
            $defect = $this->input->post('defect');
            $defect['created_date'] = date('Y-m-d');
            $defect['defect_status'] = 'O';
            $defect['created_by'] = $_SESSION['bms']['staff_id'];
            $defect['created_by_type'] = $_SESSION['bms']['staff_id'] == '-2' ? 'D' : 'S';
            $insert_id = $this->bms_defect_model->defect_insert($defect);

            if(!empty($_POST['files'])) {

                $defect_file_upload = $this->config->item('defect_file_upload');
                $defect_file_upload['upload_path'] = $defect_file_upload['upload_path'].$insert_id.'/';
                if(!is_dir($defect_file_upload['upload_path']));
                @mkdir($defect_file_upload['upload_path'], 0777);

                $defect_file_upload_temp = $this->config->item('defect_file_upload_temp');

                foreach ($_POST['files'] as $key=>$val) {
                    rename($defect_file_upload_temp['upload_path'].$val, $defect_file_upload['upload_path'].$val);
                    $img_data['defect_id'] = $insert_id;
                    $img_data['img_name'] = $val;
                    $this->bms_defect_model->defect_image_name_insert ($img_data);

                }
            }

            // for task log
            $this->bms_defect_model->set_defect_create_log($insert_id,$_SESSION['bms']['staff_id']);

            $_SESSION['flash_msg'] = 'Defect has been created successfully!';

            /*// push notification
            $this->notifications->sendPushNotification($task['property_id'],$_POST['property_name'],$task['task_name']);*/

            // Notification emil to resident for task creation

            if ( !empty($_POST['resident_email_hidd']) ) {
                if ( (filter_var($_POST['resident_email_hidd'], FILTER_VALIDATE_EMAIL)) && $_POST['resident_valid_email'] == 1) {

                    $property_info = $this->bms_masters_model->getPropertyInfo ($defect['property_id']);

                    $property_dev_info = $this->bms_masters_model->getDeveloperCredentials ($defect['property_id']);

                    $to = $_POST['resident_email_hidd'];
                    $r_name = !empty($_POST['resident_name_hidd']) ? $_POST['resident_name_hidd'] : '';

                    $this->load->library('email');

                    $subject = $_POST['defect']['defect_name'] .' | '. $property_info['property_name'];
                    $message = '<p>To <b>';
                    if(!empty($_POST['resident_gender_hidd'])) {
                        $message .= $_POST['resident_gender_hidd'] == 'Male' ? 'Mr ' : ($_POST['resident_gender_hidd'] == 'Female' ? 'Ms ' : '');
                    }
                    $message .= $r_name;
                    $message .= ',</b><br /><br />';

                    $message .=  'A defect has been created as per below description. We keep this defecct as our highest priority and looking forward to solve as soon as possible. We will notify you when the defect is solved for your kind reference.<br /><br />';

                    $message .= '<b>Defect Id:</b> '.str_pad($insert_id, 5, '0', STR_PAD_LEFT) .'<br />';
                    $message .= '<b>Unit No.:</b> '. (!empty($_POST['unit_no']) ? $_POST['unit_no']:'') .'<br />';
                    $message .= '<b>Owner name:</b> '.(!empty($r_name) ? $r_name:'') .'<br />';
                    $message .= '<b>Defect Name:</b> '. (!empty($_POST['defect']['defect_name']) ? $_POST['defect']['defect_name']:'') .'<br />';
                    $message .= '<b>Defect Location:</b> '.(!empty($_POST['defect']['defect_location']) ? $_POST['defect']['defect_location'] : ' - ' ) .'<br />';
                    $message .= '<b>Defect Details:</b> '.(!empty($_POST['defect']['defect_detail']) ? $_POST['defect']['defect_detail'] : ' - ' ) .'<br /><br />';

                    $message .= 'Thank you,<br />' . 'Managed by: ' . $property_info['managed_by'] . ' <br />' . $property_info['property_name'];

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
                    </body>
                    </html>';
                    // Also, for getting full html you may use the following internal method:
                    //$body = $this->email->full_html($subject, $message);

                    $result = $this->email
                    ->from('noreply@propertybutler.my','Propertybutler')
                    ->to($to,$r_name)
                    ->bcc('naguwin@gmail.com','Nagarajan');
                    if ( !empty ($property_dev_info) ) {
                        foreach ( $property_dev_info as $key => $val ) {
                            $this->email->bcc( $val['email_addr'], 'Propertybutler' );
                        }
                    }
                    $this->email
                    ->subject($subject)
                    ->message($body)
                    ->send();

                } else {
                    $data_unit = array (
                        'valid_email' => 0
                    );
                    if ( !empty ($defect['unit_id']) )
                        $this->bms_masters_model->update_unit_set_invalid_email ($data_unit, $defect['unit_id']);
                }
            } else {
                $property_info = $this->bms_masters_model->getPropertyInfo ($defect['property_id']);

                $property_dev_info = $this->bms_masters_model->getDeveloperCredentials ($defect['property_id']);

                $r_name = $property_info['property_name'];

                $this->load->library('email');

                $subject = $_POST['defect']['defect_name'] .' | '. $property_info['property_name'];
                $message = '<p>To <b>';
                $message .= $r_name;
                $message .= ',</b><br /><br />';


                $message .=  'A defect has been created as per below description. We keep this defecct as our highest priority and looking forward to solve as soon as possible. We will notify you when the defect is solved for your kind reference.<br /><br />';

                $message .= '<b>Defect Id:</b> '.str_pad($insert_id, 5, '0', STR_PAD_LEFT) .'<br />';
                $message .= '<b>Defect Name:</b> '. (!empty($_POST['defect']['defect_name']) ? $_POST['defect']['defect_name']:'') .'<br />';
                $message .= '<b>Defect Location:</b> '.(!empty($_POST['defect']['defect_location']) ? $_POST['defect']['defect_location'] : ' - ' ) .'<br />';
                $message .= '<b>Defect Details:</b> '.(!empty($_POST['defect']['defect_detail']) ? $_POST['defect']['defect_detail'] : ' - ' ) .'<br /><br />';

                $message .= 'Thank you,<br />Transpacc <br />' . $property_info['property_name'];

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
                    </body>
                    </html>';
                // Also, for getting full html you may use the following internal method:
                //$body = $this->email->full_html($subject, $message);

                $email_array = array ();
                foreach ( $property_dev_info as $key => $val ) {
                    $email_array[] = $val['email_addr'];
                }
                $to = implode (', ', $email_array);
                $to = !empty ($to) ? $to : 'naguwin@gmail.com';

                $result = $this->email
                    ->from('noreply@propertybutler.my','Propertybutler')
                    ->to($to)
                    ->bcc('naguwin@gmail.com','Nagarajan')
                    ->subject($subject)
                    ->message($body)
                    ->send();
            }
        }
        if(isset($_POST) && !empty($_POST['defect']) && empty($data['upload_err'])) {
            $qry_str = '';
            redirect('index.php/bms_defect/defect_list'.$qry_str);
        }
    }
}