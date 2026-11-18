<?php
/**
 * Section: Highlights strip (up to four reasons to choose the practice).
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
$tag = archwire_heading_tag();
archwire_section_open( 'highlights', $s );
?>
	<div class="container">
		<?php if ( ! empty( $s['heading'] ) ) : ?>
			<<?php echo esc_attr( $tag ); ?> class="screen-reader-text"><?php echo esc_html( archwire_plain( $s['heading'] ) ); ?></<?php echo esc_attr( $tag ); ?>>
		<?php endif; ?>
		<ul class="highlights" data-count="<?php echo count( $items ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<li class="highlight">
					<?php echo ! empty( $item['icon'] ) ? archwire_icon( $item['icon'], 24, 'highlight__icon' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<h3 class="highlight__title"><?php echo archwire_text( $item['title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
					<?php if ( ! empty( $item['text'] ) ) : ?>
						<p class="highlight__text"><?php echo archwire_text( $item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
