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
	// checking if user is logged in or not
	if (is_user_logged_in()) {
		// gets professor data attribute from javascript to insert into new like post type
		$professor = sanitize_text_field($data['professorId']);

		$existQuery = new WP_Query(array(
			'author'		=> get_current_user_id(),
			'post_type'		=> 'like',
			'meta_query' 	=> array(
				array(
					'key'		=> 'liked_professor_id', // custom fields 
					'compare'	=> '=', // exact match
					'value'		=> $professor
				)
			)
		));

		// if current user hasn't liked the selected professor, else return error message
		if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {
			// create new like post
			return wp_insert_post(array(
				'post_type' 	=> 'like',
				'post_status'	=> 'publish', // wp considers a finalized post
				'post_title'	=> 'Second PHP Test',
				'meta_input'	=> array(
					'liked_professor_id'		=> $professor
					
				)
			)); // Let us programmatically create a new post within the php code
		} else {
			die("Invalid professor id");
		}

	} else {
		die("Only logged in users can create a like.");
	}
}

function deleteLike() {
	return 'Thanks for trying to delete a like.';
}