<?php
/**
 * Layout: the five support pillars.
 *
 * @package Smilebliss
 */

$sb_intro = (string) get_sub_field( 'intro' );
?>
<section class="section services" id="<?php echo esc_attr( smilebliss_section_id( 'support' ) ); ?>">
	<div class="container">
		<div class="section-head center">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
			<?php if ( $sb_intro ) : ?>
				<p class="section-intro" style="margin-inline:auto;"><?php echo esc_html( $sb_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( have_rows( 'pillars' ) ) : ?>
			<div class="pillars__grid">
				<?php
				while ( have_rows( 'pillars' ) ) :
					the_row();
					$sb_link = smilebliss_link( get_sub_field( 'link' ) );
					?>
					<article class="pillar reveal">
						<?php
						echo smilebliss_image(
							get_sub_field( 'icon' ),
							array(
								'class' => 'pillar__icon',
								'size'  => 'smilebliss-icon',
								'alt'   => '',
							)
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
						?>
						<h3 class="pillar__title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h3>
						<p class="pillar__text"><?php echo esc_html( (string) get_sub_field( 'text' ) ); ?></p>
						<?php if ( $sb_link['url'] && $sb_link['title'] ) : ?>
							<a href="<?php echo esc_url( $sb_link['url'] ); ?>" class="pillar__link">
								<?php echo esc_html( $sb_link['title'] ); ?>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
							</a>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
