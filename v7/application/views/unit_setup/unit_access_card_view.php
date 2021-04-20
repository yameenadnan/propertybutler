
    <form role="form" id="bms_owner_frm" action="<?php echo base_url('index.php/bms_unit_setup/set_access_card');?>" method="post" >
      
      <div class="col-md-12 col-xs-12 no-padding" style="">
                 
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Access Card Details</b></h3>
                    </div>
                    <div class="box-body">
                    
                    <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_id;?>"/>
                    
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                          
                          <th>Access Card No.</th>               
                          <th>Access Type</th>
                          <th>Amount</th>
                          <th>Effective Date</th>
                          <th>Charge Code</th>                          
                          <th>Add / Remove</th>
                          
                        </tr>
                        </thead>
                        <tbody class="access_card_tbody">
                        
                        <tr id="0">
                            <td>  
                                <input type="text" name="access_card[access_card_no][]" class="form-control" value="<?php echo isset($access_card[0]['access_card_no']) && $access_card[0]['access_card_no'] != '' ? $access_card[0]['access_card_no'] : '';?>" placeholder="Enter Access Card No." maxlength="50">
                                <input type="hidden" name="access_card[access_card_id][]" value="<?php echo !empty($access_card[0]['access_card_id']) ? $access_card[0]['access_card_id'] : '';?>" />
                            </td>
                            <td>
                                <select class="form-control" name="access_card[access_card_type][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($access_card_type as $key=>$val) {                                        
                                        $selected = !empty($access_card[0]['access_card_type']) && $access_card[0]['access_card_type'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td><input type="text" name="access_card[amount][]" class="form-control" value="<?php echo isset($access_card[0]['amount']) && $access_card[0]['amount'] != '' ? $access_card[0]['amount'] : '';?>" placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="access_card[effect_date][]" value="<?php echo !empty($access_card[0]['effect_date']) && $access_card[0]['effect_date'] != '0000-00-00' ? date('d-m-Y',strtotime($access_card[0]['effect_date'])) : '';?>" type="text">
                                </div>  
                            </td>
                            <td>  
                                <select class="form-control" name="access_card[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($access_card[0]['charge_code_id']) && $access_card[0]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            
                            <td class="text-center"><a href="javascript:;" class="btn btn-success btn-circle add_more_btn" data-value="<?php echo !empty($access_cards) ? count($access_cards)+1 : 1;?>" ><i class="fa fa-plus"></i></a></td>
                            
                        </tr>
                        
                        <?php if(!empty($access_card) && count($access_card) > 1) {
                            for ($i =1; $i < count($access_card); $i++) { ?>
                                                                
                            <tr id="<?php echo $i;?>">
                            <td>  
                                <input type="text" name="access_card[access_card_no][]" class="form-control" value="<?php echo isset($access_card[$i]['access_card_no']) && $access_card[$i]['access_card_no'] != '' ? $access_card[$i]['access_card_no'] : '';?>" placeholder="Enter Access Card No." maxlength="50">
                                <input type="hidden" name="access_card[access_card_id][]" value="<?php echo !empty($access_card[$i]['access_card_id']) ? $access_card[$i]['access_card_id'] : '';?>" />
                            </td>
                            
                            <td>  
                                <select class="form-control" name="access_card[access_card_type][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($access_card_type as $key=>$val) {                                        
                                        $selected = !empty($access_card[$i]['access_card_type']) && $access_card[$i]['access_card_type'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td><input type="text" name="access_card[amount][]" class="form-control" value="<?php echo isset($access_card[$i]['amount']) && $access_card[$i]['amount'] != '' ? $access_card[$i]['amount'] : '';?>" placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="access_card[effect_date][]" value="<?php echo !empty($access_card[$i]['effect_date']) && $access_card[$i]['effect_date'] != '0000-00-00' ? date('d-m-Y',strtotime($access_card[$i]['effect_date'])) : '';?>" type="text">
                                </div>  
                            </td>
                            <td>  
                                <select class="form-control" name="access_card[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($access_card[$i]['charge_code_id']) && $access_card[$i]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            <td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" data-value="<?php echo $i;?>" ><i class="fa fa-minus"></i></a></td>
                            
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
  
  var charge_codes = $.parseJSON('<?php echo !empty($charge_codes) ? json_encode($charge_codes) : json_encode(array());?>');
  var access_card_type = $.parseJSON('<?php echo !empty($access_card_type) ? json_encode($access_card_type) : json_encode(array());?>');
  $(document).ready(function (){
    
    $('.add_more_btn').click(function () {        
        add_access_card($(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
  
    
    $('.del_more_btn').bind('click',function (){
       $('#'+$(this).attr('data-value')).remove(); 
    });
    
    function add_access_card (row_id) {
        var str = '<tr id="'+row_id+'">';
        str += '<td>';
        str += '<input type="text" name="access_card[access_card_no][]" class="form-control" value="" placeholder="Enter Access Card No." maxlength="50">'
        str += '<input type="hidden" name="access_card[access_card_id][]" value="" />';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="access_card[access_card_type][]">';
        str += '<option value="">Select</option>';
        $.each(access_card_type,function (i, item) { 
            str += '<option value="'+i+'">'+item+'</option>';
        });  
        str += '</select>';
        str += '</td>';
        str += '<td><input name="access_card[amount][]" class="form-control" value="" placeholder="Enter Amount" maxlength="100" type="text"></td>';
        str += '<td>';
        str += '<div class="input-group date">';
        str += '<div class="input-group-addon">';
        str += '<i class="fa fa-calendar"></i>';
        str += '</div>';
        str += '<input class="form-control pull-right datepicker" name="access_card[effect_date][]" value="" type="text">';
        str += '</div>';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="access_card[charge_code_id][]">';
        str += '<option value="">Select</option>';
        $.each(charge_codes,function (i, item) { 
            str += '<option value="'+item.charge_code_id+'">'+item.charge_code+'</option>';
        }); 
        str += '</select>';
        str += '</td>';
        
    
        str += '<td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" data-value="'+row_id+'" ><i class="fa fa-minus"></i></a></td>';
    
        str += '</tr>';
        
        $('.access_card_tbody').append(str);
        
        $('.datepicker').unbind('datepicker');  
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        
        $('.del_more_btn').unbind('click');
        $('.del_more_btn').bind('click',function (){
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