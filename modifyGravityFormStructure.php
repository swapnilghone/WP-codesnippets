<?php

// modify the structure on payment form , on section field break form into seperate ul structure

add_filter("gform_field_content", "create_payment_form_layout", 10, 5);
        
function create_payment_form_layout($content, $field, $value, $lead_id, $form_id){
    if ( ! is_admin() ) {
        if ($form_id == 19) {
            // target section breaks
            if($field['type'] == 'section') {
                return '</li></ul><ul class="gform_fields '.$form['labelPlacement'].' '.$field['cssClass'].'"><li class="gfield gsection empty">';
            }
        }
    }
    return $content;
}
   