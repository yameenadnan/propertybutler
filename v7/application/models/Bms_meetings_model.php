<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_meetings_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function get_meetings_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.meeting_descrip) LIKE '%".$search_txt."%' OR LOWER(a.meeting_venue) LIKE '%".$search_txt."%' OR LOWER(a.agenda_to_discuss) LIKE '%".$search_txt."%')";
        }        
        
        $sql = "SELECT meeting_id,a.property_id,meeting_descrip,meeting_venue, DATE_FORMAT(meeting_date,'%d-%m-%Y') AS meeting_date, TIME_FORMAT(meeting_time,'%h:%i %p') AS meeting_time
                FROM bms_meetings a
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY meeting_date ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    function get_meeting_details ($meeting_id) {
        $sql = "SELECT meeting_id,a.property_id,meeting_descrip,meeting_venue, meeting_date, meeting_time, agenda_to_discuss,
                (SELECT GROUP_CONCAT(minor_task_id) FROM bms_meetings_minor_task WHERE meeting_id=".$meeting_id.") AS discuss_task_id,
                (SELECT GROUP_CONCAT(user_id) FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=1) AS staff_attende,
                (SELECT GROUP_CONCAT(user_id) FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=2) AS jmb_attende                                
                FROM bms_meetings a
                WHERE meeting_id= ". $meeting_id ." AND a.property_id IN (SELECT property_id FROM bms_staff_property WHERE staff_id = ".$_SESSION['bms']['staff_id'].")";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function getPropertyStaffs ($property_id) {
        $sql = "SELECT staff_id,first_name,last_name,email_addr,b.desi_name
                FROM bms_staff a
                LEFT JOIN bms_designation b ON b.desi_id = a.designation_id              
                WHERE emp_type IN (1,2,3)
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyStaffsForEdit ($property_id,$meeting_id) {
        $sql = "SELECT staff_id,first_name,last_name,email_addr,b.desi_name
                FROM bms_staff a
                LEFT JOIN bms_designation b ON b.desi_id = a.designation_id              
                WHERE (emp_type IN (1,2,3)
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id."))
                OR staff_id IN (SELECT user_id FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=1)";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyJmb ($property_id) {
        $sql = "SELECT member_id,a.property_id,c.unit_id,c.unit_no,c.owner_name as first_name,jmb_desi_name,elect_date,jmb_role,email_addr
                FROM bms_jmb_mc a   
                LEFT JOIN bms_jmb_designation b ON b.jmb_desi_id = a.jmb_desi_id               
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id
                WHERE a.property_id=".$property_id." AND jmb_status=1";          
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyJmbForEdit ($property_id,$meeting_id) {
        $sql = "SELECT member_id,a.property_id,c.unit_id,c.unit_no,c.owner_name as first_name,jmb_desi_name,elect_date,jmb_role,email_addr
                FROM bms_jmb_mc a   
                LEFT JOIN bms_jmb_designation b ON b.jmb_desi_id = a.jmb_desi_id               
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id
                WHERE a.property_id=".$property_id." AND 
                (jmb_status=1 OR member_id IN (SELECT user_id FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=2))";          
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getToBeDiscussMinorTask ($property_id) {
        $sql = "SELECT task_id,task_name
                FROM bms_task a
                WHERE a.property_id=".$property_id." AND task_update='7'";          
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getToBeDiscussMinorTaskForEdit ($property_id,$meeting_id) {
        $sql = "SELECT task_id,task_name
                FROM bms_task a
                WHERE a.property_id=".$property_id." AND 
                (task_update='To be discuss' OR task_id IN (SELECT minor_task_id FROM bms_meetings_minor_task WHERE meeting_id=".$meeting_id."))";          
        
        $query = $this->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getExternals ($meeting_id) {
        $sql = "SELECT meetings_attend_oth_id,name,email_addr,contact_no,person_name FROM bms_meetings_attend_oth WHERE meeting_id = ".$meeting_id;
        $query = $this->db->query($sql);  
        return $query->result_array();
    }
    
    function getMeetingChkList ($meeting_id) {
        $query = $this->db->select('meeting_checklist_id, meeting_descrip, meeting_responsibility')                      
                      ->get_where('bms_meetings_checklist',array('meeting_id'=>$meeting_id));
        return $query->result_array();
    }
    
    function getMeetingChkListRemin ($meeting_checklist_id) {
        $query = $this->db->select('meeting_checklist_reminder_id,meeting_checklist_id, remind_before, email_content,email_staff,email_jmb,email_external')                      
                      ->get_where('bms_meetings_checklist_reminder',array('meeting_checklist_id'=>$meeting_checklist_id));
        return $query->result_array();
    }
    
    
    function insertMeeting ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_meetings', $data);
        return $this->db->insert_id();   
    }
    
    function updateMeeting ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_meetings', $data, array('meeting_id' => $id));   
    }
    
    function deleteMeetingStaffAttende ($meeting_id) {
        $sql = "DELETE FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=1";
        $this->db->query($sql);
    }
    
    function deleteMeetingJmbAttende ($meeting_id) {
        $sql = "DELETE FROM bms_meetings_attend WHERE meeting_id=".$meeting_id." AND user_type=2";
        $this->db->query($sql);
    }
    
    function insertMeetingAttende ($data) {        
        $this->db->insert('bms_meetings_attend', $data);
        //return $this->db->insert_id();   
    }
    
    function deleteMeetingMinorTask ($meeting_id) {
        $sql = "DELETE FROM bms_meetings_minor_task WHERE meeting_id=".$meeting_id."";
        $this->db->query($sql);
    }
    
    function insertMeetingMinorTask ($data) {        
        $this->db->insert('bms_meetings_minor_task', $data);
        //return $this->db->insert_id();   
    }
    
    function deleteMeetingAttendeOth ($meeting_id,$ids) {        
        $sql = "DELETE FROM bms_meetings_attend_oth WHERE meeting_id=".$meeting_id." AND meetings_attend_oth_id NOT IN (".implode(',',$ids).")";
        $this->db->query($sql);
    }
    
    function insertMeetingAttendeOth ($data) {        
        $this->db->insert('bms_meetings_attend_oth', $data);
        //return $this->db->insert_id();   
    } 
    
    function updateMeetingAttendeOth ($data,$id) {        
        $this->db->update('bms_meetings_attend_oth', $data, array('meetings_attend_oth_id' => $id));   
    }    
    
    function getDeleteMeetingChecklist ($meeting_id,$ids) {        
        $sql = "SELECT meeting_checklist_id FROM bms_meetings_checklist WHERE meeting_id=".$meeting_id." AND meeting_checklist_id NOT IN (".implode(',',$ids).")";
        $query = $this->db->query($sql);
        return $query->result_array();    
    }
    
    function deleteMeetingChecklistReminder ($ids) {
        $this->db->where_in('meeting_checklist_id', $ids);
        $this->db->delete('bms_meetings_checklist_reminder');
    }  
    
    function deleteMeetingChecklist ($ids) {
        $this->db->where_in('meeting_checklist_id', $ids);
        $this->db->delete('bms_meetings_checklist');
    }
    
    function deleteMeetingChklistReminderById ($master_id,$reminder_ids) {
        $sql = "DELETE FROM bms_meetings_checklist_reminder WHERE meeting_checklist_id=".$master_id." AND meeting_checklist_reminder_id NOT IN (".implode(',',$reminder_ids).")";
        $this->db->query($sql);
    }
    
    function updateMeetingChecklist ($data,$id) {        
        $this->db->update('bms_meetings_checklist', $data, array('meeting_checklist_id' => $id));   
    }
    
    function insertMeetingCheckList ($data) {        
        $this->db->insert('bms_meetings_checklist', $data);
        return $this->db->insert_id();   
    }
    
    function deleteMeetingChecklistReminderById ($master_id,$reminder_ids) {
        $sql = "DELETE FROM bms_meetings_checklist_reminder WHERE meeting_checklist_id=".$master_id." AND meeting_checklist_reminder_id NOT IN (".implode(',',$reminder_ids).")";
        $this->db->query($sql);
    }
    
    function updateMeetingChecklistReminder ($data,$id) {
        $this->db->update('bms_meetings_checklist_reminder', $data, array('meeting_checklist_reminder_id' => $id));   
    }
    
    function insertMeetingChecklistReminder ($data) {
        $this->db->insert('bms_meetings_checklist_reminder', $data);   
    }
    
    function update_checklist_status ($data,$id) {
        $data['status_updated_by'] = $_SESSION['bms']['staff_id'];
        $data['status_updated_date'] = date("Y-m-d");
        $this->db->update('bms_meetings_checklist', $data, array('meeting_checklist_id' => $id));   
    }
    
    function getMeetingWithPropName ($meeting_id) {
        $query = $this->db->select('a.property_id,b.property_name,meeting_descrip,meeting_venue,meeting_date,meeting_time') 
                      ->join('bms_property b','b.property_id=a.property_id','left')                     
                      ->get_where('bms_meetings a',array('meeting_id'=>$meeting_id));
        return $query->row_array();
    }
    
    function getMeetingChkListDesignation ($meeting_id) {
        $query = $this->db->select('meeting_checklist_id, meeting_descrip, meeting_responsibility,b.desi_name,meeting_checklist_status,meeting_checklist_remarks')  
                      ->join('bms_designation b','b.desi_id=meeting_responsibility','left')                        
                      ->get_where('bms_meetings_checklist',array('meeting_id'=>$meeting_id));
        return $query->result_array();
    }
    
    function getMeetingMinutes ($meeting_id) {
        $query = $this->db->select('meeting_minutes_id, minutes_title, minutes_descrip,action_by,progress')  
                      ->get_where('bms_meeting_minutes',array('meeting_id'=>$meeting_id));
        return $query->result_array();
    }
    
    function deleteMeetingMinutes ($meeting_id,$ids) {        
        $sql = "DELETE FROM bms_meeting_minutes WHERE meeting_id=".$meeting_id." AND meeting_minutes_id NOT IN (".implode(',',$ids).")";
        $this->db->query($sql);
    }
    
    function insertMeetingMinutes ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_meeting_minutes', $data);
        return $this->db->insert_id();   
    }
    
    function updateMeetingMinutes ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_meeting_minutes', $data, array('meeting_minutes_id' => $id));   
    }
       
}