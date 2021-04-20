  
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


    $('.cate').change(function () {
        
        var subcate = "subcate_"+$(this).attr('data-id');
        category_changes($(this).val(),subcate);
    });


    var exp_cnt = 0;
    var sub_exp_cnt = eval("<?php echo ($sub_exp_cnt!='')?$sub_exp_cnt:0;?>");
    $('.add_sub_exp_btn').click(function () {
         
        var id = $(this).attr('id');
        var val = $(this).val();
        var parent_id =$(this).attr('data-value')
        var dataid = $(this).attr('data-id')

        var str = '<div class="col-md-12 item_div_1 sub_exp_'+id+' sub_exp_'+sub_exp_cnt+'"  style="background-color:#ECF0F5;margin: 10px 0 5px 0;padding:10px 0 !important;border: 1px solid #999;border-radius: 5px;">';
        str+= '<div class="col-md-12">';
        str += '</div>';
        str += '<div class="col-md-12 no-padding">';
        str +='<div class="col-md-6">';
        str +='<div class="form-group">';
        str +='<label >Description</label>';
        str +='<textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important;border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo isset($service_provider['job_scope']) && $service_provider['job_scope'] != '' ? $service_provider['job_scope'] : '';?></textarea>';
        str +='</div>';
        str +='</div>';
        str += '<div class="col-md-4">';
        str  +='<div class="col-md-12">';
        str  +='<div class="form-group" >';
        str  +='<label >For Asset </label>';
        str  +='<select class="form-control select2 assetlst" size="3" name="items[assetlst][]">';
        str  +='<option value="">Select</option>';
        str  +='<?php
            foreach ($property_assets as $key => $val) {
                echo "<option value=" . $val[asset_id] . ">" . $val[asset_name] . "-" . $val[asset_location] . "</option>";
            }
            ?>';
        str  +='</select>';
        str  +='</div>';
        str  +='</div>';
        str  +='</div>';
        str +='<div class="col-md-2 text-right">';
        str += '<button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="'+(sub_exp_cnt++)+'"  data-id="'+dataid+'" data-subexpid=""><i class="fa fa-close"></i></button>';
        str +='</div>';

        str += '<div class="col-md-6">';
        str += '<div class="col-md-6">';
        str += '<div class="form-group">';
        str += '<label >Account Name </label>';
        str += '<select class="form-control select2 cate" name="items[category][]" id="cate_'+dataid+'"  data-id="'+dataid+'">';
        str += '<option value="">Select</option>';
        str += '<?php
            $group_name = "";
            foreach ($expense_items as $key => $val) :
                if ($val['charge_code_group_name'] != $group_name) {
                    if ($endLabel) {
                        echo "</optgroup>";
                    }
                    // echo label...
                    echo "<optgroup label=" . $val['charge_code_group_name'] . ">";
                    $group_name = $val['charge_code_group_name'];
                    $endLabel = true;
                }
                echo "<option value=" . $val['charge_code_category_id'] . ">" . $val['charge_code_category_name'] . "</option>";
            endforeach
            ;
            ?>';
        str += '</select>';
        str += '</div>';
        str += '</div>';
      
        str += '</div>';
        str += '<div>';
        str += '<div class="col-md-12 no-padding">';
        str += '<div class="col-md-2">';
        str += '<div class="form-group">';
        str += '<label >Quantity </label>';
        str += '<input class="form-control amt_quan" value="" name="items[quantity][]"  data-id="'+dataid+'" id="subquantity_'+dataid+'"  type="number" min="0" />';
        str += '</div>';
        str += '</div>';
        str +=  '<div class="col-md-2">';
        str +=  '<div class="form-group">';
        str +=  '<label >UOM</label>';
        str +=  '<input class="form-control uom" value="" name="items[uom][]" data-id="'+dataid+'" id="uom_'+dataid+'"  type="text" />';
        str +=  '</div>';
        str +=  '</div>';
        str +=  '<div class="col-md-2">';
        str +=  '<div class="form-group">';
        str +=  '<label >Unit Price</label>';
        str +=  '<input class="form-control amt_unit" value="" data-id="'+dataid+'" id="unitprice_'+dataid+'" name="items[subunitprice][]"  type="number" />';
        str +=  '</div>';
        str +=  '</div>';
        str +=  '<div class="col-md-2">';
        str +=  '<div class="form-group">';
        str +=  '<label >Amount</label>';
        str +=  '<input readonly class="form-control amt_cal" value="0" name="items[amount][]" data-id="'+dataid+'" id="subamount_'+dataid+'" type="number" />';
        str +=  '</div>';
        str +=  '</div>';
        str +=  '<div class="col-md-2">';
        str +=  '<div class="form-group">';
        str +=  '<label >Discount Amt</label>';
        str +=  ' <input class="form-control amt_disct" value="0" name="items[distamount][]" data-id="'+dataid+'" id="subdistamount_'+dataid+'" type="number"  />';
        str +=  '</div></div>';
        str +=  '<div class="col-md-2">';
        str +=  '<div class="form-group">';
        str +=  '<label >Item Net Amount</label>';
        str +=  ' <input readonly class="form-control amt_net" value="0" name="items[netamount][]" data-id="'+dataid+'" id="subnetamount_'+dataid+'"  type="number"  />';
        str += '<input type="hidden" id="exp_item_id_'+dataid+'" name="items[exp_item_id][]" value=0>';
        str +=  '</div>';
        str +=  '</div>';
        str +=  '</div>';

        str += '</div>';
        str += '</div>';

        str += '</div>';


        $('.sub_exp_container_'+parent_id).append(str);
        $(this).attr('data-id',eval(dataid)+1);

        $('#'+id).val(val);

        $('.cate').unbind('change');
        $('.assetlst').unbind('change');
        $('.cate').change(function () {
            var subcate = "subcate_"+$(this).attr('data-id');
            category_changes($(this).val(),subcate);
        });

        $('.amt_quan, .amt_unit, .amt_disct').unbind('keyup');
        $('.amt_quan, .amt_unit, .amt_disct').bind('keyup',function (){
            dataid = $(this).attr('data-id');
            calc_total_amt (dataid);
        });

        $('.delete_sub_btn').unbind("click");
        $('.delete_sub_btn').bind ("click",function () {
            delete_sub_exp ($(this).attr('data-value'),$(this).attr("value"),$(this).attr('data-id') );
        });
        $('.select2').select2();

    });


    function delete_sub_exp (div_cls_id, sub_exp_id, data_id) {
        var poItemId = $('#exp_item_id_'+data_id).val();
        if(poItemId<=0){ poItemId = 0;}
        if(poItemId<=0) {
            $('.sub_exp_'+div_cls_id).remove();
            getcountofmaintot();
            calc_main_discount();
            calc_tax_percent();
        } else {
            if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
                $.ajax({
                    type:"post",
                    async: true,
                    url: '<?php echo base_url('index.php/bms_fin_expenses/delete_expenses_item');?>/', // Reusing the same function from task creation
                    data: {'sub_exp_id':poItemId},
                    datatype:"html", // others: xml, json; default is html
                    beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                    success: function(data) {
                        $('.item_div_'+data_id).remove();
                        $("#content_area").LoadingOverlay("hide", true);
                        getcountofmaintot();
                        calc_main_discount();
                        calc_tax_percent();
                    },
                    error: function (e) {
                        $("#content_area").LoadingOverlay("hide", true);
                        console.log(e); //alert("Something went wrong. Unable to retrive data!");
                    }
                 });
            }
        }


    }
    $('.delete_sub_btn').bind ("click",function () {
        
        delete_sub_exp ($(this).attr('data-value'),$(this).attr("data-subexpid"),$(this).attr('data-id'));
    });

    $('.amt_quan, .amt_unit, .amt_disct').keyup(function(){
        dataid = $(this).attr('data-id');
        calc_total_amt (dataid);
    });


    function calc_main_discount(){
        var mainsub = $("#rstsubtot").val();
        var getval = parseFloat($('#maindiscount').val());
        var disamt = parseFloat(mainsub) -getval ;
        var disamt = parseFloat(disamt).toFixed(2)
        $('#mainntat').val(disamt);
        var disamt = parseFloat(disamt).toFixed(2)
        $('#maintotat').val(disamt)
    }

    function getcountofmaintot(){
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

    $('.maindiscount').keyup(function(){
        calc_main_discount();
        calc_tax_percent();
    });

    $('.taxpers').keyup(function(){
        calc_tax_percent();
    });


    function calc_total_amt (dataid) {
        
        var quant = 0;
        var unit = 0;
        var amount = 0;
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
        var subtotal = 0;
        amount = (quant * unit);

        subtotal = parseFloat(amount).toFixed(2);
        $('#subamount_'+dataid).val(amount);
        var amt_disct = 0;
        var subnetamt = 0;
        $('.amt_disct').each(function () {
            if($(this).val() != '') {
                amt_disct = eval($('#subdistamount_'+dataid).val());
            }
        });
        subnetamt = (amount - amt_disct);
        $('#subnetamount_'+dataid).val(subnetamt);

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

    // Load block and assign to drop down
    function category_changes(cateid, subid) {
        
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getSubCategoryList');?>',
            data: {'category_id':cateid},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.charge_code_sub_category_id+'">'+item.charge_code_sub_category_name+'</option>';
                    });
                }
                $('#'+subid).html(str);
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }


    if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
        $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));
    }

    $('#service_provider').change(function () {
        showndhide();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getPurchaseOrderNumber');?>',
            data: {'servprov_id':$(this).val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.pur_order_id+'">'+item.po_no+'</option>';
                    });
                }
                $('#po_number').html(str);
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });

    $("#po_number").change(function(){
        var ponumber = $(this).val();
        showndhide(ponumber);
    });

    function showndhide(ponumber=''){
        if(ponumber!=''){
            $('.add_more_item ').hide();
            $('.btndisp').hide();
            $('.podetails').show();
            $('.po_available').hide();
            $('.po_available').hide();
            $('#withoutPO').hide();
            $('#remarkview').show();
            getpodetails(ponumber)
        }else {
            $('.add_more_item ').show();
            $('.btndisp').show();
            $('.podetails').hide();
            $('#withoutPO').show();
        }
    }
    function getpodetails(po_id){
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getExpensesOrderDetails');?>',
            data: {'po_id':po_id},
            datatype:"json", // others: xml, json; default is html
            // beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var strap = '<table class="table table-striped table-bordered">';
                strap +='<thead>';
                strap +='<tr>';
                strap +='<th class="center">#</th>';
                strap +='<th>Item</th>';
                strap +='<th>Description</th>';
                strap +='<th class="center">Qty</th>';
                strap +='<th class="right">UOM</th>';
                strap +='<th class="center">Unit Price</th>';
                strap +='<th class="center">Price</th>';
                strap +='<th class="center">Discount</th>';
                strap +='<th class="right">Amount</th>';
                strap +=' </tr>';
                strap +='</thead>';
                strap +='<tbody>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        var subtotal = item.subtotal;
                        strap += '<tr>';
                        strap += '<td>'+(i+1)+'</td>';
                        strap += '<td>'+item.charge_code_category_name+'</td>';
                        strap += '<td>'+item.description+'</td>';
                        strap += '<td>'+item.qty+'</td>';
                        strap += '<td>'+item.uom+'</td>';
                        strap += '<td>'+item.unit_price+'</td>';
                        strap += '<td>'+item.amount+'</td>';

                        strap += '<td>'+item.discount_amt+'</td>';
                        strap += '<td>'+item.net_amount+'</td>';
                        strap+="</tr>";
                    });
                    strap +='</tbody>';
                    strap +='</table>';
                    $('.podisplayorder').html(strap);

                    var strsub = '<table class="table table-clear">';
                    strsub +=' <tbody>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Total Amount</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].subtotal+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Discount</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].discounts+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Tax('+data[0].tax_percent+'%)</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].tax_amt+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Grand Total</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].total+'</td>';
                    strsub +=' <input type="hidden" name="grandtotal" id="grandtotal" value='+ data[0].total+'>';
                    strsub +='</tr>';

                    strsub +='<input type="hidden" name="po_select_pur_order_id" id="pur_order_id" value='+ data[0].pur_order_id+'>';
                    strsub +='<input type="hidden" name="po_select_po_no" id="po_id" value='+ data[0].po_id+'>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Paid</strong>';
                    strsub +=' </td>';
                    strsub +=' <td>'+data[0].invoice_paid_amount+'</td>';
                    strsub +='</tr>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Current Total Payable</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right"><input  class="form-control curtot" style="width:100px"  type="number" name="curtot" onchange="calcuamt();" id="curtot"></td>';
                    strsub +='</tr>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Balance</strong>';
                    strsub +=' </td>';
                    strsub +=' <input type="hidden" name="pendingamt" id="pendingamt" value='+ data[0].invoice_pending_amount+'>';
                    strsub +=' <input type="hidden" name="paidamt" id="paidamt" value='+ data[0].invoice_paid_amount+'>';
                    strsub +=' <td class="right"><span id="balance" name="balance">'+data[0].invoice_pending_amount+'</span></td>';
                    strsub +=' <input type="hidden" name="balanceamt" id="balanceamt" value="">';
                    strsub +='</tr>';
                    strsub +='</tbody>';
                    strsub +=' </table>';
                    $('.podisplaytotals').html(strsub);

                }

                // $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

    function calcuamt(){
        //alert("Calculation done");
        var grandtotal =  $("#grandtotal").val();
        var paidamt =  $("#paidamt").val();
        var pending =  $("#pendingamt").val();
        var curtotpay =  $("#curtot").val();
     
        var balanceamt = ((grandtotal-paidamt) - curtotpay);
        if(balanceamt<0){
            alert("Your payable amount should be lesser than grand total amount.");
            $('#balance').html(0.00);
        }  else {
            $('#balance').html(balanceamt.toFixed(2));
            $('#balanceamt').val(balanceamt);
        }
    }

</script>
