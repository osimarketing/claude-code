<?php
/**
 * Single article. Byline, dates and a booking prompt at the end.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$p    = archwire_practice();
	$cats = get_the_category();
	?>
	<article <?php post_class( 'article' ); ?>>
		<header class="article__header container prose--narrow">
			<?php if ( $cats ) : ?>
				<p class="label"><a href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a></p>
			<?php endif; ?>
			<h1 class="article__title"><?php the_title(); ?></h1>
			<p class="article__meta">
				<?php
				printf(
					/* translators: 1: author, 2: date */
					esc_html__( 'By %1$s · %2$s', 'archwire' ),
					esc_html( get_the_author() ),
					'<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>'
				);
				if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) {
					echo ' · ';
					printf(
						/* translators: %s: date */
						esc_html__( 'Updated %s', 'archwire' ),
						'<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>'
					);
				}
				?>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="container article__media">
				<?php the_post_thumbnail( 'archwire-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1200px) 1152px, 100vw' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="container prose prose--narrow article__body">
			<?php the_content(); ?>
		</div>

		<?php if ( $p['booking_url'] || $p['phone'] ) : ?>
			<aside class="container prose--narrow">
				<div class="article__cta">
					<p><?php echo esc_html( sprintf( /* translators: 1: practice, 2: city */ __( 'Questions about your own smile? %1$s offers free consultations in %2$s.', 'archwire' ), $p['name'], $p['city'] ) ); ?></p>
					<div class="article__cta-actions">
						<?php echo archwire_booking_button( 'btn btn--primary' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php echo archwire_phone_link( 'btn btn--secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
			</aside>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
