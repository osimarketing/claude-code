<?php
/**
 * Document head, masthead and slide-out nav panel.
 *
 * @package Smilebliss
 */

$sb_logo         = smilebliss_option( 'logo' );
$sb_header_cta   = smilebliss_link( smilebliss_option( 'header_cta' ), __( "Let's Do This", 'smilebliss' ), '#contact' );
$sb_home         = home_url( '/' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#f7f3ef" />
<link rel="profile" href="https://gmpg.org/xfn/11" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text visually-hidden" href="#main"><?php esc_html_e( 'Skip to content', 'smilebliss' ); ?></a>

<header class="site-header" id="siteHeader">
	<div class="container nav">
		<a href="<?php echo esc_url( $sb_home ); ?>" class="brand" aria-label="<?php esc_attr_e( 'Smilebliss home', 'smilebliss' ); ?>">
			<?php
			echo smilebliss_image(
				$sb_logo,
				array(
					'class'    => 'brand__logo',
					'alt'      => get_bloginfo( 'name' ),
					'fallback' => 'smilebliss-logo.webp',
					'loading'  => 'eager',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
			?>
		</a>
		<div class="nav__right">
			<?php echo smilebliss_button( $sb_header_cta, 'btn btn--coral btn--sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<button class="nav__toggle" id="navToggle" aria-label="<?php esc_attr_e( 'Open menu', 'smilebliss' ); ?>" aria-expanded="false" aria-controls="navPanel">
				<span class="nav__toggle-bars"><span></span><span></span><span></span></span>
			</button>
		</div>
	</div>
</header>

<div class="nav__panel" id="navPanel">
	<button class="nav__panel-close" id="navClose" aria-label="<?php esc_attr_e( 'Close menu', 'smilebliss' ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
	</button>
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
	}
	echo smilebliss_button( $sb_header_cta, 'btn btn--coral' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
	?>
</div>

<main id="main">
