<?php 

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes() {
	register_rest_route('university/v1', 'manageLike', array(
		'methods'		=> 'POST', // type of http request
		'callback'		=> 'createLike'
	));

	register_rest_route('university/v1', 'manageLike', array(
		'methods'		=> 'DELETE', // type of http request
		'callback'		=> 'deleteLike'
	));
}

// programmability create like and delete posts
function createLike() {
	return 'Thanks for trying to create a like.';
}

function deleteLike() {
	return 'Thanks for trying to delete a like.';
}