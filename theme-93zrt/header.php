<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
	<div class="py-2 small d-none d-md-block" style="background:var(--zrt-purple-dark); border-bottom:1px solid rgba(255,255,255,0.08);">
		<div class="container d-flex justify-content-between align-items-center">
			<div>
				<span class="fw-bold" style="color:var(--zrt-yellow);">93.7 FM</span>
				<span class="mx-2" style="color: rgba(255,248,236,0.4);">|</span>
				<a href="tel:+17604281103" class="text-decoration-none" style="color: rgba(255,248,236,0.8);">Request Line: (760) 428-1103</a>
				<span class="mx-2" style="color: rgba(255,248,236,0.4);">|</span>
				<a href="tel:+17604281101" class="text-decoration-none" style="color: rgba(255,248,236,0.8);">Business: (760) 428-1101</a>
			</div>
			<div>
				<a href="https://www.instagram.com/93zrt" class="text-decoration-none me-3" style="color: rgba(255,248,236,0.8);" aria-label="Instagram">IG</a>
				<a href="https://www.facebook.com/profile.php?id=61588545631350" class="text-decoration-none" style="color: rgba(255,248,236,0.8);" aria-label="Facebook">FB</a>
			</div>
		</div>
	</div>
	<nav class="navbar navbar-expand-lg navbar-93zrt sticky-top py-3">
		<div class="container">
			<a class="navbar-brand font-retro" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					93<span style="color:var(--zrt-pink);">ZRT</span>
				<?php endif; ?>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#zrtNav" aria-controls="zrtNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="zrtNav">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-3',
						'fallback_cb'    => 'ninetythreezrt_default_menu',
					)
				);
				?>
			</div>
		</div>
	</nav>
</header>

<?php
function ninetythreezrt_default_menu() {
	?>
	<ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-3">
		<li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
		<li class="nav-item"><a class="nav-link" href="#schedule">Schedule</a></li>
		<li class="nav-item"><a class="nav-link" href="#about">About</a></li>
		<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
	</ul>
	<?php
}
?>
