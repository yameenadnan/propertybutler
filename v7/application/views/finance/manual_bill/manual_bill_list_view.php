<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 25px;
    }

    .select2-container .select2-selection--single {
        height: 34px;
    }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
     
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <form id="frm_sales_invoice" method="post" action="<?php echo base_url('index.php/bms_fin_bills/manual_bill_sendmail');?>">
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
              <div class="box-body">
                  <div class="row">
                  
                    <div class="col-md-2 col-xs-6">
                        <div class="form-group">
                            <label>Property </label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                        echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                    } ?> 
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6" style="">
                        <div class="form-group">
                          <label>Items </label>
                          <select class="form-control" id="coa_id" name="receipt[coa_id]">
                              <option value="">Select</option>
                              <?php
                              foreach ($sales_items as $key=>$val) {
                                  $selected = !empty($_GET['coa_id']) && $_GET['coa_id'] == $val['coa_id'] ?  'selected="selected" ' : '';
                                  echo "<option value='".$val['coa_id']."' ".$selected.">".$val['coa_name']."</option>";
                              } ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-xs-6 no-padding" style="">
                        <div class="form-group">
                                <label>Unit </label>
                          <select name="receipt[unit_id]" class="form-control select2" id="unit_id">
                            <option value="">All</option>
                            <?php
                            if(!empty($units)) {
                                foreach ($units as $key=>$val) {
                                    $selected = !empty($_GET['unit_id']) && $_GET['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
                                    echo "<option value='".$val['unit_id']."'
                                    data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
                                }
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <label>From </label>

                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right datepicker" name="from_date" id="from_date" type="text"  value="<?php echo !empty($_GET['from']) ? $_GET['from'] : '';?>" />
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <label>To </label>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right datepicker" name="to_date" id="to_date" type="text"  value="<?php echo !empty($_GET['to']) ? $_GET['to'] : '';?>" />
                            </div>
                            <!-- /.input group -->
                          </div>
                    </div>

                    <div class="col-md-2 col-xs-4">
                      <div class="form-group">
                          <label>Invoice No. </label>
                          <div class="input-group">
                              <input class="form-control pull-right" name="bill_no" id="bill_no" type="text" value="<?php echo !empty($_GET['bill_no']) ? $_GET['bill_no'] : '';?>" />
                          </div>
                          <!-- /.input group -->
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-xs-1 text-right">
                        <a href="javascript:;" role="button" class="btn btn-primary filter_btn"><i class="fa fa-search"></i></a>
                        <input class="btn btn-primary add_btn" value="Add Sales Invoice" type="button">
                        <input class="btn btn-primary sendmail_btn" value="Send mail" type="button">
                        <a href="javascript:;" role="button" class="btn btn-primary download_btn"><i class="fa fa-download"></i></a>
                    </div>
                  </div>
              </div>
              <!-- /.box-body -->

          <div class="inv_table_container">

            <div class="box-body inv_table">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Property Name</th>
                  <th>Bill No</th>  
                  <th>Unit</th>                  
                  <th>Name</th>
                  <th>Bill Date</th>
                  <th>Due Date</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Email <input type="checkbox" id="checkAll"></th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody id="content_tbody_inv">
                               
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
                      <span style="display: inline-block; padding-left: 15px; font-size: 14px;color:#000;" > Grant Total: </span>
                      <span style="display: inline-block; padding-left: 5px; font-size: 14px;color:#000;font-weight:bold" class="showing_stat" > - </span>
                      &ensp; &ensp;&ensp; &ensp;
                      <span class="previous_link"></span>
                      <span>Page <input class="publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span small_loader"></span></span>
                      <span class="next_link"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages" value="" type="hidden">
                  </div>
              </div>
            </div>
          </div>






          <div class="inv_itm_table_container">
            <div class="box-body inv_itm_table">
              <table id="example2" class="table table-bordered table-hover table-striped">
                  <thead>
                  <tr>
                      <th class="hidden-xs">S No</th>
                      <th>Bill No</th>
                      <th>Unit</th>
                      <th>Owner</th>
                      <th>Bill Date</th>
                      <th>Due Date</th>
                      <th>Amount</th>
                      <th>Status</th>
                  </tr>
                  </thead>
                  <tbody id="content_tbody_inv_itm">

                  </tbody>
              </table>
            </div>

            <div class="row ciovitm" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                  Show: &nbsp;<select id="records_per_page_item">
                      <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>
                      <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                      <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                  </select>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                  <div class="paging_right_div" style="padding-right: 5px;">
                      <span style="display: inline-block; padding-left: 15px; font-size: 14px;color:#000;" > Grant Total: </span>
                      <span style="display: inline-block; padding-left: 5px; font-size: 14px;color:#000;font-weight:bold" class="showing_stat" > - </span>
                      &ensp; &ensp;&ensp; &ensp;
                      <span class="previous_link_item"></span>
                      <span>Page <input class="publi_paging_item" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span_item small_loader"></span></span>
                      <span class="next_link_item"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages_item" value="" type="hidden">
                  </div>
              </div>
            </div>
          </div>

          <!-- /.box -->
        </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>
  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
  <script src="<?php echo base_url();?>assets/js/jquery.number.js"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>

$(document).ready(function () {

    $('.select2').select2();

    $('.msg_notification').fadeOut(5000);
    
    // On property name change
    $('#property_id').change(function () {
        loadUnit ('');
    });
    
    $('.filter_btn').click(function () {
        setViewTable();
    });
    setViewTable();

    $('.download_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/download_manual_bill_list');?>?property_id='+$("#property_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val()+'&unit_id='+$('#unit_id').val()+'&unit_no='+$('#unit_id option:selected').text()+'&bill_no=' + $("#bill_no").val()+'&coa_id=' + $("#coa_id").val()+'&coa_name=' + $("#coa_id option:selected").text();
        return false;
    });

    $('.add_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/add_manual_bill');?>?property_id='+$("#property_id").val();
        return false;  
    });

    $('.sendmail_btn' +
        '').click(function () {
        if ( $("#frm_sales_invoice input:checkbox:checked").length > 0 ) {
            $("#frm_sales_invoice").submit();
        } else {
            alert ('Please select sale invoice to send email to');
        }

    });

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
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
    
    // loadContent('<?php // echo $offset;?>','<?php // echo $per_page;?>',false);




    jQuery('#records_per_page_item').change(function(e){
        loadContentItem(0,jQuery('#records_per_page_item').val(),true);
        return false;
    });

    jQuery('.publi_paging_item').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages_item').val()) {
                    loadContentItem((jQuery(this).val()-1)*jQuery('#records_per_page_item').val(),jQuery('#records_per_page_item').val(),true);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages_item').val());
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

    // loadContentItem('<?php // echo $offset;?>','<?php // echo $per_page;?>',false);
    
});

