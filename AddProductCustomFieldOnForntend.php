<?php

/* 
 * Create custom field for product, which will be reflect on product front page and also included in cart and order meta
 */

/** Custom Tabs Under Product Data Section **/
 
function wsi_team_payment_meta_tab() {
?>
    <li class="team_payments"><a href="#team_payments_data"><?php _e('Team Payments', 'woothemes'); ?></a></li>
<?php
}
add_action('woocommerce_product_write_panel_tabs', 'wsi_team_payment_meta_tab'); 
 
/**
 * Custom Tab Options
 * 
 * Provides the input fields and add/remove buttons for custom tabs on the single product page.
 */
function wsi_team_payment_meta_options() {
    
    global $post;
    $team_payment_meta = get_post_meta($post->ID, 'team_payment_meta', true);
?>
    <div id="team_payments_data" class="panel woocommerce_options_panel">
        <div class="club-opening-hours">
            <p id="field-container">
                <?php
                    if(!empty($team_payment_meta)){
                        foreach ($team_payment_meta as $tpm){
                            if($tpm!=''){
                            ?>
                            <p class="form-field">
                                <label>Field Label:</label>
                                <input type="text" name="team_payment_meta[]" value="<?php echo $tpm; ?>">
                                <a href="javascript:void(0)" onclick="remove_field(this)">Remove</a>
                            </p>    
                            <?php
                            }
                        }
                    }
                ?>
            </p>
            <p class="form-field day_field_type">
                <input class="button" type="button" value="Add Meta Field" onclick="add_field_row();" />
            </p>
        </div>
    </div>
    <div id="field-html" style="display: none">
        <p class="form-field">
            <label>Field Label:</label>
            <input type="text" name="team_payment_meta[]">
            <a href="javascript:void(0)" onclick="remove_field(this)">Remove</a>
        </p>
    </div>
<?php
}
add_action('woocommerce_product_write_panels', 'wsi_team_payment_meta_options');

// Save Fields
add_action( 'woocommerce_process_product_meta', 'wsi_team_payment_meta_save' );
 
    // Function to save all custom field information from products
function wsi_team_payment_meta_save( $post_id ){
    if(isset($_POST['team_payment_meta']) && $_POST['team_payment_meta']!=''){
        update_post_meta( $post_id, 'team_payment_meta', $_POST['team_payment_meta'] );
    }
}

add_action( 'admin_head-post.php', 'print_scripts_so_14445904' );
add_action( 'admin_head-post-new.php', 'print_scripts_so_14445904' );

function print_scripts_so_14445904(){ 
    // Check for correct post_type
    global $post;
    if( 'product' != $post->post_type )
        return;
?>
    <script>
        function add_field_row() {
            var row = jQuery('#field-html').html();
            jQuery(row).appendTo('#field-container');
        }
        
        function remove_field(obj){
            var parent = jQuery(obj).parent('.form-field');
            parent.remove();
        }
    </script>
<?php }


add_action('woocommerce_before_single_variation','add_payment_fields');

function add_payment_fields(){
    
    global $post;
    $team_payment_meta = get_post_meta($post->ID,'team_payment_meta',true);
    
    if(!empty($team_payment_meta)){
        foreach ($team_payment_meta as $tpm){
            if($tpm!=''){
            ?>
            <p class="form-field">
                <label><?php echo $tpm; ?>:</label>
                <input type="text" name="team_payment_meta[<?php echo str_replace(' ','_', $tpm); ?>]">
            </p>    
            <?php
            }
        }
    }
}

// This captures additional posted information
add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,10);

function wdm_add_item_data($cart_item_data, $product_id) {

    global $woocommerce;
    $new_value = array();
    $new_value['team_payment_meta'] = $_POST['team_payment_meta'];

    if(empty($cart_item_data)) {
        return $new_value;
    } else {
        return array_merge($cart_item_data, $new_value);
    }
}


// This captures the information from the previous function and attaches it to the item.
add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );

function wdm_get_cart_items_from_session($item,$values,$key) {

    if (array_key_exists( 'team_payment_meta', $values ) ) {
        $item['team_payment_meta'] = $values['team_payment_meta'];
    }

    return $item;
}

//This displays extra information on basket & checkout from within the added info that was attached to the item.
add_filter('woocommerce_cart_item_name','add_usr_custom_session',1,3);
function add_usr_custom_session($product_name, $values, $cart_item_key ) {

    $return_string = $product_name;
    if(!empty($values['team_payment_meta'])){
        foreach ($values['team_payment_meta'] as $lable=>$val){
            $return_string .='<br/><b>'.$lable.':</b>  '.$val;
        }
    }
    return $return_string;

}

//If you want to override the price you can use information saved against the product to do so
add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
function wdm_add_values_to_order_item_meta($item_id, $values) {
    
    global $woocommerce,$wpdb;
    
    if(!empty($values['team_payment_meta'])){
        foreach ($values['team_payment_meta'] as $lable=>$val){
            wc_add_order_item_meta($item_id,$lable,$val);
        }
    }
    
}