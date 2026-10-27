<?php get_header(); ?>

<div class="container zrt-section">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1 class="font-retro zrt-section-title"><?php the_title(); ?></h1>
				<div class="entry-content fs-5">
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
