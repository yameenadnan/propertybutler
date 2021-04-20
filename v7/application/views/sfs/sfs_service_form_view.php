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
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
    <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {

            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }

    ?>
        <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_sfs/sfs_service_submit');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" id="service_id" name="service_id" value="<?php echo !empty($service_id) ? $service_id:'';?>" />
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="row">
        
            <div class="col-md-12 col-xs-12">
                <div class="box box-primary">
                
                    <div class="box-header with-border" style="padding-left:15px ;">
                      <h3 class="box-title"><b>Service Details</b></h3>
                    </div>
                    
                    <div class="box-body">
                    <div class="col-md-6 col-sm-6 col-xs-12 no-padding">

                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Service name *</label>
                                <input type="text" name="service_info[service_name]" class="form-control" value="<?php echo !empty($service_info['service_name']) ? $service_info['service_name'] : '';?>" placeholder="Ex: Aircond Installation" maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                              <label>Comission Amount</label>
                              <input type="text" name="service_info[commis_amount]" class="form-control" value="<?php echo !empty($service_info['commis_amount']) ? $service_info['commis_amount'] : '';?>" placeholder="Ex: 10.25" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label>Comission Percentage</label>
                                <input type="text" name="service_info[commis_percent]" class="form-control" value="<?php echo !empty($service_info['commis_percent']) ? $service_info['commis_percent'] : '';?>" placeholder="Ex: 10.25" maxlength="10">
                            </div>
                        </div>


                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label>Number of Questions *</label>
                                <input type="text" name="service_info[no_of_question]" class="form-control" value="<?php echo !empty($service_info['no_of_question']) ? $service_info['no_of_question'] : '';?>" placeholder="Ex: 10" maxlength="4">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label>Quote type *</label>
                                <select class="form-control" id="quote_type" name="service_info[quote_type]">
                                    <option value="1" <?php echo !empty ( $service_info['quote_type'] ) && $service_info['quote_type'] == '1' ? 'selected="selected"':'';?>>Open</option>
                                    <option value="2" <?php echo !empty ( $service_info['quote_type'] ) && $service_info['quote_type'] == '2' ? 'selected="selected"':'';?>>Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="service_info[amount]" class="form-control" value="<?php echo !empty($service_info['amount']) ? $service_info['amount'] : '';?>" placeholder="Ex. 12.5" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 col-xs-6">
                            <div class="form-group">
                                <label>Service type *</label>
                                <select class="form-control" id="service_type" name="service_info[service_type]">
                                    <option value="">Select</option>
                                    <?php $service_type_array = array (1=>'MO service', 2=>'Resident service');
                                    foreach ( $service_type_array as $key=>$val ) {
                                        $select = !empty ($service_info['service_type']) && $service_info['service_type'] == $key ? 'selected="selected"':''; ?>
                                        <option value="<?php echo $key;?>" <?php echo $select;?>><?php echo $val;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label style="display: block;">Picture</label>
                                <label class="btn-bs-file btn btn-primary">
                                    Choose Picture...

                                    <!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                    <input type="file" id="upload_picture" name="upload_picture" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                                </label>
                                <span class='label label-info' id="upload-file-info"></span>

                                <input type="hidden" name="picture_old" value="<?php echo !empty($service_info['picture']) ? $service_info['picture'] : '';?>" />
                                <?php if (!empty($service_info['picture'])) {
                                    $sfs_service_picture_upload = $this->config->item('sfs_service_picture_upload');
                                    ?>
                                    <div class="form-group">
                                        <label>Current Picture:</label><br />
                                        <img src="<?php echo $sfs_service_picture_upload['upload_path_output'] . $service_info['picture'];?>" width="150" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                        <div class="col-md-6">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group">
                                        <label>Select Categories</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <?php if ( !empty ( $categories ) ) {
                                    foreach ( $categories as $cat_key => $cat_val ) { ?>
                                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" <?php echo !empty($service_categories) && in_array($cat_val['cat_id'], $service_categories) ? 'checked="checked"':'';?> name="category[]" value="<?php echo !empty($cat_val['cat_id']) ? $cat_val['cat_id']:''; ?>">&nbsp;&nbsp;<?php echo !empty($cat_val['cat_name']) ? $cat_val['cat_name']:'';?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                     <div class="col-md-6 col-sm-12 col-xs-12 no-padding"  style="padding-top: 15px !important;">
                     </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 15px !important;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>General Info </label>
                                    <?php
                                    $message = '';
                                    if(!empty($service_info['general_info'])) {
                                        $message = $service_info['general_info'];
                                        $breaks = array("<br />","<br>","<br/>");
                                        $message = str_ireplace($breaks, "", $message);
                                    }
                                    ?>
                                    <textarea name="service_info[general_info]" class="form-control" rows="10" placeholder="Enter General Information"><?php echo $message; ?></textarea>
                                </div>
                            </div>
                          </div>

                          <div class="col-md-12 no-padding text-right">
                              <div class="box-footer">
                                  <button type="submit" class="btn btn-primary">Save</button> &ensp;&ensp;
                                  <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                              </div>
                          </div>
                    
                    </div><!-- /.box-body -->

                 </div><!-- /.box-primary -->  
            </div>
        </div><!-- /.row -->
        </form>
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
// Prevent for input type number and it increase / decrease on mouse wheel scroll  
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
// Stop enter key event on focus of input type number
$(document).on( 'keypress', 'input', function (evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
        return false;
    }    
});

$(document).ready(function () {

    $('.msg_notification').fadeOut(5000);

    /** Form validation */
    $( "#bms_frm" ).validate({
		rules: {
			"service_info[cat_id]": "required",
            "service_info[service_name]": "required",
            "service_info[no_of_question]": "required",
            "service_info[quote_type]": "required",
            "service_info[service_type]": "required"
		},
		messages: {
			"service_info[cat_id]": "Please select Category",
            "service_info[service_name]": "Please enter Service Name",
            "service_info[no_of_question]": "Please enter Number of Questions",
            "service_info[quote_type]": "Please select Quote type",
            "service_info[service_type]": "Please select Service type"
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
    
    // On property name change
});

</script>