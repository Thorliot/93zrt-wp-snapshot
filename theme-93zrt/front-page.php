<?php get_header(); ?>

<!-- Hero -->
<section class="zrt-hero">
	<div class="container">
		<span class="zrt-badge">Streaming 24/7</span>
		<h1 class="font-retro">93<span style="color:var(--zrt-pink);">ZRT</span></h1>
		<p class="lead fs-4 mb-4">
			Welcome to 93ZRT where we take you on a fantastic journey through the greatest retro!
			We&rsquo;re passionate about celebrating our vibrant community and appreciate all the
			listeners tuning in around the globe. Thank you for joining us &mdash; we celebrate
			retro hits that defined generations!
		</p>
		<div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mb-5">
			<a href="#contact" class="btn btn-zrt-outline btn-lg">Questions? Comments? Requests?</a>
		</div>

		<div class="zrt-player-mock mx-auto" id="zrt-player">
			<audio id="zrt-audio" preload="none">
				<source src="https://streaming.live365.com/a50341" type="audio/mpeg">
			</audio>
			<div class="zrt-player-mock__art">
				<span class="zrt-player-mock__disc" id="zrt-disc">
					<svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
				</span>
				<img id="zrt-art" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
			</div>
			<div class="zrt-player-mock__body">
				<div class="zrt-player-mock__top">
					<span class="zrt-live-badge"><span class="now-playing-pulse"></span>LIVE</span>
					<span class="small" style="color: rgba(255,248,236,0.6);">93.7 FM</span>
				</div>
				<div class="fw-bold fs-5 mb-1" id="zrt-track-title">93ZRT Web Player</div>
				<div class="small mb-3" style="color: rgba(255,248,236,0.6);" id="zrt-status">Set the mood &mdash; tune in for today's mix</div>
				<div class="zrt-player-mock__controls">
					<button type="button" class="zrt-player-mock__play" id="zrt-play-btn" aria-label="Play">
						<svg viewBox="0 0 16 16" width="14" height="14" fill="currentColor"><path d="M4 2.5v11l9-5.5-9-5.5z"></path></svg>
					</button>
					<div class="zrt-player-mock__bar"><span id="zrt-bar-fill"></span></div>
					<button type="button" class="zrt-player-mock__vol" id="zrt-mute-btn" aria-label="Mute">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor" stroke="none"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path><path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path></svg>
					</button>
				</div>
			</div>
		</div>

		<div class="zrt-recently-played mx-auto" id="zrt-recently-played" style="display:none;">
			<div class="small text-uppercase mb-2" style="color: var(--zrt-teal); letter-spacing:1px;">Recently Played</div>
			<ul class="list-unstyled mb-0" id="zrt-recently-played-list"></ul>
		</div>

		<script>
		(function () {
			var audio  = document.getElementById('zrt-audio');
			var btn    = document.getElementById('zrt-play-btn');
			var muteBt = document.getElementById('zrt-mute-btn');
			var status = document.getElementById('zrt-status');
			var disc   = document.getElementById('zrt-disc');
			var art    = document.getElementById('zrt-art');
			var bar    = document.getElementById('zrt-bar-fill');
			var track  = document.getElementById('zrt-track-title');
			if (!audio || !btn) { return; }

			var ICON_PLAY   = '<svg viewBox="0 0 16 16" width="14" height="14" fill="currentColor"><path d="M4 2.5v11l9-5.5-9-5.5z"></path></svg>';
			var ICON_PAUSE  = '<svg viewBox="0 0 16 16" width="14" height="14" fill="currentColor"><rect x="3" y="2" width="4" height="12" rx="1"></rect><rect x="9" y="2" width="4" height="12" rx="1"></rect></svg>';
			var ICON_VOLUME = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor" stroke="none"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path><path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path></svg>';
			var ICON_MUTED  = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor" stroke="none"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>';

			// Generic icons for the store links (not brand logos) -- a note
			// for the streaming/music side, a bag for the storefront side.
			// Distinguished for screen readers and on hover via aria-label/title.
			var ICON_APPLE  = '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>';
			var ICON_AMAZON = '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 7h12l1 13H5L6 7z"></path><path d="M9 7a3 3 0 0 1 6 0"></path></svg>';

			function setStatus(text) { status.textContent = text; }

			var nowPlayingTimer = null;

			var recentWrap = document.getElementById('zrt-recently-played');
			var recentList = document.getElementById('zrt-recently-played-list');

			function trackLabel(t) {
				return t.artist ? (t.artist + ' — ' + t.title) : t.title;
			}

			function renderHistory(history) {
				if (!recentList || !history || !history.length) {
					if (recentWrap) { recentWrap.style.display = 'none'; }
					return;
				}
				recentList.innerHTML = '';
				history.forEach(function (t) {
					var li = document.createElement('li');
					li.className = 'zrt-recently-played-item';

					var label = document.createElement('span');
					label.textContent = trackLabel(t);
					li.appendChild(label);

					if (t.links && (t.links.apple_music || t.links.amazon)) {
						var linksWrap = document.createElement('span');
						linksWrap.className = 'zrt-track-links';

						if (t.links.apple_music) {
							var appleLink = document.createElement('a');
							appleLink.href = t.links.apple_music;
							appleLink.target = '_blank';
							appleLink.rel = 'noopener';
							appleLink.setAttribute('aria-label', 'Find on Apple Music');
							appleLink.title = 'Find on Apple Music';
							appleLink.innerHTML = ICON_APPLE;
							linksWrap.appendChild(appleLink);
						}

						if (t.links.amazon) {
							var amazonLink = document.createElement('a');
							amazonLink.href = t.links.amazon;
							amazonLink.target = '_blank';
							amazonLink.rel = 'noopener';
							amazonLink.setAttribute('aria-label', 'Find on Amazon Music');
							amazonLink.title = 'Find on Amazon Music';
							amazonLink.innerHTML = ICON_AMAZON;
							linksWrap.appendChild(amazonLink);
						}

						li.appendChild(linksWrap);
					}

					recentList.appendChild(li);
				});
				recentWrap.style.display = 'block';
			}

			function fetchNowPlaying() {
				fetch('<?php echo esc_url( admin_url( 'admin-ajax.php?action=zrt_now_playing' ) ); ?>')
					.then(function (r) { return r.json(); })
					.then(function (data) {
						var nowPlaying = data && data.now_playing;
						if (nowPlaying && nowPlaying.title) {
							track.textContent = trackLabel(nowPlaying);
						} else if (nowPlaying && nowPlaying.is_ad) {
							track.textContent = 'Commercial Break';
						} else {
							track.textContent = '93ZRT Web Player';
						}
						if (nowPlaying && nowPlaying.art) {
							art.src = nowPlaying.art;
							art.style.display = 'block';
							disc.style.display = 'none';
						} else {
							art.style.display = 'none';
							disc.style.display = 'inline-block';
						}
						renderHistory(data && data.history);
					})
					.catch(function () { /* leave whatever's currently shown */ });
			}

			function startNowPlayingPolling() {
				fetchNowPlaying();
				if (!nowPlayingTimer) {
					nowPlayingTimer = setInterval(fetchNowPlaying, 20000);
				}
			}

			// Keep "now playing" current regardless of whether the visitor
			// has actually pressed play -- polls continuously in the
			// background, not just while the stream is playing.
			startNowPlayingPolling();

			// Browsers throttle setInterval heavily in background/inactive
			// tabs, so the 20s poll can stretch out (or effectively pause)
			// while the tab isn't focused -- catch up immediately the
			// moment the visitor comes back to it instead of waiting on
			// the throttled timer.
			document.addEventListener('visibilitychange', function () {
				if (document.visibilityState === 'visible') {
					fetchNowPlaying();
				}
			});

			btn.addEventListener('click', function () {
				if (audio.paused) {
					setStatus('Connecting…');
					audio.play().catch(function () {
						setStatus('Couldn’t connect to the stream — try again in a moment.');
					});
				} else {
					audio.pause();
				}
			});

			muteBt.addEventListener('click', function () {
				audio.muted = !audio.muted;
				muteBt.innerHTML = audio.muted ? ICON_MUTED : ICON_VOLUME;
				muteBt.setAttribute('aria-label', audio.muted ? 'Unmute' : 'Mute');
			});

			audio.addEventListener('playing', function () {
				btn.innerHTML = ICON_PAUSE;
				btn.setAttribute('aria-label', 'Pause');
				disc.classList.add('is-spinning');
				bar.classList.add('is-live');
				setStatus('Live now — thanks for tuning in!');
			});

			audio.addEventListener('pause', function () {
				btn.innerHTML = ICON_PLAY;
				btn.setAttribute('aria-label', 'Play');
				disc.classList.remove('is-spinning');
				bar.classList.remove('is-live');
				setStatus('Set the mood — tune in for today’s mix');
			});

			audio.addEventListener('error', function () {
				setStatus('Stream unavailable right now — please try again later.');
				btn.innerHTML = ICON_PLAY;
				disc.classList.remove('is-spinning');
			});
		})();
		</script>
	</div>
