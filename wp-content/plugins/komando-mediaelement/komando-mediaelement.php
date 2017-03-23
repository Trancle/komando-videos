<?php
/*
Plugin Name: komondo-MEDIAELEMENTJS
Plugin URI: https://www.komando.com
Description: komondo front is use to manage admin custom functionality
Author: Ujjwal Ahluwalia
Author URI: https://www.komando.com
Version: 1.0
*/
 
define('MEDIAELEMENTJS_DIR', WP_PLUGIN_URL.'/komando-mediaelement/mediaelement/');
define('MEDIAELEMENTJS_DIR_PARENT', WP_PLUGIN_URL.'/komando-mediaelement/');
// Javascript 
function mediaelement_add_scripts(){
    //if (!is_admin()){
        // the scripts
        wp_register_script("mediaelementjs-scripts", MEDIAELEMENTJS_DIR ."mediaelement-and-player.js", array('jquery'), "2.1.3", false);
		wp_register_script("mediaelementjs-scripts1", MEDIAELEMENTJS_DIR_PARENT ."youtube.js", array('jquery'), rand(), false);
		
		wp_register_style("mediaelementjs-styles", MEDIAELEMENTJS_DIR ."mediaelementplayer.min.css");
    //}
}


// register jquery and style on initialization
add_action('init', 'mediaelement_add_scripts');
// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_style');

function enqueue_style(){
	
	// Localize the script with new data
	/*$postid = get_the_ID();
	$youtube_attributes = komaindo_front_get_videofulldetails($postid); 
//print_r($youtube_attributes);
	if(isset($youtube_attributes['video']['vl_video_type'][0]) && $youtube_attributes['video']['vl_video_type'][0] == 'youtube'){
	
	    $youtube_attr = "";
		
		//show related
	    if(isset($youtube_attributes['video']['vl_show_related_videos'][0]) && $youtube_attributes['video']['vl_show_related_videos'][0]){
			$youtube_attr .= 'rel: 1';
		} else {
			$youtube_attr .= 'rel: 0';
		}
		
		//show video information
		if(isset($youtube_attributes['video']['vl_show_video_information'][0]) && $youtube_attributes['video']['vl_show_video_information'][0]){
			//$youtube_attr .= ', showinfo:1';
		} else {
			//$youtube_attr .= ', showinfo:0';
		}
		
		//show vl_start_offset_in_seconds
		if(isset($youtube_attributes['video']['vl_start_offset_in_seconds'][0]) && $youtube_attributes['video']['vl_start_offset_in_seconds'][0]){
			if(isset($youtube_attributes['video']['vl_start_offset'][0]) && $youtube_attributes['video']['vl_start_offset'][0] > 0){
				$youtube_attr .= ', start: ' . $youtube_attributes['video']['vl_start_offset'][0];
			} else {
			    $youtube_attr .= ', start: 0';
			}
		}
		
		//show vl_show_video_annotations
		if(isset($youtube_attributes['video']['vl_show_video_annotations'][0]) && $youtube_attributes['video']['vl_show_video_annotations'][0]){
			//$youtube_attr .= ', showinfo:1';
		} else {
			//$youtube_attr .= ', showinfo:0';
		}
		
		//show vl_display_controls
		if(isset($youtube_attributes['video']['vl_display_controls'][0]) && $youtube_attributes['video']['vl_display_controls'][0]){
			//$youtube_attr .= ', controls:1';
		} else {
			//$youtube_attr .= ', controls:0';
		}
		
		//show vl_display_controls
		if(isset($youtube_attributes['video']['vl_loop'][0]) && $youtube_attributes['video']['vl_loop'][0]){
			$youtube_attr .= ', loop: 1';
		} else {
			$youtube_attr .= ', loop: s0';
		}
		
		

		
		// Register the script
		wp_register_script( 'attributes', MEDIAELEMENTJS_DIR_PARENT ."youtube.js" );
	
		$translation_array = array(
			'attributes_string' => __( $youtube_attr , 'plugin-domain' ),
		);
		wp_localize_script( 'attributes', 'youtube', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'attributes' );
   } else {
   // Register the script
		wp_register_script( 'attributes', MEDIAELEMENTJS_DIR_PARENT ."youtube.js" );
	
		$translation_array = array(
			'attributes_string' => __( "" , 'plugin-domain' ),
		);
		wp_localize_script( 'attributes', 'youtube', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'attributes' );
   }
   */
   wp_enqueue_script('mediaelementjs-scripts');
   wp_enqueue_script('mediaelementjs-scripts1');
   wp_enqueue_style( 'mediaelementjs-styles' );
}


function mediaelement_youtube($youtubeurl){
	$output = "";
	$output = '<div class="youtube_video video">
	<video id="player1" width="640" height="360" style="max-width:100%;" preload="none">
		 <source type="video/youtube" src="' . $youtubeurl . '">
		<track srclang="en" label="English" kind="subtitles" src="' . MEDIAELEMENTJS_DIR_PARENT . 'mediaelement.vtt">
		<track srclang="en" kind="chapters" src="' . MEDIAELEMENTJS_DIR_PARENT . 'chapters.vtt">
	</video>
	</div>';
	return $output;
}


function mediaelement_mp4($mp4url){
	$output = "";
	
	$output = '<div class="mp4_video video">
	<video autoplay id="player1" width="640" height="360" style="max-width:100%;" preload="none">
		 <source type="video/mp4" src="' . $mp4url . '">
		<track srclang="en" label="English" kind="subtitles" src="' . MEDIAELEMENTJS_DIR_PARENT . 'mediaelement.vtt">
		<track srclang="en" kind="chapters" src="' . MEDIAELEMENTJS_DIR_PARENT . 'chapters.vtt">
	</video>
	</div>';
	return $output;
}

function mediaelement_vimeo($vimeourl){
	//print $vimeourl;die;
	$output = "";
	$output = '<div class="mp4_video video">
	<video autoplay=1 id="player1" width="640" height="360" style="max-width:100%;" preload="none">
		 <source autoplay=1 type="video/vimeo" src="' . $vimeourl . '">
		<track srclang="en" label="English" kind="subtitles" src="' . MEDIAELEMENTJS_DIR_PARENT . 'mediaelement.vtt">
		<track srclang="en" kind="chapters" src="' . MEDIAELEMENTJS_DIR_PARENT . 'chapters.vtt">
	</video>
	</div>';
	return $output;
}
	

	
?>
