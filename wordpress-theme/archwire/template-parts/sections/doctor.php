<?php
/**
 * Section: Doctor. Portrait, bio and credentials (adds Person schema).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$creds = array_filter( (array) ( $s['credentials'] ?? array() ) );
$name  = archwire_tokens( $s['name'] ?? '' );
$tag   = archwire_heading_tag();

archwire_doctor_collect(
	array(
		'name'        => $name,
		'job_title'   => archwire_tokens( $s['job_title'] ?? 'Orthodontist' ),
		'image'       => ! empty( $s['image'] ) ? wp_get_attachment_image_url( (int) $s['image'], 'archwire-portrait' ) : '',
		'description' => wp_trim_words( wp_strip_all_tags( archwire_tokens( $s['bio'] ?? '' ) ), 40, '' ),
		'same_as'     => array_values( array_filter( array_map( 'esc_url_raw', preg_split( '/\s+/', (string) ( $s['same_as'] ?? '' ) ) ) ) ),
	)
);

archwire_section_open( 'doctor', $s );
?>
	<div class="container doctor<?php echo empty( $s['image'] ) ? ' doctor--no-media' : ''; ?>">
		<?php if ( ! empty( $s['image'] ) ) : ?>
			<figure class="doctor__media">
				<?php echo archwire_image( $s['image'], 'archwire-portrait', array( 'sizes' => '(min-width: 960px) 480px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( $name ) : ?>
					<figcaption class="doctor__caption"><span><?php echo esc_html( $name ); ?></span><span><?php echo archwire_text( $s['job_title'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="doctor__copy">
			<?php if ( ! empty( $s['label'] ) ) : ?>
				<p class="label"><?php echo archwire_text( $s['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<<?php echo esc_attr( $tag ); ?> class="h2"><?php echo archwire_heading( $s['heading'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></<?php echo esc_attr( $tag ); ?>>
			<?php if ( ! empty( $s['intro'] ) ) : ?>
				<p class="lead"><?php echo archwire_text( $s['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $s['bio'] ) ) : ?>
				<div class="prose"><?php echo wp_kses_post( archwire_tokens( $s['bio'] ) ); ?></div>
			<?php endif; ?>
			<?php if ( $creds ) : ?>
				<ul class="icon-list">
					<?php foreach ( $creds as $c ) : ?>
						<li><?php echo archwire_icon( ! empty( $c['icon'] ) ? $c['icon'] : 'check', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo archwire_text( $c['text'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php echo archwire_link( $s['link'] ?? array(), 'text-link', 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
