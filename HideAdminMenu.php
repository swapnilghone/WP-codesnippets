<?php


/*
 * Action to hide the other menus such as post, comments and tools if employer is logged in
 */

add_action( 'admin_menu', 'remove_menus_for_employer' ); 

function remove_menus_for_employer(){
    
    global $current_user;

    if ( is_user_logged_in() ) {
        
	$user_roles = $current_user->roles;

            if ( !empty( $user_roles ) && is_array( $user_roles ) ) {
                    foreach ( $user_roles as $role ){
                        if($role == 'emember_admin' ){
                                remove_menu_page( 'edit-comments.php' );          //Comments
                                remove_menu_page( 'edit.php' );                    // posts
                                remove_menu_page( 'tools.php' );                  //Tools
                                remove_menu_page( 'edit.php?post_type=shortcodepro' );                  //Tools
                                return;
                        }
                    }
            }
    }
}
