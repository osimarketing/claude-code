<?php
/**
 * Any page. Renders its sections, then any classic editor content below them.
 *
 * @package Smilebliss
 */

get_header();

while ( have_posts() ) :
	the_post();

	smilebliss_render_sections();

	$sb_content = trim( (string) get_the_content() );
	if ( '' !== $sb_content ) :
		?>
		<section class="section">
			<div class="container">
				<div class="section-head">
					<h1 class="section-title"><?php the_title(); ?></h1>
				</div>
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
