<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
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
                    
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_home_butler/vendor_submit');?>" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id;?>"/>
                    
                    <!--div class="col-md-12 col-sm-12 col-xs-12 ">
                        
                       
                    </div-->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                
                                <div class="form-group">
                                    <label >Vendor Name *</label>
                                  <input type="text" name="vendor[vendor_name]" class="form-control" value="<?php echo isset($vendor['vendor_name']) && $vendor['vendor_name'] != '' ? $vendor['vendor_name'] : '';?>" placeholder="Enter Vendor Name" maxlength="150">
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                        <label >Address 1</label>
                                      <input type="text" name="vendor[vendor_address1]" class="form-control" value="<?php echo isset($vendor['vendor_address1']) && $vendor['vendor_address1'] != '' ? $vendor['vendor_address1'] : '';?>" placeholder="Enter Address1" maxlength="250">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Address 2</label>
                                      <input type="text" name="vendor[vendor_address2]" class="form-control" value="<?php echo isset($vendor['vendor_address2']) && $vendor['vendor_address2'] != '' ? $vendor['vendor_address2'] : '';?>" placeholder="Enter Address2" maxlength="250">
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Postcode </label>
                                        <input type="text" name="vendor[vendor_postcode]" class="form-control" value="<?php echo isset($vendor['vendor_postcode']) && $vendor['vendor_postcode'] != '' ? $vendor['vendor_postcode'] : '';?>" placeholder="Enter Postcode" maxlength="15">

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >State </label>
                                        <select class="form-control" id="vendor_state" name="vendor[vendor_state]">
                                            <option value="">Select State</option>
                                            <?php
                                            foreach ($states as $key=>$val) {
                                                $selected = isset($vendor['vendor_state']) && trim($vendor['vendor_state']) != '' && trim($vendor['vendor_state']) == $val['state_id'] ? 'selected="selected" ' : '';
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
                                        <select id="vendor_city" name="vendor[vendor_city]" class="form-control city_dd">
                                            <option value="">Select</option>   
                                            <?php 
                                                if(!empty($cities)) {
                                                    foreach ($cities as $key=>$val) { 
                                                        $selected = isset($vendor['vendor_city']) && trim($vendor['vendor_city']) == $val['city_id'] ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$val['city_id']."' ".$selected.">".$val['city_name']."</option>";
                                                    }
                                                }
                                                 ?> 
                                                                           
                                        </select>
                                        <input type="text" name="vendor_city_txt" value="" class="form-control city_new" placeholder="Enter City" maxlength="150" style="display: none;" />
                                    </div>
                                    
                                    
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1 no-padding text-center" style="margin-top: 25px;">
                                    <a href="javascript:;" class="btn btn-success btn-circle add_city_btn city_dd" ><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" class="btn btn-danger btn-circle rm_city_btn city_new" style="display: none;" ><i class="fa fa-minus"></i></a>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-5" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Town</label>
                                        <select id="vendor_town" name="vendor[vendor_town]" class="form-control city_dd town_dd">
                                            <option value="">Select</option>
                                            <?php
                                                if(!empty($towns)) {
                                                    foreach ($towns as $key=>$val) {
                                                        $selected = isset($vendor['vendor_town']) && trim($vendor['vendor_town']) == $val['town_id'] ? 'selected="selected" ' : '';
                                                        echo "<option value='".$val['town_id']."' ".$selected.">".$val['town_name']."</option>";
                                                    }
                                                }
                                                 ?>

                                        </select>
                                        <input type="text" name="vendor_town_txt" value="" class="form-control city_new town_new" placeholder="Enter Town" maxlength="150" style="display: none;" />
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
                                      <label >Office Phone No. *</label>
                                        <input type="text" name="vendor[vendor_office_no]" class="form-control" value="<?php echo isset($vendor['vendor_office_no']) && $vendor['vendor_office_no'] != '' ? $vendor['vendor_office_no'] : '';?>" placeholder="Enter Office Phone No." maxlength="50">

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Fax No.</label>
                                      <input type="text" name="vendor[vendor_fax]" class="form-control" value="<?php echo isset($vendor['vendor_fax']) && $vendor['vendor_fax'] != '' ? $vendor['vendor_fax'] : '';?>" placeholder="Enter Fax No." maxlength="50">

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding"> 
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding" >
                                    <div class="form-group">
                                      <label >Person Incharge Name *</label>
                                      <input type="text" name="vendor[vendor_incharge]" class="form-control" value="<?php echo isset($vendor['vendor_incharge']) && $vendor['vendor_incharge'] != '' ? $vendor['vendor_incharge'] : '';?>" placeholder="Enter Person Incharge Name" maxlength="150">  
                            
                                    </div>
                                </div>     
                                <div class="col-md-6 col-sm-6 col-xs-6 " style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Person Incharge Mobile No. </label>
                                        <input type="text" name="vendor[vendor_mobile_no]" class="form-control" value="<?php echo isset($vendor['vendor_mobile_no']) && $vendor['vendor_mobile_no'] != '' ? $vendor['vendor_mobile_no'] : '';?>" placeholder="Enter Person Incharge Mobile No." maxlength="50">
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">

                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Person Incharge Email </label>
                                        <input type="text" name="vendor[vendor_inc_email]" class="form-control" value="<?php echo isset($vendor['vendor_inc_email']) && $vendor['vendor_inc_email'] != '' ? $vendor['vendor_inc_email'] : '';?>" placeholder="Enter Person Incharge Email" maxlength="150">
                                    </div>
                                </div>

                            </div>

                        </div>
                        
                        
                        <!-- right side column -->
                        <div class="col-md-6 col-sm-6 col-xs-12"> 
                        
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                <div class="form-group">
                                    <label >Servicing Category *</label>                              
                                  <select name="vendor[vendor_catgory]" class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                        
                                        foreach ($vendor_cat as $key=>$val) {
                                            $selected = isset($vendor['vendor_catgory']) && $vendor['vendor_catgory'] == $val['vendor_cat_id'] ? 'selected="selected" ' : '';
                                            echo "<option value='".$val['vendor_cat_id']."' ".$selected.">".$val['vendor_cat_name']."</option>";
                                        }
                                    ?>                            
                                  </select>
                                </div>
                            </div>                            
                                                        
                                      
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label>Vendor Status</label>
                                      <select name="vendor[vendor_status]" class="form-control">
                                        <option value="">Select</option>
                                        <?php 
                                            $vendor_status = array(1=>'Active',0=>'Deactive');
                                            foreach ($vendor_status as $key=>$val) {
                                                $selected = isset($vendor['vendor_status']) && $vendor['vendor_status'] == $key ? 'selected="selected" ' : '';
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
                                      <label >Email Address</label>
                                        <input type="text" name="vendor[email_addr]" class="form-control" value="<?php echo isset($vendor['email_addr']) && $vendor['email_addr'] != '' ? $vendor['email_addr'] : '';?>" placeholder="Enter Email Address" maxlength="150">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Password</label>
                                      <input type="text" name="vendor[password]" class="form-control" value="<?php echo isset($vendor['password']) && $vendor['password'] != '' ? $vendor['password'] : '';?>" placeholder="Enter Password" maxlength="50">  
                            
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="form-group">
                                  <label >Vendor Keywords</label>
                                  <textarea name="vendor[vendor_keywords]" class="form-control" rows="2" placeholder="Enter Vendor Description"><?php echo isset($vendor['vendor_keywords']) && $vendor['vendor_keywords'] != '' ? $vendor['vendor_keywords'] : '';?></textarea>
                                </div>
                            </div>                      
                            
                            
                        </div>
                    </div>
                    
                    
                    
                    <div class="col-md-12" >
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <p class="help-block"> * Required Fields.</p>
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
   $('input[name="vendor[monthly_payment]"]').keyup(function(){
        $('input[name="vendor[annual_payment]"]').val(eval($(this).val())*12);
    });
    
    $('#vendor_state').change(function (){        
        loadCity($(this).val());
    });
    
    $('#vendor_city').change(function (){        
        loadTown($('#vendor_state').val(),$(this).val());
    });
    
    
    $('.add_city_btn').click(function () {
        $('.city_dd').css('display','none');
        $('.city_new').css('display','block');
        //$('.rm_city_btn').parent('div').addClass('text-center');
    });
    
    $('.rm_city_btn').click(function () {
        // clear vendor_city_txt & vendor_town_txt
        $('input[name="vendor_city_txt"]').val('');
        $('input[name="vendor_town_txt"]').val('');
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
        $('input[name="vendor_town_txt"]').val('');
        $('.town_new').css('display','none');
        $('.town_dd').css('display','block');
    });
});

