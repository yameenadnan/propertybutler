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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_notifications/add_todo_submit');?>" method="post" enctype="multipart/form-data" autocomplete="off">
              <div class="box-body">
                  <div class="row">
                  
                  
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Description *</label>
                                    <input type="text" name="to_do[description]" value="<?php echo !empty($todo_info['description']) ? $todo_info['description'] : '';?>" class="form-control" placeholder="Enter Description" maxlength="250">
                                </div>
                                <input type="hidden" id="todo_id" name="todo_id" value="<?php echo $todo_id;?>"/>
                                <input type="hidden" name="to_do[staff_id]" value="<?php echo $_SESSION['bms']['staff_id'];?>"/>
                            </div>                                    
                        </div>
                        
                        <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                            <label>Expected Complete Date *</label>
            
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right" name="to_do[complete_date]" value="<?php echo !empty($todo_info['complete_date']) ? date('d-m-Y',strtotime($todo_info['complete_date'])) : '';?>" id="datepicker" type="text" />
                            </div>
                            <!-- /.input group -->
                          </div>
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
                    <button type="submit" class="btn btn-primary">Save</button> &ensp;&ensp;
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
    
    $('#coa_acc_type_id').focus();
    
    $( "#bms_frm" ).validate({
		rules: {	
            "to_do[description]": "required",
            "to_do[complete_date]": "required"
                          
		},
		messages: {	
		    "to_do[description]": "Please Enter Description!",
            "to_do[complete_date]": "Please select Expected Complete Date"   
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

$(function () {
//Date picker
    $('#datepicker').datepicker({
      format: 'dd-mm-yyyy',
      startDate: '<?php echo date('d-m-Y');?>',
      autoclose: true   
    });
    
});

</script>