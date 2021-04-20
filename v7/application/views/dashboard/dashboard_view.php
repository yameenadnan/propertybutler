<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<style>

.info_div > label { margin-bottom: 0px; }
#minorTaskTabs > li {background-color: #9A9A9A; margin:0 3px; border-top-left-radius: 10px;border-top-right-radius: 10px;  }
#minorTaskTabs > li:first-child { margin-left:0px;  }
#minorTaskTabs > li.active > a, #minorTaskTabs > li:hover > a {    background-color: #656565 !important;    }
#minorTaskTabs > li > a { color:#FFF; border: none; }

.tab-content { background-color: #C9C9CA;padding:10px; border-bottom-left-radius: 10px;border-bottom-right-radius: 10px; border-top-right-radius: 10px;}
.tab-content > div { background-color: #FFF;padding:5px;}

.collec_row > div {  padding:0 5px; margin:10px 0; border-right: 0px solid #ADB2B5; }
.border-none { border-right:none !important; }

.payment_type_color { color:#FFF; background-color: #3F48CC  !important; padding: 5px; border-radius: 10px; }
.payment_type_color_oth { color:#FFF; background-color: #3F48CC  !important; padding: 10px; border-radius: 10px; }
.payment_mode_color { color:#FFF; background-color: #00A2E8 !important; padding: 5px; border-radius: 10px; }
.payment_mode_color_oth { color:#FFF; background-color: #00A2E8 !important; padding: 10px; border-radius: 10px; }
.payment_tot_color { color:#FFF; background-color: #dd4b39 !important; padding: 10px; border-radius: 10px; }

.payment_end_color { color:#FFF; background-color: #F39C12 !important; padding: 10px; border-radius: 10px; }
 
</style>

<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!--section class="content-header">
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        
      </h1>
      
    </section-->

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <?php if($property_id) { ?>
        <div class="row">
                  
            <div class="col-md-4 col-xs-6">
                <select class="form-control select2" id="property_id" name="property_id">
                    <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
                    <option value="">Select Property</option>
                    <?php } ?>
                    <?php 
                        foreach ($properties as $key=>$val) { 
                            $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                            echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                        } ?> 
                </select>
            </div>
            <!-- div class="col-md-8 col-xs-6 text-right" style="margin-top: 8px;">
                <?php if ( !empty($invalid_email_count) && $invalid_email_count > 0 ) { ?>
                    <i class="fa fa-warning" style="color: #f05113;"></i>&nbsp;&nbsp;<a style="color: #f05113; text-decoration: underline;" href="<?php echo base_url('index.php/bms_unit_setup/invalid_email_list');?>">Invalid / Inactive Email Count: <span style="font-size: 16px;"><b><?php echo $invalid_email_count ;?></b></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } else { ?>
                    <a style="text-decoration: underline;" href="<?php echo base_url('index.php/bms_unit_setup/invalid_email_list');?>">Invalid / Inactive Email Count: <span style="font-size: 16px;"><b>0</b></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php } ?>
            </div -->
        </div>

        <div class="box box-warning" style="margin-top: 15px;">
            <div class="box-body no-padding" style="margin-top: 15px;">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <span style="margin-bottom: 0px; font-size: 26px;"><strong><?php echo !empty( $my_open_task_count ) ? $my_open_task_count:'0';?></strong> <span style="font-size: 14px; vertical-align: 15%">(Open Task)</span></span><br />
                            <span style="font-size: 26px; margin-top: 10px;"><strong><?php echo !empty( $my_over_due_task_count ) ? $my_over_due_task_count:'0';?></strong> <span style="font-size: 14px;  vertical-align: 15%">(Over Due Task)</span></span>
                            <p style="padding-top: 12px; margin-bottom: 0px;">My Task</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#myModal">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua-cyan">
                        <div class="inner">
                            <span style="margin-bottom: 0px; font-size: 26px;"><strong><?php echo !empty( $overseeing_open_task_count ) ? $overseeing_open_task_count:'0';?></strong> <span style="font-size: 14px; vertical-align: 15%">(Open Task)</span></span><br />
                            <span style="font-size: 26px; margin-top: 10px;"><strong><?php echo !empty( $overseeing_over_due_task_count ) ? $overseeing_over_due_task_count:'0';?></strong> <span style="font-size: 14px;  vertical-align: 15%">(Over Due Task)</span></span>
                            <p style="padding-top: 12px; margin-bottom: 0px;">My Overseeing Task</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#myModalOverseeing">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <span style="margin-bottom: 0px; font-size: 26px;"><strong><?php echo !empty( $get_annual_renewal_count ) ? $get_annual_renewal_count:'0';?></strong> <span style="font-size: 14px; vertical-align: -15%; display: inline-block; line-height: 14px;">Upcoming <br />renewals</span></span><br />
                            <span style="font-size: 26px; margin-top: 10px; color: red;"><strong><?php echo !empty( $get_expired_annual_renewal_count ) ? $get_expired_annual_renewal_count:'0';?></strong> <span style="font-size: 14px;  vertical-align: 15%">(Expired)</span></span>
                            <p style="padding-top: 12px; margin-bottom: 0px;">Annual Renewals</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-repeat"></i>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#annualRenewalModal">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <span style="margin-bottom: 0px; font-size: 26px;"><strong><?php echo !empty( $get_service_schedule_count ) ? $get_service_schedule_count:'0';?></strong> <span style="font-size: 14px; vertical-align: -15%; display: inline-block; line-height: 14px;">Upcoming <br />service</span></span><br />
                            <span style="font-size: 26px; margin-top: 10px; color: red;"><strong><?php echo !empty( $get_expired_service_schedule_count ) ? $get_expired_service_schedule_count:'0';?></strong> <span style="font-size: 14px;  vertical-align: 15%">(Expired)</span></span>
                            <p style="padding-top: 12px; margin-bottom: 0px;">Service Schedule</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#serviceScheduleModal">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <span style="margin-bottom: 0px; font-size: 26px;"><strong><?php echo ( !empty($invalid_email_count) && $invalid_email_count > 0 )? $invalid_email_count:'0';?></strong></span>
                            <p>Invalid / Inactive Emails</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-email"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        <!--<a href="<?php /*echo base_url('index.php/bms_unit_setup/invalid_email_list');*/?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>
                <!-- ./col -->
            </div>
        </div>






        <!-- TO DO List -->
        <div class="box box-success">
            <div class="box-header">
                <i class="ion ion-clipboard"></i>

                <h3 class="box-title">Notice board</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <?php
                if($_SESSION['bms']['user_type'] == 'staff') {
                    echo !empty($notice_board['message']) ? $notice_board['message'] : '';
                } else if($_SESSION['bms']['user_type'] == 'jmb') {
                    if(!empty ($notice_board)) {
                        foreach ($notice_board as $key=>$val) {
                            echo "<h4 style='font-weight:bold;'>". $val['notice_title'] . ' [ '. date('d-m-Y',strtotime($val['start_date'])) . ' - ' . date('d-m-Y',strtotime($val['end_date'])). ' ]</h4>';
                            echo !empty($val['message']) ? $val['message'] ."<br />" : '';
                        }
                    } else {
                        echo 'No Notice!';
                    }
                } else {
                    echo 'No Notice!';
                } ?>
                <br /><br />
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!--<div class="row" style="margin-top: 15px;">
            <div class="col-md-12 col-xs-12">
               <div class="col-md-12 no-padding text-center" style="background-color: #C3C3C3;border-top-left-radius:7px;border-top-right-radius:7px;">
                    <b>Notice Board</b>
                </div>

                <div class="col-md-12" style="min-height:50px;padding:10px;background-color: #FFF;border:5px solid #C3C3C3;border-bottom-left-radius:7px;border-bottom-right-radius:7px;">
                 <?php /*
                    if($_SESSION['bms']['user_type'] == 'staff') {
                        echo !empty($notice_board['message']) ? $notice_board['message'] : '';
                    } else if($_SESSION['bms']['user_type'] == 'jmb') {
                        if(!empty ($notice_board)) {
                            foreach ($notice_board as $key=>$val) {
                                echo "<h4 style='font-weight:bold;'>". $val['notice_title'] . ' [ '. date('d-m-Y',strtotime($val['start_date'])) . ' - ' . date('d-m-Y',strtotime($val['end_date'])). ' ]</h4>';
                                echo !empty($val['message']) ? $val['message'] ."<br />" : '';
                            }
                        } else {
                        echo 'No Notice!';
                       }
                    } else {
                        echo 'No Notice!';
                   } */?>
               </div>
            </div>

        </div>-->
        
        <div class="row" style="margin-top: 15px;">
            
            <div class="col-md-12" >
                <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                    <div class="box box-primary">
                        <div class="box-body" style="min-height:400px !important;">
                        <?php if(!empty($property_info['jmb_mc_name']) || !empty($property_info['address_1'])) { ?>
                            <div style="padding:10px 0px;line-height: 12px;line-height: 1.3;">
                                <?php if(!empty($property_info['jmb_mc_name'])) { ?>
                                <label><?php echo $property_info['jmb_mc_name'];?> </label> <br />
                                <?php } ?>
                                <?php if(!empty($property_info['address_1'])) { ?>
                                <?php echo $property_info['address_1'];?>  <br />
                                <?php } ?>
                                <?php if(!empty($property_info['address_2'])) { ?>
                                <?php echo $property_info['address_2'];?>  <br />
                                <?php } ?>
                                <?php if(!empty($property_info['pin_code'])) { ?>
                                <?php echo $property_info['pin_code'];?>, 
                                <?php } ?>
                                <?php if(!empty($property_info['city'])) { ?>
                                <?php echo $property_info['city'];?>, 
                                <?php } ?>
                                <?php if(!empty($property_info['state_name'])) { ?>
                                <?php echo $property_info['state_name'];?>
                                <?php } ?>
                            </div>  
                            <?php } ?>
                            <div class="info_div" style="padding:10px 0px;">
                                <label>Phone : </label> <?php echo !empty($property_info['phone_no']) ? $property_info['phone_no'] : '-'; ?><br />
                                <label>Fax : </label> <?php echo !empty($property_info['fax']) ? $property_info['fax'] : '-'; ?><br />
                                <label>Email : </label> <?php echo !empty($property_info['email_addr']) ? $property_info['email_addr'] : '-'; ?>
                            </div>
                        
                        <div>  
                            <?php foreach ($staff_info as $sKey => $sVal) {
                                echo "<div><label>".$sVal['first_name'].(!empty($sVal['last_name']) ? ' '.$sVal['last_name'] : '')." </label> - ".$sVal['desi_name'].(!empty($sVal['mobile_no']) ? ' ('.$sVal['mobile_no'] .')': '').'</div>';
                            } ?>
                        </div>
                        </div>
                    </div>
                    
                    
                </div>
                <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>
                <div class="col-md-6 col-xs-12" style="min-height:400px !important;padding-left: 10px !important;padding-right: 0;" >
                    <div class="box box-warning" style="min-height:400px !important;">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">Personal Assistant - Reminder</h3>

                            <div class="box-tools pull-right">
                                <ul class="pagination pagination-sm inline">
                                    <li class="previous_link"></li>
                                    <li class="next_link"><a href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                            <ul class="todo-list" id="reminder-content">
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <button type="button" onclick="window.location.href='<?php echo base_url('index.php/bms_notifications/add_todo/');?>'" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
                        </div>
                    </div>







                    <!--<div class="box box-success">
            <div class="box-header">
              

              <h3 class="box-title">Personal Assistant - Reminder</h3>

              <div class="box-tools pull-right">
                <input class="btn btn-primary" onclick="window.location.href='<?php /*echo base_url('index.php/bms_notifications/to_do_list/0/25?status_type=0');*/?>'" value="Goto List" type="button">
              </div>
            </div>
            <div class="box-body" style="min-height:360px !important;">
              <table id="example2" class="table table-bordered table-hover table-striped" style="font-size: 13px !important;">
                <thead>
                <tr>
                  <th class="hidden-xs" style="max-width: 32px;">S No</th>
                  <th>Description</th>
                  <th style="width: 85px;">Date</th>
                  
                </tr>
                </thead>
                <tbody id="content_tbody">
                      
                </tbody>                
              </table>
            </div>
          </div>-->
                </div>
                <?php } ?>
                
            </div>
            
        
        </div>
        
        <div style="clear: both;"></div>
        <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)))) { ?>
        <div class="row" style="padding-top: 15px !important;"> 
            <div class="col-md-12" >
                <div class="col-md-12" style="border: 1px solid #999;border-radius: 10px;">
                    <div class="col-md-3">
                    <div style="color: #0C4E91;padding: 10px 5px;">Minor Task</div>

                    <div style="padding:10px 0 10px 15px;">Open Task: &ensp; &nbsp;<a href="<?php echo base_url('index.php/bms_task/task_list').'?property_id='.$property_id.'&task_status=O';?>"><?php echo $open_task_count;?></a></div>

                    <div style="padding:10px 0 10px 15px;">Closed Task: &nbsp;<a href="<?php echo base_url('index.php/bms_task/task_list').'?property_id='.$property_id.'&task_status=C';?>"><?php echo $closed_task_count;?></a></div>
                    <div style="padding:10px 0 10px 15px;">Total Task: &ensp; &nbsp;<a href="<?php echo base_url('index.php/bms_task/task_list').'?property_id='.$property_id.'&task_status=al';?>"><?php echo ($open_task_count+$closed_task_count);?></a></div>

                    </div>

                    <div class="col-md-9 no-padding">

                        <div style="padding: 10px 5px;">

                            <div id="tabs">
                                <ul class="nav nav-tabs" id="minorTaskTabs">
                                    <li class="active"><a href="#tab_1" data-url="<?php echo base_url('index.php/bms_dashboard/minor_task_chart/daily/'.$property_id); ?>">Daily</a></li>
                                    <li><a href="#tab_2" data-url="<?php echo base_url('index.php/bms_dashboard/minor_task_chart/monthly/'.$property_id); ?>">Monthly</a></li>
                                    <li><a href="#tab_3" data-url="<?php echo base_url('index.php/bms_dashboard/minor_task_chart/yearly/'.$property_id); ?>">Yearly</a></li>

                                </ul>
                                <div class="tab-content">
                                    <div id="tab_1" class="tab-pane active"></div>
                                    <div id="tab_2" class="tab-pane active"></div>
                                    <div id="tab_3" class="tab-pane active"></div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>      
        
        
        </div>      
                
       <div style="clear: both;"></div>
      <?php //if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id')))) { ?>
       <div class="row" style="padding: 15px 15px !important;">
       
        <div class="col-md-12 no-padding" style="border: 1px solid #999;border-radius: 10px;">
        
            <div class="box-header" style="padding-bottom: 0px;">
              <h3 class="box-title" style="font-weight: bold;">Today's Collections</h3>
            </div>
              <div class="box-body" >
              <div class="col-md-12 no-padding" style="border-radius: 10px;">
              <div class="col-md-12" style="padding: 5px 15px;">
                  <div class="col-md-4 text-center " style="font-weight:600;"><span class="payment_type_color">Payment Type</span></div>
                  <div class="col-md-7 text-center " style="font-weight:600;"><span class="payment_mode_color">Payment Mode</span></div>
                  <div class="col-md-1 text-center">&nbsp;</div>
              </div>
              <div class="col-md-6 no-padding collec_row">
              
              <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_type_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['sc_sf_collec']) ? number_format($today_collec['sc_sf_collec'],2) : '0';?></h5>
                    <span class="description-text ">SC/SF</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_type_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['oth_char_collec']) ? number_format($today_collec['oth_char_collec'],2) : '0';?></h5>
                    <span class="description-text ">Other Charges</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_type_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['deposit_collec']) ? number_format($today_collec['deposit_collec'],2) : '0';?></h5>
                    <span class="description-text ">Deposit </span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['cheq_collec']) ? number_format($today_collec['cheq_collec'],2) : '0';?></h5>
                    <span class="description-text ">Cheque</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
              </div>
              
              <div class="col-md-6 no-padding collec_row">
              
              <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['cash_collec']) ? number_format($today_collec['cash_collec'],2) : '0';?></h5>
                    <span class="description-text ">Cash</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6 ">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['ibg_collec']) ? number_format($today_collec['ibg_collec'],2) : '0';?></h5>
                    <span class="description-text payment_mode_color">IBG</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['cre_card_collec']) ? number_format($today_collec['cre_card_collec'],2) : '0';?></h5>
                    <span class="description-text">Credit Card</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6 border-none">
                  <div class="description-block payment_tot_color border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($today_collec['total_collec']) ? number_format($today_collec['total_collec'],2) : '0';?></h5>
                    <span class="description-text">Total </span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              
              
              </div>  
        
              </div> <!-- /.box-body -->
       
       
            <div class="box-header" style="padding-bottom: 0px;">
              <h3 class="box-title" style="font-weight: bold;">Till Date Collections</h3>
            </div>
              <div class="box-body" >
                <div class="col-md-12 no-padding" style="border-radius: 10px;">
                
                    <div class="col-md-12" style="padding: 5px 15px;">
                      <div class="col-md-4 text-center " style="font-weight:600;"><span class="payment_type_color">Payment Type</span></div>
                      <div class="col-md-7 text-center " style="font-weight:600;"><span class="payment_mode_color">Payment Mode</span></div>
                      <div class="col-md-1 text-center">&nbsp;</div>
                  </div>
                  
                  <div class="col-md-6 no-padding collec_row">
                  
                  <div class="col-sm-3 col-xs-6">
                      <div class="description-block payment_type_color_oth border-right">
                        
                        <h5 class="description-header">RM <?php echo !empty($till_collec['sc_sf_collec']) ? number_format($till_collec['sc_sf_collec'],2) : '0';?></h5>
                        <span class="description-text ">SC/SF</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block payment_type_color_oth border-right">                        
                        <h5 class="description-header">RM <?php echo !empty($till_collec['oth_char_collec']) ? number_format($till_collec['oth_char_collec'],2) : '0';?></h5>
                        <span class="description-text ">Other Charges</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block payment_type_color_oth border-right">
                        
                        <h5 class="description-header">RM <?php echo !empty($till_collec['deposit_collec']) ? number_format($till_collec['deposit_collec'],2) : '0';?></h5>
                        <span class="description-text ">Deposit </span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block payment_mode_color_oth border-right">
                        
                        <h5 class="description-header">RM <?php echo !empty($till_collec['cheq_collec']) ? number_format($till_collec['cheq_collec'],2) : '0';?></h5>
                        <span class="description-text ">Cheque</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    
                  </div>
              
              <div class="col-md-6 no-padding collec_row">
              
              <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($till_collec['cash_collec']) ? number_format($till_collec['cash_collec'],2) : '0';?></h5>
                    <span class="description-text ">Cash</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($till_collec['ibg_collec']) ? number_format($till_collec['ibg_collec'],2) : '0';?></h5>
                    <span class="description-text ">IBG</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block payment_mode_color_oth border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($till_collec['cre_card_collec']) ? number_format($till_collec['cre_card_collec'],2) : '0';?></h5>
                    <span class="description-text">Credit Card</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
                <div class="col-sm-3 col-xs-6 border-none">
                  <div class="description-block payment_tot_color border-right">
                    
                    <h5 class="description-header">RM <?php echo !empty($till_collec['total_collec']) ? number_format($till_collec['total_collec'],2) : '0';?></h5>
                    <span class="description-text">Total </span>
                  </div>
                  <!-- /.description-block -->
                </div>
              </div>
              
              </div>
        
              </div> <!-- /.box-body -->
              
              <div class="col-md-12 no-padding" style="padding-bottom: 15px !important;" >
                  <div class="col-md-12 no-padding" >
                  
                    <div class="col-sm-2 col-xs-4">
                      <div class="description-block payment_end_color border-right">
                        
                        <h5 class="description-header">RM <?php echo !empty($property_info['monthly_billing']) ? number_format($property_info['monthly_billing'],2) : '0';?></h5>
                        <span class="description-text">MONTHLY BILLING</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                    
                    <div class="col-sm-3 col-xs-4">
                      <div class="description-block payment_end_color border-right">
                        
                        <h5 class="description-header">RM <?php echo !empty($till_collec['sc_sf_collec']) ? number_format($till_collec['sc_sf_collec'],2) : '0';?>
                      <?php if(!empty($property_info['monthly_billing']) && $property_info['monthly_billing'] > 0 && !empty($till_collec['sc_sf_collec']) && $till_collec['sc_sf_collec'] > 0) {
                            echo ' ('. round((($till_collec['sc_sf_collec'] * 100) / $property_info['monthly_billing']),2).'%)';
                      } ?></h5>
                        <span class="description-text">COLLECTED</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                  
                  </div>
               </div>   
              
              <!--div class="col-md-12 no-padding" >
                  <div class="col-md-12 no-padding" >
                      <div class="col-md-2 no-padding">
                      <div style="font-weight:600;padding: 10px 15px 5px 15px;">MONTHLY BILLING: </div>
                      </div>
                      <div class="col-md-2 no-padding">
                      <div style="color: #0C4E91;padding: 10px 15px 5px 15px;">RM <?php echo !empty($property_info['monthly_billing']) ? number_format($property_info['monthly_billing'],2) : '0';?>
                      </div>
                      </div>
                  </div>
                  <div class="col-md-12 no-padding" >
                      <div class="col-md-2 no-padding">
                      <div style="font-weight:600;padding: 5px 15px 15px 15px;">COLLECTED: </div>
                      </div>
                      <div class="col-md-2 no-padding">
                      <div style="color: #0C4E91;padding: 10px 15px 5px 15px;">
                      RM <?php echo !empty($till_collec['sc_sf_collec']) ? number_format($till_collec['sc_sf_collec'],2) : '0';?>
                      <?php if(!empty($property_info['monthly_billing']) && $property_info['monthly_billing'] > 0 && !empty($till_collec['sc_sf_collec']) && $till_collec['sc_sf_collec'] > 0) {
                            echo ' ('. round((($till_collec['sc_sf_collec'] * 100) / $property_info['monthly_billing']),2).'%)';
                      } ?>
                      </div>
                      </div>
                      
                   </div>
                </div-->
            </div>
       </div>
       
      <?php } 
      
      }  ?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
 
 
 
