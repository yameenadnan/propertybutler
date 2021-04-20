<?php include_once('agm_voter_header.php');?>
                <div class="row">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">  
                            <div class="form-group"  style="font-size: 18px;">

                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-3">Sr #</div>
                                        <div class="col-lg-11 col-md-11 col-sm-10 col-xs-9">Agenda</div>
                                    </div>
                                    <?php if ( !empty($agenda_resol) ) {
                                        $counter = 0;
                                        foreach ( $agenda_resol as $key=>$value ) {
                                            $counter++;
                                            ?>
                                            <div class="row" style="border: 1px solid #f3f3f3; ">
                                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-3 "><?php echo $counter;?></div>
                                                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-9 no-padding" style=" overflow-wrap: break-word;"><a href="<?php echo base_url('index.php/bms_agm_egm_vote/display_history_result');?>?agm_agenda_id=<?php echo $value['agm_agenda_id']; ?>&agm_attendance_id=<?php echo $value['agm_attendance_id'];?>&resolu_type=<?php echo $value['resolu_type'];?>"><?php echo $value['agenda_resol'];?></a></div>
                                            </div>
                                        <?php }
                                    }?>
                                </div>
                            </div>
                       </div>
                       

                    </div>                   
                    
                </div>
                
<?php include_once('agm_voter_footer.php');?>
<script>
$(document).ready(function () {

    $('.check-agenda-detal').click (function () {

        var agm_attendance_id = $(this).data('attendence-id');
        var agm_agenda_id = $(this).data('agend-id');
        var resolu_type = $(this).data('resolu-type');
        var data = {
            'agm_attendance_id' : agm_attendance_id,
            'agm_agenda_id' : agm_agenda_id,
            'resolu_type' : resolu_type
        };

        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_agm_egm_vote/get_vote_history_results');?>',
            data: data,
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){
                // $("#content_tbody").LoadingOverlay("show");
            }, //
            success: function(data) {
                // $("#content_tbody").LoadingOverlay("hide", true);
                alert ( data );
            },
            error: function (e) {
                // $("#content_tbody").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });












    });

});
</script>
</body>
</html>