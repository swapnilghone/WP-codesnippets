<?php

/*
 * Create custom product type and add addtional fields with product type
 */

// add option under product type select option
add_filter( 'product_type_selector',function ( $types ){
    $types[ 'ticket_product' ] = __( 'Ticket Product' );
    return $types;
});

// override simple product class
add_action( 'init',function(){
     // declare the product class
     class WC_Product_Ticket_Product extends WC_Product{
        public function __construct( $product ) {
           $this->product_type = 'ticket_product';
           parent::__construct( $product );
        }
  
    }
});

// add custom fileds for product type
add_action( 'woocommerce_product_options_general_product_data',function() {
    global $woocommerce, $post;
    echo '<div class="options_group show_if_ticket_product">';

    // Create a number field, for example for UPC
    woocommerce_wp_text_input(
      array(
       'id'                => 'wsi_ticket_adult_price',
       'label'             => __( 'Adult Price', 'woocommerce' ),
       'placeholder'       => '',
       'desc_tip'    => 'true',
       'description'       => __( 'Enter ticket price for adult.', 'woocommerce' ),
       'type'              => 'text'
       ));

    // Create a checkbox for product purchase status
      woocommerce_wp_text_input(
       array(
       'id'                => 'wsi_ticket_child_price',
       'label'             => __( 'Children Price', 'woocommerce' ),
       'placeholder'       => '',
       'desc_tip'    => 'true',
       'description'       => __( 'Enter ticket price for child.', 'woocommerce' ),
       'type'              => 'text'
       ));

    echo '</div>';
});

// add following to display add to cart button for custom product type

if (! function_exists( 'woocommerce_ticket_product_add_to_cart' ) ) {

  /**
  * Output the simple product add to cart area.
  *
  * @subpackage Product
  */

  function ticket_product_add_to_cart() {
    wc_get_template( 'single-product/add-to-cart/simple.php' );
  }

  add_action('woocommerce_ticket_product_add_to_cart',  'ticket_product_add_to_cart');
}