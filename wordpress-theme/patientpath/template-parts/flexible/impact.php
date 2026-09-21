<?php
/**
 * Flexible layout: Impact (stats + testimonial carousel).
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$quotes = get_sub_field( 'testimonials' );
?>
<section class="proof" id="impact">
	<div class="container">
		<?php if ( have_rows( 'stats' ) ) : ?>
			<div class="proof__stats">
				<?php while ( have_rows( 'stats' ) ) : the_row(); ?>
					<div class="proof__stat reveal">
						<span class="proof__num"
							data-count="<?php echo esc_attr( get_sub_field( 'number' ) ); ?>"
							<?php if ( get_sub_field( 'prefix' ) ) : ?>data-prefix="<?php echo esc_attr( get_sub_field( 'prefix' ) ); ?>"<?php endif; ?>
							<?php if ( get_sub_field( 'suffix' ) ) : ?>data-suffix="<?php echo esc_attr( get_sub_field( 'suffix' ) ); ?>"<?php endif; ?>
							<?php if ( get_sub_field( 'decimals' ) ) : ?>data-decimals="<?php echo esc_attr( get_sub_field( 'decimals' ) ); ?>"<?php endif; ?>>0</span>
						<span class="proof__label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></span>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $quotes ) ) : ?>
			<div class="proof__carousel reveal">
				<div class="proof__quotes" id="proofQuotes">
					<?php
					while ( have_rows( 'testimonials' ) ) :
						the_row();
						$logo = get_sub_field( 'logo' );
						?>
						<figure class="proof__quote">
							<blockquote><?php echo esc_html( get_sub_field( 'quote' ) ); ?></blockquote>
							<figcaption>
								<?php if ( $logo ) : ?>
									<span class="proof__avatar proof__avatar--logo"><img src="<?php echo esc_url( $logo['url'] ); ?>" alt="" /></span>
								<?php else : ?>
									<span class="proof__avatar" aria-hidden="true"><?php echo esc_html( strtoupper( substr( wp_strip_all_tags( (string) get_sub_field( 'name' ) ), 0, 2 ) ) ); ?></span>
								<?php endif; ?>
								<span><strong><?php echo esc_html( get_sub_field( 'name' ) ); ?></strong><em><?php echo esc_html( get_sub_field( 'org' ) ); ?></em></span>
							</figcaption>
						</figure>
					<?php endwhile; ?>
				</div>
				<?php if ( count( $quotes ) > 1 ) : ?>
					<div class="proof__nav" aria-label="<?php esc_attr_e( 'Testimonial navigation', 'patientpath' ); ?>">
						<button class="proof__arrow" data-dir="-1" type="button" aria-label="<?php esc_attr_e( 'Previous testimonial', 'patientpath' ); ?>"><span class="proof__arrow-i"></span></button>
						<div class="proof__dots">
							<?php foreach ( $quotes as $qi => $q ) : ?>
								<button class="proof__dot<?php echo 0 === $qi ? ' is-active' : ''; ?>" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Show testimonial %d', 'patientpath' ), $qi + 1 ) ); ?>"></button>
							<?php endforeach; ?>
						</div>
						<button class="proof__arrow" data-dir="1" type="button" aria-label="<?php esc_attr_e( 'Next testimonial', 'patientpath' ); ?>"><span class="proof__arrow-i"></span></button>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
