<?php

/* 
 * Create custom short-code for wp-emember compact login box
 */

add_shortcode('wsi_compact_login','shortlogin_callback');

function shortlogin_callback(){
    $emember_config = Emember_Config::getInstance();    
    
    if(wp_emember_is_member_logged_in()){
        $emember_auth = Emember_Auth::getInstance();
//        _pre($emember_auth);
       $str = '<div class="eMember_compact_login">Hello, '.$emember_auth->getUserInfo('first_name').' '.$emember_auth->getUserInfo('last_name').'  | <a href="'.get_logout_url().'">Logout</a></div>';
    }else{
        $str = '<div class="eMember_compact_login"><a href="'.$emember_config->getValue('login_page_url').'"> LoginEmpleados</a></div>';
    }
    
    return $str;
    
}