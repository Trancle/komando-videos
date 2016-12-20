<?php
/**
 * functions-actions.php
 * Function definitions which are used with add_action()
 * Created by PhpStorm.
 * User: gilbert
 * Date: 5/15/2015
 * Time: 9:25 AM
 */

############
## Redirects attachments to parent
############

function k2_attachment_redirect() {
    global $post;

    if(is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0)) {
        wp_redirect(get_permalink($post->post_parent), 301); // permanent redirect to post/page where image or document was uploaded
        die();
    } elseif(is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent < 1)) {   // for some reason it doesnt works checking for 0, so checking lower than 1 instead...
        wp_redirect(get_bloginfo('wpurl'), 302); // temp redirect to home for image or document not associated to any post/page
        die();
    }
}

add_action('template_redirect', 'k2_attachment_redirect',1);

############
## Change the way the default scripts load
############

function k2_header_scripts() {
    if (!is_admin()) {
        wp_deregister_script('jquery'); // Deregister WordPress jQuery
        wp_deregister_script('jqueryui'); // Deregister WordPress jQuery
        wp_deregister_style('style'); // Deregister WordPress default stylesheet
    }
}
add_action('init', 'k2_header_scripts'); // Deregister styles and scripts

function k2_remove_jquery_dependency(){
    $handles = ['mediaelement', 'suggest'];
    foreach($handles as $handle) {
        k2_remove_dependencies($handle, 'jquery');
    }
}
add_action('init', 'k2_remove_jquery_dependency', 11 );

function k2_remove_dependencies($handle, $dep) {
    global $wp_scripts;

    if(is_object($wp_scripts)) {
        $script = $wp_scripts->query( $handle, 'registered' );
        if ( ! $script ) {
            return false;
        }

        if ( in_array( $dep, $script->deps ) ) {
            unset( $script->deps[ array_search( $dep, $script->deps ) ] );
        }

        return true;
    } else {
        return false;
    }
}

############
## Remove wp_head() injected Recent Comment styles
############

function my_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()

############
## Pagination for paged posts, No plugin
############

function k2wp_pagination($args = '') {

    $defaults = array(
        'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
        'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
        'total' => 1,
        'current' => 0,
        'show_all' => false,
        'prev_next' => true,
        'prev_text' => __('<i class="fa fa-angle-left"></i> Previous'),
        'next_text' => __('Next <i class="fa fa-angle-right"></i>'),
        'end_size' => 1,
        'mid_size' => 2,
        'type' => 'plain',
        'add_args' => false, // array of query args to add
        'add_fragment' => ''
    );

    $args = wp_parse_args( $args, $defaults );
    extract($args, EXTR_SKIP);

    // Who knows what else people pass in $args
    $total = (int) $total;
    if ( $total < 2 )
        return;
    $current  = (int) $current;
    $end_size = 0  < (int) $end_size ? (int) $end_size : 1; // Out of bounds?  Make it the default.
    $mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
    $add_args = is_array($add_args) ? $add_args : false;
    $r = '';
    $page_links = array();
    $n = 0;
    $dots = false;

    if ( $prev_next && $current && 1 < $current ) :
        $link = str_replace('%_%', 2 == $current ? '' : $format, $base);
        $link = str_replace('%#%', $current - 1, $link);
        if ( $add_args )
            $link = add_query_arg( $add_args, $link );
        $link .= $add_fragment;
        $page_links[] = '<a class="prev page-numbers btn" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $prev_text . '</a>';
    endif;
    for ( $n = 1; $n <= $total; $n++ ) :
        $n_display = number_format_i18n($n);
        if ( $n == $current ) :
            $page_links[] = "<span class='page-numbers current btn disabled hide-mobile' disabled>$n_display</span>";
            $dots = true;
        else :
            if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                $link = str_replace('%_%', 1 == $n ? '' : $format, $base);
                $link = str_replace('%#%', $n, $link);
                if ( $add_args )
                    $link = add_query_arg( $add_args, $link );
                $link .= $add_fragment;
                $page_links[] = "<a class='page-numbers btn hide-mobile' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>$n_display</a>";
                $dots = true;
            elseif ( $dots && !$show_all ) :
                $page_links[] = '<span class="page-numbers dots btn hide-mobile">' . __( '&hellip;' ) . '</span>';
                $dots = false;
            endif;
        endif;
    endfor;
    if ( $prev_next && $current && ( $current < $total || -1 == $total ) ) :
        $link = str_replace('%_%', $format, $base);
        $link = str_replace('%#%', $current + 1, $link);
        if ( $add_args )
            $link = add_query_arg( $add_args, $link );
        $link .= $add_fragment;
        $page_links[] = '<a class="next page-numbers btn" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_text . '</a>';
    endif;
    switch ( $type ) :
        case 'array' :
            return $page_links;
            break;
        case 'list' :
            $r .= "<ul class='page-numbers'>\n\t<li>";
            $r .= join("</li>\n\t<li>", $page_links);
            $r .= "</li>\n</ul>\n";
            break;
        default :
            $r = join("\n", $page_links);
            break;
    endswitch;
    return $r;
}
add_action('init', 'k2wp_pagination'); // Add our pagination

