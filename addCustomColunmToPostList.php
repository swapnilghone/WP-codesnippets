<?php

/* 
 * Add custom column to post listing page
 * filter to be used - manage_posts_columns (manage_{$post_type}_posts_columns)
 * action to be user - manage_posts_custom_column (manage_{$post_type}_posts_custom_column)
 */


/* Add custom column to post list */
function add_shortcode_column( $columns ) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'shortcode' => __( 'Shotcode' ),
		'date' => __( 'Date' )
    );
    return $columns;
}
add_filter('manage_property_managment_posts_columns' , 'add_shortcode_column');

/*
 * Add value of custom column
 */
add_action( 'manage_property_managment_posts_custom_column' , 'shortcode_custom_columns', 10, 2 );

function shortcode_custom_columns( $column, $post_id ) {
    if ($column == 'shortcode'){
        echo '[wsi-property id='.$post_id.']';
    }
}

