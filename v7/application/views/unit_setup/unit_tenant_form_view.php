
    <div class="row content_area">
    <form role="form" id="bms_owner_frm" action="<?php echo base_url('index.php/bms_unit_setup/set_tenant');?>" method="post" >
      <div class="col-md-12 col-xs-12" style="background-color: #ECF0F5;padding-top: 10px;">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Tenant Details</b></h3>
                    </div>
                    <div class="box-body">
                            
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Tenant Name *</label>
                                      <input type="text" name="owner[tenant_name]" class="form-control" value="<?php echo isset($owner_hist_info['tenant_name']) && $owner_hist_info['tenant_name'] != '' ? $owner_hist_info['tenant_name'] : '';?>" placeholder="Enter Tenant Name" maxlength="100">
                                    </div>
                                    <input type="hidden" name="unit_tenant_id" value="<?php echo $unit_owner_id;?>" />
                                    <input type="hidden" id="unit_id" name="owner[unit_id]" value="<?php echo $unit_id;?>"/>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Identity No *</label>
                                            <input type="text" name="owner[ic_passport_no]" class="form-control" value="<?php echo isset($owner_hist_info['ic_passport_no']) && $owner_hist_info['ic_passport_no'] != '' ? $owner_hist_info['ic_passport_no'] : '';?>" placeholder="Enter Identity No" maxlength="20">  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                            <label>Date Of Birth *</label>                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" name="owner[dob]" class="form-control" value="<?php echo isset($owner_hist_info['dob']) && $owner_hist_info['dob'] != '' && $owner_hist_info['dob'] != '0000-00-00' ? date('d-m-Y',strtotime($owner_hist_info['dob'])) : '';?>" type="text" />
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-3 col-sm-3 col-xs-6" style="padding-left: 0px;">                                    
                                        <div class="form-group">
                                            <label >Gender *</label>
                                            <select name="owner[gender]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $gender = $this->config->item('gender');
                                                foreach ($gender as $key=>$val) { 
                                                    $selected = isset($owner_hist_info['gender']) && trim($owner_hist_info['gender']) == $val ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6" style="padding-right: 0;">                                    
                                        <div class="form-group">
                                            <label >Race *</label>
                                            <select name="owner[race]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $race = $this->config->item('race');
                                                foreach ($race as $key=>$val) { 
                                                    $selected = isset($owner_hist_info['race']) && trim($owner_hist_info['race']) == $val ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                                } ?> 
                                            </select>  
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6" style="padding-right: 0;">
                                        <div class="form-group">
                                            <label>Religion *</label>                            
                                            <select name="owner[religion]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $religion = $this->config->item('religion');
                                                foreach ($religion as $key=>$val) { 
                                                    $selected = isset($owner_hist_info['religion']) && trim($owner_hist_info['religion']) == $val ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                        </div>
                                    </div>
                                    <div class="ccol-md-3 col-sm-3 col-xs-6 " style="padding-right: 0px;">
                                        <div class="form-group">
                                            <label>Nationality *</label>                            
                                            <select name="owner[nationality]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $countries = $this->config->item('countries');
                                                foreach ($countries as $key=>$val) { 
                                                    $selected = isset($owner_hist_info['nationality']) && trim($owner_hist_info['nationality']) == $val ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                            </select>  
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Contact No 1 *</label>
                                            <input type="text" name="owner[contact_1]" class="form-control" value="<?php echo isset($owner_hist_info['contact_1']) && $owner_hist_info['contact_1'] != '' ? $owner_hist_info['contact_1'] : '';?>" placeholder="Contact No 1" maxlength="15">  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                        <label >Contact No 2</label>
                                            <input type="text" name="owner[contact_2]" class="form-control" value="<?php echo isset($owner_hist_info['contact_2']) && $owner_hist_info['contact_2'] != '' ? $owner_hist_info['contact_2'] : '';?>" placeholder="Contact No 2" maxlength="15">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Email(Login) *</label>
                                            <input type="email" id="email_addr" name="owner[email_addr]" class="form-control" value="<?php echo isset($owner_hist_info['email_addr']) && $owner_hist_info['email_addr'] != '' ? $owner_hist_info['email_addr'] : '';?>" placeholder="Enter Email(Login)" maxlength="100">  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                            <label>Password *</label>                            
                                            <input type="password" name="owner[password]" class="form-control" value="<?php echo isset($owner_hist_info['password']) && $owner_hist_info['password'] != '' ? $owner_hist_info['password'] : '';?>" placeholder="Enter Password" maxlength="50">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label>Contract Start Date</label>                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" name="owner[start_date]" class="form-control" value="<?php echo isset($owner_hist_info['start_date']) && $owner_hist_info['start_date'] != '' && $owner_hist_info['start_date'] != '0000-00-00' ? date('d-m-Y',strtotime($owner_hist_info['start_date'])) : '';?>" type="text" />
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <!--div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                            <label>End Date</label>                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" name="owner[end_date]" class="form-control" value="<?php echo isset($owner_hist_info['end_date']) && $owner_hist_info['end_date'] != '' && $owner_hist_info['end_date'] != '0000-00-00' ? date('d-m-Y',strtotime($owner_hist_info['end_date'])) : '';?>" type="text" />
                                            </div>
                                            
                                        </div>
                                    </div-->
                                </div>
                    </div>
                    
                    
                </div><!-- /.box-info -->  
                
                
                <div class="box box-warning">
                 
                     <div class="box-header">
                        <h3 class="box-title"><b>Mailing Address</b></h3>
                     </div>
                     
                     <div class="box-body">
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                    <div class="form-group">
                                        <label >Address 1 *</label>
                                        <input type="text" name="owner[address_1]" class="form-control" value="<?php echo isset($owner_hist_info['address_1']) && $owner_hist_info['address_1'] != '' ? $owner_hist_info['address_1'] : '';?>" placeholder="Enter Address 1" maxlength="100">  
                            
                                    </div>
                                </div>
                        </div>
                            
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                    <div class="form-group">
                                        <label >Address 2</label>
                                        <input type="text" name="owner[address_2]" class="form-control" value="<?php echo isset($owner_hist_info['address_2']) && $owner_hist_info['address_2'] != '' ? $owner_hist_info['address_2'] : '';?>" placeholder="Enter Address 2" maxlength="100">  
                            
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                    <div class="form-group">
                                        <label >City *</label>
                                        <input type="text" name="owner[city]" class="form-control" value="<?php echo isset($owner_hist_info['city']) && $owner_hist_info['city'] != '' ? $owner_hist_info['city'] : '';?>" placeholder="Enter City" maxlength="100">  
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 " style="padding-right: 0px;">                                    
                                    <div class="form-group">
                                        <label >Post Code *</label>
                                        <input type="text" name="owner[postcode]" class="form-control" value="<?php echo isset($owner_hist_info['postcode']) && $owner_hist_info['postcode'] != '' ? $owner_hist_info['postcode'] : '';?>" placeholder="Enter Post Code" maxlength="20">  
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                    <div class="form-group">
                                        <label >State *</label>
                                        <input type="text" name="owner[state]" class="form-control" value="<?php echo isset($owner_hist_info['state']) && $owner_hist_info['state'] != '' ? $owner_hist_info['state'] : '';?>" placeholder="Enter State" maxlength="100">  
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 " style="padding-right: 0px;">                                    
                                    <div class="form-group">
                                        <label >Country *</label>
                                        <select name="owner[country]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $countries = $this->config->item('countries');
                                                foreach ($countries as $key=>$val) { 
                                                    $selected = isset($owner_hist_info['country']) && trim($owner_hist_info['country']) == $val ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                        </select>   
                                    </div>
                                </div>
                            </div>
                     
                     </div><!-- /.box-body -->   
                 </div><!-- /.box-warning --> 
                 
                 <div class="box box-success">
                    <div class="col-md-12 no-padding text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary owner_save_btn">Save</button> &ensp;&ensp;
                        <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                      </div>
                    </div>
                </div><!-- /.box-success -->
            </div>
            </form>
      </div>
    
  <script>
  $(document).ready(function (){
    
    
    /** Form validation */   
    $( "#bms_owner_frm" ).validate({
		rules: {			
            "owner[tenant_name]": "required",
            "owner[ic_passport_no]": "required",
            "owner[dob]": "required",
            "owner[race]": "required",
            "owner[religion]": "required",
            "owner[gender]": "required",
            "owner[nationality]": "required",
            "owner[contact_1]": "required",			
            "owner[email_addr]":{
					   required: true,
					   email: true/*,
                       remote: {
                        url: "<?php echo base_url('index.php/bms_unit_setup/check_email');?>",
                        type: "post",
                        data: { email_addr: function() { return $( "#email_addr" ).val(); },unit_id: function() { return $( "#unit_id" ).val(); } }
                      }*/
					},
            "owner[password]":"required",
            "owner[address_1]": "required",
            "owner[city]": "required",	
            "owner[postcode]": "required",	
            "owner[state]": "required",	
            "owner[country]": "required"
            
		},
		messages: {			
            "owner[tenant_name]": "Please enter Tenant Name",
            "owner[ic_passport_no]": "Please enter Identity No",
            "owner[dob]": "Please select Date Of Birth",
            "owner[race]": "Please select Race",
            "owner[religion]": "Please select Religion",
            "owner[gender]": "Please select Gender",
            "owner[nationality]": "Please select Nationality ",
            "owner[contact_1]": "Please enter Contact No 1",			
            "owner[email_addr]":{
					   required:"Please enter Email Address",
                       email: "Please enter valid Email Address"/*,
                       remote:"Email Address exists already!"*/
					   },
            "owner[password]":"Please enter Password",
            "owner[address_1]": "Please enter Address 1",
            "owner[city]": "Please enter City",	
            "owner[postcode]": "Please enter Post Code",	
            "owner[state]": "Please enter State ",	
            "owner[country]": "Please select Country"
            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.hasClass ( "datepicker" ) ) {
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
		}/*,
        submitHandler: function(form,e) {
           $(".content_area").LoadingOverlay("show"); 
           e.preventDefault();// using this page stop being refreshing 

          $.ajax({
            type: 'POST',
            url: '<?php echo base_url('index.php/bms_unit_setup/set_owners');?>',
            data: $('#bms_owner_frm').serialize(),
            success: function () {
              alert('form was submitted');
            }
          });

         
        }*/
	});
    /*$('#bms_owner_frm').bind('click', function (event) {

event.preventDefault();// using this page stop being refreshing 

          $.ajax({
            type: 'POST',
            url: '<?php echo base_url('index.php/bms_unit_setup/set_owners');?>',
            data: $('#bms_owner_frm').serialize(),
            success: function () {
              alert('form was submitted');
            }
          });

        });*/
    
  });
  
  $(function () {
//Date picker
    $('.datepicker').unbind('datepicker');  
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
  </script>