<?php

/* 
 * Add profile image option to user profile page
 * so that user can select his profile image on user profile page
 * Add filter to get_avatar so that custom image is used as author image
 */


add_action('personal_options', 'wsi_extra_profile_fields');

function wsi_extra_profile_fields($user){
    
    wp_nonce_field(plugin_basename(__FILE__), 'noncename_so_14091989');
    $profile_image = get_user_meta($user->ID, 'wsi_profile_image', TRUE);
    ?>
    <tr>
        <th><label for="wsi_profile_image">Profile Image</label></th>

        <td class="field_row">
            <input type="text" name="wsi_profile_image" id="wsi_profile_image" class="regular-text" value="<?php echo $profile_image;?>">
            <input type="button" name="wsi_upload_profile_image" id="wsi_upload_profile_image" value="Add Image" onclick="add_image(this)">
            <span class="image_wrap">
                <?php
                    if($profile_image!=NULL && $profile_image!=''){
                        echo '<img src="'.$profile_image.'" width="48" height="48">';
                    }
                ?>
            </span>
        </td>
    </tr>
<?php
}



/**
 * Add image upload script to user profile page
 */

add_action('admin_head-user-edit.php', 'print_scripts_so_14091989');
add_action('admin_head-user-new.php', 'print_scripts_so_14091989');
add_action('admin_head-profile.php', 'print_scripts_so_14091989');

function print_scripts_so_14091989() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    
    // Check for correct post_type
    ?>  
    <script type="text/javascript">
        function add_image(obj) {
            var parent = jQuery(obj).parent('td.field_row');
            var inputField = jQuery(parent).find("input#wsi_profile_image");

            tb_show('', 'media-upload.php?TB_iframe=true');

            window.send_to_editor = function(html) {
                console.log(html)
                var url = jQuery(html).attr('href');
                inputField.val(url);
                jQuery(parent).find("span.image_wrap").html('<img src="' + url + '" height="48" width="48" />');

                // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 

                tb_remove();
            };

            return false;
    }
    </script>
    <?php
}


// save user Profile Image

add_action('personal_options_update', 'save_extra_profile_fields');
add_action('edit_user_profile_update', 'save_extra_profile_fields');

function save_extra_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;


    update_usermeta($user_id, 'wsi_profile_image', $_POST['wsi_profile_image']);
}


// Apply filter to user avatar to set custom avatar image

add_filter( 'get_avatar' , 'wsi_custom_avatar' , 1 , 5 );

function wsi_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }

    if ( $user && is_object( $user ) ) {
        
            $custom_avatar = get_user_meta($user->data->ID,'wsi_profile_image',TRUE);
            
            if($custom_avatar!=NULL && $custom_avatar!=''){
                $avatar = "<img alt='{$alt}' src='{$custom_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
            }
    }

    return $avatar;
}