<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" _style="width:750px;">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Short Listed Names</h4>
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

<!--  MODEL POPUP  -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> My Minor Tasks</h4>
            </div>
            <div class="modal-body modal-body2">
                <div role="tabpanel" class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right">
                        <li><a id="minor-task-overdue-href" href="#minor-task-close" data-toggle="tab">Over Due Tasks <strong>(<?php echo !empty( $my_over_due_task_count ) ? $my_over_due_task_count:'0';?>)</strong></a></li>
                        <li class="active"><a id="minor-task-open-href" href="#minor-task-open" data-toggle="tab">Open Tasks <strong>(<?php echo !empty( $my_open_task_count ) ? $my_open_task_count:'0';?>)</strong></a></li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> My Minor Tasks</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <!-- Morris chart - Sales -->
                        <div role="tabpanel" class="tab-pane active" id="minor-task-open" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th style="background-color: #ffffff;">S No</th>
                                        <th style="background-color: #ffffff;">Task ID</th>
                                        <th style="background-color: #ffffff;">Task Name</th>
                                        <th style="background-color: #ffffff;">Created Date</th>
                                        <th style="background-color: #ffffff;">Due Date</th>
                                    </tr>
                                    <tbody id="minor-task-open-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_minor_task_open">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">

                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_mytask"></span>
                                        <span>Page <input class="my_publi_paging_mytask" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_mytask small_loader"></span></span>
                                        <span class="my_next_link_mytask"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_mytask" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="minor-task-close" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>S No</th>
                                        <th>Task ID</th>
                                        <th>Task Name</th>
                                        <th>Created Date</th>
                                        <th>Due Date</th>
                                    </tr>
                                    <tbody id="minor-task-close-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_minor_task_overdue">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_overduetask"></span>
                                        <span>Page <input class="my_publi_paging_overduetask" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_overduetask small_loader"></span></span>
                                        <span class="my_next_link_overduetask"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_overduetask" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>









