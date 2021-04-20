<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Soa_emails
{
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI = &get_instance();
        $this->CI->load->helper('common_functions');
    }

    function send_soa () {
        $data['browser_title'] = 'Property Butler | Statement Of Account';
        $data['page_header'] = '<i class="fa fa-file"></i> Statement Of Account';

        $custom_error_folder = FCPATH .'bms_uploads'.DIRECTORY_SEPARATOR.'custom_error_log'.DIRECTORY_SEPARATOR;
        $file_path = $custom_error_folder.'soa_mail_count.txt';

        $data['properties'] = $this->getMyProperties ();

        $data ['property_id'] = !empty($_GET['property_id']) ? $_GET['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['act'] = $this->CI->input->get('act');
        $data['sendmail'] = $this->CI->input->get('sendmail');

        $data['units'] = $this->getUnit_display ($data ['property_id'],0);

        $data['all_soa'] = array ();

        if ( !empty($data ['property_id']) && !empty($_GET['unit_id']) && !empty($_GET['from']) ) {

            if ( $_GET['unit_id'] != 'All') {

                //echo "query error!"; exit;
                $from = date('Y-m-d',strtotime(trim($_GET['from'])));
                $to = !empty($_GET['to']) ? date('Y-m-d',strtotime(trim($_GET['to']))) : date('Y-m-d',strtotime('-1 day',strtotime("+1 month", strtotime($from))));
                //strtotime("+1 month", strtotime($val['agm_last_date'])))
                // get brought forward

                $data['bf_debit'][$_GET['unit_id']] = $this->get_bf_debit ($_GET['unit_id'],$from);

                if(empty($data['bf_debit'][$_GET['unit_id']]['amount']))
                    $data['bf_debit'][$_GET['unit_id']]['amount'] = 0;

                $data['bf_credit'][$_GET['unit_id']] = $this->get_bf_credit ($_GET['unit_id'],$from);
                
                if(empty($data['bf_credit'][$_GET['unit_id']]['amount']))
                    $data['bf_credit'][$_GET['unit_id']]['amount'] = 0;

                $data['soa'] = $this->get_soa ($_GET['unit_id'],$from,$to);

                $data['all_soa'][] = $data['soa'];
                //echo "<pre>";print_r($data['bf_debit']);echo "</pre>";
            } else {
                $offset = file_get_contents($file_path);
                $data['property_unites'] = $this->getUnit ( $_GET['property_id'], 0, $offset );
                foreach ( $data['property_unites'] as $kye=>$val ) {
                    $from = date('Y-m-d',strtotime(trim($_GET['from'])));
                    $to = !empty($_GET['to']) ? date('Y-m-d',strtotime(trim($_GET['to']))) : date('Y-m-d',strtotime('-1 day',strtotime("+1 month", strtotime($from))));

                    // get brought forward
                    $data['bf_debit'][$val['unit_id']] = $this->get_bf_debit ($val['unit_id'],$from);
                    if(empty($data['bf_debit'][$val['unit_id']]['amount']))
                        $data['bf_debit'][$val['unit_id']]['amount'] = 0;

                    $data['bf_credit'][$val['unit_id']] = $this->get_bf_credit ($val['unit_id'],$from);
                    //echo "<br />".$val['unit_id'] . ' =>' .$data['bf_credit'][$val['unit_id']]['amount'];
                    if(empty($data['bf_credit'][$val['unit_id']]['amount']))
                        $data['bf_credit'][$val['unit_id']]['amount'] = 0;

                    $data['soa'] = $this->get_soa ($val['unit_id'],$from,$to);

                    if ( !empty($data['soa']) )
                        $data['all_soa'][] = $data['soa'];
                }
            }
            $data['PropertyInfo'] = $this->getPropertyInfo ( $data ['property_id'] );

            if ( $data['act'] == 'pdf' ) {

                $this->CI->load->library('M_pdf');

                if ( isset ( $data['sendmail'] ) && $data['sendmail'] == 'yes' ) {
                } else {
                    if ( count($data['all_soa']) > 1 ) {
                        $soa_PDF = $this->CI->load->view ('finance/manual_bill/smt_of_acc_pdf_cover_view', $data, true);
                        $this->CI->m_pdf->pdf->WriteHTML ($soa_PDF);
                    }
                }

                $counter = 1;
                $email_sent_count = 0;
                foreach ( $data['all_soa'] as $key_soa => $val_soa ) {
                    $data['single_page_soa'] = $val_soa;
                    $data['UnitDetail'] = $this->getUnitDetails ( $val_soa[0]['unit_id'] );
                    $m_pdf = new M_pdf();
                    if ( isset ( $data['sendmail'] ) && $data['sendmail'] == 'yes' ) {
                        $email_sent_count++;
                        if ( filter_var( $data['UnitDetail']->email_addr, FILTER_VALIDATE_EMAIL) && $data['UnitDetail']->valid_email == 1 ) {
                            $filename = "statement-of-account.pdf";
                            $soa_PDF = $this->CI->load->view ( 'finance/manual_bill/smt_of_acc_view', $data, true );
                            $m_pdf->pdf->WriteHTML ($soa_PDF);
                            $content = $m_pdf->pdf->Output('', 'S');
                            $counter++;
                            $this->CI->load->library('email');
                            $this->CI->email->clear(true);
                            $message = "Dear " . $data['UnitDetail']->owner_name . ", <br>
                            Please find attached Statement Of Account for the period of " . $_GET['from'] . " to " . $_GET['to'] . "<br><br>" .
                            "Thank you,<br>" .
                            $data['PropertyInfo']['jmb_mc_name'] . ",<br>" .
                            $data['PropertyInfo']['property_name'];
                            $result = $this->CI->email
                            ->from( 'noreply@propertybutler.my' )// Optional, an account where a human being reads.
                            ->to( $data['UnitDetail']->email_addr )
                            // ->bcc( 'yameenadnan@hotmail.com' )
                            // ->bcc( 'naguwin@gmail.com','Nagarajan' )
                            ->subject ( $data['PropertyInfo']['property_name'] . '-' . $data['UnitDetail']->unit_no . !empty($data['UnitDetail']->owner_name ? ' - ' . $data['UnitDetail']->owner_name:'' ) . " - Statement of Account"  )
                            ->message ( $message )
                            ->attach ( $content, 'attachment', $filename, 'application/pdf' )
                            ->send ();
                        } else {
                            $data_unit = array (
                                'valid_email' => 0
                            );
                            // $this->bms_masters_model->update_unit_set_invalid_email ($data_unit, $data['UnitDetail']->unit_id);
                        }

                    } else {
                        $soa_PDF = $this->CI->load->view ('finance/manual_bill/smt_of_acc_view', $data, true);
                        $this->CI->m_pdf->pdf->WriteHTML ($soa_PDF);
                    }
                }
                $offset = $offset + $email_sent_count;
                file_put_contents($file_path, $offset);
                if ( isset ( $data['sendmail'] ) && $data['sendmail'] == 'yes' ) {
                    $_SESSION['flash_msg'] = '<div style="width: 100%; text-align: center;"><b>Email sent</b></div>';
                    redirect ('index.php/bms_fin_bills/soa?property_id='. $data ['property_id'] . '&unit_id=' . $_GET['unit_id'] . '&from=' . $_GET['from'] . '&to=' . $_GET['to'] );
                } else {
                    $this->CI->m_pdf->pdf->Output("soa_All.pdf", "D");
                }
            } else {
                $this->CI->load->view ('finance/manual_bill/smt_of_acc_view',$data);
            }
        } else {
            /*$_SESSION['flash_msg'] = 'Select atleast one unit!';
            $_SESSION['flash_msg_class'] = 'alert-danger';*/
            $this->CI->load->view ('finance/manual_bill/smt_of_acc_view',$data);
        }
    }

    function get_bf_debit ($unit_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(total_amount) AS amount
                FROM bms_fin_bills a
                WHERE a.unit_id=".$unit_id ." AND bill_date < '$from' 
                UNION
                SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c
                WHERE c.unit_id=".$unit_id ." AND direct_receipt=1 AND receipt_date < '$from'
                UNION                 
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_debit_note e                
                WHERE e.unit_id=".$unit_id ." AND debit_note_date < '$from') bf_debit                
                ";

        $query = $this->CI->db->query($sql);
        //echo "<pre>";print_r($this->CI->db->last_query());echo "</pre>";
        return $query->row_array();
    }

    function get_bf_credit ($unit_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.unit_id=".$unit_id ." AND receipt_date < '$from' 
                UNION
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_credit_note e
                WHERE e.unit_id=".$unit_id ." AND credit_note_date < '$from'
                UNION
                SELECT a.opening_credit  AS amount               
                FROM bms_fin_coa a  
                LEFT JOIN bms_property_units b ON b.coa_id = a.coa_id                          
                WHERE b.unit_id=".$unit_id ." AND opening_credit > 0 AND opening_cr_date < '$from'
                ) bf_credit";
        $query = $this->CI->db->query($sql);
        //echo "<pre>";print_r($this->CI->db->last_query());echo "</pre>";
        return $query->row_array();
    }

    function get_soa ($unit_id,$from,$to) {
        $sql = "SELECT a.coa_id  as id, b.unit_id AS unit_id, b.unit_no,b.owner_name, opening_cr_date AS doc_date, 'OC' AS doc_no, 'Opening Credit' AS descrip, 
                a.opening_credit  AS amount, 'OR' as item_type, '00:00:00' AS doc_time               
                FROM bms_fin_coa a  
                LEFT JOIN bms_property_units b ON b.coa_id = a.coa_id                          
                WHERE b.unit_id=".$unit_id ." AND opening_credit > 0 AND opening_cr_date BETWEEN '$from' AND '$to'
                UNION
                SELECT a.bill_id as id, a.unit_id,b.unit_no,b.owner_name,bill_date AS doc_date,bill_no AS doc_no,
                a.remarks AS descrip,
                a.total_amount AS amount, 'RINV' as item_type,  bill_time AS doc_time
                FROM bms_fin_bills a
                LEFT JOIN bms_property_units b on a.unit_id = b.unit_id
                WHERE a.unit_id=".$unit_id ." AND bill_date BETWEEN '$from' AND '$to'
                UNION
                SELECT c.receipt_id as id, c.unit_id,d.unit_no,d.owner_name,receipt_date AS doc_date,receipt_no AS doc_no,c.remarks AS descrip,
                c.paid_amount AS amount,'OR' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                LEFT JOIN bms_property_units d on c.unit_id = d.unit_id
                WHERE c.unit_id=".$unit_id ." AND direct_receipt = 0 AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT c.receipt_id as id, c.unit_id,d.unit_no,d.owner_name,receipt_date AS doc_date,receipt_no AS doc_no,c.remarks AS descrip,
                c.paid_amount AS amount,'DOR' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                LEFT JOIN bms_property_units d on c.unit_id = d.unit_id
                WHERE c.unit_id=".$unit_id ." AND direct_receipt = 1 AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT aa.depo_receive_id as id, aa.unit_id,bb.unit_no,bb.owner_name,deposit_date AS doc_date,doc_ref_no AS doc_no,aa.remarks AS descrip,
                aa.amount AS amount,'DOR' as item_type, deposit_time AS doc_time
                FROM bms_fin_deposit_receive aa
                LEFT JOIN bms_property_units bb on aa.unit_id = bb.unit_id
                WHERE aa.unit_id=".$unit_id ." AND deposit_date BETWEEN '$from' AND '$to'
                UNION
                SELECT cc.depo_refund_id as id, cc.unit_id,dd.unit_no,dd.owner_name,depo_refund_date AS doc_date, cc.doc_ref_no AS doc_no,cc.remarks AS descrip,
                cc.amount AS amount,'DOR' as item_type, depo_refund_time AS doc_time
                FROM bms_fin_deposit_refund cc
                LEFT JOIN bms_property_units dd on cc.unit_id = dd.unit_id
                WHERE cc.unit_id=".$unit_id ." AND depo_refund_date BETWEEN '$from' AND '$to'
                UNION
                SELECT e.credit_note_id as id, e.unit_id,f.unit_no,f.owner_name,credit_note_date AS doc_date,credit_note_no AS doc_no,e.remarks AS descrip,
                e.total_amount AS amount,'CN' as item_type,  credit_note_time AS doc_time
                FROM bms_fin_credit_note e
                LEFT JOIN bms_property_units f on e.unit_id = f.unit_id
                WHERE e.unit_id=".$unit_id ." AND credit_note_date BETWEEN '$from' AND '$to'
                UNION
                SELECT g.debit_note_id as id, g.unit_id,h.unit_no,h.owner_name,debit_note_date AS doc_date,debit_note_no AS doc_no,g.remarks AS descrip,
                g.total_amount AS amount,'DN' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g
                LEFT JOIN bms_property_units h on g.unit_id = h.unit_id
                WHERE g.unit_id=".$unit_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                ORDER BY doc_date ASC, doc_time ASC";

        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }

    function getUnit ($property_id,$block_id = 0, $offset) {
        $condi = '';
        if($block_id != 0)
            $condi = " AND block_id = '".$block_id."'";
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter 
                FROM bms_property_units 
                WHERE property_id = '".$property_id."' $condi
                ORDER BY unit_no LIMIT $offset, 50" ;

        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }

    function getUnit_display ($property_id,$block_id = 0) {
        $condi = '';
        if($block_id != 0)
            $condi = " AND block_id = '".$block_id."'";
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter 
                FROM bms_property_units 
                WHERE property_id = '".$property_id."' $condi
                ORDER BY unit_no" ;
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }

    function getPropertyInfo ($property_id) {

        $sql = "SELECT property_id,property_name,property_type,total_units,email_addr,monthly_billing,a.state_id,b.state_name,
                jmb_mc_name,address_1,address_2,phone_no,fax,pin_code,city
                FROM bms_property a
                LEFT JOIN bms_state b ON b.state_id=a.state_id 
                WHERE property_status=1 AND property_id = '".$property_id."' ORDER BY property_name ASC";
        $query = $this->CI->db->query($sql);
        return $query->row_array();
    }

    function getUnitDetails ($unit_id) {
        $sql = "SELECT unit_id, unit_no, unit_status, floor_no, owner_name, email_addr, gender, contact_1, is_defaulter, valid_email FROM bms_property_units WHERE unit_id = '".$unit_id."'";
        $query = $this->CI->db->query($sql);
        return $query->row();
    }

    function getMyProperties ($staff_id ='') {
        $staff_id = $staff_id == '' ? $_SESSION['bms']['staff_id'] : $staff_id;
        $sql = "SELECT property_id,property_name,property_type,property_abbrev,total_units
                FROM bms_property WHERE property_status=1 AND property_id IN
                (SELECT property_id FROM bms_staff_property WHERE staff_id=".$staff_id.") ORDER BY property_name ASC";

        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }

}