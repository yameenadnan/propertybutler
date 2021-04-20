<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
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
              <div class="box-body" style="padding-top: 15px;">
              
              <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
                ?>
                  <div class="row">
                    <div class="col-md-4 col-xs-4">
                        <select class="form-control select2" id="property_id" name="property_id">
                            
                            <option value="">Select Property</option>
                            
                            <?php 
                                foreach ($properties as $key=>$val) { 
                                    $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                } ?> 
                        </select>
                    </div>
                    
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right datepicker" name="from" id="from" type="text"  value="<?php echo !empty($_GET['from']) ? $_GET['from'] : '';?>" placeholder="From" />
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right datepicker" name="to" id="to" type="text"  value="<?php echo !empty($_GET['to']) ? $_GET['to'] : '';?>" placeholder="To" />
                            </div>
                            <!-- /.input group -->
                          </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-3">                        
                          <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter text to search">
                    </div>
                    
                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary list_filter"><i class="fa fa-search"></i></a>
                    </div>
                                      
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs" style="text-align: center;width:5%">S No</th>
                  <th class="col-md-2">Transaction ID</th>
                  <th class="col-md-2">Reference No.</th>
                  <th class="col-md-2">Payment Mode</th>
                  <th class="col-md-2">Payment For</th>
                  <th class="col-md-2">Amount</th>
                  <th class="col-md-2">Date</th>
                </tr>
                </thead>
                <tbody id="content_tbody">
                     <tr><td class="text-center" colspan="7">No Record Found</td></tr>           
                </tbody>                
              </table>
            </div>
          
          <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    
                    Show: &nbsp;<select id="records_per_page">                            
                            <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>                            
                            <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                            <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                        </select>
                    
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                    
                    <div class="paging_right_div" style="padding-right: 5px;">
                        <span class="previous_link"></span> 
                        <span>Page <input class="publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span small_loader"></span></span>
                        <span class="next_link"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages" value="" type="hidden">                                           
                    </div>
                </div>
                
            </div> 
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>
<script>

$(document).ready(function () {
    
    $('.select2').select2();   
    
    $('.msg_notification').fadeOut(5000);
    
    $('.list_filter').click(function () {
        loadContent(0,jQuery('#records_per_page').val(),true);     
    });
    
    jQuery('#records_per_page').change(function(e){
        loadContent(0,jQuery('#records_per_page').val(),true);
        return false; 
    });
    
    jQuery('.publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    loadContent((jQuery(this).val()-1)*jQuery('#records_per_page').val(),jQuery('#records_per_page').val(),true);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }                
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }              
        }
        
    });
    
    if($('#property_id').val() != '' && $('#from').val() != '' && $('#val').val() != '' ) {
        loadContent('<?php echo $offset;?>','<?php echo $per_page;?>',false);    
    }
});

function loadContent (offset,rows,flag) {
    //$('#search_txt').val($('#search_txt').val().replace(/^\s+|\s+$/g,""));
    if($('#property_id').val() == '' ) {
        alert('Please select Property');$('#property_id').focus();return false;
    }
    if($('#from').val() == '' ) {
        alert('Please select From');$('#from').focus();return false;
    }
    if($('#to').val() == '' ) {
        alert('Please select To');$('#to').focus();return false;
    }
    
    var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_direct_pay/getPaymentsList');?>',
        data: {'offset':offset,'rows':rows,'property_id':$('#property_id').val(),'from':$('#from').val(),'to':$('#to').val(),'search_txt':search_txt},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_tbody").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) { 
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td>'+(item.Transaction_ID == null ? ' - ' : item.Transaction_ID)+'</td>';
                    str += '<td>'+(item.Reference_Number == null ? ' - ' : item.Reference_Number)+'</td>';
                    str += '<td>'+(item.pymt_for == null ? ' - ' : (item.pymt_for == '1' ? 'Clamping' : 'Over night parking'))+'</td>';
                    str += '<td>'+(item.Payment_ID == 2 ? ' Cerdit/Debit Card ' : ' FPX ')+'</td>';
                    str += '<td>'+(item.Amount == null ? ' - ' : 'RM '+(item.Amount).toString(2))+'</td>';
                    str += '<td>'+(item.trans_date == null ? ' - ' : item.trans_date)+'</td>';
                                        
                    str += '</tr>';
                });
                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.publi_paging').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages').val(total_pages);
                jQuery('.tot_pag_span').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#tot_rec_span').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                
                var previous_link = ""; 
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)-eval(rows))+","+rows+",true);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)+eval(rows))+","+rows+",true);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);
                
                
                $("#content_tbody").html(str);
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td></tr>';
                str += '<td class="visible-xs text-center" colspan="6">No Record Found</td></tr>';
                $("#content_tbody").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }
            
            //$('.showing_stat').html('Showing '+(eval(offset)+(numFound > 0 ? 1 : 0))+' - '+showing_to+' Of '+numFound+' Record(s). Total Unit(s) : '+$('#property_id').find("option:selected").attr('data-value'));
            //$(this).attr('title',$('#property_id').find("option:selected").attr('data-value'));
            
            // This is to update the url
            if(flag) {
                if (typeof (history.pushState) != "undefined") {
                    var update_url = '<?php echo base_url('index.php//bms_direct_pay/payments');?>/'+offset+'/'+rows+'?property_id='+$('#property_id').val()+'&from='+$('#from').val()+'&to='+$('#to').val()+'&search_txt='+search_txt;
                    var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler' ;?>', Url: update_url };
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    console.log("Browser does not support HTML5.");
                }
            }
            
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });    
}

$(function () {
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
});

function formatDate(in_date) {
  var in_date_arr = in_date.split(' ');
  var in_date_arr2 = in_date_arr[0].split('-');
  var in_date_arr3 = in_date_arr[1].split(':');
  var am_pm = in_date_arr3[0] >= 12 ? 'pm' : 'am';
  var hours = in_date_arr3[0] > 12 ? in_date_arr3[0] - 12 : in_date_arr3[0];
  return in_date_arr2[2] + "-" + in_date_arr2[1] + "-" + in_date_arr2[0] + " " + hours + ":" + in_date_arr3[1] + " " +am_pm;
}
</script>