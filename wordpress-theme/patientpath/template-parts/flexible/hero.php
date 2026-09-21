<?php
/**
 * Flexible layout: Hero.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img      = get_sub_field( 'hero_image' );
$primary  = get_sub_field( 'cta_primary' );
$secondary = get_sub_field( 'cta_secondary' );
?>
<section class="hero" id="hero">
	<div class="container hero__inner">
		<div class="hero__text">
			<?php if ( get_sub_field( 'eyebrow' ) ) : ?>
				<p class="hero__eyebrow reveal"><?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<?php endif; ?>
			<h1 class="hero__title">
				<span class="line"><span><?php echo esc_html( get_sub_field( 'heading_1' ) ); ?></span></span>
				<span class="line"><span><?php echo esc_html( get_sub_field( 'heading_2' ) ); ?> <em><?php echo esc_html( get_sub_field( 'heading_em' ) ); ?></em></span></span>
			</h1>
			<?php if ( get_sub_field( 'lead' ) ) : ?>
				<p class="hero__lead reveal"><?php echo wp_kses_post( get_sub_field( 'lead' ) ); ?></p>
			<?php endif; ?>
			<div class="hero__actions reveal">
				<?php if ( $primary ) : ?>
					<a <?php echo patientpath_link_attrs( $primary ); ?> class="btn btn--primary"><?php echo esc_html( $primary['title'] ?: 'Contact Sales' ); ?></a>
				<?php endif; ?>
				<?php if ( $secondary ) : ?>
					<a <?php echo patientpath_link_attrs( $secondary ); ?> class="btn btn--ghost"><?php echo esc_html( $secondary['title'] ?: 'How We Work' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $img ) : ?>
			<div class="hero__media reveal">
				<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" width="<?php echo esc_attr( $img['width'] ); ?>" height="<?php echo esc_attr( $img['height'] ); ?>" />
			</div>
		<?php endif; ?>
	</div>

	<?php if ( get_sub_field( 'trust_label' ) || have_rows( 'trust_items' ) ) : ?>
		<div class="container hero__trust reveal">
			<span class="hero__trust-label"><?php echo esc_html( get_sub_field( 'trust_label' ) ); ?></span>
			<ul class="hero__trust-list">
				<?php while ( have_rows( 'trust_items' ) ) : the_row(); ?>
					<li><?php echo esc_html( get_sub_field( 'trust_item' ) ); ?></li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; ?>
</section>
