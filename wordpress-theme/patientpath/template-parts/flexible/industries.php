<?php
/**
 * Flexible layout: Industries.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cta = get_sub_field( 'cta' );
?>
<section class="industries" id="industries">
	<div class="container industries__grid">
		<div class="industries__copy">
			<p class="eyebrow reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<h2 class="section-title reveal"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
			<?php if ( get_sub_field( 'intro' ) ) : ?>
				<p class="section-intro reveal"><?php echo esc_html( get_sub_field( 'intro' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $cta ) : ?>
				<a <?php echo patientpath_link_attrs( $cta ); ?> class="btn btn--ghost btn--dark reveal"><?php echo esc_html( $cta['title'] ?: 'Talk to a specialist' ); ?></a>
			<?php endif; ?>
		</div>
		<ul class="industries__list">
			<?php
			while ( have_rows( 'items' ) ) :
				the_row();
				$img = get_sub_field( 'image' );
				?>
				<li class="industries__item reveal">
					<span><?php echo esc_html( get_sub_field( 'name' ) ); ?></span>
					<em><?php echo esc_html( get_sub_field( 'descriptor' ) ); ?></em>
					<?php if ( $img ) : ?>
						<img class="industries__item-img" src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy" />
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>
