<?php 

// Powers Search Overlay information

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
	// a - name space that you want to use (rest api url), v1 is just the version | b - is the route (ending part of the url) | c - array describes what should happen when someone visits url
	// register_rest_route(a, b, c);
	register_rest_route('university/v1', 'search', array(
		'methods'	=> WP_REST_SERVER::READABLE,  // safe way of doing things instead of using GET
		'callback'	=> 'universitySearchResults'// Json data that will display
	));
}

function universitySearchResults($data) {
	$professors =  new WP_Query(array( // new instance of query class that takes an array of what your looking for
		'post_type' => 'professor',
		's'			=> sanitize_text_field($data['term']) // array that wp puts together, let's sanitize it though
	)); 

	$professorResults = array();

	while ($professors->have_posts()) { //however many posts live in collection is how many times the loop will run
		$professors->the_post(); // gets data ready and accessible 
		array_push($professorResults, array(
			'title' 	=> get_the_title(),
			'permalink'	=>  get_the_permalink()
		));
	}

	return $professorResults;
}




?>