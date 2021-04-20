<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications {
    
    function __construct() { $this->CI = & get_instance(); } // get the CodeIgniter object
    
    function get_notification_count ($user_type,$user_id,$type='',$property_id='') {
        $condi = '';
        switch ($type) {
            case 'create':
                $condi = " AND a.alert_info='1'";
                break;
            case 'update':
                $condi = " AND a.alert_info IN ('2','3')";
                break;
            case 'close':
                $condi = " AND a.alert_info='4'";
                break;
        }
        if(!empty($property_id)) {
            $sql = "SELECT COUNT(1) AS cnt FROM bms_task_alert a, bms_task b WHERE b.task_id = a.task_id AND b.property_id=$property_id AND a.user_id=".$user_id." AND a.user_type=";
        } else {            
            $sql = "SELECT COUNT(1) AS cnt FROM bms_task_alert a WHERE a.user_id=".$user_id." AND a.user_type=";
        }
        //$sql = "SELECT COUNT(1) AS cnt FROM bms_task_alert a WHERE a.user_id=".$user_id." AND a.user_type=";
        if($user_type == 'staff') {
           $sql .= 1;
        } else {
            $sql .= 2;
        }
        $sql .= $condi;
        
        $query = $this->CI->db->query($sql);
        //if($_SESSION['bms']['staff_id'] == 1273)
        //    echo "<pre>".$this->CI->db->last_query()."</pre>";
        $res = $query->row_array();
        /*if($type == '' && $user_type == 'staff') {
            $condi =!empty($property_id) ? " a.property_id = $property_id AND " : "";
                
            $sop_sql = "SELECT count(1) AS cnt
                    FROM bms_sop AS a
                    WHERE $condi a.property_id IN (SELECT property_id from bms_staff_property WHERE staff_id =".$user_id.")
                    AND sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '".date('Y-m-d')."')
                    AND start_date <= '".date('Y-m-d')."' AND (no_due_date = 1 OR (due_date <> '0000-00-00' AND due_date >= '".date('Y-m-d')."'))
                    AND execute_time <= '".date('H:i:s')."'
                    AND (
                        (task_schedule = 1 AND ".strtolower(date('D'))." = 1) 
                        OR (task_schedule = 2 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),14)=0)
                        OR (task_schedule = 3 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),21)=0)
                        OR (task_schedule = 4 AND DATE_FORMAT(start_date,'%m') < '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."') 
                        OR (task_schedule = 5 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),3) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 6 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),6) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 7 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),12) = 0 AND DATE_FORMAT(start_date,'%m') = '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                    )
                    ";
            $query = $this->CI->db->query($sop_sql);
            $res2 = $query->row_array();
            //echo "<pre>".$this->CI->db->last_query();
            $res['cnt']+= $res2['cnt'];
        } */
        return $res['cnt'];                  
    }   
    
    function get_sop_notification_count ($user_id,$property_id='') {
        
        $condi =!empty($property_id) ? " a.property_id = $property_id AND " : "";
        
        $sop_sql = "SELECT count(1) AS cnt
                FROM bms_sop AS a
                WHERE $condi a.property_id IN (SELECT property_id from bms_staff_property WHERE staff_id =".$user_id.")
                AND sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '".date('Y-m-d')."')
                AND start_date <= '".date('Y-m-d')."' AND (no_due_date = 1 OR (due_date <> '0000-00-00' AND due_date >= '".date('Y-m-d')."'))
                AND execute_time <= '".date('H:i:s')."'
                AND (
                        (task_schedule = 1 AND ".strtolower(date('D'))." = 1) 
                        OR (task_schedule = 2 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),14)=0)
                        OR (task_schedule = 3 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),21)=0)
                        OR (task_schedule = 4 AND DATE_FORMAT(start_date,'%m') < '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."') 
                        OR (task_schedule = 5 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),3) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 6 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),6) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 7 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),12) = 0 AND DATE_FORMAT(start_date,'%m') = '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                    )
                ";
        $query = $this->CI->db->query($sop_sql);
        $res = $query->row_array();
        
        return $res['cnt'];                  
    }  
    
    
    function get_notification_details ($user_type,$user_id,$type,$property_id='') {
        switch ($type) {
            case 'create':
                $condi = " AND alert_info='1'";
                break;
            case 'update':
                $condi = " AND alert_info IN ('2','3')";
                break;
            case 'close':
                $condi = " AND alert_info='4'";
                break;
        }
        $condi2 = !empty($property_id) ? " AND b.property_id=$property_id " : "";
        $sql = "SELECT a.task_id,alert_info,user_id,user_type,a.created_date,b.task_name,c.property_name
                FROM bms_task_alert a, bms_task b
                LEFT JOIN bms_property c ON c.property_id = b.property_id
                WHERE b.task_id = a.task_id $condi2 AND user_id=".$user_id." AND user_type=";
        if($user_type == 'staff') {
           $sql .= 1;
        } else {
            $sql .= 2;
        }
        $sql .= $condi;
        
        $sql .= ' ORDER BY a.created_date DESC';
        //$this->CI->db->query($sql);
        $query = $this->CI->db->query($sql);
        //if($_SESSION['bms']['staff_id'] == 1273)
        //    echo "<pre>".$this->CI->db->last_query()."</pre>";
        return $query->result_array();          
    }
    
    function get_sop_notification_details ($user_id,$property_id='') {
        $condi =!empty($property_id) ? " a.property_id = $property_id AND " : "";
        $sql =  "SELECT sop_id,sop_name,TIME_FORMAT(execute_time,'%h:%i %p') AS execute_time,
                TIME_FORMAT(due_by,'%h:%i %p') AS due_by,a.assign_to,a.property_id,c.property_name,d.desi_name
                FROM bms_sop AS a
                LEFT JOIN bms_property c ON c.property_id = a.property_id
                LEFT JOIN bms_designation d ON d.desi_id = a.assign_to  
                WHERE $condi a.property_id IN (SELECT property_id from bms_staff_property WHERE staff_id =".$user_id.")
                AND a.sop_id NOT IN (SELECT bms_sop_entry.sop_id FROM bms_sop_entry WHERE DATE_FORMAT(bms_sop_entry.entry_date, '%Y-%m-%d') = '".date('Y-m-d')."')
                AND a.start_date <= '".date('Y-m-d')."' AND (a.no_due_date = 1 OR (a.due_date <> '0000-00-00' AND a.due_date >= '".date('Y-m-d')."'))
                AND a.execute_time <= '".date('H:i:s')."'
                AND (
                        (task_schedule = 1 AND ".strtolower(date('D'))." = 1) 
                        OR (task_schedule = 2 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),14)=0)
                        OR (task_schedule = 3 AND MOD(DATEDIFF('".date('Y-m-d')."',start_date),21)=0)
                        OR (task_schedule = 4 AND DATE_FORMAT(start_date,'%m') < '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."') 
                        OR (task_schedule = 5 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),3) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 6 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),6) = 0 AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                        OR (task_schedule = 7 AND MOD(TIMESTAMPDIFF(MONTH, start_date, '".date('Y-m-d')."'),12) = 0 AND DATE_FORMAT(start_date,'%m') = '".date('m')."' AND DATE_FORMAT(start_date,'%d') = '".date('d')."')
                    )
                ";        
        $sql .= ' ORDER BY a.execute_time,sop_name ASC';
        //$this->CI->db->query($sql);
        $query = $this->CI->db->query($sql);
        //if($_SESSION['bms']['staff_id'] == 1273)
        //    echo "<pre>".$this->CI->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function sendPushNotification ($property_id,$property_name,$task_name,$unit_id='') {
        
        // get staff and jmb
        $sql = "SELECT staff_id,push_token FROM bms_staff WHERE push_token IS NOT NULL AND push_token <>'' AND staff_id  IN (SELECT staff_id from bms_staff_property where property_id = ".$property_id .")";
        //$this->CI->db->query($sql);
        $query = $this->CI->db->query($sql);        
        //    echo "<pre>".$this->CI->db->last_query()."</pre>";
        $result = $query->result_array();
        if(!empty($result)) {
            foreach ($result as $key=>$val) {
                $sql = "SELECT COUNT(1) AS cnt FROM bms_task_alert a WHERE a.user_id=".$val['staff_id']." AND a.user_type=1";
                $query = $this->CI->db->query($sql);
                $res = $query->row_array();
                $this->sendPn (array($val['push_token']),$res['cnt'],$property_name,$task_name);
            }
        }
        
        // JMB / MC
        $sql = "SELECT a.member_id,b.push_token FROM bms_jmb_mc a,bms_property_units b  WHERE b.unit_id =a.unit_id AND b.push_token IS NOT NULL AND b.push_token <>'' AND b.property_id = ".$property_id." AND a.jmb_status=1";
        //$this->CI->db->query($sql);
        $query = $this->CI->db->query($sql);        
        //    echo "<pre>".$this->CI->db->last_query()."</pre>";
        $result = $query->result_array();
        if(!empty($result)) {
            foreach ($result as $key=>$val) {
                $sql = "SELECT COUNT(1) AS cnt FROM bms_task_alert a WHERE a.user_id=".$val['member_id']." AND a.user_type=2";
                $query = $this->CI->db->query($sql);
                $res = $query->row_array();
                $this->sendPn (array($val['push_token']),$res['cnt'],$property_name,$task_name);
            }
        }
        
        // Owners   
        if(!empty($unit_id)) {     
            $sql = "SELECT push_token FROM bms_property_units a WHERE a.property_id = ".$property_id." AND a.push_token IS NOT NULL AND a.push_token <>'' AND a.unit_id=".$unit_id." AND a.unit_id NOT IN (SELECT b.unit_id FROM bms_jmb_mc b WHERE b.unit_id=".$unit_id.")";
            //$this->CI->db->query($sql);
            $query = $this->CI->db->query($sql);        
            //    echo "<pre>".$this->CI->db->last_query()."</pre>";
            $result = $query->result_array();
            if(!empty($result)) {
                $to_arr = array();
                foreach ($result as $key=>$val) {
                    //$to_arr[] =$val['push_token'];
                    array_push($to_arr,$val['push_token']);                   
                }
                if(!empty($to_arr)) {
                    $this->sendPn ($to_arr,'',$property_name,$task_name);
                }
                    
            }
        
        
            // Tenanr        
            $sql = "SELECT push_token FROM bms_property_unit_tenants a WHERE 1=1 AND (a.end_date ='0000-00-00' OR a.end_date >= '".date('Y-m-d')."') AND a.unit_id IN (SELECT b.unit_id FROM bms_property_units b WHERE b.unit_id=".$unit_id.")";
            $this->CI->db->query($sql);
            $query = $this->CI->db->query($sql);        
            //    echo "<pre>".$this->CI->db->last_query()."</pre>";
            $result = $query->result_array();
            if(!empty($result)) {
                $to_arr = array();
                foreach ($result as $key=>$val) {
                    array_push($to_arr,$val['push_token']);               
                }
                if(!empty($to_arr)) {
                    $this->sendPn ($to_arr,'',$property_name,$task_name);
                }
                    
            }
        }
        
    } 
    
    function sendPn ($registration_ids,$batch_count,$title,$body) {
        
        $url = 'https://fcm.googleapis.com/fcm/send';//'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'notification' => array('title' => $title,
                                    'body' => $body,
                                    'sound' => 'default',
                                    'badge' => $batch_count,
                                    'count' =>$batch_count),
            'data' => array('push_type' => 'Chat',
                                    'push_data' => array('FromId' => '1003',
                                                         'ToId' => '1024',
                                                         'FromType' => 'ME',
                                                         'ToType' => 'ME',
                                                         'MsgText' => 'How is this notification!',
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
    
    function get_todo_count($staff_id, $status_type = 0,$search_txt ='') {
        $cond = '';
        if($status_type != 'all') {
            $cond .= ' AND status='.$status_type; 
        }
        if($search_txt != '') {
            $cond .= " AND LOWER(description) LIKE '%".$search_txt."%'";
        }
        $sql = "SELECT COUNT(to_do_id) AS cnt FROM bms_staff_to_do  WHERE staff_id=".$staff_id.$cond;
        $query = $this->CI->db->query($sql);
        $result = $query->row_array();
        return $result['cnt'];
    }
    
    function get_todo_cont($staff_id,$offset = '0', $per_page = '25',$status_type = 0,$search_txt ='') {
        
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
            
            
        $cond = '';
        if($status_type != 'all') {
            $cond .= ' AND status='.$status_type; 
        }
        if($search_txt != '') {
            $cond .= " AND LOWER(description) LIKE '%".$search_txt."%'";
        }
        $sql = "SELECT to_do_id,description,complete_date,actual_complete_date,status FROM bms_staff_to_do  
                WHERE staff_id=".$staff_id.$cond." ORDER BY complete_date ASC ".$limit;
        $query = $this->CI->db->query($sql);
        return $query->result_array();        
    }
    
    function get_todo_details($todo_id) {        
        $sql = "SELECT to_do_id,description,complete_date,actual_complete_date,status FROM bms_staff_to_do  WHERE to_do_id=".$todo_id;
        $query = $this->CI->db->query($sql);
        return $query->row_array();        
    }
    
    function insert_todo ($data) {
        $this->CI->db->insert('bms_staff_to_do',$data);
    }
    
    function update_todo ($data,$todo_id) {
        $this->CI->db->update('bms_staff_to_do',$data,array('to_do_id' => $todo_id));
    }
    
}