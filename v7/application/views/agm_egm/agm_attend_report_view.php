<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
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
            <form role="form" id="bms_frm" action="" method="post" enctype="multipart/form-data">
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
                    
                    <div class="col-md-3 col-xs-12">
                        <button type="button" class="btn btn-primary capture_btn" >Capture</button>
                        &ensp; &ensp;
                        <?php 
                            if(!empty($_GET['agm_id'])) {
                                echo '<button type="button" class="btn btn-primary report_btn" ><i class="fa fa-print"></i> &nbsp;Report</button>';
                            }
                       ?>               
                    </div>
                    
                                    
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              <?php if(!empty($agm_id)) { ?>
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>                   
                  <th>Unit(s)</th>
                  <th>User Name</th>
                  <th>Mobile No</th>
                  <th>Image</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody id="content_tbody">
                       <?php 
                    $offset = 0;
                    
                    if(!empty($units)) {
                        
                        foreach ($units as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td ><?php echo !empty($val['unit_nos']) ? str_replace(',','<br />',$val['unit_nos']) : '-';?></td>                                
                            <td ><?php echo !empty($val['user_name']) ? $val['user_name'] : '-';?></td> 
                            <td ><?php echo !empty($val['mobile_no']) ? $val['mobile_no'] : '-';?></td> 
                            <td ><?php echo !empty($val['image_name']) && !empty($val['created_date']) ? '<img class="img-responsive center-block img_view" style="max-height: 100px; max-width: 100px;cursor: pointer;" src="'.base_url().'bms_uploads/agm_attendance/'.date('Y',strtotime($val['created_date'])).'/'.date('m',strtotime($val['created_date'])).'/'.$val['created_date'].'/'.$val['agm_id'].'/'.$val['image_name'].'" />' : '';?></td>
                            <td style="text-align: center;vertical-align: middle;" title="Print"><a href="javascript:;" class="print_btn" data-value="<?php echo !empty($val['user_name']) ? $val['user_name'] : '';?>" data-unit="<?php echo !empty($val['unit_nos']) ? $val['unit_nos'] : '';?>" ><i class="fa fa-print"></i></a></td>
                            
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
          <?php } ?>     
          </form>  
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<!-- Modal -->
<div id="fullImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Attendance Capture</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 full_img">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> 
  
<?php $this->load->view('footer');?>
  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(7000);
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report');?>?property_id="+$('#property_id').val();
        }           
    });
    
    $('.filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
    });
    $('.capture_btn').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val()
    });
    
    
    
    $('.img_view').bind("click",function () {
        //console.log($(this).attr('data-date'));
        $('.full_img').html('<img src="'+$(this).attr('src')+'" class="img-responsive center-block"/>');
        $('#fullImgModal').modal({show:true});       
    });
    
    $('.re_send_sms').click(function () {
        var username = $(this).attr('data-value');
        if(username != '') {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_agm_egm/reSendSms');?>',
                data: {'username':username},
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) {                   
                    $("#content_area").LoadingOverlay("hide", true);
                    if(data)
                        alert('SMS sent successfully!');                
                    else 
                        alert('SMS sending failed!');
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);              
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
        
    });
    
    
    $('.print_btn').click(function () {
        var url = '<?php echo base_url('index.php/bms_agm_egm/agm_username_print');?>?user_name='+$(this).attr('data-value')+'&units='+$(this).attr('data-unit');
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=550,directories=no,location=no');        
    });
    
    $('.report_btn').click(function () {
        var url = '<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report/print');?>?property_id='+$('#property_id').val()+'&agm_id='+$('#agm_id').val();
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=550,directories=no,location=no');        
    });
    
});
</script>