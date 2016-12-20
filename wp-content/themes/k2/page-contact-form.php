<?php 

/*
Template Name: Contact Form
*/

get_header(); 

?>
	
<div class="post-type-banner arrow arrow-post-type">Contact Kim</div>
<section class="content-left" role="main">
		
	<div class="clearfix">
		<div class="contact-page-kim hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/contact-page-kim.png" /></div>
		<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
	</div>
	
	<div class="contact-options">
		<div id="contact-button-ask-kim" class="contact-well contact-option" data-form-type="contact-ask-kim" data-form-hash="askkim">
			<span>Ask Me a Question</span>
			Can't find the digital answers you need? Send me an email.
		</div>

		<div id="contact-button-ask-orders" class="contact-well">
			<span>Orders and Kim's Club</span>
			First time visitor? Product question? Membership inquiry?
		</div>

		<div id="contact-button-ask-newsletters" class="contact-well contact-option" data-form-type="contact-newsletters" data-form-hash="newsletters">
			<span>Newsletter Subscriptions</span>
			Newsletter questions that aren't answered in my <a href="/faqs">FAQ's</a>.
		</div>

		<div id="contact-button-cool-site" class="contact-well contact-option" data-form-type="contact-cool-site" data-form-hash="coolsite">
			<span>Cool Site Submission</span>
			Have you visited a website that you think others would enjoy?
		</div>

		<div id="contact-button-ad-feedback" class="contact-well contact-option" data-form-type="contact-advertiser-feedback" data-form-hash="adfeedback">
			<span>Advertiser Feedback</span>
			Comment, praise or problem with one of my advertisers?
		</div>

		<div id="contact-button-web-feedback" class="contact-well contact-option" data-form-type="contact-website-feedback" data-form-hash="webfeedback">
			<span>Website Feedback</span>
			Having issues accessing my website? Compliments?
		</div>

		<div id="contact-button-advertising" class="contact-well contact-option" data-form-type="contact-advertising" data-form-hash="advertise">
			<span>Advertising</span>
			Want to advertise nationally with America's Digital Goddess?
		</div>

		<div id="contact-button-pr" class="contact-well contact-option" data-form-type="contact-pr" data-form-hash="pr">
			<span>Press Releases</span>
			Want to send in a news or product release?
		</div>

		<div id="contact-button-make-an-appointment" class="contact-well contact-well contact-well-large contact-option" data-form-type="contact-make-an-appointment" data-form-hash="appointment">
			<span>Make an Appointment</span>
			For the chance to be on the live show with Kim.
		</div>

		<div id="contact-button-address" class="contact-well contact-well-large">
			<span>Want to send a real honest to goodness letter?</span>
			Kim Komando<br />
			c/o WestStar Multimedia Entertainment, Inc.<br />
			The WestStar Building<br />
			6135 N. 7th Street<br />
			Phoenix, AZ 85014-1855 USA
		</div>

		<div id="contact-button-affiliates" class="contact-well contact-well-large contact-option" data-form-type="contact-affiliates" data-form-hash="affiliates">
			<span>Want to add Kim Komando to your radio station?</span>
			Contact WestStar Affiliate Relations<br />
			<strong>Phone:</strong> (602) 381-8200 ext. 211<br />
			<strong>E-mail:</strong> Affiliate Relations
		</div>

	</div>

	<div class="contact-order-options">
		<div id="contact-button-placing-trouble" class="contact-well contact-option" data-form-type="contact-order-trouble" data-form-hash="placingorder">
			<span>Placing an Order</span>
			My staff will be glad to help you place an order in my store.
		</div>

		<div id="contact-button-return-order" class="contact-well contact-option" data-form-type="contact-order-return" data-form-hash="productquestions">
			<span>Kim's Store Products</span>
			Questions about an item? Need to make a return? My staff is happy to help!
		</div>

		<div id="contact-button-club-issues" class="contact-well contact-option" data-form-type="contact-club-access" data-form-hash="kimsclubquestions">
			<span>Kim's Club Access</span>
			Need help navigating all that membership has to offer? Start here.
		</div>

		<div id="contact-button-club-billing" class="contact-well contact-option" data-form-type="contact-club-billing" data-form-hash="kimsclubbilling">
			<span>Kim's Club Billing</span>
			Questions about Kim's Club billing? Ask here.
		</div>
	</div>

	
	<div class="contact-form-wrapper clearfix">
		<div class="close-form" title="Go back"><i class="fa fa-reply fa-lg"></i></div>
		<div class="contact-information">
			<div class="contact-ask-kim hide">
				<h3>Ask Me a Question</h3>
				<p>If you would like to send me a message, please fill out the form below, type your message, and click "send" when you're done.</p>
				<p>I love to get email from my fans. Please keep in mind that due to the tremendous volume of messages, personal replies simply aren't possible. I do my best to answer your questions through our free email newsletters and on my show.</p>
			</div>

			<div class="contact-order-trouble hide">
				<h3>Placing an order</h3>
				<p>To place an order in my store, just click the "Add to Cart" button on any product page. Your virtual shopping cart is always waiting for you in the upper upper right-hand corner of the screen. Just click "Checkout" and follow the screen prompts to complete your order.</p>
				<p>If you need additional assistance, please fill out the form below. My store staff will be more than happy to help.</p>
				<p>To avoid delays in service, please remember to be specific, providing as many details as possible, including your contact information.</p>
				<p>You can also call 1-800-KOMANDO if you need more help. Thanks for shopping in my store!</p>
				<p><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-signature.png" alt="- Kim" /></p>
			</div>

			<div class="contact-order-return hide">
				<h3>Kim's Store Products</h3>
				<p>You will find a variety of products in my store. If you have any questions about a product that is not already covered on the product description pages, just fill out this form.</p>
				<p>To avoid delays in service, please remember to be specific, providing as many details as possible, including your contact information.</p>
				<p>If you need to return an item, please be sure to include the order number and the reason for your return. Your feedback helps me make future product-buying decisions.</p>
				<p>You can also call 1-800-KOMANDO if you need more help. Thanks for shopping in my store!</p>
				<p><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-signature.png" alt="- Kim" /></p>
			</div>

			<div class="contact-club-access hide">
				<h3>Kim's Club Access</h3>
				<p>If you're a Kim's Club member, thank you for joining!</p>
				<p>My staff is here to assure you have total access to all of your membership perks at Komando.com. For assistance, just fill out this form.</p>
				<p>To avoid delays in service, please remember to be specific, providing as many details as possible, including your contact information.</p>
				<p>You can also call 1-800-KOMANDO regarding your membership access questions. To become a member, <a href="<?php echo CLUB_BASE_URI; ?>/products">click here</a>.</p>
				<p>Thanks!</p>
				<p><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-signature.png" alt="- Kim" /></p>
			</div>

			<div class="contact-club-billing hide">
				<h3>Kim's Club Billing</h3>
				<p>Thank you for joining Kim's Club!</p>
				<p>If you have a question about your membership billing, my staff is here for you. Just fill out the this form for assistance.</p>
				<p>To avoid delays in service, please remember to be specific, providing as many details as possible, including your contact information.</p>
				<p>You can also call 1-800-KOMANDO regarding membership billing questions. If you would like to become a member, please <a href="<?php echo CLUB_BASE_URI; ?>/products">click here</a>.</p>
				<p>Thanks!</p>
				<p><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-signature.png" alt="- Kim" /></p>
			</div>

			<div class="contact-newsletters hide">
				<h3>Newsletter Subscriptions</h3>
				<p>If my <a href="<?php bloginfo('url') ?>/faqs">Frequently Asked Questions</a> page doesn't cover your newsletter management questions or you just need some additional assistance, send an email using this form.</p>
				<p>Please be specific and include any email addresses involved in your inquiry:
					<ul>
						<li>Any email addresses involved</li>
						<li>The names and dates of newsletters you're inquiring about</li>
						<li>The best way we can assist you</li>
					</ul>
				</p>
				<p>To avoid delays in service, please remember to be specific, providing as many details as possible, including your contact information.</p>
				<p>Thanks!</p>
				<p><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-signature.png" alt="- Kim" /></p>
			</div>

			<div class="contact-cool-site hide">
				<h3>Cool Site Submission</h3>
				<p>Have you visited a Web site that you think others would enjoy? Let me know.</p>
			</div>

			<div class="contact-advertiser-feedback hide">
				<h3>Advertiser Feedback</h3>
				<p>Your satisfaction is important to me, so please use this form if you would like to send a question, comment, concern or feedback regarding any of my advertisers.</p>
			</div>

			<div class="contact-website-feedback hide">
				<h3>Website Feedback</h3>
			</div>

			<div class="contact-advertising hide">
				<h3>Advertising</h3>
			</div>

			<div class="contact-pr hide">
				<h3>Press Releases</h3>
			</div>

			<div class="contact-affiliates hide">
				<h3>Affiliate Relations</h3>
			</div>

			<div class="contact-make-an-appointment hide">
				<h3>Great news! You can now make an appointment to personally speak directly with Kim!</h3>
			</div>

		</div>
		<div class="contact-form">
			<form id="contact-form">
				<div class="control-group control-group-half contact-advertising contact-pr hide">
					<label for="contact-company-name" class="control-label">Company Name</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-company-name" name="contact-company-name" />
					</div>
				</div>

				<div class="control-group control-group-half contact-advertising contact-pr hide">
					<label for="contact-company-url" class="control-label">Company URL</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-company-url" name="contact-company-url" />
					</div>
				</div>

                <div class="control-group control-group-half contact-full-name">
                    <label for="contact-full-name" class="control-label">Full Name *</label>
                    <div class="controls">
                        <input type="text" class="blue input-block-level" id="contact-full-name" name="contact-full-name" required="required" />
                    </div>
                </div>

                <div class="control-group control-group-half contact-first-name">
                    <label for="contact-first-name" class="control-label">First Name *</label>
                    <div class="controls">
                        <input type="text" class="blue input-block-level" id="contact-first-name" name="contact-first-name" required="required" />
                    </div>
                </div>

                <div class="control-group control-group-half contact-last-name">
                    <label for="contact-last-name" class="control-label">Last Name *</label>
                    <div class="controls">
                        <input type="text" class="blue input-block-level" id="contact-last-name" name="contact-last-name" required="required" />
                    </div>
                </div>

                <div class="control-group control-group-half contact-advertising hide">
					<label for="contact-title" class="control-label">Title</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-title" name="contact-title" />
					</div>
				</div>

				<div class="control-group control-group-half contact-email">
					<label for="contact-email" class="control-label">Email *</label>
					<div class="controls">
						<input type="email" class="blue input-block-level" id="contact-email" name="contact-email" required="required" />
					</div>
				</div>

				<div class="control-group control-group-half contact-phone">
					<label for="contact-phone" class="control-label">Phone Number *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-phone" name="contact-phone" required="required" />
					</div>
				</div>

				<div class="control-group contact-address">
					<label for="contact-address" class="control-label">Street Address *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-address" name="contact-address" required="required" />
					</div>
				</div>

				<div class="control-group contact-address">
					<label for="contact-city-state-postal" class="control-label">City, State &amp; Postal Code *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-city-state-postal" name="contact-city-state-postal" required="required" />
					</div>
				</div>

				<div class="control-group contact-order-return hide">
					<label for="contact-order-number" class="control-label">Order Number</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-order-number" name="contact-order-number" />
					</div>
				</div>

				<div class="control-group contact-website-feedback hide">
					<label for="contact-problem-page" class="control-label">Page Where Problem Found (Page name or URL) *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-problem-page" name="contact-problem-page" />
					</div>
				</div>

				<div class="control-group control-group-half contact-ask-kim hide">
					<label for="contact-age" class="control-label">Age *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-age" name="contact-age" required="required" />
					</div>
				</div>

				<div class="control-group control-group-half contact-ask-kim hide">
					<label for="contact-station" class="control-label">Station You Listen On *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-station" name="contact-station" required="required" />
					</div>
				</div>

				<div class="control-group contact-advertiser-feedback hide">
					<label for="contact-advertiser-name" class="control-label">Name of Company/Ad</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-advertiser-name" name="contact-advertiser-name" />
					</div>
				</div>

				<div class="control-group contact-advertiser-feedback hide">
					<label for="contact-advertiser-location" class="control-label">Where You Saw/Heard the Ad</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-advertiser-location" name="contact-advertiser-location" />
					</div>
				</div>

				<div class="control-group contact-cool-site hide">
					<label for="contact-cool-site-url" class="control-label">Website Address *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-cool-site-url" name="contact-cool-site-url" />
					</div>
				</div>

				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-name" class="control-label">Full Name *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-make-an-appointment-name" name="contact-make-an-appointment-name" />
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-gender" class="control-label">Gender *</label>
					<div class="controls">
						<label class="control-label"><input type="radio" id="contact-make-an-appointment-gender-male" name="contact-make-an-appointment-gender" value="male"> Male</label>
						<label class="control-label"><input type="radio" id="contact-make-an-appointment-gender-female" name="contact-make-an-appointment-gender" value="female"> Female</label>
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-email" class="control-label">Email *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-make-an-appointment-email" name="contact-make-an-appointment-email" />
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-phone" class="control-label">Phone number to best call you back at:<br /> (please include area code) *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-make-an-appointment-phone" name="contact-make-an-appointment-phone" />
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-city" class="control-label">City *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-make-an-appointment-city" name="contact-make-an-appointment-city" />
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-state" class="control-label">State or Province *</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-make-an-appointment-state" name="contact-make-an-appointment-state" />
					</div>
				</div>
				<div class="control-group contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-spoken-previously" class="control-label">Have you spoken to Kim previously? *</label>
					<div class="controls">
						<label class="control-label"><input type="radio" id="contact-make-an-appointment-spoken-previously-yes" name="contact-make-an-appointment-spoken-previously" value="yes"> Yes</label>
						<label class="control-label"><input type="radio" id="contact-make-an-appointment-spoken-previously-no" name="contact-make-an-appointment-spoken-previously" value="no"> No</label>
					</div>
				</div>
				<div class="control-group contact-make-an-appointment hide">
					<?php


					$date_time = new DateTime("13:00", new DateTimeZone("America/Phoenix"));
					$date_time_zone_eastern = new DateTimeZone("America/New_York");
					$date_time->setTimezone($date_time_zone_eastern);
					$time_eastern = $date_time->format("g");
					$date_time_zone_pacific = new DateTimeZone("America/Los_Angeles");
					$date_time->setTimezone($date_time_zone_pacific);
					$time_pacific = $date_time->format("g");

					?>
					<h2>Kim is on the air Fridays from <?php echo $time_pacific; ?> p.m. – <?php echo $time_pacific+3; ?> p.m. Pacific, <?php echo $time_eastern; ?> p.m. – <?php echo $time_eastern+3; ?> p.m. Eastern. What is the best time for you to speak with Kim? Tell us your local time. Thank you!</h2>
					<h5 class="control-label">Note: Please book your appointment time to occur during the Live radio Program hours (no private requests).</h5>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-date" class="control-label">Appointment Date *</label>
					<div class="controls">
						<input type="date" class="blue input-block-level" id="contact-make-an-appointment-date" name="contact-make-an-appointment-date" />
					</div>
				</div>
				<div class="control-group control-group-half contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-time" class="control-label">Appointment Time *</label>
					<div class="controls">
						<input type="time" class="blue input-block-level" id="contact-make-an-appointment-time" name="contact-make-an-appointment-time" value="13:00" />
					</div>
				</div>
				<div class="control-group contact-make-an-appointment hide">
					<label for="contact-make-an-appointment-question" class="control-label">Question you have for Kim: (in 100 words or less) *</label>
					<div class="controls">
						<textarea class="blue input-block-level" id="contact-make-an-appointment-question" name="contact-make-an-appointment-question"></textarea>
					</div>
				</div>

				<div class="control-group contact-advertising hide">
					<label for="contact-budget" class="control-label">Advertising Budget</label>
					<div class="controls">
						<select class="blue input-block-level" id="contact-budget" name="contact-budget">
							<option>$30,000 - $59,999</option>
							<option>$60,000 - $99,999</option>
							<option>Over $100,000</option>
						</select>
					</div>
				</div>

				<div class="control-group contact-message">
					<label for="contact-message" class="control-label">Message *</label>
					<div class="controls">
						<textarea class="blue input-block-level" id="contact-message" name="contact-message" required="required"></textarea>
					</div>
				</div>

				<div class="control-group contact-website-feedback contact-advertiser-feedback contact-cool-site hide">
					<label for="contact-description" class="control-label">Description *</label>
					<div class="controls">
						<textarea class="blue input-block-level" id="contact-description" name="contact-description"></textarea>
					</div>
				</div>

				<div class="control-group contact-pr hide">
					<label for="contact-pr" class="control-label">Press Release *</label>
					<div class="controls">
						<textarea class="blue input-block-level" id="contact-pr" name="contact-pr"></textarea>
					</div>
				</div>

				<div class="control-group contact-advertising hide">
					<p>Please rank the following criteria as it applies to your interest in advertising with The Kim Komando Show. <br /><em>(Note: Left = Least important, Right = Most important)</em></p>
						<div class="control-group"><label for="advertiser-brand-recognition" class="control-label">Strengthen Brand Recognition</label> <input type="hidden" name="advertiser-brand-recognition" class="advertiser-brand-recognition"> <div id="advertiser-brand-recognition" class="contact-form-slider"></div></div>
						<div class="control-group"><label for="advertiser-b2b" class="control-label">Business-to-Business Sales</label> <input type="hidden" name="advertiser-b2b" class="advertiser-b2b"> <div id="advertiser-b2b" class="contact-form-slider"></div></div>
						<div class="control-group"><label for="advertiser-b2c" class="control-label">Business-to-Consumer Sales</label> <input type="hidden" name="advertiser-b2c" class="advertiser-b2c"> <div id="advertiser-b2c" class="contact-form-slider"></div></div>
						<div class="control-group"><label for="advertiser-customer-acquisition" class="control-label">Customer Name Acquisition</label> <input type="hidden" name="advertiser-customer-acquisition" class="advertiser-customer-acquisition"> <div id="advertiser-customer-acquisition" class="contact-form-slider"></div></div>
						<div class="control-group"><label for="advertiser-increase-exposure" class="control-label">Increase Product Exposure</label> <input type="hidden" name="advertiser-increase-exposure" class="advertiser-increase-exposure"> <div id="advertiser-increase-exposure" class="contact-form-slider"></div></div>
				</div>
				<div class="control-group contact-advertising hide">
						<p>Have you previously spoken to a advertising representative?</p>
						<label for="advertising-prev-spoken-yes" class="control-label"><input name="advertising-prev-spoken" id="advertising-prev-spoken-yes" type="radio" value="Yes"> Yes</label> 
						<label for="advertising-prev-spoken-no" class="control-label"><input name="advertising-prev-spoken" id="advertising-prev-spoken-no" type="radio" value="No" checked="checked"> No</label></label>
				</div>

				<div class="control-group">
					<label for="newsletter-subscribe" class="control-label">Yes, please subscribe me to your newsletters.</label>
					<div class="controls newsletter-subscribe-contact-us">
						<input type="checkbox" checked id="newsletter_subscribe" name="newsletter-subscribe">
					</div>
				</div>

				<?php 
					$ip = $_SERVER['REMOTE_ADDR'];
					$referer = $_SERVER['HTTP_REFERER'];
					$timestamp = $_SERVER['REQUEST_TIME'];
					$useragent = $_SERVER['HTTP_USER_AGENT'];

					$info_string = wp_create_nonce("security") . '##' . $ip . '##' . $referer . '##' . $timestamp . '##' . $useragent;
					$encoded_info = base64_encode($info_string);
				?>

				<input type="hidden" name="contact-info" value="<?php echo $encoded_info; ?>" />
				<input type="hidden" name="screen-resolution" id="screen-resolution" value="" />
				<input type="hidden" name="form-type" id="contact-form-type" value="" />
				<p><button id="submit" class="btn btn-contact btn-large">Submit</button> <em class="contact-required">* Required fields</em></p>
			</form>
		</div>
	</div>		

	<div class="contact-spinner">
		<figure class="align-none"><img src="<?php echo k2_get_static_url('v2'); ?>/img/spinner.gif" alt="spinner" /></figure>
	</div>
	<div class="contact-success">
		<div class="alert alert-success"><strong>Thank you!</strong> We've received your email and will respond to you as soon as we can.</div>
	</div>
	<div class="contact-success-kim">
		<div class="alert alert-success"><strong>Thank you!</strong> <a href="javascript:void(0)" onClick="ga('send', 'event', 'Contact page', 'Click', 'Click through to subscribe modal');" data-modal="subscribe-modal">Sign-up now for my free newsletters.</a></div>
	</div>
	
	<div class="more-button">
		<div class="contact-button-go-back btn hide"><i class="fa fa-reply"></i> Go Back</div>
	</div>

