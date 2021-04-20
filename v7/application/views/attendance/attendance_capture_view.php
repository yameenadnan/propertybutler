<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
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
                  
                    
                    <div class="col-md-6 hidden-xs">
                    <?php if(!empty($last_capture)) { 
                        $attendance_capture_for = $this->config->item('attendance_capture_for');
                        
                        ?>
                    <div style="padding: 15px 5px;">
                        Your last capture on <b><?php echo date('d-m-Y',strtotime($last_capture[0]['attn_date'])). ' '. date('h:i:s a',strtotime($last_capture[0]['atten_time']));?>
                        </b> for <b> <?php echo $attendance_capture_for[$last_capture[0]['in_out_type']] . (isset($last_capture[0]['remarks']) && $last_capture[0]['remarks'] != '' ? ' ( '.$last_capture[0]['remarks'] . ')' : '');?>  </b>
                    </div>
                    
                    <?php } ?>
                        <form>
                        <div id="my_camera"></div>
                        <div class="col-md-12" style="padding: 5px 0;">
                            <label class="col-md-3 control-label " > Current Time : </label>
                            <div class="col-md-9 ">
                                <div class="input-group current_time">                                    
                                    <?php echo date('h:i:s a');?>
                                </div>                             
                            </div>
                        </div>
                        <div class="col-md-12" style="padding: 5px 0;">
                            <label class="col-md-3 control-label" > Property Name: </label>
                            <div class="col-md-6 ">
                                <select id="my_property_id" class="form-control" name="my_property_id">
                                    <option value="">Select property</option>
                                    <?php foreach ( $my_properties as $key => $val ) { ?>
                                        <option <?php echo ( count($my_properties) == 1 )? 'selected="selected"':'';?> value="<?php echo $val['property_id']; ?>"><?php echo $val['property_name']; ?></option>
                                    <?php } ?>
                                <?php print_r($my_properties) ;?>
                                </select>
                                <em id="my_property_id_error" class="error help-block" style="display: none;">Please select property</em>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding: 5px 0;" >
                            <label class="col-md-3 control-label " > Capture For : </label>
                            <div class="col-md-6">
                                                              
                                    <select class="form-control" id="capture_for" >
                                    <option value="">Select</option>
                                    <?php 
                                        $capture_for = $this->config->item('attendance_capture_for');
                                        foreach ($capture_for as $key=>$val) { 
                                            echo "<option value='".$key."'>".$val."</option>";
                                        } ?> 
                                     </select>
                                     <em id="capture_for_error" class="error help-block" style="display: none;">Please select Capture For</em>                        
                            </div>
                        </div>
                        <div class="col-md-12" style="padding: 5px 0;">
                            <label class="col-md-3 control-label " > Remarks : </label>
                            <div class="col-md-6 ">
                                                                   
                                    <input type="text" id="remarks" class="form-control" placeholder="Enter Remarks" maxlength="250">
                                                          
                            </div>
                        </div>  
                        <div class="col-md-12 text-center" style="padding: 5px 0;">                            
                    		<input class="btn btn-primary capture_btn" value="Capture" type="button"> &ensp;
                            <input class="btn btn-default" value="Reset" type="reset" >
                    	</div>
                        </form>
                    </div>
                    
                    <div class="col-md-6 hidden-xs">
                        <div id="results">Your captured image will appear here...</div>
                    </div>
                                        
                    
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

            if($('#my_property_id').val() == '') {
                $('#my_property_id_error').css('display','block');
                return false;
            }

            if($('#capture_for').val() == '') {
                $('#capture_for_error').css('display','block');
                return false; 
            }


            Webcam.upload( data_uri, '<?php echo base_url('index.php/bms_attendance/capture_save');?>?capture_for='+$('#capture_for').val()+'&remarks='+encodeURIComponent($('#remarks').val())+'&property_id='+$('#my_property_id').val(), function(code, text) {
            	var text_arr = text.split('~~~');
                if(text_arr[0].length == 5 && text_arr[0] == 'error' ) {
                    alert(text_arr[1]);                    
                } else {
                    document.getElementById('results').innerHTML = 
                	'<div class="alert alert-success">'  +                       
                       'Captured date: <b>' + text_arr[1] + '</b> Time: <b>'+ text_arr[2] + ' </b>For <b>' + text_arr[3] + '('+text_arr[4]+')</b>' +
                    '</div>' + 
                	'<img src="'+text_arr[0]+'"/>';
                    $('.capture_btn').unbind('click').removeClass('capture_btn');
                    
                }
                
            } );	
		} );
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

    $('#my_property_id').change(function () {
        if($('#my_property_id').val() == '') {
            $('#my_property_id_error').css('display','block');
        } else {
            $('#my_property_id_error').css('display','none');
        }
    });

    $('.capture_btn').click(function () {
        take_snapshot();
    });
});

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
                nextTime = zeroPrefix(hours) +':'+zeroPrefix(nextMins)+':'+zeroPrefix(nextSecond)+' '+ meridiem;
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