<!--  MODEL POPUP OVERSEEING -->
<div id="myModalOverseeing" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> My Overseeing Tasks</h4>
            </div>
            <div class="modal-body modal-body2">
                <div role="tabpanel" class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right">
                        <li><a id="minor-task-overseeing-overdue-href" href="#minor-task-overseeing-overdue" data-toggle="tab">Over Due Tasks <strong>(<?php echo !empty( $overseeing_over_due_task_count ) ? $overseeing_over_due_task_count:'0';?>)</strong></a></li>
                        <li class="active"><a id="minor-task-overseeing-open-href" href="#minor-task-overseeing-open" data-toggle="tab">Open Tasks <strong>(<?php echo !empty( $overseeing_open_task_count ) ? $overseeing_open_task_count:'0';?>)</strong></a></li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> My Overseeing Tasks</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <!-- Morris chart - Sales -->
                        <div role="tabpanel" class="tab-pane active" id="minor-task-overseeing-open" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th style="background-color: #ffffff;">S No</th>
                                        <th style="background-color: #ffffff;">Task ID</th>
                                        <th style="background-color: #ffffff;">Task Name</th>
                                        <th style="background-color: #ffffff;">Created Date</th>
                                        <th style="background-color: #ffffff;">Due Date</th>
                                    </tr>
                                    <tbody id="minor-task-overseeing-open-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_minor_task_overseeing_open">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_overseeing"></span>
                                        <span>Page <input class="my_publi_paging_overseeing" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_overseeing small_loader"></span></span>
                                        <span class="my_next_link_overseeing"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_overseeing" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="minor-task-overseeing-overdue" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>S No</th>
                                        <th>Task ID</th>
                                        <th>Task Name</th>
                                        <th>Created Date</th>
                                        <th>Due Date</th>
                                    </tr>
                                    <tbody id="minor-task-overseeing-overdue-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_minor_task_overseeing_overdue">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_overseeing_overduetask"></span>
                                        <span>Page <input class="my_publi_paging_overseeing_overduetask" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_overseeing_overduetask small_loader"></span></span>
                                        <span class="my_next_link_overseeing_overduetask"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_overseeing_overduetask" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>







