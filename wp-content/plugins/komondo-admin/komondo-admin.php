<?php
/*
Plugin Name: komondo-admin
Plugin URI: https://www.komando.com
Description: komondo admin is use to manage admin custom functionality
Author: Ujjwal Ahluwalia
Author URI: https://www.komando.com
Version: 1.0
*/


add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
///////////////////Start: At admin side filter category/////////////////////
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'posts'; // change to your post type
	$taxonomy  = 'posts'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET['cat']) ? $_GET['cat'] : '';
		$info_taxonomy = get_taxonomy($taxonomy);

		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			//'name'            => $taxonomy,
			//'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
		
		
		
	};
}
/**
 * Filter posts by taxonomy in admin
 */
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'posts'; // change to your post type
	$taxonomy  = 'posts'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	//print_r($q_vars);
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
	
}

//#################End: At admin side filter category#################/

/**********************************Section***************************************/
///////////////////Start: Download Image from third party & Save to wordoress/////////////////////
/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_videos_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);


    if ( "videos_library" != $post_type ) return;

    // - Update the post's metadata.
	//print_r($_POST);
	$post_meta = get_post_meta($post_id);

	if(isset($post_meta) && isset($post_meta['vl_video_type']) && $post_meta['vl_video_type'][0] == 'youtube'){
	  $imageurl = "";
	  // Run match checks for specific video sources
		
		$url = $post_meta['vl_url'][0];
		$youtube_information_arr = komaindo_front_getVideoDetails($url);
		
		/*if ( preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches) ) {
			$yurl = "https://www.googleapis.com/youtube/v3/videos?part=id,contentDetails,snippet,statistics,status&id=" . $matches[0] .  "&key=AIzaSyAqZGw8xvJwNdyZnXwIwT9nBQrCUCGsRoI";
			$youtube_information_arr = json_decode(file_get_contents($yurl));

			
		}*/

			$thumbnails = end($youtube_information_arr->items[0]->snippet->thumbnails);
			$imageurl = $thumbnails->url;
		//print_r();die;
		
		if(!empty($imageurl))
	    $media_post_id = fpHandleUpload($post_id, $imageurl);
	
	} else if(isset($post_meta) && isset($post_meta['vl_video_type']) && $post_meta['vl_video_type'][0] == 'vimeo'){
		$imageurl = "";
		$vimeo_information_arr = komaindo_front_get_vimeodetails($post_meta['vl_url'][0]);
		if(!empty($vimeo_information_arr)){
		  $imageurl	= $vimeo_information_arr[0]->thumbnail_large;
		}
	    //print $imageurl;die;
	  if(!empty($imageurl))
	    $media_post_id = fpHandleUpload($post_id, $imageurl);
	}
}

function fpHandleUpload($post_id, $file) {


	//function attach_image_url($file, $post_id, $desc = null) {
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    if ( ! empty($file) ) {
        // Download file to temp location
        $tmp = download_url( $file );
        // Set variables for storage
        // fix file filename for query strings
        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);
        $file_array['name'] = basename($matches[0]);        
        $file_array['tmp_name'] = $tmp;
        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
        }
        // do the validation and storage stuff
        $id = media_handle_sideload( $file_array, $post_id, $desc );
        // If error storing permanently, unlink
        if ( is_wp_error($id) ) {@unlink($file_array['tmp_name']);}
        //add_post_meta($post_id, '_thumbnail_id', $id, true);
		update_post_meta($post_id, '_thumbnail_id', $id);
    }

}

add_action( 'save_post', 'save_videos_meta', 10, 3 );
add_action( 'publish_post', 'save_videos_meta', 10, 3 );



//Redierction after video library added
add_filter('redirect_post_location', 'redirect_to_post_on_publish_or_save');
function redirect_to_post_on_publish_or_save($location) {
    global $post;
    if (isset($post->post_type) && $post->post_type == 'videos_library'){
        // Always redirect to the post
		$location = admin_url() .'post-new.php?videoslibrary_postid='.$post->ID;
    }
    return $location;
}

