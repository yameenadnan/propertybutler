<?php defined('BASEPATH') or exit('No drect script access allowed');

Class Bms_agm_egm_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function agm_auth ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT agm_attendance_id,user_name,a.changed_pass,c.property_id
                FROM bms_agm_attendance a, bms_agm b, bms_property c              
                WHERE b.agm_id = a.agm_id AND  c.property_id = b.property_id AND a.user_name=? AND a.password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();         
    }
    
    function agm_check_pass ($username,$pass) {
        $username = $this->db->escape_str($username);
        $pass = $this->db->escape_str($pass);
        $sql = "SELECT a.changed_pass FROM bms_agm_attendance a WHERE a.user_name=? AND a.password=?";
        $query = $this->db->query($sql,array($username,$pass));
        //echo $this->db->last_query();exit;
        return $query->result_array();         
    }
    
    function check_agm_username_existence ($username) {        
        $username = $this->db->escape_str($username);
        $sql = "SELECT COUNT(1) AS cnt FROM bms_agm_attendance a WHERE a.user_name=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        $res = $query->row_array();
        return $res['cnt'];         
    }
    
    function check_agenda_pin_existence ($pin) {        
        //$username = $this->db->escape_str($username);
        $sql = "SELECT COUNT(1) AS cnt FROM bms_agm_agenda a WHERE a.pin=?";
        $query = $this->db->query($sql,array($pin));
        //echo $this->db->last_query();exit;
        $res = $query->row_array();
        return $res['cnt'];         
    }
    
    function agm_voter_by_username ($username) {
        $username = $this->db->escape_str($username);
        $sql = "SELECT mobile_no FROM bms_agm_attendance a WHERE a.user_name=?";
        $query = $this->db->query($sql,array($username));
        //echo $this->db->last_query();exit;
        return $query->result_array();         
    }

    function agm_voter_by_id ($id) {
        $username = $this->db->escape_str($id);
        $sql = "SELECT * FROM bms_agm_attendance a WHERE a.agm_attendance_id=?";
        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    function agm_update_vote_pass ($data,$username) {
        $this->db->update('bms_agm_attendance',$data,array('user_name'=>$username));
    }

    function agm_update_vote_pass_id ($data,$id) {
        $this->db->update('bms_agm_attendance',$data,array('agm_attendance_id'=>$id));
    }

    function agm_delete_voter_by_id ($id) {
        $this->db->delete('bms_agm_attendance',array('agm_attendance_id'=>$id));
        $this->db->delete('bms_agm_atten_eligi_mapp', array('agm_attendance_id'=>$id));
    }

    function agm_update_pass ($username,$pass) {        
        $pass = $this->db->escape_str($pass);
        $sql = "UPDATE bms_agm_attendance SET changed_pass =1, password=? WHERE user_name=?";
        return $query = $this->db->query($sql,array($pass,$username));                  
    } 
    
       
    function getAgmMasterChkList ($agm_type) {
        $query = $this->db->select('agm_master_id, agm_descrip, agm_responsibility')                      
                      ->get_where('bms_agm_master',array('agm_type'=>$agm_type));
        return $query->result_array();
    }
    
    function getAgmMasterChkListRemin ($agm_master_id) {
        $query = $this->db->select('agm_master_reminder_id,agm_master_id, remind_before, email_content,email_staff,email_jmb')                      
                      ->get_where('bms_agm_master_reminder',array('agm_master_id'=>$agm_master_id));
        return $query->result_array();
    }
    
    function getDesignations () {
        $query = $this->db->select('desi_id,desi_name')
                      ->order_by('desi_name')                      
                      ->get_where('bms_designation');
        return $query->result_array();
    }
    
    function getAgmList ($property_id,$agm_type) {
        $sql = "SELECT agm_id,a.property_id,b.property_name,agm_type,agm_last_date,agm_date
                FROM bms_agm a
                LEFT JOIN bms_property b ON b.property_id=a.property_id
                WHERE a.property_id = ".$property_id. ($agm_type !='' ? " AND agm_type=".$agm_type : "") ." ORDER BY agm_date DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getAgmMain ($agm_id) {
        $query = $this->db->select('property_id,agm_type,agm_number,agm_last_date,agm_date')                      
                      ->get_where('bms_agm',array('agm_id'=>$agm_id));
        return $query->row_array();
    }
    
    function getAgmChkList ($agm_id) {
        $query = $this->db->select('agm_checklist_id, agm_descrip, agm_responsibility')                      
                      ->get_where('bms_agm_checklist',array('agm_id'=>$agm_id));
        return $query->result_array();
    }
    function getAgmMainWithPropName ($agm_id) {
        $query = $this->db->select('a.property_id,b.property_name,agm_type,agm_last_date,agm_date') 
                      ->join('bms_property b','b.property_id=a.property_id','left')                     
                      ->get_where('bms_agm a',array('agm_id'=>$agm_id));
        return $query->row_array();
    }
    
    function getAgmChkListDesignation ($agm_id) {
        $query = $this->db->select('agm_checklist_id, agm_descrip, agm_responsibility,b.desi_name,agm_checklist_status,agm_checklist_remarks')  
                      ->join('bms_designation b','b.desi_id=agm_responsibility','left')                        
                      ->get_where('bms_agm_checklist',array('agm_id'=>$agm_id));
        return $query->result_array();
    }
    
    function getAgmChkListRemin ($agm_checklist_id) {
        $query = $this->db->select('agm_checklist_reminder_id,agm_checklist_id, remind_before, email_content,email_staff,email_jmb')                      
                      ->get_where('bms_agm_checklist_reminder',array('agm_checklist_id'=>$agm_checklist_id));
        return $query->result_array();
    }
    
    function getDeleteAgm ($agm_type,$ids) {        
        $sql = "SELECT agm_master_id FROM bms_agm_master WHERE agm_type=".$agm_type." AND agm_master_id NOT IN (".implode(',',$ids).")";
        $query = $this->db->query($sql);
        return $query->result_array();    
    }
    
    function deleteAgmReminder ($ids) {
        $this->db->where_in('agm_master_id', $ids);
        $this->db->delete('bms_agm_master_reminder');
    }  
    
    function deleteAgmMaster ($ids) {
        $this->db->where_in('agm_master_id', $ids);
        $this->db->delete('bms_agm_master');
    } 
    
    function deleteAgmReminderById ($master_id,$reminder_ids) {
        $sql = "DELETE FROM bms_agm_master_reminder WHERE agm_master_id=".$master_id." AND agm_master_reminder_id NOT IN (".implode(',',$reminder_ids).")";
        $this->db->query($sql);
    }
    
    function updateAgmMaster ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_agm_master', $data, array('agm_master_id' => $id));   
    }
    
    function insertAgmMaster ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_agm_master', $data);
        return $this->db->insert_id();   
    }
    
    function updateAgmReminder ($data,$id) {
        $this->db->update('bms_agm_master_reminder', $data, array('agm_master_reminder_id' => $id));   
    }
    
    function insertAgmReminder ($data) {
        $this->db->insert('bms_agm_master_reminder', $data);   
    }
    
    function insertAgm ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_agm', $data);
        return $this->db->insert_id();   
    }
    
    function updateAgm ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_agm', $data, array('agm_id' => $id));   
    }
    
    function updateNoOfComm ($data,$id) {        
        $this->db->update('bms_agm', $data, array('agm_id' => $id));   
    }
    
    function getDeleteAgmChecklist ($agm_id,$ids) {        
        $sql = "SELECT agm_checklist_id FROM bms_agm_checklist WHERE agm_id=".$agm_id." AND agm_checklist_id NOT IN (".implode(',',$ids).")";
        $query = $this->db->query($sql);
        return $query->result_array();    
    }
    
    function deleteAgmChecklistReminder ($ids) {
        $this->db->where_in('agm_checklist_id', $ids);
        $this->db->delete('bms_agm_checklist_reminder');
    }  
    
    function deleteAgmChecklist ($ids) {
        $this->db->where_in('agm_checklist_id', $ids);
        $this->db->delete('bms_agm_checklist');
    }
    
    function deleteAgmChklistReminderById ($master_id,$reminder_ids) {
        $sql = "DELETE FROM bms_agm_checklist_reminder WHERE agm_checklist_id=".$master_id." AND agm_checklist_reminder_id NOT IN (".implode(',',$reminder_ids).")";
        $this->db->query($sql);
    }
    
    function updateAgmChecklist ($data,$id) {        
        $this->db->update('bms_agm_checklist', $data, array('agm_checklist_id' => $id));   
    }
    
    function insertAgmCheckList ($data) {        
        $this->db->insert('bms_agm_checklist', $data);
        return $this->db->insert_id();   
    }
    
    function deleteAgmChecklistReminderById ($master_id,$reminder_ids) {
        $sql = "DELETE FROM bms_agm_checklist_reminder WHERE agm_checklist_id=".$master_id." AND agm_checklist_reminder_id NOT IN (".implode(',',$reminder_ids).")";
        $this->db->query($sql);
    }
    
    function updateAgmChecklistReminder ($data,$id) {
        $this->db->update('bms_agm_checklist_reminder', $data, array('agm_checklist_reminder_id' => $id));   
    }
    
    function insertAgmChecklistReminder ($data) {
        $this->db->insert('bms_agm_checklist_reminder', $data);   
    }
    
    function update_checklist_status ($data,$id) {
        $data['status_updated_by'] = $_SESSION['bms']['staff_id'];
        $data['status_updated_date'] = date("Y-m-d");
        $this->db->update('bms_agm_checklist', $data, array('agm_checklist_id' => $id));   
    }
    
    function get_units ($property_id,$agm_id,$offset,$rows,$search_txt = '') {
        $limit = ' LIMIT '.$offset.','.$rows;
        $cond = '';
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.unit_no) LIKE '%".$search_txt."%' OR LOWER(a.owner_name) LIKE '%".$search_txt."%')";
        }
         
        $sql = "SELECT a.unit_id,a.property_id,a.block_id,block_name,unit_status_name AS unit_status,floor_no,
                a.unit_no,a.ic_passport_no,a.unit_type,a.share_unit,a.no_of_owners,
                a.owner_name,e.eli_voter_id,e.unit_id AS eligible_unit_id,e.proxy_required,e.proxy_name,e.proxy_ic_no
                FROM bms_property_units a                   
                LEFT JOIN bms_property_block c ON c.block_id = a.block_id
                LEFT JOIN bms_property_unit_status d ON d.unit_status_id = a.unit_status
                LEFT JOIN bms_agm_eligible_voters e ON (e.unit_id = a.unit_id AND agm_id=".$agm_id.")
                WHERE status=1 AND a.property_id=". $property_id  .$cond;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        
        $order_by = " ORDER BY a.unit_no ASC";
        
        $query = $this->db->query($sql.$order_by);
        $data['num_rows'] = $query->num_rows();
        
        $query = $this->db->query($sql.$order_by.$limit);
        //echo "<br />".$this->db->last_query();
        $data['units'] = $query->result_array();
        return $data;
    }
    
    function get_eligible_voters_report ($agm_id) {
        $sql = "SELECT eli_voter_id,a.unit_id,a.unit_no,unit_type,share_unit,a.owner_name,a.ic_no,
                proxy_required,proxy_name,proxy_ic_no 
                FROM bms_agm_eligible_voters a
                WHERE agm_id=". $agm_id;        
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query();
        return $query->result_array();
    }        
    
    function get_attendance_report ($agm_id) {
         
        $sql = "SELECT a.agm_attendance_id,image_name,d.agm_id,d.agm_date,d.created_date,user_name,mobile_no,
                (SELECT group_concat(CONCAT(x.unit_no, ' - ', x.owner_name, IF(proxy_name IS NULL or proxy_name = '', '', concat(' - (Proxy) ',proxy_name))), ',') FROM bms_agm_eligible_voters x  WHERE x.eli_voter_id IN (SELECT y.eli_voter_id FROM bms_agm_atten_eligi_mapp y WHERE y.agm_attendance_id = a.agm_attendance_id)) AS unit_nos
                FROM `bms_agm_attendance` a                              
                LEFT JOIN bms_agm d ON d.agm_id=a.agm_id                
                WHERE a.agm_id=".$agm_id ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        
        $order_by = " ORDER BY atten_date DESC,atten_time DESC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_username_print ($user_name) {
         
        $sql = "SELECT image_name,d.agm_id,d.agm_date,d.created_date,user_name,mobile_no,
                (SELECT GROUP_CONCAT(CONCAT(x.unit_no , ' - Share Units: ',x.share_unit,IF(proxy_required <>0, ' (Proxy)','')) SEPARATOR ';<br />') FROM bms_agm_eligible_voters x  WHERE x.eli_voter_id IN (SELECT y.eli_voter_id FROM bms_agm_atten_eligi_mapp y WHERE y.agm_attendance_id = a.agm_attendance_id)) AS unit_nos
                FROM `bms_agm_attendance` a                              
                LEFT JOIN bms_agm d ON d.agm_id=a.agm_id                
                WHERE a.user_name='".$user_name."'";//LEFT JOIN bms_property b ON b.property_id = a.property_id
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function deleteEligibleVoters ($agm_id,$unit_id) {
        $this->db->where ('agm_id',$agm_id);
        $this->db->where_in('unit_id',$unit_id);
        $this->db->delete('bms_agm_eligible_voters');
        //echo "<br />".$this->db->last_query();exit;
    }
    
    function get_agms ($property_id) {        
        $sql = "SELECT agm_id,a.agm_term,vote_by,no_of_committee FROM bms_agm a
                WHERE property_id=". $property_id;
        $order_by = " ORDER BY agm_term ASC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_agm_details ($agm_id) {        
        $sql = "SELECT agm_id,agm_type,a.agm_term,agm_number,agm_date,vote_by,no_of_committee,property_name 
                FROM bms_agm a
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                WHERE agm_id=". $agm_id;
        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function updateEligibleVoters ($data,$id) {        
        $this->db->update('bms_agm_eligible_voters', $data, array('eli_voter_id' => $id));   
    }
    
    function insertEligibleVoters ($data) {        
        $this->db->insert('bms_agm_eligible_voters', $data);
    }
    
    function get_agm_for_attendance ($property_id) {
        
        $sql = "SELECT agm_id,a.agm_term,created_date FROM bms_agm a
                WHERE property_id=". $property_id." AND agm_date='".date('Y-m-d')."'";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_eligible_voters ($agm_id) {
        $sql = "SELECT eli_voter_id,a.unit_id,a.unit_no,a.owner_name,a.ic_no,proxy_required,proxy_name,proxy_ic_no 
                FROM bms_agm_eligible_voters a
                WHERE agm_id=". $agm_id." 
                AND eli_voter_id NOT IN (SELECT eli_voter_id FROM bms_agm_atten_eligi_mapp WHERE agm_id=". $agm_id.")";        
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_attendees ($agm_id) {
        $sql = "SELECT a.eli_voter_id,a.unit_id,a.unit_no,a.owner_name,a.proxy_required,a.proxy_name,a.proxy_ic_no 
                FROM bms_agm_eligible_voters a 
                WHERE a.agm_id=". $agm_id."  AND 
                a.eli_voter_id IN (SELECT n.eli_voter_id  FROM bms_agm_atten_eligi_mapp n WHERE n.agm_id = ". $agm_id." )";
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query();
        return $query->result_array();
    }
    function get_attendees_new ($agm_id) {
        $sql = "SELECT a.eli_voter_id,a.unit_id,a.unit_no,a.owner_name,a.proxy_required,a.proxy_name,a.proxy_ic_no 
                FROM bms_agm_eligible_voters a 
                WHERE a.agm_id=". $agm_id."  AND 
                a.eli_voter_id IN (SELECT n.eli_voter_id  FROM bms_agm_eligible_voters n WHERE n.agm_id = ". $agm_id." )";
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query();
        return $query->result_array();
    }


    function get_eli_mc_nomin ($agm_id) {
        $sql = "SELECT a.eli_voter_id,a.unit_id,a.unit_no,a.owner_name,a.proxy_required,a.proxy_name,a.proxy_ic_no 
                FROM bms_agm_eligible_voters a 
                WHERE a.agm_id=". $agm_id."  AND 
                a.eli_voter_id IN (SELECT n.eli_voter_id  FROM bms_agm_atten_eligi_mapp n WHERE n.agm_id = ". $agm_id." )";
        /** Commented due to avoid the proxy checking condition issue(single owner shouldn't be a proxy, muliptle owner & company should have proxy)
         * $sql = "SELECT a.eli_voter_id,a.unit_id,a.unit_no,a.owner_name,a.proxy_required,a.proxy_name,a.proxy_ic_no
                FROM bms_agm_eligible_voters a
                WHERE a.agm_id=". $agm_id."  AND
                ((unit_type = 1 AND no_of_owners <= 1 AND proxy_required <> 1) OR (unit_type = 1 AND no_of_owners > 1 AND proxy_required = 1) OR (unit_type = 2 AND proxy_required = 1))
                AND a.eli_voter_id IN (SELECT n.eli_voter_id  FROM bms_agm_atten_eligi_mapp n WHERE n.agm_id = ". $agm_id." )";*/
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_elibible_voters_cnt ($agm_id) {
        $sql = "SELECT COUNT(1) as cnt  FROM bms_agm_eligible_voters a WHERE agm_id=". $agm_id;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_attendees_cnt ($agm_id) {
        $sql = "SELECT COUNT(1) as cnt FROM bms_agm_attendance a WHERE agm_id=". $agm_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    /** For attendance
    
    function get_eligible_voters_for_attendance ($agm_id) {
        $sql = "SELECT eli_voter_id,a.unit_id,b.unit_no,a.owner_name,proxy_required,proxy_name,proxy_ic_no 
                FROM bms_agm_eligible_voters a
                LEFT JOIN bms_property_units b ON b.unit_id = a.unit_id 
                WHERE agm_id=". $agm_id ." 
                AND eli_voter_id NOT IN (SELECT eli_voter_id FROM bms_agm_attendance WHERE agm_id=".$agm_id.")
                ORDER BY unit_no ASC";        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }*/
    
    function set_agm_attendance ($data) {
        $this->db->insert('bms_agm_attendance', $data);
        return $this->db->insert_id();
    }
    
    function set_agm_atten_eligi_mapp ($data) {
        $this->db->insert_batch('bms_agm_atten_eligi_mapp', $data);        
    }
    
    
    function getAgenda ($agm_id,$agenda_id='') {
        $cond = !empty($agenda_id) ? ' AND agm_agenda_id='. $agenda_id  : '';
        $sql = "SELECT agm_agenda_id,agm_id,agenda_resol,seq_no,re_vote,resolu_type,minutes,pin,agenda_status,start_time,end_time
                FROM bms_agm_agenda WHERE agm_id =".$agm_id.$cond."  ORDER BY seq_no,re_vote";
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    /*function getAgendaAfterAgenda ($agm_id,$re_vote) {
        $sql = "SELECT agm_agenda_id,agm_id,agenda_resol,seq_no,resolu_type
                FROM bms_agm_agenda WHERE agm_id =".$agm_id." 
                AND seq_no >= (SELECT seq_no FROM bms_agm_agenda WHERE agm_id =".$agm_id." AND agm_agenda_id=".$re_vote.")
                ORDER BY seq_no";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }*/
    
    function getAgendaForRevote ($re_vote) {
        $sql = "SELECT agm_agenda_id,agm_id,agenda_resol,seq_no,resolu_type,re_vote
                FROM bms_agm_agenda WHERE agm_agenda_id=".$re_vote;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function unsetAgenda ($ids,$agm_id) {
        $this->db->where ('agm_id',$agm_id);
        if(!empty($ids))
            $this->db->where_not_in('agm_agenda_id',$ids);
        $this->db->delete('bms_agm_agenda');
    }
    
    function insertAgenda ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_agm_agenda', $data);
    }
    
    function updateAgenda ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_agm_agenda', $data, array('agm_agenda_id' => $id));   
    }
    
    function set_vote_by ($id,$vote_by) {
        $this->db->update('bms_agm', array('vote_by'=>$vote_by), array('agm_id' => $id));   
    }
    
    function insert_pre_chair ($data) {
        $this->db->insert('bms_agm_preside_chair', $data);        
    }
    
    function update_pre_chair ($data,$id) {
        $this->db->update('bms_agm_preside_chair', $data,array('pc_id'=>$id));        
    }
    
    function insert_proposer_seconder ($data) {
        $this->db->insert('bms_agm_proposer_seconder', $data);  
        //echo "<br />".$this->db->last_query();      
    }
    
    function update_proposer_seconder ($data,$id) {
        $this->db->update('bms_agm_proposer_seconder', $data,array('proposer_seconder_id'=>$id));        
    }
    
    function get_agenda_by_pin ($pin) {
        $sql = "SELECT agm_agenda_id,a.agm_id,agenda_resol,seq_no,resolu_type,minutes,pin,b.no_of_committee
                FROM bms_agm_agenda a
                LEFT JOIN bms_agm b ON b.agm_id = a.agm_id 
                WHERE pin =".$pin;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_agenda_by_id ($id) {
        $sql = "SELECT agm_agenda_id,agm_id,agenda_resol,seq_no,resolu_type,pin
                FROM bms_agm_agenda WHERE agm_agenda_id =".$id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_pc_nominees ($agenda_id) {
        $sql = "SELECT a.pc_id, a.nominee,b.unit_no AS nom_unit_no,b.owner_name AS nom_owner_name,b.proxy_required, b.proxy_name                
                FROM bms_agm_preside_chair a
                INNER JOIN bms_agm_eligible_voters b ON b.eli_voter_id = a.nominee
                WHERE a.agenda_id=".$agenda_id." AND a.nominee <>0";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_mc_nominees ($agenda_id) {
        $sql = "SELECT a.mc_nomin_id, a.nominee,b.unit_no AS nom_unit_no,b.owner_name AS nom_owner_name,b.proxy_required, b.proxy_name                
                FROM bms_agm_mc_nomination a
                INNER JOIN bms_agm_eligible_voters b ON b.eli_voter_id = a.nominee                
                WHERE a.agenda_id=".$agenda_id." AND a.nominee <>0";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function setMcVote ($data) {
        $sql = "INSERT INTO bms_agm_mc_vote (agenda_id,mc_nomin_id,agm_attendance_id,unit_id,dt)
                SELECT ".$data['agenda_id'].",".$data['mc_nomin_id'].",".$data['agm_attendance_id'].",a.eli_voter_id,'".date('Y-m-d H:i:s')."' 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b 
                WHERE b.eli_voter_id=a.eli_voter_id AND b.agm_attendance_id=".$data['agm_attendance_id']."";
        $this->db->query($sql);
        //$this->db->insert('bms_agm_mc_vote', $data);      
    }
    
    function get_mc_result ($agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(d.share_unit)" : "COUNT(a.mc_nomin_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,c.eli_voter_id AS unit_id,c.unit_no AS nom_unit_no,c.owner_name AS nom_owner_name,c.owner_name AS nom_owner_name,c.proxy_required, c.proxy_name 
                FROM bms_agm_mc_vote a
                LEFT JOIN bms_agm_mc_nomination b ON b.mc_nomin_id = a.mc_nomin_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = b.nominee
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.unit_id    
                WHERE a.agenda_id=".$agenda_id." GROUP BY a.mc_nomin_id ORDER BY vote_cnt DESC";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    } 
    
    function get_mc_result_details ($unit_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "c.share_unit" : "a.mc_nomin_id"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,c.eli_voter_id AS unit_id,c.unit_no AS nom_unit_no,c.owner_name AS nom_owner_name,c.proxy_required, c.proxy_name 
                FROM bms_agm_mc_vote a
                LEFT JOIN bms_agm_mc_nomination b ON b.mc_nomin_id = a.mc_nomin_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id                
                WHERE a.agenda_id=".$agenda_id."  AND b.nominee=".$unit_id;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_mc_abstains ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(a.share_unit)" : "COUNT(a.unit_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT DISTINCT unit_id FROM bms_agm_mc_vote where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_mc_abstains_details ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = "a.share_unit AS vote_cnt,a.unit_no,a.owner_name,a.proxy_required, a.proxy_name";
        $sql = "SELECT ".$sub_sql."  
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT DISTINCT unit_id FROM bms_agm_mc_vote where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        return $query->result_array();
    }        
    
    function get_no_of_comm_nomination ($agenda_id) {
        $sql = "SELECT a.propose_id, a.item                
                FROM bms_agm_deci_resol_nomin a                             
                WHERE a.agenda_id=".$agenda_id." AND a.item <>'0'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_full_nominees ($agenda_id) {
        $sql = "SELECT pc_id,nominee,proposer,seconder
                FROM bms_agm_preside_chair a               
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    } 
      
    function get_full_nominees_details ($agenda_id) {
        $sql = "SELECT pc_id,a.nominee,b.owner_name as nominee_name,c.owner_name as proposer, d.owner_name as seconder
                FROM bms_agm_preside_chair a
                LEFT JOIN bms_agm_eligible_voters b ON b.eli_voter_id = a.nominee
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.proposer
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.seconder
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function setPcVote ($data) {
        $sql = "INSERT INTO bms_agm_pc_vote (agenda_id,pc_id,agm_attendance_id,unit_id,dt)
                SELECT ".$data['agenda_id'].",".$data['pc_id'].",".$data['agm_attendance_id'].",a.eli_voter_id,'".date('Y-m-d H:i:s')."' 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b 
                WHERE b.eli_voter_id=a.eli_voter_id AND b.agm_attendance_id=".$data['agm_attendance_id']."";
        $this->db->query($sql);
        //$this->db->insert('bms_agm_pc_vote', $data);      
    }
    
    function setVoteResol ($data) {
        $sql = "INSERT INTO bms_agm_vote_resol (agenda_id,vote_for,agm_attendance_id,unit_id,dt)
                SELECT ".$data['agenda_id'].",".$data['vote_for'].",".$data['agm_attendance_id'].",a.eli_voter_id 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b 
                WHERE b.eli_voter_id=a.eli_voter_id AND b.agm_attendance_id=".$data['agm_attendance_id']."";
        $this->db->query($sql);
        //$this->db->insert('bms_agm_vote_resol', $data);      
    }
        
    function get_pc_result ($agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(d.share_unit)" : "COUNT(a.pc_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,c.eli_voter_id AS unit_id,c.unit_no AS nom_unit_no,c.owner_name AS nom_owner_name,c.proxy_required, c.proxy_name 
                FROM bms_agm_pc_vote a
                LEFT JOIN bms_agm_preside_chair b ON b.pc_id = a.pc_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = b.nominee                    
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.unit_id
                WHERE a.agenda_id=".$agenda_id." GROUP BY a.pc_id ORDER BY vote_cnt DESC";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    } 
    
    function get_pc_result_details ($unit_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "c.share_unit" : "a.pc_id"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,c.eli_voter_id AS unit_id,c.unit_no AS nom_unit_no,c.owner_name AS nom_owner_name,c.proxy_required, c.proxy_name 
                FROM bms_agm_pc_vote a
                LEFT JOIN bms_agm_preside_chair b ON b.pc_id = a.pc_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id    
                WHERE a.agenda_id=".$agenda_id." AND b.nominee =".$unit_id;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_pc_abstains ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(a.share_unit)" : "COUNT(a.unit_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM `bms_agm_pc_vote` where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function get_pc_abstains_details ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = "a.share_unit AS vote_cnt, a.unit_no,a.owner_name,a.proxy_required, a.proxy_name"; 
        $sql = "SELECT ".$sub_sql."  
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM `bms_agm_pc_vote` where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    } 
    
    function get_vote_resol_result ($agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(c.share_unit)" : "COUNT(a.vote_for)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,a.vote_for 
                FROM bms_agm_vote_resol a   
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id                 
                WHERE a.agenda_id=".$agenda_id." GROUP BY a.vote_for";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    
    
    function get_vote_resol_result_details ($vote_for,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "c.share_unit" : "a.vote_id"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt,a.vote_for,c.unit_no,c.owner_name,c.proxy_required, c.proxy_name
                FROM bms_agm_vote_resol a   
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id                 
                WHERE a.agenda_id=".$agenda_id." AND a.vote_for=".$vote_for;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_vote_resol_abstains ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(a.share_unit)" : "COUNT(a.unit_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM bms_agm_vote_resol  where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        
        return $query->row_array();
    }
    
    function get_vote_resol_abstains_details ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = "a.share_unit AS vote_cnt,a.unit_no,a.owner_name,a.proxy_required, a.proxy_name";
        $sql = "SELECT ".$sub_sql." 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM bms_agm_vote_resol  where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        
        return $query->result_array();
    }
    
    function get_ps_result ($agenda_id) {
        $sql = "SELECT proposer_seconder_id,proposer,seconder,opposer 
                FROM bms_agm_proposer_seconder a
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    } 
    function get_ps_result_details ($agenda_id) {
        $sql = "SELECT proposer_seconder_id,c.owener_name as proposer,d.owner_name as seconder,opposer 
                FROM bms_agm_proposer_seconder a
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = b.proposer
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.seconder     
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    } 
    
    function get_no_of_comm_full_nomination ($agenda_id) {
        $sql = "SELECT propose_id,item,proposer,seconder
                FROM bms_agm_deci_resol_nomin a
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_no_of_comm_full_nomination_details ($agenda_id) {
        $sql = "SELECT propose_id,item,c.owner_name as proposer, d.owner_name as seconder
                FROM bms_agm_deci_resol_nomin a
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.proposer
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.seconder
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function setNoOfCommVote ($data) {
        $sql = "INSERT INTO bms_agm_deci_resol_vote (agenda_id,propose_id,agm_attendance_id,unit_id)
                SELECT ".$data['agenda_id'].",".$data['propose_id'].",".$data['agm_attendance_id'].",a.eli_voter_id 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b 
                WHERE b.eli_voter_id=a.eli_voter_id AND b.agm_attendance_id=".$data['agm_attendance_id']."";
        $this->db->query($sql);
        //$this->db->insert('bms_agm_deci_resol_vote', $data);      
    }
    
    function get_no_of_comm_result ($agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(c.share_unit)" : "COUNT(a.propose_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt, b.item
                FROM bms_agm_deci_resol_vote a
                LEFT JOIN bms_agm_deci_resol_nomin b ON b.propose_id = a.propose_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id                
                WHERE a.agenda_id=".$agenda_id." GROUP BY a.propose_id ORDER BY vote_cnt DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_no_of_comm_result_details ($item,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "c.share_unit" : "a.propose_id"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt, b.item, c.unit_no,c.owner_name,c.proxy_required, c.proxy_name
                FROM bms_agm_deci_resol_vote a
                LEFT JOIN bms_agm_deci_resol_nomin b ON b.propose_id = a.propose_id
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.unit_id                
                WHERE a.agenda_id=".$agenda_id." AND b.item='".$item."'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_no_of_comm_abstains ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = $vote_by == 2 ? "SUM(a.share_unit)" : "COUNT(a.unit_id)"; 
        $sql = "SELECT ".$sub_sql." AS vote_cnt
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM `bms_agm_deci_resol_vote` where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        return $query->row_array();
    } 
    
    function get_no_of_comm_abstains_details ($agm_id,$agenda_id,$vote_by = '') {
        $sub_sql = "a.share_unit AS vote_cnt,a.unit_no,a.owner_name,a.proxy_required, a.proxy_name"; 
        $sql = "SELECT ".$sub_sql." 
                FROM bms_agm_eligible_voters a,bms_agm_atten_eligi_mapp b  
                WHERE a.agm_id = ".$agm_id." AND b.eli_voter_id = a.eli_voter_id AND 
                a.eli_voter_id not in (SELECT unit_id FROM `bms_agm_deci_resol_vote` where agenda_id =".$agenda_id.")";
        $query = $this->db->query($sql);
        return $query->result_array();
    }  
    
    function insert_no_of_committe ($data) {
        $this->db->insert('bms_agm_deci_resol_nomin', $data);
    }
    
    function update_no_of_committe ($data,$id) {
        $this->db->update('bms_agm_deci_resol_nomin', $data,array('propose_id'=>$id));        
    }
    
    function get_mc_full_nominees ($agenda_id) {
        $sql = "SELECT mc_nomin_id,nominee,proposer,seconder
                FROM bms_agm_mc_nomination a
                WHERE a.agenda_id=".$agenda_id;

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function get_mc_full_nominees_details ($agenda_id) {
        $sql = "SELECT mc_nomin_id,a.nominee,b.owner_name as nominee_name,c.owner_name as proposer, d.owner_name as seconder
                FROM bms_agm_mc_nomination a
                LEFT JOIN bms_agm_eligible_voters b ON b.eli_voter_id = a.nominee
                LEFT JOIN bms_agm_eligible_voters c ON c.eli_voter_id = a.proposer
                LEFT JOIN bms_agm_eligible_voters d ON d.eli_voter_id = a.seconder
                WHERE a.agenda_id=".$agenda_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function insert_mc_nomin ($data) {
        $this->db->insert('bms_agm_mc_nomination', $data);
    }
    
    function update_mc_nomin ($data,$id) {
        $this->db->update('bms_agm_mc_nomination', $data,array('mc_nomin_id'=>$id));        
    }  
    
    /////////////////////////////////////////// JEFF /////////////////////////////////////////////////////

    function check_available_voting ($property_id, $now) {
      $sql = "SELECT * FROM bms_agm WHERE property_id = ? AND agm_date = ?";
      $query = $this->db->query($sql,array($property_id,$now));
      //echo "<pre>".$this->db->last_query()."</pre>";
      return $query->result_array();
    }

    function check_eligible_voters ($unitID, $agmID) {
      $sql = "SELECT * FROM bms_agm_eligible_voters WHERE unit_id = ? AND agm_id = ?";
      $query = $this->db->query($sql,array($unitID,$agmID));
      return $query->result_array();
    }

    function check_pin ($pinNo, $agmID) {
      $sql = "SELECT * FROM bms_agm_agenda WHERE pin = ? AND agm_id = ?";
      $query = $this->db->query($sql,array($pinNo,$agmID));
      return $query->result_array();
    }

    function check_pin_agenda ($pinNo, $agendaID) {
      $sql = "SELECT * FROM bms_agm_agenda WHERE pin = ? AND agm_agenda_id = ?";
      $query = $this->db->query($sql,array($pinNo,$agendaID));
      return $query->result_array();
    }
    
    function check_agenda_vote_active ($agenda_id) {
        $sql = "SELECT COUNT(pin) as cnt FROM bms_agm_agenda WHERE agm_agenda_id = ? AND pin IS NOT NULL AND pin<>''";
        $query = $this->db->query($sql,array($agenda_id));
        $result = $query->row_array();
        return $result['cnt'];
    }

    function getPCVote ($agendaID) {
      $sql = "SELECT * FROM bms_agm_preside_chair a 
              LEFT JOIN bms_property_units b ON a.nominee = b.unit_id 
              WHERE a.agenda_id = ? AND a.nominee != 0";
      $query = $this->db->query($sql,array($agendaID));
      return $query->result_array();
    }

    function getNoCommittee ($agendaID) {
      $sql = "SELECT * FROM bms_agm_deci_resol_nomin WHERE agenda_id = ? AND item != '0'";
      $query = $this->db->query($sql,array($agendaID));
      return $query->result_array();
    }

    function getMCNomination ($agendaID) {
      $sql = "SELECT * FROM bms_agm_mc_nomination a LEFT JOIN bms_property_units b ON a.nominee = b.unit_id WHERE a.agenda_id = ? AND a.nominee != 0";
      $query = $this->db->query($sql,array($agendaID));
      return $query->result_array();
    }

    function insertPCVote($data) {
      return $this->db->insert('bms_agm_pc_vote', $data);
    }

    function checkPCVote ($agendaID, $unitID) {
      $sql = "SELECT * FROM bms_agm_pc_vote WHERE agenda_id = ? AND agm_attendance_id = ?";
      $query = $this->db->query($sql,array($agendaID,$unitID));
      return $query->result_array();
    }

    function insertResolVote($data) {
      return $this->db->insert('bms_agm_vote_resol', $data);
    }

    function checkResolVote ($agendaID, $unitID) {
      $sql = "SELECT * FROM bms_agm_vote_resol WHERE agenda_id = ? AND agm_attendance_id = ?";
      $query = $this->db->query($sql,array($agendaID,$unitID));
      return $query->result_array();
    }

    function insertNoCommVote($data) {
      return $this->db->insert('bms_agm_deci_resol_vote', $data);
    }

    function checkNoCommVote ($agendaID, $unitID) {
      $sql = "SELECT * FROM bms_agm_deci_resol_vote WHERE agenda_id = ? AND agm_attendance_id = ?";
      $query = $this->db->query($sql,array($agendaID,$unitID));
      return $query->result_array();
    }

    function insertMCVote($data) {
      return $this->db->insert('bms_agm_mc_vote', $data);
    }

    function checkMCVote ($agendaID, $unitID) {
      $sql = "SELECT * FROM bms_agm_mc_vote WHERE agenda_id = ? AND agm_attendance_id = ?";
      $query = $this->db->query($sql,array($agendaID,$unitID));
      return $query->result_array();
    }

    function get_bms_agm_agenda_details ( $agm_agenda_id ){
        $sql = "SELECT * FROM bms_agm_agenda WHERE agm_agenda_id = '$agm_agenda_id'";
        $query = $this->db->query($sql);
        return $result = $query->row();
    }

    function update_bms_agm_agenda_details ( $agm_agenda_id, $agenda_resol ) {
        $sql = "UPDATE bms_agm_agenda SET agenda_resol = '$agenda_resol' WHERE agm_agenda_id = '$agm_agenda_id'";
        $query = $this->db->query($sql);
    }

    function get_agenda_id_from_agm_attendance_id ( $agm_attendance_id ) {
        $sql = "select a.agm_id, b.agenda_resol, b.agm_agenda_id, b.resolu_type, a.agm_attendance_id FROM bms_agm_attendance a 
        LEFT JOIN bms_agm_agenda b on a.agm_id = b.agm_id 
        WHERE a.agm_attendance_id = '$agm_attendance_id' AND b.agenda_status = '1' 
        ORDER BY b.seq_no, b.resolu_type";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_resolu_type_1_result ( $agm_agenda_id, $agm_attendance_id ) {
        $sql = "SELECT c.owner_name, c.proxy_required, c.proxy_name, c.proxy_name, c.unit_no 
        FROM bms_agm_pc_vote a
        LEFT JOIN bms_agm_preside_chair b ON a.pc_id = b.pc_id
        LEFT JOIN bms_agm_eligible_voters c ON b.nominee = c.eli_voter_id
        WHERE a.agm_attendance_id = '$agm_attendance_id'
        AND a.agenda_id = '$agm_agenda_id' limit 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_resolu_type_2_5_result ( $agm_agenda_id, $agm_attendance_id ) {
        $sql = "SELECT a.agenda_id, a.vote_for, a.agm_attendance_id FROM bms_agm_vote_resol a
        WHERE a.agm_attendance_id = '$agm_attendance_id'
        AND a.agenda_id = '$agm_agenda_id' limit 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_resolu_type_7_result ( $agm_agenda_id, $agm_attendance_id ) {
        $sql = "SELECT b.item FROM bms_agm_deci_resol_vote a 
        LEFT JOIN bms_agm_deci_resol_nomin b ON a.propose_id = b.propose_id
        WHERE a.agm_attendance_id = '$agm_attendance_id'
        AND a.agenda_id = '$agm_agenda_id' limit 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_resolu_type_8_result ( $agm_agenda_id, $agm_attendance_id ) {
        $sql = "SELECT DISTINCT c.unit_no, c.proxy_required, c.proxy_name, c.owner_name 
        FROM bms_agm_mc_vote a 
        LEFT JOIN bms_agm_mc_nomination b ON a.mc_nomin_id = b.mc_nomin_id
        LEFT JOIN bms_agm_eligible_voters c ON b.nominee = c.eli_voter_id
        WHERE a.agm_attendance_id = '$agm_attendance_id'
        AND a.agenda_id = '$agm_agenda_id'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_agenda_resol_from_agm_agenda_id ( $agm_agenda_id ) {
        $sql = "SELECT a.agenda_resol FROM bms_agm_agenda a
        WHERE a.agm_agenda_id = '$agm_agenda_id'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result->agenda_resol;
    }




/////////////////////////////////////////// JEFF /////////////////////////////////////////////////////
 
}