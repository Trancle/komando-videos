<?php
/**
 * Created by PhpStorm.
 * functions-ads-images.php
 * Function definitions which affect ads or images
 * User: gilbert
 * Date: 5/18/2015
 * Time: 1:36 PM
 */

############
## Theme support
############

if (class_exists('MultiPostThumbnails')) {
    $types = array('apps');
    foreach($types as $type) {
        new MultiPostThumbnails(array(
                'label' => 'App Icon',
                'id' => 'app-icon',
                'post_type' => $type
            )
        );
    }
}

if (function_exists('add_theme_support')) {
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 970, 546, true); // Large Thumbnail
    add_image_size('medium', 520, 293, true); // Medium Thumbnail
    add_image_size('thumbnail', 310, 174, true); // Small Thumbnail
    add_image_size('small', 130, 73, true); // Sidebar Thumbnail
    add_image_size('app-icon', 100, 100); // App Icon
    add_image_size('small-square-thumbnail', 148, 148, true); // Square for RSS thumb - DigitalTrends request

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');
}

############
## Featured area for sections
############

function section_featured($section) {
    wp_reset_query();

    $k2_section_ads = get_option('k2_section_ads');
    foreach ($k2_section_ads as $key => $section_ad) {
        if ($section_ad['section'] == $section) {
            $k2_section_ad = $section_ad;
        }
    }

    if($k2_section_ad['active']) {

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
    $i = 1;

    if($k2_section_ad['active'] == 1) { ?>

        <article class="grid-item grid-ad featured grid-ad-featured" data-article-url="<?php echo $k2_section_ad['link']; ?>">
            <figure>
                <a href="<?php echo $k2_section_ad['link']; ?>" target="_blank" rel="nofollow">
                    <img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $k2_section_ad['image']; ?>" data-src-retina="<?php echo $k2_section_ad['image']; ?>" alt="<?php echo $k2_section_ad['text']; ?>" />
                </a>
            </figure>
            <div class="grid-item-body">
                <header>
                    <span class="grid-item-section">Sponsor</span>
                    <h3><a href="<?php echo $k2_section_ad['link']; ?>" target="_blank" rel="nofollow"><?php echo $k2_section_ad['text']; ?></a></h3>
                </header>
            </div>
        </article>
        <script type="text/javascript">
            jQuery('document').ready(function() {
                ga('set', 'Section Featured Ad - <?php echo $section; ?>', '<?php echo $k2_section_ad['advertiser']; ?>');
            });
        </script>

        <?php $i++; }

    foreach ($my_query->posts as $post) {

        $image_id = get_post_thumbnail_id( $post->ID );
        $placeholder_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
        $large_image = wp_get_attachment_image_src($image_id, 'large')[0];
        $medium_image = wp_get_attachment_image_src($image_id, 'medium')[0];
        if(empty($large_image) || wp_get_attachment_image_src($image_id, 'large')[1] < 970) {
            $large_image = $placeholder_image;
        }

        if(empty($medium_image) || wp_get_attachment_image_src($image_id, 'large')[1] < 520) {
            $medium_image = $placeholder_image;
        }

        $url = get_permalink($post->ID);

        $app_thumb = MultiPostThumbnails::get_post_thumbnail_url($section, 'app-icon', $post->ID, 'app-icon');

        $category = get_the_terms($post->ID, $section . '_categories');

        if ($category) {
            $key = current(array_keys($category));
            $cat_link = '<a href="' . get_term_link($category[$key]->slug, $category[$key]->taxonomy) . '">' . $category[$key]->name . '</a>';
        } else {
            $cat_link = '';
        }

        ?>

        <article class="grid-item<?php if(!empty($app_thumb)) { echo ' app'; } if($i == 1) { echo ' featured'; } ?>" data-article-url="<?php echo $url; ?>" data-article-id="<?php echo $post->ID; ?>">
            <figure>
                <a href="<?php echo $url; ?>">
                    <img src="<?php echo $placeholder_image; ?>" data-src="<?php if($i == 1) { echo $large_image; } else { echo $medium_image; } ?>" data-src-retina="<?php echo $large_image; ?>" alt="<?php echo $post->post_title; ?>" />
                    <?php if(!empty($app_thumb)) { echo '<div><img src="' . $app_thumb . '" alt="' . $post->post_title . '" /></div>'; } ?>
                </a>
            </figure>
            <div class="grid-item-body">
                <header>
                    <span class="grid-item-section hide-mobile"><?php echo $cat_link; ?></span>
                    <h3><a href="<?php echo $url; ?>"><?php echo $post->post_title; ?></a></h3>
                </header>
                <div class="grid-item-meta hide-mobile clearfix">
                    <div class="grid-item-share">
                        <span class="icon-k2-share"></span> Share
                    </div>
                    <div class="grid-item-share-icons hide-mobile">
                        <div class="st_email_custom share-button" st_url="<?php echo $url; ?>" st_title="<?php echo $post->post_title; ?>"></div>
                        <div class="st_facebook_custom share-button" st_url="<?php echo $url; ?>" st_title="<?php echo $post->post_title; ?>"></div>
                        <div class="st_twitter_custom share-button" st_url="<?php echo $url; ?>" st_title="<?php echo $post->post_title; ?>"></div>
                        <div class="st_googleplus_custom share-button" st_url="<?php echo $url; ?>" st_title="<?php echo $post->post_title; ?>"></div>
                        <div class="st_pinterest_custom share-button" st_url="<?php echo $url;?>" st_title="<?php echo $post->post_title; ?>"></div>
                    </div>
                    <?php echo k2_post_view($post->ID); ?>
                </div>
            </div>
        </article>

        <?php
        if ($i++ > 10) break; }

    wp_reset_query();
}

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
remove_filter('the_content', 'wptexturize'); // Gets rid of smart quotes and stuff
remove_filter('the_title', 'wptexturize'); // Gets rid of smart quotes and stuff
remove_filter('the_excerpt', 'wptexturize'); // Gets rid of smart quotes and stuff
remove_filter('comment_text', 'wptexturize'); // Gets rid of smart quotes and stuff

// Add Filters
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)