</section>

<!-- About -->
<section id="about" class="zrt-section">
	<div class="container">
		<div class="row align-items-center gy-5">
			<div class="col-lg-6">
				<img src="/wp-content/uploads/2026/08/retro-hits-design.png"
					alt="Retro Hits of the 80's 90's and 2K"
					class="rounded-circle mb-4"
					style="width:120px;height:120px;object-fit:cover;border:3px solid var(--zrt-pink);">
				<h2 class="font-retro zrt-section-title">About 93ZRT</h2>
				<p class="fs-5">
					93ZRT is an internet radio station built for people who love retro &mdash; the hits,
					the energy, and the memories that come with them. We're passionate about our
					listener community and grateful that so many of you tune in from all over the world.
				</p>
				<p>
					Whether you're catching a classic on your commute or discovering a deep cut for
					the first time, 93ZRT keeps the golden era playing around the clock.
				</p>
			</div>
			<div class="col-lg-6">
				<div class="row g-4">
					<div class="col-6">
						<div class="zrt-card text-center">
							<div class="display-6 font-retro" style="color:var(--zrt-pink);">24/7</div>
							<div class="small text-uppercase mt-2">Nonstop Retro</div>
						</div>
					</div>
					<div class="col-6">
						<div class="zrt-card text-center">
							<div class="display-6 font-retro" style="color:var(--zrt-teal);">Global</div>
							<div class="small text-uppercase mt-2">Listener Community</div>
						</div>
					</div>
					<div class="col-6">
						<div class="zrt-card text-center">
							<div class="display-6 font-retro" style="color:var(--zrt-yellow);">Live</div>
							<div class="small text-uppercase mt-2">DJ Requests</div>
						</div>
					</div>
					<div class="col-6">
						<div class="zrt-card text-center">
							<div class="display-6 font-retro" style="color:var(--zrt-pink);">100%</div>
							<div class="small text-uppercase mt-2">Free Streaming</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Our Hometown -->
