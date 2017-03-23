<?php $user = wp_get_current_user();?>

<?php if(isset($user->ID) && $user->ID > 0): ?>
	<?php echo get_template_part('template-parts/custom/single-post-watch-live-club-member'); ?>
<?php else : ?>
	<?php echo get_template_part('template-parts/custom/single-post-watch-live-not-member'); ?>
<?php endif; ?>
