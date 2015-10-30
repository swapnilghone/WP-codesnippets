<?php

/* 
 * customize the sitemap file
 * below method adds product categories to sitemap file
 */



add_filter('the_content','sitemap_custom_content');

function sitemap_custom_content($post_content){

    if(is_page('sitemap') && in_the_loop()){
        $args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => 0,
            'parent' => 0
        );
        $pro_cat = get_categories( $args );
        echo '<h4>Product Category : </h4>';
        echo '<ul>';
        foreach ($pro_cat as $pc){ 
            echo '<li><a href="'. get_term_link($pc->slug, 'product_cat') .'">'.$pc->name.'</a>';
                has_child($pc->term_id);
            echo '</li>';
        }
        echo '</ul>';
    }
    
    return $post_content;
    
}

function has_child($id){
    $sub_args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' => 0,
                'parent' => $id
            );
    $pro_sub_cat = get_categories($sub_args);
    if($pro_sub_cat){
        echo '<ul class="children">';
        foreach ($pro_sub_cat as $psc){
//            $sub_term_link = get_term_link( $psc->term_id );
            echo '<li><a href="'. get_term_link($psc->slug, 'product_cat') .'">'.$psc->name.'</a>';
                has_child($psc->term_id);
            echo '</li>';
        }
        echo '</ul>';
    }
}