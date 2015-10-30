<?php

/* 
 * Add custom field to user profile page
 * save the field vale
 */

// Add the custom profile page option under user profile page
add_action('personal_options', 'extra_profile_fields');

function extra_profile_fields($user) {
    $profile_page = get_user_meta($user->ID, 'profile_page', TRUE);
    ?>
    <tr>
        <th><label for="twitter">Profile Page</label></th>

        <td>
            <select name="profile_page"> 
                <?php
                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'page',
                    'post_status' => 'published',
                    'post_parent' => 31
                );

                $usr = get_posts($args);

                foreach ($usr as $u) {
                    $str = '';
                    if ($u->ID == $profile_page) {
                        $str = 'selected';
                    }
                    echo '<option value="' . $u->ID . '" ' . $str . '>' . $u->post_title . '</option>';
                }
                ?>
            </select>
            <span class="description">Please Select your profile page , which will be displayed on front end.</span>
        </td>
    </tr>
<?php
}

// save the custom profile page option


add_action('personal_options_update', 'save_extra_profile_fields');
add_action('edit_user_profile_update', 'save_extra_profile_fields');

function save_extra_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;


    update_usermeta($user_id, 'profile_page', $_POST['profile_page']);
}
