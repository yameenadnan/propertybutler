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
    <form role="form" id="report_new" action="<?php echo base_url('index.php/bms_monthly_report/add_service_provider_data_submit');?>" method="post" autocomplete="off">
          <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
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
                            <select class="form-control" id="property_id" disabled="disabled" name="property_id">
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

                          .input-group {
                              width: 100%;
                          }
                    </style>
                    <div class="col-md-3 col-xs-8">
                        <div class="input-group">
                          <select class="form-control" id="report_month" name="report_month" disabled="disabled" style="width: 100%;">
                              <?php echo monthDropdown($report_month);?>
                          </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-8">
                          <div class="input-group">
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
                </div>
              </div>

              <!-- /.box-body -->

         </div>
          <!-- /.box -->

        <div class="col-md-12 col-xs-12 no-padding block_div">
            <div class="row">
                <div class="col-md-1"><b>No</b></div>
                <div class="col-md-2"><b>Contractor</b></div>
                <div class="col-md-2"><b>Services</b></div>
                <div class="col-md-1"><b>Contract Amount</b></div>
                <div class="col-md-1"><b>Contract Period</b></div>
                <div class="col-md-1"><b>Assessment</b></div>
                <div class="col-md-3"><b>Remarks</b></div>
            </div>
            <?php
            if ( isset($service_provider) && count ($service_provider) > 0 ) {

                $counter = 1;
                foreach ($service_provider as $key => $val) { ?>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-1"><?php echo $counter; ?></div>
                        <input type="hidden" name="sevice_provider[report_service_provider_id][]" value="<?php echo ( isset($val['report_service_provider_id']) && $val['report_service_provider_id'] != '' )?$val['report_service_provider_id']:''; ?>">
                        <input type="hidden" name="sevice_provider[service_provider_id][]" value="<?php echo $val['service_provider_id']?>">
                        <div class="col-md-2"><?php echo $val['provider_name'];?></div>
                        <div class="col-md-2"><?php echo $val['service_provider_cat_name'];?></div>
                        <div class="col-md-1"><?php echo $val['annual_payment'];?></div>
                        <div class="col-md-1"><?php echo $val['contract_start_date'] . ' to ' . $val['contract_end_date'];?></div>
                        <div class="col-md-1">
                            <label for="sevice_provider" class="form-control-label"><input type="radio" name="sevice_provider[assessment_<?php echo $val['service_provider_id'];?>][]" value="Good" <?php echo ( isset($val['assessment']) && $val['assessment'] == 'Good' )?'checked="checked"':''; ?>>Good</label><br />
                            <label for="sevice_provider" class="form-control-label"><input type="radio" name="sevice_provider[assessment_<?php echo $val['service_provider_id'];?>][]" value="Average" <?php echo ( isset($val['assessment']) && $val['assessment'] == 'Average' )?'checked="checked"':''; ?>>Average</label>
                            <label for="sevice_provider" class="form-control-label"><input type="radio" name="sevice_provider[assessment_<?php echo $val['service_provider_id'];?>][]" value="Bad" <?php echo ( isset($val['assessment']) && $val['assessment'] == 'Bad' )?'checked="checked"':''; ?>>Bad</label>
                            <!-- input class="form-control pull-right" name="sevice_provider[assessment][]" type="text" value="" -->
                        </div>
                        <div class="col-md-3"><textarea name="sevice_provider[remarks][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"><?php echo ( isset($val['remarks']) && $val['remarks'] != '' )?$val['remarks']:''; ?></textarea></div>
                    </div>
                <?php
                    $counter++;
                    }
                } ?>

        </div>
        <div class="col-md-12" style="margin-bottom: 15px; padding-right: 0px; margin-top: 15px;">
            <button type="submit" class="btn  btn-primary pull-right" id="common_info_add_item_form" value="0" data-value="0" data-id="2" aria-invalid="false" style="margin-top: 15px;">Submit</button>
        </div>



    </section>
      </form>
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
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(function () {
//Date picker
    $('#datepicker').datepicker ({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });

    $( "#report_new" ).validate({
        rules: {
            <?php foreach ($service_provider as $key => $val) { ?>
            "sevice_provider[assessment_<?php echo $val['service_provider_id'];?>][]": "required",
            <?php } ?>
        },
        messages: {
            <?php foreach ($service_provider as $key => $val) { ?>
            "sevice_provider[assessment_<?php echo $val['service_provider_id'];?>][]": "Please enter assessment",
            <?php } ?>
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