<!--  MODEL POPUP ANNUAL RENEWAL -->
<div id="annualRenewalModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> Annual Renewals</h4>
            </div>
            <div class="modal-body modal-body2">
                <div role="tabpanel" class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li><a id="annual-renewal-expired-href" href="#annual-renewal-expired" data-toggle="tab">Expired <strong>(<?php echo !empty( $get_expired_annual_renewal_count ) ? $get_expired_annual_renewal_count:'0';?>)</strong></a></li>
                        <li class="active"><a id="annual-renewal-href" href="#annual-renewal" data-toggle="tab">Upcoming renewals <strong>(<?php echo !empty( $get_annual_renewal_count ) ? $get_annual_renewal_count:'0';?>)</strong></a></li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Annual Renewals</li>
                    </ul>
                    <!-- Tabs within a box -->
                    <div class="tab-content no-padding">
                        <!-- Morris chart - Sales -->
                        <div role="tabpanel" class="tab-pane active" id="annual-renewal" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Item description</th>
                                        <th>Serial No.</th>
                                        <th>License No.</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                    <tbody id="annual-renewal-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_annual_renewal">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">

                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_annual_renewal"></span>
                                        <span>Page <input class="my_publi_paging_annual_renewal" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_annual_renewal small_loader"></span></span>
                                        <span class="my_next_link_annual_renewal"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_annual_renewal" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="annual-renewal-expired" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>S No</th>
                                        <th>Item description</th>
                                        <th>Serial No.</th>
                                        <th>License No.</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                    <tbody id="annual-renewal-expired-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_annual_renewal_expired">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">

                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_annual_renewal_expired"></span>
                                        <span>Page <input class="my_publi_paging_annual_renewal_expired" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_annual_renewal_expired small_loader"></span></span>
                                        <span class="my_next_link_annual_renewal_expired"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_annual_renewal_expired" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--  MODEL POPUP ANNUAL RENEWAL -->







