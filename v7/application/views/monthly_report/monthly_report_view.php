<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); ?>

<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #FFF;">
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
          <div class="box box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }

            ?>

              <form role="form" id="report_new" action="<?php echo base_url('index.php/bms_monthly_report/index');?>" method="get" autocomplete="off">
              <div class="box-body">
                <?php
                if (isset($act) && $act == 'pdf') {
                    foreach ($properties as $key=>$val) {
                        if(isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ) {
                            $selected_property_name = $val['property_name'];
                        }
                    }
                } ?>

                  <div class="row">

                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="property_id" name="property_id">
                            <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
                            <option value="">Select Property</option>
                            <?php } ?>
                            <?php

                                if ( (isset($_GET['property_id']) && trim($_GET['property_id']) != '')  ) {
                                    $property_id = $_GET['property_id'];
                                } elseif ( (isset($property_id) && trim($property_id) != '' ) ) {
                                    $property_id = $property_id;
                                } elseif ( isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ) {
                                    $property_id = $_SESSION['bms_default_property'] ;
                                }
                                $selected_property_name = '';
                                foreach ($properties as $key=>$val) {
                                    $selected = '';
                                    if ( $property_id == $val['property_id'] ) {
                                        $selected = 'selected="selected" ';
                                        $selected_property_name = $val['property_name'];
                                    }
                                    //$selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['PropertyId'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                } ?>
                              </select>

                          <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                        </div>
                    </div>
                    <style>
                        select {
                            width: 100% !important;
                        }
                    </style>
                    <div class="col-md-3 col-xs-8">
                        <div class="input-group" style="width: 100%">
                          <select class="form-control" id="report_month" name="report_month" style="width: 100%;">
                              <option value="">Month</option>
                              <?php
                              $report_month = (isset($report_month) && $report_month != '' )?$report_month:0;
                              echo monthDropdown($report_month);
                              ?>
                          </select>
                        </div>
                    </div>

                      <div class="col-md-3 col-xs-8">
                          <div class="input-group" style="width: 100%">
                              <select class="form-control" id="report_year" name="report_year">
                                  <option value="">Year</option>
                                  <?php
                                  for($i = 2018; $i <=date("Y"); $i++) {
                                      $selected = (isset($report_year) && $report_year != '' && $report_year == $i)?'selected="selected"':'';
                                      echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>' . PHP_EOL;
                                  }
                                  ?>
                              </select>
                          </div>
                      </div>


                    <div class="col-md-2 col-xs-4" style="">
                        <input type="submit" class="btn btn-primary" value="View">
                    </div>
                </div>
              </div>
              </form>

              <?php if ( isset($already_exist) && $already_exist == 'Yes'  ) { ?>
                  <div class="row report-container" style="margin: 0;padding-top:0px">
                      <div class="box-body">
                          Please click <a href="<?php echo base_url() . $file_location;?>" target="_blank">here </a> to view / download the report.<br /><br />
                          <input value="Re-generate report" class="btn btn-primary btn-regenerate-monthly-report" type="button">
                      </div>
                  </div>
              <?php } ?>

              <?php if ( isset($generate_report_status) && $generate_report_status == 'Download Report'  ) { ?>
                  <div class="row report-container" style="margin: 0;padding-top:0px">
                      <div class="box-body">
                          Please click <a href="<?php echo base_url() . $file_location;?>" target="_blank">here </a> to view / download the report.<br /><br />
                          <input value="Re-generate report" class="btn btn-primary btn-regenerate-monthly-report" type="button">
                      </div>
                  </div>
              <?php } ?>

              <!-- /.box-body -->
         </div>


            <?php if ( isset($property_id) && $property_id != '' && isset($report_month) && $report_month != '' && isset($report_year) && $report_year != '') { ?>
                <form method="post" id="generate_report_form" action="<?php echo base_url('index.php/bms_monthly_report/generate_PDF_report');?>" style="<?php echo ( isset($already_exist) && $already_exist == 'Yes'  )? 'visibility:hidden':''?><?php echo ( isset($generate_report_status) && $generate_report_status == 'Download Report'  )? 'visibility:hidden':''?>">
                    <input type="hidden" value="" id="generate_report_status" name="generate_report_status">
                    <input type="hidden" id="report_id" name="report_id" value="<?php echo $report_id;?>">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" checked="checked" class="prop_all_chk"><b>Select All</b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[balance_sheet]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Balance Sheet'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[income_and_expenditure]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Income and Expenditure'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[accouunt_summary]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Accouunt Summary'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[fixed_asset_list]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Fixed Assets List'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[cash_flow_statement]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Cash Flow Statement'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[maintenance_fund_bank_reconciliation]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Bank Reconciliation - Maintenance Fund'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[maintenance_fund_sinking_fund]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Bank Reconciliation - Sinking Fund Account'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[bank_statement]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Bank Statement'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[debtor_aging_report]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Debtor Aging Report'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[creditor_aging_summary]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Creditor Aging Summary'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[payment_summary]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Payment Summary'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[utilities]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Utilities'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[management_team]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Management Team'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[management_team_attendance]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Staff Attendance'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $CI =& get_instance();
                    $CI->load->model('bms_monthly_report_model');
                    ?>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[service_provider_assessment]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Service Provider Assessment'));?>
                                        &nbsp;&nbsp;&nbsp;[<a href="<?php echo base_url('index.php/bms_monthly_report/add_service_provider_data');?>/?report_id=<?php echo $report_id;?>">Input data</a>]
                                        <?php if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ($report_id, 'bms_service_provider_assessment') ) { ?>
                                            &nbsp;&nbsp;&nbsp;<i class="fa fa-check" aria-hidden="true"></i>
                                        <?php }    ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[service_provider_attendance]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Service Provider Attendance'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[annual_renewals]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('ANNUAL RENEWALS'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[minor_tasks]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Tasks / Complaints'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[common_info]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('General Info'));?>
                                        &nbsp;&nbsp;&nbsp;[<a href="<?php echo base_url('index.php/bms_monthly_report/add_common_info_data');?>/?report_id=<?php echo $report_id;?>">Input data</a>]
                                        <?php if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ($report_id, 'bms_report_common_info') ) { ?>
                                            &nbsp;&nbsp;&nbsp;<i class="fa fa-check" aria-hidden="true"></i>
                                        <?php }    ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[incident_report]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Incident Report'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[major_task]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Recommendation / Action Plan'));?>
                                        &nbsp;&nbsp;&nbsp;[<a href="<?php echo base_url('index.php/bms_monthly_report/add_major_task_data');?>/?report_id=<?php echo $report_id;?>">Input data</a>]
                                        <?php if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ($report_id, 'bms_report_major_task') ) { ?>
                                            &nbsp;&nbsp;&nbsp;<i class="fa fa-check" aria-hidden="true"></i>
                                        <?php }    ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input class="prop_chk" name="report[asset_service_schedule]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('ASSET SERVICE SCHEDULE'));?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="input-group" style="width: 100%">
                            <div class="checkbox" style="margin: 0;font-weight:bold;">
                                <label style="font-weight:bold;">
                                    <input class="prop_chk" name="report[utility_report]" value="1" type="checkbox" checked="checked"> <?php echo ucwords(strtolower('Utility Report'));?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">Water mark</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <input id="managed_by" name="report[managed_by]" value="<?php echo (isset($managed_by) && $managed_by != '')?$managed_by:'';?>" class="form-control" placeholder="" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-2 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">Prepared by</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <input id="prepared_by" name="report[prepared_by]" value="<?php echo (isset($prepared_by) && $prepared_by != '')?$prepared_by:'';?>" class="form-control" placeholder="" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="input-group" style="width: 100%">
                                <div class="checkbox" style="margin: 0;font-weight:bold;">
                                    <label style="font-weight:bold;">
                                        <input value="Generate report" class="btn btn-primary btn-generate-monthly-report" type="submit">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>

          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- bootstrap datepicker -->
