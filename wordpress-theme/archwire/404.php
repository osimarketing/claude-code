<?php
/**
 * Not found.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<header class="page-header">
	<div class="container">
		<h1 class="page-header__title"><?php esc_html_e( 'This page moved or never existed', 'archwire' ); ?></h1>
		<p class="page-header__lead"><?php esc_html_e( 'Try the home page, or call and we will point you the right way.', 'archwire' ); ?></p>
		<div class="hero__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'archwire' ); ?></a>
			<?php echo archwire_phone_link( 'btn btn--secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</header>
<?php
get_footer();
