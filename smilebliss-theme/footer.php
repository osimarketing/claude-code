<?php
/**
 * Footer, sticky mobile CTA and closing scripts.
 *
 * @package Smilebliss
 */

$sb_logo_rev  = smilebliss_option( 'logo_reversed' );
$sb_blurb     = smilebliss_option( 'footer_blurb' );
$sb_email     = smilebliss_option( 'contact_email' );
$sb_phone     = smilebliss_option( 'contact_phone' );
$sb_socials   = smilebliss_option( 'social_links', array() );
$sb_sticky    = smilebliss_link( smilebliss_option( 'header_cta' ), __( "Let's Do This", 'smilebliss' ), '#contact' );
$sb_legal     = smilebliss_option( 'footer_legal' );
?>
</main>

<div class="sticky-cta"><?php echo smilebliss_button( $sb_sticky, 'btn btn--coral' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></div>

<footer>
	<div class="container footer__grid">
		<div class="footer__brand">
			<span class="footer__logo-chip">
				<?php
				echo smilebliss_image(
					$sb_logo_rev,
					array(
						'class'    => 'brand__logo brand__logo--footer',
						'alt'      => get_bloginfo( 'name' ),
						'fallback' => 'smilebliss-logo-reverse.webp',
					)
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
				?>
			</span>
			<?php if ( $sb_blurb ) : ?>
				<p><?php echo esc_html( $sb_blurb ); ?></p>
			<?php endif; ?>
			<?php if ( is_array( $sb_socials ) && $sb_socials ) : ?>
				<div class="footer__social">
					<?php foreach ( $sb_socials as $sb_social ) : ?>
						<?php
						$sb_social_url = $sb_social['url'] ?? '';
						if ( ! $sb_social_url ) {
							continue;
						}
						?>
						<a href="<?php echo esc_url( $sb_social_url ); ?>" aria-label="<?php echo esc_attr( $sb_social['label'] ?? '' ); ?>" target="_blank" rel="noopener">
							<?php echo wp_kses( $sb_social['icon'] ?? '', smilebliss_svg_kses() ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $sb_email || $sb_phone ) : ?>
			<div class="footer__col">
				<h4><?php esc_html_e( 'Contact', 'smilebliss' ); ?></h4>
				<?php if ( $sb_email ) : ?>
					<a href="mailto:<?php echo esc_attr( $sb_email ); ?>"><?php echo esc_html( $sb_email ); ?></a>
				<?php endif; ?>
				<?php if ( $sb_phone ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $sb_phone ) ); ?>"><?php echo esc_html( $sb_phone ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<div class="footer__col">
				<h4><?php esc_html_e( 'Company', 'smilebliss' ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>

	<div class="container footer__bottom">
		<span>&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'smilebliss' ); ?></span>
		<?php if ( $sb_legal ) : ?>
			<span class="footer__legal"><?php echo esc_html( $sb_legal ); ?></span>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
