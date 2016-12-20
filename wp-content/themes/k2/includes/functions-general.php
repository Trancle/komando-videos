<?php
/**
 * functions-general.php
 * Function definitions which are general or global in nature
 * Created by PhpStorm.
 * User: gilbert
 * Date: 5/15/2015
 * Time: 9:27 AM
 */

############
## Dump and die, makes debugging faster
############
function dd($val) {
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
    die();
}

############
## Register k2 Blank Navigation
############

function register_k2_menu() {
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => 'Header Menu', // Main Navigation
        'sidebar-menu' => 'Sidebar Menu', // Sidebar Navigation
        'extra-menu' => 'Extra Menu' // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

############
## Super simple timeago function
############

function k2_timeago($time) {

    $now = time();

    $diff = $now - $time;

    if($diff <= 86400) {
        if($diff <= 600) {
            return 'Just now';
        } elseif($diff < 3600) {
            $min = floor($diff / 60);
            return $min . ' minutes ago';
        } else {
            $hour = floor(($diff / 60) / 60);
            if($hour == 1) {
                return 'About ' . $hour . ' hour ago';
            } else {
                return 'About ' . $hour . ' hours ago';
            }
        }
    } else {
        return date('F j, Y', $time);
    }
}

############
## Check the users role
############

function k2_check_user_role($role, $user_id = null) {

    if(is_numeric($user_id)) {
        $user = get_userdata($user_id);
    } else {
        $user = wp_get_current_user();
    }

    if(empty($user)) {
        return false;
    }
    return in_array($role, (array) $user->roles);
}

function k2_feed_rss2($for_comments) {
    $rss_template = get_template_directory() . '/feed-rss2.php';
    $rss_comments_template = get_template_directory() . '/feed-rss2-comments.php';

    if($for_comments) {
        load_template($rss_comments_template);
    } else {
        load_template($rss_template);
    }
}

function k2_feed_rdf() {
    $rdf_template = get_template_directory() . '/feed-rdf.php';

    load_template($rdf_template);
}

function k2_feed_atom($for_comments) {
    $atom_template = get_template_directory() . '/feed-atom.php';
    $atom_comments_template = get_template_directory() . '/feed-atom-comments.php';

    if($for_comments) {
        load_template($atom_comments_template);
    } else {
        load_template($atom_template);
    }
}

############
## Make sure the sharing link is always http
############

function get_sharing_permalink( $id, $is_canonical = true ) {

    $url = get_permalink( $id );

    global $wp_query;
    if(isset($wp_query->query_vars["page"]) && $wp_query->query_vars["page"] > 1 && !$is_canonical){
        $url .= "/" . $wp_query->query_vars["page"];
    }
    elseif(isset($wp_query->query_vars["all"]) && !$is_canonical){
        $url .= "/all";
    }

    return str_replace( 'https://', 'http://', $url );
}

function rel_share_canonical() {
    if ( !is_singular() )
        return;

    global $wp_the_query;
    if ( !$id = $wp_the_query->get_queried_object_id() )
        return;

    $link = get_sharing_permalink( $id, false );

    if ( $page = get_query_var('cpage') )
        $link = get_comments_pagenum_link( $page );

    echo '<link rel="canonical" href="' . $link . '" />';
}

remove_action('wp_head', 'rel_canonical');

############
## Admin menu items
############

function admin_menu_items($needle, $haystack) {
    foreach($haystack as $key => $value) {
        $current_key = $key;
        if( $needle === $value OR (is_array($value) && admin_menu_items($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

############
## Custom version of wp_link_pages
############

function custom_link_pages($args = '') {
    global $page, $numpages, $multipage, $more;
    $next_or_number = $before = $pagelink = $link_before = $link_after = $after = $echo = null;

    $defaults = array(
        'before'           => '<p>' . __('Pages:'),
        'after'            => '</p>',
        'link_before'      => '',
        'link_after'       => '',
        'next_or_number'   => 'number',
        'nextpagelink'     => __('Next page'),
        'previouspagelink' => __('Previous page'),
        'pagelink'         => '%',
        'echo'             => 1
    );

    $r = wp_parse_args($args, $defaults);
    $r = apply_filters('wp_link_pages_args', $r);
    extract($r, EXTR_OVERWRITE);

    $output = '';
    $i = 1;
    if ($multipage && $next_or_number == 'number') {
        $output .= $before;

        while ($i < ($numpages + 1)) {
            $j = str_replace('%', $i, $pagelink);
            $output .= '';

            if (($i != $page) || ((!$more) && ($page == 1))) {
                $output .= k2_wp_link_page($i);
            } elseif ($i == $page) {
                $output .= '<a href="javascript:void(0)" disabled="disabled">';
            }

            $output .= $link_before . $j . $link_after;

            if (($i != $page) || ( $i == $page ) || ((!$more) && ($page==1))) {
                $output .= '</a>';
            }

            $i++;
        }

        $output .= $after;
    }

    if ($echo) {
        echo $output;
    }

    return $output;
}

############
## Custom version of _wp_link_page
############

function k2_wp_link_page( $i ) {
    global $wp_rewrite;
    $post = get_post();

    if ( 1 == $i ) {
        $url = get_permalink();
    } else {
        if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
            $url = add_query_arg( 'page', $i, get_permalink() );
        elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
            $url = trailingslashit(get_permalink()) . user_trailingslashit("$wp_rewrite->pagination_base/" . $i, 'single_paged');
        else
            $url = trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged');
    }

    if ( is_preview() ) {
        $url = add_query_arg( array(
            'preview' => 'true'
        ), $url );

        if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
            $url = add_query_arg( array(
                'preview_nonce' => wp_unslash( $_GET['preview_nonce'] ),
                'page' => wp_unslash( $i )
            ), $url );
        }
    }

    return '<a href="' . esc_url( $url ) . '">';
}

############
## API for the user infos and podcasts
############

function k2_api_get_call( $req_path ) {
    $n = hash_hmac("sha512", "GET\n\napplication/json\n\n$req_path\n",USERAPI_KEY_SECRET);
    $ch = curl_init();
    $header = array("Content-Type:application/json","Authorization: ApiKey " . USERAPI_KEY_ID . ":".$n);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_URL, USERAPI_URL . "$req_path");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"GET");
    curl_setopt($ch, CURLOPT_HEADER,0);
    $file_contents = curl_exec($ch);
    $xmldata = json_decode($file_contents);
    curl_close($ch);
    if(is_object($xmldata)){
        return $xmldata;
    } else {
        return false;
    }
}

function k2_get_user_information($email) {
    $email = urlencode($email);
    $resp = k2_api_get_call( "/v1/user/show/by_email?email=$email" );
    if( $resp ) {
        return $resp->user;
    } else {
        return false;
    }
}

function k2_kvo_get($uuid) {
    $resp = k2_api_get_call( "/v1/user/kvo/$uuid" );
    if( $resp ) {
        return $resp;
    } else {
        return false;
    }
}

function k2_get_membership_status($uuid) {
    $resp = k2_api_get_call( "/v1/membership/$uuid" );
    if( $resp ) {
        return $resp->membership;
    } else {
        return false;
    }
}

function k2_get_username($uuid) {
    $resp = k2_api_get_call( "/v1/user/username/$uuid" );
    if( $resp ) {
        return $resp->username;
    } else {
        return false;
    }
}

function k2_get_static_url($ver) {
    if (!PRODUCTION_ASSETS) {
        return LOCAL_ASSET_PATH;
    } elseif (is_ssl() || SKIP_ACCELERATOR) {
        return 'https://static.komando.com/websites/common/' . $ver;
    } else {
        return 'http://nl-static1.komando.com/websites/common/' . $ver;
    }
}

function k2_grab_podcasts($show) { 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $show);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $file_contents = curl_exec($ch);
    $xml = simplexml_load_string($file_contents);
    curl_close($ch);

    return $xml;
}

function k2_grab_podcast_uuid( $xml_group ) {
    $matches = [];
    if( preg_match("/episode\\/([0-9]+)/", $xml_group->enclosure['url'], $matches) == 1 ) {
        return $matches[1];
    }
    return "no-id";
}

function k2_grab_podcasts_url($url, $shared_secret, $token_id) {
    $time = time();
    $grace_time = 3600;
    $time_zone_offset = 3600 * 7;

    $time = $time + $grace_time + $time_zone_offset;

    $parse_url = parse_url($url, PHP_URL_PATH);
    substr($parse_url, 0, -13);

    $h_hash = md5($shared_secret . $parse_url . $time . $token_id);
    $url = PODCAST_URI . $parse_url . '?e=' . $time . '&token_id=' . $token_id . '&h=' . $h_hash;

    return $url;
}

// =================================================================== Functions for page-on-demand
add_shortcode( 'komando_on_demand', 'k2_podcast_komando_on_demand' );
function k2_podcast_komando_on_demand($atts) {
	extract(shortcode_atts(array(
		'num' => 10,
		'offset' => 1
	), $atts));

	$show = 'http://podcast.komando.com/show/komando-live.xml';
	$xml = k2_grab_podcasts($show);

	$podcasts = '<div class="feed-player-header">More Komando On Demand Podcasts</div>
    <div class="feed-player-sub-header hide-mobile">
        <div class="sub-header-play">Play</div>
        <div class="sub-header-title">Episode Title</div>
        <div class="sub-header-runtime"> Runtime</div>
        <div class="sub-header-date">Date</div>
        <div class="sub-header-download">Download</div>
    </div>
    <div class="feed-player-wrapper clearfix"><ul>';
    for ($i = 1; $i <= ($num + $offset); $i++) {

		if ($i <= $offset) { continue; }

        $item = $xml->channel->item[$i];

		$podcasts = $podcasts . '<li><div class="feed-player audio-player">
                <audio class="feed-player-source" preload="none"><source src="' . $item->enclosure['url'] . '" type="'. $item->enclosure['type'] . '"></audio>
                <span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
                <span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                <span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
		    </div>
        <a name="id-' . k2_grab_podcast_uuid($item) . '" class="header-offset-anchor"></a>
		    <div class="feed-player-text">' . $item->title . '</div>
		    <div class="feed-player-runtime">' . sprintf("%02d",floor(( $item->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $item->xpath("itunes:duration")[0] % 60)) . ' </div>
		    <div class="feed-player-date">' . date('m/d/y', strtotime($item->pubDate)) . '</div>
		    <div class="feed-download"><a href="' . $item->enclosure['url'] . '">
		        <span class="feed-player-download-button"><i class="fa fa-download"></i></span></a>
		    </div></li>';
	}
    $podcasts .= '</ul></div>';

	return $podcasts;
}
 

function k2_podcast_komando_on_demand_latest() {
	$show = 'http://podcast.komando.com/show/komando-live.xml';
	$xml = k2_grab_podcasts($show);
    $id =k2_grab_podcast_uuid($xml->channel->item[0]);
    $episode_anchor_id = "id-" . $id;

    $url = get_site_url().'/listen/episode/';

    //$title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($xml->channel->item[0]->title))));
    $title =  htmlspecialchars(urlencode(html_entity_decode($xml->channel->item[0]->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
	$latest = '<div class="single-podcast-wrapper clearfix">

        <div class="on-demand-podcast">Komando On Demand Podcasts
            <span class="on-demand-podcast-subtitle">Your source for everything digital</span>
        </div>
        <a name="' . $episode_anchor_id . '" class="header-offset-anchor"></a>
        <div class="single-podcast-wrapper-inner clearfix">
            <div class="single-podcast-title">Hear This Episode</div>
            <div class="single-player-meta-wrapper listen-on-demand-player-root">
                <div class="single-podcast-controls audio-player">
                    <div class="single-podcast__control-button">
                        <audio id="podcast0" class="single-podcast-controls__source" preload="none"><source src="' . $xml->channel->item[0]->enclosure['url'] . '" type="'. $xml->channel->item[0]->enclosure['type'] .'"></audio>
                        <span class="single-player-controls__play-button audio-player-play"><i class="fa fa-play"></i></span>
                        <span class="single-player-controls__stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                        <span class="single-player-controls__spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
                    </div>
                </div>
                <div class="single-player-meta">
                   <div class="single-player-meta__date">' . strtoupper(date('F d, Y', strtotime($xml->channel->item[0]->pubDate))) . '</div>
                   <div class="single-player-share">
                        <div class="single-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
                        <div class="single-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
                        <div class="single-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplusk&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
                   </div>
                   <div class="single-player-meta__title">' . $xml->channel->item[0]->title . '</div>
                   <div class="single-player-meta__progress">
                        <div class="default-bar">
                            <div class="progress-bar"></div>
                        </div>
                        <span class="single-player-meta__runtime"><span class="current-index">0:00</span> / <span class="duration">' . sprintf("%02d",floor(( $xml->channel->item[0]->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $xml->channel->item[0]->xpath("itunes:duration")[0] % 60)) . '</span></span>
                        <span class="single-player-meta__download"><a href="' . $xml->channel->item[0]->enclosure['url'] . '"><span class="hide-mobile"> Download</span></a></span>
                        <span class="single-player-meta__download"><a href="' . $xml->channel->item[0]->enclosure['url'] . '"><i class="fa fa-download hide-mobile"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="podcast-subscribe">
                <span class="podcast-subscribe__title hide-mobile">Subscribe to Podcasts</span>
                <a href="https://geo.itunes.apple.com/us/podcast/komando-on-demand/id660921339?mt=2"><span class="podcast-subscribe__itunes-button">Subscribe on iTunes</span></a>
                <a href="https://play.google.com/music/podcasts/portal/#p:id=playpodcast/all-podcasts"><span class="podcast-subscribe__gp-button">Subscribe on Google Play</span></a>
                <a href="http://www.komando.com/podcast/komando-on-demand"><span class="podcast-subscribe__rss-button">Subscribe<br>by RSS</span></a>
            </div>
        </div>
	</div>';

	return $latest;
}

function k2_podcast_scared_shitless_latest() {
    $show = 'http://podcast.komando.com/show/5.xml';
    $xml = k2_grab_podcasts($show);

    $id = k2_grab_podcast_uuid($xml->channel->item[0]);
    $episode_anchor_id = "id-" .$id;
    $url = get_site_url().'/listen/episode/';

    //$title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($xml->channel->item[0]->title))));
    $title =  htmlspecialchars(urlencode(html_entity_decode($xml->channel->item[0]->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
    $latest = '<div class="single-podcast-wrapper clearfix">

        <div class="on-demand-podcast">Komando On Demand Podcasts,  Scared Shitless
            <span class="on-demand-podcast-subtitle">Your source for everything digital</span>
        </div>
        <a name="' . $episode_anchor_id . '" class="header-offset-anchor"></a>
        <div class="single-podcast-wrapper-inner clearfix">
            <div class="single-podcast-title">Hear This Episode</div>
            <div class="single-player-meta-wrapper listen-on-demand-player-root">
                <div class="single-podcast-controls audio-player">
                    <div class="single-podcast__control-button">
                        <audio id="podcast0" class="single-podcast-controls__source" preload="none"><source src="' . $xml->channel->item[0]->enclosure['url'] . '" type="'. $xml->channel->item[0]->enclosure['type'] .'"></audio>
                        <span class="single-player-controls__play-button audio-player-play"><i class="fa fa-play"></i></span>
                        <span class="single-player-controls__stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                        <span class="single-player-controls__spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
                    </div>
                </div>
                <div class="single-player-meta">
                   <div class="single-player-meta__date">' . strtoupper(date('F d, Y', strtotime($xml->channel->item[0]->pubDate))) . '</div>
                   <div class="single-player-share">
                        <div class="single-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
                        <div class="single-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
                        <div class="single-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
                   </div>
                   <div class="single-player-meta__title">' . $xml->channel->item[0]->title . '</div>
                   <div class="single-player-meta__progress">
                        <div class="default-bar">
                            <div class="progress-bar"></div>
                        </div>
                        <span class="single-player-meta__runtime"><span class="current-index">0:00</span> / <span class="duration">' . sprintf("%02d",floor(( $xml->channel->item[0]->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $xml->channel->item[0]->xpath("itunes:duration")[0] % 60)) . '</span></span>
                        <span class="single-player-meta__download"><a href="' . $xml->channel->item[0]->enclosure['url'] . '"><span class="hide-mobile"> Download</span></a></span>
                        <span class="single-player-meta__download"><a href="' . $xml->channel->item[0]->enclosure['url'] . '"><i class="fa fa-download hide-mobile"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="podcast-subscribe">
                <span class="podcast-subscribe__title hide-mobile">Subscribe to Podcasts</span>
                <a href="https://geo.itunes.apple.com/us/podcast/komando-on-demand/id660921339?mt=2"><span class="podcast-subscribe__itunes-button">Subscribe on iTunes</span></a>
                <a href="https://play.google.com/music/podcasts/portal/#p:id=playpodcast/all-podcasts"><span class="podcast-subscribe__gp-button">Subscribe on Google Play</span></a>
                <a href="http://www.komando.com/podcast/komando-on-demand"><span class="podcast-subscribe__rss-button">Subscribe<br>by RSS</span></a>
            </div>
        </div>
	</div>';

    return $latest;
}

add_shortcode( 'komando_on_demand_featured', 'k2_podcast_komando_on_demand_featured' );
function k2_podcast_komando_on_demand_featured($atts) {
    extract(shortcode_atts(array(
        'num' => 10,
        'offset' => 1
    ), $atts));

    $show = 'http://podcast.komando.com/show/komando-live.xml';
    $xml = k2_grab_podcasts($show);
    $url = get_site_url().'/listen/episode/';

  //If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php
    $featured = '<div class="featured-title">MOST RECENT EPISODES</div>
    <div class="featured-player-wrapper clearfix"><ul>';
    for ($i = 1; $i <= ($num + $offset); $i++) {

        if ($i <= $offset) { continue; }

        $item = $xml->channel->item[$i - 1];
        $episode_anchor_id = "id-" . k2_grab_podcast_uuid($xml->channel->item[0]);
        $id = k2_grab_podcast_uuid($item);
        /*$title = htmlentities($item->title);
        $title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($title))));*/
        $title =  htmlspecialchars(urlencode(html_entity_decode($item->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
        $featured .= '<li class="listen-on-demand-player-root">
         <div class="featured-player-share">
            <div class="featured-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
            <div class="featured-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
            <div class="featured-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
         </div>
        
        <a name="id-' . $id . '" class="header-offset-anchor"></a> 
        <div class="rf-image"><img src="' . $item->xpath("itunes:image")[0]['href'] . '"></div>
        <div class="rf-info">
        <div class="featured-player-date hide-mobile">' . date('F d, Y', strtotime($item->pubDate)) . '</div>
            <h3 class="featured-player-text">' . $item->title . '</h3>
            <span class="featured-player-content">' . $item->description . '</span>
        </div>
        <div class="featured-download"><a href="' . $item->enclosure['url'] . '">
            <span class="featured-player-download-button">DOWNLOAD <i class="fa fa-download hide-mobile"></i></span></a>
        </div>
        <div class="rf-listen">
            <div class="featured-player audio-player">
            <audio id="podcast' . $i . '" class="featured-player-source" preload="none"><source src="' . $item->enclosure['url'] . '" type="'. $item->enclosure['type'] . '"></audio>
            <span class="featured-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
            <span class="featured-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
            <span class="featured-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
        </div>
        <span class="featured-player-listen">Listen</span>
        <div class="default-bar">
            <div class="progress-bar"></div>
        </div>
        <span class="featured-player-runtime"><span class="current-index">0:00</span> / <span class="duration">' . sprintf("%02d",floor(( $item->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $item->xpath("itunes:duration")[0] % 60)) . '</span></span>
        </div>
        </li>';
    }

    $featured = $featured . '</ul></div>';

    // Display one 728x90 leaderboard ad beneath Featured Players
    $featured .= '<div class="leaderboard-ad">' . show_728x90_ad() . '</div><br>&nbsp;';

    return $featured;
}

function show_728x90_ad() {
    // Displays one 728x90 banner ad
    $adcode = '<div class="show-728x90-ad">
        <!-- /1064811/k2-www-listen-on-demand-content-1 -->
        <div id="div-gpt-ad-1449764728679-0">
        <script type="text/javascript">
            googletag.cmd.push(function() { googletag.display("div-gpt-ad-1449764728679-0"); });
        </script>
        </div></div>';

    return ($adcode);
}

function show_triple_ads(){
    // Displays three 300x250 ads side-by-side or stacked, depending upon space available.
    $triple = '
        <div class="ad-row">
            <div class="ad-box clearfix" style="display: inline-block;">
                <div id="ad-rectangle-sidebar-1" style="min-width:300px; min-height:250px;">
                    <script type="text/javascript">
                    googletag.cmd.push(function() { googletag.display("ad-rectangle-sidebar-1"); });
                    </script>
                </div>
            </div>
            <div class="ad-box clearfix" style="display: inline-block;">
                <div id="ad-rectangle-sidebar-2" style="min-width:300px; min-height:250px;">
                    <script type="text/javascript">
                    googletag.cmd.push(function() { googletag.display("ad-rectangle-sidebar-2"); });
                    </script>
                </div>
            </div>
            <div class="ad-box clearfix" style="display: inline-block;">
                <div class="ad-tag" style="text-align: center; font-size: 12px">-ADVERTISEMENT-</div>
                <div id="ad-rectangle-sidebar-3" style="min-width:300px; min-height:250px;">
                    <script type="text/javascript">
                    googletag.cmd.push(function() { googletag.display("ad-rectangle-sidebar-3"); });
                    </script>
                </div>
            </div>
        </div>';

    return ($triple);
}

function show_on_demand_sidebar() {

    $sidebar = '';
    global $wpdb;

    $querystr = "
        SELECT ID, post_title, post_type
        FROM wp_posts
        WHERE wp_posts.post_date < NOW()
        AND ((post_type = 'columns') OR
            (post_type = 'downloads') OR
            (post_type = 'apps') OR
            (post_type = 'cool_sites') OR
            (post_type = 'tips') OR
            (post_type = 'buying_guides') OR
            (post_type = 'small_business'))
        ORDER BY $wpdb->posts.post_date DESC
        LIMIT 500 ";

    // Gets sidebar articles as an array
    $sidebar_posts = $wpdb->get_results($querystr, ARRAY_A);

    if (count($sidebar_posts) > 0) {

        $sidebar .= '<div class="recent-on-demand">RECENTLY ON KOMANDO.COM</div>';

        $nI = 0;
        $nS = 1;
        while ($nS <= count($sidebar_posts)) {

            $aItem = $sidebar_posts[$nS - 1];
            $id = $aItem['ID'];
            $post_type = get_post_type($id);
            $post_data = get_post_type_object($post_type);
            $sidebar_image_id = get_post_thumbnail_id($id);
            $sidebar_image = wp_get_attachment_image_src($sidebar_image_id, 'thumbnail')[0];
            $item_permalink = get_permalink($id);
            if (empty($sidebar_image)) {
                $nS++;
                continue;
            }

            $sidebar .= '<div class="on-demand-sidebar-item">
                <a href="' . $item_permalink . '" title="' . $aItem['post_title'] .  '">
                <img src="' . $sidebar_image . '" alt="' . $aItem['post_title'] . '"/></a>';

            $sidebar .= '<div class="title-text">
                <a href="' . $item_permalink . '" title="' . $aItem['post_title'] .  '">'
                . $aItem['post_title'] . '</div></a></div>';

            $nI++;
            $nS++;
            if (($nI > 5) OR ($nS > 499)){
                break;
            }
        }

    } else {
        $sidebar = 'Sidebar not created.';
    }

    return ($sidebar);
}

function k2_podcast_listen_latest() {

    $show = 'http://podcast.komando.com/show/1.xml';
    $token_id = PODCAST_SHOW_TOKEN_ID;
    $shared_secret = PODCAST_SHARED_SECRET;
    $xml = k2_grab_podcasts($show);

    $i = 1;
    foreach ($xml->channel->item as $item) {

        $url = k2_grab_podcasts_url($item->enclosure['url'], $shared_secret, $token_id);

        $podcast_audio = '<audio src="' . $url . '" type="'. $item->enclosure['type'] . '" controls="controls" preload="none"></audio>';
        $podcast = array(
            'player' => $podcast_audio,
            'description' => $item->description,
            'title' => $item->title,
            'url' => $url
        );

        break;
    }

    return $podcast;
}

function k2_digital_minute_listen_latest() {

    $show = 'http://podcast.komando.com/show/2.xml';
    $token_id = DIGITAL_MINUTE_SHOW_TOKEN_ID;
    $shared_secret = DIGITAL_MINUTE_SHARED_SECRET;
    $xml = k2_grab_podcasts($show);

    $i = 1;
    foreach ($xml->channel->item as $item) {

        $url = k2_grab_podcasts_url($item->enclosure['url'], $shared_secret, $token_id);

        $digital_minute_audio = '<audio src="' . $url . '" type="'. $item->enclosure['type'] . '" controls="controls" preload="none"></audio>';
        $digital_minute = array(
            'player' => $digital_minute_audio,
            'description' => $item->description,
            'title' => $item->title,
            'url' => $url
        );

        break;
    }

    return $digital_minute;
}

function k2_podcasts($num) {

    $show = 'http://podcast.komando.com/show/1.xml';
    $token_id = PODCAST_SHOW_TOKEN_ID;
    $shared_secret = PODCAST_SHARED_SECRET;
    $xml = k2_grab_podcasts($show);

    $i = 1;
    $bites = '<div class="feed-player-wrapper listen-page-podcasts clearfix"><ul>';
    foreach ($xml->channel->item as $item) {

        $url = k2_grab_podcasts_url($item->enclosure['url'], $shared_secret, $token_id);

        $bites = $bites . '<li><div class="feed-player audio-player"><audio class="feed-player-source" preload="none"><source src="' . $url . '" type="'. $item->enclosure['type'] . '"></audio><span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span><span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span><span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span></div><div class="feed-player-text">' . $item->title . '</div><div class="feed-player-date hide-mobile">' . date('m/d/y', strtotime($item->pubDate)) . '</div><div class="feed-download"><a href="' . $url . '"><span class="feed-player-download-button"><i class="fa fa-download"></i></span></a></div></li>';

        if($i >= $num) { break; } $i++;
    }

    $bites = $bites . '</ul></div>';
    return $bites;
}

function k2_digital_minutes($num) {

    $show = 'http://podcast.komando.com/show/2.xml';
    $token_id = DIGITAL_MINUTE_SHOW_TOKEN_ID;
    $shared_secret = DIGITAL_MINUTE_SHARED_SECRET;
    $xml = k2_grab_podcasts($show);

    $i = 1;
    $bites = '<div class="feed-player-wrapper listen-page-minutes clearfix"><ul>';
    foreach ($xml->channel->item as $item) {

        $url = k2_grab_podcasts_url($item->enclosure['url'], $shared_secret, $token_id);

        $bites = $bites . '<li><div class="feed-player audio-player"><audio class="feed-player-source" preload="none"><source src="' . $url . '" type="'. $item->enclosure['type'] . '"></audio><span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span><span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span><span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span></div><div class="feed-player-text">' . $item->title . '</div><div class="feed-player-date hide-mobile">' . date('m/d/y', strtotime($item->pubDate)) . '</div><div class="feed-download hide-mobile"><a href="' . $url . '"><span class="feed-player-download-button"><i class="fa fa-download"></i></span></a></div></li>';

        if($i >= $num) { break; } $i++;
    }

    $bites = $bites . '</ul></div>';
    return $bites;
}

############
## Creates the links for BitGravity secure URLs
############

function k2_premium_eguides_link($link) {

    $secret = BITGRAVITY_SECRET;
    $expiry = time() + 3600;
    $path = parse_url($link)['path'];

    $salt = $secret . '/weststar' . $path . '?e=' . $expiry;
    $hash = md5($salt);

    $link = $link . '?e=' . $expiry . '&h=' . $hash;

    return $link;
}

############
## Creates video object from BitGravity video ID
############

function kom_videoplayer($video) {

    $video_sources = kom_videoplayer_get($video);

    $video_script = '<div id="video-' . $video . '"></div>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jwplayer("video-' . $video . '").setup({
                    sources: [';

    $vid_count = count($video_sources);
    $i = 1;
    foreach ($video_sources as $video) {
        if ($i >= $vid_count) {
            $video_script = $video_script . '{file: "' . $video['url'] . '", label: "' . $video['height'] . 'p" }';
        } else {
            $video_script = $video_script . '{file: "' . $video['url'] . '", label: "' . $video['height'] . 'p" },';
        }
        $i++;
    }

    $video_script = $video_script . '],
                    image: "' . $video_sources[0]['image'] . '",
                    skin: "' . k2_get_static_url('v2') . '/jwplayer/skins/stormtrooper.xml",
                    abouttext: "Copyright &copy; 1995 - ' . date('Y') . ' WestStar Multimedia Entertainment, Inc.",
                    aboutlink: "' . site_url() . '",
                    width: "100%",
                    aspectratio: "16:9"
                });
            });
        </script>';

    return $video_script;
}

function kom_videoplayer_get($video) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://director.bitgravity.com/companies/weststar/videos.rss?ids=' . $video);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $file_contents = curl_exec($ch);
    $xml = simplexml_load_string($file_contents);
    curl_close($ch);

    $video_sources = [];
    foreach ($xml->channel->item->children('http://search.yahoo.com/mrss/')->group->content as $video_source) {
        if ($video_source->attributes()['type'] == 'video/mp4' && $video_source->attributes()['videoFormat'] != 'source') {
            $video_sources[] = ['image' => $xml->channel->item->children('http://search.yahoo.com/mrss/')->thumbnail->attributes()['url'], 'bitrate' => (string)$video_source->attributes()['bitrate'], 'width' => (string)$video_source->attributes()['width'], 'height' => (string)$video_source->attributes()['height'], 'url' => (string)$video_source->attributes()['url']];
        }
    }

    $sort = [];
    foreach($video_sources as $k=>$v) {
        $sort['bitrate'][$k] = $v['bitrate'];
    }

    array_multisort($sort['bitrate'], SORT_DESC, $video_sources);

    return $video_sources;
}

############
## Injecting ad after x paragraph
############

function k2_ad_injector($content) {

    $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', $urlArray);
    $numSegments = count($segments);
    $currentSegment = $segments[$numSegments - 1];

    $ad_code_1 = '<div class="ad square-ad article-ad clearfix">'
        . '     <div id="ad-rectangle-content" style="min-width:300px; min-height:50px; margin:auto;">'
        . '        <script type=\'text/javascript\'>'
        . '        googletag.cmd.push(function() { googletag.display(\'ad-rectangle-content\'); });'
        . '        </script>'
        . '    </div>'
        . '</div>';

// Removed Lockerdome ad on 09-15-16 #3358
//    $ad_code_2 = '<div id="ld-6669-3744"></div><script>(function(w,d,s,i){w.ldAdInit=w.ldAdInit||[];w.ldAdInit.push({slot:7890516247321191,size:[0, 0],id:"ld-6669-3744"});if(!d.getElementById(i)){var j=d.createElement(s),p=d.getElementsByTagName(s)[0];j.async=true;j.src="//cdn2.lockerdome.com/_js/ajs.js";j.id=i;p.parentNode.insertBefore(j,p);}})(window,document,"script","ld-ajs");</script>';

    $ad_code_taboola = '<div id="taboola-mid-article-thumbnails"></div><script type="text/javascript">window._taboola = window._taboola || [];_taboola.push({mode: "thumbnails-d",container: "taboola-mid-article-thumbnails",placement: "Mid-Article Thumbnails",target_type: "mix"});</script>';

    $k2_ptype = get_post_type();

    if ((is_single() || is_post_type_archive('qotd')) && !is_admin() && $k2_ptype != 'charts') {

        if($currentSegment == 'all') {
            return k2_ad_injector_engine($ad_code_1, $ad_code_2, $ad_code_taboola, 'all', $content);
        } else {
            return k2_ad_injector_engine($ad_code_1, $ad_code_2, $ad_code_taboola, 2, $content);
        }
    }

    return $content;
}
add_filter('the_content', 'k2_ad_injector'); // Injecting ad after x paragraph

############
## Parent Function that makes the magic happen
############

function array_insert($a, $at, $what) {
    $p1 = array_slice($a, 0, $at);

    return array_merge($p1, array($what), array_slice($a, $at));
}

function k2_ad_injector_engine($ad_code_1, $ad_code_2, $ad_code_taboola, $paragraph_id, $content) {

    $content = preg_replace("/\\r\\n|\\r|\\n/", "", $content);
    $paragraphs = preg_split("#</p>#", $content, -1, PREG_SPLIT_NO_EMPTY);
    $para_count = count($paragraphs);
    $first_inject = $paragraph_id;
    $pattern_match = array('#<img#', '#<iframe#');

    foreach ($pattern_match as $pattern) {
        while(preg_match($pattern, $paragraphs[$first_inject])) {
            $first_inject++;
        }
    }

    if($paragraph_id == 'all') {
        $first_inject = 2;

        foreach ($pattern_match as $pattern) {
            while(preg_match($pattern, $paragraphs[$first_inject])) {
                $first_inject++;
            }
        }

        $second_inject = intval(round($para_count / 2 + $first_inject));
        foreach ($pattern_match as $pattern) {
            while(preg_match($pattern, $paragraphs[$second_inject - 1])) {
                $second_inject++;
            }
        }

        if ($para_count > 5) {
            $taboola_inject = intval(($second_inject - $first_inject) / 2 + $first_inject);
            foreach ($pattern_match as $pattern) {
                while(preg_match($pattern, $paragraphs[$taboola_inject - 1])) {
                    $taboola_inject++;
                }
            }
        }
    }

    $paragraphs = array_insert($paragraphs, $first_inject, $ad_code_1);
    if(!empty($taboola_inject)) {
        $paragraphs = array_insert($paragraphs, ($taboola_inject + 1), $ad_code_taboola);
    }
    if(!empty($second_inject)) {
        $paragraphs = array_insert($paragraphs, ($second_inject + 1), $ad_code_2);
    }

    $paragraphs = implode('</p>', $paragraphs);
    return preg_replace('#<\/div><\/div><\/p>#', '</div></div>', $paragraphs);
}

############
## Makes the number have the k like the 16,000 becomes 16k
############

function numbertok($num) {
    $x = round($num);
    $x_number_format = number_format($x);
    $x_array = explode(',', $x_number_format);
    $x_parts = array('k', 'm', 'b', 't');
    $x_count_parts = count($x_array) - 1;
    $x_display = $x;
    $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
    $x_display .= $x_parts[$x_count_parts - 1];
    return $x_display;
}

############
## Truncating text
############

function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * from CakePHP, modified to truncate number of words
     *
     * @param string $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (str_word_count(preg_replace('/<.*?>/', '', $text), 0) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = str_word_count($ending, 0);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                    // if tag is a closing tag (f.e. </b>)
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // unpleasant fix to prevent exact = false chopping off tag
                    $truncate .= $line_matchings[1] . ' ';
                    // if tag is an opening tag (f.e. <b>)
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                    $truncate .= $line_matchings[1];
                }
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = str_word_count(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]), 0);
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += str_word_count($entity[0], 0);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (str_word_count($text, 0) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - str_word_count($ending, 0));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}


/**
 *  Delete Shortcode tags and text
 */
function delete_shortcode($text='', $shortcode='nextpage') {

    if (empty($text)) {
        return false;
    }
    $start = '[' . $shortcode . ']';
    $end = '[/' . $shortcode . ']';
    $start_pos = strpos($text, $start);
    $end_pos = strpos($text, $end) + strlen($end);
    $code_length = $end_pos - $start_pos;
    $ret_text = substr_replace($text, '', $start_pos, $code_length);
    
    if (strlen($ret_text) < 1) {
        return false;
    } else {
        return $ret_text;
    }
}

/**
 * Create custom URL
 */

function get_sample_permalink_every_post($id, $title = null, $name = null) {
    $post = get_post( $id );
    if ( ! $post )
        return array( '', '' );

    $ptype = get_post_type_object($post->post_type);

    $original_status = $post->post_status;
    $original_date = $post->post_date;
    $original_name = $post->post_name;

    // Hack: get_permalink() would return ugly permalink for drafts, so we will fake that our post is published.
    if ( in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
        $post->post_status = 'publish';
        $post->post_name = sanitize_title($post->post_name ? $post->post_name : $post->post_title, $post->ID);
    }

    if ( !is_null($name) )
        $post->post_name = sanitize_title($name ? $name : $title, $post->ID);

    $post->post_name = wp_unique_post_slug($post->post_name, $post->ID, $post->post_status, $post->post_type, $post->post_parent);

    $post->filter = 'sample';

    $permalink = get_permalink($post, true);

    // Replace custom post_type Token with generic pagename token for ease of use.
    $permalink = str_replace("%$post->post_type%", '%pagename%', $permalink);

    // Handle page hierarchy
    if ( $ptype->hierarchical ) {
        $uri = get_page_uri($post);
        $uri = untrailingslashit($uri);
        $uri = strrev( stristr( strrev( $uri ), '/' ) );
        $uri = untrailingslashit($uri);

        /** This filter is documented in wp-admin/edit-tag-form.php */
        $uri = apply_filters( 'editable_slug', $uri );
        if ( !empty($uri) )
            $uri .= '/';
        $permalink = str_replace('%pagename%', "{$uri}", $permalink);
    }

    /** This filter is documented in wp-admin/edit-tag-form.php */
    $permalink = array( $permalink, apply_filters( 'editable_slug', $post->post_name ) );
    $post->post_status = $original_status;
    $post->post_date = $original_date;
    $post->post_name = $original_name;
    unset($post->filter);

    return $permalink;
}

function k2_episode($atts) {
    $podcast_id = $atts['id'];

    $json = 'http://podcast.komando.com/episode/'.$podcast_id.'.json';
    $xml = k2_grab_podcasts($json);

    $podcast_img         = '';
    $podcast_date        = '';
    $podcast_title       = '';
    $podcast_description = '';

    $podcast = '<style>
                .wrapper ul li .rf-info { 
                    min-height: auto; 
                }
                .default-bar { 
                    cursor:pointer; 
                }
                .featured-player-wrapper ul li { 
                    border-top: none;  
                }
                .featured-player-wrapper ul li .rf-listen .featured-player-listen { 
                    margin: 5px 0; 
                }
                .featured-player-wrapper ul li .rf-listen .featured-player-runtime { 
                    margin: 5px 8px; 
                }  
                </style>';

    //$title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($xml->channel->item[0]->title))));
    $title =  htmlspecialchars(urlencode(html_entity_decode($xml->channel->item[0]->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
  //If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php
    $podcast .= '<div class="featured-player-wrapper clearfix">
                    <ul>
                        <li class="listen-on-demand-player-root">
                            <div class="featured-player-share">
                                <div class="featured-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&amp;url=http%3A%2F%2Fwww.komando.com%2Flisten%2F%5Ckomando-on-demand&amp;title=Listen%20to%20the%20latest%20podcasts%20by%20Kim%20Komando&amp;api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank" sl-processed="1"><i class="fa fa-facebook-square"></i></a></div>
                                <div class="featured-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&amp;url=http%3A%2F%2Fwww.komando.com%2Flisten%2F%5Ckomando-on-demand&amp;title=Listen%20to%20the%20latest%20podcasts%20by%20Kim%20Komando&amp;api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank" sl-processed="1"><i class="fa fa-twitter-square"></i></a></div>
                                <div class="featured-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&amp;url=http%3A%2F%2Fwww.komando.com%2Flisten%2F%5Ckomando-on-demand&amp;title=Listen%20to%20the%20latest%20podcasts%20by%20Kim%20Komando&amp;api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank" sl-processed="1"><i class="fa fa-google-plus-square"></i></a></div>
                                <div class="featured-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&amp;url=http%3A%2F%2Fwww.komando.com%2Flisten%2F%5Ckomando-on-demand&amp;title=Listen%20to%20the%20latest%20podcasts%20by%20Kim%20Komando&amp;api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank" sl-processed="1"><i class="fa fa-google-plus-square"></i></a></div>
                            </div>
                            
                            <a name="id-'.$podcast_id.'" class="header-offset-anchor"></a>
                            <div class="rf-image"><img src="'.$podcast_img.'?e=1460067387&amp;h=f366fdf3732677f4b51da0b8d559221a"></div>
                            <div class="rf-info">
                                <div class="featured-player-date hide-mobile">'. strtoupper(date('F d, Y', strtotime($podcast_date))).'</div>
                                    <h3 class="featured-player-text">'.$podcast_title.' </h3>
                                    <span class="featured-player-content">'.$podcast_description.'</span>
                                    
                                    <div>
                                        <span class="single-player-meta__download">
                                            <a href="http://www.podtrac.com/pts/redirect.mp3/podcast.komando.com/episode/'.$podcast_id.'/download.mp3" sl-processed="1"><span class="hide-mobile"> Download</span></a>
                                        </span>
                                        <span class="single-player-meta__download">
                                            <a href="http://www.podtrac.com/pts/redirect.mp3/podcast.komando.com/episode/'.$podcast_id.'/download.mp3" sl-processed="1"><i class="fa fa-download hide-mobile"></i></a>
                                        </span>
                                    </div>
                                    
                                </div>
                                <div class="featured-download"><a href="http://www.podtrac.com/pts/redirect.mp3/podcast.komando.com/episode/'.$podcast_id.'/download.mp3" sl-processed="1">
                                    <span class="featured-player-download-button"></span></a>
                                </div>
                                <div class="rf-listen">
                                    <div class="featured-player audio-player">
                                    <audio id="podcast2" class="featured-player-source" preload="none">
                                        <source src="http://www.podtrac.com/pts/redirect.mp3/podcast.komando.com/episode/'.$podcast_id.'/download.mp3" type="audio/mpeg">
                                    </audio>
                                    <span class="featured-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
                                    <span class="featured-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                                    <span class="featured-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
                                </div>
                                <span class="featured-player-listen">Listen</span>
                                <div class="default-bar">
                                    <div class="progress-bar"></div>
                                </div>
                                <span class="featured-player-runtime"><span class="current-index">0:00</span> / <span class="duration" id="podcast_duration"></span></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="podcast-subscribe">
                    <span class="podcast-subscribe__title hide-mobile">Subscribe to Podcasts</span>
                    <a href="https://geo.itunes.apple.com/us/podcast/komando-on-demand/id660921339?mt=2"><span class="podcast-subscribe__itunes-button">Subscribe on iTunes</span></a>
                    <a href="https://play.google.com/music/podcasts/portal/#p:id=playpodcast/all-podcasts"><span class="podcast-subscribe__gp-button">Subscribe on Google Play</span></a>
                    <a href="http://www.komando.com/podcast/komando-on-demand"><span class="podcast-subscribe__rss-button">Subscribe<br>by RSS</span></a>
                </div>';

    $podcast .= '<script>
                $(document).ready(function(){
                    var duration = 0;
                    var audio = $("audio"); 
                     
                    audio.bind("loadend", function (e) {
                        e.preventDefault();
                    });
                    
                    audio.bind("canplay",function(e){
                        duration    = e.currentTarget.duration;                            
                        var hours   = Math.floor(duration / 3600);
                        var minutes = Math.floor((duration - (hours * 3600)) / 60);
                        var seconds = duration - (hours * 3600) - (minutes * 60);
        
                        if (hours   < 10) {hours   = "0"+hours;}
                        if (minutes < 10) {minutes = "0"+minutes;}
                        if (seconds < 10) {seconds = "0"+seconds;}
                        var podcast_duration    = hours+\':\'+minutes+\':\'+Math.floor(seconds);
                          
                        $("span#podcast_duration").html(podcast_duration);  
                    });
                    
                    $("div.default-bar").click(function (e) {                       
                        var width    = $(e.currentTarget).width();      
                        var position = e.pageX - $(e.currentTarget).offset().left;  
                        var percentage_div   = (position / width) * 100;                                 
                        var percentage_audio = (percentage_div /100)* duration;                         
                        audio[0].currentTime = percentage_audio;   
                    });
                    
                });
                </script>';

}
/*End function */

function k2_get_add_to_calendar_links($ics_url){
  $ics = file_get_contents($ics_url);
  if(!$ics){
    throw new \Exception('ICS file could not be read. It may not exist (' . $ics_url . ')');
  }

  preg_match('/DESCRIPTION:(.*?)URL;/', $ics , $matches);
  $description = urlencode(trim($matches[1]));

  $ics_rows = explode("\n", $ics);
  $title = urlencode(trim(str_replace('X-WR-CALNAME:', '', k2_find_first_value_in_array('X-WR-CALNAME:', $ics_rows))));
  $timezone = trim(str_replace('TZID:', '', k2_find_first_value_in_array('TZID:', $ics_rows)));
  $location = urlencode(trim(str_replace('LOCATION:', '', k2_find_first_value_in_array('LOCATION:', $ics_rows))));
  $start_date = strtotime(str_replace('DTSTART;TZID='.$timezone.':', '', k2_find_first_value_in_array('DTSTART;TZID='.$timezone.':', $ics_rows)));
  $end_date = strtotime(str_replace('DTEND;TZID='.$timezone.':', '', k2_find_first_value_in_array('DTEND;TZID='.$timezone.':', $ics_rows)));

  if('America/Phoenix' == $timezone || k2_is_daylight_savings_in_effect($start_date)){
    $offset = str_replace('TZOFFSETTO:', '', $ics_rows[k2_find_key_for_first_value_in_array('BEGIN:STANDARD', $ics_rows) + 4]);
  }
  else{
    $offset = str_replace('TZOFFSETTO:', '', $ics_rows[k2_find_key_for_first_value_in_array('BEGIN:DAYLIGHT', $ics_rows) + 4]);
  }
  $offset = ((((int) $offset) * -1)/100)*3600;

  $start_date += $offset;
  $start_date = urlencode(date('Ymd', $start_date) . 'T' . date('His', $start_date) . 'Z');

  $end_date += $offset;
  $end_date = urlencode(date('Ymd', $end_date) . 'T' . date('His', $end_date) . 'Z');

  $outlook_online_url = 'https://bay02.calendar.live.com/calendar/calendar.aspx?rru=addevent&summary=' . $title . '&location=' . $location . '&description=' . $description . '&dtstart=' . $start_date . '&dtend=' . $end_date;

  $google_url = 'https://calendar.google.com/calendar/render?action=TEMPLATE&dates=' . $start_date . '/' . $end_date . '&location=' . $location . '&text=' . $title . '&details=' . $description;

  $yahoo_url = 'https://login.yahoo.com/?.src=yc&.done=http://calendar.yahoo.com/?st=' . $start_date . '&et=' . $end_date . '&view=d&v=60&type=20&title=' . $title . '&in_loc=' . $location . '&desc=' . $description;

  return [
      'iCalendar' => $ics_url,
      'Google Calendar' => $google_url,
      'Outlook' => $ics_url,
      'Outlook Online' => $outlook_online_url,
      'Yahoo! Calendar' => $yahoo_url,
  ];
}

function k2_find_first_value_in_array($needle, $items){
  $key = k2_find_key_for_first_value_in_array($needle, $items);
  return is_null($key) ? null : $items[$key];
}

function k2_find_key_for_first_value_in_array($needle, $items){
  foreach($items as $key => $item){
    if(false !== strpos($item, $needle)){
      return $key;
    }
  }
  return null;
}

function k2_make_query_string_from_params($params = []){
  $query_vars = [];
  foreach($params as $field => $value){
    $query_vars[] = $field . '=' . urlencode($value);
  }
  return implode('&', $query_vars);
}

function k2_is_daylight_savings_in_effect($timestamp = null){
  $server_timezone = date_default_timezone_get();
  date_default_timezone_set('America/Los_Angeles');
  $daylight_savings_in_effect = date('I', is_null($timestamp) ? time() : $timestamp);
  date_default_timezone_set($server_timezone);
  return $daylight_savings_in_effect;
}