function setViewTable () {
    if ( $('#coa_id').val().trim() == '') {
        $('.inv_table_container').attr('style','display:block;');
        $('.inv_itm_table_container').attr('style','display:none;');
        loadContent (0,jQuery('#records_per_page').val(),true);
    } else {
        $('.inv_table_container').attr('style','display:none;');
        $('.inv_itm_table_container').attr('style','display:block;');
        loadContentItem (0,jQuery('#records_per_page').val(),true);
    }
}


function loadContent (offset,rows,flag) {

    //var search_txt = $('#unit_id').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/get_bills_list');?>',
        data: {'offset':offset,'rows':rows,'property_id':$('#property_id').val(),'unit_id':$('#unit_id').val(),'from':$("#from_date").val(),'to':$("#to_date").val(), 'bill_no':$("#bill_no").val() },
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody_inv").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody_inv").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            var numFound = data.numFound.num_rows;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td>'+item.property_name+'</td>';
                    str += '<td><a href="<?php echo base_url().'index.php/bms_fin_bills/manual_bill_details/';?>'+item.bill_id+'">'+item.bill_no+'</a></td>';
                    str += '<td>'+item.unit_no+'</td>';
                    str += '<td>'+item.owner_name+'</td>';
                    str += '<td>'+(item.bill_date != '' ? formatDate(item.bill_date) : '')+'</td>';
                    str += '<td>'+(item.bill_due_date != '' ? formatDate(item.bill_due_date) : '')+'</td>';
                    var tot_amt = parseFloat(item.total_amount).toFixed(2);
                    if(item.cn_amt !== null) {
                        tot_amt = (parseFloat(tot_amt) - parseFloat(item.cn_amt)).toFixed(2);
                    }
                    str += '<td>' + tot_amt + '</td>';
                    str += '<td>'+(item.bill_paid_status == '0' ? '<span style="background-color:red;padding:5px;color:#FFF;">Unpaid</span>' : '<span style="background-color:green;padding:5px;color:#FFF;">Paid</span>')+'</td>';
                    str += '<td><input type="checkbox" class="custom-control-input" name="bill_list['+ item.bill_id +']"></td>';
                    str += '<td style="text-align: center;">';

                    str += '<a href="javascript:;" onclick="printDiv(\'<?php echo base_url('index.php/bms_fin_bills/manual_bill_details');?>/'+item.bill_id+'/download\')" title="Download"><i class="fa fa-download"></i></a>&ensp;';
                    str += '<a href="javascript:;" onclick="printDiv(\'<?php echo base_url('index.php/bms_fin_bills/manual_bill_details');?>/'+item.bill_id+'/print\')" title="Print"><i class="fa fa-print"></i></a>&ensp;';
                    if('<?php echo in_array($_SESSION['bms']['designation_id'],$this->config->item('accounts_edit_del_desi')) ? 1 : 0;?>' == '1')  {
                        //str += '<a href="<?php echo base_url('index.php/bms_fin_bills/add_manual_bill');?>/'+item.bill_id+'" title="Edit"><i class="fa fa-info"></i></a>&ensp;';
                        str += '<a href="<?php echo base_url('index.php/bms_fin_bills/add_manual_bill');?>/'+item.bill_id+'" title="Edit"><i class="fa fa-edit"></i></a>&ensp;';
                        str += '<a href="javascript:;" data-id="'+item.bill_id+'" class="del_cls" title="Delete" style="color:red"><i class="fa fa-close"></i></a>';
                    }
                    str += '</td>';
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



                $("#content_tbody_inv").html(str);

                $('.del_cls').unbind('click');

                $('.del_cls').bind("click", function () {
                    remove_item ($(this).attr('data-id'));
                });

            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="11">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody_inv").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }
            var grant_tot = data.numFound.grant_tot;
            if(data.numFound.cn_amt !== null) {
                grant_tot = (parseFloat(grant_tot) - parseFloat(data.numFound.cn_amt)).toFixed(2);
            }

            $('.showing_stat').html($.number(eval(grant_tot),2));
            //$(this).attr('title',$('#property_id').find("option:selected").attr('data-value'));

            // This is to update the url
            if(flag) {
                if (typeof (history.pushState) != "undefined") {
                    var update_url = '<?php echo base_url('index.php/bms_fin_bills/manual_bill_list');?>/'+offset+'/'+rows+'?property_id='+$('#property_id').val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val()+'&bill_no='+$("#bill_no").val();
                    var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Transpacc | BMS' ;?>', Url: update_url };
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    console.log("Browser does not support HTML5.");
                }
            }

        },
        error: function (e) {
            $("#content_tbody_inv").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });

}