############
## Redirect to type-$posttype.php
############

function k2_template_redirect() {
    global $wp;
    if (is_robots() || is_feed() || is_trackback() || is_single() || !isset($wp->query_vars['post_type'])) {
        return; // run the default action
    }
    $template = locate_template(array('type-' . $wp->query_vars['post_type'] . '.php'));
    if ($template) {
        include($template);
        exit;
    }
}
add_action('template_redirect', 'k2_template_redirect'); // Redirect to type-$posttype.php

############
## 404 author archives
############

function k2_disable_author_archive() {
    if (is_author()) {
        global $wp_query;
        $wp_query->set_404();
    }
}
add_action('template_redirect', 'k2_disable_author_archive'); // 404 author archives

############
## Remove menu items from dashboard, removes meta boxes
############

function k2_remove_menu_items() {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('post-new.php');

    if(!current_user_can('manage_options')) {
        remove_meta_box('postcustom', 'post', 'normal');
        remove_meta_box('postcustom', 'columns', 'normal');
        remove_meta_box('postcustom', 'downloads', 'normal');
        remove_meta_box('postcustom', 'apps', 'normal');
        remove_meta_box('postcustom', 'cool_sites', 'normal');
        remove_meta_box('postcustom', 'tips', 'normal');
        remove_meta_box('postcustom', 'buying_guides', 'normal');
        remove_meta_box('postcustom', 'charts', 'normal');
        remove_meta_box('postcustom', 'newsletters', 'normal');
        remove_meta_box('postcustom', 'happening_now', 'normal');
        remove_meta_box('postcustom', 'qotd', 'normal');
        remove_meta_box('postcustom', 'small_business', 'normal');
        remove_meta_box('postcustom', 'new_technologies', 'normal');
    }
}
add_action('admin_menu', 'k2_remove_menu_items'); // Remove menu items from dashboard, removes meta boxes

############
## Remove menu items from the admin bar
############

function remove_wp_nodes() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('new-media');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('wp-logo');
}
add_action('admin_bar_menu', 'remove_wp_nodes', 999); // Remove menu items from the admin bar

############
## Adds the favicon to the admin side
############

function k2_add_favicon_admin() {
    echo '<link rel="shortcut icon" href="' . k2_get_static_url('v2') . '/icons/favicon-admin.ico" />';
}

add_action('login_head', 'k2_add_favicon_admin');
add_action('admin_head', 'k2_add_favicon_admin');

############
## Remove dashboard widgets
############

function k2_remove_dashboard_widgets() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');

    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'k2_remove_dashboard_widgets'); // Remove dashboard widgets

############
## Custom RSS templates
############

remove_all_actions('do_feed_rss2');
remove_all_actions('do_feed_rdf');
remove_all_actions('do_feed_atom');

add_action('do_feed_rss2', 'k2_feed_rss2', 10, 1);
add_action('do_feed_rdf', 'k2_feed_rdf', 10, 1);
add_action('do_feed_atom', 'k2_feed_atom', 10, 1);

############
## Blocks non-admins from seeing the WordPress dashboard
############

function k2_block_admin_access() {
    global $current_user;
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
    if (is_user_logged_in()) {

        if (in_array('subscriber', $current_user->roles) || in_array('basic_member', $current_user->roles) || in_array('premium_member', $current_user->roles)) {
            exit(wp_redirect($redirect));
        }
    }
}
add_action('admin_init', 'k2_block_admin_access', 100); // Blocks non-admins from seeing the WordPress dashboard

############
## Grid system for the sections (standard post types)
############

