<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
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
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_agm_egm/set_eligible_voters');?>" method="post" enctype="multipart/form-data">
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
                          <select class="form-control" id="agm_id" name="agm_id">
                            <option value="">Select AGM/EGM</option>
                            <?php 
                                foreach ($agms as $key=>$val) { 
                                    $selected = isset($agm_id) && trim($agm_id) != '' && trim($agm_id) == $val['agm_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['agm_id']."' ".$selected." >".$val['agm_term']."</option>";
                                } ?> 
                        </select>
                    </div>
                    
                    
                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary filter"><i class="fa fa-search"></i></a>
                    </div>
                    <?php if(!empty($agm_id)) { ?>
                    <div class="col-md-3 col-xs-12">
                        <button type="button" class="btn btn-primary report_btn" ><i class="fa fa-print"></i> &nbsp;Report</button>                        
                    </div>                    
                     <?php } ?>                    
                    
                </div>
                <?php 
                    $str = '';
                    if(!empty($agm_id)) {
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
                
              </div>
              <!-- /.box-body -->
              <?php if(!empty($agm_id)) { ?>
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Eligibility</th>
                  <th>Unit No</th>
                  <th>Block/Street</th>
                  <th>Owner Name</th>
                  <th>Proxy Required</th>
                  <th>Proxy Name</th>
                  <th>Proxy IC No.</th>
                  
                </tr>
                </thead>
                <tbody id="content_tbody">
                       <?php 
                    //$offset = 0;
                    
                    if(!empty($units['units'])) {
                        
                        foreach ($units['units'] as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td style="text-align: center;" >
                                <input type="hidden" name="loaded_units[<?php echo $key;?>]" value="<?php echo $val['unit_id'];?>" />
                                <input type="checkbox" name="eligible[<?php echo $key;?>]" class="common_cls" data-id="<?php echo $key;?>" value="<?php echo $val['unit_id'];?>" <?php echo !empty($val['eligible_unit_id']) ? 'checked="checked"' : '';?> /></td>
                                <?php if(!empty($val['eli_voter_id'])) { ?>
                                <input type="hidden" name="eli_voter_id[<?php echo $key;?>]" value="<?php echo $val['eli_voter_id'];?>" />
                                <?php } ?>
                            <td><?php echo $val['unit_no'];?></td>
                            <td><?php echo $val['block_name'];?></td>
                            <td style="width: 200px;">
                                <?php echo $val['owner_name'];?>
                                <input type="hidden" name="owner_name[<?php echo $key;?>]" value="<?php echo $val['owner_name'];?>"/>
                                <input type="hidden" name="unit_no[<?php echo $key;?>]" value="<?php echo $val['unit_no'];?>"/>
                                <input type="hidden" name="ic_no[<?php echo $key;?>]" value="<?php echo $val['ic_passport_no'];?>"/>
                                <input type="hidden" name="unit_type[<?php echo $key;?>]" value="<?php echo $val['unit_type'];?>"/>
                                <input type="hidden" name="share_unit[<?php echo $key;?>]" value="<?php echo $val['share_unit'];?>"/>
                                <input type="hidden" name="no_of_owners[<?php echo $key;?>]" value="<?php echo $val['no_of_owners'];?>"/>
                            </td>
                            <td style="text-align: center;">
                                <input type="checkbox" name="proxy_req[<?php echo $key;?>]" value="1" class="proxy_chk proxy_chk_<?php echo $key;?>" data-id="<?php echo $key;?>" <?php echo !empty($val['proxy_required']) ? 'checked="checked"' : (empty($val['eli_voter_id']) ? 'disabled="disabled"' : '');?>  />
                            </td>
                            <td ><input type="text" name="proxy_name[<?php echo $key;?>]" class="form-control proxy_name_<?php echo $key;?>" <?php echo !empty($val['proxy_name']) ? 'value="'.$val['proxy_name'].'"' : (empty($val['proxy_required']) ? 'disabled="disabled"' : "");?>/></td>    
                            <td ><input type="text" name="proxy_nric[<?php echo $key;?>]" class="form-control proxy_nric_<?php echo $key;?>" <?php echo !empty($val['proxy_ic_no']) ? 'value="'.$val['proxy_ic_no'].'"' : ( empty($val['proxy_required']) ? 'disabled="disabled"' : "");?>/></td>
                            
                            
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="hidden-xs text-center" colspan="9">No Record Found</td>
                            <td class="visible-xs text-center" colspan="9">No Record Found</td>
                        </tr>                    
                    <?php } ?>                       
                </tbody>                
              </table>
            </div>
          <?php echo $str."<br />"; } ?>     
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
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(7000);
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters');?>?property_id="+$('#property_id').val();
        }           
    });
    
    $('.filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
    });
    
    $('.common_cls').click(function () {
        var id = $(this).attr('data-id');
        if($(this).prop('checked')){
            $('.proxy_chk_'+id).removeAttr('disabled');
        } else {
            $('.proxy_chk_'+id).removeAttr('checked');
            $('.proxy_chk_'+id).attr('disabled','disabled');
            $('.proxy_name_'+id).val('');
            $('.proxy_nric_'+id).val('');
            $('.proxy_name_'+id).attr('disabled','disabled');
            $('.proxy_nric_'+id).attr('disabled','disabled');            
        }                
    });
    $('.proxy_chk').click(function () {
        var id = $(this).attr('data-id');
        if($(this).prop('checked')){
            $('.proxy_name_'+id).removeAttr('disabled');
            $('.proxy_nric_'+id).removeAttr('disabled');
        } else {
            $('.proxy_name_'+id).val('');
            $('.proxy_nric_'+id).val('');
            $('.proxy_name_'+id).attr('disabled','disabled');
            $('.proxy_nric_'+id).attr('disabled','disabled');            
        }
    });
    
    jQuery('.my_publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    window.location.href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters/');?>"+((jQuery(this).val()-1)*jQuery('#rows').val())+"/"+jQuery('#rows').val()+"?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
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
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters/');?>"+(eval($('#offset').val())-eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
            return false;
        } else if($(this).attr('data-value') == 'nxt') {
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/eligible_voters/');?>"+(eval($('#offset').val())+eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
            return false;
        } else {
            $('#act_type').val($(this).attr('data-value'));
            $( "#bms_frm" ).submit();    
        }
         
    });
    
    $('.report_btn').click(function () {
        var url = '<?php echo base_url('index.php/bms_agm_egm/eligible_voters_report');?>?property_id='+$('#property_id').val()+'&agm_id='+$('#agm_id').val();
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=550,directories=no,location=no');        
    });
    
});
</script>