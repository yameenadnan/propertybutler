<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>

<?php
    $today_date = new DateTime(date('Y-m-d'));
    $warranty_due_date = !empty($asset_info['warranty_due']) && !in_array($asset_info['warranty_due'], array('0000-00-00','1970-01-01')) ? new DateTime($asset_info['warranty_due']) : '';
    
    $service_provider = array();
    $warranty_status = 0;
    if(!empty($asset_info['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp)) {
        $service_provider = $asset_info;
        $warranty_status = 1;
    } else if (!empty($asset_maint_comp)){
        $service_provider = $asset_maint_comp[0];        
    }
    $total_schedule_cnt = $total_un_schedule_cnt = $service_type_arr = array();
    
    $asset_service_name = $this->config->item('asset_service_name');
    $asset_service_period = $this->config->item('asset_service_period');
    
    if(!empty($service_provider) && !empty($service_type)){
        $i=0;

        /*echo '<pre>';
        print_r ( $asset_service_name );
        echo '</pre>';

        echo '<pre>';
        print_r ( $asset_service_period );
        echo '</pre>';

        echo '<pre>';
        print_r ( $service_type );
        echo '</pre>';*/

        foreach ($service_type as $stval) {
            $service_type_arr [$i] =  $asset_service_name[$stval['service_name']].'-'.$asset_service_period[$stval['service_period']];
            $total_schedule_cnt[$i] = 0;
            $last_schedule_date = $this->bms_property_model->getServiceLastScheduleDate($asset_info['asset_id'],$stval['service_name'],$stval['service_period']);
            if(!empty($last_schedule_date)) {
                //echo "<pre>";print_r($service_provider['warranty_due']);echo "</pre>";
                
                
                $total_schedule_cnt[$i] = $this->bms_property_model->getServiceScheduleCountBetweenDate($asset_info['asset_id'],$stval['service_name'],$stval['service_period'],$service_provider['warranty_start'],$service_provider['warranty_due']);
                
                /*$datetime1 = new DateTime($service_provider['warranty_due']);
                $datetime2 = new DateTime($last_schedule_date['service_date']);            
                $difference = $datetime1->diff($datetime2);
                echo $diff_days = $difference->d;*/
                $start = strtotime($last_schedule_date['service_date']);
                $end = strtotime($service_provider['warranty_due']);
                
                $diff_days = floor(abs($end - $start) / 86400);
                
                if($diff_days >= 0) {
                    $service_period_days = $this->config->item('asset_service_period_days');
                    $total_un_schedule_cnt[$i] = floor($diff_days/$service_period_days[$stval['service_period']]);                    
                }
                
            } else {
                $total_un_schedule_cnt[$i] = '-';
            }
            $i++;
        }
    }
    $date_picker_end_date = !empty($service_provider['warranty_due'] && $service_provider['warranty_due'] != '0000-00-00' ) ? "endDate: new Date('".$service_provider['warranty_due']."')," : "";

?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
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
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    
                                    <div class="form-group">
                                      <label >Property Name </label>
                                        <select class="form-control" id="property_id" name="asset[property_id]" disabled="disabled">
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = isset($asset_info['property_id']) && $asset_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        <input type="hidden" id="asset_id" name="asset_id" value="<?php echo $asset_id;?>"/>
                                        <input type="hidden" id="warranty_status" name="warranty_status" value="<?php echo $warranty_status;?>"/>
                                        
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Asset Name </label>
                                          <input type="text" name="asset[asset_name]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['asset_name']) && $asset_info['asset_name'] != '' ? $asset_info['asset_name'] : '';?>" placeholder="Enter Asset Name" maxlength="255">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <label >Asset Description</label>
                                          <textarea name="asset[asset_descri]" class="form-control" rows="2" disabled="disabled"  ><?php echo isset($asset_info['asset_descri']) && $asset_info['asset_descri'] != '' ? $asset_info['asset_descri'] : '';?></textarea> 
                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="">
                                    <div class="col-md-6 col-sm-6 col-xs-6 ">
                                        <div class="form-group">
                                          <label >Asset Location</label>
                                            <input type="text" name="asset[asset_location]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['asset_location']) && $asset_info['asset_location'] != '' ? $asset_info['asset_location'] : '';?>"  maxlength="150">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6"  >
                                        <div class="form-group">
                                          <label >Serial No.</label>
                                          <input type="text" name="asset[serial_no]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['serial_no']) && $asset_info['serial_no'] != '' ? $asset_info['serial_no'] : '';?>"  maxlength="100">  
                                        </div>
                                    </div>
                                </div>
                                
                                <!--div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Supplier Name</label>
                                          <input type="text" name="asset[supplier_name]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['supplier_name']) && $asset_info['supplier_name'] != '' ? $asset_info['supplier_name'] : '';?>"   maxlength="255">
                                        </div>
                                    </div>
                                </div-->
                                <?php if(!empty($service_provider)) {          ?>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-4 col-xs-12 ">
                                        <div class="form-group">
                                            <label>Service Type</label>
                                          <select name="service_type" id="service_type" class="form-control">
                                                <option value="">Select</option>   
                                                <?php                                                 
                                                    
                                                    foreach ($service_type as $key=>$val) { 
                                                        //$selected = isset($val['service_name']) && in_array($val['service_name']) ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$val['service_name'].'-'.$val['service_period']."' >".$asset_service_name[$val['service_name']]." - ".$asset_service_period[$val['service_period']]."</option>";
                                                    }
                                                    ?>                                                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-4 col-xs-12 ">
                                        <div class="form-group">
                                            <label >Servicing Date</label>
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" id="schedule_date" name="schedule_date" value="" type="text">
                                            </div>
                                        </div>
                                   </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-4 col-xs-12 ">
                                        <div class="form-group">
                                          <label>Service Reminder</label>
                                          <select name="service_reminder" id="service_reminder" class="form-control">
                                              <option value="">Select</option>
                                              <?php
                                              $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                                              foreach ($asset_warranty_remin as $key=>$val) {
                                                  $selected = isset($renewal_info['remind_before']) && trim($renewal_info['remind_before']) == $key ? 'selected="selected" ' : '';
                                                  echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                              }
                                              ?>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-4 col-xs-12 text-right no-padding" style=" padding-top: 25px !important;">
                                        <button type="button" class="btn btn-primary service_schedule_add_btn">Add</button> &ensp;&ensp;
                                    </div>
                                </div>




                                <?php  if(!empty($total_schedule_cnt)) { 
                                        foreach ($total_schedule_cnt as $schKey=>$schVal) { ?>
                                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                                                    <span class="text-success">Scheduled <?php echo $service_type_arr[$schKey]?>: <b><span  ><?php echo $total_schedule_cnt[$schKey]; ?></span></b></span> &ensp;
                                                    <span class="text-danger">Unscheduled <?php echo $service_type_arr[$schKey]?>: <b><span ><?php echo $total_un_schedule_cnt[$schKey]; ?></span></b></span>
                                                </div>
                                            </div>
                                        <?php }
                                        } ?>
                                
                                <?php } ?>
                                
                            </div>
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box " style="padding-left:0px;-border: 1px solid #999;border-radius: 5px;">
                                
                                
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <!--div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"><?php echo !empty($asset_info['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Supplier Details ' : 'Maintenance Company Details';?>&nbsp; </span></h5></div-->
                                    <div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"><?php echo 'Maintenance Company Details';?>&nbsp; </span></h5></div>
                                </div>  
                                <?php if(empty($service_provider)) {                                
                                        echo '<div class="col-md-12 col-sm-12 col-xs-12 no-padding" ><div class="alert alert-warning msg_notification2" style="margin-top:5px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                                        echo '</strong>In order to schedule service, you need to Add / Update maintenance company information!</div></div>';
                                } else { ?>
                                
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Warranty Start Date ' : 'Maintenance Contract Start Date';?>:  </label>
                                        <?php echo !empty($service_provider['warranty_start'])  ? date('d-m-Y',strtotime($service_provider['warranty_start'])) : ' - ';?> 
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Warranty Due Date ' : 'Maintenance Contract Due Date';?>:  </label>
                                        <?php echo !empty($service_provider['warranty_due'])  ? date('d-m-Y',strtotime($service_provider['warranty_due'])) : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Remind Before:  </label>
                                        <?php  
                                        $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                                        echo !empty($service_provider['remind_before'])  ? $asset_warranty_remin[$service_provider['remind_before']]  : ' - ';?> 
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Supplier Name' : 'Maintenance Company Name';?>:  </label>
                                        <?php echo !empty($service_provider['supplier_name'])  ? $service_provider['supplier_name'] : ' - ';?> 
                                    </div>
                                </div>
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Company Address:  </label>
                                        <?php  
                                        $countries = $this->config->item('countries');
                                        $address = !empty($service_provider['address'])  ? $service_provider['address'] : '';
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['city'])  ? $service_provider['city'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['postcode'])  ? $service_provider['postcode'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['state'])  ? $service_provider['state'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['country'])  ? $countries[$service_provider['country']] : '');
                                        echo $address != '' ? $address : ' - ';
                                        ?>
                                         
                                    </div>
                                </div> 
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Office Number:  </label>
                                        <?php echo !empty($service_provider['office_ph_no'])  ? $service_provider['office_ph_no'] : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Name:  </label>
                                        <?php echo !empty($service_provider['person_incharge'])  ? $service_provider['person_incharge'] : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Number:  </label>
                                        <?php echo !empty($service_provider['person_inc_mobile'])  ? $service_provider['person_inc_mobile'] : ' - ';?> 
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Email:  </label>
                                        <?php echo !empty($service_provider['person_inc_email'])  ? $service_provider['person_inc_email'] : ' - ';?> 
                                    </div>
                                </div>
                                  
                                <?php } ?>
                                    
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="text-align: right;padding-top: 10px !important;">
                                    <button type="button" class="btn btn-primary open_model_btn" data-value="add/<?php echo $asset_id;?>">Add</button> &ensp;&ensp;
                                    <?php  if(!empty($service_provider) && !empty ($service_provider['maint_comp_id'])) { ?>   
                                    <!--button type="button" class="btn btn-primary open_model_btn" data-value="<?php echo $service_provider['maint_comp_id'];?>/<?php echo $asset_id;?>">Edit</button> &ensp;&ensp;-->
                                    <button type="button" class="btn btn-primary open_model_btn"  data-value="all/<?php echo $asset_id;?>">View All</button> 
                                    <?php } ?>
                                </div>                                
                            </div>
                            
                        </div> <!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row -->
                  
                  <div style="clear: both;"></div>  
                  <div class="row" style="background-color: #ecf0f5;">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"> Scheduling List:&nbsp; </span></h5></div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0 5px;">                    
                        <div class="col-md-12 col-sm-12 col-xs-12" style="background-color: #FFF;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th class="hidden-xs">S No</th>                                  
                                  <th>Service Date</th>
                                  <th>Service Type</th>
                                  <th>Schedule On</th>                  
                                  <th>Reminder before</th>
                                  <th>Warranty Status</th>
                                  <th>Service Status</th>
                                  <th>Action</th>
                                  
                                </tr>
                                </thead>
                                <tbody id="content_tbody">
                                               
                                </tbody>                
                              </table>
                        </div>
                    </div>                    
                    
                  </div>
                 
                        
                
              <!--div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div-->
          </div><!-- /.box-body -->
          
          <!--div class="row" style="text-align: right;"> 
            <div class="col-md-12">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                
              </div>
            </div>
          </div-->
        
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- hidden div for service date edit -->
<div class="col-md-12 col-sm-12 col-xs-12 service_date_edit_div" style="display: none;">
    <div class="col-md-4 col-sm-4 col-xs-4 no-padding" >
        <div class="form-group" style="margin-bottom:0px;">    
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input class="form-control pull-right datepicker"  name="schedule_edit_date" value="" type="text">
            </div>
        </div> 
    </div>
