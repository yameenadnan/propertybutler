                    
    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="background-color: #FFF;">
        <table id="example2" class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
              <th class="hidden-xs">S No</th>
              <th>Company Name</th>
              <th>Maintenance Contract Start Date</th>                  
              <th>Maintenance Contract Due Date</th>
              <th>Remind Before</th>
              <th>Contact Person Name</th>
              <th>Contact Person Number</th>
              <th>Contact Person Email</th>
              
            </tr>
            </thead>
            <tbody id="content_tbody">
              <?php if (!empty($mainten_comp))    {
                $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                foreach ($mainten_comp as $key=>$val) {
                    echo '<tr><td>'.($key+1).'</td>';
                    echo '<td>'.$val['supplier_name'].'</td>';
                    echo '<td>'.($val['warranty_start'] != '' ? date('d-m-Y',strtotime($val['warranty_start'])) : ' - ').'</td>';
                    echo '<td>'.($val['warranty_due'] != '' ? date('d-m-Y',strtotime($val['warranty_due'])) : ' - ').'</td>';
                    echo '<td>'.(!empty($val['remind_before'])  ? $asset_warranty_remin[$val['remind_before']]  : ' - ').'</td>';
                    echo '<td>'.$val['person_incharge'].'</td>';
                    echo '<td>'.$val['person_inc_mobile'].'</td>';
                    echo '<td>'.$val['person_inc_email'].'</td></tr>';
                    
                }
              } else {
                echo '<tr><td class="text-center" colspan="8">No record found!</td></tr>';
              }?>        
            </tbody>                
          </table>
    </div>
       