<section id="hometown" class="zrt-section zrt-section-alt">
	<div class="container">
		<h2 class="font-retro zrt-section-title text-center mb-2">Our Hometown</h2>
		<p class="text-center mb-5" style="color: rgba(255,248,236,0.7);">Ridgecrest, California &mdash; where 93ZRT calls home.</p>

		<?php
		$hometown_photos = array(
			array(
				'src'   => '/wp-content/uploads/2026/08/ridgecrest-1-overview.jpg',
				'alt'   => "College Heights Blvd, Ridgecrest, California",
				'title' => 'College Heights Blvd',
				'credit' => "Photo: JMBeggs'nCali, CC BY-SA 4.0, via Wikimedia Commons",
			),
			array(
				'src'   => '/wp-content/uploads/2026/08/ridgecrest-2-town.jpg',
				'alt'   => 'Overview of Ridgecrest, California',
				'title' => 'Ridgecrest Overview',
				'credit' => 'Photo: Matt Affolter (QFL247), CC BY-SA 3.0, via Wikimedia Commons',
			),
			array(
				'src'   => '/wp-content/uploads/2026/08/ridgecrest-3-museum.jpg',
				'alt'   => 'The Maturango Museum in Ridgecrest, California',
				'title' => 'Maturango Museum',
				'credit' => 'Photo: Matt Affolter (QFL247), CC BY-SA 3.0, via Wikimedia Commons',
			),
			array(
				'src'   => '/wp-content/uploads/2026/08/ridgecrest-4-clouds.jpg',
				'alt'   => 'Clouds over Ridgecrest, California',
				'title' => 'Desert Skies',
				'credit' => 'Photo: Michael93555, public domain, via Wikimedia Commons',
			),
			array(
				'src'   => '/wp-content/uploads/2026/08/ridgecrest-5-east.jpg',
				'alt'   => 'East Ridgecrest, California',
				'title' => 'East Ridgecrest',
				'credit' => 'Photo: Michael93555, public domain, via Wikimedia Commons',
			),
		);
		?>

		<div id="ridgecrestCarousel" class="carousel slide zrt-carousel mb-3" data-bs-ride="carousel">
			<div class="carousel-indicators">
				<?php foreach ( $hometown_photos as $i => $photo ) : ?>
					<button type="button" data-bs-target="#ridgecrestCarousel" data-bs-slide-to="<?php echo esc_attr( $i ); ?>"
						class="<?php echo 0 === $i ? 'active' : ''; ?>"
						<?php if ( 0 === $i ) : ?>aria-current="true"<?php endif; ?>
						aria-label="Slide <?php echo esc_attr( $i + 1 ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<div class="carousel-inner">
				<?php foreach ( $hometown_photos as $i => $photo ) : ?>
					<div class="carousel-item <?php echo 0 === $i ? 'active' : ''; ?>">
						<img src="<?php echo esc_url( $photo['src'] ); ?>" class="d-block w-100" alt="<?php echo esc_attr( $photo['alt'] ); ?>">
						<div class="carousel-caption d-none d-md-block zrt-carousel-caption">
							<h5 class="font-retro"><?php echo esc_html( $photo['title'] ); ?></h5>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#ridgecrestCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#ridgecrestCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>

		<p class="small text-center" style="color: rgba(255,248,236,0.4);">
			<?php echo esc_html( $hometown_photos[0]['credit'] ); ?> &middot;
			<?php echo esc_html( $hometown_photos[1]['credit'] ); ?> &middot;
			<?php echo esc_html( $hometown_photos[2]['credit'] ); ?> &middot;
			<?php echo esc_html( $hometown_photos[3]['credit'] ); ?> &middot;
			<?php echo esc_html( $hometown_photos[4]['credit'] ); ?>
		</p>
	</div>
