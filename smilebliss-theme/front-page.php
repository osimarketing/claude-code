<?php
/**
 * Front page: nothing but the Flexible Content stack.
 *
 * @package Smilebliss
 */

get_header();

while ( have_posts() ) {
	the_post();
	smilebliss_render_sections();
}

get_footer();
