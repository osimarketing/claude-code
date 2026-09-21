<?php
/**
 * Flexible layout: Environment photo strip.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="envstrip" aria-label="<?php esc_attr_e( 'Inside the practices we work with', 'patientpath' ); ?>">
	<div class="container">
		<div class="envstrip__grid">
			<?php
			while ( have_rows( 'images' ) ) :
				the_row();
				$img = get_sub_field( 'image' );
				if ( ! $img ) {
					continue;
				}
				?>
				<figure class="reveal"><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy" /></figure>
			<?php endwhile; ?>
		</div>
	</div>
</section>
