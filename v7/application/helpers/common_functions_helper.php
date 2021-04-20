<?php defined('BASEPATH') OR exit('No direct script access allowed.');

function get_period_dd ($format,$selected = '') {
    $str = '<option value="">Select</option>'; 
    //$str .= '<option value="Opening Balance" '.($selected == 'Opening Balance' ? 'selected="selected"': '').'>Opening Balance</option>';
    switch ($format) {
        case 'yyyy/yy':
            if(date('Y') == date ('Y', strtotime ( '-6 month' , strtotime ( date('Y-M-d') )))) {
                $start_year = date('Y');
            } else {
                $start_year = date ('Y', strtotime ( '-6 month' , strtotime ( date('Y-M-d') ))).'/'.date('y');
            }
            for($i=0; $i < 2; $i++) {
                $start_year_arr = explode('/',$start_year);
                if(count($start_year_arr) == 1) {
                    $str .= '<option value="'.$start_year.'" '.($selected == $start_year ? 'selected="selected"': '').'>'.$start_year.'</option>';
                    $str .= '<option value="'.$start_year.'/'.substr( ($start_year+1), -2 ).'" '.($selected == $start_year.'/'.substr( ($start_year+1), -2 ) ? 'selected="selected"': '').'>'.$start_year.'/'.substr( ($start_year+1), -2 ).'</option>';
                    $start_year += 1;
                } else {
                    $str .= '<option value="'.$start_year.'" '.($selected == $start_year ? 'selected="selected"': '').'>'.$start_year.'</option>';
                    $str .= '<option value="'.($start_year_arr[0]+1).'" '.($selected == ($start_year_arr[0]+1) ? 'selected="selected"': '').'>'.($start_year_arr[0]+1).'</option>';
                    $start_year = ($start_year_arr[0] + 1) . '/' . ($start_year_arr[1] + 1);
                }                    
            }                                
            break;
            
        case 'yyyy':
            for($i=-1; $i < 3; $i++) {
                $val = date ('Y', strtotime ( $i.' year' , strtotime ( date('Y-M-01') )));
                $str .= '<option value="'.$val.'" '.($selected == $val ? 'selected="selected"': '').'>'.$val.'</option>';
            }  
            break;
            
        case 'mmm-yy':
            for($i=-3;$i<21;$i++) {
                $val = date ('M-y', strtotime ( '+'.$i.' month' , strtotime ( date('Y-M-01') )));
                $str .= '<option value="'.$val.'" '.($selected == $val ? 'selected="selected"': '').'>'.$val.'</option>';
            }
            break;
    }
    return $str;
}

function getExcelColNameFromNumber($num) {
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getExcelColNameFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}

function dayCount($day,$month,$year) {
    $totalDay=cal_days_in_month(CAL_GREGORIAN,$month,$year);
   $count=0;
    for($i=1;$totalDay>=$i;$i++){
        if( date('l', strtotime($year.'-'.$month.'-'.$i))==ucwords($day)){
            $count++;
        }
    }
    return $count;
}

function searchForMulDimArrKey($search_val, $search_key, $array) {
   foreach ($array as $key => $val) {
       if ($val[$search_key] === $search_val) {
           return $key;
       }
   }
   return null;
}        

