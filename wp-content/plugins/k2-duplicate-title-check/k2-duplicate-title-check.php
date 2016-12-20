<?php
/*
Plugin Name: Duplicate Titles Check
Description: Prevent user's using similar post titles.
Version: 1.1
Author: Kelly Karnetsky
Author URI: http://www.komando.com
Original Author: 5t3ph
Original Author URI: http://stephscharf.me
*/

//jQuery to send AJAX request - only available on the post editing page
function dup_titles_enqueue_scripts( $hook ) {

    if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) )
        return;

    wp_enqueue_script( 
        'duptitles',
        plugins_url( '/duptitles.js', __FILE__ ),
        array( 'jquery' ),
        '1.1'
    );
}
add_action( 'admin_enqueue_scripts', 'dup_titles_enqueue_scripts', 2000 );


// Invoke baked-in WP ajax goodness
// Codex: http://codex.wordpress.org/AJAX_in_Plugins
add_action('wp_ajax_title_check', 'title_check_callback');

function title_check_callback() {

    function title_check() {

        $title = $_POST['post_title'];
        $post_id = $_POST['post_id'];

        global $wpdb;

        $sim_titles = "SELECT post_title,ID 
                    FROM $wpdb->posts 
                    WHERE post_status IN ('publish', 'draft', 'archived', 'pitch', 'assigned', 'in_progress', 'pending_review') 
                    AND post_type IN ('post', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'charts', 'newsletters', 'previous_shows', 'happening_now', 'small_business', 'new_technologies') 
                    AND post_title LIKE '%{$title}%' 
                    AND ID != {$post_id}
                    LIMIT 5";

        $sim_results = $wpdb->get_results($sim_titles);

        if($sim_results) {
            $titles = '<strong style="display:block;padding:10px;margin:0;">These titles match your title:</strong><ul style="padding:10px;margin:0;">';
            foreach ($sim_results as $the_title) {
                $titles .= '<li><a href="' . get_permalink($the_title->ID) . '" target="_blank">' . $the_title->post_title . '</a> in ' . get_post_type_object( get_post_type( $the_title->ID ) )->labels->name . ' (<a href="' . get_edit_post_link($the_title->ID) . '">edit</a>)</li>';
            }
            $titles .= '</ul>';

            return $titles;
        } 
    }

    echo title_check();

    die();
}
?>
