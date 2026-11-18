<?php
/**
 * Minimal WordPress + ACF stand-ins so the theme templates can render to
 * static HTML for design review. Not a test of WordPress itself.
 */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['__actions'] = array();
$GLOBALS['__filters'] = array();

function add_action( $h, $cb, $p = 10 ) { $GLOBALS['__actions'][ $h ][ $p ][] = $cb; }
function add_filter( $h, $cb, $p = 10 ) { $GLOBALS['__filters'][ $h ][ $p ][] = $cb; }
function do_action( $h ) {
	$args = array_slice( func_get_args(), 1 );
	if ( empty( $GLOBALS['__actions'][ $h ] ) ) { return; }
	ksort( $GLOBALS['__actions'][ $h ] );
	foreach ( $GLOBALS['__actions'][ $h ] as $cbs ) { foreach ( $cbs as $cb ) { call_user_func_array( $cb, $args ); } }
}
function apply_filters( $h, $v ) {
	$args = array_slice( func_get_args(), 1 );
	if ( empty( $GLOBALS['__filters'][ $h ] ) ) { return $v; }
	ksort( $GLOBALS['__filters'][ $h ] );
	foreach ( $GLOBALS['__filters'][ $h ] as $cbs ) { foreach ( $cbs as $cb ) { $args[0] = call_user_func_array( $cb, $args ); } }
	return $args[0];
}
function remove_action() {}
function add_theme_support() {}
function add_post_type_support() {}
function add_image_size() {}
function register_nav_menus() {}
function load_theme_textdomain() {}
function add_editor_style() {}
function add_meta_box() {}
function add_rewrite_rule() {}
function flush_rewrite_rules() {}
function wp_enqueue_style() {}
function wp_add_inline_style( $h, $css ) { $GLOBALS['__inline_css'] = $css; }
function wp_enqueue_script() {}
function wp_localize_script() {}
function current_user_can() { return false; }

function get_template_directory() { return THEME_DIR; }
function get_template_directory_uri() { return 'theme'; }
function __( $s ) { return $s; }
function _n( $a, $b, $n ) { return 1 === $n ? $a : $b; }
function esc_html__( $s ) { return esc_html( $s ); }
function esc_attr__( $s ) { return esc_attr( $s ); }
function esc_html_e( $s ) { echo esc_html( $s ); }
function esc_attr_e( $s ) { echo esc_attr( $s ); }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8', false ); }
function esc_attr( $s ) { return esc_html( $s ); }
function esc_url( $s ) { return esc_attr( $s ); }
function esc_url_raw( $s ) { return (string) $s; }
function wp_kses_post( $s ) { return (string) $s; }
function wp_kses( $s ) { return (string) $s; }
function sanitize_title( $s ) { return strtolower( preg_replace( '/[^a-z0-9-]+/i', '-', $s ) ); }
function sanitize_key( $s ) { return strtolower( preg_replace( '/[^a-z0-9_\-]/i', '', $s ) ); }
function sanitize_hex_color( $c ) { return preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $c ) ? $c : ''; }
function wp_json_encode( $d, $f = 0 ) { return json_encode( $d, $f ); }
function wp_timezone_string() { return 'America/Chicago'; }
function get_option( $k, $d = false ) { return array( 'time_format' => 'g:i a', 'page_on_front' => 1 )[ $k ] ?? $d; }
function number_format_i18n( $n ) { return number_format( (float) $n ); }
function wp_strip_all_tags( $s ) { return trim( strip_tags( (string) $s ) ); }
function wp_trim_words( $s, $n = 55, $more = '…' ) { $w = preg_split( '/\s+/', trim( $s ) ); return count( $w ) > $n ? implode( ' ', array_slice( $w, 0, $n ) ) . $more : trim( $s ); }
function antispambot( $s ) { return $s; }
function home_url( $p = '/' ) { return 'https://example-ortho.test' . $p; }
function get_bloginfo( $k = '' ) { return 'Practice'; }
function bloginfo( $k ) { echo 'UTF-8'; }
function language_attributes() { echo 'lang="en-US"'; }
function body_class( $c = '' ) { echo 'class="' . esc_attr( implode( ' ', apply_filters( 'body_class', array( 'home', 'page' ) ) ) ) . '"'; }
function wp_body_open() {}
function has_custom_logo() { return false; }
function get_theme_mod() { return 0; }
function has_nav_menu( $l ) { return in_array( $l, array( 'primary', 'footer', 'legal' ), true ); }
function wp_nav_menu( $a ) {
	$menus = array(
		'primary' => array( 'Treatments', 'Invisalign', 'Braces', 'Cost', 'About', 'Contact' ),
		'footer'  => array( 'Treatments', 'Invisalign', 'Braces', 'Early treatment', 'Cost and insurance', 'Blog' ),
		'legal'   => array( 'Privacy', 'Accessibility', 'Notice of privacy practices' ),
	);
	echo '<ul class="' . esc_attr( $a['menu_class'] ) . '">';
	foreach ( $menus[ $a['theme_location'] ] as $m ) { echo '<li><a href="#">' . esc_html( $m ) . '</a></li>'; }
	echo '</ul>';
}
function get_pages() { return array(); }
function get_permalink() { return home_url( '/' ); }
function get_the_ID() { return 1; }
function get_the_title() { return 'Home'; }
function has_excerpt() { return false; }

