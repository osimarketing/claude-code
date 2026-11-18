<?php
/**
 * Section: Latest articles.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$s     = $args['s'] ?? array();
$query = new WP_Query(
	array_filter(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => max( 1, min( 6, (int) ( $s['count'] ?? 3 ) ) ),
			'cat'                 => ! empty( $s['category'] ) ? (int) $s['category'] : 0,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	)
);
if ( ! $query->have_posts() ) {
	return;
}
archwire_section_open( 'posts', $s );
?>
	<div class="container">
		<?php archwire_section_head( $s, 'section-head--split', archwire_link( $s['link'] ?? array(), 'text-link', 'arrow-right' ) ); ?>
		<ul class="post-grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
