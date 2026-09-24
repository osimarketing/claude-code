<?php
/**
 * Layout: the two routes in.
 *
 * @package Smilebliss
 */
?>
<section class="section" id="<?php echo esc_attr( smilebliss_section_id( 'paths' ) ); ?>" style="background:var(--mint-soft);">
	<div class="container">
		<div class="section-head center">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
		</div>

		<?php if ( have_rows( 'cards' ) ) : ?>
			<div class="paths__grid">
				<?php
				while ( have_rows( 'cards' ) ) :
					the_row();
					$sb_style = 'conv' === (string) get_sub_field( 'style' ) ? 'conv' : 'new';
					$sb_link  = smilebliss_link( get_sub_field( 'link' ) );
					?>
					<div class="path-card path-card--<?php echo esc_attr( $sb_style ); ?> reveal">
						<h3 class="path-card__title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h3>
						<p class="path-card__text"><?php echo esc_html( (string) get_sub_field( 'text' ) ); ?></p>
						<?php echo smilebliss_button( $sb_link, 'btn btn--sm btn--path' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
