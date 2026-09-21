<?php
/**
 * Front page: renders the ACF Flexible Content "Page Sections".
 *
 * Each layout maps to a partial in /template-parts/flexible/{layout}.php.
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
		$layout = get_row_layout();
		// Layout names use underscores; partials use hyphens.
		$partial = 'template-parts/flexible/' . str_replace( '_', '-', $layout );
		get_template_part( $partial );
	}
} else {
	// Nothing configured yet — point the editor at the field group.
	if ( current_user_can( 'edit_pages' ) ) {
		echo '<section class="container" style="padding:8rem 0;"><p>Add sections to this page using the <strong>Page Sections</strong> (Flexible Content) field. Requires ACF PRO.</p></section>';
	}
}

get_footer();