//#################End: At admin side filter category#################/



/**********************************Section****************************************/
////////////////////////////Start: Add Popup in Episode Post Type///////////////////////////////
class Custom_Post_Type_komondo_admin {
	
	
	public function __construct() {
		
		add_action( 'init', array( &$this, 'init' ) );
		
		if ( is_admin() ) {
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
		}
	}
	
	
	/** Frontend methods ******************************************************/
	
	
	/**
	 * Register the custom post type
	 */
	public function init() {
	    add_thickbox(); 
	}
	
	
	/** Admin methods ******************************************************/
	
	
	/**
	 * Initialize the admin, adding actions to properly display and handle 
	 * the Book custom post type add/edit page
	 */
	public function admin_init() {
		global $pagenow;
		
		if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {
			//use to add extra field
			add_action( 'add_meta_boxes', array( &$this, 'meta_boxes' ) );
			//use to manage tickbox
			add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ), 1, 2 );
			
			//use to save extra field
			//add_action( 'save_post', array( &$this, 'meta_boxes_save' ), 1, 2 );


		}
	}
	
	
	/**
	 * Save meta boxes
	 * 
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 */
	/*public function meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'book' ) return;
			
		$this->process_book_meta( $post_id, $post );
	}
	*/
	
	
	/**
	 * Function for processing and storing all book data.
	 */
	//private function process_book_meta( $post_id, $post ) {
		//update_post_meta( $post_id, '_image_id', $_POST['upload_image_id'] );
	//}
	
	
	/**
	 * Set a more appropriate placeholder text for the New Book title field
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'post' ) { add_thickbox(); };
		return $text;
	}
	
	
	/**
	 * Add and remove meta boxes from the edit page
	 */
	public function meta_boxes() {
		add_meta_box( 'videos', __( 'Videos' ), array( &$this, 'video_meta_box' ), 'post', 'normal', 'high' );
	}
	
	
	/**
	 * Display the image meta box
	 */
	public function video_meta_box() {
		global $post;
		if(!empty($_REQUEST['videoslibrary_postid'])){
		  $postdata = empty($_REQUEST['videoslibrary_postid']) ? array(): get_post($_REQUEST['videoslibrary_postid']);
		  
		  
		  
		} else if(!empty($_REQUEST['post'])){
		  $postdata = empty($_REQUEST['post']) ? array(): get_post($_REQUEST['post']);
		  $post_meta = get_post_meta($postdata->ID);
		  $postdata = get_post($post_meta['videos'][0]);

		}

		 
		  
		
		$videoimage = get_the_post_thumbnail($postdata->ID, array(100,100));
		echo $videoimage;
		?>
		
		<input type="hidden" name="videoslibrary_postid" id="videoslibrary_postid" value="<?php echo $postdata->ID; ?>" />
		<p>
			<a class="" title="<?php esc_attr_e( 'Add Video' ) ?>"  id="VideoLibraryUpdate" href="<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true" class="thickbox" style="<?php echo (  $postdata->ID ? 'display:none;' : '' ); ?>"><?php _e( 'Add Video' ) ?></a>
			
			<a class="" title="<?php esc_attr_e( 'Update Video' ) ?>" class="thickbox" href="<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true" id="update-video" style="<?php echo ( ! $postdata->ID ? 'display:none;' : '' ); ?>"><?php _e( 'Update Video' ) ?></a>
		</p>
		<?php 
		      $isshow_popup=false;
		      global $pagenow;
		
		      if ( $pagenow == 'post-new.php' && (!(isset($_REQUEST['videoslibrary_postid']) && $_REQUEST['videoslibrary_postid'] > 0))){
		        $isshow_popup=true;
		      } else if(!(isset($_REQUEST['post']) && $_REQUEST['post'] > 0)){
		        $isshow_popup=false;
		      }
			 // var_dump(( $pagenow == 'post-new.php' && (!(isset($_REQUEST['videoslibrary_postid']) && $_REQUEST['videoslibrary_postid'] > 0))));die;
			  
		?>
		<?php if($isshow_popup){?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$( window ).load(function() {
			  // Run code
			  // replace the default send_to_editor handler function with our own
				tb_show('Video Library','<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true&width=600&height=550');

				return false;
			});
			
			$('#VideoLibraryUpdate').click(function(){
				
				// replace the default send_to_editor handler function with our own
				tb_show('Video Library','<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true&width=600&height=550');
			
				return false;
			});
		});
		</script>
		<?php } ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$( window ).load(function() {
			   $('#acf-field-videos').val($('#videoslibrary_postid').val());
			   $('#acf-videos').hide();
			   //alert($('#acf-field-videos').val());
				return false;
			});
		});
		</script>
		<?php
	}
}


