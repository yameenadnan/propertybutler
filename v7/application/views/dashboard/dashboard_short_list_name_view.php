<?php foreach ($short_listed_staff as $key=>$val) { ?>

<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
    <div class="form-group">
      <label><?php echo $staff_award_cat[$key];?>: </label>
      <ol>
          <?php foreach ($val as $key2=>$val2) { ?>
          <?php echo "<li>".$val2['first_name'].(!empty($val2['last_name']) ? ' '.$val2['last_name'] : '')." - ".$val2['desi_name'].(!empty($val2['property_name']) ? ' @ '.$val2['property_name']  : '')."</li>"; ?>
          <?php } ?>
      </ol>
    </div>
</div>
<?php } ?>
