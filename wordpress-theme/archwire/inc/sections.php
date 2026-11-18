<?php
/**
 * Section renderer.
 *
 * Reads the "page_sections" Flexible Content value as an array and hands
 * each row to template-parts/sections/{layout}.php as $args['s'].
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sections for a post, minus hidden ones.
 *
 * @param int|null $post_id Post ID.
 * @return array
 */
function archwire_get_sections( $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	$rows = get_field( 'page_sections', $post_id ? $post_id : get_the_ID() );
	if ( ! is_array( $rows ) ) {
		return array();
	}
	return array_values(
		array_filter(
			$rows,
			function ( $row ) {
				return is_array( $row ) && ! empty( $row['acf_fc_layout'] ) && empty( $row['hide'] );
			}
		)
	);
}

/**
 * Render a list of section rows.
 *
 * @param array $rows Section rows (each has acf_fc_layout).
 */
function archwire_render_sections( $rows ) {
	foreach ( $rows as $index => $row ) {
		$slug = sanitize_key( str_replace( '_', '-', $row['acf_fc_layout'] ) );
		get_template_part(
			'template-parts/sections/' . $slug,
			null,
			array(
				's'     => $row,
				'index' => $index,
			)
		);
	}
}

/**
 * Collect FAQ items while sections render; printed as one FAQPage in the footer.
 *
 * @param string $question Question.
 * @param string $answer   Answer.
 */
function archwire_faq_collect( $question, $answer ) {
	$GLOBALS['archwire_faq'][] = array(
		'q' => $question,
		'a' => $answer,
	);
}
