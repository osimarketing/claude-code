<?php
/**
 * Section: Patient reviews. The rating summary always names its source
 * and links to it; reviews are quoted with name, detail and source.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$p     = archwire_practice();
$items = array_filter( (array) ( $s['items'] ?? array() ) );

$aside = '';
if ( ! empty( $s['show_summary'] ) && $p['rating'] ) {
	$aside  = '<div class="rating">';
	$aside .= '<p class="rating__value num">' . esc_html( $p['rating'] ) . '</p>';
	$aside .= '<div class="rating__meta"><p class="rating__stars" aria-hidden="true">' . str_repeat( archwire_icon( 'star', 16, 'icon--star' ), 5 ) . '</p>';
	/* translators: 1: count, 2: source */
	$aside .= '<p>' . esc_html( sprintf( __( 'Average of %1$s reviews on %2$s', 'archwire' ), number_format_i18n( $p['review_count'] ), $p['review_source'] ) ) . '</p>';
	if ( $p['reviews_url'] ) {
		$aside .= '<a class="text-link" href="' . esc_url( $p['reviews_url'] ) . '" target="_blank" rel="noopener">' . esc_html__( 'Read all reviews', 'archwire' ) . archwire_icon( 'arrow-right', 16 ) . '</a>';
	}
	$aside .= '</div></div>';
}

if ( ! $items && ! $aside ) {
	return;
}
archwire_section_open( 'testimonials', $s );
?>
	<div class="container">
		<?php archwire_section_head( $s, 'section-head--split', $aside ); ?>
		<?php if ( $items ) : ?>
			<ul class="reviews" data-count="<?php echo count( $items ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<li class="review">
						<figure>
							<blockquote class="review__quote"><p><?php echo archwire_text( $item['quote'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></blockquote>
							<figcaption class="review__by">
								<span class="review__name"><?php echo archwire_text( $item['name'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="review__detail">
									<?php echo archwire_text( implode( ' · ', array_filter( array( $item['detail'] ?? '', $item['source'] ?? '' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</span>
							</figcaption>
						</figure>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
