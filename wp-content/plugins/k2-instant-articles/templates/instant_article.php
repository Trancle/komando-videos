<?php
$options = (array) get_post_meta($post->ID, "_instant_article_options", true);

// If the constant "DEV_IA" is set, the date is always set to current time, to trick the facebook IA system for debugging

if(defined("DEV_IA")){
    $published_date = date("c");
    $modified_date = date("c");
    $permalink = add_query_arg(array("feedbreaker" => time()), get_the_permalink());
} else {
    $published_date = get_the_date("c");
    $modified_date = get_the_modified_date("c");
    $permalink = get_the_permalink();
}
?>
<html lang="en" prefix="op: http://media.facebook.com/op#">
<head>
    <meta charset="utf-8">
    <link rel="canonical" href="<?php echo $permalink; ?>">
    <link rel="stylesheet" title="default" href="#">
    <meta property="og:title" content="<?php the_title(); ?>">
    <meta property="op:markup_version" content="v1.0">
    <meta property="fb:use_automatic_ad_placement" content="false">
    <title><?php the_title(); ?></title>

</head>
<body>
<article>
    <header>
        <!-- The header image shown inside your article -->
        <?php if(has_post_thumbnail($post->ID)):
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
            $attachment = get_post(get_post_thumbnail_id($post->ID));
            $thumbnail_url = $thumb[0];

            if(!empty($thumb) && $attachment):
            ?>
                <!-- The cover image shown inside your article -->
                <figure data-mode=aspect-fit>
                    <img src="<?php echo $thumbnail_url; ?>" />
                    <?php if(!empty($attachment->post_excerpt)): ?>
                        <figcaption><?php echo apply_filters("the_content", $attachment->post_excerpt); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; endif; ?>


        <!-- The title and subtitle shown in your article -->
        <h1><?php the_title(); ?></h1>

        <!-- A kicker for your article -->
        <?php
        $categories = get_the_category();
        if(is_array($categories) && count($categories) > 0):
            ?>
            <h3 class="op-kicker">
                <?php
                $the_category = array_pop($categories);
                echo $the_category->name;
                ?>
            </h3>
            <?php
        endif;
        ?>

         <!-- The authors of your article -->
        <?php if(get_the_author_meta('facebook')): ?>
            <address>
                <a rel="facebook" href="<?php the_author_meta('facebook'); ?>"><?php the_author(); ?></a>
                <?php the_author_meta('description'); ?>
            </address>
        <?php else: ?>
            <address>
                <a><?php the_author(); ?></a>
                <?php the_author_meta('description'); ?>
            </address>
        <?php endif; ?>


        <!-- The date and time when your article was originally published -->
        <time class="op-published" datetime="<?php echo $published_date; ?>"><?php echo get_the_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>

        <!-- The date and time when your article was last updated -->
        <time class="op-modified" datetime="<?php echo $modified_date; ?>"><?php echo get_the_modified_date(get_option('date_format') . ", " . get_option('time_format')); ?></time>




        <?php do_action( 'k2ia_article_header' ); ?>
    </header>

    <!-- Article body goes here -->
    <?php
    $the_content = get_post($post->ID); //get_the_content( '' );
    $the_content = $the_content->post_content;
    $multipage_post = false;
    if(strpos($the_content, '[nextpage]') !== false){
        $multipage_post = true;
        $end_position = strpos($the_content, '[nextpage]');
        $the_content = substr($the_content, 0, $end_position);
    }

    echo apply_filters('k2ia_content', apply_filters('the_content', $the_content));
    if($multipage_post){
        echo '<h1><a href="' . $permalink . '?page=2">Click to keep reading &raquo;</a></h1>';
    }
    ?>

    <footer>

        <?php if(get_option('k2ia_copyright')): ?>
            <!-- Copyright details for your article -->
            <small><?php echo esc_attr(get_option('k2ia_copyright')); ?></small>
        <?php endif; ?>
    </footer>

</article>


</body>
</html>
