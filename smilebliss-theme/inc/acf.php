<?php
/**
 * ACF wiring: local JSON, options page, and editor niceties.
 *
 * Field groups live in acf-json/ and load automatically, so the theme carries its
 * own fields and nothing has to be imported by hand.
 *
 * @package Smilebliss
 */

declare( strict_types = 1 );

/**
 * Save field-group edits into the theme instead of the database.
 */
function smilebliss_acf_json_save_point( string $path ): string {
	return SMILEBLISS_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'smilebliss_acf_json_save_point' );

/**
 * Load field groups from the theme.
 */
function smilebliss_acf_json_load_point( array $paths ): array {
	unset( $paths[0] );
	$paths[] = SMILEBLISS_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'smilebliss_acf_json_load_point' );

/**
 * Site-wide settings: logo, contact details, social, form endpoint.
 */
function smilebliss_acf_options_page(): void {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}
	acf_add_options_page(
		array(
			'page_title' => __( 'Smilebliss Settings', 'smilebliss' ),
			'menu_title' => __( 'Smilebliss', 'smilebliss' ),
			'menu_slug'  => 'smilebliss-settings',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-smiley',
			'position'   => 59,
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'smilebliss_acf_options_page' );

/**
 * Show the layout's own heading in the collapsed Flexible Content row, so a long
 * page is readable in the editor without opening every section.
 *
 * @param string $title  Default title.
 * @param array  $field  Field array.
 * @param array  $layout Layout array.
 * @param int    $i      Row index.
 */
function smilebliss_flexible_layout_title( string $title, array $field, array $layout, int $i ): string {
	$heading = get_sub_field( 'title' );
	if ( ! is_string( $heading ) || '' === trim( $heading ) ) {
		$heading = get_sub_field( 'heading' );
	}
	if ( is_string( $heading ) && '' !== trim( $heading ) ) {
		$heading = wp_strip_all_tags( $heading );
		$title  .= ' &mdash; <span style="font-weight:400">' . esc_html( wp_trim_words( $heading, 9, '&hellip;' ) ) . '</span>';
	}
	return $title;
}
add_filter( 'acf/fields/flexible_content/layout_title/name=sections', 'smilebliss_flexible_layout_title', 10, 4 );
