<?php

/* 
 * customize the emember compact login short code
 */


add_shortcode( 'wp_eMember_compact_login', 'custom_comnpact_login');

function custom_comnpact_login(){
    $emember_config = Emember_Config::getInstance();
    $join_url = $emember_config->getValue('eMember_payments_page');
    $auth = Emember_Auth::getInstance();
    $output = "";
    $output .= "<div class='eMember_compact_login'>";
    if ($auth->isLoggedIn()) {
        $output .= EMEMBER_HELLO;
        $name = $auth->getUserInfo('first_name') . " " . $auth->getUserInfo('last_name');
        ;
        $output .= $name;

        if (!empty($show_profile_link)) {
            $output .= ' | ';
            $edit_profile_page = $emember_config->getValue('eMember_profile_edit_page');
            $output .= '<a href="' . $edit_profile_page . '">' . EMEMBER_EDIT_PROFILE . '</a>';
        }

        $logout = get_logout_url();
        $output .= ' | ';
        $output .= '<a href="' . $logout . '">' . EMEMBER_LOGOUT . '</a>';
    } else {
        if (is_search())
            return get_login_link();
        $output .= 'Returning Users: ';
        $eMember_enable_fancy_login = $emember_config->getValue('eMember_enable_fancy_login');
        if ($eMember_enable_fancy_login) {
            $output .= '<a id="' . microtime(true) . '" class="emember_fancy_login_link" href="javascript:void(0);">' . EMEMBER_LOGIN . '</a>';
            ob_start();
            include_once('fancy_login.php');
            $output_fancy_jquery = ob_get_contents();
            ob_end_clean();
            $output .= $output_fancy_jquery;
        } else {
            $login_url = $emember_config->getValue('login_page_url');
            $output .= '<a href="' . $login_url . '">' . EMEMBER_LOGIN . '</a>';
        }
        $output .= '.   New Customers: ';
        $join_url = $emember_config->getValue('eMember_payments_page');
        $output .= '<a href="' . $join_url . '">Create an Account</a>';
    }
    $output .= "</div>";
    return $output;
}