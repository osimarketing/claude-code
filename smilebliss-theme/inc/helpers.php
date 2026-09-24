<?php
/**
 * Small rendering helpers shared by the flexible-content partials.
 *
 * @package Smilebliss
 */

declare( strict_types = 1 );

/**
 * Read a theme option set on the ACF options page, with a fallback.
 */
function smilebliss_option( string $name, mixed $default = '' ): mixed {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	return ( null === $value || '' === $value || array() === $value ) ? $default : $value;
}

/**
 * The four-point sparkle used inside eyebrow pills and decorative corners.
 */
function smilebliss_spark( string $class = 'spark' ): string {
	return sprintf(
		'<svg class="%s" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 0l2.6 8.4L23 11l-8.4 2.6L12 22l-2.6-8.4L1 11l8.4-2.6z"/></svg>',
		esc_attr( $class )
	);
}

/**
 * Eyebrow pill. Returns an empty string when there is no label, so callers can
 * echo it unconditionally.
 *
 * @param string $text     Label.
 * @param string $modifier One of '', 'mint', 'light'.
 * @param bool   $centered Whether to centre the pill in its column.
 */
function smilebliss_eyebrow( string $text, string $modifier = '', bool $centered = false ): string {
	$text = trim( $text );
	if ( '' === $text ) {
		return '';
	}
	$class = 'eyebrow' . ( $modifier ? ' eyebrow--' . $modifier : '' );
	return sprintf(
		'<span class="%s"%s>%s%s</span>',
		esc_attr( $class ),
		$centered ? ' style="margin-inline:auto;"' : '',
		smilebliss_spark(),
		esc_html( $text )
	);
}

/**
 * Render an ACF image array as a responsive <img>.
 *
 * Falls back to a bundled asset so a freshly installed theme is never broken
 * by an empty field.
 *
 * @param array|int|false $image ACF image array or attachment ID.
 * @param array           $args  class, sizes, alt, fallback, loading, fetchpriority, size.
 */
function smilebliss_image( mixed $image, array $args = array() ): string {
	$args = wp_parse_args(
		$args,
		array(
			'class'         => '',
			'sizes'         => '',
			'alt'           => '',
			'fallback'      => '',
			'size'          => 'full',
			'loading'       => 'lazy',
			'fetchpriority' => '',
		)
	);

	$id = 0;
	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$id = (int) $image['ID'];
	} elseif ( is_numeric( $image ) ) {
		$id = (int) $image;
	}

	$attr = array( 'class' => $args['class'] );
	if ( $args['sizes'] ) {
		$attr['sizes'] = $args['sizes'];
	}
	if ( $args['alt'] ) {
		$attr['alt'] = $args['alt'];
	}
	if ( 'lazy' !== $args['loading'] ) {
		$attr['loading'] = $args['loading'];
	}
	if ( $args['fetchpriority'] ) {
		$attr['fetchpriority'] = $args['fetchpriority'];
	}
	$attr['decoding'] = 'async';

	if ( $id ) {
		return wp_get_attachment_image( $id, $args['size'], false, $attr );
	}

	if ( ! $args['fallback'] ) {
		return '';
	}

	$html = '<img src="' . esc_url( SMILEBLISS_URI . '/assets/img/' . ltrim( $args['fallback'], '/' ) ) . '"';
	foreach ( $attr as $key => $value ) {
		if ( 'sizes' === $key ) {
			continue; // Meaningless without a srcset.
		}
		$html .= ' ' . esc_attr( $key ) . '="' . esc_attr( (string) $value ) . '"';
	}
	if ( ! isset( $attr['alt'] ) ) {
		$html .= ' alt=""';
	}
	return $html . '>';
}

/**
 * Normalise an ACF link field (array) or a plain URL string.
 *
 * @return array{url:string,title:string,target:string}
 */
function smilebliss_link( mixed $link, string $default_title = '', string $default_url = '' ): array {
	$out = array(
		'url'    => $default_url,
		'title'  => $default_title,
		'target' => '',
	);
	if ( is_array( $link ) ) {
		$out['url']    = $link['url'] ?? $default_url;
		$out['title']  = $link['title'] ?? $default_title;
		$out['target'] = $link['target'] ?? '';
	} elseif ( is_string( $link ) && '' !== $link ) {
		$out['url'] = $link;
	}
	return $out;
}

/**
 * Render a link array as a button.
 */
function smilebliss_button( array $link, string $classes ): string {
	if ( '' === $link['url'] || '' === $link['title'] ) {
		return '';
	}
	return sprintf(
		'<a href="%s" class="%s"%s>%s</a>',
		esc_url( $link['url'] ),
		esc_attr( $classes ),
		$link['target'] ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : '',
		esc_html( $link['title'] )
	);
}

/**
 * Walk the Flexible Content rows for a post and load one partial per layout.
 *
 * Layout names map one-to-one onto template-parts/flexible/{name}.php, so adding
 * a layout in ACF means adding a file of the same name and nothing else.
 */
function smilebliss_render_sections( int|string|null $post_id = null ): void {
	if ( ! function_exists( 'have_rows' ) ) {
		return;
	}
	$post_id = $post_id ?: get_the_ID();

	if ( ! have_rows( 'sections', $post_id ) ) {
		get_template_part( 'template-parts/no-sections' );
		return;
	}

	while ( have_rows( 'sections', $post_id ) ) {
		the_row();
		$layout = get_row_layout();
		if ( ! is_string( $layout ) || '' === $layout ) {
			continue;
		}
		get_template_part( 'template-parts/flexible/' . $layout );
	}
}

/**
 * Section id attribute: the author's anchor, else a stable per-layout default so
 * in-page links such as #contact keep working out of the box.
 */
function smilebliss_section_id( string $fallback ): string {
	$anchor = get_sub_field( 'anchor' );
	$anchor = is_string( $anchor ) ? sanitize_title( $anchor ) : '';
	return $anchor ?: $fallback;
}

/**
 * Allowed tags for inline SVG coming from an editable field.
 */
function smilebliss_svg_kses(): array {
	$attrs = array(
		'viewbox'          => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'class'            => true,
		'width'            => true,
		'height'           => true,
		'aria-hidden'      => true,
		'focusable'        => true,
		'xmlns'            => true,
	);
	return array(
		'svg'      => $attrs,
		'path'     => array_merge( $attrs, array( 'd' => true, 'transform' => true ) ),
		'circle'   => array_merge( $attrs, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
		'rect'     => array_merge( $attrs, array( 'x' => true, 'y' => true, 'rx' => true ) ),
		'line'     => array_merge( $attrs, array( 'x1' => true, 'x2' => true, 'y1' => true, 'y2' => true ) ),
		'polyline' => array_merge( $attrs, array( 'points' => true ) ),
		'g'        => array_merge( $attrs, array( 'transform' => true ) ),
	);
}
