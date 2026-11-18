<?php
/**
 * Pages. When a page has sections they render in order; otherwise the
 * normal title and editor content are shown.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	$sections = archwire_get_sections();

	if ( $sections ) {
		// Pages that do not open with a hero still get one h1: the page title.
		if ( 'hero' !== $sections[0]['acf_fc_layout'] ) {
			get_template_part( 'template-parts/page-header' );
		}
		archwire_render_sections( $sections );
	} else {
		get_template_part( 'template-parts/page-header' );
		echo '<div class="section"><div class="container prose prose--narrow">';
		the_content();
		echo '</div></div>';
	}
}

get_footer();
