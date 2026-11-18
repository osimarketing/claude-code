<?php
/**
 * Section: Numbers band. Plain figures with what they count and a source.
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
archwire_section_open( 'stats', $s );
?>
	<div class="container">
		<div class="panel panel--deep stats">
			<dl class="stats__grid" data-count="<?php echo count( $items ); ?>">
				<?php foreach ( $items as $item ) : ?>
					<div class="stat">
						<dt class="stat__label"><?php echo archwire_text( $item['label'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dt>
						<dd class="stat__value num"><?php echo archwire_text( $item['value'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
			<?php if ( ! empty( $s['note'] ) ) : ?>
				<p class="stats__note"><?php echo archwire_text( $s['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
