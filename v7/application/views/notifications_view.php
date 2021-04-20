<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .notification-type-div { display: inline;padding: 10px;width: 25px; }
  .notification-type-div > a:hover, .notification-type-div > a:active { background-color: transparent !important; }
  .notification-type-div > a > span { position: relative;top: -15px;left:-5px;text-align: center;font-size: 9px;padding: 4px 6px;line-height: .9; }
  .notifi_caption { display: inline; font-size: 16px; padding-right: 5px; }
  </style>  
  <!-- Content Wrapper. Contains page content -->
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
        if(isset($_GET['group']) && $_GET['group'] ==1) {
            echo "<input type='hidden' name='group' id='group' value='1' />";
        } else {
            echo "<input type='hidden' name='group' id='group' value='0' />";
        }
        
        ?>
            
              <div class="box-body">
              <div class="row">
                      <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label>Property Name</label>
                                <select class="form-control" id="property_id" name="property_id">
                                <option value="">All</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ? 'selected="selected" ' : '';  
                                        echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                    
                              <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                            </div>
                        </div>
                    </div>
                  <div class="row" style="margin: 0;">
                  
                  
                    
                    <div class="box box-solid">
                        <div class="box-header with-border">
                         
                          
                          <div class="notification-type-div" style=" padding-left: 0px;">
                            <a href="javascript:;" data-value="create" class="notify-icon-cls" title="Task Created">
                              <div class="notifi_caption">Minor Task Created</div><i class="fa fa-bell-o"></i><span class=" label label-primary" ><?php echo $create_cnt; ?></span>
                            </a>
                          </div>
                          
                          <!--div class="notification-type-div">
                            <a href="javascript:;" data-value="update" class="notify-icon-cls" title="Task Updated">
                              <div class="notifi_caption">Minor Task Updated</div><i class="fa fa-bell-o"></i><span class=" label label-warning"><?php echo $update_cnt; ?></span>
                            </a>
                          </div>
                          
                          <div class="notification-type-div">
                            <a href="javascript:;" data-value="close" class="notify-icon-cls" title="Task Closed">
                              <div class="notifi_caption">Minor Task Closed</div><i class="fa fa-bell-o"></i><span class=" label label-info"><?php echo $close_cnt; ?></span>
                            </a>
                          </div>
                          
                          <?php if($_SESSION['bms']['user_type'] == 'staff') {  ?>
                          <div class="notification-type-div">
                            <a href="javascript:;" data-value="sop" class="notify-icon-cls" title="Routine Task">
                              <div class="notifi_caption">Routine Task</div><i class="fa fa-bell-o"></i><span class=" label label-success"><?php echo $sop_cnt; ?></span>
                            </a>
                          </div-->
                          <?php } ?>   
                          
                          
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body notification_content_div">
                           
                        </div>
                        <!-- /.box-body -->
                      </div>
                      <!-- /.box -->
                      <?php /* if($_SESSION['bms']['user_type'] == 'staff') {  ?>
                      <div class="box box-solid">
                        <div class="box-header with-border">
                          <i class="fa fa-text-width"></i>
            
                          <h3 class="box-title">Routine Task</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <ol style="padding-left: 15px;">
                            
                            <?php 
                            
                            if(!empty($sop_notifications)){ 
                                
                                foreach ($sop_notifications as $key=>$val) { 
                                    //echo "<pre>";print_r($val); echo "</pre>";
                                    
                                    if($val['assign_to'] == $_SESSION['bms']['designation_id']) {
                                        echo '<li style="padding:5px;">';
                                        echo '<a href="'.base_url('index.php/bms_sop/keyin_entry/'.$val['sop_id']).'"><b>Property Name:</b>&nbsp;'.$val['property_name'].' &ensp;<b>SOP Name:</b>&nbsp;'.$val['sop_name'];
                                        echo '&ensp;<b>Execute Time:</b>&nbsp;'.date('h:i A', strtotime($val['execute_time'])).'&ensp;<b>Due By:</b>&nbsp;'.date('h:i A', strtotime($val['due_by'])).'</a>';
                                        echo '</li>'; 
                                    } 
                                                
                                }
                                foreach ($sop_notifications as $key=>$val) { 
                                    //echo "<pre>";print_r($val); echo "</pre>";                                    
                                    if($val['assign_to'] != $_SESSION['bms']['designation_id']) {  
                                        echo '<li style="padding:5px;">';
                                        echo '<b>Property Name:</b>&nbsp;'.$val['property_name'].' &ensp;<b>SOP Name:</b>&nbsp;'.$val['sop_name'];
                                        echo ' &ensp;<b>Assigned To:</b>&nbsp;'.$val['desi_name'];
                                        echo '&ensp;<b>Execute Time:</b>&nbsp;'.date('h:i A', strtotime($val['execute_time'])).'&ensp;<b>Due By:</b>&nbsp;'.date('h:i A', strtotime($val['due_by']));
                                        echo '</li>'; 
                                    }                                           
                                }
                                
                           } else {
                                echo 'No Task notification';
                           } ?>
                          </ol>
                        </div>
                        <!-- /.box-body -->
                      </div>
                      <!-- /.box -->
                      <?php } */?>                  
                    
                </div><!-- /.row -->  
                
              </div>          
            
         </div><!-- /.box -->  
             

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 
  
  <!-- bootstrap datepicker -->
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {    
    $('.msg_notification').fadeOut(5000);
    
    $('.notify-icon-cls').click(function (){
        loadNotificationCont($(this).attr('data-value'));
    });
    loadNotificationCont('<?php echo $n_type;?>');
    
    $('#property_id').change(function () {
        window.location.href="<?php echo base_url('index.php/bms_notifications/notifications_list');?>?property_id="+$('#property_id').val();
        return false;        
    }); 
    
});

