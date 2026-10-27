<?php
/* Template Name: About / On-Air Staff */
get_header();
?>

<section class="zrt-hero py-5">
	<div class="container">
		<span class="zrt-badge">On-Air &amp; Staff</span>
		<h1 class="font-retro">Meet the Team</h1>
		<p class="lead">The voices behind the retro hits, on the air every day of the week.</p>
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
