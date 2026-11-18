<?php
/**
 * Site header.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$p = archwire_practice();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#FFFFFF">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'archwire' ); ?></a>

<header class="site-header" id="siteHeader">
	<div class="container site-header__inner">
		<?php get_template_part( 'template-parts/brand', null, array( 'eager' => true ) ); ?>

		<nav class="nav" id="siteNav" aria-label="<?php esc_attr_e( 'Primary', 'archwire' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'archwire_nav_fallback',
				)
			);
			?>
			<div class="nav__mobile-actions">
				<?php echo archwire_phone_link( 'btn btn--secondary btn--block' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo archwire_booking_button( 'btn btn--primary btn--block' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</nav>

		<div class="site-header__actions">
			<?php echo archwire_phone_link( 'header-phone', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo archwire_booking_button( 'btn btn--primary btn--sm header-book', __( 'Book a visit', 'archwire' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<button class="nav-toggle" id="navToggle" type="button" aria-expanded="false" aria-controls="siteNav">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'archwire' ); ?></span>
				<?php echo archwire_icon( 'menu', 24, 'nav-toggle__open' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo archwire_icon( 'x', 24, 'nav-toggle__close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>
</header>

<main id="main" class="site-main">
