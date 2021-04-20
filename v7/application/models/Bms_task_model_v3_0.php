<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_task_model_v3_0 extends CI_Model {
    function __construct () { parent::__construct(); }

    function task_insert ($data) {
        $this->db->insert('bms_task', $data);
        return $insert_id = $this->db->insert_id();//1316;//
        //return $query->result_array();
    }

    function task_image_name_insert ($data) {
        $this->db->insert('bms_task_img', $data);
    }

    function get_task ($type = '', $desi_id ='', $staff_id = '', $offset = '', $per_page = '',$property_id='',$task_status='',$task_id='',$sear_txt = '',$sort_by='due_date') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        $cond = $type == '' || $type == 'own' ? '=' : '<>';
        $cond2 = ' AND b.property_status=1';
        if($property_id != '' && $property_id != 'al')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
        if($task_status != '' && $task_status != 'al')
            $cond2 .= ' AND a.task_status=\''.$task_status.'\' ';
        if($task_id != '')
            $cond2 .= ' AND a.task_id=\''.ltrim($task_id,'0').'\' ';

        if($sear_txt != '')
            $cond2 .= ' AND a.task_name LIKE \'%'.$sear_txt.'%\' ';

        $sort_by = ($sort_by == 'due_date' ? ' due_date ASC ' :  ' created_date DESC '). ', task_id DESC ';

        $num_rows = '';
        if ($limit != '') {
            $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE a.assign_to". $cond . $desi_id ." ".$cond2."
                AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.")";

            $query = $this->db->query($sql);
            $num_rows = $query->num_rows();
        }
        $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE a.assign_to". $cond . $desi_id ." ".$cond2."
                AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") ORDER BY  ".$sort_by . $limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_task_with_num_rows ($type = '', $desi_id ='', $staff_id = '', $offset = '', $per_page = '',$property_id='',$task_status='',$task_id='',$sear_txt = '',$sort_by='due_date') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        $cond = $type == '' || $type == 'own' ? '=' : '<>';
        $cond2 = ' AND b.property_status=1';
        if($property_id != '' && $property_id != 'al')
            $cond2 .= ' AND a.property_id='.$property_id.' ';
        if($task_status != '' && $task_status != 'al')
            $cond2 .= ' AND a.task_status=\''.$task_status.'\' ';

        if($task_id != '')
            $cond2 .= ' AND a.task_id=\''.ltrim($task_id,'0').'\' ';

        if($sear_txt != '')
           $cond2 .= ' AND a.task_name LIKE \'%'.$sear_txt.'%\' ';

        $sort_by = ($sort_by == 'due_date' ? ' due_date ASC ' :  ' created_date DESC '). ', task_id DESC ';

        if ($limit != '') {
            $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                    FROM bms_task a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id
                    WHERE a.assign_to". $cond . $desi_id." ".$cond2." AND
                    a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.")";
            $query = $this->db->query($sql);
            $num_rows = $query->num_rows();
        }

        $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE a.assign_to". $cond . $desi_id ." ".$cond2." AND
                a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.")
                ORDER BY  ".$sort_by . $limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_task_with_num_rows_jmb ( $property_id, $task_status='', $offset = '', $per_page = '',$task_id='',$sear_txt = '',$sort_by='due_date', $unit_id = "") {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond2 = '';

        if($unit_id != '')
            $cond2 .= ' AND a.unit_id=\''.$unit_id.'\' ';

        if($task_status != '' && $task_status != 'al')
            $cond2 .= ' AND a.task_status=\''.$task_status.'\' ';

        if($task_id != '')
            $cond2 .= ' AND a.task_id=\''.ltrim($task_id,'0').'\' ';

        if($sear_txt != '')
            $cond2 .= ' AND a.task_name LIKE \'%'.$sear_txt.'%\' ';

        $sort_by = ($sort_by == 'due_date' ? ' due_date ASC ' :  ' created_date DESC '). ', task_id DESC ';

        if ($limit != '') {
            $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                    FROM bms_task a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id
                    WHERE a.property_id = ".$property_id.$cond2;
            $query = $this->db->query($sql);
            $num_rows = $query->num_rows();
        }

        $sql = "SELECT task_id,property_name,task_name,due_date,a.created_date,task_status
                    FROM bms_task a
                    LEFT JOIN bms_property b ON b.property_id = a.property_id
                    WHERE a.property_id = ".$property_id.$cond2." ORDER BY ".$sort_by.$limit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        if(empty($num_rows)) $num_rows = $query->num_rows();
        return array('numFound'=>$num_rows,'records'=>$data);//$query->result_array();
    }

    function get_task_details ($task_id,$staff_id) {

        $sql = "SELECT task_id,task_name,due_date,a.created_date,task_status,task_location,task_details,task_category,task_source,task_update,assign_to,
                a.property_id,b.property_name,c.desi_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by,first_name,last_name, task_close_remarks, g.unit_status_name
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_staff f ON f.`staff_id` = a.created_by
                LEFT JOIN bms_property_unit_status g ON g.unit_status_id = d.unit_status
                WHERE a.task_id=". $task_id." AND
                a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.")
                ";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row();
    }

    function get_task_details_jmb_mc ($task_id,$property_id) {

        $sql = "SELECT task_id,task_name,due_date,a.created_date,task_status,task_location,task_details,task_category,task_source,task_update,assign_to,
                a.property_id,b.property_name,c.desi_name, d.unit_no, d.owner_name, d.email_addr, d.contact_1, d.unit_status, d.block_id, e.block_name,
                a.created_by,first_name,last_name, task_close_remarks, g.unit_status_name
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_designation c ON c.desi_id = a.assign_to
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                LEFT JOIN bms_property_block e ON e.block_id = d.block_id
                LEFT JOIN bms_staff f ON f.`staff_id` = a.created_by
                LEFT JOIN bms_property_unit_status g ON g.unit_status_id = d.unit_status
                WHERE a.task_id=". $task_id." AND
                a.property_id =".$property_id;

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row();
    }

    function get_task_images ($task_id) {
        $query = $this->db->select('id,task_id,img_name')->get_where('bms_task_img',array('task_id'=>$task_id));
        return $query->result_array();
    }

    function get_task_details_for_email ($task_id) {

        $sql = "SELECT task_id,task_name,task_location,task_details,b.property_name, b.email_addr, d.owner_name, d.email_addr, d.gender
                FROM bms_task a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units d ON d.unit_id = a.unit_id
                WHERE a.task_id=". $task_id;

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->row_array();
    }

    function set_task_update_with_log ($task_id,$data,$staff_id) {
        $this->db->update('bms_task', $data, array('task_id' => $task_id));
        if(isset($data['task_status']) && $data['task_status'] == 'C') {
            $log_data['log_remarks'] = 'updated as Task is Closed';
        } else {
            $task_update = $this->config->item('task_update');
            $log_data['log_remarks'] = $task_update[$data['task_update']];
        }
        $log_data['task_id'] = $task_id;
        $log_data['log_date'] = date("Y-m-d H:i:s");
        $log_data['log_by'] = $staff_id;
        if(!empty($data['due_date']))
            $log_data['log_due_date'] = $data['due_date'];

        return $this->db->insert('bms_task_log', $log_data);
    }

    function set_task_create_log ($task_id, $staff_id,$due_date) {

        $log_data['log_remarks'] = 'Task is created';
        $log_data['task_id'] = $task_id;
        $log_data['log_date'] = date("Y-m-d H:i:s");
        $log_data['log_by'] = $staff_id;
        $log_data['log_due_date'] = $due_date;

        return $this->db->insert('bms_task_log', $log_data);
    }

    function set_task_forum ($task_id,$chat_text,$img_name,$staff_id) {

        /*$log_data['task_id'] = $comment_data['task_id'] = $task_id;
        $log_data['log_date'] = date("Y-m-d H:i:s");
        $log_data['log_by'] = $_SESSION['bms']['staff_id'];
        $log_data['log_remarks'] = $chat_text;
        $this->db->insert('bms_task_log', $log_data);*/

        $comment_data['task_id'] = $task_id;
        $comment_data['comment_date'] = date("Y-m-d H:i:s");
        $comment_data['comment_by'] = $staff_id;
        $comment_data['comment'] = $chat_text;
        $comment_data['img_name'] = $img_name;
        return $this->db->insert('bms_task_forum', $comment_data);

    }

    function getTaskLog($task_id) {
        /*$query = $this->db->select('log_date,log_by,log_remarks,first_name,last_name')
                 ->join('bms_staff', 'bms_staff.staff_id = bms_task_log.log_by', 'left')
                 ->get_where('bms_task_log',array('task_id'=>$task_id));*/
        $sql = "SELECT a.task_id, a.entry_date, a.staff_id, a.description,a.due_date, a.img_name, b.first_name,b.last_name
                FROM
                (SELECT task_id,log_date AS entry_date,log_by AS staff_id,log_remarks AS description, log_due_date AS due_date, 'task_update' AS img_name
                FROM bms_task_log WHERE task_id = $task_id UNION
                SELECT task_id,comment_date AS entry_date,comment_by AS staff_id,`comment` AS description,'' AS due_date, img_name AS img_name
                FROM bms_task_forum WHERE task_id = $task_id) a
                LEFT JOIN bms_staff b ON b.staff_id = a.staff_id
                ORDER BY entry_date";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }



    function getTaskForum($task_id,$type = '') {
        /*$query = $this->db->select('task_id,comment_date,comment_by,comment,img_name,first_name,last_name')
                 ->join('bms_staff', 'bms_staff.staff_id = bms_task_forum.comment_by', 'left')
                 ->get_where('bms_task_forum',array('task_id'=>$task_id));*/
        $sql = "SELECT a.task_id, a.entry_date AS comment_date, a.staff_id AS comment_by, a.description AS comment,a.due_date, a.img_name, b.first_name,b.last_name
                FROM
                (SELECT task_id,log_date AS entry_date,log_by AS staff_id,log_remarks AS description, log_due_date AS due_date, '' AS img_name
                FROM bms_task_log WHERE task_id = $task_id UNION
                SELECT task_id,comment_date AS entry_date,comment_by AS staff_id,`comment` AS description,'' AS due_date, img_name AS img_name
                FROM bms_task_forum WHERE task_id = $task_id) a
                LEFT JOIN bms_staff b ON b.staff_id = a.staff_id
                ORDER BY entry_date";
        $query = $this->db->query($sql);
        return $type == 'cnt' ? $query->num_rows() : $query->result_array();
    }

    function task_alert_insert ($task_id,$property_id,$alert_info) {
        $data = array ();
        $staff_sql = $member_sql = '';
        /*if($_SESSION['bms']['user_type'] == 'staff') {
            $data['user_type'] = 1;
            $data['user_id'] = $_SESSION['bms']['staff_id'];
            //$staff_sql = ' AND staff_id <>'.$_SESSION['bms']['staff_id'];
        } else {
            $data['user_type'] = 2;
            $data['user_id'] = $_SESSION['bms']['member_id'];
            //$member_sql = ' AND member_id <>'.$_SESSION['bms']['member_id'];
        }*/
        $data['created_date'] = date("Y-m-d H:i:s");
        //$this->db->insert('bms_task_alert', $data);
        $sql = "INSERT INTO bms_task_alert (task_id, alert_info, user_id, user_type, created_date ) ";

        $sub_sql = "SELECT '".$task_id."', '".$alert_info."', staff_id, '1', '".$data['created_date']."' FROM bms_staff_property where property_id = ".$property_id .$staff_sql. " AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)";
        $query = $this->db->query($sql.$sub_sql);
        //echo "<pre>".$this->db->last_query();

        $sub_sql = "SELECT '".$task_id."', '".$alert_info."', member_id, '2', '".$data['created_date']."' FROM bms_jmb_mc where property_id = ".$property_id . $member_sql;
        $query = $this->db->query($sql.$sub_sql);
        //echo "<pre>".$this->db->last_query();
    }

    function task_alert_delete ($task_id,$user_type,$user_id) {
        $data = array ();
        $staff_sql = $member_sql = '';
        if($user_type == 'staff') {
            $data['user_type'] = 1;
            $data['user_id'] = $user_id;
            //$staff_sql = ' AND staff_id <>'.$_SESSION['bms']['staff_id'];
        } else {
            $data['user_type'] = 2;
            $data['user_id'] = $user_id;
            //$member_sql = ' AND member_id <>'.$_SESSION['bms']['member_id'];
        }
        //$this->db->insert('bms_task_alert', $data);
        $sql = "DELETE FROM bms_task_alert WHERE user_id='".$data['user_id']."' AND task_id='".$task_id."'";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
    }
    // For dashboard contents
    function getMinorTaskCount ($property_id,$task_cat='O') {
        $date_filed = $task_cat == 'O' ? 'created_date' : 'updated_date';

        $sql = "SELECT COUNT(task_id) AS cnt
                FROM bms_task a
                WHERE task_status='".$task_cat."' AND a.property_id=".$property_id."
                AND ".$date_filed." IS NOT NULL";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        $res = $query->row_array();
        return $res['cnt'];
    }

    function getMinorTaskCountForOwner ($property_id,$unit_id,$task_cat='O') {
        $date_filed = $task_cat == 'O' ? 'created_date' : 'updated_date';

        $sql = "SELECT COUNT(task_id) AS cnt
                FROM bms_task a
                WHERE task_status='".$task_cat."' AND a.property_id=".$property_id." AND a.unit_id=".$unit_id."
                AND ".$date_filed." IS NOT NULL";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        $res = $query->row_array();
        return $res['cnt'];
    }   

    function getMinorTaskCountForChart ($property_id,$md,$type,$task_cat='O') {
        $date_filed = $task_cat == 'O' ? 'created_date' : 'updated_date';
        $date_format = $type == 'monthly' ? '%Y-%m' : '%Y';
        $sql = "SELECT DATE_FORMAT(".$date_filed.",'".$date_format."') AS md, COUNT(1) AS cnt
                FROM bms_task a
                WHERE task_status='".$task_cat."' AND a.property_id=".$property_id."
                AND ".$date_filed." IS NOT NULL
                AND DATE_FORMAT(".$date_filed.",'".$date_format."') IN ('".$md."')
                GROUP BY md
                ORDER BY md ASC";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getMinorTaskCountForDailyChart ($property_id,$start_date,$end_date,$task_cat='O') {
        $date_filed = $task_cat == 'O' ? 'created_date' : 'updated_date';
        $date_format = '%d';
        $sql = "SELECT DATE_FORMAT(".$date_filed.",'".$date_format."') AS md, COUNT(1) AS cnt
                FROM bms_task a
                WHERE task_status='".$task_cat."' AND a.property_id=".$property_id."
                AND ".$date_filed." IS NOT NULL
                AND ".$date_filed." BETWEEN '".$start_date."' AND '".$end_date."'
                GROUP BY md
                ORDER BY md ASC";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getOverDueTaskForDaysInter ($property_id,$i) {
        $inter_condi = '';
        switch ($i) {
            case 1:
                $inter_condi = "AND due_date < '".date("Y-m-d H:i:s")."' AND due_date >='".date("Y-m-d H:i:s")."' - INTERVAL 7 DAY"; break;
            case 2:
                $inter_condi = "AND due_date < '".date("Y-m-d H:i:s")."' - INTERVAL 7 DAY AND due_date >= '".date("Y-m-d H:i:s")."' - INTERVAL 14 DAY"; break;
            case 3:
                $inter_condi = "AND due_date < '".date("Y-m-d H:i:s")."' - INTERVAL 14 DAY AND due_date >= '".date("Y-m-d H:i:s")."' - INTERVAL 21 DAY"; break;
            case 4:
                $inter_condi = "AND due_date < '".date("Y-m-d H:i:s")."' - INTERVAL 21 DAY AND due_date >= '".date("Y-m-d H:i:s")."' - INTERVAL 28 DAY"; break;
            case 5:
                $inter_condi = "AND due_date < '".date("Y-m-d H:i:s")."' - INTERVAL 28 DAY"; break;

        }
        $sql = "SELECT COUNT(1) as cnt FROM bms_task WHERE property_id=$property_id AND task_status='O' ".$inter_condi;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        $res = $query->row_array();
        return $res['cnt'];
    }

    function getCollection ($property_id,$collection_type,$start_date,$end_date) {
        $sql = "SELECT SUM(requirement_val) AS amt
                FROM bms_sop_entry AS a
                WHERE a.sop_id=(SELECT sop_id FROM bms_sop WHERE property_id=$property_id AND sop_name LIKE '%$collection_type')
                AND (DATE_FORMAT(a.entry_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' )
                ORDER BY a.id DESC";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        $res = $query->row_array();
        return $res['amt'];
    }

}
