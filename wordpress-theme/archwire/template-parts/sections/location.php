<?php
/**
 * Section: Location and hours. Address, phone, the week's hours with
 * today marked, a lazy map and the areas the practice serves.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$p     = archwire_practice();
$days  = archwire_days();
$tag   = archwire_heading_tag();
$map   = ! empty( $s['show_map'] ) ? $p['map_embed'] : '';
archwire_section_open( 'location', $s );
?>
	<div class="container location<?php echo $map ? '' : ' location--no-map'; ?>">
		<div class="location__info">
			<?php if ( ! empty( $s['label'] ) ) : ?>
				<p class="label"><?php echo archwire_text( $s['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<<?php echo esc_attr( $tag ); ?> class="h2"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
			<?php if ( ! empty( $s['intro'] ) ) : ?>
				<p class="lead"><?php echo archwire_text( $s['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>

			<ul class="icon-list icon-list--contact">
				<?php if ( $p['street'] ) : ?>
					<li><?php echo archwire_icon( 'map-pin', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo esc_html( $p['name'] ); ?><br><?php echo esc_html( archwire_address_line( $p ) ); ?></span>
					</li>
				<?php endif; ?>
				<?php if ( $p['phone'] ) : ?>
					<li><?php echo archwire_icon( 'phone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<a href="tel:<?php echo esc_attr( $p['phone_e164'] ); ?>"><?php echo esc_html( $p['phone'] ); ?></a>
					</li>
				<?php endif; ?>
				<?php if ( ! empty( $s['directions_text'] ) ) : ?>
					<li><?php echo archwire_icon( 'car', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php echo archwire_text( $s['directions_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</li>
				<?php endif; ?>
			</ul>

			<?php if ( $p['hours'] ) : ?>
				<table class="hours" data-hours-table>
					<caption class="screen-reader-text"><?php esc_html_e( 'Office hours', 'archwire' ); ?></caption>
					<tbody>
						<?php foreach ( $days as $key => $day ) : ?>
							<tr data-day="<?php echo esc_attr( $key ); ?>">
								<th scope="row"><?php echo esc_html( $day ); ?></th>
								<td class="num">
									<?php
									echo isset( $p['hours'][ $key ] )
										? esc_html( archwire_time_display( $p['hours'][ $key ][0] ) . ' – ' . archwire_time_display( $p['hours'][ $key ][1] ) )
										: esc_html__( 'Closed', 'archwire' );
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php if ( $p['hours_note'] ) : ?>
					<p class="fineprint"><?php echo esc_html( $p['hours_note'] ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<div class="location__actions">
				<?php if ( $p['directions'] ) : ?>
					<a class="btn btn--primary" href="<?php echo esc_url( $p['directions'] ); ?>" target="_blank" rel="noopener"><?php echo archwire_icon( 'navigation', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Get directions', 'archwire' ); ?></a>
				<?php endif; ?>
				<?php echo archwire_booking_button( 'btn btn--secondary', __( 'Book a visit', 'archwire' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>

		<?php if ( $map ) : ?>
			<div class="location__map">
				<iframe src="<?php echo esc_url( $map ); ?>" title="<?php echo esc_attr( sprintf( /* translators: %s: practice */ __( 'Map to %s', 'archwire' ), $p['name'] ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $s['show_areas'] ) && $p['areas'] ) : ?>
			<div class="areas">
				<p class="areas__label"><?php echo archwire_text( $s['areas_label'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<ul class="areas__list">
					<?php foreach ( $p['areas'] as $area ) : ?>
						<li><?php echo esc_html( $area ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
