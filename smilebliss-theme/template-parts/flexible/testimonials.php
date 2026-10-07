<?php
/**
 * Layout: practice-owner quotes, one at a time.
 *
 * Manually advanced only. No auto-rotation: there is no interval that suits
 * every reading speed, and a quote that moves on its own is hostile to anyone
 * part-way through it.
 *
 * Any row flagged as a sample carries a visible marker, so placeholder copy
 * cannot be mistaken for a real testimonial once the page is live.
 *
 * @package Smilebliss
 */

$sb_intro = (string) get_sub_field( 'intro' );
$sb_score = (float) get_sub_field( 'rating_score' );
$sb_count = (int) get_sub_field( 'rating_count' );
$sb_rid   = wp_unique_id( 'testiRating-' );

$sb_quotes = array();
if ( have_rows( 'items' ) ) {
	while ( have_rows( 'items' ) ) {
		the_row();
		$sb_quotes[] = array(
			'quote'    => (string) get_sub_field( 'quote' ),
			'name'     => (string) get_sub_field( 'name' ),
			'location' => (string) get_sub_field( 'location' ),
			'sample'   => (bool) get_sub_field( 'is_sample' ),
		);
	}
}
$sb_total = count( $sb_quotes );
?>
<section class="section testimonials" id="<?php echo esc_attr( smilebliss_section_id( 'testimonials' ) ); ?>">
	<div class="container">
		<div class="section-head center">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>

			<?php if ( $sb_score > 0 && $sb_count > 0 ) : ?>
				<?php $sb_pct = max( 0, min( 100, $sb_score / 5 * 100 ) ); ?>
				<div class="hero__rating rating--center">
					<svg class="rating__stars" viewBox="0 0 104 20" aria-hidden="true" focusable="false">
						<defs>
							<linearGradient id="<?php echo esc_attr( $sb_rid ); ?>" x1="0" y1="0" x2="1" y2="0">
								<stop class="s-on" offset="<?php echo esc_attr( (string) round( $sb_pct, 2 ) ); ?>%"/>
								<stop class="s-off" offset="<?php echo esc_attr( (string) round( $sb_pct, 2 ) ); ?>%"/>
							</linearGradient>
							<path id="<?php echo esc_attr( $sb_rid ); ?>-star" d="M10 1.6l2.47 5.01 5.53.8-4 3.9.94 5.5L10 14.21l-4.94 2.6.94-5.5-4-3.9 5.53-.8z"/>
						</defs>
						<g fill="url(#<?php echo esc_attr( $sb_rid ); ?>)">
							<?php foreach ( array( 0, 21, 42, 63, 84 ) as $sb_x ) : ?>
								<use href="#<?php echo esc_attr( $sb_rid ); ?>-star" x="<?php echo esc_attr( (string) $sb_x ); ?>"/>
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

			<?php if ( $sb_intro ) : ?>
				<p class="section-intro" style="margin-inline:auto;"><?php echo esc_html( $sb_intro ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $sb_total ) : ?>
			<div class="quotes reveal" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Practice owner testimonials', 'smilebliss' ); ?>">
				<span class="quotes__mark" aria-hidden="true">&ldquo;</span>

				<div class="quotes__viewport" aria-live="polite">
					<?php foreach ( $sb_quotes as $sb_i => $sb_q ) : ?>
						<figure class="quote"<?php echo $sb_i ? ' hidden' : ''; ?>>
							<blockquote class="quote__text">&ldquo;<?php echo esc_html( $sb_q['quote'] ); ?>&rdquo;</blockquote>
							<figcaption class="quote__by">
								&mdash; <?php echo esc_html( $sb_q['name'] ); ?>
								<span class="quote__loc"><?php echo esc_html( $sb_q['location'] ); ?></span>
								<?php if ( $sb_q['sample'] ) : ?>
									<span class="testi-tag"><?php esc_html_e( 'Sample Content', 'smilebliss' ); ?></span>
								<?php endif; ?>
							</figcaption>
						</figure>
					<?php endforeach; ?>
				</div>

				<?php if ( $sb_total > 1 ) : ?>
					<div class="quotes__nav">
						<button type="button" class="quotes__arrow" data-dir="-1" aria-label="<?php esc_attr_e( 'Previous testimonial', 'smilebliss' ); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
						</button>
						<div class="quotes__dots">
							<?php foreach ( $sb_quotes as $sb_i => $sb_q ) : ?>
								<button type="button" class="quotes__dot<?php echo $sb_i ? '' : ' is-on'; ?>" data-go="<?php echo esc_attr( (string) $sb_i ); ?>"
									aria-current="<?php echo $sb_i ? 'false' : 'true'; ?>"
									aria-label="
									<?php
									/* translators: 1: this testimonial's number, 2: how many there are. */
									printf( esc_attr__( 'Testimonial %1$d of %2$d', 'smilebliss' ), (int) $sb_i + 1, (int) $sb_total );
									?>
									"></button>
							<?php endforeach; ?>
						</div>
						<button type="button" class="quotes__arrow" data-dir="1" aria-label="<?php esc_attr_e( 'Next testimonial', 'smilebliss' ); ?>">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
						</button>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
