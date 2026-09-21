<?php
/**
 * Header: opens the document, prints the fixed site header / nav.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$uri = get_template_directory_uri();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="theme-color" content="#102a31" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( $uri . '/assets/img/favicon.svg' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<?php // The animated 3D canvas only belongs on the homepage hero. ?>
	<?php if ( is_front_page() ) : ?>
		<canvas id="bg-canvas" aria-hidden="true"></canvas>
	<?php endif; ?>
	<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

	<header class="site-header" id="siteHeader">
		<div class="container nav">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="<?php esc_attr_e( 'PatientPath home', 'patientpath' ); ?>">
				<img class="brand__logo" src="<?php echo esc_url( $uri . '/assets/img/logo.svg' ); ?>" alt="PatientPath" width="197" height="33" />
			</a>

			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => 'nav',
						'container_class' => 'nav__links',
						'container_id'   => 'navLinks',
						'menu_class'     => '',
						'items_wrap'     => '%3$s', // links only, matching the flat markup
						'fallback_cb'    => 'patientpath_nav_fallback',
						'depth'          => 1,
					)
				);
			} else {
				patientpath_nav_fallback();
			}
			?>

			<button class="nav__toggle" id="navToggle" aria-label="<?php esc_attr_e( 'Open menu', 'patientpath' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>
	</header>

	<main id="top">
<?php
/**
 * Fallback nav (used until a Primary menu is assigned in Appearance → Menus).
 * Mirrors the anchors on the current static homepage.
 */
function patientpath_nav_fallback() {
	$home = esc_url( home_url( '/' ) );
	echo '<nav class="nav__links" id="navLinks" aria-label="Primary">';
	echo '<a href="' . $home . '#what">' . esc_html__( 'What We Do', 'patientpath' ) . '</a>';
	echo '<a href="' . $home . '#problems">' . esc_html__( 'Problems We Solve', 'patientpath' ) . '</a>';
	echo '<a href="' . $home . '#industries">' . esc_html__( 'Industries', 'patientpath' ) . '</a>';
	echo '<a href="' . $home . '#work">' . esc_html__( 'Work', 'patientpath' ) . '</a>';
	echo '<a href="' . $home . '#insights">' . esc_html__( 'Insights', 'patientpath' ) . '</a>';
	echo '<a href="' . $home . '#contact" class="nav__cta">' . esc_html__( 'Contact Sales', 'patientpath' ) . '</a>';
	echo '</nav>';
}
