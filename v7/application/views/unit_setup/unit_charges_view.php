
    <form role="form" id="bms_owner_frm" action="<?php echo base_url('index.php/bms_unit_setup/set_charges');?>" method="post" >
      
      <div class="col-md-12 col-xs-12 no-padding" style="">
                 
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Charges For Billing</b></h3>
                    </div>
                    <div class="box-body">
                    
                    <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_id;?>"/>
                    
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                          
                          <th>Charge Name</th>               
                          <th>Amount</th>
                          <th>Effective Date</th>
                          <th>Charge Code</th>
                          <th>Pay By</th>
                          <th>Add / Remove</th>
                          
                        </tr>
                        </thead>
                        <tbody class="charges_tbody">
                        <?php //echo "<pre>";print_r($charges_mand);print_r($charges);echo "</pre>";
                            $charges_mand_name = $this->config->item('charges_mand_name');
                            for ($i=1; $i <= 4 ; $i++) {
                                $foundKey = 'a';
                                foreach ($charges_mand as $cmKey=>$cmVal) {
                                    if($cmVal['charge_type_id'] == $i) {
                                        $foundKey = $cmKey;
                                    }
                                }
                                /*if($foundKey == 'a' && in_array($charg_mand_base['calcul_base'],array(1,2)))  {
                                        
                                    $field = $charg_mand_base['calcul_base'] == 1 ? 'square_feet' : 'share_unit';
                                    $per_field = $charg_mand_base['calcul_base'] == 1 ? 'per_sq_feet' : 'per_share_unit';
                                    $tot_field = $charg_mand_base['calcul_base'] == 1 ? 'tot_sq_feet' : 'tot_share_unit';                                        
                                   
                                    switch($i) {
                                        case '1': $charge_calc[1] = number_format(($charg_mand_base[$field] * $charg_mand_base[$per_field]),2); break;
                                        case '2': $charge_calc[2] = number_format((($charge_calc[1]*$charg_mand_base['sinking_fund'])/100),2); break;
                                        case '3': $charge_calc[3] = number_format((($charg_mand_base['insurance_prem']/$charg_mand_base[$tot_field])*$charg_mand_base[$field]),2); break;
                                        case '4': $charge_calc[4] = number_format((($charg_mand_base['quit_rent']/$charg_mand_base[$tot_field])*$charg_mand_base[$field]),2); break;                                    
                                    }
                                } */
                                
                                ?> 
                        <tr id="<?php echo $i;?>">
                            <td>  
                                <?php echo $charges_mand_name[$i];?>
                                <input type="hidden" name="charges[charges_id][]" value="<?php echo isset($charges_mand[$foundKey]['charges_id']) && $charges_mand[$foundKey]['charges_id'] != '' ? $charges_mand[$foundKey]['charges_id'] : '';?>" />
                                <input type="hidden" name="charges[charge_type_id][]" value="<?php echo $i;?>" />
                            </td>
                            <td><input type="text" name="charges[amount][]" class="form-control" value="<?php echo isset($charges_mand[$foundKey]['amount']) && $charges_mand[$foundKey]['amount'] != '' ? $charges_mand[$foundKey]['amount'] : '';?>" <?php echo in_array($charg_mand_base['calcul_base'],array(1,2)) ? 'readonly="true"' : '';?>  placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="charges[e_billing_start_date][]" value="<?php echo isset($charges_mand[$foundKey]['e_billing_start_date']) && $charges_mand[$foundKey]['e_billing_start_date'] != '' ? date('d-m-Y',strtotime($charges_mand[$foundKey]['e_billing_start_date'])) : '';?>" type="text">
                                </div>  
                            </td>
                            <td>  
                                <select class="form-control" name="charges[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($charges_mand[$foundKey]['charge_code_id']) && $charges_mand[$foundKey]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td>  
                                <select class="form-control" name="charges[pay_by][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($pay_by as $key=>$val) {                                        
                                        $selected = !empty($charges_mand[$foundKey]['pay_by']) && $charges_mand[$foundKey]['pay_by'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            <td class="text-center">
                                <?php if($i == 1) { ?>
                                    <a href="javascript:;" class="btn btn-success btn-circle add_charges_btn" data-value="<?php echo !empty($charges) ? count($charges)+5 : 5;?>" ><i class="fa fa-plus"></i></a>
                                <?php } else { echo "&nbsp;"; } ?>
                            
                            </td>
                            
                        </tr>
                        <?php } ?>
                                                
                        <?php if(!empty($charges) && count($charges) > 0) {
                            for ($i =0; $i < count($charges); $i++) { ?>
                                                                
                            <tr id="<?php echo $i+5;?>">
                            <td>  
                                <select class="form-control" name="charges[charge_type_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_types as $key=>$val) {                                        
                                        $selected = !empty($charges[$i]['charge_type_id']) && $charges[$i]['charge_type_id'] == $val['charge_type_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_type_id']."' ".$selected.">".$val['charge_type_name']."</option>";
                                    } ?> 
                                </select>
                                <input type="hidden" name="charges[charges_id][]" value="<?php echo !empty($charges[$i]['charges_id']) ? $charges[$i]['charges_id'] : '';?>" />
                            </td>
                            <td><input type="text" name="charges[amount][]" class="form-control" value="<?php echo isset($charges[$i]['amount']) && $charges[$i]['amount'] != '' ? $charges[$i]['amount'] : '';?>" placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="charges[e_billing_start_date][]" value="<?php echo isset($charges[$i]['e_billing_start_date']) && $charges[$i]['e_billing_start_date'] != '' ? date('d-m-Y',strtotime($charges[$i]['e_billing_start_date'])) : '';?>" type="text">
                                </div>  
                            
                            </td>  
                            <td>  
                                <select class="form-control" name="charges[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($charges[$i]['charge_code_id']) && $charges[$i]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td>  
                                <select class="form-control" name="charges[pay_by][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($pay_by as $key=>$val) {                                        
                                        $selected = !empty($charges[$i]['pay_by']) && $charges[$i]['pay_by'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            <td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_charges_btn" data-value="<?php echo $i+5;?>" ><i class="fa fa-minus"></i></a></td>
                            
                        </tr>
                        
                            
                            <?php
                            }
                            
                        }?>
                       </table>    
                       <div class="col-md-12 no-padding text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary owner_save_btn">Save</button> &ensp;&ensp;                        
                      </div>
                    </div>   
                               
                    </div>
                    
                    
                </div><!-- /.box-info -->  
             
            </form>

    
  <script>
  var charge_types = $.parseJSON('<?php echo json_encode($charge_types)?>');
  var charge_codes = $.parseJSON('<?php echo !empty($charge_codes) ? json_encode($charge_codes) : json_encode(array());?>');
  var pay_by = $.parseJSON('<?php echo !empty($pay_by) ? json_encode($pay_by) : json_encode(array());?>');
  $(document).ready(function (){
    
    $('.add_charges_btn').click(function () {        
        add_charges($(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
  
    
    $('.del_charges_btn').bind('click',function (){
       $('#'+$(this).attr('data-value')).remove(); 
    });
    
    function add_charges (row_id) {
        var str = '<tr id="'+row_id+'">';
        str += '<td>';
        str += '<select class="form-control" name="charges[charge_type_id][]">';
        str += '<option value="">Select</option>';
        $.each(charge_types,function (i, item) { 
            str += '<option value="'+item.charge_type_id+'">'+item.charge_type_name+'</option>';
        });
         
        str += '</select>';
        str += '<input type="hidden" name="charges[charges_id][]" value="" />';
        str += '</td>';
        str += '<td><input name="charges[amount][]" class="form-control" value="" placeholder="Enter Amount" maxlength="100" type="text"></td>';
        str += '<td>';
        str += '<div class="input-group date">';
        str += '<div class="input-group-addon">';
        str += '<i class="fa fa-calendar"></i>';
        str += '</div>';
        str += '<input class="form-control pull-right datepicker" name="charges[e_billing_start_date][]" value="" type="text">';
        str += '</div>';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="charges[charge_code_id][]">';
        str += '<option value="">Select</option>';
        $.each(charge_codes,function (i, item) { 
            str += '<option value="'+item.charge_code_id+'">'+item.charge_code+'</option>';
        }); 
        str += '</select>';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="charges[pay_by][]">';
        str += '<option value="">Select</option>';
        $.each(pay_by,function (i, item) { 
            str += '<option value="'+i+'">'+item+'</option>';
        });  
        str += '</select>';
        str += '</td>';
    
        str += '<td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_charges_btn" data-value="'+row_id+'" ><i class="fa fa-minus"></i></a></td>';
    
        str += '</tr>';
        
        $('.charges_tbody').append(str);
        
        $('.datepicker').unbind('datepicker');  
        
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        
        $('.del_charges_btn').unbind('click');
        $('.del_charges_btn').bind('click',function (){
           $('#'+$(this).attr('data-value')).remove(); 
        });
    }
    
  });
  
  //Date picker
  $(function () {
    $('.datepicker').unbind('datepicker');  
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
  });
  
  </script>