<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_chart_of_accounts/coa_acc_form_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Group *</label>
                                  <select class="form-control" id="coa_acc_group_id" name="coa_name[coa_acc_group_id]">
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($acc_group as $key=>$val) { 
                                                    $selected = !empty($coa_name_info['coa_acc_group_id']) && $coa_name_info['coa_acc_group_id'] == $val['coa_acc_group_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['coa_acc_group_id']."' ".$selected."'>".$val['coa_acc_group_name']."</option>";
                                                } ?> 
                                        </select>
                                </div>
                                <input type="hidden" id="coa_acc_name_id" name="coa_acc_name_id" value="<?php echo $coa_acc_name_id;?>"/>
                            </div>                                    
                        </div>
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                    
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Name *</label>
                                  <input type="text" name="coa_name[coa_acc_name]" class="form-control" value="<?php echo isset($coa_name_info['coa_acc_name']) && $coa_name_info['coa_acc_name'] != '' ? $coa_name_info['coa_acc_name'] : '';?>" placeholder="" maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Code *</label>
                                  <input type="text" name="coa_name[coa_acc_code]" class="form-control" value="<?php echo isset($coa_name_info['coa_acc_code']) && $coa_name_info['coa_acc_code'] != '' ? $coa_name_info['coa_acc_code'] : '';?>" placeholder="" maxlength="10">
                                </div>
                            </div>  
                            
                            <!--div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Period Format</label>
                                  <input type="text" name="coa_name[period]" class="form-control" value="<?php echo isset($coa_name_info['period']) && $coa_name_info['period'] != '' ? $coa_name_info['period'] : '';?>" placeholder="" maxlength="10">
                                </div>
                            </div-->  
                                                                
                        </div>
                    </div> <!-- . col-md-12 -->
                    
                  </div><!-- . row -->
                                  
              <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div>
          </div><!-- /.box-body -->
          
          <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
                <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                    <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                  </div>
                </div>
            </div>
          </div>
        </form>
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    $('#coa_acc_group_id').focus();
    
    $( "#bms_frm" ).validate({
		rules: {			
            "coa_name[coa_acc_group_id]": "required",
            "coa_name[coa_acc_name]": "required", 
            "coa_name[coa_acc_code]": "required"                 
		},
		messages: {			
            "coa_name[coa_acc_group_id]": "Please select Account Group",
            "coa_name[coa_acc_name]": "Please enter Account Name",
            "coa_name[coa_acc_code]": "Please enter Account Code"            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.hasClass( "datepicker" ) ) {
				error.insertAfter( element.parent( "div" ) );
			} else if ( element.prop( "id" ) === "datepicker" ) {
				error.insertAfter( element.parent( "div" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		}
	});
      
     
      
});

</script>