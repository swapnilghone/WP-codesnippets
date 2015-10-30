<?php


//action to modify the query of archive page such that it does not consider post from particular catgeroy
add_action( 'pre_get_posts', 'my_change_query'); 

function my_change_query($query){
    
    if(is_archive() && $query->is_main_query()){
       $query->set('cat','-2,-5,-19,-20');//Exclude category with ID  $blog_term_id
    }  
 return $query;
 
}

// Filter to modify wp_get_archive query such that it does not consider post from particular catgeroy

add_filter('getarchives_where','modify_archive_where_query',10,2);

function modify_archive_where_query($qry,$args){
    global $wpdb;
    $qry .= "AND $wpdb->posts.ID IN (select p.ID FROM $wpdb->posts p INNER JOIN $wpdb->term_relationships tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id"
            . " WHERE p.post_type = 'post' AND p.post_status = 'publish' AND tt.term_id NOT IN (2,5,19,20))";
    return $qry;
}