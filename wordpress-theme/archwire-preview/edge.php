<?php
// Edge case: no practice settings, sections with every field missing.
error_reporting( E_ALL );
ini_set( 'display_errors', '1' );
define( 'THEME_DIR', realpath( __DIR__ . '/../archwire' ) );
define( 'PREVIEW_TITLE', 'edge' );
require __DIR__ . '/stubs.php';
require THEME_DIR . '/functions.php';
$GLOBALS['__options'] = array();
$GLOBALS['__posts'] = array();
$GLOBALS['__sections'] = array_map( function ( $l ) { return array( 'acf_fc_layout' => $l ); },
	array( 'stats', 'hero', 'highlights', 'treatments', 'consult', 'doctor', 'steps', 'testimonials', 'gallery', 'financing', 'faq', 'location', 'facts', 'posts', 'cta', 'content' ) );
ob_start();
include THEME_DIR . '/front-page.php';
$html = ob_get_clean();
if ( preg_match_all( "/(Warning|Notice|Deprecated|Fatal)[^\n]{0,160}/", $html, $mm ) ) { print_r( $mm[0] ); } else { echo "clean\n"; }
preg_match_all( '/<h1/', $html, $m ); echo 'h1 count: ' . count( $m[0] ) . "\n";
