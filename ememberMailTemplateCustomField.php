<?php

/*
 * modify the emember notification and send the custom field along with registration email
 */
add_filter('eMember_notification_email_body_filter','add_company_name_in_mail_body',10,2);

function add_company_name_in_mail_body($email_body,$curr_member_id){
    
    $name_of_installing_contractor = get_member_custom_filed($curr_member_id,'Name_of_Installing_Contractor');
    $new_email_body = str_replace('{Name_of_Installing_Contractor}', $name_of_installing_contractor, $email_body);
    return $new_email_body;
    
}

function get_member_custom_filed($member_id,$field_key){
    
    global $wpdb,$table_prefix;
    $res = $wpdb->get_row("select * from `".$table_prefix."wp_members_meta_tbl` where user_id = ".$member_id." and meta_key='custom_field'");
    $res = maybe_unserialize($res->meta_value);
    return $res[$field_key];
    
}
