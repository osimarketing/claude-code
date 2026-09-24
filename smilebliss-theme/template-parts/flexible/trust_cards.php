<?php
/**
 * Layout: the three tiles under the hero.
 *
 * @package Smilebliss
 */

if ( ! have_rows( 'cards' ) ) {
	return;
}
?>
<section class="trust" id="<?php echo esc_attr( smilebliss_section_id( 'trust' ) ); ?>">
	<div class="container trust__grid">
		<?php
		while ( have_rows( 'cards' ) ) :
			the_row();
			$sb_style = (string) get_sub_field( 'style' );
			$sb_style = in_array( $sb_style, array( 'coral', 'mint', 'ink' ), true ) ? $sb_style : 'coral';
			?>
			<div class="trust-card trust-card--<?php echo esc_attr( $sb_style ); ?> reveal">
				<?php
				echo smilebliss_image(
					get_sub_field( 'icon' ),
					array(
						'class' => 'trust-card__icon',
						'size'  => 'smilebliss-icon',
						'alt'   => '',
					)
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
				?>
				<h3><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h3>
				<p><?php echo esc_html( (string) get_sub_field( 'text' ) ); ?></p>
			</div>
		<?php endwhile; ?>
	</div>
</section>
