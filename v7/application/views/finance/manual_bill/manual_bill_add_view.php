<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/select2/dist/css/select2.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="visible-xs">
      <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
    </h1>
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
      <!--div class="box-header with-border">
<h3 class="box-title">Quick Example</h3>
</div-->
      <!-- /.box-header -->
      <div class="box-body" style="padding-top: 15px;">
        <?php if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
            unset($_SESSION['flash_msg']);
            }
            ?>
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
          <div class="row" style="background-color: #d2cece; height: 50px;" >
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($bill['bill_id']) ? 'Update Invoice ('.$bill['bill_no'].')' : 'New Invoice';?> </h3>
          </div>
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_bills/manual_bill_submit'); ?>">
            <div class="row" style="padding-top: 15px;padding-bottom:15px;">
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                <div class="col-md-1 col-xs-3"> Property </div>
                <div class="col-md-3 col-xs-5">
                  <select class="form-control" id="property_id" name="bill[property_id]">
                    <option value="">Select</option>
                    <?php 
                    foreach ($properties as $key=>$val) {
                        $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                        echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev'] . "' data-prop-due-days='".$val['bill_due_days'] . "'" . $selected .">".$val['property_name']."</option>";
                    } ?> 
                  </select>
                  <!-- Hidden fields -->
                  <input type="hidden" name="bill[bill_id]" value="<?php echo !empty($bill['bill_id']) ? $bill['bill_id'] : '';?>" />
                  <input type="hidden" name="bill[bill_no]" value="<?php echo !empty($bill['bill_no']) ? $bill['bill_no'] : '';?>" />
                  <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                  
                  
                  
                </div>
                <!--block id-->
                <div class="col-md-1 col-xs-3"> Block/Street </div>
                <div class="col-md-3 col-xs-5">
                  
                  <select class="form-control" id="block_id" name="bill[block_id]">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($blocks)) {
                        foreach ($blocks as $key=>$val) {
                            $selected = isset($bill['block_id']) && $bill['block_id'] == $val['block_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['block_id']."' ".$selected.">".$val['block_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div> 
                <!--unit no section-->
                <div class="col-md-1 col-xs-3"> Unit *</div>
                <div class="col-md-3 col-xs-5" style="">
                  <select name="bill[unit_id]" class="form-control select2" id="unit_id">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($units)) {
                        foreach ($units as $key=>$val) {
                            $selected = isset($bill['unit_id']) && $bill['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['unit_id']."'
                            data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
                        }
                    }
                    ?>                                   
                  </select>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                <div class="col-md-1 col-xs-3"> Name </div>
                <div class="col-md-3 col-xs-5">
                  <input type="text" id="owner" value=""  class="form-control">
                </div>
                <div class="col-md-1 col-xs-3">Bill Date</div>
                <div class="col-md-3 col-xs-5">                  
                  <input type="text" name="bill[bill_date]" id="bill_date" value="<?php echo !empty($bill['bill_date']) ? date('d-m-Y',strtotime($bill['bill_date'])) : date("d-m-Y"); ?>" class="form-control datepicker">
                </div>
                
                <div class="col-md-1 col-xs-3">Due Date</div>
                <div class="col-md-3 col-xs-5">                  
                  <input type="text" name="bill[bill_due_date]" id="due_date"  value="<?php echo !empty($bill['bill_due_date']) ? date('d-m-Y',strtotime($bill['bill_due_date'])) : date('d-m-Y', strtotime(date('d-m-Y'). ' + 14 days')); ?>" class="form-control datepicker">
                </div>                
              </div>	
              
              
              <div class="row">
                  <div class="col-md-12">              
                    <div class="col-md-6 col-xs-6" style="padding-left: 5px;">
                      <h3><b>Items</b></h3>
                      (All Currencies are in RM)                  
                    </div>
                    <div class="col-md-6 col-xs-6 text-right" style="margin-top:25px;">
                      <button type="button" name="add" id="add_line_item" data-id="<?php echo !empty($bill_items) ? count($bill_items) + 1 : 2;?>" class="btn btn-primary"  >Add Item</button>  
                    </div>
                  </div>                                    
              </div>
             
              
              <div class="row items_container" >
                <div class="col-md-12 " >
                    <div class="col-md-3">
                        <label>Item name</label>
                    </div>
                    
                    <div class="col-md-2">                    
                      <label>Period</label>
                    </div>
                    <div class="col-md-4">
                      <label>Description</label>
                    </div>
                    <div class="col-md-2">
                      <label>Amount</label>
                    </div>    
                    <div class="col-md-1">&nbsp;</div>             
                </div>
                
                <?php 
                
                if(empty($bill_items)) {
                    $bill_items = array(array('bill_item_id'=>'','item_cat_id'=>'','item_sub_cat_id'=>'','item_period'=>'','item_descrip'=>'','item_amount'=>'','sub_cat_dd'=>array()));
                } 
                foreach ($bill_items as $Bkey=>$Bval) {
                                
                ?>
                <div class="col-md-12 item_<?php echo ($Bkey+1);?>" style="padding-top: 10px !important;" >
                    <div class="col-md-3">
                    
                    <input type="hidden" name="items[bill_item_id][]" id="bill_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['bill_item_id']) ? $Bval['bill_item_id'] : '';?>"  />
                    
                        <select name="items[item_cat_id][]" id="cat_dd_<?php echo ($Bkey+1);?>" class="form-control cat_dd" data-id="<?php echo ($Bkey+1);?>" required="required" >
                            <option value="">Select</option>
                            <?php 
                            $period = '';
                            foreach ($sales_items as $key=>$val) {
                                
                                if(!empty ($Bval['item_cat_id']) && $Bval['item_cat_id'] == $val['coa_id']) {
                                    $selected =  'selected="selected"';
                                    if(!empty($val['period'])) {
                                        $period = $val['period'];
                                    }
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='".$val['coa_id']."' data-period='".$val['period']."' ".$selected.">".$val['coa_name']."</option>";
                            } ?> 
                        </select>
                          
                    </div>
                    
                    <div class="col-md-2">                    
                      <select class="form-control period_dd" name="items[item_period][]" id="period_dd_<?php echo ($Bkey+1);?>" data-id="<?php echo ($Bkey+1);?>">
                      <?php echo get_period_dd($period,(!empty ($Bval['item_period']) ? $Bval['item_period'] : '')); ?>
                      </select>
                      
                    </div>
                    <div class="col-md-4">
                      <input type="text" name="items[item_descrip][]" id="desc_txt_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                      <input type="number" name="items[item_amount][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" required="required">
                    </div>  
                    <div class="col-md-1 text-center"><button type="button" class="btn btn-danger btn-remove" data-id="<?php echo ($Bkey+1);?>"><i class="fa fa-close"></i></button></div>                      
                </div>
                
                <?php } ?>
              </div>
          <!--end default open-->
        
              <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                <div class="col-md-8 col-xs-6">&nbsp;</div>
                  
                <div class="col-md-1 col-xs-12" style="padding-top: 5px !important;">
                  <label>Total</label>
                </div>
                <div class="col-md-2 col-xs-12 " >
                  <input type="text" class="total_amt form-control" name="bill[total_amount]" value="0" style="text-align: right;" readonly="true" >
                </div>
                <div class="col-md-1">&nbsp;</div>    
              </div>                     
              
              <div class="col-md-12 no-padding" style="padding: 10px 0 !important;">
                <div class="col-md-2 col-xs-6">
                  <h3>
                    <b>Remarks</b>
                  </h3>
                </div>
                <div class="col-md-6 col-xs-12" >
                  <textarea rows="4" name="bill[remarks]" class="form-control" cols="50"><?php echo !empty($bill['remarks'])? $bill['remarks'] : '';?></textarea>
                </div>
              </div>
              
              <div style="color:red;padding: 15px 15px !important;"> (*) indicates mandatory fields.</div>
              
              <div class="col-md-12 no-padding">
                <div class="col-md-2 col-xs-6">
                </div>
                <div class="col-md-10 col-xs-12" >
                  <div class="col-md-6">
                    <input type="submit" value="Save"  class="btn btn-primary" style="float: right;">
                  </div>
                  <div class="col-md-6">
                    <input type="reset"  value="Reset" class="btn btn-primary" >
                  </div>
                </div>		
              </div>
             
            
          </div>		
          </form> 			
        
        </div>
          
        </div><!-- /.box-body -->
      </div><!-- /.box -->     
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- bootstrap datepicker -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>bower_components/select2/dist/js/select2.full.js"></script>
<script>
var sales_items = $.parseJSON('<?php echo !empty($sales_items) ? json_encode($sales_items) : json_encode(array());?>');

$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});

$(document).on( 'keypress', 'input', function (evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
        return false;
    }    
});

$(document).ready(function () {

    set_bil_due_date();

    $('#bill_date').change(function () {
        set_bil_due_date();
    });

    $('.select2').select2();
        
     $('#block_id').focus();
     $('.msg_notification').fadeOut(5000);
     calc_total_amt ();
     
     $('.amt_cal').keyup(function(){            
        calc_total_amt ();
     });       
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "bill[property_id]": "required",            
        "bill[block_id]": "required",
        "bill[unit_id]": "required",
        "bill[bill_due_date]": "required"
      },
      messages: {
        "bill[property_id]": "Please select Property",
        "bill[block_id]": "Please select Block/Street",
        "bill[unit_id]": "Please select Unit",
        "bill[bill_due_date]": "Please select Due Date"
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );
        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        }
        else if ( element.prop( "id" ) === "datepicker" ) {
          error.insertAfter( element.parent( "div" ) );
        }
        else {
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
    
    
    // On document ready
    if($('#property_id').val() != '' && $('#block_id').val() == '') {
        //console.log($('#property_id').val());
        property_change_eve ();//$('#property_id').trigger("change");
    }
    if($('#unit_id').val() != '') {
        set_owner ();
    }
    
    // On property name change
    $('#property_id').change(function () {    
        //console.log($('#property_id').val());
        property_change_eve ();//$('#property_id').trigger("change");
    });  
    
    // On block/street change
    $('#block_id').change(function () {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/get_unit');?>',
            data: {'property_id':$('#property_id').val(),'block_id':$('#block_id').val()},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });
        
    /*function unset_resident_info () {
        $('#owner').val('');         
    } */   
    
    $('#unit_id').change (function () {
        set_owner (); 
    });
    
    $('.cat_dd').change(function () {
        var dd_id = $(this).attr('data-id');
        if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
            get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
        } else {
            $('#period_dd_'+dd_id).html('<option value="">Select</option>');
            show_descrip ($(this).attr('data-id'));
        }
        
        
        //load_sub_cat ($(this).val(),$(this).attr('data-id'));
    });
    
    /*$('.sub_cat_dd').change(function () {        
        show_descrip ($(this).attr('data-id'));
        load_sub_cat_period ($(this).attr('data-id'));
    });*/
    
    $('.period_dd').change(function () {        
        show_descrip ($(this).attr('data-id'));        
    });   
    
});