</div>


<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" _style="width:750px;">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Maintenance Company</h4>
      </div>
      <div class="modal-body modal-body2" style="padding-bottom: 0px;">
        
        <div class="xol-xs-12 msg">
            
        </div>
        <div style="clear: both;height:1px"></div>
        <div class="xol-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer" style="padding-top: 5px !important;">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>  
  
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>

var service_type = $.parseJSON('<?php echo json_encode($this->config->item('asset_service_name'))?>');
var service_period = $.parseJSON('<?php echo json_encode($this->config->item('asset_service_period'))?>');
var asset_warranty_remin = $.parseJSON('<?php echo json_encode($this->config->item('asset_warranty_remin'));?>');

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    $('.open_model_btn').bind("click",function () {
        if( $(this).attr('data-value') == 'all/<?php echo $asset_id;?>') {
            $('.modal-content').css('width','900px');
        } else {
            $('.modal-content').css('width','600px');
        }
        $('.modal-body2').load('<?php echo base_url('index.php/bms_property/maintenance_company/');?>'+$(this).attr('data-value'),function(result){
    	    $('#myModal2').modal({show:true});           
    	});
        
    });
    /*$('#myModal2').on('hidden.bs.modal', function () {
        $("#content_area").LoadingOverlay("hide", true); 
        window.location.reload(true);
    });*/
    
    
    $('.service_schedule_add_btn').click(function (){
        //console.log('called');
        if ($('#service_type').val() == '') {
            alert('Please select Service Type');$('#service_type').focus(); return false;            
        }
        if ($('#schedule_date').val() == '') {
            alert('Please select Servicing Date');$('#schedule_date').focus(); return false;            
        }
        if ($('#service_reminder').val() == '') {
            alert('Please select Service Reminder');$('#service_reminder').focus(); return false;
        }

        if ($('#schedule_date').val() != '') {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_property/set_service_schedule');?>',
                data: {'service_type':$('#service_type').val(),'schedule_date':$('#schedule_date').val(),'asset_id':$('#asset_id').val(),'warranty_status':$('#warranty_status').val(), 'service_reminder':$('#service_reminder').val()},
                datatype:"html", // others: xml, json; default is html        
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");   }, //
                success: function(data) {  
                    $("#content_area").LoadingOverlay("hide", true); 
                    /*$('#schedule_date').val('');
                    $('#service_type').val('');  
                    $('#scheduled_cnt').html(eval($('#scheduled_cnt').html()) + 1);  
                    $('#unscheduled_cnt').html(eval($('#unscheduled_cnt').html()) - 1);
                    loadContent(0,25);  */
                    window.location.reload();                  
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);
                    alert('Service Schedule Date update Error!');
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }        
            
    });
      
    loadContent(0,25);      
      
});

