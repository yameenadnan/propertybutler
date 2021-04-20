<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
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
            <!-- /.box-header -->
              <div class="box-body">
                  <div class="row">
                  <div class="col-md-12 col-xs-12 no-padding" style="">
                      <div class="col-md-11 col-xs-10">
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Property Name</label>
                                    <select class="form-control" id="property_id" name="property_id">
                                        <option>Select</option>
                                        <?php
                                            foreach ($properties as $key=>$val) {
                                                $selected = !empty($property_id) && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                                echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4">
                                <div class="form-group">
                                      <label>Defect ID</label>
                                      <input type="text" id="defect_id" value="<?php echo !empty($_GET['defect_id']) ? trim($_GET['defect_id']) : '';?>" class="form-control" placeholder="Enter Task ID">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4">
                                <div class="form-group">
                                      <label>Search</label>
                                      <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter Search Text">
                                </div>
                            </div>
                            <div class="col-md-1 col-xs-2 no-padding" style="vertical-align: middle;margin-top:25px"><a href="javascript:;" role="button" class="btn btn-primary defect_filter"><i class="fa fa-search"></i>&nbsp;Search</a>&nbsp;
                            </div>
                            <div class="col-md-1 col-xs-2 no-padding" style="vertical-align: middle;margin-top:25px">
                                <a href="javascript:;" style="margin-left: 25px;" role="button" class="btn btn-primary defect_print"><i class="fa fa-print"></i>&nbsp;Print</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <div class="form-group">
                                  <label>Status</label>
                                  <select class="form-control" id="defect_status" name="defect_status">
                                    <?php
                                        $defect_status_array = $this->config->item('defect_filter_status');
                                        foreach ($defect_status_array as $key=>$val) {
                                            $selected = !empty($_GET['defect_status']) && trim($_GET['defect_status']) == $key ? 'selected="selected" ' : '';
                                            echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                        }
                                    ?>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-4">
                                <div class="form-group">
                                  <label>Sort By</label>
                                  <select class="form-control" id="sort_by" name="sort_by">
                                      <option value="desc" <?php echo !empty ($_GET['sort_by']) && $_GET['sort_by'] == 'desc' ? 'selected="selected"':'';?>>Descending Order</option>
                                      <option value="asc" <?php echo !empty ($_GET['sort_by']) && $_GET['sort_by'] == 'asc' ? 'selected="selected"':'';?>>Ascending Order</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>   
                                        
                    
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Block</th>
                  <th>Unit No.</th>
                  <th>Defect ID</th>
                  <th>Defect Title</th>
                  <th>Defect Location</th>
                  <th>Created date</th>
                  <th>Status</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody id="defect_tbody">
                </tbody>
              </table>
            </div>
            
            
            <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    Show: &nbsp;<select id="my_records_per_page">
                            <option value="10" selected="selected">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                    <div class="paging_right_div" style="padding-right: 5px;">
                        <span class="my_previous_link"></span> 
                        <span>Page <input class="my_publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="my_tot_pag_span small_loader"></span></span>
                        <span class="my_next_link"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="my_tot_pages" value="" type="hidden">                                           
                    </div>
                </div>
            </div>
         </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('.defect_filter').click(function () {
        var defect_id = $('#defect_id').val().replace(/^\s+|\s+$/g,"");
        var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
        window.location.href="<?php echo base_url('index.php/bms_defect/defect_list');?>?property_id="+$('#property_id').val()+"&defect_status="+$('#defect_status').val()+"&defect_id="+defect_id+"&search_txt="+search_txt+"&sort_by="+$('#sort_by').val();
        return false;        
    });

    $('.defect_print').click(function () {
        var defect_id = $('#defect_id').val().replace(/^\s+|\s+$/g,"");
        var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
        printDefectList( "<?php echo base_url('index.php/bms_defect/print_defect_list');?>?property_id="+$('#property_id').val()+"&defect_status="+$('#defect_status').val()+"&defect_id="+defect_id+"&search_txt="+search_txt+"&sort_by="+$('#sort_by').val() );
        return false;
    });



    loadDefect ('<?php echo $offset;?>','<?php echo $rows;?>',true);

    jQuery('#my_records_per_page').change(function(e){
        loadDefect(0,jQuery('#my_records_per_page').val(),false);
        return false; 
    });
    
    jQuery('.my_publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#my_tot_pages').val()) {
                    loadDefect((jQuery(this).val()-1)*jQuery('#my_records_per_page').val(),jQuery('#my_records_per_page').val(),false);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#my_tot_pages').val());
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
    
    if(<?php echo isset($_GET['act']) && $_GET['act'] == 'print' && isset($_GET['defect_id']) && $_GET['defect_id'] != '' ? 1 : 0  ?>) {
        window.open('<?php echo base_url('index.php/bms_defect/print_defect/'.(!empty($_GET['defect_id']) ? $_GET['defect_id'] : ''));?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no');
        return false;
    }
    
});

