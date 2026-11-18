<?php
/**
 * Logo link: the uploaded custom logo, or the default mark plus the practice name.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$p     = archwire_practice();
$eager = ! empty( $args['eager'] );
?>
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	<?php
	if ( has_custom_logo() ) {
		echo wp_get_attachment_image(
			(int) get_theme_mod( 'custom_logo' ),
			'full',
			false,
			array(
				'class'   => 'brand__logo',
				'alt'     => $p['name'],
				'loading' => $eager ? 'eager' : 'lazy',
			)
		);
	} else {
		get_template_part( 'template-parts/brand-mark' );
		echo '<span class="brand__name">' . esc_html( $p['name'] ) . '</span>';
	}
	?>
</a>
