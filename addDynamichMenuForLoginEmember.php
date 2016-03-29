<?php
/*
 * Add / Remove menu item if member is logged in
 */

add_filter('wp_nav_menu_items', 'add_emeber_menu', 10, 2);

function add_emeber_menu($items, $args) {
    
    $auth = Emember_Auth::getInstance();
    
    if ($auth->isLoggedIn()) {
        if ($args->menu->term_id == 5) {
//            $items = str_replace('<li><a href="https://promo.marhy.com/contractor-login/" class="">Contractor Login</a></li>','',$items);
            $items .= '<li><a href="/add-entry/">Add Entry</a></li>';
//            if ($auth->getUserInfo('membership_level') == 2)
            $items .= '<li><a href="/entries-listing/">View Entries</a></li>';
            $items .= '<li><a href="/edit-profile/">Edit Profile</a></li>';
            $items .= '<li><a href="/?event=logout">Logout</a></li>';
        }
    }

    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'remove_emeber_menu', null, 3 );

function remove_emeber_menu( $items, $menu, $args ) {
    
    $auth = Emember_Auth::getInstance();
    
    if ($auth->isLoggedIn()) {
        
        if ($menu->term_id == 5) {
            
           // Iterate over the items to search and destroy
           foreach ( $items as $key => $item ) {
                if ( $item->object_id == 28 ) unset( $items[$key] );
            }
        }
    }

    return $items;
}
