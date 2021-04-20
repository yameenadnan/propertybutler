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
          <!--div class="row" style="background-color: #d2cece; height: 50px;" >
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($jv['jv_id']) ? 'Update Invoice ('.$jv['jv_no'].')' : 'New Invoice';?> </h3>
          </div-->
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_accounting/add_journal_entry_submit'); ?>">
            <div class="row" style="padding-top: 15px;padding-bottom:15px;">
              <div class="col-md-12 col-sm-12 col-xs-12"  style="padding: 10px 0 !important;" >
                <div class="col-md-2 col-xs-3"> <label>Property Name</label></div>
                <div class="col-md-3 col-xs-5">
                  
                  <input type="hidden"  name="jv[property_id]" value="<?php echo $property_id;?>" />
                  <select class="form-control" id="property_id" disabled="disabled">
                    <option value="">Select</option>
                    <?php 
                    $prop_abbr = '';
                    foreach ($properties as $key=>$val) {
                        $selected = '';
                        if(isset($property_id) && $property_id == $val['property_id']){
                            $selected = 'selected="selected" ';
                            $prop_abbr = $val['property_abbrev'];
                        }
                        
                        echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                    } ?> 
                  </select>
                  <!-- Hidden fields -->
                  <input type="hidden" name="jv[jv_id]" value="<?php echo !empty($jv['jv_id']) ? $jv['jv_id'] : '';?>" />
                  <input type="hidden" name="jv[jv_no]" value="<?php echo !empty($jv['jv_no']) ? $jv['jv_no'] : '';?>" />
                  <input type="hidden" id="prop_abbr" name="prop_abbr" value="<?php echo $prop_abbr;?>" />
                  
                  
                  
                </div>
                
                <div class="col-md-1 col-xs-3">&nbsp;</div>
                <div class="col-md-1 col-xs-3"><label>Date<label></div>
                <div class="col-md-3 col-xs-5">                  
                  <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="jv[jv_date]" id="jv_date" type="text"  value="<?php echo !empty($jv['jv_date']) ? date('d-m-Y',strtotime($jv['jv_date'])) : date("d-m-Y"); ?>" />
                                </div>
                </div>
                
              </div>
              
              
              
              <div class="row">
                  <div class="col-md-12">              
                    <div class="col-md-6 col-xs-6" style="padding-left: 5px;">
                      <h3><b>JV Items</b></h3>
                               
                    </div>
                    <div class="col-md-6 col-xs-6 text-right" style="margin-top:25px;">
                      <button type="button" name="add" id="add_line_item" data-id="<?php echo !empty($jv_items) ? count($jv_items) + 1 : 2;?>" class="btn btn-primary"  >Add JV Item</button>  
                    </div>
                  </div>                                    
              </div>
             
              
              <div class="row items_container" >
                <div class="col-md-12 " >
                    <div class="col-md-3">
                        <label>Account name</label>
                    </div>
                    <div class="col-md-4">
                      <label>Description</label>
                    </div>
                    <div class="col-md-2">
                      <label>Debit</label>
                    </div>    
                    <div class="col-md-2">
                      <label>Credit</label>
                    </div>
                    <div class="col-md-1">&nbsp;</div>             
                </div>
                
                <?php 
                
                if(empty($jv_items)) {
                    $jv_items = array(array('jv_item_id'=>'','jv_coa_id'=>'','description'=>'','debit'=>'','credit'=>''));
                } 
                foreach ($jv_items as $Bkey=>$Bval) {
                                
                ?>
                <div class="col-md-12 item_<?php echo ($Bkey+1);?>" style="padding-top: 10px !important;" >
                    <div class="col-md-3">
                    
                        <input type="hidden" name="jv_items[jv_item_id][]" id="jv_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['jv_item_id']) ? $Bval['jv_item_id'] : '';?>"  />
                    
                        <select name="jv_items[jv_coa_id][]" id="cat_dd_<?php echo ($Bkey+1);?>" class="form-control cat_dd" data-id="<?php echo ($Bkey+1);?>" required="required" >
                            <option value="">Select</option>
                            <?php 
                            
                            foreach ($coas as $key=>$val) {
                                
                                if(!empty ($Bval['jv_coa_id']) && $Bval['jv_coa_id'] == $val['coa_id']) {
                                    $selected =  'selected="selected"';                                    
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='".$val['coa_id']."' ".$selected.">".$val['coa_name']."</option>";
                            } ?> 
                          </select> 
                          
                    </div>
                    
                    
                    <div class="col-md-4">
                      <input type="text" name="jv_items[description][]" id="desc_txt_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['description']) ? $Bval['description'] : '';?>" required="required" class="form-control">
                    </div>
                    <div class="col-md-2">
                      <input type="number" name="jv_items[debit][]" class="form-control deb_amt_cal" value="<?php echo !empty ($Bval['debit']) ? $Bval['debit'] : '';?>" >
                    </div>  
                    <div class="col-md-2">
                      <input type="number" name="jv_items[credit][]" class="form-control cre_amt_cal" value="<?php echo !empty ($Bval['credit']) ? $Bval['credit'] : '';?>" >
                    </div>  
                    <div class="col-md-1 text-center"><button type="button" class="btn btn-danger btn-remove" data-id="<?php echo ($Bkey+1);?>"><i class="fa fa-close"></i></button></div>                      
                </div>
                
                <?php } ?>
              </div>
          <!--end default open-->
        
              <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                <div class="col-md-6 col-xs-6">&nbsp;</div>
                  
                <div class="col-md-1 col-xs-12" style="padding-top: 5px !important;">
                  <label>Total</label>
                </div>
                <div class="col-md-2 col-xs-12 " >
                  <input type="text" class="total_deb_amt form-control" name="jv[jv_total_debit]" value="0" style="text-align: right;" readonly="true" >
                </div>
                <div class="col-md-2 col-xs-12 " >
                  <input type="text" class="total_cre_amt form-control" name="jv[jv_total_credit]" value="0" style="text-align: right;" readonly="true" >
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
                  <textarea rows="4" name="jv[remarks]" class="form-control" cols="50"><?php echo !empty($jv['remarks'])? $jv['remarks'] : '';?></textarea>
                </div>
              </div>
              
              <div style="color:red;padding: 15px 15px !important;"> &nbsp;</div>
              
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
var sales_items = $.parseJSON('<?php echo !empty($coas) ? str_replace("'","\'",json_encode($coas)) : json_encode(array());?>');

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
     calc_total_amt ();
     
     $('.deb_amt_cal, .cre_amt_cal').keyup(function(){            
        calc_total_amt ();
     });       
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "jv[property_id]": "required",
        "jv[jv_date]": "required"
      },
      messages: {
        "jv[property_id]": "Please select Property",        
        "jv[jv_date]": "Please select Date"
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );
        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        } else if ( element.hasClass( "datepicker" ) ) {
				error.insertAfter( element.parent( "div" ) );
        } else if ( element.prop( "id" ) === "datepicker" ) {
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
    
    
    
});  

