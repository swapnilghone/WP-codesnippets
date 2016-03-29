<?php

/**
 * Alter the cart dicount based on total number of items in cart
 * @parameter $cart - cart objet
 * 
 * @return void 
 */

function custom_discount_cal() {
    
    global $woocommerce;

    $cart = $woocommerce->cart;
    
    $level_id = wp_eMember_get_user_details("membership_level"); 
    
    if($level_id!=3){ // discount is not appplied to wholesale customers
        
        /* Grab the discount rule define by admin */
        $settings = array_values(get_option('discount_settings'));
        usort($settings, "cmp");

//        _pre($settings);
        $coupon = new WC_Coupon();
        
        /* Grab current total number of products */
        $number_products = $cart->cart_contents_count;

        /* Cart total */
        $content_total = $cart->cart_contents_total;
        $discount = 0;
        
      
        foreach($settings as $ss){
            
            $coupon_code = 'wsi-coup-'.$ss['custom_cart_discount']; // code
             
            if($number_products >= $ss['custom_cart_discount']){
                
                if ( class_exists('WC_Coupon') ) {
                    $coupon = new WC_Coupon($coupon_code);
                }
                
                
                if(!$coupon->exists){ // if coupon does not exitst then create new coupom
                    
                    $amount = $ss['custom_discount_rate']; // Amount
                    $discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product

                    $coupon_parm = array(
                        'post_title'    => $coupon_code,
                        'post_content'  => '',
                        'post_status'   => 'publish',
                        'post_author'   => 1,
                        'post_type'     => 'shop_coupon'
                    );

                    $new_coupon_id = wp_insert_post( $coupon_parm );

                    // Add meta
                    update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
                    update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
                    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
                    update_post_meta( $new_coupon_id, 'product_ids', '' );
                    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
                    update_post_meta( $new_coupon_id, 'usage_limit', '' );
                    update_post_meta( $new_coupon_id, 'expiry_date', '' );
                    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
                    update_post_meta( $new_coupon_id, 'free_shipping', 'yes' );
                }
                
//                _pre($woocommerce->cart->applied_coupons);
                if(!in_array($coupon_code, $woocommerce->cart->applied_coupons)){

                    $woocommerce->cart->remove_coupons();
                    $woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ));
                }
                
            }else{
                
                if(in_array($coupon_code, $woocommerce->cart->applied_coupons)){
                        $woocommerce->cart->remove_coupon($coupon_code);
                }
            }
            
        }
    }
}

add_action('woocommerce_before_cart_table', 'custom_discount_cal');