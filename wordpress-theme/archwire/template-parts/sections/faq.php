<?php
/**
 * Section: FAQ. Native <details>, no script. Feeds FAQPage schema.
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
		return ! empty( $i['question'] ) && ! empty( $i['answer'] );
	}
);
if ( ! $items ) {
	return;
}
archwire_section_open( 'faq', $s );
?>
	<div class="container faq">
		<div class="faq__head">
			<?php archwire_section_head( $s ); ?>
		</div>
		<div class="faq__list">
			<?php foreach ( array_values( $items ) as $i => $item ) : ?>
				<?php
				$q = archwire_plain( $item['question'] );
				$a = archwire_tokens( $item['answer'] );
				if ( ! empty( $s['schema'] ) ) {
					archwire_faq_collect( $q, wp_strip_all_tags( $a ) );
				}
				?>
				<details class="faq__item"<?php echo 0 === $i ? ' open' : ''; ?>>
					<summary>
						<h3 class="faq__q"><?php echo esc_html( $q ); ?></h3>
						<?php echo archwire_icon( 'chevron-down', 20, 'faq__chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</summary>
					<div class="faq__a"><?php echo archwire_paragraphs( $item['answer'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
