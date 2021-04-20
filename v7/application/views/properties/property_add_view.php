<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<style>.div_border { border: 1px solid #999;border-radius: 5px; }</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
        <h1>
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
            <!--small>Optional description</small-->
        </h1>

    </section>
    <style>
        .hide_developer_div {
            visibility: hidden;
            display: none;
        }
    </style>
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/add_property_submit');?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" style="padding: 0;">

                            <div class="col-md-6 col-xs-12 no-padding">
                                <div class="box-header with-border" style="padding-left:15px ;">
                                    <h3 class="box-title"><b>Property Information</b></h3>
                                </div>
                                <div class="col-md-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Property Name *&ensp; &ensp; &ensp; &ensp; &ensp; &ensp; &ensp; &ensp; &ensp;
                                                <div class="pull-right">
                                                    <input type="radio" name="property[property_status]" value="1" <?php echo (isset($property_info['property_status']) && $property_info['property_status'] != '0') || !isset($property_info['property_status']) ? 'checked="checked"' : ''; ?> > &ensp; Active &ensp; &ensp; &ensp; &ensp; &ensp; &ensp;
                                                    <input type="radio" name="property[property_status]" value="0" <?php echo isset($property_info['property_status']) && $property_info['property_status'] == '0' ? 'checked="checked"' : ''; ?> > &ensp; Inactive
                                                </div>
                                            </label>
                                            <input type="text" id="property_name" name="property[property_name]" class="form-control" value="<?php echo !empty($property_info['property_name']) ? $property_info['property_name'] : '';?>" placeholder="Enter Property Name" maxlength="100">
                                            <input type="hidden" name="property_id" value="<?php echo $property_id;?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Property Type *</label>
                                            <select name="property[property_type]" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($property_type as $key=>$val) {
                                                    $selected = !empty($property_info['property_type']) && $property_info['property_type'] == $val['type_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='".$val['type_id']."'  ".$selected.">".$val['type_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Property Abbreviation *</label>
                                            <input type="text" id="property_abbrev" name="property[property_abbrev]" class="form-control" value="<?php echo !empty($property_info['property_abbrev']) ? $property_info['property_abbrev'] : '';?>" placeholder="Enter Property Abbreviation" maxlength="10" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding" >
                                        <div class="col-md-6 col-xs-6" >
                                            <div class="form-group">
                                                <label>Property Under *</label><br />
                                                <select name="property[property_under]" id="developer_under" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $property_under = $this->config->item('property_under');

                                                    foreach ($property_under as $key=>$val) {
                                                        $selected = isset($property_info['property_under']) && $property_info['property_under'] == $key ? 'selected="selected" ' : '';
                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Total Units *</label>
                                                <input type="text" id="total_units" name="property[total_units]" class="form-control" value="<?php echo !empty($property_info['total_units']) ? $property_info['total_units'] : '';?>" placeholder="Enter Total Units" maxlength="20">
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ( !empty ( $property_info['property_under'] ) && $property_info['property_under'] == 3 ) { ?>
                                        <div class="col-md-12 col-xs-12 no-padding developer_div">
                                            <div class="col-md-12 col-xs-12 no-padding developer_div_inner">
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Developer email address</label>
                                                        <input type="text"  name="developer[email_addr][]" data-id="<?php echo !empty($developer[0]['property_dev_id']) ? $developer[0]['property_dev_id'] : '';?>" class="form-control" value="<?php echo !empty($developer[0]['email_addr']) ? $developer[0]['email_addr'] : '';?>" placeholder="Enter e-mail" maxlength="200">
                                                        <input type="hidden" name="developer[property_dev_id][]" value="<?php echo !empty($developer[0]['property_dev_id']) ? $developer[0]['property_dev_id'] : '';?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <div class="input-group">
                                                            <input type="password" name="developer[password][]" class="form-control" value="<?php echo !empty($developer[0]['password']) ? $developer[0]['password'] : '';?>" placeholder="Enter Password" maxlength="250">
                                                            <span class="input-group-btn">
                                                        <button class="btn btn-success btn-add-developer" type="button">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </button>
                                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if(!empty($developer) && count($developer) > 1) {
                                                for ($k=1;$k < count($developer); $k++) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding developer_div_inner">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" name="developer[email_addr][]" class="form-control" data-id="<?php echo !empty($developer[$k]['property_dev_id']) ? $developer[$k]['property_dev_id'] : '';?>" value="<?php echo !empty($developer[$k]['email_addr']) ? $developer[$k]['email_addr'] : '';?>" placeholder="Enter e-mail" maxlength="200">
                                                                <input type="hidden" name="developer[property_dev_id][]" value="<?php echo !empty($developer[$k]['property_dev_id']) ? $developer[$k]['property_dev_id'] : '';?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="input-group">
                                                                <input type="text" name="developer[password][]" class="form-control" value="" placeholder="Enter Password" maxlength="250">
                                                                <span class="input-group-btn">
                                                        <button class="btn btn-danger btn-remove-developer" type="button">
                                                            <span class="glyphicon btn-danger glyphicon-minus"></span>
                                                        </button>
                                                    </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php  }
                                            } ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-12 col-xs-12 no-padding developer_div hide_developer_div">
                                            <div class="col-md-12 col-xs-12 no-padding developer_div_inner">
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Developer email address</label>
                                                        <input type="text" name="developer[email_addr][]" class="form-control" data-id="<?php echo !empty($developer[0]['property_dev_id']) ? $developer[0]['property_dev_id'] : '';?>" value="<?php echo !empty($developer[0]['email_addr']) ? $developer[0]['email_addr'] : '';?>" placeholder="Enter e-mail" maxlength="200">
                                                        <input type="hidden" name="developer[property_dev_id][]" value="<?php echo !empty($developer[0]['property_dev_id']) ? $developer[0]['property_dev_id'] : '';?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <div class="input-group">
                                                            <input type="password" name="developer[password][]" class="form-control" value="<?php echo !empty($developer[0]['password']) ? $developer[0]['password'] : '';?>" placeholder="Enter Password" maxlength="250">
                                                            <span class="input-group-btn">
                                                        <button class="btn btn-success btn-add-developer" type="button">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </button>
                                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if(!empty($developer) && count($developer) > 1) {
                                                for ($k=1;$k < count($developer); $k++) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding developer_div_inner">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <input type="text" name="developer[email_addr][]" class="form-control" data-id="<?php echo !empty($developer[$k]['property_dev_id']) ? $developer[$k]['property_dev_id'] : '';?>" value="<?php echo !empty($developer[$k]['email_addr']) ? $developer[$k]['email_addr'] : '';?>" placeholder="Enter e-mail" maxlength="200">
                                                                <input type="hidden" name="developer[property_dev_id][]" value="<?php echo !empty($developer[$k]['property_dev_id']) ? $developer[$k]['property_dev_id'] : '';?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="input-group">
                                                                <input type="text" name="developer[password][]" class="form-control" value="" placeholder="Enter Password" maxlength="250">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-danger btn-remove-developer" type="button">
                                                                        <span class="glyphicon btn-danger glyphicon-minus"></span>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php  }
                                            } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-12 col-xs-12" >
                                        <div class="form-group">
                                            <label>JMB / MC / Developer Name</label>
                                            <input type="text" id="jmb_mc_name" name="property[jmb_mc_name]" class="form-control" value="<?php echo !empty($property_info['jmb_mc_name']) ? $property_info['jmb_mc_name'] : '';?>" placeholder="Enter JMB / MC / Developer Name" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12" >
                                        <div class="form-group">
                                            <label>Manage by</label>
                                            <input type="text" id="jmb_mc_name" name="property[managed_by]" class="form-control" value="<?php echo !empty($property_info['managed_by']) ? $property_info['managed_by'] : '';?>" placeholder="Enter managed by" maxlength="100">
                                        </div>
                                    </div>

                                    <div style="clear: both;height:1px"></div>

                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Contact Information</b></h3>
                                    </div>

                                    <div class="col-md-12" style="padding: 0;">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Address Line 1</label>
                                                <input type="text" id="address_1" name="property[address_1]" class="form-control" value="<?php echo !empty($property_info['address_1']) ? $property_info['address_1'] : '';?>" placeholder="Enter Address Line 1" maxlength="255">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Address Line 2</label>
                                                <input type="text" id="address_2" name="property[address_2]" class="form-control" value="<?php echo !empty($property_info['address_2']) ? $property_info['address_2'] : '';?>" placeholder="Enter Address Line 2" maxlength="150">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 0;">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" id="city" name="property[city]" class="form-control" value="<?php echo !empty($property_info['city']) ? $property_info['city'] : '';?>" placeholder="Enter City" maxlength="150">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Post Code</label>
                                                <input type="text" id="pin_code" name="property[pin_code]" class="form-control" value="<?php echo !empty($property_info['pin_code']) ? $property_info['pin_code'] : '';?>" placeholder="Enter Pincode" maxlength="25" _style="background-color:#D2F6FF ;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding: 0;">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <select name="property[state_id]" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($property_state as $key=>$val) {
                                                        $selected = !empty($property_info['state_id']) && $property_info['state_id'] == $val['state_id'] ? 'selected="selected" ' : '';
                                                        echo "<option value='".$val['state_id']."'  ".$selected.">".$val['state_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <select name="property[country_id]" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php
                                                    foreach ($countries_mas as $key=>$val) {
                                                        $selected = !empty($property_info['country_id']) && $property_info['country_id'] == $val['country_id'] ? 'selected="selected" ' : '';
                                                        echo "<option value='".$val['country_id']."'  ".$selected.">".$val['country_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Fax</label>
                                            <input type="text" id="fax" name="property[fax]" class="form-control" value="<?php echo !empty($property_info['fax']) ? $property_info['fax'] : '';?>" placeholder="Enter Fax" maxlength="50">
                                        </div>
                                    </div>



                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Phone No1</label>
                                            <input type="text" id="phone_no" name="property[phone_no]" class="form-control" value="<?php echo !empty($property_info['phone_no']) ? $property_info['phone_no'] : '';?>" placeholder="Enter Phone No" maxlength="50">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Phone No 2</label>
                                            <input type="text" id="phone_no2" name="property[phone_no2]" class="form-control" value="<?php echo !empty($property_info['phone_no2']) ? $property_info['phone_no2'] : '';?>" placeholder="Enter Phone No 2" maxlength="50">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" id="email_addr" name="property[email_addr]" class="form-control" value="<?php echo !empty($property_info['email_addr']) ? $property_info['email_addr'] : '';?>" placeholder="Enter Email Address" maxlength="100">
                                        </div>
                                    </div>


                                    <div class="col-md-12 col-xs-12 no-padding block_div">

                                        <div class="col-md-6 col-xs-12 block_div_inner">
                                            <label>Block / Street Name</label>
                                            <div class="input-group">
                                                <input class="form-control" name="blocks[block_name][]" value="<?php echo !empty($blocks[0]['block_name']) ? $blocks[0]['block_name'] : '';?>" type="text" placeholder="Enter Block / Street Name" maxlength="100" />
                                                <input type="hidden" name="blocks[block_id][]" value="<?php echo !empty($blocks[0]['block_id']) ? $blocks[0]['block_id'] : '';?>" />
                                                <span class="input-group-btn">
                                            <button class="btn btn-success btn-add-block" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                            </div>
                                        </div>
                                        <?php if(!empty($blocks) && count($blocks) > 1) {
                                            for ($k=1;$k < count($blocks); $k++) { ?>

                                                <div class="col-md-6 col-xs-12 block_div_inner" style="margin-top: <?php echo $k == 1 ? 25 : 15;?>px">

                                                    <div class="input-group">
                                                        <input class="form-control" name="blocks[block_name][]" value="<?php echo !empty($blocks[$k]['block_name']) ? $blocks[$k]['block_name'] : '';?>" type="text" placeholder="Enter Block / Street Name" maxlength="100" />
                                                        <input type="hidden" name="blocks[block_id][]" value="<?php echo !empty($blocks[$k]['block_id']) ? $blocks[$k]['block_id'] : '';?>" />
                                                        <span class="input-group-btn">
                                            <button class="btn btn-danger btn-remove-block" type="button">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                                    </div>
                                                </div>

                                            <?php  }
                                        } ?>

                                    </div>

                                    <div class="col-md-12" style="padding: 0;margin-top:15px">
                                        <div class="col-md-6 col-xs-12 no-padding">



                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="">Property Logo</label>
                                                    <div style="position:relative;">
                                                        <label class="btn-bs-file btn btn-primary">
                                                            Choose Logo...

                                                            <!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                                            <input type="file" id="attach_file" name="prop_logo" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                                                        </label>
                                                        &nbsp;
                                                        <span class='label label-info' id="upload-file-info"></span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="property_logo_old" value="<?php echo !empty($property_info['logo']) ? $property_info['logo'] : '';?>" />
                                                <?php if(!empty($property_info['logo'])) {
                                                    $property_logo_upload = $this->config->item('property_logo_upload');

                                                    ?>
                                                    <div class="form-group">
                                                        <label>Current Logo:</label><br />
                                                        <img src="<?php echo '../../../'.$property_logo_upload['upload_path'].$property_info['logo'];?>" width="150" />
                                                    </div>
                                                <?php } ?>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 col-xs-12 no-padding">
                                <div class="box-header with-border" style="padding-left:15px ;">
                                    <h3 class="box-title">
                                        <b>Accounts Information&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="pull-right" style="font-size: 14px;">
                                                <label class="radio-inline">
                                                    <b><input type="radio" name="property[account_status]" value="1" <?php echo (isset($property_info['account_status']) && $property_info['account_status'] != '0') || !isset($property_info['account_status']) ? 'checked="checked"' : ''; ?> > Active &ensp; &ensp; &ensp; &ensp; &ensp; &ensp;</b>
                                                </label>
                                                <label class="radio-inline">
                                                    <b><input type="radio" name="property[account_status]" value="0" <?php echo isset($property_info['account_status']) && $property_info['account_status'] == '0' ? 'checked="checked"' : ''; ?> > Inactive</b>
                                                </label>
                                            </div>
                                        </b>
                                    </h3>
                                </div>
                                <div class="col-md-12 col-xs-12 no-padding div_border">
                                    <!--
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                      <label>Financial Year Start Month</label><br />
                                        <select name="property[finance_year_start_month]" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                    /*                                                $finance_year_start_month = $this->config->item('finance_year_start_month');

                                                                                    foreach ($finance_year_start_month as $key=>$val) {
                                                                                        $selected = isset($property_info['finance_year_start_month']) && $property_info['finance_year_start_month'] == $key ? 'selected="selected" ' : '';
                                                                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                                                    }
                                     */
                                    ?>

                                          </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                      <label>e-Billing start from</label>

                                      <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker" name="property[e_billing_start_date]" value="<?php echo isset($property_info['e_billing_start_date']) && $property_info['e_billing_start_date'] != '' && $property_info['e_billing_start_date'] != '0000-00-00' && $property_info['e_billing_start_date'] != '1970-01-01' ? date('d-m-Y', strtotime($property_info['e_billing_start_date'])) : '';?>" type="text">
                                      </div>
                                    </div>
                                </div>
                                -->

                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Billing Cycle </label>
                                            <select name="property[billing_cycle]" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                $billing_cycle = $this->config->item('billing_cycle');
                                                foreach ($billing_cycle as $key=>$val) {
                                                    $selected = isset($property_info['billing_cycle']) && $property_info['billing_cycle'] == $key ? 'selected="selected" ' : '';
                                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Auto bill generate date </label>

                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" name="property[bill_generate_date]" id="bill_generate_date" class="form-control inline datepicker" value="<?php echo !empty($property_info['bill_generate_date']) ? date('d-m-Y', strtotime($property_info['bill_generate_date'])) : '';?>" placeholder="Bill Generate Date" maxlength="13">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group" style="margin: 10px 0;">
                                            <label>Calculation based on *</label> &ensp;

                                            <label class="radio-inline">
                                                <input type="radio" name="property[calcul_base]" value="1" <?php echo isset($property_info['calcul_base']) && $property_info['calcul_base'] == 1 ? 'checked="checked"' : '';?>><b>Sq. Foot</b> &ensp;
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="property[calcul_base]" value="2" <?php echo isset($property_info['calcul_base']) && $property_info['calcul_base'] == 2 ? 'checked="checked"' : '';?>><b>Share Unit</b>  &ensp;
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="property[calcul_base]" value="3" <?php echo isset($property_info['calcul_base']) && $property_info['calcul_base'] == 3 ? 'checked="checked"' : '';?>><b>Fixed</b> &ensp;
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label id="tot_sq_feet_container">Total Sq. Foot</label>
                                                <label id="tot_share_unit_container">Total Share Unit</label>
                                                <label id="amount_container">Amount</label>
                                                <input type="number" id="tot_sq_feet" name="property[sc_charge]" class="form-control" value="<?php echo !empty($property_info['sc_charge']) ? $property_info['sc_charge'] : '';?>" maxlength="13">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Sinking Fund % </label>
                                                <input type="number" name="property[sinking_fund]" id="sinking_fund" class="form-control inline" value="<?php echo !empty($property_info['sinking_fund']) && $property_info['sinking_fund'] != '0.00' ? $property_info['sinking_fund'] : '';?>" placeholder="Enter Sinking Fund %" maxlength="13">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding tier_div">
                                        <div class="col-md-12 col-xs-12 no-padding tier_div_inner">
                                            <div class="col-md-6 col-xs-6">
                                                <div class="form-group">
                                                    <label>Tier Name(For SC Calculation)</label>
                                                    <input type="text" name="tier[tier_name][]" class="form-control" value="<?php echo !empty($tiers[0]['tier_name']) ? $tiers[0]['tier_name'] : '';?>" placeholder="Enter Tier Name" maxlength="100">
                                                    <input type="hidden" name="tier[tier_id][]" value="<?php echo !empty($tiers[0]['tier_id']) ? $tiers[0]['tier_id'] : '';?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-6">
                                                <label>Value</label>
                                                <div class="input-group">
                                                    <input type="number" name="tier[tier_value][]" class="form-control" value="<?php echo !empty($tiers[0]['tier_value']) ? $tiers[0]['tier_value'] : '';?>" placeholder="Enter Value" maxlength="13">
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-success btn-add-tier" type="button">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(!empty($tiers) && count($tiers) > 1) {
                                            for ($k=1;$k < count($tiers); $k++) { ?>
                                                <div class="col-md-12 col-xs-12 no-padding tier_div_inner">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <input type="text" name="tier[tier_name][]" class="form-control" value="<?php echo !empty($tiers[$k]['tier_name']) ? $tiers[$k]['tier_name'] : '';?>" placeholder="Enter Tier Name" maxlength="100">
                                                            <input type="hidden" name="tier[tier_id][]" value="<?php echo !empty($tiers[$k]['tier_id']) ? $tiers[$k]['tier_id'] : '';?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="input-group">
                                                            <input type="number" name="tier[tier_value][]" class="form-control" value="<?php echo !empty($tiers[$k]['tier_value']) ? $tiers[$k]['tier_value'] : '';?>" placeholder="Enter Value" maxlength="13">
                                                            <span class="input-group-btn">
                                                    <button class="btn btn-danger btn-remove-tier" type="button">
                                                        <span class="glyphicon btn-danger glyphicon-minus"></span>
                                                    </button>
                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php  }
                                        } ?>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Insurance Premium (RM)</label>
                                                <input type="number" id="insurance_prem" name="property[insurance_prem]" class="form-control" value="<?php echo !empty($property_info['insurance_prem']) && $property_info['insurance_prem'] != '0.00' ? $property_info['insurance_prem'] : '';?>" placeholder="Enter Insurance Premium (RM)" maxlength="25">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Premium Invoice Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input class="form-control pull-right datepicker" name="property[insur_prem_date]" value="<?php echo isset($property_info['insur_prem_date']) && $property_info['insur_prem_date'] != '' && $property_info['insur_prem_date'] != '0000-00-00' && $property_info['insur_prem_date'] != '1970-01-01' ? date('d-m-Y', strtotime($property_info['insur_prem_date'])) : '';?>" type="text">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Quit Rent (RM)</label>
                                                <input type="number" id="quit_rent" name="property[quit_rent]" class="form-control" value="<?php echo !empty($property_info['quit_rent']) && $property_info['quit_rent'] != '0.00' ? $property_info['quit_rent'] : '';?>" placeholder="Enter Quit Rent (RM)" maxlength="25">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label >Quit Rent Invoice Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input class="form-control pull-right datepicker" name="property[quit_rent_paid_on]" value="<?php echo isset($property_info['quit_rent_paid_on']) && $property_info['quit_rent_paid_on'] != '' && $property_info['quit_rent_paid_on'] != '0000-00-00' && $property_info['quit_rent_paid_on'] != '1970-01-01' ? date('d-m-Y', strtotime($property_info['quit_rent_paid_on'])) : '';?>" type="text">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Billing Due Days</label>
                                                <input type="number" id="bill_due_days" name="property[bill_due_days]" class="form-control" value="<?php echo !empty($property_info['bill_due_days']) ? $property_info['bill_due_days'] : '';?>" placeholder="Enter Billing Due Days" maxlength="13">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Include Outstading in Invoice</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property[include_os_inv]" value="1" <?php echo (isset($property_info['include_os_inv']) && $property_info['include_os_inv'] != '0') || !isset($property_info['include_os_inv']) ? 'checked="checked"' : ''; ?> > Yes &ensp; &ensp; &ensp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property[include_os_inv]" value="0" <?php echo isset($property_info['include_os_inv']) && $property_info['include_os_inv'] == '0' ? 'checked="checked"' : ''; ?> > No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Monthly billing</label>
                                                <input type="number" id="monthly_billing" name="property[monthly_billing]" class="form-control" value="<?php echo !empty($property_info['monthly_billing']) ? $property_info['monthly_billing'] : '';?>" placeholder="Monthly billing" maxlength="13">
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-12 div_border" style="padding: 0;margin-top:15px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Payment Gateway Settings</b></h3>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Payment Gateway URL</label><br />
                                                <input type="text" name="property[pymt_gateway_url]" id="pymt_gateway_url" class="form-control inline" value="<?php echo !empty($property_info['pymt_gateway_url']) ? $property_info['pymt_gateway_url'] : '';?>" placeholder="Enter Merchant ID" maxlength="150">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Merchant ID</label><br />
                                                <input type="text" name="property[payment_merchant_id]" id="merchant_id" class="form-control inline" value="<?php echo !empty($property_info['payment_merchant_id']) ? $property_info['payment_merchant_id'] : '';?>" placeholder="Enter Merchant ID" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Merchant Key Index</label><br />
                                                <input type="text" name="property[payment_merchant_key_index]" id="payment_merchant_key_index" class="form-control inline" value="<?php echo !empty($property_info['payment_merchant_key_index']) ? $property_info['payment_merchant_key_index'] : '';?>" placeholder="Enter Merchant Key Index" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Merchant Key</label><br />
                                                <input type="text" name="property[payment_merchant_key]" id="payment_merchant_key" class="form-control inline" value="<?php echo !empty($property_info['payment_merchant_key']) ? $property_info['payment_merchant_key'] : '';?>" placeholder="Enter Merchant Key" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Transaction Charges Bear By</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property[payment_bear_by]" <?php echo (isset($property_info['payment_bear_by']) && $property_info['payment_bear_by'] != 1 || !isset($property_info['payment_bear_by']) )? 'checked="checked"' : '';?> value="1">JMB/MC &ensp; &ensp; &ensp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property[payment_bear_by]" <?php echo isset($property_info['payment_bear_by']) && $property_info['payment_bear_by'] == 2 ? 'checked="checked"' : '';?> value="2">Resident
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>FPX (Amount)</label><br />
                                                <input type="number" name="property[payment_fpx]" class="form-control inline" value="<?php echo !empty($property_info['payment_fpx']) ? $property_info['payment_fpx'] : '';?>" placeholder="Enter FPX" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Credit/Debit Card (Percentage)</label><br />
                                                <input type="number" name="property[payment_cc_card]" class="form-control inline" value="<?php echo !empty($property_info['payment_cc_card']) ? $property_info['payment_cc_card'] : '';?>" placeholder="Enter Credit/Debit Card" maxlength="13">
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-12 div_border" style="padding: 0;margin-top:15px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Late Payment Interest</b></h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Late Payment Charge % pa&ensp; <input type="checkbox" name="property[late_payment]" id="late_payment" value="1" <?php echo isset($property_info['late_payment']) && $property_info['late_payment'] == '1' ? 'checked="checked"' : ''; ?> > </label>
                                            <input type="number" id="late_pay_percent" name="property[late_pay_percent]" class="form-control" value="<?php echo !empty($property_info['late_pay_percent']) ? $property_info['late_pay_percent'] : '';?>" placeholder="Enter Late Payment Charge %" maxlength="13">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>LPI Effective From</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control pull-right datepicker" id="late_pay_effect_from" name="property[late_pay_effect_from]" value="<?php echo isset($property_info['late_pay_effect_from']) && $property_info['late_pay_effect_from'] != '' && $property_info['late_pay_effect_from'] != '0000-00-00' && $property_info['late_pay_effect_from'] != '1970-01-01' ? date('d-m-Y', strtotime($property_info['late_pay_effect_from'])) : '';?>" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xs-12 no-padding">
                                            <div class="form-group">
                                                <label>LPI Grace&ensp; &ensp;
                                                    <div class="pull-right">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[late_pay_grace_type]" <?php echo isset($property_info['late_pay_grace_type']) && $property_info['late_pay_grace_type'] == 1 ? 'checked="checked"' : '';?> value="1" >Days &ensp; &ensp;
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[late_pay_grace_type]" <?php echo isset($property_info['late_pay_grace_type']) && $property_info['late_pay_grace_type'] == 2 ? 'checked="checked"' : '';?> value="2"  >Amount
                                                        </label>
                                                    </div>
                                                </label>
                                                <input type="number" id="late_pay_grace_value" name="property[late_pay_grace_value]" class="form-control" value="<?php echo !empty($property_info['late_pay_grace_value']) ? $property_info['late_pay_grace_value'] : '';?>" placeholder="Enter Late Payment Grace Period (Days)" maxlength="13">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 div_border" style="padding: 0;margin-top:5px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Utility - Water Settings</b></h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Water Min. Charge&ensp; <input type="checkbox" id="water" name="property[water]" value="1" <?php echo isset($property_info['water']) && $property_info['water'] == '1' ? 'checked="checked"' : ''; ?> > </label>
                                            <input type="number" id="water_min_charg" name="property[water_min_charg]" class="form-control" value="<?php echo !empty($property_info['water_min_charg']) ? $property_info['water_min_charg'] : '';?>" placeholder="Enter Water Min. Charge" maxlength="13">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Charge per m<sup>3</sup>(Rate 1)</label>
                                            <input type="number" name="property[water_charge_per_unit_rate_1]" class="form-control" value="<?php echo !empty($property_info['water_charge_per_unit_rate_1']) ? $property_info['water_charge_per_unit_rate_1'] : '';?>" placeholder="" maxlength="13">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Range&ensp;</label>
                                                <input type="number" name="property[water_charge_range]" class="form-control" value="<?php echo !empty($property_info['water_charge_range']) ? $property_info['water_charge_range'] : '';?>" placeholder="Enter Range" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Charge per m<sup>3</sup>(Rate 2)</label>
                                                <input type="number" name="property[water_charge_per_unit_rate_2]" class="form-control" value="<?php echo !empty($property_info['water_charge_per_unit_rate_2']) ? $property_info['water_charge_per_unit_rate_2'] : '';?>" placeholder="" maxlength="13">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 div_border" style="padding: 0;margin-top:5px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Reminder Settings</b></h3>
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Reminder Grace&ensp;
                                                    <div class="pull-right">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_grace_type]" <?php echo isset($property_info['acb_grace_type']) && $property_info['acb_grace_type'] == 1 ? 'checked="checked"' : '';?> value="1" >Days
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_grace_type]" <?php echo isset($property_info['acb_grace_type']) && $property_info['acb_grace_type'] == 2 ? 'checked="checked"' : '';?> value="2"  >Amount
                                                        </label>
                                                    </div>
                                                </label>
                                                <input type="number" name="property[acb_grace_value]" class="form-control" value="<?php echo !empty($property_info['acb_grace_value']) ? $property_info['acb_grace_value'] : '';?>" placeholder="" maxlength="13">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Unblock Charges&ensp;
                                                    <div class="pull-right">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_unblock_charges_type]" <?php echo isset($property_info['acb_unblock_charges_type']) && $property_info['acb_unblock_charges_type'] == 1 ? 'checked="checked"' : '';?> value="1">Yes &ensp; &ensp; &ensp;
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_unblock_charges_type]" <?php echo isset($property_info['acb_unblock_charges_type']) && $property_info['acb_unblock_charges_type'] == 0 ? 'checked="checked"' : '';?> value="0">No
                                                        </label>
                                                    </div>
                                                </label>
                                                <input type="number" id="late_pay_grace" name="property[acb_unblock_charges_value]" class="form-control" value="<?php echo !empty($property_info['acb_unblock_charges_value']) ? $property_info['acb_unblock_charges_value'] : '';?>" placeholder="" maxlength="13">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-xs-12 no-padding acb_div">
                                        <div class="col-md-12 col-xs-12 no-padding acb_div_inner">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-12"></div>
                                                    <div class="col-md-3 col-xs-12"><label>Days</label></div>
                                                    <div class="col-md-3 col-xs-12"><label>Card block</label></div>
                                                    <div class="col-md-3 col-xs-12"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-12"><label>Reminder 1</label></div>
                                                    <div class="col-md-3 col-xs-12">
                                                        <input type="number" name="property[acb_reminder1_days]" class="form-control" value="<?php echo !empty($property_info['acb_reminder1_days']) ? $property_info['acb_reminder1_days'] : '';?>" placeholder="" maxlength="3">
                                                    </div>
                                                    <div class="col-md-3 col-xs-12" style="margin-top: 5px;">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_block_card]" <?php echo !empty($property_info['acb_block_card']) && $property_info['acb_block_card'] == 1 ? 'checked="checked"' : '';?> value="1">Reminder 1
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 col-xs-12" style="margin-top: 5px;">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[acb_block_card]" <?php echo !empty($property_info['acb_block_card']) && $property_info['acb_block_card'] == 3 ? 'checked="checked"' : '';?> value="3">Don't Block
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 12px; padding-bottom: 10px;">
                                                    <div class="col-md-3 col-xs-12"><label>Reminder 2</label></div>
                                                    <div class="col-md-3 col-xs-12">
                                                        <input type="number" name="property[acb_reminder2_days]" class="form-control" value="<?php echo !empty($property_info['acb_reminder2_days']) ? $property_info['acb_reminder2_days'] : '';?>" placeholder="" maxlength="3">
                                                    </div>
                                                    <div class="col-md-3 col-xs-12">
                                                        <label class="radio-inline" style="margin-top: 5px;">
                                                            <input type="radio" name="property[acb_block_card]" <?php echo isset($property_info['acb_block_card']) && $property_info['acb_block_card'] == 2 ? 'checked="checked"' : '';?> value="2">Reminder 2
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 col-xs-12"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(!empty($acbs) && count($acbs) > 1) {
                                            for ($k=1;$k < count($acbs); $k++) { ?>
                                                <div class="col-md-12 col-xs-12 no-padding acb_div_inner">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <input type="text" name="acb[acb_remin_name][]" class="form-control" value="<?php echo !empty($acbs[$k]['acb_remin_name']) ? $acbs[$k]['acb_remin_name'] : '';?>" placeholder="Enter Reminder Name" maxlength="100">
                                                            <input type="hidden" name="acb[acb_id][]" class="form-control" value="<?php echo !empty($acbs[$k]['acb_id']) ? $acbs[$k]['acb_id'] : '';?>" placeholder="Enter Reminder Name" maxlength="100">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="input-group">
                                                            <input type="number" name="acb[acb_remin_days][]" class="form-control" value="<?php echo !empty($acbs[$k]['acb_remin_days']) ? $acbs[$k]['acb_remin_days'] : '';?>" placeholder="Enter Value" maxlength="13">
                                                            <span class="input-group-btn">
                                                    <button class="btn btn-danger btn-remove-acb" type="button">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php  }
                                        } ?>
                                    </div>
                                </div>

                                <div class="col-md-12 div_border" style="padding: 0;margin-top:5px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>VMS</b></h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Status&ensp;
                                                <div class="pull-right">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[vms_status]" <?php echo isset($property_info['vms_status']) && $property_info['vms_status'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[vms_status]" <?php echo isset($property_info['vms_status']) && $property_info['vms_status'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Access&ensp;
                                                <div class="pull-right">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[vms_access]" <?php echo isset($property_info['vms_access']) && $property_info['vms_access'] == 1 ? 'checked="checked"' : '';?> value="1">SMS &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[vms_access]" <?php echo isset($property_info['vms_access']) && $property_info['vms_access'] == 2 ? 'checked="checked"' : '';?> value="2">Mobile App
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>




                                <div class="col-md-12 div_border" style="padding: 0;margin-top:5px;">
                                    <div class="box-header with-border" style="padding-left:15px ;">
                                        <h3 class="box-title"><b>Mobile App</b></h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Issue/Complaint&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_issue]" <?php echo isset($property_info['mob_app_issue']) && $property_info['mob_app_issue'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_issue]" <?php echo isset($property_info['mob_app_issue']) && $property_info['mob_app_issue'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>e-billing & Payment&ensp;<br />
                                                    <div class="pull-left">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[mob_app_billing]" <?php echo isset($property_info['mob_app_billing']) && $property_info['mob_app_billing'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="property[mob_app_billing]" <?php echo isset($property_info['mob_app_billing']) && $property_info['mob_app_billing'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                        </label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Defect Complaint&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_defect]" <?php echo isset($property_info['mob_app_defect']) && $property_info['mob_app_defect'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_defect]" <?php echo isset($property_info['mob_app_defect']) && $property_info['mob_app_defect'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Facility Booking&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_fasc_book]" <?php echo isset($property_info['mob_app_fasc_book']) && $property_info['mob_app_fasc_book'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_fasc_book]" <?php echo isset($property_info['mob_app_fasc_book']) && $property_info['mob_app_fasc_book'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Property Document&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_pro_doc]" <?php echo isset($property_info['mob_app_pro_doc']) && $property_info['mob_app_pro_doc'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_pro_doc]" <?php echo isset($property_info['mob_app_pro_doc']) && $property_info['mob_app_pro_doc'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Daily Report&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_daily_rpt]" <?php echo isset($property_info['mob_app_daily_rpt']) && $property_info['mob_app_daily_rpt'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_daily_rpt]" <?php echo isset($property_info['mob_app_daily_rpt']) && $property_info['mob_app_daily_rpt'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Survey Form&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_survey_form]" <?php echo isset($property_info['mob_app_survey_form']) && $property_info['mob_app_survey_form'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_survey_form]" <?php echo isset($property_info['mob_app_survey_form']) && $property_info['mob_app_survey_form'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Visit List&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_visit_list]" <?php echo isset($property_info['mob_app_visit_list']) && $property_info['mob_app_visit_list'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_visit_list]" <?php echo isset($property_info['mob_app_visit_list']) && $property_info['mob_app_visit_list'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Prebook&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_prebook]" <?php echo isset($property_info['mob_app_prebook']) && $property_info['mob_app_prebook'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_prebook]" <?php echo isset($property_info['mob_app_prebook']) && $property_info['mob_app_prebook'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Frequent Visit&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_freq_visit]" <?php echo isset($property_info['mob_app_freq_visit']) && $property_info['mob_app_freq_visit'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_freq_visit]" <?php echo isset($property_info['mob_app_freq_visit']) && $property_info['mob_app_freq_visit'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Note to Guard&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_note_to_guard]" <?php echo isset($property_info['mob_app_note_to_guard']) && $property_info['mob_app_note_to_guard'] == 1 ? 'checked="checked"' : '';?> value="1" >Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_note_to_guard]" <?php echo isset($property_info['mob_app_note_to_guard']) && $property_info['mob_app_note_to_guard'] == 0 ? 'checked="checked"' : '';?> value="0"  >Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Panic Alert&ensp;<br />
                                                <div class="pull-left">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_panic_alert]" <?php echo isset($property_info['mob_app_panic_alert']) && $property_info['mob_app_panic_alert'] == 1 ? 'checked="checked"' : '';?> value="1">Active &ensp; &ensp; &ensp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="property[mob_app_panic_alert]" <?php echo isset($property_info['mob_app_panic_alert']) && $property_info['mob_app_panic_alert'] == 0 ? 'checked="checked"' : '';?> value="0">Inactive
                                                    </label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>






                        <div class="col-md-12" >
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <p class="help-block"> * Required Fields.</p>
                                </div>
                            </div>
                        </div>

                        <!-- /.box-body -->
                        <div class="row" style="text-align: right;margin:0 -10px;">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary submit_btn" name="action" value="save_only">Submit</button> &ensp;
                                    <!--button type="submit" class="btn btn-primary submit_btn" name="action" value="save_print">Submit & Print</button> &ensp;-->
                                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
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