function loadContentItem (offset,rows,flag) {
    //var search_txt = $('#unit_id').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_fin_bills/get_bill_item_list');?>',
        data: {'offset':offset,'rows':rows,'property_id':$('#property_id').val(),'unit_id':$('#unit_id').val(),'from':$("#from_date").val(),'to':$("#to_date").val(),'coa_id':$("#coa_id").val(), 'bill_no':$("#bill_no").val() },
        // datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody_inv_itm").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody_inv_itm").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            var numFound = data.numFound.num_rows;
            //console.log(data.numFound);
            if(numFound > 0) {
                $.each(data.records,function (i, item) {
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td><a href="<?php echo base_url().'index.php/bms_fin_bills/manual_bill_details/';?>'+item.bill_id+'">'+item.bill_no+'</a></td>';
                    str += '<td>'+item.unit_no+'</td>';
                    str += '<td>'+item.owner_name+'</td>';
                    str += '<td>'+(item.bill_date != '' ? formatDate(item.bill_date) : '')+'</td>';
                    str += '<td>'+(item.bill_due_date != '' ? formatDate(item.bill_due_date) : '')+'</td>';
                    var tot_amt = parseFloat(item.item_amount).toFixed(2);
                    if(item.cn_amt !== null) {
                        tot_amt = (parseFloat(tot_amt) - parseFloat(item.cn_amt)).toFixed(2);
                    }
                    str += '<td>'+tot_amt+'</td>';
                    str += '<td>'+(item.paid_status == '0' ? '<span style="background-color:red;padding:5px;color:#FFF;">Unpaid</span>' : '<span style="background-color:green;padding:5px;color:#FFF;">Paid</span>')+'</td>';
                    str += '</tr>';
                });
                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.publi_paging_item').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages_item').val(total_pages);
                jQuery('.tot_pag_span_item').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#tot_rec_span_item').html('<span id="tot_rec_item">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                var previous_link_item = "";
                var next_link_item = "";
                if(offset > 0 ){
                    previous_link_item = "<a href='javascript:;' onclick='loadContentItem("+(eval(offset)-eval(rows))+","+rows+",true);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                }

                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link_item = "<a href='javascript:;' onclick='loadContentItem("+(eval(offset)+eval(rows))+","+rows+",true);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                        // do nothing
                    }
                }
                jQuery('.previous_link_item').html(previous_link_item);
                jQuery('.next_link_item').html(next_link_item);



                $("#content_tbody_inv_itm").html(str);

            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="11">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody_inv_itm").html(str);
                jQuery('.tot_pag_span_item').html('1');
                jQuery('.next_link_item').html('');
            }
            var grant_tot = data.numFound.grant_tot;
            if(data.numFound.cn_amt !== null) {
                grant_tot = (parseFloat(grant_tot) - parseFloat(data.numFound.cn_amt)).toFixed(2);
            }

            $('.showing_stat').html($.number(eval(grant_tot),2));
            //$(this).attr('title',$('#property_id').find("option:selected").attr('data-value'));

            // This is to update the url
            if(flag) {
                if (typeof (history.pushState) != "undefined") {
                    var update_url = '<?php echo base_url('index.php/bms_fin_bills/manual_bill_list');?>/'+offset+'/'+rows+'?property_id='+$('#property_id').val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val()+'&coa_id='+$("#coa_id").val()+'&bill_no='+$("#bill_no").val();
                    var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Transpacc | BMS' ;?>', Url: update_url };
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    console.log("Browser does not support HTML5.");
                }
            }

        },
        error: function (e) {
            $("#content_tbody_inv_itm").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });

}










function remove_item (id){
    //console.log(id);
    if(confirm('You cannot undo this action. Are you sure want to delete?')) {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_bills/unset_bill');?>',
            data: {'bill_id':id},
            datatype:"html", // others: xml, json; default is html
            beforeSend:function (){ $("#content_tbody_inv").LoadingOverlay("show");  }, //
            success: function(data) {
                loadContent(0,jQuery('#records_per_page').val(),true);
                $("#content_tbody_inv").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_tbody_inv").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
    
    
}

function loadUnit (unit_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_jmb_mc/get_unit');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">All</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.unit_id+'" '+selected+'>'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                        
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
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

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');
}

function formatDate(date) {  
  var date_arr = date.split('-');
  return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}


</script>