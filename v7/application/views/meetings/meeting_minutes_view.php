<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
    <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
        <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_meetings/meeting_minutes_submit');?>" method="post" enctype="multipart/form-data">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="row">
        
            <div class="col-md-12 col-xs-12">
                <div class="box box-primary">
                
                    <!--div class="box-header with-border" style="padding-left:15px ;">
                      <h3 class="box-title"><b>Meeting Details</b></h3>
                    </div-->
                    
                    <div class="box-body">
                    <div class="col-md-12 col-sm-6 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Property Name *</label>
                                <select class="form-control" id="property_id"  <?php echo !empty($meetings_info) ? 'disabled="disabled"' : 'name="meetings[property_id]"';?> >
                                    <option value="">Select Property</option>
                                    <?php 
                                        foreach ($properties as $key=>$val) { 
                                            $selected = isset($meetings_info['property_id']) && $meetings_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                            $selected .= ' data-calc-base="'.(!empty($val['calcul_base']) ? $val['calcul_base'] : '').'"';
                                            $selected .= ' data-sinking-fund="'.(isset($val['sinking_fund']) && $val['sinking_fund'] != '' ? $val['sinking_fund'] : '').'"';
                                            $selected .= ' data-tot-sq-feet="'.(isset($val['tot_sq_feet']) && $val['tot_sq_feet'] != '' ? $val['tot_sq_feet']: '').'"';
                                            $selected .= ' data-per-sq-feet="'.(isset($val['per_sq_feet']) && $val['per_sq_feet'] != '' ? $val['per_sq_feet']: '').'"';
                                            $selected .= ' data-tot-share-unit="'.(isset($val['tot_share_unit']) && $val['tot_share_unit'] != '' ? $val['tot_share_unit'] : '').'"';
                                            $selected .= ' data-per-share-unit="'.(isset($val['per_share_unit']) && $val['per_share_unit'] != '' ? $val['per_share_unit'] : '').'"';
                                            $selected .= ' data-insurance-prem="'.(isset($val['insurance_prem']) && $val['insurance_prem'] != '' ? $val['insurance_prem'] : '').'"';
                                            $selected .= ' data-quit-rent="'.(isset($val['quit_rent']) && $val['quit_rent'] != '' ? $val['quit_rent'] : '').'"';
                                            
                                            echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                                </select>
                                <?php if(!empty($meetings_info)) { echo '<input type="hidden" name="meetings[property_id]" value="'.$meetings_info['property_id'].'">'; } ?>
                                <input type="hidden" id="meeting_id" name="meetings[meeting_id]" value="<?php echo $meeting_id;?>"/>
                            </div>
                           
                        </div>  
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Meeting Description *</label>
                                <input type="text" name="meetings[meeting_descrip]" class="form-control" value="<?php echo isset($meetings_info['meeting_descrip']) && $meetings_info['meeting_descrip'] != '' ? $meetings_info['meeting_descrip'] : '';?>" placeholder="Enter Meeting Description" maxlength="250">                                
                            </div>
                           
                        </div>                           
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Venue *</label>
                                <input type="text" name="meetings[meeting_venue]" class="form-control" value="<?php echo isset($meetings_info['meeting_venue']) && $meetings_info['meeting_venue'] != '' ? $meetings_info['meeting_venue'] : '';?>" placeholder="Enter Venue" maxlength="200">
                            </div>
                           
                        </div> 
                        <div class="col-md-3 col-sm-6 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Date *</label>
                                
                              <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right datepicker" name="meetings[meeting_date]" value="<?php echo isset($meetings_info['meeting_date']) && $meetings_info['meeting_date'] != '' && $meetings_info['meeting_date'] != '0000-00-00' && $meetings_info['meeting_date'] != '1970-01-01' ? date('d-m-Y', strtotime($meetings_info['meeting_date'])) : '';?>" type="text">
                                    </div>                                
                            </div>                           
                        </div> 
                        
                        <div class="col-md-3 col-sm-6 col-xs-12 "> 
                            <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                      <label>Time *</label>
                                      <div class="input-group">
                                        <input type="text" name="meetings[meeting_time]" value="<?php echo isset($meetings_info['meeting_time']) && $meetings_info['meeting_time'] != '' ? date('h:i A', strtotime($meetings_info['meeting_time'])) : '';?>" class="form-control timepicker">
                    
                                        <div class="input-group-addon">
                                          <i class="fa fa-clock-o"></i>
                                        </div>
                                      </div><!-- /.input group -->
                                      
                                    </div><!-- /.form group -->
                                </div>
                           
                        </div>                           
                    </div>
                    
                    <div class="col-md-12 col-sm-6 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                            
                            <div class="form-group">
                              <label >Agendas to be discussed *</label>
                              <textarea rows="5" class="form-control" name="meetings[agenda_to_discuss]" placeholder="Enter Agendas to be discussed"><?php echo !empty($meetings_info['agenda_to_discuss']) ? $meetings_info['agenda_to_discuss'] : '';?></textarea>
                                
                            </div>
                           
                        </div>  
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 minor_task_div" style="<?php echo !empty($minot_task) ? '' : 'display: none;';?>">
                            
                            <div class="form-group">
                              <label >Agendas to be discussed from Minor Task</label>
                            	<?php if(!empty($minot_task)) { 
                                    $discuss_task_id = !empty($meetings_info['discuss_task_id']) ? explode(',',$meetings_info['discuss_task_id']) : array();
                                    foreach ($minot_task as $key=>$val) {
                                        echo '<div class="form-group"><div class="checkbox"><label>';
                                        echo '<input name="minor_task[]" value="'.$val['task_id'].'" '.(in_array($val['task_id'],$discuss_task_id) ? 'checked="checked"' : '').' type="checkbox"> ';
                                        echo '<a target="_blank" href="'.base_url('index.php/bms_task/task_details/').$val['task_id'].'">'.$val['task_name'] .'</a>';
                                        echo ' </label></div></div>';                                        
                                    }
                            }?>
                                
                            </div>
                           
                        </div>                           
                    </div>
                    
                    <!--div class="col-md-12 col-sm-12 col-xs-12 no-padding"></div-->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding extenal_attende_div" style="border: 2px solid #f4f4f4;border-radius: 5px;">
                        <div class="box-header with-border" style="padding-left:15px ;">
                          <h3 class="box-title"><b>Meeting Attendes</b></h3>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 staff_div" style="padding-bottom:15px;border-right: 2px solid #f4f4f4;">
                            <label class="with-border">Building Staffs</label>
                            <?php if(!empty($staff)) { 
                                    $staff_attende = !empty($meetings_info['staff_attende']) ? explode(',',$meetings_info['staff_attende']) : array();
                                    foreach ($staff as $key=>$val) {
                                        echo '<div class="form-group"><div class="checkbox"><label>';
                                        echo '<input name="building_staff[]" value="'.$val['staff_id'].'" '.(in_array($val['staff_id'],$staff_attende) ? 'checked="checked"' : '').' type="checkbox"> ';
                                        echo $val['first_name']. (!empty($val['last_name']) ? ' '.$val['last_name'] : '' ) .' - '. $val['desi_name'];
                                        echo ' </label></div></div>';                                        
                                    }
                            }?>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 jmb_mc_div" style="padding-bottom:15px;border-left: 2px solid #f4f4f4;left: -2px;">
                            <label class="with-border">JMB / MC Members</label>
                            <?php if(!empty($jmb)) { 
                                    $jmb_attende = !empty($meetings_info['jmb_attende']) ? explode(',',$meetings_info['jmb_attende']) : array();
                                    foreach ($jmb as $key=>$val) {
                                        echo '<div class="form-group"><div class="checkbox"><label>';
                                        echo '<input name="jmb_member[]" value="'.$val['member_id'].'" '.(in_array($val['member_id'],$jmb_attende) ? 'checked="checked"' : '').' type="checkbox"> ';
                                        echo $val['first_name'].' - '. $val['jmb_desi_name'];
                                        echo ' </label></div></div>';                                        
                                    }
                            }?>
                        </div>
                        
                        <?php if(!empty($externals)) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12" style="border-top: 2px solid #f4f4f4;">
                                <label class="with-border">External(s)</label><br />
                                <?php 
                                    foreach ($externals as $key=>$val) {
                                        echo '<div class="form-group"><div class="checkbox"><label>';
                                        echo '<input name="jmb_member[]" value="'.$val['meetings_attend_oth_id'].'" checked="checked" type="checkbox"> ';
                                        echo (!empty($val['name']) ? $val['name'] : '') . (!empty($val['person_name']) ? (!empty($val['name']) ? ' - ' : ''). $val['person_name'] : '');
                                        echo ' </label></div></div>';                                        
                                    }
                                
                                ?>
                                
                            </div>  
                        <?php } ?>
                        
                        
                    </div>
                    <?php $meeting_cnt = 0;?>
                    <div class="col-md-12 minutes_list_div_<?php echo ++$meeting_cnt;?>" style="margin: 10px 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                        
                        <div class="row add_reminder_bottom" style="padding-top: 15px;">
                            <div class="col-md-12 col-xs-12">
                            
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label >Minutes Title</label>
                                        <input type="text" name="meeting_minutes[minutes_title][]" class="form-control" value="<?php echo !empty($meeting_minutes[0]['minutes_title']) ? $meeting_minutes[0]['minutes_title'] : '';?>" placeholder="Enter Minutes Title" maxlength="250">
                                        <input type="hidden" name="meeting_minutes[meeting_minutes_id][]" value="<?php echo !empty($meeting_minutes[0]['meeting_minutes_id']) ? $meeting_minutes[0]['meeting_minutes_id'] : '';?>"/>                                                                        
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Minutes Description</label>                            
                                        <textarea rows="5" class="form-control" name="meeting_minutes[minutes_descrip][]" placeholder="Enter Minutes Description"><?php echo !empty($meeting_minutes[0]['minutes_descrip']) ? $meeting_minutes[0]['minutes_descrip'] : '';?></textarea>
                                      </div>
                                </div>                        
                                    
                                <div class="col-md-4 col-sm-4 col-xs-12 ">                                    
                                    <div class="form-group">
                                      <label >Action By</label>
                                        <input type="text" name="meeting_minutes[action_by][]" class="form-control" value="<?php echo isset($meeting_minutes[0]['action_by']) && $meeting_minutes[0]['action_by'] != '' ? $meeting_minutes[0]['action_by'] : '';?>" placeholder="Enter Action By" maxlength="100">
                                    </div>                                   
                                </div> 
                                <div class="col-md-8 col-sm-8 col-xs-12 ">                                    
                                    <div class="form-group">
                                      <label >Progress</label>
                                      <textarea rows="3" class="form-control" name="meeting_minutes[progress][]" placeholder="Enter Progress"><?php echo !empty($meeting_minutes[0]['progress']) ? $meeting_minutes[0]['progress'] : '';?></textarea>
                                    </div>                           
                                </div>
                            </div> 
                        </div> <!-- /.row -->
                    </div> <!-- /.col-md-12 -->
                    
                    <?php if(!empty($meeting_minutes) && count($meeting_minutes)>1) {
                            $mm_cnt = count ($meeting_minutes);
                            for($i=1;$i < $mm_cnt;$i++) { ?>
                            
                            <div class="col-md-12 minutes_list_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                        
                            <div class="row add_reminder_bottom" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12">
                                
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label >Minutes Title</label>
                                            <input type="text" name="meeting_minutes[minutes_title][]" class="form-control" value="<?php echo !empty($meeting_minutes[$i]['minutes_title']) ? $meeting_minutes[$i]['minutes_title'] : '';?>" placeholder="Enter Minutes Title" maxlength="250">
                                            <input type="hidden" name="meeting_minutes[meeting_minutes_id][]" value="<?php echo !empty($meeting_minutes[$i]['meeting_minutes_id']) ? $meeting_minutes[$i]['meeting_minutes_id'] : '';?>"/>                                                                        
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Minutes Description</label>                            
                                            <textarea rows="5" class="form-control" name="meeting_minutes[minutes_descrip][]" placeholder="Enter Minutes Description"><?php echo !empty($meeting_minutes[$i]['minutes_descrip']) ? $meeting_minutes[$i]['minutes_descrip'] : '';?></textarea>
                                          </div>
                                    </div> 
                                
                                        
                                    <div class="col-md-4 col-sm-12 col-xs-12 ">
                                        
                                        <div class="form-group">
                                          <label >Action By</label>
                                            <input type="text" name="meeting_minutes[action_by][]" class="form-control" value="<?php echo isset($meeting_minutes[$i]['action_by']) && $meeting_minutes[$i]['action_by'] != '' ? $meeting_minutes[$i]['action_by'] : '';?>" placeholder="Enter Action By" maxlength="100">
                                        </div>
                                       
                                    </div> 
                                    <div class="col-md-8 col-sm-6 col-xs-12 ">
                                        
                                        <div class="form-group">
                                          <label >Progress</label>
                                          <textarea rows="3" class="form-control" name="meeting_minutes[progress][]" placeholder="Enter Progress"><?php echo !empty($meeting_minutes[$i]['progress']) ? $meeting_minutes[$i]['progress'] : '';?></textarea>
                                        </div>                           
                                    </div>
                                </div> 
                                    
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-danger del_meeting_btn" value="0" data-value="1" aria-invalid="false">Delete Minutes</button>
                                </div>
                                    
                            </div> <!-- /.row -->
                        </div> <!-- /.col-md-12 -->
                    
                    <?php
                            }
                        } ?>
                    
                    <div class="col-md-12 add_meeting_before_div" style="padding-right:0">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-success add_meeting_btn" id="add_meeting_btn" value="0" data-value="<?php echo $meeting_cnt;?>" aria-invalid="false">Add Minutes</button>
                        </div>
                   
                    </div>        
                             
                    <div class="col-md-12 no-padding text-right">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save</button> &ensp;&ensp;
                            <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;                        
                        </div>
                    </div>                               
                    
                    </div><!-- /.box-body -->   
                 
                 </div><!-- /.box-primary -->  
                  
            
            </div>
            
        </div><!-- /.row --> 
        
        </form>  
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>
var meeting_remin = $.parseJSON('<?php echo json_encode($meeting_remin)?>');
var designations = $.parseJSON('<?php echo !empty($designations) ? json_encode($designations) : json_encode(array());?>');

