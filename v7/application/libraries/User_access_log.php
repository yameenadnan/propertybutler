<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_access_log {
    
    function __construct() { $this->CI = & get_instance(); } // get the CodeIgniter object
    
    function user_access_log_insert () {
        /*$ip = $this->CI->input->ip_address(); 
        if($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' && $_SESSION['bms']['staff_id'] != 1273) {
            $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. $ip); 
            $json_arr = json_decode($json);
            //echo '<b>'. $ip .'</b> resolves to:' ;
            
            $data['staff_id'] = $_SESSION['bms']['staff_id'];
            $data['ip_address'] = $json_arr->geobytesipaddress;
            $data['remoteip'] = $json_arr->geobytesremoteip;
            $data['latitude'] = $json_arr->geobyteslatitude;        
            $data['longitude'] = $json_arr->geobyteslongitude;
            $data['accessed_module'] = $this->CI->uri->segment(1);
            $data['accessed_method'] = $this->CI->uri->segment(2);
            $data['accessed_date'] = date('Y-m-d H:i:s');
            $data['regionlocationcode'] = $json_arr->geobytesregionlocationcode;
            $data['region'] = $json_arr->geobytesregion;
            $data['city'] = $json_arr->geobytescity;
            $data['code'] = $json_arr->geobytescode;
            $data['fqcn'] = $json_arr->geobytesfqcn;
            $this->CI->db->insert('bms_user_access_log', $data);  
        }  */
        //echo "<pre>";print_r($data); echo "</pre>"; exit;
    } 
    
}