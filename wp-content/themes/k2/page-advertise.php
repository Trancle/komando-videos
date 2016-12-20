<?php 

/*
Template Name: Advertise
*/

get_header(); ?>
	
    <section class="content-full advertise clearfix" role="main">
		
		<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>

		<div class="advertise-wrapper">
			<div class="advertise-header"><h1>Advertise on the <span>Largest</span> National Weekend Talk Radio Show in the Country!</h1></div>

			<div class="advertise-bullet-1">
				<div class="advertise-bullet-icon"><img src="<?php echo k2_get_static_url('v2'); ?>/img/advertise-radio-tower.png" alt="radio-tower" /></div>
				Millions hear Kim Komando --America's Digital Pro-- every weekend. Over three hours of news, commentary, and calls from listeners, The Kim Komando Show informs and inspires as Kim helps her listeners navigate the ever-changing landscape of the digital era.
			</div>

			<div class="advertise-bullet-2">
				<div class="advertise-bullet-icon"><img src="<?php echo k2_get_static_url('v2'); ?>/img/advertise-globe.png" alt="globe" /></div>
				Through a diverse and dynamic group of multimedia platforms, Kim is a trusted resource for her fans as she demystifies the digital lifestyle. For over twenty years, Kim has developed an ever-growing loyal fan base that follows her advice and recommendations on products and services.
			</div>

			<div class="advertise-bullet-3">
				<div class="advertise-bullet-icon"><img src="<?php echo k2_get_static_url('v2'); ?>/img/advertise-email.png" alt="email" /></div>
				By breaking news and dispensing advice on air and on the web, Kim's show provides the perfect context for products that influence and shape our world today.
			</div>

			<div class="advertise-bullet-4">
				<div class="advertise-bullet-icon"><img src="<?php echo k2_get_static_url('v2'); ?>/img/advertise-headphones.png" alt="headphones" /></div>
				425+ affiliate stations carry the Kim Komando Show<br />
				6.9 million cume listeners weekly<br />
				12.2 million weekly emails sent to newsletter subscribers<br />
				2.5 million unique visitors every month to Komando.com
			</div>

			<div class="advertise-contact">
				Contact us today to advertise on The Kim Komando Show. <a href="<?php bloginfo('url') ?>/contact-us#advertise" class="btn btn-gold">Click Here</a>
			</div>
		</div>

	</section>
	
<?php get_footer(); ?>