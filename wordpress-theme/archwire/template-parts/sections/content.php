<?php
/**
 * Section: Text (rich text for long-form, location or treatment copy).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s = $args['s'] ?? array();
if ( empty( $s['body'] ) && empty( $s['heading'] ) ) {
	return;
}
$tag = archwire_heading_tag();
archwire_section_open( 'content', $s );
?>
	<div class="container prose<?php echo 'wide' === ( $s['width'] ?? 'narrow' ) ? '' : ' prose--narrow'; ?>">
		<?php if ( ! empty( $s['heading'] ) ) : ?>
			<<?php echo esc_attr( $tag ); ?>><?php echo archwire_heading( $s['heading'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
		<?php endif; ?>
		<?php echo wp_kses_post( archwire_tokens( $s['body'] ?? '' ) ); ?>
	</div>
</section>
