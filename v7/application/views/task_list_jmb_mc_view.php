<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_task/new_task');?>" >New Task</a>
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
          <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
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
                          <label for="exampleInputEmail1">Property Name</label>
                            <select class="form-control" id="property_id" name="property_id">
                            
                            <?php 
                                foreach ($properties as $key=>$val) { 
                                    $selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                } ?> 
                              </select>
                
                          <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                          <label for="exampleInputPassword1">Task Status</label>
                          <select class="form-control" id="task_status" name="task_status">
                                <?php 
                                $task_cat = $this->config->item('task_filter_status');
                                foreach ($task_cat as $key=>$val) {
                                    $selected = isset($_GET['task_status']) && trim($_GET['task_status']) != '' && trim($_GET['task_status']) == $key ? 'selected="selected" ' : '';
                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                }
                            ?>
                              </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                          <label>Task ID</label>
                          <input type="text" id="search_id" value="<?php echo isset($_GET['search_id']) && trim($_GET['search_id']) != '' ? trim($_GET['search_id']) : '';?>" class="form-control" placeholder="Enter Task ID">
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                          <label>Search</label>
                          <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter Search Text">
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                          <label>Sort By</label>
                          <select class="form-control" id="sort_by" name="sort_by">
                            <option value="due_date">Due Date</option>  
                            <option value="created_date" <?php echo !empty($_GET['sort_by']) && $_GET['sort_by'] == 'created_date' ? 'selected="selected"' : '';?>>Created Date</option>  
                          </select>
                        </div>
                    </div>
                    
                    <div class="col-md-1 col-xs-3" style="margin-top:25px">
                        <a href="javascript:;" role="button" class="btn btn-primary task_filter"><i class="fa fa-search"></i></a>
                    </div>
                    
                                        
                    
                </div>
                
              </div>
              <!-- /.box-body -->
           
              <div class="box-body">
              <table id="example3" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Property</th>
                  <th>Task Name</th>
                  <th class="hidden-xs">Created Date</th>
                  <th class="hidden-xs">Due Date</th>
                  <th>Status</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody id="os_tasks_tbody">
                    
                
                </tbody>
                
              </table>
            </div>
              
          
          
          <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    
                    Show: &nbsp;<select id="records_per_page">
                            <option value="10" selected="selected">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
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
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('.task_filter').click(function () {
        var search_id = $('#search_id').val().replace(/^\s+|\s+$/g,"");
        var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
        window.location.href="<?php echo base_url('index.php/bms_task/task_list');?>?property_id="+$('#property_id').val()+"&task_status="+$('#task_status').val()+"&search_id="+search_id+"&search_txt="+search_txt+"&sort_by="+$('#sort_by').val();
        return false;        
    });
    
    loadTask ('<?php echo $os_offset;?>','<?php echo $os_rows;?>');
    
    jQuery('#records_per_page').change(function(e){
        loadTask(0,jQuery('#records_per_page').val());
        return false; 
    });
    
    jQuery('.publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    loadTask((jQuery(this).val()-1)*jQuery('#records_per_page').val(),jQuery('#records_per_page').val());
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
    
});

function loadTask (offset,rows) {
    var search_id = $('#search_id').val().replace(/^\s+|\s+$/g,"");
    var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_task/get_os_task_list');?>',
        data: {'property_id':$('#property_id').val(),'task_status':$('#task_status').val(),'search_id':search_id,'offset':offset,'rows':rows,"search_txt":search_txt,"sort_by":$('#sort_by').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#os_tasks_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#os_tasks_tbody").LoadingOverlay("hide", true);
            var str = ''; 
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) {                    
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+(eval(offset)+eval(i)+1)+'</td>';
                    str += '<td>'+item.property_name+'</td>';
                    str += '<td>'+item.task_name+'</td>';
                    str += '<td class="hidden-xs">'+(item.created_date != '' ? formatDate(item.created_date) : '')+'</td>';
                    str += '<td class="hidden-xs">'+(item.due_date != '' ? formatDate(item.due_date) : '')+'</td>';
                    var task_stat = '';
                    
                    if(item.task_status == 'O' || item.task_status == 'R') {
                        //task_stat = diff_days(new Date(item.due_date),new Date('now'));
                        toDate = parseInt(new Date(item.due_date).getTime()/1000); 
                        fromDate = parseInt(new Date('<?php echo date('Y-m-d');?>').getTime()/1000);
                        //console.log(item.task_status);
                        var days_diff = (toDate - fromDate)/(3600*24);
                        //console.log(days_diff);
                        if(days_diff > 0) {
                            str += '<td >'+(item.task_status == 'R' ? 'Re-Assigned' : 'OPEN')+'</td>';
                        } else if(days_diff == 0) {
                            str += '<td class="text-info">'+(item.task_status == 'R' ? 'Re-Assigned' : 'OPEN')+'(Due Day)</td>';
                        } else if(days_diff == -1) {
                            str += '<td class="text-danger">'+(item.task_status == 'R' ? 'Re-Assigned' : 'OPEN')+'(OD '+days_diff+' Day)</td>';
                        } else {
                            str += '<td class="text-danger">'+(item.task_status == 'R' ? 'Re-Assigned' : 'OPEN')+'(OD '+days_diff+' Days)</td>';
                        }
                    } else {
                        //console.log(task_stat);
                        str += '<td >Closed</td>';
                    }
                    str += '<td style="text-align: center;"><a href="<?php echo base_url('index.php/bms_task/task_details/');?>'+item.task_id+'"><i class="fa fa-edit"></i></a></td>';
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
                    previous_link = "<a href='javascript:;' onclick='loadTask("+(eval(offset)-eval(rows))+","+rows+",false);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadTask("+(eval(offset)+eval(rows))+","+rows+",false);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);
                
                
                $("#os_tasks_tbody").html(str);
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#os_tasks_tbody").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }
            
        },
        error: function (e) {
            $("#os_tasks_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
    
}

function formatDate(date) {  
  var date_arr = date.split('-');
  return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}
</script>