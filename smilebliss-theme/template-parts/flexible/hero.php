<?php
/**
 * Layout: Hero.
 *
 * @package Smilebliss
 */

$sb_title   = (string) get_sub_field( 'title' );
$sb_accent  = (string) get_sub_field( 'title_accent' );
$sb_lead    = (string) get_sub_field( 'lead' );
$sb_primary = smilebliss_link( get_sub_field( 'primary_cta' ) );
$sb_second  = smilebliss_link( get_sub_field( 'secondary_cta' ) );
$sb_image   = get_sub_field( 'image' );
$sb_badge_v = (string) get_sub_field( 'badge_value' );
$sb_badge_l = (string) get_sub_field( 'badge_label' );
$sb_score   = (float) get_sub_field( 'rating_score' );
$sb_count   = (int) get_sub_field( 'rating_count' );
$sb_grad_id = wp_unique_id( 'ratingFill-' );
?>
<section class="hero" id="<?php echo esc_attr( smilebliss_section_id( 'top' ) ); ?>">
	<div class="container hero__grid">
		<div class="hero__copy">
			<?php if ( $sb_title || $sb_accent ) : ?>
				<h1 class="hero__title">
					<?php
					echo wp_kses( $sb_title, array( 'br' => array() ) );
					if ( $sb_accent ) {
						echo ' <span class="accent">' . esc_html( $sb_accent ) . '</span>';
					}
					?>
				</h1>
			<?php endif; ?>

			<?php if ( $sb_lead ) : ?>
				<p class="hero__lead"><?php echo esc_html( $sb_lead ); ?></p>
			<?php endif; ?>

			<?php if ( $sb_primary['url'] || $sb_second['url'] ) : ?>
				<div class="hero__actions">
					<?php
					echo smilebliss_button( $sb_primary, 'btn btn--coral' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
					echo smilebliss_button( $sb_second, 'btn btn--ghost-dark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
					?>
				</div>
			<?php endif; ?>

			<?php if ( $sb_score > 0 && $sb_count > 0 ) : ?>
				<?php
				// One source of truth: the stars fill to the same score the label states,
				// and the stars are aria-hidden because the label already says it.
				$sb_pct = max( 0, min( 100, $sb_score / 5 * 100 ) );
				?>
				<div class="hero__rating">
					<svg class="rating__stars" viewBox="0 0 104 20" aria-hidden="true" focusable="false">
						<defs>
							<linearGradient id="<?php echo esc_attr( $sb_grad_id ); ?>" x1="0" y1="0" x2="1" y2="0">
								<stop class="s-on" offset="<?php echo esc_attr( (string) round( $sb_pct, 2 ) ); ?>%"/>
								<stop class="s-off" offset="<?php echo esc_attr( (string) round( $sb_pct, 2 ) ); ?>%"/>
							</linearGradient>
							<path id="<?php echo esc_attr( $sb_grad_id ); ?>-star" d="M10 1.6l2.47 5.01 5.53.8-4 3.9.94 5.5L10 14.21l-4.94 2.6.94-5.5-4-3.9 5.53-.8z"/>
						</defs>
						<g fill="url(#<?php echo esc_attr( $sb_grad_id ); ?>)">
							<?php foreach ( array( 0, 21, 42, 63, 84 ) as $sb_x ) : ?>
								<use href="#<?php echo esc_attr( $sb_grad_id ); ?>-star" x="<?php echo esc_attr( (string) $sb_x ); ?>"/>
							<?php endforeach; ?>
						</g>
					</svg>
					<span class="rating__text">
						<b><?php echo esc_html( number_format_i18n( $sb_score, 1 ) ); ?></b>
						<?php
						/* translators: %s: formatted review count. */
						printf( esc_html__( 'stars from %s reviews', 'smilebliss' ), esc_html( number_format_i18n( $sb_count ) ) );
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>

		<div class="hero__visual">
			<?php
			// The hero image is the LCP element, so it loads eagerly at high priority.
			echo smilebliss_image(
				$sb_image,
				array(
					'class'         => 'hero__photo',
					'sizes'         => '(max-width:980px) min(420px, 92vw), min(559px, 44vw)',
					'fallback'      => 'hero-smile-840.webp',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
			?>

			<?php if ( $sb_badge_v || $sb_badge_l ) : ?>
				<div class="hero__badge">
					<span class="hero__badge-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 12h4l3-9 4 18 3-9h4"/></svg>
					</span>
					<span>
						<strong><?php echo esc_html( $sb_badge_v ); ?></strong>
						<span><?php echo esc_html( $sb_badge_l ); ?></span>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
