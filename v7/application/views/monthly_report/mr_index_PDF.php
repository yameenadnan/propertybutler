<?php $this->load->view('header.php'); ?>
<?php if(!isset($act) || $act != 'pdf') { $this->load->view('sidebar.php'); } ?>

<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  body {background-image:url('<?php echo base_url();?>assets/images/pdf_bg.gif'); background-image-resize:6}
  </style>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background: none;">
    <?php if(!isset($act) || $act != 'pdf') { ?><!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
    </section>
    <?php } ?>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
          <div class="box box-primary box-pdf"><br /><br />
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?>>
                    <div class="row">
                        <div class="col-xs-12 col-md-12"><h3>Contents :</h3></div><br /><br />
                            <?php $counter = 1;
                                foreach ($report as $key => $val ) {
                                if ( $report[$key] == 1) {
                                    switch ($key) {
                                        case "balance_sheet":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Balance Sheet</div>';
                                            break;
                                        case "income_and_expenditure":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Income and Expenditure</div>';
                                            break;
                                        case "accouunt_summary":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Accouunt Summary</div>';
                                            break;
                                        case "fixed_asset_list":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Fixed Assets List</div>';
                                            break;
                                        case "cash_flow_statement":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Cash Flow Statement</div>';
                                            break;
                                        case "maintenance_fund_bank_reconciliation":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Bank Reconciliation - Maintenance Fund</div>';
                                            break;
                                        case "maintenance_fund_sinking_fund":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Bank Reconciliation - Sinking Fund Account</div>';
                                            break;
                                        case "bank_statement":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Bank Statement</div>';
                                            break;
                                        case "debtor_aging_report":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Debtor Aging Report</div>';
                                            break;
                                        case "creditor_aging_summary":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Creditor Aging Summary</div>';
                                            break;
                                        case "payment_summary":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Payment Summary</div>';
                                            break;
                                        case "utilities":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Utilities</div>';
                                            break;
                                        case "management_team":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Management Team</div>';
                                            break;
                                        case "management_team_attendance":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Staff Attendance</div>';
                                            break;
                                        case "service_provider_assessment":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Service Provider Assessment</div>';
                                            break;
                                        case "service_provider_attendance":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Service Provider Attendance</div>';
                                            break;
                                        case "annual_renewals":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Annual Renewals</div>';
                                            break;
                                        case "minor_tasks":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Minor Task</div>';
                                            break;
                                        case "common_info":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. General Info</div>';
                                            break;
                                        case "incident_report":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Incident Report</div>';
                                            break;
                                        case "major_task":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Recommendation / Action Plan</div>';
                                            break;
                                        case "asset_service_schedule":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Asset Service Schedule</div>';
                                            break;
                                        case "utility_report":
                                            echo '<div class="col-xs-12 col-md-12">' . $counter . '. Utility Report</div>';
                                            break;
                                    }
                                    $counter++;
                                }
                            } ?>

                            <!-- div class="col-xs-12 col-md-12">Utility Report</div -->


                    </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Modal -->


  <!-- bootstrap datepicker -->
<?php if (isset($act) && $act == 'pdf') {
        // echo '<div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated By <b>'.$_SESSION['bms']['full_name'].' ['.$gen_by_desi[0]['desi_name'].']</b> On <b>'. date('d-m-Y h:i:s a') .'</b></div>';
        echo '</body></html>';
} ?>