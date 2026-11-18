<?php
/**
 * Page title band. Prints the page's h1.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$GLOBALS['archwire_h1_done'] = true;
?>
<header class="page-header">
	<div class="container">
		<h1 class="page-header__title"><?php the_title(); ?></h1>
		<?php if ( is_page() && has_excerpt() ) : ?>
			<p class="page-header__lead"><?php echo esc_html( archwire_tokens( get_the_excerpt() ) ); ?></p>
		<?php endif; ?>
	</div>
</header>
