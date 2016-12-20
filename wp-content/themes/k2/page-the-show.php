<?php 

/*
Template Name: The Show
*/

$post_id = get_queried_object_id();

get_header(); 

?>

<?php if(function_exists('k2_on_air')) { k2_on_air(); } ?>
<section class="the-new-show-page clearfix" role="main">

	<div class="the-show-header-wrapper clearfix">
        <div class="show-page-header" >DIGITAL LIFESTYLE <span class="gold-tone">TOTAL ACCESS</span></div>
        <div class="the-show-reel">
            <?php // Podcast Video ?>
            <iframe src="https://www.youtube.com/embed/i8WZI1fmwko" frameborder="0" allowfullscreen></iframe>
        </div>
		<div class="digital-goddess">
			<h1><?php echo get_post_meta($post_id, 'the_show_title', true); ?></h1>
			<?php echo get_post_meta($post_id, 'the_show_teaser', true); ?><p>
            <a class="btn btn-gold" href="https://club.komando.com/" sl-processed="1">START YOUR FREE KIM'S CLUB TRIAL</a>
            <p>Digital Diva &reg;</p>
		</div>
    </div>

    <div class="the-new-show-header-buttons-wrapper clearfix">
        <div class="the-new-show-buttons-bar">
                <div class="the-new-show-watch the-new-show-button">
                <a href="<?php echo VIDEOS_BASE_URI; ?>/live-from-the-studio/latest"><span class="clickable">
                    <i class="fa fa-angle-double-right"></i><span class="btn-text"> WATCH THE SHOW</span></span></a>
            </div>

            <div class="the-new-show-listen-online the-new-show-button">
                <a href="<?php bloginfo('url') ?>/listen">
                    <span class="clickable"><i class="fa fa-angle-double-right"></i><span class="btn-text"> LISTEN ONLINE</span></span></a>
            </div>

            <div class="the-new-show-digital-minute the-new-show-button">
                <a href="<?php bloginfo('url') ?>/listen">
                    <span class="clickable"><i class="fa fa-angle-double-right"></i><span class="btn-text"> THE DIGITAL MINUTE&trade;</span></span></a>
            </div>

            <div class="the-new-show-station-finder the-new-show-button">
                <a href="<?php echo STATION_FINDER_BASE_URI; ?>">
                    <span class="clickable"><i class="fa fa-angle-double-right"></i><span class="btn-text"> FIND YOUR STATION</span></span></a>
            </div>
        </div>
    </div>

    <div><img class="show-page-kim-image hide-mobile" src="<?php echo k2_get_static_url('v2'); ?>/img/kim-standing-1.png" alt="Kim Komando" /></div>

    <div class="content-wrapper">
        <div class="about-watch-listen">
            <h3>THE KIM KOMANDO SHOW</h3>
            <p>My 3-hour show is jam-packed with over 35 different tips, security alerts, insider secrets and everything you need to know
                to stay up-to-date in the digital world. I share the latest gadgets out on the market, breaking tech news and how they
                impact your life, money tips to make the most out of your earned money, and privacy tips to help you stay safe and secure
                when you're online. And the show isn't complete without my callers! Every hour I answer their questions about everything
                digital from online addiction to how to select the best way to share videos and photos in an ever-changing world of
                technology. Don't miss a single show. You can watch me or listen to my show anytime at your leisure.</p>
            <h3>ABOUT KIM</h3>
            <p>My passion for all things digital &ndash; and decades of experience in the tech and computer industry &ndash; has made me the
                go-to expert for living in the  digital world. My weekly radio show and daily "Digital Minute" are heard on nearly 500
                stations; millions more get tech guidance and advice at Komando.com, read my weekly column on USAToday.com, and receive my
                email newsletters.</p>
            <h3>WATCH THE KIM KOMANDO SHOW</h3>
            <p>See me in action, get to know my amazing crew, go behind the scenes during commercials for hilarious chit-chat, and more! <i>The
                Kim Komando Show</i> streams weekly and exclusively to Kim's Club members.</p>
            <p>Not a Kim's Club member? Start your free trial now. Stream directly to your TV, computer, smartphone or tablet. Watch the show
                anytime you want. With ten HD cameras in the studio to record all the action, you won't miss a thing.</p>
            <p>Want to watch more shows?</p>

            <a class="btn btn-gold" href="https://club.komando.com/" sl-processed="1">START YOUR FREE KIM'S CLUB TRIAL</a>
            <br />
        </div>
        <div class="about-watch-listen">
            <h3>KIM'S FREE NEWSLETTER</h3>
            <p>Get must-have information delivered right to your inbox every day. From tips to improve your digital life and the latest tech
                news to free downloads, cool sites, amazing apps, and incredible videos, my daily email newsletters have everything you
                need to navigate the digital world.</p>

            <div class="align-left">
                <a href="javascript:void(0)" data-modal="subscribe-modal" class="btn btn-gold" sl-processed="1">SIGN UP FOR FREE</a>
            </div>
        </div>

    </div>

<!--
    <h4>Help us spread the word!</h4>
    <div class="share-buttons">
        <div class="st_email_custom share-button" st_url="http://www.komando.com/the-show">&nbsp;<span>Email</span></div>
        <div class="st_facebook_custom share-button" st_url="http://www.komando.com/the-show">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Facebook</span></div>
        <div class="st_twitter_custom share-button" st_url="http://www.komando.com/the-show">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Twitter</span></div>
        <div class="st_googleplus_custom share-button" st_url="http://www.komando.com/the-show">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Google+</span></div>
        <div class="st_pinterest_custom share-button" st_url="http://www.komando.com/the-show">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Pinterest</span></div>
    </div>
-->
</section>
	
<?php get_footer(); ?>
