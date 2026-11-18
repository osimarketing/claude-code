<?php
/**
 * Post card (used by the Latest articles section and archives).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$cats = get_the_category();
?>
<li class="post-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-card__media"><?php the_post_thumbnail( 'archwire-card', array( 'loading' => 'lazy', 'sizes' => '(min-width: 960px) 380px, 100vw' ) ); ?></div>
	<?php endif; ?>
	<p class="post-card__meta">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<?php if ( $cats ) : ?>
			<span>· <?php echo esc_html( $cats[0]->name ); ?></span>
		<?php endif; ?>
	</p>
	<h3 class="post-card__title"><a class="card__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
</li>