function section_grid($page = null, $section = null) {

    if (empty($page)) $page = $_GET['page'];

    if (empty($section)) $section = $_GET['section'];

    wp_reset_query();

    $featured_ads = get_option('k2_featured_ads_' . $section);

    if($featured_ads['active']) {

        if($section == 'happening_now') {
            $num = 9;
        } else {
            $num = 6;
        }

    } else {

        if($section == 'happening_now') {
            $num = 10;
        } else {
            $num = 7;
        }

    }

    $args = array(
        'posts_per_page' => $num,
        'post_type' => $section,
        'meta_query' => array(
            array(
                'key' => 'feature_meta_id',
                'value' => '1',
                'compare' => '='
            )
        )
    );

    $my_query = new WP_Query($args);

    foreach ($my_query->posts as $post) {
        $excluded[] = $post->ID;
    }

    wp_reset_query();

    // Getting the last x# posts
    $args = array(
        'posts_per_page' => 29,
        'post__not_in' => $excluded,
        'paged' => $page,
        'post_type' => $section,
        'post_status' => 'publish'
    );

    $my_query = new WP_Query($args);

    $i = 1;

    foreach ($my_query->posts as $post) {

        $app_thumb = MultiPostThumbnails::get_post_thumbnail_url($section, 'app-icon', $post->ID, 'app-icon');

        $image_id = get_post_thumbnail_id($post->ID);
        $placeholder_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
        $large_image = wp_get_attachment_image_src($image_id, 'large')[0];
        $medium_image = wp_get_attachment_image_src($image_id, 'medium')[0];
        if(empty($large_image) || wp_get_attachment_image_src($image_id, 'large')[1] < 970) {
            $large_image = $placeholder_image;
        }

        if(empty($medium_image) || wp_get_attachment_image_src($image_id, 'medium')[1] < 520) {
            $medium_image = $placeholder_image;
        }

        $category = get_the_terms($post->ID, $section . '_categories');

        if ($category) {
            $key = current(array_keys($category));
            $cat_link = '<a href="' . get_term_link($category[$key]->slug, $category[$key]->taxonomy) . '">' . $category[$key]->name . '</a>';
        } else {
            $cat_link = '';
        }

        if($i == 2) {

            // If first page show the ad else grab a block
            if($page == 1) { ?>

                <div class="grid-item-ad">
                    <div class="ad-container clearfix">
                        <div id="ad-rectangle-grid-1" style="min-width:300px; min-height:50px; margin:auto;">
                            <script type='text/javascript'>
                                googletag.cmd.push(function() { googletag.display('ad-rectangle-grid-1'); });
                            </script>
                        </div>   
                    </div>
                </div>

            <?php } else {

                kims_club_grid_block();

            } ?>

            <article class="grid-item<?php if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php echo get_permalink($post->ID); ?>" data-article-id="<?php echo $post->ID; ?>">
                <figure>
                    <a href="<?php echo get_permalink($post->ID); ?>">
                        <img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $medium_image; ?>" data-src-retina="<?php echo $large_image; ?>" alt="<?php echo get_the_title($post->ID); ?>" />
                        <?php if(!empty($app_thumb)) { echo '<div><div><img src="' . $app_thumb . '" alt="' . $item['title'] . '" /></div></div>'; } ?>
                    </a>
                </figure>
                <div class="grid-item-body">
                    <header>
                        <span class="grid-item-section hide-mobile"><?php echo $cat_link; ?></span>
                        <h3><a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h3>
                    </header>
                    <div class="grid-item-meta hide-mobile clearfix">
                        <div class="grid-item-share">
                            <span class="icon-k2-share"></span> Share
                        </div>
                        <div class="grid-item-share-icons hide-mobile">
                            <div class="st_email_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($post->ID);?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                        </div>
                        <?php echo k2_post_view($post->ID); ?>
                    </div>
                </div>
            </article>

        <?php } elseif ($i == 3 || $i == 9 || $i == 13 || $i == 19 || $i == 23 || $i == 29) { ?>

            <article class="grid-item featured<?php if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php echo get_permalink($post->ID); ?>" data-article-id="<?php echo $post->ID; ?>">
                <figure>
                    <a href="<?php echo get_permalink($post->ID); ?>">
                        <img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $large_image; ?>" data-src-retina="<?php echo $large_image; ?>" alt="<?php echo get_the_title($post->ID); ?>" />
                        <?php if(!empty($app_thumb)) { echo '<div><div><img src="' . $app_thumb . '" alt="' . $item['title'] . '" /></div></div>'; } ?>
                    </a>
                </figure>
                <div class="grid-item-body">
                    <header>
                        <span class="grid-item-section hide-mobile"><?php echo $cat_link; ?></span>
                        <h3><a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h3>
                    </header>
                    <div class="grid-item-meta hide-mobile clearfix">
                        <div class="grid-item-share">
                            <span class="icon-k2-share"></span> Share
                        </div>
                        <div class="grid-item-share-icons hide-mobile">
                            <div class="st_email_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($post->ID);?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                        </div>
                        <?php echo k2_post_view($post->ID); ?>
                    </div>
                </div>
            </article>

        <?php } else { ?>

            <article class="grid-item<?php if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php echo get_permalink($post->ID); ?>" data-article-id="<?php echo $post->ID; ?>">
                <figure>
                    <a href="<?php echo get_permalink($post->ID); ?>">
                        <img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $medium_image; ?>" data-src-retina="<?php echo $large_image; ?>" alt="<?php echo get_the_title($post->ID); ?>" />
                        <?php if(!empty($app_thumb)) { echo '<div><div><img src="' . $app_thumb . '" alt="' . $item['title'] . '" /></div></div>'; } ?>
                    </a>
                </figure>
                <div class="grid-item-body">
                    <header>
                        <span class="grid-item-section hide-mobile"><?php echo $cat_link; ?></span>
                        <h3><a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h3>
                    </header>
                    <div class="grid-item-meta hide-mobile clearfix">
                        <div class="grid-item-share">
                            <span class="icon-k2-share"></span> Share
                        </div>
                        <div class="grid-item-share-icons hide-mobile">
                            <div class="st_email_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($post->ID); ?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($post->ID);?>" st_title="<?php echo get_the_title($post->ID); ?>"></div>
                        </div>
                        <?php echo k2_post_view($post->ID); ?>
                    </div>
                </div>
            </article>

        <?php }

        $i++;
        if($i > 29) { break; }

    }

    if($page >= 2) {
        die();
    }
}
add_action('k2_ajax_section_grid', 'section_grid'); // This handles the section grid ajax
add_action('k2_ajax_nopriv_section_grid', 'section_grid'); // This handles the section grid ajax for non-admin users