// finally instantiate our plugin class and add it to the set of globals
$GLOBALS['custom_post_type_komondo_admin'] = new Custom_Post_Type_komondo_admin();

//#################End: Add Popup in Episode Post Type#################/

/**********************************Section****************************************/
////////////////////////////Start: Set Default Title///////////////////////////////
add_filter('default_title', 'set_default_title', 10, 2 );

function set_default_title( $post_title, $post ){
  $custom_post_type = 'post';
  $post_title = "";
  
  // do it only on your custom post type(s)
  if( $post->post_type == $custom_post_type ){
		if(!empty($_REQUEST['videoslibrary_postid'])){
		  $postdata = empty($_REQUEST['videoslibrary_postid']) ? array(): get_post($_REQUEST['videoslibrary_postid']);
		  
		} else if(!empty($_REQUEST['post'])){
		  $postdata = empty($_REQUEST['post']) ? array(): get_post($_REQUEST['post']);
		  $post_meta = get_post_meta($postdata->ID);
		  $postdata = get_post($post_meta['videos'][0]);
		}

		if(isset($postdata->ID) && $_REQUEST['videoslibrary_postid'] == $postdata->ID){
		  $post_title = $postdata->post_title;
		  add_filter('acf/load_value/key=field_58bb70edb9d93', 'amount_of_time_the_videos_acf_load_value', 10, 3);
		}
  } 
  // create your preferred title here
  //$post_title = $custom_post_type . date( 'Y-m-d :: H:i:s', time() );
  
  return $post_title;
}


add_filter( 'default_content', 'set_default_content' , 10, 2 );
function set_default_content( $content, $post ) {

    switch( $post->post_type ) {
        case 'post':
		
			if(!empty($_REQUEST['videoslibrary_postid'])){
			  $postdata = empty($_REQUEST['videoslibrary_postid']) ? array(): get_post($_REQUEST['videoslibrary_postid']);
			  
			} else if(!empty($_REQUEST['post'])){
			  $postdata = empty($_REQUEST['post']) ? array(): get_post($_REQUEST['post']);
			  $post_meta = get_post_meta($postdata->ID);
			  $postdata = get_post($post_meta['videos'][0]);
			}

			if(isset($postdata->ID) && $_REQUEST['videoslibrary_postid'] == $postdata->ID){
			  $post_content = $postdata->post_content;
			}
            $content = $post_content;
        break;
   }

    return $content;
}
//#################End: Set Default Title#######################/




/**********************************Section****************************************/
/////////////////////Start: To change default position of WP meta boxes////////////////////

add_action('do_meta_boxes', 'wpse33063_move_meta_box');