<!--  MODEL POPUP SERVICE SCHEDULE -->
<div id="serviceScheduleModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> Asset Service Schedule</h4>
            </div>
            <div class="modal-body modal-body2">
                <div role="tabpanel" class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right">
                        <li><a id="service-schedule-expired-href" href="#service-schedule-expired" data-toggle="tab">Expired <strong>(<?php echo !empty( $get_expired_service_schedule_count ) ? $get_expired_service_schedule_count:'0';?>)</strong></a></li>
                        <li class="active"><a id="service-schedule-href" href="#service-schedule" data-toggle="tab">Upcoming service <strong>(<?php echo !empty( $get_service_schedule_count ) ? $get_service_schedule_count:'0';?>)</strong></a></li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Asset Service Schedule</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <!-- Morris chart - Sales -->
                        <div role="tabpanel" class="tab-pane active" id="service-schedule" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>S No</th>
                                        <th>Asset Name</th>
                                        <th>Asset ID</th>
                                        <th>Schedule Date</th>
                                        <th>Location</th>
                                    </tr>
                                    <tbody id="asset-service-schedule">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_service_schedule">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">

                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_service_schedule"></span>
                                        <span>Page <input class="my_publi_paging_service_schedule" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_service_schedule small_loader"></span></span>
                                        <span class="my_next_link_service_schedule"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_service_schedule" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="service-schedule-expired" style="position: relative; height: 350px; overflow: auto;">
                            <div class="box box-primary">
                                <table class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <th>S No</th>
                                        <th>Asset Name</th>
                                        <th>Asset ID</th>
                                        <th>Schedule Date</th>
                                        <th>Location</th>
                                    </tr>
                                    <tbody id="asset-service-schedule-expired">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                                    Show: &nbsp;<select id="records_per_page_service_schedule_expired">
                                        <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                                        <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                                        <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">

                                    <div class="paging_right_div" style="padding-right: 5px;">
                                        <span class="my_previous_link_service_schedule_expired"></span>
                                        <span>Page <input class="my_publi_paging_service_schedule_expired" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span_service_schedule_expired small_loader"></span></span>
                                        <span class="my_next_link_service_schedule_expired"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_service_schedule_expired" value="" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--  MODEL POPUP SERVICE SCHEDULE -->

