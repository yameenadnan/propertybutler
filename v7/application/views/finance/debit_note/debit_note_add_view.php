<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">

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
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($debit_note['debit_note_id']) ? 'Update Debit Note ('.$debit_note['debit_note_no'].')' : 'New Debit Note';?> </h3>
          </div>
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_debit_note/debit_note_submit'); ?>">
            <div class="row" style="padding-top: 15px;padding-bottom:15px;">
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                <div class="col-md-1 col-xs-3"> Property </div>
                <div class="col-md-3 col-xs-5">
                  <select class="form-control" id="property_id" name="debit_note[property_id]">
                    <option value="">Select</option>
                    <?php 
                    foreach ($properties as $key=>$val) {
                        $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                        echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                    } ?> 
                  </select>
                  <!-- Hidden fields -->
                  <input type="hidden" name="debit_note[debit_note_id]" value="<?php echo !empty($debit_note['debit_note_id']) ? $debit_note['debit_note_id'] : '';?>" />
                  <input type="hidden" name="debit_note[debit_note_no]" value="<?php echo !empty($debit_note['debit_note_no']) ? $debit_note['debit_note_no'] : '';?>" />
                  <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                  
                  
                  
                </div>
                <!--block id-->
                <div class="col-md-1 col-xs-3"> Block/Street </div>
                <div class="col-md-3 col-xs-5">
                  
                  <select class="form-control" id="block_id" name="debit_note[block_id]">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($blocks)) {
                        foreach ($blocks as $key=>$val) {
                            $selected = isset($debit_note['block_id']) && $debit_note['block_id'] == $val['block_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['block_id']."' ".$selected.">".$val['block_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div> 
                <!--unit no section-->
                <div class="col-md-1 col-xs-3"> Unit *</div>
                <div class="col-md-3 col-xs-5" style="">
                  <select name="debit_note[unit_id]" class="form-control" id="unit_id">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($units)) {
                        foreach ($units as $key=>$val) {
                            $selected = isset($debit_note['unit_id']) && $debit_note['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['unit_id']."'
                            data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
                        }
                    }
                    ?>                                   
                  </select>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                <div class="col-md-1 col-xs-3"> From </div>
                <div class="col-md-3 col-xs-5">
                  <input type="text" id="owner" value=""  class="form-control">
                </div>
                <div class="col-md-1 col-xs-3">Date</div>
                <div class="col-md-3 col-xs-5">                  
                  <input type="text" name="debit_note[debit_note_date]" value="<?php echo !empty($debit_note['debit_note_date']) ? date('d-m-Y',strtotime($debit_note['debit_note_date'])) : date("d-m-Y"); ?>" class="form-control datepicker">
                </div>
                <div class="col-md-1 col-xs-3"> Receipt_No*</div>
                <div class="col-md-3 col-xs-5">                  
                  <select class="form-control" id="receipt_id" name="debit_note[receipt_id]" >
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($receipts)) {
                        foreach ($receipts as $key=>$val) {
                            $selected = isset($debit_note['receipt_id']) && $debit_note['receipt_id'] == $val['receipt_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['receipt_id']."' ".$selected.">".$val['receipt_no'].'('.$val['total_amount'].')'."</option>";
                        }
                    }
                    ?>                                
                  </select>
                  <input type="hidden" id="total_amount" name="debit_note[total_amount]" value="" />
                  <input type="hidden" id="bank_id" name="debit_note[bank_id]" value="" />
                </div>
                
                
                              
              </div>	
              
              
              <div class="row">
                                                    
              </div>
             
              
              <div class="row items_container" >
                
                
              </div>
          <!--end default open-->
        
              <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                
              </div>                     
              
              <div class="col-md-12 no-padding" style="padding: 10px 0 !important;">
                <div class="col-md-2 col-xs-6">
                  <h3>
                    <b>Remarks</b>
                  </h3>
                </div>
                <div class="col-md-6 col-xs-12" >
                  <textarea rows="4" name="debit_note[remarks]" class="form-control" cols="50"><?php echo !empty($debit_note['remarks'])? $debit_note['remarks'] : '';?></textarea>
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
<script>
var sales_items = $.parseJSON('<?php echo !empty($sales_items) ? json_encode($sales_items) : json_encode(array());?>');

$(document).on( 'keypress', 'input', function (evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
        return false;
    }    
});

$(document).ready(function () {
    
     $('#block_id').focus();
     $('.msg_notification').fadeOut(5000);
          
     
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "debit_note[property_id]": "required",            
        "debit_note[block_id]": "required",
        "debit_note[unit_id]": "required",
        "debit_note[receipt_id]": "required",
      },
      messages: {
        "debit_note[property_id]": "Please select Property",
        "debit_note[block_id]": "Please select Block/Street",
        "debit_note[unit_id]": "Please select Unit",
        "debit_note[receipt_id]": "Please select  Receipt_No"
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
    
    $('#receipt_id').change(function () {    
        if($(this).val() != '') {
            //$('#unit_status').val($(this).find('option:selected').data('total-amount'));
            $('#total_amount').val($(this).find('option:selected').data('total-amount'));
            $('#bank_id').val($(this).find('option:selected').data('bank-id'));
        } else {
            $('#total_amount').val('');
            $('#bank_id').val('');
        }
        /*if($(this).val() != '') {
            //$('#total_amount').val($(this).attr('data-total-amount'));
        } eles {
            //$('#total_amount').val('');
        }*/
    });  
    
    
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
                 $('#receipt_id').html(str)
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
        if($(this).val() != '')
            get_unit_receipts ($(this).val());
        else 
            $('#receipt_id').html('<option value="">Select</option>'); 
    });  
    
});  

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
            /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                window.location.href= '<?php echo base_url();?>';
                return false;
            }*/
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                });
            }
            $('#block_id').html(str); 
            $('#unit_id, #receipt_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
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

function get_unit_receipts (unit_id) {
    //console.log(sub_cat_dd_id);
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_debit_note/get_unit_receipt');?>',
        data: {'unit_id':unit_id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {  
            
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.receipt_id+'" data-total-amount="'+item.paid_amount+'" data-bank-id="'+item.bank_id+'" >'+item.receipt_no+' ('+item.paid_amount+')'+'</option>';
                });
            } 
            
            $('#receipt_id').html(str); 
                       
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
        url: '<?php echo base_url('index.php/bms_fin_debit_note/get_period');?>',
        data: {'period_format':period_format},
        datatype:"json", // others: xml, json; default is html
        /*beforeSend:function (){ $("#content_area").LoadingOverlay("show");  },*/ //
        success: function(data) { 
            $('#period_dd_'+dd_id).html(data);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
    
function show_descrip (id) {
    
    $('#desc_txt_id_'+id).val($('#cat_dd_'+id).find('option:selected').text()+($('#sub_cat_dd_'+id).find('option:selected').text() != 'Select' ? '/'+$('#sub_cat_dd_'+id).find('option:selected').text() : ''));
}

function load_sub_cat_period (dd_id) {
    if(typeof($('#sub_cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#sub_cat_dd_'+dd_id).find('option:selected').data('period') != '') {
        get_period($('#sub_cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
    } else {
        $('#period_dd_'+dd_id).html('<option value="">Select</option><option value="Opening Balance">Opening Balance</option>');
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