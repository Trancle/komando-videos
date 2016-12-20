<?php
/*
	Template Name: Kim's Shopping Network
*/

	/**
	 *
	 * Change the date of the KSN show via the line:
	 * $start_time  = mktime(7,0,0,12,13,2016);
	 ******************
	* That will automatically update the date on the page to the new KSN date. However, a new ics file will need to be created for the new KSN date and the URL will need to be replaced via the line:
	* $add_to_calendar_links = k2_get_add_to_calendar_links('http://static.komando.com/websites/www.komando.com/2016/11/ksn/ksn-live-nov-28-2016-7am-mst.ics');
	 ******************
	* The state will automatically change to the during state once the target date has been reached.
	 *
	 *
	 */

//TODO: ACTIVATE VIDEO THUMBNAILS ON OR AROUND LINE 516 (in the const 'video_data')

//https://projects.komando.com/issues/3932
//Changed to 9AM per sandra
$start_time  = mktime(8,45,0,12,13,2016);
if(time() < $start_time){
	$state = 'before';
}
else{
	$state = 'during';
}

if(isset($_GET['state']) && in_array($_GET['state'], ['before', 'during'])){
	$state = $_GET['state'];
}

$post = get_post();
if(!empty($post)){
	$content = $post->post_content;
	preg_replace('/<[^>]*>/', '', $content);
	$content = strip_tags(html_entity_decode($content));
	$content = preg_replace('/\s+/', '', $content);
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	if( !empty($matches) && isset($matches[1][0]) && !empty($matches[1][0])){
		$timeSecond = strtotime($matches[1][0]);
		}
	}

get_header(); ?>

