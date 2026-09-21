<?php
/**
 * Blog index / archive fallback — matches the Insights card style.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
	<section class="insights" style="padding-top:9rem;">
		<div class="container">
			<div class="insights__head">
				<div>
					<p class="eyebrow"><span class="eyebrow__num"></span> <?php single_post_title( '', true ); ?><?php if ( is_home() && ! is_front_page() ) { echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ); } ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Insights', 'patientpath' ); ?></h2>
				</div>
			</div>
			<div class="posts">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
						<article class="post"><a class="post__link" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<span class="post__shot"><?php the_post_thumbnail( 'large' ); ?></span>
							<?php endif; ?>
							<span class="post__body">
								<?php $cat = get_the_category(); if ( $cat ) : ?>
									<span class="post__cat"><?php echo esc_html( $cat[0]->name ); ?></span>
								<?php endif; ?>
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
								<span class="post__meta"><?php echo esc_html( get_the_date() ); ?></span>
							</span>
						</a></article>
						<?php
					endwhile;
				else :
					echo '<p>' . esc_html__( 'No posts yet.', 'patientpath' ) . '</p>';
				endif;
				?>
			</div>
			<div style="margin-top:2.5rem;"><?php the_posts_pagination(); ?></div>
		</div>
	</section>
<?php
get_footer();
