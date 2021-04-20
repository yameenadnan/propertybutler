<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
  <!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>
    <style>
        .hide-content {
            visibility: hidden;
            display: none;
        }

        .show-content {
            visibility: visible;
            display: block;
        }
    </style>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
        <form id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_unit_setup/unit_list_save');?>">
          <div class="box box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
            ?>
              <div class="box-body">
                  <div class="row">
                  
                    <div class="col-md-4 col-xs-6">
                        <select class="form-control" id="property_id" name="property_id">
                            <option value="">Select Property</option>
                            <?php 
                                foreach ($properties as $key=>$val) { 
                                    $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                } ?> 
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-xs-5">                        
                          <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter text to search Unit">
                    </div>
                    
                    
                    <div class="col-md-4 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary unit_filter"><i class="fa fa-search"></i></a>
                        <input class="btn btn-primary" style="margin-left: 5px;" onclick="window.location.href='<?php echo base_url('index.php/bms_unit_setup/add_unit');?>'" value="Add Unit" type="button">
                        <a href="javascript:;" style="margin-left: 5px;" role="button" class="btn btn-primary unit_view">View Units</a>
                        <a href="javascript:;" style="margin-left: 3px;" role="button" class="btn btn-primary unit_download">Download Units</a>
                    </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="content-edit-units">

                  <?php
                  $str = '';
                  if(!empty($unit_list)) {
                      $str = '<div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12 text-right">';

                      if($offset > 0 ) {
                          $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="pre">&laquo; Pre</a> &ensp;
                            <a href="javascript:;" class="act_btn btn btn-primary" data-value="save_pre">&laquo; Save &amp; Pre</a> &ensp;';
                      }
                      $str .= '<span>Page <input class="my_publi_paging" size="2" pattern="[0-9]*" value="'.(($offset/$rows)+1).'" type="text"> of '.ceil($unit_list['num_rows']/$rows).'</span> &ensp;
                            <a href="javascript:;" class="act_btn btn btn-primary" data-value="save"> Save </a> &ensp;';
                      if($unit_list['num_rows'] > ($offset+$rows)) {
                          $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="save_nxt"> Save &amp; Next &raquo; </a> &ensp;
                                <a href="javascript:;" class="act_btn btn btn-primary" data-value="nxt"> Next &raquo; </a>';
                      }
                      $str .= '</div>
                            </div>';

                      echo $str.'<input id="tot_pages" value="'.ceil($unit_list['num_rows']/$rows).'" type="hidden">
                            <input id="offset" name="offset" value="'.$offset.'" type="hidden">
                            <input id="rows" name="rows" value="'.$rows.'" type="hidden">
                            <input id="act_type" name="act_type" value="" type="hidden">';
                  }
                  ?>



                  <div class="box-body">
                      <table id="example2" class="table table-bordered table-hover">
                          <tbody id="content_tbody_edit">
                          <?php if ( !empty ($unit_list['records']) ) {
                              $counter = 0;
                              foreach ( $unit_list['records'] as $key => $val ) {
                                  $counter++;
                                  ?>
                                  <tr style="background-color: #f4f4f4;">
                                      <th class="hidden-xs" style="border-top:1px solid #000000;"><?php echo $offset + $counter;?></th>
                                      <th style="border-top:1px solid #000000;">Unit No</th>
                                      <th colspan="3" style="border-top:1px solid #000000;">Name</th>
                                      <th style="border-top:1px solid #000000;">Unit status</th>
                                      <?php if ( !empty($val['calcul_base']) && $val['calcul_base'] == 1 ) { ?>
                                          <th style="border-top:1px solid #000000;">Squre Foot</th>
                                      <?php } elseif ( !empty($val['calcul_base']) && $val['calcul_base'] == 2 ) { ?>
                                          <th style="border-top:1px solid #000000;">Share Unit</th>
                                      <?php } else { ?>
                                          <th style="border-top:1px solid #000000;">N/A</th>
                                      <?php } ?>
                                      <th style="border-top:1px solid #000000;">Identity No.</th>
                                      <th style="border-top:1px solid #000000;">Date of Birth</th>
                                  </tr>
                                  <tr style="background-color: #f4f4f4; border: 1px solid #000000;">
                                      <td class="hidden-xs"></td>
                                      <td>
                                          <input style="width: 90px;" type="text" class="form-control" name="unit_list[unit_no][]" value="<?php echo $val['unit_no'];?>">
                                          <input style="width: 90px;" type="hidden" name="unit_list[unit_id][]" value="<?php echo $val['unit_id'];?>">
                                      </td>
                                      <td colspan="3"><input style="width: 500px;" class="form-control" type="text" name="unit_list[owner_name][]" value="<?php echo !empty($val['owner_name'])?$val['owner_name']:'';?>"></td>
                                      <td>
                                          <select name="unit_list[unit_status][]" class="form-control">
                                              <option value="">Select</option>
                                              <?php
                                              //$unit_status = $this->config->item('unit_status');
                                              foreach ($unit_status as $key_unit=>$val_unit) {
                                                  $selected = !empty($val['unit_status']) && trim($val['unit_status']) == $val_unit['unit_status_id'] ? 'selected="selected" ' : '';
                                                  echo "<option value='".$val_unit['unit_status_id']."' ".$selected.">".$val_unit['unit_status_name']."</option>";
                                              } ?>

                                          </select>
                                      </td>
                                      <td><input style="width: 90px;" type="text" class="form-control" name="unit_list[square_feet][]" value="<?php echo !empty($val['square_feet'])?$val['square_feet']:'';?>"></td>
                                      <td><input style="width: 140px;" type="text" class="form-control" name="unit_list[ic_passport_no][]" value="<?php echo !empty($val['ic_passport_no'])?$val['ic_passport_no']:'';?>"></td>
                                      <td><input style="width: 90px;" class="datepicker datepick form-control" type="text" name="unit_list[dob][]" value="<?php echo ($val['dob'] == '0000-00-00')?'':date('d-m-Y',strtotime($val['dob']));?>"></td>
                                  </tr>
                                  <tr style="background-color: #f4f4f4; border: 1px solid #000000;">
                                      <th></th>
                                      <th>Contact 1</th>
                                      <th>Contact 2</th>
                                      <th colspan="2">Email</th>
                                      <th>Password</th>
                                      <th>Gender</th>
                                      <th>Race</th>
                                      <th colspan="2">Religion</th>
                                  </tr>
                                  <tr style="background-color: #f4f4f4; border: 1px solid #000000;">
                                      <td></td>
                                      <td><input style="width: 120px;" type="text" class="form-control" name="unit_list[contact_1][]" value="<?php echo !empty($val['contact_1'])?$val['contact_1']:'';?>"></td>
                                      <td><input style="width: 120px;" type="text" class="form-control" name="unit_list[contact_2][]" value="<?php echo !empty($val['contact_2'])?$val['contact_2']:'';?>"></td>
                                      <td colspan="2">
                                          <input style="width: 350px;" type="text" class="form-control" name="unit_list[email_addr][]" value="<?php echo !empty($val['email_addr'])?$val['email_addr']:'';?>">
                                          <input style="width: 350px;" type="hidden" class="form-control" name="unit_list[email_addr_old][]" value="<?php echo !empty($val['email_addr'])?$val['email_addr']:'';?>">
                                      </td>
                                      <td><input style="width: 90px;" type="password" class="form-control" name="unit_list[password][]" value="<?php echo !empty($val['password'])?$val['password']:'';?>"></td>
                                      <td>
                                          <select name="unit_list[gender][]" class="form-control">
                                              <option value="">Select</option>
                                              <option value="Male" <?php echo !empty( $val['gender'] ) && $val['gender'] == "Male" ? 'selected="selected"':'';?>>Male</option>
                                              <option value="Female" <?php echo !empty( $val['gender'] ) && $val['gender'] == "Female" ? 'selected="selected"':'';?>>Female</option>
                                          </select>
                                      </td>
                                      <td>
                                          <select name="unit_list[race][]" class="form-control">
                                              <option value="">Select</option>
                                              <option value="Chinese" <?php echo !empty( $val['race'] ) && $val['race'] == "Chinese" ? 'selected="selected"':'';?>>Chinese</option>
                                              <option value="Indian" <?php echo !empty( $val['race'] ) && $val['race'] == "Indian" ? 'selected="selected"':'';?>>Indian</option>
                                              <option value="Malay" <?php echo !empty( $val['race'] ) && $val['race'] == "Malay" ? 'selected="selected"':'';?>>Malay</option>
                                              <option value="Others" <?php echo !empty( $val['race'] ) && $val['race'] == "Others" ? 'selected="selected"':'';?>>Others</option>
                                          </select>
                                      </td>
                                      <td colspan="2">
                                          <select name="unit_list[religion][]" class="form-control">
                                              <option value="">Select</option>
                                              <option value="Buddhist" <?php echo !empty( $val['religion'] ) && $val['religion'] == "Buddhist" ? 'selected="selected"':'';?>>Buddhist</option>' +
                                              <option value="Christian" <?php echo !empty( $val['religion'] ) && $val['religion'] == "Christian" ? 'selected="selected"':'';?>>Christian</option>' +
                                              <option value="Hindu" <?php echo !empty( $val['religion'] ) && $val['religion'] == "Hindu" ? 'selected="selected"':'';?>>Hindu</option>' +
                                              <option value="Islam" <?php echo !empty( $val['religion'] ) && $val['religion'] == "Islam" ? 'selected="selected"':'';?>>Islam</option>' +
                                              <option value="Others" <?php echo !empty( $val['religion'] ) && $val['religion'] == "Others" ? 'selected="selected"':'';?>>Others</option>' +
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="9" style="height: 35px; background-color: #ffffff;"></td>
                                  </tr>
                              <?php }
                          } ?>
                          </tbody>
                      </table>
                  </div>
              </div>

         </div>
          <!-- /.box -->     
        </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>

  <!-- bootstrap datepicker -->
  <script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script>

