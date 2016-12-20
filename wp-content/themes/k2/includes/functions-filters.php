<?php
/**
 * functions-filters.php
 * Function definitions which are used with add_filter()
 * Created by PhpStorm.
 * User: gilbert
 * Date: 5/15/2015
 * Time: 9:28 AM
 */

############
## Disabling trackbacks
## This also prevents the site from DDOSing other sites
## More info here: http://blog.sucuri.net/2014/03/more-than-162000-wordpress-sites-used-for-distributed-denial-of-service-attack.html
############

function k2_unset_pingbacks($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    return $methods;
}
add_filter('xmlrpc_methods', 'k2_unset_pingbacks');

function k2_filter_headers($headers) {
    if(isset($headers['X-Pingback'])) {
        unset($headers['X-Pingback']);
    }
    return $headers;
}
add_filter('wp_headers', 'k2_filter_headers', 10, 1);

function k2_remove_trackback_rewrite($rules) {
    foreach($rules as $rule => $rewrite) {
        if(preg_match('/trackback\/\?\$$/i', $rule)) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}
add_filter('rewrite_rules_array', 'k2_remove_trackback_rewrite'); // Kills the rewrite rule

function k2_remove_bloginfo_pingback_url($output, $show) {
    if($show == 'pingback_url') {
        $output = '';
    }
    return $output;
}
add_filter('bloginfo_url', 'k2_remove_bloginfo_pingback_url', 10, 2); // Kill bloginfo('pingback_url')

// hijack options updating for XMLRPC
add_filter('pre_update_option_enable_xmlrpc', '__return_false');
add_filter('pre_option_enable_xmlrpc', '__return_zero');

function k2_kill_xmlrpc_for_pingback($action) {
    if('pingback.ping' === $action) {
        wp_die('Pingbacks are not supported', 'Not Allowed!', array( 'response' => 403 ));
    }
}
add_action('xmlrpc_call', 'k2_kill_xmlrpc_for_pingback'); // Disable XMLRPC call for pingbacks

############
## Change the WordPress cookie expiration
############

function k2_extend_cookie_expiration($expirein) {
    return 2592000;
}
add_filter('auth_cookie_expiration', 'k2_extend_cookie_expiration');

############
## Add div wrapper to oembeds for responsive goodness
############

function my_embed_oembed_html($html, $url, $attr, $post_id) {

    $parsed = parse_url($url);
    $domain = $parsed['host'];
    $domain = preg_replace('/^www\./', '', $domain);

    if($domain == 'youtube.com' || $domain == 'youtu.be' || $domain == 'vimeo.com') {
        return '<p><div class="video-embed">' . $html . '</div></p>';
    } else {
        return $html;
    }
}
add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);

############
## Remove the <div> surrounding the dynamic navigation to cleanup markup
############

function my_wp_nav_menu_args($args = '') {
    $args['container'] = false;
    return $args;
}
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation

############
## Remove invalid rel attribute values in the categorylist
############

function remove_category_rel_from_category_list($thelist) {
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute

############
## Add page slug to body class, love this - Credit: Starkers Wordpress Theme
############

function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }
    array_unshift($classes, 'default');
    return $classes;
}
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)

############
## Custom View Article link to Post
############