<script>
$(document).ready(function () {

    $('.select2').select2();

    $('#tabs').on('click','.tablink,#minorTaskTabs a',function (e) {
        //console.log(this);
        e.preventDefault();
        var url = $(this).attr("data-url");
    
        if (typeof url !== "undefined") {
            var pane = $(this), href = this.hash;
            
            if($(href).html() == '') {
               // ajax load from data-url
                $(href).load(url,function(result){      
                    pane.tab('show');
                }); 
            } else {
                $(this).tab('show');
            }            
        } else {
            $(this).tab('show');
        }
    });
    
    $('#minorTaskTabs').tab();
    $('a[href="#tab_1"]').trigger('click');
    
    $('.open_model_btn').bind("click",function () {
        $('.modal-content').css('width','750px');
        $('#modal-window  .modal-body2').load('<?php echo base_url('index.php/bms_dashboard/short_listed_names/'.$awarded_year.'/'.$awarded_month);?>',function(result){
    	    $('#myModal2').modal({show:true});           
    	});
    });
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_dashboard/index');?>?property_id="+$('#property_id').val();
            return false;
        }           
    });
    
    loadContent (0,7);

    // POPUP MY TASK START
    // POPUP MY TASK START
    $('#minor-task-overdue-href').click(function () {
        loadContentMinorTaskOverDue (0,jQuery('#records_per_page_minor_task_overdue').val());
    });
    $('#minor-task-open-href').click(function () {
        loadContentMinorTaskOpen (0,jQuery('#records_per_page_minor_task_open').val());
    });
    $( "#myModal" ).on('shown.bs.modal', function() {
        loadContentMinorTaskOpen (0,jQuery('#records_per_page_minor_task_open').val());
    });
    jQuery('#records_per_page_minor_task_open').change(function(e){
        loadContentMinorTaskOpen (0,jQuery('#records_per_page_minor_task_open').val(),true);
        return false;
    });
    jQuery('#records_per_page_minor_task_overdue').change(function(e){
        loadContentMinorTaskOverDue (0,jQuery('#records_per_page_minor_task_overdue').val(),true);
        return false;
    });
    jQuery('.my_publi_paging_mytask').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_mytask').val()) {
                    loadContentMinorTaskOpen((jQuery(this).val()-1)*jQuery('#records_per_page_minor_task_open').val(),jQuery('#records_per_page_minor_task_open').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_mytask').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    jQuery('.my_publi_paging_overduetask').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_overduetask').val()) {
                    loadContentMinorTaskOverDue((jQuery(this).val()-1)*jQuery('#records_per_page_minor_task_overdue').val(),jQuery('#records_per_page_minor_task_overdue').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_overduetask').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    // POPUP MY TASK END
    // POPUP MY TASK END


    // POPUP OVERSEEING TASK START
    // POPUP OVERSEEING TASK START
    $('#minor-task-overseeing-overdue-href').click(function () {
        loadContentMinorTaskOverseeingOverDue (0,jQuery('#records_per_page_minor_task_overseeing_overdue').val());
    });
    $('#minor-task-overseeing-open-href').click(function () {
        loadContentMinorTaskOverseeingOpen (0,jQuery('#records_per_page_minor_task_overseeing_open').val());
    });
    $( "#myModalOverseeing" ).on('shown.bs.modal', function() {
        loadContentMinorTaskOverseeingOpen (0,jQuery('#records_per_page_minor_task_overseeing_open').val());
    });
    jQuery('#records_per_page_minor_task_overseeing_open').change(function(e){
        loadContentMinorTaskOverseeingOpen (0,jQuery('#records_per_page_minor_task_overseeing_open').val(),true);
        return false;
    });
    jQuery('#records_per_page_minor_task_overseeing_overdue').change(function(e){
        loadContentMinorTaskOverseeingOverDue (0,jQuery('#records_per_page_minor_task_overseeing_overdue').val(),true);
        return false;
    });
    jQuery('.my_publi_paging_overseeing').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_overseeing').val()) {
                    loadContentMinorTaskOverseeingOpen((jQuery(this).val()-1)*jQuery('#records_per_page_minor_task_overseeing_open').val(),jQuery('#records_per_page_minor_task_overseeing_open').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_overseeing').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    jQuery('.my_publi_paging_overseeing_overduetask').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_overseeing_overduetask').val()) {
                    loadContentMinorTaskOverseeingOverDue((jQuery(this).val()-1)*jQuery('#records_per_page_minor_task_overseeing_overdue').val(),jQuery('#records_per_page_minor_task_overseeing_overdue').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_overseeing_overduetask').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    // POPUP OVERSEEING TASK END
    // POPUP OVERSEEING TASK END


    // POPUP ANNUAL RENEWAL START
    // POPUP ANNUAL RENEWAL START
    $( "#annualRenewalModal" ).on('shown.bs.modal', function() {
        loadContentAnnualRenewals(0,jQuery('#records_per_page_annual_renewal').val() );
    });
    $('#annual-renewal-href').click(function () {
        loadContentAnnualRenewals (0,jQuery('#records_per_page_annual_renewal').val());
    });
    $( "#annual-renewal-expired-href" ).click(function () {
        loadContentAnnualRenewalsExpired (0,jQuery('#records_per_page_annual_renewal_expired').val());
    });
    jQuery('#records_per_page_annual_renewal').change(function(e){
        loadContentAnnualRenewals (0,jQuery('#records_per_page_annual_renewal').val(),true);
        return false;
    });
    jQuery('#records_per_page_annual_renewal_expired').change(function(e){
        loadContentAnnualRenewalsExpired (0,jQuery('#records_per_page_annual_renewal_expired').val(),true);
        return false;
    });

    jQuery('.my_publi_paging_annual_renewal').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_annual_renewal').val()) {
                    loadContentAnnualRenewals((jQuery(this).val()-1)*jQuery('#records_per_page_annual_renewal').val(),jQuery('#records_per_page_annual_renewal').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_annual_renewal').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    jQuery('.my_publi_paging_annual_renewal_expired').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_annual_renewal_expired').val()) {
                    loadContentAnnualRenewalsExpired((jQuery(this).val()-1)*jQuery('#records_per_page_annual_renewal_expired').val(),jQuery('#records_per_page_annual_renewal_expired').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_annual_renewal_expired').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    // POPUP ANNUAL RENEWAL END
    // POPUP ANNUAL RENEWAL END


    // POPUP SERVICE SCHEDULE STARTS
    // POPUP SERVICE SCHEDULE STARTS
    $( "#serviceScheduleModal" ).on('shown.bs.modal', function() {
        loadContentAssetServiceSchedule (0,jQuery('#records_per_page_service_schedule').val());
    });
    $('#service-schedule-href').click(function () {
        loadContentAssetServiceSchedule (0,jQuery('#records_per_page_service_schedule').val());
    });
    $( "#service-schedule-expired-href" ).click(function () {
        loadContentAssetServiceScheduleExpired (0,jQuery('#records_per_page_service_schedule_expired').val());
    });
    jQuery('#records_per_page_service_schedule_expired').change(function(e){
        loadContentAssetServiceScheduleExpired (0,jQuery('#records_per_page_service_schedule_expired').val(),true);
        return false;
    });
    jQuery('#records_per_page_service_schedule').change(function(e){
        loadContentAssetServiceScheduleExpired (0,jQuery('#records_per_page_service_schedule').val(),true);
        return false;
    });
    jQuery('.my_publi_paging_service_schedule').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_service_schedule').val()) {
                    loadContentAssetServiceSchedule((jQuery(this).val()-1)*jQuery('#records_per_page_service_schedule').val(),jQuery('#records_per_page_service_schedule').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_service_schedule').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    jQuery('.my_publi_paging_service_schedule_expired').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_service_schedule_expired').val()) {
                    loadContentAssetServiceScheduleExpired((jQuery(this).val()-1)*jQuery('#records_per_page_service_schedule_expired').val(),jQuery('#records_per_page_service_schedule_expired').val(),'','',false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_service_schedule_expired').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }
    });
    // POPUP SERVICE SCHEDULE ENDS
    // POPUP SERVICE SCHEDULE ENDS
});

function formatDate(in_date) {
  
  var in_date_arr2 = in_date.split('-');  
  return in_date_arr2[2] + "-" + in_date_arr2[1] + "-" + in_date_arr2[0];
  
}

function loadContent (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_notifications/get_todo_content');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {  
            //$("#content_tbody").LoadingOverlay("hide", true);
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) {
                    str_reminder += '<li>';
                    str_reminder += '<span class="handle">';
                    str_reminder += '<i class="fa fa-ellipsis-v"></i>';
                    str_reminder += '<i class="fa fa-ellipsis-v"></i>';
                    str_reminder += '</span>';
                    str_reminder += '<span class="text">'+item.description+'</span>';
                    str_reminder += '<small class="label label-default"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' + item.complete_date.split("-").reverse().join("-")   + '</small>';
                    str_reminder += '</li>';
                });

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)-eval(rows))+","+rows+",true);'>&laquo;</a> ";
                } else {
                    previous_link = "<a href='javascript:;'>&laquo;</a>";
                }

                if (eval(numFound) > (eval(offset)+eval(rows))) {
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)+eval(rows))+","+rows+",true);'>&raquo;</a>";
                    } else {
                        next_link = "<a href='javascript:;'>&raquo;</a>";
                    }
                } else {
                    next_link = "<a href='javascript:;'>&raquo;</a>";
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);

                $("#reminder-content").html(str_reminder);

            } else {

                str_reminder += '<li>';
                str_reminder += '<span class="handle">';
                str_reminder += '<i class="fa fa-ellipsis-v"></i>';
                str_reminder += '<i class="fa fa-ellipsis-v"></i>';
                str_reminder += '</span>';
                str_reminder += '<span class="text">No Reminder Found!</span>';
                str_reminder += '<small class="label label-default"><i class="fa fa-clock-o"></i></small>';
                str_reminder += '</li>';

                $("#reminder-content").html(str_reminder);
            }
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

