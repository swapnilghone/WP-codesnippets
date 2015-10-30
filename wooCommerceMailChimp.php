<?php

/* 
 * Add checkbox at checkout page which is checked by default
 * When user checkouts he automatically get subscribe to mail chimp 
 */

/*
 * Add the follwing code to payment page tempalate of woocommerce
 * 
 */
 do_action( 'woocommerce_review_order_before_submit' ); // add after this line ?>
<label style="display: inline-block;float: left">
    <input type="checkbox" name="newsletter_signup" value="1" checked>
    Yes, I'd like to receive email updates!
</label>

<?php
// Add follwing code to function.php


/**
 * check if email is already singup else subscribe mail with mailchimp
 */
add_action( 'woocommerce_checkout_update_order_meta', 'add_user_to_mailchimp' );

function add_user_to_mailchimp( $order_id ) {
    
    /*
     * signup user for mailchimp newsletter
     */
    if (isset($_POST['newsletter_signup'])) {
        if(class_exists('GF_MailChimp_Bootstrap')){
            
            require_once ABSPATH . '/wp-content/plugins/gravityformsmailchimp/api/Mailchimp.php';
            
            $api_key = "ed4ac31debf0bed4a0d710639aa1d3a3-us3";
            $list_id = "d41e211c8f";
            
            $Mailchimp = new Mailchimp( $api_key );
            $Mailchimp_Lists = new Mailchimp_Lists( $Mailchimp );
            $subscriber = $Mailchimp_Lists->subscribe( $list_id, array( 'email' => htmlentities($_POST['billing_email']) ),array(),'html',true,true,true,false );
        }
    }
}