############
## Changing the admin menu order
############

function reorder_admin_menu() {
    global $menu;
    $mod_menu = array();

    $separator = admin_menu_items('separator2', $menu);
    $upload = admin_menu_items('upload.php', $menu);
    $comments = admin_menu_items('edit-comments.php', $menu);

    if($upload) {
        $mod_menu['upload'] = $menu[$upload];
        unset($menu[$upload]);
    }

    if($comments) {
        $mod_menu['comments'] = $menu[$comments];
        unset($menu[$comments]);
    }

    $position_menu = (int)$separator - count($mod_menu);

    foreach($mod_menu as $m) {
        $menu[$position_menu] = $m;
        $position_menu++;
    }
}
add_action('admin_menu', 'reorder_admin_menu', 9999); // Reorder the admin menu for custom post types

############
## Adding caps to contributors
############

function allow_contributor_uploads() {
    $contributor = get_role('contributor');
    $contributor->add_cap('upload_files');

}
add_action('admin_init', 'allow_contributor_uploads'); // Adding capabilities to user roles

############
## Allows you to search posts by ID (ex #123)
############

function k2_id_search($wp) {
    global $pagenow;

    // If it's not the post listing return
    if('edit.php' != $pagenow) {
        return;
    }

    // If it's not a search return
    if(!isset($wp->query_vars['s'])) {
        return;
    }

    // If it's a search but there's no prefix, return
    if('#' != substr($wp->query_vars['s'], 0, 1)) {
        return;
    }

    // Validate the numeric value
    $id = absint(substr($wp->query_vars['s'], 1));
    if(!$id) {
        return; // Return if no ID, absint returns 0 for invalid values
    }

    // If we reach here, all criteria is fulfilled, unset search and select by ID instead
    unset($wp->query_vars['s']);
    $wp->query_vars['p'] = $id;
}
add_action('parse_request', 'k2_id_search'); // Allows you to search posts by ID (ex #123)

############
## Custom dashboard activity widget
############

// unregister the default activity widget
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets() {

    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

}

// register your custom activity widget
add_action('wp_dashboard_setup', 'add_custom_dashboard_activity' );
function add_custom_dashboard_activity() {
    wp_add_dashboard_widget('custom_dashboard_activity', 'Activities', 'custom_wp_dashboard_site_activity');
}