<?php $this->load->view('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(function () {

    $('#property_id').change ( function () {
        window.location.href="<?php echo base_url('index.php/bms_monthly_report/index');?>/?property_id="+$(this).val();
        return false;
    });


//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });


    $( "#generate_report_form" ).validate({
        rules: {
            "report[managed_by]": "required"
        },
        messages: {
            "report[managed_by]": "Please enter Managed by"
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else if ( element.prop( "type" ) === "radio" ) {
                error.insertAfter( element.parent( "label" ).parent('div') );
            } else if ( element.hasClass("datepicker") ) {
                error.insertAfter( element.parent( "div" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }
    });

    jQuery(".prop_all_chk").change(function () {
        jQuery(".prop_chk").prop('checked', jQuery(this).prop("checked"));
    });

    $(".btn-generate-monthly-report").click(function (e) {
        e.preventDefault ();
        if ( $('#managed_by').val().trim() == '') {
            alert('Please key in managed by');
            $('#managed_by').focus();
            return;
        }
        $("body").LoadingOverlay("show");
        $("#generate_report_status").val('Download Report');
        setTimeout(submit_form, 50);
    });

    $(".btn-regenerate-monthly-report").click(function (e) {
        $("#generate_report_form").css({"visibility": "visible"});
    });

    $("body").LoadingOverlay("hide");
});

function submit_form() {
    $("#generate_report_form").submit();
}
</script>
<?php

function monthDropdown($selected=null)
{
    $dd = '';
    $months = array(
        1 => 'january',
        2 => 'february',
        3 => 'march',
        4 => 'april',
        5 => 'may',
        6 => 'june',
        7 => 'july',
        8 => 'august',
        9 => 'september',
        10 => 'october',
        11 => 'november',
        12 => 'december');
    /*** the current month ***/
    $selected = is_null($selected) ? date('n', time()) : $selected;

    for ($i = 1; $i <= 12; $i++)
    {
        $dd .= '<option value="'.$i.'"';
        if ($i == $selected)
        {
            $dd .= ' selected';
        }
        /*** get the month ***/
        $dd .= '>'.ucfirst ($months[$i]).'</option>';
    }
    return $dd;
}

?>