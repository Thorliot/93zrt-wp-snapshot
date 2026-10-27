<?php
/**
 * 93ZRT Retro Radio theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ninetythreezrt_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'ninetythreezrt' ),
			'footer'  => __( 'Footer Menu', 'ninetythreezrt' ),
		)
	);
}
add_action( 'after_setup_theme', 'ninetythreezrt_setup' );

/**
 * wp_nav_menu() doesn't add Bootstrap's "nav-link" class on its own, so menu
 * links were falling back to Bootstrap's default (low-contrast) link color
 * against the dark navbar. Add it to every top-level menu item link.
 */
function ninetythreezrt_nav_link_class( $atts, $item, $args ) {
	if ( ! empty( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ninetythreezrt_nav_link_class', 10, 3 );

function ninetythreezrt_scripts() {
	// Bootstrap 5 (CSS + bundled JS incl. Popper)
	wp_enqueue_style( 'bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );

	// Google Fonts used by the retro theme
	wp_enqueue_style( 'ninetythreezrt-fonts', 'https://fonts.googleapis.com/css2?family=Bungee&family=Poppins:wght@400;600;700&display=swap', array(), null );

	// Theme stylesheet (loaded after Bootstrap so overrides win)
	wp_enqueue_style( 'ninetythreezrt-style', get_stylesheet_uri(), array( 'bootstrap5' ), wp_get_theme()->get( 'Version' ) );

	wp_enqueue_script( 'bootstrap5-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ninetythreezrt_scripts' );

/**
 * Live weather for the station's coverage area (Ridgecrest, CA), via the
 * free Open-Meteo API -- no API key required. Cached in a transient so we
 * don't hit the API on every page load.
 */
function ninetythreezrt_weather_code_info( $code ) {
	$map = array(
		0  => array( 'Clear sky', "\u{2600}\u{FE0F}" ),
		1  => array( 'Mostly clear', "\u{1F324}\u{FE0F}" ),
		2  => array( 'Partly cloudy', "\u{26C5}" ),
		3  => array( 'Overcast', "\u{2601}\u{FE0F}" ),
		45 => array( 'Fog', "\u{1F32B}\u{FE0F}" ),
		48 => array( 'Fog', "\u{1F32B}\u{FE0F}" ),
		51 => array( 'Light drizzle', "\u{1F326}\u{FE0F}" ),
		53 => array( 'Drizzle', "\u{1F326}\u{FE0F}" ),
		55 => array( 'Dense drizzle', "\u{1F327}\u{FE0F}" ),
		61 => array( 'Light rain', "\u{1F327}\u{FE0F}" ),
		63 => array( 'Rain', "\u{1F327}\u{FE0F}" ),
		65 => array( 'Heavy rain', "\u{1F327}\u{FE0F}" ),
		71 => array( 'Light snow', "\u{1F328}\u{FE0F}" ),
		73 => array( 'Snow', "\u{1F328}\u{FE0F}" ),
		75 => array( 'Heavy snow', "\u{1F328}\u{FE0F}" ),
		80 => array( 'Rain showers', "\u{1F326}\u{FE0F}" ),
		81 => array( 'Rain showers', "\u{1F326}\u{FE0F}" ),
		82 => array( 'Violent showers', "\u{26C8}\u{FE0F}" ),
		95 => array( 'Thunderstorm', "\u{26C8}\u{FE0F}" ),
		96 => array( 'Thunderstorm w/ hail', "\u{26C8}\u{FE0F}" ),
		99 => array( 'Thunderstorm w/ hail', "\u{26C8}\u{FE0F}" ),
	);
	return isset( $map[ $code ] ) ? $map[ $code ] : array( 'Conditions unavailable', "\u{1F321}\u{FE0F}" );
}

function ninetythreezrt_get_weather() {
	$cached = get_transient( 'ninetythreezrt_weather' );
	if ( false !== $cached ) {
		return $cached;
	}

	$url = add_query_arg(
		array(
			'latitude'         => '35.6225',
			'longitude'        => '-117.6709',
			'current'          => 'temperature_2m,weather_code,relative_humidity_2m,wind_speed_10m',
			'daily'            => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
			'forecast_days'    => 7,
			'temperature_unit' => 'fahrenheit',
			'wind_speed_unit'  => 'mph',
			'timezone'         => 'America/Los_Angeles',
		),
		'https://api.open-meteo.com/v1/forecast'
	);

	$response = wp_remote_get( $url, array( 'timeout' => 8 ) );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return false;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $data['current'] ) ) {
		return false;
	}

	list( $desc, $icon ) = ninetythreezrt_weather_code_info( $data['current']['weather_code'] );

	$forecast = array();
	if ( ! empty( $data['daily']['time'] ) ) {
		foreach ( $data['daily']['time'] as $i => $date ) {
			list( $day_desc, $day_icon ) = ninetythreezrt_weather_code_info( $data['daily']['weather_code'][ $i ] );
			$forecast[] = array(
				'label'   => 0 === $i ? 'Today' : date_i18n( 'D', strtotime( $date ) ),
				'date'    => date_i18n( 'M j', strtotime( $date ) ),
				'icon'    => $day_icon,
				'desc'    => $day_desc,
				'high'    => round( $data['daily']['temperature_2m_max'][ $i ] ),
				'low'     => round( $data['daily']['temperature_2m_min'][ $i ] ),
				'pop'     => isset( $data['daily']['precipitation_probability_max'][ $i ] ) ? $data['daily']['precipitation_probability_max'][ $i ] : null,
			);
		}
	}

	$weather = array(
		'location'    => 'Ridgecrest, CA',
		'temp'        => round( $data['current']['temperature_2m'] ),
		'description' => $desc,
		'icon'        => $icon,
		'humidity'    => $data['current']['relative_humidity_2m'],
		'wind'        => round( $data['current']['wind_speed_10m'] ),
		'high'        => isset( $data['daily']['temperature_2m_max'][0] ) ? round( $data['daily']['temperature_2m_max'][0] ) : null,
		'low'         => isset( $data['daily']['temperature_2m_min'][0] ) ? round( $data['daily']['temperature_2m_min'][0] ) : null,
		'updated'     => current_time( 'g:i A' ),
		'forecast'    => $forecast,
	);

	set_transient( 'ninetythreezrt_weather', $weather, 30 * MINUTE_IN_SECONDS );

	return $weather;
}

function ninetythreezrt_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widget Area', 'ninetythreezrt' ),
			'id'            => 'footer-1',
			'before_widget' => '<div class="col-md-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="text-uppercase mb-3" style="color:var(--zrt-yellow);">',
			'after_title'   => '</h5>',
		)
	);
}
add_action( 'widgets_init', 'ninetythreezrt_widgets_init' );

