<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/select2/dist/css/select2.css">

<style>
.items_container > div > div > div { padding: 0 5px !important; }
.items_container > div > div > div > select, .items_container > div > div > div > input { padding:6px !important; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="visible-xs">
      <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
    </h1>
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
      <!--div class="box-header with-border">
<h3 class="box-title">Quick Example</h3>
</div-->
      <!-- /.box-header -->
      <div class="box-body" style="padding-top: 15px;">
        <?php if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
            unset($_SESSION['flash_msg']);
            }
            ?>
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
          <div class="row" style="background-color: #d2cece; height: 50px;" >
            <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($receipt['receipt_id']) ? 'Update Receipt ('.$receipt['receipt_no'].')' : 'New Receipt';?> </h3>
          </div>
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_receipt/add_receipt_submit'); ?>" autocomplete="off">
            <div class="row" style="padding-top: 15px;padding-bottom:15px;">
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                <div class="col-md-1 col-xs-3"> Property </div>
                <div class="col-md-3 col-xs-5">
                <?php if($act_type == 'amend') { ?>
                    <input type="hidden" name="receipt[property_id]" value="<?php echo $property_id;?>" /> 
                    <select class="form-control" id="property_id"  disabled="disabled">
                                       
                <?php } else { ?>
                    <select class="form-control" id="property_id" name="receipt[property_id]">
                <?php } ?>
                  
                    <option value="">Select</option>
                    <?php 
                    foreach ($properties as $key=>$val) {
                        $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                        echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                    } ?> 
                  </select>
                  <!-- Hidden fields -->
                  <input type="hidden" name="receipt[receipt_id]" value="<?php echo !empty($receipt['receipt_id']) ? $receipt['receipt_id'] : '';?>" />
                  <input type="hidden" name="receipt[receipt_no]" value="<?php echo !empty($receipt['receipt_no']) ? $receipt['receipt_no'] : '';?>" />
                  <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                  <input type="hidden" name="receipt[direct_receipt]" id="direct_receipt" value="<?php echo !empty($receipt['direct_receipt']) ? $receipt['direct_receipt'] : '0';?>" />                  
                  
                  
                </div>
                <!--block id-->
                <div class="col-md-1 col-xs-3"> Block/Street</div>
                <div class="col-md-3 col-xs-5">
                <?php if($act_type == 'amend') { ?>
                    <input type="hidden" id="block_id" name="receipt[block_id]" value="<?php echo !empty($receipt['block_id']) ? $receipt['block_id'] : '';?>" />
                    <select class="form-control"  disabled="disabled">
                    
                <?php } else { ?>
                    <select class="form-control" id="block_id" name="receipt[block_id]">
                <?php } ?>
                  
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($blocks)) {
                        foreach ($blocks as $key=>$val) {
                            $selected = isset($receipt['block_id']) && $receipt['block_id'] == $val['block_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['block_id']."' ".$selected.">".$val['block_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div> 
                <!--unit no section-->
                <div class="col-md-1 col-xs-3"> Unit *</div>
                <div class="col-md-3 col-xs-5" style="">
                <?php if($act_type == 'amend') { ?>
                    <input type="hidden" id="unit_id" name="receipt[unit_id]" value="<?php echo !empty($receipt['unit_id']) ? $receipt['unit_id'] : '';?>" />
                    <select  class="form-control select2"  disabled="disabled">
                    
                <?php } else { ?>
                    <select name="receipt[unit_id]" class="form-control select2" id="unit_id">
                <?php } ?>
                  
                    <option value="">Select</option> 
                    <?php 
                    $owner_name = '';
                    if(!empty($units)) {
                        foreach ($units as $key=>$val) {
                            $selected = '';
                            if(isset($receipt['unit_id']) && $receipt['unit_id'] == $val['unit_id']) {
                                $owner_name = $val['owner_name'];
                                $selected = 'selected="selected" ';
                            }                                 
                            echo "<option value='".$val['unit_id']."'
                            data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
                        }
                    }
                    ?>                                   
                  </select>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                <div class="col-md-1 col-xs-3"> From </div>
                <div class="col-md-3 col-xs-5">
                  <input type="text" id="owner" value="<?php echo $owner_name;?>"  class="form-control" readonly="true">
                </div>
                <div class="col-md-1 col-xs-3">Date</div>
                <div class="col-md-3 col-xs-5">                  
                  <input type="text" name="receipt[receipt_date]" value="<?php echo !empty($receipt['receipt_date']) ? date('d-m-Y',strtotime($receipt['receipt_date'])) : date("d-m-Y"); ?>" class="form-control <?php echo $act_type == 'amend' || !in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi')) ? '" readonly="true"': 'datepicker" ';?> >
                </div>
                
                <div class="col-md-1 col-xs-3">Bank *</div>
                <div class="col-md-3 col-xs-5">   
                <?php if($act_type == 'amend') { ?>
                    <input type="hidden" name="receipt[bank_id]" value="<?php echo !empty($receipt['bank_id']) ? $receipt['bank_id'] : '';?>" />
                    <select class="form-control"  disabled="disabled">
                    
                <?php } else { ?>
                    <select class="form-control" id="bank_id" name="receipt[bank_id]">
                <?php } ?>               
                    
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($banks)) {
                        foreach ($banks as $key=>$val) {
                            $selected = isset($receipt['bank_id']) && $receipt['bank_id'] == $val['bank_id'] ?  'selected="selected" ' : '';
                            echo "<option value='".$val['bank_id']."' ".$selected.">".$val['bank_name']."</option>";
                        }
                    }
                    ?>                                
                  </select>
                </div>                
              </div>	
              
              <div class="row">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-2 col-xs-4">
                       <label>Payment mode *</label>
                    </div>
                    
                    
                   <div class="col-md-3 col-xs-4">
                    <?php if($act_type == 'amend') { ?>
                        <input type="hidden" id="payment_mode" name="receipt[payment_mode]" value="<?php echo !empty($receipt['payment_mode']) ? $receipt['payment_mode'] : '';?>" />
                        <select class="form-control" disabled="disabled">
                        
                    <?php } else { ?>
                        <select class="form-control" id="payment_mode" name="receipt[payment_mode]">
                    <?php } ?>
                                        
                    <option value="">Select</option> 
                    
                            <?php $payment_mode = $this->config->item ('payment_mode'); 
                            foreach ($payment_mode as $key=>$val) {                                
                                $selected = isset($receipt['payment_mode']) && $receipt['payment_mode'] == $key ?  'selected="selected" ' : '';
                                echo "<option value='".$key."' ".$selected.">".$val."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_2" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Bank
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_bank]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 2 && !empty($receipt['bank']) ? $receipt['bank'] : '';?>" class="form-control">
                    </div>
                    
                    <div class="col-md-1 col-xs-4" >
                       Cheque No.
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_no]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 2 && !empty($receipt['cheq_card_txn_no']) ? $receipt['cheq_card_txn_no'] : '';?>" class="form-control">
                    </div>
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Date
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[cheq_date]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 2 && !empty($receipt['cheq_txn_online_date']) ? date('d-m-Y',strtotime($receipt['cheq_txn_online_date'])) : '';?>" class="form-control datepicker">
                    </div>
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_3" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Bank
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[card_bank]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 3 && !empty($receipt['bank']) ? $receipt['bank'] : '';?>" class="form-control">
                    </div>
                    
                    <div class="col-md-1 col-xs-4" >
                       Txn No.
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[card_txn_no]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 3 && !empty($receipt['cheq_card_txn_no']) ? $receipt['cheq_card_txn_no'] : '';?>" class="form-control">
                    </div>
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Card Type
                    </div>
                    <div class="col-md-3 col-xs-4">
                       
                       <select name="pm_details[card_type]" class="form-control" >
                            <option value="">Select</option> 
                            <?php $card_type = $this->config->item ('card_type');                    
                                foreach ($card_type as $key=>$val) {
                                    echo $selected = !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 3 && $key == $receipt['online_r_card_type'] ? 'selected="selected"' : '';
                                    echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                                }                            
                            ?>
                                                         
                      </select>
                    </div>
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_4" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Bank
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[online_bank]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 4 && !empty($receipt['bank']) ? $receipt['bank'] : '';?>" class="form-control">
                    </div>
                    
                    <div class="col-md-1 col-xs-4" >
                       Txn No.
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[online_txn_no]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 4 && !empty($receipt['cheq_card_txn_no']) ? $receipt['cheq_card_txn_no'] : '';?>" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;">
                    </div>
                  </div>
                  
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Type
                    </div>
                    <div class="col-md-3 col-xs-4">
                       
                       <select name="pm_details[online_type]" class="form-control" >
                            <option value="">Select</option> 
                            <?php $online_type = $this->config->item ('online_type');
                                foreach ($online_type as $key=>$val) {
                                    echo $selected = !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 4 && $key == $receipt['online_r_card_type'] ? 'selected="selected"' : '';
                                    echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
                                }                            
                            ?>
                      </select>
                    </div>
                    
                    <div class="col-md-1 col-xs-4" style="padding-top: 5px;">
                       Date
                    </div>
                    <div class="col-md-3 col-xs-4">
                       <input type="text" name="pm_details[online_date]" value="<?php echo !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 4 && !empty($receipt['cheq_txn_online_date']) ? date('d-m-Y',strtotime($receipt['cheq_txn_online_date'])) : '';?>" class="form-control datepicker">
                    </div>
                  
                  </div>                                    
              </div>
              
              <div class="row pay_mode_details pay_mode_5" style="display: none;">
                  <div class="col-md-12" style="margin-top:15px;"> 
                    <div class="col-md-2 col-xs-4" style="padding-top: 5px;">
                       Deposit Receipt
                    </div>
                    <div class="col-md-3 col-xs-4">
                        <?php if(!empty($receipt['receipt_id']) && !empty($receipt['depo_receive_id'])){
                            echo '<input type="hidden" id="deposit_no" name="receipt[depo_receive_id]" value="'.$receipt['depo_receive_id'].'"  >'; 
                            echo '<select class="form-control" disabled="disabled">';
                        } else {
                            echo '<select id="deposit_no" name="receipt[depo_receive_id]" class="form-control">';
                        } ?>
                       
                            <option value="">Select</option>                            
                            <?php 
                                if(!empty($depo_receive_ids)) {
                                    foreach ($depo_receive_ids as $key=>$val) {
                                        $selected = !empty($receipt['payment_mode']) && $receipt['payment_mode'] == 5 && $val['depo_receive_id'] == $receipt['depo_receive_id'] ? 'selected="selected" ' : '';
                                        echo '<option value="'.$val['depo_receive_id'].'" '.$selected.'>'.$val['doc_ref_no'].'('.$val['amount'].')</option>';
                                    }
                                }
                            ?>                                   
                      </select>
                    </div>
                  
                  </div>                                    
              </div>
              
              <div class="row">
                  <div class="col-md-12">              
                    <div class="col-md-3 col-xs-3" style="padding-left: 5px;">
                      <h3><b>Items</b></h3>
                      (All Currencies are in RM)                  
                    </div>
                    <div class="col-md-7 col-xs-7">
                        <div class="col-md-6 col-xs-6 " style="margin-top:25px;">
                            <div class="col-md-5 no-padding" style="margin-top: 5px;">
                                <b id="paid_amt_label"><?php echo !empty($receipt['opening_credit']) && $receipt['opening_credit'] > 0 ? 'COA Opening Balance:' : 'Paid Amount:';?> </b>
                            </div>
                            <div class="col-md-7 no-padding">
                                <input type="number" name="receipt[paid_amount]" id="paid_amount"  value="<?php echo !empty ($receipt['paid_amount']) && $receipt['paid_amount'] > 0 ? $receipt['paid_amount'] : (!empty($receipt['opening_credit']) && $receipt['opening_credit'] > 0  ? $receipt['opening_credit'] : '');?>" <?php echo !empty($receipt['receipt_id']) || !empty($receipt['depo_receive_id']) || $act_type == 'amend' ? 'readonly="true" ' : '';?>  class="form-control" style="text-align: right;" >
                                <input type="hidden" name="receipt[opening_credit]" id="opening_credit"  value="<?php echo !empty ($receipt['opening_credit']) ? $receipt['opening_credit'] : '';?>" >
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-xs-6" style="margin-top:25px;">
                          <div class="col-md-5 no-padding" style="margin-top: 5px;">
                                <b>Open Credit: </b>
                            </div>
                            <div class="col-md-7 no-padding">
                                <input type="number" name="receipt[open_credit]" id="open_credit" data-oc="0.00" value="<?php echo !empty ($receipt['open_credit']) ? $receipt['open_credit'] : '0.00';?>" class="form-control" style="text-align: right;" readonly="true">
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-3 text-right add_item_div" style="margin-top:25px;display: none;">
                      <button type="button" name="add" id="add_line_item" data-id="<?php echo !empty($receipt_items) ? count($receipt_items) + 1 : 2;?>" class="btn btn-primary"  >Add Item</button>  
                    </div>
                    <div class="col-md-2 col-xs-3 text-right remove_all_item_div" style="margin-top:25px;display: none;">
                      <button type="button" name="add" id="remove_all_item" data-id="<?php echo !empty($receipt_items) ? count($receipt_items) + 1 : 2;?>" class="btn btn-danger"  >Remove All Item</button>  
                    </div>
                  </div>                                    
              </div>
             
              
              <div class="row items_container" style="border: 1px solid #;background-color: #ECF0F5;border: 1px solid #999; border-radius: 5px;margin: 15px 5px; padding: 15px 0 20px 0 !important;" >
                <div class="col-md-12 no-padding" >
                    <div class="col-md-3 no-padding">
                        <div class="col-md-7">
                            <label>Item Name</label>
                        </div>
                        
                        <div class="col-md-5">                    
                          <label>Period</label>
                        </div>
                        
                    </div>
                    <div class="col-md-9 no-padding" >
                        <div class="col-md-5">
                          <label>Description</label>
                        </div>
                        <div class="col-md-2">
                          <label>Amount</label>
                        </div> 
                        <div class="col-md-2">
                          <label>Applied Amt</label>
                        </div>    
                        <div class="col-md-2">
                          <label>Balance</label>
                        </div>     
                        <div class="col-md-1">&nbsp;</div>  
                     </div>           
                </div>
                
                <?php 
                
                /*if(empty($receipt_items)) {
                    //$receipt_items = array(array('receipt_item_id'=>'','item_cat_id'=>'','item_sub_cat_id'=>'','item_period'=>'','item_descrip'=>'','item_amount'=>'','sub_cat_dd'=>array()));
                } */
                if(!empty($receipt_items)) {                
                    foreach ($receipt_items as $Bkey=>$Bval) {
                                
                ?>
                <div class="col-md-12 no-padding item_div item_<?php echo ($Bkey+1);?>" style="padding-top: <?php echo ($Bkey+1) == 1 ? 0 : 10;?>px !important;" >
                    <div class="col-md-3 no-padding">
                        <div class="col-md-7">
                        
                            <input type="hidden" name="items[receipt_item_id][]" id="receipt_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['receipt_item_id']) ? $Bval['receipt_item_id'] : '';?>"  />
                            <input type="hidden" name="items[bill_item_id][]" id="bill_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['bill_item_id']) ? $Bval['bill_item_id'] : '';?>"  />
                            <?php if($act_type == 'amend') { ?>
                
                            <?php } else { ?>
                                
                            <?php } ?>
                            <select name="items[item_cat_id][]" id="cat_dd_<?php echo ($Bkey+1);?>" class="form-control cat_dd" data-id="<?php echo ($Bkey+1);?>" required="true">
                                <option value="">Select</option>
                                <?php 
                                $period = '';
                                foreach ($sales_items as $key=>$val) {
                                    
                                    if(!empty ($Bval['item_cat_id']) && $Bval['item_cat_id'] == $val['coa_id']) {
                                        $selected =  'selected="selected"';
                                        if(!empty($val['period'])) {
                                            $period = $val['period'];
                                        }
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value='".$val['coa_id']."' data-period='".$val['period']."' ".$selected.">".$val['coa_name']."</option>";
                                } ?> 
                              </select> 
                              
                        </div>
                        
                        <div class="col-md-5">
                            <?php if($act_type == 'amend') { ?>
                
                            <?php } else { ?>
                                
                            <?php } ?>                    
                          <select class="form-control period_dd" name="items[item_period][]" id="period_dd_<?php echo ($Bkey+1);?>" data-id="<?php echo ($Bkey+1);?>">
                          <?php echo get_period_dd($period,(!empty ($Bval['item_period']) ? $Bval['item_period'] : '')); ?>
                          </select>
                          
                        </div>
                        
                        
                    </div>
                    <div class="col-md-9 no-padding" >
                        <div class="col-md-5">
                            <?php if($act_type == 'amend') { ?>
                
                            <?php } else { ?>
                                
                            <?php } ?>
                          <input type="text" name="items[item_descrip][]" id="desc_txt_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">
                        </div>
                        <div class="col-md-2">                            
                          <input type="number" name="items[item_amount][]" id="tot_amt_<?php echo ($Bkey+1);?>" class="form-control tot_amt" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] . '" readonly="true' : '';?>" required="true">
                          
                        </div> 
                        <div class="col-md-2">
                            <?php if($act_type == 'amend') { ?>
                                <input type="number" name="items[paid_amount][]" id="pay_amt_<?php echo ($Bkey+1);?>" class="form-control pay_amt" value="<?php echo !empty ($Bval['paid_amount']) ? $Bval['paid_amount'] : '';?>" readonly="true">
                            <?php } else { ?>
                                <input type="number" name="items[paid_amount][]" id="pay_amt_<?php echo ($Bkey+1);?>" class="form-control pay_amt" value="<?php echo !empty ($Bval['paid_amount']) ? $Bval['paid_amount'] : '';?>" required="true">
                            <?php } ?>
                          
                        </div> 
                        <div class="col-md-2">
                          <input type="number" name="items[bal_amount][]" id="bal_amt_<?php echo ($Bkey+1);?>" class="form-control bal_amt" value="<?php echo !empty ($Bval['bal_amount']) ? $Bval['bal_amount'] : '';?>" readonly="true">
                        </div> 
                     
                        <div class="col-md-1 text-center">
                        <?php if($act_type != 'amend') { ?>
                            <button type="button" class="btn btn-danger btn-remove" data-id="<?php echo ($Bkey+1);?>"><i class="fa fa-close"></i></button>
                        <?php } ?>
                        </div>
                    </div>                      
                </div>
                
                <?php } 
                } ?>
              </div>
          <!--end default open-->
        
              <div class="col-md-12 no-padding" style="padding-top: 15px !important;" >
                <div class="col-md-3 col-xs-6 "> </div>
                  
                
                
                <div class="col-md-9 no-padding" >
                        <div class="col-md-5 text-right" style="padding-top: 5px;">
                          <b>Total:</b>
                        </div>
                        <div class="col-md-2" style="padding: 0 5px !important;">
                          <input type="text" class="tot_tot_amt form-control" value="0" style="text-align: right;" readonly="true" >
                        </div> 
                        <div class="col-md-2" style="padding: 0 5px !important;">
                          <input type="text" class="form-control tot_pay_amt" value="0" style="text-align: right;" readonly="true" >
                        </div> 
                        <div class="col-md-2" style="padding: 0 5px !important;">
                          <input type="text" class="form-control tot_bal_amt" value="0" style="text-align: right;" readonly="true" >
                        </div> 
                     
                        <div class="col-md-1 text-center">&nbsp;</div>
                    </div>    
                
                
                
              </div>
                
              
              
              <div class="col-md-12 no-padding" style="padding: 20px 0 10px 0 !important;">
                <div class="col-md-2 col-xs-6">
                  <h3>
                    <b>Remarks</b>
                  </h3>
                </div>
                <div class="col-md-6 col-xs-12" >
                  <textarea rows="4" name="receipt[remarks]" class="form-control" cols="50"><?php echo !empty($receipt['remarks'])? $receipt['remarks'] : '';?></textarea>
                </div>
              </div>
              
              <div style="color:red;padding: 15px 15px !important;"> (*) indicates mandatory fields.</div>
              
              <div class="col-md-12 no-padding">
                <div class="col-md-2 col-xs-6">
                </div>
                <div class="col-md-10 col-xs-12" >
                  <div class="col-md-6">
                    <input type="submit" value="Save"  class="btn btn-primary" style="float: right;">
                  </div>
                  <div class="col-md-6">
                    <input type="reset"  value="Reset" class="btn btn-danger reset_btn" >
                  </div>
                </div>		
              </div>
             
            
          </div>		
          </form> 			
        
        </div>
          
        </div><!-- /.box-body -->
      </div><!-- /.box -->     
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- bootstrap datepicker -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.number.js"></script>
<script src="<?php echo base_url();?>bower_components/select2/dist/js/select2.full.js"></script>
<script>
var sales_items = $.parseJSON('<?php echo !empty($sales_items) ? json_encode($sales_items) : json_encode(array());?>');

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

$(document).ready(function () {
    
    $('#bms_frm').submit(function() {
        $('.submit_btn').prop("disabled", "disabled");
        setTimeout(function(){ $('.submit_btn').prop('disabled', false); }, 3000);
    });
             
    $('.select2').select2();
    $('#block_id').focus();
    $('.msg_notification').fadeOut(5000);
     
    if('<?php echo $act_type;?>' == 'amend') {
        get_outstanding_bills ($('#unit_id').val());
    }
     
     calc_total_amt ();
     
     $('#paid_amount').keyup(function(){            
        calc_total_amt ();
     });
     
     $('.pay_amt').focus(function() {
        if($('#paid_amount').val() == '') {            
            alert('Please Enter Paid Amount!');
            $('#paid_amount').focus();
            return false;
        } 
     });
     
     $('.tot_amt, .pay_amt').keyup(function(){ 
        calc_total_amt ();        
     });  
     
     
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "receipt[property_id]": "required",            
        "receipt[block_id]": "required",
        "receipt[unit_id]": "required",
        "receipt[receipt_date]": "required",
        "receipt[bank_id]":"required",
        "receipt[paid_amount]":"required",
        "receipt[payment_mode]":"required",
      },
      messages: {
        "receipt[property_id]": "Please select Property",
        "receipt[block_id]": "Please select Block/Street",
        "receipt[unit_id]": "Please select Unit",
        "receipt[receipt_date]": "Please select Due Date",
        "receipt[bank_id]":"Please select Bank",
        "receipt[paid_amount]":"Please enter Paid Amount",
        "receipt[payment_mode]":"Please select Payment mode",
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );
        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        }
        else if ( element.prop( "id" ) === "datepicker" ) {
          error.insertAfter( element.parent( "div" ) );
        }
        else {
          error.insertAfter( element );
        }
      },
      highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
      },
      unhighlight: function (element, errorClass, validClass) {
        $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
      }/*,
        submitHandler: function(form) {
            //alert('test');
            $( "#bms_frm").submit();
        }*/
    });
    
    
    // On document ready
    if($('#property_id').val() != '' && $('#block_id').val() == '') {
        //console.log($('#property_id').val());
        property_change_eve ();//$('#property_id').trigger("change");
    }   
    
    // On property name change
    $('#property_id').change(function () {    
        //console.log($('#property_id').val());
        //property_change_eve ();//$('#property_id').trigger("change");
        window.location.href='<?php echo base_url('index.php/bms_fin_receipt/add_receipt');?>?property_id='+$("#property_id").val();
        return false;
    });  
    
    // On block/street change
    $('#block_id').change(function () {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_receipt/get_unit_for_receipt');?>',
            data: {'property_id':$('#property_id').val(),'block_id':$('#block_id').val()},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-defaulter="'+item.is_defaulter +'" data-opening-crdit="'+item.opening_credit +'" data-op-used="'+item.open_credit_used +'">'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });
        
    
    $('#unit_id').change (function () {
        set_owner (); 
        console.log($(this).find('option:selected').data('opening-crdit') +' = '+$(this).find('option:selected').data('op-used'));
        if($(this).find('option:selected').data('op-used') == 0 && parseFloat($(this).find('option:selected').data('opening-crdit')) > 0) {
            alert('This unit has COA Opening Credit('+$(this).find('option:selected').data('opening-crdit')+'). Please utilize this first!');
            $('#bank_id').attr('disabled','disabled');
            $('#payment_mode').attr('disabled','disabled');
            $('#paid_amount').val(parseFloat($(this).find('option:selected').data('opening-crdit')));
            $('#opening_credit').val(parseFloat($(this).find('option:selected').data('opening-crdit')));
            $('#paid_amount').attr('readonly','true');
            $('#paid_amt_label').html('COA Opening Balance');            
        } else {
            $('#bank_id').removeAttr('disabled');
            $('#payment_mode').removeAttr('disabled');
            $('#paid_amount').val('');
            $('#opening_credit').val('');
            $('#paid_amount').removeAttr('readonly');
            $('#paid_amt_label').html('Paid Amount'); 
        }
        if($(this).val() != '')
            get_outstanding_bills ($(this).val());
    });   
    
    
    $('.cat_dd').change(function () {
        var dd_id = $(this).attr('data-id');
        if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
            get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
        } else {
            $('#period_dd_'+dd_id).html('<option value="">Select</option>');
            show_descrip ($(this).attr('data-id'));
        }
    });
    
    
    $('.period_dd').change(function () {        
        show_descrip ($(this).attr('data-id'));        
    }); 
    
    $('#payment_mode').change(function () {
        set_pay_mode_details ();      
    });
    set_pay_mode_details ();
    
    $('.reset_btn').click(function () {
        $("#content_area").LoadingOverlay("show");
        window.location.reload();
    });
    
    $('#deposit_no').change(function (){
        $('#paid_amount').val($(this).find('option:selected').data('amount'));
        $('#paid_amount').attr('readonly','true');
    });    
    
});  