function loadCity (state_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_home_butler/get_city');?>',
            data: {'state_id':state_id},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select City</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.city_id+'">'+item.city_name+'</option>';
                    });
                }
                $('#vendor_city').html(str); 
                $("#content_area").LoadingOverlay("hide", true);                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

function loadTown (state_id,city_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_home_butler/get_town');?>',
            data: {'state_id':state_id,'city_id':city_id},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select City</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.town_id+'">'+item.town_name+'</option>';
                    });
                }
                $('#vendor_town').html(str); 
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
			"vendor[vendor_name]": "required",            
            /*"vendor[vendor_address1]": "required",
            "vendor[vendor_postcode]": "required",
            "vendor[vendor_city]": "required",
            "vendor[vendor_state]": "required",*/
            "vendor[vendor_office_no]": "required",
            "vendor[vendor_incharge]": "required",
            "vendor[vendor_catgory]": "required",
            "vendor[person_inc_email]": {                
                email: true
            }    
		},
		messages: {
		       
			"vendor[vendor_name]": "Please enter Vendor Name",
            /*"vendor[vendor_address1]": "Please enter Address1",             
            "vendor[vendor_postcode]": "Please enter Postcode",
            "vendor[vendor_city]": "Please select / enter City",
            "vendor[vendor_state]": "Please select State",*/
            "vendor[vendor_office_no]": "Please enter Office Phone No.",
            "vendor[vendor_incharge]": "Please enter Person Incharge Name",
            "vendor[vendor_catgory]": "Please select Service Category",            
            "vendor[person_inc_email]": {               
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