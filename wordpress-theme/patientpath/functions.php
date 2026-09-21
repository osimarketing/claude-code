<?php
/**
 * PatientPath theme functions.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PATIENTPATH_VERSION', '1.0.0' );

/**
 * Theme setup.
 */
function patientpath_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'patientpath' ),
		)
	);
}
add_action( 'after_setup_theme', 'patientpath_setup' );

/**
 * Enqueue styles and scripts.
 *
 * NOTE FOR DEVS: hero-scene.js is an ES module that relies on an import map
 * (printed in footer.php). Caching/optimization plugins (WP Rocket, Autoptimize,
 * LiteSpeed, etc.) must EXCLUDE gsap, ScrollTrigger, hero-scene.js and site.js
 * from JS concatenation / minification / defer, or the 3D hero and interactions
 * will break. The module tag is printed manually in footer.php for that reason.
 */
function patientpath_assets() {
	$uri = get_template_directory_uri();
	$ver = PATIENTPATH_VERSION;

	// Google Fonts (swap for Museo Sans Rounded once licensed).
	wp_enqueue_style(
		'patientpath-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'patientpath', $uri . '/assets/css/site.css', array(), $ver );

	// GSAP + ScrollTrigger (self-hosted vendor copies).
	wp_enqueue_script( 'gsap', $uri . '/assets/js/vendor/gsap.min.js', array(), $ver, true );
	wp_enqueue_script( 'gsap-scrolltrigger', $uri . '/assets/js/vendor/ScrollTrigger.min.js', array( 'gsap' ), $ver, true );

	// Site interactions (mobile nav, reveals, counters, accordion, carousel).
	wp_enqueue_script( 'patientpath', $uri . '/assets/js/site.js', array(), $ver, true );
}
add_action( 'wp_enqueue_scripts', 'patientpath_assets' );

/**
 * Register the ACF Flexible Content field group (works without manual import).
 */
require get_template_directory() . '/inc/acf-fields.php';

/**
 * Small helper: render a link array field ({ title, url, target }) or a
 * text/url pair, returning safe attributes. Accepts an ACF "Link" field value.
 */
function patientpath_link_attrs( $link ) {
	if ( empty( $link ) ) {
		return '';
	}
	$url    = is_array( $link ) ? ( $link['url'] ?? '' ) : $link;
	$target = is_array( $link ) && ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : '';
	return 'href="' . esc_url( $url ) . '"' . $target;
}

/**
 * Rough reading time for a post ("N min read").
 */
function patientpath_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$minutes = max( 1, (int) round( $words / 200 ) );
	/* translators: %d: minutes */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'patientpath' ), $minutes );
}

/**
 * Admin notice if ACF PRO (Flexible Content) is not active.
 */
function patientpath_acf_notice() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		echo '<div class="notice notice-error"><p><strong>PatientPath theme:</strong> Advanced Custom Fields PRO is required for the homepage "Page Sections" (Flexible Content). Please install and activate ACF PRO.</p></div>';
	}
}
add_action( 'admin_notices', 'patientpath_acf_notice' );