function set_bil_due_date() {
    var bill_due_days = $('#property_id').find('option:selected').data('prop-due-days');

    if ( bill_due_days == '' ) {
        bill_due_days = 14;
    }

    var in_date_arr = $('#bill_date').val().split('-');
    var in_date = in_date_arr[1]+'/'+in_date_arr[0]+'/'+in_date_arr[2];

    var due_date = new Date(in_date);
    due_date.setDate( due_date.getDate() + bill_due_days );
    var date = due_date.getDate() + '-' + ((due_date.getMonth()+1) > 9 ? (due_date.getMonth()+1) : '0'+(due_date.getMonth()+1)) + '-' +  due_date.getFullYear();
    $('#due_date').val(date);
}

function set_owner () {
    $('#owner').val('');  
    $('#prop_abbr').val('');                      
    if(typeof($('#unit_id').find('option:selected').data('owner')) != 'undefined') {
        $('#owner').val($('#unit_id').find('option:selected').data('owner'));        
    }
     
    if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
        $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));        
    }
}   
  

// Load block and assign to drop down
function property_change_eve () {
    if($('#property_id').val() != '') {
        $('#property_name').val($(this).find('option:selected').data('pname'));
    } else {
        $('#property_name').val('');
    }
    
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
                    str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                });
            }
            $('#block_id').html(str); 
            $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
            set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already
            //$('#assign_to').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already               
            $("#content_area").LoadingOverlay("hide", true);
            //loadBank ($('#property_id').val());
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function load_sub_cat (cat_id,dd_id) {
    //console.log(sub_cat_dd_id);
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/getSubCategory');?>',
        data: {'cat_id':cat_id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {  
            
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.charge_code_sub_category_id+'" data-period="'+item.period+'" >'+item.charge_code_sub_category_name+'</option>';
                });
            } else {
                str = '<option value="">None</option>';
                
            }
            if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
                get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
            } else {
                $('#period_dd_'+dd_id).html('<option value="">Select</option>');
            }
            $('#sub_cat_dd_'+dd_id).html(str); 
            show_descrip (dd_id);                    
            $("#content_area").LoadingOverlay("hide", true);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function get_period (period_format,dd_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/get_period');?>',
        data: {'period_format':period_format},
        datatype:"json", // others: xml, json; default is html
        /*beforeSend:function (){ $("#content_area").LoadingOverlay("show");  },*/ //
        success: function(data) { 
            $('#period_dd_'+dd_id).html(data);
            show_descrip (dd_id);
            $('.period_dd').bind("change");
            $('.period_dd').bind("change",function () {        
                show_descrip ($(this).attr('data-id'));        
            }); 
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
    
function show_descrip (id) {
    var cat_val = $('#cat_dd_'+id).find('option:selected').text()  != 'Select' ? $('#cat_dd_'+id).find('option:selected').text() : ''; 
    
    var period_val = $('#period_dd_'+id).val() != '' ? $('#period_dd_'+id).val() : '';
    if(cat_val != '' && period_val != '') {
        period_val = ' (' + period_val + ')'; 
    }
    $('#desc_txt_id_'+id).val(cat_val + ' '+ period_val);
}

function load_sub_cat_period (dd_id) {
    if(typeof($('#sub_cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#sub_cat_dd_'+dd_id).find('option:selected').data('period') != '') {
        get_period($('#sub_cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
    } else {
        $('#period_dd_'+dd_id).html('<option value="">Select</option>');
    }
}

function calc_total_amt () {
    //console.log('called');
    var total = 0;
    $('.amt_cal').each(function () {
        if($(this).val() != '') {
            //tot_tot_amt = (parseFloat(tot_tot_amt) + parseFloat($(this).val())).toFixed(5);
           total = (parseFloat(total) + parseFloat($(this).val())).toFixed(5);
           //total += eval($(this).val()); 
        }
        
    });     
    total = parseFloat(total).toFixed(2);
    var net_total = 0;
    /*var round_chk = total - parseFloat(total).toFixed(1);
    var round = 0;
    //console.log(round_chk);
    if(round_chk == 0.01 || round_chk == 0.06) {
        round = -0.01;
    } else if(round_chk == 0.02 || round_chk == 0.07) {
        round = -0.02;
    } else if(round_chk == 0.03 || round_chk == 0.08) {
        round = 0.02;
    } else if(round_chk == 0.04 || round_chk == 0.09) {
        round = 0.01;
    } */
    //$('.round_cls').html(round);
    $('.total_amt').val(parseFloat(total).toFixed(2));   
}

$('#add_line_item').click (function () {
    var  id = $(this).attr('data-id');
    
    // alert(rdivs);
    var row = '';
    row += '<div class="col-md-12 item_'+id+'" style="padding-top: 10px !important;" >' ;
    row += '<div class="col-md-3">' ;
    row += '<select name="items[item_cat_id][]" id="cat_dd_'+id+'" class="form-control cat_dd" data-id="'+id+'" required="required">';
    row += '<option value="">Select</option>';
    $.each(sales_items,function (i, item) { 
        row += '<option value="'+item.coa_id+'" data-period="'+item.period+'">'+item.coa_name+'</option>';
    });
    row += '</select>';
    row += '</div>';
    
    row += '<div class="col-md-2">';
    row += '<select class="form-control period_dd" name="items[item_period][]" id="period_dd_'+id+'" data-id="'+id+'">'
    row += '</select>';
    row += '</div>';
    row += '<div class="col-md-4">';
    row += '<input type="text" name="items[item_descrip][]" id="desc_txt_id_'+id+'" value="" class="form-control">';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="items[item_amount][]" class="form-control amt_cal" value="" required="required">';
    row += '</div>';
    row += '<div class="col-md-1 text-center">';
    row += '<button type="button" class="btn btn-danger btn-remove" data-id="'+id+'"><i class="fa fa-close"></i></button>';
    row += '</div>';
    row += '</div>';
    $('.items_container').append(row);
    
    $(this).attr('data-id',(id+1));
    
    $('.cat_dd').unbind('change');
    $('.cat_dd').change(function () {
        var dd_id = $(this).attr('data-id');
        if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
            get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
        } else {
            $('#period_dd_'+dd_id).html('<option value="">Select</option>');
            show_descrip ($(this).attr('data-id'));            
        }
        
    });
    
    
    
    $('.amt_cal').unbind('keyup');
    $('.amt_cal').bind('keyup',function (){
       calc_total_amt ();
    });
    $('.btn-remove').unbind('click');
        
    $('.btn-remove').bind("click", function () {
        remove_item ($(this).attr('data-id'));    
    });
    
    /*$('.cat_dd').removeAttr('required');
    $('.cat_dd').attr('required','required');
    
    ("#bms_frm").on('submit', function(event) {

        // adding rules for inputs with class 'comment'
        $('select.cat_dd').each(function() {
            $(this).rules("add", 
                {
                    required: true
                })
        });            

        // prevent default submit action         
        event.preventDefault();

        // test if form is valid 
        if($("#bms_frm").validate().form()) {
            console.log("validates");
        } else {
            console.log("does not validate");
        }
    })*/
            
});  
  
$('.btn-remove').click(function () {
    remove_item ($(this).attr('data-id'));    
});

function remove_item (id){
    if($('#bill_item_id_'+id).val() != '') {
        if(confirm('You cannot undo this action. Are you sure want to delete?')) {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_bills/unset_bill_item');?>',
                data: {'bill_item_id':$('#bill_item_id_'+id).val()},
                datatype:"html", // others: xml, json; default is html
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) {
                    $('.item_'+id).remove();
                    calc_total_amt ();
                    $("#content_area").LoadingOverlay("hide", true);
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);              
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
    } else {
        $('.item_'+id).remove();
        calc_total_amt ();
    }
    
}
  
$(function () {
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });        
});     
</script>