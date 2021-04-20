<h3>Current Owner </h3>
<table id="datatable" class="table table-striped table-bordered">
<thead>
	<tr>
		<th>Owner Name</th>
        <th>Defaulter Resident</th>
        <th>Identity No</th>
		<th>Gender</th>
		<th>Nationality</th>
		<th>Contact No</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php if(!empty($owner_info) && !empty($owner_info['owner_name'])) {  ?>
        <tr>
            <td><?php echo $owner_info['owner_name'];?></td>
            <td><?php echo !empty($owner_info['is_defaulter']) && $owner_info['is_defaulter'] == 1 ? 'Yes' : 'No';?></td>
            <td><?php echo !empty($owner_info['ic_passport_no']) ? $owner_info['ic_passport_no'] : ' - ';?></td>
            <td><?php echo !empty($owner_info['gender']) ? $owner_info['gender'] : ' - ';?></td>
            <td><?php echo !empty($owner_info['nationality']) ? $owner_info['nationality'] : ' - ';?></td>
            <td><?php echo !empty($owner_info['contact_1']) ? $owner_info['contact_1'] : ' - ';?></td>
            <td class="text-center"><a href="javascript:;" class="add_owner_btn" data-value="curr_owner" data-unitId="<?php echo $unit_id;?>" title="Edit"><i class="fa fa-edit"></i></a></td>
        </tr>
    <?php
} else { ?>
<tr><td colspan="6" style="text-align: center;"> No record found!</td></tr>
<?php } ?>
</tbody>
</table>
<h3>History Of Owners</h3>
<table id="datatable" class="table table-striped table-bordered">
<thead>
	<tr>
		<th>Owner Name</th>
        <th>Identity No</th>
		<th>Gender</th>
		<th>Nationality</th>
		<th>Contact No</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php if(!empty($owner_hist)) {
        foreach($owner_hist as $key=>$val) { ?>
        <tr>
            <td><?php echo $val['owner_name'];?></td>
            <td><?php echo !empty($val['ic_passport_no']) ? $val['ic_passport_no'] : ' - ';?></td>
            <td><?php echo !empty($val['gender']) ? $val['gender'] : ' - ';?></td>
            <td><?php echo !empty($val['nationality']) ? $val['nationality'] : ' - ';?></td>
            <td><?php echo !empty($val['contact_1']) ? $val['contact_1'] : ' - ';?></td>
            <td class="text-center"><!--a href="javascript:;" title="View"><i class="fa fa-info-circle"></i></a> &ensp;--><a href="javascript:;" class="add_owner_btn" data-value="<?php echo $val['unit_owner_id'];?>" data-unitId="<?php echo $unit_id;?>" data-toggle="modal" data-target="#myModal" title="Edit"><i class="fa fa-edit"></i></a></td>
        </tr>
    <?php }
} else { ?>
<tr><td colspan="7" style="text-align: center;"> No record found!</td></tr>
<?php } ?>
</tbody>
</table>

<button type="button" class="btn btn-info add_owner_btn" data-value="new" data-unitId="<?php echo $unit_id;?>" data-toggle="modal" data-target="#myModal">Add Owner</button>

<!-- Modal2 -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary" style="padding: 10px 15px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" style="margin-top: -15px;">

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
  $(document).ready( function () {

    $('.add_owner_btn').unbind('click');
    $('.add_owner_btn').bind("click",function () {

        console.dir ( 'Event trigger' );

        if ($(this).attr('data-value') == 'new') {
            $('.modal-title').html ('Add Owner');
        } else {
            $('.modal-title').html ('Update Owner');
        }
        $('.modal-body').load('<?php echo base_url('index.php/bms_unit_setup/getOwner/');?>'+$(this).attr('data-value')+'/'+$(this).attr('data-unitId'),function(result){
    	    $('#myModal').modal({show:true});
    	});
    });
    <?php if ( !empty( $invalid_email ) && $invalid_email == 'yes' ) { ?>

      $('.add_owner_btn').first().trigger('click');
    <?php }  ?>
});
</script>