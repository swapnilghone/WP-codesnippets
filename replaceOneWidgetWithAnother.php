<?php

/* 
 * Code to replace one widget with another
 */

add_action('widgets_init', array($this, 'wsits_replace_feature_page'));

/*
     * Function to replace feature page with feature post
     */

  public function wsits_replace_feature_page() {
//        _pre(get_option('wsigenesiswarp_theme_options'));
//        _pre(maybe_unserialize(get_option('widget_wsits-featured-page')));
//        _pre(maybe_unserialize(get_option('widget_wsits-featured-post')));
        if (get_option('widget_wsits-featured-page')) {

            $feature_page = maybe_unserialize(get_option('widget_wsits-featured-page'));
            $temp = $feature_page;
            $key_array = array();

            // fetch all featre post widget
            $feature_post = maybe_unserialize(get_option('widget_wsits-featured-post'));

            // fetch all widget positions
            $all_widgets = maybe_unserialize(get_option('sidebars_widgets'));
            
            // fetch the theme settings data for widget assocaition
            $wsigenesiswarp_theme_options = maybe_unserialize(get_option('wsigenesiswarp_theme_options'));
//            _pre($wsigenesiswarp_theme_options);
            $widget_options = $wsigenesiswarp_theme_options['widgets'];
            // create new array to be replace the feature post
            foreach ($feature_page as $key => $fp) {
                if ($key != '_multiwidget') {
                    $fp['posts_type'] = 'page';
                    if (!empty($fp['show_content'])) {
                        if (empty($fp['content_limit'])) {
                            $fp['show_content'] = 'content';
                        } else {
                            $fp['show_content'] = 'content-limit';
                        }
                    } else {
                        $fp['show_content'] = '';
                    }
                    $feature_post[] = $fp;
                    $key_array['wsits-featured-page-' . $key] = 'wsits-featured-post-' . end(array_keys($feature_post));
                }
            }

            if (array_key_exists('_multiwidget', $feature_post)) {

                unset($feature_post['_multiwidget']);
                $feature_post['_multiwidget'] = 1;
            }
            
//            _pre($feature_post);

            // replace all the feature page widget with feature post
            foreach ($all_widgets as $allw_key => $widget_area) {
                foreach ($widget_area as $wk => $wd) {
                    if (array_key_exists($wd, $key_array)) {
                        $widget_area[$wk] = $key_array[$wd];
                    }
                    $all_widgets[$allw_key] = $widget_area;
                }
            }
            
            
            // modify the widget assocaition data in theme settings
            
            
            foreach ($key_array as $krk=>$krv){
                if(array_key_exists($krk, $widget_options)){
                    $widget_options[$krv] = $widget_options[$krk];
                    unset($widget_options[$krk]);
                }
            }
            $wsigenesiswarp_theme_options['widgets'] = $widget_options;
            
//            _pre($wsigenesiswarp_theme_options);
            update_option('widget_wsits-featured-post',$feature_post);
            update_option('sidebars_widgets',$all_widgets);
            update_option('wsigenesiswarp_theme_options',$wsigenesiswarp_theme_options);
            delete_option('widget_wsits-featured-page');
        } else {
            return;
        }
    }

