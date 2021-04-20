<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <!--div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p-->
          <!-- Status -->
          <!--a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div-->

      <!-- search form (Optional) -->
      <!--form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">        
        <?php // section to setup active slidermenu 
            $dboard_act = $payment_act = $rfq_act = $property_act = $attendance_act = $task_act = $jmb_mc_act = $agm_egm_act = $sop_act
            = $daily_report_act = $user_access_log_act = $common_doc_cen_act = $sop_doc_act = $human_resource_act =  $unit_setup_act =
            $e_notice_act = $staff_eval_act = $staff_eval_award_act =  $home_butler_act = $incident_act = $monthly_report_act = $defect_act = $sfs_act = 0;
            
            $finance_act = $acc_recei_act = $acc_payable_act = $acc_reports_act = 0; 
            
            $s_val = $this->uri->segment(1);
            
            switch ($s_val) {
                 
                case 'bms_task': $task_act = 1; break;
                case 'bms_defect': $defect_act = 1; break;
                case 'bms_sop': $sop_act = 1; break;
                case 'bms_property': $property_act = 1; break;
                case 'bms_attendance': $attendance_act = 1; break;
                case 'bms_daily_report': $daily_report_act = 1; break;
                case 'bms_user_access_log': $user_access_log_act = 1; break;
                case 'bms_document_center': 
                    if(in_array($this->uri->segment('2'),array('sop_docs_list','add_sop_doc'))) $sop_doc_act = 1; 
                    else $common_doc_cen_act = 1; break;
                case 'bms_human_resource': $human_resource_act = 1; break;
                case 'bms_unit_setup': $unit_setup_act = 1; break;
                case 'bms_e_notice': $e_notice_act = 1; break;
                case 'bms_jmb_mc': $jmb_mc_act = 1; break;
                case 'bms_agm_egm': $agm_egm_act = 1; break;
                case 'bms_home_butler' : $home_butler_act = 1; break;                
                
                
                case 'bms_fin_bills': 
                case 'bms_fin_receipt' : 
                case 'bms_fin_debit_note': 
                case 'bms_fin_credit_note':
                case 'bms_fin_dp_receive':
                case 'bms_fin_dp_refund':
                        $finance_act = $acc_recei_act = 1; break; 
                
                
                case 'bms_fin_purchase_order' : 
                case 'bms_fin_expenses' : 
                case 'bms_fin_payment' : 
                case 'bms_fin_ap_cn_dn' :
                        $finance_act = $acc_payable_act = 1; break;
                
                case 'bms_fin_coa':
                case 'bms_charge_codes':
                case 'bms_chart_of_accounts': 
                case 'bms_fin_banks' :
                    $finance_act = $acc_reports_act= 1; break;
                    
                case 'bms_fin_accounting' :
                if($this->uri->segment(2) == 'debtor_aging_report') {
                    $finance_act = $acc_recei_act = 1; break; 
                } else if($this->uri->segment(2) == 'creditor_aging_report') {
                    $finance_act = $acc_payable_act = 1; break; 
                } else {
                    $finance_act = $acc_reports_act= 1; break;
                }
                        
                
                
                
                case 'bms_staff_eval': 
                    if($this->uri->segment('2') == 'award')
                        $staff_eval_award_act = 1; 
                    else 
                        $staff_eval_act = 1;    
                    break;
                case 'bms_dashboard': $dboard_act = 1; break;
                case 'bms_direct_pay': $payment_act = 1; break;
                case 'bms_rfq': $rfq_act = 1; break;
                case 'bms_incident': $incident_act = 1; break;
                case 'bms_monthly_report': $monthly_report_act = 1; break;
                case 'bms_sfs': $sfs_act = 1; break;
            }            
            
            //$user_access_log_menu = $this->config->item('');
        
        ?>   
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) { ?>     
        <!--li class="hidden-xs <?php echo $rfq_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_rfq/quotation_list');?>"><i class="fa fa-list-ol"></i><span> Request For Quotation </span></a></li-->
        <?php } ?>
        <?php if($_SESSION['bms']['user_type'] != 'developer' ) { ?>
        <li class="hidden-xs <?php echo $dboard_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i><span> Dashboard </span></a></li>
        <?php } ?>

        <?php
        /**
         *  online payment( Using QR code & Mobile Pay tab)
         *
         * */
         if($_SESSION['bms']['user_type'] == 'staff' && (in_array($_SESSION['bms']['staff_id'],array(1229, 1273, 1522)) || in_array($_SESSION['bms']['designation_id'], array(0)))) { ?>
        <li class="hidden-xs <?php echo $payment_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_direct_pay/payments');?>"><i class="fa fa-dollar"></i><span> Payments </span></a></li>
        <?php } ?>

        <?php
        /**
         *  Accounts module
         *
         * */
        //in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))
        if(($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['staff_id'],array(1229,1219,1273,1353,1309,1522,1337,1546,1520,1562,1581,1547,1584,1430,1615))) || in_array($_SESSION['bms']['designation_id'], array(10,27,28))) { ?>
            <li class="treeview <?php echo $finance_act ? 'active' : '';?>">
                  <a href="#"><i class="fa fa-usd"></i> <span>Accounts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu"> 
                    <li class="treeview <?php echo $acc_recei_act ? 'active' : '';?>">
                      <a href="#"><i class="fa fa-search-plus"></i> <span>Account Receivable</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                               
                        <li <?php echo in_array ($this->uri->segment(2), array('manual_bill_list','add_manual_bill','manual_bill_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_bills/manual_bill_list');?>"><i class="fa fa-file"></i>Sales Invoice</a></li>
                        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi'))) { ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('semi_auto_invoice_list')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_bills/semi_auto_invoice_list');?>"><i class="fa fa-file"></i>Semi Auto Invoices</a></li>
                        <?php } ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('meter_reading_list','meter_reading_list')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_bills/meter_reading_list');?>"><i class="fa fa-file"></i>Meter Reading</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('unapplied_amount_list')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_receipt/unapplied_amount_list');?>"><i class="fa fa-print"></i>Unapplied Amount</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('lpi_calc_list','lpi_calc_list')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_bills/lpi_calc_list');?>"><i class="fa fa-file"></i>LPI Calculations</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('receipt_list','add_receipt','receipt_details')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_receipt/receipt_list');?>"><i class="fa fa-print"></i>Receipts</a></li>    
                        
                        
                        <li <?php echo in_array ($this->uri->segment(2), array('receipt_summary')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_receipt/receipt_summary');?>"><i class="fa fa-list"></i>Receipt Summary</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('soa')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_bills/soa');?>"><i class="fa fa-list"></i>Statement Of Account</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('outstanding_invoices_list')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_bills/outstanding_invoices_list');?>"><i class="fa fa-list"></i>Outstanding invoices</a></li>

                        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi'))) { ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('credit_note_list','add_credit_note','credit_note_details')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_credit_note/credit_note_list');?>"><i class="fa fa-list"></i>Credit Note</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('debit_note_list','add_debit_note','debit_note_details')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_fin_debit_note/debit_note_list');?>"><i class="fa fa-list"></i>Debit Note</a></li>
                        <?php } ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('dp_receive_list','add_dp_receive','dp_receive_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_dp_receive/dp_receive_list');?>"><i class="fa fa-list"></i>Deposit Receive</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('dp_refund_list','add_dp_refund','dp_refund_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_dp_refund/dp_refund_list');?>"><i class="fa fa-list"></i>Deposit Refund</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('dp_receive_summary')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_dp_receive/dp_receive_summary');?>"><i class="fa fa-list"></i>Deposit Summary</a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('debtor_aging_report')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/debtor_aging_report"><i class="fa fa-book"></i><span>Debtor Aging Report</span></a></li>
                        <!--li class="<?php echo !empty($meter_reading) ? 'active' : '';?>"><a href="javascript:;"><i class="fa fa-thermometer"></i><span>Meter Reading</span></a></li-->
            		  </ul>
                    </li>
                    
                    <li class="treeview <?php echo $acc_payable_act ? 'active' : '';?>">
                      <a href="#"><i class="fa fa-search-plus"></i> <span>Account Payable</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                      </a>
                      <ul class="treeview-menu">
                        
                        <li <?php echo in_array ($this->uri->segment(2), array('purchase_list_view','purchase_add')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_purchase_order/purchase_list_view');?>"><i class="fa fa-list"></i>Purchase Orders</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('expenses_list','add_expenses')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_expenses/expenses_list');?>"><i class="fa fa-list"></i>Expense Invoices</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('payment_list','add_payment')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_payment/payment_list');?>"><i class="fa fa-list"></i>Payments</a></li>
                        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi'))) { ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('cn_list','add_cn', 'cn_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_ap_cn_dn/cn_list');?>"><i class="fa fa-list"></i>Credit Note</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('dn_list','add_dn', 'dn_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_ap_cn_dn/dn_list');?>"><i class="fa fa-list"></i>Debit Note</a></li>
                        <?php } ?>
                        <li <?php echo in_array ($this->uri->segment(2), array('payment_summary')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_payment/payment_summary');?>"><i class="fa fa-list"></i>Payment Summary</a></li>
                        <li <?php echo in_array ($this->uri->segment(2), array('expenses_summary')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_fin_expenses/expenses_summary');?>"><i class="fa fa-list"></i>Expenses Summary</a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('creditor_aging_report')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/creditor_aging_report"><i class="fa fa-book"></i><span>Creditor Aging Report</span></a></li>
            		  </ul>
                    </li>
                  
                  
                  <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi'))) { ?>
                  
                    <li class="treeview <?php echo $acc_reports_act ? 'active' : '';?>">
                      <a href="#"><i class="fa fa-search-plus"></i> <span>Finance</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                      </a>
                      <ul class="treeview-menu">  
                        
                        <li class="<?php echo in_array ($this->uri->segment(2), array('coa_list','coa_form')) ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_fin_coa/coa_list');?>"><i class="fa fa-code"></i><span>COA</span></a></li>
                        
                       
                        <li class="<?php echo in_array ($this->uri->segment(2), array('journal_entry','add_journal_entry')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/journal_entry"><i class="fa fa-book"></i><span>Journal Entry</span></a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('general_ledger')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/general_ledger"><i class="fa fa-book"></i><span>General Ledger</span></a></li>
                        
                        
                        
                        <li class="<?php echo in_array ($this->uri->segment(2), array('income_expenses')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/income_expenses"><i class="fa fa-book"></i><span>Income &amp; Expenses</span></a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('cash_flow')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/cash_flow"><i class="fa fa-book"></i><span>Cash Flow</span></a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('trail_balance')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/trail_balance"><i class="fa fa-book"></i><span>Trial Balance</span></a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('bal_sheet')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/balance_sheet"><i class="fa fa-book"></i><span>Balance Sheet</span></a></li>
                        <li class="<?php echo in_array ($this->uri->segment(2), array('bank_recon')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/bank_recon"><i class="fa fa-book"></i><span>Bank Reconciliation</span></a></li>
                        <!--li class="<?php echo in_array ($this->uri->segment(2), array('bank_trans_old_list', 'create_bank_trans_old')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/bank_trans_old_list"><i class="fa fa-book"></i><span>Bank Transaction (Old)</span></a></li-->
                        
                         
                        <!--li class="<?php echo in_array ($this->uri->segment(2), array('payment_receipt')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/payment_receipt"><i class="fa fa-book"></i><span>Receipt &amp; Payment</span></a></li-->
                        <!--li class="<?php echo in_array ($this->uri->segment(2), array('reminder_letter')) ? "active" : '';?>"><a href="<?php echo base_url();?>index.php/bms_fin_accounting/reminder_letter"><i class="fa fa-book"></i><span>Reminder Letter</span></a></li-->
                        
                        
                        
            		  </ul>
                    </li>
                  
                  <?php } ?>
                  </ul>
             </li>
        <?php } 
        /**
         *  End of Accounts module
         * 
         * */
        
         ?>

        <?php if($_SESSION['bms']['user_type'] != 'developer' ) { ?>
        <li class="treeview <?php echo $property_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-building"></i> <span>Property Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">    
           <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>                 
            <li <?php echo in_array($this->uri->segment(2),array('properties_list','add_property')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/properties_list');?>"><i class="fa fa-list"></i>Properties List</a></li>            
            <li <?php echo in_array($this->uri->segment(2),array('property_asset_list','add_asset')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/property_asset_list');?>"><i class="fa fa-clipboard"></i>Property Assets</a></li>
            <li <?php echo in_array($this->uri->segment(2),array('asset_service_schedule_list','asset_service_schedule')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/asset_service_schedule_list');?>"><i class="fa fa-calendar"></i>Asset Service Schedule</a></li>
            <li <?php echo in_array($this->uri->segment(2),array('asset_service_details_list','asset_service_details','asset_service_details_entry')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/asset_service_details_list');?>"><i class="fa fa-info"></i>Asset Service Entry</a></li>
            <li <?php echo in_array($this->uri->segment(2),array('annual_renewal_list','add_annual_renewal')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/annual_renewal_list');?>"><i class="fa fa-repeat"></i>Annual Renewals</a></li>
            <?php } ?>
            <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>             
            <li class="treeview <?php echo in_array($this->uri->segment(2),array('service_provider_list','add_service_provider','service_provider_cat_list','service_provider_cat_form')) ? 'active' : '';?>">
                <a href="#"><i class="fa fa-industry"></i><span>Service Provider</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                    <ul class="treeview-menu">            
                        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))) { ?>         
                            <li <?php echo in_array($this->uri->segment(2), array('service_provider_cat_list','service_provider_cat_form')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/service_provider_cat_list');?>"><i class="fa fa-certificate"></i>Category</a></li>
                        <?php } ?>
                        <li <?php echo in_array($this->uri->segment(2),array('service_provider_list','add_service_provider')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_property/service_provider_list');?>"><i class="fa fa-industry"></i>Providers</a></li>
                    </ul>
            </li>
            <?php } ?>
            <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>
                <li class="treeview <?php echo in_array($this->uri->segment(2), array('facility_list','add_facility', 'facility_booking_list', 'facility_booking_edit')) ? 'active' : '';?>">
                    <a href="#"><i class="fa fa-industry"></i><span>Facility for Booking</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li <?php echo in_array($this->uri->segment(2), array('facility_list','add_facility')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/facility_list');?>"><i class="fa fa-building-o"></i>Facilities</a></li>
                        <li <?php echo in_array($this->uri->segment(2), array('facility_booking_list', 'facility_booking_edit')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/facility_booking_list');?>"><i class="fa fa-ticket"></i>Bookings</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) { ?>
                <li <?php echo in_array($this->uri->segment(2), array('resi_notice_list','add_resi_notice')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_property/resi_notice_list');?>"><i class="fa fa-envelope-open"></i>Resident Notice Board</a></li>
            <?php } ?>
           
           <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)))) { ?> 
            <li <?php echo in_array($this->uri->segment(2),array('docs_list','add_doc','docs_list_jmb')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_property/').($_SESSION['bms']['user_type'] == 'staff' ? 'docs_list' : 'docs_list_jmb' );?>"><i class="fa fa-file"></i>Property Documents</a></li>
            <?php } ?>
              
          </ul>
        </li>
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)) && !in_array($_SESSION['bms']['staff_id'],array(1615))) { ?>
            <li class="<?php echo $sop_doc_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_document_center/sop_docs_list');?>"><i class="fa fa-file"></i><span>Standard Operating Procedure</span></a></li>
        <?php } // user type staff ?>
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>
        <li class="treeview <?php echo $attendance_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-book"></i> <span>Attendance</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo $this->uri->segment(2) == 'capture' ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_attendance/capture');?>"><i class="fa fa-camera"></i>Capture</a></li>
            <li <?php echo $this->uri->segment(2) == 'report' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_attendance/report');?>"><i class="fa fa-list"></i>Report</a></li>
            
            <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))) { ?>
                <li <?php echo $this->uri->segment(2) == 'absence_report' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_attendance/absence_report');?>"><i class="fa fa-list"></i>Absence Report</a></li>
                <li <?php echo $this->uri->segment(2) == 'manual_attendance' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_attendance/manual_attendance');?>"><i class="fa fa-list"></i>Manual Attendance</a></li>
            <?php } ?>  
                         
          </ul>
        </li>
        <?php } ?>

        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))) { ?>
        <li class="treeview <?php echo $human_resource_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-cogs"></i> <span>Human Resource</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo in_array($this->uri->segment(2), array('staff_list','staff_profile')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_human_resource/staff_list');?>"><i class="fa fa-users"></i>Staff Setup</a></li>
            <li <?php echo in_array($this->uri->segment(2), array('notice_board')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_human_resource/notice_board');?>"><i class="fa fa-envelope-open"></i>Staff Notice Board</a></li>            
            <li <?php echo in_array($this->uri->segment(2), array('holiday_list','holiday_form')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_human_resource/holiday_list');?>"><i class="fa fa-undo"></i>Holiday Setup</a></li>
            <li <?php echo in_array($this->uri->segment(2), array('designation_list','designation_form')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_human_resource/designation_list');?>"><i class="fa fa-object-group"></i>Designation Setup</a></li>            
          </ul>
        </li>
        <?php } ?>
        <?php if ( !empty ( $_SESSION['bms']['access_mod'] ) ) { ?>
        <?php if(in_array('3',$_SESSION['bms']['access_mod']) || ($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'], array(28)))) { ?>

        <li class="<?php echo in_array($this->uri->segment(2), array('unit_list','add_unit')) ? 'active' : '';?>">
          <a href="<?php echo base_url('index.php/bms_unit_setup/unit_list');?>"><i class="fa fa-cog"></i> <span>Unit Setup</span>
          </a>
        </li>
        <!--<li class="<?php /*echo in_array($this->uri->segment(2), array('invalid_email_list')) ? 'active' : '';*/?>">
            <a href="<?php /*echo base_url('index.php/bms_unit_setup/invalid_email_list');*/?>"><i class="fa fa-envelope"></i> <span>Invalid / Inactive Emails</span></a>
        </li>-->
        <?php } ?>
        <?php } ?>
        <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>
        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) { ?>
        <li class="treeview <?php echo $e_notice_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-exclamation-triangle"></i> <span>e-Notice</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo in_array($this->uri->segment(2), array('notice_list')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_e_notice/notice_list');?>"><i class="fa fa-list"></i>e-Notice List</a></li>
            <li <?php echo $this->uri->segment(2) == 'create_notice' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_e_notice/create_notice');?>"><i class="fa fa-plus-square"></i>Create e-Notice</a></li>
            <li <?php echo $this->uri->segment(2) == 'notice_queue' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_e_notice/notice_queue');?>"><i class="fa fa-paper-plane"></i>e-Notice Queue</a></li>           
          </ul>
        </li>
        <?php } ?>
        
        <?php if(in_array('4',$_SESSION['bms']['access_mod'])) { ?>
        <li class="treeview <?php echo $jmb_mc_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-user-circle"></i> <span>JMB/MC</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo in_array($this->uri->segment(2), array('jmb_mc_list')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_jmb_mc/jmb_mc_list');?>"><i class="fa fa-list"></i>JMB/MC List</a></li>
            <li <?php echo $this->uri->segment(2) == 'add_jmb_mc' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_jmb_mc/add_jmb_mc');?>"><i class="fa fa-plus-square"></i>Add JMB/MC</a></li>
          </ul>
        </li>
       <?php } ?>

        <li class="treeview <?php echo $agm_egm_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-flickr"></i> <span>AGM/EGM</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) { ?>
                <li <?php echo $this->uri->segment(2) == 'agm_master' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_master');?>"><i class="fa fa-bandcamp"></i>AGM Master</a></li>
            <?php } ?>
            <li <?php echo in_array($this->uri->segment(2),array('add_agm','agm_list')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_list');?>"><i class="fa fa-bandcamp"></i>AGM Setup</a></li>
            <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id')) ) {  ?>
                <li <?php echo $this->uri->segment(2) == 'agm_agenda' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_agenda');?>"><i class="fa fa-bandcamp"></i>AGM Agenda</a></li>
                <!--li <?php echo $this->uri->segment(2) == 'agm_notice' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_notice');?>"><i class="fa fa-bandcamp"></i>AGM Notice</a></li-->
                <li <?php echo $this->uri->segment(2) == 'eligible_voters' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters');?>"><i class="fa fa-bandcamp"></i>Eligible Voters</a></li>
                <li <?php echo in_array($this->uri->segment(2), array('agm_attendance_report','agm_attendance')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report/0/5');?>"><i class="fa fa-bandcamp"></i>AGM Attendance</a></li>
                <!--li <?php echo $this->uri->segment(2) == 'agm_attendance' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report');?>"><i class="fa fa-bandcamp"></i>AGM Attendance Report</a></li-->
                <li <?php echo $this->uri->segment(2) == 'agm_process' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>"><i class="fa fa-bandcamp"></i>AGM Voting</a></li>
            <?php } ?>            
          </ul>
        </li>

        
        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi')) || in_array($_SESSION['bms']['designation_id'],array(2,3,9,18,27)))  { ?>        
            <li class="<?php echo in_array($this->uri->segment(2), array('meetings_list','add_meeting')) ? 'active' : '';?>">
              <a href="<?php echo base_url('index.php/bms_meetings/meetings_list');?>"><i class="fa fa-comment"></i> <span>Meetings</span>            
              </a>
            </li>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        
        
        
          <?php if ( !empty($_SESSION['property_under']) && $_SESSION['property_under'] == 3 ) { ?>
              <?php if (($_SESSION['bms']['user_type'] == 'developer') || in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi')) || in_array($_SESSION['bms']['designation_id'],array(2,3,9,18,27)) ) { ?>
                  <li class="treeview <?php echo $defect_act ? 'active' : '';?>">
                      <a href="#"><i class="fa fa-info-circle"></i> <span>Defect</span>
                          <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                      </a>
                      <ul class="treeview-menu">
                          <li <?php echo $this->uri->segment(2) == 'defect_list' ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_defect/defect_list');?>"><i class="fa fa-list"></i>Defect List</a></li>
                          <li <?php echo $this->uri->segment(2) == 'new_defect' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_defect/new_defect');?>"><i class="fa fa-plus-square"></i>Add Defect</a></li>
                      </ul>
                  </li>
              <?php } ?>
          <?php } ?>
        <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)))) { ?>
        <li class="treeview <?php echo $task_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-tasks"></i> <span>Minor Tasks</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo $this->uri->segment(2) == 'task_list' ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_task/task_list?task_status=O');?>"><i class="fa fa-list"></i>Minor Task List</a></li>
            <li <?php echo $this->uri->segment(2) == 'new_task' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_task/new_task');?>"><i class="fa fa-plus-square"></i>Add Minor Task</a></li>
            
          </ul>
        </li>
        <?php } ?>
        
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>        
        
        <li class="treeview <?php echo $sop_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-server"></i> <span>Routine Task</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li <?php echo in_array($this->uri->segment(2), array('sop_list','view_details')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sop/sop_list');?>"><i class="fa fa-list"></i>Routine Task List</a></li>
            <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) { ?>
            <li <?php echo $this->uri->segment(2) == 'new_sop' ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sop/new_sop');?>"><i class="fa fa-plus-square"></i>Add Routine Task</a></li>
            <?php } // user type staff ?> 
            
            <?php if($_SESSION['bms']['user_type'] == 'staff') { //&& in_array('6',$_SESSION['bms']['access_mod']) ?>
            <li <?php echo in_array($this->uri->segment(2), array('entry_list','keyin_entry')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sop/entry_list');?>"><i class="fa fa-edit"></i>Routine Task Entry</a></li>
            <?php } // user type staff ?>  
            <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && in_array('7',$_SESSION['bms']['access_mod']))) { ?>
            <li <?php echo in_array($this->uri->segment(2), array('sop_history')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sop/sop_history');?>"><i class="fa fa-history"></i>Routine Task History</a></li>
            <?php } // user type staff ?>   
            <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))) {  ?>
            <li <?php echo in_array($this->uri->segment(2), array('sop_copy')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sop/sop_copy');?>"><i class="fa fa-edit"></i>Routine Task Copy</a></li>
            <?php } // user type staff ?>     
          </ul>
        </li>
        
        
        <!--li class="dropdown notifications-menu <?php echo $dboard_act ? 'active' : '';?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o">Alert</i>
              <span class="label label-warning">10</span>
            </a>
        </li-->
        
        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('user_access_log_access_desi'))) { ?>
        <!--li class="<?php echo $user_access_log_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_user_access_log/user_access_log_list');?>"><i class="fa fa-expeditedssl"></i> <span>User Access Log</span></a></li-->
        <!--li class="treeview <?php echo $user_access_log_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-expeditedssl"></i> <span>User Access</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li <?php echo $this->uri->segment(2) == 'user_access_log_list' ? 'class="active"' : '';?>><a href="<?php echo  base_url('index.php/bms_user_access_log/user_access_log_list');?>"><i class="fa fa-list"></i>Logs</a></li>
            <li <?php echo $this->uri->segment(2) == 'user_access_log_matching' ? 'class="active"' : '';?>><a href="<?php echo  base_url('index.php/bms_user_access_log/user_access_log_matching')?>"><i class="fa fa-crosshairs"></i>Matching</a></li>                        
          </ul>
        </li-->
        <?php } ?>
        <?php } // user type staff ?>
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['staff_id'],array(1229,1219,1273,1522))) { ?>
            <li class="treeview <?php echo $incident_act ? 'active' : '';?>">
              <a href="#"><i class="fa fa-tasks"></i> <span>Incident report</span>
                  <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">
                  <li <?php echo $this->uri->segment(2) == 'incident_list' ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_incident/incident_list');?>"><i class="fa fa-list"></i>Incident report List</a></li>
                  <li <?php echo $this->uri->segment(2) == 'new_incident' ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_incident/new_incident');?>"><i class="fa fa-plus-square"></i>Add Incident</a></li>
              </ul>
            </li>
        <?php } ?>
        
        <?php if(in_array($_SESSION['bms']['designation_id'],$this->config->item('daily_report_access_desi')) || $_SESSION['bms']['user_type'] == 'jmb') { ?>
        <li class="<?php echo $daily_report_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_daily_report/index');?>"><i class="fa fa-folder"></i><span>Daily Report</span></a></li>
        <?php } ?>
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('daily_report_access_desi'))) { ?>
            <li class="<?php echo $monthly_report_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_monthly_report/index');?>"><i class="fa fa-folder"></i><span>Monthly report</span></a></li>
        <?php } ?>
        <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)) && !in_array($_SESSION['bms']['staff_id'],array(1615))) { ?>
            <li class="<?php echo $common_doc_cen_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_document_center/common_docs_list');?>"><i class="fa fa-file"></i><span>Common Document Center</span></a></li>
        <?php } // user type staff ?>
        <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],array(2,7,20)))) { ?>
        <!--li class="<?php echo $staff_eval_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_staff_eval/index');?>"><i class="fa fa-bullseye"></i><span>Staff Evaluation</span></a></li-->
        <?php } // user type staff ?>

       
        
        
        
         
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],array(7,14,15,20))) { ?>
        <!--li class="<?php echo $staff_eval_award_act ? 'active' : '';?>"><a href="<?php echo base_url('index.php/bms_staff_eval/award');?>"><i class="fa fa-trophy"></i><span>Staff Evaluation Award</span></a></li-->
        <?php } // user type staff ?>
        
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['staff_id'],array(1229,1219,1273,1521,1522))) { ?>
        <li class="treeview <?php echo $home_butler_act ? 'active' : '';?>">
          <a href="#"><i class="fa fa-home"></i> <span>Home Butler</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">            
            <li <?php echo in_array($this->uri->segment(2), array('vendor_cat_list','vendor_cat_form')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_home_butler/vendor_cat_list');?>"><i class="fa fa-certificate"></i>Vendor Category</a></li>
            <li <?php echo in_array($this->uri->segment(2), array('vendor_list','vendor_form')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_home_butler/vendor_list');?>"><i class="fa fa-gavel"></i>Vendors</a></li>
            
          </ul>
        </li>
        
        <?php } // user type staff ?>

        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['staff_id'],array(1229, 1273, 1522, 1521))) { ?>
          <li class="treeview <?php echo $sfs_act ? 'active' : '';?>">
              <a href="#"><i class="fa fa-home"></i> <span>Services</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">
                  <li <?php echo in_array($this->uri->segment(2), array('tsp_list','tsp_form')) ? 'class="active"' : '';?>><a href="<?php echo base_url('index.php/bms_sfs/tsp_list');?>"><i class="fa fa-certificate"></i>TSP Setup</a></li>
                  <li <?php echo in_array($this->uri->segment(2), array('sfs_company_list')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_sfs/sfs_company_list');?>"><i class="fa fa-gavel"></i>Topup Management</a></li>
                  <li <?php echo in_array($this->uri->segment(2), array('sfs_cat_list','sfs_cat_form')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_sfs/sfs_cat_list');?>"><i class="fa fa-gavel"></i>Category Setup</a></li>
                  <li <?php echo in_array($this->uri->segment(2), array('sfs_service_list','sfs_service_form')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_sfs/sfs_service_list');?>"><i class="fa fa-gavel"></i>Service Setup</a></li>
                  <li <?php echo in_array($this->uri->segment(2), array('sfs_question_list','sfs_question_form')) ? 'class="active"' : '';?> ><a href="<?php echo base_url('index.php/bms_sfs/sfs_question_list');?>"><i class="fa fa-gavel"></i>Question Setup</a></li>
              </ul>
          </li>
        <?php } ?>
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['staff_id'],array(1229, 1273, 1522, 1521))) { ?>
        <li class="<?php echo in_array($this->uri->segment(2), array('goto_services')) ? 'active' : '';?>">
          <a href="<?php echo base_url('index.php/bms_sfs_mo/goto_services');?>"><i class="fa fa-shopping-cart"></i> <span>Search For Service</span>
          </a>
        </li>
        <?php } ?>
       </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>