/* Images: preview files registered by ID. */
function wp_get_attachment_image_url( $id ) { return $GLOBALS['__images'][ $id ]['src'] ?? ''; }
function wp_get_attachment_image( $id, $size = '', $icon = false, $attr = array() ) {
	$img = $GLOBALS['__images'][ $id ] ?? null;
	if ( ! $img ) { return ''; }
	unset( $attr['sizes'] );
	$a = '';
	foreach ( array_merge( array( 'alt' => $img['alt'], 'width' => $img['w'], 'height' => $img['h'] ), $attr ) as $k => $v ) { $a .= ' ' . $k . '="' . esc_attr( $v ) . '"'; }
	return '<img src="' . esc_attr( $img['src'] ) . '"' . $a . '>';
}

/* ACF */
function get_field( $name, $where = null ) {
	if ( 'option' === $where ) { return $GLOBALS['__options'][ $name ] ?? null; }
	if ( 'page_sections' === $name ) { return $GLOBALS['__sections']; }
	return null;
}

/* Template loading */
function get_template_part( $slug, $name = null, $args = array() ) {
	$file = THEME_DIR . '/' . $slug . '.php';
	if ( file_exists( $file ) ) { include $file; }
}
function get_header() { include THEME_DIR . '/header.php'; }
function get_footer() { include THEME_DIR . '/footer.php'; }
function wp_head() {
	do_action( 'wp_head' );
	echo '<link rel="stylesheet" href="theme/assets/css/main.css">' . "\n";
	if ( ! empty( $GLOBALS['__inline_css'] ) ) { echo '<style>' . $GLOBALS['__inline_css'] . '</style>'; }
	echo '<title>' . esc_html( PREVIEW_TITLE ) . '</title>';
}
function wp_footer() {
	do_action( 'wp_footer' );
	echo '<script>window.archwireHours=' . archwire_hours_json() . ';</script>';
	echo '<script src="theme/assets/js/main.js" defer></script>';
}

/* Loop: one page, then posts for the Latest articles section. */
$GLOBALS['__loop'] = 1;
function have_posts() { return $GLOBALS['__loop'] > 0; }
function the_post() { $GLOBALS['__loop']--; }
class WP_Query {
	public $posts; private $i = -1;
	public function __construct( $q ) { $this->posts = array_slice( $GLOBALS['__posts'], 0, $q['posts_per_page'] ?? 3 ); }
	public function have_posts() { return $this->i + 1 < count( $this->posts ); }
	public function the_post() { $this->i++; $GLOBALS['__post'] = $this->posts[ $this->i ]; }
}
function wp_reset_postdata() {}
function the_permalink() { echo '#'; }
function the_title() { echo esc_html( $GLOBALS['__post']['title'] ?? 'Home' ); }
function get_the_date( $f = '' ) { return 'c' === $f ? '2026-10-02' : $GLOBALS['__post']['date']; }
function get_the_category() { return array( (object) array( 'name' => $GLOBALS['__post']['cat'] ) ); }
function has_post_thumbnail() { return ! empty( $GLOBALS['__post']['img'] ); }
function the_post_thumbnail( $s, $a = array() ) { echo wp_get_attachment_image( $GLOBALS['__post']['img'], $s, false, $a ); }
function is_singular() { return true; }
function is_page() { return true; }
function is_front_page() { return true; }
function get_the_excerpt() { return ''; }
function get_edit_post_link() { return ''; }
function get_permalink_stub() {}
