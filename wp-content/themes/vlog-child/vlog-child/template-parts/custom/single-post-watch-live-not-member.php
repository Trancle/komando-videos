<div class="vlog-section dark-bg">
	<div class="container">
		<div class="vlog-single-content">
				<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
					<?php echo $breadcrumbs; ?>
				<?php endif; ?>

			<div class="row" >
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 post-single-video-section1"> 
					<div class="entry-video">
						<div class="video-sections">
						<section class="club-watch">
							<div class="left-video">
								<img src="<?php echo get_template_directory_uri(); ?>/images/video-bg.jpg" class="img-responsive video-img">
								<div class="video-up-text for-desktop">
										<h3>Join Kim's Club to Watch Now!</h3>
										<p>Kim's Club Members can watch the kim Komando Show anytime.<br>
									if you'r a member, click below to sign in <br>
									want to sign up? Click on the <strong>Join Kim's Club button below.</strong></p>

									<div class="button-opt">
										<a href="<?php echo 'https://club.komando.com/products/premium?r=' . get_permalink(); ?>" class="btn btn-lg btn-primary">15 Day Free Trial</a>
										<a href="<?php echo 'https://auth.komando.com/login?service=' . get_permalink(); ?>" class="btn btn-lg btn-default">Sign In </a>
									</div>
								</div>
							</div>
							<div class="video-up-text for-mobile">
									<h3>Join Kim's Club to Watch Now!</h3>
									<p>Kim's Club Members can watch the kim Komando Show anytime.<br>
								if you'r a member, click below to sign in <br>
								want to sign up? Click on the <strong>Join Kim's Club button below.</strong></p>

								<div class="button-opt">
									<a href="<?php echo 'https://club.komando.com/products/premium?r=' . get_permalink(); ?>" class="btn btn-lg btn-primary">15 Day Free Trial</a>
									<a href="<?php echo 'https://auth.komando.com/login?service=' . get_permalink(); ?>" class="btn btn-lg btn-default">Sign In </a>
								</div>
							</div>
						</section>
						</div>
					</div>
				<div class="entry-carsoul">
					<?php get_template_part( 'template-parts/custom/post-slider' ); ?>
				</div>


				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="post-content"> 
						<div class="entry-title post-title">
							 <?php the_title( sprintf( '<h2 class="entry-title h1">', esc_url( get_permalink() ) ), '</h2>' ); ?>
						</div>
						<div class="entry-meta description" style="color:#FFF;"><?php 	echo substr(strip_tags($post->post_content), 0, 300);?>
						</div>
					</div>
					<div class="entry-share">
						<?php get_template_part( 'template-parts/single/share' ); ?>
					</div>
					<div class="entry-ads">
						<?php get_template_part('template-parts/ads/below-single'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
			
			
			

<section class="join-club grey-bg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="main-heading">A few more reason to join kim's Club</h3>
			</div>
		</div>
		<div class="row">
		<?php 
			$club_box_arr = array();
			for ($i=0; $i < 11; $i++) { 
				$temp = array();
				$temp['main_img_src'] = get_template_directory_uri().'/images/club-box-bg.jpg';
				$temp['icon_img_src'] = get_template_directory_uri().'/images/club-icon.png';
				$temp['title'] = 'Automatic Entry in My Contests';
				$club_box_arr[] = $temp;
			}
			foreach ($club_box_arr as $key => $value) {
		?>
			<div class="col-sm-6 col-md-4">
				<div class="club-box">
					<img src="<?php echo $value['main_img_src']; ?>">
					<div class="icon">
						<img src="<?php echo $value['icon_img_src']; ?>">
						<h3><?php echo $value['title']; ?></h3>
					</div>
				</div>
			</div>

		<?php } ?>
		</div>

		<div class="row">
			<div class="col-md-12">
				<a href="<?php echo 'https://auth.komando.com/login?service=' . get_permalink(); ?>" class="join-now">Join Now </a>
			</div>
		</div>
	</div>
</section>

<section class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/images/add-bottom.jpg"></section>



