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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_charge_codes/charge_code_form_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Category Group Name *</label>
                                  <select class="form-control" id="charge_code_group_id" name="charge_code[charge_code_group_id]">
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($cat_group as $key=>$val) { 
                                                    $selected = !empty($charge_code_info['charge_code_group_id']) && $charge_code_info['charge_code_group_id'] == $val['charge_code_group_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['charge_code_group_id']."' ".$selected."' data-pname='".$val['charge_code_group_name']."'>".$val['charge_code_group_name']."</option>";
                                                } ?> 
                                        </select>
                                </div>
                                <input type="hidden" id="charge_code_id" name="charge_code_id" value="<?php echo $charge_code_id;?>"/>
                            </div>                                    
                        </div>
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                    
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Category Name *</label>
                                  <input type="text" name="charge_code[charge_code_category_name]" class="form-control" value="<?php echo isset($charge_code_info['charge_code_category_name']) && $charge_code_info['charge_code_category_name'] != '' ? $charge_code_info['charge_code_category_name'] : '';?>" placeholder="Enter Category Name" maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Category Charge Code</label>
                                  <input type="text" name="charge_code[charge_code]" class="form-control" value="<?php echo isset($charge_code_info['charge_code']) && $charge_code_info['charge_code'] != '' ? $charge_code_info['charge_code'] : '';?>" placeholder="Enter Category Charge Code" maxlength="20">
                                </div>
                            </div>                                      
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                            <b >Modules</b>
                            
                                <div class="checkbox">
                                    <label><input name="charge_code[payment]" value="1" type="checkbox" <?php echo !empty($charge_code_info['payment']) && $charge_code_info['payment'] == 1 ? "checked='checked'" : ""; ?>> Payment</label>
                                </div>
                                <div class="checkbox">
                                    <label><input name="charge_code[expenses]" value="1" type="checkbox" class="prop_chk" <?php echo !empty($charge_code_info['expenses']) && $charge_code_info['expenses'] == 1 ? "checked='checked'" : ""; ?>> Expenses</label>
                                </div>
                                <div class="checkbox">
                                    <label><input name="charge_code[purchase_order]" value="1" type="checkbox" class="prop_chk" <?php echo !empty($charge_code_info['purchase_order']) && $charge_code_info['purchase_order'] == 1 ? "checked='checked'" : ""; ?> > Purchase Order</label>
                                </div>
                                <div class="checkbox">
                                    <label><input name="charge_code[receipt]" value="1" type="checkbox" class="prop_chk" <?php echo !empty($charge_code_info['receipt']) && $charge_code_info['receipt'] == 1 ? "checked='checked'" : ""; ?>> Receipt</label>
                                </div>
                                <div class="checkbox">
                                    <label><input name="charge_code[bills]" value="1" type="checkbox" class="prop_chk" <?php echo !empty($charge_code_info['bills']) && $charge_code_info['bills'] == 1 ? "checked='checked'" : ""; ?>> Bills</label>
                                </div>
                            
                            
                        </div>
                    
                    </div> <!-- . col-md-12 -->
                    
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 sub_cat_div" >
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                    <div class="form-group">
                                        <label >Sub Category Name </label>
                                      <input type="text" name="sub_cat[charge_code_sub_category_name][]" class="form-control" value="<?php echo isset($charge_code_sub_cat_info[0]['charge_code_sub_category_name']) && $charge_code_sub_cat_info[0]['charge_code_sub_category_name'] != '' ? $charge_code_sub_cat_info[0]['charge_code_sub_category_name'] : '';?>" placeholder="Enter Sub Category Name" maxlength="150">
                                      <input type="hidden" name="sub_cat[charge_code_sub_category_id][]" value="<?php echo isset($charge_code_sub_cat_info[0]['charge_code_sub_category_id']) && $charge_code_sub_cat_info[0]['charge_code_sub_category_id'] != '' ? $charge_code_sub_cat_info[0]['charge_code_sub_category_id'] : '';?>" />
                                    </div>
                                </div>
                                                                    
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                    <div class="input-group">
                                        <label >Sub Category Charge Code</label>
                                      <input type="text" name="sub_cat[charge_code][]" class="form-control" value="<?php echo isset($charge_code_sub_cat_info[0]['charge_code']) && $charge_code_sub_cat_info[0]['charge_code'] != '' ? $charge_code_sub_cat_info[0]['charge_code'] : '';?>" placeholder="Enter Sub Category Name" maxlength="150">
                                      <span class="input-group-btn" style="left: 15px;top: 12px;">
                                            <button class="btn btn-success btn-add-other" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span> 
                                    </div>
                                </div>
                                                                    
                            </div>
                        </div>
                        <?php if(!empty($charge_code_sub_cat_info) && count($charge_code_sub_cat_info)>1) {
                            for($k = 1; $k < count($charge_code_sub_cat_info); $k++) { ?>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                    <div class="form-group">
                                        <label >Sub Category Name </label>
                                      <input type="text" name="sub_cat[charge_code_sub_category_name][]" class="form-control" value="<?php echo isset($charge_code_sub_cat_info[$k]['charge_code_sub_category_name']) && $charge_code_sub_cat_info[$k]['charge_code_sub_category_name'] != '' ? $charge_code_sub_cat_info[$k]['charge_code_sub_category_name'] : '';?>" placeholder="Enter Sub Category Name" maxlength="150">
                                      <input type="hidden" name="sub_cat[charge_code_sub_category_id][]" value="<?php echo isset($charge_code_sub_cat_info[$k]['charge_code_sub_category_id']) && $charge_code_sub_cat_info[$k]['charge_code_sub_category_id'] != '' ? $charge_code_sub_cat_info[$k]['charge_code_sub_category_id'] : '';?>" />
                                    </div>
                                </div>
                                                                    
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                    <div class="form-group">
                                        <label >Sub Category Charge Code</label>
                                      <input type="text" name="sub_cat[charge_code][]" class="form-control" value="<?php echo isset($charge_code_sub_cat_info[$k]['charge_code']) && $charge_code_sub_cat_info[$k]['charge_code'] != '' ? $charge_code_sub_cat_info[$k]['charge_code'] : '';?>" placeholder="Enter Sub Category Name" maxlength="150">
                                      
                                    </div>
                                </div>
                                                                    
                            </div>
                        </div>
                        
                                
                        <?php }
                        } ?>
                    
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
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
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
    
    
    
    $('.btn-add-other').click(function () {
        addSubCategory ();    
    });
    
    
    $( "#bms_frm" ).validate({
		rules: {			
            "charge_code[charge_code_group_id]": "required",
            "charge_code[charge_code_category_name]": "required"                   
		},
		messages: {			
            "charge_code[charge_code_group_id]": "Please select Category Group Name",
            "charge_code[charge_code_category_name]": "Please enter Category Name"            
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

function addSubCategory () {
    var str = '<div class="col-md-12 col-sm-12 col-xs-12 no-padding more-sub-cat" >';
    str += '<div class="col-md-6 col-sm-6 col-xs-12 no-padding">';
    str += '    <div class="col-md-12 col-sm-12 col-xs-12">                                    ';
    str += '        <div class="form-group">';
    str += '            <label >Sub Category Name </label>';
    str += '          <input type="text" name="sub_cat[charge_code_sub_category_name][]" class="form-control" value="" placeholder="Enter Sub Category Name" maxlength="150">';
    str += '        </div>';
    str += '    </div>';
    str += '</div>';
    str += '';
    str += '<div class="col-md-6 col-sm-6 col-xs-12 no-padding">';
    str += '    <div class="col-md-12 col-sm-12 col-xs-12">                                    ';
    str += '        <div class="input-group">';
    str += '            <label >Sub Category Charge Code</label>';
    str += '          <input type="text" name="sub_cat[charge_code][]" class="form-control" value="" placeholder="Enter Sub Category Name" maxlength="20">';
    str += '          <span class="input-group-btn" style="left: 15px;top: 12px;">';
    str += '                <button class="btn btn-danger btn-remov-other" type="button">';
    str += '                    <span class="glyphicon glyphicon-minus"></span>';
    str += '                </button>';
    str += '            </span> ';
    str += '        </div>';
    str += '    </div>';
    str += '</div>';
    str += '</div>';
    
    $('.sub_cat_div').append(str);
    $('.btn-remov-other').unbind('click');
    $('.btn-remov-other').bind('click',function (){
       $(this).parents('div.more-sub-cat').remove(); 
    }); 
}


</script>