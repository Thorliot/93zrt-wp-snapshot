<?php
/* Template Name: Full Schedule */
get_header();
?>

<section class="zrt-hero py-5">
	<div class="container">
		<span class="zrt-badge">Radio Schedule</span>
		<h1 class="font-retro">On the Air</h1>
		<p class="lead">Our full weekday and weekend lineup &mdash; retro hits around the clock.</p>
	</div>
</section>

<section class="zrt-section">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</section>

<?php get_footer(); ?>
