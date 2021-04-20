<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_masters_model_v3_0 extends CI_Model {
    function __construct () { parent::__construct(); }

    function auth ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT staff_id,first_name,last_name,designation_id,email_addr,forgot_pass_key,mobile_no
                FROM bms_staff WHERE emp_type IN (1,2,3) AND email_addr=? AND password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function auth_jmb ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,b.member_id,a.contact_1
                FROM bms_property_units a, bms_jmb_mc b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1 AND status=1 AND jmb_status = 1
                AND a.email_addr=? AND a.password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function auth_owner ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,a.contact_1
                FROM bms_property_units a, bms_property c
                WHERE c.property_id=a.property_id AND c.property_status=1 AND a.email_addr=? AND a.password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function auth_resident ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.unit_id,b.unit_no,a.tenant_name,b.property_id,a.email_addr,a.forgot_pass_key,a.contact_1
                FROM bms_property_unit_tenants a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=b.property_id AND c.property_status=1
                AND a.email_addr=? AND a.password=?
                AND (a.end_date ='0000-00-00' OR a.end_date >= '".date('Y-m-d')."')";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function auth_vms_ma_users ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.unit_id,b.unit_no,a.ma_user_name,b.property_id,a.ma_user_email,a.ma_user_contact
                FROM bms_property_unit_ma_users a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1
                AND a.ma_user_email=? AND a.ma_user_pass=?
                ";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function set_push_token ($tbl_name,$data,$update_id) {
        $this->db->update($tbl_name, $data,$update_id);
    }

    function update_pass ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_staff SET last_password_change_date = '".date('Y-m-d')."', password=? WHERE emp_type IN (1,2,3) AND email_addr=?";
        return $query = $this->db->query($sql,array($pass,$username));
    }

    function update_pass_jmb ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_property_units SET password=? WHERE status=1 AND email_addr=?";
        return $query = $this->db->query($sql,array($pass,$username));
    }

    function update_pass_tenant ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_property_unit_tenants SET password=? WHERE email_addr=?";
        return $query = $this->db->query($sql,array($pass,$username));
    }

    function update_pass_vms_ma_user ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_property_unit_ma_users SET ma_user_pass=? WHERE ma_user_email=?";
        return $query = $this->db->query($sql,array($pass,$username));
    }

    function get_access_module ($staff_id) {
        $sql = "SELECT GROUP_CONCAT(module_id) AS module_id FROM bms_staff_module a
                WHERE staff_id=". $staff_id ." GROUP BY staff_id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function staffActive ($username) {
        $username = $this->db->escape_str($username);
        $sql = "SELECT staff_id,first_name,last_name,designation_id,email_addr,forgot_pass_key,mobile_no
                FROM bms_staff WHERE emp_type IN (1,2,3) AND email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function jmbActive ($username) {
        $username = $this->db->escape_str($username);

        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,b.member_id,a.contact_1
                FROM bms_property_units a, bms_jmb_mc b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1 AND status=1 AND jmb_status = 1
                AND a.email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function ownerActive ($username) {
        $username = $this->db->escape_str($username);
        $sql = "SELECT a.unit_id,unit_no,owner_name,a.property_id,a.email_addr,forgot_pass_key,a.contact_1
                FROM bms_property_units a, bms_property c
                WHERE c.property_id=a.property_id AND c.property_status=1 AND a.email_addr=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function residentActive ($username) {
        $username = $this->db->escape_str($username);
        $sql = "SELECT a.unit_id,b.unit_no,a.tenant_name,b.property_id,a.email_addr,a.forgot_pass_key,a.contact_1
                FROM bms_property_unit_tenants a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=b.property_id AND c.property_status=1
                AND a.email_addr=?
                AND (a.end_date ='0000-00-00' OR a.end_date >= '".date('Y-m-d')."')";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function vms_ma_usersActive ($username) {
        $username = $this->db->escape_str($username);
        $sql = "SELECT a.unit_id,b.unit_no,a.ma_user_name,b.property_id,a.ma_user_email,a.ma_user_contact
                FROM bms_property_unit_ma_users a, bms_property_units b, bms_property c
                WHERE b.unit_id = a.unit_id AND c.property_id=a.property_id AND c.property_status=1
                AND a.ma_user_email=?
                ";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function getCountries () {
        $query = $this->db->select('country_id,country_name')->order_by('country_name','ASC')->get('bms_countries'); //
        return $query->result_array();
    }

    function getProperties () {
        $query = $this->db->select('property_id,property_name,property_type')->where('property_status',1)->order_by('property_name','ASC')->get('bms_property');
        /*$sql = "SELECT PropertyId,PropertyName,PropertyType FROM propertysetup WHERE 1=1 ORDER BY PropertyName ASC";
        $query = $this->db->query($sql);*/
        return $query->result_array();
    }

    function getMyProperties ($staff_id ='') {
        $staff_id = $staff_id == '' ? $_SESSION['bms']['staff_id'] : $staff_id;
        $sql = "SELECT property_id,property_name,property_type,property_abbrev,jmb_mc_name,total_units,account_status,vms_status,vms_access
                FROM bms_property WHERE property_status=1 AND property_id IN
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function getJmbProperties ($property_id) {

        $sql = "SELECT property_id,property_name,property_type,total_units,account_status,vms_status,vms_access
                FROM bms_property WHERE property_status=1 AND property_id = '".$property_id."' ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPropertyInfo ($property_id) {

        $sql = "SELECT property_id,property_name,property_type,total_units,email_addr,monthly_billing,a.state_id,b.state_name,
                jmb_mc_name,address_1,address_2,phone_no,fax,pin_code,city
                FROM bms_property a
                LEFT JOIN bms_state b ON b.state_id=a.state_id
                WHERE property_status=1 AND property_id = '".$property_id."' ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function getPropertyMonthlyCollec($property_id) {

        $sql = "SELECT monthly_billing AS amt
                FROM bms_property WHERE property_status=1 AND property_id = '".$property_id."'";
        $query = $this->db->query($sql);
        $res = $query->row_array();
        return $res['amt'];
    }


    function getMyPropertiesWitTypeState ($search_txt = '') {

        $cond = $search_txt != '' ?  " AND LOWER(property_name) LIKE '%".strtolower($search_txt)."%' " : '';

        $sql = "SELECT property_id,property_name,total_units,email_addr,type_name,state_name
                FROM bms_property
                LEFT JOIN bms_property_type ON type_id=property_type
                LEFT JOIN bms_state ON bms_state.state_id = bms_property.state_id
                WHERE property_status=1 AND property_id IN
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$_SESSION['bms']['staff_id'].")
                ".$cond."
                ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getAllPropertiesWitTypeState ($search_txt = '') {

        $cond = $search_txt != '' ?  " AND LOWER(property_name) LIKE '%".strtolower($search_txt)."%' " : '';

        $sql = "SELECT property_id,property_name,total_units,email_addr,type_name,state_name,property_status
                FROM bms_property
                LEFT JOIN bms_property_type ON type_id=property_type
                LEFT JOIN bms_state ON bms_state.state_id = bms_property.state_id
                WHERE 1=1
                ".$cond."
                ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getMyPropertiesWithCharg ($staff_id ='') {
        $staff_id = $staff_id == '' ? $_SESSION['bms']['staff_id'] : $staff_id;
        $sql = "SELECT property_id,property_name,property_type,total_units,
                calcul_base,sinking_fund,tot_sq_feet,per_sq_feet,tot_share_unit,per_share_unit,insurance_prem,quit_rent
                FROM bms_property WHERE property_status=1 AND property_id IN
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") ORDER BY property_name ASC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function getBlocks ($property_id) {
        $query = $this->db->select('block_id,property_id,block_name')->order_by('block_name','ASC')->get_where('bms_property_block',array('property_id'=>$property_id));
        return $query->result_array();
    }

    function getBlock ($block_id) {
        $query = $this->db->select('block_id,property_id,block_name')->get_where('bms_property_block',array('block_id'=>$block_id));
        return $query->row();
    }

    function getUnit ($property_id,$block_id = 0) {
        $condi = '';
        if($block_id != 0)
            $condi = " AND block_id = '".$block_id."'";
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter,b.unit_status_name
                FROM bms_property_units
                LEFT JOIN bms_property_unit_status b ON unit_status = b.unit_status_id
                WHERE property_id = '".$property_id."' $condi
                ORDER BY unit_no";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }

    function getUnitDetails ($unit_id) {
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,email_addr,gender,contact_1,is_defaulter FROM bms_property_units WHERE unit_id = '".$unit_id."'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row();
    }

    function getAssignTo ($property_id) {
        $sql = "SELECT desi_id,desi_name FROM `bms_designation`
                WHERE desi_id IN (SELECT DISTINCT designation_id FROM bms_staff
                    WHERE emp_type IN (1,2,3) AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443) AND staff_id IN
                    (SELECT staff_id FROM `bms_staff_property` WHERE property_id=".$property_id."))
                     ORDER BY desi_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getDesignation ($desi_id = '') {
        $condi = $desi_id != '' ? " AND desi_id=".$desi_id : '';
        $sql = "SELECT desi_id,desi_name FROM `bms_designation` WHERE 1=1 $condi ORDER BY desi_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getEmpType () {
        $sql = "SELECT emp_type_id, emp_type_name FROM  bms_emp_type ORDER BY emp_type_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getModule () {
        $sql = "SELECT module_id, module_name FROM  bms_module ORDER BY module_order_by ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function getStaffNames ($desi_id = '') {
        $condi ='';
        if($desi_id && $desi_id != 'all')
            $condi = ' AND designation_id='.$desi_id;
        $sql = "SELECT staff_id,first_name,last_name  FROM `bms_staff` WHERE emp_type IN (1,2,3) ".$condi." ORDER BY first_name ASC,last_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getStaffNamesByProperty ($property_id = '',$staff_id = '') {
        $condi ='';
        $sql = "SELECT staff_id,first_name,last_name
                    FROM `bms_staff` WHERE emp_type IN (1,2,3)
                    AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443,1060,1039,1038,1218,1229,1273,1521,1522,1580,1582,1587)
                    AND staff_id IN(
                        SELECT staff_id from bms_staff_property WHERE property_id IN(
                        SELECT property_id from bms_staff_property WHERE staff_id =".$staff_id;

        if($property_id && $property_id != 'all') {
            $sql .=" AND property_id=".$property_id;
        }
        $sql .= ")) ORDER BY first_name ASC,last_name ASC";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getStaffNamesForSA ($property_id = '',$staff_id = '') {
        $condi ='';
        $sql = "SELECT staff_id,first_name,last_name
                    FROM `bms_staff` WHERE emp_type IN (1,2,3)
                    ";
        $sql .= " ORDER BY first_name ASC,last_name ASC";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getPropertyStaffs ($property_id,$exclude_desi_ids='') {
        $condi = $exclude_desi_ids != '' ? "AND designation_id NOT IN (".$exclude_desi_ids.")" : '';
        $sql = "SELECT staff_id,first_name,last_name,mobile_no,desi_name
                FROM `bms_staff`
                LEFT JOIN bms_designation ON desi_id=designation_id
                WHERE emp_type IN (1,2,3)
                ".$condi."
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443,1580,1582,1587)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY sort_order ASC,desi_name ASC,first_name ASC,last_name ASC";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getShiftPattern () {
        $sql = "SELECT shift_id , work_duty_description
                FROM bms_shift_pattern AS a
                ORDER BY work_duty_description";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_user_access_log ($offset,$rows,$staff_id,$desi_id,$rec_date) {

        $where = isset($staff_id) && $staff_id != 'all' ? 'bms_user_access_log.staff_id='.$staff_id : '1=1';
        $where .= isset($desi_id) && $desi_id != 'all' ? ' AND bms_staff.designation_id='.$desi_id : '';
        $where .= isset($rec_date) && $rec_date != '' ? " AND DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($rec_date))."'" : '';
        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')
                 ->get_where('bms_user_access_log',$where);
        $num_rows = $query->num_rows();

        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')
                 ->limit($rows,$offset)
                 ->order_by('accessed_date','DESC')
                 ->get_where('bms_user_access_log',$where);
        $data = $query->result_array();
        //echo "<pre>".$this->db->last_query();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_user_accessed_ip ($staff_id,$mat_date) {
        $where = isset($staff_id) && $staff_id != '' ? 'bms_user_access_log.staff_id='.$staff_id : '1=1';
        $where .= isset($mat_date) && $mat_date != '' ? " AND DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($mat_date))."'" : '';
        $query = $this->db->select('bms_user_access_log.staff_id,first_name,last_name,ip_address,latitude,longitude,accessed_module,accessed_method,accessed_date',FALSE)
                 ->join('bms_staff', 'bms_staff.staff_id = bms_user_access_log.staff_id', 'left')
                 ->order_by('accessed_date','DESC')
                 ->group_by('ip_address')
                 ->get_where('bms_user_access_log',$where);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }


    function get_user_access_log_mat ($offset,$rows,$mat_date) {

        $where = isset($mat_date) && $mat_date != '' ? " DATE_FORMAT(bms_user_access_log.accessed_date, '%Y-%m-%d')='".date("Y-m-d",strtotime($mat_date))."'" : '1=1';
        //$where .= isset($desi_id) && $desi_id != 'all' ? ' AND bms_staff.designation_id='.$desi_id : '';

        $sql = "SELECT tbl.*,COUNT(staff_id) FROM
                (SELECT `bms_user_access_log`.`staff_id`, COUNT(`bms_user_access_log`.`staff_id`) AS hit_cnt, `first_name`, `last_name`, designation_id,
                `latitude`, `longitude`, `ip_address`, `accessed_module`, `accessed_method`, DATE_FORMAT(accessed_date, '%Y-%m-%d')  AS a_date
                FROM `bms_user_access_log`
                LEFT JOIN `bms_staff`  ON `bms_staff`.`staff_id` = `bms_user_access_log`.`staff_id`
                WHERE  ".$where."
                GROUP BY staff_id,ip_address,a_date) as tbl group by staff_id HAVING COUNT(staff_id) > 1
                ";

        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $sql = "SELECT tbl.*,COUNT(staff_id) FROM
                (SELECT `bms_user_access_log`.`staff_id`, COUNT(`bms_user_access_log`.`staff_id`) AS hit_cnt, `first_name`, `last_name`, designation_id,
                `latitude`, `longitude`, `ip_address`, `accessed_module`, `accessed_method`, DATE_FORMAT(accessed_date, '%Y-%m-%d')  AS a_date
                FROM `bms_user_access_log`
                LEFT JOIN `bms_staff`  ON `bms_staff`.`staff_id` = `bms_user_access_log`.`staff_id`
                WHERE  ".$where."
                GROUP BY staff_id,ip_address, a_date
                ORDER BY `first_name`) as tbl group by staff_id HAVING COUNT(staff_id) > 1
                LIMIT ".$offset.",".$rows;

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }


    function getChatList ($user_type,$last_list_sync='',$staff_id='',$property_id='') {

        if($user_type == 'jmb') {
            $condi = " a.property_id=(SELECT property_id FROM bms_jmb_mc where member_id =".$staff_id.")";
        } else {
            $condi = " a.property_id IN (SELECT c.property_id FROM bms_staff_property c WHERE c.staff_id=$staff_id)";
        }
        $condi2 = $last_list_sync != '' ? " AND chat_date >= '$last_list_sync'" : '';
        $sql = "SELECT a.property_id, b.property_name,COUNT(*) AS chat_cnt FROM bms_chat a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE $condi $condi2
                GROUP BY property_id";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getChatMessages ($property_id,$last_sync='') {

        $condi = $last_sync != '' ? " AND chat_date >= '$last_sync'" : '';
        $sql = "SELECT chat_id,`property_id`,`user_id`,
                CASE user_type
	               WHEN 1 THEN (SELECT CONCAT(first_name, ' ',last_name) FROM bms_staff WHERE staff_id=a.user_id)
	               WHEN 2 THEN (SELECT owner_name FROM bms_property_units p, bms_jmb_mc q WHERE q.unit_id = p.unit_id AND q.member_id=a.user_id )
                END AS user_name,
                `chat_txt`,`file_name`,`chat_date`
                FROM bms_chat a
                WHERE property_id=$property_id $condi ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function set_chat ($data) {
        $this->db->insert('bms_chat', $data);
        return $insert_id = $this->db->insert_id();
    }

    function getAwardedStaffs ($award_year,$award_month) {
        $sql = "SELECT a.staff_id,first_name,last_name,desi_name,d.property_name,awarded_cat,
                jmb_percentage,am_percentage,hr_percentage,total_percentage,awarded
                FROM bms_staff_awarded a
                LEFT JOIN bms_staff b ON a.staff_id=b.staff_id
                LEFT JOIN bms_designation c ON c.desi_id=b.designation_id
                LEFT JOIN bms_property d ON d.property_id=a.property_id
                WHERE awarded=1 AND award_year=".$award_year." AND award_month=".$award_month."
                ORDER BY awarded_cat";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getShortListedStaffs ($award_cat,$award_year,$award_month) {
        $sql = "SELECT a.staff_id,first_name,last_name,desi_name,d.property_name,awarded_cat,
                jmb_percentage,am_percentage,hr_percentage,total_percentage,awarded
                FROM bms_staff_awarded a
                LEFT JOIN bms_staff b ON a.staff_id=b.staff_id
                LEFT JOIN bms_designation c ON c.desi_id=b.designation_id
                LEFT JOIN bms_property d ON d.property_id=a.property_id
                WHERE awarded=0 AND awarded_cat=".$award_cat." AND award_year=".$award_year." AND award_month=".$award_month."
                ORDER BY awarded_cat LIMIT 0,4";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getNoticeBoard () {
        $sql = "SELECT notice_id,message
                FROM bms_notice_board AS a";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    function getResiNoticeBoard ($property_id) {
        $sql = "SELECT resident_notice_id,property_id,notice_title,start_date,end_date,message,attachment_name
                FROM bms_resident_notice_board  WHERE property_id=".$property_id." AND '".date('Y-m-d')."' BETWEEN start_date AND end_date";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function getChargeCodes () {
        $query = $this->db->select('charge_code_id,charge_code')->get('bms_charge_code'); //->order_by('charge_code','ASC')
        return $query->result_array();
    }

    function getCommonDocs ($search_txt) {
        $cond = '';
        if($search_txt != '') {
            $search_txt = strtolower($this->db->escape_str($search_txt));
            $cond = " AND (LOWER(a.doc_name) LIKE '%".$search_txt."%' OR LOWER(a.doc_description) LIKE '%".$search_txt."%')";
        }
        $sql = "SELECT doc_id , doc_name, doc_description, doc_file_name
                FROM bms_doc_center AS a
                WHERE 1=1 $cond
                ORDER BY doc_name";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function common_docs_insert ($data) {
        $this->db->insert('bms_doc_center', $data);
    }

    function insertPrebookVisitor($data) {
        $result = $this->db->insert('bms_vms_prebook_visitors', $data);
        $insert_id = $this->db->insert_id();
        $return['result'] = $result;
        $return['insert_id'] = $insert_id;
        return $return;
    }

    /*function insertPrebookVisitor($data) {
        return $this->db->insert('bms_vms_prebook_visitors', $data);
    }*/

    function getPrebookVisitor($propertyID, $unitID) {
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('yesterday'));
        $yesterday = $date->format('Y-m-d 00:00:00');
        $today = date('Y-m-d 23:59:59');

      $sql = "SELECT *
              FROM bms_vms_prebook_visitors
              WHERE property_id=".$propertyID." AND unit_id=".$unitID . " AND booking_date between '$yesterday' AND '$today' ";
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function getFrequentVisitor($propertyID, $unitID) {
      $sql = "SELECT a.frequent_visitor_id,a.vehicle_no,a.verify_req,c.visitor_name
              FROM bms_vms_frequent_visitor a 
              LEFT JOIN bms_vms_visit_details b ON b.vehicle_no = a.vehicle_no
              LEFT JOIN bms_vms_visitor_master c ON b.visitor_id = c.visitor_id
              WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitID." AND a.verify_req=1";
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function insertFrequentVisitor($data) {
        $result = $this->db->insert('bms_vms_frequent_visitor', $data);
        $insert_id = $this->db->insert_id();
        $return['result'] = $result;
        $return['insert_id'] = $insert_id;
        return $return;
    }

    /*function insertFrequentVisitor($data) {
        return $this->db->insert('bms_vms_frequent_visitor', $data);
    }*/

    function deleteFrequentVisitor ($deleteVehicle) {
        foreach ($deleteVehicle as $key => $value) {
            $sql = "UPDATE bms_vms_frequent_visitor SET verify_req = 0 WHERE frequent_visitor_id = ".$value;
            $query = $this->db->query($sql);
        }
    return $query;
    }

    function insertNoteToGuard ($data) {
        $result = $this->db->insert('bms_vms_note_to_guard', $data);
        $insert_id = $this->db->insert_id();
        $return['result'] = $result;
        $return['insert_id'] = $insert_id;
        return $return;
    }

    /*function insertNoteToGuard ($data) {
        return $this->db->insert('bms_vms_note_to_guard', $data);
    }*/

    function insertPanicAlert ($data) {
        $result = $this->db->insert('bms_vms_panic_alert', $data);
        $insert_id = $this->db->insert_id();
        $return['result'] = $result;
        $return['insert_id'] = $insert_id;
        return $return;
    }

    /*function insertPanicAlert($data) {
        return $this->db->insert('bms_vms_panic_alert', $data);
    }*/

    function insertVisitorMaster($data) {
      return $this->db->insert('bms_vms_visitor_master', $data);
    }

    function insertVisitorDetails($data) {
      return $this->db->insert('bms_vms_visit_details', $data);
    }

    function getVisitorList($propertyID, $unitNo) {
/*        $sql = "SELECT *
              FROM bms_vms_visitor_master a LEFT JOIN bms_vms_visit_details b ON b.visitor_id = a.visitor_id
              WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo;*/

        $sql = "SELECT a.vehicle_no, a.visit_type, c.visitor_name, c.visitor_img, b.visit_status
              FROM bms_vms_prebook_visitors a 
              LEFT JOIN bms_vms_visit_details b ON a.vehicle_no = b.vehicle_no 
              LEFT JOIN bms_vms_visitor_master c ON b.visitor_id = c.visitor_id 
              WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getInvoiceList($propertyID, $unitID) {
      $sql = "SELECT a.*,b.property_name,b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,c.block_name,d.state_name,e.country_name,f.unit_no,f.owner_name,g.first_name,g.last_name FROM bms_fin_bills a
              LEFT JOIN bms_property b ON b.property_id = a.property_id
              LEFT JOIN bms_property_block c ON c.block_id = a.block_id
              LEFT JOIN bms_state d ON d.state_id = b.state_id
              LEFT JOIN bms_countries e ON e.country_id = b.country_id
              LEFT JOIN bms_property_units f ON f.unit_id = a.unit_id
              LEFT JOIN bms_staff g ON g.staff_id = a.created_by
              WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitID." AND a.bill_paid_status=0 ORDER BY a.bill_date DESC, a.bill_time DESC";
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function getInvoiceDetail($billID) {
      $sql = "SELECT * FROM bms_fin_bill_items
              WHERE bill_id=".$billID." AND paid_status=0";
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function getReceiptList($propertyID, $unitID) {
      $sql = "SELECT a.*,b.property_name,b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,c.block_name,d.state_name,e.country_name,f.unit_no,f.owner_name,g.first_name,g.last_name,h.pmode_name FROM bms_fin_receipt a
            LEFT JOIN bms_property b ON b.property_id = a.property_id
            LEFT JOIN bms_property_block c ON c.block_id = a.block_id
            LEFT JOIN bms_state d ON d.state_id = b.state_id
            LEFT JOIN bms_countries e ON e.country_id = b.country_id
            LEFT JOIN bms_property_units f ON f.unit_id = a.unit_id
            LEFT JOIN bms_staff g ON g.staff_id = a.created_by
            LEFT JOIN bms_fin_payment_mode h ON h.pmode_id = a.payment_mode
            WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitID." ORDER BY a.receipt_date DESC, a.receipt_time DESC";
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function getReceiptDetail($receiptID) {
      $sql = "SELECT * FROM bms_fin_receipt_items
              WHERE receipt_id=".$receiptID;
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function getPaymentChargeDetail($propertyID, $userType) {
      $sql = "SELECT payment_fpx, payment_cc_card, payment_merchant_id, payment_merchant_key_index FROM bms_property
              WHERE property_id=".$propertyID." AND payment_bear_by=".$userType ;
      $query = $this->db->query($sql);
      return $query->result_array();
    }

    function updateRefNo($refNo,$checkedBill){

      foreach ($checkedBill as $key => $value) {
        $sql = "UPDATE bms_fin_bill_items SET ref_number=".$refNo." WHERE bill_item_id = ".$value;
        $query = $this->db->query($sql);
      }

      return $query;

    }

    function insertReceipt($merchantID,$paymentID,$transID,$transDesc,$refNo,$keyIndex,$signature,$bankCode,$requestDatetime,$responseDatetime) {

      $sql = "SELECT * FROM bms_fin_bill_items WHERE ref_number=".$refNo;
      $query = $this->db->query($sql);
      $data = $query->result_array();

      $totalAmount = 0;

      for ($i=0; $i < $query->num_rows(); $i++) {
        $totalAmount += $data[$i]['bal_amount'];
      }

      $sql2 = "SELECT * FROM bms_fin_bills WHERE bill_id=".$data[0]['bill_id'];
      $query2 = $this->db->query($sql2);
      $data2 = $query2->result_array();

      $propertyID = $data2[0]['property_id'];
      $blockID = $data2[0]['block_id'];
      $unitID = $data2[0]['unit_id'];

      $sql3 = "SELECT property_abbrev FROM bms_property WHERE property_id=".$propertyID;
      $query3 = $this->db->query($sql3);
      $data3 = $query3->result_array();

      $propertyAbbr = $data3[0]['property_abbrev'];
      $receipt_no_format = ($propertyAbbr != '' ? $propertyAbbr : 'XX'). '/OR/'.date('y', strtotime($responseDatetime)).'/'.date('m', strtotime($responseDatetime)).'/';

      $sql4 = "SELECT receipt_no FROM bms_fin_receipt WHERE receipt_no LIKE '".$receipt_no_format."%' ORDER BY receipt_id DESC LIMIT 1";
      $query4 = $this->db->query($sql4);
      $data4 = $query4->row_array();

      if(!empty ($data4)) {
          $data4 = explode('/',$data4['receipt_no']);
          $receiptNo = $receipt_no_format . (end($data4) +1);
      } else {
          $receiptNo = $receipt_no_format . 1001;
      }

      $sql5 = "SELECT coa_id FROM bms_fin_coa WHERE payment_source=1 AND default_acc=1 AND property_id=".$propertyID;
      $query5 = $this->db->query($sql5);
      $data5 = $query5->result_array();

      $bankID = $data5[0]['coa_id'];

      $insertReceipt['property_id'] = $propertyID;
      $insertReceipt['block_id'] = $blockID;
      $insertReceipt['unit_id'] = $unitID;
      $insertReceipt['receipt_no'] = $receiptNo;
      $insertReceipt['receipt_date'] = date('Y-m-d');
      $insertReceipt['receipt_time'] = date('h:i:s');
      $insertReceipt['payment_mode'] = 4;
      $insertReceipt['bank_id'] = $bankID;
      $insertReceipt['bank'] = $bankCode;
      $insertReceipt['cheq_card_txn_no'] = $transID;
      $insertReceipt['cheq_txn_online_date'] = date('Y-m-d', strtotime($responseDatetime));
      $insertReceipt['online_r_card_type'] = $paymentID;
      $insertReceipt['remarks'] = $transDesc;
      $insertReceipt['paid_amount'] = $totalAmount;
      $insertReceipt['created_date'] = date('Y-m-d h:i:s');

      $this->db->insert('bms_fin_receipt', $insertReceipt);
      $receiptID = $this->db->insert_id();

      for ($i=0; $i < $query->num_rows(); $i++) {

        $billItemID = $data[$i]['bill_item_id'];
        $itemCatID = $data[$i]['item_cat_id'];
        $itemPeriod = $data[$i]['item_period'];
        $itemDesc = $data[$i]['item_descrip'];
        $paidAmount = $data[$i]['bal_amount'];

        $insertReceiptItem['receipt_id'] = $receiptID;
        $insertReceiptItem['item_cat_id'] = $itemCatID;
        $insertReceiptItem['item_period'] = $itemPeriod;
        $insertReceiptItem['item_descrip'] = $itemDesc;
        $insertReceiptItem['item_amount'] = $paidAmount;
        $insertReceiptItem['paid_amount'] = $paidAmount;
        $insertReceiptItem['bill_item_id'] = $billItemID;

        $this->db->insert('bms_fin_receipt_items', $insertReceiptItem);

        $sql6 = "UPDATE bms_fin_bill_items SET paid_amount='".$paidAmount."', bal_amount=0, paid_status=1 WHERE bill_item_id = ".$billItemID;
        $query6 = $this->db->query($sql6);

        $sql7 = "SELECT * FROM bms_fin_bill_items WHERE paid_status=0 AND bill_id=".$data[$i]['bill_id'];
        $query7 = $this->db->query($sql7);
        $data7 = $query7->result_array();

        if(count($data7)==0){
          $sql8 = "UPDATE bms_fin_bills SET bill_paid_status=1 WHERE bill_id=".$data[$i]['bill_id'];
          $query8 = $this->db->query($sql8);
        }

      }
      return true;
    }








    function getAllVisitorList($propertyID, $unitNo) {

        $sql = "SELECT b.visit_detail_id, b.visitor_id, c.visitor_name, a.vehicle_no, b.visit_type, 'Pre-booked' AS visit_category, b.visit_date AS time_in, b.exit_date AS time_out
        FROM bms_vms_prebook_visitors a 
        INNER JOIN bms_vms_visit_details b on b.vehicle_no = a.vehicle_no
        INNER JOIN bms_vms_visitor_master c on c.visitor_id = b.visitor_id  
        WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo . " 
        UNION ALL
        SELECT b.visit_detail_id, b.visitor_id, c.visitor_name, a.vehicle_no, b.visit_type, 'Frequent Visit' AS visit_category, b.visit_date AS time_in, b.exit_date AS time_out 
        FROM bms_vms_frequent_visitor a 
        LEFT JOIN bms_vms_visit_details b ON b.vehicle_no = a.vehicle_no
        LEFT JOIN bms_vms_visitor_master c ON b.visitor_id = c.visitor_id
        WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo . " AND a.verify_req = 1
        UNION ALL
        SELECT b.visit_detail_id, b.visitor_id, a.visitor_name, b.vehicle_no, b.visit_type, 'Ad-hoc Visit' AS visit_category, b.visit_date AS time_in, b.exit_date AS time_out 
        FROM bms_vms_visitor_master a 
        LEFT JOIN bms_vms_visit_details b ON a.visitor_id = b.visitor_id
        WHERE a.property_id = ".$propertyID." AND a.unit_id = ".$unitNo . " AND a.visitor_id NOT IN
                ( SELECT b.visitor_id
                FROM bms_vms_prebook_visitors a 
                INNER JOIN bms_vms_visit_details b on b.vehicle_no = a.vehicle_no
                INNER JOIN bms_vms_visitor_master c on c.visitor_id = b.visitor_id  
                WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo . "  
                UNION
                SELECT c.visitor_id
                FROM bms_vms_frequent_visitor a 
                LEFT JOIN bms_vms_visit_details b ON b.vehicle_no = a.vehicle_no
                LEFT JOIN bms_vms_visitor_master c ON b.visitor_id = c.visitor_id
                WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo . " AND a.verify_req = 1 AND b.visitor_id IS NOT NULL 
            ); ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPrebookVisitorList($propertyID, $unitNo) {
        $date = new DateTime();
        $date->add(DateInterval::createFromDateString('yesterday'));
        $yesterday = $date->format('Y-m-d 00:00:00');
        $today = date('Y-m-d 23:59:59');

        $sql = "SELECT a.prebook_visitor_id, a.vehicle_no, a.booking_date
        FROM bms_vms_prebook_visitors a 
        LEFT JOIN bms_vms_visit_details b on b.vehicle_no = a.vehicle_no  
        WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo . " AND a.booking_date between '$yesterday' AND '$today' ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getFrequentVisitorById ($frequent_visitor_id) {
        $sql = "SELECT frequent_visitor_id, property_id, unit_id, vehicle_no, verify_req, mobile_no, reg_date, flag, status, tstamp
        FROM bms_vms_frequent_visitor
        WHERE frequent_visitor_id = " . $frequent_visitor_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getFrequentVisitorByProperty ($property_id, $unit_id) {
        $sql = "SELECT frequent_visitor_id, property_id, unit_id, vehicle_no, verify_req, mobile_no, reg_date, flag, status, tstamp 
        FROM bms_vms_frequent_visitor 
        WHERE property_id = '" . $property_id . "' AND unit_id = '" . $unit_id . "'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updateFrequentVisitor ($frequent_visitor_id, $data) {
        $this->db->where('frequent_visitor_id', $frequent_visitor_id);
        return $this->db->update('bms_vms_frequent_visitor',$data);
    }

    function deleteFrequentVisitorById ($frequent_visitor_id) {
        $this->db->where('frequent_visitor_id', $frequent_visitor_id);
        return $this->db->delete('bms_vms_frequent_visitor');
    }





















    function getPrebookVisitorById ($prebook_visitor_id) {
        $sql = "SELECT prebook_visitor_id, property_id, unit_id, vehicle_no, visit_type, booking_date, mobile_no, flag, status, tstamp
        FROM bms_vms_prebook_visitors
        WHERE prebook_visitor_id = " . $prebook_visitor_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPrebookVisitorByProperty ($property_id, $unit_id) {
        $sql = "SELECT prebook_visitor_id, property_id, unit_id, vehicle_no, visit_type, booking_date, mobile_no, flag, status, tstamp
        FROM bms_vms_prebook_visitors
        WHERE property_id = " . $property_id . " AND unit_id = " . $unit_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updatePrebookVisitor ($prebook_visitor_id, $data) {
        $this->db->where('prebook_visitor_id', $prebook_visitor_id);
        return $this->db->update('bms_vms_prebook_visitors',$data);
    }

    function deletePrebookVisitor ($prebook_visitor_id) {
        $this->db->where('prebook_visitor_id', $prebook_visitor_id);
        return $this->db->delete('bms_vms_prebook_visitors');
    }









    function getPanicAlertById ($panic_alert_id) {
        $sql = "SELECT panic_alert_id, property_id, unit_id, mobile_no, received, response, alert_type, notes, flag, status, tstamp
        FROM bms_vms_panic_alert 
        WHERE panic_alert_id = " . $panic_alert_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPanicAlert ($property_id, $unit_id) {
        $sql = "SELECT panic_alert_id, property_id, unit_id, mobile_no, received, response, alert_type, notes, flag, status, tstamp
        FROM bms_vms_panic_alert
        WHERE property_id = " . $property_id . " AND unit_id = " . $unit_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updatePanicAlert($panic_alert_id, $data) {
        $this->db->where('panic_alert_id', $panic_alert_id);
        return $this->db->update('bms_vms_panic_alert',$data);
    }

    function deletePanicAlert($panic_alert_id) {
        $this->db->where('panic_alert_id', $panic_alert_id);
        return $this->db->delete('bms_vms_panic_alert');
    }















    function getNoteToGuardById ($note_id) {
        $sql = "SELECT note_id, property_id, unit_id, event_type, start, end, mobile_no, notes, flag, status, tstamp
        FROM bms_vms_note_to_guard
        WHERE note_id = " . $note_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getNoteToGuard ($property_id, $unit_id) {
        $sql = "SELECT note_id, property_id, unit_id, event_type, start, end, mobile_no, notes, flag, status, tstamp
        FROM bms_vms_note_to_guard
        WHERE property_id = " . $property_id . " AND unit_id = " . $unit_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updateNoteToGuard ($note_id, $data) {
        $this->db->where('note_id', $note_id);
        return $this->db->update('bms_vms_note_to_guard',$data);
    }

    function deleteNoteToGuard ($note_id) {
        $this->db->where('note_id', $note_id);
        return $this->db->delete('bms_vms_note_to_guard');
    }

    function getResidentUserInfo ($property_id, $unit_id) {
        $sql = "SELECT property_id, unit_id, ma_user_name, ma_user_contact
        FROM bms_property_unit_ma_users
        WHERE property_id = " . $property_id . " AND unit_id = " . $unit_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getUnitVisitorList ($propertyID, $unitNo) {
        $sql = "SELECT visitor_id, property_id, unit_id, visitor_name, gender, ic_no, visitor_addr, visitor_img, reg_date, flag, status, tstamp 
        FROM bms_vms_visitor_master a 
        WHERE a.property_id=".$propertyID." AND a.unit_id=".$unitNo;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getVisitorById ($visitor_id) {
        $sql = "SELECT visitor_id, property_id, unit_id, visitor_name, gender, ic_no, visitor_addr, visitor_img, reg_date, flag, status, tstamp 
        FROM bms_vms_visitor_master a 
        WHERE a.visitor_id=".$visitor_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function deleteVisitor($visitor_id) {
        $this->db->where('visitor_id', $visitor_id);
        return $this->db->delete('bms_vms_visitor_master');
    }

    function updateVisitor($visitor_id, $data) {
        $this->db->where('visitor_id', $visitor_id);
        return $this->db->update('bms_vms_visitor_master',$data);
    }

    function getVisiLogById ( $visit_detail_id ) {
        $sql = "SELECT visit_detail_id, visitor_id, vehicle_no, col_make_model, mobile_no, visit_type, visit_type, visit_date, visit_status, group_size, exit_date, notes, flag, status, tstamp
              FROM bms_vms_visit_details 
              WHERE visit_detail_id = ".$visit_detail_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getVisitLogByUnit ($property_id, $unit_id) {
        $sql = "SELECT visit_detail_id, visitor_id, vehicle_no, col_make_model, mobile_no, visit_type, visit_type, visit_date, visit_status, group_size, exit_date, notes, flag, status, tstamp
              FROM bms_vms_visit_details 
              WHERE property_id = ".$property_id .
            " AND unit_id = ".$unit_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getVisitLogByVisitor ($visitor_id) {
        $sql = "SELECT visit_detail_id, visitor_id, vehicle_no, col_make_model, mobile_no, visit_type, visit_type, visit_date, visit_status, group_size, exit_date, notes, flag, status, tstamp
              FROM bms_vms_visit_details 
              WHERE visitor_id = ".$visitor_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function deletePrebookVisitorByVehicleNo ( $vehicle_no ) {
        $this->db->select('prebook_visitor_id');
        $this->db->from('bms_vms_prebook_visitors');
        $this->db->where('vehicle_no', $vehicle_no);
        $query = $this->db->get();
        $ret = $query->row();
        $prebook_visitor_id = $ret->prebook_visitor_id;

        $this->db->where('vehicle_no', $vehicle_no);
        $this->db->delete('bms_vms_prebook_visitors');

        return $prebook_visitor_id;
    }

    function getFrequentVisitorIdFromVehicleNo ($vehicle_no) {
        $this->db->select('frequent_visitor_id');
        $this->db->from('bms_vms_frequent_visitor');
        $this->db->where('vehicle_no', $vehicle_no);
        $query = $this->db->get();
        $ret = $query->row();
        return $ret->frequent_visitor_id;
    }

    function deleteVisitLog($visit_detail_id) {
        $this->db->where('visit_detail_id', $visit_detail_id);
        return $this->db->delete('bms_vms_visit_details');
    }

    function updateVisitorDetails ($visit_detail_id, $data) {
        $this->db->where('visit_detail_id', $visit_detail_id);
        return $this->db->update('bms_vms_visit_details',$data);
    }

    function getButtonStatusByProperty ($property_id) {
        $sql = "SELECT mob_app_issue, mob_app_billing, mob_app_defect, mob_app_fasc_book, mob_app_pro_doc,
              mob_app_daily_rpt, mob_app_survey_form, mob_app_visit_list, mob_app_prebook, mob_app_freq_visit, 
              mob_app_note_to_guard, mob_app_panic_alert
              FROM bms_property 
              WHERE property_id = ".$property_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getFacilityDetail ($facility_id) {
        $sql = "SELECT property_id, facility_name, start_time, end_time, booking_slot, number_of_slots
              FROM bms_property_facility 
              WHERE facility_id = ".$facility_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function getSlotOccupied ($property_id, $facility_id, $booking_date) {
        $sql = "SELECT facility_booking_id, property_id, facility_id, booking_date, booking_slot
              FROM bms_property_facility_booking 
              WHERE property_id = '$property_id'  
              AND facility_id = '$facility_id' 
              AND booking_date = '$booking_date'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getFrequentVisitorDetail ($frequent_visitor_id) {
        $sql = "SELECT frequent_visitor_id, property_id, unit_id, vehicle_no, mobile_no, reg_date, flag, status, tstamp
              FROM bms_property_facility_booking 
              WHERE frequent_visitor_id = '$frequent_visitor_id'";

        $query = $this->db->query($sql);
        return $query->row_array();
    }


}