function k2_blank_view_article($more) {
    global $post;

    return '...';
}
add_filter('excerpt_more', 'k2_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts

############
## Compresses JPEG files
############

function k2_compress_jpeg($file) {

    // Lets make sure it's a jpeg first
    if($file['type'] == 'image/jpeg') {

        $file_location = $file['file'];

        // Grabbing the last 10 characters of the filename
        $file_path = pathinfo($file_location);
        $last_bit = substr($file_path['filename'], -10);

        if($last_bit != '-optimized') {
            exec('djpeg ' . $file_location . ' | cjpeg -quality 90 > ' . $file_path['dirname'] . '/' . $file_path['filename'] . '.temp');
            rename($file_path['dirname'] . '/' . $file_path['filename'] . '.temp', $file_location);
        }

        return $file;
    }

    return $file;
}

add_filter('wp_handle_upload', 'k2_compress_jpeg');

############
## Remove 'text/css' from our enqueued stylesheet
############

function k2_style_remove($tag) {
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}
add_filter('style_loader_tag', 'k2_style_remove'); // Remove 'text/css' from enqueued stylesheet

############
## Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
############

function remove_thumbnail_dimensions($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

############
## Removes the more anchor from links
############

function remove_more_jumper($link) {
    $offset = strpos($link, '#more-');
    if ($offset) { $end = strpos($link, '"',$offset); }
    if ($end) { $link = substr_replace($link, '', $offset, $end-$offset); }
    return $link;
}
add_filter('the_content_more_link', 'remove_more_jumper'); // Removes the more anchor from links

############
## Removes the words private or protected from the post title
############

function k2_remove_words_from_title($title) {

    $title = esc_attr($title);
    $findthese = array(
        '#Protected: #',
        '#Private: #'
    );

    $replacewith = array(
        '', // What to replace "Protected:" with
        '' // What to replace "Private:" with
    );

    $title = preg_replace($findthese, $replacewith, $title);
    return $title;
}
add_filter('the_title', 'k2_remove_words_from_title'); // Removes the words private or protected from the post title

############
## Allow same email addresses for registered users
############

function k2_skip_email_exist($user_email){
    define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
    return $user_email;
}
add_filter('pre_user_email', 'k2_skip_email_exist'); // Allow same email addresses for registered users

############
## Change the sort order in the admin side
############

function k2_set_post_order_in_admin($wp_query) {
    if (is_admin() && !isset($_GET['orderby'])) {
        $wp_query->set('orderby', 'id');
        $wp_query->set('order', 'DESC');
    }
}
add_filter('pre_get_posts', 'k2_set_post_order_in_admin');

############
## Sets dashboard columns option to 1
############

function k2_screen_layout_dashboard() {
    return 1;
}
add_filter('get_user_option_screen_layout_dashboard', 'k2_screen_layout_dashboard'); // Changes the dashboard to 1 column

add_action('admin_head-index.php', function() { ?>
    <style>
        .postbox-container {
            min-width: 100% !important;
        }

        .meta-box-sortables.ui-sortable.empty-container {
            display: none;
        }
    </style>
<?php });

############
## Customize the settings for TinyMCE
############

// This is loading the tinymce plugin for the next page shortcode
function k2_tinymce_next_page($plugin_array) {
    $plugin_array['k2nextpage'] = k2_get_static_url('v2') . '/tinymce/k2-next-page/k2-next-page.js';
    return $plugin_array;
}
add_filter('mce_external_plugins', 'k2_tinymce_next_page');

// This strips the shortcode from the content when its on the view all page
function k2_nextpage_strip($content) {
    // finds the last URL segment
    $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', $urlArray);
    $numSegments = count($segments);
    $currentSegment = $segments[$numSegments - 1];

    if(isset($_GET['page'])) {
        $preview_page = $_GET['page'];
    }

    if (($currentSegment == 'all' || $preview_page == 'all') OR (restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member')))) {
//    if ($currentSegment == 'all' || $preview_page == 'all') {
        $no_pagination = 'yes';
    } else {
        $no_pagination = 'no';
    }

    if($no_pagination == 'yes') {
        $content = preg_replace('/\[nextpage\](.*?)\[\/nextpage\]/', '', $content);
    }

    return $content;
}
add_filter('the_content', 'k2_nextpage_strip');

// Custom init of tinymce, including the custom plugins
function k2_customize_tinymce($in) {

    $in['remove_linebreaks'] = false; // Not sure if needed -kk
    $in['gecko_spellcheck'] = false; // Not sure if needed -kk
    $in['keep_styles'] = true;
    $in['accessibility_focus'] = true; // Not sure if needed -kk
    $in['tabfocus_elements'] = 'major-publishing-actions'; // Not sure if needed -kk
    $in['media_strict'] = false; // Not sure if needed -kk
    $in['paste_remove_styles'] = false; // Not sure if needed -kk
    $in['paste_remove_spans'] = false; // Not sure if needed -kk
    $in['paste_strip_class_attributes'] = 'all'; // Not sure if needed -kk
    $in['paste_word_valid_elements'] = 'strong,em,h1,h2'; // Not sure if needed -kk
    $in['extended_valid_elements'] = 'span[!class],figure[data-attachment|class|id],figcaption[style|class]'; // Not sure if needed -kk
    $in['paste_text_use_dialog'] = true; // Not sure if needed -kk
    $in['wpeditimage_disable_captions'] = true;
    $in['plugins'] = 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,k2nextpage';
    $in['wpautop'] = true; // Not sure if needed -kk
    $in['apply_source_formatting'] = false; // Not sure if needed -kk
    $in['toolbar1'] = 'formatselect,bold,italic,underline,|,bullist,numlist,blockquote,|,link,unlink,anchor,|,pastetext,removeformat,|,charmap,|,outdent,indent,|,undo,redo,|,k2nextpage,wp_more,|,spellchecker,|,wp_help,dfw';
    $in['toolbar2'] = '';
    $in['toolbar3'] = '';
    $in['toolbar4'] = '';
    return $in;
}
add_filter('tiny_mce_before_init', 'k2_customize_tinymce', 0, 1); // Customize the settings for TinyMCE

############
# Adding profile fields to check the member level and expiration date
############

function k2_add_profile_fields($profile_fields) {

    // Add new fields
    $profile_fields['cas_username'] = 'CAS Username';
    $profile_fields['membership_expiration'] = 'Membership Expiration';
    $profile_fields['account_creator'] = 'Account Creator';
    $profile_fields['edit_flow_order'] = 'Edit Flow Order';

    return $profile_fields;
}
add_filter('user_contactmethods', 'k2_add_profile_fields'); // Adding profile fields to check the member level and expiration date

############
## Fixes WordPress bug: https://core.trac.wordpress.org/ticket/27961
## Internal Ticket: https://projects.office.weststar.com/issues/1900
############

function replace_4byte_characters_callback( $match ) {
    return ( strlen( $match[0] ) < 4 ) ? $match[0] : '';
}

function replace_4byte_characters_27961( $output ) {
    return preg_replace_callback( '/./u', 'replace_4byte_characters_callback', $output );
}
add_filter( 'oembed_result', 'replace_4byte_characters_27961' );

############
## Fixes WordPress bug: http://core.trac.wordpress.org/ticket/15928
############

function fix_ssl_attachment_url($url) {

    if (is_ssl())
        $url = str_replace('http://', 'https://', $url);
    return $url;
}
add_filter('wp_get_attachment_url', 'fix_ssl_attachment_url'); // Fixes WordPress bug: http://core.trac.wordpress.org/ticket/15928
add_filter('wp_get_attachment_image_src', 'fix_ssl_attachment_url');

############
## Fixes the loop when using the default post_tag from posts: http://core.trac.wordpress.org/ticket/14589
############

function post_type_tags_fix($request) {
    if ( isset($request['tag']) && !isset($request['post_type']) )
        $request['post_type'] = 'any';
    return $request;
}
add_filter('request', 'post_type_tags_fix'); // Fixes the loop when using the default post_tag from posts

############
## Removes version param from any enqueued scripts
############

function k2_remove_ver_css_js( $src ) {
    if ( is_admin() ) {
        return $src;
    }

    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }

    return $src;
}

add_filter('style_loader_src', 'k2_remove_ver_css_js', 9999);
add_filter('script_loader_src', 'k2_remove_ver_css_js', 9999);

############
## Adds extra cron schedule times to the existing schedules
############

function k2_cron_add_schedules( $schedules ) {
    $schedules['quarterday'] = array(
        'interval' => 14400,
        'display' => __('Once every 4 hours')
    );
    $schedules['minutely'] = array(
        'interval' => 60,
        'display' => __('Once every 1 minute')
    );
    $schedules['fiver'] = array(
        'interval' => 300,
        'display' => __('Once every 5 minute')
    );
    return $schedules;
}
add_filter('cron_schedules', 'k2_cron_add_schedules'); // Adds extra cron schedule times to the existing schedules

############
## Disables WordPress theme check
############

function k2_hide_theme($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/themes/update-check'))
        return $r; // Not a theme update request. Bail immediately.
    $themes = unserialize($r['body']['themes']);
    unset($themes[get_option('template')]);
    unset($themes[get_option('stylesheet')]);
    $r['body']['themes'] = serialize($themes);
    return $r;
}
add_filter('http_request_args', 'k2_hide_theme', 5, 2); // Hiding the theme from WordPress update checks

############
## Add prev and next links to a numbered link list
############

function wp_link_pages_args_prevnext_add($args) {
    global $page, $numpages, $more, $pagenow;

    if (!$args['next_or_number'] == 'next_and_number')
        return $args;

    $args['next_or_number'] = 'number';
    if (!$more)
        return $args;

    if($page-1)
        $args['before'] .= k2_wp_link_page($page-1)
            . $args['link_before'] . $args['previouspagelink'] . $args['link_after'] . '</a>'
        ;

    if ($page<$numpages)
        $args['after'] = k2_wp_link_page($page+1)
            . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
            . $args['after']
        ;

    return $args;
}
add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add'); // Adding prev and next links and numbers to pagination in single

############
## Adding attribution fields to the media uploader
############

function k2_add_media_attribute($form_fields, $post) {
    $form_fields['credit'] = array(
        'label' => __('Author'),
        'input' => 'text',
        'value' => get_post_meta($post->ID, '_credit', true)
    );

    $form_fields['link'] = array(
        'label' => __('Author Link'),
        'input' => 'text',
        'value' => get_post_meta($post->ID, '_link', true),
    );

    $form_fields['html'] = array(
        'label' => __('Shutterstock (HTML)'),
        'input' => 'text',
        'value' => get_post_meta($post->ID, '_html', true),
    );

    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'k2_add_media_attribute', null, 2);

function k2_save_media_attribute($post, $attachment) {
    if (isset($attachment['credit'])) {
        update_post_meta($post['ID'], '_credit', $attachment['credit']);
        update_post_meta($post['ID'], '_link', $attachment['link']);
        update_post_meta($post['ID'], '_html', $attachment['html']);
    }

    return $post;
}
add_filter('attachment_fields_to_save', 'k2_save_media_attribute', null, 2);

function k2_the_author($cAuthorName) {

    if (!is_admin()) {
        global $post;
        if (get_post_type($post->ID) == 'columns') {
            return 'Kim Komando';
        }
        if ((get_the_date('Y-m-d') < AUTHOR_SWITCHOVER_DATE) OR (empty($cAuthorName))) {
            return 'Komando Staff';
        }
    }
    return $cAuthorName;

}
add_filter('the_author', 'k2_the_author', null, 2);

