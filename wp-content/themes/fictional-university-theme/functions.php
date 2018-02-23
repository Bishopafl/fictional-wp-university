<?php 
// private behind the scenes files that talks to wp itself

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
// reusable function for page banner images and text
function pageBanner($args = NULL) {
	// if no title, use WP title for page
	if (!$args['title']) {
		$args['title'] = get_the_title();
	}
	// if no subtitle, use WP subtitle 
	if (!$args['subtitle']) {
		$args['subtitle'] = get_field('page_banner_subtitle');
	}
	// if no photo, use default
	if (!$args['photo']) {
		if (get_field('page_banner_background_image')) {
			$args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
		} else {
			$args['photo'] = get_theme_file_uri('/images/ocean.jpg');
		}
	}

	?>
	<div class="page-banner">
    	<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
		<div class="page-banner__content container container--narrow">
  			<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
  			<div class="page-banner__intro">
    			<p><?php echo $args['subtitle']; ?></p>
  			</div>
		</div>  
  	</div>
<?php }


/*******************************************/
/*		UNIVERSITY IMAGE ACTIONS		   */
/*******************************************/

function university_features() {
	// code for registering new nav menus, commented just to have as reference 
	// register_nav_menu('headerMenuLocation','Header Menu Location');
	// register_nav_menu('footerLocationOne','Footer Location One');
	// register_nav_menu('footerLocationTwo','Footer Location Two');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true); 
	add_image_size('professorPortrait', 480, 650, true);
	add_image_size('pageBanner', 1500, 350, true);
}

// dynamically sets up title for webpages
add_action('after_setup_theme','university_features');

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/

/*******************************************/
/*		UNIVERSITY JS & CSS FILES   	   */
/*******************************************/

function university_files() {
	// WP function looks for two arguments
	// 1st doesn't matter
	// 2nd is the file root 

	//javascript takes 3 arguements 
	wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBTA4puW3NgVBObm6hpKembiBNSp3KTxjs', NULL, '1.0', true);
	wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);

	// styles
	wp_enqueue_style('custom-google-fonts','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
}
// useful function takes 2 arguments
// 1st arguement tells WP what kind of instructions to give it ( runs at different times )
// 2nd argument gives WP name of function we want to run
add_action('wp_enqueue_scripts','university_files');

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/

/*******************************************/
/*		ADJUST DEFAULT QUERY ACTIONS	   */
/*******************************************/

// manipulates queries from default WP
function university_adjust_queries($query) {
	if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
		$query->set('orderby', 'title'); // orders alphabetically
		$query->set('order', 'ASC');
		$query->set('post_per_page', -1);
	}


	// if not admin and post type archive is event and query is a main query, not a custom query
	if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
		$today = date('Ymd');

		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'ASC');
		$query->set('meta_query', array(
			array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
				'type' => 'numeric'
			)
		));
	}
}

// before we get the posts, do this function
add_action('pre_get_posts', 'university_adjust_queries');

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/

/*******************************************/
/*			UNIVERSITY MAP ACTIONS		   */
/*******************************************/


function universityMapKey($api) {
	$api['key'] = 'AIzaSyBTA4puW3NgVBObm6hpKembiBNSp3KTxjs';
	return $api;
}


add_filter('acf/fields/google_map/api', 'universityMapKey');

/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/


?>