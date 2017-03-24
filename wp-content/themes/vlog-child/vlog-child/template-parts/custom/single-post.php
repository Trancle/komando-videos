<div class="vlog-section dark-bg">
	<div class="container">
		<div class="vlog-single-content">
				<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
					<?php echo $breadcrumbs; ?>
				<?php endif; ?>

			<div class="row" >
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 post-single-video-section1"> 
					<div class="entry-video">
						<div class="video-sections">
							<?php 
								$post_meta = komaindo_front_get_videofulldetails($post->ID); 
								$post_videos_meta = $post_meta['video'];
								?>
							<?php if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'youtube'){
							?>				
	
									<?php 
									
										preg_match("/youtube\.com\/(v\/|watch\?v=)([\w\-]+)/", $post_videos_meta['vl_url'][0], $match_id);
										$yt_id = $match_id[2]; 
		
									
									    $videos_library_attr = $post_meta['video'];
										$youtube_attr = array();
										
										//related
										if(isset($videos_library_attr['vl_show_related_videos'][0]) && $videos_library_attr['vl_show_related_videos'][0] == 1){
											$youtube_attr['rel'] = 1;
										} else {
										   $youtube_attr['rel'] = 0;
										}
										
										//showinfo
										if(isset($videos_library_attr['vl_show_video_information'][0]) && $videos_library_attr['vl_show_video_information'][0] == 1){
											$youtube_attr['showinfo'] = 1;
										} else {
										   $youtube_attr['showinfo'] = 0;
										}
										//start offset 
										if(isset($videos_library_attr['vl_start_offset_in_seconds'][0]) && $videos_library_attr['vl_start_offset_in_seconds'][0] > 0){
											$youtube_attr['start'] = $videos_library_attr['vl_start_offset_in_seconds'][0];
										} else {
										    $youtube_attr['start'] = 0;
										}
										
										if(isset($videos_library_attr['vl_show_video_annotations']) && $videos_library_attr['vl_show_video_annotations'] == 1){
											$youtube_attr['iv_load_policy'] = 1;
										} else {
										   $youtube_attr['iv_load_policy'] = 3;
										}
										
										if(isset($videos_library_attr['vl_display_controls']) && $videos_library_attr['vl_display_controls'] == 1){
											$youtube_attr['controls'] = 1;
										} else {
										   $youtube_attr['controls'] = 0;
										}
										
										if(isset($videos_library_attr['vl_display_control_caption']) && $videos_library_attr['vl_display_control_caption'] == 1){
											$youtube_attr['cc_load_policy'] = 1;
										} else {
										   $youtube_attr['cc_load_policy'] = 0;
										}
										
										//loop
										if(isset($videos_library_attr['vl_loop']) && $videos_library_attr['vl_loop'] == 1){
											$youtube_attr['loop'] = 1;
										} else {
										   $youtube_attr['loop'] = 0;
										}
										
										//loop
										if(isset($videos_library_attr['vl_modest_branding_hide_youtube_logo']) && $videos_library_attr['vl_modest_branding_hide_youtube_logo'] == 1){
											$youtube_attr['modestbranding'] = 0;
										} else {
										   $youtube_attr['modestbranding'] = 1;
										}
										
										$youtubeurl="https://www.youtube.com/embed/";
										$youtubeurl .= $yt_id . '?autoplay=1&' ;

										$youtube_arr = array();
										foreach($youtube_attr as $key => $val){
										  $youtube_arr[] = $key . '=' . $val;
										}
										$youtubeurl .= implode('&',$youtube_arr);
										
										
										//$url .= "?autoplay=1&disablekb=1&modestbranding=1&playsinline=1"; ?>
										<iframe width="100%"  src="<?php echo $youtubeurl; ?>" frameborder="0" allowfullscreen></iframe>
										<?php //echo mediaelement_youtube($youtubeurl);?>
								
								
								<?php } else if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'vimeo') { ?>
									<?php  echo mediaelement_vimeo($post_videos_meta['vl_url'][0]); ?>
								<?php } else if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'html'){ ?>
										<?php 
										
											$htmlmp4url = wp_get_attachment_url($post_videos_meta['vl_upload_video'][0]); 
											echo mediaelement_mp4($htmlmp4url);?>
								
								<?php } ?>
							
							
						</div>
					</div>
				<div class="entry-carsoul">
					<?php get_template_part( 'template-parts/custom/post-slider' ); ?>
				</div>


				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
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
			
			
			
<div class="vlog-section more-videos" >
	<div class="container">
			<div class="vlog-tab-title">More Video on  komando.com</div>
			<div class="vlog-single-content">
				<?php get_template_part( 'template-parts/custom/post-tabs-content' ); ?>
			</div>
	</div>
</div>

<div class="vlog-section related" >
	<div class="container">
		<div class="vlog-content-related vlog-single-content">	
			<?php if( vlog_get_option('single_related') ) : ?>
				<?php get_template_part( 'template-parts/single/related'); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
