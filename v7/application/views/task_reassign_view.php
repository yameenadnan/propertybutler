<form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_task/set_reassign/'.$task_id);?>" method="post" enctype="multipart/form-data">

<input type="hidden" id="asset_id" name="task[task_id]" value="<?php echo $task_id;?>"/>
<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
    <div class="col-md-12 col-sm-12 col-xs-12">                                    
        <div class="form-group">
                          <label>Assign To *</label>
                          <select id="assign_to" name="task[assign_to]" class="form-control">
                            <option value="">Select</option>
                            <?php 
                    foreach ($assign_to as $key=>$val) { 
                          
                        echo "<option value='".$val['desi_id']."'>".$val['desi_name']."</option>";
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
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
$( "#bms_frm" ).validate({
		rules: {
			"task[assign_to]": "required"
		},
		messages: {
			"task[assign_to]": "Please select Assign To!"
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