<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
              <div class="box-body">
                  <div class="row">



                    
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/set_service_provider');?>" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" id="service_provider_id" name="service_provider_id" value="<?php echo $service_provider_id;?>"/>
                    <input type="hidden" name="service_provider[coa_id]" value="<?php echo !empty($service_provider['coa_id']) ? $service_provider['coa_id'] : "";?>" />
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12">               
                        <div class="form-group">
                          <label >Property Name *</label>
                            <select class="form-control" id="property_id" name="service_provider[property_id]">
                                <option value="">Select Property</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = !empty($service_provider['property_id']) && $service_provider['property_id'] == $val['property_id'] ? 'selected="selected" ' : (!empty($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '');  
                                        echo "<option value='".$val['property_id']."' ".$selected."' data-pname='".$val['property_name']."'>".$val['property_name']."</option>";
                                    } ?> 
                            </select>
                            
                        </div>
                        </div>
                       
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                
                                <div class="form-group">
                                    <label >Service Provider Name *</label>
                                  <input type="text" name="service_provider[provider_name]" class="form-control" value="<?php echo isset($service_provider['provider_name']) && $service_provider['provider_name'] != '' ? $service_provider['provider_name'] : '';?>" placeholder="Enter Supplier Name" maxlength="255">
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="form-group">
                                    <label >Address *</label>
                                  <input type="text" name="service_provider[address]" class="form-control" value="<?php echo isset($service_provider['address']) && $service_provider['address'] != '' ? $service_provider['address'] : '';?>" placeholder="Enter Address" maxlength="255">
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Postcode *</label>
                                        <input type="text" name="service_provider[postcode]" class="form-control" value="<?php echo isset($service_provider['postcode']) && $service_provider['postcode'] != '' ? $service_provider['postcode'] : '';?>" placeholder="Enter Postcode" maxlength="10">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >City *</label>
                                      <input type="text" name="service_provider[city]" class="form-control" value="<?php echo isset($service_provider['city']) && $service_provider['city'] != '' ? $service_provider['city'] : '';?>" placeholder="Enter City" maxlength="50">  
                            
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >State *</label>
                                        <input type="text" name="service_provider[state]" class="form-control" value="<?php echo isset($service_provider['state']) && $service_provider['state'] != '' ? $service_provider['state'] : '';?>" placeholder="Enter State" maxlength="50">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                        <label >Country </label>
                                        <select name="service_provider[country]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                //$countries = $this->config->item('countries');
                                                foreach ($countries as $key=>$val) { 
                                                    $selected = isset($service_provider['country']) && trim($service_provider['country']) == $val['country_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['country_id']."' ".$selected.">".$val['country_name']."</option>";
                                                } ?> 
                                                                           
                                        </select>   
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Office Phone No. *</label>
                                        <input type="text" name="service_provider[office_ph_no]" class="form-control" value="<?php echo isset($service_provider['office_ph_no']) && $service_provider['office_ph_no'] != '' ? $service_provider['office_ph_no'] : '';?>" placeholder="Enter Office Phone No." maxlength="50">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Person Incharge Name *</label>
                                      <input type="text" name="service_provider[person_incharge]" class="form-control" value="<?php echo isset($service_provider['person_incharge']) && $service_provider['person_incharge'] != '' ? $service_provider['person_incharge'] : '';?>" placeholder="Enter Person Incharge Name" maxlength="150">  
                            
                                    </div>
                                </div>
                            </div> 
                            
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Person Incharge Mobile No. *</label>
                                        <input type="text" name="service_provider[person_inc_mobile]" class="form-control" value="<?php echo isset($service_provider['person_inc_mobile']) && $service_provider['person_inc_mobile'] != '' ? $service_provider['person_inc_mobile'] : '';?>" placeholder="Enter Person Incharge Mobile No." maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Person Incharge Email Address *</label>
                                      <input type="text" name="service_provider[person_inc_email]" class="form-control" value="<?php echo isset($service_provider['person_inc_email']) && $service_provider['person_inc_email'] != '' ? $service_provider['person_inc_email'] : '';?>" placeholder="Enter Email Address" maxlength="150">  
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                            
                            </div> 
                            
                        </div>
                        
                        
                        <!-- left side column -->
                        <div class="col-md-6 col-sm-6 col-xs-12"> 
                        
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                <div class="form-group">
                                    <label >Service Category *</label>                              
                                  <select name="service_provider[service_provider_cat_id]" class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                        //$service_provider_cat = $this->config->item('service_provider_cat');
                                        //asort($service_provider_cat);
                                        foreach ($service_provider_cat as $key=>$val) {
                                            $selected = isset($service_provider['service_provider_cat_id']) && $service_provider['service_provider_cat_id'] == $val['service_provider_cat_id'] ? 'selected="selected" ' : '';
                                            echo "<option value='".$val['service_provider_cat_id']."' ".$selected.">".$val['service_provider_cat_name']."</option>";
                                        }
                                    ?>                            
                                  </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                            
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                    <div class="form-group">
                                        <label>Contract Start Date &ensp; <input type="checkbox" name="service_provider[contractual]" value="1" <?php echo isset($service_provider['contractual']) && $service_provider['contractual'] == '1' ? 'checked="checked"' : ''; ?> title="Contractual"></label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker contract_cls" name="service_provider[contract_start_date]" <?php echo isset($service_provider['contractual']) && $service_provider['contractual'] == '1' ? '' : 'disabled="disabled"'; ?> value="<?php echo isset($service_provider['contract_start_date']) && $service_provider['contract_start_date'] != '' && $service_provider['contract_start_date'] != '0000-00-00' && $service_provider['contract_start_date'] != '1970-01-01' ? date('d-m-Y', strtotime($service_provider['contract_start_date'])) : '';?>" type="text">
                                        </div>    
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6  " style="padding-right: 0px;">
                                    <div class="form-group">
                                        <label>Contract End Date *</label>                            
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker contract_cls" name="service_provider[contract_end_date]" <?php echo isset($service_provider['contractual']) && $service_provider['contractual'] == '1' ? '' : 'disabled="disabled"'; ?> value="<?php echo isset($service_provider['contract_end_date']) && $service_provider['contract_end_date'] != '' && $service_provider['contract_end_date'] != '0000-00-00' && $service_provider['contract_end_date'] != '1970-01-01' ? date('d-m-Y', strtotime($service_provider['contract_end_date'])) : '';?>" type="text">
                                        </div>  
                                    </div>
                                </div>
                            
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <!--div class="col-md-6 col-sm-6 col-xs-6  " style="margin-top: 20px;padding-left:20px;">                                    
                                    <div class="form-group">
                                      <div class="checkbox" style="font-weight: bold;">
                                         <input type="checkbox" name="service_provider[contractual]" value="1" <?php echo isset($service_provider['contractual']) && $service_provider['contractual'] == '1' ? 'checked="checked"' : ''; ?> >Contractual
                                      </div>
                                    </div>
                                    
                                </div-->
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding" style="padding-right: 0;">   
                                    <div class="form-group">
                                        <label >Contract Expiry Reminder</label>
                                        <select name="service_provider[remind_before]" class="form-control contract_cls" <?php echo isset($service_provider['contractual']) && $service_provider['contractual'] == '1' ? '' : 'disabled="disabled"'; ?>>
                                            <option value="">Select</option>   
                                            <?php 
                                            
                                                $service_provider_remin = $this->config->item('service_provider_remin');
                                                foreach ($service_provider_remin as $key=>$val) { 
                                                    $selected = isset($service_provider['remind_before']) && trim($service_provider['remind_before']) == $key ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                }
                                                
                                                ?>                                              
                                                                           
                                        </select>    
                            
                                    </div>
                                    
                                </div>
                            </div> 
                            
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label>Head Count</label>
                                        <input type="text" name="service_provider[head_count]" class="form-control" value="<?php echo isset($service_provider['head_count']) && $service_provider['head_count'] != '' ? $service_provider['head_count'] : '';?>" placeholder="Enter Head Count" maxlength="10">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Billing Cycle</label>
                                        <select id="billing_cycle" name="service_provider[billing_cycle]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                            
                                                $service_provider_remin = $this->config->item('service_provider_billing_cycle');
                                                foreach ($service_provider_remin as $key=>$val) { 
                                                    $selected = isset($service_provider['billing_cycle']) && trim($service_provider['billing_cycle']) == $key ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                }
                                                
                                                ?>                                              
                                                                           
                                        </select>    
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Mon</label>
                                        <input type="text" name="service_provider[mon]" class="form-control" value="<?php echo isset($service_provider['mon']) && $service_provider['mon'] != '' ? $service_provider['mon'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Tue</label>
                                        <input type="text" name="service_provider[tue]" class="form-control" value="<?php echo isset($service_provider['tue']) && $service_provider['tue'] != '' ? $service_provider['tue'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Wed</label>
                                        <input type="text" name="service_provider[wed]" class="form-control" value="<?php echo isset($service_provider['wed']) && $service_provider['wed'] != '' ? $service_provider['wed'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Thu</label>
                                        <input type="text" name="service_provider[thu]" class="form-control" value="<?php echo isset($service_provider['thu']) && $service_provider['thu'] != '' ? $service_provider['thu'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Fri</label>
                                        <input type="text" name="service_provider[fri]" class="form-control" value="<?php echo isset($service_provider['fri']) && $service_provider['fri'] != '' ? $service_provider['fri'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Sat</label>
                                        <input type="text" name="service_provider[sat]" class="form-control" value="<?php echo isset($service_provider['sat']) && $service_provider['sat'] != '' ? $service_provider['sat'] : '';?>"  maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <label>Sun</label>
                                        <input type="text" name="service_provider[sun]" class="form-control" value="<?php echo isset($service_provider['sun']) && $service_provider['sun'] != '' ? $service_provider['sun'] : '';?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Public holiday</label>
                                        <input type="text" name="service_provider[public_holiday]" class="form-control" value="<?php echo isset($service_provider['public_holiday']) && $service_provider['public_holiday'] != '' ? $service_provider['public_holiday'] : '';?>" placeholder="Enter Head Count" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label class="pay_cycle_label" >Payment</label>
                                        <input type="text" name="service_provider[monthly_payment]" class="form-control" value="<?php echo isset($service_provider['monthly_payment']) && $service_provider['monthly_payment'] != '' ? $service_provider['monthly_payment'] : '';?>" placeholder="Enter Monthly Payment" maxlength="13">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Annual Payment</label>
                                      <input type="text" name="service_provider[annual_payment]" class="form-control" value="<?php echo isset($service_provider['annual_payment']) && $service_provider['annual_payment'] != '' ? $service_provider['annual_payment'] : '';?>" readonly="true" maxlength="13">  
                            
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">      
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label >Login Email Address</label>
                                        <input type="text" name="service_provider[email_addr]" class="form-control" value="<?php echo isset($service_provider['email_addr']) && $service_provider['email_addr'] != '' ? $service_provider['email_addr'] : '';?>" placeholder="Enter Email Address" maxlength="150">
                            
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0;">
                                    <div class="form-group">
                                      <label >Password</label>
                                      <input type="text" name="service_provider[password]" class="form-control" value="<?php echo isset($service_provider['password']) && $service_provider['password'] != '' ? $service_provider['password'] : '';?>" placeholder="Enter Password" maxlength="50">  
                            
                                    </div>
                                </div>
                            </div>                      
                            
                            
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                          <label >Service Provider's Job Scope</label>
                          <textarea name="service_provider[job_scope]" class="form-control" rows="2" placeholder="Enter Service Provider's Job Scope"><?php echo isset($service_provider['job_scope']) && $service_provider['job_scope'] != '' ? $service_provider['job_scope'] : '';?></textarea> 
                
                        </div>
                    </div>
                    
                                                 
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-6 ">                                    
                            <div class="form-group">
                              <label for="">Attachment</label>
                              <input type="hidden" name="old_attach" value="<?php echo !empty($service_provider['file_name']) ? $service_provider['file_name'] : '';?>" />
                              <div style="position:relative;">
                            		<label class="btn-bs-file btn btn-primary">
                                    Choose File...
                            			
                            			<!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                        <input type="file" id="attach_file" name="document" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                            		</label>
                            		&nbsp;
                            		<span class='label label-info' id="upload-file-info"></span>
                        	  </div>
                            </div>
                            
                            <?php if(!empty($service_provider['file_name'])) { 
                                    $service_provider_attach = $this->config->item('service_provider_attach_upload');
                                    
                                    ?>
                                   <div class="form-group">
                                   <label><a href="<?php echo '../../../'.$service_provider_attach['upload_path'].$service_provider['property_id'].'/'.$service_provider['file_name'];?>" target="_blank" >Click here to view / download Current Attachment</a></label><br />                                    
                                   </div>
                                <?php } ?>
                                
                        </div>
                    </div>
                    <div class="col-md-12" >
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <p class="help-block"> * Required Fields.</p>
                                            </div>
                                        </div>
                                  </div>
                    <div class="row" style="text-align: right;"> 
                        <div class="col-md-12">
                          <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                            <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                            <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                          </div>
                        </div>
                    </div>
                    </form>
                    
                </div> <!-- /.row -->
                
            </div> <!-- /.box-body -->
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>
$(document).ready(function (){
   $('input[name="service_provider[monthly_payment]"]').keyup(function(){
        annual_payment_calc ();        
    }); 
    
    $('input[name="service_provider[contractual]"]').change(function (){
        contract_cls();//amt_div            
    });
    
    $('#billing_cycle').change(function (){
        pay_cycle_label ();
        annual_payment_calc ();
    });
    pay_cycle_label ();
    annual_payment_calc ();
    
});

function contract_cls () {
    if($('input[name="service_provider[contractual]"]:checked').val()) {
        $('.contract_cls').removeAttr ('disabled');        
    } else {
        $('.contract_cls').val('');
        $('.contract_cls').attr ('disabled','disabled');
    }    
}

function annual_payment_calc () {
    if($('#billing_cycle').val() != '' && $('input[name="service_provider[monthly_payment]"]').val () != '') {
            var mul = 1;
            switch ($('#billing_cycle').val()) {
                case '1': mul = 12; break;
                case '2': mul = 6; break;
                case '3': mul = 4; break;
                case '4': mul = 2; break;
                case '5': mul = 1; break;
            }
            $('input[name="service_provider[annual_payment]"]').val(parseFloat(eval($('input[name="service_provider[monthly_payment]"]').val())*mul).toFixed(2));
        } else {
           $('input[name="service_provider[annual_payment]"]').val(''); 
        }
}

function pay_cycle_label () {
    //console.log($('#billing_cycle').val());
    if($('#billing_cycle').val() != '') {
        $('.pay_cycle_label').html ($('#billing_cycle').find('option:selected').text()+ ' Payment');
    } else {
       $('.pay_cycle_label').html ('Payment'); 
    }
}

$('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html(''); 
        contract_cls();
        pay_cycle_label ();       
    });
