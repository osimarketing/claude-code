<?php
/**
 * Footer: closes <main>, prints the site footer, and the 3D hero module scripts.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$uri = get_template_directory_uri();
?>
	</main>

	<footer class="footer">
		<div class="container footer__inner">
			<div class="footer__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">
					<img class="brand__logo" src="<?php echo esc_url( $uri . '/assets/img/logo.svg' ); ?>" alt="PatientPath" width="167" height="28" />
				</a>
				<p><?php echo esc_html( get_theme_mod( 'patientpath_footer_blurb', 'Healthcare growth & operational support. We help practices grow, save, optimize, and prepare for what\'s next.' ) ); ?></p>
			</div>
			<?php if ( has_nav_menu( 'footer_firm' ) ) : ?>
				<nav class="footer__col" aria-label="Firm"><h4><?php esc_html_e( 'Firm', 'patientpath' ); ?></h4>
					<?php wp_nav_menu( array( 'theme_location' => 'footer_firm', 'items_wrap' => '%3$s', 'container' => false, 'depth' => 1 ) ); ?>
				</nav>
			<?php else : ?>
				<nav class="footer__col" aria-label="Firm">
					<h4><?php esc_html_e( 'Firm', 'patientpath' ); ?></h4>
					<a href="<?php echo esc_url( home_url( '/#what' ) ); ?>"><?php esc_html_e( 'What We Do', 'patientpath' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#problems' ) ); ?>"><?php esc_html_e( 'Problems We Solve', 'patientpath' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#how' ) ); ?>"><?php esc_html_e( 'How We Work', 'patientpath' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#impact' ) ); ?>"><?php esc_html_e( 'Our Impact', 'patientpath' ); ?></a>
				</nav>
			<?php endif; ?>
			<nav class="footer__col" aria-label="Explore">
				<h4><?php esc_html_e( 'Explore', 'patientpath' ); ?></h4>
				<a href="<?php echo esc_url( home_url( '/#industries' ) ); ?>"><?php esc_html_e( 'Industries', 'patientpath' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#work' ) ); ?>"><?php esc_html_e( 'Work', 'patientpath' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#insights' ) ); ?>"><?php esc_html_e( 'Insights', 'patientpath' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'patientpath' ); ?></a>
			</nav>
			<div class="footer__col">
				<h4><?php esc_html_e( 'Get in touch', 'patientpath' ); ?></h4>
				<a href="mailto:<?php echo esc_attr( get_theme_mod( 'patientpath_email', 'hello@yourpatientpath.com' ) ); ?>"><?php echo esc_html( get_theme_mod( 'patientpath_email', 'hello@yourpatientpath.com' ) ); ?></a>
				<a href="tel:<?php echo esc_attr( get_theme_mod( 'patientpath_phone', '+18005551234' ) ); ?>"><?php echo esc_html( get_theme_mod( 'patientpath_phone_display', '(800) 555-1234' ) ); ?></a>
			</div>
		</div>
		<div class="container footer__bar">
			<span>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> PatientPath. <?php esc_html_e( 'All rights reserved.', 'patientpath' ); ?></span>
			<span class="footer__built"><?php esc_html_e( 'Healthcare growth, the consulting way.', 'patientpath' ); ?></span>
		</div>
	</footer>

	<?php wp_footer(); ?>

	<?php if ( is_front_page() ) : ?>
		<?php // ES-module import map + hero module. Kept out of wp_enqueue so it stays a real module; exclude from JS optimization plugins. ?>
		<script type="importmap">
		{ "imports": { "three": "<?php echo esc_url( $uri . '/assets/js/vendor/three.module.js' ); ?>" } }
		</script>
		<script type="module" src="<?php echo esc_url( $uri . '/assets/js/hero-scene.js?ver=' . PATIENTPATH_VERSION ); ?>"></script>
	<?php endif; ?>
</body>
</html>
