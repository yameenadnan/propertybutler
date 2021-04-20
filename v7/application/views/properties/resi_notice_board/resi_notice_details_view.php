<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
    <div class="form-group">
      <label for="property_id">Property Name: </label>
        <?php echo $notice_details['property_name'];?>
    </div>
</div>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Notice Title : </label>
        <?php echo $notice_details['notice_title'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Start Date : </label>
        <?php echo $notice_details['start_date'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>End Date : </label>
        <?php echo $notice_details['end_date'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Message : </label><br />
        <?php echo $notice_details['message'];?>
    </div>
</div>
<?php if(!empty($notice_details['attachment_name'])) { ?>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Attachment : </label>
        <a href="<?php echo base_url().'bms_uploads/resident_notice_attach/'.$notice_details['property_id'].'/'.$notice_details['attachment_name'];?>" target="_blank" >Click here to View / Download</a>
    </div>
</div>

<?php } ?>


