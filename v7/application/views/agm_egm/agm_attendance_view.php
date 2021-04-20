<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area"  style="background-color: #fff;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      
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
              <div class="box-body">
                  <div class="row hidden-xs" id="capture_div">
                  
                    <form>
                    <div class="col-md-12 col-sm-12 no-padding">                                        
                        
                        <div class="col-md-4 col-sm-5 no-padding hidden-xs">                            
                            
                            <div id="my_camera"></div>
                            
                            <div class="col-md-12" style="padding: 15px 0 5px 0;">
                                <label class="col-md-3 control-label no-padding " >Current Time : </label>
                                <div class="col-md-9 ">
                                    <div class="input-group current_time">                                    
                                        <?php echo date('h:i:s a');?>
                                    </div>                             
                                </div>
                            </div>
                            <div class="col-md-12" style="padding: 5px 0;" >
                                <label class="col-md-3 control-label no-padding" >Property : </label>
                                <div class="col-md-9">
                                                                  
                                    <select class="form-control" id="property_id" name="property_id">
                                        <option value="">Select Property</option>
                                        <?php 
                                            foreach ($properties as $key=>$val) { 
                                                $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                            } ?> 
                                    </select>                                                            
                                </div>
                            </div>
                            <div class="col-md-12" style="padding: 5px 0;" >
                                <label class="col-md-3 control-label no-padding " >AGM / EGM : </label>
                                <div class="col-md-9">
                                                                  
                                    <select class="form-control" id="agm_id" name="agm_id">
                                        <option value="">Select</option>  
                                        <?php 
                                            if(!empty($agms)) {
                                                foreach ($agms as $key=>$val) { 
                                                    $selected = !empty($agm_id) && trim($agm_id) == $val['agm_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['agm_id']."' ".$selected." data-agm-created-date='".$val['created_date']."'>".$val['agm_term']."</option>";
                                                }
                                            } 
                                        ?>                                      
                                    </select>
                                    <input type="hidden" id="agm_created_date" value="" />                            
                                </div>
                            </div>
                            <!--div class="col-md-12" style="padding: 5px 0;" >
                                <label class="col-md-3 control-label " > Unit : </label>
                                <div class="col-md-6">
                                                                  
                                    <select class="form-control" id="eli_voter_id" >
                                        <option value="">Select</option>                                    
                                    </select>
                                                                                                    
                                </div>
                            </div-->
                            <div class="col-md-12" style="padding: 5px 0;">
                                <label class="col-md-3 control-label no-padding" > Mobile No : </label>
                                <div class="col-md-9 ">                                                                   
                                    <input type="text" id="mobile_no" class="form-control"  maxlength="12" placeholder="Mobile No" >                                                          
                                </div>
                            </div>
                             
                            
                            
                        </div>
                        
                        <div class="col-md-8 col-sm-7 hidden-xs">
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-4 col-sm-4 col-xs-6 no-padding">
                                    <div class="form-group">
                                      <label for="">Unit(s)</label>                                      
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                    <div class="input-group">
                                      <input type="text" class="form-control" placeholder="Search" name="search" id="search_txt">
                                      <div class="input-group-btn">
                                        <button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                                      </div>
                                    </div>
                                </div>
                                    
                            </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 unit_div"  style="padding:0px;margin-bottom:5px;max-height: 400px; overflow-y: scroll;">
                                    <?php 
                                            if(!empty($units)) {
                                                /*echo '<div class="col-xs-12"><div class="form-group">
                                      <div class="checkbox">
                                        <label><input type="checkbox" class="chk_all"><b>Select All</b></label>
                                      </div>
            
                                    </div></div>';*/
                                                foreach ($units as $key=>$val) { 
                                                      
                                                    echo '<div class="col-xs-12"><div class="form-group"><div class="checkbox"><label>';
                                                    echo '<input name="notice_unit[]" value="'.$val['eli_voter_id'].'" type="checkbox" class="eli_chk">';
                                                    echo $val['unit_no'].' - ' . $val['owner_name'] .' ('.$val['ic_no'].')';
                                                    if($val['proxy_required'] == 1) {
                                                        echo ' - (Proxy) '.$val['proxy_name'].' ('.$val['proxy_ic_no'].')';
                                                    }
                                                    echo '</label></div></div></div>';
                                                    
                                                }
                                            } else {
                                                echo 'No record found. Please select/change Property Name and AGM/EGM to load Units.';                                                
                                            } 
                                        ?>    
                                    
                                </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 no-padding">          
                        <div class="col-md-12 text-center" style="padding: 5px 0;">                            
                        		<input class="btn btn-primary capture_btn" value="Capture" type="button"> &ensp;
                                <input class="btn btn-default reset_btn" value="Reset" type="button" >
                        	</div>
                    </div>
                    
                                                            
                    </form>                    
                    
                </div>
                <div class="row visible-xs">
                    
                    <div class="alert alert-danger">                        
                        You are not allowed to capture attendance using mobile device!
                    </div>
            
                </div>
                
              </div>
              <!-- /.box-body -->
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>assets/js/webcam.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>

<script>
    Webcam.set({
		width: 350,
		height: 250,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach( '#my_camera' );
    
    function take_snapshot() {
		// take snapshot and get image data
		Webcam.snap( function(data_uri) {
            // display results in page		
            /*if($('#capture_for').val() == '') {
                $('#capture_for_error').css('display','block');
                return false; 
            } */
            if($('#property_id').val() == '') {
                alert('Please select Property!'); $('#property_id').focus(); return false;
            }
            if($('#agm_id').val() == '') {
                alert('Please select AGM / EGM!'); $('#agm_id').focus(); return false;
            }
            
            var checkedVals = $('.eli_chk:checkbox:checked').map(function() {
                return this.value;
            }).get();
            //console.log( checkedVals.length + '--' +checkedVals.join(","));
            if(!checkedVals.length) {
                alert('Please select Unit(s)!'); $('.eli_chk').focus(); return false;
            }
            
            if($('#mobile_no').val() == '') {
                alert('Please enter Mobile No!'); $('#mobile_no').focus(); return false;
            }
            
            $('#agm_created_date').val($('#agm_id').find('option:selected').data('agm-created-date'));
            
            $("#content_area").LoadingOverlay("show");
            
            Webcam.upload( data_uri, '<?php echo base_url('index.php/bms_agm_egm/atten_capture');?>?agm_id='+$('#agm_id').val()+'&eli_voter_id='+checkedVals.join(",")+'&agm_created_date='+$('#agm_created_date').val()+'&mobile_no='+$('#mobile_no').val(), function(code, text) { 
                //$("#content_area").LoadingOverlay("hide", true);	
            	console.log(text);
                var text_arr = text.split('~~~');
                if(text_arr[0].length == 5 && text_arr[0] == 'error' ) {
                    alert(text_arr[1]);                    
                } else {
                    var url = '<?php echo base_url('index.php/bms_agm_egm/agm_username_print');?>?user_name='+text;
                    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=550,directories=no,location=no');
                    setInterval(function(){ page_reload() }, 1000);
                    
                    //window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_attendance_report');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
                    /*document.getElementById('results').innerHTML = 
                	'<div class="alert alert-success">'  +                       
                       'Captured date: <b>' + text_arr[1] + '</b> Time: <b>'+ text_arr[2] + ' </b>' +
                    '</div>' + 
                	'<img src="'+text_arr[0]+'"/>';
                    $('.capture_btn').unbind('click').removeClass('capture_btn');*/
                    
                }                
            });	
		});
	}
    
function page_reload() {
    window.location.reload();
}
var CurrTimeInterval;
$(document).ready(function () {
    CurrTimeInterval = setInterval(function(){ increaseTimeByOne('<?php echo date('h:i:s a');?>') }, 2000);
    $('#capture_for').change(function () {
        if($('#capture_for').val() == '') {
           $('#capture_for_error').css('display','block'); 
        } else {
            $('#capture_for_error').css('display','none'); 
        }
    });
    $('.capture_btn').click(function () {
        take_snapshot();
    });
    
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            property_change_eve ();
        }           
    });
    
    $('#agm_id').change(function () {
        if($('#agm_id').val() != '') {
            getAgmEligibleVoters ();
        }           
    });
    
    // On document ready
    if($('#property_id').val() != '' && $('#agm_id').val() == '') {        
        property_change_eve ();
    }
    
    $('.reset_btn').click(function () {
        window.location.reload();
    });
    
    $("#search_txt").keyup(function() {
        //$(".eli_chk").prop('checked', false);
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(),
        count = 0;
        
        // Loop through the comment list
        $('.unit_div div').each(function() {
            //console.log($(this).find('label').text());
            if ($(this).find('label').text().search(new RegExp(filter, "i")) < 0 && $(this).find('label').text() != 'Select All') {
                $(this).hide();
            } else {
                $(this).show(); 
                count++;
            }            
        });
        
        /*if ($('.eli_chk:checked').length == $('.eli_chk').length ){
            $(".chk_all").prop('checked', true);
        }*/

    });
    
    /*$('.eli_chk').change(function(){ 
        if(false == $(this).prop("checked")){ 
            $(".chk_all").prop('checked', false); 
        }                    
        if ($('.eli_chk:checked').length == $('.prop_chk').length ){
            $(".chk_all").prop('checked', true);
        }
    });
    
    $(".chk_all").change(function () {
        $(".eli_chk").prop('checked', $(this).prop("checked"));
    });*/
    
});


