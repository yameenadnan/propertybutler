<?php $this->load->view('header'); ?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet"
      href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components\select2\dist\css\select2.css">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 25px;
    }

    .select2-container .select2-selection--single {
        height: 34px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
            <?php if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
                //if($_GET['login_err'] == 'invalid')
                echo '<div class="alert ' . $_SESSION['flash_msg_class'] . ' msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
                unset($_SESSION['flash_msg']);
            }
            ?>
            <?php if (!empty($property_setting_chk) && !empty($property_setting_chk['late_payment']) && !empty($property_setting_chk['late_pay_percent']) && ($property_setting_chk['late_pay_percent'] > 0) && !empty($property_setting_chk['late_pay_grace_type'])) { ?>

            <?php } else { ?>
                <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><span
                                class="bill-generate-date">Late payment interest values not set in property settings.</span></strong>
                </div>
            <?php } ?>
            <?php if ($unapplied_amount->total_records > 0) { ?>
                <div class="alert alert-warning">
                    <span><b>Warning! </b>Some units have unapplied amount for this property. Do you want to knock off invoices of those units</span>
                </div>
            <?php } ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Property </label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php
                                foreach ($properties as $key => $val) {
                                    $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='" . $val['property_id'] . "' " . $selected . " data-prop-abbr='" . $val['property_abbrev'] . "' data-value='" . $val['total_units'] . "'>" . $val['property_name'] . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Unit </label>
                            <select class="form-control select2" id="unit_id" name="unit_id">
                                <option value="">All</option>
                                <?php
                                foreach ($pro_units as $key => $val) {
                                    $selected = isset($unit_id) && trim($unit_id) != '' && trim($unit_id) == $val['unit_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='" . $val['unit_id'] . "' " . $selected . " >" . $val['unit_no'] . "</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Date </label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right datepicker" name="lpi_calc_date"
                                       id="lpi_calc_date" type="text"
                                       value="<?php echo !empty($_GET['lpi_calc_date']) ? $_GET['lpi_calc_date'] : ''; ?>"/>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4" style="padding-top: 25px;">
                        <div class="form-group">
                            <div class="input-group">
                                <a href="javascript:;" role="button" class="btn btn-primary calc_btn">Calculate</a>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <?php if (!empty($units)) { ?>
                <div class="inv_table_container">
                    <div class="box-body inv_table">
                        <table id="example2" class="table table-bordered table-hover table-striped">
                            <tbody id="content_tbody_inv">
                            <?php
                            $counter = 1;
                            if (!empty ($units)) {
                                foreach ($units as $key => $val) {
                                    ?>
                                    <tr style="background-color: #ffffff;">
                                        <th colspan="3"><?php echo $counter; ?> -&nbsp;&nbsp;&nbsp;&nbsp;Unit
                                            No: <?php echo $units[$key]['unit_no']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name: <?php echo $units[$key]['name']; ?>
                                        </th>
                                    </tr>
                                    <tr style="background-color: #e6e6e6;">
                                        <th>Invoice No</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                    <?php
                                    if (!empty($units[$key]['invoices'])) {
                                        foreach ($units[$key]['invoices'] as $key_inv => $val_inv) { ?>
                                            <tr style="background-color: #ffffff;">
                                                <td><?php echo $val_inv[$key]['bill_no']; ?></td>
                                                <td><?php echo $val_inv[$key]['desc'] . '&nbsp;&nbsp;&nbsp; ( From: ' . $val_inv[$key]['date_from'] . ',&nbsp;&nbsp;&nbsp;&nbsp;to: ' . $val_inv[$key]['date_to'] . ' )'; ?></td>
                                                <td><?php echo $val_inv[$key]['amount']; ?></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    <?php
                                    $counter++;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="col-md-12 col-xs-4 no-padding">
                            <div class="form-group text-right" style="padding-top: 25px;">
                                <a href="javascript:;" role="button" class="btn btn-primary confirm_btn">Confirm</a>
                                <a href="javascript:;" role="button" class="btn btn-primary cancel_btn">Cancel</a>
                                <!-- /.input group -->
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                    <div class="col-md-12 col-xs-4 no-padding text-center" style="background-color: #ffffff;">
                        <b>No record found</b>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php $this->load->view('footer'); ?>
<!-- loadingoverlay JS -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.number.js"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url(); ?>bower_components\select2\dist\js\select2.full.js"></script>

<script>

    $(document).ready(function () {

        $('.select2').select2();
        $('.msg_notification').fadeOut(5000);

        // On property name change
        $('#property_id').change(function () {
            window.location.href = '<?php echo base_url('index.php/bms_fin_bills/lpi_calc_list');?>?property_id=' + $("#property_id").val() + '&lpi_calc_date=' + $("#lpi_calc_date").val() + '&unit_id=' + $("#unit_id").val();
            // loadUnit ('');
        });

        $('.calc_btn').click(function () {
            window.location.href = '<?php echo base_url('index.php/bms_fin_bills/lpi_calc_list');?>?property_id=' + $("#property_id").val() + '&lpi_calc_date=' + $("#lpi_calc_date").val() + '&unit_id=' + $("#unit_id").val();
            return false;
        });

        $('.confirm_btn').click(function () {
            if (typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
                var prop_abbr = $('#property_id').find('option:selected').data('prop-abbr');
            }
            window.location.href = '<?php echo base_url('index.php/bms_fin_bills/lpi_calc_list');?>?property_id=' + $("#property_id").val() + '&lpi_calc_date=' + $("#lpi_calc_date").val() + '&save_invoices=yes&prop_abbr=' + prop_abbr + '&unit_id=' + $("#unit_id").val();
            return false;
        });
        /*    $('.calc_btn').click(function () {
         calculate_lpi ();
         });*/

        jQuery('#records_per_page').change(function (e) {
            loadContent(0, jQuery('#records_per_page').val(), true);
            return false;
        });

        jQuery('.publi_paging').keypress(function (event) {
            jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g, ''));
            if (event.which == 13) {
                //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
                event.preventDefault();
                if (jQuery.isNumeric(jQuery(this).val())) {
                    if (eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                        loadContent((jQuery(this).val() - 1) * jQuery('#records_per_page').val(), jQuery('#records_per_page').val(), true);
                        return false;
                    } else {
                        var max_limit = eval(jQuery('#tot_pages').val());
                        alert('Please enter the page number between 1 and ' + max_limit);
                        jQuery(this).focus();
                        return false;
                    }
                } else {
                    alert('Please enter a valid page number');
                    jQuery(this).val('');
                    jQuery(this).focus();
                    return false;
                }
            }

        });

        jQuery('#records_per_page_item').change(function (e) {
            loadContentItem(0, jQuery('#records_per_page_item').val(), true);
            return false;
        });

        jQuery('.publi_paging_item').keypress(function (event) {
            jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g, ''));
            if (event.which == 13) {
                //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
                event.preventDefault();
                if (jQuery.isNumeric(jQuery(this).val())) {
                    if (eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_item').val()) {
                        loadContentItem((jQuery(this).val() - 1) * jQuery('#records_per_page_item').val(), jQuery('#records_per_page_item').val(), true);
                        return false;
                    } else {
                        var max_limit = eval(jQuery('#tot_pages_item').val());
                        alert('Please enter the page number between 1 and ' + max_limit);
                        jQuery(this).focus();
                        return false;
                    }
                } else {
                    alert('Please enter a valid page number');
                    jQuery(this).val('');
                    jQuery(this).focus();
                    return false;
                }
            }

        });
    });

    function calculate_lpi() {

        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_bills/get_lpi_calc_list');?>',
            data: {'property_id': $('#property_id').val(), 'lpi_calc_end_date': $("#lpi_calc_end_date").val()},

            beforeSend: function () {
                $("#content_tbody_inv_itm").LoadingOverlay("show");
            }, //
            success: function (data) {
                console.dir(data);

                $("#content_tbody_inv_itm").LoadingOverlay("hide", true);
                var str = '';
                var showing_to = 0;
                var numFound = data.length;

                for (var i = 0; i <= data.length; i++) {
                    console.log('Test >> ' + data.unit_id);
                }


                alert(data.unit_id.length);
                if (numFound > 0) {


                    $.each(data.records, function (i, item) {

                        // showing_to = (eval(offset)+eval(i)+1);
                        str += '<tr>';
                        str += '<td class="hidden-xs">' + showing_to + '</td>';
                        str += '<td><a href="<?php echo base_url() . 'index.php/bms_fin_bills/manual_bill_details/';?>' + item.bill_id + '">' + item.bill_no + '</a></td>';
                        str += '<td>' + item.unit_no + '</td>';
                        str += '<td>' + item.owner_name + '</td>';
                        str += '<td>' + (item.bill_date != '' ? formatDate(item.bill_date) : '') + '</td>';
                        str += '<td>' + (item.bill_due_date != '' ? formatDate(item.bill_due_date) : '') + '</td>';
                        var tot_amt = parseFloat(item.item_amount).toFixed(2);
                        if (item.cn_amt !== null) {
                            tot_amt = (parseFloat(tot_amt) - parseFloat(item.cn_amt)).toFixed(2);
                        }
                        str += '<td>' + tot_amt + '</td>';
                        str += '<td>' + (item.paid_status == '0' ? '<span style="background-color:red;padding:5px;color:#FFF;">Unpaid</span>' : '<span style="background-color:green;padding:5px;color:#FFF;">Paid</span>') + '</td>';
                        str += '<td style="text-align: center;">';
                        str += '</td>';
                        str += '</tr>';
                    });

                    /*
                     var page = (eval(offset) / eval(rows)) + 1;
                     jQuery('.publi_paging_item').val(page);
                     var total_pages = Math.ceil(numFound / rows);
                     total_pages = total_pages == 0 ? 1 : total_pages;
                     jQuery('#tot_pages_item').val(total_pages);
                     jQuery('.tot_pag_span_item').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                     jQuery('#tot_rec_span_item').html('<span id="tot_rec_item">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                     */
                    var previous_link_item = "";
                    var next_link_item = "";
                    /*if(offset > 0 ){
                     previous_link_item = "<a href='javascript:;' onclick='loadContentItem("+(eval(offset)-eval(rows))+","+rows+",true);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                     }*/

                    /*if(eval(numFound) > (eval(offset)+eval(rows))){
                     if((eval(offset)+eval(rows)) < numFound){
                     next_link_item = "<a href='javascript:;' onclick='loadContentItem("+(eval(offset)+eval(rows))+","+rows+",true);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                     } else {
                     // do nothing
                     }
                     }
                     jQuery('.previous_link_item').html(previous_link_item);
                     jQuery('.next_link_item').html(next_link_item);*/


                    $("#content_tbody_inv_itm").html(str);

                } else {
                    str = '<tr><td class="hidden-xs text-center" colspan="10">No Record Found</td>';
                    str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                    $("#content_tbody_inv_itm").html(str);
                    jQuery('.tot_pag_span_item').html('1');
                    jQuery('.next_link_item').html('');
                }

                /*var grant_tot = data.numFound.grant_tot;
                 if(data.numFound.cn_amt !== null) {
                 grant_tot = (parseFloat(grant_tot) - parseFloat(data.numFound.cn_amt)).toFixed(2);
                 }

                 $('.showing_stat').html($.number(eval(grant_tot),2));*/
                //$(this).attr('title',$('#property_id').find("option:selected").attr('data-value'));

                // This is to update the url
                var flag = '1';
                if (flag != 1) {
                    if (typeof (history.pushState) != "undefined") {
                        var update_url = '<?php // echo base_url('index.php/bms_fin_bills/manual_bill_list');?>/' + offset + '/' + rows + '?property_id=' + $('#property_id').val() + '&unit_id=' + $("#unit_id").val() + '&from=' + $("#from_date").val() + '&to=' + $("#to_date").val() + '&coa_id=' + $("#coa_id").val() + '&bill_no=' + $("#bill_no").val();
                        var obj = {
                            Title: '<?php // echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Transpacc | BMS' ;?>',
                            Url: update_url
                        };
                        history.pushState(obj, obj.Title, obj.Url);
                    } else {
                        console.log("Browser does not support HTML5.");
                    }
                }

            },
            error: function (e) {
                $("#content_tbody_inv_itm").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });

    }


    function remove_item(id) {
        //console.log(id);
        if (confirm('You cannot undo this action. Are you sure want to delete?')) {
            $.ajax({
                type: "post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_bills/unset_bill');?>',
                data: {'bill_id': id},
                datatype: "html", // others: xml, json; default is html
                beforeSend: function () {
                    $("#content_tbody_inv").LoadingOverlay("show");
                }, //
                success: function (data) {
                    loadContent(0, jQuery('#records_per_page').val(), true);
                    $("#content_tbody_inv").LoadingOverlay("hide", true);
                },
                error: function (e) {
                    $("#content_tbody_inv").LoadingOverlay("hide", true);
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }


    }

    function loadUnit(unit_id) {
        $.ajax({
            type: "post",
            async: true,
            url: '<?php echo base_url('index.php/bms_jmb_mc/get_unit');?>',
            data: {'property_id': $('#property_id').val()},
            datatype: "json", // others: xml, json; default is html

            beforeSend: function () {
                $("#content_area").LoadingOverlay("show");
            }, //
            success: function (data) {
                var str = '<option value="">All</option>';
                if (data.length > 0) {
                    $.each(data, function (i, item) {
                        var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                        str += '<option value="' + item.unit_id + '" ' + selected + '>' + item.unit_no + '</option>';
                    });
                }
                $('#unit_id').html(str);

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

    function printDiv(url) {
        window.open(url, 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');
    }

    function formatDate(date) {
        var date_arr = date.split('-');
        return date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0];
    }

</script>