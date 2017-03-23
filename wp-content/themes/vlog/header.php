<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">

<meta name="viewport" content="width=device-width,initial-scale=1.0">

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/fonts.css">	
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/screen.css">	



</head>



<body <?php body_class(); ?>>

<div class="header-fix">

	<?php if( vlog_get_option( 'content_layout' ) == 'boxed' ): ?>

		<div class="vlog-body-box">

	<?php endif; ?>



	<?php if( vlog_get_option( 'header_top' ) ): ?>

		<?php get_template_part( 'template-parts/header/topbar' ); ?>

	<?php endif; ?>



	<?php $shadow_class = vlog_get_option('header_shadow') ? 'vlog-header-shadow' : ''; ?>

	

	<header id="header" class="vlog-site-header <?php echo esc_attr( $shadow_class ); ?> hidden-xs hidden-sm">

		

		<?php get_template_part( 'template-parts/header/layout-' . vlog_get_option('header_layout') ); ?>



	</header>

</div>

	<?php if ( vlog_get_option( 'header_sticky' ) ): ?>

		<?php get_template_part( 'template-parts/header/topbar' ); ?>
		<?php get_template_part( 'template-parts/header/sticky' ); ?>

	<?php endif; ?>



	<?php get_template_part( 'template-parts/header/responsive' ); ?>



	<div id="content" class="vlog-site-content">