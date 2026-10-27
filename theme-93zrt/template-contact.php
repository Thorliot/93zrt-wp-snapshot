<?php
/* Template Name: Contact */
get_header();
?>

<section class="zrt-hero py-5">
	<div class="container">
		<span class="zrt-badge">Get In Touch</span>
		<h1 class="font-retro">Contact 93ZRT</h1>
		<p class="lead">Questions, comments, or requests &mdash; we'd love to hear from you.</p>
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
