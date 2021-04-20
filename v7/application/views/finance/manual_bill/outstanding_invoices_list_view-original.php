<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px;
        }

        .select2-container .select2-selection--single {
            height: 34px;
        }
    </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" <?php echo ( isset($act) || $act == 'pdf') ? 'style="background-color: #FFF;"':''; ?>>
    <!-- Content Header (Page header) -->
    <?php if(!isset($act) || $act != 'pdf') { ?>
    <section class="content-header">

      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>

    </section>
    <?php } ?>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

    <?php if( isset($act) && $act == 'pdf') {
        echo '<div style="margin: 0 auto; text-align: center;">' . "<h4>". $PropertyInfo['jmb_mc_name'] . "</h4></div>";
        echo '<div style="margin: 0 auto; text-align: center;">' . $PropertyInfo['address_1'] . ' ' . $PropertyInfo['address_2'] . "<br>";
        echo 'Tel: ' . $PropertyInfo['phone_no'];
        echo '&nbsp;&nbsp;&nbsp;&nbsp;Email: ' . $PropertyInfo['email_addr'] . "<br /><br />
        <h3>Outstanding invoice</h3>";
        echo date("d-F-Y");
        echo "</div>";
    } ?>


        <!-- general form elements -->
          <div class="<?php if(!isset($act) || $act != 'pdf') { ?> box <?php } ?>box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
            ?>
              <?php if ( !isset($act) || $act != 'pdf') { ?>
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
                                        echo "<option value='".$val['property_id']."' ".$selected . " data-prop-abbr='".$val['property_abbrev'] . "' data-value='".$val['total_units']."'>".$val['property_name']."</option>";
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
                              foreach ($pro_units as $key=>$val) {
                                  $selected = isset($unit_id) && trim($unit_id) != '' && trim($unit_id) == $val['unit_id'] ? 'selected="selected" ' : '';
                                  echo "<option value='".$val['unit_id']."' ".$selected . " >".$val['unit_no']."</option>";
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
                              <input class="form-control pull-right datepicker" name="lpi_calc_date" id="lpi_calc_date" type="text"  value="<?php echo !empty($_GET['lpi_calc_date']) ? $_GET['lpi_calc_date'] : '';?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-4" style="padding-top: 25px;">
                          <div class="form-group">
                              <div class="input-group">
                                  <a href="javascript:;" role="button" class="btn btn-primary calc_btn">View</a>
                                  <?php if ( !empty($property_units) > 0 ) { ?>
                                      <a href="javascript:;" role="button" class="btn btn-primary download_btn">Download</a>
                                      <a href="javascript:;" role="button" class="btn btn-primary send_mail_btn">Send mail</a>
                                  <?php } ?>
                              </div>
                              <!-- /.input group -->
                          </div>
                    </div>
                  </div>
              </div>
              <?php } ?>
              <!-- /.box-body -->
          <?php if ( !empty($property_units) ) {?>
          <div class="inv_table_container">
            <div class="box-body inv_table">
                <?php if (isset($act) && $act == 'pdf') { ?>
                    <style>
                        table {
                            border-collapse: collapse;
                            width: 100%;
                        }

                        td, th {
                            padding: 7px;
                            border: 1px solid;
                        }

                        .top-row {
                            background-color: lightgrey;
                        }
                    </style>
                <?php } ?>
              <table id="example2" class="table table-bordered table-hover table-striped">
                <tbody id="content_tbody_inv">
                    <?php
                        $counter = 1;
                        if ( !empty ($property_units) ) {
                            foreach ( $property_units as $key => $val ) {
                                ?>
                                <tr style="background-color: #ffffff;">
                                    <th colspan="8" <?php if ( $counter != 1) { ?>style="padding-top: 35px !important;"<?php } ?>><?php echo $counter;?> -&nbsp;&nbsp;&nbsp;&nbsp;Unit No: <?php echo $val['unit_no'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name: <?php echo $val['owner_name'];?></th>
                                </tr>
                                <tr style="background-color: #e6e6e6;">
                                    <th>Sr#</th>
                                    <th>Date</th>
                                    <th>Due date</th>
                                    <th>Doc No</th>
                                    <th>Remarks</th>
                                    <th>Invoice amount</th>
                                    <th>Paid amount</th>
                                    <th>Balance</th>
                                </tr>
                                <?php
                                if ( !empty( $unit_os_items[$val['unit_id']] ) ) {
                                    $total = 0;
                                    $counter_item = 0;
                                    foreach ( $unit_os_items[$val['unit_id']] as $key_inv => $val_inv ) { $counter_item++; ?>
                                    <tr style="background-color: #ffffff;">
                                        <td><?php echo $counter_item;?></td>
                                        <td><?php echo $val_inv['bill_date'];?></td>
                                        <td><?php echo $val_inv['bill_due_date'];?></td>
                                        <td><?php echo $val_inv['bill_no'];?></td>
                                        <td><?php echo $val_inv['item_descrip'];?></td>
                                        <td><?php echo $val_inv['item_amount'];?></td>
                                        <td><?php echo $val_inv['paid_amount'];?></td>
                                        <td><?php echo $val_inv['bal_amount'];?></td>
                                    </tr>
                                    <?php $total += $val_inv['bal_amount'];
                                        } ?>
                                        <tr style="background-color: #e6e6e6;">
                                            <th colspan="7" style="text-align: right;">Total</th>
                                            <th><?php echo $total; ?></th>
                                        </tr>
                                    <?php } ?>
                            <?php
                                $counter++;
                            }
                        }
                    ?>
                </tbody>                
              </table>
            </div>
          </div>
          <?php } ?>



          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php
if ( !isset($act) || $act != 'pdf') {
    $this->load->view('footer');
}
?>

  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery.number.js"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>

$(document).ready(function () {

    $('.select2').select2();
    $('.msg_notification').fadeOut(5000);

    // On property name change
    $('#property_id').change(function () {
        loadUnit ('');
    });

    $('.calc_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/outstanding_invoices_list');?>?property_id='+$("#property_id").val()+'&lpi_calc_date='+$("#lpi_calc_date").val()+'&unit_id='+$("#unit_id").val();
        return false;
    });

    $('.download_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/outstanding_invoices_list');?>?property_id='+$("#property_id").val()+'&lpi_calc_date='+$("#lpi_calc_date").val()+'&unit_id='+$("#unit_id").val()+'&act=pdf';
        return false;
    });

    $('.send_mail_btn').click(function () {
        $('.send_mail_btn').attr('disabled','disabled');
        $('.pre_mail_msg_notification').attr('display', 'block');
        $('.pre_mail_msg_notification').html('<div align="center">Sending email can take time</div>');
        $('.pre_mail_msg_notification').show();
        $('.pre_mail_msg_notification').fadeOut(7000);
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/outstanding_invoices_list');?>?property_id='+$("#property_id").val()+'&lpi_calc_date='+$("#lpi_calc_date").val()+'&unit_id='+$("#unit_id").val()+'&act=pdf&sendmail=yes';
        return false;
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
            var str = '<option value="">All</option>';
            if(data.length > 0) {
                $.each(data,function (i, item) {
                    var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                    str += '<option value="'+item.unit_id+'" '+selected+'>'+item.unit_no+'</option>';
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

function formatDate(date) {  
  var date_arr = date.split('-');
  return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}

</script>