function loadDefect (offset,rows,flag) {
    var defect_id = $('#defect_id').val().replace(/^\s+|\s+$/g,"");
    var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");

    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_defect/get_defect_list');?>',
        data: {'property_id':$('#property_id').val(),'defect_status':$('#defect_status').val(),'defect_id':defect_id,'offset':offset,'rows':rows,"search_txt":search_txt,"sort_by":$('#sort_by').val()},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#defect_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#defect_tbody").LoadingOverlay("hide", true);
            var str = ''; 
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) {                    
                    str += '<tr>';                    
                    str += '<td class="hidden-xs">'+(eval(offset)+eval(i)+1)+'</td>';
                    str += '<td>'+item.block_name+'</td>';
                    str += '<td>'+item.unit_no+'</td>';
                    str += '<td><a href="<?php echo base_url('index.php/bms_defect/defect_details/');?>'+item.defect_id+'">'+item.defect_id.padStart(5, "0");+'</a></td>';
                    str += '<td>'+item.defect_name+'</td>';
                    str += '<td>'+item.defect_location+'</td>';
                    str += '<td class="hidden-xs">'+(item.created_date != '' ? formatDate(item.created_date) : '')+'</td>';
                    
                    if (item.defect_status == 'O' ) {
                        str += '<td >Open</td>';
                    } else {
                        str += '<td >Closed</td>';
                    }
                    str += '<td style="text-align: center;">';
                    str += '<a href="<?php echo base_url('index.php/bms_defect/defect_details/');?>'+item.defect_id+'" title="Update"><i class="fa fa-edit"></i></a> &ensp;';
                    str += '<a href="javascript:;" onclick="printDiv(\'<?php echo base_url('index.php/bms_defect/print_defect/');?>'+item.defect_id+'\')" title="Print"><i class="fa fa-print"></i></a> &ensp;';
                    str += '</td>';
                    str += '</tr>';
                });
                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.my_publi_paging').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#my_tot_pages').val(total_pages);
                jQuery('.my_tot_pag_span').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#my_tot_rec_span').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                
                var previous_link = ""; 
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadDefect("+(eval(offset)-eval(rows))+","+rows+");'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadDefect("+(eval(offset)+eval(rows))+","+rows+");'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.my_previous_link').html(previous_link);
                jQuery('.my_next_link').html(next_link);
                
                
                $("#defect_tbody").html(str);
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="9">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="8">No Record Found</td></tr>';
                $("#defect_tbody").html(str);
                jQuery('.my_tot_pag_span').html('1');
                jQuery('.my_next_link').html('');
            }
        },
        error: function (e) {            
            $("#defect_tbody").LoadingOverlay("hide", true);
            console.log(e);
        }
    });
}

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no');
}

function printDefectList(url) {
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no');
}

function formatDate(date) {  
  var date_arr = date.split('-');
  return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}
</script>