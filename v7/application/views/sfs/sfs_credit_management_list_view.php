<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
  <!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
    </section>
    <style>
        .hide-content {
            visibility: hidden;
            display: none;
        }

        .show-content {
            visibility: visible;
            display: block;
        }
    </style>
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
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-4 col-xs-6">
                        <select class="form-control" id="tsp_id" name="tsp_id">
                            <option value="">All</option>
                            <?php 
                            foreach ($tsp_list as $key=>$val) {
                                $selected = !empty($tsp_id) && trim($tsp_id) == $val['tsp_id'] ? 'selected="selected" ' : '';
                                echo "<option value='".$val['tsp_id']."' ".$selected.">".$val['tsp_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-xs-5">                        
                        <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter text to search Service Name">
                    </div>

                    <div class="col-md-4 col-xs-1 no-padding">
                        <a href="javascript:;" role="button" class="btn btn-primary category_filter"><i class="fa fa-search"></i></a>
                        <a href="javascript:;" role="button" class="btn btn-primary topup" style="margin-left: 5px;">Add Topup</i></a>
                    </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="content-view-units">
                  <div class="box-body">
                      <table id="example2" class="table table-bordered table-hover table-striped">
                          <thead>
                          <tr>
                              <th>S No</th>
                              <th>Company name</th>
                              <th>Topup amount</th>
                              <th>Topup date</th>
                              <th>Payment mode</th>
                              <th>Reference</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                          <tbody id="content_tbody">

                          </tbody>
                      </table>
                  </div>
                  <div class="row ciov"
                       style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                      <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="padding: 0px;">

                          Show: &nbsp;<select id="records_per_page">
                              <option value="25" <?php echo isset($per_page) && $per_page == 25 ? 'selected="selected"' : ''; ?>>
                                  25 per page
                              </option>
                              <option value="50" <?php echo isset($per_page) && $per_page == 50 ? 'selected="selected"' : ''; ?>>
                                  50 per page
                              </option>
                              <option value="100" <?php echo isset($per_page) && $per_page == 100 ? 'selected="selected"' : ''; ?>>
                                  100 per page
                              </option>
                          </select>

                          <span style="display: inline-block; padding-left: 15px; font-size: 12px;color:#000;font-weight:bold" class="showing_stat"> Showing 0 to 0 Of 0 Record(s) </span>
                      </div>
                      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right" style="padding: 0px;">

                          <div class="paging_right_div" style="padding-right: 5px;">
                              <span class="previous_link"></span>
                              <span>Page <input class="publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span small_loader"></span></span>
                              <span class="next_link"><a href="javascript:;"> <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages" value="" type="hidden">
                          </div>
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

  <!-- bootstrap datepicker -->
  <script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>

    <script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

var offset_link = '<?php echo $offset;?>';
var rows_link = '<?php echo $per_page;?>';

$(document).ready(function () {

    $('.msg_notification').fadeOut(5000);
    
    $('#tsp_id').change(function () {
        loadContent(0,jQuery('#records_per_page').val(),true);
    });

    $( "#bms_frm" ).validate({
        rules: {
            "topup[hidd_tsp_id]": "required",
            "topup[topup_amount]": "required",
            "topup[topup_date]": "required",
            "topup[topup_time]": "required",
        },
        messages: {
            "topup[hidd_tsp_id]": "Please select Company",
            "topup[topup_amount]": "Please enter Topup amount",
            "topup[topup_date]": "Please enter Topup date",
            "topup[topup_time]": "Please enter  Topup time",
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else if ( element.prop( "type" ) === "radio" ) {
                error.insertAfter( element.parent( "label" ).parent('div') );
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
    
    $('.category_filter').click(function () {
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
    
    loadContent('<?php echo $offset;?>','<?php echo $per_page;?>',false);
    
});

function loadContent (offset,rows,flag) {

    var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_sfs/get_credit_topup_list');?>',
        data: {'offset':offset,'rows':rows,'tsp_id':$('#tsp_id').val(),'search_txt':search_txt},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            var numFound = data.numFound;

            if(numFound > 0) {

                $.each(data.records,function (i, item) {
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td>'+ ( (item.tsp_name != null) ? item.tsp_name:'-') +'</td>';
                    str += '<td>'+ ( (item.topup_amount != null) ? item.topup_amount:'0') +'</td>';
                    var topup_date = item.topup_date.split(" ");
                    str += '<td>'+ formatDate( topup_date[0] ) + ' ' + formatTime( topup_date[1] ) +'</td>';
                    var payment_mode = '';
                    switch (item.pymt_mode) {
                        case '1':
                            payment_mode = 'Cash';
                            break;
                        case '2':
                            payment_mode = 'Cheque';
                            break;
                        case '3':
                            payment_mode = 'Online';
                            break;
                        case '4':
                            payment_mode = 'FPX';
                            break;
                        case '5':
                            payment_mode = 'V/M Card';
                            break;
                    }

                    str += '<td>'+ payment_mode +'</td>';
                    str += '<td>'+ item.reference +'</td>';
                    str += '<td><a data-topup_id="' + item.topup_id + '" data-tsp_id="' + item.tsp_id + '" href="#/" class="topup-edit">Edit</a> | <a class="delete" href="<?php echo base_url('index.php/bms_sfs/delete_credit_topup/?topup_id=');?>' + item.topup_id + '&tsp_id=' + item.tsp_id + '">Delete</a></td>';
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

                if (eval(numFound) > (eval(offset)+eval(rows))){
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
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }

            $('.showing_stat').html('Showing '+(eval(offset)+(numFound > 0 ? 1 : 0))+' - '+showing_to+' Of '+numFound+' Record(s). ' );

            // This is to update the url
            if ( flag ) {
                if (typeof (history.pushState) != "undefined") {
                    offset_link = offset;
                    rows_link = rows;
                    var update_url = '<?php echo base_url('index.php/bms_sfs/sfs_company_list');?>/'+offset+'/'+rows+'?tsp_id='+$('#tsp_id').val()+'&search_txt='+search_txt;
                    var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler' ;?>', Url: update_url };
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    console.log("Browser does not support HTML5.");
                }
            }

             $('.showing_stat').html()

        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function formatDate (input) {
    var datePart = input.match(/\d+/g),
        year = datePart[0].substring(0), // get only two digits
        month = datePart[1], day = datePart[2];

    return day+'-'+month+'-'+year;
}

function formatTime (timeString) {
    var hourEnd = timeString.indexOf(":");
    var H = +timeString.substr(0, hourEnd);
    var h = H % 12 || 12;
    var ampm = H < 12 ? " AM" : " PM";

    return timeString = h + timeString.substr(hourEnd, 3) + ampm;
}

$('body').on('click', 'a.topup', function() {
    var tsp_id = $('#tsp_id').val();
    var tsp_name = $('#tsp_id option:selected').text();

    $.ajax ({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_sfs/get_server_date_time');?>',
        data: {},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody").LoadingOverlay("hide", true);

            var len = data.tsp_list.length;
            $("#hidd_tsp_id").empty();
            $("#hidd_tsp_id").append("<option value=''>Select company</option>");
            for ( var i = 0; i<len; i++ ) {
                var id = data.tsp_list[i]['tsp_id'];
                var name = data.tsp_list[i]['tsp_name'];
                $("#hidd_tsp_id").append("<option value='"+id+"'>"+name+"</option>");
            }
            $('#hidd_tsp_id').val(tsp_id);
            $('#tsp_name').val(tsp_name);
            $('#topup_amount').val('');
            $('#reference').val('');
            $('#pymt_mode').val(1);
            $('#topup_date').val(data.date);
            $('#topup_time').val(data.time);
            $('#myModal2').modal({show:true});
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
});

$('body').on('click', 'a.topup-edit', function() {

    var tsp_id = $(this).data("tsp_id");
    var topup_id = $(this).data("topup_id");

    var tsp_name = $('#tsp_id option:selected').text();

    $.ajax ({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_sfs/get_credit_topup_detail');?>',
        data: { 'topup_id' : topup_id},
        datatype:"json",

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  },
        success: function(data) {
            $("#content_tbody").LoadingOverlay("hide", true);

            $(".modeltitledisp").html("<i class='fa fa-file'></i>&nbsp;&nbsp;Edit Topup");

            var len = data.tsp_list.length;
            $("#hidd_tsp_id").empty();
            for( var i = 0; i<len; i++) {
                var id = data.tsp_list[i]['tsp_id'];
                var name = data.tsp_list[i]['tsp_name'];
                $("#hidd_tsp_id").append("<option value='"+id+"'>"+name+"</option>");
            }
            $('#hidd_tsp_id').val(data.credit_topup.tsp_id);
            $('#hidd_tsp_id_old').val(data.credit_topup.tsp_id);
            $('#topup_id').val(data.credit_topup.topup_id);
            $('#tsp_name').val(data.credit_topup.tsp_name);
            $('#topup_amount').val(data.credit_topup.topup_amount);
            $('#topup_amount_old').val(data.credit_topup.topup_amount);
            $('#pymt_mode').val(data.credit_topup.pymt_mode);
            var topup_date = data.credit_topup.topup_date.split(" ");
            $('#topup_date').val(formatDate( topup_date[0] ));
            $('#topup_time').val(formatTime( topup_date[1] ));
            $('#reference').val(data.credit_topup.reference);
            $('#myModal2').modal({show:true});
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
});

$('body').on('keypress', '#topup_amount', function(eve) {

    if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
        eve.preventDefault();
    }

});

$('body').on('click', '.delete', function(eve) {
    event.preventDefault();
    var r=confirm("Are you sure you want to delete? This cannot be undo");
    if ( r == true )   {
        window.location = $(this).attr('href');
    }
});


$('body').on('keyup', '#topup_amount', function(eve) {
    var current_balance = !$('#current_balance').val() ? 0:$('#current_balance').val();
    var topup_amount = !$('#topup_amount').val() ? 0:$('#topup_amount').val();
    $('#available_balance').val( parseFloat(current_balance) + parseFloat(topup_amount) );
});

</script>

<!--  MODEL POPUP  -->
<style>
    .top-buffer {
        margin-top:20px;
    }
</style>
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i>&nbsp;&nbsp;Add Topup</h4>
            </div>
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_sfs/add_topup_submit');?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="topup_id" name="topup[topup_id]">
                <div class="modal-body modal-body2">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Company name *</label></div>
                            <div class="col-md-6">
                                <select class="form-control" id="hidd_tsp_id" name="topup[hidd_tsp_id]">

                                </select>
                                <input type="hidden" class="form-control" id="hidd_tsp_id_old" name="hidd_tsp_id_old" />
                                <!--<input type="text" class="form-control" id="tsp_name" readonly="readonly" />-->
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Topup amount *</label></div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="topup_amount" name="topup[topup_amount]" />
                                <input type="hidden" class="form-control" id="topup_amount_old" name="topup_amount_old" />
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Topup date *</label></div>
                            <div class="col-md-6">
                                <input type="text" class="form-control datepick" id="topup_date" name="topup[topup_date]" value="<?php echo date('d-m-Y');?>"  />
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Topup time *</label></div>
                            <div class="col-md-6">
                                <div class="bootstrap-timepicker">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" id="topup_time" name="topup[topup_time]">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--<input type="text" class="form-control" id="topup_time" name="topup_time" value="<?php /*echo date('h:i:s');*/?>"  />-->
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Payment method *</label></div>
                            <div class="col-md-6">
                                <select  class="form-control" id="pymt_mode" name="topup[pymt_mode]">
                                    <option value="1">Cash</option>
                                    <option value="2">Cheque</option>
                                    <option value="3">Online</option>
                                    <option value="4">Fpx</option>
                                    <option value="5">V/M card</option>
                                </select>
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label>Reference No. </label></div>
                            <div class="col-md-6">
                                <textarea class="form-control" id="reference" name="topup[reference]"></textarea>
                            </div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"><label style="color: red;">(*) indicates mandatory fields. </label></div>
                        </div>
                        <div class="row top-buffer">
                            <div class="col-md-6 text-right" style="margin-top: 5px;"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </form>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>

    $('body').on('focus',".datepick", function(){
        $(this).datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            "setDate": new Date()
        });
    });

   //Timepicker
   $('.timepicker').timepicker({
       minuteStep: 1,
       showInputs: false
   });

</script>