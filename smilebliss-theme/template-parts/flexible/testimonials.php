<?php
/**
 * Layout: practice-owner quotes.
 *
 * Any row flagged as a sample carries a visible marker, so placeholder copy
 * cannot be mistaken for a real testimonial once the page is live.
 *
 * @package Smilebliss
 */

$sb_intro = (string) get_sub_field( 'intro' );
?>
<section class="section testimonials" id="<?php echo esc_attr( smilebliss_section_id( 'testimonials' ) ); ?>">
	<div class="container">
		<div class="section-head center">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
			<?php if ( $sb_intro ) : ?>
				<p class="section-intro" style="margin-inline:auto;"><?php echo esc_html( $sb_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'items' ) ) : ?>
			<div class="testi__grid">
				<?php
				while ( have_rows( 'items' ) ) :
					the_row();
					$sb_sample = (bool) get_sub_field( 'is_sample' );
					?>
					<div class="testi-card reveal">
						<?php echo smilebliss_spark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?>
						<div class="testi-avatar" style="background:#054d66;"><?php echo esc_html( (string) get_sub_field( 'initials' ) ); ?></div>
						<p class="testi-quote">&ldquo;<?php echo esc_html( (string) get_sub_field( 'quote' ) ); ?>&rdquo;</p>
						<p class="testi-name"><?php echo esc_html( (string) get_sub_field( 'name' ) ); ?></p>
						<p class="testi-loc"><?php echo esc_html( (string) get_sub_field( 'location' ) ); ?></p>
						<?php if ( $sb_sample ) : ?>
							<span class="testi-tag"><?php esc_html_e( 'Sample Content', 'smilebliss' ); ?></span>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
