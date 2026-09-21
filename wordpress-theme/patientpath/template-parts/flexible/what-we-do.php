<?php
/**
 * Flexible layout: What We Do (levers).
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="what" id="what">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<h2 class="section-title reveal"><?php echo wp_kses( get_sub_field( 'heading' ), array( 'br' => array() ) ); ?></h2>
			<?php if ( get_sub_field( 'intro' ) ) : ?>
				<p class="section-intro reveal"><?php echo esc_html( get_sub_field( 'intro' ) ); ?></p>
			<?php endif; ?>
		</div>
		<div class="levers">
			<?php while ( have_rows( 'levers' ) ) : the_row(); ?>
				<article class="lever reveal">
					<span class="lever__idx"><?php echo esc_html( get_sub_field( 'index' ) ); ?></span>
					<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
					<p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
