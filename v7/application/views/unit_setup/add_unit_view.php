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
    <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
        <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_unit_setup/add_unit_submit');?>" method="post" enctype="multipart/form-data">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="row">
        
            <div class="col-md-12 col-xs-12">
                <div class="box box-primary">
                
                    <div class="box-header with-border" style="padding-left:15px ;">
                      <h3 class="box-title"><b>Unit Details</b></h3>
                    </div>
                    
                    <div class="box-body">
                    <div class="col-md-12 col-sm-6 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Property Name *</label>
                                <select class="form-control" id="property_id" name="unit[property_id]">
                                    <option value="">Select Property</option>
                                    <?php 
                                        foreach ($properties as $key=>$val) { 
                                            $selected = '';
                                            if(isset($unit_info['property_id'])) {
                                                $selected = isset($unit_info['property_id']) && $unit_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                            } else if(isset($_SESSION['bms_default_property'])) {
                                                $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                            }
                                            //$selected = isset($unit_info['property_id']) && $unit_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                            $selected .= ' data-calc-base="'.(!empty($val['calcul_base']) ? $val['calcul_base'] : '').'"';
                                            $selected .= ' data-sinking-fund="'.(isset($val['sinking_fund']) && $val['sinking_fund'] != '' ? $val['sinking_fund'] : '').'"';
                                            $selected .= ' data-tot-sq-feet="'.(isset($val['tot_sq_feet']) && $val['tot_sq_feet'] != '' ? $val['tot_sq_feet']: '').'"';
                                            $selected .= ' data-per-sq-feet="'.(isset($val['per_sq_feet']) && $val['per_sq_feet'] != '' ? $val['per_sq_feet']: '').'"';
                                            $selected .= ' data-tot-share-unit="'.(isset($val['tot_share_unit']) && $val['tot_share_unit'] != '' ? $val['tot_share_unit'] : '').'"';
                                            $selected .= ' data-per-share-unit="'.(isset($val['per_share_unit']) && $val['per_share_unit'] != '' ? $val['per_share_unit'] : '').'"';
                                            $selected .= ' data-sc-charge="'.(isset($val['sc_charge']) && $val['sc_charge'] != '' ? $val['sc_charge'] : '').'"';
                                            $selected .= ' data-insurance-prem="'.(isset($val['insurance_prem']) && $val['insurance_prem'] != '' ? $val['insurance_prem'] : '').'"';
                                            $selected .= ' data-quit-rent="'.(isset($val['quit_rent']) && $val['quit_rent'] != '' ? $val['quit_rent'] : '').'"';
                                            
                                            echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                                </select>
                                <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_id;?>"/>
                            </div>
                           
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="form-group">
                              <label >Block/Street *</label>
                              <select class="form-control" name="unit[block_id]"  id="block_id">
                                <option value="">Select</option>                                
                              </select>
                    
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">                                    
                                <div class="form-group">
                                    <label >Unit No *</label>
                                  <input type="text" name="unit[unit_no]" class="form-control" value="<?php echo isset($unit_info['unit_no']) && $unit_info['unit_no'] != '' ? $unit_info['unit_no'] : '';?>" placeholder="Ex: A-01-01" maxlength="25">
                                </div>
                            </div>
                            
                    </div>
                          <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 15px !important;">
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                          <label >Unit Status *</label>                                          
                                            <select name="unit[unit_status]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                //$unit_status = $this->config->item('unit_status');
                                                foreach ($unit_status as $key=>$val) { 
                                                    $selected = isset($unit_info['unit_status']) && trim($unit_info['unit_status']) == $val['unit_status_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['unit_status_id']."' ".$selected.">".$val['unit_status_name']."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                          <label >Floor/Level</label>
                                          <input type="text" name="unit[floor_no]" class="form-control" value="<?php echo isset($unit_info['floor_no']) && $unit_info['floor_no'] != '' ? $unit_info['floor_no'] : '';?>" placeholder="Enter Floor" maxlength="20"> 
                                
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                          <label >Tier Name *</label>
                                          <select name="unit[tier_id]" id="tier_id" class="form-control">
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group" id="share_unit_container" style="visibility: hidden; display: none;">
                                          <label>Share Unit</label>
                                          <input type="number" name="unit[share_unit]" id="share_unit" class="form-control" value="<?php echo isset($unit_info['share_unit']) && $unit_info['share_unit'] != '' ? $unit_info['share_unit'] : '';?>" placeholder="Enter Share Unit" maxlength="20">
                                        </div>
                                        <div class="form-group" id="square_feet_container" style="visibility: hidden; display: none;">
                                          <label >Square Feet</label>
                                          <input type="number" name="unit[square_feet]" id="square_feet" class="form-control" value="<?php echo isset($unit_info['square_feet']) && $unit_info['square_feet'] != '' ? $unit_info['square_feet'] : '';?>" placeholder="Enter Square Feet" maxlength="10">
                                        </div>
                                        <div class="form-group" id="fixed_amount_container" style="visibility: hidden; display: none;">
                                          <label>Fixed Amount</label>
                                          <input type="number" name="fixed_amount_label" id="fixed_amount_label" class="form-control" value="<?php echo !empty($val['sc_charge']) ? $val['sc_charge'] : '';?>" placeholder="Fixed Amount" maxlength="10">
                                        </div>
                                    </div>
                                    
                                </div>  
                               <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 15px !important;">
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                          <label >Unit Type *</label>                                          
                                            <select name="unit[unit_type]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $unit_type = $this->config->item('unit_type');
                                                foreach ($unit_type as $key=>$val) { 
                                                    $selected = isset($unit_info['unit_type']) && trim($unit_info['unit_type']) == $key ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                          </select>  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                          <label >No Of Owners *</label>
                                          <input type="text" name="unit[no_of_owners]" class="form-control" value="<?php echo isset($unit_info['no_of_owners']) && $unit_info['no_of_owners'] != '' ? $unit_info['no_of_owners'] : '';?>" placeholder="Enter No Of Owners" maxlength="3"> 
                                
                                        </div>
                                    </div>
                              </div> 
                                <div class="col-md-12 no-padding text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button> &ensp;&ensp;
                        <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                        <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                      </div>
                    </div>                               
                    
                    </div><!-- /.box-body -->   
                 
                 </div><!-- /.box-primary -->  
                 
                  
            
            </div>
            
            
            
           
            
            <!--div class="col-md-6 col-xs-12">               
                    
                <div class="col-md-12 no-padding text-right">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;                            
                  </div>
                </div>
            </div-->
        
        </div><!-- /.row --> 
        
        </form>  
        <!-- general form elements -->
          <!--div class="box box-success">
            
            
              
              
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                    
                  </div>
                </div>
              </div>
            
            </div--> <!-- /.box -->
            <?php if($unit_id != '') { ?>
                <div class="row" style="margin: 15px 0 0 0;">
                
                    <div class="box box-info">
                
                    <!--div class="box-header with-border" style="padding-left:15px ;">
                      <h3 class="box-title"><b>More Details</b></h3>
                    </div-->
                    
                    <div class="box-body">
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-top:15px;border: 1px solid #999;border-radius: 5px;">
                                <div  id="tabs" class="col-md-12" style="margin-top: 15px;">
                                    <ul class="nav nav-tabs" id="unitTabs">
                                        <li class="active">
                                            <a id="home_link" href="#home" data-url="<?php echo base_url('index.php/bms_unit_setup/getOwners/'.$unit_id . '/?invalid_email=' . $invalid_email); ?>">Owners</a>
                                        </li>
                                        <li>
                                            <a href="#tenants" data-url="<?php echo base_url('index.php/bms_unit_setup/getTenants/'.$unit_id); ?>">Tenants</a>
                                        </li>
                                        <li>
                                            <a href="#ma_users" data-url="<?php echo base_url('index.php/bms_unit_setup/getMaUsers/'.$unit_id); ?>">VMS/Mobile App Users</a>
                                        </li>
                                        <!-- li>
                                            <a href="#vehicles" data-url="<?php echo base_url('index.php/bms_unit_setup/getVehicles/'.$unit_id); ?>">Vehicles</a>
                                        </li -->
                                        <!-- li>
                                            <a href="#charges" data-url="<?php echo base_url('index.php/bms_unit_setup/getCharges/'.$unit_id); ?>">Charges</a>
                                        </li -->
                                        <li>
                                            <a href="#parking" data-url="<?php echo base_url('index.php/bms_unit_setup/getParking/'.$unit_id); ?>">Parking</a>
                                        </li>
                                        <li>
                                            <a href="#access_card" data-url="<?php echo base_url('index.php/bms_unit_setup/getAccessCard/'.$unit_id); ?>">Access Card</a>
                                        </li>
                                        <!-- li>
                                            <a data-toggle="tab" href="#menu1">Billings</a>
                                        </li -->
                                        <!-- li>
                                            <a data-toggle="tab" href="#menu2">Accounts</a>
                                        </li -->
                                    </ul>
                                    <div class="tab-content" style="padding-bottom: 10px;">
                                        <div id="home" class="tab-pane fade in active"></div>
                                        <div id="tenants" class="tab-pane fade"></div>
                                        <div id="ma_users" class="tab-pane fade"></div>
                                        <div id="vehicles" class="tab-pane fade"></div>
                                        <div id="charges" class="tab-pane fade"></div>
                                        <div id="parking" class="tab-pane fade"></div>
                                        <div id="access_card" class="tab-pane fade"></div>
                                        <div id="menu1" class="tab-pane fade">
                                            <h3>
                                                Billings
                                            </h3>
                                            <table id="datatable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            No
                                                        </th>
                                                        <th>
                                                            Bill No
                                                        </th>
                                                        <th>
                                                            Date
                                                        </th>
                                                        <th>
                                                            Due Date
                                                        </th>
                                                        <th>
                                                            Overdue
                                                        </th>
                                                        <th>
                                                            Payment Status
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="menu2" class="tab-pane fade">
                                            <h3>
                                                Account Details
                                            </h3>
                                            &nbsp;
                                            <div>
                                                <td>
                                                    <b>
                                                        Range
                                                    </b>
                                                </td>
                                                <td>
                                                    <input type="text" name="" id="" value="">
                                                </td>
                                                <b>
                                                    To
                                                </b>
                                                <td>
                                                    <input type="text" name="" id="" value="">
                                                </td>
                                                <td>
                                                    <input type="submit" name="" class="btn btn-primary" id="" value="view">
                                                </td>
                                                <br>
                                            </div>
                                            &nbsp; &nbsp;
                                            <div style="padding-left: 1%;">
                                                Last Refreshed:3-sep-2018 1:27:39PM
                                            </div>
                                            &nbsp;
                                            <div class="box-body">
                                                <table id="example2" class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="hidden-xs">
                                                                Date
                                                            </th>
                                                            <th>
                                                                Description
                                                            </th>
                                                            <th>
                                                                Doc No
                                                            </th>
                                                            <th>
                                                                Debit
                                                            </th>
                                                            <th>
                                                                Credit
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center" colspan="4">
                                                                No Record Found
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <h3>
                                                Notes
                                            </h3>
                                            <tr>
                                                <td>
                                                    1.All cheques/TT should made payable to "*******". please indicate your NAME,UNIT NO.and CONTACT NO. on the reverse side of your cheque.
                                                </td>
                                                <br>
                                                <!--td>
                                                    2.for bank transfer, kindly credit to our building maintenance account at MBB Account No:564810521802
                                                </td-->
                                            </tr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- . col-md-12 -->
                	</div><!-- /.box-body -->   
                 </div><!-- /.box-danger --> 
                </div>
                
                <?php } ?>
            
            
        
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

    setTimeout (function() {
        $("#home_link").trigger('click');
    },2);

    $('#fixed_amount_label').val( eval($('#property_id').find('option:selected').data('sc-charge')) ) ;

    $('.msg_notification').fadeOut(5000);
    
    $('#tabs').on('click','.tablink,#unitTabs a',function (e) {
        //console.log(this);
        e.preventDefault();
        var url = $(this).attr("data-url");
    
        if (typeof url !== "undefined") {
            var pane = $(this), href = this.hash;
            
            if($(href).html() == '') {
               // ajax load from data-url
                $(href).load(url,function(result){      
                    pane.tab('show');
                }); 
            } else {
                $(this).tab('show');
            }            
        } else {
            $(this).tab('show');
        }
    });
    
    $('#unitTabs').tab();
    $('a[href="#<?php echo isset($_GET['tab']) && $_GET['tab'] != '' ? $_GET['tab'] : 'home';?>"]').trigger('click');
    
    /*$('#square_feet, #share_unit').keyup(function(){
        calculateCharges();
    });*/
    
    
    if ( $('#unit_id').val() != '' ) {
        setChargesFields ();
        loadBlock ('<?php echo isset($unit_info['block_id']) && $unit_info['block_id'] != '' ? $unit_info['block_id'] : '';?>');
        loadTier ('<?php echo isset($unit_info['tier_id']) && $unit_info['tier_id'] != '' ? $unit_info['tier_id'] : '';?>');
    } else if($('#property_id').val() != '') {
        setChargesFields ();
        loadBlock ('');
        loadTier ('');
    }
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"unit[property_id]": "required",
            "unit[unit_no]": "required",
            "unit[unit_status]": "required",
            "unit[block_id]": "required",
            "unit[unit_type]": "required",
            "unit[no_of_owners]": "required",            
            "unit[owner_name]": "required",
            "unit[ic_passport_no]": "required",
            "unit[dob]": "required",
            "unit[race]": "required",
            "unit[religion]": "required",
            "unit[gender]": "required",
            "unit[nationality]": "required",
            "unit[contact_1]": "required",			
            "unit[email_addr]":{
					   required: true,
					   email: true
					},
            "unit[password]":"required",
            "unit[address_1]": "required",
            "unit[city]": "required",	
            "unit[postcode]": "required",	
            "unit[state]": "required",	
            "unit[country]": "required"
            
		},
		messages: {
			"unit[property_id]": "Please select Property Name",
            "unit[unit_no]": "Please enter Unit No",
            "unit[unit_status]": "Please select Unit Status",
            "unit[block_id]": "Please select Block/Street",
            "unit[unit_type]": "Please select Unit Type",
            "unit[no_of_owners]": "Please enter No Of Owners",
            "unit[owner_name]": "Please enter Owner Name",
            "unit[ic_passport_no]": "Please enter Identity No",
            "unit[dob]": "Please select Date Of Birth",
            "unit[race]": "Please select Race",
            "unit[religion]": "Please select Religion",
            "unit[gender]": "Please select Gender",
            "unit[nationality]": "Please select Nationality ",
            "unit[contact_1]": "Please enter Contact No 1",			
            "unit[email_addr]":{
					   required:"Please enter Email Address",
                       email: "Please enter valid Email Address"/*,
                       remote:"Email Address exists already!"*/
					   },
            "unit[password]":"Please enter Password",
            "unit[address_1]": "Please enter Address 1",
            "unit[city]": "Please enter City",	
            "unit[postcode]": "Please enter Post Code",	
            "unit[state]": "Please enter State ",	
            "unit[country]": "Please select Country"
            
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
        setChargesFields();
        loadBlock ('');
        loadTier ('');
    });  
             
});