$(document).ready(function () { 
    
    $('.msg_notification').fadeOut(5000);
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"meetings[property_id]": "required",
            "meetings[meeting_descrip]": "required",
            "meetings[meeting_venue]": "required",
            "meetings[meeting_date]": "required",
            "meetings[meeting_time]": "required",
            "meetings[agenda_to_discuss]": "required"            
		},
		messages: {
			"meetings[property_id]": "Please select Property Name",
            "meetings[meeting_descrip]": "Please enter Meeting Description ",
            "meetings[meeting_venue]": "Please enter Venue",
            "meetings[meeting_date]": "Please select Date",
            "meetings[meeting_time]": "Please select Time",
            "meetings[agenda_to_discuss]": "Please enter Agendas to be discussed"            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.hasClass( "datepicker" ) ) {
				error.insertAfter( element.parent( "div" ) );
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
    
    // On property name change
    $('#property_id').change(function () { 
        if($('#property_id').val() != '') {
            loadAttendes ('');
        }        
    });
    
    $('.btn-add-other').click(function () {
        addExtenalAttende ();    
    });
        
    $('.btn-remov-other').bind('click',function (){
       $(this).parents('div.more_extenal_attende').remove(); 
    });
    
    $('.add_meeting_btn').click(function () {        
        add_checklist($(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.add_reminder_btn').click(function () {
        //console.log($(this).attr('data-parent') + ' = '+$(this).attr('data-value') + ' = '+$(this).val());
        add_reminder($(this).attr('data-parent'),$(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.del_meeting_btn').bind('click',function (){
       //$('.meeting_chk_list_div_'+$(this).attr('data-value')).remove();
       $(this).parents('div.minutes_list_div').remove();  
    });
    
    $('.del_reminder_btn').bind('click',function (){
       $('.reminder_div_'+$(this).attr('data-parent')+'_'+$(this).attr('data-value')).remove(); 
    });
    
    
    function add_checklist (meeting_cnt) {
        var str = '<div class="col-md-12 minutes_list_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">';
        str += '<div class="row add_reminder_bottom" style="padding-top: 15px;">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="form-group">';
        str += '<label >Minutes Title</label>';
        str += '<input type="text" name="meeting_minutes[minutes_title][]" class="form-control" value="" placeholder="Enter Minutes Title" maxlength="250">';
        str += '<input type="hidden" name="meeting_minutes[meeting_minutes_id][]" value=""/>                                                                        ';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="form-group">';
        str += '<label>Minutes Description</label>                            ';
        str += '<textarea rows="5" class="form-control" name="meeting_minutes[minutes_descrip][]" placeholder="Enter Minutes Description"></textarea>';
        str += '</div>';
        str += '</div> ';
        str += '<div class="col-md-4 col-sm-4 col-xs-12 ">';
        str += '<div class="form-group">';
        str += '<label >Action By</label>';
        str += '<input type="text" name="meeting_minutes[action_by][]" class="form-control" value="" placeholder="Enter Action By" maxlength="100">';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-8 col-sm-8 col-xs-12 ">';
        str += '<div class="form-group">';
        str += '<label >Progress</label>';
        str += '<textarea rows="3" class="form-control" name="meeting_minutes[progress][]" placeholder="Enter Progress"></textarea>';
        str += '</div>';
        str += '</div>';        
        str += '</div>';
        str += '</div>';
        //str += '</div>';
        str += '<div class="col-md-12 text-right">';
        str +='<button type="button" class="btn btn-danger del_meeting_btn" value="0" data-value="'+meeting_cnt+'" aria-invalid="false">Delete Minutes</button>';
        str +='</div>';
        str += '</div>';
        
        $('.add_meeting_before_div').before(str);
        
        
        
        $('.del_meeting_btn').unbind('click');
        $('.del_meeting_btn').bind('click',function (){
           //$('.meeting_chk_list_div_'+$(this).attr('data-value')).remove();
           $(this).parents('div.minutes_list_div').remove();  
        });    
        
    }
    
    
             
});

function loadAttendes () {
    
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_meetings/attendes_list');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                console.log(data);
                var sstr = '';
                var jstr = '';
                var mt_str = '';
                var desi_str = '';
                if(data.staff.length > 0) { 
                    sstr = '<label class="with-border">Building Staffs</label>';
                    $.each(data.staff,function (i, item) {
                        //var selected = block_id != '' && block_id == item.block_id ? 'selected="selected"' : '';
                        //str += '<option value="'+item.block_id+'" >'+item.block_name+'</option>';
                        sstr += '<div class="form-group">';
                        sstr += '<div class="checkbox">';
                        sstr += '<label><input name="building_staff[]" value="'+item.staff_id+'" type="checkbox" > '+item.first_name+' '+item.last_name+' - '+item.desi_name+'  </label>';
                        sstr += '</div>';
                        sstr += '</div>';                        
                    });
                }
                if(data.jmb.length > 0) {  
                    jstr = '<label class="with-border">JMB / MC Members</label>';
                    $.each(data.jmb,function (i, item) {
                        //var selected = block_id != '' && block_id == item.block_id ? 'selected="selected"' : '';
                        //str += '<option value="'+item.block_id+'" >'+item.block_name+'</option>';
                        jstr += '<div class="form-group">';
                        jstr += '<div class="checkbox">';
                        jstr += '<label><input name="jmb_member[]" value="'+item.member_id+'" type="checkbox" > '+item.first_name+ ' - '+item.jmb_desi_name+'  </label>';
                        jstr += '</div>';
                        jstr += '</div>';                        
                    });
                }
                if(data.minot_task.length > 0) {  
                    mt_str = '<label >Agendas to be discussed from Minor Task</label>';
                    $.each(data.minot_task,function (i, item) {
                        //var selected = block_id != '' && block_id == item.block_id ? 'selected="selected"' : '';
                        //str += '<option value="'+item.block_id+'" >'+item.block_name+'</option>';
                        mt_str += '<div class="form-group">';
                        mt_str += '<div class="checkbox">';
                        mt_str += '<label><input name="minor_task[]" value="'+item.task_id+'" type="checkbox" > <a target="_blank" href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'" >'+item.task_name+ ' </a> </label>';
                        mt_str += '</div>';
                        mt_str += '</div>';
                        
                    });
                    $('.minor_task_div').css('display','block');
                } else {
                    $('.minor_task_div').css('display','none');
                }
                desi_str = '<option value="">Select</option>';
                designations = data.designations;
                if(designations.length > 0) { 
                    
                    $.each(data.designations,function (i, item) {
                        desi_str += '<option value="'+item.desi_id+'">'+item.desi_name+'</option>';
                    });
                } 
                
                //role_resposibility
                
                $('.staff_div').html(sstr);
                $('.jmb_mc_div').html(jstr);
                $('.minor_task_div').html(mt_str);
                $('.role_resposibility').html(desi_str);
                $("#content_area").LoadingOverlay("hide", true);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

function addExtenalAttende () {
    var str  = '<div class="col-md-12 col-sm-12 col-xs-12 more_extenal_attende">';
    str += '<div class="col-md-3 col-sm-6 col-xs-12 " style="padding-left: 0;">';
    str += '<div class="form-group">';
    str += '<input type="text" name="meetings_attend_oth[name][]" class="form-control" value="" placeholder="Enter Company/Person Name" maxlength="150">';
    str += '<input type="hidden" name="meetings_attend_oth[meetings_attend_oth_id][]" value="">';
    str += '</div>';
    str += '</div>';
    str += '<div class="col-md-3 col-sm-6 col-xs-12 " style="padding-left: 0;">';
    str += '<div class="form-group">';
    str += '<input type="text" name="meetings_attend_oth[email_addr][]" class="form-control" value="" placeholder="Enter Email Address" maxlength="150">';
    str += '</div>';
    str += '</div>';
    str += '<div class="col-md-3 col-sm-6 col-xs-12 " style="padding-left: 0;">';
    str += '<div class="form-group">';
    str += '<input type="text" name="meetings_attend_oth[contact_no][]" class="form-control" value="" placeholder="Enter Contact No." maxlength="50">';
    str += '</div>';
    str += '</div>';
    str += '<div class="col-md-3 col-sm-6 col-xs-12 " style="padding-left: 0;">';
    str += '<div class="input-group">';
    str += '<input type="text" name="meetings_attend_oth[person_name][]" class="form-control" value="" placeholder="Enter Contact Person Name" maxlength="150">';
    str += '<span class="input-group-btn" style="left: 15px;">';
    str += '<button class="btn btn-danger btn-remov-other" type="button">';
    str += '<span class="glyphicon glyphicon-minus"></span>';
    str += '</button>';
    str += '</span>';
    str += '</div>';
    str += '</div>';
    str += '</div>';
    
    $('.extenal_attende_div').append(str);
    $('.btn-remov-other').unbind('click');
    $('.btn-remov-other').bind('click',function (){
       $(this).parents('div.more_extenal_attende').remove(); 
    }); 
    //more_extenal_attende
}



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
    
});
</script>