/**
 * Open Graph + Twitter Card meta tags for social sharing, covering the
 * homepage and every page (About, Schedule, Weather, Contact, legal pages,
 * etc.).
 */
function ninetythreezrt_default_og_image() {
	$attachment_id = 38; // 93ZRT logo, uploaded via media library.
	$url           = wp_get_attachment_url( $attachment_id );
	if ( $url ) {
		$meta = wp_get_attachment_metadata( $attachment_id );
		return array(
			'url'    => $url,
			'width'  => isset( $meta['width'] ) ? $meta['width'] : 1000,
			'height' => isset( $meta['height'] ) ? $meta['height'] : 376,
		);
	}
	return array(
		'url'    => home_url( '/wp-content/uploads/2026/08/93zrt-logo.png' ),
		'width'  => 1000,
		'height' => 376,
	);
}

function ninetythreezrt_open_graph_tags() {
	if ( is_admin() || is_feed() || is_search() || is_404() ) {
		return;
	}

	$site_name  = get_bloginfo( 'name' );
	$default_og = ninetythreezrt_default_og_image();

	$og_type   = 'website';
	$og_url    = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$og_title  = $site_name;
	$og_desc   = get_bloginfo( 'description' );
	$og_image  = $default_og['url'];
	$og_img_w  = $default_og['width'];
	$og_img_h  = $default_og['height'];

	if ( is_front_page() ) {
		$og_url   = home_url( '/' );
		$og_title = $site_name . ' | Retro Hits of the 80s, 90s & 2K';
		$og_desc  = "Streaming 93.7 FM's retro hits 24/7, live and local from Ridgecrest, CA.";
	} elseif ( is_singular( array( 'page', 'post' ) ) ) {
		$og_url   = get_permalink();
		$og_title = get_the_title() . ' | ' . $site_name;
		$og_type  = 'article';
		$excerpt  = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
		if ( $excerpt ) {
			$og_desc = $excerpt;
		}
	}

	if ( has_post_thumbnail() ) {
		$thumb_id = get_post_thumbnail_id();
		$img      = wp_get_attachment_image_src( $thumb_id, 'large' );
		if ( $img ) {
			list( $og_image, $og_img_w, $og_img_h ) = $img;
		}
	}

	if ( empty( $og_desc ) ) {
		$og_desc = $site_name . ' — retro hits, live and local from Ridgecrest, CA.';
	}
	?>
	<!-- Open Graph -->
	<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
	<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( $og_desc ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>" />
	<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>" />
	<meta property="og:image:width" content="<?php echo (int) $og_img_w; ?>" />
	<meta property="og:image:height" content="<?php echo (int) $og_img_h; ?>" />
	<meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>" />
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( $og_title ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( $og_desc ); ?>" />
	<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>" />
	<?php
}
add_action( 'wp_head', 'ninetythreezrt_open_graph_tags', 1 );

/**
 * "Now Playing" for the web player -- reads the track title (and, when
 * available, cover art) straight out of the Live365 stream's in-band ICY
 * metadata (the standard Shoutcast/Icecast mechanism every stream uses to
 * broadcast the current song), rather than relying on a separate API.
 */
/**
 * Best-effort heuristic for spotting ad reads / station IDs / sweepers that
 * come through the same StreamTitle field as real songs -- e.g. the artist
 * being the station's own name, or a phone number / promo URL in the title.
 * Not foolproof, just filters the obvious cases.
 */
function ninetythreezrt_looks_like_ad( $title, $artist ) {
	if ( ! $title ) {
		return false;
	}

	if ( $artist && preg_match( '/93\s*-?\s*zrt|retro\s*hits/i', $artist ) ) {
		return true;
	}

	if ( preg_match( '/\b\d{3}[-.\s]\d{3}[-.\s]\d{4}\b|\b1-?800\b|www\.|\.com\b/i', $title ) ) {
		return true;
	}

	return false;
}

function ninetythreezrt_fetch_now_playing() {
	$cached = get_transient( 'ninetythreezrt_now_playing' );
	if ( false !== $cached ) {
		return $cached;
	}

	$result = array(
		'title'  => null,
		'artist' => null,
		'art'    => null,
		'is_ad'  => false,
	);

	if ( function_exists( 'curl_init' ) ) {
		$ch      = curl_init( 'https://streaming.live365.com/a50341' );
		$buffer  = '';
		$metaint = null;

		curl_setopt_array(
			$ch,
			array(
				CURLOPT_HTTPHEADER     => array( 'Icy-MetaData: 1' ),
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_TIMEOUT        => 6,
				CURLOPT_USERAGENT      => 'Mozilla/5.0',
			)
		);

		curl_setopt(
			$ch,
			CURLOPT_HEADERFUNCTION,
			function ( $curl_handle, $header_line ) use ( &$metaint ) {
				if ( preg_match( '/^icy-metaint:\s*(\d+)/i', $header_line, $m ) ) {
					$metaint = (int) $m[1];
				}
				return strlen( $header_line );
			}
		);

		curl_setopt(
			$ch,
			CURLOPT_WRITEFUNCTION,
			function ( $curl_handle, $chunk ) use ( &$buffer, &$metaint ) {
				$buffer .= $chunk;
				// Stop the transfer once we're safely past the metadata block.
				if ( $metaint && strlen( $buffer ) > $metaint + 4096 ) {
					return 0;
				}
				return strlen( $chunk );
			}
		);

		curl_exec( $ch );
		curl_close( $ch );

		if ( $metaint && strlen( $buffer ) > $metaint ) {
			$meta_len = ord( substr( $buffer, $metaint, 1 ) ) * 16;
			if ( $meta_len > 0 ) {
				$meta_block = substr( $buffer, $metaint + 1, $meta_len );

				if ( preg_match( "/StreamTitle='([^']*)'/", $meta_block, $m ) ) {
					$stream_title = trim( $m[1] );
					if ( $stream_title ) {
						if ( false !== strpos( $stream_title, ' - ' ) ) {
							list( $artist, $title ) = array_map( 'trim', explode( ' - ', $stream_title, 2 ) );
							$result['artist'] = $artist;
							$result['title']  = $title;
						} else {
							$result['title'] = $stream_title;
						}
					}
				}

				if ( preg_match( "/StreamUrl='([^']*)'/", $meta_block, $m ) ) {
					$art = trim( $m[1] );
					if ( $art ) {
						$result['art'] = $art;
					}
				}
			}
		}
	}

	// The stream pushes ad reads, station IDs, and sweepers through the same
	// metadata field as actual songs -- there's no separate marker for it.
	// Filter out the obvious cases so they don't show up as "now playing"
	// or get logged to the recently-played history.
	if ( ninetythreezrt_looks_like_ad( $result['title'], $result['artist'] ) ) {
		$result['title']  = null;
		$result['artist'] = null;
		$result['art']    = null;
		$result['is_ad']  = true;
	}

	// Short cache -- long enough to avoid hammering the stream on every
	// pageview, short enough that "now playing" stays reasonably current.
	set_transient( 'ninetythreezrt_now_playing', $result, 20 );

	if ( $result['title'] ) {
		ninetythreezrt_log_track_history( $result );
	}

	return $result;
}

/**
 * Live365 doesn't offer a public "recently played" API (it's an open
 * feature request on their own feedback board), so we build our own: every
 * time the now-playing fetch above sees the track change, log it here. The
 * list is only as complete as how often something triggers a fetch (a
 * visitor polling the player, or the background cron below), not a
 * guaranteed full history of everything aired.
 */
function ninetythreezrt_log_track_history( $track ) {
	$history = get_option( 'ninetythreezrt_track_history', array() );

	$last = isset( $history[0] ) ? $history[0] : null;
	$same_as_last = $last
		&& $last['title'] === $track['title']
		&& $last['artist'] === $track['artist'];

	if ( $same_as_last ) {
		return;
	}

	array_unshift(
		$history,
		array(
			'title'  => $track['title'],
			'artist' => $track['artist'],
			'art'    => $track['art'],
			'time'   => current_time( 'timestamp' ),
		)
	);

	$history = array_slice( $history, 0, 10 );

	update_option( 'ninetythreezrt_track_history', $history, false );
}

/**
 * "Find this song" links for a track. Built as search-query URLs (not
 * direct track pages) since that requires no API keys/credentials and
 * works reliably for any title/artist combination.
 */
function ninetythreezrt_track_store_links( $title, $artist ) {
	if ( ! $title ) {
		return array(
			'apple_music' => null,
			'amazon'      => null,
		);
	}

	$query = trim( $artist . ' ' . $title );

	return array(
		'apple_music' => 'https://music.apple.com/us/search?term=' . rawurlencode( $query ),
		'amazon'      => 'https://music.amazon.com/search/' . rawurlencode( $query ),
	);
}

function ninetythreezrt_get_track_history() {
	$history = get_option( 'ninetythreezrt_track_history', array() );
	// Never show the currently-playing track as its own "recently played" entry.
	$history = array_slice( $history, 1, 8 );

	foreach ( $history as &$track ) {
		$track['links'] = ninetythreezrt_track_store_links( $track['title'], $track['artist'] );
	}
	unset( $track );

	return $history;
}

function ninetythreezrt_ajax_now_playing() {
	$now_playing = ninetythreezrt_fetch_now_playing();
	wp_send_json(
		array(
			'now_playing' => $now_playing,
			'history'     => ninetythreezrt_get_track_history(),
		)
	);
}
add_action( 'wp_ajax_zrt_now_playing', 'ninetythreezrt_ajax_now_playing' );
add_action( 'wp_ajax_nopriv_zrt_now_playing', 'ninetythreezrt_ajax_now_playing' );

/**
 * Background polling so the history keeps building even when nobody's on
 * the site with the player open. WP-Cron only fires on page loads (there's
 * no true background process here), so this is best-effort, not a
 * guaranteed heartbeat -- but it's the standard WordPress-native approach.
 */
add_filter(
	'cron_schedules',
	function ( $schedules ) {
		$schedules['ninetythreezrt_two_minutes'] = array(
			'interval' => 120,
			'display'  => __( 'Every 2 minutes (93ZRT now-playing poll)', 'ninetythreezrt' ),
		);
		return $schedules;
	}
);

// Registering the schedule above must happen before this check runs, so
// this is hooked to init (safely after cron_schedules has been filtered)
// rather than called at the top level of this file.
add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'ninetythreezrt_poll_now_playing' ) ) {
			wp_schedule_event( time(), 'ninetythreezrt_two_minutes', 'ninetythreezrt_poll_now_playing' );
		}
	}
);
add_action( 'ninetythreezrt_poll_now_playing', 'ninetythreezrt_fetch_now_playing' );
