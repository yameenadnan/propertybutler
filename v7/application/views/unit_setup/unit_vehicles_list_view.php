<h3>Vehicle Details</h3>
<table id="datatable" class="table table-striped table-bordered">
<thead>
	<tr>
		<th>Vehicle No.</th>        
        <th>Vehicle Type</th>
		<th>Make</th>
		<th>Model</th>		
		<th>Color</th>		
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php if(!empty($vehicles)) {  
        foreach($vehicles as $key=>$val) { ?>
        <tr>
            <td><?php echo $val['vehicle_no'];?></td>            
            <td><?php echo !empty($val['vehicle_type']) ? $vehicle_type[$val['vehicle_type']] : ' - ';?></td> 
            <td><?php echo !empty($val['make']) ? $val['make'] : ' - ';?></td>
            <td><?php echo !empty($val['model']) ? $val['model'] : ' - ';?></td>
            <td><?php echo !empty($val['color']) ? $val['color'] : ' - ';?></td>
            <td><!--a href="javascript:;" title="View"><i class="fa fa-info-circle"></i></a> &ensp;-->
            <a href="javascript:;" class="add_vehicle_btn" data-value="<?php echo $val['vehicle_id'];?>" data-unitId="<?php echo $unit_id;?>"  title="Edit"><i class="fa fa-edit"></i></a></td>      
        
        </tr>
    <?php }
} else { ?>
<tr><td colspan="7" style="text-align: center;"> No record found!</td></tr>
<?php } ?>
</tbody>
</table>

<button type="button" class="btn btn-info add_vehicle_btn" data-value="new" data-unitId="<?php echo $unit_id;?>" >Add Vehicle</button>

<!-- Modal2 -->
<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary" style="padding: 10px 15px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title3"></h4>
      </div>
      <div class="modal-body modal-body3" style="margin-top: -15px;">
        
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
    
    
    $('.add_vehicle_btn').unbind('click');
    $('.add_vehicle_btn').bind("click",function () {
        //$('.modal-dialog-tenant').css('width','630px');
        if($(this).attr('data-value') == 'new') {
            $('.modal-title3').html ('Add Vehicle');
        } else {
            $('.modal-title3').html ('Update Vehicle');
        }
        $('.modal-body3').load('<?php echo base_url('index.php/bms_unit_setup/getVehicle/');?>'+$(this).attr('data-value')+'/'+$(this).attr('data-unitId'),function(result){
    	    $('#myModal3').modal({show:true});
    	});
    });    
    
  });
  </script>