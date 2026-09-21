<?php
/**
 * Flexible layout: Selected Work.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="work" id="work">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<h2 class="section-title reveal"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
			<?php if ( get_sub_field( 'intro' ) ) : ?>
				<p class="section-intro reveal"><?php echo esc_html( get_sub_field( 'intro' ) ); ?></p>
			<?php endif; ?>
		</div>
		<div class="work__grid">
			<?php
			while ( have_rows( 'cards' ) ) :
				the_row();
				$img    = get_sub_field( 'image' );
				$mobile = get_sub_field( 'mobile_only' ) ? ' work__card--mobile' : '';
				?>
				<article class="work__card<?php echo esc_attr( $mobile ); ?> reveal">
					<?php if ( $img ) : ?>
						<div class="work__shot"><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy" /></div>
					<?php endif; ?>
					<div class="work__body">
						<span class="work__cat"><?php echo esc_html( get_sub_field( 'category' ) ); ?></span>
						<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
						<p><?php echo esc_html( get_sub_field( 'description' ) ); ?></p>
						<?php if ( have_rows( 'tags' ) ) : ?>
							<ul class="work__tags">
								<?php while ( have_rows( 'tags' ) ) : the_row(); ?>
									<li><?php echo esc_html( get_sub_field( 'tag' ) ); ?></li>
								<?php endwhile; ?>
							</ul>
						<?php endif; ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
