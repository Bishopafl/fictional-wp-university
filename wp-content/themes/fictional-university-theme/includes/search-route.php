<?php 

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
	// a - name space that you want to use (rest api url), v1 is just the version | b - is the route (ending part of the url) | c - array describes what should happen when someone visits url
	// register_rest_route(a, b, c);
	register_rest_route('university/v1', 'search', array(
		'methods'	=> WP_REST_SERVER::READABLE,  // safe way of doing things instead of using GET
		'callback'	=> 'universitySearchResults'// Json data that will display
	));
}

function universitySearchResults() {
	return 'Congratulations, you created a route.';
}




?>