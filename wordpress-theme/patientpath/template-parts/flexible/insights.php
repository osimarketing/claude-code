<?php
/**
 * Flexible layout: Insights (pulls latest blog posts).
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$view_all = get_sub_field( 'view_all' );
$count    = (int) ( get_sub_field( 'count' ) ?: 3 );
$q        = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<section class="insights" id="insights">
	<div class="container">
		<div class="insights__head">
			<div>
				<p class="eyebrow reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
				<h2 class="section-title reveal"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
			</div>
			<?php if ( $view_all ) : ?>
				<a <?php echo patientpath_link_attrs( $view_all ); ?> class="btn btn--ghost btn--dark reveal"><?php echo esc_html( $view_all['title'] ?: 'View all insights' ); ?></a>
			<?php endif; ?>
		</div>
		<div class="posts">
			<?php
			if ( $q->have_posts() ) :
				while ( $q->have_posts() ) :
					$q->the_post();
					$cat = get_the_category();
					?>
					<article class="post reveal"><a class="post__link" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<span class="post__shot"><?php the_post_thumbnail( 'large' ); ?></span>
						<?php endif; ?>
						<span class="post__body">
							<?php if ( $cat ) : ?>
								<span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span>
							<?php endif; ?>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<span class="post__meta"><?php echo esc_html( get_the_date() ); ?> &middot; <?php echo esc_html( patientpath_reading_time() ); ?></span>
						</span>
					</a></article>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>