function k2_og_image($id) {
    $post_thumbnail_id = get_post_thumbnail_id($id);
    $image_array = wp_get_attachment_image_src($post_thumbnail_id, 'large');
    if(empty($image_array)) {
        $image = k2_get_static_url('v2') . '/img/placeholder-image.png';
    } else {
        $image = $image_array[0];
    }

    return $image;
}

############
## Throws an error if the featured image is the wrong size
############

function k2_check_featured_image_size($content) {
    global $post;

    $attach_id = get_post_thumbnail_id($post->ID);

    if(empty($attach_id)) {
        return $content . '(Minimum 970x546)';
    }

    $attach_id_width = wp_get_attachment_image_src($attach_id, 'large')[1];
    $attach_id_height = wp_get_attachment_image_src($attach_id, 'large')[2];

    if(empty($attach_id_width)) { $attach_id_width = 0; }
    if(empty($attach_id_height)) { $attach_id_height = 0; }

    if ($attach_id_width < 970 || $attach_id_height < 546) {
        return '<div id="notice" class="error"><p>The featured image must be at least 970x546. The one selected is ' . $attach_id_width . 'x' . $attach_id_height . '.</p></div>' . $content . '(Minimum 970x546)';
    } else {
        return $content . '(Minimum 970x546)';
    }
}
add_filter('admin_post_thumbnail_html', 'k2_check_featured_image_size');

############
## Wrap images in figure tags
############

function k2_insert_image($html, $id, $caption, $title, $align, $url, $size, $alt) {

    $url = wp_get_attachment_url($id);
    $img = '<img src="' . $url . '" alt="' . $alt . '" data-align="' . $align . '" data-attachment="' . $id . '" />';

    return $img;
}
add_filter('image_send_to_editor', 'k2_insert_image', 10, 9);

