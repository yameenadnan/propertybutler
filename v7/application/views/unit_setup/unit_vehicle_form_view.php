
    <div class="row content_area">
    <form role="form" id="bms_vehicle_frm" action="<?php echo base_url('index.php/bms_unit_setup/set_vehicle');?>" method="post" >
      <div class="col-md-12 col-xs-12" style="background-color: #ECF0F5;padding-top: 10px;">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Vehicle Details</b></h3>
                    </div>
                    <div class="box-body">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Vehicle No. *</label>
                                      <input type="text" name="vehicle[vehicle_no]" class="form-control" value="<?php echo isset($vehicle_info['vehicle_no']) && $vehicle_info['vehicle_no'] != '' ? $vehicle_info['vehicle_no'] : '';?>" placeholder="Enter Vehicle No." maxlength="25">
                                    </div>
                                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id;?>" />
                                    <input type="hidden" id="unit_id" name="vehicle[unit_id]" value="<?php echo $unit_id;?>"/>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" style="padding-right: 0;">                                    
                                        <div class="form-group">
                                            <label >Vehicle Type *</label>
                                            <select name="vehicle[vehicle_type]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                
                                                foreach ($vehicle_type as $key=>$val) { 
                                                    $selected = isset($vehicle_info['vehicle_type']) && trim($vehicle_info['vehicle_type']) == $key ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Make</label>
                                            <input type="text" name="vehicle[make]" class="form-control" value="<?php echo isset($vehicle_info['make']) && $vehicle_info['make'] != '' ? $vehicle_info['make'] : '';?>" placeholder="Enter Make" maxlength="25">  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                            <label>Model</label>                            
                                            <input type="text" name="vehicle[model]" class="form-control" value="<?php echo isset($vehicle_info['model']) && $vehicle_info['model'] != '' ? $vehicle_info['model'] : '';?>" placeholder="Enter Model" maxlength="25">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-6 no-padding" >                                    
                                        <div class="form-group">
                                            <label >Color</label>
                                            <input type="text" name="vehicle[color]" class="form-control" value="<?php echo isset($vehicle_info['color']) && $vehicle_info['color'] != '' ? $vehicle_info['color'] : '';?>" placeholder="Enter Color" maxlength="25"> 
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                
                    </div>
                    <div class="col-md-12 no-padding text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary owner_save_btn">Save</button> &ensp;&ensp;
                        <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                      </div>
                    </div>
                    
                    
                </div><!-- /.box-info -->  
                
                
                
            </div>
            </form>
      </div>
    
  <script>
  $(document).ready(function (){
    
    
    /** Form validation */   
    $( "#bms_vehicle_frm" ).validate({
		rules: {			
            "vehicle[vehicle_no]": "required",
            "vehicle[vehicle_type]": "required"
		},
		messages: {			
            "vehicle[vehicle_no]": "Please enter Vehicle No.",
            "vehicle[vehicle_type]": "Please select Vehicle Type"
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
		}
	
    
    });
    
});
  </script>