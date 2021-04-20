<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
    <div class="form-group">
      <label for="property_id">Property Name: </label>
        <?php echo $notice_details['property_name'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Block / Street : </label>
        <?php echo $notice_details['block_id'] == '0' ? 'All' : $notice_details['block_name'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Unit(s) : </label>
        <?php 
        if($notice_details['unit_ids']  != 'All' && !empty($unit_info)) {
            $units = '';
            foreach ($unit_info as $key=>$val) {
                $units .= $val['unit_no'].', ';
            }
            echo rtrim($units,', ');
            
        } else if (empty($notice_details['unit_ids']) || $notice_details['unit_ids']  == 'All') {
            echo "All";
        } else 
            echo ' - ';
        //echo $notice_details['block_id'] == '0' ? 'All' : $notice_details['block_name'];?>
    </div>
</div>
<!--div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Start Date : </label>
        <?php echo $notice_details['start_date'];?>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>End Date : </label>
        <?php echo !empty($notice_details['end_date']) ? $notice_details['end_date'] : ' - ';?>
    </div>
</div-->
<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Subject : </label>
        <?php echo $notice_details['subject'];?>
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
        <a href="<?php echo base_url().'bms_uploads/e_notice_attach/'.date('Y',strtotime($notice_details['created_date'])).'/'.date('m',strtotime($notice_details['created_date'])).'/'.$notice_details['attachment_name'];?>" target="_blank" >Click here to View / Download</a>
    </div>
</div>

<?php } ?>

<div class="col-md-12 col-xs-12">
    <div class="form-group">
      <label>Created & Sent By : </label>
        <?php echo $notice_details['first_name']. ' '.$notice_details['last_name'];?> <b>on</b> <?php echo $notice_details['created_date'];?>
    </div>
</div>
