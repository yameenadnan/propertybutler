<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_cron_jobs extends CI_Controller {
   
    function __construct () {
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }

        $this->load->model('bms_fin_bills_model');
        $this->load->model('bms_fin_cron_jobs_model');
        $this->load->model('bms_property_model');
        $this->load->model('bms_fin_bills_model');
        $this->load->model('bms_masters_model');
    }

	public function auto_billing_sc_sf_original () {
        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');

        $data['properties'] = $this->bms_fin_cron_jobs_model->getPropertiesForAutoBilling ();

        if ( !empty($data['properties']) ) {
            foreach ( $data['properties'] as $key => $val ) {
                if ( !empty ($val['billing_cycle']) && !empty ($val['calcul_base']) && !empty ($val['sinking_fund']) && !empty ($val['property_abbrev']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_service_charge_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sc');
                    $coa_sinking_fund_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sf');
                    if ( !empty ($coa_service_charge_data) && !empty ($coa_sinking_fund_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );
                        if ( !empty( $data['property_units'] ) ) {
                            $limit = 1;
                            switch ( $val['billing_cycle'] ) {
                                case 2:
                                    $limit = 2;
                                    break;
                                case 3:
                                    $limit = 3;
                                    break;
                                case 4:
                                    $limit = 4;
                                    break;
                                case 5:
                                    $limit = 6;
                                    break;
                                case 6:
                                    $limit = 12;
                                    break;
                            }

                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) && !empty ($val_unit['tier_value']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) && !empty($val_unit['tier_value']) ) || ($val['calcul_base'] == 3 && !empty($val['sc_charge']) ) ) ) {
                                    $pdf_attachment = array ();
                                    for ( $i = 0; $i<$limit;$i++ ) {
                                        $manual_bill_items = array ();
                                        $service_charge = 0;
                                        $sinking_fund = 0;

                                        switch ( $val['calcul_base'] ) {
                                            case 1:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['square_feet'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 2:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['share_unit'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 3:
                                                $service_charge = $val['sc_charge'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                        }

                                        $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( date("Y-m-d") )) );

                                        $period = date_format(date_create($bill_date),"M-y");

                                        $prop_abbrev = $val['property_abbrev'];
                                        $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                        $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                        if (!empty ($last_bill_no)) {
                                            $last_no = explode('/', $last_bill_no['bill_no']);
                                            $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                        } else {
                                            $bill_no_format = $bill_no_format . 1001;
                                        }

                                        $bill_data = array (
                                            'property_id' => $val['property_id'],
                                            'block_id' => $val_unit['block_id'],
                                            'unit_id' => $val_unit['unit_id'],
                                            'bill_no' => $bill_no_format,
                                            'bill_date' => $bill_date,
                                            'bill_time' => date('H:i:s'),
                                            'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                            'remarks' => 'SC & SF-' . $period,
                                            'total_amount' => $service_charge + $sinking_fund,
                                            'bill_paid_status' => 0,
                                            'bill_type' => 1,
                                            'created_by' => 1541,
                                            'created_date' => date('Y-m-d')
                                        );
                                        $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                        $data['manual_bill'] = array (
                                            'jmb_mc_name' => $val['jmb_mc_name'],
                                            'property_name' => $val['property_name'],
                                            'address_1' => $val['address_1'],
                                            'address_2' => $val['address_2'],
                                            'pin_code' => $val['pin_code'],
                                            'state_name' => $val_unit['state_name'],
                                            'country_name' => $val_unit['country_name'],
                                            'unit_no' => $val_unit['unit_no'],
                                            'bill_date' => $bill_date,
                                            'owner_name' => $val_unit['owner_name'],
                                            'bill_no' => $bill_no_format,
                                            'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                            'remarks' =>'SC & SF-' . $period,
                                            'first_name' => 'System',
                                            'last_name' => '',
                                            'created_date' => date('Y-m-d')
                                        );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_service_charge_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                            'paid_amount' => 0,
                                            'bal_amount' => $service_charge,
                                            'paid_status' => 0
                                        );

                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_sinking_fund_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                            'paid_amount' => 0,
                                            'bal_amount' => $sinking_fund,
                                            'paid_status' => 0
                                        );
                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $items = array (
                                            'cat_name' => $coa_service_charge_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $items = array (
                                            'cat_name' => $coa_sinking_fund_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $data['manual_bill_items'] = $manual_bill_items;

                                        $m_pdf = new M_pdf();

                                        // Generate pdf and sed attach to email
                                        $sc_and_sf_PDF = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                        $m_pdf->pdf->WriteHTML ( $sc_and_sf_PDF );
                                        $pdf_attachment[] = $m_pdf->pdf->Output('', 'S');
                                    }

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                        "Email address for invoice is: " . $val_unit['email_addr'] .
                                        $val['jmb_mc_name'] . ", <br>" .
                                        $val['property_name'];
                                        $result = $this->email
                                        ->from( $val['email_addr'], $val['property_name'] )
                                        ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                        // ->to($val_unit['email_addr'])
                                        ->to('yameenadnan@hotmail.com')
                                        ->bcc('naguwin@gmail.com','Nagarajan')
                                        ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                        ->message ( $message );
                                        if( !empty( $pdf_attachment ) ) {
                                            foreach ($pdf_attachment as $kye_email) {
                                                $this->email->attach($kye_email, 'attachment', $filename, 'application/pdf');
                                            }
                                        }
                                        $this->email->send ();
                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check owner email in unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' OR \'tier_value\' OR \'sc_charge\'value. Check unit set up and property settings');
                                }
                            }
                        }
                        $sc_charged_date = date ("Y-m-d", strtotime("+1 month", strtotime('-1 day', strtotime ( $bill_date))) );

                        $data_prop = array (
                            'sc_charged_date' => $sc_charged_date
                        );
                        $this->bms_fin_cron_jobs_model->update_property ( $data_prop, $val['property_id'] );
                    } else {
                        $this->generate_log ('auto_billing', $val['property_id'], '', 'Unable to find \'sc\' OR \'sf\' value in chart of Account. Check Chart Of Account');
                    }







                } else {
                    $this->generate_log ('auto_billing', $val['property_id'], '', '\'billing_cycle\' OR \'calcul_base\' OR \'sinking_fund\' OR \'property_abbrev\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings');
                }
            }
        }
	}

	public function auto_billing_sc_sf () {
        $data['properties'] = $this->bms_fin_cron_jobs_model->getPropertiesForAutoBilling ();

        if ( !empty($data['properties']) ) {
            foreach ( $data['properties'] as $key => $val ) {
                if ( !empty ($val['billing_cycle']) && !empty ($val['calcul_base']) && !empty ($val['sinking_fund']) && !empty ($val['property_abbrev']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_service_charge_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sc');
                    $coa_sinking_fund_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sf');
                    if ( !empty ($coa_service_charge_data) && !empty ($coa_sinking_fund_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );

                        if ( !empty( $data['property_units'] ) ) {
                            $limit = 1;
                            switch ( $val['billing_cycle'] ) {
                                case 2:
                                    $limit = 2;
                                    break;
                                case 3:
                                    $limit = 3;
                                    break;
                                case 4:
                                    $limit = 4;
                                    break;
                                case 5:
                                    $limit = 6;
                                    break;
                                case 6:
                                    $limit = 12;
                                    break;
                            }

                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) && !empty ($val_unit['tier_value']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) && !empty($val_unit['tier_value']) ) || ($val['calcul_base'] == 3 && !empty($val['sc_charge']) ) ) ) {
                                    for ( $i = 0; $i<$limit;$i++ ) {
                                        $service_charge = 0;
                                        $sinking_fund = 0;

                                        switch ( $val['calcul_base'] ) {
                                            case 1:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['square_feet'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 2:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['share_unit'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 3:
                                                $service_charge = $val['sc_charge'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                        }

                                        $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( date("Y-m-d") )) );

                                        $period = date_format(date_create($bill_date),"M-y");

                                        $prop_abbrev = $val['property_abbrev'];
                                        $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                        $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                        if (!empty ($last_bill_no)) {
                                            $last_no = explode('/', $last_bill_no['bill_no']);
                                            $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                        } else {
                                            $bill_no_format = $bill_no_format . 1001;
                                        }

                                        $bill_data = array (
                                            'property_id' => $val['property_id'],
                                            'block_id' => $val_unit['block_id'],
                                            'unit_id' => $val_unit['unit_id'],
                                            'bill_no' => $bill_no_format,
                                            'bill_date' => $bill_date,
                                            'bill_time' => date('H:i:s'),
                                            'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                            'remarks' => 'SC & SF-' . $period,
                                            'total_amount' => $service_charge + $sinking_fund,
                                            'bill_paid_status' => 0,
                                            'bill_type' => 1,
                                            'created_by' => 1541,
                                            'created_date' => date('Y-m-d')
                                        );
                                        $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_service_charge_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                            'paid_amount' => 0,
                                            'bal_amount' => $service_charge,
                                            'paid_status' => 0
                                        );
                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_sinking_fund_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                            'paid_amount' => 0,
                                            'bal_amount' => $sinking_fund,
                                            'paid_status' => 0
                                        );
                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                    }
                                } else {
                                    $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' OR \'tier_value\' OR \'sc_charge\'value. Check unit set up and property settings');
                                }
                            }
                        }
                        $sc_charged_date = date ("Y-m-d", strtotime("+1 month", strtotime('-1 day', strtotime ( $bill_date))) );

                        $data_prop = array (
                            'sc_charged_date' => $sc_charged_date
                        );
                        $this->bms_fin_cron_jobs_model->update_property ( $data_prop, $val['property_id'] );
                    } else {
                        $this->generate_log ('auto_billing', $val['property_id'], '', 'Unable to find \'sc\' OR \'sf\' value in chart of Account. Check Chart Of Account');
                    }
                } else {
                    $this->generate_log ('auto_billing', $val['property_id'], '', '\'billing_cycle\' OR \'calcul_base\' OR \'sinking_fund\' OR \'property_abbrev\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings');
                }
            }
        }
	}

    public function auto_billing_fire_insurance_original () {

        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');

        $data['property_list'] = $this->bms_fin_cron_jobs_model->get_properties_for_fire_insurance ();

        if ( !empty($data['property_list']) ) {

            foreach ( $data['property_list'] as $key => $val ) {









                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {

                    $coa_fire_insurance_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'fi');

                    if ( !empty ($coa_fire_insurance_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );
                        if ( !empty( $data['property_units'] )  ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {
                                    $manual_bill_items = array ();
                                    $fire_insurance = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $fire_insurance = $val['insurance_prem'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Fire Insurance -' . $period,
                                        'total_amount' => $fire_insurance,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $data['manual_bill'] = array (
                                        'jmb_mc_name' => $val['jmb_mc_name'],
                                        'property_name' => $val['property_name'],
                                        'address_1' => $val['address_1'],
                                        'address_2' => $val['address_2'],
                                        'pin_code' => $val['pin_code'],
                                        'state_name' => $val_unit['state_name'],
                                        'country_name' => $val_unit['country_name'],
                                        'unit_no' => $val_unit['unit_no'],
                                        'bill_date' => $bill_date,
                                        'owner_name' => $val_unit['owner_name'],
                                        'bill_no' => $bill_no_format,
                                        'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                        'remarks' =>'Fire Insurance -' . $period,
                                        'first_name' => 'System',
                                        'last_name' => '',
                                        'created_date' => date('Y-m-d')
                                    );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_fire_insurance_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_fire_insurance_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $fire_insurance,
                                        'paid_amount' => 0,
                                        'bal_amount' => $fire_insurance,
                                        'paid_status' => 0
                                    );

                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                    $items = array (
                                        'cat_name' => $coa_fire_insurance_data->coa_name,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_fire_insurance_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $fire_insurance,
                                    );
                                    array_push ( $manual_bill_items, $items );

                                    $data['manual_bill_items'] = $manual_bill_items;

                                    $m_pdf = new M_pdf();

                                    // Generate pdf and sed attach to email
                                    $fire_insurance_view = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                    $m_pdf->pdf->WriteHTML ( $fire_insurance_view );
                                    $pdf_attachment = $m_pdf->pdf->Output('', 'S');

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                            "Email address for invoice is: " . $val_unit['email_addr'] .
                                            $val['jmb_mc_name'] . ", <br>" .
                                            $val['property_name'];
                                        $result = $this->email
                                            ->from( $val['email_addr'], $val['property_name'] )
                                            ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                            // ->to($val_unit['email_addr'])
                                            ->to('yameenadnan@hotmail.com')
                                            ->bcc('naguwin@gmail.com','Nagarajan')
                                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                            ->message ( $message )
                                            ->attach ($pdf_attachment, 'attachment', $filename, 'application/pdf')
                                            ->send ();

                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', 'Unable to find \'fi\' value in chart of Account. Check Chart Of Account');
                    }
                } else {
                    $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings');
                }











            }
        }
    }

    public function auto_billing_fire_insurance () {
        $data['property_list'] = $this->bms_fin_cron_jobs_model->get_properties_for_fire_insurance ();
        if ( !empty($data['property_list']) ) {
            foreach ( $data['property_list'] as $key => $val ) {
                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_fire_insurance_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'fi');
                    if ( !empty ($coa_fire_insurance_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );
                        if ( !empty( $data['property_units'] )  ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {
                                    $manual_bill_items = array ();
                                    $fire_insurance = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $fire_insurance = $val['insurance_prem'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Fire Insurance -' . $period,
                                        'total_amount' => $fire_insurance,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_fire_insurance_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_fire_insurance_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $fire_insurance,
                                        'paid_amount' => 0,
                                        'bal_amount' => $fire_insurance,
                                        'paid_status' => 0
                                    );
                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                } else {
                                    $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', 'Unable to find \'fi\' value in chart of Account. Check Chart Of Account');
                    }
                } else {
                    $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings');
                }
            }
        }
    }

    public function send_outstanding_payment_reminder () {
        // Get all properties where reminder1 or reminder2 is set (From property settings)
        $data['properties_list'] = $this->bms_fin_cron_jobs_model->get_properties_for_outstanding_reminder();
        if ( !empty ( $data['properties_list'] ) ) {
            foreach ( $data['properties_list'] as $key => $val ) {
                // Get all property units where amount is outstanding
                $data['property_units'] = $this->bms_fin_cron_jobs_model->get_units_with_outstanding_bills( $val['property_id'] );

                if ( !empty($data['property_units']) ) {
                    foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                        // Get maximum reminder date of unit
                        $data['maximum_reminder_date'] = $this->bms_fin_cron_jobs_model->get_last_reminder_date ( $val_unit['unit_id'] );

                        // If have previous reminder
                        if ( !empty ( $data['maximum_reminder_date']['reminder1_date'] ) ) {
                            // Get all bills where bill date is between previous reminder date and current reminder date
                            $data['outstanding_bill'] = $this->bms_fin_cron_jobs_model->get_current_first_reminder_date ( $data['maximum_reminder_date']['reminder1_date'], $val_unit['unit_id'] );
                        } else {
                            // If no previous reminder
                            $data['outstanding_bills'] = $this->bms_fin_cron_jobs_model->get_outstanding_bills_for_first_reminder ( date('Y-m-d', strtotime(date('Y-m-d'). ' - ' . round( $val['acb_grace_value'] ) . 'days')) , $val_unit['unit_id'] );
                        }

                        if ( !empty ( $data['outstanding_bills'] ) ) {
                            if ( $val['acb_block_card'] == 1 ) {
                                $reminder1_date = date('Y-m-d');
                                $form_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + ' . $val['acb_reminder1_days'] . ' days'));
                                $this->bms_fin_cron_jobs_model->get_outstanding_bills_for_first_reminder;
                            } elseif ( $val['acb_block_card'] == 2 ) {
                                $reminder1_date = date('Y-m-d');
                                $reminder2_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + ' . $val['acb_reminder1_days'] . ' days'));
                                $form_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + ' . ( $val['acb_reminder1_days'] + $val['acb_reminder2_days'] ) . ' days'));
                            }
                        }

                        if ( $val_unit['outstanding'] > 0 && date('Y-m-d') >= $reminder1_date  ) {
                            // $this->bms_fin_cron_jobs_model->get_units_with_outstanding_bills();
                        }
                    }
                } else {
                    $_SESSION['flash_msg'] = 'None of unit found where property id = )' . $val['property_id'];
                    $_SESSION['flash_msg_class'] = 'alert-danger';
                }
            }
        } else {
            $_SESSION['flash_msg'] = 'None of property found where reminder is set)';
            $_SESSION['flash_msg_class'] = 'alert-danger';
        }
    }

    public function auto_billing_quit_rent_original () {

        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');

        $data['property_list'] = $this->bms_fin_cron_jobs_model->get_properties_for_quit_rent ();

        if ( !empty($data['property_list']) ) {
            foreach ( $data['property_list'] as $key => $val ) {










                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_quit_rent_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'qr');
                    if ( !empty ($coa_quit_rent_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );

                        if ( !empty( $data['property_units'] ) ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {

                                    $manual_bill_items = array ();
                                    $quite_rent = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $quite_rent = $val['quit_rent'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Quite Rent -' . $period,
                                        'total_amount' => $quite_rent,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $data['manual_bill'] = array (
                                        'jmb_mc_name' => $val['jmb_mc_name'],
                                        'property_name' => $val['property_name'],
                                        'address_1' => $val['address_1'],
                                        'address_2' => $val['address_2'],
                                        'pin_code' => $val['pin_code'],
                                        'state_name' => $val_unit['state_name'],
                                        'country_name' => $val_unit['country_name'],
                                        'unit_no' => $val_unit['unit_no'],
                                        'bill_date' => $bill_date,
                                        'owner_name' => $val_unit['owner_name'],
                                        'bill_no' => $bill_no_format,
                                        'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                        'remarks' =>'Quite Rent -' . $period,
                                        'first_name' => 'System',
                                        'last_name' => '',
                                        'created_date' => date('Y-m-d')
                                    );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_quit_rent_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_quit_rent_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $quite_rent,
                                        'paid_amount' => 0,
                                        'bal_amount' => $quite_rent,
                                        'paid_status' => 0
                                    );

                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                    $items = array (
                                        'cat_name' => $coa_quit_rent_data->coa_name,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_quit_rent_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $quite_rent,
                                    );
                                    array_push ( $manual_bill_items, $items );

                                    $data['manual_bill_items'] = $manual_bill_items;

                                    $m_pdf = new M_pdf();

                                    // Generate pdf and sed attach to email
                                    $quite_rent_view = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                    $m_pdf->pdf->WriteHTML ( $quite_rent_view );
                                    $pdf_attachment = $m_pdf->pdf->Output('', 'S');

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                        "Email address for invoice is: " . $val_unit['email_addr'] .
                                        $val['jmb_mc_name'] . ", <br>" .
                                        $val['property_name'];
                                        $result = $this->email
                                        ->from( $val['email_addr'], $val['property_name'] )
                                        ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                        // ->to($val_unit['email_addr'])
                                        ->to('yameenadnan@hotmail.com')
                                        ->bcc('naguwin@gmail.com','Nagarajan')
                                        ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                        ->message ( $message )
                                        ->attach ($pdf_attachment, 'attachment', $filename, 'application/pdf')
                                        ->send ();
                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing_quit_rent', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing_quit_rent', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up and match with property settings');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', 'Unable to find \'qr\' value in chart of Account. Check Chart Of Account');
                    }
                } else { $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings'); }
            }
        }
    }

    public function auto_billing_quit_rent () {
        $data['property_list'] = $this->bms_fin_cron_jobs_model->get_properties_for_quit_rent ();
        if ( !empty($data['property_list']) ) {
            foreach ( $data['property_list'] as $key => $val ) {
                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_quit_rent_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'qr');
                    if ( !empty ($coa_quit_rent_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );
                        if ( !empty( $data['property_units'] ) ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {

                                    $quite_rent = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $quite_rent = $val['quit_rent'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Quite Rent -' . $period,
                                        'total_amount' => $quite_rent,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_quit_rent_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_quit_rent_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $quite_rent,
                                        'paid_amount' => 0,
                                        'bal_amount' => $quite_rent,
                                        'paid_status' => 0
                                    );
                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                } else {
                                    $this->generate_log ('auto_billing_quit_rent', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up and match with property settings');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', 'Unable to find \'qr\' value in chart of Account. Check Chart Of Account');
                    }
                } else { $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings'); }
            }
        }
    }

    public function email_invoices () {
        $data['browser_title'] = 'Property Butler | Sales Invoice ';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice Details';

        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');

        $data['properties'] = $this->bms_fin_cron_jobs_model->getPropertiesForEmailInvoicing ();

        if ( !empty($data['properties']) ) {
            foreach ( $data['properties'] as $key => $val ) {

                $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForEmailInvoicing ( $val['property_id'] );

                if ( !empty ( $data['property_units'] ) ) {
                    foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                        $pdf_attachment = array ();

                        $bills = $this->bms_fin_cron_jobs_model->getBillsList ($val_unit['unit_id'], 0);
                        $records = array ();
                        if ( !empty($bills) ) {
                            foreach ( $bills as $key_bills => $val_bills ) {
                                if (!empty($val_bills['bill_id'])) {
                                    $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val_bills['bill_id']);
                                    if (!empty($data['manual_bill']['bill_id'])) {
                                        $records[] = $this->bms_fin_bills_model->getBillItemsDetailEmailInvoicing ( $data['manual_bill']['bill_id'] );
                                    }
                                }
                            }
                        }

                        $data['manual_bill_items'] = call_user_func_array('array_merge', $records);
                        $data['invoices_per_unit'] = '1';
                        $m_pdf = new M_pdf();
                        $html = $this->load->view('finance/manual_bill/manual_bill_details_print_view',$data,true);
                        $m_pdf->pdf->WriteHTML ( $html );
                        $pdf_attachment[] = $m_pdf->pdf->Output('', 'S');
                        if ( !empty($val_unit['owner_email_addr']) && (filter_var($val_unit['owner_email_addr'], FILTER_VALIDATE_EMAIL)) && $val_unit['valid_email'] == 1 ) {
                            $filename = 'sales_invoice.pdf';
                            $this->load->library('email');
                            $this->email->clear(true);
                            $message = "Dear " . $val_unit['owner_name'] . ",<br><br>
                            Please find attached invoice for your kind reference.<br><br>
                            Thank you,<br><br>" .
                            $val['property_name'] . "<br>" .
                            "Management office <br><br>" .
                            "<i><u>This is an automated email, please do not reply to this message.</u></i><br>" .
                            "For any query you can contact us at:<br>" .
                            "<b>Email: </b><a mailto: " . $data['manual_bill']['email_addr'] . ">" . $data['manual_bill']['email_addr'] . "</a><br>" .
                            "<b>Phone number:  </b>" . $data['manual_bill']['phone_no'];
                            $result = $this->email
                            ->from( 'noreply@propertybutler.my' )
                            ->to($val_unit['owner_email_addr'])
                            //->to('yameenadnan@hotmail.com')
                            ->bcc('yameenadnan@hotmail.com','Nagarajan')
                            ->bcc('naguwin@gmail.com','Nagarajan')
                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' - Invoice' )
                            ->message ( $message );
                            if( !empty( $pdf_attachment ) ) {
                                foreach ($pdf_attachment as $kye_email) {
                                    $this->email->attach($kye_email, 'attachment', $filename, 'application/pdf');
                                }
                            }
                            $this->email->send ();
                            $bill_data = array (
                                'email_status' => 1
                            );
                            if ( !empty($bills) ) {
                                foreach ( $bills as $key_bills => $val_bills ) {
                                    if (!empty($val_bills['bill_id'])) {
                                        $this->bms_fin_bills_model->updateBill ( $bill_data, $val_bills['bill_id'] );
                                    }
                                }
                            }

                            sleep (5);
                        } else {
                            $data_unit = array (
                                'valid_email' => 0
                            );
                            $this->bms_masters_model->update_unit_set_invalid_email( $data_unit, $val_unit['unit_id'] );

                            $bill_data = array (
                                'email_status' => 1
                            );
                            if ( !empty($bills) ) {
                                foreach ( $bills as $key_bills => $val_bills ) {
                                    if (!empty($val_bills['bill_id'])) {
                                        $this->bms_fin_bills_model->updateBill ( $bill_data, $val_bills['bill_id'] );
                                    }
                                }
                            }
                            $this->generate_log ('email_invoices', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check owner email in unit setup.');
                        }
                    }
                }
            }
        }
    }













    // Real invoices starts here
    // Real invoices starts here
    // Real invoices starts here

    public function create_sc_sf_invoices_live () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_live () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 628;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 28-Feb-2020)";

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period 01-03-2020 - 28-01-2021)";

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'];
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.9310626475568168 * $val_unit['share_unit'];
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Real invoices ends here
    // Real invoices ends here
    // Real invoices ends here






































    // Fake invoices batch 1 starts here
    // Fake invoices batch 1 starts here
    // Fake invoices batch 1 starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');


                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');


                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_insurance_invoices () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period 01-03-2020 - 28-01-2021";

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.9310626475568168 * $val_unit['share_unit'];
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }

    // Developer SC Invoice
    // Developer SC Invoice
    // Developer SC Invoice
    public function create_sc_sf_invoices_developer () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    public function create_insurance_invoices_developer () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 28-Feb-2021";

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'];
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-03-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise () {
        $limit = 1;

        $unit_ids = array (
            30246,30249,30250,30253,30254,30256,30257,30261,30262,30265,30266,30269,30273,30274,30275,30277,30282,30284,30285,30286,30289,30290,30292,30293,30297,30298,30299,30301,30302,30304,30305,30307,30308,30311,30312,30313,30317,30321,30322,30323,30325,30328,30329,30330,30331,30332,30333,30334,30335,30336,30337,30338,30340,30341,30343,30344,30345,30346,30347,30348,30349,30352,30353,30354,30355,30356,30357,30359,30360,30365,30369,30375,30376,30381,30384,30388,30412,30429,30443
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');

                $html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');

                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                // $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');

                $this->m_pdf->pdf->Output('112233.pdf', 'D');
                die;
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
    }

    // Fake invoices batch 1 ends here
    // Fake invoices batch 1 ends here
    // Fake invoices batch 1 ends here












    // Fake invoices batch 2 starts here
    // Fake invoices batch 2 starts here
    // Fake invoices batch 2 starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices_batch2 () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch2 () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');


                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');


                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_insurance_invoices_batch2 () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-04-2020 - 28-01-2021)";

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 10;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }

    // Developer SC Invoice
    // Developer SC Invoice
    // Developer SC Invoice
    public function create_sc_sf_invoices_developer_batch2 () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    public function create_insurance_invoices_developer_batch2 () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 31-Mar-2020)";

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 2;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise_batch2 () {
        $limit = 1;

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');

                /*$html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
                $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
                die;*/
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
    }

    // Fake invoices batch 2 ends here
    // Fake invoices batch 2 ends here
    // Fake invoices batch 2 ends here



    // Real invoices Batch2 starts here
    // Real invoices Batch2 starts here
    // Real invoices Batch2 starts here

    public function create_sc_sf_invoices_batch2_live () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch2_live () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";

        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 628;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 31-Mar-2020)";

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-04-2020 - 28-01-2021)";

        $unit_ids = array (
            30242,30247,30248,30252,30255,30258,30263,30264,30267,30268,30270,30271,30272,30276,30278,30279,30280,30281,30287,30288,30294,30295,30296,30303,30309,30310,30316,30320,30324,30326,30350,30351,30361,30364,30368,30371,30372,30373,30374,30377,30378,30379,30380,30382,30383,30385,30389,30392,30398,30406,30410,30411,30414,30419,30422,30423,30431,30432,30437,30439,30444,30445,30450,30451,30453
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'] * 2;
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 10;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Real invoices Batch2 ends here
    // Real invoices Batch2 ends here
    // Real invoices Batch2 ends here















    // Fake invoices batch 3 starts here
    // Fake invoices batch 3 starts here
    // Fake invoices batch 3 starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices_batch3 () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch3 () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');


                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');


                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    public function create_insurance_invoices_batch3 () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-05-2020 - 28-01-2021)";

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 9;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }

    // Developer SC Invoice
    // Developer SC Invoice
    // Developer SC Invoice
    public function create_sc_sf_invoices_developer_batch3 () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    public function create_insurance_invoices_developer_batch3 () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 30-Apr-2020)";

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 3;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise_batch3 () {
        $limit = 1;

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');

                /*$html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
                $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
                die;*/
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
    }

    // Fake invoices batch 3 ends here
    // Fake invoices batch 3 ends here
    // Fake invoices batch 3 ends here



    // Real invoices Batch3 starts here
    // Real invoices Batch3 starts here
    // Real invoices Batch3 starts here

    public function create_sc_sf_invoices_batch3_live () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch3_live () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";

        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 628;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 31-Mar-2020)";

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-04-2020 - 28-01-2021)";

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'] * 3;
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 9;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Real invoices Batch3 ends here
    // Real invoices Batch3 ends here
    // Real invoices Batch3 ends here


























    // Fake invoices batch 1 selesa starts here
    // Fake invoices batch 1 selesa starts here
    // Fake invoices batch 1 selesa starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices_batch1_selesa () {
        $limit = 4;
        $sc_coa_id = 1704;
        $sf_coa_id = 1705;
        $sc_ca_coa_id = 1742;
        $sf_ca_coa_id = 1743;

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch1_selesa () {
        $limit = 1;
        $sc_coa_id = 1745;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1747;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1746;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1748;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1749;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1750;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((5.925921052631579 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.1382894736842105 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    public function create_insurance_invoices_batch1_selesa () {
        $limit = 1;

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (01-05-2020 - 28-01-2021)";

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 9;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    // Developer SC Invoice
    // Developer SC Invoice
    // Developer SC Invoice
    public function create_sc_sf_invoices_developer_batch1_selesa () {
        $limit = 4;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    public function create_insurance_invoices_developer_batch1_selesa () {
        $limit = 1;

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 30-Apr-2020)";

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 3;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise_batch1_selesa () {
        $limit = 1;

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details_selesa ($val_unit['unit_id'],$act_type = 'download');

                /*$html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
                $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
                die;*/
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
    }

    // Fake invoices batch 1 selesa ends here
    // Fake invoices batch 1 selesa ends here
    // Fake invoices batch 1 selesa ends here



    // Real invoices Batch1 selesa starts here
    // Real invoices Batch1 selesa starts here
    // Real invoices Batch1 selesa starts here

    public function create_sc_sf_invoices_batch1_live_selesa () {
        $limit = 4;
        $sc_coa_id = 1704;
        $sf_coa_id = 1705;
        $sc_ca_coa_id = 1742;
        $sf_ca_coa_id = 1743;

        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch1_live_selesa () {
        $limit = 1;
        $sc_coa_id = 1745;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1747;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1746;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1748;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";

        $electricity_coa_id = 1749;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1750;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 1706;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 30-Apr-2020)";

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (01-05-2020 - 28-01-2021)";;


        $unit_ids = array (
            30459,30461,30462,30463,30464,30465,30466,30467,30468,30469,30472,30487,30489,30490,30496,30497,30499,30500,30503,30504,30505,30506,30507,30508,30509,30512,30513,30514,30515,30517,30518,30520,30521,30523,30524,30525,30526,30528,30530,30531,30534,30535,30536,30540,30541,30542,30543,30544
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((5.925921052631579 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.1382894736842105 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'] * 3;
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 9;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Real invoices Batch1 selesa ends here
    // Real invoices Batch1 selesa ends here
    // Real invoices Batch1 selesa ends here


    public function manual_bill_details_selesa ($unit_id, $act_type = 'view') {
        $data['browser_title'] = 'Property Butler | Sales Invoice ';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice Details';

        $bills = $this->bms_fin_cron_jobs_model->getBillsList ($unit_id);

        $records = array ();
        if ( !empty($bills) ) {
            foreach ( $bills as $key => $val ) {
                if (!empty($val['bill_id'])) {
                    $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                    if (!empty($data['manual_bill']['bill_id'])) {
                        $records[] = $this->bms_fin_bills_model->getBillItemsDetail($data['manual_bill']['bill_id']);
                    }
                }
            }
        }

        $data['manual_bill_items'] = call_user_func_array('array_merge', $records);

        $act_type = 'download';
        $this->load->helper('number_to_word');
        if ( $act_type == 'download' ) {
            $this->load->library('M_pdf');
            return $html = $this->load->view('finance/manual_bill/manual_unit_details_print_selesa_view',$data,true);
        }
    }



































    // Fake invoices batch 2 selesa starts here
    // Fake invoices batch 2 selesa starts here
    // Fake invoices batch 2 selesa starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices_batch2_selesa () {
        $limit = 4;
        $sc_coa_id = 1704;
        $sf_coa_id = 1705;
        $sc_ca_coa_id = 1742;
        $sf_ca_coa_id = 1743;

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch2_selesa () {
        $limit = 1;
        $sc_coa_id = 1745;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1747;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1746;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1748;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";


        $electricity_coa_id = 1749;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1750;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((5.925921052631579 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.1382894736842105 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );


                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    public function create_insurance_invoices_batch2_selesa () {
        $limit = 1;

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (01-06-2020 - 28-01-2021)";

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 8;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    // Developer SC Invoice
    // Developer SC Invoice
    // Developer SC Invoice
    public function create_sc_sf_invoices_developer_batch2_selesa () {
        $limit = 4;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30454
            // 30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    // Developer Insurance Invoice (1 Month)
    public function create_insurance_invoices_developer_batch2_selesa () {
        $limit = 1;

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 31-May-2020)";

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 4;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise_batch2_selesa () {
        $limit = 1;

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details_selesa ($val_unit['unit_id'],$act_type = 'download');

                /*$html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
                $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
                die;*/
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
    }

    // Fake invoices batch 2 selesa ends here
    // Fake invoices batch 2 selesa ends here
    // Fake invoices batch 2 selesa ends here



    // Real invoices Batch2 selesa starts here
    // Real invoices Batch2 selesa starts here
    // Real invoices Batch2 selesa starts here

    public function create_sc_sf_invoices_batch2_live_selesa () {
        $limit = 4;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-04-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_batch2_live_selesa () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";

        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 1706;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 31-Mar-2020)";

        $insurance_coa_id = 1706;
        $insurance_coa_name = "Insurance Start Period (01-04-2020 - 28-01-2021)";

        $unit_ids = array (
            30454,30455,30456,30457,30458,30460,30470,30471,30473,30474,30475,30476,30477,30478,30479,30480,30481,30482,30483,30484,30485,30486,30488,30491,30492,30493,30494,30495,30498,30501,30502,30510,30511,30516,30519,30522,30527,30529,30532,30533,30537,30538,30539
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_selesa ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'] * 4;
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 8;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = '';
                    $prop_abbrev = 'RS';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 194,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Real invoices Batch2 selesa ends here
    // Real invoices Batch2 selesa ends here
    // Real invoices Batch2 selesa ends here





















    // Fake invoices Riyang Single Unit starts here
    // Fake invoices Riyang Single Unit starts here
    // Fake invoices Riyang Single Unit starts here

    // Resident invoices
    // Resident invoices
    // Resident invoices
    // Resident invoices

    public function create_sc_sf_invoices_single_unit () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;
        $property_id = '191';
        $prop_abbrev = 'RC';
        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');
                    $bill_date = '2020-06-01';
                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( $bill_date )) );
                    $period = date_format(date_create($bill_date),"M-y");

                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => $property_id,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_single_unit () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";
        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";
        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";
        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";
        $bill_date = '2020-06-01';
        $property_id = '191';
        $prop_abbrev = 'RC';
        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');

                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');


                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');


                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( $bill_date )) );
                    $period = '';

                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => $property_id,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Advance Payment for SC, SF, Electricity & Water. Non refundable.',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    public function create_insurance_invoices_single_unit () {
        $limit = 1;
        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-06-2020 - 28-01-2021)";
        $bill_date = '2020-06-01';
        $property_id = '191';
        $prop_abbrev = 'RC';

        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 8;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( $bill_date )) );
                    $period = '';

                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => $property_id,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );
                }
            }
        }
    }

    // Developer Insurance
    // Developer Insurance
    // Developer Insurance

    public function create_sc_sf_invoices_developer_single_unit () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC-LCP & SF-LCP / SC-CA & SF-CA - ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );

                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    // $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_insurance_invoices_developer_single_unit () {
        $limit = 1;

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (29-Jan-2020 - 31-May-2020)";

        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 4;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-06-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'Fire Insurance 2020.',
                        'total_amount' => $insurance_amount,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );



                }
            }
        }
    }



    public function get_invoices_unit_wise_single_unit () {
        $limit = 1;

        $unit_ids = array (
            30444
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {

                $html [] = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');

                /*$html11 = $this->manual_bill_details ($val_unit['unit_id'],$act_type = 'download');
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML( $html11 );
                $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
                $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
                die;*/
            }
        }

        if ( !empty ($html) ) {
            foreach ( $html as $key => $val ) {
                $this->m_pdf->pdf->AddPage();
                $this->m_pdf->pdf->WriteHTML($val);
            }
        }

        $this->m_pdf->pdf->Output('_invoice_batch2.pdf', 'D');
    }

    // Fake invoices Riyang Single Unit starts here
    // Fake invoices Riyang Single Unit starts here
    // Fake invoices Riyang Single Unit starts here



    // Real invoices Single Unit starts here
    // Real invoices Single Unit starts here
    // Real invoices Single Unit starts here

    public function create_sc_sf_invoices_single_unit_live () {
        $limit = 3;
        $sc_coa_id = 626;
        $sf_coa_id = 627;
        $sc_ca_coa_id = 897;
        $sf_ca_coa_id = 898;

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {
                    $service_charge = number_format((2.39 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = date_format(date_create($bill_date),"M-y");
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'SC, SF For ' . $period,
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array (
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Limited Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Service Charge For Common Property' . "(" . $period . ")",
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_period' => $period,
                        'item_descrip' => 'Sinking Fund For Common Property' . "(" . $period . ")",
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    public function create_advance_sc_sf_invoices_single_unit_live () {
        $limit = 1;
        $sc_coa_id = 1596;
        $sc_coa_name = "3 Month Service Charge Deposit For Limited Common Property";
        $sf_coa_id = 1598;
        $sf_coa_name = "3 Month Sinking Fund Deposit For Limited Common Property";

        $sc_ca_coa_id = 1597;
        $sc_ca_coa_name = "3 Month Service Charge Deposit For Common Property";
        $sf_ca_coa_id = 1599;
        $sf_ca_coa_name = "3 Month Sinking Fund Deposit For Common Property";

        $electricity_coa_id = 1600;
        $electricity_coa_name = "Common Property Utilities Deposit - TNB";

        $water_coa_id = 1601;
        $water_coa_name = "Common Property Utilities Deposit - SYABAS";

        $insurance_coa_id_dev = 628;
        $insurance_coa_name_dev = "Insurance Start Period (29-Jan-2020 - 31-Mar-2020)";

        $insurance_coa_id = 628;
        $insurance_coa_name = "Insurance Start Period (01-04-2020 - 28-01-2021)";

        $unit_ids = array (
            30243,30244,30245,30251,30259,30260,30283,30291,30300,30306,30314,30315,30318,30319,30327,30339,30342,30358,30362,30363,30366,30367,30370,30386,30387,30390,30391,30393,30394,30395,30396,30397,30399,30400,30401,30402,30403,30404,30405,30407,30408,30409,30413,30415,30416,30417,30418,30420,30421,30424,30425,30426,30427,30428,30430,30433,30434,30435,30436,30438,30440,30441,30442,30443,30446,30447,30448,30449,30452
        );
        $data['unit_detail'] = $this->bms_fin_bills_model->get_units_for_riyang ($unit_ids);
        if ( !empty( $data['unit_detail'] ) ) {
            foreach ( $data['unit_detail'] as $key_unit => $val_unit ) {
                for ( $i = 0; $i<$limit;$i++ ) {

                    $service_charge = number_format((2.39 * $val_unit['share_unit'] * 3), 2, '.', '');
                    $sinking_fund = number_format(((10 * $service_charge) / 100), 2, '.', '');

                    $service_charge_ca = number_format((0.27 * $val_unit['share_unit']*3), 2, '.', '');
                    $sinking_fund_ca = number_format(((10 * $service_charge_ca) / 100), 2, '.', '');

                    $electricity_amount = number_format((2.803997938026233 * $val_unit['share_unit'] ), 2, '.', '');
                    $water_amount = number_format((0.6441228019932413 * $val_unit['share_unit'] ), 2, '.', '');

                    $insurance_amount_dev = 0.0846420480289986 * $val_unit['share_unit'] * 3;
                    $insurance_amount_dev = number_format($insurance_amount_dev, 2, '.', '');

                    $insurance_amount = 0.0846420480289986 * $val_unit['share_unit'] * 9;
                    $insurance_amount = number_format($insurance_amount, 2, '.', '');

                    $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-05-01' )) );
                    $period = '';
                    $prop_abbrev = 'RC';
                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                    if (!empty ($last_bill_no)) {
                        $last_no = explode('/', $last_bill_no['bill_no']);
                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                    } else {
                        $bill_no_format = $bill_no_format . 1001;
                    }

                    $bill_data = array (
                        'property_id' => 191,
                        'block_id' => $val_unit['block_id'],
                        'unit_id' => $val_unit['unit_id'],
                        'bill_no' => $bill_no_format,
                        'bill_date' => $bill_date,
                        'bill_time' => date('H:i:s'),
                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . 14 . " day")),
                        'remarks' => 'DEPOSIT OF SC & SF, COMMON PROPERTY UTILITY DEPOSIT, INSURANCE (2020)',
                        'total_amount' => $service_charge + $sinking_fund + $service_charge_ca + $sinking_fund_ca + $electricity_amount + $water_amount + $insurance_amount + $insurance_amount_dev,
                        'bill_paid_status' => 0,
                        'bill_type' => 1,
                        'created_by' => 1541,
                        'created_date' => date('Y-m-d')
                    );
                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_coa_id,
                        'item_descrip' => $sc_coa_name,
                        'item_amount' => $service_charge,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_coa_id,
                        'item_descrip' => $sf_coa_name,
                        'item_amount' => $sinking_fund,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sc_ca_coa_id,
                        'item_descrip' => $sc_ca_coa_name,
                        'item_amount' => $service_charge_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $service_charge_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $sf_ca_coa_id,
                        'item_descrip' => $sf_ca_coa_name,
                        'item_amount' => $sinking_fund_ca,
                        'paid_amount' => 0,
                        'bal_amount' => $sinking_fund_ca,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $electricity_coa_id,
                        'item_descrip' => $electricity_coa_name,
                        'item_amount' => $electricity_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $electricity_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $water_coa_id,
                        'item_descrip' => $water_coa_name,
                        'item_amount' => $water_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $water_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id_dev,
                        'item_descrip' => $insurance_coa_name_dev,
                        'item_amount' => $insurance_amount_dev,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount_dev,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                    $bill_item_data = array(
                        'bill_id' => $insert_id,
                        'item_cat_id' => $insurance_coa_id,
                        'item_descrip' => $insurance_coa_name,
                        'item_amount' => $insurance_amount,
                        'paid_amount' => 0,
                        'bal_amount' => $insurance_amount,
                        'paid_status' => 0
                    );
                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                }
            }
        }
    }

    // Fake invoices Riyang Single Unit ends here
    // Fake invoices Riyang Single Unit ends here
    // Fake invoices Riyang Single Unit ends here










































    public function manual_bill_details ($unit_id, $act_type = 'view') {
        $data['browser_title'] = 'Property Butler | Sales Invoice ';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice Details';

        $bills = $this->bms_fin_cron_jobs_model->getBillsList ($unit_id);

        $records = array ();
        if ( !empty($bills) ) {
            foreach ( $bills as $key => $val ) {
                if (!empty($val['bill_id'])) {
                    $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                    if (!empty($data['manual_bill']['bill_id'])) {
                        $records[] = $this->bms_fin_bills_model->getBillItemsDetail($data['manual_bill']['bill_id']);
                    }
                }
            }
        }

        $data['manual_bill_items'] = call_user_func_array('array_merge', $records);

        $act_type = 'download';
        $this->load->helper('number_to_word');
        if ( $act_type == 'download' ) {
            $this->load->library('M_pdf');
            return $html = $this->load->view('finance/manual_bill/manual_unit_details_print_view',$data,true);
        }
    }

    public function auto_billing_for_kiaraeast () {
        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');

        $data['properties'] = $this->bms_fin_cron_jobs_model->getPropertiesForAutoBillingByProperty (193);

        if ( !empty($data['properties']) ) {
            foreach ( $data['properties'] as $key => $val ) {
                if ( !empty ($val['billing_cycle']) && !empty ($val['calcul_base']) && !empty ($val['sinking_fund']) && !empty ($val['property_abbrev']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_service_charge_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sc');
                    $coa_sinking_fund_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'sf');
                    if ( !empty ($coa_service_charge_data) && !empty ($coa_sinking_fund_data) ) {
                        $bill_date = '';

                        if ( $val['calcul_base'] == 1 ) {
                            $verif_sq_units = $this->bms_fin_bills_model->verifyPropertyUnits ( 193, 'square_feet' );
                            if ( !empty($verif_sq_units) ) {
                                $this->generate_log ('auto_billing', $val['property_id'], '', 'Some units missing Unit No OR Square Feet OR Tier Name');
                                exit ();
                            }
                        } elseif ( $val['calcul_base'] == 2 ) {
                            $verif_su_units = $this->bms_fin_bills_model->verifyPropertyUnits ( 193, 'share_unit' );
                            if ( !empty($verif_sq_units) ) {
                                $this->generate_log ('auto_billing', $val['property_id'], '', 'Some units missing Unit No OR Square Feet OR Tier Name');
                                exit ();
                            }
                        } elseif ( $val['calcul_base'] == 3 ) {
                            if ( !empty($verif_sq_units) ) {
                                $this->generate_log ('auto_billing', $val['property_id'], '', 'Some units missing Unit No OR Square Feet OR Tier Name');
                                exit ();
                            }
                        }


                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling_kiaraeast ( $val['property_id'] );
                        if ( !empty( $data['property_units'] ) ) {
                            $limit = 1;
                            switch ( $val['billing_cycle'] ) {
                                case 2:
                                    $limit = 2;
                                    break;
                                case 3:
                                    $limit = 3;
                                    break;
                                case 4:
                                    $limit = 4;
                                    break;
                                case 5:
                                    $limit = 6;
                                    break;
                                case 6:
                                    $limit = 12;
                                    break;
                            }
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) && !empty ($val_unit['tier_value']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) && !empty($val_unit['tier_value']) ) || ($val['calcul_base'] == 3 && !empty($val['sc_charge']) ) ) ) {
                                    $pdf_attachment = array ();
                                    for ( $i = 0; $i<$limit;$i++ ) {
                                        $manual_bill_items = array ();
                                        $service_charge = 0;
                                        $sinking_fund = 0;

                                        switch ( $val['calcul_base'] ) {
                                            case 1:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['square_feet'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 2:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['share_unit'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 3:
                                                $service_charge = $val['sc_charge'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                        }

                                        $bill_date = date ("Y-m-d", strtotime("+$i month", strtotime ( '2020-02-01' )) );

                                        $period = date_format(date_create($bill_date),"M-y");

                                        $prop_abbrev = $val['property_abbrev'];
                                        $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                        $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                        if (!empty ($last_bill_no)) {
                                            $last_no = explode('/', $last_bill_no['bill_no']);
                                            $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                        } else {
                                            $bill_no_format = $bill_no_format . 1001;
                                        }

                                        $bill_data = array (
                                            'property_id' => $val['property_id'],
                                            'block_id' => $val_unit['block_id'],
                                            'unit_id' => $val_unit['unit_id'],
                                            'bill_no' => $bill_no_format,
                                            'bill_date' => $bill_date,
                                            'bill_time' => date('H:i:s'),
                                            'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                            'remarks' => 'SC & SF-' . $period,
                                            'total_amount' => $service_charge + $sinking_fund,
                                            'bill_paid_status' => 0,
                                            'bill_type' => 1,
                                            'created_by' => 1541,
                                            'created_date' => date('Y-m-d')
                                        );
                                        $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                        $data['manual_bill'] = array (
                                            'jmb_mc_name' => $val['jmb_mc_name'],
                                            'property_name' => $val['property_name'],
                                            'address_1' => $val['address_1'],
                                            'address_2' => $val['address_2'],
                                            'pin_code' => $val['pin_code'],
                                            'state_name' => $val_unit['state_name'],
                                            'country_name' => $val_unit['country_name'],
                                            'unit_no' => $val_unit['unit_no'],
                                            'bill_date' => $bill_date,
                                            'owner_name' => $val_unit['owner_name'],
                                            'bill_no' => $bill_no_format,
                                            'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                            'remarks' =>'SC & SF-' . $period,
                                            'first_name' => 'System',
                                            'last_name' => '',
                                            'created_date' => date('Y-m-d')
                                        );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_service_charge_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                            'paid_amount' => 0,
                                            'bal_amount' => $service_charge,
                                            'paid_status' => 0
                                        );

                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_sinking_fund_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                            'paid_amount' => 0,
                                            'bal_amount' => $sinking_fund,
                                            'paid_status' => 0
                                        );
                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        /*$items = array (
                                            'cat_name' => $coa_service_charge_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $items = array (
                                            'cat_name' => $coa_sinking_fund_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $data['manual_bill_items'] = $manual_bill_items;

                                        $m_pdf = new M_pdf();

                                        // Generate pdf and sed attach to email
                                        $sc_and_sf_PDF = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                        $m_pdf->pdf->WriteHTML ( $sc_and_sf_PDF );
                                        $pdf_attachment[] = $m_pdf->pdf->Output('', 'S');*/
                                    }

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        /*$filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                            "Email address for invoice is: " . $val_unit['email_addr'] .
                                            $val['jmb_mc_name'] . ", <br>" .
                                            $val['property_name'];
                                        $result = $this->email
                                            ->from( $val['email_addr'], $val['property_name'] )
                                            ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                            // ->to($val_unit['email_addr'])
                                            ->to('yameenadnan@hotmail.com')
                                            ->bcc('naguwin@gmail.com','Nagarajan')
                                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                            ->message ( $message );
                                        if( !empty( $pdf_attachment ) ) {
                                            foreach ($pdf_attachment as $kye_email) {
                                                $this->email->attach($kye_email, 'attachment', $filename, 'application/pdf');
                                            }
                                        }
                                        $this->email->send ();*/
                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check owner email in unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' OR \'tier_value\' OR \'sc_charge\'value. Check unit set up and property settings');
                                }
                            }
                        }
                        $sc_charged_date = date ("Y-m-d", strtotime("+1 month", strtotime('-1 day', strtotime ( $bill_date))) );

                        $data_prop = array (
                            'sc_charged_date' => $sc_charged_date,
                        );
                        $this->bms_fin_cron_jobs_model->update_property ( $data_prop, $val['property_id'] );
                    } else {
                        $this->generate_log ('auto_billing', $val['property_id'], '', 'Unable to find \'sc\' OR \'sf\' value in chart of Account. Check Chart Of Account');
                    }

                } else {
                    $this->generate_log ('auto_billing', $val['property_id'], '', '\'billing_cycle\' OR \'calcul_base\' OR \'sinking_fund\' OR \'property_abbrev\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings');
                }
            }
        }
    }

    public function generate_log ($script_name, $property_id, $unit_id = '', $log_message) {
        $custom_error_folder = FCPATH .'bms_uploads'.DIRECTORY_SEPARATOR.'custom_error_log'.DIRECTORY_SEPARATOR;
        $invalid_email_file = $custom_error_folder.'cron_job_error_msg.txt';
        $content = "\n\r". date('d-m-Y H:i:s') . "\n" . 'Script Name: ' . $script_name. "\n" . 'File Name: ' . basename(__FILE__) .  "\n" . 'property_id=>' . $property_id . ( (!empty($unit_id))? ', Unit_id=>' . $unit_id:'' ) . ', Error_msg=>' . $log_message;
        echo $log_message;
        file_put_contents($invalid_email_file,$content,FILE_APPEND);
    }

    public function set_email_addresses_inactivate () {
        /* connect to server */
        $hostname = '{mail.propertybutler.my:993/imap/ssl}INBOX';
        $username = 'noreply@propertybutler.my';
        $password = 'D3nyAcc355@@';

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mailbox: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox,'ALL');

        $email_list = array ();

        /* if emails are returned, cycle through each... */
        if ( $emails ) {

            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);
            $counter = 1;
            /* for every email... */
            foreach($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $message = imap_fetchbody($inbox,$email_number, '2');
                $message2 = imap_fetchbody($inbox,$email_number, '1');
                $header_info = imap_headerinfo ( $inbox, $email_number);

                if ( $overview[0]->subject == "Undelivered Mail Returned to Sender" ) {
                    if (
                        strpos($message2, "The email account that you tried to reach does not exist.") !== false || // gmail
                        strpos($message2, "This user doesn\'t have a yahoo.com account") !== false || // yahoo.com
                        strpos($message2, "This user doesn\'t have a ymail.com account") !== false || // ymail
                        strpos($message2, "Requested action not taken: mailbox unavailable.") !== false || // hotmail
                        strpos($message2, "Host or domain name not found.") !== false
                    ) {
                        $msg = explode ('Final-Recipient: rfc822; ', $message);
                        $msg1 = explode ('Original-Recipient: ', $msg[1]);
                        $msg1 = explode ('Original-Recipient: ', $msg[1]);

                        if ( !in_array ($msg1[0], $email_list) ) {
                            $email_list[] = $msg1[0];
                            // imap_delete ($inbox, $email_number );
                        }
                    }
                }
            }
            imap_expunge ( $inbox );
        }
        /* close the connection */
        imap_close($inbox);

        $invalid_email_list = "'" . implode ("','",$email_list) . "'";

        $this->bms_fin_cron_jobs_model->setValidEmailByEmailAddress ( $invalid_email_list );
    }

    public function check_email () {
        $this->load->library('email');
        $this->email->clear(true);
        $message = "Dear 
        Please find attached invoice for your kind reference.<br><br>
        Thank you,<br><br>";
        $result = $this->email
        ->from( 'noreply@propertybutler.my' )
        ->to('yameenadnan@hotmail.com','Adnan')
        ->bcc('naguwin@gmail.com','Nagarajan')
        ->subject ( 'Sendgrid email testing 1122' )
        ->message ( $message )
        ->send ();
    }





}