<?php

/* 
 * Add custom field to custom taxanomy
 * In the following example we have custom taxonomy named as "wsi-product-cat" and
 * we will create custom field named as "category template" for that taxonomy
 * 
 */
/*
 * here the important actions are:
 * 
 * {$taxonomy_name}_add_form_fields - add custom field
 * {$taxonomy_name}_edit_form_fields - edit custom field
 * edit_{$taxonomy_name}  - save custom field
 * create_{$taxonomy_name}  - save custom field
 */


add_action('wsi-product-cat_add_form_fields', 'add_category_template_field');

function add_category_template_field() {
    ?>
    <div class="form-field">
        <label for="wsi_prodcut_cat_template"><?php _e('Category template', 'wsigenesis'); ?></label>
        <select name="wsi_prodcut_cat_template" id="wsi_prodcut_cat_template">
            <option value="tempalte1">Template1</option>
            <option value="tempalte2">Template2</option>
            <option value="tempalte3">Template3</option>
        </select>
        <p class="description"><?php _e('Select the template which will be applied to all category products', 'wsigenesis'); ?></p>
    </div>
    <?php
}

add_action('wsi-product-cat_edit_form_fields', 'edit_category_template_field', 10, 2);

function edit_category_template_field($term) {
    
    $cat_template = get_term_meta($term->term_id,'wsi_prodcut_cat_template',true);
    $templates = array(
        'tempalte1' => 'Template1',
        'tempalte2' => 'Template2',
        'tempalte3' => 'Template3',
    );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="wsi_prodcut_cat_template"><?php _e('Category template', 'wsigenesis'); ?></label>
        </th>
        <td>
            <select name="wsi_prodcut_cat_template" id="wsi_prodcut_cat_template">
                <?php
                foreach ($templates as $val => $label) {
                    if ($cat_template == $val) {
                        echo '<option value="' . $val . '" selected>' . $label . '</option>';
                    } else {
                        echo '<option value="' . $val . '">' . $label . '</option>';
                    }
                }
                ?>
            </select>
            <p class="description"><?php _e('Select the template which will be applied to all category products', 'wsigenesis'); ?></p>
        </td>
    </tr>
    <?php
}

// Save extra taxonomy fields callback function.

add_action('edit_wsi-product-cat', 'save_category_template_field', 10, 2);
add_action('create_wsi-product-cat', 'save_category_template_field', 10, 2);

function save_category_template_field($term_id) {
    if (isset($_POST['wsi_prodcut_cat_template'])) {
        update_term_meta($term_id, 'wsi_prodcut_cat_template', $_POST['wsi_prodcut_cat_template']);
    }
}

