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
                if($_SESSION['bms']['user_type'] == 'staff') {
        ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/new_sop');?>" >New SOP</a>
        
        <?php }  
        if($_SESSION['bms']['user_type'] == 'staff' && in_array('6',$_SESSION['bms']['access_mod'])) {
        ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/entry_list');?>" style="margin-top:5px">SOP Entry</a>
        <?php } 
        if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && in_array('7',$_SESSION['bms']['access_mod']))) {
        ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/sop_history');?>" style="margin-top:5px" >SOP History</a>
        <?php }  ?> 
        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
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
                            <?php if($_SESSION['bms']['user_type'] != 'jmb') { ?>
                            <option value="">All</option>
                            <?php } ?>
                            <?php 
                                foreach ($properties as $key=>$val) { 
                                    $selected = !empty($property_id) && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
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
              <div class="box-header" style="padding-bottom: 0px;">
              <h3 class="box-title" style="font-weight: bold;">Routine Task List</h3>
            </div>
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Property</th>
                  <th>Routine Task Title</th>
                  <th class="hidden-xs">Routine Task Name(s)</th>
                  <th class="visible-xs">Routine Task Count</th>  
                  <th class="hidden-xs">Assigned Date</th>               
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
                            <td class="col-md-2">Routine Task for <?php echo $val['desi_name'];?></td>
                            <td class="hidden-xs col-md-4"><?php echo $val['sop_name'] != '' ? $val['sop_name'] : ' - ';?></td>
                            <td class="visible-xs" style="text-align: center;"><?php echo $val['sop_name'] != '' ? count(explode(',',$val['sop_name'])) : '0';?></td>                            
                            <td class="hidden-xs col-md-1"><?php echo date('d-m-Y', strtotime($val['created_date']));?></td>  
                            <td style="text-align: center;" class="col-md-1">
                                <a href="<?php echo base_url('index.php/bms_sop/view_details/'.$val['property_id'].'/'.$val['assign_to'].'/view');?>" title="View & Edit / Delete"><i class="fa fa-info-circle"></i></a> 
                                <?php /*if(in_array($_SESSION['bms']['designation_id'],$prop_doc_download_desi_id)) {  ?>
                                &ensp; &ensp;
                                <a href="<?php echo base_url('index.php/bms_sop/new_sop?property_id='.$val['property_id'].'&assign_to='.$val['assign_to'].'');?>" title="Update"><i class="fa fa-edit"></i></a>
                                <?php } */?>
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
    $('#property_id').change(function () {
        window.location.href="<?php echo base_url('index.php/bms_sop/sop_list');?>?property_id="+$('#property_id').val();
        return false;        
    });    
});

</script>