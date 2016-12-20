<?php
/**
 * Created by PhpStorm.
 * functions-post-types.php
 * Functions pertaining to post_type usage
 * User: gilbert
 * Date: 5/18/2015
 * Time: 2:39 PM
 */

/**
 * Create Custom Post Types
 *
 * Recursively creates individual custom post types
 * from the list provided in the array of $post_types.
 *
 *
 */
function create_post_types() {
    foreach( list_of_custom_post_types() as $pt  ) {
        create_post_type( $pt );
    }
}

/**
 * Define a List of Custom Post Types
 *
 * Master list of custom post types in K2
 *
 * @return array of strings -- List of Post Types
 */
function list_of_custom_post_types() {
    return array(
        'columns',
        'downloads',
        'apps',
        'cool_sites',
        'tips',
        'buying_guides',
        'charts',
        'newsletters',
        'previous_shows',
        'happening_now',
        'qotd',
        'small_business',
        'new_technologies',
    );
}
/**
 * Create an individual Custom Post Type
 *
 * Creates and registers the taxonomy and
 * arrays needed for a Custom Post Type.
 * Called recursively by create_post_types( )
 *
 * @param string $type is one of: 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'charts',
 * 'newsletters', 'previous_shows', 'happening_now', 'qotd', or 'small_business'.
 * @return void
 *
 * User: gilbert, Enhancement: #2247
 */
function create_post_type( $type ) {
    $titles = pt_make_title_hash( $type );

    register_taxonomy($type . '_categories', $type, pt_register_taxonomy_arguments($titles) );
    register_taxonomy_for_object_type($type . '_categories', $type); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', $type);
    register_post_type($type, pt_make_post_type_arguments( $type, $titles ) ); // Register Custom Post Type

    pt_register_url_rewrites_for_post_type( $titles );
}

add_action('init', 'register_k2_menu'); // Add k2 Blank Menu
add_action('init', 'create_post_types'); // Add all other post types

/**
 * Modify the Type parameter
 *
 * Creates an array of the Type parameter turned into
 * a title by replacing underscores with a space and
 * capitalizing the first letter of each word, with a
 * plural version, a singular version, and a version
 * that is not capped and has hyphens instead of
 * underscores for use in a URL.
 *
 * @param string $type
 * @return array of strings -- Lookup table for variations on the $type
 */
function pt_make_title_hash( $type ) {
    $ret = array();
    // Replace underscores with space and Initial Cap each word
    $ret['plural'] = ucwords( str_replace('_',' ',$type) );
    // Make a plural be singular by removing the ending 's'
    $ret['singular'] = substr($ret['plural'],0,-1);
    // Replace underscores with hyphens
    $ret['slug'] = str_replace('_','-',$type);

    // Handle the variations of the $type hyphenation, capitalization, and single/plural form
    switch($type) {
        case 'happening_now':
            $ret['plural'] = 'Happening Now';
            $ret['singular'] = 'Happening Now';
            break;
        case 'qotd':
            $ret['plural'] = 'Questions';
            $ret['singular'] = 'Question';
            $ret['slug'] = 'question-of-the-day';
            break;
        case 'small_business':
            $ret['plural'] = 'Small Business';
            $ret['singular'] = 'Small Business';
            break;
    }
    return $ret;
}

/**
 * Define Post Type Arguments
 *
 * Assigns a value to each of the desired parameters
 * for a Custom Post Type.
 *
 * @param string $type -- the Post Type
 * @param array of strings $titles -- Lookup table for variations on the $type
 * @return array of strings $ret -- values assigned to variables that include the Titles
 */
