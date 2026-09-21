<?php
/**
 * Flexible layout: Problems We Solve (tabs on desktop / accordion on mobile).
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$i = 0;
?>
<section class="psolve" id="problems">
	<div class="container">
		<div class="section-head">
			<p class="eyebrow eyebrow--light reveal"><span class="eyebrow__num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span> <?php echo esc_html( get_sub_field( 'eyebrow' ) ); ?></p>
			<h2 class="section-title section-title--light reveal"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
			<?php if ( get_sub_field( 'intro' ) ) : ?>
				<p class="section-intro section-intro--light reveal"><?php echo esc_html( get_sub_field( 'intro' ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="psolve__accordion reveal" id="psolveAccordion">
			<?php
			while ( have_rows( 'items' ) ) :
				the_row();
				$active = 0 === $i ? ' is-active' : '';
				$expanded = 0 === $i ? 'true' : 'false';
				?>
				<div class="psolve__item<?php echo esc_attr( $active ); ?>" data-i="<?php echo esc_attr( $i ); ?>">
					<button class="psolve__head" id="ptab-<?php echo esc_attr( $i ); ?>" aria-expanded="<?php echo esc_attr( $expanded ); ?>" aria-controls="ppanel-<?php echo esc_attr( $i ); ?>">
						<span class="psolve__tab-num"><?php echo esc_html( get_sub_field( 'number' ) ); ?></span>
						<span class="psolve__head-label"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
						<span class="psolve__chev" aria-hidden="true"></span>
					</button>
					<div class="psolve__panel" id="ppanel-<?php echo esc_attr( $i ); ?>" role="region" aria-labelledby="ptab-<?php echo esc_attr( $i ); ?>">
						<div class="psolve__panel-inner">
							<p class="psolve__kicker"><?php echo esc_html( get_sub_field( 'kicker' ) ); ?></p>
							<h3 class="psolve__headline"><?php echo esc_html( get_sub_field( 'headline' ) ); ?></h3>
							<p class="psolve__copy"><?php echo esc_html( get_sub_field( 'copy' ) ); ?></p>
							<?php if ( have_rows( 'points' ) ) : ?>
								<ul class="psolve__list">
									<?php while ( have_rows( 'points' ) ) : the_row(); ?>
										<li><?php echo esc_html( get_sub_field( 'point' ) ); ?></li>
									<?php endwhile; ?>
								</ul>
							<?php endif; ?>
							<?php if ( get_sub_field( 'outcome_num' ) ) : ?>
								<div class="psolve__outcome">
									<span class="psolve__outcome-num" data-count="<?php echo esc_attr( get_sub_field( 'outcome_num' ) ); ?>" data-suffix="<?php echo esc_attr( get_sub_field( 'outcome_suffix' ) ); ?>">0</span>
									<span><?php echo esc_html( get_sub_field( 'outcome_label' ) ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
				$i++;
			endwhile;
			?>
		</div>
	</div>
</section>