function loadContent (offset,rows,flag) {
    //$('#search_txt').val($('#search_txt').val().replace(/^\s+|\s+$/g,""));
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_property/get_service_schedule_list');?>',
        data: {'offset':offset,'rows':rows,'asset_id':$('#asset_id').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_tbody").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            //var numFound = data.numFound;
            //console.log(data.numFound);
            if(data.length > 0) {                    
                $.each(data,function (i, item) { 
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td class="col-md-2 col-sm-4 service_date_div_'+item.asset_service_schedule_id+'">'+item.service_date+'</td>';
                    str += '<td>'+service_type[item.service_type]+"-"+service_period[item.service_period]+'</td>';
                    str += '<td>'+item.created_date+'</td>';
                    str += '<td>'+(item.service_reminder  ? asset_warranty_remin[item.service_reminder] : '')+'</td>';
                    str += '<td>'+(item.warranty_status == 1 ? 'Warranty' : 'Contract')+'</td>';
                    str += '<td>-</td>';                                     
                    str += '<td style="text-align: center;">';
                    str += '<a href="javascript:;" class="sd_edit_cls sd_edit_cls_'+item.asset_service_schedule_id+'" data-value="'+item.asset_service_schedule_id+'" title="Service Schedule Edit"><i class="fa fa-edit"></i></a>';
                    str += '<a href="javascript:;" class="sd_save_cls sd_save_cls_'+item.asset_service_schedule_id+'" style="display:none;" data-value="'+item.asset_service_schedule_id+'" title="Service Schedule Save"><i class="fa fa-save"></i></a>';
                    str += '</td>';
                    str += '</tr>';
                });
                /*var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.publi_paging').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages').val(total_pages);
                jQuery('.tot_pag_span').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#tot_rec_span').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                
                var previous_link = ""; 
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)-eval(rows))+","+rows+",true);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)+eval(rows))+","+rows+",true);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);*/
                $("#content_tbody").html(str);
                $('.sd_edit_cls').unbind('click');
                $('.sd_edit_cls').bind("click",function () {
                    //console.log($('.service_date_div_'+$(this).attr('data-value')).html());
                    //$('#schedule_edit_date').val($('.service_date_div_'+$(this).attr('data-value')).html());
                    var row_id = $(this).attr('data-value');
                    var datepic_str = '<div class="col-md-12 col-sm-12 col-xs-12 no-padding">';
                    datepic_str += '<div class="form-group" style="margin-bottom:0px;">';
                    datepic_str += '<div class="input-group date">';
                    datepic_str += '<div class="input-group-addon">';
                    datepic_str += '<i class="fa fa-calendar"></i>';
                    datepic_str += '</div>';
                    datepic_str += '<input class="form-control pull-right datepicker" id="schedule_edit_date_'+row_id+'" value="'+$('.service_date_div_'+$(this).attr('data-value')).html()+'" type="text">';
                    datepic_str += '</div>';
                    datepic_str += '</div>';                    
                    datepic_str += '</div>';
                    $('.service_date_div_'+row_id).html(datepic_str);
                    $('.sd_edit_cls_'+row_id).css('display','none');
                    $('.sd_save_cls_'+row_id).css('display','block');
                    $('.datepicker').unbind('datepicker');
                    $('.datepicker').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true
                    });
                });
                
                $('.sd_save_cls').unbind('click');
                $('.sd_save_cls').bind("click",function () {
                    var row_id = $(this).attr('data-value');
                    if($('#schedule_edit_date_'+row_id).val() != '') {
                        $.ajax({
                            type:"post",
                            async: true,
                            url: '<?php echo base_url('index.php/bms_property/update_service_schedule');?>',
                            data: {'schedule_date':$('#schedule_edit_date_'+row_id).val(),'asset_service_schedule_id':row_id},
                            datatype:"html", // others: xml, json; default is html        
                            beforeSend:function (){ $("#content_area").LoadingOverlay("show");   }, //
                            success: function(data) {  
                                $("#content_area").LoadingOverlay("hide", true);     
                                /*$('.service_date_div_'+row_id).html($('#schedule_edit_date_'+row_id).val());
                                $('.sd_edit_cls_'+row_id).css('display','block');
                                $('.sd_save_cls_'+row_id).css('display','none');*/
                                window.location.reload();              
                            },
                            error: function (e) {
                                $("#content_area").LoadingOverlay("hide", true);
                                $('.sd_edit_cls_'+row_id).css('display','block');
                                $('.sd_save_cls_'+row_id).css('display','none');   
                                alert('Service Schedule Date update Error!');
                                console.log(e); //alert("Something went wrong. Unable to retrive data!");
                            }
                        });
                    }
                        
                });    
                
                
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody").html(str);                
            } 
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
    
}

$(function () {

    //Date picker
    var date = new Date();
    date.setDate(date.getDate()-0);
    //console.log(date);
    $('#schedule_date').datepicker({
        format: 'dd-mm-yyyy',
        //startDate: date,
        <?php echo $date_picker_end_date;?>
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>