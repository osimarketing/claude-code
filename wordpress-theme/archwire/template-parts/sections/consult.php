<?php
/**
 * Section: Consultation offer. The main conversion block after the hero.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s        = $args['s'] ?? array();
$includes = array_filter( (array) ( $s['includes'] ?? array() ) );
$tag      = archwire_heading_tag();
$button   = ! empty( $s['button']['url'] ) ? archwire_link( $s['button'], 'btn btn--light btn--lg' ) : archwire_booking_button( 'btn btn--light btn--lg' );
$has_img  = ! empty( $s['image'] );
archwire_section_open( 'consult', $s );
?>
	<div class="container">
		<div class="panel panel--deep consult<?php echo $has_img ? ' consult--media' : ''; ?>">
			<div class="consult__copy">
				<?php if ( ! empty( $s['label'] ) ) : ?>
					<p class="label label--light"><?php echo archwire_text( $s['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
				<<?php echo esc_attr( $tag ); ?> class="h2"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
				<?php if ( ! empty( $s['intro'] ) ) : ?>
					<p class="consult__intro"><?php echo archwire_text( $s['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
				<div class="consult__actions">
					<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php
					if ( ! empty( $s['show_phone'] ) ) {
						echo archwire_phone_link( 'btn btn--ghost-light btn--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
				</div>
			</div>

			<?php if ( $includes ) : ?>
				<div class="consult__list">
					<p class="consult__list-title"><?php esc_html_e( 'Your visit includes', 'archwire' ); ?></p>
					<ul class="checklist">
						<?php foreach ( $includes as $row ) : ?>
							<li><?php echo archwire_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo archwire_text( $row['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $has_img ) : ?>
				<div class="consult__media"><?php echo archwire_image( $s['image'], 'archwire-portrait', array( 'sizes' => '(min-width: 960px) 360px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
