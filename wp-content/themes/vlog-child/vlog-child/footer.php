<?php do_action('vlog_before_end_content'); ?>

<?php get_template_part('template-parts/ads/above-footer'); ?>

</div>
    <footer id="footer" class="vlog-site-footer">

    	<?php if( vlog_get_option('footer_widgets') ): ?>

	        <div class="container">
	            <div class="row">
	                <?php 
						$layout = explode( "_", vlog_get_option('footer_layout') );
						$columns = $layout[0]+1;
						$col_class = $layout[1];
					?>

					<?php for($i = 1; $i <= $columns; $i++) : ?>
					
					    <?php 
						  /*if($i==1)	$col_class_no=3;
						  if($i==2)	$col_class_no=2;
						  if($i==3)	$col_class_no=3;
						  if($i==4)	$col_class_no=2;
						  if($i==5)	$col_class_no=3;
						  <div class="col-lg-<?php echo $col_class_no; ?> col-md-<?php echo $col_class_no; ?>">
						  */
						?>
						
						<div class="footer-colum col-lg-<?php echo esc_attr($col_class); ?> col-md-<?php echo esc_attr($col_class); ?>">
							<?php if( is_active_sidebar( 'vlog_footer_sidebar_'.$i ) ) : ?>
								<?php dynamic_sidebar( 'vlog_footer_sidebar_'.$i );?>
							<?php endif; ?>
						</div>
					<?php endfor; ?>

	            </div>
	        </div>

	    <?php endif; ?>

        <?php if( vlog_get_option('footer_bottom') ): ?>
    
	        <div class="vlog-copyright">
	            <div class="container">
	                <?php echo wp_kses_post( vlog_get_option('footer_copyright') ); ?>
	            </div>
	        </div>

    	<?php endif; ?>

    </footer>

<?php if( vlog_get_option( 'content_layout' ) == 'boxed' ): ?>
		</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>

</html>