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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_jmb_mc/add_jmb_mc_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:15px ;">
                                  <h3 class="box-title"><b>JMB / MC Details</b></h3>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">                                    
                                        <div class="form-group">
                                      <label >Property Name *</label>
                                        <select class="form-control" id="property_id" name="jmb_mc[property_id]">
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = '';
                                                    if(isset($jmb_mc['property_id'])) {
                                                        $selected = isset($jmb_mc['property_id']) && $jmb_mc['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                    } else if(isset($_SESSION['bms_default_property'])) {
                                                        $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                                    }
                                                    //$selected = isset($jmb_mc['property_id']) && $jmb_mc['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        <input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id;?>"/>
                                    </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Unit No *</label>                                          
                                            <select name="jmb_mc[unit_id]" id="unit_id" class="form-control">
                                            <option value="">Select</option>   
                                            
                                                                           
                                          </select>  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Member's Name</label>
                                          <input type="text" id="resident_name" class="form-control" value="<?php echo isset($jmb_mc['owner_name']) && $jmb_mc['owner_name'] != '' ? $jmb_mc['owner_name'] : '';?>" placeholder="Member's Name" disabled="disabled"> 
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Mobile No</label>
                                          <input type="text" id="contact_number" class="form-control" value="<?php echo isset($jmb_mc['contact_1']) && $jmb_mc['contact_1'] != '' ? $jmb_mc['contact_1'] : '';?>" placeholder="Mobile No" disabled="disabled"> 
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Email ID</label>
                                            <input type="text" id="email_addr" class="form-control" value="<?php echo isset($jmb_mc['email_addr']) && $jmb_mc['email_addr'] != '' ? $jmb_mc['email_addr'] : '';?>" placeholder="Email ID" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Position *</label>                                          
                                            <select name="jmb_mc[jmb_desi_id]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                
                                                foreach ($positions as $key=>$val) { 
                                                    $selected = isset($jmb_mc['jmb_desi_id']) && trim($jmb_mc['jmb_desi_id']) == $val['jmb_desi_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['jmb_desi_id']."' ".$selected.">".$val['jmb_desi_name']."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Elected Date *</label>                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right" name="jmb_mc[elect_date]" class="form-control" value="<?php echo isset($jmb_mc['elect_date']) && $jmb_mc['elect_date'] != '' ? date('d-m-Y',strtotime($jmb_mc['elect_date'])) : '';?>" id="datepicker" type="text" />
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >JMB/MC Role</label>
                                          <input type="text" name="jmb_mc[jmb_role]" class="form-control" value="<?php echo isset($jmb_mc['jmb_role']) && $jmb_mc['jmb_role'] != '' ? $jmb_mc['jmb_role'] : '';?>" placeholder="Enter JMB/MC Role" maxlength="100"> 
                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                      <label>JMB/MC Status</label>
                                      <select name="jmb_mc[jmb_status]" class="form-control">
                                        <option value="">Select</option>
                                        <?php 
                                            $jmb_status = array(1=>'Active',0=>'Deactive');
                                            foreach ($jmb_status as $key=>$val) {
                                                $selected = isset($jmb_mc['jmb_status']) && $jmb_mc['jmb_status'] == $key ? 'selected="selected" ' : '';
                                                echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                            }
                                        ?>
                                        
                                      </select>
                                    </div>      
                                </div>
                                    
                                </div>                                
                                
                            </div>
                            
                        </div>
                        
                         
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
    
    // right side box hight adjustments    
    if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }
    
    if($('#property_id').val() != '') {        
        loadUnit ('<?php echo isset($jmb_mc['unit_id']) && $jmb_mc['unit_id'] != '' ? $jmb_mc['unit_id'] : '';?>');
    }
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"jmb_mc[property_id]": "required",
            "jmb_mc[unit_id]": "required",  
            "jmb_mc[jmb_desi_id]": "required",          
            "jmb_mc[elect_date]": "required",
            "jmb_mc[jmb_status]": "required"
		},
		messages: {
			"jmb_mc[property_id]": "Please select Property Name",
            "jmb_mc[unit_id]": "Please select Unit No", 
            "jmb_mc[jmb_desi_id]": "Please select Position ",            
            "jmb_mc[elect_date]": "Please select Elected Date",
            "jmb_mc[jmb_status]": "Please select JMB/MC Status"
            
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
    $('#property_id').change(function () {
        loadUnit ('');
    });  
    
    $('#unit_id').change (function () {
        unset_resident_info(); // unset the resident onfo if loaded already        
        if(typeof($(this).find('option:selected').data('owner')) != 'undefined') {
            $('#resident_name').val($(this).find('option:selected').data('owner'));
            //$('#resident_name_hid').val($(this).find('option:selected').data('owner'));
        }
        
        if(typeof($(this).find('option:selected').data('contact')) != 'undefined') {
            $('#contact_number').val($(this).find('option:selected').data('contact'));
            //$('#contact_number_hid').val($(this).find('option:selected').data('contact'));            
        } 
        if(typeof($(this).find('option:selected').data('email')) != 'undefined'){
            $('#email_addr').val($(this).find('option:selected').data('email'));
            //$('#email_addr_hid').val($(this).find('option:selected').data('email'));            
        } 
        if(typeof($(this).find('option:selected').data('defaulter')) != 'undefined' && $(this).find('option:selected').data('defaulter') == 1) $('#defaulter').attr('checked',true);    
           
        //console.log($(this).find('option:selected').data('defaulter'));
    });
               
});

function loadUnit (unit_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_jmb_mc/get_unit');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.unit_id+'" '+selected+' data-owner="'+item.owner_name+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                if(unit_id == '') unset_resident_info(); // unset the resident onfo if loaded already             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

function unset_resident_info () {        
    $('#resident_name').val('');    //$('#resident_name_hid').val('');
    //$('#unit_status').val('');      //$('#unit_status_hid').val('');
    $('#contact_number').val('');   //$('#contact_number_hid').val('');
    $('#email_addr').val('');       //$('#email_addr_hid').val('');
    //$('#defaulter').attr('checked',false);
}
        

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
</script>