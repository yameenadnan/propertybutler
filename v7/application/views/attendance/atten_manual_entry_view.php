<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
            <!--small>Optional description</small-->
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
            
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_attendance/manual_attendance_submit');?>" method="post" >
                  
                <div class="box-body">                    
                  
                    <div class="row">
                      
                    
                        <div class="col-md-4 col-xs-6">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Property Name</label>
                                <select name="property_id" id="property_id" class="form-control">                             
                                <option value="all">All</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {
                                        $selected = isset($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                                            
                        <div class="col-md-4 col-xs-6">
                            <div class="form-group">
                              <label>Staff Name</label>
                              <select id="staff_id" name="staff_id" class="form-control">
                                <option value="">Select</option>  
                                <?php 
                                    foreach ($staffs as $key=>$val) {
                                        $selected = !empty($_GET['staff_id']) && $_GET['staff_id'] == $val['staff_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['staff_id']."' ".$selected.">".$val['first_name']. " " .$val['last_name']. "</option>";
                                    } ?>                                
                              </select>
                            </div>
                        </div> 
                    
                        <div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>Date </label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="start_date" id="start_date" type="text"  value="<?php echo !empty($_GET['date']) ?  $_GET['date'] : date('d-m-Y',strtotime ( '-1 day' , strtotime ( date('Y-M-d') )));?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                    
                        
                        <div class="col-md-1 col-xs-2" style="margin-top:25px">
                            <a href="javascript:;" role="button" class="btn btn-primary attend_filter"><i class="fa fa-search"></i></a>
                        </div>
                        
                    
                                                                                   
                    </div>  
                    
                    <div class="row report_content" style="padding-top: 10px !important;">
                    
                    
                    
                    <?php 
                    
                        if(!empty($_GET['staff_id']) && !empty($_GET['date'])) { ?>
                        
                        <div class="col-md-12 no-padding">
                            <div class="col-md-2" style="font-weight: bold;">Attendance Type</div>
                            <div class="col-md-2" style="font-weight: bold;">Time</div>
                            <div class="col-md-2" style="font-weight: bold;">Remarks</div>
                            <div class="col-md-2" style="font-weight: bold;">
                                <button type="button" name="add" id="add_new" class="btn btn-primary"  >Add New</button>
                            </div>
                        </div>
                        
                        <?php    $capture_for = $this->config->item('attendance_capture_for');
                            foreach($staff_attendance as $key=>$val) { ?>
                                <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                    <div class='col-md-2'>
                                        <select class="form-control" name="atten_det[in_out_type][]" >
                                        <option value="">Select</option>
                                        <?php foreach ($capture_for as $key2=>$val2) { 
                                                $selected = $val['in_out_type'] == $key2 ? 'selected="selected"' : '';
                                                echo "<option value='".$key2."' ".$selected.">".$val2."</option>";
                                            } ?>
                                        </select>
                                   </div>
                                   <div class='col-md-2'>
                                       <div class="bootstrap-timepicker">
                                            <div class="form-group">                                          
                                                <div class="input-group">
                                                  <input type="text" name="atten_det[atten_time][]" class="form-control timepicker" value="<?php echo !empty($val['atten_time']) ?  date('h:i A', strtotime($val['atten_time'])) : '';?>">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                  </div>
                                                </div>
                                              
                                            </div>
                                       </div>
                                   </div>  
                                   
                                   <div class='col-md-2'>
                                        <input type="text" name="atten_det[remarks][]"  class="form-control" value="<?php echo $val['remarks'];?>" placeholder="Enter Remarks" maxlength="250">
                                   </div> 
                                   <input type="hidden" name="atten_det[atten_id][]" value="<?php echo $val['atten_id'];?>"/>
                                   
                                </div> 
                                
                        <?php    } ?>
                        </div>
                        <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                            <div class="col-md-2 col-xs-6">
                            </div>
                            <div class="col-md-10 col-xs-12" >
                              <div class="col-md-6">
                                <input type="submit" value="Save"  class="btn btn-primary" style="float: right;">
                              </div>
                              <div class="col-md-6">
                                <input type="reset"  value="Reset" class="btn btn-primary" >
                              </div>
                            </div>		
                          </div>
                            
                        
                      <?php  }
                        
                        ?>
                    
                    
                                     
                </div>
              <!-- /.box-body -->
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Modal -->
<div id="attenModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Attendance Photo</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 atten_msg">
            
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
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
var capture_for = $.parseJSON('<?php echo !empty($capture_for) ? json_encode($capture_for) : json_encode(array());?>');
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    // On property name change
    $('#property_id').change(function () {
        //loadStaffs ();
        window.location.href="<?php echo base_url('index.php/bms_attendance/manual_attendance');?>?property_id="+$('#property_id').val();
        return false;
    });
    
    /*$('#staff_id').change(function () {
        //loadStaffs ();
        window.location.href="<?php echo base_url('index.php/bms_attendance/manual_attendance');?>?property_id="+$('#property_id').val();
    });*/
        
    if('<?php echo in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi')) ? '1' : '0';?>' =='1') {
        //loadStaffs ();  
    }
    
    $('.attend_filter').click(function () {
        
        if($('#property_id').val() == '') {
            alert('Please select Property Name'); $('#property_id').focus(); 
            return false;
        }
        if($('#staff_id').val() == '') {
            alert('Please select Staff Name'); $('#staff_id').focus(); 
            return false;
        }
        $('#start_date').val($('#start_date').val().replace(/^\s+|\s+$/g,""));
        if($('#start_date').val() == '') {
            alert('Please choose Date'); $('#start_date').focus(); 
            return false;
        }
        
        window.location.href="<?php echo base_url('index.php/bms_attendance/manual_attendance');?>?property_id="+$('#property_id').val()+"&staff_id="+$('#staff_id').val()+"&date="+$('#start_date').val();
        return false;
              
    });
    
    
    $('#add_new').click(function () {
        var str = '<div class="col-md-12 no-padding" style="padding-top: 10px !important;">';
        str += '<div class="col-md-2">';
        str += '<select class="form-control" name="atten_det[in_out_type][]" >';
        str += '<option value="">Select</option>';
        $.each(capture_for,function (i, item) { 
            str += '<option value="'+i+'">'+item+'</option>';
        });        
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-2">';
        str += '<div class="bootstrap-timepicker">';
        str += '<div class="form-group">  ';
        str += '<div class="input-group">';
        str += '  <input type="text" name="atten_det[atten_time][]" class="form-control timepicker" value="">';
        str += '  <div class="input-group-addon">';
        str += '<i class="fa fa-clock-o"></i>';
        str += '</div>';
        str += '</div>';
        str += '</div>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-2">';
        str += '<input type="text" name="atten_det[remarks][]"class="form-control" value="" placeholder="Enter Remarks" maxlength="250">';
        str += '</div>';
        str += '</div>';
        $('.report_content').append(str);
        
        $('.timepicker').unbind('timepicker');
        $('.timepicker').timepicker({
            showInputs: false
        });
    });
    
    
});

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    
    $('.timepicker').timepicker({
      showInputs: false
    });
})
</script>