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
	$mainQuery =  new WP_Query(array( // new instance of query class that takes an array of what your looking for
		'post_type' => array('post','page','professor', 'program', 'campus', 'event'), // in array of post types, you can query multiple post types
		's'			=> sanitize_text_field($data['term']) // array that wp puts together, let's sanitize it though
	)); 

	$results = array(	// results is an associative array that contains empty arrays to push data into from our mainQuery while loop
		'generalInfo'	=> array(),
		'professors'	=> array(),
		'programs'		=> array(),
		'events'		=> array(),
		'campuses'		=> array()
	);

	while ($mainQuery->have_posts()) { //however many posts live in collection is how many times the loop will run
		$mainQuery->the_post(); // gets data ready and accessible 

		// conditional checks to push array information to correct nested arrays in the results variable
		if (get_post_type() == 'post' || get_post_type() == 'page') {
			array_push($results['generalInfo'], array(
				'title' 		=> get_the_title(),
				'permalink'		=>  get_the_permalink(),
				'postType'		=> get_post_type(),
				'authorName'	=> get_the_author()
			));	
		}
		// conditional check if post type is professor
		if (get_post_type() == 'professor') {
			array_push($results['professors'], array(
				'title' 	=> get_the_title(),
				'permalink'	=> get_the_permalink(),
				'image'		=> get_the_post_thumbnail_url(0, 'professorLandscape')
			));	
		}

		if (get_post_type() == 'program') {
			array_push($results['programs'], array(
				'id'		=> get_the_id(),
				'title' 	=> get_the_title(),
				'permalink'	=>  get_the_permalink()
			));	
		}

		if (get_post_type() == 'campus') {
			array_push($results['campuses'], array(
				'title' 	=> get_the_title(),
				'permalink'	=>  get_the_permalink()
			));	
		}

		if (get_post_type() == 'event') {
			$eventDate = new DateTime(get_field('event_date'));
			$description = NULL;
			
			if (has_excerpt()) { 
				$description = get_the_excerpt();	
			} else {
				$description = wp_trim_words(get_the_content(), 18);
			}

			array_push($results['events'], array(
				'title' 		=> get_the_title(),
				'permalink'		=>  get_the_permalink(),
				'month'			=> $eventDate->format('M'),
				'day'			=> $eventDate->format('d'),
				'description'	=> $description
			));	
		}

	}

	// code will only execute if programs relate to search
	if ($results['programs']) {
		$programsMetaQuery = array('relation' => 'OR');

		foreach ($results['programs'] as $program) {
			array_push($programsMetaQuery, array(
				'key' => 'related_program', // advanced custom field name
				'compare' => 'LIKE',
				'value' => '"' . $program['id'] . '"'
			));
		}

		$programRelationshipQuery = new WP_Query(array(
			'post_type'		=> 'professor',
			'meta_query'	=> $programsMetaQuery
		));

		while ($programRelationshipQuery->have_posts()) {
			$programRelationshipQuery->the_post();

			// conditional check if post type is professor based on the relationship query on line 86
			if (get_post_type() == 'professor') {
				array_push($results['professors'], array(
					'title' 	=> get_the_title(),
					'permalink'	=> get_the_permalink(),
					'image'		=> get_the_post_thumbnail_url(0, 'professorLandscape')
				));	
			}
		}

		// function that removes duplicates that could potentially get duplicate results 
		$results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));

	}

	return $results;
}




?>