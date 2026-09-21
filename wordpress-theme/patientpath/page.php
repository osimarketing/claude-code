<?php
/**
 * Generic page. If the page has Page Sections (Flexible Content), render them;
 * otherwise fall back to the standard content.
 *
 * @package PatientPath
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( function_exists( 'have_rows' ) && have_rows( 'page_sections' ) ) {
	while ( have_rows( 'page_sections' ) ) {
		the_row();
		$layout  = get_row_layout();
		$partial = 'template-parts/flexible/' . str_replace( '_', '-', $layout );
		get_template_part( $partial );
	}
} else {
	while ( have_posts() ) {
		the_post();
		?>
		<article class="article">
			<div class="container article__body">
				<h1 class="section-title" style="color:var(--ink);margin-bottom:1.5rem;"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	}
}

get_footer();
