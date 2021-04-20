<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
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
            <form role="form" id="sop_new" action="<?php echo base_url('index.php/bms_sop/new_sop_submit');?>" method="post" >
                  
                <div class="box-body">
                    <!--div class="box-header with-border" style="padding: 10px 0;">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task</h3>
                    </div-->
                  
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Property Name *</label>
                                <select class="form-control" id="property_id" name="sop_glob[property_id]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {                                        
                                        $selected = isset($_GET['property_id']) ?  (trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ? 'selected="selected" ' : '') : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '');
                                        echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                                            
                        <div class="col-md-6 col-xs-6">
                            <div class="form-group">
                              <label>Assign To *</label>
                              <select id="assign_to" name="sop_glob[assign_to]" class="form-control">
                                <option value="">Select</option>
                                <?php 
                                    foreach ($assign_to as $key=>$val) {
                                        
                                        $selected = isset($_GET['assign_to']) && trim($_GET['assign_to']) != '' && trim($_GET['assign_to']) == $val['desi_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['desi_id']."' ".$selected.">".$val['desi_name']."</option>";
                                    } ?> 
                              </select>
                            </div>
                        </div>                                            
                    
                </div>
                
                <?php 
                    $last_sop_cnt = 0; if(!empty($sop)){ $val = $sop[0]; }
                    /*if(!empty($sop)){
                        foreach ($sop as $key=>$val) {
                            $last_sop_cnt = $last_sop_cnt + 1; 
                    
                ?>
                
                <div class="row" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task <?php echo $last_sop_cnt;?></h3>
                    </div>
                  
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label>Routine Task Title *</label>
                            <input type="hidden" name="sop[<?php echo $last_sop_cnt;?>][sop_id]" value="<?php echo $val['sop_id'];?>" />
                            <input type="text" name="sop[<?php echo $last_sop_cnt;?>][sop_name]" value="<?php echo isset($val['sop_name']) && $val['sop_name'] != '' ? $val['sop_name'] : '';?>" class="form-control" placeholder="Enter Routine Task Title" maxlength="200">
                        </div>
                        
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label>Start Date</label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="sop[<?php echo $last_sop_cnt;?>][start_date]" value="<?php echo isset($val['start_date']) && $val['start_date'] != '' && $val['start_date'] != '0000-00-00' && $val['start_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['start_date'])) : '';?>" type="text">
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div> 
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label> Due Date <p class="help-block hidden-xs" style="display: inline;">(Leave blank if no due date)</p></label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="sop[<?php echo $last_sop_cnt;?>][due_date]" value="<?php echo isset($val['due_date']) && $val['due_date'] != '' && $val['due_date'] != '0000-00-00' && $val['due_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['due_date'])) : '';?>" type="text">
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>  
                        
                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                        
                            <div class="form-group">
                              <div class="checkbox">
                                <div class="col-md-6">                          
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][mon]" <?php echo isset($val['mon']) && $val['mon'] == '1' ? 'checked="checked"' : ''; ?> value="1">Mon </label>&ensp;&ensp;
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][tue]" <?php echo isset($val['tue']) && $val['tue'] == '1' ? 'checked="checked"' : ''; ?> value="1">Tue </label>&ensp;&ensp;
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][wed]" <?php echo isset($val['wed']) && $val['wed'] == '1' ? 'checked="checked"' : ''; ?> value="1">Wed </label>&ensp;&ensp;
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][thu]" <?php echo isset($val['thu']) && $val['thu'] == '1' ? 'checked="checked"' : ''; ?> value="1">Thu </label>&ensp;&ensp;                                                                                  
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][fri]" <?php echo isset($val['fri']) && $val['fri'] == '1' ? 'checked="checked"' : ''; ?> value="1">Fri </label>&ensp;&ensp;
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][sat]" <?php echo isset($val['sat']) && $val['sat'] == '1' ? 'checked="checked"' : ''; ?> value="1">Sat </label>&ensp;&ensp;
                                    <label><input type="checkbox" name="sop[<?php echo $last_sop_cnt;?>][sun]" <?php echo isset($val['sun']) && $val['sun'] == '1' ? 'checked="checked"' : ''; ?> value="1">Sun </label>&ensp;&ensp;
                                    
                                </div>                            
                              </div>
                             </div> 
                        
                        </div>
                        
                        <div class="col-md-3 col-xs-6">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                  <label>Execute Time</label>
                                  <div class="input-group">
                                    <input type="text" name="sop[<?php echo $last_sop_cnt;?>][execute_time]" data-value="<?php echo $val['execute_time'];?>" value="<?php echo isset($val['execute_time']) && $val['execute_time'] != '' ? date('h:i A', strtotime($val['execute_time'])) : '';?>" class="form-control timepicker">
                
                                    <div class="input-group-addon">
                                      <i class="fa fa-clock-o"></i>
                                    </div>
                                  </div><!-- /.input group -->
                                  
                                </div><!-- /.form group -->
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-xs-6">
                            
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                  <label>Due By</label>
                                  <div class="input-group">
                                    <input type="text" name="sop[<?php echo $last_sop_cnt;?>][due_by]" data-value="<?php echo $val['due_by'];?>" value="<?php echo isset($val['due_by']) && $val['due_by'] != '' ? date('h:i A', strtotime($val['due_by'])) : '';?>" class="form-control timepicker">
                
                                    <div class="input-group-addon">
                                      <i class="fa fa-clock-o"></i>
                                    </div>
                                  </div><!-- /.input group -->
                                  
                                </div><!-- /.form group -->
                            </div>                            
                        </div>
                        
                        
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                              <label>Reminder(Min)</label>
                              <input type="text" name="sop[<?php echo $last_sop_cnt;?>][reminder]" value="<?php echo isset($val['reminder']) && $val['reminder'] != '' ? $val['reminder'] : '';?>" class="form-control" placeholder="Enter Reminder(Min)">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label>Repeat Timer(Min)</label>            
                                <input type="text" name="sop[<?php echo $last_sop_cnt;?>][repeat_rem]" value="<?php echo isset($val['repeat_rem']) && $val['repeat_rem'] != '' ? $val['repeat_rem'] : '';?>" class="form-control" placeholder="Enter Repeat Timer(Min)">
                              </div>
                        </div>  
                        
                        <div class="col-md-3 col-xs-6" style="">
                            <div class="form-group">
                              <div class="radio">
                                <label><input type="radio" name="sop[<?php echo $last_sop_cnt;?>][req_type]" <?php echo isset($val['condition_req']) && $val['condition_req'] == '1' ? 'checked="checked"' : ''; ?>  value="condition_req">Condition Required </label>
                              </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-xs-6" style="">
                            <div class="form-group">
                              <div class="radio">
                                <label><input type="radio" name="sop[<?php echo $last_sop_cnt;?>][req_type]" <?php echo isset($val['reading_req']) && $val['reading_req'] == '1' ? 'checked="checked"' : ''; ?>  value="reading_req">Reading Required </label>
                              </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-xs-12" style="text-align:right;">
                            <div class="form-group">
                              <div class="checkbox">
                                <button type="button" class="btn btn-success add_sub_sop_btn" id="sop_<?php echo $last_sop_cnt;?>" value="<?php echo !empty($sub_sop[$key]) ? count($sub_sop[$key]) : 0;?>" data-value="<?php echo $last_sop_cnt;?>">Add Sub Task</button>
                              </div>
                            </div>
                        </div>                   
                    
                    </div> <!-- /.row -->
                    
                    <!-- Sub Routine Task tasks if any -->
                    <div class="col-md-12 col-xs-12 no-padding sub_sop_container_<?php echo $last_sop_cnt;?>" > 
                    <?php if(!empty($sub_sop[$key])) { // SUB Routine Task
                            
                            foreach ($sub_sop[$key] as $key2=>$val2) { ?>
                            
                            <div class="row sub_sop_<?php echo ($key2+1);?>" style="padding: 15px 0 15px 35px;">
                            	<div class="box-header with-border">
                            		<h3 class="box-title" style="font-weight: bold;">
                            			Sub Routine Task <?php echo ($key2+1);?>
                            		</h3>
                            	</div>
                            	<div class="col-md-6 col-xs-12">
                            		<label>
                            			Sub Routine Task Title *
                            		</label>
                                    <input type="hidden" name="sub_sop[<?php echo $last_sop_cnt;?>][<?php echo ($key2+1);?>][sop_sub_id]" value="<?php echo $val2['sop_sub_id'];?>" />
                            		<input class="form-control" name="sub_sop[<?php echo $last_sop_cnt;?>][<?php echo ($key2+1);?>][sub_sop_name]" value="<?php echo isset($val2['sub_sop_name']) && $val2['sub_sop_name'] != '' ? $val2['sub_sop_name'] : '';?>" placeholder="Enter Routine Task Title" type="text">
                            	</div>
                            	<div class="col-md-3 col-xs-6" style="margin-top: 15px;">
                            		<div class="form-group">
                            			<div class="radio">
                            				<label>
                            					<input name="sub_sop[<?php echo $last_sop_cnt;?>][<?php echo ($key2+1);?>][req_type]" <?php echo isset($val2['condition_req']) && $val2['condition_req'] == '1' ? 'checked="checked"' : ''; ?>  value="condition_req" type="radio">
                            					Condition Required
                            				</label>
                            			</div>
                            		</div>
                            	</div>
                            	<div class="col-md-3 col-xs-6" style="margin-top: 15px;">
                            		<div class="form-group">
                            			<div class="radio">
                            				<label>
                            					<input name="sub_sop[<?php echo $last_sop_cnt;?>][<?php echo ($key2+1);?>][req_type]" <?php echo isset($val2['reading_req']) && $val2['reading_req'] == '1' ? 'checked="checked"' : ''; ?>  value="reading_req" type="radio">
                            					Reading Required
                            				</label>
                            			</div>
                            		</div>
                            	</div>
                            </div>
                            
                    <?php  } 
                    } 
                    ?>
                    
                    </div> <!-- /.sub_sop_container_* -->
                    
                </div> <!-- /.row -->
                
                
                <?php 
                
                        }
                }*/ 
                //echo "<pre>";print_r($val);echo "</pre>";
                ?>
                
                
                
                <!-- Routine Task -->
                <div class="row" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <!--div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task </h3>
                    </div-->
                  
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                            <div class="col-md-12 col-xs-12">
                                <label>Routine Task Title *</label>
                                <input type="hidden" name="sop[0][sop_id]" value="<?php echo !empty($val['sop_id']) ? $val['sop_id'] : '';?>" />
                                <input type="text" name="sop[0][sop_name]" value="<?php echo isset($val['sop_name']) && $val['sop_name'] != '' ? $val['sop_name'] : '';?>" class="form-control" placeholder="Enter Routine Task Title" maxlength="200">
                            </div>
                            
                            <div class="col-md-6 col-xs-12" style="padding-top: 10px;">
                                <label>Task Schedule </label>
                                <select name="sop[0][task_schedule]" id="task_schedule" class="form-control">
                                <?php $r_task_schedule = $this->config->item('r_task_schedule');
                                    foreach ($r_task_schedule as $key2=>$val2) { 
                                        $selected = !empty($val['task_schedule']) && $val['task_schedule'] == $key2 ? 'selected="selected" ' : '';
                                        echo "<option value='".$key2."' ".$selected." >".$val2."</option>";
                                    
                                    } ?>
                                </select>
                                
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                        
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                    <label>Start Date *</label>
                    
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right datepicker" id="datepicker" name="sop[0][start_date]" value="<?php echo isset($val['start_date']) && $val['start_date'] != '' && $val['start_date'] != '0000-00-00' && $val['start_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['start_date'])) : '';?>" type="text">
                                    </div>
                                    <!-- /.input group -->
                                  </div>
                            </div> 
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                    <label> Due Date <p class="help-block hidden-xs" style="display: inline;font-size:10px;">(Leave blank if no due date)</p></label>
                    
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right datepicker" name="sop[0][due_date]" value="<?php echo isset($val['due_date']) && $val['due_date'] != '' && $val['due_date'] != '0000-00-00' && $val['due_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['due_date'])) : '';?>" type="text">
                                    </div>
                                    <!-- /.input group -->
                                  </div>
                            </div>  
                            
                            <div class="col-md-6 col-xs-12 task_days_div" style="margin-bottom: 15px;">
                            
                                <div class="form-group">
                                    <label>Task Days</label>
                                  <div class="checkbox"  style="margin-top: 5px;">
                                    <div class="col-md-12">                                                           
                                        <label><input type="checkbox" name="sop[0][mon]" <?php echo isset($val['mon']) && $val['mon'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Mon </label>&ensp;&ensp;
                                        <label><input type="checkbox" name="sop[0][tue]" <?php echo isset($val['tue']) && $val['tue'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Tue </label>&ensp;&ensp;
                                        <label><input type="checkbox" name="sop[0][wed]" <?php echo isset($val['wed']) && $val['wed'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Wed </label>&ensp;&ensp;
                                        <label><input type="checkbox" name="sop[0][thu]" <?php echo isset($val['thu']) && $val['thu'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Thu </label>&ensp;&ensp;                                                                                  
                                        <label><input type="checkbox" name="sop[0][fri]" <?php echo isset($val['fri']) && $val['fri'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Fri </label>&ensp;&ensp;
                                        <label><input type="checkbox" name="sop[0][sat]" <?php echo isset($val['sat']) && $val['sat'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Sat </label>&ensp;&ensp;
                                        <label><input type="checkbox" name="sop[0][sun]" <?php echo isset($val['sun']) && $val['sun'] == '1' ? 'checked="checked"' : ''; ?> value="1" class="task_days_chk">Sun </label>&ensp;&ensp;
                                    </div>                           
                                  </div>
                                 </div> 
                            
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                            <div class="col-md-3 col-xs-6">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                      <label>Execute Time</label>
                                      <div class="input-group">
                                        <input type="text" name="sop[0][execute_time]" value="<?php echo isset($val['execute_time']) && $val['execute_time'] != '' ? date('h:i A', strtotime($val['execute_time'])) : '';?>" class="form-control timepicker">
                    
                                        <div class="input-group-addon">
                                          <i class="fa fa-clock-o"></i>
                                        </div>
                                      </div><!-- /.input group -->
                                      
                                    </div><!-- /.form group -->
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-xs-6">
                                
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                      <label>Due By</label>
                                      <div class="input-group">
                                        <input type="text" name="sop[0][due_by]" value="<?php echo isset($val['due_by']) && $val['due_by'] != '' ? date('h:i A', strtotime($val['due_by'])) : '';?>"  class="form-control timepicker">
                    
                                        <div class="input-group-addon">
                                          <i class="fa fa-clock-o"></i>
                                        </div>
                                      </div><!-- /.input group -->
                                      
                                    </div><!-- /.form group -->
                                </div>
                                                            
                            </div>
                            
                            
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                  <label>Reminder(Min)</label>
                                  <input type="text" name="sop[0][reminder]" value="<?php echo isset($val['reminder']) && $val['reminder'] != '' ? $val['reminder'] : '';?>" class="form-control" placeholder="Enter Reminder(Min)">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                    <label>Repeat Timer(Min)</label>            
                                    <input type="text" name="sop[0][repeat_rem]" value="<?php echo isset($val['repeat_rem']) && $val['repeat_rem'] != '' ? $val['repeat_rem'] : '';?>"  class="form-control" placeholder="Enter Repeat Timer(Min)">
                                  </div>
                            </div>  
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="margin-bottom: 10px;">
                            <div class="col-md-3 col-xs-6" style="">
                                <div class="form-group">
                                  <div class="radio">
                                    <label><input type="radio" name="sop[0][req_type]" <?php echo isset($val['condition_req']) && $val['condition_req'] == '1' ? 'checked="checked"' : ''; ?> value="condition_req">Condition Required </label>
                                  </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-xs-6" style="">
                                <div class="form-group">
                                  <div class="radio">
                                    <label><input type="radio" name="sop[0][req_type]" <?php echo isset($val['reading_req']) && $val['reading_req'] == '1' ? 'checked="checked"' : ''; ?> value="reading_req">Reading Required </label>
                                  </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-xs-6 ">
                                <div class="form-group">
                                  <div class="checkbox">
                                    <label><input name="sop[0][exclude_print]" <?php echo isset($val['exclude_print']) && $val['exclude_print'] == '1' ? 'checked="checked"' : ''; ?> value="1" type="checkbox">Exclude to Print</label>
                                  </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-xs-6" style="text-align:right;">
                                <div class="form-group">
                                  <div class="checkbox">
                                    <button type="button" class="btn btn-success add_sub_sop_btn" id="sop_<?php echo $last_sop_cnt;?>" value="0" data-value="0">Add Sub Task</button>
                                  </div>
                                </div>
                            </div> 
                         </div>                  
                    
                    </div> <!-- /.row -->
                    
                    <div class="col-md-12 col-xs-12 no-padding sub_sop_container_0" > 
                    
                    <?php 
                    $sub_sop_cnt = 0;
                    if(!empty($sub_sop[0])) { // SUB Routine Task
                            
                            foreach ($sub_sop[0] as $key2=>$val2) { //echo "<pre>";print_r($val2);echo "</pre>"; ?>
                            
                            <div class="row sub_sop_<?php echo $sub_sop_cnt;?>" style="padding: 15px 0 15px 35px;">
                            	<div class="box-header with-border">
                            		<h3 class="box-title" style="font-weight: bold;">
                            			Sub Routine Task 
                            		</h3>
                            	</div>
                            	<div class="col-md-12 col-sm-12 col-xs-12">
                            		<label>
                            			Sub Routine Task Title *
                            		</label>
                                    <input type="hidden" name="sub_sop[0][<?php echo ($key2+1);?>][sop_sub_id]" value="<?php echo $val2['sop_sub_id'];?>" />
                            		<input class="form-control" name="sub_sop[0][<?php echo ($key2+1);?>][sub_sop_name]" value="<?php echo isset($val2['sub_sop_name']) && $val2['sub_sop_name'] != '' ? $val2['sub_sop_name'] : '';?>" placeholder="Enter Sub Routine Task Title" type="text">
                            	</div>
                            	<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                            		<div class="form-group">
                            			<div class="radio">
                            				<label>
                            					<input name="sub_sop[0][<?php echo ($key2+1);?>][req_type]" <?php echo isset($val2['condition_req']) && $val2['condition_req'] == '1' ? 'checked="checked"' : ''; ?>  value="condition_req" type="radio">
                            					Condition Required
                            				</label>
                            			</div>
                            		</div>
                            	</div>
                            	<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">
                            		<div class="form-group">
                            			<div class="radio">
                            				<label>
                            					<input name="sub_sop[0][<?php echo ($key2+1);?>][req_type]" <?php echo isset($val2['reading_req']) && $val2['reading_req'] == '1' ? 'checked="checked"' : ''; ?>  value="reading_req" type="radio">
                            					Reading Required
                            				</label>
                            			</div>
                            		</div>
                            	</div>
                                <div class="col-md-6 col-sm-4 col-xs-4 text-right" style="margin-top: 15px;">
                                    <button type="button" class="btn btn-danger delete_sub_btn" data-value="<?php echo $sub_sop_cnt++;?>" data-subsopid="<?php echo $val2['sop_sub_id'];?>" >Delete Sub Task</button>
                                </div>
                            </div>
                            
                    <?php  } } ?>
                    
                    </div> <!-- /.sub_sop_container_* -->
                    
                </div> <!-- /.row -->
                
                <!-- Add More Routine Task -->
                
                <div class="row">
                    <div class="col-md-12 col-xs-12" >
                        <p class="help-block"> * Required Fields.</p>
                    </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;
                    <button type="Reset" class="btn btn-default">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
            
            
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once('footer.php');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    set_task_days($('#task_schedule').val());
    /** Form validation */
    
    $("#sop_new" ).validate({
		rules: {
			"sop_glob[property_id]": "required",					
            "sop_glob[assign_to]":"required",
            "sop[0][start_date]":"required"            
		},
		messages: {
			"sop_glob[property_id]": "Please select Property Name",					
            "sop_glob[assign_to]":"Please select Assign To",
            "sop[0][start_date]":"Please select Start Date"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "id" ) === "datepicker" ) {
				error.insertAfter( element.parent( "div" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	});
    
    var sop_cnt = eval("<?php echo $last_sop_cnt;?>");
    $('.add_sub_sop_btn').click(function () {
        //console.log($(this).attr('data-value') + ' = '+$(this).attr('id') + ' = '+$(this).val());
        add_sub_sop($(this).attr('id'),$(this).val(),$(this).attr('data-value'));
    });
    
    var sub_sop_cnt = eval("<?php echo $sub_sop_cnt;?>");
    
    function add_sub_sop (id,val,parent_id) {
        val = val+1;
        var str = '<div class="row sub_sop_'+id+' sub_sop_'+sub_sop_cnt+'" style="padding: 15px 0 15px 35px;">'; 
        str += '<div class="box-header with-border">';
        str += '<h3 class="box-title" style="font-weight: bold;">';
        str += 'Sub Routine Task ';
        str += '</h3>';
        str += '</div>';
        str += '<div class="col-md-12 col-sm-12 col-xs-12">';
        str += '<label>Sub Routine Task Title *</label>';
        str += '<input type="text" class="form-control" name="sub_sop['+parent_id+']['+val+'][sub_sop_name]" placeholder="Enter Sub Routine Task Title" maxlength="200">';
        str += '</div>';
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<div class="radio">';
        str += '<label><input type="radio" name="sub_sop['+parent_id+']['+val+'][req_type]" value="condition_req">Condition Required</label>';
        str += '</div>';
        str += '</div>';
        str += '</div>';       
        str += '<div class="col-md-3 col-sm-4 col-xs-4" style="margin-top: 15px;">';
        str += '<div class="form-group">';
        str += '<div class="radio">';
        str += '<label><input type="radio" name="sub_sop['+parent_id+']['+val+'][req_type]" value="reading_req">Reading Required </label>';
        str += '</div>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-6 col-sm-4 col-xs-4 text-right" style="margin-top: 15px;">';
        str += '<button type="button" class="btn btn-danger delete_sub_btn" data-value="'+(sub_sop_cnt++)+'" data-subsopid="">Delete Sub Task</button>';
        str += '</div>';
        //str += '';
        str += '</div>'; 
        $('.sub_sop_container_'+parent_id).append(str);
        //console.log($('.sub_sop_'+id).length);
        //$('#'+id).parent('div').parent('div').parent('div').parent('div').after(str);
        /*if($('.sub_sop_'+id).length)
            $('#'+id).parent('div').parent('div').parent('div').parent('div').siblings('.sub_sop_'+id+':last').after(str);
        else 
            $('#'+id).parent('div').parent('div').parent('div').parent('div').after(str);
        //$('.sub_sop1').insertBefore(str);*/
        $('#'+id).val(val);
        $('.delete_sub_btn').unbind("click");
        $('.delete_sub_btn').bind ("click",function () {
            delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"));
        });
    }
    
    function delete_sub_sop (div_cls_id, sub_sop_id) {
        //console.log($(this).attr('data-value'));
        if(sub_sop_id == "") {
            $('.sub_sop_'+div_cls_id).remove();    
        } else {
            if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
                $.ajax({
                    type:"post",
                    async: true,
                    url: '<?php echo base_url('index.php/bms_sop/delete_sub_sop');?>/'+sub_sop_id, // Reusing the same function from task creation
                    data: {'sub_sop_id':sub_sop_id},
                    //datatype:"json", // others: xml, json; default is html
                    beforeSend:function (){ $('.sub_sop_'+div_cls_id).LoadingOverlay("show"); }, //
                    success: function(data) {
                        $('.sub_sop_'+div_cls_id).LoadingOverlay("hide", true);
                        if(data == 1) {
                            $('.sub_sop_'+div_cls_id).remove();                                                           
                        }
                    },
                    error: function (e) {  
                        $('.sub_sop_'+div_cls_id).LoadingOverlay("hide", true);
                        console.log(e); //alert("Something went wrong. Unable to retrive data!");
                    }
                });
            }
        }           
    }
    $('.delete_sub_btn').bind ("click",function () {
       delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"));  
    });
    
    function property_change_eve () {
        var property_id = $('#property_id').val();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/assign_to');?>', // Reusing the same function from task creation
            data: {'property_id':property_id},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show"); }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                var str2 = '';
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str2 += '<option value="'+item.desi_id+'">'+item.desi_name+'</option>';
                    });
                }
                $('#assign_to').html(str+str2);   
                //$('#sop_ov_desi').html(str2); 
                //loadOverSeeingJMB (property_id);             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {  
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
    
    // On document ready
    if($('#property_id').val() != '' && $('#assign_to').val() == '') {
        //console.log($('#property_id').val());
        property_change_eve ();//$('#property_id').trigger("change");
    }
    
    // On property name change
    $('#property_id').change(function () {
        property_change_eve ();        
    });   
    
});

function set_task_days (val) {
    if(val == 1) {
        $('.task_days_div').css('display','block');
    } else {
        $('.task_days_div').css('display','none');
        $('.task_days_chk').prop('checked',false);
    }
}

$('#task_schedule').change(function () {
    set_task_days($('#task_schedule').val());
});

$('#assign_to').change(function () {
    //window.location.href="<?php echo base_url('index.php/bms_sop/new_sop');?>?property_id="+$('#property_id').val()+"&assign_to="+$('#assign_to').val();
    //return false;
});

/*function loadOverSeeingJMB (property_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sop/get_ov_jmb');?>', // Reusing the same function from task creation
            data: {'property_id':property_id},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $('#sop_ov_jmp').html('<option value="">Loading...</option>'); }, //
            success: function(data) {
                var str = '';                 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.MemPosition+'">'+item.MemPosition+'</option>';
                    });
                }
                
                $('#sop_ov_jmp').html(str);                 
                
            },
            error: function (e) {
                $('#sop_ov_jmp').html('');
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}*/

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  })
</script>