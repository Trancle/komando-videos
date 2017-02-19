<?php
/*
Plugin Name: komondo-admin
Plugin URI: https://www.komando.com
Description: komondo admin is use to manage admin custom functionality
Author: Ujjwal Ahluwalia
Author URI: https://www.komando.com
Version: 1.0

	Copyright: © 2017 Ujjwal Ahluwalia (email : ujjwalahluwalia20@gmail.com)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
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
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
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

///////////////////End: At admin side filter category/////////////////////

/**********************************Section***************************************/
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

    // If this isn't a 'book' post, don't update it.
    if ( "videos_library" != $post_type ) return;

    // - Update the post's metadata.
	//print_r($_POST);
	$post_meta = get_post_meta($post_id);
	if(isset($post_meta) && isset($post_meta['vl_video_type'])){
	  $imageurl = "";
	  // Run match checks for specific video sources
		if ( preg_match("/youtube\.com\/(v\/|watch\?v=)([\w\-]+)/", $post_meta['vl_url'][0]) ) {
			preg_match("/youtube\.com\/(v\/|watch\?v=)([\w\-]+)/", $post_meta['vl_url'][0], $match_id);
			//$imageurl = 'https://img.youtube.com/vi/<insert-youtube-video-id-here>/maxresdefault.jpg';
			$yt_id = $match_id[2]; 
			$imageurl = "http://img.youtube.com/vi/" . $yt_id . "/0.jpg";
		}
	  if(!empty($imageurl))
	    $media_post_id = fpHandleUpload($post_id, $imageurl);
	  //print_r($media_post_id);die;	
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
        add_post_meta($post_id, '_thumbnail_id', $id, true);
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
		$location = admin_url() .'post-new.php';
    }
    return $location;
}




/**********************************Section****************************************/
////////////////////////////Add Popup in Episode Post Type///////////////////////////////
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
				tb_show('Video Library','<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true');

				return false;
			});
			
			$('#VideoLibraryUpdate').click(function(){
				
				// replace the default send_to_editor handler function with our own
				tb_show('Video Library','<?php echo admin_url(); ?>/admin.php?page=admin-videos-library-page.php&TB_iframe=true');
			
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



