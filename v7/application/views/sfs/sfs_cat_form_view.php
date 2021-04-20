<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
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
    <section class="content container-fluid">
        <div class="box box-primary">
        <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }
        
        ?>        
        
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="box-body">
            <div class="row">
                    <form id="bms_frm" action="<?php echo base_url('index.php/bms_sfs/sfs_cat_submit');?>"  method="post" enctype="multipart/form-data">
                    <fieldset>
                    
                    <!-- Form Name -->
                    
                    
                    <div class="row">
                        <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label>Category Name * </label>
                                  <input name="cat[cat_name]" type="text" placeholder="Enter Category Name" value="<?php echo !empty($cat['cat_name']) ? $cat['cat_name'] : '';?>" class="form-control input-md" maxlength="150"  >
                                  <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>" />
                                </div>
                            </div>
                        <div class="col-md-4"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Category type * </label>
                                <select class="form-control" id="cat_type" name="cat[cat_type]">
                                    <option value="">Select</option>
                                    <?php $cat_type_array = array (1=>'MO category', 2=>'Resident category');
                                    foreach ( $cat_type_array as $key=>$val ) {
                                        $select = !empty ($cat['cat_type']) && $cat['cat_type'] == $key ? 'selected="selected"':''; ?>
                                        <option value="<?php echo $key;?>" <?php echo $select;?>><?php echo $val;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="display: block;">Picture </label>
                                <label class="btn-bs-file btn btn-primary">
                                    Choose Picture...

                                    <!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                    <input type="file" id="attach_file" name="upload_picture" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                                </label>
                                <span class='label label-info' id="upload-file-info"></span>

                                <input type="hidden" name="picture_old" value="<?php echo !empty($cat['picture']) ? $cat['picture'] : '';?>" />
                                <?php if (!empty($cat['picture'])) {
                                    $sfs_category_picture_upload = $this->config->item('sfs_category_picture_upload');
                                    ?>
                                    <div class="form-group">
                                        <label>Current Picture:</label><br />
                                        <img src="<?php echo $sfs_category_picture_upload['upload_path_output'] . $cat['picture'];?>" width="150" />
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                    <!-- Button (Double) -->
                    <div class="row" style="margin-top: 15px;">
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="bCancel"></label>
                          <div class="col-md-8">
                            <button id="bGodkend" type="submit"  class="btn btn-primary">Save</button> &ensp;
                            <button  type="Reset" id="bCancel" class="btn btn-default">Reset</button>

                          </div>
                        </div>
                    </div>
                    </fieldset>
                    </form>
        
                </div>
          
          </div>
          
       </div>       
              
    </section> <!-- /.content -->
 
  </div> <!-- /.content-wrapper -->
<?php $this->load->view('footer');?> 
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script> 
<script>
$(document).ready(function () {
    
    //$('#current_pass').focus();
    $('.msg_notification').fadeOut(3000);
    
    /** Form validation */    
    $( "#bms_frm" ).validate({
			rules: {				
			    "cat[cat_name]": "required"
			},
			messages: {				
			    "cat[cat_name]": "Please enter Category Name"
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
    
 
});
</script>