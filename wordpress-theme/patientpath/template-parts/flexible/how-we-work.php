<?php
/**
 * Flexible layout: How We Work (services).
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="how" id="how">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<h2 class="section-title reveal"><?php echo wp_kses( get_sub_field( 'heading' ), array( 'br' => array() ) ); ?></h2>
			<?php if ( get_sub_field( 'intro' ) ) : ?>
				<p class="section-intro reveal"><?php echo esc_html( get_sub_field( 'intro' ) ); ?></p>
			<?php endif; ?>
		</div>
		<ol class="steps">
			<?php while ( have_rows( 'steps' ) ) : the_row(); ?>
				<li class="step reveal">
					<span class="step__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span>
					<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
					<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
				</li>
			<?php endwhile; ?>
		</ol>
	</div>
</section>