function property_change_eve () {
        
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_agm_egm/getTodaysAgm');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.agm_id+'">'+item.agm_term+'</option>';
                    });
                }
                $('#agm_id').html(str);
                $("#content_area").LoadingOverlay("hide", true);                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

function getAgmEligibleVoters () {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_agm_egm/getAgmEligibleVoters');?>',
            data: {'agm_id':$('#agm_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                /*var str = '<option value="">Select</option>';
                var owner_name = ''; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        owner_name = item.proxy_required == 1 ? item.proxy_name +' - '+ item.proxy_ic_no +' (Proxy)' : item.owner_name;
                        str += '<option value="'+item.eli_voter_id+'">'+item.unit_no+'-'+owner_name+'</option>';
                    });
                }
                $('#eli_voter_id').html(str);*/
                var str = ''; 
                if(data.length > 0) {  
                    /*str += '<div class="col-xs-12"><div class="form-group"><div class="checkbox">';
                    str += '<label><input type="checkbox" class="prop_all_chk"><b>Select All</b></label>';
                    str += '</div></div></div>';*/
                    $.each(data,function (i, item) {
                        str += '<div class="col-xs-12"><div class="form-group">';
                        str += '<div class="checkbox">';
                        str += '<label><input name="notice_unit[]" value="'+item.eli_voter_id+'"  type="checkbox" class="eli_chk"> ';
                        str += item.unit_no+' - ' + item.owner_name +' ('+item.ic_no+')';
                        if(item.proxy_required == 1) {
                            str += ' - (Proxy) '+item.proxy_name+' ('+item.proxy_ic_no+')';
                        }
                        str += '</label>';
                        str += '</div>';
                        str += '</div></div>';
                        //str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
                    });
                }
                //console.log(str);
                $('.unit_div').html(str); 
                
                /*$('.eli_chk').unbind('change');
                $(".chk_all").unbind('change');
                $('.eli_chk').change(function(){ 
                    if(false == $(this).prop("checked")){ 
                        $(".chk_all").prop('checked', false); 
                    }                    
                    if ($('.eli_chk:checked').length == $('.prop_chk').length ){
                        $(".chk_all").prop('checked', true);
                    }
                });
                $(".chk_all").change(function () {
                    $(".eli_chk").prop('checked', $(this).prop("checked"));
                }); */ 
                $("#content_area").LoadingOverlay("hide", true);                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}




