<?php
/* 
 * Create custom post status and dispaly it at admin end
 */

/* register new post status (eg : Archive)
 * This methond will only register post statuts but do not display it anywhere
 */

add_action( 'init', 'create_porject_custom_post_status' );

function create_porject_custom_post_status(){
    
    register_post_status( 'archive', array(
            'label'                     => _x('Archive','wsi_project'),
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>' ),
    ) );
}

/*
 * add archive status to post edit page status dropdown
 */

add_action('admin_footer-post.php', 'wsi_append_post_status_list');
function wsi_append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';
     if($post->post_type == 'wsi_project'){
          if($post->post_status == 'archive'){
               $complete = ' selected=\"selected\"';
               $label = '<span id=\"post-status-display\">Archive</span>';
          }
          echo '
          <script>
          jQuery(document).ready(function($){
               $("select#post_status").append("<option value=\"archive\" '.$complete.'>Archive</option>");
               $(".misc-pub-section label").append("'.$label.'");
          });
          </script>
          ';
     }
}

/*
 * add archive status to quick edit status dropdown
 */
add_action( 'admin_footer-edit.php', 'wsi_append_post_status_bulk_edit' );

function wsi_append_post_status_bulk_edit(){
     global $post;
     if($post->post_type == 'wsi_project'){
         echo '<script>
          jQuery(document).ready(function($){
               $(".inline-edit-status select").append("<option value=\"archive\">Archive</option>");
          });
          </script>';
     }
}
