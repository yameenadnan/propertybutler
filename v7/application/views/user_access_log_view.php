<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
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
          <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }
        if(isset($_GET['group']) && $_GET['group'] ==1) {
            echo "<input type='hidden' name='group' id='group' value='1' />";
        } else {
            echo "<input type='hidden' name='group' id='group' value='0' />";
        }
        
        ?>
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Designation Name</label>
                            <select class="form-control" id="desi_id" name="desi_id">
                            <option value="all">All</option>
                            <?php 
                                foreach ($designations as $key=>$val) { 
                                    $selected = isset($_GET['desi_id']) && trim($_GET['desi_id']) != '' && trim($_GET['desi_id']) == $val['desi_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['desi_id']."' ".$selected.">".$val['desi_name']."</option>";
                                } ?> 
                              </select>
                
                          <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                          <label for="exampleInputPassword1">Staff Name</label>
                          <select class="form-control" id="staff_id" name="staff_id">
                            <option value="all">All</option>
                                <?php 
                                //$task_cat = $this->config->item('task_filter_status');
                                foreach ($staff_names as $key=>$val) {
                                    $selected = isset($_GET['staff_id']) && trim($_GET['staff_id']) != '' && trim($_GET['staff_id']) == $val['staff_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val['staff_id']."' ".$selected.">".$val['first_name'].' '.$val['last_name']."</option>";
                                }
                            ?>
                              </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-6">
                        
                        <div class="form-group">
                            <label>Choose Date *</label>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right" name="rec_date" id="datepicker" type="text" value="<?php echo isset($_GET['rec_date']) && trim($_GET['rec_date']) != '' ? date('d-m-Y',strtotime($_GET['rec_date'])) : '' ;?>" />
                            </div>
                            <!-- /.input group -->
                          </div> 
                    </div>
                    
                    
                    <div class="col-md-3 col-xs-6" style="margin-top:25px">
                        <a href="javascript:;" role="button" class="btn btn-primary log_filter"><i class="fa fa-search"></i></a>
                    </div>
                    
                                        
                    
                </div>
                
              </div>
             
              
           
              <div class="box-body">
              <table id="example3" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th class="col-md-2 col-xs-2">Staff Name</th>                  
                  <th class="col-md-2 col-xs-2">IP Address</th>
                  <th class="col-md-2 col-xs-2">GEO Coordinates</th>                  
                  <th class="col-md-2 col-xs-2">Module</th>
                  <th class="col-md-2 col-xs-2">Method</th>
                  <th class="col-md-2 col-xs-2">Date Time</th>
                </tr>
                </thead>
                <tbody id="content_tbody">
                    
                
                </tbody>
                
              </table>
            </div>
              
          
          
          <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    
                    Show: &nbsp;<select id="records_per_page">
                            <option value="10" >10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100" selected="selected">100 per page</option>
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
  
  <!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Staff Property Details</h4>
      </div>
      <div class="modal-body modal-body2">
        
        <div class="xol-xs-12 msg">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="xol-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  
  <!-- bootstrap datepicker -->
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    
    
    loadLogList ('<?php echo $offset;?>','<?php echo $rows;?>');
    jQuery('#records_per_page').change(function(e){
        loadLogList(0,jQuery('#records_per_page').val());
        return false; 
    });
    
    jQuery('.publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    loadLogList((jQuery(this).val()-1)*jQuery('#records_per_page').val(),jQuery('#records_per_page').val());
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
    
    jQuery('#desi_id').change(function( event ) {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_user_access_log/get_staff_names');?>',
            data: {'desi_id':$('#desi_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                //console.log(data);
                var str = '<option value="all">All</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.staff_id+'">'+item.first_name +' '+item.last_name+'</option>';
                    });
                }
                $('#staff_id').html(str);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
        
    });
    
    $('.log_filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_user_access_log/user_access_log_list');?>?desi_id="+$('#desi_id').val()+"&staff_id="+$('#staff_id').val()+"&rec_date="+$('#datepicker').val()+"&group="+$('#group').val();
        return false;  
        loadLogList(0,100);      
    });
    
});

function loadLogList (offset,rows) {
    
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_user_access_log/get_user_access_log');?>',
        data: {'desi_id':$('#desi_id').val(),'staff_id':$('#staff_id').val(),'rec_date':$('#datepicker').val(),'offset':offset,'rows':rows},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_tbody").LoadingOverlay("hide", true);
            var str = ''; 
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) { 
                    var d = new Date(item.accessed_date);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+(eval(offset)+eval(i)+1)+'</td>';
                    str += '<td> <a href="javascript:;" class="staff_property" data-value="'+item.staff_id+'">'+item.first_name+' '+item.last_name+'</a></td>';
                    /*if(item.property_name.length > 100)
                        str += '<td title="'+item.property_name+'">'+item.property_name.substring(0, 97)+'...</td>';
                    else 
                        str += '<td>'+item.property_name+'</td>';*/
                    str += '<td>'+item.ip_address+'</td>';
                    str += '<td><a href="https://www.google.com/maps/?q='+item.latitude+','+item.longitude+'" target="_blank">'+item.latitude+','+item.longitude+'</a></td>';
                    str += '<td>'+item.accessed_module+'</td>';
                    str += '<td>'+item.accessed_method+'</td>';
                    str += '<td>'+formatDate(d)+'</td>';                   
                    
                    
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
                    previous_link = "<a href='javascript:;' onclick='loadLogList("+(eval(offset)-eval(rows))+","+rows+",false);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadLogList("+(eval(offset)+eval(rows))+","+rows+",false);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);
                
                
                $("#content_tbody").html(str);
                
                $('.staff_property').unbind('click');
                $('.staff_property').bind("click",function () {
                    $('.modal-body2').load('<?php echo base_url('index.php/bms_user_access_log/staff_property_details/');?>'+$(this).attr('data-value'),function(result){
                	    $('#myModal2').modal({show:true});
                	});
                });
                        
                
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }
            
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });    
}
function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  var mont = date.getMonth()+1;
  return  date.getDate() + "-" + mont + "-" + date.getFullYear() + "  " + strTime;
}

$(function () {
//Date picker
    $('#datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true   
    });
    
});
</script>