<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" _style="width:750px;">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change History</h4>
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
<!--script src="<?php echo base_url();?>assets/js/jquery.number.js"></script-->
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>


<script>

    // Prevent for input type number and it increase / decrease on mouse wheel scroll
    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });
    // Stop enter key event on focus of input type number
    $(document).on( 'keypress', 'input', function (evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
            return false;
        }
    });


    $(document).ready(function () {

        //$('input[type="text"]').css('background-color','#FFEBD2');
        //$('input[type="text"]').css('box-shadow','-3px -3px 3px #ddd')

        $('#tot_sq_feet, #per_sq_feet, #tot_share_unit, #per_share_unit, #sinking_fund').keyup(function(){
            check_bill_due_days();
        });

        $('input[name="property[calcul_base]"]').change(function (){
            check_disabled();
        });

        $('input[name="property[payment_bear_by]"]').change(function (){
            check_transaction_charges_bear_by();;
        });
        check_transaction_charges_bear_by ();

        /*$('#tax_type').change(function (){
         check_tax_fields ();
         });*/

        $('#late_payment').click(function () {
            check_late_payment_fields ();
        });
        check_late_payment_fields ();

        $('#water').change(function (){
            check_water_fields ();
        });
        check_water_fields ();

        $('.property_charg_cls').bind("click",function () {
            $('.modal-title').html('History of '+$(this).attr('data-title'));
            $('.modal-body2').load('<?php echo base_url('index.php/bms_property/getPropertyChargHistDetails/');?>'+$(this).attr('data-property')+'/'+$(this).attr('data-cat'),function(result){
                $('#myModal2').modal({show:true});
            });
        });

        $('.property_prem_quit_cls').bind("click",function () {
            $('.modal-title').html('History of '+$(this).attr('data-title'));
            $('.modal-body2').load('<?php echo base_url('index.php/bms_property/getPropertyPremQuitHistDetails/');?>'+$(this).attr('data-property')+'/'+$(this).attr('data-cat'),function(result){
                $('#myModal2').modal({show:true});
            });
        });

        $('.btn-remove-block').bind('click',function (){
            //$('#'+$(this).attr('data-value')).remove();
            $(this).parents('div.block_div_inner').remove();
        });

        $('.btn-add-block').click(function () {
            var bstr = '<div class="col-md-6 col-xs-12 block_div_inner" style="margin-top:'+($('.block_div_inner').length > 1 ? 15 : 25)+'px;"> <div class="input-group">';
            bstr += '<input class="form-control" name="blocks[block_name][]" type="text" placeholder="Enter Block / Street Name" maxlength="100" />';
            bstr += '<input type="hidden" name="blocks[block_id][]" value="" />'
            bstr += '<span class="input-group-btn">';
            bstr += '<button class="btn btn-danger btn-remove-block" type="button">';
            bstr += '<span class="glyphicon glyphicon-minus"></span>';
            bstr += '</button>';
            bstr += '</span>';
            bstr += '</div></div>';
            $('.block_div').append(bstr);
            $('.btn-remove-block').unbind('click');
            $('.btn-remove-block').bind('click',function (){
                //$('#'+$(this).attr('data-value')).remove();
                $(this).parents('div.block_div_inner').remove();
            });

        });

        $('.btn-remove-tier').bind('click',function (){
            //$('#'+$(this).attr('data-value')).remove();
            $(this).parents('div.tier_div_inner').remove();
        });

        $('.btn-add-tier').click(function () {
            //console.log('called');
            var tstr  = ' <div class="col-md-12 col-xs-12 no-padding tier_div_inner">';
            tstr += ' <div class="col-md-6 col-xs-6">';
            tstr += ' <div class="form-group">';
            //tstr += ' <label>Tier Name</label>';
            tstr += ' <input type="text" name="tier[tier_name][]" class="form-control" value="" placeholder="Enter Tier Name" maxlength="100">';
            tstr += ' <input type="hidden" name="tier[tier_id][]" value="" />'
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' <div class="col-md-6 col-xs-6">';
            //tstr += ' <label>Value</label>';
            tstr += ' <div class="input-group">';
            tstr += ' <input type="number" name="tier[tier_value][]" class="form-control" value="" placeholder="Enter Value" maxlength="13">';
            tstr += ' <span class="input-group-btn">';
            tstr += ' <button class="btn btn-danger btn-remove-tier" type="button">';
            tstr += ' <span class="glyphicon glyphicon-minus"></span>';
            tstr += ' </button>';
            tstr += ' </span>';
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' </div>';
            $('.tier_div').append(tstr);
            $('.btn-remove-tier').unbind('click');
            $('.btn-remove-tier').bind('click',function (){
                //$('#'+$(this).attr('data-value')).remove();
                $(this).parents('div.tier_div_inner').remove();
            });

        });

        $("#developer_under").change(function () {
            if ( $(this).val() == "3" ) {
                $(".developer_div").removeClass('hide_developer_div');
            } else {
                $(".developer_div").addClass('hide_developer_div');
            }
        });

        $('.btn-add-developer').click(function () {
            //console.log('called');
            var tstr  = ' <div class="col-md-12 col-xs-12 no-padding developer_div_inner">';
            tstr += ' <div class="col-md-6 col-xs-6">';
            tstr += ' <div class="form-group">';
            tstr += ' <input type="text" data-id="" name="developer[email_addr][]" class="form-control" value="" placeholder="Enter Email" maxlength="200">';
            tstr += ' <input type="hidden" name="developer[property_dev_id][]" value="" />'
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' <div class="col-md-6 col-xs-6">';
            //tstr += ' <label>Value</label>';
            tstr += ' <div class="input-group">';
            tstr += ' <input type="text" name="developer[password][]" class="form-control" value="" placeholder="Enter Password" maxlength="250">';
            tstr += ' <span class="input-group-btn">';
            tstr += ' <button class="btn btn-danger btn-remove-developer" type="button">';
            tstr += ' <span class="glyphicon glyphicon-minus"></span>';
            tstr += ' </button>';
            tstr += ' </span>';
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' </div>';
            $('.developer_div').append(tstr);
        });


        $('.btn-remove-acb').bind('click',function (){
            //$('#'+$(this).attr('data-value')).remove();
            $(this).parents('div.acb_div_inner').remove();
        });
        $('.btn-add-acb').click(function () {
            //console.log('called');
            var tstr  = ' <div class="col-md-12 col-xs-12 no-padding acb_div_inner">';
            tstr += ' <div class="col-md-6 col-xs-6">';
            tstr += ' <div class="form-group">';
            //tstr += ' <label>Tier Name</label>';
            tstr += ' <input type="text" name="acb[acb_remin_name][]" class="form-control" value="" placeholder="" maxlength="100">';
            tstr += ' <input type="hidden" name="acb[acb_id][]" value="" />'
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' <div class="col-md-6 col-xs-6">';
            //tstr += ' <label>Value</label>';
            tstr += ' <div class="input-group">';
            tstr += ' <input type="number" name="acb[acb_remin_days][]" class="form-control" value="" placeholder="" maxlength="13">';
            tstr += ' <span class="input-group-btn">';
            tstr += ' <button class="btn btn-danger btn-remove-acb" type="button">';
            tstr += ' <span class="glyphicon glyphicon-minus"></span>';
            tstr += ' </button>';
            tstr += ' </span>';
            tstr += ' </div>';
            tstr += ' </div>';
            tstr += ' </div>';
            $('.acb_div').append(tstr);
            $('.btn-remove-acb').unbind('click');
            $('.btn-remove-acb').bind('click',function (){
                //$('#'+$(this).attr('data-value')).remove();
                $(this).parents('div.acb_div_inner').remove();
            });

        });

        //$('#tot_sq_feet, #per_sq_feet, #tot_share_unit, #per_share_unit').number( true, 2 );

        /** Form validation */

        $( "#bms_frm" ).validate({
            rules: {
                "property[property_name]": "required",
                "property[property_type]": "required",
                "property[property_abbrev]": "required",
                "property[total_units]":"required",
                "property[property_under]":"required",
                "property[calcul_base]":"required"/*,
                 "property[sinking_fund]":"required"*/
            },
            messages: {
                "property[property_name]": "Please enter Property Name",
                "property[property_type]": "Please select Property Type",
                "property[property_abbrev]": "Please enter Property Abbreviation",
                "property[total_units]":"Please enter Total Units",
                "property[property_under]":"Please select Property Under",
                "property[calcul_base]":"Please select Calculation based on"/*,
                 "property[sinking_fund]":"Please enter Sinking Fund %"*/
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
            }
        });

        $('.reset_btn').click(function () {
            //console.log('reset clicked');
            $('input[type=file]').val('');
            $('#upload-file-info').html('');
        });

    });

    $(document).on( 'click', '.btn-remove-developer', function (evt) {
        $(this).parents('div.developer_div_inner').remove();
    });

    $(document).on( 'blur', 'input[name="developer[email_addr][]"]', function (evt) {
        // checkEmailAddressExists ( $(this).data("id"), $(this).val(), $(this) );
    });

    function checkEmailAddressExists ( property_dev_id , email_addr, obj) {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_property/check_email_addr_exists');?>',
            data: {'email_addr':email_addr, 'property_dev_id': property_dev_id},
            datatype:"json", // others: xml, json; default is html
            success: function(data) {
                if ( data == 1 ) {
                    alert ( 'Developer with this email address already Exists!' );
                    obj.val('');
                    obj.focus();
                }
            },
            error: function (e) {
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

    function check_disabled () {
        if ($('input[name="property[calcul_base]"]:checked').val() == 1) {
            $('#tot_sq_feet_container').attr('style','display:block;');
            $('#tot_share_unit_container').attr('style','display:none;');
            $('#amount_container').attr('style','display:none;');
        } else if($('input[name="property[calcul_base]"]:checked').val() == 2) {
            $('#tot_sq_feet_container').attr('style','display:none;');
            $('#tot_share_unit_container').attr('style','display:block;');
            $('#amount_container').attr('style','display:none;');
        } else {
            $('#tot_sq_feet_container').attr('style','display:none;');
            $('#tot_share_unit_container').attr('style','display:none;');
            $('#amount_container').attr('style','display:block;');
        }
    }

    function check_transaction_charges_bear_by () {
        if ($('input[name="property[payment_bear_by]"]:checked').val() == 1) {
            $('input[name="property[payment_fpx]"').attr('disabled','disabled').val('');
            $('input[name="property[payment_cc_card]"').attr('disabled','disabled').val('');
        } else if($('input[name="property[payment_bear_by]"]:checked').val() == 2) {
            $('input[name="property[payment_fpx]"').removeAttr('disabled');
            $('input[name="property[payment_cc_card]"').removeAttr('disabled');
        }
    }




    function check_tax_fields () {
        if($('#tax_type').val() == 1 || $('#tax_type').val() == 2 ) {
            $('#tax_percentage').removeAttr('disabled');
        }  else {
            $('#tax_percentage').val('');
            $('#tax_percentage').attr('disabled','disabled');
        }
    }

    function check_late_payment_fields () {
        if($('#late_payment').is(":checked") ) {
            $('#late_pay_percent').removeAttr('disabled');
            $('#late_pay_grace_value').removeAttr('disabled');
            $('#late_pay_effect_from').removeAttr('disabled');
        }  else {
            $('#late_pay_percent').val('');
            $('#late_pay_grace_value').val('');
            $('#late_pay_effect_from').val('');
            $('#late_pay_percent').attr('disabled','disabled');
            $('#late_pay_grace_value').attr('disabled','disabled');
            $('#late_pay_effect_from').attr('disabled','disabled');
            $('input[name="property[late_pay_grace_type]"]:checked').each(function(){
                this.checked = false;
            });
        }
    }

    function check_electricity_fields () {
        if($('#electricity').is(":checked") ) {
            $('#electricity_min_charg').removeAttr('disabled');
            $('#electricity_charge_per_unit').removeAttr('disabled');
        }  else {
            $('#electricity_min_charg').val('');
            $('#electricity_charge_per_unit').val('');
            $('#electricity_min_charg').attr('disabled','disabled');
            $('#electricity_charge_per_unit').attr('disabled','disabled');
        }
    }

    function check_water_fields () {
        /*if ($('#water').is(":checked") ) {
         $('#water_min_charg').removeAttr('disabled');
         $('#water_charge_per_unit').removeAttr('disabled');
         }  else {
         $('#water_min_charg').val('');
         $('#water_charge_per_unit').val('');
         $('#water_min_charg').attr('disabled','disabled');
         $('#water_charge_per_unit').attr('disabled','disabled');
         }*/
    }

    function check_bill_due_days () {
        //bill_due_days #tot_sq_feet, #per_sq_feet, #tot_share_unit, #per_share_unit
        //console.log($('input[name="property[calcul_base]"]:checked').val());
        /*if($('input[name="property[calcul_base]"]:checked').val() == 1 || $('input[name="property[calcul_base]"]:checked').val() == 2) {
         $('#sinking_fund').val($('#sinking_fund').val().replace(/^\s+|\s+$/g,""));
         if($('#sinking_fund').val() == '') {
         alert('Please enter Sinking Fund %'); $('#sinking_fund').focus(); return false;
         }

         $('#tot_sq_feet').val($('#tot_sq_feet').val().replace(/^\s+|\s+$/g,""));
         $('#per_sq_feet').val($('#per_sq_feet').val().replace(/^\s+|\s+$/g,""));
         $('#tot_share_unit').val($('#tot_share_unit').val().replace(/^\s+|\s+$/g,""));
         $('#per_share_unit').val($('#per_share_unit').val().replace(/^\s+|\s+$/g,""));
         if($('#tot_sq_feet').val() != '' && $('#per_sq_feet').val() != '' && $('#per_sq_feet').val() != 0) {
         var mon_bill = eval($('#tot_sq_feet').val())*eval($('#per_sq_feet').val());
         mon_bill = mon_bill + (mon_bill*eval($('#sinking_fund').val())/100);
         $('#bill_due_days').val(mon_bill.toFixed(2) );
         $('#bill_due_days').attr('readonly','true');
         $('#tot_share_unit').attr('disabled','disabled');
         $('#per_share_unit').attr('disabled','disabled');
         } else if($('#tot_share_unit').val() != '' && $('#per_share_unit').val() != '' && $('#per_share_unit').val() != 0) {
         var mon_bill = eval($('#tot_share_unit').val())*eval($('#per_share_unit').val());
         mon_bill = mon_bill + (mon_bill*eval($('#sinking_fund').val())/100);
         $('#bill_due_days').val(mon_bill.toFixed(2));
         $('#bill_due_days').attr('readonly','true');
         $('#tot_sq_feet').attr('disabled','disabled');
         $('#per_sq_feet').attr('disabled','disabled');
         //console.log('else if');
         } else { //
         $('#bill_due_days').attr('readonly','true');
         if($('input[name="property[calcul_base]"]:checked').val() == 1) {
         $('#tot_sq_feet').removeAttr('disabled');
         $('#per_sq_feet').removeAttr('disabled');
         $('#tot_share_unit').attr('disabled','disabled');
         $('#per_share_unit').attr('disabled','disabled');

         } else if($('input[name="property[calcul_base]"]:checked').val() == 2) {
         $('#tot_share_unit').removeAttr('disabled');
         $('#per_share_unit').removeAttr('disabled');
         $('#tot_sq_feet').attr('disabled','disabled');
         $('#per_sq_feet').attr('disabled','disabled');
         }


         }
         } else { //$('input[name="property[calcul_base]"]:checked').val() == 1
         $('#bill_due_days').removeAttr('readonly');
         $('#tot_share_unit').removeAttr('disabled');
         $('#per_share_unit').removeAttr('disabled');
         $('#tot_sq_feet').removeAttr('disabled');
         $('#per_sq_feet').removeAttr('disabled');
         }*/


        //console.log('monthly billing '+$('#bill_due_days').val()+' tot_sq_feet- '+$('#tot_sq_feet').val()+' per_sq_feet- '+$('#per_sq_feet').val()+' tot_share_unit- '+$('#tot_share_unit').val()+' per_share_unit- '+$('#per_share_unit').val() );
    }

    check_disabled();
    //check_bill_due_days ();
    //check_tax_fields ();
    //check_late_payment_fields ();
    //check_electricity_fields ();
    //check_water_fields ();

    $(function () {
//Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            'minDate': new Date()
        });

    });
</script>