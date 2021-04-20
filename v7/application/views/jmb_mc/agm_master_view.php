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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_jmb_mc/agm_master_save');?>" method="post" >
                  
                <div class="box-body">
                
                    <div class="row">
                    
                        <div class="col-md-12 col-xs-12 no-padding">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                  <label>AGM Type</label>
                                    <select class="form-control" id="agm_type" name="agm[agm_type]" >                              
                                    <option value="">Select Type</option>
                                    <?php
                                        foreach ($agm_types as $key=>$val) {                                        
                                            $selected = isset($_GET['agm_type']) && trim($_GET['agm_type']) != '' && trim($_GET['agm_type']) == $key ? 'selected="selected" ' : '';
                                            echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                        } ?> 
                                      </select>
                                </div>
                            </div>
                        </div>                       
                    
                    </div>
                
                <!-- AGM -->                
                <?php 
                $agm_cnt = 0;
                $agm_remin = $this->config->item('agm_remin');
                if (isset($_GET['agm_type']) && trim($_GET['agm_type']) != '' && in_array($_GET['agm_type'],array_keys($agm_types))) { 
                    if(!empty($check_list)) {
                        foreach ($check_list as $key=>$val) {  ?>
                        
                    <div class="row agm_div_<?php echo ++$agm_cnt;?>" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                        
                      
                        <div class="row add_reminder_bottom" style="padding-top: 15px;">
                            <div class="col-md-12 col-xs-12">
                            
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label ><?php echo $agm_types[$_GET['agm_type']];?> Checklist</label>
                                        <input type="text" name="agm[agm_descrip][<?php echo $agm_cnt;?>]" class="form-control" value="<?php echo !empty($val['agm_descrip']) ? $val['agm_descrip'] : '';?>" placeholder="Enter <?php echo $agm_types[$_GET['agm_type']];?> Checklist" maxlength="250">
                                        <input type="hidden" name="agm[agm_master_id][<?php echo $agm_cnt;?>]" value="<?php echo !empty($val['agm_master_id']) ? $val['agm_master_id'] : '';?>"/>                                
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Role Responsibility</label>                            
                                            <select class="form-control" name="agm[agm_responsibility][<?php echo $agm_cnt;?>]" >                              
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($designations as $key2=>$val2) {                                        
                                                    $selected = !empty($val['agm_responsibility']) && $val['agm_responsibility'] == $val2['desi_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='".$val2['desi_id']."' ".$selected.">".$val2['desi_name']."</option>";
                                                } ?> 
                                              </select>
                                          </div>
                                    </div> 
                                </div>                         
                                
                                
                                <?php 
                                
                                if(!empty($chk_list_reminder[$key])) {
                                
                                    foreach ($chk_list_reminder[$key] as $key4=>$val4) { ?>
                                    
                                        <div class="col-md-12 col-xs-12 reminder_div_<?php echo $agm_cnt;?>_<?php echo $key4;?>">
                                        <div class="col-md-5 col-xs-5">
                                            <div class="form-group">
                                                <label >Reminder</label>
                                                <select name="agm_reminder[<?php echo $agm_cnt;?>][remind_before][<?php echo $key4;?>]" class="form-control">
                                                    <option value="">Select</option>   
                                                    <?php                                                
                                                        
                                                        foreach ($agm_remin as $key3=>$val3) { 
                                                            $selected = !empty($val4['remind_before']) && $val4['remind_before'] == $key3 ? 'selected="selected" ' : '';  
                                                            echo "<option value='".$key3."' ".$selected.">".$val3."</option>";
                                                        }
                                                    ?>                             
                                                </select>
                                                <input type="hidden" name="agm_reminder[<?php echo $agm_cnt;?>][agm_master_reminder_id][<?php echo $key4;?>]" value="<?php echo !empty($val4['agm_master_reminder_id']) ? $val4['agm_master_reminder_id'] : '';?>"/> 
                                                <input type="hidden" name="agm_reminder[<?php echo $agm_cnt;?>][agm_master_id][<?php echo $key4;?>]" value="<?php echo !empty($val4['agm_master_id']) ? $val4['agm_master_id'] : '';?>"/>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-6 col-xs-12">
                                            <div class="col-md-12 col-xs-12 no-padding">
                                                <div class="col-md-6 col-xs-6 no-padding">
                                                    <label>Reminder Email</label>
                                                </div>
                                                <div class="col-md-3 col-xs-3 no-padding">                                            
                                                      <div class="checkbox" style="margin: 0;font-weight:bold;">
                                                        <label style="font-weight:bold;">
                                                            <input name="agm_reminder[<?php echo $agm_cnt;?>][email_staff][<?php echo $key4;?>]" value="1" type="checkbox" <?php echo !empty($val4['email_staff']) && $val4['email_staff'] == 1 ? 'checked="checked" ' : '';?> > Staff 
                                                        </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-3 col-xs-3 no-padding">                                            
                                                      <div class="checkbox" style="margin: 0;">
                                                        <label style="font-weight:bold;"><input name="agm_reminder[<?php echo $agm_cnt;?>][email_jmb][<?php echo $key4;?>]" value="1" type="checkbox" <?php echo !empty($val4['email_jmb']) && $val4['email_jmb'] == 1 ? 'checked="checked" ' : '';?> > JMB / MC </label>
                                                      </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 no-padding">
                                                <div class="form-group">                                      
                                                  <textarea name="agm_reminder[<?php echo $agm_cnt;?>][email_content][<?php echo $key4;?>]" class="form-control" rows="3" placeholder="Enter Reminder Email"><?php echo !empty($val4['email_content']) ? $val4['email_content'] : '';?></textarea>
                                                </div>
                                            </div>                                    
                                        </div>
                                        <?php if($key4 == 0) { ?>
                                            <div class="col-md-1 col-xs-1" style="padding-top: 50px;" >
                                                <a href="javascript:;" class="btn btn-success btn-circle add_reminder_btn" data-parent="<?php echo $agm_cnt;?>" data-value="<?php echo count($chk_list_reminder[$key])-1;?>" ><i class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-1 col-xs-1" style="padding-top: 50px;" >
                                                <a href="javascript:;" class="btn btn-danger btn-circle del_reminder_btn" data-parent="<?php echo $agm_cnt;?>" data-value="<?php echo $key4;?>" ><i class="fa fa-minus"></i></a>
                                            </div>
                                            
                                        <?php } ?>
                                    </div>
                                        
                                   <?php  }
                                        
                                } else { ?>
                                
                                <div class="col-md-12 col-xs-12 reminder_div_<?php echo $agm_cnt;?>_0">
                                    <div class="col-md-5 col-xs-5">
                                        <div class="form-group">
                                            <label >Reminder</label>
                                            <select name="agm_reminder[<?php echo $agm_cnt;?>][remind_before][0]" class="form-control">
                                                <option value="">Select</option>   
                                                <?php                                                
                                                    
                                                    foreach ($agm_remin as $key3=>$val3) { 
                                                        echo "<option value='".$key3."' >".$val3."</option>";
                                                    }
                                                ?>                             
                                            </select> 
                                            
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6 col-xs-12">
                                        <div class="col-md-12 col-xs-12 no-padding">
                                            <div class="col-md-6 col-xs-6 no-padding">
                                                <label>Reminder Email</label>
                                            </div>
                                            <div class="col-md-3 col-xs-3 no-padding">                                            
                                                  <div class="checkbox" style="margin: 0;font-weight:bold;">
                                                    <label style="font-weight:bold;"><input name="agm_reminder[<?php echo $agm_cnt;?>][email_staff][0]" value="1" type="checkbox"  > Staff </label>
                                                  </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3 no-padding">                                            
                                                  <div class="checkbox" style="margin: 0;">
                                                    <label style="font-weight:bold;"><input name="agm_reminder[<?php echo $agm_cnt;?>][email_jmb][0]" value="1" type="checkbox"  > JMB / MC </label>
                                                  </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 no-padding">
                                            <div class="form-group">                                      
                                              <textarea name="agm_reminder[<?php echo $agm_cnt;?>][email_content][0]" class="form-control" rows="3" placeholder="Enter Reminder Email"></textarea>
                                            </div>
                                        </div>                                    
                                    </div>
                                    
                                    <div class="col-md-1 col-xs-1" style="padding-top: 50px;" >
                                        <a href="javascript:;" class="btn btn-success btn-circle add_reminder_btn" data-parent="<?php echo $agm_cnt;?>" data-value="0" ><i class="fa fa-plus"></i></a>
                                    </div>
                                
                                </div>                        
                                  
                                 <?php } ?> 
                                
                                                        
                                              
                        
                        </div> <!-- /.row -->
                        
                        <div class="col-md-12 text-right"><button type="button" class="btn btn-danger del_agm_btn" value="0" data-value="<?php echo $agm_cnt;?>" aria-invalid="false">Delete AGM Checklist</button></div>
                        
                        
                    </div> <!-- /.row -->
                
               <?php 
                    } 
                } else {
               ?>  
                <div class="row agm_div_<?php echo ++$agm_cnt;?>" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                        
                      
                        <div class="row add_reminder_bottom" style="padding-top: 15px;">
                            <div class="col-md-12 col-xs-12">
                            
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label ><?php echo $agm_types[$_GET['agm_type']];?> Checklist</label>
                                        <input type="text" name="agm[agm_descrip][<?php echo $agm_cnt;?>]" class="form-control" value="" placeholder="Enter <?php echo $agm_types[$_GET['agm_type']];?> Checklist" maxlength="250">
                                        <input type="hidden" name="agm[agm_master_id][<?php echo $agm_cnt;?>]" value=""/>                                
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label> Role Responsibility</label>                            
                                            <select class="form-control" name="agm[agm_responsibility][<?php echo $agm_cnt;?>]">                              
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($designations as $key2=>$val2) {
                                                    echo "<option value='".$val2['desi_id']."'>".$val2['desi_name']."</option>";
                                                } ?> 
                                              </select>
                                          </div>
                                    </div> 
                                </div> 
                                <div class="col-md-12 col-xs-12 reminder_div_<?php echo $agm_cnt;?>_0">
                                    <div class="col-md-5 col-xs-5">
                                        <div class="form-group">
                                            <label >Reminder</label>
                                            <select name="agm_reminder[<?php echo $agm_cnt;?>][remind_before][0]" class="form-control">
                                                <option value="">Select</option>   
                                                <?php
                                                    foreach ($agm_remin as $key3=>$val3) {  
                                                        echo "<option value='".$key3."' >".$val3."</option>";
                                                    }
                                                ?>                             
                                            </select> 
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6 col-xs-12 ">
                                        <div class="col-md-12 col-xs-12 no-padding">
                                            <div class="col-md-6 col-xs-6 no-padding">
                                                <label>Reminder Email</label>
                                            </div>
                                            <div class="col-md-3 col-xs-3 no-padding">                                            
                                                  <div class="checkbox" style="margin: 0;font-weight:bold;">
                                                    <label style="font-weight:bold;"><input name="agm_reminder[<?php echo $agm_cnt;?>][email_staff][0]" value="1" type="checkbox"  > Staff </label>
                                                  </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3 no-padding">                                            
                                                  <div class="checkbox" style="margin: 0;">
                                                    <label style="font-weight:bold;"><input name="agm_reminder[<?php echo $agm_cnt;?>][email_jmb][0]" value="1" type="checkbox"  > JMB / MC </label>
                                                  </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 no-padding">
                                            <div class="form-group">                                      
                                              <textarea name="agm_reminder[<?php echo $agm_cnt;?>][email_content][0]" class="form-control" rows="3" placeholder="Enter Reminder Email"></textarea>
                                            </div>
                                        </div>                                    
                                    </div>
                                    
                                    <div class="col-md-1 col-xs-1" style="padding-top: 50px;" >
                                        <a href="javascript:;" class="btn btn-success btn-circle add_reminder_btn" data-parent="<?php echo $agm_cnt;?>" data-value="0"><i class="fa fa-plus"></i></a>
                                    </div>
                                
                                </div>
                                           
                        
                        </div> <!-- /.row -->
                        
                    </div> <!-- /.row -->
               
               <?php } ?> 
               
                <div class="row add_agm_before_div">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-success add_agm_btn" id="add_agm_btn" value="0" data-value="<?php echo $agm_cnt;?>" aria-invalid="false">Add <?php echo $agm_types[$_GET['agm_type']];?> Checklist</button>
                    </div>
               
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button> &ensp;
                    <button type="Reset" id="reset_btn"  class="btn btn-default">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
            <?php } ?>  
            
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
var agm_reminder = $.parseJSON('<?php echo json_encode($agm_remin)?>');
var designations = $.parseJSON('<?php echo !empty($designations) ? json_encode($designations) : json_encode(array());?>');
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);   
    
    $('#reset_btn').click(function (){
       window.location.reload(); return false; 
    });
    
    $('.add_agm_btn').click(function () {        
        add_agm($(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.add_reminder_btn').click(function () {
        //console.log($(this).attr('data-parent') + ' = '+$(this).attr('data-value') + ' = '+$(this).val());
        add_reminder($(this).attr('data-parent'),$(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.del_agm_btn').bind('click',function (){
           $('.agm_div_'+$(this).attr('data-value')).remove(); 
        });
    
    $('.del_reminder_btn').bind('click',function (){
       $('.reminder_div_'+$(this).attr('data-parent')+'_'+$(this).attr('data-value')).remove(); 
    });
        
    
    function add_agm (agm_cnt) {
        var str = '<div class="row agm_div_'+(++agm_cnt)+'" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">';
        str += '<div class="row add_reminder_bottom" style="padding-top: 15px;">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="col-md-6 col-xs-6">';
        str += '<div class="form-group">';
        str += '<label><?php echo !empty($_GET['agm_type']) ? $agm_types[$_GET['agm_type']] : 'AGM';?> Checklist</label>';
        str += '<input name="agm[agm_descrip]['+agm_cnt+']" class="form-control" value="" placeholder="Enter <?php echo !empty($_GET['agm_type']) ? $agm_types[$_GET['agm_type']] : 'AGM';?> Checklist" maxlength="250" type="text">';
        str += '<input type="hidden" name="agm[agm_master_id]['+agm_cnt+']" value=""/>';                                
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-6 col-xs-12">';
        str += '<div class="form-group">';
        str += '<label> Role Responsibility</label>';                            
        str += '<select class="form-control" name="agm[agm_responsibility]['+agm_cnt+']">';                              
        str += '<option value="">Select</option>';
        $.each(designations,function (i, item) { 
            str += '<option value="'+item.desi_id+'">'+item.desi_name+'</option>';
        });
        str += '</select>';
        str += '</div>';
        str += '</div>'; 
        str += '</div>';                         
        str += '<div class="col-md-12 col-xs-12 reminder_div_'+agm_cnt+'_0">';
        str += '<div class="col-md-5 col-xs-5">';
        str += '<div class="form-group">';
        str += '<label>Reminder</label>';
        str += '<select name="agm_reminder['+agm_cnt+'][remind_before][0]" class="form-control">';
        str += '<option value="">Select</option>';   
        $.each(agm_reminder,function (i, item) { 
            str += '<option value="'+(i)+'">'+item+'</option>';
        });                             
        str += '</select>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-6 col-xs-12">';
        str += '<div class="col-md-12 col-xs-12 no-padding">';
        str += '<div class="col-md-6 col-xs-6 no-padding">';
        str += '<label>Reminder Email</label>';
        str += '</div>';
        str += '<div class="col-md-3 col-xs-3 no-padding"> ';                                           
        str += '<div class="checkbox" style="margin: 0;font-weight:bold;">';
        str += '<label style="font-weight:bold;"><input name="agm_reminder['+agm_cnt+'][email_staff][0]" value="1" type="checkbox"> Staff </label>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-3 col-xs-3 no-padding">';                                           
        str += '<div class="checkbox" style="margin: 0;">';
        str += '<label style="font-weight:bold;"><input name="agm_reminder['+agm_cnt+'][email_jmb][0]" value="1" type="checkbox"> JMB / MC </label>';
        str += '</div>'; 
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-12 col-xs-12 no-padding">';
        str += '<div class="form-group">';                                  
        str += '<textarea name="agm_reminder['+agm_cnt+'][email_content][0]" class="form-control" rows="3" placeholder="Enter Reminder Email"></textarea>';
        str += '</div>';
        str += '</div>';                                    
        str += '</div>';
        str += '<div class="col-md-1 col-xs-1" style="padding-top: 50px;">';
        str += '<a href="javascript:;" class="btn btn-success btn-circle add_reminder_btn" data-parent="'+agm_cnt+'" data-value="0"><i class="fa fa-plus"></i></a>';
        str += '</div>';
        str += '</div>';
        str += '</div> <!-- /.row -->';
        str += '<div class="col-md-12 text-right">';
        str +='<button type="button" class="btn btn-danger del_agm_btn" value="0" data-value="'+agm_cnt+'" aria-invalid="false">Delete <?php echo !empty($_GET['agm_type']) ? $agm_types[$_GET['agm_type']] : 'AGM';?> Checklist</button>';
        str +='</div>';
        str += '</div>';
        
        $('.add_agm_before_div').before(str);
        
        $('.add_reminder_btn').unbind('click');
        $('.add_reminder_btn').bind('click',function (){        
            //console.log($(this).attr('data-parent') + ' = '+$(this).attr('data-value') + ' = '+$(this).val());
            add_reminder($(this).attr('data-parent'),$(this).attr('data-value'));
            $(this).attr('data-value',eval($(this).attr('data-value'))+1);
        });
        
        $('.del_agm_btn').unbind('click');
        $('.del_agm_btn').bind('click',function (){
           $('.agm_div_'+$(this).attr('data-value')).remove(); 
        });    
        
    }
    
    function add_reminder (parent_id,ele_id) {
        console.log(parent_id + ' = '+ele_id );        
        var str = '';
        str += '<div class="col-md-12 col-xs-12 reminder_div_'+parent_id+'_'+(++ele_id)+'">';
        str += '<div class="col-md-5 col-xs-5">';
        str += '<div class="form-group">';
        str += '<label>Reminder</label>';
        str += '<select name="agm_reminder['+parent_id+'][remind_before]['+ele_id+']" class="form-control">';
        str += '<option value="">Select</option>';   
        $.each(agm_reminder,function (i, item) { 
            str += '<option value="'+(i)+'">'+item+'</option>';
        });                             
        str += '</select>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-6 col-xs-12">';
        str += '<div class="col-md-12 col-xs-12 no-padding">';
        str += '<div class="col-md-6 col-xs-6 no-padding">';
        str += '<label>Reminder Email</label>';
        str += '</div>';
        str += '<div class="col-md-3 col-xs-3 no-padding"> ';                                           
        str += '<div class="checkbox" style="margin: 0;font-weight:bold;">';
        str += '<label style="font-weight:bold;"><input name="agm_reminder['+parent_id+'][email_staff]['+ele_id+']" value="1" type="checkbox"> Staff </label>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-3 col-xs-3 no-padding">';                                           
        str += '<div class="checkbox" style="margin: 0;">';
        str += '<label style="font-weight:bold;"><input name="agm_reminder['+parent_id+'][email_jmb]['+ele_id+']" value="1" type="checkbox"> JMB / MC </label>';
        str += '</div>'; 
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-12 col-xs-12 no-padding">';
        str += '<div class="form-group">';                                  
        str += '<textarea name="agm_reminder['+parent_id+'][email_content]['+ele_id+']" class="form-control" rows="3" placeholder="Enter Reminder Email"></textarea>';
        str += '</div>';
        str += '</div>';                                    
        str += '</div>';
        str += '<div class="col-md-1 col-xs-1" style="padding-top: 50px;">';
        str += '<a href="javascript:;" class="btn btn-danger btn-circle del_reminder_btn" data-parent="'+parent_id+'" data-value="'+ele_id+'"><i class="fa fa-minus"></i></a>';
        str += '</div>';
        str += '</div>';
        console.log('.reminder_div_'+parent_id+'_'+(eval(ele_id)-1));
        console.log(parent_id + ' = '+ele_id );    
        //$('.reminder_div_'+parent_id+'_'+(eval(ele_id)-1)).insertAfter(str);
        $(str).insertAfter($('.reminder_div_'+parent_id+'_'+(eval(ele_id)-1)));
        
        $('.del_reminder_btn').unbind('click');
        $('.del_reminder_btn').bind('click',function (){
           $('.reminder_div_'+$(this).attr('data-parent')+'_'+$(this).attr('data-value')).remove(); 
        });
        
    }
});

$('#agm_type').change(function () {
    window.location.href="<?php echo base_url('index.php/bms_jmb_mc/agm_master');?>?agm_type="+$('#agm_type').val();
    return false;
});
</script>