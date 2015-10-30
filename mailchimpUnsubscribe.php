<?php

/* 
 * Create shortcode for unsubscribe form of mail chip 
 * 
 */


/**
* Calls the MailChimp API
*
* @uses WP_HTTP
*
* @param string $method
* @param array $data
*
* @return object
*/
function call_unsub($email)
{	
    
        global $table_prefix , $wpdb;
        $opt = maybe_unserialize(get_option('gf_mailchimp_settings'));
        $api_key = $opt['apikey'];
        
        $list = $wpdb->get_results("Select * from ".$table_prefix."rg_mailchimp");
        $list_meta = maybe_unserialize($list[0]->meta);
        $list_id = $list_meta['contact_list_id'];
        
        // do not make request when no api key was provided.
        if(!$api_key) { 
                return false; 
        }
       
        if( strpos( $api_key, '-' ) !== false ) {
            $api_url = 'https://' . substr( $api_key, -3 ) . '.api.mailchimp.com/2.0/lists/unsubscribe.json';
        }else{
            $api_url = 'https://api.mailchimp.com/2.0/lists/unsubscribe.json';
        }
        
        $final_call = array( 
                'timeout' => 20,
                'headers' => array('Accept-Encoding' => ''),
                'sslverify' => false,
                'body' => array( 
                        'apikey' => $api_key,
			'id' => $list_id,
			'email' => array( 'email' => $email),
			'delete_member' => true,
			'send_goodbye' => true,
			'send_notify' => true
		)
        ) ;
        
        $response = wp_remote_post( $api_url,$final_call );
        
        
        // test for wp errors
        if( is_wp_error( $response ) ) {
                // show error message to admins
                $this->show_error( "HTTP Error: " . $response->get_error_message() );
                return false;
        }

        $body = wp_remote_retrieve_body( $response );

        return json_decode( $body );
}

add_shortcode('mailchimp_unsubscribe','unsubscribe_form') ;


function unsubscribe_form(){
    if(class_exists('GFMailChimp')){
    if($_POST['email']){
        $result = call_unsub($_POST['email']);
        
        if( $result ) {

                if( ! isset( $result->error ) ) {
                        $message = 'successfully un-subscribed!!';
                } else {
                    $message = $result->error;
                }
                
        } else {
                    $message = 'No response recevied , Please try after some time';
        }
    }
    $res = '<div class="gf_browser_chrome gform_wrapper">
    <form method="post">
        <div class="gform_body">
            <ul id="gform_fields_1" class="gform_fields top_label description_below">
                <li id="field_1_1" class="gfield">
                    <label class="gfield_label" for="input_1_1">Email</label>
                        <div class="ginput_container">
                            <input type="text" name="email" class="medium">
                        </div>
                </li>
            </ul>
        </div>
        <div class="gform_footer top_label">
            <input type="submit" name="unsubscrbe" class="gform_button button" value="unsubscrbe">
        </div>
        <div class="gform_confirmation_wrapper "> '.$message.'</div>
            
    </form>
</div>'; 

    }else{
        $res ='You need Gravtiy Form and Gravity Form MailChimp addon Active, in-order to use this functionality!!';
    }
    
    return $res;
    }