$(document).ready(function () {

   $('.msg_notification').fadeOut(5000);

   $('.sidebar-mini').addClass('sidebar-collapse');

    jQuery('.my_publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list_edit/');?>"+((jQuery(this).val()-1)*jQuery('#rows').val())+"/"+jQuery('#rows').val()+"?property_id="+$('#property_id').val();
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
            window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list_edit/');?>"+(eval($('#offset').val())-eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&unit_id="+$('#unit_id').val();
            return false;
        } else if($(this).attr('data-value') == 'nxt') {
            window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list_edit/');?>"+(eval($('#offset').val())+eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&unit_id="+$('#unit_id').val();
            return false;
        } else {
            $('#act_type').val($(this).attr('data-value'));
            $( "#bms_frm" ).submit();
        }
    });

    $('.unit_view').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list/');?>"+(eval($('#offset').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&unit_id="+$('#unit_id').val()+'&search_txt='+encodeURIComponent($('#search_txt').val());
        return false;
    });

    $('.unit_filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list_edit/');?>"+(eval($('#offset').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+'&search_txt='+encodeURIComponent($('#search_txt').val());
        return false;
    });

    $('.unit_download').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_unit_setup/unit_list_download/');?>?property_id="+$('#property_id').val()+"&property_name="+$('#property_id :selected').text()+'&search_txt='+$('#search_txt').val();
        return false;
    });

});

$(function () {
//Date picker

    $('body').on('focus',".datepick", function(){
        $(this).datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            'minDate': new Date()
        });
    });


});

</script>