</section>

<?php get_sidebar(); ?>

<link href="<?php echo k2_get_static_url('v2'); ?>/nouislider/jquery.nouislider.min.css" rel="stylesheet" type="text/css" />

<script>
jQuery(document).ready(function($) {

	$('.contact-form-slider').noUiSlider({ range: [1,5], start: 1, handles: 1, step: 1 });

	$('#contact-button-ask-orders').click(function() {
		$('.contact-options').slideUp();
		$('.contact-order-options').slideDown();
		$('.more-button div').addClass('hide');
		$('.contact-button-go-back').removeClass('hide');
		document.location.hash = 'orders';
	});

	$('.contact-button-go-back, .close-form').click(function() {
		var current_url = document.location.hash;

		$('.contact-button-go-back').addClass('hide');
		$('.contact-button-ask-more.more').removeClass('hide');
		$('.contact-options').slideDown(400, function() {
			$('body').animate({'scrollTop': ($('.contact-options').offset().top - 160) + 'px'}, 'fast');
		});
		$('#contact-cool-site-url, #contact-description, #contact-message, #contact-pr').removeAttr('required');
		$('.contact-order-options, .contact-form-wrapper').slideUp();
		$('.contact-advertising, .contact-store, .contact-pr, .contact-website-feedback, .contact-ask-kim, .contact-order-trouble, .contact-order-return, .contact-club-access, .contact-club-billing, .contact-newsletters, .contact-cool-site, .contact-advertiser-feedback, .contact-affiliates').addClass('hide');
		
		setTimeout(slide_up_timeout, 500);
		function slide_up_timeout() {
			$('.contact-address, .contact-message, .contact-phone').removeClass('hide');
			var validator = $('#contact-form').validate();
			validator.resetForm();
			
			if (current_url == '#placingorder' || current_url == '#productquestions' || current_url == '#kimsclubquestions' || current_url == '#kimsclubbilling') {
				$('#contact-button-ask-orders').trigger('click');
			} 
		}
	});

	$('.contact-option').click(function() {
		var form_type = $(this).attr('data-form-type');
		document.location.hash = $(this).attr('data-form-hash');
		
		if (form_type == 'contact-cool-site') {
			$('.contact-first-name, .contact-last-name, .contact-address, .contact-message, .contact-phone').addClass('hide');
			$('#contact-cool-site-url, #contact-description').attr('required', 'required');
		} else if (form_type == 'contact-make-an-appointment') {
            $('.contact-full-name, .contact-first-name, .contact-last-name, .contact-email, .contact-address, .contact-message, .contact-phone').addClass('hide');
            $('#contact-full-name, #contact-email').removeAttr('required');
            $('#contact-make-an-appointment-name, #contact-make-an-appointment-phone, #contact-make-an-appointment-state, #contact-make-an-appointment-date, #contact-make-an-appointment-time, #contact-make-an-appointment-question, #contact-make-an-appointment-email, #contact-make-an-appointment-city, input[name=contact-make-an-appointment-spoken-previously], input[name=contact-make-an-appointment-gender]').attr('required', 'required');
        }else if (form_type == 'contact-ask-kim') {
            $('.contact-full-name').addClass('hide');
            $('#contact-first-name, #contact-last-name, #contact-email, #contact-phone, #contact-address, #contact-city-state-postal, #contact-age, #contact-station, #contact-message').attr('required', 'required');
        } else if (form_type == 'contact-website-feedback') {
			$('.contact-first-name, .contact-last-name, .contact-address, .contact-message, .contact-phone').addClass('hide');
			$('#contact-description, #contact-problem-page').attr('required', 'required');
		} else if (form_type == 'contact-advertiser-feedback') {
			$('.contact-first-name, .contact-last-name, .contact-message').addClass('hide');
			$('#contact-description').attr('required', 'required');
		} else if (form_type == 'contact-pr') {
			$('.contact-first-name, .contact-last-name, .contact-message').addClass('hide');
			$('#contact-pr').attr('required', 'required');
		} else {
            $('.contact-first-name, .contact-last-name').addClass('hide');
			$('#contact-message').attr('required', 'required');
		}

		$('#contact-form-type').val(form_type);
		$('#screen-resolution').val(screen.width+'x'+screen.height);

		$('.contact-options, .contact-order-options').slideUp();
		$('.'+form_type).removeClass('hide');

		$('.contact-form-wrapper').slideDown(400, function() {
			$('body').animate({'scrollTop': ($('.contact-form-wrapper').offset().top - 160) + 'px'}, 'fast');
		});

		$('.more-button div').addClass('hide');
		$('.contact-button-go-back').removeClass('hide');
	});

	// Skip to the contact form
	var current_url = document.location.hash;

	if (typeof(current_url) != 'undefined' && current_url != null) {

		if (current_url == '#advertise') {
			$('#contact-button-advertising').trigger('click');

		} else if (current_url == '#askkim') {
			$('#contact-button-ask-kim').trigger('click');

		} else if (current_url == '#appointment') {
			$('#contact-button-make-an-appointment').trigger('click');

		} else if (current_url == '#newsletters') {
			$('#contact-button-ask-newsletters').trigger('click');

		} else if (current_url == '#coolsite') {
			$('#contact-button-cool-site').trigger('click');

		} else if (current_url == '#adfeedback') {
			$('#contact-button-ad-feedback').trigger('click');

		} else if (current_url == '#webfeedback') {
			$('#contact-button-web-feedback').trigger('click');

		} else if (current_url == '#pr') {
			$('#contact-button-pr').trigger('click');

		} else if (current_url == '#affiliates') {
			$('#contact-button-affiliates').trigger('click');

		} else if (current_url == '#placingorder') {
			$('#contact-button-placing-trouble').trigger('click');

		} else if (current_url == '#productquestions') {
			$('#contact-button-return-order').trigger('click');

		} else if (current_url == '#kimsclubquestions') {
			$('#contact-button-club-issues').trigger('click');

		} else if (current_url == '#kimsclubbilling') {
			$('#contact-button-club-billing').trigger('click');
		} else if (current_url == '#orders') {
			$('#contact-button-ask-orders').trigger('click');
		}
	};

	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>"; 
	// Validate and process contact form
	$('#contact-form').validate({
		onfocusout: false,
		highlight: function(element) {
			$(element).closest('.control-group').addClass('error');
		},
		unhighlight: function(element) {
			$(element).closest('.control-group').removeClass('error');
		},
		submitHandler: function(form) {
			if(jQuery('#contact-form-type').val() == 'contact-advertising') {
				jQuery('.advertiser-brand-recognition').val(jQuery('#advertiser-brand-recognition').val());
				jQuery('.advertiser-b2b').val(jQuery('#advertiser-b2b').val());
				jQuery('.advertiser-b2c').val(jQuery('#advertiser-b2c').val());
				jQuery('.advertiser-customer-acquisition').val(jQuery('#advertiser-customer-acquisition').val());
				jQuery('.advertiser-increase-exposure').val(jQuery('#advertiser-increase-exposure').val());
			}

			$('.more-button').addClass('hide');
			$('.contact-form-wrapper').slideUp();
			$('.contact-spinner').slideDown(function(){ $('body').animate({'scrollTop': ($('.contact-spinner').offset().top - 160) + 'px'}, 'fast'); });
			
			var form_data = $(form).serialize();
			$.ajax({
				url: ajaxurl,
				data: form_data+'&action=k2_contact_form',
				success: function(response) {
					setTimeout(spinner_delay, 2000);
					function spinner_delay() {
						$('.contact-spinner').slideUp();
						if(jQuery('#contact-form-type').val() == 'contact-ask-kim') {
							$('.contact-success-kim').slideDown();
						} else {
							$('.contact-success').slideDown();
						}
					}
				},
				error: function(response) {
					var response = JSON.parse(response.responseText);
					$('.contact-spinner').slideUp();
					$('.contact-success').html('<div class="alert alert-error"><p>' + response.message + '</div>')
					$('.contact-success').slideDown();
				}
			});
		}
	});
});
</script>

<?php get_footer(); ?>
