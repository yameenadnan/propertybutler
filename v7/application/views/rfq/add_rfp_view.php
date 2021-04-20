<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">  
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_rfq/add_rfq_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                        
                       <div class="col-md-6 col-sm-12 col-xs-12 no-padding" > 
                            
                                
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                            
                            <div class="col-md-4 col-sm-5">
                              <label >Property Name *</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                                <select class="form-control" id="property_id" name="rfq[property_id]">
                                    <option value="">Select Property</option>
                                    <?php 
                                        foreach ($properties as $key=>$val) { 
                                            $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                            echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                                </select>                                        
                            </div>
                           
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                            <div class="col-md-4 col-sm-5">
                              <label >RFQ Title *</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                            <input type="text" name="rfq[rfq_title]"  class="form-control" value=""  /> 
                              
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                            <div class="col-md-4 col-sm-5">
                              <label >Vendor Search By *</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                            <div>
                                <input type="radio" name="rfq[search_by]" value="1"  /> &ensp; Category &ensp; &ensp; &ensp; &ensp; &ensp; &ensp;
                              <input type="radio" name="rfq[search_by]" value="2" /> &ensp; Keywords
                              </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 1_cls same_cls" style="padding-bottom: 15px;display: none;">
                            <div class="col-md-4 col-sm-5">
                              <label >Category</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                                <select class="form-control" id="category_id" name="rfq_cat[]">
                                    <option value="">Select</option>
                                    <?php 
                                        foreach ($category as $key=>$val) { 
                                            //selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                            echo "<option value='".$val['vendor_cat_id']."' >".$val['vendor_cat_name']."</option>";
                                        } ?> 
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 2_cls same_cls" style="padding-bottom: 15px;">
                            <div class="col-md-4 col-sm-5">
                              <label >Keywords</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                            <select class="form-control select2 show-tick selectpicker"  data-live-search="true"  id="keywords" name="rfq_kw[]" multiple>                            
                               
                                    <option value="">Select</option>
                                    <?php 
                                        foreach ($keywords_arr as $key=>$val) { 
                                            //selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                            echo "<option value='".$val."' >".$val."</option>";
                                        } ?> 
                                </select>                                        
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                            <div class="col-md-4 col-sm-5">
                              <label >State</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                                <select class="form-control select2 show-tick selectpicker"  data-live-search="true"  id="state_id" name="rfq_state[]" multiple> 
                                    <option value="">Select</option>
                                    <?php 
                                        foreach ($state as $key=>$val) { 
                                            //selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                            echo "<option value='".$val['state_id']."' >".$val['state_name']."</option>";
                                        } ?> 
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 15px;">
                            <div class="col-md-4 col-sm-5">
                              <label >City</label>
                            </div>
                            <div class="col-md-8 col-sm-7">
                                <select class="form-control select2 show-tick selectpicker"  data-live-search="true"  id="city" name="rfq_city[]" multiple>
                                    <option value="">Select</option>
                                    <?php 
                                        foreach ($city as $key=>$val) { 
                                            //selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                            echo "<option value='".$val['city_id']."' >".$val['city_name']."</option>";
                                        } ?> 
                                </select>                                        
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            
                                <div class="col-md-4 col-sm-5">
                                    <label>Last Date For Quotation Submission </label>
                                </div>
                                
                                <div class="col-md-8 col-sm-7">
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control datepicker" value="" type="text" />
                                      <input type="hidden" name="rfq[ldf_quo_sub]" value="<?php echo date('d-m-Y');?>" />
                                    </div>
                                    <!-- /.input group -->
                                </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right" style="padding-right: 15px;">
                            <input type="button" class="btn btn-success load_vendor_btn" value="Load Vendors" />
                        </div>  
                        
                      </div>
                      
                      <div class="col-md-6 col-sm-12 col-xs-12 vendor_name" style="min-height: 250px; max-height: 300px;overflow-y: scroll;" > 
                      <label style="padding-left:15px;">Vendor's List</label>
                      </div>
                                               
                    
                
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12" style="margin-top: 15px;">
                    <div class="col-md-12 no-parking items_container" style="-webkit-box-shadow: 3px 3px 7px 3px rgba(0,0,0,0.5);-moz-box-shadow: 3px 3px 7px 3px rgba(0,0,0,0.5);box-shadow: 3px 3px 7px 3px rgba(0,0,0,0.5);border-box:5px;padding: 15px 0; background-color:#F0F0F0;" >
                        <div class="col-md-12 no-padding" >
                            <div class="col-md-4">
                                <label>Description</label>
                            </div>                            
                            <div class="col-md-2">                    
                              <label>Quantity</label>
                            </div>
                            <div class="col-md-2">                    
                              <label>UOM</label>
                            </div>
                            <div class="col-md-3">
                              <label>Remarks</label>
                            </div>
                            
                            <div class="col-md-1">&nbsp;</div>             
                        </div>
                        
                        <div class="col-md-12 no-padding item" style="padding-top: 10px !important;" >                   
                            
                            <div class="col-md-4">
                              <input type="text" name="items[item_descrip][]" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">
                            </div>
                            <div class="col-md-2">
                              <input type="number" name="items[qty][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" required="required">
                            </div> 
                            <div class="col-md-2">
                              <input type="text" name="items[uom][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" required="required">
                            </div>  
                            <div class="col-md-3">
                              <textarea name="items[remarks][]" class="form-control" rows="2" placeholder="Enter Remarks"></textarea>
                            </div>  
                            <div class="col-md-1 text-center">
                                <a href="javascript:;" class="btn btn-success btn-circle" id="add_item"><i class="fa fa-plus"></i></a>
                            </div>                      
                        </div>
                        
                    </div>
                    </div>        
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;" >
                        <div class="col-md-9 col-xs-12">
                                <div class="col-md-2 col-sm-3">
                                    <label>Notes (if any)</label>
                                </div>
                                <div class="col-md-6 col-sm-9">
                                      
                                  <textarea name="rfq[message]" class="form-control" rows="4" placeholder="Enter Notice Message"></textarea>
                                </div>
                            </div>                    
                    </div>
                    
                    
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="">Attachment</label>
                              <div >
                            		<label class="btn-bs-file btn btn-primary">
                                    Choose File...
                            			
                            			<!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                        <input type="file" id="attach_file" name="attach" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                            		</label>
                            		&nbsp;
                            		<span class='label label-info' id="upload-file-info"></span>
                        	  </div>
                            </div>
                        </div>
                    </div>
                    
                    
                  </div><!-- . row -->
                  
                  <!-- . row -->
                        
                
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
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<!-- SELECT 2 -->
<script
     src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>
 $(document).ready(function (){
    $('.select2').select2();
    $('.same_cls').val('').css('display','none');
    $('.'+$('input[name="rfq[search_by]"]:checked').val()+'_cls').slideDown();    
    $('input[name="rfq[search_by]"]').change(function (){
        $('.same_cls').val('').css('display','none');
        $('.'+$('input[name="rfq[search_by]"]:checked').val()+'_cls').slideDown();
    });
    
    $('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
    
    
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"rfq[property_id]": "required",
            "rfq[rfq_title]": "required",
            "rfq[search_by]": "required", 
            "rfq[ldf_quo_sub]": "required"           
		},
		messages: {
			"rfq[property_id]": "Please select Property Name",
            "rfq[rfq_title]": "Please enter RFQ Title ",
            "rfq[search_by]": "Please select Vendor Search By",   
            "rfq[ldf_quo_sub]": "Please select Last Date For Quotation Submission"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent('div') );
			} else if ( element.hasClass( "datepicker" ) ) {
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
    
    $('#state_id').change(function () {
        
        var state_id = [];
        $.each($('#state_id'), function (){
            state_id.push($(this).val());            
        });
        if(state_id.length > 0) {
            $.ajax({
                 type:"post",
                 async: true,
                 url: '<?php echo base_url('index.php/bms_rfq/get_city');?>',
                 data: {'state_id':state_id.join(',')},
                 datatype:"json", // others: xml, json; default is html

                 beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                 success: function(data) {
                    //console.log(data);
                     var str = '<option value="">Select</option>';
                     if(data.length > 0) {
                         $.each(data,function (i, item) {
                             str += '<option value="'+item.city_id+'">'+item.city_name+'</option>';
                         });
                     }
                     $('#city').html(str);
                     $("#content_area").LoadingOverlay("hide", true);

                 },
                 error: function (e) {
                     $("#content_area").LoadingOverlay("hide", true);
                     console.log(e); //alert("Something went wrong. Unable to retrive data!");
                 }
             }); 
        } else {
            $('#city').html('');
        }
    });
    
    
    $('.load_vendor_btn').click(function () {
        if(!$('input[name="rfq[search_by]').is(':checked')) {
            alert('Please select Vendor Search By!'); 
            return false;             
        }
        var s_type = '';
        var val = '';
        if($('input[name="rfq[search_by]"]:checked').val() == '1') {
            s_type = 'cat';
            if($('#category_id').val() == '') {
                alert('Please select Category!'); 
                $('#category_id').focus();
                return false;
            }
            val = $('#category_id').val();            
        } 
        if($('input[name="rfq[search_by]"]:checked').val() == '2') {
            s_type = 'kw';
            var kw = [];
            $.each($("#keywords option:selected"), function(){            
                kw.push($(this).val());
            });
            if(kw.length == 0) {
                alert('Please select Keywords!'); 
                $('#keywords').focus();
                return false;                
            }
            val = kw.join(',');                        
        }
        //console.log($('#state_id').val() + " === city ===>"+ $("#city").val());
        
        $.ajax({
                 type:"post",
                 async: true,
                 url: '<?php echo base_url('index.php/bms_rfq/get_vendor');?>',
                 data: {'s_type':s_type,'val':val,'state_id':$('#state_id').val().join(','),'city':$("#city").val().join(',')},
                 datatype:"json", // others: xml, json; default is html

                 beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                 success: function(data) {
                    //console.log(data);
                     var str = '<label style="padding-left:15px;">Vendor\'s List</label>';
                     if(data.length > 0) {
                         str += '<ol>';
                         $.each(data,function (i, item) {
                             str += '<li>'+item.vendor_name+'</li>';
                         });
                         str += '</ol>';
                     }
                     $('.vendor_name').html(str);
                     $("#content_area").LoadingOverlay("hide", true);

                 },
                 error: function (e) {
                     $("#content_area").LoadingOverlay("hide", true);
                     console.log(e); //alert("Something went wrong. Unable to retrive data!");
                 }
             });  
       
    });
     
             
});

$('#add_item').click (function () {
    var str ='<div class="col-md-12 no-padding item" style="padding-top: 10px !important;" >';
    str +='<div class="col-md-4">';
    str +='<input type="text" name="items[item_descrip][]" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">';
    str +='</div>';
    str +='<div class="col-md-2">';
    str +='<input type="number" name="items[qty][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" required="required">';
    str +='</div>';
    str +='<div class="col-md-2">';
    str +='<input type="text" name="items[uom][]" class="form-control amt_cal" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" required="required">';
    str +='</div>';
    str +='<div class="col-md-3">';
    str +='<textarea name="items[remarks][]" class="form-control" rows="2" placeholder="Enter Remarks"></textarea>';
    str +='</div>';
    str +='<div class="col-md-1 text-center">';
    str +='<a href="javascript:;" class="btn btn-danger btn-circle del_item"><i class="fa fa-minus"></i></a>';
    str +='</div>';
    str +='</div>';
    $('.items_container').append(str);
    $('.del_item').unbind('click');
    $('.del_item').click(function () {
       $(this).parents('div.item').remove();
        
    });
});

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        startDate: '<?php echo date('d-m-Y', strtotime(date('Y-m-d').'+5 days'));?>',
        autoclose: true
    });   
    
  })
</script>