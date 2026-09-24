<?php
/**
 * 404.
 *
 * @package Smilebliss
 */

get_header();
?>
<section class="section">
	<div class="container">
		<div class="section-head center">
			<h1 class="section-title"><?php esc_html_e( 'That page has moved on.', 'smilebliss' ); ?></h1>
			<p class="section-intro" style="margin-inline:auto;"><?php esc_html_e( 'The link may be out of date. Head back to the start and pick up from there.', 'smilebliss' ); ?></p>
			<p style="margin-top:2rem;"><a class="btn btn--coral" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'smilebliss' ); ?></a></p>
		</div>
	</div>
</section>
<?php
get_footer();
