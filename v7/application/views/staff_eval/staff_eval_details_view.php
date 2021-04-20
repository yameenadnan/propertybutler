<div class="row" style="margin: 0;">
    <?php if(!empty($jmb_eval)) { ?>
    <div class="box-header" style="padding-bottom: 0px;">
                  <h3 class="box-title" style="font-weight: bold;">JMB / MC Evaluation</h3>
                </div>
                  <div class="box-body" style="border-bottom: 1px solid #666;">
    <table id="example2" class="table table-bordered table-hover table-striped">
        <!--thead>
        <tr>
          <th class="hidden-xs">S No</th>                          
          <th>Proactive</th>   
          <th>Proactive Remarks</th>               
          <th>Communication</th>
          <th>Communication Remarks</th>
          <th>Attitude</th>
          <th>Attitude Remarks</th>
          <th>Initiative</th>
          <th>Initiative Remarks</th>   
          <th>Resposibility</th>               
          <th>Resposibility Remarks</th>
          <th>Courtesy</th>
          <th>Courtesy Remarks</th>
          <th>Addi Remarks</th>
          <th>Eval Date</th>
        </tr>
        </thead>
        <tbody id="content_tbody">
           <?php foreach ($jmb_eval as $key=>$val) { 
            
            echo "<tr>";
            echo "<td>".($key+1)."</td>";
            echo "<td>".$val['proactive']."</td>";
            echo "<td>".$val['proactive_remarks']."</td>";
            echo "<td>".$val['communication']."</td>";
            echo "<td>".$val['communication_remarks']."</td>";
            echo "<td>".$val['attitude']."</td>";
            echo "<td>".$val['attitude_remarks']."</td>";
            echo "<td>".$val['initiative']."</td>";
            echo "<td>".$val['initiative_remarks']."</td>";
            echo "<td>".$val['resposibility']."</td>";
            echo "<td>".$val['resposibility_remarks']."</td>";
            echo "<td>".$val['courtesy']."</td>";
            echo "<td>".$val['courtesy_remarks']."</td>";
            echo "<td>".$val['addi_remarks']."</td>";
            echo "<td>".date('d-m-Y',strtotime($val['eval_date']))."</td>";
            
            
            echo "</tr>";
            
            
            } ?>
        </tbody-->
        <?php 
        
        $columns  = array_keys($jmb_eval[0]);
        
        echo '<tr><th class="col-md-3">S No</th>';
        foreach ($jmb_eval as $key=>$val) {
            echo '<th>JMB / MC '.($key+1).'</th>';
        }
        echo '</tr>';
        
        foreach ($columns as $col_name) {
            echo '<tr><th>'.ucwords(implode(' ',explode('_',$col_name))).'</th>';
            foreach ($jmb_eval as $key=>$val) {
                if($col_name == 'eval_date') {
                    echo '<td>'.date('d-m-Y',strtotime($val[$col_name])).'</td>';
                } else {
                    echo '<td>'.$val[$col_name].'</td>';
                }
                
            }
        }
        
        echo '</tr>';
        
        
        ?>
                        
    </table>
    </div>
    <?php } ?>
    
    
        <?php if(!empty($am_eval)) { ?>
    <div class="box-header" style="padding-bottom: 0px;">
                  <h3 class="box-title" style="font-weight: bold;">HR Evaluation</h3>
                </div>
                  <div class="box-body">
    <table id="example3" class="table table-bordered table-hover table-striped">
        
        <?php 
        
        $columns  = array_keys($am_eval[0]);
        
        /*echo '<tr><th class="col-md-2">S No</th>';
        foreach ($am_eval as $key=>$val) {
            echo '<th>'.($key+1).'</th>';
        }
        echo '</tr>';*/
        
        foreach ($columns as $col_name) {
            echo '<tr><th class="col-md-3">'.ucwords(implode(' ',explode('_',$col_name))).'</th>';
            foreach ($am_eval as $key=>$val) {
                if($col_name == 'eval_date') {
                    echo '<td>'.date('d-m-Y',strtotime($val[$col_name])).'</td>';
                } else {
                    echo '<td>'.$val[$col_name].'</td>';
                }
                
            }
        }
        
        echo '</tr>';
        
        
        ?>
                        
    </table>
    </div>
    <?php } ?>
    
    
    
    <?php if(!empty($hr_eval)) { ?>
    <div class="box-header" style="padding-bottom: 0px;">
                  <h3 class="box-title" style="font-weight: bold;">AM Evaluation</h3>
                </div>
                  <div class="box-body">
    <table id="example4" class="table table-bordered table-hover table-striped">
        
        <?php 
        
        $columns  = array_keys($hr_eval[0]);
        
        /*echo '<tr><th class="col-md-2">S No</th>';
        foreach ($hr_eval as $key=>$val) {
            echo '<th>'.($key+1).'</th>';
        }
        echo '</tr>';*/
        
        foreach ($columns as $col_name) {
            echo '<tr><th class="col-md-3">'.ucwords(implode(' ',explode('_',$col_name))).'</th>';
            foreach ($hr_eval as $key=>$val) {
                if($col_name == 'eval_date') {
                    echo '<td>'.date('d-m-Y',strtotime($val[$col_name])).'</td>';
                } else {
                    echo '<td>'.$val[$col_name].'</td>';
                }
                
            }
        }
        
        echo '</tr>';
        
        
        ?>
                        
    </table>
    </div>
    <?php } ?>
    
         
</div>