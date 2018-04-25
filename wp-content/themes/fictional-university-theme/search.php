<?php 

get_header(); 

pageBanner(array(
	'title'		=> 'Search Results',
	'subtitle'	=> 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;' // get_search_query function adds escape code esc_html converts or escapes text
));

?>


<div class="container container--narrow page-section">


	<?php 

	if (have_posts()) {
		while (have_posts()) { 
			the_post(); 
			get_template_part('template-parts/content', get_post_type()); // go into the template parts folder and look for a file named content

		}	
		echo paginate_links();
	
	} else {
		echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';		
	}
	
	get_search_form();

	?>

</div>


<?php get_footer(); ?>