// My task starts here
// My task starts here
// My task starts here
function loadContentMinorTaskOpen (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_minor_tasks_open');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            var numFound = data.numFound;
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'" target="_blank">' +  item.task_id + '</td>';
                    str_reminder += '<td>' + item.task_name + '</td>';
                    str_reminder += '<td>' + reformatDate (item.created_date) + '</td>';
                    str_reminder += '<td>' + reformatDate (item.due_date) + '</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_mytask').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_mytask').val(total_pages);
                jQuery('.my_tot_pag_span_mytask').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_mytask').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentMinorTaskOpen("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentMinorTaskOpen("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_mytask').html(previous_link);
                jQuery('.my_next_link_mytask').html(next_link);

                $("#minor-task-open-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#minor-task-open-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function loadContentMinorTaskOverDue (offset,rows) {

    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_minor_tasks_overdue');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    toDate = parseInt(new Date(item.due_date).getTime()/1000);
                    fromDate = parseInt(new Date('<?php echo date('Y-m-d');?>').getTime()/1000);

                    var days_diff = (toDate - fromDate)/(3600*24);
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'" target="_blank">' + item.task_id + '</a></td>';
                    str_reminder += '<td>' + item.task_name + '</td>';
                    str_reminder += '<td>' + reformatDate (item.created_date) + '</td>';
                    str_reminder += '<td>' + reformatDate (item.due_date) + ' (OD ' + days_diff + ' Day)</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_overduetask').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_overduetask').val(total_pages);
                jQuery('.my_tot_pag_span_overduetask').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_overduetask').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverDue("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverDue("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_overduetask').html(previous_link);
                jQuery('.my_next_link_overduetask').html(next_link);

                $("#minor-task-close-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#minor-task-close-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
// My task ends here
// My task ends here
// My task ends here


// Overseeing task starts here
// Overseeing task starts here
// Overseeing task starts here
function loadContentMinorTaskOverseeingOpen (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_minor_tasks_overseeing_open');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            var numFound = data.numFound;
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'" target="_blank">' +  item.task_id + '</td>';
                    str_reminder += '<td>' + item.task_name + '</td>';
                    str_reminder += '<td>' + reformatDate (item.created_date) + '</td>';
                    str_reminder += '<td>' + reformatDate (item.due_date) + '</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_overseeing').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_overseeing').val(total_pages);
                jQuery('.my_tot_pag_span_overseeing').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_overseeing').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverseeingOpen("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverseeingOpen("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_overseeing').html(previous_link);
                jQuery('.my_next_link_overseeing').html(next_link);

                $("#minor-task-overseeing-open-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#minor-task-overseeing-open-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function loadContentMinorTaskOverseeingOverDue (offset,rows) {

    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_minor_tasks_overseeing_overdue');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    toDate = parseInt(new Date(item.due_date).getTime()/1000);
                    fromDate = parseInt(new Date('<?php echo date('Y-m-d');?>').getTime()/1000);

                    var days_diff = (toDate - fromDate)/(3600*24);
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'" target="_blank">' + item.task_id + '</a></td>';
                    str_reminder += '<td>' + item.task_name + '</td>';
                    str_reminder += '<td>' + reformatDate (item.created_date) + '</td>';
                    str_reminder += '<td>' + reformatDate (item.due_date) + ' (OD ' + days_diff + ' Day)</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_overseeing_overduetask').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_overseeing_overduetask').val(total_pages);
                jQuery('.my_tot_pag_span_overseeing_overduetask').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_overseeing_overduetask').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverseeingOverDue("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentMinorTaskOverseeingOverDue("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_overseeing_overduetask').html(previous_link);
                jQuery('.my_next_link_overseeing_overduetask').html(next_link);

                $("#minor-task-overseeing-overdue-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#minor-task-overseeing-overdue-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
// Overseeing task ends here
// Overseeing task ends here
// Overseeing task ends here




// Annual renewal starts here
// Annual renewal starts here
// Annual renewal starts here
function loadContentAnnualRenewals (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_annual_renewals');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            //$("#content_tbody").LoadingOverlay("hide", true);
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_property/add_annual_renewal');?>/'+item.annual_renewal_id+'" title="Edit" target="_blank">' + item.item_descrip + '</a></td>';
                    str_reminder += '<td>' + item.serial_no + '</td>';
                    str_reminder += '<td>' + item.license_no + '</td>';
                    str_reminder += '<td>' + reformatDate (item.license_expiry_date) + '</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_annual_renewal').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_annual_renewal').val(total_pages);
                jQuery('.my_tot_pag_span_annual_renewal').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_annual_renewal').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentAnnualRenewals("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentAnnualRenewals("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_annual_renewal').html(previous_link);
                jQuery('.my_next_link_annual_renewal').html(next_link);

                $("#annual-renewal-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#annual-renewal-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function loadContentAnnualRenewalsExpired (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_annual_renewals_expired');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            //$("#content_tbody").LoadingOverlay("hide", true);
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_property/add_annual_renewal');?>/'+item.annual_renewal_id+'" title="Edit" target="_blank">' + item.item_descrip + '</a></td>';
                    str_reminder += '<td>' + item.serial_no + '</td>';
                    str_reminder += '<td>' + item.license_no + '</td>';
                    str_reminder += '<td>' + reformatDate (item.license_expiry_date) + '</td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_annual_renewal_expired').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_annual_renewal_expired').val(total_pages);
                jQuery('.my_tot_pag_span_annual_renewal_expired').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_annual_renewal_expired').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentAnnualRenewalsExpired("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentAnnualRenewalsExpired("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_annual_renewal_expired').html(previous_link);
                jQuery('.my_next_link_annual_renewal_expired').html(next_link);

                $("#annual-renewal-expired-content").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#annual-renewal-expired-content").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
