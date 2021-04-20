<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
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
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/add_asset_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    
                                    <div class="form-group">
                                      <label >Property Name *</label>
                                        <select class="form-control" id="property_id" name="asset[property_id]">
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = !empty($asset_info['property_id']) && $asset_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : (!empty($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '');  
                                                    echo "<option value='".$val['property_id']."' ".$selected."' data-pname='".$val['property_name']."'>".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        <input type="hidden" id="asset_id" name="asset_id" value="<?php echo $asset_id;?>"/>
                                        <input type="hidden" name="property_name" id="property_name" value="" />
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Asset Name *</label>
                                          <input type="text" name="asset[asset_name]" class="form-control" value="<?php echo isset($asset_info['asset_name']) && $asset_info['asset_name'] != '' ? $asset_info['asset_name'] : '';?>" placeholder="Enter Asset Name" maxlength="255">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <label >Asset Description</label>
                                          <textarea name="asset[asset_descri]" class="form-control" rows="2" placeholder="Enter Asset Description"><?php echo isset($asset_info['asset_descri']) && $asset_info['asset_descri'] != '' ? $asset_info['asset_descri'] : '';?></textarea> 
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12  "  style="padding-top: 10px !important;">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Warranty Start</label>
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" id="warranty_start" name="asset[warranty_start]" value="<?php echo isset($asset_info['warranty_start']) && $asset_info['warranty_start'] != '' && $asset_info['warranty_start'] != '0000-00-00' && $asset_info['warranty_start'] != '1970-01-01' ? date('d-m-Y', strtotime($asset_info['warranty_start'])) : '';?>" type="text">
                                            </div>    
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 warranty_group" style="padding-right: 0px;<?php echo empty($asset_info['warranty_start']) || $asset_info['warranty_start'] == '0000-00-00' || $asset_info['warranty_start'] == '1970-01-01' ? 'display:none;' : '';?>">
                                        <div class="form-group">
                                            <label>Warranty Due</label>                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right" id="wd_datepicker" name="asset[warranty_due]" value="<?php echo isset($asset_info['warranty_due']) && $asset_info['warranty_due'] != '' && $asset_info['warranty_due'] != '0000-00-00' && $asset_info['warranty_due'] != '1970-01-01' ? date('d-m-Y', strtotime($asset_info['warranty_due'])) : '';?>" type="text">
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Supplier Name</label>
                                          <input type="text" name="asset[supplier_name]" id="supplier_name" class="form-control" value="<?php echo isset($asset_info['supplier_name']) && $asset_info['supplier_name'] != '' ? $asset_info['supplier_name'] : '';?>" placeholder="Enter Supplier Name" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Address</label>
                                          <input type="text" name="asset[address]" id="address" class="form-control" value="<?php echo isset($asset_info['address']) && $asset_info['address'] != '' ? $asset_info['address'] : '';?>" placeholder="Enter Address" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Postcode</label>
                                            <input type="text" name="asset[postcode]" id="postcode" class="form-control" value="<?php echo isset($asset_info['postcode']) && $asset_info['postcode'] != '' ? $asset_info['postcode'] : '';?>" placeholder="Enter Postcode" maxlength="10">
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >City</label>
                                          <input type="text" name="asset[city]" id="city" class="form-control" value="<?php echo isset($asset_info['city']) && $asset_info['city'] != '' ? $asset_info['city'] : '';?>" placeholder="Enter City" maxlength="50">  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >State</label>
                                            <input type="text" name="asset[state]" id="state" class="form-control" value="<?php echo isset($asset_info['state']) && $asset_info['state'] != '' ? $asset_info['state'] : '';?>" placeholder="Enter State" maxlength="50">
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label >Country</label>
                                            <select name="asset[country]" id="country" class="form-control">
                                                <option value="">Select</option>   
                                                <?php 
                                                    $countries = $this->config->item('countries');
                                                    foreach ($countries as $key=>$val) { 
                                                        $selected = isset($asset_info['country']) && trim($asset_info['country']) == $key ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                    } ?> 
                                                                               
                                            </select>   
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Office Phone No.</label>
                                            <input type="text" name="asset[office_ph_no]" id="office_ph_no" class="form-control" value="<?php echo isset($asset_info['office_ph_no']) && $asset_info['office_ph_no'] != '' ? $asset_info['office_ph_no'] : '';?>" placeholder="Enter Office Phone No." maxlength="50">
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Person Incharge Name </label>
                                          <input type="text" name="asset[person_incharge]" id="person_incharge" class="form-control" value="<?php echo isset($asset_info['person_incharge']) && $asset_info['person_incharge'] != '' ? $asset_info['person_incharge'] : '';?>" placeholder="Enter Person Incharge Name" maxlength="150">  
                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding supplier_group" style="<?php echo empty($asset_info['warranty_due']) || $asset_info['warranty_due'] == '0000-00-00' || $asset_info['warranty_due'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Person Incharge Mobile No.</label>
                                            <input type="text" name="asset[person_inc_mobile]" id="person_inc_mobile" class="form-control" value="<?php echo isset($asset_info['person_inc_mobile']) && $asset_info['person_inc_mobile'] != '' ? $asset_info['person_inc_mobile'] : '';?>" placeholder="Enter Person Incharge Mobile No." maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label >Email Address</label>
                                          <input type="text" name="asset[person_inc_email]" id="person_inc_email" class="form-control" value="<?php echo isset($asset_info['person_inc_email']) && $asset_info['person_inc_email'] != '' ? $asset_info['person_inc_email'] : '';?>" placeholder="Enter Email Address" maxlength="150">  
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box " style="padding-left:0px;-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="">
                                    <div class="form-group">
                                        <label >Asset Category</label>
                                        <select name="asset[asset_cat]" class="form-control">
                                            <option value="">Select</option>   
                                            <?php 
                                                $asset_cat = $this->config->item('asset_category');
                                                foreach ($asset_cat as $key=>$val) { 
                                                    $selected = isset($asset_info['asset_cat']) && trim($asset_info['asset_cat']) == $key ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                } ?> 
                                                                           
                                        </select>   
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="">
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                        <div class="form-group">
                                            <label >Asset Brand</label>
                                          <input type="text" name="asset[asset_make]" class="form-control" value="<?php echo isset($asset_info['asset_make']) && $asset_info['asset_make'] != '' ? $asset_info['asset_make'] : '';?>" placeholder="Enter Asset Make (Manufacturer)" maxlength="150">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                        <div class="form-group">
                                          <label >Asset Location</label>
                                            <input type="text" name="asset[asset_location]" class="form-control" value="<?php echo isset($asset_info['asset_location']) && $asset_info['asset_location'] != '' ? $asset_info['asset_location'] : '';?>" placeholder="Enter Asset Location" maxlength="150">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                          <label >Serial No.</label>
                                          <input type="text" name="asset[serial_no]" class="form-control" value="<?php echo isset($asset_info['serial_no']) && $asset_info['serial_no'] != '' ? $asset_info['serial_no'] : '';?>" placeholder="Enter Serial No." maxlength="100">  
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 10px !important;">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Purchase Date</label>
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" name="asset[purchase_date]" value="<?php echo isset($asset_info['purchase_date']) && $asset_info['purchase_date'] != '' && $asset_info['purchase_date'] != '0000-00-00' && $asset_info['purchase_date'] != '1970-01-01' ? date('d-m-Y', strtotime($asset_info['purchase_date'])) : '';?>" type="text">
                                            </div>  
                                
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="form-group">
                                        <label >Price (RM)</label>
                                            <input type="text" name="asset[price]" class="form-control" value="<?php echo isset($asset_info['price']) && $asset_info['price'] != '' ? $asset_info['price'] : '';?>" placeholder="Enter Price" maxlength="13">
                                        </div>
                                    </div>
                                </div>
                                      
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding warranty_group"  style="padding-top: 10px !important;<?php echo empty($asset_info['warranty_start'])  || $asset_info['warranty_start'] == '0000-00-00' || $asset_info['warranty_start'] == '1970-01-01' ? 'display:none;' : '';?>">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Expiry Remind Before</label>
                                            <select name="asset[remind_before]" class="form-control">
                                                <option value="">Select</option>   
                                                <?php 
                                                
                                                    $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                                                    foreach ($asset_warranty_remin as $key=>$val) { 
                                                        $selected = isset($asset_info['remind_before']) && trim($asset_info['remind_before']) == $key ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                    }
                                                    
                                                    /*for ($i =1; $i <=31;$i++) { 
                                                        $selected = isset($asset_info['remind_before']) && trim($asset_info['remind_before']) == $i ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$i."' ".$selected.">".$i." Day".($i == 1 ? "" : "s")."</option>";
                                                    }*/ ?> 
                                                     
                                                                               
                                            </select>    
                                
                                        </div>
                                    </div>
                                    <!--div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                                        <div class="bootstrap-timepicker">
                                            <div class="form-group">
                                            <label>Remind Time</label>                            
                                            <div class="input-group">
                                            <input type="text" name="asset[remaind_time]"  data-value="" value="<?php echo isset($asset_info['remaind_time']) && $asset_info['remaind_time'] != '' ? date('h:i A', strtotime($asset_info['remaind_time'])) : '';?>" class="form-control timepicker">
                        
                                            <div class="input-group-addon">
                                              <i class="fa fa-clock-o"></i>
                                            </div>
                                          </div> 
                                        </div>
                                        </div>
                                    </div-->
                                </div>   
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 10px !important;">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                            <label >Decommission Date</label>
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control pull-right datepicker" name="asset[decommission_date]" value="<?php echo isset($asset_info['decommission_date']) && $asset_info['decommission_date'] != '' && $asset_info['decommission_date'] != '0000-00-00' && $asset_info['decommission_date'] != '1970-01-01' ? date('d-m-Y', strtotime($asset_info['decommission_date'])) : '';?>" type="text">
                                            </div>  
                                        </div>
                                    </div>
                                </div>  
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 10px !important;">
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">                                    
                                        <div class="form-group">
                                          <label >Periodic Service Required?</label>
                                          <div style="padding-left: 0px;">
                                            <label class="radio-inline"> 
                                              <input type="radio" id="periodic_service" name="asset[periodic_service]" value="1" <?php echo isset($asset_info['periodic_service']) && $asset_info['periodic_service'] == 1 ? 'checked="checked"' : '';?> class="periodic_service_cls">Yes &ensp; &ensp;
                                            </label>
                                            <label class="radio-inline">
                                              <input type="radio" name="asset[periodic_service]" value="0" <?php echo isset($asset_info['periodic_service']) && $asset_info['periodic_service'] == 0 ? 'checked="checked"' : '';?> class="periodic_service_cls">No  &ensp; &ensp;
                                            </label>
                                          </div>
                                        </div>
                                    </div>
                                </div>  
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding service_div"  style="padding-top: 10px !important;<?php echo !empty($asset_info['periodic_service']) && $asset_info['periodic_service'] == 1 ? '' : 'display: none;';?>;">
                                    <div class="col-md-5 col-sm-5 col-xs-5 no-padding "> 
                                    <input type="hidden" name="service[service_period_id][]" value="<?php echo isset($service[0]['service_period_id']) && $service[0]['service_period_id'] != '' ? $service[0]['service_period_id'] : '';?>" >                                   
                                        <div class="form-group service_name_div">
                                          <label >Servicing Type</label>
                                            <!--input type="text" name="service[service_name][]" class="form-control" value="<?php echo isset($service[0]['service_name']) && $service[0]['service_name'] != '' ? $service[0]['service_name'] : '';?>" placeholder="Enter Service Name" maxlength="150"-->
                                            <select name="service[service_name][]" class="form-control">
                                                <option value="">Select</option>   
                                                <?php                                                 
                                                    $asset_service_name = $this->config->item('asset_service_name');
                                                    foreach ($asset_service_name as $key=>$val) { 
                                                        $selected = isset($service[0]['service_name']) && trim($service[0]['service_name']) == $key ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                    }
                                                    ?>                                                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-5 ">                                    
                                        <div class="form-group service_period_div">
                                          <label >Service Period</label>
                                          <select name="service[service_period][]" class="form-control">
                                                <option value="">Select</option>   
                                                <?php                                                 
                                                    $asset_service_period = $this->config->item('asset_service_period');
                                                    foreach ($asset_service_period as $key=>$val) { 
                                                        $selected = isset($service[0]['service_period']) && trim($service[0]['service_period']) == $key ? 'selected="selected" ' : '';  
                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                    }
                                                    ?>                                                                                
                                            </select>    
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2 col-xs-2" style="padding-top: 25px;">
                                        <a href="javascript:;" role="button" class="btn btn-primary add_service"><i class="fa fa-plus"></i></a>
                                    </div>
                                    
                                    <?php 
                                    
                                    if(!empty($service) && count($service) > 1) {
                                        $ser_per_cnt = count($service);
                                        for ($i=1;$i<$ser_per_cnt;$i++) {
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                                <div class="col-md-5 col-sm-5 col-xs-5 no-padding ">                                    
                                                    <div class="form-group">
                                                      <label >Service Name</label>
                                                        <input type="hidden" name="service[service_period_id][]" value="<?php echo isset($service[$i]['service_period_id']) && $service[$i]['service_period_id'] != '' ? $service[$i]['service_period_id'] : '';?>" >
                                                        <!--input type="text" name="service[service_name][]" class="form-control" value="<?php echo isset($service[$i]['service_name']) && $service[$i]['service_name'] != '' ? $service[$i]['service_name'] : '';?>" placeholder="Enter Service Name" maxlength="150"-->
                                                        <select name="service[service_name][]" class="form-control">
                                                            <option value="">Select</option>   
                                                            <?php                                                 
                                                                //$asset_service_period = $this->config->item('asset_service_period');
                                                                foreach ($asset_service_name as $key=>$val) { 
                                                                    $selected = isset($service[$i]['service_name']) && trim($service[$i]['service_name']) == $key ? 'selected="selected" ' : '';  
                                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                                }
                                                                ?>                                                                                
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-5 col-xs-5 ">                                    
                                                    <div class="form-group">
                                                      <label >Service Period</label>
                                                      <select name="service[service_period][]" class="form-control">
                                                            <option value="">Select</option>   
                                                            <?php                                                 
                                                                //$asset_service_period = $this->config->item('asset_service_period');
                                                                foreach ($asset_service_period as $key=>$val) { 
                                                                    $selected = isset($service[$i]['service_period']) && trim($service[$i]['service_period']) == $key ? 'selected="selected" ' : '';  
                                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                                }
                                                                ?>                                                                                
                                                        </select>    
                                                    </div>
                                                </div>    
                                             </div>       
                                                
                                    <?php
                                        }
                                        
                                    }
                                    
                                    ?>
                                    
                                </div>                       
                                
                            </div>
                            
                        </div> <!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row -->
                  
                 
                        
                
              <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div>
          </div><!-- /.box-body -->
          
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

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    $('#warranty_start').on('blur',function (){
        $('.warranty_group').css('display','block');       
    });
    
    $('#wd_datepicker').on('blur',function (){
        $('.supplier_group').css('display','block');       
    });
    
    $('.periodic_service_cls').click(function (){
        if($("#periodic_service").prop("checked")) {
            $('.service_div').css('display','block');
        } else {
            $('.service_div').css('display','none');
        }
    });
    
    $('.add_service').click(function (){
        //console.log('test');
        var str = '<div class="col-md-12 col-sm-12 col-xs-12 no-padding">';
        str += '<div class="col-md-5 col-sm-5 col-xs-5 no-padding ">';
        str += $('.service_name_div').clone().html();
        str += '</div>';
        str += '<div class="col-md-5 col-sm-5 col-xs-5 ">';
        str += $('.service_period_div').clone().html();
        str += '</div>';
        str += '</div>';
        $('.service_div').append(str);        
    });
    
      
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"asset[property_id]": "required",
            "asset[asset_name]": "required",
            "asset[periodic_service]": "required"            
		},
		messages: {
			"asset[property_id]": "Please select Property Name",
            "asset[asset_name]": "Please enter Asset Name",
            "asset[periodic_service]": "Please select Periodic Service Required?"            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "label" ).parent('div') );
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
		},
        submitHandler: function(form) {
            if($('.warranty_group').css('display') == 'block' && $('#wd_datepicker').val() != '') {
                $('#supplier_name').val($('#supplier_name').val().replace(/^\s+|\s+$/g,""));
                if($('#supplier_name').val() == '') {
                    alert('Please enter Supplier Name'); $('#supplier_name').focus();return false;
                }
                $('#address').val($('#address').val().replace(/^\s+|\s+$/g,""));
                if($('#address').val() == '') {
                    alert('Please enter Address'); $('#address').focus();return false;
                }
                $('#postcode').val($('#postcode').val().replace(/^\s+|\s+$/g,""));
                if($('#postcode').val() == '') {
                    alert('Please enter Postcode'); $('#postcode').focus();return false;
                }
                $('#city').val($('#city').val().replace(/^\s+|\s+$/g,""));
                if($('#city').val() == '') {
                    alert('Please enter City'); $('#city').focus();return false;
                }
                $('#state').val($('#state').val().replace(/^\s+|\s+$/g,""));
                if($('#state').val() == '') {
                    alert('Please enter State'); $('#state').focus();return false;
                }
                $('#country').val($('#country').val().replace(/^\s+|\s+$/g,""));
                if($('#country').val() == '') {
                    alert('Please slect Country'); $('#country').focus();return false;
                }
                $('#office_ph_no').val($('#office_ph_no').val().replace(/^\s+|\s+$/g,""));
                if($('#office_ph_no').val() == '') {
                    alert('Please enter Office Phone No.'); $('#office_ph_no').focus();return false;
                }
                $('#person_incharge').val($('#person_incharge').val().replace(/^\s+|\s+$/g,""));
                if($('#person_incharge').val() == '') {
                    alert('Please enter Person Incharge Name'); $('#person_incharge').focus();return false;
                }
                $('#person_inc_mobile').val($('#person_inc_mobile').val().replace(/^\s+|\s+$/g,""));
                if($('#person_inc_mobile').val() == '') {
                    alert('Please enter Person Incharge Mobile No.'); $('#person_inc_mobile').focus();return false;
                }
                $('#person_inc_email').val($('#person_inc_email').val().replace(/^\s+|\s+$/g,""));
                if($('#person_inc_email').val() == '') {
                    alert('Please enter Email Address'); $('#person_inc_email').focus();return false;
                }
            } 
            $('#property_name').val($('#property_id').find('option:selected').data('pname')); 
            $("#content_area").LoadingOverlay("show"); 
            $( "#bms_frm").submit();
        }
	}); 
      
});

$(function () {    
    var date = new Date();
    date.setDate(date.getDate()-0);
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //startDate: date,
        autoclose: true
    });
    $('#wd_datepicker').datepicker({        
        format: 'dd-mm-yyyy',
        startDate: date,
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>