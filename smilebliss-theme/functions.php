<?php
/**
 * Smilebliss Licensing theme bootstrap.
 *
 * @package Smilebliss
 */

declare( strict_types = 1 );

define( 'SMILEBLISS_VERSION', '1.0.0' );
define( 'SMILEBLISS_DIR', get_template_directory() );
define( 'SMILEBLISS_URI', get_template_directory_uri() );

require_once SMILEBLISS_DIR . '/inc/helpers.php';
require_once SMILEBLISS_DIR . '/inc/acf.php';
require_once SMILEBLISS_DIR . '/inc/demo-content.php';

/**
 * Theme supports and menus.
 */
function smilebliss_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 490,
			'width'       => 1500,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary (slide-out panel)', 'smilebliss' ),
			'footer'  => __( 'Footer', 'smilebliss' ),
		)
	);

	// Widths that match the exports the design was built against.
	add_image_size( 'smilebliss-portrait-sm', 580, 667, true );
	add_image_size( 'smilebliss-portrait-md', 880, 1011, true );
	add_image_size( 'smilebliss-portrait-lg', 1160, 1333, true );
	add_image_size( 'smilebliss-portrait-xl', 1740, 2000, true );
	add_image_size( 'smilebliss-hero-sm', 560, 0, false );
	add_image_size( 'smilebliss-hero-md', 840, 0, false );
	add_image_size( 'smilebliss-hero-lg', 1120, 0, false );
	add_image_size( 'smilebliss-icon', 168, 168, false );
}
add_action( 'after_setup_theme', 'smilebliss_setup' );

/**
 * Front-end assets.
 *
 * main.js is deferred: it only decorates and never writes markup during parse.
 */
function smilebliss_assets(): void {
	wp_enqueue_style(
		'smilebliss-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'smilebliss-main',
		SMILEBLISS_URI . '/assets/css/main.css',
		array( 'smilebliss-fonts' ),
		smilebliss_asset_version( '/assets/css/main.css' )
	);

	wp_enqueue_script(
		'smilebliss-main',
		SMILEBLISS_URI . '/assets/js/main.js',
		array(),
		smilebliss_asset_version( '/assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'smilebliss-main',
		'smileblissConfig',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'smilebliss_enquiry' ),
			'endpoint' => smilebliss_option( 'form_endpoint', '' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'smilebliss_assets' );

/**
 * Preconnect to the font hosts, matching the static build.
 */
function smilebliss_resource_hints( array $hints, string $relation ): array {
	if ( 'preconnect' === $relation ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'smilebliss_resource_hints', 10, 2 );

/**
 * Cache-bust from file mtime so deploys do not need a version bump.
 */
function smilebliss_asset_version( string $relative_path ): string {
	$file = SMILEBLISS_DIR . $relative_path;
	return file_exists( $file ) ? (string) filemtime( $file ) : SMILEBLISS_VERSION;
}

/**
 * Flexible Content is a PRO-only field type, so test for the type itself rather
 * than for a constant or class name that has moved between ACF releases.
 */
function smilebliss_has_acf_pro(): bool {
	return function_exists( 'acf_get_field_type' ) && (bool) acf_get_field_type( 'flexible_content' );
}

/**
 * Tell an administrator why the page is empty, rather than failing silently.
 */
function smilebliss_require_acf_pro(): void {
	if ( smilebliss_has_acf_pro() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Smilebliss theme:', 'smilebliss' ),
		esc_html__( 'Advanced Custom Fields PRO is required. Page sections are built with Flexible Content, which the free plugin does not include.', 'smilebliss' )
	);
}
add_action( 'admin_notices', 'smilebliss_require_acf_pro' );
