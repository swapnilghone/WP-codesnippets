<?php

// Add custom wholesale pricing option to products 
add_action( 'woocommerce_product_options_pricing', 'woo_add_custom_wholesale_price_fields' );

function woo_add_custom_wholesale_price_fields(){
    global $woocommerce, $post;
  
    echo '<div class="options_group">';

    woocommerce_wp_text_input( 
	array( 
		'id'          => '_wholesale_price', 
		'label'       => __( 'Wholesale Price ('.get_woocommerce_currency_symbol().')', 'woocommerce' ), 
		'desc_tip'    => 'true',
		'description' => __( 'Price will be applicable only for wholesale customers', 'woocommerce' ) 
	)
    );  
    echo '</div>';
}
 
// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_wholesale_price_fields_save' );

function woo_add_custom_wholesale_price_fields_save( $post_id ){
	
	// Text Field
	$woocommerce_text_field = $_POST['_wholesale_price'];
	if( !empty( $woocommerce_text_field ) ){
		update_post_meta( $post_id, '_wholesale_price', esc_attr( $woocommerce_text_field ) );
        }else{
            delete_post_meta($post_id, '_wholesale_price');
        }
}
