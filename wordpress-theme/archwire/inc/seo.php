<?php
/**
 * Search and AI-answer helpers.
 *
 * - Fallback meta description when no SEO plugin is active.
 * - /llms.txt: a plain-text practice summary for AI assistants, built
 *   from Practice Settings so it never drifts from the site.
 *
 * Titles, canonicals, sitemaps and Open Graph are left to the SEO plugin
 * (Yoast, Rank Math, SEOPress, AIOSEO). The theme does not compete with it.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * True when a known SEO plugin handles meta tags.
 *
 * @return bool
 */
function archwire_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'The_SEO_Framework\Load' );
}

/**
 * Fallback meta description: page excerpt, else the practice description.
 */
function archwire_meta_description() {
	if ( archwire_has_seo_plugin() ) {
		return;
	}
	$desc = '';
	if ( is_singular() && has_excerpt() ) {
		$desc = get_the_excerpt();
	}
	if ( ! $desc ) {
		$desc = archwire_practice()['description'];
	}
	$desc = wp_strip_all_tags( archwire_tokens( $desc ) );
	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_trim_words( $desc, 30, '' ) ) );
	}
}
add_action( 'wp_head', 'archwire_meta_description', 2 );

/**
 * Register /llms.txt.
 */
function archwire_llms_rewrite() {
	add_rewrite_rule( '^llms\.txt$', 'index.php?archwire_llms=1', 'top' );
}
add_action( 'init', 'archwire_llms_rewrite' );

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'archwire_llms';
		return $vars;
	}
);

/**
 * Flush rewrites once when the theme is switched on.
 */
function archwire_flush_rewrites() {
	archwire_llms_rewrite();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'archwire_flush_rewrites' );

/**
 * Serve /llms.txt.
 */
function archwire_llms_serve() {
	if ( ! get_query_var( 'archwire_llms' ) ) {
		return;
	}
	if ( ! archwire_practice()['llms_on'] ) {
		status_header( 404 );
		exit;
	}
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	echo archwire_llms_text(); // phpcs:ignore WordPress.Security.EscapeOutput -- plain text response.
	exit;
}
add_action( 'template_redirect', 'archwire_llms_serve' );

/**
 * Build the llms.txt body (Markdown, per llmstxt.org).
 *
 * @return string
 */
function archwire_llms_text() {
	$p   = archwire_practice();
	$out = '# ' . $p['name'] . "\n\n";
	if ( $p['description'] ) {
		$out .= '> ' . wp_strip_all_tags( archwire_tokens( $p['description'] ) ) . "\n\n";
	}

	$facts = array(
		'Orthodontist'      => $p['doctor'],
		'Address'           => archwire_address_line( $p ),
		'Phone'             => $p['phone'],
		'Book a visit'      => $p['booking_url'],
		'Treatments'        => implode( ', ', $p['treatments'] ),
		'Areas served'      => implode( ', ', $p['areas'] ),
		'Languages'         => implode( ', ', $p['languages'] ),
		'Insurance'         => implode( ', ', $p['insurance'] ),
		'Reviews'           => $p['rating'] ? sprintf( '%s average from %d reviews on %s (%s)', $p['rating'], $p['review_count'], $p['review_source'], $p['reviews_url'] ) : '',
	);
	$hours = array();
	foreach ( archwire_hours_summary() as $g ) {
		$hours[] = $g['days'] . ' ' . $g['hours'];
	}
	$facts['Hours'] = implode( '; ', $hours );

	$out .= "## Practice facts\n\n";
	foreach ( array_filter( $facts ) as $label => $value ) {
		$out .= '- ' . $label . ': ' . $value . "\n";
	}

	$pages = get_pages(
		array(
			'sort_column' => 'menu_order,post_title',
			'number'      => 40,
		)
	);
	if ( $pages ) {
		$out .= "\n## Pages\n\n";
		foreach ( $pages as $page ) {
			$summary = has_excerpt( $page ) ? ': ' . wp_strip_all_tags( archwire_tokens( get_the_excerpt( $page ) ) ) : '';
			$out    .= '- [' . get_the_title( $page ) . '](' . get_permalink( $page ) . ')' . $summary . "\n";
		}
	}

	$posts = get_posts( array( 'numberposts' => 10 ) );
	if ( $posts ) {
		$out .= "\n## Articles\n\n";
		foreach ( $posts as $post ) {
			$out .= '- [' . get_the_title( $post ) . '](' . get_permalink( $post ) . ")\n";
		}
	}

	/**
	 * Filter the llms.txt output.
	 *
	 * @param string $out Text.
	 */
	return apply_filters( 'archwire_llms_text', $out );
}
