<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
     
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-danger msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
              <div class="box-body">
                  <div class="row">




                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/set_facility');?>" method="post" enctype="multipart/form-data">

                    <input type="hidden" id="facility_id" name="facility_id" value="<?php echo $facility_id;?>"/>

                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                    <label >Property Name *</label>
                                    <select class="form-control" id="property_id" name="facility[property_id]">
                                        <option value="">Select Property</option>
                                        <?php
                                        foreach ($properties as $key=>$val) {
                                            $selected = !empty($facility['property_id']) && $facility['property_id'] == $val['property_id'] ? 'selected="selected" ' : (!empty($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '');
                                            echo "<option value='".$val['property_id']."' ".$selected."' data-pname='".$val['property_name']."'>".$val['property_name']."</option>";
                                        } ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                
                                <div class="form-group">
                                    <label >Facility Name *</label>
                                  <input type="text" name="facility[facility_name]" class="form-control" value="<?php echo isset($facility['facility_name']) && $facility['facility_name'] != '' ? $facility['facility_name'] : '';?>" placeholder="Enter Facility Name" maxlength="200">
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12 no-padding">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Booking start from</label>
                                        <div class="input-group">
                                            <input type="text" name="facility[start_time]" value="<?php echo isset($facility['start_time']) && $facility['start_time'] != '' ? $facility['start_time'] : '';?>" class="form-control timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12 no-padding col-md-offset-2">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Booking end to</label>
                                        <div class="input-group">
                                            <input type="text" name="facility[end_time]" value="<?php echo !empty($facility['end_time']) ? date('h:i a', strtotime($facility['end_time'])) : '';?>" class="form-control timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                    <label>Booking slot</label><br />
                                    <div class="col-md-3 col-sm-3 col-xs-12 no-padding">
                                        <div class="form-group">
                                            <select class="form-control" style="max-width: 60px; display: inline;" name="facility[slot_hour]">
                                                <?php for ($i=1; $i<24; $i++) { ?>
                                                    <option <?php echo !empty($facility['booking_slot']) && date('H', strtotime($facility['booking_slot'])) == $i ? 'selected="selected"':'';?>><?php echo $i;?></option>
                                                <?php } ?>
                                            </select><span style="display: inline;">&nbsp;&nbsp;<b>(hr/s)</b></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12 no-padding">
                                        <div class="form-group">
                                            <select class="form-control" style="max-width: 60px; display: inline;" name="facility[slot_min]">
                                                <option <?php echo !empty($facility['booking_slot']) && date('i', strtotime($facility['booking_slot'])) == '00' ? 'selected="selected"':'';?>>00</option>
                                                <option <?php echo !empty($facility['booking_slot']) && date('i', strtotime($facility['booking_slot'])) == '30' ? 'selected="selected"':'';?>>30</option>
                                            </select><span style="display: inline;">&nbsp;&nbsp;<b>(min)</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right side column -->
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" style="margin: 10px 0 0 0;">
                                    <label >Deposit Required *</label> &ensp; &ensp;

                                    <label class="radio-inline">
                                        <input type="radio" name="facility[deposit_require]" value="1" <?php echo isset($facility['deposit_require']) && $facility['deposit_require'] == 1 ? 'checked="checked"' : '';?> >Yes &ensp; &ensp;
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="facility[deposit_require]" value="0" <?php echo isset($facility['deposit_require']) && $facility['deposit_require'] == 0 ? 'checked="checked"' : '';?> >No  &ensp; &ensp;
                                    </label>

                                </div>
                            </div>

                            <div class="col-md-12 amt_div" style="padding:0;display: <?php echo isset($facility['deposit_require']) && $facility['deposit_require'] == 1 ? 'block' : 'none';?>;">

                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" name="facility[amount]" class="form-control" value="<?php echo !empty($facility['amount']) ? $facility['amount'] : '';?>" placeholder="Enter Amount" maxlength="13">
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="col-md-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                        <label>Disclaimer </label>
                                        <?php
                                        $message = '';
                                        if(!empty($facility['disclaimer'])) {
                                            $message = $facility['disclaimer'];
                                            $breaks = array("<br />","<br>","<br/>");
                                            $message = str_ireplace($breaks, "", $message);
                                        }
                                        ?>
                                        <textarea name="facility[disclaimer]" class="form-control" rows="5" placeholder="Enter Disclaimer"><?php echo $message; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label for="">Picture</label>
                                        <div style="position:relative;">
                                            <label class="btn-bs-file btn btn-primary">
                                                Choose Picture...
                                                <input type="file" id="picture" name="picture" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                                            </label>
                                            &nbsp;
                                            <span class='label label-info' id="upload-file-info"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="picture_old" value="<?php echo !empty($facility['picture']) ? $facility['picture'] : '';?>" />
                                    <?php if ( !empty($facility['picture']) ) {
                                        $facility_picture_upload = $this->config->item('facility_picture_upload');
                                        ?>
                                        <div class="form-group">
                                            <label>Current Picture:</label><br />
                                            <img src="<?php echo base_url() . $facility_picture_upload['upload_path'].$facility['picture'];?>" width="150" />
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="col-md-12" >
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 text-right">
                                            <p class="help-block"> * Required Fields.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 text-right">
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                              </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    
                </div> <!-- /.row -->
                
            </div> <!-- /.box-body -->
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>
$(document).ready(function () {

    $('.timepicker').timepicker({
        showInputs: false,
        minuteStep: 30
    })

    $('input[name="facility[deposit_require]"]').change(function (){
        //amt_div
        $('.amt_div').css('display',($('input[name="facility[deposit_require]"]:checked').val() == 1 ? 'block' : 'none'));
    });

    jQuery.validator.addMethod("validTime", function(value, element){
        return false;
    }, "wrong Time");
});


$( "#bms_frm" ).validate({
		rules: {
            "facility[property_id]": "required",     
			"facility[facility_name]": "required",            
            "facility[deposit_require]": "required",
            "validTime":true
		},
		messages: {
		    "facility[property_id]": "Please select Property Name",     
			"facility[facility_name]": "Please enter Facility Name",
            "facility[deposit_require]": "Please select Deposit Required"
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
    
</script>