// Enqueue script and style sheet

add_action( 'wp_enqueue_scripts', 'wp_enqueue_scripts_style' );
function wp_enqueue_scripts_style() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/', array(), CHILD_THEME_VERSION );
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'),'1.0',true);

}

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Movies', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Movie', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Movies', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Movie', 'twentytwenty' ),
        'all_items'           => __( 'All Movies', 'twentytwenty' ),
        'view_item'           => __( 'View Movie', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Movie', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Movie', 'twentytwenty' ),
        'update_item'         => __( 'Update Movie', 'twentytwenty' ),
        'search_items'        => __( 'Search Movie', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'movies', 'twentytwenty' ),
        'description'         => __( 'Movie news and reviews', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'movies', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );


// Our function for getting data of post
function post_shortcode_init() {
	$c_s_args = array(
		'posts_per_page' => 4,
		'post_type'      => 'post',
	);
	$c_s_query = new WP_Query( $c_s_args );
	global $post;   

	$c_s_html = '';
	$c_s_html .= '<div class="blogs-list">';
	if( $c_s_query->have_posts() ) {
		while( $c_s_query->have_posts() ) {
			$c_s_query->the_post();
			$c_s_html .= '<div class="single-blogs">';
			$c_s_html .= get_the_post_thumbnail($post->ID); 
			$c_s_html .= '<a href="'.get_the_permalink().'"><h3 class="blogs-head">'.get_the_title().'</h3></a>';
			$c_s_html .= '</div>';
		}
		wp_reset_postdata();
	}
	$c_s_html .= '</div>';
	return $c_s_html;
}
// Add shortcode for getting data of posts
add_shortcode('post_shortcode', 'post_shortcode_init');

/*
* Creating a function to create our widgets
*/

function cust_widgets(){
	register_sidebar(array(
		'name'          => 'Mobile Menu',
		'id'            => 'before-header',
		'description'   => 'The content of this widget will appear as Mobile Menu.',
	));
}
add_action('widgets_init', 'cust_widgets');

function mobile_menu() {
	echo '<div class="header-menu">';
	dynamic_sidebar('before-header');
	echo '</div>';
}