/*function  show_current_tiem() {    
    $.post( "<?php echo base_url('index.php/bms_attendance/get_live_time');?>", function( data ) {
      $( ".current_time" ).html( data );
    });
}*/

function increaseTimeByOne(timeStr) {
    
    clearInterval(CurrTimeInterval);
    var timeSplited = timeStr.split(" ");
    var splitedTimeStr = timeSplited[0].split(':');
    var meridiem = timeSplited[1];    
    //console.log(splitedTimeStr);
    var hours = parseInt(splitedTimeStr[0]);
    var mins = parseInt(splitedTimeStr[1]);
    var secs = parseInt(splitedTimeStr[2]);
    
    var nextSecond = (secs + 1);    
    var nextMins = '';
    var nextHour = '';    
    var nextTime = '';
    if (nextSecond >= 60) {        
        nextSecond = 0;
        nextMins = mins + 1;        
        if(nextMins >= 60) {
            nextSecond = 0;
            nextMins = 0;
            nextHour = hours+1;
            if(nextHour >= 12) {
                if(nextHour == 12) {
                    if (meridiem.toLowerCase() == "am") {
                        meridiem = "pm";
                    } else if (meridiem.toLowerCase() == "pm") {
                        meridiem = "am";
                    }
                }
                if(nextHour == 13) {
                    nextHour = 1;
                }
                nextTime = zeroPrefix(nextHour) +':'+zeroPrefix(nextMins)+':'+zeroPrefix(nextSecond)+' '+ meridiem;
            } else {
                nextTime = zeroPrefix(nextHour) +':'+zeroPrefix(nextMins)+':'+zeroPrefix(nextSecond)+' '+ meridiem;
            }
        } else {
            nextTime = zeroPrefix(hours) +':'+zeroPrefix(nextMins)+':'+zeroPrefix(nextSecond)+' '+ meridiem;
            $( ".current_time" ).html( nextTime);
        }        

    } else {
        nextTime = zeroPrefix(hours) +':'+zeroPrefix(mins)+':'+zeroPrefix(nextSecond)+' '+ meridiem;
        $( ".current_time" ).html( nextTime);
    }
    CurrTimeInterval = setInterval(function(){ increaseTimeByOne(nextTime) }, 1000);
}

function zeroPrefix( param ) {
    return param >= 0 && param <=9 ? '0'+param : param; 
}

</script>