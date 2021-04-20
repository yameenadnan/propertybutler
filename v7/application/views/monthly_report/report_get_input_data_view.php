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

              <form role="form" id="generate_report_new" action="<?php echo base_url('index.php/bms_monthly_report/report_get_input_data');?>" method="post" autocomplete="off">
              <input type="hidden" name="report_id" value="<?php echo $insert_id;?>">
              <input type="hidden" name="managed_by_text" id="managed_by_text" class="managed_by_text" value="">
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?>>
                <?php
                if(isset($act) && $act == 'pdf') {
                    foreach ($properties as $key=>$val) {
                        if(isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ){
                            $selected_property_name = $val['property_name'];
                        }
                    }
                }?>


                  <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="property_id" name="property_id" disabled="disabled">
                            <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
                            <option value="">Select Property</option>
                            <?php } ?>
                            <?php
                                $selected_property_name = '';
                                foreach ($properties as $key=>$val) {
                                    $selected = '';
                                    if( isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ) {
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
                        <div class="input-group" style="width: 100%;">
                          <select class="form-control" id="report_month" name="report_month" style="width: 100%;" disabled="disabled">
                              <?php echo monthDropdown($report_month);?>
                          </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-8">
                          <div class="input-group" style="width: 100%;">
                              <select class="form-control" id="report_year" name="report_year" disabled="disabled">
                                  <option value="">Year</option>
                                  <?php
                                  for($i = 2018; $i <=date("Y"); $i++) {
                                      $selected = ($report_year == $i) ? 'selected="selected"':'';
                                      echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>' . PHP_EOL;
                                  }
                                  ?>
                              </select>
                          </div>
                    </div>

                        <?php

                        $CI =& get_instance();
                        $CI->load->model('bms_monthly_report_model');
                        if (
                                $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_service_provider_assessment' ) > 0 &&
                                $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_report_common_info' ) > 0 &&
                                $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_report_major_task' ) > 0
                        )
                        { ?>
                            <div class="col-md-2 col-xs-4" style="">
                                <input type="button" class="btn btn-primary generate_report_monthly" value="Generate report">
                            </div>
                        <?php } ?>
                </div>
              </div>
              </form>

              <!-- /.box-body -->

         </div>
          <!-- /.box -->
        <?php
        if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_service_provider_assessment' ) < 1 ) {
            ?>
            <div class="row">
                <div class="col-md-1 col-xs-12">1</div>
                <div class="col-md-11 col-xs-12"><a href="<?php echo base_url('index.php/bms_monthly_report/add_service_provider_data');?>/?report_id=<?php echo $insert_id;?>">Add service provider data</a></div>
            </div>
        <?php } ?>

        <?php if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_report_common_info' ) < 1 ) { ?>
        <div class="row">
            <div class="col-md-1 col-xs-12">2</div>
            <div class="col-md-11 col-xs-12"><a href="<?php echo base_url('index.php/bms_monthly_report/add_common_info_data');?>?report_id=<?php echo $insert_id;?>">Add common info</a></div>
        </div>
        <?php } ?>

        <?php if ( $CI->bms_monthly_report_model->check_if_insert__id_exists_in_service_provider_report_table ( $insert_id, 'bms_report_major_task' ) < 1 ) { ?>
        <div class="row">
            <div class="col-md-1 col-xs-12">3</div>
            <div class="col-md-11 col-xs-12"><a href="<?php echo base_url('index.php/bms_monthly_report/add_major_task_data');?>?report_id=<?php echo $insert_id;?>">Add major task</a></div>
        </div>
        <?php } ?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- bootstrap datepicker -->
<?php if(isset($act) && $act == 'pdf') {
        echo '<div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated By <b>'.$_SESSION['bms']['full_name'].' ['.$gen_by_desi[0]['desi_name'].']</b> On <b>'. date('d-m-Y h:i:s a') .'</b></div>';
        echo '</body></html>';
    } else { ?>
<?php $this->load->view('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script>
$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });

    $('.generate_report_monthly').click( function (e) {
        e.preventDefault();
        $('#myModal2').modal({show:true});
    });

    $('.generate-report-after-managed-by').click( function (e) {
        $('#myModal2').modal('toggle');
        e.preventDefault();
        $('.managed_by_text').val( $('#managed_by').val() );
        $('#generate_report_new').attr('action', "<?php echo base_url('index.php/bms_monthly_report/generate_PDF_report');?>").submit();
    });

});
</script>
<?php }

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
        $dd .= '>'.$months[$i].'</option>';
    }
    return $dd;
}

?>

<!--  MODEL POPUP  -->
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> <b>Managed by</b></h4>
            </div>
            <div class="modal-body modal-body2">
                <div class="col-md-4 text-right" style="margin-top: 6px;"><b>Managed by: </b></div>
                <div class="col-md-8"><input type="text" class="form-control" id="managed_by" name="managed_by"></div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default generate-report-after-managed-by" href="javascript:;">Generate report</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>