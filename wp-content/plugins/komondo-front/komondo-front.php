<?php
/*
Plugin Name: komondo-front
Plugin URI: https://www.komando.com
Description: komondo front is use to manage admin custom functionality
Author: Ujjwal Ahluwalia
Author URI: https://www.komando.com
Version: 1.0
*/


/**********************************Section : Helping Functions****************************************/


//This function is used to get Home page carsoul with two conditions & ASC order
function komando_front_get_home_slider_posts(){
	
	$posts_array = array();
	for($i=1; $i <= 8;$i++){
		$posts_array[$i] = 0;
	}
	$args1 = array(
		'meta_key' => '_custom_post_order',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'orderby'   => array(
					'meta_value'    => 'ASC',
					),
		'posts_per_page'   => 8,
		'offset'           => 0,
		'post_type'        => 'post',
		'post_status'      => 'publish',			
		'meta_query' => array(
			array(
				 'key'     => 'vc_pinning',
				 'value'   => 1,
				 'compare' => '='
				 
		    ),
			
	   )
  );

  //$query1 = new WP_query ( $args1 );
  $posts_array1 = get_posts( $args1 ); 

  foreach($posts_array1 as $postdata){
    $postmeta = get_post_meta( $postdata->ID, '_custom_post_order', true);
	if($postmeta > 0)
	 $posts_array[$postmeta] =$postdata;
    
  }
  
  $position = array();

  foreach($posts_array as $key => $postdata1){
	if(!((bool)$postdata1)){
	  $position[] = $key;
	}
    
  }


  $args2 = array(
  'post_type' => 'post',
  'posts_per_page'   => count($position),
  'offset'           => 0,
  'orderby' => 'date',
  'order' => 'DESC',
  'meta_query' => array(
			'relation' => 'AND',
			array(
				 'key'     => 'vc_post_show_in_home_carousal',
				 'value'   => 1,
				 'compare' => '='//'compare' => 'IN'
				 
		    ),
			
	   )
);

  //$query1 = new WP_query ( $args1 );
  $posts_array2 = get_posts( $args2 ); 
  
  
  

  foreach($posts_array2 as $postdata2){
	  for($i=0;$i < count($position) && $i < count($posts_array2) ; $i++){
		  if(!((bool)$posts_array[$position[$i]])){
			$posts_array[$position[$i]] = $postdata2;
			break;
		  }
	  }
    
	
  }

  $posts_feature = array_filter($posts_array);
  return $posts_feature;
}


//Home Page category three piece section category only
function custom_get_terms_orderby($orderby, $args) {
	if (isset($args['orderby']) && $args['orderby'] == "term_order" && $orderby != "term_order"){
		return "t.term_order";
	}
	return $orderby;
}
function komando_front_get_home_three_piece_categories(){
	$taxonomy = 'category';
	add_filter('get_terms_orderby', 'custom_get_terms_orderby', 1, 2);
	// Query pages.
	$args = array(
		'orderby' =>  'term_order' ,
		'order'   => 'ASC',
		'hide_empty' => false
		
	);
	$taxonomy_terms = get_terms($taxonomy, $args);
	$categories = array();
	foreach($taxonomy_terms as $term){
		$categories[$term->term_id]['term'] = $term;
		$categories[$term->term_id]['meta_data'] = get_fields($term);
	}

	$three_piece_categories = array();
	foreach($categories as $cat){
		if(isset($cat['meta_data']['vcat_come_in_three_piece']) && $cat['meta_data']['vcat_come_in_three_piece'] == 1){
		  $three_piece_categories[$cat['term']->term_id] = $cat;   
		}
	}
	return $three_piece_categories;
}


//all categories
function komando_front_get_home_all_categories(){
	$taxonomy = 'category';
	add_filter('get_terms_orderby', 'custom_get_terms_orderby', 1, 2);
	// Query pages.
	$args = array(
		'orderby' =>  'term_order' ,
		'order'   => 'ASC',
		
	);
	$taxonomy_terms = get_terms($taxonomy, $args);
	$categories = array();
	foreach($taxonomy_terms as $term){
		$categories[$term->term_id]['cat'] = $term;
		$categories[$term->term_id]['meta'] = get_fields($term);
	}

	
	return $categories;
}

//This function is used to get category id based data
function komando_front_get_home_three_piece_categories_based_post(){
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'orderby'   => array(
					'menu_order'    => 'ASC',
					'post_date'     =>  'DESC'
					),
		);
	$posts_array = get_posts( $args ); 
	return $posts_array;
}

//This function is used to get Home page carsoul with two conditions & ASC order
function komando_front_get_post_tabs_posts_category_based($catid) {
	$args = array(
		'posts_per_page'   => 8,
		'offset'           => 0,
		'category'         => $catid,
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'orderby'   => array(
					'menu_order'    => 'ASC',
					'post_date'     =>  'DESC'
					),
	);
	$posts_array = get_posts( $args ); 
	return $posts_array;
}

/*
	$urls   = array();
$videos = array();

// vimeo test
$urls[] = 'http://vimeo.com/6271487';
$urls[] = 'http://vimeo.com/68546202';


$urls[] = 'https://www.youtube.com/watch?v=Mtn6KqO3RcA&list=RD022g5PnydsOrY';


foreach ($urls as $url) {
    $videos[] = getVideoDetails($url);
}

function getVideoDetails($url)
{
    $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))));
    $host = isset($host[0]) ? $host[0] : $host;

    switch ($host) {
        case 'vimeo':
            $video_id = substr(parse_url($url, PHP_URL_PATH), 1);
            $hash = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$video_id}.json"));

            // header("Content-Type: text/plain");
             print_r($hash);
            // exit;

            return array(
                    'provider'          => 'Vimeo',
                    'title'             => $hash[0]->title,
                    'description'       => str_replace(array("<br>", "<br/>", "<br />"), NULL, $hash[0]->description),
                    'description_nl2br' => str_replace(array("\n", "\r", "\r\n", "\n\r"), NULL, $hash[0]->description),
                    'thumbnail'         => $hash[0]->thumbnail_large,
                    'video'             => "https://vimeo.com/" . $hash[0]->id,
                    'embed_video'       => "https://player.vimeo.com/video/" . $hash[0]->id,
                );
            break;

        case 'youtube':
            preg_match("/v=([^&#]*)/", parse_url($url, PHP_URL_QUERY), $video_id);
            $video_id = $video_id[1];
            $hash = json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$video_id}?v=2&alt=jsonc"));

            // header("Content-Type: text/plain");
            // print_r($hash);
            // exit;

            return array(
                    'provider'          => 'YouTube',
                    'title'             => $hash->data->title,
                    'description'       => str_replace(array("<br>", "<br/>", "<br />"), NULL, $hash->data->description),
                    'description_nl2br' => str_replace(array("\n", "\r", "\r\n", "\n\r"), NULL, nl2br($hash->data->description)),
                    'thumbnail'         => $hash->data->thumbnail->hqDefault,
                    'video'             => "http://www.youtube.com/watch?v=" . $hash->data->id,
                    'embed_video'       => "http://www.youtube.com/v/" . $hash->data->id,
                );
            break;
    }
}


print_r($videos);
	
*/	


/**********************************Section****************************************/




