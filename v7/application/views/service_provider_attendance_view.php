<?php include_once('header.php');?>
<?php if(!isset($act) || $act != 'pdf') { include_once('sidebar.php'); } ?>

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
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?>>
                <form role="form" id="add_service_provider_attendance" action="<?php echo base_url('index.php/Bms_daily_report/add_service_provider_attendance_submit');?>" method="post" autocomplete="off">
                    <input class="form-control" value="<?php echo !empty($_GET['report_date']) ? $_GET['report_date'] : '';?>" name="date" type="hidden">
                    <input type="hidden" id="property_id_attendence" name="property_id_attendence" value="<?php echo $_GET['property_id']; ?>">



                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="col-md-3">Service Provider</th>
                            <th class="col-md-3">Headcount</th>
                            <th class="col-md-3">Attended</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ( !empty($service_providers) ) {
                            foreach ($service_providers as $key=>$val) {
                        ?>
                        <tr>
                            <td class="col-md-3">
                                <input class="form-control" type="hidden" name="service_provider_attendance[service_provider_id][]" value="<?php echo $val['service_provider_id'];?>">
                                <div class="input-group"><?php echo $val['provider_name'];?></div>
                            </td>
                            <td class="col-md-2">
                                <?php echo $val['head_count'];?>
                            </td>
                            <td class="col-md-3">
                                <input class="form-control" name="service_provider_attendance[head_count_attended][]" style="width: 80px;" value="">
                            </td>
                        </tr>
                        <?php }
                        } ?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">

                                <input class="btn btn-primary add_btn pull-right" value="Submit" type="submit">

                        </div>
                    </div>
                </form>

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
<?php if(isset($act) && $act == 'pdf') {
        echo '<div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated By <b>'.$_SESSION['bms']['full_name'].' ['.$gen_by_desi[0]['desi_name'].']</b> On <b>'. date('d-m-Y h:i:s a') .'</b></div>';
        echo '</body></html>';
    } else { ?>
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

$(document).ready(function () {

    /** Form validation */
    $( "#add_service_provider_attendance" ).validate({
        rules: {
            "service_provider_attendance[head_count_attended][]": "required",
        },
        messages: {
            "service_provider_attendance[head_count_attended][]": "Please key in attended",
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else if ( element.hasClass( "datepicker" ) ) {
                error.insertAfter( element.parent( "div" ) );
            } else if ( element.prop( "id" ) === "datepicker" ) {
                error.insertAfter( element.parent( "div" ) );
            }
            else {
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

});

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });

    $('#datepicker1').datepicker({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });

});
</script>
<?php } ?>