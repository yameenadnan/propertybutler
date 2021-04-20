<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
#header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

class Bms_api_for_vms extends CI_Controller {

    function __construct () {
        parent::__construct ();
        header('Content-type: application/json');
        $inputs = json_decode(file_get_contents('php://input'));

        if (!(isset($inputs->auth_key) && $inputs->auth_key === '@aVDc9$rQctBN9JJNPEw9VVpJW2C=5ue') ) {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Authentication failed!'));
            echo json_encode(array($data));	exit;
        }

        $this->load->model('bms_api_for_vms_model','bms_api_for_vms_model');
    }

    function get_prebooks_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result['prebooks_to_add'] = $this->bms_api_for_vms_model->get_prebooks_of_property ($inputs->property_id);
            $result['prebooks_to_cencel'] = $this->bms_api_for_vms_model->get_prebooks_to_cancel_of_property ($inputs->property_id);
            $data['Data'] = $result;
        } else {
            $data['Data'] = array('prebooks_of_property' => '0' );
        }
        echo json_encode( $data );
    }

    function update_prebooks_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->add_prebook_visitor_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $prebook_visitor_id = explode(',', $inputs->add_prebook_visitor_id);
            $result = $this->bms_api_for_vms_model->update_prebooks_of_property ($prebook_visitor_id, $data_update);
            $data['Data']['prebooks_add'] = $result;
        } else {
            $data['Data']['prebooks_add'] = 0;
        }

        if ( !empty($inputs->cancel_prebook_visitor_id) ) {
            $data_update = array (
                'flag' => 4
            );
            $prebook_visitor_id = explode(',', $inputs->cancel_prebook_visitor_id);
            $result = $this->bms_api_for_vms_model->update_prebooks_of_property ($prebook_visitor_id, $data_update);
            $data['Data']['prebooks_cancel'] = $result;
        } else {
            $data['Data']['prebooks_cancel'] = 0;
        }

        if ( !empty($inputs->expired_prebook_visitor_id) ) {
            $data_update = array (
                'flag' => 5
            );
            $prebook_visitor_id = explode(',', $inputs->expired_prebook_visitor_id);
            $result = $this->bms_api_for_vms_model->update_prebooks_of_property ($prebook_visitor_id, $data_update);
            $data['Data']['prebooks_expired'] = $result;
        } else {
            $data['Data']['prebooks_expired'] = 0;
        }

        if ( !empty($inputs->booked_prebook_visitor_id) ) {
            $data_update = array (
                'flag' => 6
            );
            $prebook_visitor_id = explode(',', $inputs->booked_prebook_visitor_id);
            $result = $this->bms_api_for_vms_model->update_prebooks_of_property ($prebook_visitor_id, $data_update);
            $data['Data']['prebooks_booked'] = $result;
        } else {
            $data['Data']['prebooks_booked'] = 0;
        }
        echo json_encode( $data );
    }
    
    function getFreqVisitor_NoteToGuard () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result['frequent_visitor_to_add'] = $this->bms_api_for_vms_model->getFrequentVisitor ($inputs->property_id);
            $result['frequent_visitor_to_update'] = $this->bms_api_for_vms_model->getFrequentVisitorToUpdate ($inputs->property_id);
            $result['note_to_guard_to_add'] = $this->bms_api_for_vms_model->getNoteToGuard ($inputs->property_id);
            $data['Data'] = $result;
        } else {
            $data['Data'] = array();
        }
        echo json_encode( $data );
    }

    function updateFreqVisitor_NoteToGuard () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->frequent_visitor_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $frequent_visitor_id = explode(',', $inputs->frequent_visitor_id);
            $result = $this->bms_api_for_vms_model->updateFreqVisitor ($frequent_visitor_id, $data_update);
            $data['Data']['frequent_visitor_update'] = $result;
        } else {
            $data['Data']['frequent_visitor_update'] = 0;
        }
        
        if ( !empty($inputs->note_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $note_id = explode(',', $inputs->note_id);
            $result = $this->bms_api_for_vms_model->updateNoteToGuard ($note_id, $data_update);
            $data['Data']['note_to_guard_update'] = $result;
        } else {
            $data['Data']['note_to_guard_update'] = 0;
        }
        echo json_encode( $data );
    }

    function getPanicAlert () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result['panic_alert_to_add'] = $this->bms_api_for_vms_model->getPanicAlert ($inputs->property_id);
            $data['Data'] = $result;
        } else {
            $data['Data'] = array('panic_alert_to_add' => '0' );
        }
        echo json_encode( $data );
    }

    function updatePanicAlert () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->panic_alert_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $panic_alert_id = explode(',', $inputs->panic_alert_id);
            $result = $this->bms_api_for_vms_model->updatePanicAlert ($panic_alert_id, $data_update);
            $data['Data']['panic_alert_update'] = $result;
        } else {
            $data['Data']['panic_alert_update'] = 0;
        }

        echo json_encode( $data );
    }
    
    function sendVisitorPushNotification ($to,$batch_count,$title,$body,$task_id,$notificationType) {

        $idTitle = 'vms';

        $url = 'https://fcm.googleapis.com/fcm/send';//'https://android.googleapis.com/gcm/send';
        $fields = array(
            'to' => $to,
            'notification' => array('title' => $title,
                                    'body' => $body,
                                    'sound' => 'default',
                                    'badge' => $batch_count,
                                    'count' =>$batch_count),
            'data' => array('badge' => $batch_count,$idTitle => $task_id,'push_type' => 'Chat',
                                    'push_data' => array('FromId' => '1003',
                                                         'ToId' => '1024',
                                                         'FromType' => 'ME',
                                                         'ToType' => 'ME',
                                                         'MsgText' => 'Push Notification!',
                                                         'FileURL' =>'',
                                                         'ReadStatus'=>'0'))
        );

        //define('GOOGLE_API_KEY', 'AIzaSyCjctNK2valabAWL7rWUTcoRA-UAXI_3ro');
        //AAAAeZ_p7LE:APA91bH2MZha6bOXdcONf7yDiYrpflmVnMy_HNgofjM3XICW4GyC8KPQ5medrqpLF19z2TxsMFz_nsYFvTTqyKrV_dgkhaL0nX_YbJ_MmsFI_QtLMx0dFz95-4ph3u12OjT2ioVrqpJ7
        $headers = array(
            'Authorization:key=AAAAGAqDMPg:APA91bFSt42cakNRMfZ-SrLZbSrMVa74baJRo7Q3tpCfpAM4WPcnssx7VdkiHoDePVQAWJCuHYj-SYuVWJohcuB8ehW6kwJ_rRIzmqurNgZmLOG-i-q3yOOEtRuC6EaxuwHV3rIZy7Yl',
            'Content-Type: application/json'
        );
        //echo json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        /*if($result === false)
            die('Curl failed ' . curl_error());*/

        curl_close($ch);
        //echo "<br />". $result;
    }

    function visitor_registration () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        $data['prebooks_booked'] =  $data['registrationDetails'] = $data['visitorMaster'] = false;
        // Prebook visitor arrived
        if ( !empty($inputs->booked_prebook_visitor_id) ) {
            $data_update = array (
                'flag' => 6
            );
            $prebook_visitor_id = $inputs->booked_prebook_visitor_id;
            $data ['prebooks_booked'] = $this->bms_api_for_vms_model->update_prebooks_of_property ($prebook_visitor_id, $data_update);
        } 
        
        if ( !empty ($inputs->visitorMaster) ) {
            $data_visitorMaster = array (
                "visitor_id"     => $inputs->visitorMaster->visitor_id,
                "property_id"    => $inputs->visitorMaster->property_id,
                "vehicle_no"     => $inputs->visitorMaster->vehicle_no,
                "vehicle_make"   => $inputs->visitorMaster->vehicle_make,
                "visitor_name"   => $inputs->visitorMaster->visitor_name,
                "gender"         => $inputs->visitorMaster->gender,
                "ic_no"          => $inputs->visitorMaster->ic_no,
                "visitor_addr"   => $inputs->visitorMaster->visitor_addr
            );
            
            $data_mas_where = array (
                "visitor_id"     => $inputs->visitorMaster->visitor_id,
                "property_id"    => $inputs->visitorMaster->property_id
            );
            
            $mas_cnt = $this->bms_api_for_vms_model->check_visit_master ( $data_mas_where );
            if($mas_cnt > 0) {
                $data['visitorMaster'] = $this->bms_api_for_vms_model->update_visitor_master ( $data_visitorMaster, $data_mas_where );
            } else {
                $data['visitorMaster'] = $this->bms_api_for_vms_model->insert_visitor_master ( $data_visitorMaster );
            }            
        }
        if ( !empty ($inputs->registrationDetails) ) {
            
            // Retrive mobile number to add in the registration details page since vms is not sending the mobile no
            if(!empty($inputs->visitorMaster->visitor_name)) {
                $visitor_name = $inputs->visitorMaster->visitor_name;
            } else {
                $visitor_info = $this->bms_api_for_vms_model->getVisitor($inputs->registrationDetails->VMS_Id,$inputs->registrationDetails->property_id);
                $visitor_name = !empty($visitor_info['visitor_name']) ? $visitor_info['visitor_name'] : 'Visitor';
            }
            $mobile_no = array ();
            if(!empty($inputs->registrationDetails->booking_type)) {                
                // prebooked visitor
                if($inputs->registrationDetails->booking_type == 0) {
                    if(!empty($inputs->booked_prebook_visitor_id)) {
                        // get prebooked mobile no
                        $mobile_no = $this->bms_api_for_vms_model->getPrebookedMobileNo($inputs->booked_prebook_visitor_id);                        
                    }
                } else if($inputs->registrationDetails->booking_type == 1 && !empty($inputs->registrationDetails->vehicle_no)) { // Frequent visitor
                        // get frequent visitor registered mobile no
                        $mobile_no = $this->bms_api_for_vms_model->getFrequentRegMobileNo($inputs->registrationDetails->property_id,$inputs->registrationDetails->unit_id,$inputs->registrationDetails->vehicle_no);
                } /*else if ($inputs->registrationDetails->booking_type == 2) { // Adhoc visitor
                }*/
            }
            $mobile_nos = array();
            if(!empty($mobile_no)) { 
                $mobile_nos = array_column($mobile_no, 'mobile_no');
            }
            $mob_nos = !empty($mobile_nos) ? implode(',',$mobile_nos) : '';
            
            $data_visit_details = array (
                "visit_detail_id"   => $inputs->registrationDetails->Id,
                "visitor_id"        => $inputs->registrationDetails->VMS_Id,
                "property_id"       => $inputs->registrationDetails->property_id,
                "unit_id"           => $inputs->registrationDetails->unit_id,
                "mobile_no"         => $mob_nos,
                "visit_type"        => $inputs->registrationDetails->visit_type,
                "booking_type"      => $inputs->registrationDetails->booking_type,
                "visit_duration"    => $inputs->registrationDetails->visit_duration,
                "visit_date"        => $inputs->registrationDetails->visit_date,
                "visit_time"        => $inputs->registrationDetails->visit_time
            );
            
            $data_where = array (
                "visit_detail_id"   => $inputs->registrationDetails->Id,
                "visitor_id"        => $inputs->registrationDetails->VMS_Id,
                "property_id"       => $inputs->registrationDetails->property_id
            );
            
            $cnt = $this->bms_api_for_vms_model->check_visit_details ( $data_where );
            if($cnt > 0) {
                $data['registrationDetails'] =  $this->bms_api_for_vms_model->update_visit_details ( $data_visit_details, $data_where );
            } else {
                $data['registrationDetails'] = $this->bms_api_for_vms_model->insert_visit_details ( $data_visit_details );                
            }
            
            
            
            // push notification for visitor arrived 
            $push_data[0] = $this->bms_api_for_vms_model->getOwnerPushToken($inputs->registrationDetails->unit_id,$mobile_nos);
            $push_data[1] = $this->bms_api_for_vms_model->getTenantPushToken($inputs->registrationDetails->unit_id,$mobile_nos);
            $push_data[2] = $this->bms_api_for_vms_model->getMAUsersPushToken($inputs->registrationDetails->unit_id,$mobile_nos);
            
            foreach ($push_data as $key => $val) {
                if(!empty($val)) {
                    foreach ($val as $k => $v ) { 
                        $body = $visitor_name. ' has arrived!';
                        $this->sendVisitorPushNotification ($v['push_token'],'1','VMS',$body,'','vms');
                    }                    
                }                
            }
        }
        echo json_encode ( $data );

    }
    
    function visitorSignOutUpdate () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));        
        
        if ( !empty ($inputs->registrationDetails) ) {
            $data_details = array (
                "exit_date"         => $inputs->registrationDetails->signout_date,
                "exit_time"         => $inputs->registrationDetails->signout_time
            );
            $data_where = array (
                "visit_detail_id"   => $inputs->registrationDetails->Id,
                "visitor_id"        => $inputs->registrationDetails->VMS_Id,
                "property_id"       => $inputs->registrationDetails->property_id
            );
            $result['registrationDetails'] = $this->bms_api_for_vms_model->update_visit_details ( $data_details, $data_where);
        }
        $data['registrationDetails'] = $result['registrationDetails'];
        echo json_encode ( $data );

    }
    
    function unitSync () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        $data['Data']['sync_updated'] = $data['Data']['sync_data'] = 0;
        if ( !empty($inputs->synced_unit_ids) ) {
            $data_update = array ('vms_sync' => 1);
            $ids = explode(',', $inputs->synced_unit_ids);
            $data['Data']['sync_updated'] = $this->bms_api_for_vms_model->update_unit_vms_sync_flag ($data_update, $ids);            
        }
        if ( !empty($inputs->property_id) ) {
            $data['Data']['sync_data'] = $this->bms_api_for_vms_model->get_unit_unsync_data ($inputs->property_id);            
        } 
        echo json_encode( $data );
    }

    /*function get_ntg_fv_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result['note_to_guard_to_add'] = $this->bms_api_for_vms_model->get_note_to_guard_of_property ($inputs->property_id);
        }
        if ( !empty($inputs->property_id) ) {
            $result['frequent_visitor_to_add'] = $this->bms_api_for_vms_model->get_frequent_visitor_of_property ($inputs->property_id);
            $result['frequent_visitor_to_update'] = $this->bms_api_for_vms_model->get_frequent_visitor_to_update_of_property ($inputs->property_id);
        }
        echo json_encode( $data );
    }

    function get_note_to_guard_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if ( !empty($inputs->property_id) ) {
            $result['note_to_guard_to_add'] = $this->bms_api_for_vms_model->get_note_to_guard_of_property ($inputs->property_id);
            $data['Data'] = $result;
        } else {
            $data['Data'] = array('note_to_guard_of_property' => '0' );
        }
        echo json_encode( $data );
    }

    function update_note_to_guard_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->note_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $note_id = explode(',', $inputs->note_id);
            $result = $this->bms_api_for_vms_model->update_note_to_guard_of_property ($note_id, $data_update);
            $data['Data']['note_to_guard_add'] = $result;
        } else {
            $data['Data']['note_to_guard_add'] = 0;
        }

        echo json_encode( $data );
    }

    

    function update_status_of_frequent_visitor_of_property () {
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));

        if ( !empty($inputs->frequent_visitor_id) ) {
            $data_update = array (
                'flag' => 2
            );
            $frequent_visitor_id = explode(',', $inputs->frequent_visitor_id);
            $result = $this->bms_api_for_vms_model->update_frequent_visitor_of_property ($frequent_visitor_id, $data_update);
            $data['Data']['frequent_visitor_add'] = $result;
        } else {
            $data['Data']['frequent_visitor_add'] = 0;
        }

        echo json_encode( $data );
    }*/
    
    

}