function calc_total_amt () {
    //console.log('called');
    var total = 0;
    $('.deb_amt_cal').each(function () {
        if($(this).val() != '') {
            //tot_tot_amt = (parseFloat(tot_tot_amt) + parseFloat($(this).val())).toFixed(5);
           total = (parseFloat(total) + parseFloat($(this).val())).toFixed(5);
           //total += eval($(this).val()); 
        }        
    });         
    total = parseFloat(total).toFixed(2);
    $('.total_deb_amt').val(parseFloat(total).toFixed(2));
    total = 0;   
    $('.cre_amt_cal').each(function () {
        if($(this).val() != '') {
            //tot_tot_amt = (parseFloat(tot_tot_amt) + parseFloat($(this).val())).toFixed(5);
           total = (parseFloat(total) + parseFloat($(this).val())).toFixed(5);
           //total += eval($(this).val()); 
        }        
    });         
    total = parseFloat(total).toFixed(2);
    $('.total_cre_amt').val(parseFloat(total).toFixed(2));   
    var net_total = 0;
    
    
}

$('#add_line_item').click (function () {
    var  id = $(this).attr('data-id');
    
    // alert(rdivs);
    var row = '';
    row += '<div class="col-md-12 item_'+id+'" style="padding-top: 10px !important;" >' ;
    row += '<div class="col-md-3">' ;
    row += '<input type="hidden" name="jv_items[jv_item_id][]" id="jv_item_id_'+id+'" value="" />'
    row += '<select name="jv_items[jv_coa_id][]" id="cat_dd_'+id+'" class="form-control cat_dd" data-id="'+id+'" required="required">';
    row += '<option value="">Select</option>';
    $.each(sales_items,function (i, item) { 
        row += '<option value="'+item.coa_id+'">'+item.coa_name+'</option>';
    });
    row += '</select>';
    row += '</div>';
    
    
    row += '<div class="col-md-4">';
    row += '<input type="text" name="jv_items[description][]" id="desc_txt_id_'+id+'" value="" required="required" class="form-control">';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="jv_items[debit][]" class="form-control deb_amt_cal" value="" >';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="jv_items[credit][]" class="form-control cre_amt_cal" value="" >';
    row += '</div>';
    row += '<div class="col-md-1 text-center">';
    row += '<button type="button" class="btn btn-danger btn-remove" data-id="'+id+'"><i class="fa fa-close"></i></button>';
    row += '</div>';
    row += '</div>';
    $('.items_container').append(row);
    
    $(this).attr('data-id',(id+1));
    
    
    
    $('.deb_amt_cal, .cre_amt_cal').unbind('keyup');
    $('.deb_amt_cal, .cre_amt_cal').bind('keyup',function (){
       calc_total_amt ();
    });
    $('.btn-remove').unbind('click');
        
    $('.btn-remove').bind("click", function () {
        remove_item ($(this).attr('data-id'));    
    });
});  
  
$('.btn-remove').click(function () {
    remove_item ($(this).attr('data-id'));    
});

function remove_item (id){
    if($('#jv_item_id_'+id).length > 0 && $('#jv_item_id_'+id).val() != '') {
        if(confirm('You cannot undo this action. Are you sure want to delete?')) {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_accounting/unset_jv_item');?>',
                data: {'jv_item_id':$('#jv_item_id_'+id).val()},
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