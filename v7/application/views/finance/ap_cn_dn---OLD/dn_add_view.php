<?php
$this->load->view('header');
$this->load->view('sidebar'); // echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet"  href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="hidden-xs">
            <?php echo !empty($page_header) && $page_header != '' ? $page_header : ''; ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <!-- general form elements -->
        <div class="box box-primary">
            <?php
 
                if (!empty($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
                    unset($_SESSION['flash_msg']);
                }
             ?>
            <div class="box-body">
                <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
                    <div class="row" style="background-color: #d2cece; height: 50px;" >
                        <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($poitem['ap_dn_id']) ? 'Update Debit Note ('.$poitem['ap_dn_id'].')' : 'New Credit Note';?> </h3>
                    </div>
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_ap_cn_dn/add_dn_submit');?>"  method="post">
                        <!-- Hidden fields -->
                        <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                        <!-- New Invoice Block Start -->
                        <div class="row" style="padding-top: 15px;padding-bottom:15px;">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-2">
                                    <label>Property Name *</label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="property_id" name="ap_dn[property_id]" readonly="readonly">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($properties as $key=>$val) {
                                            $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?>
                                    </select>
                                    <input type="hidden" name="ap_dn[ap_dn_id]" value="<?php echo !empty($ap_dn['ap_dn_id']) ? $ap_dn['ap_dn_id'] : '';?>" />
                                    <input type="hidden" name="ap_dn[ap_dn_no]" value="<?php echo !empty($ap_dn['ap_dn_no']) ? $ap_dn['ap_dn_no'] : '';?>" />
                                </div>
                                <div class="col-md-2">
                                    <label>Credit Note Date *</label>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="ap_dn[debit_note_date]" value="<?php echo !empty($ap_dn['debit_note_date']) ? date('d-m-Y',strtotime($ap_dn['debit_note_date'])) : date("d-m-Y"); ?>" class="form-control datepicker">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                                <div class="col-md-2">
                                    <label>Service Provider *</label>
                                </div>
                                <div class="col-md-4">
                                    <select required class="form-control select2" id="service_provider" name="ap_dn[service_provider_id]">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($service_provider as $key => $val) {
                                            $selected = !empty($poitem['service_provider_id']) && $ap_dn['service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='" . $val['service_provider_id'] . "'
											  data-spaddress='" . $val['address'] . "'
											  data-sppostcode='" . $val['postcode'] . "'
											  data-spcity='" . $val['city'] . "'
											  data-spstate='" . $val['state'] . "'
											  data-spcountry='" . $val['country_name'] . "'
											  data-spphoneno='" . $val['office_ph_no'] . "'
											  data-sppinc='" . $val['person_incharge'] . "'
											  data-sppincmobile='" . $val['person_inc_mobile'] . "'
											  data-sppincemail='" . $val['person_inc_email'] . "'
											" . $selected . ">" . $val['provider_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Invoice Number</label>
                                </div>

                                <div class="col-md-4">
                                    <select class="form-control select2" id="exp_inv_id" name="ap_dn[invoice_id]">
                                        <option value="">Select</option>
                                        <?php
										if(!empty($ponumb)){
                                        foreach ($ponumb as $key => $val) {
                                            $selected = $poitem['po_id'] == $val['pur_order_id'] ? 'selected="selected"' : '';
                                            echo "<option value='" . $val['pur_order_id'] . "'" . $selected . ">" . $val['po_no']. "</option>";
                                        }
										}
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- End New Invoice Block Start -->

                        <!-- /ITEM CONTAINER EDIT WITH PO SELECTION START -->
                        <div class="col-md-12 no-padding podetails" style="display: none">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Purchase Order summary</strong></h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive-sm">
                                        <div class="podisplayorder"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-sm-5 ml-auto"></div>
                                <div class="col-lg-3 col-sm-5 ml-auto">
                                    <div class="podisplaytotals"></div>
                                </div>
                            </div>
                        </div>
                        <!-- END ITEM CONTAINER EDIT WITH PO SELECTION START -->

                            <div class="row" style="padding-top: 15px;padding-bottom:15px;">



                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6 col-xs-6" style="padding-left: 5px;">
                                            <h3><b>Items</b></h3>
                                            (All Currencies are in RM)
                                        </div>
                                        <div class="col-md-6 col-xs-6 text-right" style="margin-top:25px;">
                                            <!--button type="button" name="add" id="add_line_item" data-id="<?php echo !empty($credit_note_items) ? count($credit_note_items) + 1 : 2;?>" class="btn btn-primary"  >Add Item</button-->
                                        </div>
                                    </div>
                                </div>


                                <div class="row items_container" style="border: 1px solid #;background-color: #ECF0F5;border: 1px solid #999; border-radius: 5px;margin: 15px 5px; padding: 15px 0 20px 0 !important;">
                                    <div class="col-md-12 no-padding">
                                        <div class="col-md-3 no-padding">
                                            <div class="col-md-7">
                                                <label>Item Name</label>
                                            </div>

                                            <div class="col-md-5">
                                                <label>Period</label>
                                            </div>

                                        </div>
                                        <div class="col-md-9 no-padding" >
                                            <div class="col-md-5">
                                                <label>Description</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Amount</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Adjust Amt</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Balance</label>
                                            </div>
                                            <div class="col-md-1">&nbsp;</div>
                                        </div>
                                    </div>

                                    <?php

                                    if(!empty($credit_note_items)) {

                                        foreach ($credit_note_items as $Bkey=>$Bval) {

                                            ?>
                                            <div class="col-md-12 no-padding item_<?php echo ($Bkey+1);?>"  >
                                                <div class="col-md-4 no-padding">
                                                    <div class="col-md-4">

                                                        <input type="hidden" name="items[credit_note_item_id][]" id="credit_note_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['credit_note_item_id']) ? $Bval['credit_note_item_id'] : '';?>"  />

                                                        <select name="items[item_cat_id][]" id="cat_dd_<?php echo ($Bkey+1);?>" class="form-control cat_dd" data-id="<?php echo ($Bkey+1);?>" >
                                                            <option value="">Select</option>
                                                            <?php
                                                            $period = '';
                                                            foreach ($sales_items as $key=>$val) {

                                                                if(!empty ($Bval['item_cat_id']) && $Bval['item_cat_id'] == $val['charge_code_category_id']) {
                                                                    $selected =  'selected="selected"';
                                                                    if(!empty($val['period'])) {
                                                                        $period = $val['period'];
                                                                    }
                                                                } else {
                                                                    $selected = '';
                                                                }
                                                                echo "<option value='".$val['charge_code_category_id']."' data-period='".$val['period']."' ".$selected.">".$val['charge_code_category_name']."</option>";
                                                            } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control sub_cat_dd" name="items[item_sub_cat_id][]" id="sub_cat_dd_<?php echo ($Bkey+1);?>" data-id="<?php echo ($Bkey+1);?>">
                                                            <option value="">Select</option>
                                                            <?php if(!empty($Bval['sub_cat_dd'])) {
                                                                foreach ($Bval['sub_cat_dd'] as $key=>$val) {

                                                                    if(!empty ($Bval['item_sub_cat_id']) && $Bval['item_sub_cat_id'] == $val['charge_code_sub_category_id']) {
                                                                        $selected =  'selected="selected"';
                                                                        if(!empty($val['period'])) {
                                                                            $period = $val['period'];
                                                                        }
                                                                    } else {
                                                                        $selected = '';
                                                                    }
                                                                    echo "<option value='".$val['charge_code_sub_category_id']."' data-period='".$val['period']."' ".$selected.">".$val['charge_code_sub_category_name']."</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control period_dd" name="items[item_period][]" id="period_dd_<?php echo ($Bkey+1);?>" data-id="<?php echo ($Bkey+1);?>">
                                                            <?php echo get_period_dd($period,(!empty ($Bval['item_period']) ? $Bval['item_period'] : '')); ?>
                                                        </select>

                                                    </div>


                                                </div>
                                                <div class="col-md-8 no-padding" >
                                                    <div class="col-md-5">
                                                        <input type="text" name="items[item_descrip][]" id="desc_txt_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" name="items[item_amount][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" >
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" name="items[paid_amount][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" >
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="number" name="items[bal_amount][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" >
                                                    </div>

                                                </div>
                                            </div>

                                            <?php

                                        }
                                    }

                                    ?>
                                </div>
                                <!--end default open-->

                                <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                                    <div class="col-md-3 col-xs-6 "> </div>



                                    <div class="col-md-9 no-padding" >
                                        <div class="col-md-6 text-right" style="padding-top: 5px;">
                                            <b>Total:</b>
                                        </div>
                                        <div class="col-md-2" style="padding: 0 5px !important;">
                                            <input type="text" class="tot_tot_amt form-control" value="0" style="text-align: right;" readonly="true" >
                                        </div>
                                        <div class="col-md-2" style="padding: 0 5px !important;">
                                            <input type="text" class="form-control tot_pay_amt" name="ap_dn[total_amount]" value="0" style="text-align: right;" readonly="true" >
                                        </div>
                                        <div class="col-md-2" style="padding: 0 5px !important;">
                                            <input type="text" class="form-control tot_bal_amt" value="0" style="text-align: right;" readonly="true" >
                                        </div>


                                    </div>



                                </div>

                                <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>

                                    <div class="col-md-1 col-xs-12" style="padding-top: 5px !important;">
                                        <!--label>Total</label-->
                                    </div>
                                    <div class="col-md-2 col-xs-12 " >
                                        <!--input type="text" class="total_amt form-control" name="credit_note[total_amount]" value="0" style="text-align: right;" readonly="true" -->
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
                                        <textarea rows="4" name="ap_dn[remarks]" class="form-control" cols="50"><?php echo !empty($ap_dn['remarks'])? $ap_dn['remarks'] : '';?></textarea>
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
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php 
	 $proasset = $property_assets;
	
	$this->load->view('footer'); 
	
	
	//require_once("expenses_add_script.php");
?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<!-- SELECT 2 -->
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>
    $(document).ready(function (){

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

        $('.select2').select2();

        $( "#bms_frm" ).validate({
            rules: {
                "po[po_date]": "required",
                "po[service_provider]": "required"
            },
            messages: {
                "po[po_date]": "Please select Date",
                "po[service_provider]": "Please select Service Provider"
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
    });

    $('.amt_quan, .amt_unit, .amt_disct, .amtitemtaxper').keyup(function(){
        dataid = $(this).attr('data-id');
        calc_total_amt (dataid);
    });


    function calc_main_discount() {
        var mainsub = $("#rstsubtot").val();
        var getval = parseFloat($('#maindiscount').val());
        var disamt = parseFloat(mainsub) -getval ;
        var disamt = parseFloat(disamt).toFixed(2)
        $('#mainntat').val(disamt);
        var disamt = parseFloat(disamt).toFixed(2)
        $('#maintotat').val(disamt)
    }

    function getcountofmaintot() {
        var amt_net = 0;
        $('.amt_net').each(function () {
            if($(this).val() != '') {
                amt_net += eval($(this).val());
            }
        });
         var amt_net = parseFloat(amt_net).toFixed(2)
        $('.resultsubtotal').html(amt_net);
        $('#rstsubtot').val(amt_net);

        var getdist = $('#maindiscount').val();
        if(getdist>0){
            amt_net = parseFloat(amt_net) - parseFloat(getdist);
        }
        var amt_net = parseFloat(amt_net).toFixed(2)
        $('.maintotalamount').html(amt_net);
        $('#maintotat').val(amt_net)
        var gettax = $('#taxpers').val();
        amt_nettotal = $('.mainnetamt').html()
        if(gettax>0){
            var gettaxval = parseFloat($('#taxpers').val());
            var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
            $('.taxamt').val(calctax);
            var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
            var netat = parseFloat(disnetamt).toFixed(2)
            $('.maintotalamount').html(netat);
            $('#maintotat').val(netat);
        }else {
            var netat = parseFloat(disnetamt).toFixed(2)
            $('.maintotalamount').html(netat);
            $('#maintotat').val(netat)
        }
    }


    function calc_tax_percent() {
        var mainnet = $('#mainntat').val();
        var gettaxval = parseFloat($('#taxpers').val());
        var calctax = (mainnet*gettaxval/100).toFixed(2);
        $('.taxamt').val(calctax);
        var disnetamt = parseFloat(mainnet) - parseFloat(calctax) ;
        var disnetamt = parseFloat(disnetamt).toFixed(2);
        $('#maintotat').val(disnetamt);
    }

    $('.maindiscount').keyup(function() {
        calc_main_discount();
        calc_tax_percent();
    });

    $('.taxpers').keyup(function(){
        calc_tax_percent();
    });

    function calc_total_amt (dataid) {   var quant = 0;
    var unit = 0;
    var amount = 0;
    var amt_disct = 0;
    $('.amt_quan').each(function () {
        if($(this).val() != '') {
        	quant = eval($('#subquantity_'+dataid).val());
        }
    });
    $('.amt_unit').each(function () {
        if($(this).val() != '') {
        	 unit = eval($('#unitprice_'+dataid).val());
        }
    });
    $('.amt_disct').each(function () {
        if($(this).val() != '') {
        	amt_disct = eval($('#subdistamount_'+dataid).val());
        }
    });
    var subtotal = 0;
    amount = (quant * unit - amt_disct);

    subtotal = parseFloat(amount).toFixed(2);
    $('#subamount_'+dataid).val( parseFloat(amount).toFixed(2) );
    var calctaxamt = 0;
    var subnetamt = 0;

    $('.amtitemtaxper').each(function () {
        if($(this).val() != '') {
        	var gettaxval = parseFloat($('#amtitemtaxper_'+dataid).val());
    	    calctaxamt = (amount*gettaxval/100).toFixed(2);
            console.log("calctax "+calctaxamt);
    	    $('#amtitemtaxamt_'+dataid).val(calctaxamt);
        }
    });
    amount = (amount + parseFloat(calctaxamt));
    //console.log("amount Total => "+amount);

    subnetamt = (amount);

    $('#subnetamount_'+dataid).val( parseFloat(subnetamt).toFixed(2) );

    var amt_net = 0;
    $('.amt_net').each(function () {
        if($(this).val() != '') {
        	amt_net += eval($(this).val());
        }
    });

    var amt_net = parseFloat(amt_net).toFixed(2)
	$('.resultsubtotal').html(amt_net);
	$('#rstsubtot').val(amt_net);
    var getdist = $('#maindiscount').val();
    if(getdist>0){
    	amt_net = parseFloat(amt_net) - parseFloat(getdist);
     }
    var amt_net = parseFloat(amt_net).toFixed(2)
	$('.mainnetamt').html(amt_net);
	$('#mainntat').val(amt_net);
    var gettax = $('#taxpers').val();
    amt_nettotal = $('.mainnetamt').html()
    if(gettax>0){
    	var gettaxval = parseFloat($('#taxpers').val());
    	var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
    	$('.taxamt').val(calctax);
    	var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
    	var disnetamt = parseFloat(disnetamt).toFixed(2)
    	$('.maintotalamount').html(disnetamt);
    	$('#maintotat').val(disnetamt)
     }else {
    	 var amt_net = parseFloat(amt_net).toFixed(2)
     	$('.maintotalamount').html(amt_net);
     	$('#maintotat').val(amt_net)
      }
    	calc_main_discount();
    	calc_tax_percent();
    }

    $(function () {
//Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            'minDate': new Date()
        });

    });

    if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
        $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));
    }

    $('#service_provider').change(function () {
        // showndhide();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_ap_cn_dn/getExpeseInvoiceNumber');?>',
            data: {'service_provider_id':$(this).val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.exp_inv_id+'">'+item.exp_inv_no+'</option>';
                    });
                }
                $('#exp_inv_id').html(str);
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });

    $("#exp_inv_id").change(function() {
        get_bill_items ($(this).val());

    });

</script>


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


        $('.msg_notification').fadeOut(5000);
        calc_total_amt ();

        $('.amt_cal').keyup(function(){
            calc_total_amt ();
        });

        /** Form validation */
        $( "#bms_frm" ).validate({
            rules: {
                "credit_note[property_id]": "required",
                "credit_note[block_id]": "required",
                "credit_note[unit_id]": "required"
            },
            messages: {
                "credit_note[property_id]": "Please select Property",
                "credit_note[block_id]": "Please select Block/Street",
                "credit_note[unit_id]": "Please select Unit"
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

        // On property name change
        $('#property_id').change(function () {
            //console.log($('#property_id').val());
            property_change_eve ();//$('#property_id').trigger("change");
        });


    });




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
                $('#assign_to').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already
                $("#content_area").LoadingOverlay("hide", true);
                //loadBank ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

    function get_bill_items (exp_id) {


        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_ap_cn_dn/getExpensesOrderDetails');?>',
            data: {'exp_id':exp_id,'property_id':$('#property_id').val()},
            datatype:"html", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                //console.log(data);
                if(data != '') {
                    $('.items_container').html(data);
                }

                $('.tot_amt, .pay_amt').unbind('keyup');
                $('.pay_amt').unbind('focus');
                $('.pay_amt').focus(function() {
                    if($('#paid_amount').val() == '') {
                        alert('Please Enter Paid Amount!');
                        $('#paid_amount').focus();
                        return false;
                    }
                });

                $('.tot_amt, .pay_amt').bind('keyup',function (){
                    calc_total_amt ();
                });
                calc_total_amt ();

                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }



    function load_sub_cat_period (dd_id) {
        if(typeof($('#sub_cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#sub_cat_dd_'+dd_id).find('option:selected').data('period') != '') {
            get_period($('#sub_cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);
        } else {
            $('#period_dd_'+dd_id).html('<option value="">Select</option><option value="Opening Balance">Opening Balance</option>');
        }
    }

    function calc_total_amt () {
        //console.log('called');
        var total = 0; var tot_tot_amt = 0; var tot_pay_amt = 0; var tot_bal_amt = 0;

        //var paid_amt = $('#paid_amount').val();

        $('.tot_amt').each(function () {
            if($(this).val() != '') {
                //console.log(tot_tot_amt+' '+$(this).val());
                tot_tot_amt = (parseFloat(tot_tot_amt) + parseFloat($(this).val())).toFixed(5);
                var ele_id = $(this).attr('id').substring(8);

                var minus_from = $('#ori_bal_amt_'+ele_id).length && $('#ori_bal_amt_'+ele_id).val() != '' ? 'ori_bal_amt_'+ele_id : 'tot_amt_'+ele_id;
                //console.log(ori_bal_amt);

                if ($('#pay_amt_'+ele_id).val() != '') {
                    if(parseFloat($('#'+minus_from).val()) < (parseFloat($('#pay_amt_'+ele_id).val()))) {
                        alert('You cannot Apply more than Balance Amount!');
                        $('#pay_amt_'+ele_id).val(parseFloat($('#'+minus_from).val()));
                    }
                    $('#bal_amt_'+ele_id).val((parseFloat($('#'+minus_from).val()) - (parseFloat($('#pay_amt_'+ele_id).val()))).toFixed(2));
                } else {
                    $('#bal_amt_'+ele_id).val(parseFloat($('#'+minus_from).val()));
                }
                //console.log(tot_tot_amt);
            }
        });
        //console.log(tot_tot_amt);
        tot_tot_amt = parseFloat(tot_tot_amt).toFixed(2);
        $('.tot_tot_amt').val(tot_tot_amt);

        $('.pay_amt').each(function () {
            if($(this).val() != '') {
                tot_pay_amt = (parseFloat(tot_pay_amt) + parseFloat($(this).val())).toFixed(5);
                /*console.log(parseFloat(paid_amt) + ' - '+ parseFloat(tot_pay_amt));
                 if(parseFloat(paid_amt) < parseFloat(tot_pay_amt)) {
                 alert('You cannot apply more than Paid Amount!');
                 tot_pay_amt = (parseFloat(tot_pay_amt) - parseFloat($(this).val())).toFixed(5);
                 $(this).val('');
                 }*/
                //tot_pay_amt += eval($(this).val());
            }
        });
        tot_pay_amt = parseFloat(tot_pay_amt).toFixed(2);
        $('.tot_pay_amt').val(tot_pay_amt);

        $('.bal_amt').each(function () {
            if($(this).val() != '') {
                tot_bal_amt = (parseFloat(tot_bal_amt) + parseFloat($(this).val())).toFixed(5);
                //tot_bal_amt += eval($(this).val());
            }
        });
        //console.log(tot_bal_amt);
        tot_bal_amt = parseFloat(tot_bal_amt).toFixed(2);
        $('.tot_bal_amt').val(tot_bal_amt);

        //$('#open_credit').val((parseFloat(paid_amt) - parseFloat(tot_pay_amt)).toFixed(2));

        /*$('.amt_cal').each(function () {
         if($(this).val() != '') {
         total += eval($(this).val());
         }

         });
         total = parseFloat(total).toFixed(2);
         var net_total = 0;
         var round_chk = total - parseFloat(total).toFixed(1);
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
        //$('.total_amt').val(parseFloat(total).toFixed(2));
    }

    $('#add_line_item').click (function () {
        var  id = $(this).attr('data-id');

        // alert(rdivs);
        var row = '';
        row += '<div class="col-md-12 no-padding item_'+id+'" style="padding-top: 10px !important;" >' ;
        row += '<div class="col-md-4 no-padding">';
        row += '<div class="col-md-4">' ;
        row += '<input type="hidden" name="items[credit_note_item_id][]" id="credit_note_item_id_'+id+'" value=""  />';
        row += '<select name="items[item_cat_id][]" id="cat_dd_'+id+'" class="form-control cat_dd" data-id="'+id+'" >';
        row += '<option value="">Select</option>';
        $.each(sales_items,function (i, item) {
            row += '<option value="'+item.charge_code_category_id+'" data-period="'+item.period+'">'+item.charge_code_category_name+'</option>';
        });
        row += '</select>';
        row += '</div>';
        row += '<div class="col-md-4">';
        row += '<select class="form-control sub_cat_dd" name="items[item_sub_cat_id][]" id="sub_cat_dd_'+id+'" data-id="'+id+'">';
        row += '<option value="">Select</option>';
        row += '</select>';
        row += '</div>';
        row += '<div class="col-md-4">';
        row += '<select class="form-control period_dd" name="items[item_period][]" id="period_dd_'+id+'" data-id="'+id+'">'
        row += '</select>';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-md-8 no-padding" >';
        row += '<div class="col-md-5">';
        row += '<input type="text" name="items[item_descrip][]" id="desc_txt_id_'+id+'" value="" class="form-control">';
        row += '</div>';
        row += '<div class="col-md-2">';
        row += '<input type="number" name="items[item_amount][]" id="tot_amt_'+id+'"  class="form-control tot_amt" value="" >';
        row += '</div>';
        row += '<div class="col-md-2">';
        row += '<input type="number" name="items[pay_amount][]" id="pay_amt_'+id+'"  class="form-control pay_amt" value="" >';
        row += '</div>';
        row += '<div class="col-md-2">';
        row += '<input type="number" name="items[bal_amount][]" id="bal_amt_'+id+'"  class="form-control bal_amt" value="" readonly="true">';
        row += '</div>';
        row += '<div class="col-md-1 text-center">';
        //row += '<button type="button" class="btn btn-danger btn-remove" data-id="'+id+'"><i class="fa fa-close"></i></button>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        $('.items_container').append(row);

        $(this).attr('data-id',(id+1));

        $('.cat_dd, .sub_cat_dd').unbind('change');
        $('.cat_dd').change(function () {
            load_sub_cat ($(this).val(),$(this).attr('data-id'));
        });

        //$('.sub_cat_dd').unbind('change');
        $('.sub_cat_dd').change(function () {
            show_descrip ($(this).attr('data-id'));
            load_sub_cat_period ($(this).attr('data-id'));
        });

        $('.amt_cal').unbind('keyup');
        $('.amt_cal').bind('keyup',function (){
            calc_total_amt ();
        });

    });

    $(function () {
        //Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
</script>