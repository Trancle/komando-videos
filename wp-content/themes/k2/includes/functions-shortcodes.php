<?php
/**
 * functions-shortcodes.php
 * Function definitions used with add_shortcode()
 * Created by PhpStorm.
 * User: gilbert
 * Date: 5/15/2015
 * Time: 9:24 AM
 */

// This processes the shortcode

############
## Gets next page shortcode
############

function k2_next_page_shortcode($atts, $content = null) {
    global $page, $numpages, $multipage, $more, $pagenow;

    $cur_page = get_post_permalink();

    $next_page_number = $page + 1;
    $next_page = $cur_page . '/' . $next_page_number;

    if(isset($_GET['preview'])) {
        $next_page = $cur_page . '&page=' . $next_page_number;
    }

    if($multipage && $numpages >= $page) {
        if (restrictor_require_memebership() != 'true' OR (current_user_can('premium_member') OR current_user_can('basic_member'))) {
            return '<span class="nextpage-link">Next page: <a href="' . $next_page . '">' . $content . '</a></span>';
        }
    }
    return $content;

}
add_shortcode('nextpage', 'k2_next_page_shortcode');

############
## Share buttons shortcode
############

function k2_share_button_shortcode($atts) {
    extract(shortcode_atts(array(
        'buttons' => 'all',
        'class' => 'share-button-insert'
    ), $atts));

    $share_buttons = '<div class="' . $class . '">'
        . '<div class="st_email_custom share-button" st_url="' . get_permalink() . '">&nbsp;<span>Email</span></div>'
        . '<div class="st_facebook_custom share-button" st_url="' . get_permalink() . '">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Facebook</span></div>'
        . '<div class="st_twitter_custom share-button" st_url="' . get_permalink() . '">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Twitter</span></div>'
        . '<div class="st_googleplus_custom share-button" st_url="' . get_permalink() . '">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Google+</span></div>'
        . '<div class="st_pinterest_custom share-button" st_url="' . get_permalink() . '">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Pinterest</span></div>'
        . '</div>';

    return $share_buttons;
}
add_shortcode('share_buttons', 'k2_share_button_shortcode');

############
## Subscribe button for newsletters
############

function k2_subscribe_button() {
    return '<a href="javascript:void(0)" data-modal="subscribe-modal" class="btn btn-blue">Click here to get email updates</a>';
}
add_shortcode('subscribe_newsletter', 'k2_subscribe_button');

############
## Shortcode for the discount coupon on the member discount page
############

function k2_discount_coupon($atts) {
    extract(shortcode_atts(array(
        'title' => 'all',
        'desc' => 'desc here',
        'code' => 'code here',
        'fine' => 'No adjustments to prior purchases. Offer valid only on orders placed online at shop.komando.com. Cannot be combined with any other offer.'
    ), $atts));

    $coupon = '<div class="discount-wrapper">'
        . '<span class="discount-title">' . $title . '</span>'
        . '<span class="discount-description">' . $desc . '</span>'
        . '<input type="text" class="discount-code" onClick="this.select()" value="' . $code . '">'
        . '<span class="discount-helper">Ctr/Cmd + C to copy</span>'
        . '<span class="discount-fine-print">' . $fine . '</span>'
        . '</div>';

    return $coupon;

}
add_shortcode('discount_coupon', 'k2_discount_coupon');

############
## Shortcode for wrapping a contest
############

function k2_contest_wrapper($atts, $content = null) {

    return '<div class="contest clearfix">' . $content . '</div>';

}
add_shortcode('contest', 'k2_contest_wrapper');

############
## Shortcode for datetime
############

function k2_datetime_shortcode($atts) {
    extract(shortcode_atts(array(
        'format' => 'F n, Y'
    ), $atts));

    return date($format);

}
add_shortcode('datetime', 'k2_datetime_shortcode');

############
## Dataplan calculator for the mobile page
############

