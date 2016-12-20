<?php 

/*
Template Name: E-Guide Downloads
*/

// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));

initializeCas();

if(!is_user_logged_in()) {
	status_header(401);
	header("Location: $login_boomerang");
}

get_header(); ?>
	
	<section class="content-full eguide-downloads clearfix" role="main">

	<?php if(current_user_can('premium_member')) { ?>

		<div class="kims-club-dashboard premium">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-logo"><img src="http://static.komando.com/websites/common/v2/img/kims-club-logo.png" alt="[LOGO] Kim's Club"> <div class="kcd-badge premium">Premium Member</div></div>
					<div class="kcd-welcome">Kim Komando's <em>FREE</em> membership e-guide access</div>
				</div>
			</header>
		
			<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>

		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/digital-camera-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Guide to Digital Cameras <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Whether you want to break into the field of photography as an amateur or a pro, you need a camera. My 30-page eGuide will walk you through the process of choosing the perfect camera for you.</p>
					<p>I tell you about the different kinds of cameras available with all the pros and cons of each. Learn what accessories will help you take the best pictures and make your camera last.</p>
					<p>Don't head to the store without reading this eGuide first. Download it now and start learning.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-essential-guide-to-digital-cameras.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>
		
		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/photo-taking.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Guide to Taking Great Photos <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Are you just getting started as a photographer? Or, are you looking to take your skills to the next level? Whether you want to go pro or just spice up your family photos, my 51-page eGuide is all the guidance you'll need.</p>
					<p>I walk you through the camera settings any photographer needs to know, from aperture to zoom. You'll learn how exposure can change your photos and why you want to shoot in RAW format. There's much more I want to share with you, and it's all in this easy-to-download guide!</p>
					<p>Don't waste another moment taking less-than-fantastic shots. Download it now and start learning.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-essential-guide-to-taking-great-photos.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>
		
		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/creative-photo-editing.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Guide to Creative Photo Editing <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>You've bought the perfect camera and learned how to take spectacular images. However, your journey to the perfect photo isn't over yet. No matter how good a picture is, there's always something to tweak.</p>
					<p>Fortunately, modern computer graphics programs allow anyone to spruce up their photos after the fact. In this information-packed, 33-page eGuide, I'll walk you through the entire editing process.</p>
					<p>From choosing a photo editing program to editing basics, it's all here. Learn about layers, cropping, rotation, color correction and all the other tricks professionals use. I even cover how to make the best prints of your masterpieces.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komando-essential-guide-to-creative-photo-editing.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/mac-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to Your New Mac <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Congratulations! You just bought a new computer. Now what? You have to set it up, of course. Unfortunately, that can be a daunting task.</p>
					<p>Never fear! With my comprehensive 16-page Guide to your new Mac, you'll have your computer up and running in no time. I walk you through setting up hardware and software, and even include troubleshooting tips for common roadblocks.</p>
					<p>Plus, you'll learn how to secure your computer against threats, transfer your old information and fill your computer with great free software. What are you waiting for? Download this eGuide and start setting up your new Mac today!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-guide-to-your-new-mac.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/pc-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to your new PC <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Congratulations! You just bought a new computer. Now what? You have to set it up, of course. Unfortunately, that can be a daunting task.</p>
					<p>Never fear! With my comprehensive 15-page eGuide to your new PC, you'll have your computer up and running in no time. I walk you through setting up hardware and software,and even include troubleshooting tips for common roadblocks.</p>
					<p>Plus, you'll learn how to secure your computer against threats, transfer your old information and fill your computer with great free software. What are you waiting for? Download this eGuide and start setting up your new PC today!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-guide-to-your-new-pc.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/windows-8-user-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Windows 8 Start Guide <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Microsoft's newest operating system is here! Unfortunately, Windows 8 is a big change from Windows 7.</p>
					<p>The Start Menu is now a Start Screen, there are apps and many familiar settings and options are hidden.</p>
					<p>Don't worry, though; I've got you covered with this excellent eGuide. It has 15 pages of illustrated instructions to get you started.</p>
					<p>You'll learn how to navigate the new Windows 8 interface, run your Windows programs and adjust common settings. You'll be a Windows 8 pro in no time!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komando-essential-windows8-quick-start-guide.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/ipad-user-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential iPad User Guide <span class="kcde-value">$7.95 value</span></div>
				<div class="kcde-description">
					<p>Congratulations! You just bought a shiny new iPad. Now what do you do with it?</p>
					<p>Fear not; I'll guide you through the process of setting up and using your new gadget. From figuring out the interface to recommended settings and must-have apps, I've got 35 pages of must-read information for any iPad user. I've even included troubleshooting information for times when things just aren't going right.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-essential-ipad-user-guide.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/nexus-user-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Nexus User Guide <span class="kcde-value">$5.95 value</span></div>
				<div class="kcde-description">
					<p>Congratulations! You bought a cool new Nexus tablet or smartphone. Now what do you do with it?</p>
					<p>Fear not! I'll guide you through the process of setting up and using your new gadget. From figuring out the interface to recommended settings and must-have apps,I've got 25 pages of must-read information for any Nexus user.</p>
					<p>This guide will also help owners of most Android-based smartphones and tablets. Just note that some gadget manufacturers make their own tweaks to Android,so not all the information in the guide will be accurate for your gadget. This guide is not for Kindle or Nook tablet owners.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-essential-nexus-user-guide.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/time-to-buy-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">A Time To Buy <span class="kcde-value">$5.95 value</span></div>
				<div class="kcde-description">
					<p>For everything there is a season and finding great shopping deals is no exception.</p>
					<p>In this 24-page guide I show you how to save hundreds of dollars by purchasing products at just the right time. You'll learn how to save money on items such as televisions, laptops, smartphones and cameras. It isn't just tech; appliances, furniture, toys, candy, clothing and even when to buy a new car are also included.</p>
					<p>The guide takes you through the entire year and is broken into months and categories so it's easy to find exactly what you need. Spend a little now to save a bundle later; you won't be sorry.</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-a-time-to-buy.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>
		
		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/essential-tech-guide.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Essential Tech Guide <span class="kcde-value">$4.95 value</span></div>
				<div class="kcde-description">
					<p>Should you buy a new laptop or a tablet instead? What's the best e-reader for you? Can you really buy a worthwhile computer for less than $500? What's the best choice for a serious gamer?</p>
					<p>Get all the answers you need in my "Essential Tech Buying eGuide!</p>
					<ul>
					You'll learn:
					<li>How much computer you really need</li>
					<li>What you can live without</li>
					<li>Best brands and models</li>
					<li>What to avoid</li>
					</ul>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-essential-tech-guide.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/browser-shortcuts.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Browser Shortcuts Guide <span class="kcde-value">$1.95 value</span></div>
				<div class="kcde-description">
					<p>Every Web browser has useful, time-saving shortcuts,and this handy eGuide show you exactly what they are and how to use them. You'll find 3 pages packed with the best shortcuts for Internet Explorer, Firefox, Chrome and Safari, on both Windows and Mac!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-browser-shortcuts-guide.pdf'); ?>" class="kcde-link btn btn-blue btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/google-shortcuts.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to Time-Saving Shortcuts for Google <span class="kcde-value">$1.95 value</span></div>
				<div class="kcde-description">
					<p>Google is great for searching the Internet, but sometimes it gives you thousands of search results you don't need. This 8-page eGuide is packed with handy tricks and shortcuts can help you narrow down your results for faster, more effective searches. I've even included details on helpful Google services you might not know exist.</p>
					<p>Download it and start searching more effectively today!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/guide-to-google-shortcuts.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/itunes-shortcuts.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to Time-Saving Shortcuts for iTunes <span class="kcde-value">$1.95 value</span></div>
				<div class="kcde-description">
					<p>iTunes is a powerful media management and playback program, but it often takes multiple mouse clicks to do anything. This eGuide has 2 pages of keyboard shortcuts that can replace several time-consuming clicks.</p>
					<p>Download it and begin your journey to easier iTunes use today!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-guide-to-time-saving-shortcuts-for-itunes.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/mac-shortcuts.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to Time-Saving Shortcuts for Mac OS X <span class="kcde-value">$1.95 value</span></div>
				<div class="kcde-description">
					<p>Mac OS X is simple to control with a mouse, but a mouse isn't always the fastest way to get things done. A single keyboard shortcut can often replace several mouse clicks. In this handy eGuide, I give you 2 pages packed with useful, time-saving keyboard shortcuts.</p>
					<p>Whether you're new to Mac or a long-time user, there's something for you to learn. Download this eGuide and take control of your computer today!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-guide-to-time-saving-shortucts-for-mac-os-x.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

		<div class="kcd-eguide-wrapper clearfix">
			<div class="kcde-image hide-mobile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/eguides/windows-7-shortcuts.jpg" /></div>
			<div class="kcde-info">
				<div class="kcde-title">Guide to Time-Saving Shortcuts for Windows 7 <span class="kcde-value">$1.95 value</span></div>
				<div class="kcde-description">
					<p>Every Web browser has useful, time-saving shortcuts and this handy eGuide show you exactly what they are and how to use them. You'll find the best shortcuts for Internet Explorer, Firefox, Chrome and Safari on both Windows and Mac!</p>
					<p>Download it and start saving valuable time today! No e-reader needed!</p>
				</div>
				<a href="<?php echo k2_premium_eguides_link('http://weststar.bc.cdn.bitgravity.com/secure/www-prod1/premium-eguides/kim-komandos-guide-to-time-saving-shortcuts-for-windows-7.pdf'); ?>" class="kcde-link btn btn-blue">Download Now</a>
			</div>
		</div>

	<?php } elseif(current_user_can('basic_member')) { ?>

		<div class="kims-club-dashboard premium">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-logo"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kims-club-logo.png" alt="[LOGO] Kim's Club"> <div class="kcd-badge basic">Basic Member</div></div>
					<div class="kcd-welcome">Kim Komando's <em>FREE</em> membership e-guide access</div>
				</div>
			</header>
		
			You must be a premium member to access the E-Guide downloads. 

		</div>

		<div class="kims-club-dashboard-upgrade">
			<h3><div class="kcd-badge basic">Basic Member</div> Upgrade your membership now and receive:</h3>
			<ul class="blue-arrow">
				<li>Watch the show Live!</li>
				<li>Exclusive Discounts</li>
				<li>Live chat during the Kim Komando Show on the <a href="<?php echo VIDEOS_BASE_URI; ?>/live-from-the-studio">Watch the Show</a> page</li>
				<li>Priority Email to ask Kim your tech questions</li>
			</ul>
			<a href="<?php echo CLUB_BASE_URI; ?>/account/membership/upgrade" class="kcdu-button">Upgrade Now</a>
		</div>

	<?php } else { ?>

		<div class="kims-club-dashboard free">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-welcome">Kim Komando's <em>FREE</em> membership e-guide access</div>
				</div>
			</header>
		
			You must be a premium member to access the E-Guide downloads. 

		</div>

		<div class="kims-club-dashboard-upgrade">
			<h3>Join Kim's Club to get these great membership benefits:</h3>
			<ul class="blue-arrow">
				<li>Watch the show live - on Your Tablet, Computer, Phone or TV</li>
				<li>Instant Access to Shows and Podcasts - on Your Schedule</li>
				<li>Automatic Entry in Our Contests</li>
				<li>Free Downloads, How-To Guides, Buying Recommendations</li>
				<li>Answers on the Members-Only Message Board</li>
				<li>Exclusive Discounts</li>
				<li>Crystal clear audio and video in HD Quality</li>
				<li>Live chat during the Kim Komando Show</li>
				<li>Priority Email to ask Kim your tech questions</li>
			</ul>
			<a href="<?php echo CLUB_BASE_URI; ?>/account/membership/upgrade" class="kcdu-button">Upgrade Now</a>
		</div>

	<?php } ?>

	</section>
	
<?php get_footer(); ?>