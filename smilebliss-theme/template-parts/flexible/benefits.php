<?php
/**
 * Layout: numbered benefits beside a photograph.
 *
 * @package Smilebliss
 */
?>
<section class="section benefits" id="<?php echo esc_attr( smilebliss_section_id( 'benefits' ) ); ?>">
	<div class="container benefits__grid">
		<div class="benefits__copy">
			<div class="section-head">
				<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
				<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
			</div>

			<?php if ( have_rows( 'items' ) ) : ?>
				<div class="benefits__list">
					<?php
					$sb_n = 0;
					while ( have_rows( 'items' ) ) :
						the_row();
						++$sb_n;
						?>
						<div class="benefit reveal">
							<span class="benefit__num"><?php echo esc_html( (string) $sb_n ); ?></span>
							<p class="benefit__text"><?php echo esc_html( (string) get_sub_field( 'text' ) ); ?></p>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="benefits__visual reveal">
			<div class="benefits__visual-card">
				<?php
				echo smilebliss_image(
					get_sub_field( 'image' ),
					array(
						'class'    => 'benefits__photo',
						'sizes'    => '(max-width:900px) calc(100vw - 2.5rem), min(562px, 45vw)',
						'fallback' => 'team-practice-880.webp',
					)
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
				?>
			</div>
		</div>
	</div>
</section>
