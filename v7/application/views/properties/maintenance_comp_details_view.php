<form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/set_maintenance_company/'.$asset_id);?>" method="post" enctype="multipart/form-data">
<input type="hidden" id="maint_comp_id" name="maint_comp_id" value="<?php echo $maint_comp_id;?>"/>
<input type="hidden" id="asset_id" name="mainten_comp[asset_id]" value="<?php echo $asset_id;?>"/>
<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
    <div class="col-md-12 col-sm-12 col-xs-12">                                    
        <div class="form-group">
            <label >Service Provider Name *</label>
            <select class="form-control" id="service_provider_id" name="mainten_comp[service_provider_id]">
                <option value="">Select Service Provider</option>
                
                <?php 
                    foreach ($service_providers as $key=>$val) { 
                          
                        echo "<option value='".$val['service_provider_id']."'>".$val['provider_name']."</option>";
                    } ?> 
            </select>
        </div>
    </div>
</div>

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

<script>
$('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
$( "#bms_frm" ).validate({
		rules: {
			"mainten_comp[supplier_name]": "required"
            
		},
		messages: {
			"mainten_comp[supplier_name]": "Please Select Service Provider Name"
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
    $(function () {    
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>