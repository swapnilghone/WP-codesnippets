<?php

/*
 * create shortcode custom archive widget , if you want to modify the html structure of default wordpress archive widget
 */

add_shortcode('wsi-archive-link', 'wsi_archive_link_callback');

function wsi_archive_link_callback() {
    $res = '';
    $year_prev = null;
    $months = $wpdb->get_results("SELECT DISTINCT MONTH( post_date ) AS month ,
								YEAR( post_date ) AS year,
								COUNT( id ) as post_count FROM $wpdb->posts
								WHERE post_status = 'publish' and post_date <= now( )
								and post_type = 'post'
								GROUP BY month , year
								ORDER BY post_date DESC");
    foreach ($months as $month) :
        $year_current = $month->year;
        if ($year_current != $year_prev) {
            if ($year_prev != null) {
                $res .= '</ul>';
            }
            $res .= '<h3>' . $month->year . '</h3>';
            $res .= '<ul class="archive-list">';
        }
        $res .='<li>
		<a href="' . get_bloginfo('url') . '/' . $month->year . '/' . date("m", mktime(0, 0, 0, $month->month, 1, $month->year)) . '">
			<span class="archive-month">' . date("F", mktime(0, 0, 0, $month->month, 1, $month->year)) . '</span>
			<span class="archive-count">' . $month->post_count . '</span>
		</a>
	</li>';
        $year_prev = $year_current;
    endforeach;
    $res .='</ul>';
    return $res;
}