// the new function based on wp_dashboard_recent_posts (in wp-admin/includes/dashboard.php)
function wp_dashboard_recent_post_types( $args ) {

    /* Changed from here */

    if ( ! $args['post_type'] ) {
        $args['post_type'] = 'any';
    }

    $query_args = array(
        'post_type'      => $args['post_type'],

        /* to here */

        'post_status'    => $args['status'],
        'orderby'        => 'date',
        'order'          => $args['order'],
        'posts_per_page' => intval( $args['max'] ),
        'no_found_rows'  => true,
        'cache_results'  => false
    );
    $posts = new WP_Query( $query_args );

    if ( $posts->have_posts() ) {

        echo '<div id="' . $args['id'] . '" class="activity-block">';

        if ( $posts->post_count > $args['display'] ) {
            echo '<small class="show-more hide-if-no-js"><a href="#">' . sprintf( __( 'See %s more'), $posts->post_count - intval( $args['display'] ) ) . '</a></small>';
        }

        echo '<h4>' . $args['title'] . '</h4>';

        echo '<ul>';

        $i = 0;
        $today    = date( 'Y-m-d', current_time( 'timestamp' ) );
        $tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );

        while ( $posts->have_posts() ) {
            $posts->the_post();

            $time = get_the_time( 'U' );
            if ( date( 'Y-m-d', $time ) == $today ) {
                $relative = __( 'Today' );
            } elseif ( date( 'Y-m-d', $time ) == $tomorrow ) {
                $relative = __( 'Tomorrow' );
            } else {
                /* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
                $relative = date_i18n( __( 'M jS' ), $time );
            }

            $text = sprintf(
            /* translators: 1: relative date, 2: time, 4: post title */
                __( '<span>%1$s, %2$s</span> <a href="%3$s">%4$s</a>' ),
                $relative,
                get_the_time(),
                get_edit_post_link(),
                _draft_or_post_title()
            );

            $hidden = $i >= $args['display'] ? ' class="hidden"' : '';
            echo "<li{$hidden}>$text</li>";
            $i++;
        }

        echo '</ul>';
        echo '</div>';

    } else {
        return false;
    }

    wp_reset_postdata();

    return true;
}

// The replacement widget
function custom_wp_dashboard_site_activity() {

    echo '<div id="activity-widget">';

    $future_posts = wp_dashboard_recent_post_types( array(
        'post_type'  => 'any',
        'display' => 3,
        'max'     => 7,
        'status'  => 'future',
        'order'   => 'ASC',
        'title'   => __( 'Publishing Soon' ),
        'id'      => 'future-posts',
    ) );

    $recent_posts = wp_dashboard_recent_post_types( array(
        'post_type'  => 'any',
        'display' => 3,
        'max'     => 7,
        'status'  => 'publish',
        'order'   => 'DESC',
        'title'   => __( 'Recently Published' ),
        'id'      => 'published-posts',
    ) );

    $recent_comments = wp_dashboard_recent_comments( 10 );

    if ( !$future_posts && !$recent_posts && !$recent_comments ) {
        echo '<div class="no-activity">';
        echo '<p class="smiley"></p>';
        echo '<p>' . __( 'No activity yet!' ) . '</p>';
        echo '</div>';
    }

    echo '</div>';
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */

function obtain_add_meta_box() {
    $current_post_details	=	get_post( $post->ID, $output, $filter );
    $screens = array( 'apps','happening_now','columns','cool_sites','downloads','tips','buying_guides','charts','newsletters','previous_shows','new_technologies','qotd','small_business');
    if($current_post_details->post_status=='draft' || $current_post_details->post_status=='pending') :
        foreach ( $screens as $screen ) {
            add_meta_box(
                'obtainURL',
                __( 'Post URL', '' ),
                'obtain_url_meta_box_callback',
                $screen, 'side', 'high'
            );
        }
    endif;
}
add_action( 'add_meta_boxes', 'obtain_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */

function obtain_url_meta_box_callback( $post ) {
    wp_nonce_field( 'obtain_add_meta_box', 'obtain_add_meta_box_nonce' );
    $ist_cpt_array	=	get_sample_permalink_every_post($post->ID);
    $permalink_draft	  = 	$ist_cpt_array[0].$ist_cpt_array[1];
    $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
    if($post->post_status=='draft' || $post->post_status=='pending') :
        if ( 'page' != get_post_type()  ) :
            echo '<textarea rows="10" cols="33">' . $permalink_draft . ' </textarea>';
        endif;
    endif;
}

############
## Hide the toolbar from the frontend of the site
############

function k2_remove_admin_bar() {
    show_admin_bar(false);
}
add_action('after_setup_theme', 'k2_remove_admin_bar'); // Hide the toolbar from the frontend of the site