function wpse33063_move_meta_box(){
    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', 'post', 'normal', 'high');
	//remove theme options from form
	remove_meta_box( 'vlog_layout', 'post', 'side' );
	remove_meta_box( 'vlog_sidebar', 'post', 'side' );
	
	//remove_meta_box( 'myplugin_sectionid', 'post', 'advanced' );
	
	//remove theme options form pages
	remove_meta_box( 'vlog_fa', 'page', 'normal');
	remove_meta_box( 'vlog_modules', 'page', 'normal' );
	remove_meta_box( 'vlog_pagination', 'page', 'normal' );
	

	
	
}
//#################End: To change default position of WP meta boxes#######################/

/**********************************Section****************************************/


add_filter('wp_insert_post_data', 'change_title', 99, 2);
function change_title($data, $postarr) {   
	
	if(isset($postarr['post_status']) && $postarr['post_status'] == 'auto-draft') {
	  return $data;
	} else if(isset($postarr['post_status']) && $postarr['post_status'] == 'publish') {

	if(isset($postarr['post_type']) && $postarr['post_type'] == 'videos_library') {
		$videotype = $postarr['fields']['field_58a31a2a2baa2'];
		if(!empty($videotype) && $videotype == 'youtube'){
			$youtubeURL = $postarr['fields']['field_58a31a70d1a21'];
			$youtube_information_arr = komaindo_front_getVideoDetails($youtubeURL);
			
			if(!empty($youtube_information_arr)){
			  //$youtube_information_arr = json_decode($youtube_data);
			  if(isset($youtube_information_arr->items[0]->snippet->title) && $youtube_information_arr->items[0]->snippet->title != ""){
				$data['post_title'] = $youtube_information_arr->items[0]->snippet->title;
			  }
			  
			  if(isset($youtube_information_arr->items[0]->snippet->description) && $youtube_information_arr->items[0]->snippet->description != ""){
				$data['post_content']  = $youtube_information_arr->items[0]->snippet->description;
			  }

			  
			}
		} else if(!empty($videotype) && $videotype == 'vimeo'){
			$vimeoURL = $postarr['fields']['field_58a31a70d1a21'];
			$vimeo_information_arr = komaindo_front_get_vimeodetails($vimeoURL);
			if(!empty($vimeo_information_arr)){
			  //$youtube_information_arr = json_decode($vimeo_data);
			  if(isset($vimeo_information_arr[0]->title) && $vimeo_information_arr[0]->title != ""){
				$data['post_title'] = $vimeo_information_arr[0]->title;
			  }
			  if(isset($vimeo_information_arr[0]->description) && $vimeo_information_arr[0]->description != ""){
			    $description = $vimeo_information_arr[0]->description; //str_replace(array("\n", "\r", "\r\n", "\n\r"), NULL, $youtube_information_arr->description);
				$data['post_content'] = $description;
			  }
			}
		}
	}
	if(isset($postarr['post_type']) && $postarr['post_type'] == 'post') {
		  $post_time = strtotime($postarr['post_date']);
		  if($post_time < time()){
		    $data['post_status'] = 'publish';
		  } else {
		    $data['post_status'] = 'future';
		  }
		
	} else {
	  $data['post_status'] = 'publish';
	}
	
    return $data;
	}	
}

function komaindo_front_getVideoDetails($url) {	
	
	if ( preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches) ) {
		$yt_id = $matches[0]; 
		$yurl = "https://www.googleapis.com/youtube/v3/videos?part=id,contentDetails,snippet,statistics,status&id=" . $yt_id .  "&key=AIzaSyAqZGw8xvJwNdyZnXwIwT9nBQrCUCGsRoI";
		$hash = json_decode(file_get_contents($yurl));

		return $hash;
	}
}

$vimeo = komaindo_front_get_vimeodetails('https://vimeo.com/202925050');

function komaindo_front_get_vimeodetails($url) {
    $host = explode('.', str_replace('www.', '', strtolower(parse_url($url, PHP_URL_HOST))));
    $host = isset($host[0]) ? $host[0] : $host;

	$video_id = substr(parse_url($url, PHP_URL_PATH), 1);
	$hash = json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$video_id}.json"));
	return $hash;
}

