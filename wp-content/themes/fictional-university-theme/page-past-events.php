<?php 

get_header(); 

pageBanner(array(
	'title'		=> 'Past Events',
	'subtitle'	=> 'A recap of our past events.'
));

?>

<div class="container container--narrow page-section">


	<?php 
		$today = date('Ymd');
		$pastEvents = new WP_Query(array(
			'paged'			 => get_query_var('paged', 1), // get url of paged results, if none use 1
			'post_type' 	 => 'event',
			'meta_key'		 => 'event_date', // custom field data
			'orderby'		 => 'meta_value_num', // gets the number of the meta value
			'order'			 => 'ASC',
			'meta_query'	 => array( // ordering by past event date 
				array(
					'key' => 'event_date',
					'compare' => '<',
					'value' => $today,
					'type' => 'numeric'
				)
			)
		));

		while ($pastEvents->have_posts()) { 
			$pastEvents->the_post(); 	
			get_template_part('template-parts/content-event');
		}	
		echo paginate_links(array(
			'total' => $pastEvents->max_num_pages
		));
	?>
	
	

</div>


<?php get_footer(); ?>