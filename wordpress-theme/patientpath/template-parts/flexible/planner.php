<?php
/**
 * Flexible layout: Planner callout.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$btn = get_sub_field( 'button' );
$img = get_sub_field( 'image' );
?>
<section class="planner" id="planner">
	<div class="container planner__inner reveal">
		<div class="planner__body">
			<div class="planner__text">
				<?php if ( get_sub_field( 'eyebrow' ) ) : ?>
					<p class="eyebrow eyebrow--light"><?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
				<?php endif; ?>
				<h2 class="planner__title"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
				<?php if ( get_sub_field( 'lead' ) ) : ?>
					<p class="planner__lead"><?php echo esc_html( get_sub_field( 'lead' ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $btn ) : ?>
				<div class="planner__action">
					<a <?php echo patientpath_link_attrs( $btn ); ?> class="btn btn--primary"><?php echo esc_html( $btn['title'] ?: 'Download the free planner' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $img ) : ?>
			<div class="planner__media">
				<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy" />
			</div>
		<?php endif; ?>
	</div>
</section>
