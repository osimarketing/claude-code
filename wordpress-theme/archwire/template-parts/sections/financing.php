<?php
/**
 * Section: Cost and financing.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$p     = archwire_practice();
$items = array_filter( (array) ( $s['items'] ?? array() ) );
$tag   = archwire_heading_tag();
archwire_section_open( 'financing', $s );
?>
	<div class="container financing">
		<div class="financing__copy">
			<?php if ( ! empty( $s['label'] ) ) : ?>
				<p class="label"><?php echo archwire_text( $s['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<<?php echo esc_attr( $tag ); ?> class="h2"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
			<?php if ( ! empty( $s['intro'] ) ) : ?>
				<p class="lead"><?php echo archwire_text( $s['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $s['price'] ) ) : ?>
				<div class="price">
					<p class="price__label"><?php echo archwire_text( $s['price_label'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<p class="price__value num"><?php echo archwire_text( $s['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( '/month', 'archwire' ); ?></span></p>
					<?php if ( ! empty( $s['price_note'] ) ) : ?>
						<p class="price__note"><?php echo archwire_text( $s['price_note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			$button = ! empty( $s['button']['url'] ) ? archwire_link( $s['button'], 'btn btn--primary' ) : '';
			echo $button; // phpcs:ignore WordPress.Security.EscapeOutput
			?>
		</div>

		<div class="financing__side">
			<?php if ( $items ) : ?>
				<ul class="pay-list">
					<?php foreach ( $items as $item ) : ?>
						<li class="pay-item">
							<?php echo ! empty( $item['icon'] ) ? archwire_icon( $item['icon'], 24 ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<h3 class="pay-item__title"><?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
								<p class="pay-item__text"><?php echo archwire_text( $item['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $s['use_insurance'] ) && $p['insurance'] ) : ?>
				<p class="insurers">
					<span class="insurers__label"><?php echo archwire_text( $s['insurance_heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php echo esc_html( implode( ' · ', $p['insurance'] ) ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
