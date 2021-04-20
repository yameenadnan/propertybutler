<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
    <div class="form-group">
      <label for="property_id">Property Name: </label>
        <?php echo $meetings['property_name'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Meeting Description: </label>
        <?php echo !empty($meetings['meeting_descrip']) ? $meetings['meeting_descrip'] : ' - ';?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Meeting Venue: </label>
        <?php echo !empty($meetings['meeting_venue']) ? $meetings['meeting_venue'] : ' - ';?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Meeting Date: </label>
        <?php echo !empty($meetings['meeting_date']) && $meetings['meeting_date'] != '0000-00-00' ? date('d-m-Y', strtotime($meetings['meeting_date'])) : ' - ';?>
    </div>
</div>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Meeting Time: </label>
        <?php echo !empty($meetings['meeting_time']) ? date('h:i a',strtotime($meetings['meeting_time'])) : ' - ';?>
    </div>
</div>
<table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>   
                  <th>AGM Checklist</th>               
                  <th>Role Responsibility</th>
                  <th>Status</th>
                  <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $offset = 0;
                    //$task_status = $this->config->item('task_db_status');
                    if(!empty($check_list)) {
                        //$prop_doc_download_desi_id = $this->config->item('prop_doc_download_desi_id');
                        //$task_status = $this->config->item('task_db_status');
                        foreach ($check_list as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td><?php echo $val['meeting_descrip'];?></td>
                            <td><?php echo !empty($val['desi_name']) ? $val['desi_name'] : ' - ';?></td>
                            <td><?php 
                                $checked = $val['meeting_checklist_status'] == 1 ? "checked='checked'" : '';
                                $disabled = $_SESSION['bms']['designation_id'] != $val['meeting_responsibility'] ? "disabled='disabled'" : '';
                                $readonly = $_SESSION['bms']['designation_id'] != $val['meeting_responsibility'] ? 'readonly="true"' : '';    
                                
                            ?><input type="checkbox" data-id="id_<?php echo $val['meeting_checklist_id'];?>" data-value="<?php echo $val['meeting_checklist_id'];?>" class="chklist_chk" value="1" <?php echo $checked. ' '.$disabled; ?> /></td>
                            <td><textarea id="id_<?php echo $val['meeting_checklist_id'];?>" rows="1" <?php echo $readonly; ?>><?php echo !empty($val['meeting_checklist_remarks']) ? $val['meeting_checklist_remarks'] : '';?></textarea></td>                            
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="text-center" colspan="5">No Checklist Found</td>                            
                        </tr>                    
                    <?php } ?>                
                </tbody>                
              </table>
              
<div style="clear: both;height:10px"></div>
      <div class="modal-footer">   
        <button type="button" class="btn btn-primary update_btn" >Update</button> &ensp;    
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
              
<script>
$(document).ready(function (){
   
   $('.update_btn').unbind('click');
   $('.update_btn').bind('click',function (){
        //console.log('Update event triggered'); 
        var a = {};
        $('.chklist_chk').each(function() {
            if ($(this).is(":checked") && !$(this).is(":disabled")) {
                //console.log($(this).attr('data-value') + ' - '+$('#'+$(this).attr('data-id')).val());
                a[$(this).attr('data-value')] = $('#'+$(this).attr('data-id')).val();
            }
        
        });
        //console.log(Object.keys(a).length +' - ' +a);
        if(Object.keys(a).length > 0 ) {
            $.ajax({
              url:"<?php echo base_url('index.php/bms_meetings/checklist_status_update');?>",
              data: a,
              type: 'post',
              success: function(data) {
                if(data) {
                    alert('Status updated successfully!');
                } else {
                    alert('Status not updated!');
                }
              }
            });
        }
   });
});
</script>              

