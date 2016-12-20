<?php 
// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);

if(restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member')) && preg_match('/^facebookexternalhit.*/', $_SERVER['HTTP_USER_AGENT']) != 1) {

	initializeCas();

	if(!is_user_logged_in() && $_GET['auth'] != 'checked') {
		status_header(401);
		$redirect = $login_boomerang . '&auth=check';
		header("Location: $redirect");
	}
}
?>
<?php get_header(); ?>

<div class="content-left clearfix">

	<div class="show-picks-intro">Welcome to the "Show Picks" page! Here, you'll find quick links to things that Kim mentions on the show. It's updated every show. Never miss a week: <a href="<?php echo STATION_FINDER_BASE_URI; ?>">find your station</a> now.</div>

	<?php 
	if(have_posts()) {
		while(have_posts()) : the_post(); 
			$id = get_the_ID();

			$show_grid_data = get_post_meta($id, 'show_picks_data', true);
			$cuff_id = $show_grid_data['cuff'];
			$money_id = $show_grid_data['money'];
			$security_id = $show_grid_data['security'];
			$column_id = $show_grid_data['column'];

			$cuff_image_id = get_post_thumbnail_id($cuff_id);
			$cuff_image = wp_get_attachment_image_src($cuff_image_id, 'medium')[0];
			if(empty($cuff_image)) {
				$cuff_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
			}

			$money_image_id = get_post_thumbnail_id($money_id);
			$money_image = wp_get_attachment_image_src($money_image_id, 'medium')[0];
			if(empty($money_image)) {
				$money_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
			}

			$security_image_id = get_post_thumbnail_id($security_id);
			$security_image = wp_get_attachment_image_src($security_image_id, 'medium')[0];
			if(empty($security_image)) {
				$security_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
			}

			$column_image_id = get_post_thumbnail_id($column_id);
			$column_image = wp_get_attachment_image_src($column_image_id, 'medium')[0];
			if(empty($column_image)) {
				$column_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
			}
	?>

	<div class="show-picks-grid-wrapper">
		<span class="show-picks-sub">Broadcast the weekend of</span>
<div class="show-picks-title">
		<h1><?php the_title(); ?></h1>
<div class="show-picks-mini-nav hide-mobile">
				<?php 
				$prev_post_id = get_previous_post()->ID;
				$next_post_id = get_next_post()->ID;

				if(empty($prev_post_id)) {
				?>
				<a href="javascript:void(0)" class="show-picks-mini-nav-item disabled">
					<div class="show-picks-mini-nav-btn"><span class="icon-k2-left-arrow"></span></div>
				</a>
				<?php } else { ?>
				<a href="<?php echo get_permalink($prev_post_id); ?>" class="show-picks-mini-nav-item">
					<div class="show-picks-mini-nav-btn"><span class="icon-k2-left-arrow"></span></div>
				</a>
				<?php } ?>

				<a href="<?php bloginfo('url') ?>/previous-shows" class="show-picks-mini-nav-item">
					<div class="show-picks-mini-nav-btn calendar"><span class="icon-k2-calendar"></span></div>
				</a>

				<?php if(empty($next_post_id)) { ?>
				<a href="javascript:void(0)" class="show-picks-mini-nav-item disabled">
					<div class="show-picks-mini-nav-btn"><span class="icon-k2-right-arrow"></span></div>
				</a>
				<?php } else { ?>
				<a href="<?php echo get_permalink($next_post_id); ?>" class="show-picks-mini-nav-item">
					<div class="show-picks-mini-nav-btn"><span class="icon-k2-right-arrow"></span></div>
				</a>
				<?php } ?>
			</div>
            </div>
		<div class="show-picks-grid">			

			<article class="show-picks-grid-item security">
				<div class="show-picks-grid-item-header"><span class="icon-k2-trophy"></span> Privacy and Security Tip</div>
				<figure><a href="<?php echo get_permalink($security_id); ?>"><img src="<?php echo $security_image; ?>" alt="<?php echo get_the_title($security_id); ?>" /></a></figure>
				<div class="show-picks-grid-item-body">
					<header>
						<h3><a href="<?php echo get_permalink($security_id); ?>"><?php echo get_the_title($security_id); ?></a></h3>
					</header>
					<div class="show-picks-grid-item-meta clearfix">
						<div class="show-picks-grid-item-share">
							<span class="icon-k2-share"></span> Share
						</div>
						<div class="show-picks-grid-item-share-icons">
							<div class="st_email_custom share-button" st_url="<?php echo get_permalink($security_id); ?>" st_title="<?php echo get_the_title($security_id); ?>"></div>
							<div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($security_id); ?>" st_title="<?php echo get_the_title($security_id); ?>"></div>
							<div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($security_id); ?>" st_title="<?php echo get_the_title($security_id); ?>"></div>
							<div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($security_id); ?>" st_title="<?php echo get_the_title($security_id); ?>"></div>
							<div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($security_id);?>" st_title="<?php echo get_the_title($security_id); ?>"></div>
						</div>
						<?php k2_post_view($security_id); ?>
					</div>
				</div>
			</article>

			<article class="show-picks-grid-item money">
				<div class="show-picks-grid-item-header"><span class="icon-k2-trophy"></span> Money Tip</div>
				<figure><a href="<?php echo get_permalink($money_id); ?>"><img src="<?php echo $money_image; ?>" alt="<?php echo get_the_title($money_id); ?>" /></a></figure>
				<div class="show-picks-grid-item-body">
					<header>
						<h3><a href="<?php echo get_permalink($money_id); ?>"><?php echo get_the_title($money_id); ?></a></h3>
					</header>
					<div class="show-picks-grid-item-meta clearfix">
						<div class="show-picks-grid-item-share">
							<span class="icon-k2-share"></span> Share
						</div>
						<div class="show-picks-grid-item-share-icons">
							<div class="st_email_custom share-button" st_url="<?php echo get_permalink($money_id); ?>" st_title="<?php echo get_the_title($money_id); ?>"></div>
							<div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($money_id); ?>" st_title="<?php echo get_the_title($money_id); ?>"></div>
							<div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($money_id); ?>" st_title="<?php echo get_the_title($money_id); ?>"></div>
							<div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($money_id); ?>" st_title="<?php echo get_the_title($money_id); ?>"></div>
							<div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($money_id);?>" st_title="<?php echo get_the_title($money_id); ?>"></div>
						</div>
						<?php k2_post_view($money_id); ?>
					</div>
				</div>
			</article>

			<article class="show-picks-grid-item cuff">
				<div class="show-picks-grid-item-header"><span class="icon-k2-trophy"></span> Small Business Tip</div>
				<figure><a href="<?php echo get_permalink($cuff_id); ?>"><img src="<?php echo $cuff_image; ?>" alt="<?php echo get_the_title($cuff_id); ?>" /></a></figure>
				<div class="show-picks-grid-item-body">
					<header>
						<h3><a href="<?php echo get_permalink($cuff_id); ?>"><?php echo get_the_title($cuff_id); ?></a></h3>
					</header>
					<div class="show-picks-grid-item-meta clearfix">
						<div class="show-picks-grid-item-share">
							<span class="icon-k2-share"></span> Share
						</div>
						<div class="show-picks-grid-item-share-icons">
							<div class="st_email_custom share-button" st_url="<?php echo get_permalink($cuff_id); ?>" st_title="<?php echo get_the_title($cuff_id); ?>"></div>
							<div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($cuff_id); ?>" st_title="<?php echo get_the_title($cuff_id); ?>"></div>
							<div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($cuff_id); ?>" st_title="<?php echo get_the_title($cuff_id); ?>"></div>
							<div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($cuff_id); ?>" st_title="<?php echo get_the_title($cuff_id); ?>"></div>
							<div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($cuff_id);?>" st_title="<?php echo get_the_title($cuff_id); ?>"></div>
						</div>
						<?php k2_post_view($cuff_id); ?>
					</div>
				</div>
			</article>

			<article class="show-picks-grid-item column">
				<div class="show-picks-grid-item-header"><span class="icon-k2-trophy"></span> USA Today Column Sneak Peek</div>
				<figure><a href="<?php echo get_permalink($column_id); ?>"><img src="<?php echo $column_image; ?>" alt="<?php echo get_the_title($column_id); ?>" /><div class="column-cover"><img src="<?php echo k2_get_static_url('v2'); ?>/img/usatoday-logo.png" /></div></a></figure>
				<div class="show-picks-grid-item-body">
					<header>
						<h3><a href="<?php echo get_permalink($column_id); ?>"><?php echo get_the_title($column_id); ?></a></h3>
					</header>
					<div class="show-picks-grid-item-meta clearfix">
						<div class="show-picks-grid-item-share">
							<span class="icon-k2-share"></span> Share
						</div>
						<div class="show-picks-grid-item-share-icons">
							<div class="st_email_custom share-button" st_url="<?php echo get_permalink($column_id); ?>" st_title="<?php echo get_the_title($column_id); ?>"></div>
							<div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($column_id); ?>" st_title="<?php echo get_the_title($column_id); ?>"></div>
							<div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($column_id); ?>" st_title="<?php echo get_the_title($column_id); ?>"></div>
							<div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($column_id); ?>" st_title="<?php echo get_the_title($column_id); ?>"></div>
							<div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($column_id);?>" st_title="<?php echo get_the_title($column_id); ?>"></div>
						</div>
						<?php k2_post_view($column_id); ?>
					</div>
				</div>
			</article>

		</div>
	</div>

	<p>In addition, here are links to more information about other topics on the show:</p>

	<div class="show-picks-more-links">
		<?php 
		$content = get_the_content(); 
		$content = preg_replace( '#<br(\s*/)?>#i', "\n", $content );
		echo $content;
		?>
	</div>

	<p><a name="sponsors"></a>If you want more information about products or services offered by our show sponsors, click on the links below:</p>

	<?php if(class_exists('Kom_Homepage')) { echo (new Kom_Www_Api())->get_sponsors_wordpress(); } ?>

	<h2>Join Kim's Club</h2>
	<a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>"><figure class="align-right"><img alt="Subscribe Now" src="<?php echo k2_get_static_url('v2'); ?>/img/the-show-page-subscribe-button.jpg"></figure></a>
	<p>Kim's Club Members: You have complete access to watch or listen to the show right now. <a href="<?php echo site_url($login_boomerang); ?>">Sign in now</a> to access it all.</p>
	<p>To join Kim's Club for this show's video or podcast as well as archived show, <a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>">click here to join now</a>.</p>

	<div class="show-picks-nav">
		<span class="show-picks-nav-title">Looking for the past weekend Kim Komando Show picks?</span>
		
		<?php if(empty($prev_post_id)) { ?>
		<a href="javascript:void(0)" class="picks-nav-item disabled">&nbsp;</a>
		<?php } else { ?>
		<a href="<?php echo get_permalink($prev_post_id); ?>" class="picks-nav-item">
			<div class="picks-nav-icon"><span class="icon-k2-left-arrow"></span></div><div class="picks-nav-text"><?php echo get_the_title($prev_post_id); ?></div>
		</a>
		<?php } ?>

		<a href="<?php bloginfo('url') ?>/previous-shows" class="picks-nav-item">
			<div class="picks-nav-icon calendar"><span class="icon-k2-calendar"></span></div><div class="picks-nav-text">Picks Archive</div>
		</a>

		<?php if(empty($next_post_id)) { ?>
		<a href="javascript:void(0)" class="picks-nav-item disabled">&nbsp;</a>
		<?php } else { ?>
		<a href="<?php echo get_permalink($next_post_id); ?>" class="picks-nav-item">
			<div class="picks-nav-text"><?php echo get_the_title($next_post_id); ?></div><div class="picks-nav-icon"><span class="icon-k2-right-arrow"></span></div>
		</a>
		<?php } ?>
	</div>

	<?php endwhile; } wp_reset_query(); ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>