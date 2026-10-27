<?php
/* Template Name: Legal Page */
get_header();
?>

<section class="zrt-hero py-5">
	<div class="container">
		<span class="zrt-badge">Legal</span>
		<h1 class="font-retro"><?php the_title(); ?></h1>
	</div>
</section>

<section class="zrt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<div class="entry-content fs-6" style="color: rgba(255,248,236,0.85); line-height:1.8;">
					<?php
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
