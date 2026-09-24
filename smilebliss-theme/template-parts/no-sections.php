<?php
/**
 * Shown when a page has no Flexible Content rows. Visible to editors only, so a
 * half-built page does not leak an instruction to the public.
 *
 * @package Smilebliss
 */

if ( ! current_user_can( 'edit_posts' ) ) {
	return;
}
?>
<section class="section">
	<div class="container">
		<div class="section-head center">
			<h2 class="section-title"><?php esc_html_e( 'No sections yet', 'smilebliss' ); ?></h2>
			<p class="section-intro" style="margin-inline:auto;">
				<?php esc_html_e( 'Edit this page and add rows to the Page Sections field. Only logged-in editors see this message.', 'smilebliss' ); ?>
			</p>
			<?php if ( current_user_can( 'edit_post', get_the_ID() ) ) : ?>
				<p style="margin-top:2rem;"><a class="btn btn--coral" href="<?php echo esc_url( (string) get_edit_post_link() ); ?>"><?php esc_html_e( 'Edit this page', 'smilebliss' ); ?></a></p>
			<?php endif; ?>
		</div>
	</div>
</section>
