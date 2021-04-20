<?php
$this->load->view('header');
$this->load->view('sidebar'); // echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet"
	href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
	<!-- Content Header (Page header) -->
	<section class="content-header">

		<h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>

	</section>

	<!-- Main content -->
	<section class="content container-fluid cust-container-fluid">
        <?php
        $last_itm_cnt = 0;
        ?>
      <!-- general form elements -->
		<div class="box box-primary">
        <?php

        if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
            unset($_SESSION['flash_msg']);
        }

        ?>
        <div class="box-body">
		<div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;padding-bottom:10px;">
          <div class="row" style="background-color: #d2cece; height: 50px;" >
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($poitem['pur_order_id']) ? 'Update PO ('.$poitem['po_no'].')' : 'New Purchase Order';?> </h3>
          </div>
				<form role="form" id="bms_frm"
					action="<?php echo base_url('index.php/bms_fin_purchase_order/add_purchase_order');?>"
					method="post" enctype="multipart/form-data">

					<div class="row" style="padding-top: 15px;padding-bottom:15px;">
						<input type="hidden" id="po_id" name="po_id"
							value="<?php echo $_SESSION['bms_default_property'];?>" />

						<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
							<div class="col-md-2">
								<label>Property Name *</label>
							</div>
							<div class="col-md-4">
								<select class="form-control" id="property_id"
									name="property_id" disabled="disabled">
									<option value="">Select</option>
                             			 <?php 
                                        foreach ($properties as $key=>$val) {
                                            $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                              		</select>
                              <!-- Hidden fields -->
                                <input type="hidden" name="poitem[pur_order_id]" value="<?php echo !empty($poitem['pur_order_id']) ? $poitem['pur_order_id'] : '';?>" />
                  				<input type="hidden" name="poitem[po_no]" value="<?php echo !empty($poitem['po_no']) ? $poitem['po_no'] : '';?>" />
                              <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
							</div>

							<div class="col-md-2">
								<label>Date *</label>
							</div>

							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input class="form-control pull-right datepicker"
										name="po_date" type="text" value="<?php echo !empty($poitem['date']) ? date('d-m-Y',strtotime($poitem['date'])) : date("d-m-Y"); ?>" />
								</div>
								<!-- /.input group -->
							</div>
						</div>

						<div class="col-md-12 col-sm-12 col-xs-12 no-padding"
							style="padding-top: 15px !important;">
							<div class="col-md-2">
								<label>Service Provider *</label>
							</div>
							<div class="col-md-4">
								<select class="form-control select2" required id="service_provider" name="service_provider">
									<option value="">Select</option>
                                    <?php
                                    foreach ($service_provider as $key => $val) {
                                        $selected = isset($poitem['service_provider_id']) && $poitem['service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
                                        echo "<option value='" . $val['service_provider_id'] . "' 
                                                  data-spaddress='" . $val['address'] . "' 
                                                  data-sppostcode='" . $val['postcode'] . "'
                                                  data-spcity='" . $val['city'] . "'
                                                  data-spstate='" . $val['state'] . "'
                                                  data-spcountry='" . $val['country_name'] . "'
                                                  data-spphoneno='" . $val['office_ph_no'] . "'
                                                  data-sppinc='" . $val['person_incharge'] . "'
                                                  data-sppincmobile='" . $val['person_inc_mobile'] . "'
                                                  data-sppincemail='" . $val['person_inc_email'] . "'
                                                  " . $selected . ">" . $val['provider_name'] . "</option>";
                                    }
                                    ?>
                                </select>
							</div>

							<div class="col-md-2">
								<label>Delivery Date</label>
							</div>

							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input class="form-control pull-right datepicker"
										name="delivery_date" type="text" value="<?php echo !empty($poitem['delivery_date']) ? date('d-m-Y',strtotime($poitem['delivery_date'])) : date('d-m-Y'); ?>"/>
								</div>
								<!-- /.input group -->
							</div>

 
						</div>
						<?php 
						if($poitem['address']!=""){
						    $addcont = "block";
						}else{
						    $addcont = "none";
						}
						?>
						<div id="address_contianer" class="address_contianer" style="display:<?php echo $addcont;?>">
						<div class="col-md-12 col-sm-12 col-xs-12 no-padding address_display" style="padding-top: 15px !important;">
							<div class="col-md-2">
								<label>Address</label>
							</div>
		 					<div class="col-md-8">
								 <span id="spaddress"><?php echo $poitem['address'];?></span>,&nbsp;
								 <span id="spcity"><?php echo $poitem['city'];?></span>,&nbsp;
								 <span id="spstate"><?php echo $poitem['state'];?></span>,&nbsp;
								 <span id="spcountry"><?php echo $poitem['state'];?></span>,&nbsp;
								 <span id="sppostcode"><?php echo $poitem['postcode'];?></span>
							</div>
 						</div>
						 
						<div class="col-md-12 col-sm-12 col-xs-12 no-padding incha_name" style="padding-top: 15px !important;">
							<div class="col-md-2">
								<label>Person Incharge Name</label>
							</div>
							<div class="col-md-4">
								<span id="sppinc"><?php echo $poitem['person_incharge'];?></span> 
							</div>
 						</div>
						
						 
						<div class="col-md-12 col-sm-12 col-xs-12 no-padding incha_phne_num" style="padding-top: 15px !important;">
							<div class="col-md-2">
								<label>Phone No</label>
							</div>
					 
		 					<div class="col-md-4">
								Office :  <span id="spphoneno"><?php echo $poitem['office_ph_no'];?></span> <br>
								Mobile :  <span id="sppincmobile"><?php echo $poitem['person_inc_mobile'];?></span> 
							</div>
 						</div>
 
 						<div class="col-md-12 col-sm-12 col-xs-12 no-padding incha_email" style="padding-top: 15px !important;">
							<div class="col-md-2">
								<label>Person Incharge Email</label>
							</div>
							<div class="col-md-4">
								<span id="sppincemail"><?php echo $poitem['person_inc_email'];?></span> 
							</div>
						</div>
 					</div>
 					</div>
					<!-- /.row -->

					<div class="col-md-12 no-padding">
						<div class="col-md-2 no-padding">
							<h2 style="color: #afa8a8;">Items</h2>
						</div>
						<div class="col-md-10" style="margin-top: 25px;">
							<button type="button" class="btn  btn-primary add_sub_sop_btn pull-right"
								id="sop_<?php echo count($po_sub_items);?>" value="0" data-value="0"
								data-id="<?php echo (count($po_sub_items)? (count($po_sub_items)+1):2);?>">Add Item</button>
						</div>

						<div class="col-md-12 items_container add_more_item no-padding">
							 
							<div class="col-md-12 item_div_1"
								style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
								<div class="col-md-12 no-padding">
									<div class="col-md-6">
										<div class="form-group">
											<label>Description</label>
											<textarea name="items[description][]" class="form-control"
												style="-webkit-border-radius: 4px !important; border-radius: 4px !important;"
												rows="5" placeholder="Enter Description"><?php echo isset($po_sub_items[0]['description']) && $po_sub_items[0]['description'] != '' ? $po_sub_items[0]['description'] : '';?></textarea>
										</div>
									</div>

									<div class="col-md-4">
										<div class="col-md-12">
											<div class="form-group">
												<label>For Asset </label> <select
													class="form-control select2 assetlst" name="items[assetlst][]">
													<option value="">Select</option>
                                                <?php 
                                                foreach ($property_assets as $key => $val) {
                                                    $selected = isset( $po_sub_items[0]['asset_id']) && trim( $po_sub_items[0]['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='" . $val['asset_id'] . "'                                                         
                                                              " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                }
                                                ?>

                                            </select>
											</div>
										</div>
									</div>
									 
									
									<div class="col-md-4">
										<div class="col-md-12">
											<div class="form-group">
												<label>Account Name * </label> <select required
													class="form-control select2 cate" name="items[category][]"
													data-id="1" id="cate_1">
													<option value="">Select</option>
                                                <?php
                                                $group_name = "";
                                                foreach ($expense_items as $key => $val) :
                                                $selected = isset( $po_sub_items[0]['coa_id']) && trim( $po_sub_items[0]['coa_id']) == $val['coa_id'] ? 'selected="selected" ' : '';
                                                    if ($val['coa_type_name'] != $group_name) {
                                                        if ($endLabel) {
                                                            echo '</optgroup>';
                                                        }
                                                        // echo label...
                                                        echo '<optgroup label="' . $val['coa_type_name'] . '">';
                                                        $group_name = $val['coa_type_name'];
                                                        $endLabel = true;
                                                    }
                                                    // $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='" . $val['coa_id'] . "'                                                    
                                                              " . $selected . ">" . $val['coa_name'] . "</option>";
                                                endforeach;
                                                ?>
                                            </select>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-12 no-padding">
                                    <div class="col-md-6 no-padding">
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Quantity </label> <input class="form-control amt_quan"
                                                    name="items[quantity][]" value="<?php echo $po_sub_items[0]['qty'];?>" data-id="1" id="subquantity_1"
                                                    type="text" min="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>UOM</label> <input class="form-control uom" 
                                                    name="items[uom][]"value="<?php echo $po_sub_items[0]['uom'];?>"  data-id="1" id="uom_1" type="text" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Unit Price</label> <input
                                                    class="form-control amt_unit"  data-id="1"
                                                    id="unitprice_1" name="items[subunitprice][]" value="<?php echo ($po_sub_items[0]['unit_price']!='')?$po_sub_items[0]['unit_price']:0;?>"  type="text" min="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Discount Amt</label> <input
                                                    class="form-control amt_disct" value="<?php echo !empty($po_sub_items[0]['discount_amt']) ? $po_sub_items[0]['discount_amt'] : 0;?>"  name="items[distamount][]"
                                                    data-id="1" id="subdistamount_1" type="text" min="0" />
                                            </div>
                                        </div>
                                      
                                    </div>
                                    <div class="col-md-6 no-padding">
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Amount</label> <input readonly
                                                    class="form-control amt_cal" value="<?php echo $po_sub_items[0]['amount'];?>" name="items[amount][]"
                                                    data-id="1" id="subamount_1" type="text" min="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Tax (%)</label> <input
                                                    class="form-control amtitemtaxper" value="<?php echo !empty($po_sub_items[0]['tax_percent']) ? $po_sub_items[0]['tax_percent'] : 0;?>" 
                                                    data-id="1" id="amtitemtaxper_1" name="items[amtitemtaxper][]" type="text" min="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 two-padding-left">
                                            <div class="form-group">
                                                <label>Tax Amt</label> <input readonly
                                                    class="form-control amtitemtaxamt" value="<?php echo !empty($po_sub_items[0]['tax_amt']) ? $po_sub_items[0]['tax_amt'] : 0;?>" 
                                                    data-id="1" id="amtitemtaxamt_1" name="items[amtitemtaxamt][]" type="text" min="0" />
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-3 two-padding-right">
                                            <div class="form-group">
                                                <label>Item Amount</label> <input readonly
                                                    class="form-control amt_net" value="<?php echo $po_sub_items[0]['net_amount'];?>"  name="items[netamount][]"
                                                    data-id="1" id="subnetamount_1" type="text" min="0" />
                                            </div>
                                        </div>
                                    </div>
								</div>
								<input type="hidden" name="items[po_item_id][]" value="<?php echo !empty($po_sub_items[0]['po_item_id']) ? $po_sub_items[0]['po_item_id'] : '';?>" />
							</div>
						 
				        <div class="col-md-12 col-xs-12 no-padding">   
                            <?php
                                $sub_sop_cnt = 0;
                                if (! empty($poitem['po_no'])) { // SUB Routine Task
                                    $total_count = count($po_sub_items);
                                    if($total_count>1){
                                        $array_slice = array_slice($po_sub_items,1);
                                        foreach ($array_slice as $keyslice => $valslice)
                                        {
                                            $dataid_incre = $keyslice+2;
                                        ?>
                                        <div class="col-md-12 item_div_<?php echo ($dataid_incre); ?> sub_sop_<?php echo ($dataid_incre); ?>" style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                            <div class="col-md-12 no-padding">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <textarea name="items[description][]" class="form-control"
                                                            style="-webkit-border-radius: 4px !important; border-radius: 4px !important;"
                                                            rows="5" placeholder="Enter Description"><?php echo isset($valslice['description']) && $valslice['description'] != '' ? $valslice['description'] : '';?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>For Asset </label> <select
                                                                class="form-control select2 assetlst" name="items[assetlst][]">
                                                                <option value="">Select</option>
                                                            <?php 
                                                            foreach ($property_assets as $key => $val) {
                                                                $selected = isset( $valslice['asset_id']) && trim( $valslice['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                                echo "<option value='" . $val['asset_id'] . "'                                                         
                                                                        " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                            }
                                                            ?>

                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-right">
                                                    <button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="<?php echo $sub_sop_cnt; ?>'" data-id="<?php echo $dataid_incre; ?>" data-subsopid=""><i class="fa fa-close"></i></button>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Account Name </label> 
                                                            <select 
                                                                class="form-control select2 cate" name="items[category][]"
                                                                data-id="<?php echo $dataid_incre;?>" id="cate_<?php echo $dataid_incre;?>">
                                                                <option value="">Select</option>
                                                                    <?php
                                                                        $group_name = "";
                                                                        foreach ($expense_items as $key => $val) :
                                                                        $selected = isset( $valslice['coa_id']) && trim( $valslice['coa_id']) == $val['coa_id'] ? 'selected="selected" ' : '';
                                                                            if ($val['coa_type_name'] != $group_name) {
                                                                                if ($endLabel) {
                                                                                    echo '</optgroup>';
                                                                                }
                                                                                // echo label...
                                                                                echo '<optgroup label="' . $val['coa_type_name'] . '">';
                                                                                $group_name = $val['coa_type_name'];
                                                                                $endLabel = true;
                                                                            }
                                                                            // $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                                            echo "<option value='" . $val['coa_id'] . "'                                                    
                                                                                        " . $selected . ">" . $val['coa_name'] . "</option>";
                                                                        endforeach;
                                                                    ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 no-padding">
                                                <div class="col-md-6 no-padding">
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Quantity </label> <input class="form-control amt_quan"
                                                                name="items[quantity][]" value="<?php echo $valslice['qty'];?>" data-id="<?php echo $dataid_incre;?>" id="subquantity_<?php echo $dataid_incre;?>"
                                                                type="text" min="0" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>UOM</label> <input class="form-control uom" 
                                                                name="items[uom][]" value="<?php echo $valslice['uom'];?>"  data-id="<?php echo $dataid_incre;?>" id="uom_<?php echo $dataid_incre;?>" type="text" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Unit Price</label> <input
                                                                class="form-control amt_unit"  data-id="<?php echo $dataid_incre;?>"
                                                                id="unitprice_<?php echo $dataid_incre;?>" name="items[subunitprice][]" value="<?php echo $valslice['unit_price'];?>"  type="text" min="0" />
                                                        </div>
                                                    </div>

                                                    

                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Discount Amt</label> <input
                                                                class="form-control amt_disct" value="<?php echo !empty($valslice['discount_amt']) ? $valslice['discount_amt'] : 0;?>"  name="items[distamount][]"
                                                                data-id="<?php echo $dataid_incre;?>" id="subdistamount_<?php echo $dataid_incre;?>" type="text"  min="0" />
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 no-padding">
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Amount</label> <input readonly
                                                                class="form-control amt_cal" value="<?php echo $valslice['amount'];?>" name="items[amount][]"
                                                                data-id="<?php echo $dataid_incre;?>" id="subamount_<?php echo $dataid_incre;?>" type="text" min="0" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Tax (%)</label> <input
                                                                class="form-control amtitemtaxper" value="<?php echo $valslice['tax_percent'];?>" 
                                                                data-id="<?php echo $dataid_incre;?>"  id="amtitemtaxper_<?php echo $dataid_incre;?>" name="items[amtitemtaxper][]" type="text" min="0" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 two-padding-left">
                                                        <div class="form-group">
                                                            <label>Tax Amt</label> <input readonly
                                                                class="form-control amtitemtaxamt"  value="<?php echo $valslice['tax_amt'];?>" 
                                                                data-id="<?php echo $dataid_incre;?>" id="amtitemtaxamt_<?php echo $dataid_incre;?>" name="items[amtitemtaxamt][]" type="text" min="0" />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 two-padding-right">
                                                        <div class="form-group">
                                                            <label>Item Amount</label> <input readonly
                                                                class="form-control amt_net" value="<?php echo $valslice['net_amount'];?>"  name="items[netamount][]"
                                                                data-id="<?php echo $dataid_incre;?>" id="subnetamount_<?php echo $dataid_incre;?>" type="text"  min="0" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <input type="hidden" id="po_item_id_<?php echo $dataid_incre; ?>" name="items[po_item_id][]" value="<?php echo !empty($valslice['po_item_id']) ? $valslice['po_item_id'] : '';?>" />
                                            <?php
                                         }
                                    }
                                }
                            ?>
    					</div>
    						
    						<div class="col-md-12 col-xs-12 no-padding sub_sop_container_0">
    						</div>
						
						<div class="col-md-12 items_container col-xs-6" style="margin-top: 15px;">
							<input type="hidden" name="rstsubtot" id="rstsubtot"  value="0">
							<input type="hidden" name="maindiscount" id="maindiscount"  value="0">
							<input type="hidden" name="mainntat" id="mainntat"  value="0">
						    <input type="hidden" name="taxpers" id="taxpers"  value="0">
							<input type="hidden" name="taxamt" id="taxamt"  value="0">
						  
                          <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                            <div class="col-md-8 col-xs-6">&nbsp;</div>
                            <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                              <label>Total (RM)</label>
                            </div>
                            <div class="col-md-2 col-xs-12 ">
                              <input type="text" class="maintotat form-control" name="maintotat" id="maintotat" value="<?php echo (($poitem['total']!='')?$poitem['total']:0);?>" style="text-align: right;" readonly="true">
                            </div>   
                          </div>

                          <div class="col-md-12 no-padding" style="padding: 10px 0 !important;">
			                <div class="col-md-2 col-xs-6">
			                  <h3>
			                    <b>Remarks</b>
			                  </h3> 
			                </div>

			                <div class="col-md-6 col-xs-12" >
			                  <textarea rows="4" name="remarks" class="form-control" cols="50"><?php echo !empty($poitem['remarks'])? $poitem['remarks'] : '';?></textarea>
			                </div>
			              </div>
			               <div style="color:red;padding: 15px 15px !important;"> (*) indicates mandatory fields.</div>

			                 	<div class="row" style="text-align: right; margin: 0 -10px;">
								<div class="col-md-8">
									<div class="box-footer">
										<button type="submit" class="btn btn-primary submit_btn"
											name="action" value="save_only">Submit</button>
										&ensp;
										<!--button type="submit" class="btn btn-primary submit_btn" name="action" value="save_print">Submit & Print</button> &ensp;-->
										<button type="Reset" class="btn btn-default reset_btn">Reset</button>
										&ensp;&ensp;
									</div>
								</div>
							</div>

  						</div>
 					</div>
					<!-- /.row -->

				
 				</form>
 			</div>
			<!-- /.box-body -->

		</div>
		<!-- /.box -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php $this->load->view('footer'); ?>
<!-- bootstrap datepicker -->
<script
	src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script
	src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<!-- SELECT 2 -->
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>
$(document).ready(function () {

    $('#bms_frm').submit(function() {
        $('.submit_btn').prop("disabled", "disabled");
        setTimeout(function(){ $('.submit_btn').prop('disabled', false); }, 3000);
    });

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    $(document).on( 'keypress', 'input', function (evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
            return false;
        }
    });

    $('.select2').select2();
    $('#service_provider').change(function () {
        var ser_val = $(this).val();
         if(ser_val==''){
			$('.address_contianer').hide();
			 
         }else{
        	$('.address_contianer').show(); 
         }
        set_service_provider_info();
    });

     $( "#bms_frm" ).validate({
		rules: {
            "po[po_date]": "required",
			"po[service_provider]": "required"
		},
		messages: {
		    "po[po_date]": "Please select Date",
			"po[service_provider]": "Please select Service Provider"
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

});

function set_service_provider_info() {
        $('#spaddress').html('');
        $('#sppostcode').html('');
        $('#spcity').html('');
        $('#spstate').html('');
        $('#spcountry').html('');
        $('#spphoneno').html('');
        $('#sppinc').html('');
        $('#sppincmobile').html('');
        $('#sppincemail').html('');

        if(typeof($('#service_provider').find('option:selected').data('spaddress')) != 'undefined') {
            $('#spaddress').html($('#service_provider').find('option:selected').data('spaddress'));
        }
        if(typeof($('#service_provider').find('option:selected').data('sppostcode')) != 'undefined') {
            $('#sppostcode').html($('#service_provider').find('option:selected').data('sppostcode'));
        }
        if(typeof($('#service_provider').find('option:selected').data('spcity')) != 'undefined'){
            $('#spcity').html($('#service_provider').find('option:selected').data('spcity'));
        }
        if(typeof($('#service_provider').find('option:selected').data('spstate')) != 'undefined') {
            $('#spstate').html($('#service_provider').find('option:selected').data('spstate'));
        }
        if(typeof($('#service_provider').find('option:selected').data('spcountry')) != 'undefined'){
        	 $('#spcountry').html($('#service_provider').find('option:selected').data('spcountry')); 
            //$('#spcountry option[value='+$('#service_provider').find('option:selected').data('spcountry')+']').prop('selected', true);
        }
        if(typeof($('#service_provider').find('option:selected').data('spphoneno')) != 'undefined') {
            $('#spphoneno').html($('#service_provider').find('option:selected').data('spphoneno'));
        }
        if(typeof($('#service_provider').find('option:selected').data('sppinc')) != 'undefined') {
            $('#sppinc').html($('#service_provider').find('option:selected').data('sppinc'));
        }
        if(typeof($('#service_provider').find('option:selected').data('sppincmobile')) != 'undefined'){
            $('#sppincmobile').html($('#service_provider').find('option:selected').data('sppincmobile'));
        }
        if(typeof($('#service_provider').find('option:selected').data('sppincemail')) != 'undefined') {
            $('#sppincemail').html($('#service_provider').find('option:selected').data('sppincemail'));
        }
        //console.log($('#service_provider').find('option:selected').data('defaulter'));
}


/*
$('.cate').change(function () {
	debugger;
	var subcate = "subcate_"+$(this).attr('data-id');
	category_changes($(this).val(),subcate);
});
 */

var sop_cnt = eval("<?php echo $last_itm_cnt;?>");
var sub_sop_cnt = eval("<?php echo $sub_sop_cnt;?>");
$('.add_sub_sop_btn').click(function () {
	var id = $(this).attr('id');
	var val = $(this).val();
	var parent_id =$(this).attr('data-value') 
	var dataid = $(this).attr('data-id')

    var str = '<div class="col-md-12 item_div_1 sub_sop_'+id+' sub_sop_'+sub_sop_cnt+'"  style="background-color:#ECF0F5;margin: 10px 0 5px 0;padding:10px 0 !important;border: 1px solid #999;border-radius: 5px;">';
    str+= '<div class="col-md-12">';
	 str += '</div>';
    str += '<div class="col-md-12 no-padding">';
    str +='<div class="col-md-6">';
    str +='<div class="form-group">';
    str +='<label >Description</label>';
    str +='<textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important;border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo isset($service_provider['job_scope']) && $service_provider['job_scope'] != '' ? $service_provider['job_scope'] : '';?></textarea>';
    str +='</div>';
    str +='</div>';
    str += '<div class="col-md-4">';
    str  +='<div class="col-md-12">';
    str  +='<div class="form-group" >';
    str  +='<label >For Asset </label>';
    str  +='<select class="form-control select2 assetlst" size="3" name="items[assetlst][]">';
    str  +='<option value="">Select</option>';
    str  +='<?php
    foreach ($property_assets as $key => $val) {
        echo "<option value=" . $val[asset_id] . ">" . $val[asset_name] . "-" . $val[asset_location] . "</option>";
    }
    ?>';
    str  +='</select>';
    str  +='</div>';
    str  +='</div>';
    str  +='</div>';
    str +='<div class="col-md-2 text-right">';
    str += '<button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="'+(sub_sop_cnt++)+'"  data-id="'+dataid+'" data-subsopid=""><i class="fa fa-close"></i></button>';
    str +='</div>';
										
    str += '<div class="col-md-4">';
    str += '<div class="col-md-12">';
    str += '<div class="form-group">';
    str += '<label >Account Name</label>';
    str += '<select class="form-control select2 cate" required name="items[category][]" id="cate_'+dataid+'"  data-id="'+dataid+'">';
    str += '<option value="">Select</option>';
    str += '<?php
    $group_name = "";
    foreach ($expense_items as $key => $val) :
        if ($val['coa_type_name'] != $group_name) {
            if ($endLabel) {
                echo "</optgroup>";
            }
            // echo label...
            echo "<optgroup label=" . $val['coa_type_name'] . ">";
            $group_name = $val['coa_type_name'];
            $endLabel = true;
        }
        echo "<option value=" . $val['coa_id'] . ">" . $val['coa_name'] . "</option>";
    endforeach
    ;
    ?>';
    str += '</select>';
    str += '</div>';
    str += '</div>';
    str += '</div>';
    str += '<div>';
    str += '<div class="col-md-12 no-padding">';
    str += '<div class="col-md-6 no-padding">';
    str += '<div class="col-md-3 two-padding-left">';
    str += '<div class="form-group">';
    str += '<label >Quantity </label>';
    str += '<input class="form-control amt_quan" value="" name="items[quantity][]"  data-id="'+dataid+'" id="subquantity_'+dataid+'"  type="text" min="0" />';
    str += '</div>';
    str += '</div>';
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >UOM</label>';
    str +=  '<input class="form-control uom" value="" name="items[uom][]" data-id="'+dataid+'" id="uom_'+dataid+'"  type="text" />';
    str +=  '</div>';
    str +=  '</div>';
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >Unit Price</label>';
    str +=  '<input class="form-control amt_unit" value="0" data-id="'+dataid+'" id="unitprice_'+dataid+'" name="items[subunitprice][]"  type="text"  min="0" />';
    str +=  '</div>';
    str +=  '</div>';   
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >Discount Amt</label>';
    str +=  ' <input class="form-control amt_disct" value="0" name="items[distamount][]" data-id="'+dataid+'" id="subdistamount_'+dataid+'" type="text" min="0"  />';
    str +=  '</div></div>';

    str +=  '</div>';
    str += '<div class="col-md-6 no-padding">'; 
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >Amount</label>';
    str +=  '<input readonly class="form-control amt_cal" value="0" name="items[amount][]" data-id="'+dataid+'" id="subamount_'+dataid+'" type="text" min="0" />';
    str +=  '</div>';
    str +=  '</div>';
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >Tax (%)</label>';
    str +=  ' <input class="form-control amtitemtaxper" value="0" name="items[amtitemtaxper][]" data-id="'+dataid+'" id="amtitemtaxper_'+dataid+'" type="text" min="0" />';
    str +=  '</div></div>';
    str +=  '<div class="col-md-3 two-padding-left">';
    str +=  '<div class="form-group">';
    str +=  '<label >Tax Amt</label>';
    str +=  ' <input readonly class="form-control amtitemtaxamt" value="0" name="items[amtitemtaxamt][]" data-id="'+dataid+'" id="amtitemtaxamt_'+dataid+'" type="text" min="0" />';
    str +=  '</div></div>';

    str +=  '<div class="col-md-3 two-padding-right">';
    str +=  '<div class="form-group">';
    str +=  '<label >Item Amount</label>';
    str +=  ' <input readonly class="form-control amt_net" value="0" name="items[netamount][]" data-id="'+dataid+'" id="subnetamount_'+dataid+'"  type="text" min="0"  />';
    str +=  '<input type="hidden" id="po_item_id_'+dataid+'" name="items[po_item_id][]">';
    str +=  '</div>';
    str +=  '</div>';
    str +=  '</div>';
    str +=  '</div>';
    str += '</div>';
    str += '</div>';
    str += '</div>';

    $('.sub_sop_container_'+parent_id).append(str);
    $(this).attr('data-id',eval(dataid)+1);

    $('#'+id).val(val);
	
   // $('.cate').unbind('change');
   // $('.assetlst').unbind('change');
   // $('.cate').change(function () {
  // var subcate = "subcate_"+$(this).attr('data-id');
  // category_changes($(this).val(),subcate);
  // }); 

    $('.amt_quan, .amt_unit, .amt_disct, .amtitemtaxper').unbind('keyup');
    $('.amt_quan, .amt_unit, .amt_disct, .amtitemtaxper').bind('keyup',function (){
    	dataid = $(this).attr('data-id');
       calc_total_amt (dataid);
    });

    $('.delete_sub_btn').unbind("click");
    $('.delete_sub_btn').bind ("click",function () {
        delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"),$(this).attr('data-id') );
    });
     $('.select2').select2();
 
});

function delete_sub_sop (div_cls_id, sub_sop_id, data_id) {
    var poItemId = $('#po_item_id_'+data_id).val();
    if(poItemId<=0){ poItemId = 0;}
    if(poItemId == "") {
        $('.sub_sop_'+div_cls_id).remove();
        getcountmaintotal();
        calc_main_discount();
        calc_tax_percent();
    } else {
        if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_purchase_order/unset_purchase_item');?>/', // Reusing the same function from task creation
                data: {'po_item_id':poItemId},
                datatype:"html", // others: xml, json; default is html
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) {
                    $('.item_div_'+data_id).remove();
                    $("#content_area").LoadingOverlay("hide", true);
                    getcountmaintotal ();
                    calc_main_discount ();
                    calc_tax_percent ();
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
    }
}

function getcountmaintotal() {
    var amt_net = 0;
    $('.amt_net').each(function () {
        if($(this).val() != '') {
            amt_net += eval($(this).val());
        }
    });

    var amt_net = parseFloat(amt_net).toFixed(2)
    $('.resultsubtotal').html(amt_net);
    $('#rstsubtot').val(amt_net);

    var getdist = $('#maindiscount').val();
    if(getdist>0){
        amt_net = parseFloat(amt_net) - parseFloat(getdist);
    }
    var amt_net = parseFloat(amt_net).toFixed(2)
    $('.maintotalamount').html(amt_net);
    $('#maintotat').val(amt_net)
    var gettax = $('#taxpers').val();
    amt_nettotal = $('.mainnetamt').html()
    if(gettax>0){
        var gettaxval = parseFloat($('#taxpers').val());
        var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
        $('.taxamt').val(calctax);
        var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
        var netat = parseFloat(disnetamt).toFixed(2)
        $('.maintotalamount').html(netat);
        $('#maintotat').val(netat);
    }else {
        var netat = parseFloat(disnetamt).toFixed(2)
        $('.maintotalamount').html(netat);
        $('#maintotat').val(netat)
    }
}

$('.delete_sub_btn').bind ("click",function () {
    delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"),$(this).attr('data-id'));
});

$('.amt_quan, .amt_unit, .amt_disct, .amtitemtaxper').keyup(function(){    
	dataid = $(this).attr('data-id');
    calc_total_amt (dataid);
 });  


function calc_main_discount(){
	var mainsub = $("#rstsubtot").val();
	var getval = parseFloat($('#maindiscount').val());
	var disamt = parseFloat(mainsub) -getval ;
	var disamt = parseFloat(disamt).toFixed(2)
	$('#mainntat').val(disamt);
	var disamt = parseFloat(disamt).toFixed(2)
	$('#maintotat').val(disamt)
}


function calc_tax_percent() {
	var mainnet = $('#mainntat').val();
	var gettaxval = parseFloat($('#taxpers').val());
	var calctax = (mainnet*gettaxval/100).toFixed(2);
	$('.taxamt').val(calctax);
	var disnetamt = parseFloat(mainnet) - parseFloat(calctax) ;
	var disnetamt = parseFloat(disnetamt).toFixed(2);
	$('#maintotat').val(disnetamt);
}

$('.maindiscount').keyup(function(){ 
	calc_main_discount();
	calc_tax_percent();
 });

$('.taxpers').keyup(function(){ 
	calc_tax_percent();
 });


function calc_total_amt (dataid) {
    var quant = 0;
    var unit = 0;
    var amount = 0;
    var amt_disct = 0;

    $('.amt_quan').each(function () {
        if ( $('#subquantity_'+dataid).val() != '' ) {
            quant = eval( $('#subquantity_'+dataid).val() );
        }
    });
    $('.amt_unit').each(function () {
        if($('#unitprice_'+dataid).val() != '') {
            unit = eval($('#unitprice_'+dataid).val());
        }
    });
    $('.amt_disct').each(function () {
        if( $('#subdistamount_'+dataid).val() != '') {
            amt_disct = eval( $('#subdistamount_'+dataid).val() );
        }
    });

    var subtotal = 0;
    amount = (quant * unit - amt_disct);
    
    subtotal = parseFloat(amount).toFixed(2);  
    $('#subamount_'+dataid).val( parseFloat(amount).toFixed(2) );
    var calctaxamt = 0;
    var subnetamt = 0;
   
    $('.amtitemtaxper').each(function () {
        if($(this).val() != '') {
        	var gettaxval = parseFloat( $('#amtitemtaxper_'+dataid).val() );
    	    calctaxamt = (amount *  gettaxval/100).toFixed(2);
            console.log("calctax "+calctaxamt);
    	    $('#amtitemtaxamt_'+dataid).val(calctaxamt);
        }
    }); 
    amount = (amount + parseFloat(calctaxamt));
    subnetamt = (amount);  
    $('#subnetamount_'+dataid).val(  parseFloat(subnetamt).toFixed(2) );

    var amt_net = 0;
    $('.amt_net').each(function () {
        if($(this).val() != '') {
        	amt_net += eval($(this).val());
        }
    }); 

    var amt_net = parseFloat(amt_net).toFixed(2)
	$('.resultsubtotal').html(amt_net);
	$('#rstsubtot').val(amt_net);
    var getdist = $('#maindiscount').val();
    if(getdist>0){
    	amt_net = parseFloat(amt_net) - parseFloat(getdist);	
     }
    var amt_net = parseFloat(amt_net).toFixed(2)
	$('.mainnetamt').html(amt_net);
	$('#mainntat').val(amt_net);
    var gettax = $('#taxpers').val();
    amt_nettotal = $('.mainnetamt').html()
    if(gettax>0){
    	var gettaxval = parseFloat($('#taxpers').val());
    	var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
    	$('.taxamt').val(calctax);
    	var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
    	var disnetamt = parseFloat(disnetamt).toFixed(2)
    	$('.maintotalamount').html(disnetamt);
    	$('#maintotat').val(disnetamt)
     }else {
    	 var amt_net = parseFloat(amt_net).toFixed(2)
     	$('.maintotalamount').html(amt_net);
     	$('#maintotat').val(amt_net)
      }
    	calc_main_discount();
    	calc_tax_percent();
    
}

$(function () {
//Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });

});

// Load block and assign to drop down
function category_changes(cateid, subid) {
  debugger;
     $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_purchase_order/getSubCategoryList');?>',
        data: {'category_id':cateid},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {
            var str = '<option value="">Select</option>';
            if(data.length > 0) {
                $.each(data,function (i, item) {
                    str += '<option value="'+item.charge_code_sub_category_id+'">'+item.charge_code_sub_category_name+'</option>';
                });
            }
            $('#'+subid).html(str);
             $("#content_area").LoadingOverlay("hide", true);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}


if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
    $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));        
}

function isNumber(evt){
    var inputCode = event.which;
    var currentValue = $(this).val();
    if (inputCode > 0 && (inputCode < 48 || inputCode > 57)) {
        if (inputCode == 46) {
            if (getCursorPosition(this) == 0 && currentValue.charAt(0) == '-') return false;
            if (currentValue.match(/[.]/)) return false;
        } 
        else if (inputCode == 45) {
            if (currentValue.charAt(0) == '-') return false;
            if (getCursorPosition(this) != 0) return false;
        } 
        else if (inputCode == 8) return true;
        else return false;
    } 
    else if (inputCode > 0 && (inputCode >= 48 && inputCode <= 57)) {
        if (currentValue.charAt(0) == '-' && getCursorPosition(this) == 0) return false;
    }
}

</script>