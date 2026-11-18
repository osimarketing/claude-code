<?php
/**
 * Render the Archwire home page with sample practice data.
 *
 * Usage: php render.php <theme-dir> > dist/index.html
 */
define( 'THEME_DIR', realpath( $argv[1] ?? __DIR__ . '/../archwire' ) );
define( 'PREVIEW_TITLE', 'Archwire theme preview' );
require __DIR__ . '/stubs.php';
require THEME_DIR . '/functions.php';

$img = function ( $file, $alt, $w = 720, $h = 540 ) {
	static $id = 100;
	$id++;
	$GLOBALS['__images'][ $id ] = array( 'src' => 'img/' . $file, 'alt' => $alt, 'w' => $w, 'h' => $h );
	return $id;
};

$GLOBALS['__options'] = array(
	'practice_name'        => 'Northline Orthodontics',
	'doctor_name'          => 'Dr. Ana Ruiz',
	'practice_description' => 'Northline Orthodontics is an orthodontic practice in Round Rock, Texas, treating children, teens and adults with braces and Invisalign. First consultations are free.',
	'phone'                => '(512) 555-0142',
	'email'                => 'hello@northline-ortho.test',
	'booking_url'          => '#book',
	'booking_label'        => 'Book a free consultation',
	'street'               => '1840 Hillcrest Avenue',
	'suite'                => 'Suite 210',
	'city'                 => 'Round Rock',
	'state'                => 'TX',
	'zip'                  => '78664',
	'latitude'             => '30.5083',
	'longitude'            => '-97.6789',
	'map_embed_url'        => 'img/map.html',
	'directions_url'       => '#directions',
	'service_areas'        => "Round Rock\nCedar Park\nPflugerville\nGeorgetown\nHutto\nNorth Austin",
	'hours'                => array(
		array( 'days' => array( '1', '2', '3', '4' ), 'open' => '07:30', 'close' => '17:00' ),
		array( 'days' => array( '5' ), 'open' => '07:30', 'close' => '13:00' ),
	),
	'hours_note'           => 'Closed on major holidays',
	'rating'               => '4.9',
	'review_count'         => 312,
	'review_source'        => 'Google',
	'reviews_url'          => '#reviews',
	'treatments'           => "Metal braces\nClear braces\nInvisalign clear aligners\nEarly orthodontic treatment\nRetainers",
	'languages'            => 'English, Spanish',
	'insurance'            => "Delta Dental\nCigna\nAetna\nMetLife\nGuardian\nUnited Concordia",
	'price_range'          => '$$',
	'google_business_url'  => '#google',
	'instagram_url'        => '#instagram',
	'facebook_url'         => '#facebook',
);

$sections = archwire_starter_sections();
foreach ( $sections as &$s ) {
	switch ( $s['acf_fc_layout'] ) {
		case 'hero':
			$s['image'] = $img( 'hero.svg', 'Patient smiling in the treatment chair', 960, 1200 );
			break;
		case 'treatments':
			$files = array( 't-braces.svg', 't-clear.svg', 't-invisalign.svg', 't-early.svg' );
			foreach ( $s['items'] as $i => &$t ) {
				$t['image'] = $img( $files[ $i ], '' );
			}
			unset( $t );
			break;
		case 'doctor':
			$s['image'] = $img( 'doctor.svg', 'Dr. Ana Ruiz in the office', 960, 1200 );
			$s['link']  = array( 'title' => 'More about the team', 'url' => '#team' );
			break;
		case 'testimonials':
			$s['items'] = array(
				array( 'quote' => 'Sample review. They showed us the scan of our daughter\'s bite on a screen and explained every option. The fee in the plan was the fee we paid.', 'name' => 'Parent of a patient', 'detail' => 'Early treatment', 'source' => 'Sample' ),
				array( 'quote' => 'Sample review. I put off aligners for years because of cost. The monthly plan made it work, and the 7:30 appointments meant I never missed work.', 'name' => 'Adult patient', 'detail' => 'Invisalign', 'source' => 'Sample' ),
				array( 'quote' => 'Sample review. Adjustment visits took 15 minutes. My son actually looked forward to picking his band colors.', 'name' => 'Parent of a patient', 'detail' => 'Metal braces', 'source' => 'Sample' ),
			);
			break;
		case 'gallery':
			$s['items'] = array(
				array( 'before' => $img( 'b1.svg', 'Crowded teeth before treatment' ), 'after' => $img( 'a1.svg', 'Aligned teeth after treatment' ), 'title' => 'Invisalign', 'detail' => 'Crowding, 14 months' ),
				array( 'before' => $img( 'b2.svg', 'Gap between front teeth before treatment' ), 'after' => $img( 'a2.svg', 'Gap closed after treatment' ), 'title' => 'Clear braces', 'detail' => 'Spacing, 9 months' ),
				array( 'before' => $img( 'b3.svg', 'Rotated teeth before treatment' ), 'after' => $img( 'a3.svg', 'Straight teeth after treatment' ), 'title' => 'Metal braces', 'detail' => 'Rotations, 20 months' ),
			);
			$s['note']  = 'Illustrations for the preview. Real sites use patient photos shared with permission.';
			break;
		case 'financing':
			$s['button'] = array( 'title' => 'See payment options', 'url' => '#financing' );
			break;
		case 'location':
			$s['directions_text'] = 'Free parking behind the building, with a ramp at the back entrance.';
			break;
	}
}
unset( $s );
$GLOBALS['__sections'] = $sections;

$GLOBALS['__posts'] = array(
	array( 'title' => 'What to eat in the first week of braces', 'date' => 'October 2, 2026', 'cat' => 'Braces', 'img' => $img( 'p1.svg', '' ) ),
	array( 'title' => 'Is your child ready for an orthodontic check at 7?', 'date' => 'September 18, 2026', 'cat' => 'Kids', 'img' => $img( 'p2.svg', '' ) ),
	array( 'title' => 'Invisalign or clear braces: how adults decide', 'date' => 'August 27, 2026', 'cat' => 'Invisalign', 'img' => $img( 'p3.svg', '' ) ),
);

do_action( 'after_setup_theme' );
do_action( 'init' );

ob_start();
include THEME_DIR . '/front-page.php';
$html = ob_get_clean();

$banner = '<div style="background:#0F2730;color:#fff;font:500 14px/20px Figtree,system-ui,sans-serif;padding:10px 20px;text-align:center">Archwire theme preview. Practice, photos, reviews and numbers are sample content.</div>';
echo preg_replace( '/(<body[^>]*>)/', '$1' . $banner, $html, 1 );
