<?php 

/*

search page

get_the_title() function takes an ID number and gives title of that post instead of the one looped through
the_title() function outputs the title of current post or page

*/


get_header();


while(have_posts()) {
	// WP function keeps track of post working with
	the_post(); 

	pageBanner();

	?>
	

  <div class="container container--narrow page-section">

	<?php 
	$theParent = wp_get_post_parent_id(get_the_ID());

	if ($theParent) { ?>
	    <div class="metabox metabox--position-up metabox--with-home-link">
			<p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php  the_title(); ?></span></p>
	    </div>
	<?php } 
	?>


	<?php 
	$testArray = get_pages(array(
		'child_of' => get_the_ID()
	));

	// ONLY DISPLAY MENU IF YOU ARE ON A CHILD PAGE
	if ($theParent or $testArray): ?>
	    <div class="page-links">
	      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
	      <ul class="min-list">
	        <?php 
	        	if ($theParent) {
	        		$findChildrenOf = $theParent;
	        	} else {
	        		$findChildrenOf = get_the_ID();
	        	}


	        	wp_list_pages(array(
	        		'title_li' => NULL,
	        		'child_of' => $findChildrenOf,
	        		'sort_column' => 'menu_order' //sorts order of page pulling based on order in page admin section
	        	));
	        ?>
	      </ul>
	    </div>

	<?php endif ?>

    <div class="generic-content">
      <form method="get" action="<?php echo esc_url(site_url('/')); ?>">
      	<input type="search" name="s">
      	<input type="submit" value="Search">
      </form>
    </div>

  </div>
	
	<?php }

get_footer();

?>