<?php 

/*
Template Name: Operation Komando
*/

// finds the last URL segment  
$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $urlArray);
$numSegments = count($segments); 
$currentSegment = $segments[$numSegments - 1];

get_header(); ?>
	
<section class="content-left operation-komando clearfix" role="main">

    <div class="page-content-operation-komando clearfix">
		<div class="navbar">
			<div class="navbar-inner">
				<a class="brand" href="/operation-komando">Operation Komando</a>
				<ul class="nav hide-mobile hide-tablet">
					<li <?php if($currentSegment == 'operation-komando') { echo ' class="active"'; } ?>><a href="/operation-komando">Home</a></li>
					<li <?php if($currentSegment == 'send-a-package') { echo ' class="active"'; } ?>><a href="/operation-komando/send-a-package">Send a Package</a></li>
					<li <?php if($currentSegment == 'spread-the-word') { echo ' class="active"'; } ?>><a href="/operation-komando/spread-the-word">Spread the Word</a></li>
					<li <?php if($currentSegment == 'operation-photos') { echo ' class="active"'; } ?>><a href="/operation-komando/operation-photos">Operation Photos</a></li>
					<li <?php if($currentSegment == 'frequently-asked-questions') { echo ' class="active"'; } ?>><a href="/operation-komando/frequently-asked-questions">FAQ's</a></li>
				</ul>
				<a href="javascript:void(0)" class="navbar-toggle hide-desktop"><i class="fa fa-bars"></i></a>
			</div>
		</div>
		
		<?php 
			if (have_posts()): while (have_posts()) : the_post();
				the_content();		
			endwhile;
			else: 
		?>
				<h2>Sorry, nothing to display.</h2>
		
		<?php endif; ?>

	</div>

</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>