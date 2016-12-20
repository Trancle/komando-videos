<?php 
/*
Template Name: Search
*/
$search_query = stripslashes(htmlentities($_GET["s"]));

function spanOrder($sort, $order, $thisSpan) {
    $search_query = stripslashes(htmlentities($_GET["s"]));
    
    if ($sort.$order==$thisSpan) {
        return '<span/>';
    }
    return '';
}

function k2_search_display_podcast_show($content){
    $content = (object) $content;
    $content->id = intval(str_replace("podcast-show-", "", $content->id));

    if(class_exists("\K2\Podcast\Show")){
        $podcast = \K2\Podcast\Show::new_from_rss($content->id);
    }
    else{
        return "";
    }

    try{
        $title = $podcast->get_title();
        $image = $podcast->get_logo();
        $description = $podcast->get_description();
        $permalink = $content->permalink . "/" . \K2\Podcast\Helper\Utils::urlize($title);
    }
    catch(\K2\Podcast\PodcastRssLookupException $p){
        return "";
    }

    echo <<<EOF

<div class="search-result clearfix" data-article-url="$permalink" data-article-id="$content->id">

                <a href="$permalink" class="search-image">
                <img src="$image" />
                </a>

                <div class="search-results-text">
        		    <h3><a href="$permalink">$title</a></h3>

                    $description
                    <div class="result-meta">
                        <span class="search-result-post-type">
                            <a href="http://www.komando.com/listen/podcast-directory">View more podcasts...</a></span>
                    </div>
                </div>

</div>
EOF;

}

function k2_search_display_podcast_episode($content){
    $content = (object) $content;
    $content->id = intval(str_replace("podcast-episode-", "", $content->id));

    if(class_exists("\K2\Podcast\Episode")){
        $episode = \K2\Podcast\Episode::get_episode_by_id($content->id);
    }
    else{
        return "";
    }

    if(is_null($episode)){
        return "";
    }

    try{
        $title = $episode->get_title();
        $image = $episode->get_image();
        $description = $episode->get_description();
        $pub_date = date('F j, Y', strtotime($episode->get_pub_date()));
        $permalink = $content->permalink . "/" . \K2\Podcast\Helper\Utils::urlize($title);
        $parent_show = $episode->get_show();
        $show_title = $parent_show->get_title();
        $show_permalink = "http://www.komando.com/listen/show/" . $episode->get_show_id() . "/" . \K2\Podcast\Helper\Utils::urlize($show_title);
    }
    catch(\K2\Podcast\PodcastRssLookupException $p){
        return "";
    }

    echo <<<EOF

<div class="search-result clearfix" data-article-url="$permalink" data-article-id="$content->id">

                <a href="$permalink" class="search-image">
                <img src="$image" />
                </a>

                <div class="search-results-text">
        		    <h3><a href="$permalink">$title</a></h3>

                    $description
                    <div class="result-meta">
                        <span class="search-result-post-type">
                            <a href="$show_permalink">$show_title</a>
                        </span>
                        <span class="search-result-date">$pub_date</span>
                    </div>
                </div>

</div>
EOF;

}


