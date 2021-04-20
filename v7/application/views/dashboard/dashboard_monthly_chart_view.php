<div class="row">
<div class="col-lg-12 col-xs-12">
  <div id="h_chart_2" >
        <div class="related-graph">
            <img width="20" class="img-responsive center-block"  src="<?php echo base_url();?>assets/images/loading.gif" />
        </div>
    </div>
</div> 
</div>
<script>
$(document).ready(function (){    
    $(function () {
                    Highcharts.chart('h_chart_2', {
                        
                        chart: {
                            type: 'column',                
                            height:200,
                            spacingLeft:0,
                            spacingRight:10,
                            spacingBottom: 0,                            
                            spacingTop: 15
                        },
                        exporting: { enabled: false },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',                            
                        },
                        xAxis: {
                            categories: ['<?php echo implode("','",$x_axis);?>'],
                            title: {
                                text:'<b>Months</b>'
                            }
                        },
                        yAxis: {
                            
                            allowDecimals: false,
                            title: {
                                text: '<b>Total Minor Tasks<b>'
                            },
                            align: 'high'
                        },
                        series: [{
                            name: 'Open Minor Task',
                            data: [<?php echo implode(",",$open_cnt);?>],
                            color: '#3F48CC'
                        },{
                            name: 'Closed Minor Task',
                            data: [<?php echo implode(",",$close_cnt);?>],
                            color: '#00A2E8'
                        }],
                        legend: {
                            margin: 0,
                            itemStyle:{"fontWeight": "normal" }
                        },
                        tooltip: {
                            formatter: function () {
                                return '<b>' + this.y + ' ' + this.series.name + '</b> ' + ' on <b>' + this.x + '</b> ';
                            }
                        }
                    });
                });
    
                // hide graph legends
                $('.highcharts-legend').css('display','none');
});
</script>