// Annual renewal ends here
// Annual renewal ends here
// Annual renewal ends here



// Service schedule starts here
// Service schedule starts here
// Service schedule starts here
function loadContentAssetServiceSchedule (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_asset_service_schedule');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            //$("#content_tbody").LoadingOverlay("hide", true);
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_property/asset_service_details_entry');?>/'+item.asset_service_schedule_id+'" title="Service Details" target="_blank">' + item.asset_name + '</a></td>';
                    str_reminder += '<td>' + item.asset_id + '</td>';
                    str_reminder += '<td>' + reformatDate (item.schedule_date) + '</td>';
                    str_reminder += '<td>' + item.asset_location + '</td>';
                    // str_reminder += '<td ' + ((item.service_done_date == null) ? "bgcolor='red'":"bgcolor='green'") + '></td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_service_schedule').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_service_schedule').val(total_pages);
                jQuery('.my_tot_pag_span_service_schedule').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_service_schedule').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentAssetServiceSchedule("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentAssetServiceSchedule("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_service_schedule').html(previous_link);
                jQuery('.my_next_link_service_schedule').html(next_link);

                $("#asset-service-schedule").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#asset-service-schedule").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function loadContentAssetServiceScheduleExpired (offset,rows) {
    //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_dashboard/get_asset_service_schedule_expired');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':0},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {
            //$("#content_tbody").LoadingOverlay("hide", true);
            var str = '';
            var showing_to = 0;
            var str_reminder = '';
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    str_reminder += '<tr>';
                    str_reminder += '<td>' + parseFloat(i + 1) + '</td>';
                    str_reminder += '<td><a href="<?php echo base_url('index.php/bms_property/asset_service_details_entry');?>/'+item.asset_service_schedule_id+'" title="Service Details" target="_blank">' + item.asset_name + '</a></td>';
                    str_reminder += '<td>' + item.asset_id + '</td>';
                    str_reminder += '<td>' + reformatDate (item.schedule_date) + '</td>';
                    str_reminder += '<td>' + item.asset_location + '</td>';
                    // str_reminder += '<td ' + ((item.service_done_date == null) ? "bgcolor='red'":"bgcolor='green'") + '></td>';
                    str_reminder += '</tr>';
                });

                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging_service_schedule_expired').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_service_schedule_expired').val(total_pages);
                jQuery('.my_tot_pag_span_service_schedule_expired').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span_service_schedule_expired').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link = "";
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContentAssetServiceScheduleExpired("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContentAssetServiceScheduleExpired("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.my_previous_link_service_schedule_expired').html(previous_link);
                jQuery('.my_next_link_service_schedule_expired').html(next_link);

                $("#asset-service-schedule-expired").html(str_reminder);

            } else {

                str_reminder += '<tr>';
                str_reminder += '<th colspan="5" align="center" style="text-align: center;">No record found</th>';
                str_reminder += '</tr>';

                $("#asset-service-schedule-expired").html(str_reminder);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
// Service schedule ends here
// Service schedule ends here
// Service schedule ends here

function reformatDate (dateStr) {
    dArr = dateStr.split("-");  // ex input "2010-01-18"
    return dArr[2]+ "-" +dArr[1]+ "-" +dArr[0]; //ex out: "18/01/10"
}

</script>
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>
<script src="<?php echo base_url();?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url();?>assets/js/highcharts/modules/data.js"></script>
<script src="<?php echo base_url();?>assets/js/highcharts/modules/exporting.js"></script>