<?php

/* 
 * Add custom field with all security standards
 * 
 */

// Add our own meta box
add_action('add_meta_boxes_event','add_custom_booking_meta_box');
function add_custom_booking_meta_box(){
    add_meta_box('wsi-custom-booking','Booking Details','wsi_booking_details_callback');
}

function wsi_booking_details_callback($post){ 
    
    wp_nonce_field( 'wsi_booking_details', 'wsi_nonce_19890914' );
    
    ?>

<label for="wsi_booking_price">Price</label>
<input type="text" name="wsi_booking_price" id="wsi_booking_price" value="<?php echo get_post_meta($post->ID,'wsi_booking_price',true); ?>">
    
<?php }

add_action('save_post','save_booking_details',10,2);

function save_booking_details($post_id,$post){
    
    // verify nonce
    if(!isset($_POST['wsi_nonce_19890914']) || ! wp_verify_nonce( $_POST['wsi_nonce_19890914'], 'wsi_booking_details' ) ){
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
    }
    
    // check user permission
    if ( !current_user_can( 'edit_post', $post_id ) )
                return;
    
    // check for our post type
    if ($post->post_type!='event' )
        return;
    
    update_post_meta($post_id,'wsi_booking_price',$_POST['wsi_booking_price']);

    
}