function pt_make_post_type_arguments( $type, $titles ) {
    $ret = array(
        'labels' => array(
            'name' => __($titles['plural'], $type), // Rename these to suit
            'singular_name' => __($titles['plural'], $type),
            'add_new' => __('Add New', $type),
            'add_new_item' => __('Add New ' . $titles['plural'], $type),
            'edit' => __('Edit', $type),
            'edit_item' => __('Edit ' . $titles['plural'], $type),
            'new_item' => __('New ' . $titles['plural'], $type),
            'view' => __('View ' . $titles['plural'], $type),
            'view_item' => __('View ' . $titles['plural'], $type),
            'search_items' => __('Search ' . $titles['plural'], $type),
            'not_found' => __('No ' . $titles['plural'] . ' Found', $type),
            'not_found_in_trash' => __('No ' . $titles['plural'] . ' Found in Trash', $type)
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'author',
            'custom-fields',
            'revisions'
        ),
        'can_export' => true, // Allows export in Tools > Export
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => '5',
        'taxonomies' => array('post_tag'),
        'rewrite' => array(
            'slug' => $titles['slug'] . '/%post_id%',
            'with_front' => false,
            'pages' => true,
            'ep_mask' => EP_ALL
        )
    );

    // QOTD has a special public name. Override here
    if ($type == 'qotd'){
        $ret['labels']['name'] =  __('QOTD', $type);
        $ret['labels']['singular_name'] =  __('QOTD', $type);
    }

    return $ret;
}

/**
 * Register URL Rewrites
 *
 * Defines rules for URL Rewrites
 *
 * @param array of strings $titles -- Lookup table for variations on the $type
 */
function pt_register_url_rewrites_for_post_type( $titles ) {
    global $wp_rewrite;
    $new_rules = array();
    foreach ( $wp_rewrite->extra_rules_top as $key => $rule ) {
        if (strpos($key, $titles['slug'] . '/%post_id%/') === 0 ) {
            $new_rules[ str_replace('%post_id%/', '', $key) ] = $rule;
            unset( $wp_rewrite->extra_rules_top[$key] );
        }
    }
    $wp_rewrite->extra_rules_top = $wp_rewrite->extra_rules_top + $new_rules;
}

/*
 * Register Taxonomy Template
 *
 * Assigns default values to the desired Post Type variables
 *
 * @return array of strings -- values assigned to variables for Titles
 */
function pt_register_taxonomy_template() {

    return array(
        'labels' => array(
            'name' => _x('Categories', 'taxonomy general name'),
            'singular_name' => _x('Category', 'taxonomy singular name'),
            'search_items' => __('Search Categories'),
            'all_items' => __('All Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Categories')
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true
    );
}

/**
 * Register Taxonomy Arguments for Titles
 *
 * Establish default values for the $titles array and
 * add the 'rewrite' element to it.
 *
 * @param array of strings $titles -- Lookup table for variations on the $type
 * @return array of strings -- values assigned to variables for Titles
 */
function pt_register_taxonomy_arguments( $titles ) {
    $ret = pt_register_taxonomy_template();
    $ret['rewrite'] = array('slug' => $titles['slug'] . '/category');
    return $ret;
}

/**
 * Custom K2 URL Formatting
 *
 * @param string $post_link
 * @param int $post
 * @param bool $leavename
 * @return mixed
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/post_type_link
 */
function custom_post_type_link($post_link, $post = 0, $leavename = false) {
    $k2_post_types = list_of_custom_post_types();

    if (in_array($post->post_type, $k2_post_types)) {
        return str_replace('%post_id%', $post->ID, $post_link);
    } else {
        return $post_link;
    }
}
add_filter('post_type_link', 'custom_post_type_link', 10, 3); // Adding the post ID to the custom post type URLs

/**
 * Add a Custom End Point to All
 */
function custom_end_point() {
    add_rewrite_endpoint('all', EP_ALL);
}
add_action('init', 'custom_end_point'); // Custom end point

/**
 * @param $qv
 * @return mixed
 */
function k2_add_post_types_rss($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type'])) {
        $qv['post_type'] = list_of_custom_post_types();
        $qv['post_type'][] = 'post';
    }
    return $qv;
}
add_filter('request', 'k2_add_post_types_rss');

/*
 * Remove the Version Number
 */
function k2_remove_ver_number() {
    return '';
}
add_filter('the_generator', 'k2_remove_ver_number'); // Removing the version number

//-------------------------------------------------------------------------------
/**
 * Orphan Navigation Building function
 *
 * Unsure why this exists. Legacy?
 *
 * @deprecated
 */
function k2_nav() {
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul>%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}
//-------------------------------------------------------------------------------
