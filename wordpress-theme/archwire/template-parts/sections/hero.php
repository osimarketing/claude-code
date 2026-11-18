<?php
/**
 * Section: Hero.
 *
 * Asymmetric split: copy on the left, a real photo on the right. One
 * primary and one secondary action, nothing stacked under them except the
 * sourced review rating.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s       = $args['s'] ?? array();
$p       = archwire_practice();
$tag     = archwire_heading_tag();
$primary = ! empty( $s['primary']['url'] ) ? archwire_link( $s['primary'], 'btn btn--primary btn--lg' ) : archwire_booking_button( 'btn btn--primary btn--lg' );
$second  = $s['secondary'] ?? 'phone';

archwire_section_open( 'hero', $s );
?>
	<div class="container hero">
		<div class="hero__copy">
			<?php if ( ! empty( $s['label'] ) ) : ?>
				<p class="hero__label"><?php echo archwire_icon( 'map-pin', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo archwire_text( $s['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>

			<<?php echo esc_attr( $tag ); ?> class="hero__title"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>

			<?php if ( ! empty( $s['lead'] ) ) : ?>
				<p class="hero__lead"><?php echo archwire_text( $s['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>

			<div class="hero__actions">
				<?php
				echo $primary; // phpcs:ignore WordPress.Security.EscapeOutput
				if ( 'phone' === $second ) {
					echo archwire_phone_link( 'btn btn--secondary btn--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput
				} elseif ( 'link' === $second ) {
					echo archwire_link( $s['secondary_link'] ?? array(), 'btn btn--secondary btn--lg' ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>

			<?php if ( ! empty( $s['show_rating'] ) && $p['rating'] ) : ?>
				<p class="hero__proof">
					<?php echo archwire_icon( 'star', 16, 'icon--star' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span>
						<?php
						printf(
							/* translators: 1: rating, 2: source, 3: count */
							esc_html__( '%1$s on %2$s from %3$s reviews', 'archwire' ),
							'<strong class="num">' . esc_html( $p['rating'] ) . '</strong>',
							esc_html( $p['review_source'] ),
							'<span class="num">' . esc_html( number_format_i18n( $p['review_count'] ) ) . '</span>'
						);
						?>
					</span>
					<?php if ( $p['reviews_url'] ) : ?>
						<a href="<?php echo esc_url( $p['reviews_url'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read them', 'archwire' ); ?></a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="hero__visual">
			<div class="hero__media<?php echo empty( $s['image'] ) ? ' is-empty' : ''; ?>">
				<?php
				echo archwire_image( // phpcs:ignore WordPress.Security.EscapeOutput
					$s['image'] ?? 0,
					'archwire-portrait',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 960px) 520px, 100vw',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
