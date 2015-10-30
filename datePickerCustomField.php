<?php

/* 
 * Add datepicker to custom field
 */

add_action('admin_enqueue_scripts', 'load_datepicker_scripts');

function load_datepicker_scripts(){
     wp_enqueue_script(
			'jquery-ui-datepicker', 
			'jquery-ui-datepicker.js', 
			array('jquery', 'jquery-ui-core'),
			time(),
			true
		);	
                wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
                wp_enqueue_style( 'jquery-ui' );    
}


/* add start date and end date custom feild to content sechudelar page
 * 
 */

add_action("add_meta_boxes_post", "add_custom_meta_box");
 
function add_custom_meta_box($post){
  add_meta_box('interval-meta', 'Post Sechedule', 'p_sec', 'post' , 'normal' , 'default');
}

function p_sec(){
	global $post;
	$ps_start_date = get_post_meta($post->ID,'ps_start_date',TRUE);
	$ps_end_date = get_post_meta($post->ID,'ps_end_date',TRUE);
	?>
  <p><strong>Start Date : </strong></p>
  <p><input name="ps_start_date" id="ps_start_date" value="<?php echo $ps_start_date; ?>" ></p>
  <p><strong>End Date : </strong></p>
  <p><input name="ps_end_date" id="ps_end_date" value="<?php echo $ps_end_date; ?>" ></p>
  <script>
        jQuery(function() {
            jQuery("#ps_start_date").datepicker();
            jQuery("#ps_end_date").datepicker();
        });
    </script>
<?php
  
}

//to save the details

add_action('save_post', 'save_details');
function save_details(){
  global $post;
  update_post_meta($post->ID, "ps_start_date", $_POST["ps_start_date"]);
  update_post_meta($post->ID, "ps_end_date", $_POST["ps_end_date"]);
}
