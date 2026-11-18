<?php
/**
 * Section: Treatment steps (numbered).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$items = array_filter( (array) ( $s['items'] ?? array() ) );
if ( ! $items ) {
	return;
}
archwire_section_open( 'steps', $s );
?>
	<div class="container">
		<?php archwire_section_head( $s, 'section-head--split' ); ?>
		<ol class="steps" data-count="<?php echo count( $items ); ?>">
			<?php foreach ( array_values( $items ) as $i => $item ) : ?>
				<li class="step">
					<span class="step__num num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
					<h3 class="step__title"><?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
					<?php if ( ! empty( $item['meta'] ) ) : ?>
						<p class="step__meta"><?php echo archwire_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo archwire_text( $item['meta'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $item['text'] ) ) : ?>
						<p class="step__text"><?php echo archwire_text( $item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
