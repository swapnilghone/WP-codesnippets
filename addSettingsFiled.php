<?php

/* 
 * Add new Feilds on settings -> general page
 * The feilds are image upload and color picker fields 
 * 
 */

// Add color filed and image upload option to admin settings -> General page

add_action('admin_init','wsi_add_color_options');

function wsi_add_color_options(){
    
    register_setting( 'general', 'wsi_bg_color');
    add_settings_field( 'wsi_bg_color', 'Background color', 'wsi_bg_color_callback', 'general');

    register_setting( 'general', 'wsi_bg_image');
    add_settings_field( 'wsi_bg_image', 'Background Image', 'wsi_bg_img_callback', 'general');

}

function wsi_bg_color_callback(){
    
    $wsi_bg_color = get_option('wsi_bg_color','');
    
?>
<input type="text" name="wsi_bg_color" id="wsi_bg_color" class="color" value="<?php echo $wsi_bg_color;?>">
<?php }


function wsi_bg_img_callback(){
    
    $wsi_bg_image = get_option('wsi_bg_image','');
    $img = '';
    $sty = 'display:none';
    if($wsi_bg_image!=''){
        $img = '<img src="'. $wsi_bg_image .'" height="48" width="48" />';
        $sty = 'display:inline';
    }
    ?>
    <div id="field_row">
        <input type="text" name="wsi_bg_image" id="wsi_bg_image" value="<?php echo $wsi_bg_image; ?>" class="regular-text">
        <div class="image_wrap">
            <?php echo $img; ?>
        </div>
         <a href="javascript:void(0)" onclick="add_image(this)">Add Image</a>
        <a href="javascript:void(0)" onclick="remove_field(this)" id="remove_img" style="<?php echo $sty; ?>">Remove Image</a>
    </div>
<?php }



// Add Scripts required for image upload and color picker

add_action('admin_head-options-general.php', 'print_scripts_so_14445904');

function print_scripts_so_14445904(){ 
    
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    
    wp_register_script('wsi_color_picker',get_template_directory_uri().'/js/jscolor.js',array('jquery'));
    wp_enqueue_script( 'wsi_color_picker',get_template_directory_uri().'/js/jscolor.js',array('jquery')); 
    
    ?>
    <script>
        
        function add_image(obj) {
                var parent = jQuery(obj).parent('div#field_row');
                var inputField = jQuery(parent).find("input#wsi_bg_image");
                
//            var inputField = jQuery("#input.wsi_bg_img");

                tb_show('', 'media-upload.php?TB_iframe=true');

                window.send_to_editor = function(html) {
                    
                    var url = jQuery(html).attr('href');
                    inputField.val(url);
                    jQuery(parent).find("div.image_wrap").html('<img src="' + url + '" height="48" width="48" />');
                    jQuery("#remove_img").show();
                    // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 

                    tb_remove();
                    
                };

                return false;
            }
            
            function remove_field(obj){
                var parent = jQuery(obj).parent('div#field_row');
                var inputField = jQuery(parent).find("input#wsi_bg_image");
                inputField.val('');
                jQuery(obj).hide();
                jQuery(parent).find("div.image_wrap").empty();
            }
    </script>
<?php }