$( "#bms_frm" ).validate({
		rules: {
            "service_provider[property_id]": "required",     
			"service_provider[provider_name]": "required",            
            "service_provider[address]": "required",
            "service_provider[postcode]": "required",
            "service_provider[city]": "required",
            "service_provider[state]": "required",
            "service_provider[office_ph_no]": "required",
            "service_provider[person_incharge]": "required",
            "service_provider[service_provider_cat_id]": "required",
            "service_provider[contract_start_date]": "required",
            "service_provider[contract_end_date]": "required",
            "service_provider[person_inc_email]": {
                required: true,
                email: true
            }    
		},
		messages: {
		    "service_provider[property_id]": "Please select Property Name",     
			"service_provider[provider_name]": "Please enter Service Provider Name",
            "service_provider[address]": "Please enter Address",             
            "service_provider[postcode]": "Please enter Postcode",
            "service_provider[city]": "Please enter City",
            "service_provider[state]": "Please enter State",
            "service_provider[office_ph_no]": "Please enter Office Phone No.",
            "service_provider[person_incharge]": "Please enter Person Incharge Name",
            "service_provider[service_provider_cat_id]": "Please select Service Category",
            "service_provider[contract_start_date]": "Please enter Contract Start Date",
            "service_provider[contract_end_date]": "Please enter Contract Due Date",
            "service_provider[person_inc_email]": {
                required:"Please enter Email Address",
                email: "Please enter valid Email Address"
            }            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "label" ).parent('div') );
			} else if ( element.hasClass("datepicker") ) {
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
    $(function () {    
        //Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        
        //Timepicker
        /*$('.timepicker').timepicker({
          showInputs: false
        });*/
      });
</script>