<?php if('before' == $state){
	/**
	 *
	 *  BEFORE STATE
	 *
	 */
	?>

	<div class="komando-shopping-network-container k2-ksn-global-rules">
		<div class="bs-row">
			<div class="col-xs-12 k2-ksn-top-heading-1 k2-ksn-super-large-padding">
				<div class="bs-row">
					<div class="col-xs-12">
					</div>
				</div>
			</div>
		</div>
		<div class="bs-row">
			<div class="col-xs-12">
				<div class="bs-row k2-ksn-top-links-container">
					<span class="k2-ksn-top-links"><a href="http://shop.komando.com" target="_blank">Shop Now</a></span>
					<span class="k2-ksn-top-links"><a href="http://club.komando.com" target="_blank">Join Kim's Club for extra savings</a></span>
			</div>
		</div>
		<div class="bs-row k2-ksn-background-light-gray">
			<div class="col-xs-12">
				<div class="bs-row">
					<div class="col-md-3 col-xs-12 k2-ksn-calendar-portion k2-ksn-background-dark-sky-blue">
						<div class="k2-ksn-cyber-monday"><?php echo date("l", $start_time); ?></div>
						<div class="k2-ksn-month"><?php echo date("F", $start_time); ?></div>
						<div class="k2-ksn-super-sized-text"><?php echo date("j", $start_time); ?></div>
					</div>
					<div class="col-md-9 col-xs-12 k2-ksn-mark-your-calendar">
						<h2 class="k2-ksn-all-caps k2-ksn-no-margin-bottom k2-ksn-small-margin-top">
							<?php

								$add_to_calendar_links = k2_get_add_to_calendar_links('http://static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-live-dec-13-2016-9am-mst.ics');

								$add_to_calendar_links_html = "";
								foreach($add_to_calendar_links as $name => $link){
									$add_to_calendar_links_html .= '<li class="k2-ksn-add-to-cal-link"><a href="' . $link . '" target="_blank">' . $name . '</a></li>';
								}

							?>
							<a id="k2_ksn_add_to_calendar_menu" href="javascript:;">Mark your calendar</a>
							<script>
								$(document).ready(function(){
									$('#k2_ksn_add_to_calendar_menu, .k2-ksn-add-to-cal-link a').click(function(){
										$('#k2_ksn_add_to_calendar_list').toggle();
									});
									$('.komando-shopping-network-container').click(function(e){
										current_element = e.toElement;
										if('k2_ksn_add_to_calendar_menu' !== current_element.id){
											$('#k2_ksn_add_to_calendar_list').hide();
										}
									});
								});
							</script>
						</h2>
						<ul id="k2_ksn_add_to_calendar_list"><?php echo $add_to_calendar_links_html; ?></ul>
						<p class="k2-ksn-no-margin">Watch the live show at <a href="http://www.komando.com">Komando.com</a>, <a href="https://www.facebook.com/kimkomando/">Facebook</a> or <a href="https://www.youtube.com/kimkomando/">YouTube</a></p>
						<p class="k2-ksn-no-margin-top k2-ksn-small-margin-bottom"><strong>Time:</strong> 8 a.m. PST/11 a.m. EST </p>
					</div>
				</div>
			</div>
		</div>
		<div class="bs-row k2-ksn-middle-reasons k2-ksn-large-padding k2-ksn-background-beige">
			<div class="col-xs-12">
				<div class="bs-row">
					<div class="col-xs-2">
						&nbsp;
					</div>
					<div class="col-md-8 col-xs-12 k2-ksn-all-caps k2-ksn-medium-headline k2-ksn-centered">
						3 reasons why you need to attend my KSN event
					</div>
					<div class="col-xs-2">
						&nbsp;
					</div>
				</div>
				<div class="bs-row">
					<div class="bs-row">
						<div class="col-md-2 col-xs-12"></div>
						<div class="col-md-8 col-xs-12">
							<div class="col-md-4 col-xs-12 k2-ksn-centered">
								<img class="k2-ksn-reason-img" alt="Komando Shopping Network" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/3838-ele-1.png">
								<div class="k2-ksn-reason-text">Get FREE shipping on orders $50 and over. The hot stuff sells quickly, so don't wait to buy!</div>
							</div>
							<div class="col-md-4 col-xs-12 k2-ksn-centered">
								<img class="k2-ksn-reason-img" alt="Komando Shopping Network" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/3838-ele-2.png">
								<div class="k2-ksn-reason-text">Hand-picked great tech gear selected for you by USA Today Columnist and National Radio host, Kim Komando.</div>
							</div>
							<div class="col-md-4 col-xs-12 k2-ksn-centered">
								<img class="k2-ksn-reason-img" alt="Komando Shopping Network" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/3838-ele-3.png">
								<div class="k2-ksn-reason-text">Brand new products revealed during the show.</div>
							</div>
						</div>
						<div class="col-md-2 col-xs-12"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="bs-row k2-ksn-large-padding">
			<div class="col-xs-12">
					<div class="col-md-6 col-xs-12">
						<div class="bs-row">
							<div class="col-xs-12 k2-ksn-small-padding">
								<div class="k2-ksn-all-caps"><strong>Visit my shop</strong></div>
								<p><strong>While I've got your attention, have you visited my shop lately?</strong> I update it regularly with the latest gadgets to fit your everyday life.</p>
								<a href="http://shop.komando.com" class="btn btn-yellow ksn-btn-buy-now">Start shopping now</a>
							</div>
						</div>
						<div class="bs-row">
							<div class="col-xs-12 k2-ksn-small-padding">
								<div class="k2-ksn-all-caps"><strong>Join Kim's Club</strong></div>
								<p>Not a Kim's Club member? Start your free trial now. Stream my show directly to your TV, computer, smartphone or tablet. Watch and listen to the show anytime you want. You won’t miss a thing.</p>
								<a href="http://club.komando.com" class="btn btn-yellow ksn-btn-buy-now">Sign up for FREE</a>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-12 k2-ksn-small-padding">
						<div class="k2-ksn-all-caps"><strong>Sign up for my free newsletters</strong></div>
						<p>Get must-have information delivered right to your inbox every day. From tips to improve your digital life and the latest tech news to special offers in my shop, my daily email newsletters have everything you need to navigate the digital world.</p>
						<p>Don't forget to sign up for my KSN Insider list! You'll be the first to learn about all the great products and exclusive offers that only you will know about. Get a first peek at great gadgets I've personally hand-picked and put on sale at unbelievable prices.</p>
						<a href="http://club.komando.com/newsletters" class="btn btn-yellow ksn-btn-buy-now">Sign up for FREE</a>
					</div>
				</div>
			</div>
			<div class="col-xs-12 ksn-top-message ksn-top-message-margin-not">
<?php
/*			<div class="col-md-6 col-xs-12 ksn-video" id="ksn-video-frame">
				<video id="ksn_promo_vid" controls width="400"><source src="http://bitcast-a.v1.sjc1.bitgravity.com/weststar/free/www/ksn/2016/ksn-promo-clip.mp4" type="video/mp4"></video>
			</div>
*/
?>
			<div class="col-md-6 col-xs-12 ksn-video" id="ksn-video-frame">
				<img src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/kim-ksn-pic.jpg" />
			</div>
			<div class="col-md-6 col-xs-12 ksn-message ksn-message-text">
				<h2>A MESSAGE FROM ME, KIM KOMANDO</h2>
				<p>Did you miss my Cyber Monday KSN show? We had such a spectacular event and showcased amazing products that, by popular demand, we decided to have another one for last minute shopping ideas before Christmas! During the KSN shows, I'm able to offer my viewers a way to learn about the products available in my online shop, and give everyone the opportunity to see them in use, and buy them right on the spot.</p>
				<p>Don't miss my best-selling last minute gifts KSN event! <a href="http://static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-live-dec-13-2016-9am-mst.ics" target="_blank">Mark your calendar.</a></p>
			</div>
		</div>
		</div>

	<script>
		function resizeVideo(width, height){
			$("#ksn_promo_vid").width(width);
			$("#ksn_promo_vid").height(height);
		}

		function doResize(){
			var width = 400;
			var height = 400;
			if(750 >= $(window).width()){
				width = $(window).width()*0.75;
			}
			else{
				width = $(window).width()*0.45;
			}

			width = Math.min(400, width);

			resizeVideo(width, width*0.5625);
		}

		$(document).ready(function(){
			$( window ).resize(function() {
				doResize();
			});
			doResize();
		});
	</script>

<?php } elseif('during' == $state){
	/**
	 *
	 *  DURING STATE
	 *
	 */
	?>
		<div class="komando-shopping-network-container k2-ksn-global-rules">
			<div class="bs-row">
				<div class="col-xs-12 k2-ksn-top-heading-2 k2-ksn-mobile-hide">
					<div class="bs-row">
						<div class="col-xs-12">
							<style>
								.wrapper  {
									border: none;
								}
							</style>
							<img src="//static.komando.com/websites/www.komando.com/2016/12/ksn/landing-banner-2.jpg">
							<div class="k2-ksn-top-2-video-box"><!-- auto generated content --></div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 k2-ksn-top-heading-2-mobile k2-ksn-mobile-show-only">
					<div class="bs-row">
						<div class="col-xs-12">
							<style>
								.wrapper  {
									border: none;
								}
							</style>
							<img src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/during/ksn-mobile-banner2.jpg">
							<div class="k2-ksn-top-2-video-box-mobile"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="bs-row">
				<div class="col-xs-12">
					<div class="bs-row k2-ksn-top-links-container">
						<span class="k2-ksn-top-links"><a href="http://shop.komando.com" target="_blank">Shop Now</a></span>
						<span class="k2-ksn-top-links"><a href="http://club.komando.com" target="_blank">Join Kim's Club for extra savings</a></span>
					</div>
				</div>
				<div id="ksn_top_thumbnail_container" class="bs-row"><!-- auto generated content --></div>
				<div class="bs-row k2-ksn-middle-reasons k2-ksn-large-padding k2-ksn-background-beige">
					<div class="col-xs-12">
						<div class="bs-row">

							<div class="col-md-6 col-xs-12 ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/gamer-eye-fatigue-glasses">
											<img alt="Protective Anti-Reflective Gaming Glasses" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/gamer-glasses.png">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Protective Anti-Reflective Gaming Glasses</span>
										<span class="desc">Longer game play with less eye strain. Prevent glare-induced headaches and future ocular problems with these gadgets.</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$24.95</strong></s>
											<br />KSN show price: <strong>$19.95</strong></p>
											<a href="http://shop.komando.com/gamer-eye-fatigue-glasses" class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="col-md-6 col-xs-12   ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/digital-eyestrain-protection">
											<img alt="Protective Anti-Glare Computer, Tablet and Phone Eyewear" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/eyestrain-glasses.png">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Protective Anti-Glare Computer, Tablet and Phone Eyewear</span>
										<span class="desc">Don't let eye strain cause problems. Protect your vision now!</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$24.95</strong></s>
												<br />KSN show price: <strong>$19.95</strong>
											<br />Buy 2 or more: <strong>$16.95 ea</strong></p>
											<a href="http://shop.komando.com/digital-eyestrain-protection" class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

						</div>
						<div class="bs-row">

							<div class="col-md-6 col-xs-12  ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/komandotm-hd-video-camera-spy-pen" >
											<img alt="Komando&trade; Multi-Use Ballpoint Pen with Covert AV Capabilities" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/spy-pen.png">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Komando&trade; Multi-Use Ballpoint Pen with Covert AV Capabilities</span>
										<span class="desc">Capture crystal clear sound, record high definition movies and take brilliant pictures with a stylish writing instrument.</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$59.99</strong></s>
												<br />KSN show price: <strong>$44.95</strong></p>
											<a href="http://shop.komando.com/komandotm-hd-video-camera-spy-pen" class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="col-md-6 col-xs-12   ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/all-in-one-media-docking-station">
											<img alt="Universal USB 3.0 Device and Dual Monitor Docking Station" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/docking-station.jpg">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Universal USB 3.0 Device and Dual Monitor Docking Station</span>
										<span class="desc">Complete connectivity all-in-one solution for multiple media devices and accessories. For flexible connectivity!</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$199.95</strong></s>
												<br />KSN show price: <strong>$179.00</strong></p>
											<a href="http://shop.komando.com/all-in-one-media-docking-station" class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

						</div>

						<div class="bs-row">

							<div class="col-md-6 col-xs-12  ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/usb-wall-valet-3pk " >
											<img alt="Dual Wall Outlet and USB Combo - 3PK" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/usb-wall-outlet.png">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Wall Outlet and USB Charging Station - 3 Pack</span>
										<span class="desc">Convenient wall charging station with dual USB ports. Power up multiple devices at once!</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$59.97</strong></s>
												<br />KSN show price: <strong>$39.95</strong></p>
											<a href="http://shop.komando.com/usb-wall-valet-3pk " class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

							<div class="col-md-6 col-xs-12   ksn-grid-products">
								<div class="row ksn-shopping-products">
									<div class="col-md-6 col-xs-12 ksn-box-products">
										<a href="http://shop.komando.com/komando-rotating-dual-lens-dash-cam2 ">
											<img alt="Komando Reliable, Easy-to-use HD Dual Lens Dash Cam Driving Recorder" src="<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/dec-13/dash-cam.png">
										</a>
									</div>
									<div class="col-md-6 col-xs-12 ksn-box-details">
										<span class="heading">Komando Reliable, Easy-to-use HD Dual Lens Dash Cam Driving Recorder</span>
										<span class="desc">Keep a record of what's happening around you. The facts will speak for themselves!</span>
										<div class="ksn-box-details-content">
											<p class="k2-ksn-prices-display">Retail value: <s><strong>$184.99</strong></s>
												<br />KSN show price: <strong>$149.95</strong></p>
											<a href="http://shop.komando.com/komando-rotating-dual-lens-dash-cam2 " class="btn btn-yellow ksn-btn-buy-now">Buy Now</a>
										</div>
									</div>
									<div style="clear: both"></div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="bs-row">

			<div class="bs-row k2-ksn-large-padding">
				<div class="col-xs-12">
					<div class="col-md-6 col-xs-12">
						<div class="bs-row">
							<div class="col-xs-12 k2-ksn-small-padding">
								<div class="k2-ksn-all-caps"><strong>Visit my shop</strong></div>
								<p><strong>While I've got your attention, have you visited my shop lately?</strong> I update it regularly with the latest gadgets to fit your everyday life.</p>
								<a href="http://shop.komando.com/featured/ksn-featured-products" class="btn btn-yellow ksn-btn-buy-now">Start shopping now</a>
							</div>
						</div>
						<div class="bs-row">
							<div class="col-xs-12 k2-ksn-small-padding">
								<div class="k2-ksn-all-caps"><strong>Join Kim's Club</strong></div>
								<p>Not a Kim's Club member? Start your free trial now. Stream my show directly to your TV, computer, smartphone or tablet. Watch and listen to the show anytime you want. You won’t miss a thing.</p>
								<a href="http://club.komando.com" class="btn btn-yellow ksn-btn-buy-now">Sign up for FREE</a>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-xs-12 k2-ksn-small-padding">
						<div class="k2-ksn-all-caps"><strong>Sign up for my free newsletters</strong></div>
						<p>Get must-have information delivered right to your inbox every day. From tips to improve your digital life and the latest tech news to special offers in my shop, my daily email newsletters have everything you need to navigate the digital world.</p>
						<p>Don't forget to sign up for my KSN Insider list! You'll be the first to learn about all the great products and exclusive offers that only you will know about. Get a first peek at great gadgets I've personally hand-picked and put on sale at unbelievable prices.</p>
						<a href="http://club.komando.com/newsletters" class="btn btn-yellow ksn-btn-buy-now">Sign up for FREE</a>
					</div>
				</div>
			</div>
<!--
### Removed Per Sandra 2016-12-09 16:09 ##
			<div class="col-xs-12 ksn-top-message ksn-top-message-margin-not">
				<div class="col-md-6 col-xs-12 ksn-video" id="ksn-video-frame">
					<video id="ksn_promo_vid" controls width="400"><source src="http://bitcast-a.v1.sjc1.bitgravity.com/weststar/free/www/ksn/2016/ksn-promo-clip.mp4" type="video/mp4"></video>
				</div>
				<div class="col-md-6 col-xs-12 ksn-message ksn-message-text">
					<h2>A MESSAGE FROM ME, KIM KOMANDO</h2>
					<p>Did you miss my first KSN show? We had such a spectacular event last month that, by popular demand, we decided to have another one! I was able to offer my viewers a way to learn about the products available in my online shop, and give everyone the opportunity to see them in use, and buy them right on the spot.</p>
					<p>Don't miss this Cyber Monday's KSN event! Watch the video for details.</p>
				</div>
			</div>
-->
		</div>

			<script>

				const k2_ksn_video_display = {
					videos: [],
					addProduct: function(video){
						this.videos.push(video);
					},
					generateDisplay: function(){
						let display = {
							playing: "",
							playing_mobile: "",
							played: [],
						};
						let last_video_index = (this.videos.length - 1);
						for(let i in this.videos){
							if(0 == i){
								display.played.push(`<div class="col-md-1 col-xs-12">&nbsp;</div>`);
							}

							if(i == last_video_index){
								display.playing = this.generateYouTubeEmbedCode(this.videos[i], 400);
								display.playing_mobile = this.generateYouTubeEmbedCodeMobile(this.videos[i], 400);
								display.played.push(`<div class="col-md-${11 - parseInt(last_video_index)} col-xs-12">&nbsp;</div>`);
							}
							else{
								display.played.push(this.generateThumbnail(this.videos[i]));
							}
						}
						return display;
					},
					updateDisplay: function(){
						let display = this.generateDisplay();
						let top_video_box = document.querySelector('.k2-ksn-top-2-video-box');
						let top_video_box_mobile = document.querySelector('.k2-ksn-top-2-video-box-mobile');
						let top_thumbnail_container = document.querySelector('#ksn_top_thumbnail_container');
						top_video_box.innerHTML = display.playing;
						top_video_box_mobile.innerHTML = display.playing_mobile;
						top_thumbnail_container.innerHTML = display.played.join(' ');
						/*

						 <div class="col-md-1 col-xs-12">&nbsp;</div>
						 <div class="col-md-1 col-xs-12">&nbsp;</div>

						 */
					},
					generateYouTubeUrl: function(video){
						return `https://youtu.be/${video.video}`;
					},
					generateThumbnail: function(video){
						return `
						<div class="col-md-2 col-xs-12 k2-ksn-product-vid-thumb"><a href="${this.generateYouTubeUrl(video)}">
								<img src="${video.thumbnail}"><br /><p>${video.text}</p></a>
						</div>
						`
					},
					generateYouTubeEmbedCode: function(video, width){
						return `<iframe id="k2_ksn_top_video_iframe" width="${width}" src="https://www.youtube.com/embed/${video.video}" frameborder="0" allowfullscreen></iframe>`;
					},
					generateYouTubeEmbedCodeMobile: function(video, width){
						return `<iframe id="k2_ksn_top_video_iframe_mobile" width="${width}" src="https://www.youtube.com/embed/${video.video}" frameborder="0" allowfullscreen></iframe>`;
					},
					resizeVideo: function(element_id, max_width = 400){
						let video = document.querySelector('#' + element_id);
						if(!video){
							console.log('Couldn\'t resize #' + element_id);
							return false;
						}
						let width = max_width;
						if(768 >= $(window).width()){
							width = $(window).width()*0.75;
						}
						else{
							width = $(window).width()*0.45;
						}
						width = Math.min(max_width, width);
						video.width = width;
						video.height = width*0.5625;
						console.log('Resized #' + element_id);
						return true;
					}
				};

				const video_data = [
					{
						id: 1,
						text: "Protective Anti-Reflective Gaming Glasses and Anti-Glare Device Eyewear",
						thumbnail: "//static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-video-glasses.jpg",
						video: "x3k2g-d300Y",
						active: 1
					},
					{
						id: 2,
						text: "Komando&trade; Multi-Use Ballpoint Pen with Covert AV Capabilities",
						thumbnail: "//static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-video-spypen.jpg",
						video: "abjCxLvgWbE",
						active: 1
					},
					{
						id: 3,
						text: "Universal USB 3.0 Device and Dual Monitor Docking Station",
						thumbnail: "//static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-video-docking.jpg",
						video: "yvD7T7ee-9s",
						active: 1
					},
					{
						id: 4,
						text: "Wall Outlet and USB Charging Station - 3 Pack",
						thumbnail: "//static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-video-usb-wall.jpg",
						video: "yJHyB0yuJko",
						active: 1
					},
					{
						id: 5,
						text: "Best Seller! Komando Reliable, Easy-to-use HD Dual Lens Dash Cam Driving Recorder",
						thumbnail: "//static.komando.com/websites/www.komando.com/2016/12/ksn/ksn-video-dashcam.jpg",
						video: "chKwfXiP_Tc",
						active: 1
					},
					{
						id: 6,
						text: "Recap of All Products",
						thumbnail: "<?php echo k2_get_static_url('v2'); ?>/img/www/komando-shopping-network/during/thumbnail-vid.jpg",
						video: "uetceYqZtTs",
						active: 1
					}
				];

				<?php if(isset($_GET['thumbs']) && is_numeric($_GET['thumbs'])){ ?>
					let to_display = <?php echo $_GET['thumbs']; ?>;
					for(i in video_data){
						if(i <= (to_display - 1)){
							video_data[i].active = 1;
						}
					}

				<?php } ?>

				$(document).ready(function(){
					for(let i in video_data){
						if(1 === video_data[i].active){
							k2_ksn_video_display.addProduct(video_data[i]);
						}
					}
					k2_ksn_video_display.updateDisplay();
					$( window ).resize(function() {
						if($(window).outerWidth() > 1180){
							k2_ksn_video_display.resizeVideo('k2_ksn_top_video_iframe', 400);
						}
						else if($(window).outerWidth() > 900){
							k2_ksn_video_display.resizeVideo('k2_ksn_top_video_iframe', 300);
						}
						else{
							k2_ksn_video_display.resizeVideo('k2_ksn_top_video_iframe', 250);
						}
						k2_ksn_video_display.resizeVideo('k2_ksn_top_video_iframe_mobile', 650);
					});
					$( window ).resize();
				});

				function resizeVideo(width, height){
					$("#ksn_promo_vid").width(width);
					$("#ksn_promo_vid").height(height);
				}

				function doResize(){
					var width = 400;
					var height = 400;
					if(750 >= $(window).width()){
						width = $(window).width()*0.75;
					}
					else{
						width = $(window).width()*0.45;
					}

					width = Math.min(400, width);

					resizeVideo(width, width*0.5625);
				}

				$(document).ready(function(){
					$( window ).resize(function() {
						doResize();
					});
					doResize();
				});
			</script>
<?php } ?>

	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery-1.10.2.min.js"></script>

	<script>
		$(document).ready( function(){
			$(window).resize(function(){
				if($(window).width() < 500){
					$("#ksn-video-frame iframe").width($(window).width() - 100);
					$("#ksn-video-frame iframe").height($("#ksn-video-frame iframe").width() * (9/16));
				}
				else if($(window).width() < 900){
					$("#ksn-video-frame iframe").width(300);
					$("#ksn-video-frame iframe").height(197);
				}
				else{
					$("#ksn-video-frame iframe").width(400);
					$("#ksn-video-frame iframe").height(225);
				}
			})
		})
	</script>

<?php get_footer(); ?>
