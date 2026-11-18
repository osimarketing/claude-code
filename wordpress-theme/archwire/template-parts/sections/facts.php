<?php
/**
 * Section: Key facts. A plain definition list of who, where, what and
 * how much. Easy for people to scan and for AI assistants to quote.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$items = array_filter(
	(array) ( $s['items'] ?? array() ),
	function ( $i ) {
		return ! empty( $i['term'] ) && '' !== trim( archwire_tokens( $i['value'] ?? '' ) );
	}
);
if ( ! $items ) {
	return;
}
archwire_section_open( 'facts', $s );
?>
	<div class="container facts">
		<?php archwire_section_head( $s ); ?>
		<dl class="facts__list">
			<?php foreach ( $items as $item ) : ?>
				<div class="facts__row">
					<dt><?php echo archwire_text( $item['term'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dt>
					<dd><?php echo archwire_text( $item['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
