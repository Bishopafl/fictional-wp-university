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
function createLike($data) {
	$professor = sanitize_text_field($data['professorId']);


	wp_insert_post(array(
		'post_type' 	=> 'like',
		'post_status'	=> 'publish', // wp considers a finalized post
		'post_title'	=> 'Second PHP Test',
		'meta_input'	=> array(
			'liked_professor_id'		=> $professor
			
		)
	)); // Let us programmatically create a new post within the php code
}

function deleteLike() {
	return 'Thanks for trying to delete a like.';
}