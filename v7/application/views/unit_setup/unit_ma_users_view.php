
    <form role="form" id="bms_owner_frm" action="<?php echo base_url('index.php/bms_unit_setup/setMaUsers');?>" method="post" autocomplete="off" >
      <h3>VMS/Mobile App Users</h3>
      <div class="col-md-12 col-xs-12 no-padding" style="">
                    
                    <div class="box-body">
                    
                    <input type="hidden" id="unit_id" name="ma_user[unit_id]" value="<?php echo $unit_id;?>"/>
                    <input type="hidden" name="ma_user[property_id]" value="<?php echo !empty($ma_user[0]['property_id']) ? $ma_user[0]['property_id'] : (!empty($owner_info['property_id']) ? $owner_info['property_id'] : '');?>" />
                    
                    
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>             
                          <th>Name</th>
                          <th>Contact No</th>
                          <th>Email</th>
                          <th>Password</th>                                                    
                          <th>Add / Remove</th>                          
                        </tr>
                        </thead>
                        <tbody class="parking_tbody">
                        <?php if(!empty($ma_user)) {
                            for ($i =0; $i < count($ma_user); $i++) { ?>
                        <tr class="item">
                            <td>  
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="<?php echo isset($ma_user[$i]['ma_user_name']) && $ma_user[$i]['ma_user_name'] != '' ? $ma_user[$i]['ma_user_name'] : '';?>" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="<?php echo !empty($ma_user[$i]['unit_ma_user_id']) ? $ma_user[$i]['unit_ma_user_id'] : '';?>" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="<?php echo isset($ma_user[$i]['ma_user_contact']) && $ma_user[$i]['ma_user_contact'] != '' ? $ma_user[$i]['ma_user_contact'] : '';?>" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="<?php echo isset($ma_user[$i]['ma_user_email']) && $ma_user[$i]['ma_user_email'] != '' ? $ma_user[$i]['ma_user_email'] : '';?>" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="<?php echo isset($ma_user[$i]['ma_user_pass']) && $ma_user[$i]['ma_user_pass'] != '' ? $ma_user[$i]['ma_user_pass'] : '';?>" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-circle <?php echo $i== 0 ? 'btn-success add_more_btn' : 'btn-danger del_more_btn';?> " ><i class="fa fa-<?php echo $i== 0 ? 'plus' : 'minus';?>"></i></a></td>                            
                        </tr>
                        <?php
                            }
                        } else if (!empty($owner_info) && $owner_info['unit_status'] != '2') {
                            
                             ?>
                        <tr class="item">
                            <td>  
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="<?php echo !empty($owner_info['owner_name']) ? $owner_info['owner_name'] : '';?>" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="<?php echo !empty($owner_info['contact_1']) ? $owner_info['contact_1'] : '';?>" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-success btn-circle add_more_btn"  ><i class="fa fa-plus"></i></a></td>                            
                        </tr>
                        <tr class="item">
                            <td>  
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="<?php echo !empty($owner_info['owner_name']) ? $owner_info['owner_name'] : '';?>" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="<?php echo !empty($owner_info['contact_2']) ? $owner_info['contact_2'] : '';?>" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" ><i class="fa fa-minus"></i></a></td>                            
                        </tr>
                        <?php
                        } else if(!empty($tenant_info) && $owner_info['unit_status'] == '2') {
                             ?>
                        <tr class="item">
                            <td> 
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="<?php echo !empty($tenant_info['tenant_name']) ? $tenant_info['tenant_name'] : '';?>" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="<?php echo !empty($tenant_info['contact_1']) ? $tenant_info['contact_1'] : '';?>" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-success btn-circle add_more_btn"  ><i class="fa fa-plus"></i></a></td>                            
                        </tr>
                        <tr class="item">
                            <td>  
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="<?php echo !empty($tenant_info['tenant_name']) ? $tenant_info['tenant_name'] : '';?>" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="<?php echo !empty($tenant_info['contact_2']) ? $tenant_info['contact_2'] : '';?>" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" ><i class="fa fa-minus"></i></a></td>                            
                        </tr>
                        <?php
                            
                            
                        } else {
                            
                             ?>
                        <tr class="item">
                            <td>  
                                <input type="text" name="ma_user[ma_user_name][]" class="form-control" value="" placeholder="Enter Name" maxlength="100">
                                <input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />
                            </td>
                            <td>
                                <input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="" placeholder="Enter Contact No" maxlength="15">                                
                            </td>
                            <td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100">
                            <td>
                                <input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">                            
                            </td>
                            <td class="text-center"><a href="javascript:;" class="btn btn-success btn-circle add_more_btn"  ><i class="fa fa-plus"></i></a></td>                            
                        </tr>
                        
                        <?php
                            
                            
                        } ?>
                        
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
  
    $(document).ready(function (){
    
    $('.add_more_btn').click(function () {        
        add_parking();
        //$(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
  
    
    $('.del_more_btn').bind('click',function (){
       $(this).parents('tr.item').remove();
    });
    
    function add_parking () {
        if($('input[name="ma_user[ma_user_name][]"').length < 6) {
        //console.log($('input[name="ma_user[ma_user_name][]"').length);
            var str = '<tr class="item">';
            str += '<td>';
            str += '<input type="text" name="ma_user[ma_user_name][]" class="form-control" value="" placeholder="Enter Name" maxlength="100">';
            str += '<input type="hidden" name="ma_user[unit_ma_user_id][]" value="" />';
            str += '</td>';
            str += '<td>';
            str += '<input type="text" name="ma_user[ma_user_contact][]" class="form-control" value="" placeholder="Enter Contact No" maxlength="15">';
            str += '</td>';
            str += '<td><input type="text" name="ma_user[ma_user_email][]" class="form-control" value="" placeholder="Enter Email" maxlength="100"></td>';
            str += '<td>';
            str += '<input type="password" name="ma_user[ma_user_pass][]" class="form-control" value="" placeholder="Enter Password" maxlength="50">';
            str += '</td>';
            
            str += '<td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" ><i class="fa fa-minus"></i></a></td>';
        
            str += '</tr>';
            
            $('.parking_tbody').append(str);
            
            
            $('.del_more_btn').unbind('click');
            $('.del_more_btn').bind('click',function (){
               //$('#'+$(this).attr('data-value')).remove();
               $(this).parents('tr.item').remove(); 
            });
        } else {
            alert('You cannot add more than 6 users!'); 
            return false;
        }
    }
    
  });
  
  
  </script>