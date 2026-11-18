<?php
/**
 * Fallback template: blog index, archives and search.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( is_home() && get_option( 'page_for_posts' ) ) {
	$title = get_the_title( (int) get_option( 'page_for_posts' ) );
} elseif ( is_search() ) {
	/* translators: %s: search query */
	$title = sprintf( __( 'Results for "%s"', 'archwire' ), get_search_query() );
} elseif ( is_archive() ) {
	$title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$title = __( 'Articles', 'archwire' );
}
?>
<header class="page-header">
	<div class="container">
		<h1 class="page-header__title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( is_archive() && get_the_archive_description() ) : ?>
			<div class="page-header__lead"><?php echo wp_kses_post( get_the_archive_description() ); ?></div>
		<?php endif; ?>
	</div>
</header>

<div class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/post-card' );
				endwhile;
				?>
			</ul>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( 'Newer', 'archwire' ),
					'next_text' => __( 'Older', 'archwire' ),
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing here yet.', 'archwire' ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