function k2_dataplan_calculator_shortcode($atts) {
    extract(shortcode_atts(array(
        'buttons' => 'all',
        'class' => 'share-button-insert'
    ), $atts));

    $calculator = '<form>'
        . '    <div class="calc-table-header clearfix">'
        . '        <div class="calc-header-title">Enter the number of Phones/Devices</div>'
        . '        <div class="calc-header-data">Data(GB)</div>'
        . '        <div class="calc-header-prices">Verizon</div>'
        . '        <div class="calc-header-prices">AT&amp;T</div>'
        . '    </div>'
        . '    <div class="calc-table">'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-inputs">Smart Phones <input type="text" id="SmartPhoneNum" value="0" size="5"/></div>'
        . '             <div class="calc-table-cell">1</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-1GB">$50</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-1GB">$40</div>'
        . '         </div>'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-inputs">Basic Phones <input type="text" id="BasicPhoneNum" value="0" size="5"/></div>'
        . '             <div class="calc-table-cell">2</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-2GB">$60</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-2GB">N/A</div>'
        . '         </div>'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-inputs">Hotspots/USB <input type="text" id="HotSpotNum" value="0" size="5"/></div>'
        . '             <div class="calc-table-cell">4</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-4GB">$70</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-4GB">$70</div>'
        . '         </div>'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-inputs">Tablets <input type="text" id="TabletNum" value="0" size="5"/></div>'
        . '             <div class="calc-table-cell">6</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-6GB">$80</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-6GB">$90</div>'
        . '         </div>'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-inputs"></div>'
        . '             <div class="calc-table-cell">8</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-8GB">$90</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-8GB">N/A</div>'
        . '         </div>'
        . '         <div class="calc-table-row">'
        . '             <div class="calc-table-cell calc-reset"><input type="reset" class="btn" value="Clear all fields" name="reset" id="resetButton" /></div>'
        . '             <div class="calc-table-cell">10</div>'
        . '             <div class="calc-table-cell calc-prices" id="Verizon-10GB">$100</div>'
        . '             <div class="calc-table-cell calc-prices" id="ATT-10GB">$120</div>'
        . '         </div>'
        . '    </div>'
        . '    <div class="calc-disclaimer">Disclaimer: This information is subject to change without notice if the carriers decide to alter their terms of service.</div>'
        . '</form>'
        . '<script src="//static.komando.com/websites/common/v1/js/k2-calculator.js" type="text/javascript"></script>';

    return $calculator;
}
add_shortcode('dataplan_calculator', 'k2_dataplan_calculator_shortcode');

############
## Creating a blockquote shortcode to overcome the editor adding extra p tags
############

function k2_blockquote_shortcode($atts, $content = null) {
    return '<blockquote>' . do_shortcode($content) . '</blockquote>';
}
add_shortcode('blockquote', 'k2_blockquote_shortcode');

############
## Creating the charts middle ad unit
############

function k2_chart_leaderboard_ad_shortcode() {

    $ad = '<div class="ad leaderboard-ad clearfix">'
        . '        <script type="text/javascript">'
        . '        googletag.cmd.push(function() { googletag.display(\'k2-www-charts-1\'); });'
        . '        </script>'
        . '    </div>'
        . '</div>';

    return $ad;
}
add_shortcode('chart_leaderboard_ad', 'k2_chart_leaderboard_ad_shortcode');

############
## Show Bites players for the show page
############

function k2_show_bites() {

    $show = 'http://podcast.komando.com/show/4.xml';
    $xml = k2_grab_podcasts($show);

    $i = 1;
    $bites = '<!-- themes/k2/includes/functions-shortcodes.php --><div class="feed-player-wrapper clearfix"><ul>';
    foreach ($xml->channel->item as $item) {

        $bites = $bites . '<li><div class="feed-player audio-player"><span class="listen-on-demand-player-root"><audio class="feed-player-source" preload="none"><source src="' . $item->enclosure['url'] . '" type="'. $item->enclosure['type'] . '"></audio><span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span><span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span><span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span></span></div><div class="feed-player-text">' . $item->title . '</div><div class="feed-player-date hide-mobile">' . date('m/d/y', strtotime($item->pubDate)) . '</div><div class="feed-download"><a href="' . $item->enclosure['url'] . '"><span class="feed-player-download-button"><i class="fa fa-download"></i></span></a></div></li>';

        if($i > 4) { break; } $i++;
    }

    $bites = $bites . '</ul></div>';
    return $bites;
}
add_shortcode('show_bites', 'k2_show_bites');
