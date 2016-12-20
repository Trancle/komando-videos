<?php
    /**
     * Sidebar for page-on-demand
     * Created by Tyger Gilbert
     * Date: 1/8/2016
     * Time: 1:20 PM
     */
?>

    <div class="grid">

    <?php
    // List the days content
    $today = getdate();

    $args = array(
        'year' => $today["year"],
        'monthnum' => $today["mon"],
        'day' => $today["mday"],
        'ignore_sticky_posts' => 1,
        'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business')
    );

    $my_query = new WP_Query($args);

    $i = 1;
    if($my_query->have_posts()) {
        echo '<h2 class="arrow">Today\'s Content</h2>';

        while($my_query->have_posts()) {

            $my_query->the_post();
            $id = get_the_ID();
            $post_type = get_post_type($id);
            $post_data = get_post_type_object($post_type);
            $sidebar_image_id = get_post_thumbnail_id();
            $sidebar_image = wp_get_attachment_image_src($sidebar_image_id, 'medium')[0];
            if (empty($sidebar_image)) {
                $sidebar_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
            }

            $app_thumb = MultiPostThumbnails::get_post_thumbnail_url($post_type, 'app-icon', $id, 'app-icon');
            ?>
            <article class="grid-item<?php if ($post_type == 'viral_video') {
                echo ' video';
            }
                if (!empty($app_thumb)) {
                    echo ' app';
                } ?>" data-article-url="<?php the_permalink(); ?>" data-article-id="<?php echo $id; ?>">
                <figure>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img
                            src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>"
                            data-src="<?php echo $sidebar_image; ?>" alt="<?php the_title_attribute(); ?>"/></a>
                    <?php if ($post_type == 'viral_video') {
                        echo '<div><img src="' . k2_get_static_url('v2') . '/img/play-icon-circle.png" alt="Play" /></div>';
                    }
                        if (!empty($app_thumb)) {
                            echo '<div><div><img src="' . $app_thumb . '" alt="' . the_title_attribute() . '" /></div></div>';
                        } ?>
                </figure>
                <div class="grid-item-body">
                    <header>
                        <span
                            class="grid-item-section hide-mobile"><?php echo $post_data->labels->singular_name; ?></span>

                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </header>
                    <div class="grid-item-meta hide-mobile clearfix">
                        <div class="grid-item-share">
                            <span class="icon-k2-share"></span> Share
                        </div>
                        <div class="grid-item-share-icons hide-mobile">
                            <div class="st_email_custom share-button" st_url="<?php the_permalink(); ?>"
                                 st_title="<?php the_title(); ?>"></div>
                            <div class="st_facebook_custom share-button" st_url="<?php the_permalink(); ?>"
                                 st_title="<?php the_title(); ?>"></div>
                            <div class="st_twitter_custom share-button" st_url="<?php the_permalink(); ?>"
                                 st_title="<?php the_title(); ?>"></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php the_permalink(); ?>"
                                 st_title="<?php the_title(); ?>"></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php the_permalink(); ?>"
                                 st_title="<?php the_title(); ?>"></div>
                        </div>
                        <?php echo k2_post_view($id); ?>
                    </div>
                </div>
            </article>

            <?php $i++;
        }
    }
    wp_reset_query();
    ?>

    </div>