function loadBlock (block_id) {
    
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/get_blocks');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        var selected = block_id != '' && block_id == item.block_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.block_id+'" '+selected+'>'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str);               
                $("#content_area").LoadingOverlay("hide", true);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

function loadTier (tier_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_property/get_tiers');?>',
        data: {'property_id':$('#property_id').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {
            var str = '<option value="">Select</option>';
            if(data.length > 0) {
                $.each(data,function (i, item) {
                    var selected = tier_id != '' && tier_id == item.tier_id ? 'selected="selected"' : '';
                    str += '<option value="'+item.tier_id+'" '+selected+'>'+item.tier_name + ' - ' + item.tier_value +'</option>';
                });
            }
            $('#tier_id').html(str);
            $("#content_area").LoadingOverlay("hide", true);

        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}


function setChargesFields () {
    if($('#property_id').val() != '') {
        //console.log($('#property_id').find('option:selected').data('calc-base'));
        if($('#property_id').find('option:selected').data('calc-base') == 1 || $('#property_id').find('option:selected').data('calc-base') == 2 || $('#property_id').find('option:selected').data('calc-base') == 3 ) {
            /*$('input[name="unit[service_charge]"').attr('readonly','true');
            $('input[name="unit[sinking_fund]"').attr('readonly','true');
            $('input[name="unit[insurance_prem]"').attr('readonly','true');
            $('input[name="unit[quit_rent]"').attr('readonly','true');*/
            if( $('#property_id').find('option:selected').data('calc-base') == 1 ) {
                $('#square_feet_container').attr("style", "visibility:visible; display:block;" );
                $('#square_feet').removeAttr('disabled');
                $('#share_unit').val('');
                $('#share_unit').attr('disabled','disabled');
                $('#share_unit_container').attr("style", "visibility:hidden; display:none;" );
                $('#fixed_amount_container').attr("style", "visibility:hidden; display:none;" );
            } else if ($('#property_id').find('option:selected').data('calc-base') == 2) {
                $('#share_unit_container').attr("style", "visibility:visible; display:block;" );
                $('#share_unit').removeAttr('disabled');
                $('#square_feet').val('');
                $('#square_feet').attr('disabled','disabled');
                $('#square_feet_container').attr("style", "visibility:hidden; display:none;" );
                $('#fixed_amount_container').attr("style", "visibility:hidden; display:none;" );
            } else if ($('#property_id').find('option:selected').data('calc-base') == 3) {
                $('#fixed_amount_container').attr("style", "visibility:visible; display:block;" );
                $('#square_feet').val('');
                $('#share_unit').val('');
                $('#square_feet').attr('disabled','disabled');
                $('#share_unit').attr('disabled','disabled');
                $('#share_unit_container').attr("style", "visibility:hidden; display:none;" );
                $('#square_feet_container').attr("style", "visibility:hidden; display:none;" );
            }
            //calculateCharges ();            
        } else {
            $('#square_feet').removeAttr('disabled');
            $('#share_unit').removeAttr('disabled');
            /*$('input[name="unit[service_charge]"').removeAttr('readonly');
            $('input[name="unit[sinking_fund]"').removeAttr('readonly');
            $('input[name="unit[insurance_prem]"').removeAttr('readonly');
            $('input[name="unit[quit_rent]"').removeAttr('readonly');*/
        }
    }
}

function calculateCharges () {
    if($('#property_id').find('option:selected').data('calc-base') == 1 && $('#property_id').find('option:selected').data('per-sq-feet') != '' && $('#square_feet').val() != '') {
        var sc = eval($('#property_id').find('option:selected').data('per-sq-feet'))*eval($('#square_feet').val());
        $('input[name="unit[service_charge]"').val(sc.toFixed(2));
        if($('input[name="unit[service_charge]"').val() != '' && eval($('input[name="unit[service_charge]"').val()) > 0 && $('#property_id').find('option:selected').data('sinking-fund') != '') {
            var sinking_fund = (eval($('input[name="unit[service_charge]"').val())*eval($('#property_id').find('option:selected').data('sinking-fund')))/100;
            $('input[name="unit[sinking_fund]"').val(sinking_fund.toFixed(2));             
        }
        if($('#property_id').find('option:selected').data('insurance-prem') != '' && $('#property_id').find('option:selected').data('tot-sq-feet') != '') {
            var insurance_prem = (eval($('#property_id').find('option:selected').data('insurance-prem'))/eval($('#property_id').find('option:selected').data('tot-sq-feet')))*eval($('#square_feet').val());
            $('input[name="unit[insurance_prem]"').val(insurance_prem.toFixed(2));
        }
        if($('#property_id').find('option:selected').data('quit-rent') != '' && $('#property_id').find('option:selected').data('tot-sq-feet') != '') {
            var quit_rent = (eval($('#property_id').find('option:selected').data('quit-rent'))/eval($('#property_id').find('option:selected').data('tot-sq-feet')))*eval($('#square_feet').val());
            $('input[name="unit[quit_rent]"').val(quit_rent.toFixed(2));
        }        
                
    } else if($('#property_id').find('option:selected').data('calc-base') == 2 && $('#property_id').find('option:selected').data('per-share-unit') != '' && $('#share_unit').val() != '') {
        
        var sc = eval($('#property_id').find('option:selected').data('per-share-unit'))*eval($('#share_unit').val());
        $('input[name="unit[service_charge]"').val(sc.toFixed(2));
        if($('input[name="unit[service_charge]"').val() != '' && eval($('input[name="unit[service_charge]"').val()) > 0 && $('#property_id').find('option:selected').data('sinking-fund') != '') {
            var sinking_fund = (eval($('input[name="unit[service_charge]"').val())*eval($('#property_id').find('option:selected').data('sinking-fund')))/100;
            $('input[name="unit[sinking_fund]"').val(sinking_fund.toFixed(2));            
        }
        if($('#property_id').find('option:selected').data('insurance-prem') != '' && $('#property_id').find('option:selected').data('tot-share-unit') != '') {
            //console.log((eval($('#property_id').find('option:selected').data('insurance-prem'))/eval($('#property_id').find('option:selected').data('tot-share-unit')))*eval($('#share_unit').val()));
            var insurance_prem = (eval($('#property_id').find('option:selected').data('insurance-prem'))/eval($('#property_id').find('option:selected').data('tot-share-unit')))*eval($('#share_unit').val());
            $('input[name="unit[insurance_prem]"').val(insurance_prem.toFixed(2));
        }   
        if($('#property_id').find('option:selected').data('quit-rent') != '' && $('#property_id').find('option:selected').data('tot-share-unit') != '') {
            var quit_rent = (eval($('#property_id').find('option:selected').data('quit-rent'))/eval($('#property_id').find('option:selected').data('tot-share-unit')))*eval($('#share_unit').val());
            $('input[name="unit[quit_rent]"').val(quit_rent.toFixed(2));
        } 
        
    }
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