function set_pay_mode_details () {
    if('<?php echo !empty($receipt['receipt_id']) ? '0' : '1';?>' == '1') {
        if($('#payment_mode').val() == 5) {
            if($('#unit_id').val() == '') {
                alert('Please select Unit first!');
                $('#payment_mode').val('');
                $('#unit_id').focus();
                return false;
            }
            get_unit_deposits ($('#unit_id').val());
        } else {
            $('#deposit_no').val('');
            $('#paid_amount').val('');
            $('#paid_amount').removeAttr('readonly');
        }
    }
    $('.pay_mode_details').css('display','none');
    $('.pay_mode_'+$('#payment_mode').val()).slideDown();
    
    //$('.'+$("input[name='receipt[payment_mode]']:checked").attr('id')).css('display','block');
    //console.log($("input[name='receipt[pay_mode]']:checked").val());
}

function set_owner () {
    //console.log($('#unit_id').attr('disabled'));
    $('#owner').val('');  
    $('#prop_abbr').val('');                      
    if(typeof($('#unit_id').find('option:selected').data('owner')) != 'undefined') {
        $('#owner').val($('#unit_id').find('option:selected').data('owner'));        
    }
     
    if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
        $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));        
    }
} 

function get_unit_deposits (unit_id) {
    //console.log(sub_cat_dd_id);
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_dp_refund/get_unit_deposits');?>',
        data: {'unit_id':unit_id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_area").LoadingOverlay("hide", true);
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.depo_receive_id+'" data-amount="'+item.amount+'" data-description="'+item.description+'" data-coa-id="'+item.coa_id+'"  >'+item.doc_ref_no+' ('+item.amount+')'+'</option>';
                });
            } else {
                alert('There is no deposit for selected unit. Please change the payment mode!');
                $('#payment_mode').val('');
            }            
            $('#deposit_no').html(str);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
} 

 
  

