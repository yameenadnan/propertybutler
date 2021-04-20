<h3>History Of Tenants</h3>
<table id="datatable" class="table table-striped table-bordered">
<thead>
	<tr>
		<th>Tenant Name</th>        
        <th>Identity No</th>
		<th>Gender</th>
		<th>Nationality</th>		
		<th>Contact No</th>		
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php if(!empty($tenant_hist)) {  
        foreach($tenant_hist as $key=>$val) { ?>
        <tr>
            <td><?php echo $val['tenant_name'];?></td>            
            <td><?php echo !empty($val['ic_passport_no']) ? $val['ic_passport_no'] : ' - ';?></td> 
            <td><?php echo !empty($val['gender']) ? $val['gender'] : ' - ';?></td>
            <td><?php echo !empty($val['nationality']) ? $val['nationality'] : ' - ';?></td>
            <td><?php echo !empty($val['contact_1']) ? $val['contact_1'] : ' - ';?></td>
            <td><!--a href="javascript:;" title="View"><i class="fa fa-info-circle"></i></a> &ensp;-->
            <a href="javascript:;" class="add_tenant_btn" data-value="<?php echo $val['unit_tenant_id'];?>" data-unitId="<?php echo $unit_id;?>"  title="Edit"><i class="fa fa-edit"></i></a></td>      
        
        </tr>
    <?php }
} else { ?>
<tr><td colspan="7" style="text-align: center;"> No record found!</td></tr>
<?php } ?>
</tbody>
</table>

<button type="button" class="btn btn-info add_tenant_btn" data-value="new" data-unitId="<?php echo $unit_id;?>" >Add Tenant</button>

<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary" style="padding: 10px 15px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title2"></h4>
      </div>
      <div class="modal-body modal-body2" style="margin-top: -15px;">
        
        <div class="col-xs-12 msg">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      
    </div>

  </div>
</div>
  
  <script>
  $(document).ready(function (){
    
    
    $('.add_tenant_btn').unbind('click');
    $('.add_tenant_btn').bind("click",function () {
        //$('.modal-dialog-tenant').css('width','630px');
        if($(this).attr('data-value') == 'new') {
            $('.modal-title2').html ('Add Tenant');
        } else {
            $('.modal-title2').html ('Update Tenant');
        }
        $('.modal-body2').load('<?php echo base_url('index.php/bms_unit_setup/getTenant/');?>'+$(this).attr('data-value')+'/'+$(this).attr('data-unitId'),function(result){
    	    $('#myModal2').modal({show:true});
    	});
    });    
    
  });
  </script>