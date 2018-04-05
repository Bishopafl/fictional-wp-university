<?php 

// file is for individual campuses

get_header(); 

while(have_posts()) {
	// WP function keeps track of post working with
	the_post(); 

	pageBanner();

	?>

  	<div class="container container--narrow page-section">
		<div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> 
					All Campuses
				</a> 
				<span class="metabox__main"><?php the_title(); ?></span>
			</p>
	    </div>

		<div class="generic-content">
			<?php the_content(); ?>
		</div>

		<?php $mapLocation = get_field('map_location'); ?>

		<div class="acf-map">
			<div data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>" class="marker">
                <h3><?php the_title(); ?></h3>
                <?php echo $mapLocation['address'] ?>
			</div>
		</div>        

		<?php 
			$relatedPrograms = new WP_Query(array(
	    		'posts_per_page' => -1,
	    		'post_type' 	 => 'program',
	    		'orderby'		 => 'title', // orders professors by name
	    		'order'			 => 'ASC',
	    		'meta_query'	 => array( // ordering by event date showing days in order and not in the past
	    			array(
	    				'key' => 'related_campus',
	    				'compare' => 'LIKE',
	    				'value' => '"' . get_the_ID() . '"' // Put the ID of the program that matches the campus custom field inbetween " " 
	    			)
	    		)
	    	));

	    	if ($relatedPrograms->have_posts()) {
	    		echo '<hr class="section-break">';
		    	echo '<h2 class="headline headline--medium">Programs Avaliable At This Campus</h2>';


		    	echo '<ul class="min-list link-list">';
		    	while ($relatedPrograms->have_posts()) {
		    		// gets data for information in loop
		    		$relatedPrograms->the_post(); ?>
		    		<li>
		    			<a href="<?php the_permalink(); ?>">
		    				<?php the_title(); ?>
		    			</a>
		    		</li>
		    <?php	} // end while loop
		    echo '</ul>';
	    	}

	    	// Needs to be called if you use multiple custom queries on the same page in order to clear the data
	    	wp_reset_postdata();


	    	 ?> 
  	</div>
	
	<?php }

get_footer();

?>