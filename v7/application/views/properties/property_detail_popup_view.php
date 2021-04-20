<div class="col-md-12 col-xs-12 no-padding">
    <div class="box-header with-border" style="padding-left:15px ;">
      <h3 class="box-title"><b>Property Information</b></h3>
    </div>
    <div class="col-md-12 col-xs-12 no-padding">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
          <label>Property Name: </label>
          <?php echo !empty($property_info['property_name']) ? $property_info['property_name'] : '';?>
        </div>
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
          <label>Property Type: </label>
          <?php echo !empty($property_info['type_name']) ? $property_info['type_name'] : '';?>
        </div>
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
          <label>Property Abbreviation: </label>
          <?php echo !empty($property_info['property_abbrev']) ? $property_info['property_abbrev'] : '';?>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 no-padding" >
        <div class="col-md-12 col-xs-6" >
            <div class="form-group">
              <label>Property Under: </label>
                <?php
                    $property_under = $this->config->item('property_under');
                    echo !empty( $property_under[$property_info['property_under']] ) ? $property_under[$property_info['property_under']]:'';
                ?>
            </div>
        </div>

        <div class="col-md-12 col-xs-6">
            <div class="form-group">
              <label>Total Units: </label>
              <?php echo !empty($property_info['total_units']) ? $property_info['total_units'] : '';?>
            </div>
        </div>
    </div>

    <div style="clear: both;height:1px"></div>

    <div class="col-md-12" style="padding: 0;">
        <div class="col-md-12 col-xs-12">
            <div class="form-group">
              <label>Address: </label><br />
              <?php echo !empty($property_info['jmb_mc_name']) ? $property_info['jmb_mc_name'] : '';?><br />
              <?php echo !empty($property_info['address_1']) ? $property_info['address_1'] : '';?> <?php echo !empty($property_info['address_2']) ? $property_info['address_2'] . '<br />': '';?>
              <?php echo !empty($property_info['pin_code']) ? $property_info['pin_code'] : '';?> <?php echo !empty($property_info['city']) ? ',' . $property_info['city'] : '';?>
              <?php echo !empty($property_info['state_name']) ? "<br>" . $property_info['state_name'] : '';?>
              <?php echo !empty($property_info['country_name']) ? "<br>" . $property_info['country_name'] : '';?>
              <br><label>Phone: </label>
              <?php echo !empty($property_info['phone_no']) ? $property_info['phone_no'] : '';
              echo !empty($property_info['phone_no2']) ? "<br>" . $property_info['phone_no2'] : ''; ?>
              <br><label>Email Address: </label>
              <?php echo !empty($property_info['email_addr']) ? $property_info['email_addr']: '';?>
              <?php echo !empty($property_info['fax']) ? '<br><label>FAX: </label>' . $property_info['fax']: '';?>
            </div>
        </div>
    </div>
    <?php if( !empty($blocks) && count($blocks) > 1 ) { ?>
        <div class="col-md-12 col-xs-12 no-padding">
            <div class="col-md-12 col-xs-12">
                <label>Block / Street Name</label>
                <?php
                   for ($k=0;$k < count($blocks); $k++) {
                       if ( ($k + 1) == count($blocks))
                           echo !empty($blocks[$k]['block_name']) ? $blocks[$k]['block_name']:'';
                       else
                           echo !empty($blocks[$k]['block_name']) ? $blocks[$k]['block_name'] . ', ':'';
                   }
                ?>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-12" style="padding: 0;margin-top:15px">
        <div class="col-md-12 col-xs-12 no-padding">

            <div class="col-md-12 col-xs-12">
                <?php if(!empty($property_info['logo'])) {
                    $property_logo_upload = $this->config->item('property_logo_upload');
                    ?>
                    <div class="form-group">
                  <label>Logo:</label><br />
                    <img src="<?php echo base_url().$property_logo_upload['upload_path'].$property_info['logo'];?>" width="150" />
                </div>
                <?php } ?>
            </div>

        </div>

    </div>
    </div>

</div>