function k2_wrap_images($content) {
    global $post;

    if(!is_page() && $post->post_type != 'charts') {

        preg_match_all('/(<figure.*?><img.*?\/><\/figure>)/', $content, $figures);
        if(!empty($figures)) {

            foreach($figures[1] as $index => $value) {

                preg_match('/src="(.*?)"/', $value, $src);
                preg_match('/data-attachment="(.*?)"/', $value, $image_id);
                preg_match('/class="(.*?)"/', $value, $align);

                $new_image = '<img src="' . $src[1] . '" data-align="' . $align[1] . '" data-attachment="' . $image_id[1] . '" />';

                $content = str_replace($figures[0][$index], $new_image, $content);
            }
        }

        preg_match_all('/<img (.*?)\/>/', $content, $images);
        if(!empty($images)) {

            foreach($images[1] as $index => $value) {

                preg_match('/src="(.*?)"/', $value, $src);
                preg_match('/data-attachment="(.*?)"/', $value, $image_id);
                preg_match('/data-align="(.*?)"/', $value, $align);
                preg_match('/alt="(.*?)"/', $value, $alt);

                $new_image = '<figure class="' . $align[1] . '" data-attachment="' . $image_id[1] . '"><img src="' . $src[1] . '" alt="' . $alt[1] . '" /></figure>';

                $content = str_replace($images[0][$index], $new_image, $content);
                
                $regex_extract_image = '/<a (.*?)><figure (.*?)>(.*?)<\/figure><\/a>/';
                preg_match_all($regex_extract_image, $content, $links);
                if(!empty($links)){
                    $new_a_image = '<a '.$links[1][0].' class="figure-link" '.$links[2][0].'>'.$links[3][0].'</a>';
                    $content = str_replace($links[0][0], $new_a_image, $content);
                }
            }
        }
    }
    return $content;
}
add_filter('the_content', 'k2_wrap_images', 99999);

############
## Change the URL for images to the CDN
############

function k2_change_attachment_url($f, $id, $size) {

    // This is pretty much just a replication of the image_downsize function from wp-includes/media.php

    $img_url = wp_get_attachment_url($id);
    $meta = wp_get_attachment_metadata($id);
    $width = $height = 0;
    $is_intermediate = false;
    $img_url_basename = wp_basename($img_url);

    // try for a new style intermediate size
    if ($intermediate = image_get_intermediate_size($id, $size)) {
        $img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
        $width = $intermediate['width'];
        $height = $intermediate['height'];
        $is_intermediate = true;

    } elseif ($size == 'thumbnail') {
        // fall back to the old thumbnail
        if (($thumb_file = wp_get_attachment_thumb_file($id)) && $info = getimagesize($thumb_file)) {
            $img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
            $width = $info[0];
            $height = $info[1];
            $is_intermediate = true;
        }
    }

    if (!$width && !$height && isset( $meta['width'], $meta['height'])) {
        // any other type: use the real image
        $width = $meta['width'];
        $height = $meta['height'];
    }

    if ($img_url) {
        // we have the actual image size, but might need to further constrain it if content_width is narrower
        list($width, $height) = image_constrain_size_for_editor($width, $height, $size);

        $img_url = k2_get_static_image_url($img_url);

        return array($img_url, $width, $height, $is_intermediate);
    }

    return false;
}
add_filter('image_downsize', 'k2_change_attachment_url', 10, 3);

function k2_get_static_image_url($img_url){
    $img_url = str_replace(array('http://', 'https://'), array(''), $img_url);
    $img_url = str_replace(array('www-dev3.komando.com', 'www-stg1.komando.com', 'www.komando.com', 'localhost.komando.com', 'www-stg02.komando.com'), array(''), $img_url);

    if(!is_ssl() && SERVER_ENVIRONMENT == 'production' && SKIP_ACCELERATOR == false) {
        $img_url = 'http://static-assets.komando.com' . $img_url;
    } else {
        $img_url = get_bloginfo('url') . $img_url;
    }
    return $img_url;
}
