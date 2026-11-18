<?php
/**
 * Archwire theme bootstrap.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ARCHWIRE_VERSION', '1.0.0' );
define( 'ARCHWIRE_DIR', get_template_directory() );
define( 'ARCHWIRE_URI', get_template_directory_uri() );

require ARCHWIRE_DIR . '/inc/icons.php';
require ARCHWIRE_DIR . '/inc/helpers.php';
require ARCHWIRE_DIR . '/inc/practice.php';
require ARCHWIRE_DIR . '/inc/setup.php';
require ARCHWIRE_DIR . '/inc/fields-practice.php';
require ARCHWIRE_DIR . '/inc/fields-sections.php';
require ARCHWIRE_DIR . '/inc/sections.php';
require ARCHWIRE_DIR . '/inc/schema.php';
require ARCHWIRE_DIR . '/inc/seo.php';
require ARCHWIRE_DIR . '/inc/starter-content.php';
