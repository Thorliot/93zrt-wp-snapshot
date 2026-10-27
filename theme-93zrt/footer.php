	<footer class="zrt-footer">
		<div class="container">
			<div class="row gy-4">
				<div class="col-md-4">
					<h5 class="font-retro" style="color:var(--zrt-yellow);">93<span style="color:var(--zrt-pink);">ZRT</span></h5>
					<p class="small mb-3">Your home for the greatest retro hits, streaming around the globe, 24/7.</p>
					<div class="zrt-social">
						<a href="https://www.facebook.com/profile.php?id=61588545631350" aria-label="Facebook"><i class="bi bi-facebook">FB</i></a>
						<a href="https://www.instagram.com/93zrt" aria-label="Instagram"><i class="bi bi-instagram">IG</i></a>
					</div>
				</div>
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<div class="col-md-4">
						<h5 class="text-uppercase mb-3" style="color:var(--zrt-yellow);">Listen</h5>
						<ul class="list-unstyled small">
							<li class="mb-2"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-decoration-none" style="color:var(--zrt-cream);">Live Stream</a></li>
							<li class="mb-2"><a href="<?php echo esc_url( home_url( '/schedule/' ) ); ?>" class="text-decoration-none" style="color:var(--zrt-cream);">Show Schedule</a></li>
							<li class="mb-2"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="text-decoration-none" style="color:var(--zrt-cream);">Request a Song</a></li>
						</ul>
					</div>
					<div class="col-md-4">
						<h5 class="text-uppercase mb-3" style="color:var(--zrt-yellow);">Contact</h5>
						<p class="small mb-0">Questions, comments, or requests?<br>
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="text-decoration-none" style="color:var(--zrt-teal);">Get in touch &rarr;</a></p>
					</div>
				<?php endif; ?>
			</div>
			<hr class="my-4" style="border-color: rgba(255,255,255,0.15);">
			<div class="d-flex flex-wrap justify-content-center gap-3 small mb-3">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Privacy Policy</a>
				<a href="<?php echo esc_url( home_url( '/accessibility-statement/' ) ); ?>" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Accessibility Statement</a>
				<a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Terms &amp; Conditions</a>
				<a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Refund Policy</a>
				<a href="https://publicfiles.fcc.gov/FM-profile/KZRT" target="_blank" rel="noopener" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Public File</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="text-decoration-none" style="color: rgba(255,248,236,0.7);">Contact Us</a>
			</div>
			<p class="small text-center mb-0" style="color: rgba(255,248,236,0.6);">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> 93ZRT. All rights reserved.
			</p>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
