<?php
/**
 * Section: Call to action (end of page).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s      = $args['s'] ?? array();
$tag    = archwire_heading_tag();
$button = ! empty( $s['button']['url'] ) ? archwire_link( $s['button'], 'btn btn--light btn--lg' ) : archwire_booking_button( 'btn btn--light btn--lg' );
archwire_section_open( 'cta', $s );
?>
	<div class="container">
		<div class="panel panel--deep cta">
			<div class="cta__copy">
				<<?php echo esc_attr( $tag ); ?> class="h2"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
				<?php if ( ! empty( $s['text'] ) ) : ?>
					<p><?php echo archwire_text( $s['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
			<div class="cta__actions">
				<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php
				if ( ! empty( $s['show_phone'] ) ) {
					echo archwire_phone_link( 'btn btn--ghost-light btn--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>
		</div>
	</div>
</section>
