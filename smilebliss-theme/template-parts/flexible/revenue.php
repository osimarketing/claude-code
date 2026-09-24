<?php
/**
 * Layout: the licence-model proforma.
 *
 * The chart is drawn by assets/js/main.js. The figures travel to it as JSON in a
 * script tag rather than as inline script, so no data is interpolated into
 * executable code and the script stays cacheable.
 *
 * @package Smilebliss
 */

$sb_chart = array(
	'labels'      => array(),
	'base'        => array(),
	'baseStarts'  => array(),
	'best'        => array(),
	'bestStarts'  => array(),
);

if ( have_rows( 'years' ) ) {
	while ( have_rows( 'years' ) ) {
		the_row();
		$sb_chart['labels'][]     = (string) get_sub_field( 'label' );
		$sb_chart['base'][]       = (float) get_sub_field( 'base_revenue' );
		$sb_chart['baseStarts'][] = (int) get_sub_field( 'base_starts' );
		$sb_chart['best'][]       = (float) get_sub_field( 'best_revenue' );
		$sb_chart['bestStarts'][] = (int) get_sub_field( 'best_starts' );
	}
}

$sb_has_data   = (bool) $sb_chart['labels'];
$sb_intro      = (string) get_sub_field( 'intro' );
$sb_disclaimer = (string) get_sub_field( 'disclaimer' );
?>
<section class="section" id="<?php echo esc_attr( smilebliss_section_id( 'revenue' ) ); ?>">
	<div class="container">
		<div class="section-head center">
			<?php echo smilebliss_eyebrow( (string) get_sub_field( 'eyebrow' ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
			<h2 class="section-title">
				<?php
				echo esc_html( (string) get_sub_field( 'title' ) );
				$sb_muted = (string) get_sub_field( 'title_muted' );
				if ( $sb_muted ) {
					echo ' <span class="muted">' . esc_html( $sb_muted ) . '</span>';
				}
				?>
			</h2>
			<?php if ( $sb_intro ) : ?>
				<p class="section-intro" style="margin-inline:auto;"><?php echo esc_html( $sb_intro ); ?></p>
			<?php endif; ?>
		</div>

		<div class="revenue__card reveal">
			<?php if ( $sb_has_data ) : ?>
				<script type="application/json" id="smilebliss-chart-data">
					<?php echo wp_json_encode( $sb_chart ); ?>
				</script>
			<?php endif; ?>

			<div class="revenue__legend">
				<span class="legend-item"><span class="legend-swatch" style="background:#054d66;"></span><?php esc_html_e( 'Base Scenario', 'smilebliss' ); ?></span>
				<span class="legend-item"><span class="legend-swatch" style="background:#ff7d5c;"></span><?php esc_html_e( 'Best Scenario', 'smilebliss' ); ?></span>
			</div>

			<div class="scenario" role="group" aria-label="<?php esc_attr_e( 'Which scenario to show', 'smilebliss' ); ?>">
				<button type="button" class="scenario__btn is-on" data-scenario="both" aria-pressed="true"><?php esc_html_e( 'Both', 'smilebliss' ); ?></button>
				<button type="button" class="scenario__btn" data-scenario="base" aria-pressed="false"><?php esc_html_e( 'Base only', 'smilebliss' ); ?></button>
				<button type="button" class="scenario__btn" data-scenario="best" aria-pressed="false"><?php esc_html_e( 'Best only', 'smilebliss' ); ?></button>
			</div>

			<div class="chart-wrap"><div id="revenueChart"></div></div>

			<?php if ( have_rows( 'meta_pills' ) ) : ?>
				<div class="revenue__meta">
					<?php
					while ( have_rows( 'meta_pills' ) ) :
						the_row();
						?>
						<span class="meta-pill"><?php echo esc_html( (string) get_sub_field( 'text' ) ); ?></span>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>

			<?php if ( $sb_has_data ) : ?>
				<details class="figures">
					<summary><?php esc_html_e( 'Show every figure', 'smilebliss' ); ?></summary>
					<div class="figures__scroll">
						<table class="figures__table">
							<caption class="visually-hidden"><?php esc_html_e( 'Projected revenue and patient starts by year', 'smilebliss' ); ?></caption>
							<thead>
								<tr>
									<th scope="col"><?php esc_html_e( 'Year', 'smilebliss' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Base revenue', 'smilebliss' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Base starts', 'smilebliss' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Best revenue', 'smilebliss' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Best starts', 'smilebliss' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $sb_chart['labels'] as $sb_i => $sb_label ) : ?>
									<tr>
										<th scope="row"><?php echo esc_html( $sb_label ); ?></th>
										<td>$<?php echo esc_html( number_format_i18n( $sb_chart['base'][ $sb_i ] ) ); ?></td>
										<td><?php echo esc_html( number_format_i18n( $sb_chart['baseStarts'][ $sb_i ] ) ); ?></td>
										<td>$<?php echo esc_html( number_format_i18n( $sb_chart['best'][ $sb_i ] ) ); ?></td>
										<td><?php echo esc_html( number_format_i18n( $sb_chart['bestStarts'][ $sb_i ] ) ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</details>
			<?php endif; ?>

			<?php if ( $sb_disclaimer ) : ?>
				<p class="revenue__disclaimer"><?php echo esc_html( $sb_disclaimer ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
