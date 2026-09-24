<?php
/**
 * Fallback archive/blog template. Kept deliberately plain: this theme exists to
 * serve the composed landing pages.
 *
 * @package Smilebliss
 */

get_header();
?>
<section class="section">
	<div class="container">
		<div class="section-head">
			<h1 class="section-title">
				<?php
				if ( is_home() && ! is_front_page() ) {
					single_post_title();
				} elseif ( is_archive() ) {
					the_archive_title();
				} elseif ( is_search() ) {
					/* translators: %s: search query. */
					printf( esc_html__( 'Results for %s', 'smilebliss' ), '<span class="muted">' . esc_html( get_search_query() ) . '</span>' );
				} else {
					bloginfo( 'name' );
				}
				?>
			</h1>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="pillars__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'pillar' ); ?>>
						<h2 class="pillar__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="pillar__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<a class="pillar__link" href="<?php the_permalink(); ?>">
							<?php esc_html_e( 'Read more', 'smilebliss' ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p class="section-intro"><?php esc_html_e( 'Nothing here yet.', 'smilebliss' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
