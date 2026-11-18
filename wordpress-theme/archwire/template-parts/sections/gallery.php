<?php
/**
 * Section: Before and after. Each case is a compare slider (works with
 * keyboard and touch; shows both photos side by side without JavaScript).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$items = array_values(
	array_filter(
		(array) ( $s['items'] ?? array() ),
		function ( $i ) {
			return ! empty( $i['before'] ) && ! empty( $i['after'] );
		}
	)
);
if ( ! $items ) {
	return;
}
archwire_section_open( 'gallery', $s );
?>
	<div class="container">
		<?php archwire_section_head( $s, 'section-head--split' ); ?>
		<ul class="compare-grid" data-count="<?php echo count( $items ); ?>">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $label = archwire_plain( trim( ( $item['title'] ?? '' ) . ' ' . ( $item['detail'] ?? '' ) ) ); ?>
				<li class="case">
					<div class="compare" data-compare style="--pos:50%">
						<div class="compare__img compare__img--before">
							<?php echo archwire_image( $item['before'], 'archwire-card', array( 'sizes' => '(min-width: 960px) 380px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="compare__tag"><?php esc_html_e( 'Before', 'archwire' ); ?></span>
						</div>
						<div class="compare__img compare__img--after">
							<?php echo archwire_image( $item['after'], 'archwire-card', array( 'sizes' => '(min-width: 960px) 380px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="compare__tag compare__tag--after"><?php esc_html_e( 'After', 'archwire' ); ?></span>
						</div>
						<span class="compare__handle" aria-hidden="true"><?php echo archwire_icon( 'chevrons-left-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<input class="compare__range" type="range" min="0" max="100" value="50" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: case */ __( 'Compare before and after: %s', 'archwire' ), $label ) ); ?>" hidden>
					</div>
					<p class="case__caption">
						<span class="case__title"><?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php if ( ! empty( $item['detail'] ) ) : ?>
							<span class="case__detail"><?php echo archwire_text( $item['detail'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php endif; ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( ! empty( $s['note'] ) ) : ?>
			<p class="fineprint"><?php echo archwire_text( $s['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
		<?php endif; ?>
	</div>
</section>
