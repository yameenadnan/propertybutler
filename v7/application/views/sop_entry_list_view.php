<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; 
                
        ?>
        <!-- &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/sop_list');?>" >SOP List</a-->
        
        <?php
                if($_SESSION['bms']['user_type'] == 'staff') {
        ?>
        <!-- &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/new_sop');?>" >New SOP</a-->
        <?php } // user type staff ?> 
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array('7',$_SESSION['bms']['access_mod'])) {
        ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/sop_history');?>" >Routine Task History</a>
        <?php } ?>
        
        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

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
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
              <div class="box-body" style="padding-bottom: 0px;">
                  <div class="row">
                    <div class="col-md-6 col-xs-10">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Property Name</label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                        echo "<option value='".$val['property_id']."' ".$selected." >".$val['property_name']."</option>";
                                    } ?> 
                            </select>
                        </div>
                    </div>  
                    <!--div class="col-md-2 col-xs-2" style="padding:0;margin-top:25px">
                        <a href="javascript:;" role="button" class="btn btn-primary task_filter"><i class="fa fa-search"></i></a>
                    </div-->                                
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              <div style="clear: both;height: 0px;"></div>
              
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Property</th>
                  <th class="hidden-xs">Routine Task Title</th>
                  <th >Routine Task Name</th>                    
                           
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>
                
                <?php 
                    $offset = 0;
                    if(!empty($sop_main)) {
                        $prop_doc_download_desi_id = $this->config->item('prop_doc_download_desi_id');
                        foreach ($sop_main as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs" style="text-align: center;width:5%"><?php echo ++$offset;?></td>
                            <td class="col-md-2"><?php echo $val['property_name'];?></td>
                            <td class="col-md-3 hidden-xs">SOP for <?php echo $val['desi_name'];?></td>
                            <td class="col-md-3"><?php echo $val['sop_name'] != '' ? $val['sop_name'] : ' - ';?></td>
                                                        
                            
                            <td style="text-align: center;" class="col-md-1">
                                 
                                <?php 
                                $today_date = new DateTime(date('Y-m-d'));
                                $start_date = new DateTime($val['start_date']); 
                                $due_date = isset($val['due_date']) && $val['due_date'] != '' && $val['due_date'] != '0000-00-00' && $val['due_date'] != '1970-01-01' ? new DateTime($val['due_date']) : '';
                                
                                
                                /*if(isset($val[strtolower(date('D'))]) && $val[strtolower(date('D'))] == 1 && empty($sop_entry[$val['sop_id']]) && $today_date >= $start_date && ((isset($val['no_due_date']) && $val['no_due_date'] == 1) || ($due_date != '' && $today_date <= $due_date)) ) {  ?>
                                
                                <a href="<?php echo base_url('index.php/bms_sop/keyin_entry/'.$val['sop_id']);?>" title="Update"><i class="fa fa-edit"></i></a>
                                <?php } else { ?>
                                <a href="javascript:;" title="No Action"><i class="fa fa-ban"></i></a>
                                <?php }*/ ?>
                                <a href="<?php echo base_url('index.php/bms_sop/keyin_entry/'.$val['sop_id']);?>" title="Update"><i class="fa fa-edit"></i></a>
                            </td>
                        
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="hidden-xs text-center" colspan="7">No Record Found</td>
                            <td class="visible-xs text-center" colspan="4">No Record Found</td>
                        </tr>                    
                    <?php } ?> 
                </tbody>
                <!--tfoot>
                <tr>
                  <th>Rendering engine</th>
                  <th>Browser</th>
                  <th>Platform(s)</th>
                  <th>Engine version</th>
                  <th>CSS grade</th>
                </tr>
                </tfoot-->
              </table>
            </div>
            
            
            
              
              
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>  
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('#property_id').change(function () {
        window.location.href="<?php echo base_url('index.php/bms_sop/entry_list');?>?property_id="+$('#property_id').val();
        return false;        
    });    
});

</script>