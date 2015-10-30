<?php
/*
 * Apply ssl to payment pages
 */

add_action('wp_head', 'apply_ssl_to_payment_page');

function apply_ssl_to_payment_page(){
    if ( ! is_admin() ) {
        if ( is_page(1349) ) {
                if($_SERVER['SERVER_PORT'] != '443'){
                    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                }
        } else {
                if($_SERVER['SERVER_PORT'] == '443') 
                        header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        }
    }
}