function loadNotificationCont (type) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_notifications/get_notification_content');?>',
        data: {'staff_type':'<?php echo $_SESSION['bms']['user_type'];?>','staff_id':'<?php echo $_SESSION['bms']['user_type'] == 'staff' ? $_SESSION['bms']['staff_id'] : $_SESSION['bms']['member_id'];?>','n_type':type,'property_id':$('#property_id').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $(".notification_content_div").LoadingOverlay("show");  }, //
        success: function(data) {  
            var str = ''; 
            if(type== 'create') {
                str += '<h4 class="box-title" style="margin-top: 0;">Minor Task Created Notification(s)</h4>';
            } else if(type=='update') {
                str += '<h4 class="box-title" style="margin-top: 0;">Minor Task Updated Notification(s)</h4>';                
            } else if (type == 'close') {
                str += '<h4 class="box-title" style="margin-top: 0;">Minor Task Closed Notification(s)</h4>';                
            } else if (type == 'sop') {
                str += '<h4 class="box-title" style="margin-top: 0;">Routine Task Notification(s)</h4>';                
            }
            
            if(data.length > 0) {
                str += '<ol style="padding-left: 15px;">';
                if (type == 'sop') {
                    $.each(data,function (i, item) { 
                        if(item.assign_to == '<?php echo $_SESSION['bms']['designation_id'];?>') {
                            str += '<li style="padding:5px;">';
                            str += '<a href="<?php echo base_url('index.php/bms_sop/keyin_entry/');?>'+item.sop_id+'"><b>Property Name:</b>&nbsp;'+item.property_name+' &ensp;<b>SOP Name:</b>&nbsp;'+item.sop_name+' &ensp;<b>Assigned To:</b>&nbsp;'+item.desi_name;
                            str += '&ensp;<b>Execute Time:</b>&nbsp;'+item.execute_time+'&ensp;<b>Due By:</b>&nbsp;'+item.due_by+'</a>';
                            str += '</li>';
                        }           
                    });
                    
                    $.each(data,function (i, item) { 
                        if(item.assign_to != '<?php echo $_SESSION['bms']['designation_id'];?>') {
                            str += '<li style="padding:5px;">';
                            str += '<b>Property Name:</b>&nbsp;'+item.property_name+' &ensp;<b>SOP Name:</b>&nbsp;'+item.sop_name+' &ensp;<b>Assigned To:</b>&nbsp;'+item.desi_name;
                            str += '&ensp;<b>Execute Time:</b>&nbsp;'+item.execute_time+'&ensp;<b>Due By:</b>&nbsp;'+item.due_by;
                            str += '</li>';
                        }        
                    });
                } else {
                    $.each(data,function (i, item) { 
                        str += '<li style="padding:5px;"><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'"><b>Property Name:</b>&nbsp;'+item.property_name+' &ensp;<b>Task Name:</b>&nbsp;'+item.task_name+' &ensp;<b>Action:</b>&nbsp;';
                        if(item.alert_info != ''){
                            if(item.alert_info == '1') {
                                str += 'Task Created';
                            } else if(item.alert_info == '2') {
                                str += 'Task Updated';
                            } else if(item.alert_info == '3') {
                                str += 'Task Forum Updated';
                            } else if(item.alert_info == '4') {
                                str += 'Task Closed';
                            }
                        }
                        str += '&ensp;<b>Date & Time:</b>&nbsp;'+formatDate(item.created_date)+'</a></li>';
                                    
                    });
                }
                    
                str += '</ol >';
            } else {
                str += 'No notification';
            }
            //console.log(data.length);
            $(".notification_content_div").LoadingOverlay("hide", true);
            $(".notification_content_div").html(str);
        },
        error: function (e) {
            $(".notification_content_div").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
    
}


function formatDate(in_date) {
  var in_date_arr = in_date.split(' ');
  var in_date_arr2 = in_date_arr[0].split('-');
  var in_date_arr3 = in_date_arr[1].split(':');
  var am_pm = in_date_arr3[0] >= 12 ? 'pm' : 'am';
  var hours = in_date_arr3[0] > 12 ? in_date_arr3[0] - 12 : in_date_arr3[0];
  return in_date_arr2[2] + "-" + in_date_arr2[1] + "-" + in_date_arr2[0] + " " + hours + ":" + in_date_arr3[1] + " " +am_pm;
  
  /*var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  var mont = date.getMonth()+1;
  return  date.getDate() + "-" + mont + "-" + date.getFullYear() + "  " + strTime;*/
}

$(function () {
//Date picker
    $('#datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true   
    });
    
});
</script>