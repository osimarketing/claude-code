<?php
/**
 * Small rendering helpers shared by every section.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read an ACF option, with a fallback when ACF is missing or the field is empty.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function archwire_opt( $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	return ( null === $value || '' === $value || false === $value || array() === $value ) ? $default : $value;
}

/**
 * Replace practice tokens like {city} in editor text.
 *
 * Tokens let one set of starter copy work for every client:
 * {practice} {doctor} {city} {state} {phone} {area} {areas}
 *
 * @param string $text Raw text.
 * @return string
 */
function archwire_tokens( $text ) {
	if ( ! is_string( $text ) || false === strpos( $text, '{' ) ) {
		return (string) $text;
	}
	$p     = archwire_practice();
	$areas = $p['areas'];
	$map   = array(
		'{practice}' => $p['name'],
		'{doctor}'   => $p['doctor'],
		'{city}'     => $p['city'],
		'{state}'    => $p['state'],
		'{phone}'    => $p['phone'],
		'{area}'     => $areas ? $areas[0] : $p['city'],
		'{areas}'    => archwire_list_sentence( $areas ),
	);
	return strtr( $text, $map );
}

/**
 * "A, B and C" from a list.
 *
 * @param string[] $items Items.
 * @return string
 */
function archwire_list_sentence( $items ) {
	$items = array_values( array_filter( (array) $items ) );
	if ( count( $items ) < 2 ) {
		return $items ? $items[0] : '';
	}
	$last = array_pop( $items );
	/* translators: 1: comma separated list, 2: last item */
	return sprintf( __( '%1$s and %2$s', 'archwire' ), implode( ', ', $items ), $last );
}

/**
 * Escaped text with tokens replaced.
 *
 * @param string $text Raw text.
 * @return string
 */
function archwire_text( $text ) {
	return esc_html( archwire_tokens( $text ) );
}

/**
 * Escaped multi-line text: tokens replaced, line breaks kept.
 *
 * @param string $text Raw text.
 * @return string
 */
function archwire_paragraphs( $text ) {
	$text = trim( archwire_tokens( (string) $text ) );
	if ( '' === $text ) {
		return '';
	}
	$out = '';
	foreach ( preg_split( "/\n\s*\n/", $text ) as $para ) {
		$out .= '<p>' . nl2br( esc_html( trim( $para ) ) ) . '</p>';
	}
	return $out;
}

/**
 * Heading text. Wrap words in *asterisks* to draw the smile arc under them,
 * the theme's one signature detail. Use it once per page, in the hero or CTA.
 *
 * @param string $text Raw heading.
 * @return string Safe HTML.
 */
function archwire_heading( $text ) {
	$safe = esc_html( archwire_tokens( (string) $text ) );
	return preg_replace_callback(
		'/\*([^*]+)\*/',
		function ( $m ) {
			return '<span class="arc">' . $m[1] . '<svg class="arc__line" viewBox="0 0 200 16" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M3 4 Q100 18 197 4" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" vector-effect="non-scaling-stroke"/></svg></span>';
		},
		$safe
	);
}

/**
 * Strip the *highlight* markers for places that need plain text.
 *
 * @param string $text Raw heading.
 * @return string
 */
function archwire_plain( $text ) {
	return trim( str_replace( '*', '', archwire_tokens( (string) $text ) ) );
}

/**
 * Print a button or text link from an ACF link array.
 *
 * @param array|null $link  ACF link ({url,title,target}).
 * @param string     $class Classes.
 * @param string     $icon  Optional trailing icon name.
 * @return string
 */
function archwire_link( $link, $class = 'btn btn--primary', $icon = '' ) {
	if ( empty( $link['url'] ) ) {
		return '';
	}
	$title  = archwire_tokens( $link['title'] ?? '' );
	$title  = '' !== $title ? $title : __( 'Learn more', 'archwire' );
	$target = ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : '';
	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
		esc_attr( $class ),
		esc_url( archwire_tokens( $link['url'] ) ),
		$target,
		esc_html( $title ),
		$icon ? archwire_icon( $icon, 18 ) : ''
	);
}

/**
 * Booking button that uses the practice booking URL.
 *
 * @param string $class Classes.
 * @param string $label Optional label override.
 * @return string
 */