</section>

<!-- Schedule -->
<section id="schedule" class="zrt-section zrt-section-alt">
	<div class="container">
		<h2 class="font-retro zrt-section-title text-center mb-5">Show Schedule</h2>
		<div class="row justify-content-center g-4">
			<div class="col-md-6 col-lg-5">
				<h3 class="small text-uppercase mb-3 text-center" style="color:var(--zrt-teal); letter-spacing:1px;">Week Day</h3>
				<div class="zrt-schedule-item">
					<span class="zrt-schedule-time">6:00 &ndash; 9:00 AM</span>
					<span class="flex-grow-1 ps-3">Lugnut in the Morning</span>
				</div>
				<div class="zrt-schedule-item">
					<span class="zrt-schedule-time">2:00 &ndash; 6:00 PM</span>
					<span class="flex-grow-1 ps-3">Dave Bradley Afternoons</span>
				</div>
			</div>
			<div class="col-md-6 col-lg-5">
				<h3 class="small text-uppercase mb-3 text-center" style="color:var(--zrt-teal); letter-spacing:1px;">Weekend</h3>
				<div class="zrt-schedule-item">
					<span class="zrt-schedule-time">Sat, 8 &ndash; 12 PM</span>
					<span class="flex-grow-1 ps-3">Rick Dees Weekly Top 40: The Eighties</span>
				</div>
				<div class="zrt-schedule-item">
					<span class="zrt-schedule-time">8:00 &ndash; 12:00 PM</span>
					<span class="flex-grow-1 ps-3">Casey Kasem American Top 40: The Eighties</span>
				</div>
			</div>
		</div>
		<div class="text-center mt-4">
			<a href="<?php echo esc_url( home_url( '/schedule/' ) ); ?>" class="btn btn-zrt-outline">View Full Schedule</a>
		</div>
	</div>
</section>

<!-- Contact -->
<section id="contact" class="zrt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center mb-4">
				<h2 class="font-retro zrt-section-title">Questions? Comments? Requests?</h2>
				<p class="fs-5">Click below to get in touch &mdash; we'd love to hear from you.</p>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="zrt-form">
					<?php echo do_shortcode( '[contact-form-7 id="contact-form-1" title="Contact form 1"]' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
