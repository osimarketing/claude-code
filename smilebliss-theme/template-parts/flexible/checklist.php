<?php
/**
 * Layout: the self-assessment checklist.
 *
 * Each row is a real button; the progress readout is driven by main.js.
 *
 * @package Smilebliss
 */

$sb_closing = (string) get_sub_field( 'closing' );
$sb_total   = (int) ( function_exists( 'get_sub_field' ) ? count( (array) get_sub_field( 'questions' ) ) : 0 );
?>
<section class="section checklist-section" id="<?php echo esc_attr( smilebliss_section_id( 'checklist-section' ) ); ?>">
	<div class="container">
		<div class="section-head center">
			<h2 class="section-title"><?php echo esc_html( (string) get_sub_field( 'title' ) ); ?></h2>
		</div>

		<div class="checklist">
			<?php
			if ( have_rows( 'questions' ) ) :
				while ( have_rows( 'questions' ) ) :
					the_row();
					?>
					<button type="button" class="check-item reveal" aria-pressed="false">
						<span class="check-box">
							<svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="5 13 10 18 19 7"/></svg>
						</span>
						<span class="check-text"><?php echo esc_html( (string) get_sub_field( 'question' ) ); ?></span>
					</button>
					<?php
				endwhile;
			endif;
			?>

			<?php if ( $sb_total ) : ?>
				<p class="checklist__progress reveal" role="status" aria-live="polite">
					<span class="checklist__bar"><span class="checklist__bar-fill"></span></span>
					<span class="checklist__count">
						<b id="checkCount">0</b>
						<?php
						/* translators: %d: total number of questions. */
						printf( esc_html__( 'of %d answered yes', 'smilebliss' ), (int) $sb_total );
						?>
					</span>
				</p>
			<?php endif; ?>

			<?php if ( $sb_closing ) : ?>
				<p class="checklist__closing reveal"><?php echo esc_html( $sb_closing ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
