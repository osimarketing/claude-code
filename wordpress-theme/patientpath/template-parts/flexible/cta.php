<?php
/**
 * Flexible layout: Call to action (contact).
 *
 * The form comes from a form plugin shortcode (WPForms / Gravity / CF7).
 * If no shortcode is set, a simple static placeholder form is shown.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shortcode = trim( (string) get_sub_field( 'form_shortcode' ) );
?>
<section class="cta" id="contact">
	<div class="container cta__inner">
		<?php if ( get_sub_field( 'eyebrow' ) ) : ?>
			<p class="eyebrow eyebrow--light reveal"><?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
		<?php endif; ?>
		<h2 class="reveal"><?php echo wp_kses( get_sub_field( 'heading' ), array( 'br' => array() ) ); ?></h2>
		<?php if ( get_sub_field( 'lead' ) ) : ?>
			<p class="cta__lead reveal"><?php echo esc_html( get_sub_field( 'lead' ) ); ?></p>
		<?php endif; ?>

		<div class="cta__form reveal">
			<?php
			if ( $shortcode ) {
				echo do_shortcode( $shortcode );
			} else {
				// Placeholder — replace by pasting a form shortcode into the section field.
				?>
				<form class="cta__form" onsubmit="return false;" novalidate>
					<input type="text" name="name" placeholder="<?php esc_attr_e( 'Your name', 'patientpath' ); ?>" aria-label="<?php esc_attr_e( 'Your name', 'patientpath' ); ?>" required />
					<input type="email" name="email" placeholder="<?php esc_attr_e( 'Work email', 'patientpath' ); ?>" aria-label="<?php esc_attr_e( 'Work email', 'patientpath' ); ?>" required />
					<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Contact Sales', 'patientpath' ); ?></button>
				</form>
				<?php
			}
			?>
		</div>
	</div>
</section>
