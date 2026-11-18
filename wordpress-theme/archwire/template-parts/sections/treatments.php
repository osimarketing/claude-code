<?php
/**
 * Section: Treatments (cards linking to treatment pages).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$items = array_filter( (array) ( $s['items'] ?? array() ) );
archwire_section_open( 'treatments', $s );
?>
	<div class="container">
		<?php archwire_section_head( $s, 'section-head--split', archwire_link( $s['link'] ?? array(), 'text-link', 'arrow-right' ) ); ?>
		<?php if ( $items ) : ?>
			<ul class="cards" data-count="<?php echo count( $items ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<li class="card">
						<?php if ( ! empty( $item['image'] ) ) : ?>
							<div class="card__media"><?php echo archwire_image( $item['image'], 'archwire-card', array( 'sizes' => '(min-width: 1100px) 280px, (min-width: 640px) 45vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<?php endif; ?>
						<div class="card__body">
							<h3 class="card__title">
								<?php if ( ! empty( $item['link']['url'] ) ) : ?>
									<a class="card__link" href="<?php echo esc_url( $item['link']['url'] ); ?>"><?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
								<?php else : ?>
									<?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php endif; ?>
							</h3>
							<?php if ( ! empty( $item['text'] ) ) : ?>
								<p class="card__text"><?php echo archwire_text( $item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Learn more', 'archwire' ); ?><?php echo archwire_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
