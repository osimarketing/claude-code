<?php
/**
 * Theme supports, menus, assets and brand color output.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function archwire_setup() {
	load_theme_textdomain( 'archwire', ARCHWIRE_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_post_type_support( 'page', 'excerpt' );

	add_image_size( 'archwire-card', 720, 540, true );   // 4:3 cards.
	add_image_size( 'archwire-portrait', 960, 1200, true ); // 4:5 hero, doctor.
	add_image_size( 'archwire-wide', 1600, 900, true );  // 16:9 posts.

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'archwire' ),
			'footer'  => __( 'Footer menu', 'archwire' ),
			'legal'   => __( 'Legal links (footer bottom)', 'archwire' ),
		)
	);
}
add_action( 'after_setup_theme', 'archwire_setup' );

/**
 * Front-end assets. No jQuery, no animation libraries.
 */
function archwire_assets() {
	wp_enqueue_style( 'archwire', ARCHWIRE_URI . '/assets/css/main.css', array(), ARCHWIRE_VERSION );
	wp_add_inline_style( 'archwire', archwire_brand_css() );

	wp_enqueue_script(
		'archwire',
		ARCHWIRE_URI . '/assets/js/main.js',
		array(),
		ARCHWIRE_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'archwire',
		'archwireHours',
		json_decode( archwire_hours_json(), true )
	);
}
add_action( 'wp_enqueue_scripts', 'archwire_assets' );

/**
 * Preload the one font file so headings do not shift on load.
 */
function archwire_preload_font() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( ARCHWIRE_URI . '/assets/fonts/figtree-latin-var.woff2' )
	);
}
add_action( 'wp_head', 'archwire_preload_font', 1 );

/**
 * Brand colors from Practice Settings → Brand, printed as CSS variables.
 * Defaults are the palette: Dark Turquoise, Light Sky Blue, Pale Cool Gray.
 *
 * @return string
 */
function archwire_brand_css() {
	$brand = archwire_hex( archwire_opt( 'brand_color' ), '#2E95B3' );
	$ink   = archwire_hex( archwire_opt( 'brand_ink' ), archwire_mix( $brand, '#000000', 0.25 ) );
	$deep  = archwire_hex( archwire_opt( 'brand_deep' ), archwire_mix( $brand, '#06161C', 0.72 ) );
	$sky   = archwire_hex( archwire_opt( 'brand_sky' ), '#85BADB' );
	$paper = archwire_hex( archwire_opt( 'brand_paper' ), '#F2F7FA' );

	if ( '#2E95B3' === $brand && '#85BADB' === $sky && '#F2F7FA' === $paper && ! archwire_opt( 'brand_ink' ) && ! archwire_opt( 'brand_deep' ) ) {
		return ''; // Defaults already live in main.css.
	}
	return sprintf(
		':root{--brand:%1$s;--brand-ink:%2$s;--brand-deep:%3$s;--sky:%4$s;--paper:%5$s;}',
		$brand,
		$ink,
		$deep,
		$sky,
		$paper
	);
}

/**
 * Validate a hex color.
 *
 * @param string $value   Input.
 * @param string $default Fallback.
 * @return string
 */
function archwire_hex( $value, $default ) {
	$value = is_string( $value ) ? sanitize_hex_color( $value ) : '';
	return $value ? strtoupper( $value ) : strtoupper( $default );
}

/**
 * Mix two hex colors. $amount is how much of $b to add (0–1).
 *
 * @param string $a      Hex.
 * @param string $b      Hex.
 * @param float  $amount Amount of $b.
 * @return string
 */
function archwire_mix( $a, $b, $amount ) {
	$a   = sscanf( ltrim( $a, '#' ), '%02x%02x%02x' );
	$b   = sscanf( ltrim( $b, '#' ), '%02x%02x%02x' );
	$out = '#';
	for ( $i = 0; $i < 3; $i++ ) {
		$out .= sprintf( '%02X', (int) round( $a[ $i ] * ( 1 - $amount ) + $b[ $i ] * $amount ) );
	}
	return $out;
}

/**
 * Remove emoji scripts and styles (unused, saves a request).
 */
function archwire_cleanup() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'archwire_cleanup' );

/**
 * Body classes for the mobile action bar.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function archwire_body_class( $classes ) {
	if ( archwire_practice()['mobile_bar'] ) {
		$classes[] = 'has-action-bar';
	}
	return $classes;
}
add_filter( 'body_class', 'archwire_body_class' );

/**
 * Fallback primary navigation until a menu is assigned.
 */
function archwire_nav_fallback() {
	$pages = get_pages(
		array(
			'sort_column' => 'menu_order',
			'number'      => 5,
			'parent'      => 0,
		)
	);
	echo '<ul class="nav__list">';
	foreach ( $pages as $page ) {
		if ( (int) get_option( 'page_on_front' ) === (int) $page->ID ) {
			continue;
		}
		printf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $page ) ), esc_html( get_the_title( $page ) ) );
	}
	echo '</ul>';
}

/**
 * Notice when no field plugin is active.
 */
function archwire_fields_notice() {
	if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p><strong>Archwire:</strong> ' . esc_html__( 'Install and activate Advanced Custom Fields PRO or Secure Custom Fields. Page sections and Practice Settings need Flexible Content, Repeater and Options pages.', 'archwire' ) . '</p></div>';
}
add_action( 'admin_notices', 'archwire_fields_notice' );
