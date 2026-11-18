<?php
/**
 * Site footer: practice details (name, address, phone, hours), menus, profiles.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$p       = archwire_practice();
$summary = archwire_hours_summary();
$labels  = array(
	'google'    => array( 'map-pin', 'Google' ),
	'facebook'  => array( 'facebook', 'Facebook' ),
	'instagram' => array( 'instagram', 'Instagram' ),
	'youtube'   => array( 'youtube', 'YouTube' ),
	'tiktok'    => array( '', 'TikTok' ),
);
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php get_template_part( 'template-parts/brand' ); ?>
			<?php if ( $p['description'] ) : ?>
				<p class="site-footer__desc"><?php echo archwire_text( $p['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<?php if ( $p['social'] ) : ?>
				<ul class="social">
					<?php foreach ( $p['social'] as $key => $url ) : ?>
						<li>
							<a href="<?php echo esc_url( $url ); ?>" rel="noopener" target="_blank">
								<?php echo $labels[ $key ][0] ? archwire_icon( $labels[ $key ][0], 18 ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php echo esc_html( $labels[ $key ][1] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__title"><?php esc_html_e( 'Visit', 'archwire' ); ?></h2>
			<address class="site-footer__address">
				<?php if ( $p['street'] ) : ?>
					<?php echo esc_html( trim( $p['street'] . ( $p['suite'] ? ', ' . $p['suite'] : '' ) ) ); ?><br>
					<?php echo esc_html( trim( $p['city'] . ', ' . $p['state'] . ' ' . $p['zip'] ) ); ?><br>
				<?php endif; ?>
				<?php if ( $p['phone'] ) : ?>
					<a href="tel:<?php echo esc_attr( $p['phone_e164'] ); ?>"><?php echo esc_html( $p['phone'] ); ?></a><br>
				<?php endif; ?>
				<?php if ( $p['email'] ) : ?>
					<a href="mailto:<?php echo esc_attr( antispambot( $p['email'] ) ); ?>"><?php echo esc_html( antispambot( $p['email'] ) ); ?></a>
				<?php endif; ?>
			</address>
			<?php if ( $p['directions'] ) : ?>
				<a class="text-link" href="<?php echo esc_url( $p['directions'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get directions', 'archwire' ); ?><?php echo archwire_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $summary ) : ?>
			<div class="site-footer__col">
				<h2 class="site-footer__title"><?php esc_html_e( 'Hours', 'archwire' ); ?></h2>
				<dl class="hours-mini">
					<?php foreach ( $summary as $g ) : ?>
						<div><dt><?php echo esc_html( $g['days'] ); ?></dt><dd><?php echo esc_html( $g['hours'] ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
				<?php if ( $p['hours_note'] ) : ?>
					<p class="site-footer__note"><?php echo esc_html( $p['hours_note'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Footer', 'archwire' ); ?>">
				<h2 class="site-footer__title"><?php esc_html_e( 'Explore', 'archwire' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>

	<div class="container site-footer__bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) . ' ' . $p['name'] ); ?></p>
		<?php
		if ( has_nav_menu( 'legal' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'legal',
					'container'      => false,
					'menu_class'     => 'legal-menu',
					'depth'          => 1,
				)
			);
		}
		?>
	</div>
</footer>

<?php if ( $p['mobile_bar'] && ( $p['phone'] || $p['booking_url'] ) ) : ?>
	<div class="action-bar" aria-label="<?php esc_attr_e( 'Quick actions', 'archwire' ); ?>">
		<?php echo archwire_phone_link( 'btn btn--secondary', true, __( 'Call', 'archwire' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php echo archwire_booking_button( 'btn btn--primary', __( 'Book a free visit', 'archwire' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
