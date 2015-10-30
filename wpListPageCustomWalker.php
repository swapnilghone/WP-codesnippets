<?php

/* 
 * Custom Walker for wp_list_pages
 */

class my_custom_walker extends walker_nav_menu {

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        $element->main_count = count($children_elements[$element->menu_item_parent]);
        // check, whether there are children for the given ID and append it to the element with a (new) ID
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {

        if ($depth == 0) {
            $output .= '';
        } else {
            $output .= '<ul>';
        }
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {

        if ($depth == 0) {
            $output .= '';
        } else {
            $output .= '</ul>';
        }
    }

    function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0) {
        $child_active_cls = '';
        if ($object->object_id == get_the_ID()) {
            $child_active_cls = 'child-active';
        }

        if ($depth == 0) {
            $main_active = '';
            $parent = get_post_ancestors(get_the_ID());
            if (in_array($object->object_id, $parent)) {
                $main_active = 'active';
            }
            $output .='<li><a href="javascript:void(0)" id="menu-switcher-' . $object->ID . '" class="' . $main_active . '">' . $object->title . '</a>'
                    . '<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-width-4" id = "menu-switcher-' . $object->ID . '" data-menu="menu-switcher-' . $object->ID . '">'
                    . '<div class="uk-grid uk-dropdown-grid">';
        } elseif ($depth == 1) {
            if ($object->hasChildren) {
                if ($object->ID == 740) {
                    $output .= '<div class="uk-width-3-5"><ul class="uk-nav uk-nav-navbar">'
                            . '<li class="uk-parent" data-attr=' . $object->ID . ' ><a class="heading ' . $child_active_cls . '" href="' . $object->url . '">' . $object->title . '</a>';
                } elseif ($object->ID == 326 || $object->ID == 327 || $object->ID == 809 || $object->ID == 810) {
                    $output .= '<div class="uk-width-1-5"><ul class="uk-nav uk-nav-navbar">'
                            . '<li class="uk-parent" data-attr=' . $object->ID . ' ><a class="heading ' . $child_active_cls . '" href="' . $object->url . '">' . $object->title . '</a>';
                } else {
                    $output .= '<div class="uk-width-1-' . $object->main_count . '"><ul class="uk-nav uk-nav-navbar">'
                            . '<li class="uk-parent" data-attr=' . $object->ID . ' ><a class="heading ' . $child_active_cls . '" href="' . $object->url . '">' . $object->title . '</a>';
                }
            } else {
                $output .= '<div class="uk-width-1-5"><ul class="uk-nav uk-nav-navbar">'
                        . '<li class="uk-parent" data-attr=' . $object->ID . ' ><a class="' . $child_active_cls . '" href="' . $object->url . '">' . $object->title . '</a>';
            }
        } elseif ($depth == 2) {
            if ($object->hasChildren) {
                $output .= '<div class="uk-width-1-' . $object->main_count . '">
                            <ul class="uk-nav uk-nav-navbar">
                            <li class="uk-parent"><a href="' . $object->url . '" class="heading ' . $child_active_cls . '">' . $object->title . '</a>';
            } else {
                $output .='<li><a href="' . $object->url . '" class="' . $child_active_cls . '">' . $object->title . '</a>';
            }
        } else {
            $output .='<li data-attr="child-' . $object->ID . '" curr-attr="' . get_the_ID() . '"><a href="' . $object->url . '" class="' . $child_active_cls . '">' . $object->title . '</a>';
        }
    }

    function end_el(&$output, $object, $depth = 0, $args = array()) {
        if ($depth == 0) {
            $output .='</div></div></li>';
        } elseif ($depth == 1) {
            $output .= '</li></ul></div>';
        } elseif ($depth == 2) {
            if ($object->hasChildren) {
                $output .= '</li></ul></div>';
            } else {
                $output .='</li>';
            }
        } else {
            $output .='</li>';
        }
    }

}