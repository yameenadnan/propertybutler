<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
    <div class="form-group">
      <label for="property_id">Property Name: </label>
        <?php echo $service_details['property_name'];?>
    </div>
</div>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Asset Name : </label>
        <?php echo $service_details['asset_name'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Asset Description : </label>
        <?php echo $service_details['asset_descri'];?>
    </div>
</div>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Service Date: </label>
        <?php echo $service_details['service_date'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Job Sheet No : </label>
        <?php echo $service_details['job_sheet_no'];?>
    </div>
</div>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Service By: </label>
        <?php echo $service_details['service_by'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Service Description : </label>
        <?php echo $service_details['service_description'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Remarks : </label>
        <?php echo $service_details['remarks'];?>
    </div>
</div>

<?php if(!empty($service_details_att)) { ?>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Attachment : </label>
      <ol>
      <?php foreach ($service_details_att as $key=>$val) { ?>
        <li><a href="<?php echo base_url().'bms_uploads/asset_service_entry_docs/'.date('Y',strtotime($service_details['created_date'])).'/'.date('m',strtotime($service_details['created_date'])).'/'.$val['file_name'];?>" target="_blank" >Attachment <?php echo ($key+1);?></a></li>
      <?php } ?>
      </ol>
    </div>
</div>

<?php } ?>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Details Entered By : </label>
        <?php echo $service_details['first_name']. ' '.$service_details['last_name'];?> <b>on</b> <?php echo $service_details['created_date'];?>
    </div>
</div>
