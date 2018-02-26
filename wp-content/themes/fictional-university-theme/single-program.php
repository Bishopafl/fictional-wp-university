<?php 

get_header(); 

while(have_posts()) {
	// WP function keeps track of post working with
	the_post(); 

	pageBanner();

	?>

  	<div class="container container--narrow page-section">
		<div class="metabox metabox--position-up metabox--with-home-link">
			<p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
	    </div>

		<div class="generic-content">
			<?php the_content(); ?>
		</div>

		<?php 
			$relatedProfessors = new WP_Query(array(
	    		'posts_per_page' => -1,
	    		'post_type' 	 => 'professor',
	    		'orderby'		 => 'title', // orders professors by name
	    		'order'			 => 'ASC',
	    		'meta_query'	 => array( // ordering by event date showing days in order and not in the past
	    			array(
	    				'key' => 'related_program',
	    				'compare' => 'LIKE',
	    				'value' => '"' . get_the_ID() . '"'
	    			)
	    		)
	    	));

	    	if ($relatedProfessors->have_posts()) {
	    		echo '<hr class="section-break">';
		    	echo '<h2 class="headline headline--medium">Upcoming '. get_the_title().' Events</h2>';


		    	echo '<ul class="professor-cards">';
		    	while ($relatedProfessors->have_posts()) {
		    		// gets data for information in loop
		    		$relatedProfessors->the_post(); ?>
		    		<li class="professor-card__list-item">
		    			<a class="professor-card" href="<?php the_permalink(); ?>">
		    				<img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
		    				<span class="professor-card__name"><?php the_title(); ?></span>
		    			</a>
		    		</li>
		    <?php	} // end while loop
		    echo '</ul>';
	    	}

	    	// Needs to be called if you use multiple custom queries on the same page in order to clear the data
	    	wp_reset_postdata();


	    	$today = date('Ymd');
	    	$homepageEvents = new WP_Query(array(
	    		'posts_per_page' => 2,
	    		'post_type' 	 => 'event',
	    		'meta_key'		 => 'event_date', // custom field data
	    		'orderby'		 => 'meta_value_num', // gets the number of the meta value
	    		'order'			 => 'ASC',
	    		'meta_query'	 => array( // ordering by event date showing days in order and not in the past
	    			array(
	    				'key' => 'event_date',
	    				'compare' => '>=',
	    				'value' => $today,
	    				'type' => 'numeric'
	    			),
	    			array(
	    				'key' => 'related_program',
	    				'compare' => 'LIKE',
	    				'value' => '"' . get_the_ID() . '"'
	    			)
	    		)
	    	));

	    	if ($homepageEvents->have_posts()) {
	    		echo '<hr class="section-break">';
		    	echo '<h2 class="headline headline--medium"> ' . get_the_title() .' Professors</h2>';

		    	while ($homepageEvents->have_posts()) {
		    		// gets data for information in loop
		    		$homepageEvents->the_post(); 
		    		get_template_part('template-parts/content-event');
		    	} // end while loop
	    	}

	    	wp_reset_postdata(); // Clean slate so code doesn't get mixed up in WP...

	    	$relatedCampuses = get_field('related_campus');

	    	// only if related campus exists
	    	if ($relatedCampuses) {
	    		echo '<hr class="section-break">';
	    		echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Avaliable At These Campuses:</h2>';
	    		echo '<ul class="min-list link-list">';
	    		foreach ($relatedCampuses as $campus) {
	    			?> 
	    			<li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>

	    			<?php
	    		}
	    		echo '</ul>';
	    	}

	   	?> 
  	</div>
	
	<?php }

get_footer();

?>