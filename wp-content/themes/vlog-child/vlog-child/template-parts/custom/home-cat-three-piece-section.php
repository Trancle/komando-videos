<?php $carousel = vlog_get_home_category(); ?> 


<?php foreach($carousel as $catkey => $slides ): ?>
	<!--<?php echo $slides['catname'];?> Section-->
	<?php $backaddcss = ""; ?>
	<?php if(isset($slides['catname']['meta_data']['vcat_come_in_club_member']) && $slides['catname']['meta_data']['vcat_come_in_club_member'] == 1) : ?>
		<?php $backaddcss = "style=background:#ebeeef"; ?>
	<?php endif; ?>
	<div class="vlog-section-cat" <?php echo $backaddcss; ?>>  
		<div class="container">  
			<div class="vlog-content-cat">  
				<div class="carousel-sec">
					<div class="vlog-mod-head">
						<div class="vlog-mod-title">
							<h4><a href="<?php echo get_category_link( $catkey ); ?>"><?php echo $slides['catname'];?></a></h4>
						</div>
						<div class="vlog-mod-actions">
							<a class="vlog-all-link" href="<?php echo get_category_link( $catkey ); ?>">View All Videos <i class="fa fa-angle-right" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="hidden-xs">
						<div class="home-vlog-slider">
							<?php if(isset($slides['first']) && count($slides['first']) > 0): ?>
								<div class="row row-eq-height">
									<article class="vlog-lay-e vlog-post col-lg-8  col-sm-8 col-md-8  col-xs-12 bigimage"> 
										<article class="vlog-lay-a">
												<div class="video-sec">
													<div class="entry-image">
															<a href="<?php echo esc_url( get_permalink($slides['first'][0]->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
																<div class="play small"><span>&nbsp;</span></div>
															
																<?php echo vlog_get_featured_image('vlog-lay-a', $slides['first'][0]->ID); ?>
															<div class="video-caption">
																<div class="entry-header">
																	<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
																	<h2 class="entry-title h2"><?php echo esc_attr( get_the_title($slides['first'][0]->ID) ); ?></h2>
																</div>
																<div class="entry-meta visible-lg">
																	<h2 class="entry-title h6"><?php echo substr(strip_tags($slides['first'][0]->post_content), 0, 500);?>
																	</h2>
																</div>
															</div>	
															</a>
													</div>
												</div>
											</article>
									
									
										
									</article>
									<article class="vlog-lay-e vlog-post col-lg-4  col-sm-4 col-md-4  col-xs-12 smallimage-home">
										<?php foreach($slides['first'] as $key => $slide ): if($key>0):?>
										
											<div class="video-sec">
												<div class="entry-image">
													<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
														<div class="play small"><span>&nbsp;</span></div>
													
														<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
														<div class="video-caption">
															<div class="entry-header">
																<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
																<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
															</div>
														</div>
													</a>
												</div>
												<div class="tooltip-wrap">
													<div class="tooltip-rectangle"></div>
													<h2><?php echo substr( esc_attr( get_the_title($slide->ID) ), 0, 50 ); ?></h2>
													<p><?php echo substr(strip_tags($slide->post_content), 0, 250);?><?php echo strlen(strip_tags($slide->post_content))>250 ? '...':''; ?></p>
												</div>
											</div>

										 <?php endif; endforeach; ?>
									</article>
								
							
								</div>
							<?php endif; ?>
							
							<?php if(isset($slides['rest']) && count($slides['rest']) > 0): ?>
							  <?php foreach($slides['rest'] as $key => $slidesrestall ): ?>

								<div class="row row-eq-height">
									<?php foreach($slidesrestall as $key1 => $slide ): ?>
										
											<article class="vlog-lay-e vlog-post col-lg-4  col-sm-4 col-md-4  col-xs-12 smallimage-home">
				
												<div class="video-sec">
													<div class="entry-image">
															<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
																<div class="play small"><span>&nbsp;</span></div>
															
																<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
															<div class="video-caption">
																<div class="entry-header">
																	<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
																	<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
																</div>
															</div>	
															</a>
													</div>
													<div class="tooltip-wrap <?php echo ($key1 % 3 == 0) ? 'left' : '' ?>">
														<div class="tooltip-rectangle"></div>
														<h2><?php echo substr( esc_attr( get_the_title($slide->ID) ), 0, 50 ); ?></h2>
														<p><?php echo substr(strip_tags($slide->post_content), 0, 250);?><?php echo strlen(strip_tags($slide->post_content))>250 ? '...':''; ?></p>
													</div>
												</div>
											</article>
										
									<?php endforeach; ?>
								</div>	
							  <?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="visible-xs">
						<div class="home-vlog-mobile-slider">
							<?php if(isset($slides['first']) && count($slides['first']) > 0): ?>
							<?php foreach($slides['first'] as $key => $slide ):?>
								<div class="row row-eq-height">
									<article class="vlog-lay-e vlog-post col-xs-12 smallimage-home">
										<article class="vlog-lay-a">
											<div class="video-sec">
												<div class="entry-image">
													<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
														<div class="play small"><span>&nbsp;</span></div>
													
														<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
														<div class="video-caption">
															<div class="entry-header">
																<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
																<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
															</div>
														</div>	
													</a>
												</div>
											</div>
										</article>
									</article>
								</div>
							 <?php endforeach; ?>
							<?php endif; ?>
							
							<?php if(isset($slides['rest']) && count($slides['rest']) > 0): ?>
							  <?php foreach($slides['rest'] as $key => $slidesrestall ): ?>

									<?php foreach($slidesrestall as $key1 => $slide ): ?>
										<div class="row row-eq-height">
											<article class="vlog-lay-e vlog-post col-xs-12 smallimage-home">
				
												<div class="video-sec">
													<div class="entry-image">
															<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
																<div class="play small"><span>&nbsp;</span></div>
															
																<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
															<div class="video-caption">
																<div class="entry-header">
																	<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
																	<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
																</div>
															</div>	
															</a>
													</div>
												</div>
											</article>
										
										</div>	
									<?php endforeach; ?>
							  <?php endforeach; ?>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
