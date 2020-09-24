// Enqueue script and style sheet

add_action( 'wp_enqueue_scripts', 'wp_enqueue_scripts_style' );
function wp_enqueue_scripts_style() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/', array(), CHILD_THEME_VERSION );
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'),'1.0',true);

}
/**
 * Our custom post type function for Our Team
*/
function create_posttype_team() {

	register_post_type( 'team',
					   array(
						   'labels' => array(
							   'name' => __( 'Our Team' ),
							   'singular_name' => __( 'Our Team' )
						   ),
						   'public' => true,
						   'has_archive' => false,
						   'rewrite' => array('slug' => 'team'),
						   'show_in_rest' => true,
						   'supports'=> array( 'title','editor', 'thumbnail' ),
					   )
					  );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype_team' );
/**
 * Add custom taxonomy for team
 */
register_taxonomy(
	'team-member',
	'team',
	array(
		'labels' => array(
			'name' => 'Categories',
			'add_new_item' => 'Add New Category',
			'new_item_name' => "New Category"
		),
		'show_ui' => true,
		'show_tagcloud' => true,
		'hierarchical' => true,
		'show_in_rest' => true
	)
);


/**
 * Setup query to show the ‘services’ 
 */

function smile_shortcode_cpt($attrs) {
	$attrs = shortcode_atts( array(
		'id' => '8'
	), $attrs );

	$args = array(  
		'post_type' => 'smile',
		'posts_per_page' => -1, 
		'tax_query' => array(
			array(
				'taxonomy' => 'smile-galleries',
				'field' => 'term_id',
				'terms' => $attrs['id'],
			)
		)
	);
	$term = get_term($attrs['id']); //Example term ID
	//print_r ($term);
	$term_link = get_term_link( $term );
	$loop = new WP_Query( $args ); 
	$dotml = '';
	$dotml .= '<div class="smile-galleries-section-single"><h3 class="wrap">'.$term->name.'</h3><div class="wrap"><a href="'.$term_link.'" class="wp-block-columnscusstom">';
	if( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post(); 
		$dotml .= '<div  class="home-serv">'. get_the_post_thumbnail() .'</div>';
		endwhile;

		wp_reset_postdata(); 
	}
	$dotml .= '</a></div><div class="wp-block-button simple-text-btn wrap"><a class="wp-block-button__link" href="'.$term_link.'">see all '.$term->name.' Before &amp; Afters</a></div></div>';
	return $dotml;
}

add_shortcode( 'smile_list', 'smile_shortcode_cpt' );

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
