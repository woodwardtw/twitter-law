<?php
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
	// Get the theme data
	$the_theme = wp_get_theme();

    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    wp_enqueue_script( 'custom-wtf-scripts' , get_stylesheet_directory_uri() . '/js/custom-wtf.js', array(), '1', true);
    wp_localize_script('custom-wtf-scripts', 'WPURLS', array( 'siteurl' => get_option('siteurl') )); //gets you the home url as a js variable

}


//create title for tweet that does 'claime by' & current user
function claimTitle(){
	$current_user = wp_get_current_user();
	$name = $current_user->user_login;
	return 'claimed by ' . $name;
}

// Register Custom Post Type Tweet
// Post Type Key: tweet
function create_tweet_cpt() {

	$labels = array(
		'name' => __( 'tweets', 'Post Type General Name', 'textdomain' ),
		'singular_name' => __( 'tweet', 'Post Type Singular Name', 'textdomain' ),
		'menu_name' => __( 'tweets', 'textdomain' ),
		'name_admin_bar' => __( 'project', 'textdomain' ),
		'archives' => __( 'tweet Archives', 'textdomain' ),
		'attributes' => __( 'tweet Attributes', 'textdomain' ),
		'parent_item_colon' => __( 'Parent tweet:', 'textdomain' ),
		'all_items' => __( 'All tweets', 'textdomain' ),
		'add_new_item' => __( 'Add New tweet', 'textdomain' ),
		'add_new' => __( 'Add New', 'textdomain' ),
		'new_item' => __( 'New tweet', 'textdomain' ),
		'edit_item' => __( 'Edit tweet', 'textdomain' ),
		'update_item' => __( 'Update tweet', 'textdomain' ),
		'view_item' => __( 'View tweet', 'textdomain' ),
		'view_items' => __( 'View tweets', 'textdomain' ),
		'search_items' => __( 'Search tweets', 'textdomain' ),
		'not_found' => __( 'Not found', 'textdomain' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
		'featured_image' => __( 'Featured Image', 'textdomain' ),
		'set_featured_image' => __( 'Set featured image', 'textdomain' ),
		'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
		'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
		'insert_into_item' => __( 'Insert into tweet', 'textdomain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this tweet', 'textdomain' ),
		'items_list' => __( 'tweets list', 'textdomain' ),
		'items_list_navigation' => __( 'tweet list navigation', 'textdomain' ),
		'filter_items_list' => __( 'Filter tweets list', 'textdomain' ),
	);
	$args = array(
		'label' => __( 'tweet', 'textdomain' ),
		'description' => __( 'legal Twitter tweets', 'textdomain' ),
		'labels' => $labels,
		'menu_icon' => '',
		'supports' => array('title', 'editor', 'thumbnail', 'comments', 'revisions', 'author', 'trackbacks', 'page-attributes', 'custom-fields', ),
        'taxonomies' => array('category'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
		'menu_icon' => 'dashicons-hammer',
	);
	register_post_type( 'tweet', $args );

}
add_action( 'init', 'create_tweet_cpt', 0 );


//add custom class to category list
add_filter('the_category','add_class_to_category',10,3);

function add_class_to_category( $thelist){
    $class_to_add = 'wtf-cat-class';
    return str_replace('<a href="', '<a class="' . $class_to_add . '" href="', $thelist);
}

//add tweets custom post type to category archive results
function schoollawwtf_add_custom_types( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'nav_menu_item', 'tweet'
		));
	  return $query;
	}
}
add_filter( 'pre_get_posts', 'schoollawwtf_add_custom_types' );

//overlay builder for displaying twitter posts but linking them to wp posts

function tweetLink($id){
	$tweetHTML = wp_oembed_get(get_post_meta($id, 'tweet', true));
	return $tweetHTML;
}