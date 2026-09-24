<?php
/**
 * Layout: photo left, copy and stat rows right.
 *
 * @package Smilebliss
 */

$sb_chip = (string) get_sub_field( 'chip_text' );
?>
<section class="section about" id="<?php echo esc_attr( smilebliss_section_id( 'about' ) ); ?>">
	<div class="container about__grid">
		<div class="about__visual reveal">
			<div class="about__visual-card">
				<?php
				echo smilebliss_image(
					get_sub_field( 'image' ),
					array(
						'class'    => 'about__photo',
						'sizes'    => '(max-width:900px) calc(100vw - 2.5rem), min(562px, 45vw)',
						'fallback' => 'about-doctor-880.webp',
					)
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
				?>
			</div>
			<?php if ( $sb_chip ) : ?>
				<div class="about__chip">
					<span class="about__chip-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2l3 6 6.5.9-4.7 4.6 1.1 6.5L12 17l-5.9 3 1.1-6.5L2.5 8.9 9 8z"/></svg>
					</span>
					<span><?php echo esc_html( $sb_chip ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<div class="about__copy">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), 'mint' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title reveal">
				<?php
				echo esc_html( (string) get_sub_field( 'title' ) );
				$sb_muted = (string) get_sub_field( 'title_muted' );
				if ( $sb_muted ) {
					echo ' <span class="muted">' . esc_html( $sb_muted ) . '</span>';
				}
				?>
			</h2>
			<?php $sb_body = (string) get_sub_field( 'body' ); ?>
			<?php if ( $sb_body ) : ?>
				<p><?php echo esc_html( $sb_body ); ?></p>
			<?php endif; ?>

			<?php if ( have_rows( 'stats' ) ) : ?>
				<div class="stat-cards">
					<?php
					while ( have_rows( 'stats' ) ) :
						the_row();
						$sb_value  = (string) get_sub_field( 'value' );
						$sb_prefix = (string) get_sub_field( 'prefix' );
						$sb_suffix = (string) get_sub_field( 'suffix' );
						?>
						<div class="stat-card reveal">
							<?php echo smilebliss_spark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?>
							<span
								class="stat-card__num"
								data-count="<?php echo esc_attr( $sb_value ); ?>"
								<?php echo $sb_prefix ? 'data-prefix="' . esc_attr( $sb_prefix ) . '"' : ''; ?>
								<?php echo $sb_suffix ? 'data-suffix="' . esc_attr( $sb_suffix ) . '"' : ''; ?>
							><?php echo esc_html( $sb_prefix . '0' . $sb_suffix ); ?></span>
							<span class="stat-card__label"><?php echo esc_html( (string) get_sub_field( 'label' ) ); ?></span>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
