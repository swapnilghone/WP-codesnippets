<?php

/* 
 * Add Image upload custom filed to post 
 * Image upload options uses wordpress default media upload option
 * 
 */

add_action('add_meta_boxes_post', 'add_image_upload_option');
add_action('admin_head-post.php', 'print_scripts_so_14445904');
add_action('admin_head-post-new.php', 'print_scripts_so_14445904');
add_action( 'save_post', 'save_post_img_background' );

function add_image_upload_option() {
    add_meta_box('post_back_image', 'Background Image', 'add_post_bacg_image', 'post', 'normal', 'core');
}

function add_post_bacg_image() {
    
    global $post;
    $image_url = get_post_meta($post->ID, 'image_url', true);

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'noncename_so_14445904');
    ?>

    <div id="dynamic_form">

        <div id="field_wrap">


            <div class="field_row">

                <div class="field_left">
                    <div class="form_field">
                        <label>Image :</label>
                        <input type="text" class="meta_image_url"   name="image_url" value="<?php esc_html_e($image_url); ?>" />
                    </div>
                </div>
                <div class="field_right image_wrap">
                    <?php
                    if ($image_url != '' || $image_url != NULL) {
                        ?>
                        <img src="<?php esc_html_e($image_url); ?>" height="48" width="48" />
                        <?php
                    } // endif
                    ?>
                </div>
                <div class="field_right">
                    <input class="button" type="button" value="Choose File" onclick="add_image(this)" /><br />
                    <!--<input class="button" type="button" value="Remove" onclick="remove_field(this)" />-->
                </div>

                <div class="clear" /></div> 
        </div>

    </div>

    </div>

    <?php
}

function save_post_img_background($post_id){
    if(isset($_POST['image_url'])){
        update_post_meta($post_id,'image_url',$_POST['image_url']);
    }
}

/**
 * Print styles and scripts
 */
function print_scripts_so_14445904() {
    // Check for correct post_type
    global $post;
    if ('post' != $post->post_type)
        return;
    ?>  
    <style type="text/css">
        .field_left {
            float:left;
        }

        .field_right {
            float:left;
            margin-left:10px;
        }

        .clear {
            clear:both;
        }

        #dynamic_form {
            width:580px;
        }

        #dynamic_form input[type=text] {
            width:300px;
        }

        #dynamic_form .field_row {
            border:1px solid #999;
            margin-bottom:10px;
            padding:10px;
        }

        #dynamic_form label {
            padding:0 6px;
        }
    </style>

    <script type="text/javascript">
        function add_image(obj) {
            var parent = jQuery(obj).parent().parent('div.field_row');
            var inputField = jQuery(parent).find("input.meta_image_url");

            tb_show('', 'media-upload.php?TB_iframe=true');

            window.send_to_editor = function(html) {
                var url = jQuery(html).attr('href');
                inputField.val(url);
                jQuery(parent).find("div.image_wrap").html('<img src="' + url + '" height="48" width="48" />');

                // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 

                tb_remove();
            };

            return false;
        }

        function remove_field(obj) {
            var parent = jQuery(obj).parent().parent();
            //console.log(parent)
            parent.remove();
        }
    </script>
    <?php
}