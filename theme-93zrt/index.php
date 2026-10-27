<?php get_header(); ?>

<div class="container zrt-section">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'zrt-card mb-4' ); ?>>
						<h2 class="font-retro" style="color:var(--zrt-yellow);">
							<a href="<?php the_permalink(); ?>" class="text-decoration-none" style="color:inherit;"><?php the_title(); ?></a>
						</h2>
						<div class="entry-content">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; ?>
				<div class="d-flex justify-content-between">
					<?php previous_posts_link( '&laquo; Newer' ); ?>
					<?php next_posts_link( 'Older &raquo;' ); ?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'Nothing found.', 'ninetythreezrt' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
