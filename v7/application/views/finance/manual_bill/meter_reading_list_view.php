<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 25px;
    }

    .select2-container .select2-selection--single {
        height: 34px;
    }

    .generate-bill-button-hide {
        display: none;
    }

    .generate-bill-button-show {
        display: block;
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
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                unset ($_SESSION['flash_msg']);
            } ?>

            <?php if(isset($_SESSION['flash_msg_key_in']) && trim( $_SESSION['flash_msg_key_in'] ) != '') {
                echo '<div class="alert alert-danger msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>'.$_SESSION['flash_msg_key_in'].'</div>';
                unset ($_SESSION['flash_msg_key_in']);
            } ?>


              <div class="msg_notification_date"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>
                <span class="bill-generate-date"></span></strong>
              </div>

              <?php if ( !empty($property_setting_chk) && !empty($property_setting_chk['water']) && !empty($property_setting_chk['water_min_charg']) && !empty($property_setting_chk['water_charge_per_unit_rate_1']) && $property_setting_chk['water_charge_per_unit_rate_1'] > 0 ) { ?>

              <?php } else { ?>
                  <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>
                    <span class="bill-generate-date">Meter reading values not set in property settings.</span></strong>
                  </div>
              <?php } ?>

              <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_bills/set_meter_reading');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">

                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Property </label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php
                                    foreach ($properties as $key=>$val) {
                                        $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                    } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Unit </label>
                            <select name="receipt[unit_id]" class="form-control select2" id="unit_id">
                                <option value="">All</option>
                                  <?php
                                  foreach ($pro_units as $key=>$val) {
                                      $selected = isset($unit_id) && trim($unit_id) != '' && trim($unit_id) == $val['unit_id'] ? 'selected="selected" ' : '';
                                      echo "<option value='".$val['unit_id']."' ".$selected . " >".$val['unit_no']."</option>";
                                  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <label>Reading month </label>
                            <select name="reading_mon_year" class="form-control" id="reading_mon_year">
                                <?php echo $reading_mon_year; ?>
                            </select>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-2" style="margin-top: 25px;">
                      <div class="form-group">
                          <a href="javascript:;" role="button" class="btn btn-primary filter_btn"><i class="fa fa-search"></i></a>
                      </div>
                    </div>
                    <div class="col-md-2 col-xs-4 generate-bill-button-hide">
                        <div class="form-group">
                            <label>Bill date </label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right datepicker" name="bill_generate_date" id="bill_generate_date" type="text" value="<?php echo !empty($_GET['bill_generate_date']) ? $_GET['bill_generate_date'] : '';?>" />
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-1 generate-bill-button-hide" style="margin-top: 25px;">
                        <a href="javascript:;" role="button" class="btn btn-primary geneate_bill">Generate bill</a>
                    </div>
                    <?php if ( !empty ( $_GET['reading_mon_year'] ) && empty ( $check_invoice_already_generated ) ) { ?>
                        <div class="col-md-3 col-xs-1" style="margin-top: 25px;">
                            <a href="javascript:;" role="button" class="btn btn-primary generate_pdf">Print Invoices</a>
                            <a href="javascript:;" style="visibility: hidden;" role="button" class="btn btn-primary send_email">Send mail</a>
                        </div>
                    <?php } ?>
                    <?php if ( !empty ( $_GET['reading_mon_year'] ) && empty ( $check_invoice_already_generated ) ) { ?>
                        <div class="col-md-2 col-xs-1" style="margin-top: 25px;">

                        </div>
                    <?php } ?>
                  </div>
                  <div class="row">

                  </div>
              </div>
              <!-- /.box-body -->

          <div class="inv_table_container">
              <?php
              $str = '';
              if(!empty($units['units'])) {
                  $str = '<div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12 text-right">';

                  if($offset > 0 ) {
                      $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="pre">&laquo; Pre</a> &ensp;
                            <a href="javascript:;" class="act_btn btn btn-primary" data-value="save_pre">&laquo; Save &amp; Pre</a> &ensp;';
                  }
                  $str .= '<span>Page <input class="my_publi_paging" size="2" pattern="[0-9]*" value="'.(($offset/$rows)+1).'" type="text"> of '.ceil($units['num_rows']/$rows).'</span> &ensp;
                            <a href="javascript:;" class="act_btn btn btn-primary" data-value="save"> Save </a> &ensp;';
                  if($units['num_rows'] > ($offset+$rows)) {
                      $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="save_nxt"> Save &amp; Next &raquo; </a> &ensp;
                                <a href="javascript:;" class="act_btn btn btn-primary" data-value="nxt"> Next &raquo; </a>';
                  }
                  $str .= '</div>
                            </div>';

                  echo $str.'<input id="tot_pages" value="'.ceil($units['num_rows']/$rows).'" type="hidden">
                            <input id="offset" name="offset" value="'.$offset.'" type="hidden">
                            <input id="rows" name="rows" value="'.$rows.'" type="hidden">
                            <input id="act_type" name="act_type" value="" type="hidden">';
              }
              ?>
            <div class="box-body inv_table">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <input type="hidden" id="water_min_charg" name="water_min_charg" value="<?php echo $property_detail->water_min_charg; ?>">
                <input type="hidden" id="water_charge_per_unit_rate_1" name="water_charge_per_unit_rate_1" value="<?php echo $property_detail->water_charge_per_unit_rate_1; ?>">
                <input type="hidden" id="water_charge_per_unit_rate_2" name="water_charge_per_unit_rate_2" value="<?php echo $property_detail->water_charge_per_unit_rate_2; ?>">
                <input type="hidden" id="water_charge_range" name="water_charge_range" value="<?php echo $property_detail->water_charge_range; ?>">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Unit No</th>
                  <th>Name</th>
                  <th>Previous Reading</th>
                  <th>Current Reading</th>
                  <th>Amount</th>
                  <th>Exclude from invoicing</th>
                </tr>
                </thead>
                  <tbody id="content_tbody">
                  <?php
                  if(!empty($units)) {
                      foreach ($units['units'] as $key=>$val) { ?>
                          <tr>
                              <td class="hidden-xs"><?php echo ($offset+$key+1);?><input type="hidden" name="meter_reading[unit_id][]" value="<?php echo $val['unit_id'];?>"><input type="hidden" name="meter_reading[meter_reading_id][]" value="<?php echo $val['meter_reading_id'];?>"></td>
                              <td><?php echo $val['unit_no'];?></td>
                              <td><?php echo $val['owner_name'];?></td>
                              <td><?php if ( $total_meter_readings >= 2 ) {
                                  echo $val['previous_reading']; ?>
                                  <input type="hidden" name="meter_reading[previous_reading][]" class="form-control previous_reading" value="<?php echo (!empty($val['previous_reading']) && $val['previous_reading'] > 0 )?$val['previous_reading']:''; ?>" />
                              <?php } else {
                                  if ( !empty ($check_invoice_already_generated) ) { ?>
                                      <input type="text" name="meter_reading[previous_reading][]" class="form-control previous_reading" value="<?php echo (!empty( $val['previous_reading'] ) && $val['previous_reading'] > 0 )?$val['previous_reading']:''; ?>" />
                                  <?php } else {
                                      echo $val['previous_reading'];
                                      ?>
                                      <input type="hidden" name="meter_reading[previous_reading][]" class="form-control previous_reading" value="<?php echo (!empty($val['previous_reading']) && $val['previous_reading'] > 0 )?$val['previous_reading']:''; ?>" />
                                  <?php } ?>
                              <?php } ?>
                              </td>
                              <td>
                                  <?php
                                  if ($val['bill_generated'] == 1 || !empty($val['exclude_to_inv']) && $val['exclude_to_inv'] == 1 ) {
                                      echo $val['reading'];
                                  } else { ?>
                                        <input type="text" name="meter_reading[reading][]" class="form-control current_reading" value="<?php echo (!empty($val['reading']) && $val['reading'] > 0)?$val['reading']:''; ?>" />
                                  <?php }
                                  ?>
                              </td>
                              <td><label class="amount_label"><?php echo ( !empty($val['amount']) && $val['amount'] > 0 )?$val['amount']:''; ?></label><input type="hidden" id="amount" name="meter_reading[amount][]" class="amount" value="<?php echo ( $val['amount'] != '' )?$val['amount']:''; ?>"></td>
                              <td><input type="checkbox" id="exclude_to_inv" name="meter_reading[exclude_to_inv][]" class="exclude_to_inv" value="<?php echo !empty( $val['meter_reading_id'] )? $val['meter_reading_id']:''; ?>" <?php echo !empty($val['exclude_to_inv']) && $val['exclude_to_inv'] == 1 ?'checked="checked"':''; ?> <?php echo empty($check_invoice_already_generated)? 'disabled="disabled"':'';?> ></td>
                          </tr>
                      <?php }
                  } else { ?>
                      <tr>
                          <td class="hidden-xs text-center" colspan="9">No Record Found</td>
                          <td class="visible-xs text-center" colspan="9">No Record Found</td>
                      </tr>
                  <?php } ?>
                  </tbody>
                </tbody>
              </table>
            </div>
              <?php if(!empty($units['units'])) { echo $str."<br />";} ?>
              </form>
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php $this->load->view('footer');?>
  <!-- loadingoverlay JS -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.number.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>

$(document).ready(function () {

    <?php if ( empty($check_units_not_keyed_in) && !empty($check_invoice_already_generated) ) { ?>
    $('.generate-bill-button-hide').removeClass('generate-bill-button-hide').addClass('generate-bill-button-show');
    <?php } ?>

    $('.select2').select2();

    $('.msg_notification').fadeOut(5000);
    $('.msg_notification_date').hide();

    // On property name change
    $('#property_id').change(function () {
        // loadUnit ('');
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/meter_reading_list');?>?property_id='+$("#property_id").val();
    });

    $('.filter_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/meter_reading_list');?>?property_id='+$("#property_id").val()+'&unit_id='+$('#unit_id').val()+'&reading_mon_year='+$('#reading_mon_year').val();
        return false;
        // setViewTable();
    });

    $('.geneate_bill').click(function () {
        if ( $('#bill_generate_date').val() == '' ) {
            $('.msg_notification_date').show();
            $('.msg_notification_date').addClass('alert');
            $('.msg_notification_date').addClass('alert-danger');
            $('.bill-generate-date').html('Please input bill generate date');
            $('.msg_notification_date').fadeOut(5000);
            return;
        }
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/meter_reading_generate_bill');?>?property_id='+$("#property_id").val()+'&unit_id='+$('#unit_id').val()+"&reading_mon_year="+$('#reading_mon_year').val()+"&bill_generate_date="+$('#bill_generate_date').val();
        return false;
        // setViewTable();
    });

    $('.generate_pdf').click(function () {
        var url = '<?php echo base_url('index.php/bms_fin_bills/meter_reading_generate_pdf');?>?property_id='+$("#property_id").val()+'&unit_id='+$('#unit_id').val()+"&reading_mon_year="+$('#reading_mon_year').val();
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
        return false;
        // setViewTable();
    });

    $('.send_email').click(function () {
        var url = '<?php echo base_url('index.php/bms_fin_bills/meter_reading_send_mail');?>?property_id='+$("#property_id").val()+'&unit_id='+$('#unit_id').val()+"&reading_mon_year="+$('#reading_mon_year').val();
        window.location.href = url;
        // window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
        // return false;
        // setViewTable();
    });

    $('.current_reading').blur (function () {
        var previous_reading = $(this).closest('tr').find('.previous_reading').val();
        var current_reading = $(this).val();

        previous_reading = (previous_reading === '')? 0:previous_reading;
        current_reading = (current_reading === '')? 0:current_reading;

        var water_min_charg = $('#water_min_charg').val();
        var water_charge_per_unit_rate_1 = $('#water_charge_per_unit_rate_1').val();
        var water_charge_per_unit_rate_2 = 0;
        if ( $('#water_charge_per_unit_rate_2').val() ) {
            water_charge_per_unit_rate_2 = $('#water_charge_per_unit_rate_2').val();
        }

        var water_charge_range = 0;
        if ( $('#water_charge_range').val() ) {
            water_charge_range = $('#water_charge_range').val();
        }

        var unit_consumed = parseFloat(current_reading) - parseFloat(previous_reading);

        var amount = 0;
        if ( water_charge_range > 0 && unit_consumed >=  water_charge_range ) {
            var rate_2_applicable_units = parseFloat(unit_consumed) - parseFloat(water_charge_range);
            var rate2 = parseFloat(rate_2_applicable_units) * parseFloat(water_charge_per_unit_rate_2);
            var rate1 = parseFloat(water_charge_range) * parseFloat(water_charge_per_unit_rate_1);
            amount = parseFloat(rate2) + parseFloat(rate1);
        } else {
            amount = parseFloat(unit_consumed) * parseFloat(water_charge_per_unit_rate_1);
        }

        if ( amount < water_min_charg ) {
            amount = parseFloat(water_min_charg);
        }

        $(this).closest('tr').find('.amount').val(amount.toFixed(2));
        $(this).closest('tr').find('.amount_label').html(amount.toFixed(2));
    });

    $('.previous_reading').blur (function () {
        var previous_reading = $(this).val();
        var current_reading = $(this).closest('tr').find('.current_reading').val();

        previous_reading = (previous_reading === '')? 0:previous_reading;
        current_reading = (current_reading === '')? 0:current_reading;

        var water_min_charg = $('#water_min_charg').val();
        var water_charge_per_unit_rate_1 = $('#water_charge_per_unit_rate_1').val();
        var water_charge_per_unit_rate_2 = $('#water_charge_per_unit_rate_2').val();
        var water_charge_range = $('#water_charge_range').val();
        var unit_consumed = parseFloat(current_reading) - parseFloat(previous_reading);

        var amount = 0;
        if ( water_charge_range > 0 && unit_consumed >=  water_charge_range ) {
            var rate_2_applicable_units = parseFloat(unit_consumed) - parseFloat(water_charge_range);
            var rate2 = parseFloat(rate_2_applicable_units) * parseFloat(water_charge_per_unit_rate_2);
            var rate1 = parseFloat(water_charge_range) * parseFloat(water_charge_per_unit_rate_1);
            amount = parseFloat(rate2) + parseFloat(rate1);
        } else {
            amount = parseFloat(unit_consumed) * parseFloat(water_charge_per_unit_rate_1);
        }

        if ( amount < water_min_charg ) {
            amount = parseFloat(water_min_charg);
        }

        $(this).closest('tr').find('.amount').val(amount.toFixed(2));
        $(this).closest('tr').find('.amount_label').html(amount.toFixed(2));
    });


    jQuery('.my_publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    window.location.href="<?php echo base_url('index.php/bms_fin_bills/meter_reading_list/');?>"+((jQuery(this).val()-1)*jQuery('#rows').val())+"/"+jQuery('#rows').val()+"?property_id="+$('#property_id').val()+"&reading_mon_year="+$('#reading_mon_year').val()+"&unit_id="+$('#unit_id').val();
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });


    $('.act_btn').click(function () {
        if($(this).attr('data-value') == 'pre') {
            window.location.href="<?php echo base_url('index.php/bms_fin_bills/meter_reading_list/');?>"+(eval($('#offset').val())-eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&reading_mon_year="+$('#reading_mon_year').val()+"&unit_id="+$('#unit_id').val();
            return false;
        } else if($(this).attr('data-value') == 'nxt') {
            window.location.href="<?php echo base_url('index.php/bms_fin_bills/meter_reading_list/');?>"+(eval($('#offset').val())+eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&reading_mon_year="+$('#reading_mon_year').val()+"&unit_id="+$('#unit_id').val();
            return false;
        } else {
            $('#act_type').val($(this).attr('data-value'));
            $( "#bms_frm" ).submit();
        }
    });

    $( "#bms_frm" ).validate({
        rules: {
            "reading_mon_year": "required",
        },
        messages: {
            "reading_mon_year": "Please select Reading Month"
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

function loadUnit (unit_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/get_unit');?>',
        data: {'property_id':$('#property_id').val(), 'reading_mon_year':$('#reading_mon_year').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {
            var str = '<option value="">All</option>';
            if (data.units.length > 0) {
                $.each(data.units,function (i, item) {
                    var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                    str += '<option value="'+item.unit_id+'" '+selected+'>'+item.unit_no+'</option>';
                });
            }
            $('#unit_id').html(str);

            if (data.reading_mon_year.length > 0) {
                $('#reading_mon_year').html(data.reading_mon_year);
            }

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

function formatDate(date) {
  var date_arr = date.split('-');
  return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}


</script>