// Load block and assign to drop down
function property_change_eve () {
    if($('#property_id').val() != '') {
        $('#property_name').val($(this).find('option:selected').data('pname'));    
    
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/get_blocks');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html
    
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str); 
                $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
                set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already
                $('#bank_id').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already               
                $("#content_area").LoadingOverlay("hide", true);
                loadBank ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    } else {
        $('#property_name').val('');
        var str = '<option value="">Select</option>'; 
        $('#unit_id').html(str);
        $('#block_id').html(str);  
        $('#bank_id').html(str); 
    }
}

function loadBank (property_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_receipt/get_banks');?>',
        data: {'property_id':$('#property_id').val()},
        datatype:"json", // others: xml, json; default is html
        success: function(data) {  
            /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                window.location.href= '<?php echo base_url();?>';
                return false;
            }*/
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.bank_id+'">'+item.bank_name+'</option>';
                });
            }
            $('#bank_id').html(str); 
            
        },
        error: function (e) {           
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function get_outstanding_bills (unit_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_receipt/getOutstandingBills/').($act_type == 'amend' ? count($receipt_items): '').'/'.$act_type;?>',
        data: {'unit_id':unit_id,'property_id':$('#property_id').val()},
        datatype:"html", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {
            
            if(data != '') {
                var data_arr = data.split('~~~');
                if(data_arr[0] == 'This Unit has Unapplied Amount!') {
                    if(confirm ('This Unit has Unapplied Amount (Open Credit)! Do you want to utilize it first?')) {
                        window.location.href='<?php echo base_url().'index.php/bms_fin_receipt/add_receipt/';?>'+data_arr[1]+'/amend';
                        return false;
                    } else {
                       $('.items_container').html(data_arr[2]); 
                       if($(".items_container > div").length > 1) {
                            $('.add_item_div').css('display','none');
                            $('#direct_receipt').val('0');
                            $('.remove_all_item_div').css('display','block');
                            $('#remove_all_item').click(function () {
                                removeAllItem();
                            });
                        } else {
                            $('.add_item_div').css('display','block');
                            $('#direct_receipt').val('1');
                        }
                    }   
                } else {
                    if('<?php echo $act_type;?>' == 'amend') {
                        $('.items_container').append(data);
                    } else {
                        $('.items_container').html(data);
                        if($(".items_container > div").length > 1) {
                            $('.add_item_div').css('display','none');
                            $('#direct_receipt').val('0');
                            $('.remove_all_item_div').css('display','block');
                            $('#remove_all_item').click(function () {
                                removeAllItem();
                            });
                        } else {
                            $('.add_item_div').css('display','block');
                            $('#direct_receipt').val('1');
                        }
                    } 
                                   
                }
                
                var len = $(".items_container > div").length;
                //console.log(len);
                $('#add_line_item').attr('data-id',(len));
                
                $('.cat_dd').unbind('change');
                $('.cat_dd').change(function () {
                    var dd_id = $(this).attr('data-id');
                    if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
                        get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
                    } else {
                        $('#period_dd_'+dd_id).html('<option value="">Select</option>');
                        show_descrip ($(this).attr('data-id'));
                    }
                });
                
                
                
                $('.period_dd').bind("change");
                $('.period_dd').bind("change",function () {        
                    show_descrip ($(this).attr('data-id'));        
                }); 
                
                $('.tot_amt, .pay_amt').unbind('keyup');
                $('.pay_amt').unbind('focus');
                $('.pay_amt').focus(function() {
                    if($('#paid_amount').val() == '') {            
                        alert('Please Enter Paid Amount!');
                        $('#paid_amount').focus();
                        return false;
                    } 
                });
                
                $('.tot_amt, .pay_amt').bind('keyup',function (){
                   calc_total_amt ();
                });
                calc_total_amt ();
                
                $('.btn-remove').unbind('click');
                    
                $('.btn-remove').bind("click", function () {
                    remove_item ($(this).attr('data-id'));    
                });
                            
            } 
                          
            $("#content_area").LoadingOverlay("hide", true);
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function get_period (period_format,dd_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/get_period');?>',
        data: {'period_format':period_format},
        datatype:"json", // others: xml, json; default is html
        /*beforeSend:function (){ $("#content_area").LoadingOverlay("show");  },*/ //
        success: function(data) { 
            $('#period_dd_'+dd_id).html(data);
            show_descrip (dd_id);
            $('.period_dd').bind("change");
            $('.period_dd').bind("change",function () {        
                show_descrip ($(this).attr('data-id'));        
            }); 
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
    
function show_descrip (id) { 
    var cat_val = $('#cat_dd_'+id).find('option:selected').text()  != 'Select' ? $('#cat_dd_'+id).find('option:selected').text() : ''; 
    
    //console.log($('#period_dd_'+id).val());
    var period_val = $('#period_dd_'+id).val() != '' ? $('#period_dd_'+id).val() : '';
    if(cat_val != '' && period_val != '') {
        period_val = ' (' + period_val + ')'; 
    }
    $('#desc_txt_id_'+id).val(cat_val + '' +period_val);
}



function calc_total_amt () {
    //console.log('called');
    var total = 0; var tot_tot_amt = 0; var tot_pay_amt = 0; var tot_bal_amt = 0;
    //alert('Please enter Paid Amount!'); $('#paid_amount').focus();return false;
    
    var paid_amt = $('#paid_amount').val();
    
    $('.tot_amt').each(function () {
        if($(this).val() != '') {
           //console.log(tot_tot_amt+' '+$(this).val());  
           tot_tot_amt = (parseFloat(tot_tot_amt) + parseFloat($(this).val())).toFixed(5);
           var ele_id = $(this).attr('id').substring(8);
           
           var minus_from = $('#ori_bal_amt_'+ele_id).length && $('#ori_bal_amt_'+ele_id).val() != '' ? 'ori_bal_amt_'+ele_id : 'tot_amt_'+ele_id; 
           //console.log(ori_bal_amt);
           
           if($('#pay_amt_'+ele_id).val() != '') {
                if(parseFloat($('#'+minus_from).val()) < (parseFloat($('#pay_amt_'+ele_id).val()))) {
                    alert('You cannot Apply more than Balance Amount!');
                    $('#pay_amt_'+ele_id).val(parseFloat($('#'+minus_from).val()));
                } 
                $('#bal_amt_'+ele_id).val((parseFloat($('#'+minus_from).val()) - (parseFloat($('#pay_amt_'+ele_id).val()))).toFixed(2));                                    
           } else {
                $('#bal_amt_'+ele_id).val(parseFloat($('#'+minus_from).val()));
           }           
           //console.log(tot_tot_amt);    
        }        
    });
    //console.log(tot_tot_amt);
    tot_tot_amt = parseFloat(tot_tot_amt).toFixed(2);
    $('.tot_tot_amt').val(tot_tot_amt);
    
    $('.pay_amt').each(function () {
        if($(this).val() != '') {
           tot_pay_amt = (parseFloat(tot_pay_amt) + parseFloat($(this).val())).toFixed(5);
           //console.log(parseFloat(paid_amt) + ' - '+ parseFloat(tot_pay_amt));
           if(parseFloat(paid_amt) < parseFloat(tot_pay_amt)) {
                alert('You cannot apply more than Paid Amount!');
                tot_pay_amt = (parseFloat(tot_pay_amt) - parseFloat($(this).val())).toFixed(5);
                $(this).val('');
           }
           //tot_pay_amt += eval($(this).val()); 
        }        
    });
    tot_pay_amt = parseFloat(tot_pay_amt).toFixed(2);
    $('.tot_pay_amt').val(tot_pay_amt);
    
    $('.bal_amt').each(function () {
        if($(this).val() != '') {
           tot_bal_amt = (parseFloat(tot_bal_amt) + parseFloat($(this).val())).toFixed(5);
           //tot_bal_amt += eval($(this).val()); 
        }        
    });
    //console.log(tot_bal_amt);
    tot_bal_amt = parseFloat(tot_bal_amt).toFixed(2);
    $('.tot_bal_amt').val(tot_bal_amt);
    
    $('#open_credit').val((parseFloat(paid_amt) - parseFloat(tot_pay_amt)).toFixed(2));
    
    /*$('.amt_cal').each(function () {
        if($(this).val() != '') {
           total += eval($(this).val()); 
        }
        
    });     
    total = parseFloat(total).toFixed(2);
    var net_total = 0;
    var round_chk = total - parseFloat(total).toFixed(1);
    var round = 0;
    //console.log(round_chk);
    if(round_chk == 0.01 || round_chk == 0.06) {
        round = -0.01;
    } else if(round_chk == 0.02 || round_chk == 0.07) {
        round = -0.02;
    } else if(round_chk == 0.03 || round_chk == 0.08) {
        round = 0.02;
    } else if(round_chk == 0.04 || round_chk == 0.09) {
        round = 0.01;
    } */
    //$('.round_cls').html(round);
    //$('.total_amt').val(parseFloat(total).toFixed(2));   
}

$('#add_line_item').click (function () {
    var  id = $(this).attr('data-id');
    
    // alert(rdivs);
    var row = '';
    row += '<div class="col-md-12 no-padding item_'+id+'" style="padding-top: 10px !important;" >' ;
    row += '<div class="col-md-3 no-padding">';
    row += '<div class="col-md-7">' ;
    row += '<input type="hidden" name="items[bill_item_id][]" id="bill_item_id_'+id+'" value=""  />';
    row += '<select name="items[item_cat_id][]" id="cat_dd_'+id+'" class="form-control cat_dd" data-id="'+id+'" >';
    row += '<option value="">Select</option>';
    $.each(sales_items,function (i, item) { 
        row += '<option value="'+item.coa_id+'" data-period="'+item.period+'">'+item.coa_name+'</option>';
    });
    row += '</select>';
    row += '</div>';
    
    row += '<div class="col-md-5">';
    row += '<select class="form-control period_dd" name="items[item_period][]" id="period_dd_'+id+'" data-id="'+id+'">';
    row += '<option value="">Select</option>';
    row += '</select>';
    row += '</div>';
    row += '</div>';
    row += '<div class="col-md-9 no-padding" >';
    row += '<div class="col-md-5">';
    row += '<input type="text" name="items[item_descrip][]" id="desc_txt_id_'+id+'" value="" class="form-control">';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="items[item_amount][]" id="tot_amt_'+id+'"  class="form-control tot_amt" value="" >';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="items[paid_amount][]" id="pay_amt_'+id+'"  class="form-control pay_amt" value="" >';
    row += '</div>';
    row += '<div class="col-md-2">';
    row += '<input type="number" name="items[bal_amount][]" id="bal_amt_'+id+'"  class="form-control bal_amt" value="" readonly="true">';
    row += '</div>';
    row += '<div class="col-md-1 text-center">';
    row += '<button type="button" class="btn btn-danger btn-remove" data-id="'+id+'"><i class="fa fa-close"></i></button>';
    row += '</div>';
    row += '</div>';
    row += '</div>';
    $('.items_container').append(row);
    
    $(this).attr('data-id',(eval(id)+1));
    
    $('.cat_dd').unbind('change');
    $('.cat_dd').change(function () {
        var dd_id = $(this).attr('data-id');
        if(typeof($('#cat_dd_'+dd_id).find('option:selected').data('period')) != 'undefined' && $('#cat_dd_'+dd_id).find('option:selected').data('period') != '') {
            get_period($('#cat_dd_'+dd_id).find('option:selected').data('period'),dd_id);        
        } else {
            $('#period_dd_'+dd_id).html('<option value="">Select</option>');
            show_descrip ($(this).attr('data-id'));
        }
    });
    
    
    $('.tot_amt, .pay_amt').unbind('keyup');
    $('.tot_amt, .pay_amt').bind('keyup',function (){
       calc_total_amt ();
    });
    $('.pay_amt').unbind('focus');
    $('.pay_amt').focus(function() {
        if($('#paid_amount').val() == '') {            
            alert('Please Enter Paid Amount!');
            $('#paid_amount').focus();
            return false;
        } 
    });
    
    $('.btn-remove').unbind('click');
        
    $('.btn-remove').bind("click", function () {
        remove_item ($(this).attr('data-id'));    
    });
            
});  
  
$('.btn-remove').click(function () {
    remove_item ($(this).attr('data-id'));    
});

function remove_item (id){
    if($('#receipt_item_id_'+id).length && $('#receipt_item_id_'+id).val() != '') {
        //$('.item_'+id).remove();
        //calc_total_amt ();
        if(confirm('You cannot undo this action. Are you sure want to delete?')) {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_receipt/unset_receipt_item');?>',
                data: {'receipt_item_id':$('#receipt_item_id_'+id).val()},
                datatype:"html", // others: xml, json; default is html
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) {
                    $('.item_'+id).remove();
                    calc_total_amt ();
                    $("#content_area").LoadingOverlay("hide", true);
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);              
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
    } else {
        $('.item_'+id).remove();
        calc_total_amt ();
    }    
}

function removeAllItem () {
    //console.log('test');
    $('.item_div').each(function () {
        $(this).remove();
    });
    
    $('.remove_all_item_div').css('display','none');
    $('.add_item_div').css('display','block');
    $('#direct_receipt').val('1');
        
}
  
$(function () {
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });        
});     
</script>