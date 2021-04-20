<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
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
            
              <div class="box-body">
                <?php 
                
                foreach ($properties as $key=>$val) {                        
                    if(isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ){
                        $selected_property_name = $val['property_name'];
                    }
                }
                ?>
                
                <?php 
                
                if(!in_array($report_status, array(0,6))) {
                    echo '<div class="alert alert-warning msg_notification" style="margin-top:5px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$report_message[$report_status].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
                if(!empty($_GET['property_id']) && !empty($_GET['report_date']) && $report_status == 0) { ?>
                <div class="row report-container" style="margin: 0;">
                
                    <div class="col-xs-12 col-md-12"><span>Daily Report </span></div>
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;"><?php echo $selected_property_name;?></div>
                    <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;">Date:- <?php echo date('d-m-Y (l)' , strtotime($_GET['report_date']));?></div>
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">Management Attendance</div>
                    <?php if(!empty($mgt_staffs)) {
                        $present_cnt = 0;
                        foreach ($mgt_staffs as $key => $val) {
                            $attn_status = 'Absent';
                            if(count($val['today']) > 0) {
                                $attn_status = 'Present';
                                $present_cnt++;
                            }
                                
                            echo '<div class="col-xs-12 col-md-12" >'.($key+1).'. ' .$val['first_name']. ' '.$val['last_name'].' &ensp;['.$val['desi_name'].'] - <b>'.$attn_status.'</b></div>';
                        }
                        echo '<div class="col-xs-12 col-md-12" ><b>'. $present_cnt .' of '.count($mgt_staffs).' staff(s) are present</b></div>';
                    } ?>
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;border-bottom: 1px dashed #999;margin-top:15px;">Minor Task Created</div>
                    <?php // Tasks created
                    
                    if(!empty($tasks)) {
                       
                        foreach ($tasks as $key => $val) {
                            ?>
                            <div style='clear: both;'></div>    
                            <div class="col-xs-12 col-md-12" style="_border-bottom: 1px dashed #999;">
                        
                                <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                        <!--div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label for="property_id">Property Name: </label>
                                                <?php echo $val['property_name'];?>
                                            </div>
                                        </div-->
                                        <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label>Task Name : </label>
                                                <?php echo $val['task_name'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Location: </label>
                                                <?php echo isset($val['task_location']) ? $val['task_location'] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-xs-12" style="padding-bottom: 15px;">
                                            <div class="form-group">
                                              <label>Task Details: </label>
                                                <?php echo isset($val['task_details']) ? $val['task_details'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Catagory: </label>
                                                <?php 
                                                    $task_cat = $this->config->item('task_cat');
                                                    echo isset($val['task_category']) ? $task_cat[$val['task_category']] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Source Of Assignment: </label>
                                                <?php 
                                                    $source_assign = $this->config->item('source_assign');
                                                    echo isset($val['task_source']) ? $source_assign[$val['task_source']] : ' - ';?>
                                            </div>
                                        </div>
                                         <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Created By : </label>
                                                                       
                                                <?php 
                                                if(isset($val['created_by'])) {
                                                
                                                    if($val['created_by'] == '0'){
                                                        echo 'JMB / MC';
                                                    } else {
                                                        echo $val['first_name'] .' '.$val['last_name'];
                                                    }
                                                
                                                } else {
                                                echo ' - ';
                                                }?>
                                                    
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                              <label>Created Date :</label>
                                                <?php echo isset($val['created_date']) && $val['created_date'] != '' && $val['created_date'] != '0000-00-00' && $val['created_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['created_date'])) : ''; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                        
                        
                                <div class="col-md-6 col-xs-12" style="padding:10px 0 0 0;" >
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box1" style="border: 1px solid #999;border-radius: 5px;">
                                        <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label for="">Block/Street: </label>
                                                <?php echo isset($val['block_name']) ? $val['block_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Unit No: </label>
                                              <?php echo isset($val['unit_no']) ? $val['unit_no'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Unit Status: </label>
                                              <?php echo isset($val['unit_status']) ? $val['unit_status'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Name: </label>
                                                <?php echo isset($val['owner_name']) ? $val['owner_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Contact: </label>
                                                    <?php echo isset($val['contact_1']) ? $val['contact_1'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Email: </label>
                                                <?php echo isset($val['email_addr']) ? $val['email_addr'] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box2" style="margin-top:5px;border: 1px solid #999;border-radius: 5px;">
                                        <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label>Assign To :</label>
                                                <?php echo isset($val['desi_name']) ? $val['desi_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Due Date : </label>
                                                    <span id="due_date_span">                            
                                                      <?php echo isset($val['due_date']) ? date('d-m-Y',strtotime($val['due_date'])) : ' - ';?>
                                                    </span>
                                                <!-- /.input group -->
                                              </div>
                                        </div> 
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Status :</label>
                                                <span id="task_status_span" <?php echo isset($val['task_status']) && $val['task_status'] == 'C' ? 'class="text-success"': '';?>>
                                                    <?php 
                                                        $task_status = $this->config->item('task_db_status');
                                                        echo isset($val['task_status']) ? $task_status[$val['task_status']] : ' - ';
                                                        
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                         <!--div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Task Update : </label>
                                                <span id="task_update_span">
                                                
                                                  <?php echo isset($val['task_update']) ? $val['task_update'] : ' - ';?>
                                                </span>
                                                 
                                              </div>
                                        </div--> 
                                        
                                         
                                    </div>
                                    
                                </div>
                        
                        
                            </div> <!-- /.col-md-12 -->
                            
                            
                              <?php if(!empty($task_images[$val['task_id']])) {  ?>
                              <div style="clear: both;"></div>
                              <div class="col-md-12 col-xs-12" style="border: 1px solid #999;padding: 10px 15px;" >
                              <!-- The container for the uploaded files -->
                              
                                <div id="img_preview" class="row" style="margin: 0px;">
                                <label>Images :</label>
                                    <div class="col-md-12" style="padding: 0;min-height:150px;">                                        
                                            
                                            <?php foreach ($task_images[$val['task_id']] as $tkey=>$tval) { ?>  
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding: 0;">                                               
                                                <img class="img-responsive center-block img_view img_view_<?php echo $val['task_id'];?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../bms_uploads/task_uploads/'.$val['task_id'].'/'.$task_images[$val['task_id']][$tkey]['img_name'];?>" />
                                            </div>
                                            <?php } ?>
                                                
                                            
                                        
                                        <!--div class="col-md-3 col-xs-4" style="padding: 0;"> 
                                        <label>Images :</label>
                                            <ul style="padding-left:20px ;">
                                            <?php foreach ($task_images[$val['task_id']] as $tkey=>$tval) { ?>
                                                <li><a href="javascript:;" class="imgs_a" data-id="<?php echo $val['task_id'];?>" data-value="<?php echo $tval['img_name'];?>">image <?php echo $tkey+1;?> </a></li>
                                            <?php } ?>
                                                
                                            </ul> 
                                        </div>
                                        
                                        <div class="col-md-6 col-xs-8">   
                                            <img class="img-responsive center-block img_view img_view_<?php echo $val['task_id'];?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../bms_uploads/task_uploads/'.$val['task_id'].'/'.$task_images[$val['task_id']][0]['img_name'];?>" /> 
                                        </div-->
                                    </div>
                                
                                </div>
                              </div>
                              <?php } ?>
                              <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;height: 1px;"></div>
                    
                    <?php
                        }
                    } else { echo '<div class="col-xs-12 col-md-12" >No Task Found!</div>'; }
                    ?>
                    <div style='clear: both;'></div>
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;border-bottom: 1px dashed #999;margin-top:15px;">Minor Task Closed</div>
                    <?php // Tasks created
                    
                    if(!empty($tasks_closed)) {
                       
                        foreach ($tasks_closed as $key => $val) {
                            ?>
                            <div style='clear: both;'></div>    
                            <div class="col-xs-12 col-md-12" style="_border-bottom: 1px dashed #999;">
                        
                                <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                        <!--div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label for="property_id">Property Name: </label>
                                                <?php echo $val['property_name'];?>
                                            </div>
                                        </div-->
                                        <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label>Task Name : </label>
                                                <?php echo $val['task_name'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Location: </label>
                                                <?php echo isset($val['task_location']) ? $val['task_location'] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-xs-12" style="padding-bottom: 15px;">
                                            <div class="form-group">
                                              <label>Task Details: </label>
                                                <?php echo isset($val['task_details']) ? $val['task_details'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Catagory: </label>
                                                <?php 
                                                    $task_cat = $this->config->item('task_cat');
                                                    echo isset($val['task_category']) ? $task_cat[$val['task_category']] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Source Of Assignment: </label>
                                                <?php 
                                                    $source_assign = $this->config->item('source_assign');
                                                    echo isset($val['task_source']) ? $source_assign[$val['task_source']] : ' - ';?>
                                            </div>
                                        </div>
                                         <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Created By : </label>
                                                                       
                                                <?php 
                                                if(isset($val['created_by'])) {
                                                
                                                    if($val['created_by'] == '0'){
                                                        echo 'JMB / MC';
                                                    } else {
                                                        echo $val['first_name'] .' '.$val['last_name'];
                                                    }
                                                
                                                } else {
                                                echo ' - ';
                                                }?>
                                                    
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                              <label>Created Date :</label>
                                                <?php echo isset($val['created_date']) && $val['created_date'] != '' && $val['created_date'] != '0000-00-00' && $val['created_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['created_date'])) : ''; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                        
                        
                                <div class="col-md-6 col-xs-12" style="padding:10px 0 0 0;">
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box1" style="border: 1px solid #999;border-radius: 5px;">
                                        <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label for="">Block/Street: </label>
                                                <?php echo isset($val['block_name']) ? $val['block_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Unit No: </label>
                                              <?php echo isset($val['unit_no']) ? $val['unit_no'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Unit Status: </label>
                                              <?php echo isset($val['unit_status']) ? $val['unit_status'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Name: </label>
                                                <?php echo isset($val['owner_name']) ? $val['owner_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Contact: </label>
                                                    <?php echo isset($val['contact_1']) ? $val['contact_1'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Resident Email: </label>
                                                <?php echo isset($val['email_addr']) ? $val['email_addr'] : ' - ';?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box2" style="margin-top:5px;border: 1px solid #999;border-radius: 5px;">
                                        <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                            <div class="form-group">
                                              <label>Assign To :</label>
                                                <?php echo isset($val['desi_name']) ? $val['desi_name'] : ' - ';?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Due Date : </label>
                                                    <span id="due_date_span">                            
                                                      <?php echo isset($val['due_date']) ? date('d-m-Y',strtotime($val['due_date'])) : ' - ';?>
                                                    </span>
                                                <!-- /.input group -->
                                              </div>
                                        </div> 
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <label>Task Status :</label>
                                                <span id="task_status_span" <?php echo isset($val['task_status']) && $val['task_status'] == 'C' ? 'class="text-success"': '';?>>
                                                    <?php 
                                                        $task_status = $this->config->item('task_db_status');
                                                        echo isset($val['task_status']) ? $task_status[$val['task_status']] : ' - ';
                                                        
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                         <!--div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Task Update : </label>
                                                <span id="task_update_span">
                                                
                                                  <?php echo isset($val['task_update']) ? $val['task_update'] : ' - ';?>
                                                </span>
                                                 
                                              </div>
                                        </div-->                                      
                                         
                                    </div>
                                    
                                </div>                      
                        
                            </div> <!-- /.col-md-12 -->                           
                            
                              <?php if(!empty($task_images[$val['task_id']])) {  ?>
                              <div style="clear: both;"></div>
                              <div class="col-md-12 col-xs-12" style="border: 1px solid #999;padding: 10px 15px;" >
                              <!-- The container for the uploaded files -->
                              
                                <div id="img_preview" class="row" style="margin: 0px;">
                                <label>Images :</label>
                                    <div class="col-md-12" style="padding: 0;min-height:150px;">                                        
                                            
                                            <?php foreach ($task_images[$val['task_id']] as $tkey=>$tval) { ?>  
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding: 0;">                                               
                                                <img class="img-responsive center-block img_view img_view_<?php echo $val['task_id'];?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../bms_uploads/task_uploads/'.$val['task_id'].'/'.$task_images[$val['task_id']][$tkey]['img_name'];?>" />
                                            </div>
                                            <?php } ?>
                                                
                                            
                                        
                                        <!--div class="col-md-3 col-xs-4" style="padding: 0;"> 
                                        <label>Images :</label>
                                            <ul style="padding-left:20px ;">
                                            <?php foreach ($task_images[$val['task_id']] as $tkey=>$tval) { ?>
                                                <li><a href="javascript:;" class="imgs_a" data-id="<?php echo $val['task_id'];?>" data-value="<?php echo $tval['img_name'];?>">image <?php echo $tkey+1;?> </a></li>
                                            <?php } ?>
                                                
                                            </ul> 
                                        </div>
                                        
                                        <div class="col-md-6 col-xs-8">   
                                            <img class="img-responsive center-block img_view img_view_<?php echo $val['task_id'];?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../bms_uploads/task_uploads/'.$val['task_id'].'/'.$task_images[$val['task_id']][0]['img_name'];?>" /> 
                                        </div-->
                                    </div>
                                
                                </div>
                              </div>
                              <?php } ?>
                              
                              <?php if(!empty($task_log[$val['task_id']])) {  ?>
                              <div style="clear: both;"></div>
                              <div class="col-md-12 col-xs-12" style="border: 1px solid #999;padding: 10px 15px;" >
                              <!-- The container for the uploaded files -->
                              
                                <div id="img_preview" class="row" style="margin: 0px;">
                                <label>Task Log :</label>
                                    <div class="col-md-12" style="padding: 0;min-height:150px;">                                        
                                            
                                            <?php 
                                                foreach ($task_log[$val['task_id']] as $tkey=>$tval) { echo '<div class="row" style="margin:15px 0;">';
                                                    echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.($tval['staff_id'] == '0' ? 'JMB / MC ' : $tval['first_name']).' </span> on '.date('d-m-Y h:i:s a',strtotime($tval['entry_date'])).'</div>';
                                                    echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.($tval['img_name'] == 'task_update' ? 'Task Update: ' : 'Forum: ').' </span>'.$tval['description'].'</div>';
                                                    echo "</div>";
                                                }
                                            ?>                                        
                                    </div>
                                
                                </div>
                              </div>
                              <?php } ?>
                              
                              <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;height: 1px;"></div>
                    
                    <?php
                        }
                    } else { echo '<div class="col-xs-12 col-md-12" >No Task Found!</div>'; }
                    ?>
                    <div style='clear: both;'></div>
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;border-bottom: 1px dashed #999;margin-top:15px;">Routine Task(s)</div>
                    
                    <?php
                        
                    if(!empty($sop_entries)) {     
                        foreach($sop_entries as $key=>$val) {
                        
                    ?>                
                
                <div style='clear: both;'></div>
                <div class="row" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;"><?php echo $val['sop_name'];?></h3>
                    </div>
                
                <div class="row">  
                                    
                    <?php
                                                                       
                    $entry_str = "<div class='col-md-12 col-xs-12'>";
                    $entry_str .= "<div class='col-md-6 col-xs-6'>";
                    if(!empty($val['requirement_type'])) {
                        if($val['requirement_type'] == 'C') {
                            $entry_str .= "<div class='form-group'><label>Condition: </label> ".(!empty($val['requirement_val']) && $val['requirement_val'] == 'Y' ? 'Ok' : 'Not Ok') ."</div>";
                        } else {
                            $entry_str .= "<div class='form-group'><label>Reading: </label> ".(!empty($val['requirement_val']) ?  $val['requirement_val'] : ' - ') ."</div>";
                        }
                    } else {
                        $entry_str .= " - ";
                    }
                    
                    $entry_str .= "</div>";
                    $entry_str .= "<div class='col-md-6 col-xs-6'>";
                    $entry_str .= "<div class='form-group'><label>Remarks: </label> ".(!empty($val['remarks']) ? $val['remarks'] : ' - ') ."</div>";
                    $entry_str .= "</div>";
                    $entry_str .= "<div class='col-md-6 col-xs-6'>";
                    if(!empty($val['is_overwtrite']) && $val['is_overwtrite'] == 1) {
                        $entry_str .= "<div class='form-group'><label>Overwritten By: </label> ".$val['first_name'] . ' '. $val['last_name'] ." &ensp;<label>On&ensp;</label>".(!empty($val['entered_date']) ? date('d-m-Y h:i:s a',strtotime($val['entered_date'])) : ' - ')."</div>";
                    } else {
                        $entry_str .= "<div class='form-group'><label>Entered By: </label> ".$val['first_name'] . ' '. $val['last_name'] ." &ensp;<label>On&ensp;</label>".(!empty($val['entered_date']) ? date('d-m-Y h:i:s a',strtotime($val['entered_date'])) : ' - ')."</div>";
                    }
                    
                    $entry_str .= "</div>";
                    $entry_str .= "</div>";
                    
                        
                    
                    if($entry_str != '') {
                        echo $entry_str;
                        if(!empty($sop_entry_img[$val['sop_id']])) {
                            
                            echo "<div class='col-md-12' style='margin: 0px;padding-bottom:15px;'>";
                            echo "<label>Images :</label>";
                            echo "<div class='col-md-12'>";
                            //echo "<div class='col-md-3 col-xs-4 no-padding' > ";
                            //echo "<ul style='padding-left:20px ;'>";
                            foreach ($sop_entry_img[$val['sop_id']] as $mkey=>$mval) { 
                                //echo "<li><a href='javascript:;' class='imgs_m_a' data-target='img_view_m_".$val['sop_id']."' data-value='".$mval['sop_entry_id']."/".$mval['img_name']."'>image ".($mkey+1)." </a></li>";
                                echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6 img_content' style='padding:0 15px 0 15px;'>";   
                                echo "<img class='img-responsive center-block img_view img_view_m_".$val['sop_id']."' style='max-height: 200px; max-width: 150px;cursor: pointer;' src='../../bms_uploads/sop_entry_upload/".$sop_entry_img[$val['sop_id']][$mkey]['sop_entry_id']."/".$sop_entry_img[$val['sop_id']][$mkey]['img_name']."' />";
                                echo "</div>";
                             } 
                                
                            //echo "</ul>"; 
                            //echo "</div>";
                            
                            
                            echo "</div>";
                    
                            echo "</div>";
                        }                             
                        
                    } 
                    
                    if(!empty($sop_sub[$val['sop_id']])) {
                        foreach ($sop_sub[$val['sop_id']] as $key2=>$val2) { ?>
                        <div style='clear: both;'></div>
                        <div class="box-header with-border" style="padding:15px;">
                            <h3 class="box-title" style="font-weight: bold;"><?php echo $val2['sub_sop_name'];?></h3>
                        </div>
                        <?php
                            if(!empty($sop_sub_entry[$val2['sop_sub_id']])) {
                                    
                                //echo "Entry Found";
                                $entry_str = "<div class='col-md-12 col-xs-12'>";
                                $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                if(!empty($sop_sub_entry[$val2['sop_sub_id']]['requirement_type'])) {
                                    if($sop_sub_entry[$val2['sop_sub_id']]['requirement_type'] == 'C') {
                                        $entry_str .= "<div class='form-group'><label>Condition: </label> ".(!empty($sop_sub_entry[$val2['sop_sub_id']]['requirement_val']) && $sop_sub_entry[$val2['sop_sub_id']]['requirement_val'] == 'Y' ? 'Ok' : 'Not Ok') ."</div>";
                                    } else {
                                        $entry_str .= "<div class='form-group'><label>Reading: </label> ".(!empty($sop_sub_entry[$val2['sop_sub_id']]['requirement_val']) ?  $sop_sub_entry[$val2['sop_sub_id']]['requirement_val'] : ' - ') ."</div>";
                                    }
                                } else {
                                    $entry_str .= " - ";
                                }
                                
                                $entry_str .= "</div>";
                                $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                $entry_str .= "<div class='form-group'><label>Remarks: </label> ".(!empty($sop_sub_entry[$val2['sop_sub_id']]['remarks']) ? $sop_sub_entry[$val2['sop_sub_id']]['remarks'] : ' - ') ."</div>";
                                $entry_str .= "</div>";
                                $entry_str .= "</div>";
                                
                                // for image display purpose
                                //$thisKey = $key2;
                                $thisId = $sop_sub_entry[$val2['sop_sub_id']]['id'];
                                
                                if($entry_str != '') {
                                    echo $entry_str;
                                    
                                    if($thisId != '' && !empty($sop_sub_entry_img[$thisId])) {
                                
                                        echo "<div class='col-md-12' style='margin: 0px;padding-bottom:15px;'>";
                                        echo "<label>Images :</label>";
                                        echo "<div class='col-md-12'>";
                                        //echo "<div class='col-md-3 col-xs-4 no-padding' > ";
                                        //echo "<ul style='padding-left:20px ;'>";
                                        foreach ($sop_sub_entry_img[$thisId] as $mkey=>$mval) { 
                                            //echo "<li><a href='javascript:;' class='imgs_s_a'  data-target='img_view_s_".$thisId."' data-value='".$thisId."/".$mval['img_name']."'>image ".($mkey+1)." </a></li>";
                                            echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6 img_content' style='padding:0 15px 0 15px;'>";   
                                            echo "<img class='img-responsive center-block img_view img_view_s_".$thisId."' style='max-height: 200px; max-width: 150px;cursor: pointer;'src='../../bms_uploads/sop_sub_entry_upload/".$thisId."/".$sop_sub_entry_img[$thisId][$mkey]['img_name']."' />";
                                            echo "</div>";
                                         } 
                                            
                                        //echo "</ul>"; 
                                        //echo "</div>";
                                        
                                        //echo "<div class='col-md-6 col-xs-8 img_content' style='padding:0 5px 0 25px;'>";   
                                        //echo "<img class='img-responsive center-block img_view img_view_s_".$thisId."' style='max-height: 200px; max-width: 150px;cursor: pointer;'src='../../bms_uploads/sop_sub_entry_upload/".$thisId."/".$sop_sub_entry_img[$thisId][0]['img_name']."' />";
                                        //echo "</div>";
                                        echo "</div>";
                                
                                        echo "</div>";
                                    }
                                    
                                }                                     
                                
                            } else {
                                echo " <div class='col-md-12 col-xs-12 text-center' style='padding-bottom:15px;'> Entry Not Found!</div>";
                            }
                        }
                    }
                    
                ?>
                
                
                
                </div> <!-- /.row -->    
                    
                  
            </div>
                
                    
                <?php 
                
                    }        
                } else { echo '<div class="col-xs-12 col-md-12" >No SOP Found!</div>'; } ?>
                        
                    
                    
                    
                    <!--div class="col-xs-12 col-md-12" >Security Services &ensp; <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" >Lift Services &ensp; <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" >Cleaners Services &ensp; <i class="fa fa-times"></i></div>                    
                    <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;">Contractors&ensp; <i class="fa fa-check"></i></div>
                    
                    <div class="col-xs-12 col-md-12" style="font-weight: bold;">Task(s)</div>
                    <div class="col-xs-12 col-md-12" >Block A Light Repair &ensp; <i class="fa fa-check"></i> </div>
                    <div class="col-xs-12 col-md-12" >pipe leaking b2 &ensp; <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" >bomba hose leaking &ensp; <i class="fa fa-times"></i> </div>
                    <div class="col-xs-12 col-md-12" >BUILDING INSPECTION <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;">RECIFY COMMON AREA LIGHTING &ensp; <i class="fa fa-check"></i></div-->
                
                    
                    <!--div class="col-xs-12 col-md-12" >Water Tank Check &ensp; <i class="fa fa-check"></i> </div>
                    <div class="col-xs-12 col-md-12" >Meter Reading - 102345.67(Prev - 10224.35) &ensp; <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" >Adult Pool Check &ensp; <i class="fa fa-times"></i> </div>
                    <div class="col-xs-12 col-md-12" >Gym <i class="fa fa-check"></i></div>
                    <div class="col-xs-12 col-md-12" >Genset <i class="fa fa-times"></i></div>
                    <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;">Light Condition &ensp; <i class="fa fa-check"></i></div-->
                    
                    <!--div class="col-xs-12 col-md-12" style="font-weight: bold;">Other Matter(s)</div>
                    
                    <div class="col-xs-12 col-md-12" >1. &ensp;Syabas person came today to change meter for Block A</div>
                    <div class="col-xs-12 col-md-12" style="border-bottom: 1px dashed #999;">2. &ensp;TNB personal came today to Block D meter</div>
                    
                    <div class="col-md-12 col-xs-12 text-right" style="padding: 15px;">                        
                        <button type="button" class="btn btn-primary history_btn" value="" data-value="1">Send Report</button>                    
                    </div-->
                
                <div class="col-xs-12 col-md-12 text-right">
                    <a role="button" class="btn btn-primary" href="<?php echo base_url('index.php/bms_daily_report/index').'?property_id='.$_GET['property_id'].'&report_date='.$_GET['report_date'].'&act=pdf';?>" >Generate Report</a>
                </div>
                </div>
                
                
                <?php } if(!empty($_GET['property_id']) && !empty($_GET['report_date']) && $report_status == 4) { ?>
                <div class="row report-container" style="margin: 0;padding-top:0px">
                    <div class="box-body">
                      <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                          <th class="hidden-xs">S No</th>
                          <th>Property</th>
                          <th class="hidden-xs">Routine Task Title</th>
                          <th >Routine Task Name</th>
                            
                          <th class="">Assigned Date</th> 
                                    
                          <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <?php 
                            $offset = 0;
                            if(!empty($pending_sop)) {
                                //$prop_doc_download_desi_id = $this->config->item('prop_doc_download_desi_id');
                                foreach ($pending_sop as $key=>$val) { ?>
                                <tr>
                                    <td class="hidden-xs" style="text-align: center;width:5%"><?php echo ++$offset;?></td>
                                    <td class="col-md-2"><?php echo $val['property_name'];?></td>
                                    <td class="col-md-3 hidden-xs">Routine Task for <?php echo $val['desi_name'];?></td>
                                    <td class="col-md-3"><?php echo $val['sop_name'] != '' ? $val['sop_name'] : ' - ';?></td>
                                                                
                                    <td class="col-md-1"><?php echo date('d-m-Y', strtotime($val['created_date']));?></td>  
                                    
                                    <td style="text-align: center;" class="col-md-1">
                                        
                                        <a href="<?php echo base_url('index.php/bms_daily_report/overwrite_sop_entry/'.$val['sop_id']).'?property_id='.$_GET['property_id'].'&report_date='.$_GET['report_date'];?>" title="Update"><i class="fa fa-edit"></i></a>
                                        
                                    </td>
                                
                                </tr>
                            
                        <?php }
                        
                            }  ?> 
                        </tbody>
                        
                      </table>
                    </div>
                </div>
                
                <?php } if(!empty($_GET['property_id']) && !empty($_GET['report_date']) && $report_status == 6) { ?>
                <div class="row report-container" style="margin: 0;padding-top:0px">
                    <div class="box-body">
                      
                      Please click <a href="../../<?php echo $file_location;?>" target="_blank">here </a> to view / download the report. 
                        
                    </div>
                </div>
                <?php } ?>
                
                
              </div>
              <!-- /.box-body -->
          
           
            
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
        <h4 class="modal-title">Image</h4>
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
  <div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated On '. date('d-m-Y h:i:s a') .'</div>
  </body></html>