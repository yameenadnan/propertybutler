<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
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
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
            ?>
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_staff_eval/eval_submit');?>" method="post" >
            
              <input type="hidden" name="eval[hr_staff_id]" value="<?php echo $_SESSION['bms']['staff_id'];?>" />
                
              <div class="box-body">
                <?php 
                //echo $eval_status; exit;
                if(!in_array($eval_status, array(0,6))) {
                    echo '<div class="alert alert-warning" style="margin-top:5px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$eval_message[$eval_status].'</div>';
                    //unset($_SESSION['flash_msg']);
                } else {
                
                ?>
                  <div class="row">
                  
                    <div class="col-md-5 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="property_id" name="eval[property_id]">
                            
                            <?php 
                                $selected_property_name = '';
                                foreach ($properties as $key=>$val) {
                                    $selected = '';
                                    if(isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ){
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
                    
                    <div class="col-md-5 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="staff_id" name="eval[staff_id]">
                            
                            <option value="">Select Staff</option>
                            
                            <?php 
                                
                                foreach ($mgt_staffs as $key=>$val) {
                                    $selected = '';
                                    if(isset($_GET['staff_id']) && trim($_GET['staff_id']) != '' && trim($_GET['staff_id']) == $val['staff_id'] ){
                                        $selected = 'selected="selected" ';                                        
                                    }
                                    //$selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['PropertyId'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['staff_id']."' ".$selected.">".$val['first_name']. " " .$val['last_name']." [". $val['desi_name'] ."]</option>";
                                } ?> 
                              </select>
                
                          <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                        </div>
                    </div>
                    
                </div>
                
                
                
                <div class="row report-container" style="margin: 0;">
                
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">1. Punctuality</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                            <li>Quality of being on time.</li>                            
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[punctuality]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[punctuality]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[punctuality]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[punctuality]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[punctuality]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-9 col-sm-6 col-md-5">
                                <textarea name="eval[punctuality_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">2. Attendance</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                            <li>Reporting to work regularly.</li>
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[attendance]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attendance]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attendance]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attendance]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attendance]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-5 col-sm-6 col-md-5">
                                <textarea name="eval[attendance_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">3. Discipline</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                            <li>Code of behaviour and good interpersonal relationships with residents and colleagues.</li>
                         
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[discipline]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[discipline]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[discipline]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[discipline]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[discipline]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-5 col-sm-6 col-md-5">
                                <textarea name="eval[discipline_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">4. Communication</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                            <li>Ability to express and communicate with all level of management team and residents. </li>
                            <li>Able to express and interacts well with majority.</li>
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[communication]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[communication]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[communication]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[communication]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[communication]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-5 col-sm-6 col-md-5">
                                <textarea name="eval[communication_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">5. Attitude</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                           <li>Attitude towards work and staff exceptionally well mannered and good etiquette and commands respect from others.</li>
                            
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[attitude]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attitude]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attitude]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attitude]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[attitude]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-5 col-sm-6 col-md-5">
                                <textarea name="eval[attitude_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">6. Grooming</div>
                    <div class="col-xs-12 col-md-12" style="padding-top: 0;">
                        <ul>
                            <li>Personal grooming and compliance to uniform standards.</li>
                            
                        </ul>
                        <div style="padding-left: 25px;">
                            <label class="radio-inline">
                              <input type="radio" name="eval[grooming]" value="5">Very Good &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[grooming]" value="4">Good  &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[grooming]" value="3">Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[grooming]" value="2">Below Average &ensp; &ensp;
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="eval[grooming]" value="1">Poor &ensp; &ensp;
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-2 col-sm-2 col-md-1 control-label" style="padding: 5px 0;">Remarks:</label>                            
                            <div class="col-xs-5 col-sm-6 col-md-5">
                                <textarea name="eval[grooming_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                    </div>
                    
                    <div class="col-xs-12 col-md-12" style="padding:15px 0 0 25px;">
                        <div class="form-group"> 
                                                 
                            <label class="col-xs-12 col-sm-12 col-md-12 control-label" style="padding: 5px 0;">Additional comments / remarks / recommendations :</label>                            
                            <div class="col-xs-8 col-sm-8 col-md-6">
                                <textarea name="eval[addi_remarks]"  maxlength="2500" class="form-control inline remarks_txtarea" placeholder="Enter Remarks"></textarea>
                                <!--h6 class="pull-right" id="count_message">dsafdsf</h6-->
                            </div>                              
                        </div>
                        </div>
                    
                             
                </div>
              <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary eval_submit_btn">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
              </div>
            </div>
          </div>  
                
              </div><!-- /.box-body -->
              
              <?php } ?>
              
            </form>
          
           
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 
  <!-- bootstrap datepicker -->
 
<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(10000);
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_staff_eval/index');?>?property_id="+$('#property_id').val();
            return false;      
        }           
    });
    
    $( "#bms_frm" ).validate({
		rules: {
			"eval[property_id]": "required",
			"eval[staff_id]": "required",
            "eval[punctuality]":"required",
            "eval[attendance]":"required",
            "eval[discipline]":"required",
            "eval[communication]":"required",
            "eval[attitude]":"required",
            "eval[grooming]":"required"
		},
		messages: {
			"eval[property_id]": "Please select Property Name",
			"eval[staff_id]": "Please select Staff",
            "eval[punctuality]":"Please rate for Punctuality",
            "eval[attendance]":"Please rate for Attendance",
            "eval[discipline]":"Please rate for Discipline",
            "eval[communication]":"Please rate for Communication",
            "eval[attitude]":"Please rate for Attitude",
            "eval[grooming]":"Please rate for Grooming"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "label" ).parent('div') );
			} else if ( element.prop( "id" ) === "datepicker" ) {
				error.insertAfter( element.parent( "div" ) );
			}else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		}/*,
        submitHandler: function(form) {
           $("#content_area").LoadingOverlay("show"); 
           $( "#task_new").submit();
        }*/
	});
    
    $('.eval_submit_btn').click(function (){
        //window.location.href="<?php echo base_url('index.php/bms_daily_report/index').'?property_id='.(isset($_GET['property_id']) ? $_GET['property_id'] : '').'&eval_date='.(isset($_GET['eval_date']) ? $_GET['eval_date'] : '').'&act=pdf';?>";
        //$(this).attr("disabled", 'disabled');
        //return false;
    });
    
   
});

</script>