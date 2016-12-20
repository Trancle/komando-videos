<?php 

/*
Template Name: KCorvette
*/

get_header(); ?>
	
    <section class="content-full kcorvette clearfix" role="main">
		
		<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>

		<h1>For Sale - 1966 Chevrolet Corvette Custom Convertible</h1>
		<h2>A Superb Show Car and Incredible Driving Machine</h2>

		<div class="kcorvette-left">
			<div class="kcorvette-info">
				<dl>
					<dt>Year:</dt><dd>1966</dd>
					<dt>Make:</dt><dd>Chevrolet</dd>
					<dt>Model:</dt><dd>Corvette</dd>
					<dt>Style:</dt><dd>Custom Convertible</dd>
					<dt>VIN:</dt><dd>194676S101107</dd>
					<dt>Exterior Color:</dt><dd>Viper Blue</dd>
					<dt>Interior Color:</dt><dd>Oyster</dd>
					<dt>Cylinders:</dt><dd>8</dd>
					<dt>Engine Size:</dt><dd>LS3 Cammer</dd>
					<dt>Transmission:</dt><dd>5-Speed Manual</dd>
				</dl>
			</div>
			<p>This Corvette is simply one-of-a-kind.</p>
			<p>This fabulous resto-mod, completed in December 2010 has been driven less then 600 miles since acquisition.</p>
			<p>It's distinctive Viper Blue exterior paint together with the Oyster colored interior, Oyster colored Hartz cloth top, and the OEM Oyster colored hard top give this car it's unique appeal.</p>
			<p>Under the hood is a new 600+ Horse Power LS3 Cammer engine package, mated to a new 5-speed Tremec transmission. The engine's distinctive and powerful rumble will have heads turning.</p>
			<p>Not to miss are all street and performance chrome engine components including the two air filers, the alternator, air-conditioning compressor, radiator, side exhaust and more.</p>
			<p>The custom Art Morrison/Hedges chassis with C5 and C6 suspension gives this Corvette incredible stability.</p>
			<p>Z06 4-wheel disc brakes provide powerful stopping power for the new Z06 spider 18" and 19" wheels.</p>
			<p>This car is equipped with all new electronic dash instruments, power steering, power brakes, power windows, AM/FM, power big block stinger hood, vintage heat and air.</p>
			<p>Runs like a dream, and in pristine condition!</p>
			<p>Serious inquiries only. Priced to sell at $279,000.</p>

			<a href="javascript:void(0)" data-modal="kcorvette-contact-modal" class="btn btn-blue btn-xlarge">Contact Me</a>
		</div>
		<div class="kcorvette-right">
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-interior.jpg" data-slb-gallery="kcorvette" /></figure>
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-engine.jpg" data-slb-gallery="kcorvette" /></figure>
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-front-overhead.jpg" data-slb-gallery="kcorvette" /></figure>
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-front3qtr.jpg" data-slb-gallery="kcorvette" /></figure>
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-profile.jpg" data-slb-gallery="kcorvette" /></figure>
			<figure class="kcorvette-image"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kcorvette/kcorvette-rear3qtr.jpg" data-slb-gallery="kcorvette" /></figure>
		</div>

	</section>
	<div class="kcorvette-contact-modal modal">
		<div class="kcorvette-contact-modal-body">
			<form>
				<div class="control-group"><h2>Contact Me</h2></div>
				<div class="control-group contact-name">
					<label for="contact-name" class="control-label-dark">Name</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-name" name="contact-name" />
					</div>
				</div>
				<div class="control-group contact-phone">
					<label for="contact-phone" class="control-label-dark">Phone Number</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-phone" name="contact-phone" />
					</div>
				</div>
				<div class="control-group contact-email">
					<label for="contact-email" class="control-label-dark">Email</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-email" name="contact-email" />
					</div>
				</div>
				<div class="control-group contact-enquiry">
					<label for="contact-enquiry" class="control-label-dark">Enquiry</label>
					<div class="controls">
						<textarea class="blue input-block-level" id="contact-enquiry" name="contact-enquiry"></textarea>
					</div>
				</div>
				<div class="control-group contact-hp hide">
					<label for="contact-hp" class="control-label-dark">HP</label>
					<div class="controls">
						<input type="text" class="blue input-block-level" id="contact-hp" name="contact-hp" />
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
				<div class="control-group"><button class="btn btn-blue">Send</button></div>
			</form>

			<div class="contact-spinner">
				<img src="<?php echo k2_get_static_url('v2'); ?>/img/spinner.gif" alt="spinner" />
			</div>
			<div class="contact-success">
				<div class="alert alert-success"><strong>Thank you!</strong> I've received your email and will respond to you as soon as I can.</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.kcorvette-contact-modal-body form').on('submit', function(e) {

			$('.kcorvette-contact-modal-body form').slideUp();
			$('.contact-spinner').slideDown();

			var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
			var form_data = $('.kcorvette-contact-modal-body form').serialize();
			$.ajax({
				url: ajaxurl,
				data: form_data+'&action=k2_corvette_form',
				success: function(response) {
					setTimeout(spinner_delay, 2000);
					function spinner_delay() {
						$('.contact-spinner').slideUp();
						$('.contact-success').slideDown();
					}
				}
			});

			e.preventDefault();
			return false;

		});
	});
	</script>
	
<?php get_footer(); ?>