function komaindo_front_get_videofulldetails($postid){
	$post['post'] = get_post($postid);
	$post['meta'] = get_post_meta($postid);
	if(isset($post['meta']['videos'][0]) && $post['meta']['videos'][0] != ""){
		$post['video'] = get_post_meta($post['meta']['videos'][0]);
		$post['video']['post'] = get_post($post['meta']['videos'][0]);
	}
	return $post;
}


function komaindo_front_get_video_library_fulldetails($postid){
	$post['vl_post'] = get_post($postid);
	$post['vl_meta'] = get_post_meta($postid);
	
	return $post;
}
function komaindo_front_get_video_time($postid){
	$details =komaindo_front_get_videofulldetails($postid);
	//print_r($details);
	return $details['meta']['vc_amount_of_time_the_videos'][0];
	
}



 
 

//Load default vaule of Amount of time the videos
function amount_of_time_the_videos_acf_load_value( $value, $post_id, $field ) {
	if(!empty($_REQUEST['videoslibrary_postid'])){
	  $postdata = empty($_REQUEST['videoslibrary_postid']) ? array(): get_post($_REQUEST['videoslibrary_postid']);
	  
	} else if(!empty($_REQUEST['post'])){
	  $postdata = empty($_REQUEST['post']) ? array(): get_post($_REQUEST['post']);
	  $post_meta = get_post_meta($postdata->ID);
	  $postdata = get_post($post_meta['videos'][0]);
	}
	$post_meta_video = komaindo_front_get_video_library_fulldetails($postdata->ID);

	if(isset($post_meta_video['vl_meta']['vl_video_type'][0]) && $post_meta_video['vl_meta']['vl_video_type'][0] == 'youtube'){
	  $youtubeurl = $post_meta_video['vl_meta']['vl_url'][0];
	  $videos = komaindo_front_getVideoDetails($youtubeurl);
	  $value = covtime($videos->items[0]->contentDetails->duration); 
	} else if(isset($post_meta_video['vl_meta']['vl_video_type'][0]) && $post_meta_video['vl_meta']['vl_video_type'][0] == 'vimeo'){
	  $videos = komaindo_front_get_vimeodetails($post_meta_video['vl_meta']['vl_url'][0]);
	  $value = gmdate("i:s", $videos[0]->duration);
//date("mm:ss", $videos[0]->duration); 
	} else{
	  $value = "";
	}
	
	

    return $value;
}


function covtime($youtube_time) {
    preg_match_all('/(\d+)/',$youtube_time,$parts);

    // Put in zeros if we have less than 3 numbers.
    if (count($parts[0]) == 1) {
        array_unshift($parts[0], "0", "0");
    } elseif (count($parts[0]) == 2) {
        array_unshift($parts[0], "0");
    }

    $sec_init = $parts[0][2];
    $seconds = $sec_init%60;
    $seconds_overflow = floor($sec_init/60);

    $min_init = $parts[0][1] + $seconds_overflow;
    $minutes = ($min_init)%60;
    $minutes_overflow = floor(($min_init)/60);

    $hours = $parts[0][0] + $minutes_overflow;


    if($hours != 0)
        return sprintf('%02s',$hours).':'.sprintf('%02s',$minutes).':'.sprintf('%02s',$seconds);
    else
        return sprintf('%02s',$minutes).':'.sprintf('%02s',$seconds);
}




// acf/load_value/key={$field_key} - filter for a specific field based on it's name



//This function is used to get Home page carsoul with two conditions & ASC order
function komando_admin_get_home_slider_posts(){
	
	$posts_array = komando_front_get_home_slider_posts();
	$order_results = array();
 	foreach($posts_array as $order){
	  	$order_results[$order->ID] = komaindo_front_get_videofulldetails($order->ID);
	}

  return $order_results;
}