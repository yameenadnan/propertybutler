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
                    <div class="box-header with-border" style="padding: 10px 0;">
                    <h3 class="box-title" style="font-weight: bold;">Routine Task</h3>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Property Name : </label>
                            <?php echo $sop[0]['property_name'];?>
                        </div>
                    </div>                   
                    
                    <div class="col-md-6 col-xs-12">
                        <div class="form-group">
                          <label>Assign To : </label>
                          <?php echo $sop[0]['desi_name'];?>
                        </div>
                    </div>
                                                                             
                    
                </div>
                
                <?php 
                    
                    if(!empty($sop)){
                        $r_task_schedule = $this->config->item('r_task_schedule');
                        foreach ($sop as $key=>$val) {
                    
                ?>
                
                
                
                <div class="row sop_div_<?php echo $val['sop_id'];?>" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;"><?php echo $val['sop_name'];?></h3>
                    </div>
                  
                  <div class="row">
                    <!--div class="col-md-6 col-xs-12">
                        <label>SOP Assignment Title : </label>
                        <?php echo $val['sop_name'];?>
                    </div-->
                    
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Start Date :</label>
                            <?php echo isset($val['start_date']) && $val['start_date'] != '0000-00-00' ? date('d-m-Y',strtotime($val['start_date'])) : ' - ';?>          
                            
                          </div>
                    </div> 
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label> Due Date : </label>
            
                            <?php   if(isset($val['no_due_date']) && $val['no_due_date'] == 1)
                                        echo 'No Due Date.';
                                    else 
                                        echo isset($val['due_date']) && $val['due_date'] != '0000-00-00'? date('d-m-Y',strtotime($val['due_date'])) : ' - ';?>
                          </div>
                    </div>  
                    
                    <div class="col-md-6 col-xs-12" style="margin-bottom: 0px;">
                        <div class="form-group">
                            <label> Task Scheduled : </label>
                            <?php echo !empty($r_task_schedule[$val['task_schedule']]) ? $r_task_schedule[$val['task_schedule']] : '';?>
                        </div><!-- /.form group -->
                        
                    </div>
                    
                    <div class="col-md-6 col-xs-12" style="margin-bottom: 0px;">
                    
                        <div class="form-group">
                            <label> Task Day(s) : </label>
                                
                                <?php   
                                $result_str = '';
                                if(isset($val['mon']) && $val['mon'] == 1)
                                    $result_str .= 'Mon';
                                if(isset($val['tue']) && $val['tue'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : ''). 'Tue';
                                if(isset($val['wed']) && $val['wed'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Wed';
                                if(isset($val['thu']) && $val['thu'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Thu';
                                if(isset($val['fri']) && $val['fri'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Fri';
                                if(isset($val['sat']) && $val['sat'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sat'; 
                                if(isset($val['sun']) && $val['sun'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sun';
                                echo $result_str;           
                            ?>
                         </div> 
                    
                    </div>
                    
                    <div class="col-md-6 col-xs-6">
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Execute Time : </label>
                                <?php echo isset($val['execute_time']) && $val['execute_time'] != '' ? date('h:i:s a',strtotime($val['execute_time'])) : ' - ';?>
                            </div><!-- /.form group -->
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-xs-6">
                        
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Due By : </label>
                              <?php echo isset($val['due_by']) && $val['due_by'] != '' ? date('h:i:s a',strtotime($val['due_by'])) : ' - ';?>                              
                            </div><!-- /.form group -->
                        </div>
                        
                    </div>
                    
                    
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                          <label>Reminder : </label>
                          <?php echo isset($val['reminder']) && $val['reminder'] != '' ? $val['reminder'].' Min(s)' : ' - ';?>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Repeat Timer : </label>            
                            <?php echo isset($val['repeat_rem']) && $val['repeat_rem'] != '' ? $val['repeat_rem'].' Min(s)' : ' - ';?>
                          </div>
                    </div>  
                    
                    <?php  ?>
                    <div class="col-md-6 col-xs-12" style="">
                        <div class="form-group">
                          <label>Requirement Type : </label>            
                            <?php echo isset($val['condition_req']) && $val['condition_req'] == '1' ? 'Condition Required ' : ' Reading Required ';?>
                          </div>
                    </div>
                    <?php  if ($mode == 'entry') { // Entry for SOP ?>
                    <div class="col-md-12 col-xs-12" style="padding: 0;margin-top: 10px;">
                        <!--div class="box-header with-border" style="padding: 15px 0 10px 15px; ">
                            <h3 class="box-title text-info" style="font-weight: bold;">SOP <?php echo $key+1;?> Entry:</h3>
                        </div-->
                        <div class="col-md-6 col-xs-12" >
                            <div class="form-group">
                              <?php if( isset($val['condition_req']) && $val['condition_req'] == '1' ) { ?>
                                <label> Condition : &ensp;</label>
                                  <div class="radio-inline">                                
                                      <input name="sop_<?php echo $val['sop_id'];?>" class="sop_<?php echo $val['sop_id'];?>" value="Y" type="radio"> Ok                                
                                  </div>
                                  <div class="radio-inline">                                
                                      <input name="sop_<?php echo $val['sop_id'];?>" class="sop_<?php echo $val['sop_id'];?>" value="N" type="radio"> Not Ok                                
                                  </div>
                             <?php } else { ?>
                                  <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;"> Reading : </label>
                                  <div class="col-xs-9 col-sm-10">
                                    <div class="input-group">
                                        <input type="text" name="reading" class="form-control inline sop_<?php echo $val['sop_id'];?>" placeholder="Enter Reading">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-camera"></i></button>
                                        </span>
                                    </div>
                                    <span class="text-danger" style="font-size: 10px;" >Last Reading:  - &ensp; &ensp;</span> &ensp; &ensp; 
                                    <span class="text-danger" style="font-size: 10px;" >Variance: - </span> 
                                  </div>                          
                              <?php } ?>
                              </div>
                        </div>
                        <div class="col-md-6 col-xs-12" style="">
                            <div class="form-group">                          
                                <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;">Remarks:</label>                            
                                <div class="col-xs-9 col-sm-10">
                                    <input type="text" name="remarks" class="form-control inline" placeholder="Enter Remarks">
                                </div>                              
                            </div>
                        </div>
                    </div>
                    
                    <?php } ?>
                    </div> <!-- /.row -->
                    <!-- Sub SOP tasks if any -->
                    <?php if(!empty($sub_sop[$key])) { // SUB SOP
                            
                            foreach ($sub_sop[$key] as $key2=>$val2) { ?>
                            
                            <div class="row sub_sop_sop_1 sub_sop_details" style="padding: 10px 0 5px 35px;">
                            	<div class="box-header with-border col-md-6 col-xs-12">
                            		<h3 class="box-title" style="font-weight: bold;">
                            			<?php echo $val2['sub_sop_name'];?>
                            		</h3>
                            	</div>
                            	<!--div class="col-md-6 col-xs-12">
                            		<label> Sub SOP Assignment Title : </label>
                            		<?php echo isset($val2['sub_sop_name']) && $val2['sub_sop_name'] != '' ? $val2['sub_sop_name'] : ' - ';?>
                            	</div-->
                                <div class="col-md-6 col-xs-12" style="">
                                    <div class="form-group">
                                      <label>Requirement Type : </label>            
                                        <?php echo isset($val2['condition_req']) && $val2['condition_req'] == '1' ? 'Condition Required ' : ' Reading Required ';?>
                                      </div>
                                </div>
                                <?php  if ($mode == 'entry') { // SUB SOP's ?>
                                <div class="col-md-12 col-xs-12" style="padding: 0;margin-top: 10px;">
                                    <!--div class="box-header with-border" style="padding: 15px 0 10px 15px; ">
                                        <h3 class="box-title text-info" style="font-weight: bold;">Sub SOP <?php echo $key2+1;?> Entry:</h3>
                                    </div-->
                                    <div class="col-md-6 col-xs-12" style="">
                                        <div class="form-group">
                                          <?php if( isset($val2['condition_req']) && $val2['condition_req'] == '1' ) { ?>
                                            <label> Condition : &ensp;</label>
                                              <div class="radio-inline">                                
                                                  <input name="optionsRadios" id="optionsRadios1" value="Y" type="radio"> Ok                                
                                              </div>
                                              <div class="radio-inline">                                
                                                  <input name="optionsRadios" id="optionsRadios2" value="N" type="radio"> Not Ok                                
                                              </div>
                                         <?php } else { ?>
                                              <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;"> Reading : </label>
                                              <div class="col-xs-9 col-sm-10">
                                                <div class="input-group">
                                                    <input type="text" name="reading" class="form-control inline" placeholder="Enter Reading">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-camera"></i></button>
                                                    </span>
                                                </div>
                                                <span class="text-danger" style="font-size: 10px;" >Last Reading:  - &ensp; &ensp;</span> &ensp; &ensp; 
                                                <span class="text-danger" style="font-size: 10px;" >Variance: - </span> 
                                              </div>                          
                                          <?php } ?>
                                          </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12" style="">
                                        <div class="form-group">                          
                                            <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;">Remarks:</label>                            
                                            <div class="col-xs-9 col-sm-10">
                                                <input type="text" name="remarks" class="form-control inline" placeholder="Enter Remarks">
                                            </div>                              
                                        </div>
                                    </div>
                                </div>
                                
                                <?php } ?>
                                
                             </div>
                                
                           <?php  } 
                    } 
                    
                    if($mode == 'entry') {
                    
                    ?>
                    
                        <div class="col-md-12 col-xs-12 text-right" style="padding: 15px;">
                            <button type="button" class="btn btn-success" value="" data-value="1">Save</button>
                        </div>
                    
                    <?php }
                    
                    if($mode == 'view') {
                    if(in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id'))) {
                    ?>
                    
                    <div class="col-md-12 col-xs-12 text-right" style="padding: 0 15px 5px 0;">                        
                        <button type="button" class="btn btn-success edit_btn" value="" data-value="<?php echo $val['sop_id'];?>" data-propertyid="<?php echo $sop[0]['property_id'];?>" data-assignto="<?php echo $sop[0]['assign_to'];?>">Edit Task</button> &nbsp; 
                        <button type="button" class="btn btn-danger delete_sop_btn" value="" data-value="<?php echo $val['sop_id'];?>">Delete Task</button>
                    </div>
                    
                    <?php }
                    } ?>
                  
            </div>
                
                    
                <?php 
                
                        }
                } ?>
                              
                
              </div>
              <!-- /.box-body -->
              <!--div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="button" class="btn btn-primary"  onclick="window.history.go(-1); return false;" _onclick="window.location.href='<?php echo base_url('index.php/bms_sop/sop_list');?>'">Back</button> &ensp;                    
                  </div>
                </div>
              </div-->
            </form>
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div><!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">SOP History</h4>
      </div>
      <div class="modal-body modal-body2">
        
        <div class="xol-xs-12 msg">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="xol-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('.delete_sop_btn').click(function(){
        
        console.log($(this).attr('data-value'));
        
        if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
            var sop_id = $(this).attr('data-value');
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_sop/delete_sop');?>/'+sop_id, // Reusing the same function from task creation
                data: {'sop_id':sop_id},
                //datatype:"json", // others: xml, json; default is html
                beforeSend:function (){ $('.sop_div_'+sop_id).LoadingOverlay("show"); }, //
                success: function(data) {
                    $('.sop_div_'+sop_id).LoadingOverlay("hide", true);
                    if(data == 1) {
                        $('.sop_div_'+sop_id).remove();                                                           
                    }
                },
                error: function (e) {  
                    $('.sop_div_'+sop_id).LoadingOverlay("hide", true);
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
        //sop_div_
  
      	/*$('.modal-body2').load('<?php echo base_url('index.php/bms_sop/get_sop_history/');?>',function(result){
    	    $('#myModal2').modal({show:true});
    	});*/
    });
    
    $('.edit_btn').click( function () {
        window.location.href="<?php echo base_url('index.php/bms_sop/new_sop');?>/"+$(this).attr('data-value')+"?property_id="+$(this).attr('data-propertyid')+"&assign_to="+$(this).attr('data-assignto');
        return false;
    });
    
});
</script>