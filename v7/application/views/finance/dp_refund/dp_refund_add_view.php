<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">

<style>
.items_container > div > div > div { padding: 0 5px !important; }
.items_container > div > div > div > select, .items_container > div > div > div > input { padding:6px !important; }
</style>

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
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($dp_refund['depo_receive_id']) ? 'Update Deposit Refund('.$dp_refund['doc_ref_no'].')' : 'New Deposit Refund';?> </h3>
          </div>
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_dp_refund/add_dp_refund_submit'); ?>">
            <div class="row" style="padding-top: 15px;padding-bottom:15px;">
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                <div class="col-md-1 col-xs-3"> Property </div>
                <div class="col-md-3 col-xs-5">
                
                    <select class="form-control" id="property_id" name="dp_refund[property_id]">                 
                    <option value="">Select</option>
                    <?php 
                    foreach ($properties as $key=>$val) {
                        $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                        echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                    } ?> 
                  </select>
                  <!-- Hidden fields -->
                  <input type="hidden" name="dp_refund[depo_refund_id]" value="<?php echo !empty($dp_refund['depo_refund_id']) ? $dp_refund['depo_refund_id'] : '';?>" />
                  <input type="hidden" name="dp_refund[doc_ref_no]" value="<?php echo !empty($dp_refund['doc_ref_no']) ? $dp_refund['doc_ref_no'] : '';?>" />
                  <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                  
                  
                  
                </div>
                <!--block id-->
                <div class="col-md-1 col-xs-3"> Block/Street</div>
                <div class="col-md-3 col-xs-5">
                
                    <select class="form-control" id="block_id" name="dp_refund[block_id]">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($blocks)) {
                        foreach ($blocks as $key=>$val) {
                            $selected = isset($dp_refund['block_id']) && $dp_refund['block_id'] == $val['block_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['block_id']."' ".$selected.">".$val['block_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div> 
                <!--unit no section-->
                <div class="col-md-1 col-xs-3"> Unit *</div>
                <div class="col-md-3 col-xs-5" style="">
                
                    <select name="dp_refund[unit_id]" class="form-control" id="unit_id">                
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($units)) {
                        foreach ($units as $key=>$val) {
                            $selected = isset($dp_refund['unit_id']) && $dp_refund['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
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
                  <input type="text" name="dp_refund[depo_refund_date]" value="<?php echo !empty($dp_refund['depo_refund_date']) ? date('d-m-Y',strtotime($dp_refund['depo_refund_date'])) : date("d-m-Y"); ?>" class="form-control datepicker" />
                </div>
                
                <div class="col-md-1 col-xs-3">Bank *</div>
                <div class="col-md-3 col-xs-5">   
                
                    <select class="form-control" id="bank_id" name="dp_refund[bank_id]">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($banks)) {
                        foreach ($banks as $key=>$val) {
                            $selected = isset($dp_refund['bank_id']) && $dp_refund['bank_id'] == $val['bank_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['bank_id']."' ".$selected.">".$val['bank_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div>                
              </div>	
              <div class="row">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-2 col-xs-4">
                       <label>Deposit Receipt No *</label>
                    </div>
                    
                    
                   <div class="col-md-2 col-xs-4">
                    
                        <select class="form-control" id="deposit_no" name="dp_refund[depo_receive_id]">
                            <option value="">Select</option>                    
                            
                        </select>
                        
                        
                        <input type="hidden" id="coa_id" name="dp_refund[coa_id]" value="" />
                        <input type="hidden" id="description" name="dp_refund[description]" value="" />
                        <input type="hidden" id="amount" name="dp_refund[amount]" value="" />
                        
                    </div>
                    
                  
                  </div>                                    
              </div>
              
              <div class="row">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-2 col-xs-4">
                       <label>Payment mode *</label>
                    </div>
                    
                    
                   <div class="col-md-2 col-xs-4">
                    
                        <select class="form-control" id="payment_mode" name="dp_refund[payment_mode]">
                            <option value="">Select</option> 
                    
                            <?php $payment_mode = $this->config->item ('payment_mode'); 
                            foreach ($payment_mode as $key=>$val) { 
                                if($key != 5) {
                                    $selected = isset($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == $key ?  'selected="selected" ' : '';
                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_2" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Bank
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_bank]" value="<?php echo !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 2 && !empty($dp_refund['bank']) ? $dp_refund['bank'] : '';?>" class="form-control">
                    </div>
                    
                    <div class="col-md-1 col-xs-4" >
                       Cheque No.
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_no]" value="<?php echo !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 2 && !empty($dp_refund['cheq_card_txn_no']) ? $dp_refund['cheq_card_txn_no'] : '';?>" class="form-control">
                    </div>
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Date
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_date]" value="<?php echo !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 2 && !empty($dp_refund['cheq_txn_online_date']) ? date('d-m-Y',strtotime($dp_refund['cheq_txn_online_date'])) : '';?>" class="form-control datepicker">
                    </div>
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_3" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Bank
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[card_bank]" value="<?php echo !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 3 && !empty($dp_refund['bank']) ? $dp_refund['bank'] : '';?>" class="form-control">
                    </div>
                    
                    <div class="col-md-1 col-xs-4" >
                       Txn No.
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[card_txn_no]" value="<?php echo !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 3 && !empty($dp_refund['cheq_card_txn_no']) ? $dp_refund['cheq_card_txn_no'] : '';?>" class="form-control">
                    </div>
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Card Type
                    </div>
                    <div class="col-md-3 col-xs-4">
                       
                       <select name="pm_details[card_type]" class="form-control" >
                            <option value="">Select</option> 
                            <?php $card_type = $this->config->item ('card_type');                    
                                foreach ($card_type as $key=>$val) {
                                    echo $selected = !empty($dp_refund['payment_mode']) && $dp_refund['payment_mode'] == 3 && $key == $dp_refund['online_r_card_type'] ? 'selected="selected"' : '';
                                    echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                                }                            
                            ?>
                                                         
                      </select>
                    </div>
                  
                  </div>                                    
              </div>
              
              
          <!--end default open-->
                     
              <div class="col-md-12 no-padding" style="padding: 20px 0 10px 0 !important;">
                <div class="col-md-2 col-xs-6">
                  <h3>
                    <b>Remarks</b>
                  </h3>
                </div>
                <div class="col-md-6 col-xs-12" >
                  <textarea rows="4" name="dp_refund[remarks]" class="form-control" cols="50"><?php echo !empty($dp_refund['remarks'])? $dp_refund['remarks'] : '';?></textarea>
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
                    <input type="reset"  value="Reset" class="btn btn-primary reset_btn" >
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
<script src="<?php echo base_url();?>assets/js/jquery.number.js"></script>
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
    
    $('#block_id').focus();
    $('.msg_notification').fadeOut(5000);           
     
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "dp_refund[property_id]": "required",            
        "dp_refund[block_id]": "required",
        "dp_refund[unit_id]": "required",
        "dp_refund[deposit_date]": "required",
        "dp_refund[bank_id]":"required",
        "dp_refund[paid_amount]":"required",
        "dp_refund[payment_mode]":"required",
        "dp_refund[payment_mode]":"required",
      },
      messages: {
        "dp_refund[property_id]": "Please select Property",
        "dp_refund[block_id]": "Please select Block/Street",
        "dp_refund[unit_id]": "Please select Unit",
        "dp_refund[deposit_date]": "Please select Due Date",
        "dp_refund[bank_id]":"Please select Bank",
        "dp_refund[paid_amount]":"Please enter Paid Amount",
        "dp_refund[payment_mode]":"Please select Payment mode",
        "dp_refund[payment_mode]":"Please select Payment mode",
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
        
    
    $('#unit_id').change (function () {
        set_owner ();  
        if($(this).val() != '')
            get_unit_deposits ($(this).val());
        else 
            $('#receipt_id').html('<option value="">Select</option>');        
    });   
    
    $('#deposit_no').change(function () {    
        if($(this).val() != '') {
            //$('#unit_status').val($(this).find('option:selected').data('total-amount'));
            $('#coa_id').val($(this).find('option:selected').data('coa-id'));
            $('#description').val($(this).find('option:selected').data('description'));
            $('#amount').val($(this).find('option:selected').data('amount'));
            
        } else {
            $('#coa_id').val('');
            $('#description').val('');
            $('#amount').val('');            
        }        
    });  
    
    $('#payment_mode').change(function () {
        set_pay_mode_details ();      
    });
    set_pay_mode_details ();
    
    $('.reset_btn').click(function () {
        $("#content_area").LoadingOverlay("show");
        window.location.reload();
    });
    
    
    
});  

function set_pay_mode_details () {
    $('.pay_mode_details').css('display','none');
    $('.pay_mode_'+$('#payment_mode').val()).slideDown();
    //$('.'+$("input[name='dp_refund[payment_mode]']:checked").attr('id')).css('display','block');
    //console.log($("input[name='dp_refund[pay_mode]']:checked").val());
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
                $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
                set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already
                $('#bank_id').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already               
                $("#content_area").LoadingOverlay("hide", true);
                loadBank ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    } else {
        $('#property_name').val('');
        var str = '<option value="">Select</option>'; 
        $('#unit_id').html(str);
        $('#block_id').html(str);  
        $('#bank_id').html(str); 
    }
}

function loadBank (property_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_dp_refund/get_banks');?>',
        data: {'property_id':$('#property_id').val()},
        datatype:"json", // others: xml, json; default is html
        success: function(data) {  
            /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                window.location.href= '<?php echo base_url();?>';
                return false;
            }*/
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.bank_id+'">'+item.bank_name+'</option>';
                });
            }
            $('#bank_id').html(str);            
        },
        error: function (e) {           
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function get_unit_deposits (unit_id) {
    //console.log(sub_cat_dd_id);
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_dp_refund/get_unit_deposits');?>',
        data: {'unit_id':unit_id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {  
            
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.depo_receive_id+'" data-amount="'+item.amount+'" data-description="'+item.description+'" data-coa-id="'+item.coa_id+'"  >'+item.doc_ref_no+' ('+item.amount+')'+'</option>';
                });
            } 
            
            $('#deposit_no').html(str); 
                       
            $("#content_area").LoadingOverlay("hide", true);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
  
$(function () {
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });        
});     
</script>