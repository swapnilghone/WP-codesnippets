<?php

/* 
 * Create entry object and fetch entries 
 */

    $search_criteria = array(
           'start_date'=> wsi_get_gmt_date($sdate),
           'end_date'=> wsi_get_gmt_date($edate),
           'status' => 'active'
       );

    // fetch all the entries for current day from 12:00 am to 11:59pm
    
    
    $entries = GFFormsModel::search_leads($form_id,$search_criteria); 
    
    
// fucntion handles time conversion of UTC to UTC-4 (which is set from setting general)
function wsi_format_date($gmt_datetime, $is_human = true, $date_format="Y-m-d H:i:s", $include_time=FALSE){
        
        if(empty($gmt_datetime))
            return "";

        //adjusting date to local configured Time Zone
        $lead_gmt_time = mysql2date("G", $gmt_datetime);
        $lead_local_time = $lead_gmt_time + (get_option( 'gmt_offset' ) * 3600 );;

        if(empty($date_format))
            $date_format = get_option('date_format');

        $time_diff = time() - $lead_gmt_time;

        $date_display = $include_time ? sprintf(__('%1$s at %2$s', 'gravityforms'), date_i18n($date_format, $lead_local_time, true), date_i18n(get_option('time_format'), $lead_local_time, true)) : date_i18n($date_format, $lead_local_time, true);
        
        return $date_display;
}

function wsi_get_gmt_date($local_date){

    $local_timestamp = strtotime($local_date);
    $gmt_timestamp = $local_timestamp - (get_option( 'gmt_offset' ) * 3600 );
    $date = gmdate("Y-m-d H:i:s", $gmt_timestamp);
    return $date;
}