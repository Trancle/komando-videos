<?php
/**
 * Template Name: Scared Shitless podcast
 * Created by PhpStorm.
 * User: dominguez
 * Date: 4/25/2016
 * Time: 2:52 PM
 */
global $inject_above_body_class_div;

$inject_above_body_class_div = k2_podcast_scared_shitless_latest();

get_header(); ?>

<style>
    .wrapper.page {
        border-top: none;
        margin: 0 auto 0 auto;
        padding-top: 20px;
    }
</style>
<div class="on-demand-main-column clearfix">

    <?php
    // Shows page text and images below all podcast listings
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            global $wp_query;

            $id = get_the_ID();
            $image_id = get_post_thumbnail_id();
            $image = wp_get_attachment_image_src($image_id, 'medium');
            $post_info = get_post_type($id);
            $post_data = get_post_type_object($post_info);

            // finds the last URL segment
            $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $segments = explode('/', $urlArray);
            $numSegments = count($segments);
            $currentSegment = $segments[$numSegments - 1];

            if ($currentSegment == 'all') {
                $no_pagination = 'yes';
            } else {
                $no_pagination = 'no';
            }

            if ($no_pagination == 'yes') {
                echo apply_filters('the_content', $post->post_content);
                $lastpage = 'yes';
                $k2_paginate = 'no';
            } else {
                the_content();
                if ($page == $numpages) {
                    $lastpage = 'yes';
                } else {
                    $lastpage = 'no';
                }
                $k2_paginate = 'yes';
            };

            $link_pages = custom_link_pages(array(
                'before' => '<div class="article-pager"><div class="btn-group">',
                'after' => '</div><a href="' . get_permalink() . '/all" id="all-button">All</a></div>',
                'next_or_number' => 'next_and_number',
                'nextpagelink' => 'Next <i class="icon-angle-right"></i>',
                'previouspagelink' => '<i class="icon-angle-left"></i> Previous',
                'pagelink' => '%',
                'echo' => FALSE
            ));

            if (!empty($link_pages) && $k2_paginate == 'yes') {
                $doc = new DOMDocument();
                $doc->loadHTML($link_pages);
                $links = $doc->getElementsByTagName('a');
                foreach ($links as $link) {
                    $link->setAttribute('class', 'btn');
                }
                $html = $doc->saveHTML();

                echo $html;
            }
        }
    }
    ?>

</div>

<div class="two-double-ads">
    <?php echo show_triple_ads(); ?>
</div>

<div class="show-sidebar">
    <?php echo show_on_demand_sidebar();?>
</div>

<?php get_footer(); ?>