<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_monthly_report extends CI_Controller {
    
    function __construct () { 
        parent::__construct (); 
        
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false ) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    } 
        $this->load->model('bms_masters_model');
        $this->load->model('bms_task_model_v2_0','bms_task_model');
        $this->load->model('bms_sop_model');
        $this->load->model('bms_monthly_report_model');
    }
    
    public function index () {
        $data ['property_id'] = $this->input->get('property_id');
        if ( isset ( $data ['property_id'] ) && $data ['property_id'] != '' ) {
            $data ['report_month'] = $this->input->get('report_month');
            $data ['report_year'] = $this->input->get('report_year');
            if ( isset ( $data ['report_month'] ) && $data ['report_month'] != '' && isset ( $data ['report_year'] ) && $data ['report_year'] != '' ) {
                $report_exists = $this->bms_monthly_report_model->check_report_exists ( $data ['property_id'], $data ['report_month'], $data ['report_year'] );
                if ( !empty($report_exists) && $report_exists['total_record'] > 0  ) {
                    $monthly_report_path = $this->config->item('monthly_report_path');
                    $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_exists['report_id'] );

                    $data = array (
                        'report_id' => $bms_report_data->report_id,
                        'property_id' => $bms_report_data->property_id,
                        'report_month' => $bms_report_data->report_month,
                        'report_year' => $bms_report_data->report_year,
                        'insert_id' => $bms_report_data->report_id
                    );
                    $report_year = $bms_report_data->report_year;
                    $report_mon  = $bms_report_data->report_month;
                    $filename = "MR_" . $bms_report_data->property_id . "_" . $report_year . '_' . $report_mon  . ".pdf";
                    $url = base_url() . $monthly_report_path . $report_year . '/' . $report_mon . '/' . $filename;

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ( $code == 200 ) {
                        curl_close( $ch );
                        if ($_SESSION['bms']['user_type'] == 'staff') {
                            $data['properties'] = $this->bms_masters_model->getMyProperties();
                        } else {
                            $data['properties'] = $this->bms_masters_model->getJmbProperties($_SESSION['bms']['property_id']);
                        }
                        $data['file_location'] = $monthly_report_path . $report_year . '/' . $report_mon . '/' . $filename;
                        $data['already_exist'] = 'Yes';
                    }

                    $data['report_id'] = $report_exists['report_id'];
                    $data['managed_by'] = $report_exists['managed_by'];
                    $data['prepared_by'] = $report_exists['prepared_by'];
                } else {
                    $data['created_date'] = date("Y-m-d H:i:s");
                    $data['created_by'] = isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '';
                    $insert_id = $this->bms_monthly_report_model->insert($data);
                    $data['report_id'] = $insert_id;
                }
            }
        }
        if($_SESSION['bms']['user_type'] == 'staff') {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        } else {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        }
        $data['browser_title'] = 'Property Butler | Monnthly Report';
        $data['page_header'] = '<i class="fa fa-folder"></i> Monthly Report';
        $this->load->view('monthly_report/monthly_report_view',$data);
	}

    function check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, $bms_report_service_provider ) {
        return $this->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, $bms_report_service_provider );
    }

    function add_common_info_data () {
        $report_id = $this->input->get('report_id');
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        $data['browser_title'] = 'Property Butler | General Info';
        $data['page_header'] = '<i class="fa fa-folder"></i> General Info';
        $report_common_info_data = $this->bms_monthly_report_model->get_common_info_data_PDF ($report_id);
        if ( !empty( $report_common_info_data ) ) {
            $data['common_info'] = $report_common_info_data;
        }

        $data['hr_access_desi'] = $this->config->item('hr_access_desi');

        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }

        $this->load->view('monthly_report/add_common_info_data_view',$data);

    }

    function add_common_ino_data_submit () {
        $report_id = $this->input->post('report_id');
        $common_info = $_POST['common_info'];
        foreach ( $common_info['date'] as $key=>$val ) {
            if ( isset( $common_info['report_commoon_info_id'][$key] ) && $common_info['report_commoon_info_id'][$key] != '' ) {

                $data = array (
                    'date' => date('Y-m-d',strtotime($common_info['date'][$key])),
                    'remarks' => $common_info['remarks'][$key],
                    'info_base' => $common_info['info_base'][$key]
                );

                $this->bms_monthly_report_model->update_common_info ( $data, $common_info['report_commoon_info_id'][$key] );
            } else {
                $data = array (
                    'report_id' => $report_id,
                    'date' => date('Y-m-d',strtotime($common_info['date'][$key])),
                    'remarks' => $common_info['remarks'][$key],
                    'info_base' => $common_info['info_base'][$key]
                );
                $data['created_date'] = date("Y-m-d H:i:s");
                $data['created_by'] = isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '';
                $this->bms_monthly_report_model->insert_common_info ( $data );
            }
        }

        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        redirect ('index.php/bms_monthly_report/index/?property_id=' . $bms_report_data->property_id . '&report_month=' . $bms_report_data->report_month . '&report_year=' . $bms_report_data->report_year );
    }

    function unset_report_commoon_info_id () {
        $report_commoon_info_id = $this->input->post('report_commoon_info_id');
        $this->bms_monthly_report_model->unset_report_commoon_info_id ( $report_commoon_info_id );
    }

    function add_major_task_data () {
        $report_id = $this->input->get('report_id');
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        $data['browser_title'] = 'Property Butler | Recommendation / Action Plan';
        $data['page_header'] = '<i class="fa fa-folder"></i> Recommendation / Action Plan';
        $report_major_task_data = $this->bms_monthly_report_model->get_major_task_data_PDF ($report_id);
        if ( !empty( $report_major_task_data ) ) {
            $data['major_task'] = $report_major_task_data;
        }

        $data['hr_access_desi'] = $this->config->item('hr_access_desi');

        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }
        $this->load->view('monthly_report/add_major_task_data_view',$data);
    }

    function add_major_task_data_submit () {
        $report_id = $this->input->post('report_id');
        $major_task = $_POST['major_task'];
        foreach ( $major_task['location'] as $key=>$val ) {
            if ( isset( $major_task['report_major_task_id'][$key] ) && $major_task['report_major_task_id'][$key] != '' ) {
                $data = array (
                    'location' => $major_task['location'][$key],
                    'description' => $major_task['description'][$key],
                    'action' => $major_task['action'][$key],
                    'date_report' => date('Y-m-d',strtotime($major_task['date_report'][$key])),
                    'date_line' => date('Y-m-d',strtotime($major_task['date_line'][$key])),
                    'date_approved' => date('Y-m-d',strtotime($major_task['date_approved'][$key])),
                    'date_rectified' => date('Y-m-d',strtotime($major_task['date_rectified'][$key])),
                );

                $this->bms_monthly_report_model->update_major_task ( $data, $major_task['report_major_task_id'][$key] );
            } else {
                $data = array (
                    'report_id' => $report_id,
                    'location' => $major_task['location'][$key],
                    'description' => $major_task['description'][$key],
                    'action' => $major_task['action'][$key],
                    'date_report' => date('Y-m-d',strtotime($major_task['date_report'][$key])),
                    'date_line' => date('Y-m-d',strtotime($major_task['date_line'][$key])),
                    'date_approved' => date('Y-m-d',strtotime($major_task['date_approved'][$key])),
                    'date_rectified' => date('Y-m-d',strtotime($major_task['date_rectified'][$key])),
                );
                $data['created_date'] = date("Y-m-d H:i:s");
                $data['created_by'] = isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '';
                $this->bms_monthly_report_model->insert_major_task ( $data );
            }
        }
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        redirect ('index.php/bms_monthly_report/index/?property_id=' . $bms_report_data->property_id . '&report_month=' . $bms_report_data->report_month . '&report_year=' . $bms_report_data->report_year );
    }

    function add_service_provider_data () {
        $report_id = $this->input->get('report_id');
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        $data['browser_title'] = 'Property Butler | Service provider assessment';
        $data['page_header'] = '<i class="fa fa-folder"></i> Service provider assessment';

        $report_service_provider_data = $this->bms_monthly_report_model->get_service_provider_data_from_report_table ($report_id);

        if ( !empty( $report_service_provider_data ) ) {
            $data['service_provider'] = $report_service_provider_data;
        } else {
            $data['service_provider'] = $this->bms_monthly_report_model->get_service_provider_data ( $bms_report_data->report_year, $bms_report_data->report_month, $bms_report_data->property_id );
        }

        $data['hr_access_desi'] = $this->config->item('hr_access_desi');

        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }


        $this->load->view('monthly_report/add_service_provider_data_view',$data);
    }

    function add_service_provider_data_submit () {
        $report_id = $this->input->post('report_id');
        $sevice_provider = $_POST['sevice_provider'];

        foreach ( $sevice_provider['remarks'] as $key=>$val ) {
            if ( isset( $sevice_provider['report_service_provider_id'][$key] ) && $sevice_provider['report_service_provider_id'][$key] != '' ) {
                $data = array (
                    'assessment' => $sevice_provider['assessment_' . $sevice_provider['service_provider_id'][$key]][0],
                    'remarks' => $sevice_provider['remarks'][$key],
                );
                $this->bms_monthly_report_model->update_service_provider ( $data, $sevice_provider['report_service_provider_id'][$key] );
            } else {
                $data = array (
                    'report_id' => $report_id,
                    'assessment' => $sevice_provider['assessment_' . $sevice_provider['service_provider_id'][$key]][0],
                    'service_provider_id' => $sevice_provider['service_provider_id'][$key],
                    'remarks' => $sevice_provider['remarks'][$key],
                );
                $data['created_date'] = date("Y-m-d H:i:s");
                $data['created_by'] = isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '';
                $this->bms_monthly_report_model->insert_service_provider ( $data );
            }
        }
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );
        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        redirect ('index.php/bms_monthly_report/index/?property_id=' . $bms_report_data->property_id . '&report_month=' . $bms_report_data->report_month . '&report_year=' . $bms_report_data->report_year );
    }

    function generate_PDF_report () {

        $report_id = $this->input->post('report_id');
        $report = $this->input->post('report');
        $generate_report_status = $this->input->post('generate_report_status');
        $bms_report_data = $this->bms_monthly_report_model->bms_report_data ( $report_id );

        $data = array (
            'report_id' => $bms_report_data->report_id,
            'property_id' => $bms_report_data->property_id,
            'report_month' => $bms_report_data->report_month,
            'report_year' => $bms_report_data->report_year,
            'insert_id' => $bms_report_data->report_id
        );

        $data['browser_title'] = 'Property Butler | Monthly Report';
        $data['page_header'] = '<i class="fa fa-folder"></i> Monthly Report';

        $data['report'] = $report;

        $data['generate_report_status'] = $generate_report_status;
        $data['report_data'] = $bms_report_data;

        $data['property_data'] = $this->bms_monthly_report_model->get_property_data_PDF($bms_report_data->property_id);

        $data['utility_report_data'] = $this->bms_monthly_report_model->get_utility_report_data_PDF($bms_report_data->property_id, $bms_report_data->report_year, $bms_report_data->report_month);
        $data['property_info'] = $this->bms_masters_model->getPropertyInfo($bms_report_data->property_id);

        $exclude_desi_ids = implode(',', $this->config->item('exclude_desi_for_dashboard'));

        $data['staff_info'] = $this->bms_monthly_report_model->get_property_staff_attendance ($bms_report_data->property_id, $exclude_desi_ids, $bms_report_data->report_year, $bms_report_data->report_month);


        foreach ($data['staff_info'] as $key => $val) {
            $data['staff_attendannce_detal'][$val['staff_id']]['name'] = $val['first_name'] . ' ' . $val['last_name'];
            $data['staff_attendannce_detal'][$val['staff_id']]['attendance'] = $this->bms_monthly_report_model->get_property_staff_detail($val['staff_id'], $bms_report_data->report_year, $bms_report_data->report_month);
            foreach ($data['staff_attendannce_detal'][$val['staff_id']]['attendance'] as $val1) {
                $data['staff_attendannce_detal'][$val['staff_id']][$val1['attendance_day']] = $val1['attendance_day'];
            }
        }

        // $data['staff_attendannce_detal'] = $row;

        $data['act'] = 'pdf';

        //// Page title starts here
        $this->load->library('M_pdf');
        $managed_by = $report['managed_by'];
        $data['managed_by'] = $report['managed_by'];

        $data['prepared_by'] = $report['prepared_by'];

        $data_monthly_report = array(
            'managed_by' => $managed_by,
            'prepared_by' => $data['prepared_by'],
        );

        $this->bms_monthly_report_model->update_report_table($data_monthly_report, $bms_report_data->report_id);

        $this->m_pdf->pdf->SetHTMLFooter( 'Prepared by: <span style="font-weight: bold; display: inline-block;">' . $data['prepared_by'] . '</span>' );

        $mr_cover_page_PDF = $this->load->view('monthly_report/mr_cover_page_PDF', $data, true);
        $this->m_pdf->pdf->WriteHTML($mr_cover_page_PDF);
        $this->m_pdf->pdf->SetWatermarkText('DRAFT');
        $this->m_pdf->pdf->showWatermarkText = true;
        $this->m_pdf->pdf->watermark_font = 'erasbd';
        $this->m_pdf->pdf->SetWatermarkText($managed_by);
        $this->m_pdf->pdf->watermarkTextAlpha = 0.06;

        $mr_index_PDF = $this->load->view('monthly_report/mr_index_PDF', $data, true);
        $this->m_pdf->pdf->WriteHTML($mr_index_PDF);
        //// Page title ends here

        //// Accounts related starts here
        if ($report['balance_sheet'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_balance_sheet_title_PDF = $this->load->view('monthly_report/mr_balance_sheet_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_balance_sheet_title_PDF);
        }

        if ($report['income_and_expenditure'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_income_and_expenditure_title_PDF = $this->load->view('monthly_report/mr_income_and_expenditure_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_income_and_expenditure_title_PDF);
        }

        if ($report['accouunt_summary'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_faq_title_PDF = $this->load->view('monthly_report/mr_account_summary_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_faq_title_PDF);
        }

        if ($report['fixed_asset_list'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_fixed_assets_list_title_PDF = $this->load->view('monthly_report/mr_fixed_assets_list_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_fixed_assets_list_title_PDF);
        }

        if ($report['cash_flow_statement'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_cash_flow_statement_title_PDF = $this->load->view('monthly_report/mr_cash_flow_statement_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_cash_flow_statement_title_PDF);
        }

        if ($report['maintenance_fund_bank_reconciliation'] == 1) {
            $mr_bank_reconciliation_maintenance_title_PDF = $this->load->view('monthly_report/mr_bank_reconciliation_maintenance_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_bank_reconciliation_maintenance_title_PDF);
        }

        if ($report['maintenance_fund_sinking_fund'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_bank_reconciliation_sinking_title_PDF = $this->load->view('monthly_report/mr_bank_reconciliation_sinking_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_bank_reconciliation_sinking_title_PDF);
        }

        if ($report['bank_statement'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_bank_statment_title_PDF = $this->load->view('monthly_report/mr_bank_statment_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_bank_statment_title_PDF);
        }

        if ($report['debtor_aging_report'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_debtor_aging_report_title_PDF = $this->load->view('monthly_report/mr_debtor_aging_report_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_debtor_aging_report_title_PDF);
        }

        if ($report['creditor_aging_summary'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_creditor_aging_summary_title_PDF = $this->load->view('monthly_report/mr_creditor_aging_summary_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_creditor_aging_summary_title_PDF);
        }

        if ($report['payment_summary'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_payment_summary_title_PDF = $this->load->view('monthly_report/mr_payment_summary_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_payment_summary_title_PDF);
        }

        if ($report['utilities'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_utitlities_title_PDF = $this->load->view('monthly_report/mr_utitlities_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_utitlities_title_PDF);
        }





        //// Accounts related ends here

        if ($report['management_team'] == 1) {
            $mr_staff_info_title_PDF = $this->load->view('monthly_report/mr_staff_info_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_staff_info_title_PDF);
            $mr_staff_info_data_PDF = $this->load->view('monthly_report/mr_staff_info_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_staff_info_data_PDF);
        }

        //// BMS starts here
        if ($report['management_team_attendance'] == 1) {
            $mr_staff_info_title_PDF = $this->load->view('monthly_report/mr_staff_attendance_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_staff_info_title_PDF);
            $mr_staff_info_data_PDF = $this->load->view('monthly_report/mr_staff_attendance_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_staff_info_data_PDF);
        }

        //// BMS starts here
        $data['property_info'];
        if ( $report['service_provider_assessment'] == 1) {
            $get_off_day_detail = $this->get_off_day_detail ( $data['property_data']->state_id, $bms_report_data->report_year, $bms_report_data->report_month );
            $week_days_total = $get_off_day_detail['week_days_total'];
            $holidays = $get_off_day_detail['holidays'];

            $data['service_provider_data'] = $this->bms_monthly_report_model->get_service_provider_data_PDF ($bms_report_data->property_id, $report_id, $bms_report_data->report_year, $bms_report_data->report_month);
            foreach ( $data['service_provider_data'] as $key => $val ) {
                $total_head_count = 0;
                foreach ( $week_days_total as $day => $total) {
                    $total_head_count += $val[strtolower(substr($day, 0, 3))] * $total;
                    if ( in_array (strtolower(substr($day, 0, 3)), $holidays) ) {
                        $tmp = array_count_values($holidays);
                        $cnt = $tmp[strtolower(substr($day, 0, 3))];

                        $total_head_count += ($val['public_holiday'] * $cnt);
                    }
                }
                $data['service_provider_data'][$key]['total_head_count'] = $total_head_count;
            }

            $mr_service_provider_summary_title_PDF = $this->load->view('monthly_report/mr_service_provider_summary_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_service_provider_summary_title_PDF);
            $mr_service_provider_summary_data_PDF = $this->load->view('monthly_report/mr_service_provider_summary_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_service_provider_summary_data_PDF);
        }

        if ( $report['service_provider_attendance'] == 1 ) {

            $get_off_day_detail = $this->get_off_day_detail ( $data['property_data']->state_id, $bms_report_data->report_year, $bms_report_data->report_month );
            $week_days_total = $get_off_day_detail['week_days_total'];
            $holidays = $get_off_day_detail['holidays'];

            $data['service_provider_attendance'] = $this->bms_monthly_report_model->get_service_provider_attendance_PDF($bms_report_data->property_id, $report_id, $bms_report_data->report_year, $bms_report_data->report_month);
            foreach ( $data['service_provider_attendance'] as $key => $val ) {
                $total_head_count = 0;
                foreach ( $week_days_total as $day => $total) {
                    $total_head_count += $val[strtolower(substr($day, 0, 3))] * $total;
                    if ( in_array (strtolower(substr($day, 0, 3)), $holidays) ) {
                        $tmp = array_count_values($holidays);
                        $cnt = $tmp[strtolower(substr($day, 0, 3))];

                        $total_head_count += ($val['public_holiday'] * $cnt);
                    }
                }
                $data['service_provider_attendance'][$key]['total_head_count'] = $total_head_count;
            }



            $data['service_provider_attendance_detail'] = $this->bms_monthly_report_model->get_service_provider_attendance_detail_PDF ($bms_report_data->property_id, $report_id, $bms_report_data->report_year, $bms_report_data->report_month);
            $mr_service_provider_attendance_title_PDF = $this->load->view('monthly_report/mr_service_provider_attendance_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_service_provider_attendance_title_PDF);
            $mr_service_provider_attendance_data_PDF = $this->load->view('monthly_report/mr_service_provider_attendance_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_service_provider_attendance_data_PDF);
        }

        if ( $report['annual_renewals'] == 1) {
            $data['renewal_data'] = $this->bms_monthly_report_model->get_renewal_data_PDF($bms_report_data->property_id, $bms_report_data->report_year, $bms_report_data->report_month);
            $mr_renewals_title_PDF = $this->load->view('monthly_report/mr_renewals_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_renewals_title_PDF);
            $mr_renewals_data_PDF = $this->load->view('monthly_report/mr_renewals_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_renewals_data_PDF);
        }

        if ( $report['minor_tasks'] == 1) {
            $data['minor_task_data'] = $this->bms_monthly_report_model->get_minor_task_data_PDF($bms_report_data->property_id, $bms_report_data->report_year, $bms_report_data->report_month);
            $mr_minor_task_title_PDF = $this->load->view('monthly_report/mr_minor_task_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_minor_task_title_PDF);
            $mr_minor_task_data_PDF = $this->load->view('monthly_report/mr_minor_task_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_minor_task_data_PDF);
        }

        if ( $report['common_info'] == 1) {
            $data['common_info_data'] = $this->bms_monthly_report_model->get_common_info_data_PDF($report_id);
            $mr_common_info_title_PDF = $this->load->view('monthly_report/mr_common_info_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_common_info_title_PDF);
            $mr_common_info_data_PDF = $this->load->view('monthly_report/mr_common_info_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_common_info_data_PDF);
        }

        if ( $report['incident_report'] == 1) {
            $data['incident_data'] = $this->bms_monthly_report_model->get_incident_data_PDF($bms_report_data->property_id, $bms_report_data->report_year, $bms_report_data->report_month);
            $mr_incident_title_PDF = $this->load->view('monthly_report/mr_incident_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_incident_title_PDF);
            $mr_incident_data_PDF = $this->load->view('monthly_report/mr_incident_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_incident_data_PDF);
        }

        if ( $report['major_task'] == 1) {
            $data['major_task_data'] = $this->bms_monthly_report_model->get_major_task_data_PDF($report_id);
            $mr_major_task_title_PDF = $this->load->view('monthly_report/mr_major_task_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_major_task_title_PDF);
            $mr_major_task_data_PDF = $this->load->view('monthly_report/mr_major_task_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_major_task_data_PDF);
        }

        if ( $report['asset_service_schedule'] == 1) {
            $data['asset_schedule_chart'] = $this->bms_monthly_report_model->get_asset_schedule_chart_data_PDF($bms_report_data->property_id, $bms_report_data->report_year, $bms_report_data->report_month);
            $mr_asset_service_entry_title_PDF = $this->load->view('monthly_report/mr_asset_service_entry_title_PDF', $data, true);
            $this->m_pdf->pdf->addPage();
            $this->m_pdf->pdf->WriteHTML($mr_asset_service_entry_title_PDF);
            $mr_asset_service_entry_data_PDF = $this->load->view('monthly_report/mr_asset_service_entry_data_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_asset_service_entry_data_PDF);
        }

        if ( $report['utility_report'] == 1) {
            $this->m_pdf->pdf->addPage();
            $mr_utility_report_title_PDF = $this->load->view('monthly_report/mr_utility_report_title_PDF', $data, true);
            $this->m_pdf->pdf->WriteHTML($mr_utility_report_title_PDF);
        }
        //// BMS ends here

        //download it D save F.
        $monthly_report_path = $this->config->item('monthly_report_path');
        $report_year = $bms_report_data->report_year;
        $report_mon = $bms_report_data->report_month;
        $filename = "MR_" . $bms_report_data->property_id . "_" . $report_year . '_' . $report_mon . ".pdf";
        if (!is_dir($monthly_report_path . $report_year . '/')) ;
        @mkdir ($monthly_report_path . $report_year . '/', 0777);
        if (!is_dir($monthly_report_path . $report_year . '/' . $report_mon . '/')) ;
        @mkdir ($monthly_report_path . $report_year . '/' . $report_mon . '/', 0777);

        if ($_SESSION['bms']['user_type'] == 'staff') {
            $data['properties'] = $this->bms_masters_model->getMyProperties();
        } else {
            $data['properties'] = $this->bms_masters_model->getJmbProperties($_SESSION['bms']['property_id']);
        }

        $data['browser_title'] = 'Property Butler | Monthly Report';
        $data['page_header'] = '<i class="fa fa-folder"></i> Monthly Report';

        $this->m_pdf->pdf->Output($monthly_report_path . $report_year . '/' . $report_mon . '/' . $filename, "F");
        $data['file_location'] = $monthly_report_path . $report_year . '/' . $report_mon . '/' . $filename;
        $this->load->view('monthly_report/monthly_report_view', $data);
    }

    function unset_report_major_task_id () {
        $report_major_task_id = $this->input->post('report_major_task_id');
        $this->bms_monthly_report_model->unset_report_major_task_id ( $report_major_task_id );
    }

    function get_off_day_detail ($state_id, $report_year, $report_month) {
        $holidays_result = $this->bms_monthly_report_model->get_holiday_dates ($state_id, $report_year, $report_month);
        $last_date_of_last_month = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $report_year . '-' . $report_month . '-01' ) ) ));

        // Check last date of last month for sunday and public holiday
        $holidays = array ();
        if ( !empty ($holidays_result) ) {
            if ( $holidays_result[0]['day'] == $last_date_of_last_month ) {
                if ( date ('D',strtotime($last_date_of_last_month) ) == 'Sun' )
                    array_push($holidays,'Mon');
                array_shift($holidays_result);
            }
        }

        // Check all dates of reporting month
        if ( !empty ( $holidays_result ) ) {
            foreach ( $holidays_result as $key => $val ) {
                if ( strtolower(date ('D',strtotime($val['day']) )) == 'sun' )
                    array_push($holidays,'mon');
                else
                    array_push($holidays,strtolower(date('D', strtotime($val['day']))));
            }
        }

        //
        $week_days = array ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $week_days_total = array ();
        $this->load->helper('common_functions');
        foreach ( $week_days as $day ) {
            $week_days_total[$day] = dayCount ($day,$report_month,$report_year);
            if ( in_array (strtolower(substr($day, 0, 3)), $holidays) ) {
                $tmp = array_count_values($holidays);
                $cnt = $tmp[strtolower(substr($day, 0, 3))];
                $week_days_total[$day] = $week_days_total[$day] - $cnt;
            }
        }
        $return['week_days_total'] = $week_days_total;
        $return['holidays'] = $holidays;
        return $return;
    }

}