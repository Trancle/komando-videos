<?php 

/* 
	This is Vlog Child Theme functions file
	You can use it to modify specific features and styling of Vlog Theme
*/	

add_action( 'after_setup_theme', 'vlog_child_theme_setup', 99 );

function vlog_child_theme_setup(){
	add_action('wp_enqueue_scripts', 'vlog_child_load_scripts');
}

function vlog_child_load_scripts() {	
	wp_register_style('vlog_child_load_scripts', trailingslashit(get_stylesheet_directory_uri()).'style.css', false, VLOG_THEME_VERSION, 'screen');
	wp_enqueue_style('vlog_child_load_scripts');
}


?>