function archwire_booking_button( $class = 'btn btn--primary', $label = '' ) {
	$p = archwire_practice();
	if ( ! $p['booking_url'] ) {
		return '';
	}
	return archwire_link(
		array(
			'url'   => $p['booking_url'],
			'title' => $label ? $label : $p['booking_label'],
		),
		$class
	);
}

/**
 * Phone button or link.
 *
 * @param string $class Classes.
 * @param bool   $icon  Show phone icon.
 * @param string $label Optional label; defaults to the number.
 * @return string
 */
function archwire_phone_link( $class = 'btn btn--secondary', $icon = true, $label = '' ) {
	$p = archwire_practice();
	if ( ! $p['phone'] ) {
		return '';
	}
	return sprintf(
		'<a class="%1$s" href="tel:%2$s">%3$s<span>%4$s</span></a>',
		esc_attr( $class ),
		esc_attr( $p['phone_e164'] ),
		$icon ? archwire_icon( 'phone', 18 ) : '',
		esc_html( $label ? archwire_tokens( $label ) : $p['phone'] )
	);
}

/**
 * Responsive image from an attachment ID (or ACF image array).
 *
 * @param int|array $image  Attachment ID or array.
 * @param string    $size   Image size.
 * @param array     $attrs  Extra attributes.
 * @return string
 */
function archwire_image( $image, $size = 'large', $attrs = array() ) {
	$id = is_array( $image ) ? (int) ( $image['ID'] ?? $image['id'] ?? 0 ) : (int) $image;
	if ( ! $id ) {
		return '';
	}
	$attrs = array_merge(
		array(
			'loading'  => 'lazy',
			'decoding' => 'async',
		),
		$attrs
	);
	if ( isset( $attrs['loading'] ) && 'eager' === $attrs['loading'] ) {
		unset( $attrs['decoding'] );
	}
	return wp_get_attachment_image( $id, $size, false, $attrs );
}

/**
 * Open a section wrapper with the shared settings (anchor, background).
 *
 * @param string $slug    Layout slug (used for the class).
 * @param array  $s       Section values.
 * @param string $default Default background: white | paper | deep.
 */
function archwire_section_open( $slug, $s, $default = 'white' ) {
	$bg = ! empty( $s['background'] ) && 'auto' !== $s['background'] ? $s['background'] : $default;
	$id = ! empty( $s['section_id'] ) ? sanitize_title( $s['section_id'] ) : '';
	printf(
		'<section class="section section--%1$s bg--%2$s"%3$s>',
		esc_attr( $slug ),
		esc_attr( $bg ),
		$id ? ' id="' . esc_attr( $id ) . '"' : ''
	);
}

/**
 * Standard section heading block: optional label, heading, intro.
 *
 * @param array  $s     Section values (label, heading, intro).
 * @param string $class Extra class on the wrapper.
 * @param string $aside Optional HTML for the right column (link, rating).
 */
function archwire_section_head( $s, $class = '', $aside = '' ) {
	if ( empty( $s['heading'] ) && empty( $s['label'] ) && empty( $s['intro'] ) ) {
		return;
	}
	$tag = archwire_heading_tag();
	echo '<header class="section-head ' . esc_attr( $class ) . '"><div class="section-head__main">';
	if ( ! empty( $s['label'] ) ) {
		echo '<p class="label">' . archwire_text( $s['label'] ) . '</p>';
	}
	if ( ! empty( $s['heading'] ) ) {
		printf( '<%1$s class="h2">%2$s</%1$s>', esc_attr( $tag ), archwire_heading( $s['heading'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</div>';
	if ( ! empty( $s['intro'] ) || $aside ) {
		echo '<div class="section-head__aside">';
		if ( ! empty( $s['intro'] ) ) {
			echo '<div class="section-head__intro">' . archwire_paragraphs( $s['intro'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo $aside; // phpcs:ignore WordPress.Security.EscapeOutput -- built by trusted helpers.
		echo '</div>';
	}
	echo '</header>';
}

/**
 * The first heading on a page is the h1, everything after is h2.
 * Keeps one h1 per page however editors order the sections.
 *
 * @return string
 */
function archwire_heading_tag() {
	if ( empty( $GLOBALS['archwire_h1_done'] ) ) {
		$GLOBALS['archwire_h1_done'] = true;
		return 'h1';
	}
	return 'h2';
}
