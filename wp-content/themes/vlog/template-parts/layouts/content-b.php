<article <?php post_class('vlog-lay-b lay-horizontal vlog-post'); ?>>
    <div class="row">
        
            <?php if( $fimg = vlog_get_featured_image('vlog-lay-b') ) : ?>
                <div class="col-lg-6 col-md-6  col-sm-6 col-xs-12">
                    <div class="entry-image">
                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                       	<?php echo $fimg; ?>
                        <?php if( $labels = vlog_labels('b', 'medium') ) : ?>
                        <?php echo $labels; ?>
                        <?php endif; ?>
                    </a>
                    </div>
                </div>
            <?php endif; ?>
        
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            
            <div class="entry-header">

                <?php if( vlog_get_option( 'lay_b_cat' ) ) : ?>
                    <span class="entry-category"><?php echo vlog_get_category(); ?></span>
                <?php endif; ?>

                <?php the_title( sprintf( '<h2 class="entry-title h2"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

                <?php if( $meta = vlog_get_meta_data( 'b' ) ) : ?>
                    <div class="entry-meta"><?php echo $meta; ?></div>
                <?php endif; ?>

            </div>

            <?php if( vlog_get_option('lay_b_excerpt') ) : ?>
                <div class="entry-content">
                    <?php echo vlog_get_excerpt( 'b' ); ?>
                </div>
            <?php endif; ?>

            <?php if( vlog_get_option('lay_b_rm') ) : ?>
                <a class="vlog-rm" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo __vlog('read_more'); ?></a>
            <?php endif; ?>

        </div>
    </div>
</article>