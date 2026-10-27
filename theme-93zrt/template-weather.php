<?php
/* Template Name: Weather */
get_header();

$weather = function_exists( 'ninetythreezrt_get_weather' ) ? ninetythreezrt_get_weather() : false;
?>

<section class="zrt-hero py-5">
	<div class="container">
		<span class="zrt-badge">Local Forecast</span>
		<h1 class="font-retro">Weather</h1>
		<p class="lead">Keep an eye on conditions before you tune in for your drive.</p>
	</div>
</section>

<section class="zrt-section">
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="zrt-card text-center">
					<?php if ( $weather ) : ?>
						<p class="small text-uppercase mb-2" style="color: var(--zrt-teal); letter-spacing:1px;">
							<?php echo esc_html( $weather['location'] ); ?>
						</p>
						<div style="font-size:3.5rem;"><?php echo $weather['icon']; ?></div>
						<div class="display-4 font-retro" style="color:var(--zrt-yellow);"><?php echo esc_html( $weather['temp'] ); ?>&deg;F</div>
						<p class="mb-1"><?php echo esc_html( $weather['description'] ); ?></p>
						<p class="small mb-3" style="color: rgba(255,248,236,0.6);">
							<?php if ( null !== $weather['high'] && null !== $weather['low'] ) : ?>
								H: <?php echo esc_html( $weather['high'] ); ?>&deg; &nbsp;/&nbsp; L: <?php echo esc_html( $weather['low'] ); ?>&deg;
							<?php endif; ?>
						</p>
						<p class="small mb-0" style="color: rgba(255,248,236,0.5);">
							Humidity <?php echo esc_html( $weather['humidity'] ); ?>% &middot; Wind <?php echo esc_html( $weather['wind'] ); ?> mph
						</p>
						<p class="small mt-3 mb-0" style="color: rgba(255,248,236,0.4);">
							Updated <?php echo esc_html( $weather['updated'] ); ?> &middot; data via Open-Meteo
						</p>
					<?php else : ?>
						<div style="font-size:3.5rem;">&#127749;&#65039;</div>
						<p class="mb-1">Weather data is temporarily unavailable.</p>
						<p class="small mb-0" style="color: rgba(255,248,236,0.5);">Please check back in a bit.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if ( $weather && ! empty( $weather['forecast'] ) ) : ?>
			<h2 class="font-retro zrt-section-title text-center mt-5 mb-4">7-Day Forecast</h2>
			<div class="row g-3 justify-content-center">
				<?php foreach ( $weather['forecast'] as $day ) : ?>
					<div class="col-6 col-sm-4 col-md-3 col-lg">
						<div class="zrt-card text-center h-100" style="padding:1.25rem 0.75rem;">
							<div class="fw-bold text-uppercase small mb-1"><?php echo esc_html( $day['label'] ); ?></div>
							<div class="small mb-2" style="color: rgba(255,248,236,0.5);"><?php echo esc_html( $day['date'] ); ?></div>
							<div style="font-size:2rem;"><?php echo $day['icon']; ?></div>
							<div class="small mb-2" style="color: rgba(255,248,236,0.6);"><?php echo esc_html( $day['desc'] ); ?></div>
							<div class="fw-bold">
								<span style="color:var(--zrt-yellow);"><?php echo esc_html( $day['high'] ); ?>&deg;</span>
								<span style="color: rgba(255,248,236,0.4);"> / </span>
								<span style="color: rgba(255,248,236,0.6);"><?php echo esc_html( $day['low'] ); ?>&deg;</span>
							</div>
							<?php if ( null !== $day['pop'] && $day['pop'] > 0 ) : ?>
								<div class="small mt-1" style="color: var(--zrt-teal);">
									&#128167; <?php echo esc_html( $day['pop'] ); ?>%
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
