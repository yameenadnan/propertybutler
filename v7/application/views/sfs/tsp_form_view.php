<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
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
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
            ?>
              <div class="box-body">
                  <div class="row">
                    
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_sfs/tsp_submit');?>" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" id="tsp_id" name="tsp_id" value="<?php echo $tsp_id;?>"/>

                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                
                                <div class="form-group">
                                    <label >Company Name *</label>
                                  <input type="text" name="tsp[tsp_name]" class="form-control" value="<?php echo isset($tsp['tsp_name']) && $tsp['tsp_name'] != '' ? $tsp['tsp_name'] : '';?>" placeholder="Enter TSP Name" maxlength="250">
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                    <label >Company Reg. No.</label>
                                    <input type="text" name="tsp[company_reg_no]" class="form-control" value="<?php echo isset($tsp['company_reg_no']) && $tsp['company_reg_no'] != '' ? $tsp['company_reg_no'] : '';?>" placeholder="Enter TSP Name" maxlength="50">
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label >Company Address 1</label>
                                        <input type="text" name="tsp[addr_1]" class="form-control" value="<?php echo isset($tsp['addr_1']) && $tsp['addr_1'] != '' ? $tsp['addr_1'] : '';?>" placeholder="Enter Address1" maxlength="250">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Company Address 2</label>
                                        <input type="text" name="tsp[addr_2]" class="form-control" value="<?php echo isset($tsp['addr_2']) && $tsp['addr_2'] != '' ? $tsp['addr_2'] : '';?>" placeholder="Enter Address2" maxlength="250">
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Postcode </label>
                                        <input type="text" name="tsp[postcode]" class="form-control" value="<?php echo isset($tsp['postcode']) && $tsp['postcode'] != '' ? $tsp['postcode'] : '';?>" placeholder="Enter Postcode" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >State </label>
                                        <select class="form-control" id="state" name="tsp[state]">
                                            <option value="">Select State</option>
                                            <?php
                                            foreach ($states as $key=>$val) {
                                                $selected = isset($tsp['state']) && trim($tsp['state']) != '' && trim($tsp['state']) == $val['state_id'] ? 'selected="selected" ' : '';
                                                echo "<option value='".$val['state_id']."' ".$selected." >".$val['state_name']."</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                                    <div class="form-group">
                                      <label >City </label>
                                        <select id="city" name="tsp[city]" class="form-control city_dd">
                                            <option value="">Select</option>   
                                            <?php 
                                                if (!empty($cities)) {
                                                    foreach ($cities as $key=>$val) { 
                                                        $selected = isset($tsp['city']) && trim($tsp['city']) == $val['city_id'] ? 'selected="selected" ' : '';
                                                        echo "<option value='".$val['city_id']."' ".$selected.">".$val['city_name']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <input type="text" name="tsp_city_txt" value="" class="form-control city_new" placeholder="Enter City" maxlength="150" style="display: none;" />
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1 no-padding text-center" style="margin-top: 25px;">
                                    <a href="javascript:;" class="btn btn-success btn-circle add_city_btn city_dd" ><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-circle rm_city_btn city_new" style="display: none;" ><i class="fa fa-minus"></i></a>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-5" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Town</label>
                                        <select id="town" name="tsp[town]" class="form-control city_dd town_dd">
                                            <option value="">Select</option>
                                            <?php
                                                if(!empty($towns)) {
                                                    foreach ($towns as $key=>$val) {
                                                        $selected = isset($tsp['town']) && trim($tsp['town']) == $val['town_id'] ? 'selected="selected" ' : '';
                                                        echo "<option value='".$val['town_id']."' ".$selected.">".$val['town_name']."</option>";
                                                    }
                                                }
                                                 ?>

                                        </select>
                                        <input type="text" name="tsp_town_txt" value="" class="form-control city_new town_new" placeholder="Enter Town" maxlength="150" style="display: none;" />
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1 no-padding text-center" style="margin-top: 25px;">
                                    <a href="javascript:;" class="btn btn-success btn-circle add_town_btn city_dd town_dd" ><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-circle rm_town_btn city_new town_new" style="display: none;" ><i class="fa fa-minus"></i></a>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label>Company Office Phone No. 1 *</label>
                                        <input type="text" name="tsp[company_phone_1]" class="form-control" value="<?php echo isset($tsp['company_phone_1']) && $tsp['company_phone_1'] != '' ? $tsp['company_phone_1'] : '';?>" placeholder="Company Office Tel 1" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Company Office Phone No. 2</label>
                                      <input type="text" name="tsp[company_phone_2]" class="form-control" value="<?php echo isset($tsp['company_phone_2']) && $tsp['company_phone_2'] != '' ? $tsp['company_phone_2'] : '';?>" placeholder="Company Office Tel 2" maxlength="50">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label >Company Fax. </label>
                                        <input type="text" name="tsp[fax]" class="form-control" value="<?php echo isset($tsp['fax']) && $tsp['fax'] != '' ? $tsp['fax'] : '';?>" placeholder="Enter Company Fax." maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="tsp[status]" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            $vendor_status = array(1=>'Active', 2=>'Deactive', 3=>'Suspended');
                                            foreach ($vendor_status as $key=>$val) {
                                                $selected = isset($tsp['status']) && $tsp['status'] == $key ? 'selected="selected" ' : '';
                                                echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label>Contact Person Name *</label>
                                        <input type="text" name="tsp[contact_person]" class="form-control" value="<?php echo isset($tsp['contact_person']) && $tsp['contact_person'] != '' ? $tsp['contact_person'] : '';?>" placeholder="Enter Contact Person Name" maxlength="150">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding" >
                                    <div class="form-group">
                                        <label>Contact Person Phone 1 *</label>
                                        <input type="text" name="tsp[contact_phone_1]" class="form-control" value="<?php echo isset($tsp['contact_phone_1']) && $tsp['contact_phone_1'] != '' ? $tsp['contact_phone_1'] : '';?>" placeholder="Enter Contact Phone 1" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label>Contact Person Phone 2</label>
                                        <input type="text" name="tsp[contact_phone_2]" class="form-control" value="<?php echo isset($tsp['contact_phone_2']) && $tsp['contact_phone_2'] != '' ? $tsp['contact_phone_2'] : '';?>" placeholder="Enter Contact Phone 2" maxlength="50">
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        
                        <!-- right side column -->
                        <div class="col-md-6 col-sm-6 col-xs-12"> 

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group">
                                        <label>Select Category</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <?php if ( !empty ( $categories ) ) {
                                    foreach ( $categories as $cat_key => $cat_val ) { ?>
                                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" <?php echo !empty($tsp_categories) && in_array($cat_val['cat_id'], $tsp_categories) ? 'checked="checked"':'';?> name="category[]" value="<?php echo !empty($cat_val['cat_id']) ? $cat_val['cat_id']:''; ?>">&nbsp;&nbsp;<?php echo !empty($cat_val['cat_name']) ? $cat_val['cat_name']:'';?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="tsp[tsp_type]" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                        $tsp_types = array(1=>'MO SP', 2=>'Resident SP');
                                        foreach ($tsp_types as $key=>$val) {
                                            $selected = !empty($tsp['tsp_type']) && $tsp['tsp_type'] == $key ? 'selected="selected" ' : '';
                                            echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                    <label >Company Info</label>
                                    <textarea name="tsp[company_info]" class="form-control" rows="2" placeholder="Enter Company Information"><?php echo isset($tsp['company_info']) && $tsp['company_info'] != '' ? $tsp['company_info'] : '';?></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group">
                                        <label>Service area</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <?php if ( !empty ( $states ) ) {
                                    foreach ( $states as $state_key => $state_val ) { ?>
                                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" <?php echo !empty($tsp_states) && in_array($state_val['state_id'], $tsp_states) ? 'checked="checked"':'';?> name="tsp_states[]" value="<?php echo !empty($state_val['state_id']) ? $state_val['state_id']:''; ?>">&nbsp;&nbsp;<?php echo !empty($state_val['state_name']) ? $state_val['state_name']:'';?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group">
                                        <label>TSP Document</label>
                                        <div style="display: block;">
                                            <label class="btn-bs-file btn btn-primary">Choose File...
                                                <input type="file" id="attachment" name="attachment" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                                            </label>
                                            <span class='label label-info' id="upload-file-info"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="attachment_old" value="<?php echo !empty($tsp['attachment']) ? $tsp['attachment'] : '';?>" />
                            <?php if(!empty($tsp['attachment'])) {
                                $tsp_document_upload = $this->config->item('tsp_document_upload');
                                ?>
                                <div class="form-group">
                                    <label>Current Document:</label><br />
                                    <a href="<?php echo base_url() . $tsp_document_upload['upload_path'].$tsp['attachment'];?>"><?php echo $tsp['attachment'];?></a>
                                </div>
                            <?php } ?>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label >Email Address</label>
                                        <input type="text" name="tsp[email_addr]" class="form-control" value="<?php echo isset($tsp['email_addr']) && $tsp['email_addr'] != '' ? $tsp['email_addr'] : '';?>" placeholder="Enter Email Address" maxlength="200">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Password</label>
                                        <input type="text" name="tsp[password]" class="form-control" value="" placeholder="Enter Password" maxlength="100">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12" >
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <p class="help-block"> * Required Fields.</p>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
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
$(document).ready(function (){
   $('input[name="tsp[monthly_payment]"]').keyup(function(){
        $('input[name="tsp[annual_payment]"]').val(eval($(this).val())*12);
    });
    
    $('#state').change(function (){
        loadCity($(this).val());
    });
    
    $('#city').change(function (){
        loadTown($('#state').val(),$(this).val());
    });
    
    
    $('.add_city_btn').click(function () {
        $('.city_dd').css('display','none');
        $('.city_new').css('display','block');
        //$('.rm_city_btn').parent('div').addClass('text-center');
    });
    
    $('.rm_city_btn').click(function () {
        // clear city_txt & vendor_town_txt
        $('input[name="tsp_city_txt"]').val('');
        $('input[name="tsp_town_txt"]').val('');
        $('.city_new').css('display','none');
        $('.city_dd').css('display','block');
    });
    
    $('.add_town_btn').click(function () {
        $('.town_dd').css('display','none');
        $('.town_new').css('display','block');
        //$('.rm_city_btn').parent('div').addClass('text-center');
    });
    
    $('.rm_town_btn').click(function () {
        // clear vendor_city_txt & vendor_town_txt
        $('input[name="tsp_town_txt"]').val('');
        $('.town_new').css('display','none');
        $('.town_dd').css('display','block');
    });
});

function loadCity (state_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sfs/get_city');?>',
            data: {'state_id':state_id},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select City</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.city_id+'">'+item.city_name+'</option>';
                    });
                }
                $('#city').html(str);
                $("#content_area").LoadingOverlay("hide", true);                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

function loadTown (state_id,city_id) {

    console.log ('state_id: ' + state_id );
    console.log ('city_id: ' + city_id );

    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sfs/get_town');?>',
            data: {'state_id':state_id,'city_id':city_id},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select Town</option>';
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.town_id+'">'+item.town_name+'</option>';
                    });
                }
                $('#town').html(str);
                $("#content_area").LoadingOverlay("hide", true);                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

$('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
$( "#bms_frm" ).validate({
		rules: {               
			"tsp[vendor_name]": "required",
            /*"tsp[vendor_address1]": "required",
            "tsp[vendor_postcode]": "required",
            "tsp[city]": "required",
            "tsp[vendor_state]": "required",*/
            "tsp[vendor_office_no]": "required",
            "tsp[vendor_incharge]": "required",
            "tsp[vendor_catgory]": "required",
            "tsp[tsp_type]": "required",
            "tsp[person_inc_email]": {
                email: true
            }    
		},
		messages: {
		       
			"tsp[vendor_name]": "Please enter Vendor Name",
            /*"tsp[vendor_address1]": "Please enter Address1",
            "tsp[vendor_postcode]": "Please enter Postcode",
            "tsp[city]": "Please select / enter City",
            "tsp[vendor_state]": "Please select State",*/
            "tsp[vendor_office_no]": "Please enter Office Phone No.",
            "tsp[vendor_incharge]": "Please enter Person Incharge Name",
            "tsp[vendor_catgory]": "Please select Service Category",
            "tsp[tsp_type]": "Please select TSP Type",
            "tsp[person_inc_email]": {
                email: "Please enter valid Email Address"
            }            
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
        /*$('.timepicker').timepicker({
          showInputs: false
        });*/
      });
</script>