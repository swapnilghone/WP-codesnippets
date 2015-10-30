<?php

/*
 * Setup the Cron Job for custom csv report generated from the Pay Tuition form
 */

function wsi_schedule_send_payment_report() {
    
    // set system defualt time zone to utc-5
    date_default_timezone_set('America/New_York'); 
//    wp_clear_scheduled_hook('wsi_daily_send_payment_report');
    if (!wp_next_scheduled('wsi_daily_send_payment_report')) {
        
        if( time() > strtotime( 'today 02:00:00' ) ) {
            wp_schedule_event( strtotime( 'tomorrow 02:00:00' ), 'daily', 'wsi_daily_send_payment_report' );
        } else {
            wp_schedule_event( strtotime( 'today 02:00:00' ), 'daily', 'wsi_daily_send_payment_report' );
        }
    }
}

add_action('wp', 'wsi_schedule_send_payment_report');
register_activation_hook(__FILE__, 'wsi_schedule_send_payment_report');



/*
 * Create csv report of payment and send email to client
 */
function wsi_daily_send_payment_report_callback(){
    
    // cron execution login
}
add_action('wsi_daily_send_payment_report','wsi_daily_send_payment_report_callback');
