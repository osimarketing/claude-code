<?php
/**
 * Single blog post — mirrors the static blog article layout.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$cat = get_the_category();
	?>
	<section class="article-hero">
		<div class="container article-hero__inner">
			<a href="<?php echo esc_url( home_url( '/#insights' ) ); ?>" class="article-hero__back">&larr; <?php esc_html_e( 'All insights', 'patientpath' ); ?></a>
			<?php if ( $cat ) : ?>
				<p class="article-hero__cat"><?php echo esc_html( $cat[0]->name ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<div class="article-hero__meta">
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<span>&middot;</span>
				<span><?php echo esc_html( patientpath_reading_time() ); ?></span>
			</div>
		</div>
	</section>

	<article class="article">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="container">
				<figure class="article__figure"><?php the_post_thumbnail( 'large' ); ?></figure>
			</div>
		<?php endif; ?>
		<div class="container article__body">
			<?php the_content(); ?>

			<div class="article__cta">
				<p><?php esc_html_e( 'Ready to see what a growth partner can do for your practice?', 'patientpath' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Contact Sales', 'patientpath' ); ?></a>
			</div>

			<?php
			$next = get_next_post();
			$prev = get_previous_post();
			$more = $next ? $next : $prev;
			if ( $more ) :
				?>
				<div class="article__more">
					<span><?php esc_html_e( 'Keep reading', 'patientpath' ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $more ) ); ?>"><?php echo esc_html( get_the_title( $more ) ); ?> &rarr;</a>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
