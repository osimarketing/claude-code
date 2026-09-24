<?php
/**
 * Layout: closing CTA and the enquiry form.
 *
 * The field set and the two toggle groups are fixed markup on purpose. They map
 * to the intake process, so they are not editable content; only the surrounding
 * copy and the submit label come from ACF.
 *
 * @package Smilebliss
 */

$sb_intro   = (string) get_sub_field( 'intro' );
$sb_submit  = (string) get_sub_field( 'submit_label' );
$sb_submit  = $sb_submit ?: __( "Let's Do This", 'smilebliss' );
$sb_note    = (string) get_sub_field( 'form_note' );
$sb_success = (string) get_sub_field( 'success_title' );
$sb_succ_p  = (string) get_sub_field( 'success_text' );
?>
<section class="section cta-band" id="<?php echo esc_attr( smilebliss_section_id( 'contact' ) ); ?>">
	<div class="container cta-grid">
		<div class="cta-copy reveal">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), 'light' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title section-title--light"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
			<?php if ( $sb_intro ) : ?>
				<p class="section-intro section-intro--light"><?php echo esc_html( $sb_intro ); ?></p>
			<?php endif; ?>

			<?php if ( have_rows( 'trust_points' ) ) : ?>
				<ul class="cta-trust">
					<?php
					while ( have_rows( 'trust_points' ) ) :
						the_row();
						?>
						<li>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
							<?php echo esc_html( (string) get_sub_field( 'text' ) ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="form-card reveal">
			<form id="joinForm" novalidate
				method="post"
				action="<?php echo esc_url( (string) smilebliss_option( 'form_endpoint', '' ) ); ?>">
				<?php wp_nonce_field( 'smilebliss_enquiry', 'smilebliss_nonce' ); ?>
				<div class="form-grid">
					<div class="field"><label for="fname"><?php esc_html_e( 'First Name', 'smilebliss' ); ?></label><input id="fname" name="fname" type="text" autocomplete="given-name" required></div>
					<div class="field"><label for="lname"><?php esc_html_e( 'Last Name', 'smilebliss' ); ?></label><input id="lname" name="lname" type="text" autocomplete="family-name" required></div>
					<div class="field"><label for="email"><?php esc_html_e( 'Email', 'smilebliss' ); ?></label><input id="email" name="email" type="email" autocomplete="email" required></div>
					<div class="field"><label for="phone"><?php esc_html_e( 'Phone', 'smilebliss' ); ?></label><input id="phone" name="phone" type="tel" autocomplete="tel" required></div>
					<div class="field"><label for="pname"><?php esc_html_e( 'Practice Name', 'smilebliss' ); ?></label><input id="pname" name="pname" type="text" autocomplete="organization"></div>
					<div class="field"><label for="pweb"><?php esc_html_e( 'Website', 'smilebliss' ); ?></label><input id="pweb" name="pweb" type="url" autocomplete="url"></div>

					<div class="field full">
						<label for="source"><?php esc_html_e( 'How did you hear about us?', 'smilebliss' ); ?></label>
						<select id="source" name="source">
							<option value=""><?php esc_html_e( 'Select one', 'smilebliss' ); ?></option>
							<option><?php esc_html_e( 'Google Search', 'smilebliss' ); ?></option>
							<option><?php esc_html_e( 'Social Media', 'smilebliss' ); ?></option>
							<option><?php esc_html_e( 'Referral / Colleague', 'smilebliss' ); ?></option>
							<option><?php esc_html_e( 'Industry Event', 'smilebliss' ); ?></option>
							<option><?php esc_html_e( 'Other', 'smilebliss' ); ?></option>
						</select>
					</div>

					<div class="field full">
						<label><?php esc_html_e( "I'm interested in Smilebliss for:", 'smilebliss' ); ?></label>
						<div class="toggle-group" data-group="interest">
							<label class="toggle-pill active"><input type="radio" name="interest" value="Starting New Practice" checked><?php esc_html_e( 'Starting New Practice', 'smilebliss' ); ?></label>
							<label class="toggle-pill"><input type="radio" name="interest" value="Converting Existing Practice"><?php esc_html_e( 'Converting Existing Practice', 'smilebliss' ); ?></label>
						</div>
					</div>

					<div class="field full">
						<label><?php esc_html_e( 'I am interested in Smilebliss as a:', 'smilebliss' ); ?></label>
						<div class="toggle-group" data-group="role">
							<label class="toggle-pill active"><input type="radio" name="role" value="Practice Owner" checked><?php esc_html_e( 'Practice Owner', 'smilebliss' ); ?></label>
							<label class="toggle-pill"><input type="radio" name="role" value="Practice Employee"><?php esc_html_e( 'Practice Employee', 'smilebliss' ); ?></label>
							<label class="toggle-pill"><input type="radio" name="role" value="Patient"><?php esc_html_e( 'Patient', 'smilebliss' ); ?></label>
						</div>
					</div>
				</div>

				<button type="submit" class="btn btn--coral btn--block"><?php echo esc_html( $sb_submit ); ?></button>
				<?php if ( $sb_note ) : ?>
					<p class="form-note"><?php echo esc_html( $sb_note ); ?></p>
				<?php endif; ?>
			</form>

			<div class="form-success" id="formSuccess">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="8 12 11 15 16 9"/></svg>
				<h3><?php echo esc_html( $sb_success ?: __( "Thanks &mdash; we've got it.", 'smilebliss' ) ); ?></h3>
				<p><?php echo esc_html( $sb_succ_p ?: __( 'A member of the Smilebliss team will be in touch shortly.', 'smilebliss' ) ); ?></p>
			</div>
		</div>
	</div>
</section>
