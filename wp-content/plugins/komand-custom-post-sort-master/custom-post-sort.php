<?php
/**
 * Plugin Name:   Komando Override: Custom Post Sort
 * Plugin URI:    https://github.com/jpen365/custom-post-sort
 * Description:   Add a custom post order field to WordPress posts and display posts on the blog page using this new field.
 * Version:       0.1
 * Author:        Jon Penland
 * Author URI:    http://www.jonpenland.com
 * Text Domain:   custom-post-sort
 */


/* Create custom meta data box to the post edit screen */

function jpen_custom_post_sort( $post ){
  add_meta_box( 
    'custom_post_sort_box', 
    'Pinning Position in List of Posts', 
    'jpen_custom_post_order', 
    'post' ,
    'side'
    );
}
add_action( 'add_meta_boxes', 'jpen_custom_post_sort' );


/* Add a field to the metabox */

function jpen_custom_post_order( $post ) {
  if($post->post_type == 'post'){
  wp_nonce_field( basename( __FILE__ ), 'jpen_custom_post_order_nonce' );
  $current_pos = get_post_meta( $post->ID, '_custom_post_order', true); ?>
  <p>Enter the pinning position at which you would like the post to appear. For exampe, post "1" will appear first, post "2" second, and so forth.</p>
  <?php $args = array(
		'posts_per_page'   => 8,
		'offset'           => 0,
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'meta_query'	=> array(
			array(
					'key'	  	=> 'vc_pinning',
					'value'	  	=> '1',
					'compare' 	=> '=',
			),
		),
		
		
	);
	//$the_query = new WP_Query( $args );
	//print_r($the_query);die;
	$posts_array = get_posts( $args ); 
	$already_exists = array();
	
	foreach($posts_array as $postdata){
	    $postmeta = get_post_meta( $postdata->ID, '_custom_post_order', true);
		$already_exists[$postmeta] = $postmeta; 
	}
	?>
  <p>
	 <?php echo '<select id="selectPinning" name="pos">';
			echo '<option value="0" selected="selected">Select</option>';
			$catDisabled = false;
			for($i=1;$i <= 8;$i++){
				$selected = '';
				$disabled = '';
				
				if( isset($current_pos) && ($current_pos == $i) && $i > 0){
						$selected = ' selected = "selected"';
				}
				
				if( array_key_exists($i, $already_exists) && $i > 0){
						$disabled = ' disabled = "disabled"';
				}
				
				echo '<option' . $selected . $disabled.' value="'.$i.'">' . $i . '</option>';
			}
			echo '</select>';
	  ?>
	  </p>
  <?php
}
}


/* Save the input to post_meta_data */

function jpen_save_custom_post_order( $post_id ){
  if ( !isset( $_POST['jpen_custom_post_order_nonce'] ) || !wp_verify_nonce( $_POST['jpen_custom_post_order_nonce'], basename( __FILE__ ) ) ){
    return;
  } 
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ){
    return;
  }
  if ( isset( $_REQUEST['pos'] ) ) {
    update_post_meta( $post_id, '_custom_post_order', sanitize_text_field( $_POST['pos'] ) );
  }
}
add_action( 'save_post', 'jpen_save_custom_post_order' );


/* Add custom post order column to post list */

function jpen_add_custom_post_order_column( $columns ){
  return array_merge ( $columns,
    array( 'pos' => 'Pinning Position', ));
}

//add_filter('manage_posts_columns' , 'jpen_add_custom_post_order_column');
add_filter('manage_post_posts_columns', 'jpen_add_custom_post_order_column');



/* Display custom post order in the post list */

function jpen_custom_post_order_value( $column, $post_id ){
  if ($column == 'pos' ){
    echo '<p>' . get_post_meta( $post_id, '_custom_post_order', true) . '</p>';
  }
}
add_action( 'manage_posts_custom_column' , 'jpen_custom_post_order_value' , 10 , 2 );


add_action('admin_menu', 'home_page_slider');

function home_page_slider()
{
	//put a menu within all custom types if apply
            $post_types = get_post_types();
            foreach( $post_types as $post_type) 
                {
                        
                    //check if there are any taxonomy for this post type
                                    
                    
                    if ($post_type == 'post')
    					    add_submenu_page( 'edit.php', 'Home Page Slider with Pinning', 'Home Page Slider with Pinning', 'manage_options', 'home-page-slider', 'home_page_slider_callback');
                        
                }
	
	
	
}

function home_page_slider_callback() { 
	global $wpdb, $wp_locale;
            
	$posts_array = komando_admin_get_home_slider_posts(); 
	$counter=1;
	?>
		<div class="wrap">
			
                
                
                <form action="#" method="get" id="to_form">
                    <input type="hidden" name="page" value="to-interface-<?php echo esc_attr($post_type) ?>" />
                    <?php
                
                     
                       // echo '<input type="hidden" name="post_type" value="'. esc_attr($post_type) .'" />';

                            ?>
                            
                            <h2 class="subtitle"><?php _e( "Order") ?></h2>
                            <table cellspacing="0" class="wp-list-taxonomy">
                                <thead>
                                <tr>
                                    <th style="" class="column-cb check-column" id="cb" scope="col">&nbsp;</th>
									
									<th style="" class="" id="author" scope="col"><?php _e( "Title") ?></th>
									<th style="" class="manage-column" id="categories" scope="col"><?php _e( "Position") ?></th>    
									</th>
									<th style="" class="manage-column" id="categories" scope="col"><?php _e( "Published") ?></th>
									<th style="" class="manage-column" id="categories" scope="col"><?php _e( "Pinning") ?></th>
									<th style="" class="manage-column" id="categories" scope="col"><?php _e( "Update Option") ?></th>
									</tr>
                                </thead>

   
                                <tbody id="the-list">
                                <?php
 
										foreach ($posts_array as $post) {
											$title = $post['post']->post_title;
                                            $pinning = !empty($post['meta']['vc_pinning'][0])?'Yes(' . $post['meta']['_custom_post_order'][0] . ')' : '-';
											$postdate = $post['post']->post_date;
											
                                            ?>
                                                <tr valign="top">
                                                        <th class="check-column" scope="row">
															&nbsp;</th>
                                                        
														<td class="categories column-categories"><b><?php echo $title; ?></b></td>
                                                        <td class="categories column-categories"><?php echo $counter; ?></td>
														<td class="categories column-categories"><?php echo $postdate; ?></td>
														<td class="categories column-categories"><?php echo $pinning; ?></td>
														<td class="categories column-categories"><a href="<?php echo admin_url() .'post.php?post='.$post['post']->ID  .'&action=edit'; ?>">Edit</a></td>
                                                </tr>
                                            
                                            <?php
											$counter++;
                                        }
                                ?>
                                </tbody>
                            </table>

                </form>
                
                
                
            </div>
            <?php 
            
}
 
?>