<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_human_resource/staff_profile_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-8 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:15px ;">
                                  <h3 class="box-title"><b>Staff Details</b></h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6 ">
                                        <div class="form-group">
                                          <label >First Name *</label>
                                            <input type="text" name="staff[first_name]" class="form-control" value="<?php echo isset($staff_info['first_name']) && $staff_info['first_name'] != '' ? $staff_info['first_name'] : '';?>" placeholder="Enter First Name" maxlength="150">
                                            <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $staff_id;?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Last Name</label>
                                          <input type="text" name="staff[last_name]" class="form-control" value="<?php echo isset($staff_info['last_name']) && $staff_info['last_name'] != '' ? $staff_info['last_name'] : '';?>" placeholder="Enter Last Name" maxlength="150">  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">                                    
                                        <div class="form-group">
                                            <label>Date Of Birth *</label>
                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right" name="staff[dob]" value="<?php echo isset($staff_info['dob']) && $staff_info['dob'] != '' ? date('d-m-Y',strtotime($staff_info['dob'])) : '';?>" id="datepicker" type="text" />
                                            </div>
                                            <!-- /.input group -->
                                          </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Mobile No</label>
                                          <input type="text" name="staff[mobile_no]" class="form-control" value="<?php echo isset($staff_info['mobile_no']) && $staff_info['mobile_no'] != '' ? $staff_info['mobile_no'] : '';?>" placeholder="Enter Mobile No" maxlength="15">  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Email Address *</label>
                                            <input type="email" id="email_addr" name="staff[email_addr]" class="form-control" value="<?php echo isset($staff_info['email_addr']) && $staff_info['email_addr'] != '' ? $staff_info['email_addr'] : '';?>" placeholder="Enter Email Address" maxlength="200">
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Password *</label>
                                          <input type="password" name="staff[password]" class="form-control" value="<?php echo isset($staff_info['password']) && $staff_info['password'] != '' ? $staff_info['password'] : '';?>" placeholder="Password" maxlength="50">  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Designation *</label>
                                            <select name="staff[designation_id]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                foreach ($designation as $key=>$val) { 
                                                    $selected = isset($staff_info['designation_id']) && trim($staff_info['designation_id']) != '' && trim($staff_info['designation_id']) == $val['desi_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['desi_id']."' ".$selected.">".$val['desi_name']."</option>";
                                                } ?> 
                                                                           
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Employment Type *</label>
                                          <select name="staff[emp_type]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                foreach ($emp_type as $key=>$val) { 
                                                    $selected = isset($staff_info['emp_type']) && trim($staff_info['emp_type']) != '' && trim($staff_info['emp_type']) == $val['emp_type_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['emp_type_id']."' ".$selected.">".$val['emp_type_name']."</option>";
                                                } ?> 
                                                                           
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >AL</label>
                                          <input type="text" name="staff_leave_ent[al]" class="form-control" value="<?php echo isset($staff_leave_ent['al']) && $staff_leave_ent['al'] != '' ? $staff_leave_ent['al'] : '';?>" maxlength="4">  
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >ML</label>
                                          <input type="text" name="staff_leave_ent[ml]" class="form-control" value="<?php echo isset($staff_leave_ent['ml']) && $staff_leave_ent['ml'] != '' ? $staff_leave_ent['ml'] : '';?>" maxlength="4">  
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >PL</label>
                                          <input type="text" name="staff_leave_ent[pl]" class="form-control" value="<?php echo isset($staff_leave_ent['pl']) && $staff_leave_ent['pl'] != '' ? $staff_leave_ent['pl'] : '';?>" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >CPL</label>
                                          <input type="text" name="staff_leave_ent[cpl]" class="form-control" value="<?php echo isset($staff_leave_ent['cpl']) && $staff_leave_ent['cpl'] != '' ? $staff_leave_ent['cpl'] : '';?>" maxlength="4">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >MC</label>
                                          <input type="text" name="staff_leave_ent[mc]" class="form-control" value="<?php echo isset($staff_leave_ent['mc']) && $staff_leave_ent['mc'] != '' ? $staff_leave_ent['mc'] : '';?>" maxlength="4">                       
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                          <label >MGL</label>
                                          <input type="text" name="staff_leave_ent[mgl]" class="form-control" value="<?php echo isset($staff_leave_ent['mgl']) && $staff_leave_ent['mgl'] != '' ? $staff_leave_ent['mgl'] : '';?>" maxlength="4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-top:15px;border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:10px ;">
                                  <h3 class="box-title"><b>Access Level</b></h3>
                                </div>
                                <div class="box-body" style="padding-top: 0px;">
                                    <?php 
                                    $assigned_module = array(); 
                                    if(!empty($staff_module)) {
                                        $assigned_module = explode(',',$staff_module['module_id']);
                                    }
                                    foreach ($module as $key=>$val) { 
                                        
                                        $checked = in_array($val['module_id'],$assigned_module) ? 'checked="checked"' : '';
                                        
                                        ?>
                                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                            <div class="form-group">
                                              <div class="checkbox">
                                                <label><input name="staff_module[]" value="<?php echo $val['module_id'];?>" type="checkbox" <?php echo $checked;?> class="module_chk"> <?php echo $val['module_name'];?> </label>
                                              </div>
                    
                                            </div>
                                        </div>
                                    <?php } ?>  
                                    
                                    
                                </div><!-- . box-body (inside one)-->
                            </div>
                            
                        </div>
                        
                        <div class="col-md-4 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 " style="border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:0px ;">
                                  <h3 class="box-title"><b>Work Details</b></h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Monday</label>
                                      <select name="staff_duty[monday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['monday']) && trim($staff_duty['monday']) != '' && trim($staff_duty['monday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Tuesday</label>
                                      <select name="staff_duty[tuesday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['tuesday']) && trim($staff_duty['tuesday']) != '' && trim($staff_duty['tuesday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Wenesday</label>
                                      <select name="staff_duty[wednessday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['wednessday']) && trim($staff_duty['wednessday']) != '' && trim($staff_duty['wednessday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Thursday</label>
                                      <select name="staff_duty[thrusday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['thrusday']) && trim($staff_duty['thrusday']) != '' && trim($staff_duty['thrusday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Friday</label>
                                      <select name="staff_duty[friday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['friday']) && trim($staff_duty['friday']) != '' && trim($staff_duty['friday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Saturday</label>
                                      <select name="staff_duty[saturday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['saturday']) && trim($staff_duty['saturday']) != '' && trim($staff_duty['saturday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Sunday</label>
                                      <select name="staff_duty[sunday]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                            foreach ($shift_pattern as $key=>$val) { 
                                                $selected = isset($staff_duty['sunday']) && trim($staff_duty['sunday']) != '' && trim($staff_duty['sunday']) == $val['work_duty_description'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['work_duty_description']."' ".$selected.">".$val['work_duty_description']."</option>";
                                            } ?> 
                                                                       
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 " style="margin-top:10px;padding-top:10px !important;border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:0px ;">
                                  <h3 class="box-title"><b>Assign Property</b></h3>
                                </div>
                                <div class="box-body"  style="padding:0px;margin-bottom:5px;max-height: 350px; overflow-y: scroll;">
                                
                                    <div class="form-group">
                                      <div class="checkbox">
                                        <label><input type="checkbox" class="prop_all_chk"><b>Select All</b></label>
                                      </div>
            
                                    </div>
                                    
                                    <?php
                                    $assigned_prop = array(); 
                                    if(!empty($staff_property)) {
                                        $assigned_prop = explode(',',$staff_property['property_id']);
                                    }
                                    foreach ($properties as $key=>$val) { 
                                        $checked = in_array($val['property_id'],$assigned_prop) ? 'checked="checked"' : '';
                                        ?>
                                        <div class="form-group">
                                          <div class="checkbox">
                                            <label><input name="staff_property[]" value="<?php echo $val['property_id'];?>" <?php echo $checked;?> type="checkbox" class="prop_chk"> <?php echo $val['property_name'];?> </label>
                                          </div>
                
                                        </div>
                                    <?php } ?>  
                                    
                                </div><!-- . box-body (inside one)-->
                                
                            </div>
                        </div> <!-- . right side box (shift patern & property -->
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row -->
                
              <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div>
          </div><!-- /.box-body -->
          
          <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
              </div>
            </div>
          </div>
        </form>
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>

$(document).ready(function () {
    
    /** Form validation */
    
    $( "#bms_frm" ).validate({
		rules: {
			"staff[first_name]": "required",
			"staff[dob]": "required",
            "staff[email_addr]":{
					   required: true,
					   email: true,
                       remote: {
                        url: "<?php echo base_url('index.php/bms_human_resource/check_email');?>",
                        type: "post",
                        data: { email_addr: function() { return $( "#email_addr" ).val(); },staff_id: function() { return $( "#staff_id" ).val(); } }
                      }
					},
            "staff[password]":"required",
            "staff[designation_id]":"required",
            "staff[emp_type]":"required"
		},
		messages: {
			"staff[first_name]": "Please enter First Name",
			"staff[dob]": "Please select Date Of Birth",
            "staff[email_addr]":{
					   required:"Please enter Email Address",
                       email: "Please enter valid Email Address",
                       remote:"Email Address exists already!"
					   },
            "staff[password]":"Please enter Password ",
            "staff[designation_id]":"Please select Designation",
            "staff[emp_type]":"Please select Employment Type"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "id" ) === "datepicker" ) {
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

    jQuery('.prop_chk').change(function(){ 
        if(false == jQuery(this).prop("checked")){ 
            jQuery(".prop_all_chk").prop('checked', false); 
        }                    
        if (jQuery('.prop_chk:checked').length == jQuery('.prop_chk').length ){
            jQuery(".prop_all_chk").prop('checked', true);
        }
    });
    
    $(function () {
        
       if (jQuery('.prop_chk:checked').length == jQuery('.prop_chk').length ){
            jQuery(".prop_all_chk").prop('checked', true);
        } 
        
    });    
   
             
});

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
</script>