get_header(); ?>

    <div class="content-left">

        <?php 
        $results = mss_search_results();
        if(!empty($results)) {

            if($results['dym']) { ?>
                <div class="solr_suggest">Did you mean: <a href="<?php echo $results['dym']['link'] ?>"><?php $results['dym']['term'] ?></a>?</div>
            <?php
            } 

            if ($results['hits'] && $results['query']) {
                if ($results['firstresult'] === $results['lastresult']) { ?>
                    <form method="get" action="<?php echo home_url(); ?>" role="search"><input type="text" name="s" class="search-results-input" placeholder="Search Komando.com..." value="<?php echo $search_query; ?>" /><button type="submit" class="search-results-button">Search</button></form>
                    Displaying result <?php echo $results['firstresult'] ?> of <span id="resultcnt"><?php echo $results['hits'] ?></span>
                <?php } else { ?>
                    <form method="get" action="<?php echo home_url(); ?>" role="search"><input type="text" name="s" class="search-results-input" placeholder="Search Komando.com..." value="<?php echo $search_query; ?>" /><button type="submit" class="search-results-button">Search</button></form>
                    Displaying results <?php echo $results['firstresult'] ?>-<?php echo $results['lastresult'] ?> of <span id="resultcnt"><?php echo $results['hits'] ?></span>
               <?php }
            } 

            if ($results['hits'] === "0") { ?>
                <form method="get" action="<?php echo home_url(); ?>" role="search"><input type="text" name="s" class="search-results-input" placeholder="Search Komando.com..." value="<?php echo $search_query; ?>" /><button type="submit" class="search-results-button">Search</button></form>
            <?php 
            } 
            $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'score';
            $order = (isset($_GET['order'])) ? $_GET['order'] : 'desc';
            ?>
                        
            <div class="search-options clearfix">
                <div class="solr-sort-wrapper clearfix">
                    <div class="solr-sort">
                        <ul>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&sort=score&order=desc<?php if($_GET['fq']) { echo '&fq=' . $_GET['fq']; } ?>" <?php if($_GET['sort'] == 'score' || empty($_GET['sort'])) { echo 'class="active"'; } ?>>Relevance</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&sort=date&order=desc<?php if($_GET['fq']) { echo '&fq=' . $_GET['fq']; } ?>" <?php if($_GET['sort'] == 'date' && $_GET['order'] == 'desc') { echo 'class="active"'; } ?>>Newest</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&sort=date&order=asc<?php if($_GET['fq']) { echo '&fq=' . $_GET['fq']; } ?>" <?php if($_GET['sort'] == 'date' && $_GET['order'] == 'asc') { echo 'class="active"'; } ?>>Oldest</a></li>
                        </ul>
                    </div>
                </div>

                <div class="solr-filter-wrapper clearfix">
                    <span>Filter by: </span>
                    <div class="solr-filter-post-type">
                        <div class="solr-filter-selected"><div class="sfs-text">
                            <?php 
                            
                            switch ($_GET['fq']) {
                                case 'type:columns':
                                    echo 'Columns';
                                    $k2_fq = 'columns';
                                    break;

                                case 'type:downloads':
                                    echo 'Downloads';
                                    $k2_fq = 'downloads';
                                    break;

                                case 'type:apps':
                                    echo 'Apps';
                                    $k2_fq = 'apps';
                                    break;

                                case 'type:cool_sites':
                                    echo 'Cool Sites';
                                    $k2_fq = 'cool_sites';
                                    break;

                                case 'type:tips':
                                    echo 'Tips';
                                    $k2_fq = 'tips';
                                    break;

                                case 'type:buying_guides':
                                    echo 'Buying Guides';
                                    $k2_fq = 'buying_guides';
                                    break;

                                case 'type:charts':
                                    echo 'Charts';
                                    $k2_fq = 'charts';
                                    break;

                                case 'type:newsletters':
                                    echo 'Newsletters';
                                    $k2_fq = 'newsletters';
                                    break;

                                case 'type:previous_shows':
                                    echo 'Previous Shows';
                                    $k2_fq = 'previous_shows';
                                    break;

                                case 'type:happening_now':
                                    echo 'Happening Now';
                                    $k2_fq = 'happening_now';
                                    break;

                                case 'type:small_business':
                                    echo 'Small Business';
                                    $k2_fq = 'small_business';
                                    break;

                                case 'type:podcast_show':
                                    echo 'Podcast Show';
                                    $k2_fq = 'podcast_show';
                                    break;

                                case 'type:podcast_episode':
                                    echo 'Podcast Episode';
                                    $k2_fq = 'podcast_episode';
                                    break;

                                default:
                                    echo 'Everything';
                                    $k2_fq = 'everything';
                                    break;
                            }

                            ?></div> <i class="fa fa-angle-down"></i></div>
                        <ul>
                            <?php if($k2_fq != 'everything') { ?><li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?><?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Everything</a></li><?php } ?>
                            <li><a href="<?php echo VIDEOS_BASE_URI . '/search/find?q=' . $search_query; ?>">Videos</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:columns<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Columns</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:downloads<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Downloads</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:apps<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Apps</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:cool_sites<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Cool Sites</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:tips<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Tips</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:small_business<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Small Business</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:happening_now<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Happening Now</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:buying_guides<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Buying Guides</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:charts<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Charts</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:newsletters<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Newsletters</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:previous_shows<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Previous Shows</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:podcast_show<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Podcast Shows</a></li>
                            <li><a href="<?php echo bloginfo('url') . '?s=' . $search_query; ?>&fq=type:podcast_episode<?php if($_GET['sort']) { echo '&sort=' . $_GET['sort']; } if($_GET['order']) { echo '&order=' . $_GET['order']; } ?>">Podcast Episodes</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <script>
            jQuery(document).ready(function() {
                jQuery('.solr-filter-selected').on('click', function(event) {
                    jQuery('.solr-filter-post-type ul').slideToggle(200);
                    jQuery(this).toggleClass('dropped');
                    event.stopPropagation();
                })

                jQuery(document).on('click', function(event) {
                    if(!jQuery('.solr-filter-wrapper').is(event.target) && jQuery('.solr-filter-wrapper').has(event.target).length === 0) {
                        jQuery('.solr-filter-wrapper ul').slideUp(200);
                        jQuery('.solr-filter-selected').removeClass('dropped');
                    }
                })
            });
            </script>

            <hr />

            <?php if ($results['hits'] === "0") { ?>

                <h1>Sorry, no results were found.</h1>
                <p>Perhaps you misspelled your search query, or need to try using broader search terms.</p>

                <p>For example, instead of searching for "Apple iPhone 6", try something simple like "iPhone".</p>

                <h3>Did you get here after clicking on a Komando email newsletter link?</h3>

                <p>Please copy and paste the article title into the search box above to find the article you're looking for.</p>
            <?php } else { 

                foreach($results['results'] as $result) {
                    
                    $type = $result['type'];

                    if($type == "podcast_show"){
                        k2_search_display_podcast_show($result);
                        continue;
                    }
                    elseif($type == "podcast_episode"){
                        k2_search_display_podcast_episode($result);
                        continue;
                    }

                        $teaser = $result['teaser'];
                    $findthese = array('#<em>#', '#</em>#');
                    $replacewith = array('<span class="highlight">', '</span>');
                    $teaser = preg_replace($findthese, $replacewith, $teaser);
                    $image = get_the_post_thumbnail($result['id'], 'thumbnail');
                    $post_type_obj = get_post_type_object(get_post_type($result['id']));
                    $category = get_the_terms($result['id'], $result['type'] . '_categories');
                    $post_type_link = preg_replace('/[%](post_id)[%]/', '', get_post_type_archive_link($type));

                    $views = k2_post_view($result['id']);
                    ?>
                    <div class="search-result clearfix" data-article-url="<?php echo get_permalink($result['id']); ?>" data-article-id="<?php echo $result['id']; ?>">

                    <?php if(!empty($image)) { ?>
                        <a href="<?php echo get_permalink($result['id']); ?>" class="search-image">
                        <?php echo $image; ?>
                        </a>
                    <?php } ?>

                <div class="search-results-text">       
        		    <h3><a href="<?php echo get_permalink($result['id']); ?>"><?php echo get_the_title($result['id']);?></a></h3>

                    <?php
                    // Line below checks for a video from videos.komando.com (type=videos), and then creates a link from the permalink. The $result['thumb'] holds the video thumbnail url.
        		    if ($result['type']=="videos") {?><a href="<?php echo $result['permalink'];?>"><h3>Watch: <?php echo $result['title']?> @ Komando Video</h3></a><?php } ?>  
                   
                    <?php echo $teaser; ?>
        		    <div class="result-meta">
                        <?php
                        if(get_post_type($result['id']) != 'page') { ?>
                            <span class="search-result-post-type">
                            <?php
                                echo '<a href="' . $post_type_link . '">' . $post_type_obj->labels->name . '</a>';
                                if( is_array($category) && !empty($category[0]) && $post_type_obj->labels->name) {
                                    echo ': <a href="' . get_term_link($category[0]->term_id, $result['type'] . '_categories') . '">' . $category[0]->name . '</a>';
                                } ?>
                            </span>
                        <?php } ?>
                        <?php if(!empty($views)) { echo '<span class="search-result-views hide-mobile">' . $views . '</span>'; } ?>
                        <span class="search-result-date"><?php echo date('F j, Y', strtotime($result['date'])) ?></span>
                    </div>
                </div>

		    </div>

                <?php 
                } 
            } ?>

        <?php

        if(isset($_GET['sort'])) {
            $sort = '&sort=' . $_GET['sort'];
        } else {
            $sort = '';
        }

        if(isset($_GET['order'])) {
            $order = '&order=' . $_GET['order'];
        } else {
            $order = '';
        }        

        if ($results['pager'] && $results['hits'] != "0" && $results['hits'] > 20) {

            echo '<div class="article-pager"><div class="btn-group">';
            $pages = count($results['pager']);
            $page_data = $results['pager'];

            $range = 2;
            $showitems = ($range * 2) + 1;
            if(isset($_GET['offset']) && isset($_GET['count'])) {
                $cur_page = ($_GET['offset'] / $_GET['count']) + 1;
                if ($cur_page == 0) {
                    $cur_page = 1;
                }
            } else {
                $cur_page = ($_GET['offset'] / 20) + 1;
            }

            $next_page = $cur_page;
            $prev_page = $cur_page - 2;
            $last_page = $pages - 1;

            if($pages != 1) {
                if($cur_page > 2 && $cur_page > $range + 1 && $showitems < $pages) echo '<a href="' . $page_data[0]['link'] . $sort . $order . '" class="btn"><i class="fa fa-angle-double-left"></i> First</a>';
                if($cur_page > 1) echo '<a href="' . $page_data[$prev_page]['link'] . $sort . $order . '" class="btn"><i class="fa fa-angle-left"></i> Previous</a>';
                
                for($i = $cur_page - $range; $i <= $cur_page + $range; $i++) {
                    if($i >= 1 && $i < $pages + 1) {
                        $adata = $i - 1;
                        echo ($cur_page == $i)? '<a href="' . $page_data[$adata]['link'] . $sort . $order . '" class="btn disabled active" disabled="disabled">' . $i . '</a>' : '<a href="' . $page_data[$adata]['link'] . $sort . $order . '" class="btn">' . $i . '</a>';
                    }
                }

                var_dump($cur_page);
                var_dump($pages);
                var_dump($showitems);

                if($cur_page < $pages && $showitems > $pages) echo '<a href="' . $page_data[$next_page]['link'] . $sort . $order . '" class="btn">Next <i class="fa fa-angle-right"></i></a>';  
                if($cur_page < $pages - 1 && $cur_page + $range - 1 < $pages && $showitems < $pages) echo '<a href="' . $page_data[$last_page]['link'] . $sort . $order . '" class="btn">Last <i class="fa fa-angle-double-right"></i></a>';
            }

            echo '</div></div>';


        } } else { ?>
        <h1>Search is temporarily unavailable.</h1>
        <h3>Please try your search again in a few minutes.</h3>
        <?php } ?>
    </div>

    <?php get_sidebar(); ?>


<?php get_footer(); ?>