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
                echo '<div class="alert ' . (!empty($_SESSION['flash_msg_class']) ? $_SESSION['flash_msg_class']:' alert-success ') . ' msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                unset ( $_SESSION['flash_msg'] );
                unset ( $_SESSION['flash_msg_class'] );
            } ?>

            <?php if(isset($_SESSION['flash_msg_key_in']) && trim( $_SESSION['flash_msg_key_in'] ) != '') {
                echo '<div class="alert alert-danger msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>'.$_SESSION['flash_msg_key_in'].'</div>';
                unset ($_SESSION['flash_msg_key_in']);
            } ?>


              <div class="msg_notification_date"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>
                <span class="bill-generate-date"></span></strong>
              </div>

              <?php if ( !empty($property_setting_chk) && !empty($property_setting_chk['calcul_base']) && !empty($property_setting_chk['sinking_fund']) && !empty($property_setting_chk['property_abbrev']) && !empty($property_setting_chk['bill_due_days']) ) { ?>

              <?php } else { ?>
                  <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>
                        <span class="bill-generate-date">SC & SF values not set in Property Settings. Check Calculation based on, Sinking Fund %, Property Abbreviation and Billing Due Days</span></strong>
                  </div>
              <?php } ?>

              <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_bills/set_semi_auto_invoice_list');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">

                    <div class="col-md-3 col-xs-6">
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
                            <label>Invoice date </label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right datepicker" name="bill_generate_date" id="bill_generate_date" type="text" value="<?php echo !empty($_GET['bill_generate_date']) ? $_GET['from'] : date('d-m-Y');?>" />
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Invoice type </label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <select id="invoice_type" name="property[invoice_type]" class="form-control">
                                    <option value="scsf">SC & SF</option>
                                    <option value="fi">Fire Insurance</option>
                                    <option value="qr">Quit rent</option>
                                </select>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <label>Billing cycle </label>
                            <select id="billing_cycle" name="property[billing_cycle]" class="form-control">
                                <option value="1">Monthly</option>
                                <option value="2">Two Month Once</option>
                                <option value="3">Quarterly</option>
                                <option value="4">Four Month Once</option>
                                <option value="5">Half Yearly</option>
                                <option value="6">Yearly</option>
                            </select>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-1 generate-bill-button-hide" style="margin-top: 25px;">
                        <a href="javascript:;" role="button" class="btn btn-primary geneate_bill">Generate bill</a>
                    </div>
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
                <thead>
                <tr>
                  <th width="15%">S No</th>
                  <th width="15%">Select <input type="checkbox" id="checkAll"></th>
                  <th width="15%">Unit no</th>
                  <th>Owner name</th>
                </tr>
                </thead>
                  <tbody id="content_tbody">
                  <?php
                  if(!empty($units)) {
                      foreach ($units['units'] as $key=>$val) { ?>
                          <tr>
                              <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                              <td><input type="checkbox" id="" name="unit_id_checked[]" <?php echo (!empty($val['generate_sc_sf']) && $val['generate_sc_sf'] == 1)? 'checked="checked"':''; ?> value="<?php echo $val['unit_id'];?>"></td>
                              <input type='hidden' value='<?php echo $val['unit_id']?>' name='unit_id[]'>
                              <td><label><?php echo $val['unit_no'];?></label></td>
                              <td><label><?php echo $val['owner_name'];?></label></td>
                          </tr>
                      <?php }
                  } else { ?>
                      <tr>
                          <td class="hidden-xs text-center" colspan="3">No Record Found</td>
                          <td class="visible-xs text-center" colspan="3">No Record Found</td>
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
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/semi_auto_invoice_list');?>?property_id='+$("#property_id").val();
    });

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
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
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/semi_auto_invoice_generate_bill');?>?property_id='+$("#property_id").val()+"&bill_generate_date="+$('#bill_generate_date').val()+"&bill_generate_date="+$('#bill_generate_date').val()+'&billing_cycle='+$('#billing_cycle').val()+'&invoice_type='+$('#invoice_type').val();
        return false;
        // setViewTable();
    });

    $('.act_btn').click(function () {
        if($(this).attr('data-value') == 'pre') {
            window.location.href="<?php echo base_url('index.php/bms_fin_bills/semi_auto_invoice_list/');?>"+(eval($('#offset').val())-eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val();
            return false;
        } else if($(this).attr('data-value') == 'nxt') {
            window.location.href="<?php echo base_url('index.php/bms_fin_bills/semi_auto_invoice_list/');?>"+(eval($('#offset').val())+eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val();
            return false;
        } else {
            $('#act_type').val($(this).attr('data-value'));
            $( "#bms_frm" ).submit();
        }
    });

});

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