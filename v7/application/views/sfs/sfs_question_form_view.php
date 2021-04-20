<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
            <!--small>Optional description</small-->
        </h1>      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

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
            
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_new" action="<?php echo base_url('index.php/bms_sfs/sfs_question_form_submit');?>" method="post">
                <input type="hidden" id="question_id" name="question_id" value="<?php echo !empty($question_id) ? $question_id : '';?>" />
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Service Name *</label>
                                <select class="form-control" id="service_id" name="question_detail[service_id]">
                                    <option value="">Select</option>
                                    <?php
                                        foreach ($services as $key=>$val) {
                                            $selected = !empty($question_detail['service_id']) && trim($question_detail['service_id']) == $val['service_id'] ? 'selected="selected" ' : '';
                                            echo "<option value='".$val['service_id']."' ".$selected.">".$val['service_name']."</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8 col-xs-12">
                            <label>Question Title *</label>
                            <input type="text" name="question_detail[question_name]" value="<?php echo !empty($question_detail['question_name']) ? $question_detail['question_name'] : '';?>" class="form-control" placeholder="Enter Question Title" maxlength="250">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount </label>
                                <input type="text" name="question_detail[amount]" value="<?php echo !empty($question_detail['amount']) ? $question_detail['amount'] : '';?>" class="form-control" placeholder="Enter amount" maxlength="11">
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Input type </label>
                                <select name="question_detail[input_type]"class="form-control">
                                    <option value="">Select</option>
                                    <?php $input_type_array = $this->config->item('sfs_question_input_type');;
                                    foreach ( $input_type_array as $val_input ) {
                                        $selected = !empty( $question_detail['input_type'] ) && $question_detail['input_type'] == $val_input ? 'selected="selected"':'';
                                        ?>
                                        <option value="<?php echo $val_input;?>" <?php echo $selected; ?>><?php echo $val_input;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sequence no. *</label>
                                <input type="text" name="question_detail[sequence_no]" value="<?php echo !empty($question_detail['sequence_no']) ? $question_detail['sequence_no'] : '';?>" class="form-control" placeholder="Enter Amount" maxlength="11">
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Is required</label>
                                <select class="form-control" name="question_detail[is_required]">
                                    <option value="0" <?php echo !empty($question_detail['is_required']) && $question_detail['is_required'] == '0' ? 'selected="selected"':'';?> >No</option>
                                    <option value="1" <?php echo !empty($question_detail['is_required']) && $question_detail['is_required'] == '1' ? 'selected="selected"':'';?>>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label>Remarks </label>
                                <?php
                                $message = '';
                                if(!empty($question_detail['remarks'])) {
                                    $message = $question_detail['remarks'];
                                    $breaks = array("<br />","<br>","<br/>");
                                    $message = str_ireplace($breaks, "", $message);
                                }
                                ?>
                                <textarea name="question_detail[remarks]" class="form-control" rows="5" placeholder="Enter Question Remarks"><?php echo $message; ?></textarea>
                            </div>
                        </div>
                    </div>

                <!-- Routine Task -->
                <div class="row" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <!--div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task </h3>
                    </div-->
                  
                    <div class="row" style="padding-top: 15px;">

                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                            <div class="col-md-9 col-xs-6" style="">
                            </div>

                            <div class="col-md-3 col-xs-6" style="text-align:right;">
                                <div class="form-group">
                                  <div class="checkbox">
                                    <button type="button" class="btn btn-success add_question_item_btn" value="0" data-value="0">Add Question Items</button>
                                  </div>
                                </div>
                            </div> 
                         </div>                  
                    
                    </div> <!-- /.row -->
                    
                    <div class="col-md-12 col-xs-12 no-padding question_items_container" >
                    
                    <?php
                    if( !empty( $question_items ) ) { // Question items
                            foreach ($question_items as $key => $val) { ?>
                            
                            <div class="row question_item" style="padding: 0px 15px 15px 15px;">
                            	<div class="box-header with-border">
                            		<h3 class="box-title" style="font-weight: bold;">
                            			Question Items
                            		</h3>
                            	</div>
                            	<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                            		<div class="form-group">
                                        <label>Input type</label>
                                        <select class="form-control" name="question_items[input_type][]">
                                            <?php $input_type_array = $this->config->item('sfs_question_input_type');
                                            foreach ( $input_type_array as $val_input ) {
                                                $selected = !empty( $val['input_type'] ) && $val['input_type'] == $val_input ? 'selected="selected"':'';
                                                ?>
                                                <option value="<?php echo $val_input;?>" <?php echo $selected;?>><?php echo $val_input;?></option>
                                            <?php } ?>
                                        </select>
                            		</div>
                            	</div>
                            	<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                            		<div class="form-group">
                                        <label>Amount</label>
                                        <input name="question_items[amount][]" class="form-control" value="<?php echo !empty($val['amount']) ? $val['amount']:''; ?>" type="text">
                            		</div>
                            	</div>
                                <div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                                    <div class="form-group">
                                        <label>Price Guide</label>
                                        <input name="question_items[price_guide][]" class="form-control" value="<?php echo !empty($val['price_guide']) ? $val['price_guide']:''; ?>" type="text">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                                    <div class="form-group">
                                        <label>Sequence no.</label>
                                        <input name="question_items[item_sequence_no][]" class="form-control" value="<?php echo !empty($val['item_sequence_no']) ? $val['item_sequence_no']:''; ?>" type="text">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label>Question Item Details</label>
                                    <input type="hidden" name="question_items[question_item_id][]" value="<?php echo !empty($val['question_item_id']) ? $val['question_item_id']:'';?>" />
                                    <textarea class="form-control" name="question_items[question_item_detail][]" placeholder="Enter Question Item Detail"><?php echo !empty($val['question_item_detail']) ? $val['question_item_detail'] : '';?></textarea>
                                </div>
                                <div class="col-md-12 col-sm-4 col-xs-4 text-right" style="margin-top: 15px;">
                                    <button type="button" class="btn btn-danger delete_question_item_btn" data-value="<?php echo !empty($val['question_item_id']) ? $val['question_item_id']:'';?>">Delete Question Item</button>
                                </div>
                            </div>
                            
                    <?php  } } ?>
                    
                    </div> <!-- /.question_items_container_* -->
                    
                </div> <!-- /.row -->
                
                <!-- Add More Routine Task -->
                
                <div class="row">
                    <div class="col-md-12 col-xs-12" >
                        <p class="help-block"> * Required Fields.</p>
                    </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;
                    <button type="Reset" class="btn btn-default">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
            
            
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    /** Form validation */
    
    $("#bms_new" ).validate({
		rules: {
			"question_detail[service_id]": "required",
            "question_detail[question_name]":"required",
            "question_detail[sequence_no]":"required"
		},
		messages: {
			"question_detail[service_id]": "Please select Service Name",
            "question_detail[question_name]":"Please enter Question Title",
            "question_detail[sequence_no]":"Please enter Sequence no."
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

    $('.add_question_item_btn').click(function () {
        //console.log($(this).attr('data-value') + ' = '+$(this).attr('id') + ' = '+$(this).val());
        add_question_item();
    });
    
    function add_question_item () {
        var str = '<div class="row question_item" style="padding: 0px 15px 15px 15px;">';
        str += '<div class="box-header with-border">';
        str += '<h3 class="box-title" style="font-weight: bold;">';
        str += 'Question Items ';
        str += '</h3>';
        str += '</div>';
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<label>Input type</label>';
        str += '<select class="form-control" name="question_items[input_type][]">';
        <?php $input_type_array = $this->config->item('sfs_question_input_type');
        foreach ( $input_type_array as $val ) { ?>
            str += '<option value="<?php echo $val;?>"><?php echo $val;?></option>';
        <?php } ?>
        str += '</select>';
        str += '</div>';
        str += '</div>';       
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<label>Amount</label>';
        str += '<label><input class="form-control" type="text" name="question_items[amount][]" value=""></label>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<label>Price guide</label>';
        str += '<label><input class="form-control" type="text" name="question_items[price_guide][]" value=""></label>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<label>Item Sequence No.</label>';
        str += '<label><input class="form-control" type="text" name="question_items[item_sequence_no][]" value=""></label>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-12 col-sm-12 col-xs-12">';
        str += '<label>Item details *</label>';
        str += '<textarea name="question_items[question_item_detail][]" class="form-control" rows="5" placeholder="Enter Question Item Details"></textarea>';
        str += '</div>';
        str += '<div class="col-md-12 col-sm-4 col-xs-4 text-right" style="margin-top: 15px;">';
        str += '<button type="button" class="btn btn-danger delete_question_item_btn" data-value="">Delete Question Item</button>';
        str += '</div>';
        //str += '';
        str += '</div>'; 
        $('.question_items_container').append(str);

        $('.delete_question_item_btn').unbind("click");
        $('.delete_question_item_btn').bind ("click",function () {
            delete_question_item ($(this).attr('data-value'), $(this));
        });
    }
    
    function delete_question_item (question_item_id, question_item_object) {
        //console.log($(this).attr('data-value'));
        if (question_item_id == "") {
            question_item_object.closest(".question_item").remove();
        } else {
            if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
                $.ajax({
                    type:"post",
                    async: true,
                    url: '<?php echo base_url('index.php/bms_sfs/delete_question_item');?>/', // Reusing the same function from task creation
                    data: {'question_item_id':question_item_id},
                    //datatype:"json", // others: xml, json; default is html
                    beforeSend:function (){ $('.question_items_container').LoadingOverlay("show"); }, //
                    success: function(data) {
                        if ( data == 1 ) {
                            question_item_object.closest(".question_item").remove();
                            $('.question_items_container').LoadingOverlay("hide", true);
                        }
                    },
                    error: function (e) {
                        $('.question_items_container').LoadingOverlay("hide", true);
                        console.log(e);
                    }
                });
            }
        }
    }
    $('.delete_question_item_btn').bind ("click",function () {
       delete_question_item ($(this).attr('data-value'), $(this));
    });
});

</script>