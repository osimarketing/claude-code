<?php
/**
 * Starter sections for a new client page.
 *
 * The home page starter leaves out Cost and financing, Location and hours,
 * Key facts and the Numbers band. The first three belong on their own pages
 * (Cost, Contact, About), built from the same section layouts.
 *
 * A "Load starter sections" button appears on any page that has no
 * sections yet. The copy uses tokens ({city}, {practice}, {doctor},
 * {phone}, {areas}) so it reads correctly once Practice Settings are
 * filled in. Review every number and claim with the practice before
 * launch, and paste real reviews and photos: none are included.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Starter home page sections.
 *
 * @return array
 */
function archwire_starter_sections() {
	return array(
		array(
			'acf_fc_layout' => 'hero',
			'label'         => 'Orthodontist in {city}, {state}',
			'heading'       => 'Braces and Invisalign for kids, teens and adults in *{city}*',
			'lead'          => 'Your first visit is free. You get a 3D scan, X-rays and a written plan with the timeline and full cost before you decide anything.',
			'secondary'     => 'phone',
			'show_rating'   => 1,
		),
		array(
			'acf_fc_layout' => 'highlights',
			'heading'       => 'Why families choose {practice}',
			'items'         => array(
				array(
					'icon'  => 'scan-line',
					'title' => '3D scans, no putty',
					'text'  => 'A digital scan replaces the tray of impression material. It takes a few minutes.',
				),
				array(
					'icon'  => 'shield-check',
					'title' => 'Specialist care',
					'text'  => 'Every plan is designed and checked by a licensed orthodontist, not handed off.',
				),
				array(
					'icon'  => 'wallet',
					'title' => 'Monthly plans',
					'text'  => 'Interest-free payments, with insurance and FSA or HSA funds applied first.',
				),
				array(
					'icon'  => 'sun-moon',
					'title' => 'Before and after school',
					'text'  => 'Early and late appointments so school and work come first.',
				),
			),
		),
		array(
			'acf_fc_layout' => 'treatments',
			'label'         => 'Treatments',
			'heading'       => 'Straighten teeth in the way that fits your life',
			'intro'         => 'Every option starts with the same free consultation, so you can compare them side by side.',
			'link'          => array(
				'title' => 'All treatments',
				'url'   => '/treatments/',
			),
			'items'         => array(
				array(
					'title' => 'Metal braces',
					'text'  => 'The most precise option for complex bites. Smaller brackets than you remember.',
					'link'  => array( 'url' => '/treatments/braces/' ),
				),
				array(
					'title' => 'Clear braces',
					'text'  => 'Tooth-colored ceramic brackets that work like metal braces and are hard to spot.',
					'link'  => array( 'url' => '/treatments/clear-braces/' ),
				),
				array(
					'title' => 'Invisalign',
					'text'  => 'Removable clear aligners you switch every one to two weeks. Eat what you like.',
					'link'  => array( 'url' => '/treatments/invisalign/' ),
				),
				array(
					'title' => 'Early treatment',
					'text'  => 'A first check by age 7 finds jaw and spacing issues while they are easier to guide.',
					'link'  => array( 'url' => '/treatments/early-treatment/' ),
				),
			),
		),
		array(
			'acf_fc_layout' => 'doctor',
			'label'         => 'Your orthodontist',
			'heading'       => 'Meet {doctor}',
			'name'          => '{doctor}',
			'job_title'     => 'Orthodontist',
			'bio'           => '<p>After dental school, orthodontists complete two to three more years of residency that focus only on moving teeth and guiding jaw growth. {doctor} brings that training to every plan at {practice}, from a seven-year-old\'s first check to adult aligners.</p><p>You see the same orthodontist at every adjustment, so the person who designed your plan is the one watching it work.</p>',
			'credentials'   => array(
				array(
					'icon' => 'award',
					'text' => 'Certified by the American Board of Orthodontics',
				),
				array(
					'icon' => 'graduation-cap',
					'text' => 'Orthodontic residency and MS in Orthodontics',
				),
				array(
					'icon' => 'users',
					'text' => 'Member, American Association of Orthodontists',
				),
			),
			'background'    => 'paper',
		),
		array(
			'acf_fc_layout' => 'consult',
			'label'         => 'Free first visit',
			'heading'       => 'Your first visit is free, and you leave with a plan',
			'intro'         => 'About an hour with {doctor}. There is no pressure to start the same day.',
			'includes'      => array(
				array( 'text' => 'A 3D scan of your teeth, no impression putty' ),
				array( 'text' => 'Panoramic X-rays taken in the office' ),
				array( 'text' => 'A written treatment plan with your timeline' ),
				array( 'text' => 'Your full fee, insurance estimate and monthly payment' ),
			),
			'show_phone'    => 1,
		),
		array(
			'acf_fc_layout' => 'steps',
			'label'         => 'How treatment works',
			'heading'       => 'Four steps from first visit to final retainer',
			'items'         => array(
				array(
					'title' => 'Free consultation',
					'meta'  => 'About 60 minutes',
					'text'  => 'Scan, X-rays and an exam. You get your options, timeline and cost in writing.',
				),
				array(
					'title' => 'Fitting day',
					'meta'  => '1 to 2 hours',
					'text'  => 'Braces go on or you get your first aligners, with a walkthrough of cleaning and eating.',
				),
				array(
					'title' => 'Check-ins',
					'meta'  => 'Every 6 to 10 weeks',
					'text'  => 'Short visits to adjust wires or pick up your next set of aligners.',
				),
				array(
					'title' => 'Retainers',
					'meta'  => 'After treatment',
					'text'  => 'Custom retainers hold your teeth where they finished.',
				),
			),
		),
		array(
			'acf_fc_layout' => 'testimonials',
			'label'         => 'Patient reviews',
			'heading'       => 'What {city} families say',
			'show_summary'  => 1,
			'items'         => array(),
			'background'    => 'paper',
		),
		array(
			'acf_fc_layout' => 'gallery',
			'label'         => 'Before and after',
			'heading'       => 'Real patients, real results',
			'intro'         => 'Drag the handle to compare. Each case lists the treatment and how long it took.',
			'items'         => array(),
			'note'          => 'Photos of our patients, shared with permission. Results vary.',
		),
		array(
			'acf_fc_layout' => 'faq',
			'label'         => 'Questions',
			'heading'       => 'Questions {city} patients ask us',
			'intro'         => 'Still unsure? Call {phone} and a treatment coordinator will answer.',
			'schema'        => 1,
			'section_id'    => 'faq',
			'items'         => array(
				array(
					'question' => 'How much do braces cost in {city}?',
					'answer'   => 'Most full treatment at {practice} costs between $4,500 and $7,000 before insurance, depending on the type of braces and how long treatment takes. Your free consultation ends with your exact fee and monthly payment in writing.',
				),
				array(
					'question' => 'Do I need a referral from my dentist?',
					'answer'   => 'No. You can book a consultation directly. We send a summary to your dentist afterward so your care stays coordinated, and you keep seeing them for cleanings during treatment.',
				),
				array(
					'question' => 'At what age should my child see an orthodontist?',
					'answer'   => 'By age 7. The American Association of Orthodontists recommends a first check at that age, because jaw growth and incoming adult teeth show problems early. Most children do not need treatment yet, and we monitor them until the timing is right.',
				),
				array(
					'question' => 'Is Invisalign or braces better?',
					'answer'   => 'Both move teeth well. Braces suit complex bite corrections and patients who would rather not manage removable trays. Invisalign suits teens and adults who want something nearly invisible and will wear aligners 20 to 22 hours a day. {doctor} will tell you which fits your bite.',
				),
				array(
					'question' => 'How long does orthodontic treatment take?',
					'answer'   => 'Most treatment takes 12 to 24 months. Minor spacing can close in about six months, and complex bites can take longer. Your plan includes an estimated finish date, and we review progress at every visit.',
				),
				array(
					'question' => 'Do you take my insurance?',
					'answer'   => 'We work with most PPO plans that include an orthodontic benefit and file the claims for you. Call {phone} with your plan details and we will check your coverage before your visit.',
				),
			),
		),
		array(
			'acf_fc_layout' => 'posts',
			'label'         => 'From the blog',
			'heading'       => 'Notes from our team',
			'count'         => 3,
			'link'          => array(
				'title' => 'All articles',
				'url'   => '/blog/',
			),
			'background'    => 'paper',
		),
		array(
			'acf_fc_layout' => 'cta',
			'heading'       => 'Book a free consultation in {city}',
			'text'          => 'It takes about an hour. Bring your insurance card, and any recent X-rays from your dentist if you have them.',
			'show_phone'    => 1,
		),
	);
}

/**
 * Meta box with the "Load starter sections" button.
 */
function archwire_starter_metabox() {
	add_meta_box(
		'archwire-starter',
		__( 'Archwire starter', 'archwire' ),
		function ( $post ) {
			if ( archwire_get_sections( $post->ID ) ) {
				echo '<p>' . esc_html__( 'This page already has sections.', 'archwire' ) . '</p>';
				return;
			}
			$url = wp_nonce_url(
				admin_url( 'admin-post.php?action=archwire_starter&post=' . (int) $post->ID ),
				'archwire_starter_' . $post->ID
			);
			echo '<p>' . esc_html__( 'Fill this page with the starter orthodontic home page. Save the page first.', 'archwire' ) . '</p>';
			echo '<p><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'Load starter sections', 'archwire' ) . '</a></p>';
		},
		'page',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes_page', 'archwire_starter_metabox' );

/**
 * Handle the button.
 */
function archwire_starter_load() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	check_admin_referer( 'archwire_starter_' . $post_id );
	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) || ! function_exists( 'update_field' ) ) {
		wp_die( esc_html__( 'You cannot edit this page.', 'archwire' ) );
	}
	if ( ! archwire_get_sections( $post_id ) ) {
		update_field( 'field_aw_sections', archwire_starter_sections(), $post_id );
	}
	wp_safe_redirect( get_edit_post_link( $post_id, 'url' ) );
	exit;
}
add_action( 'admin_post_archwire_starter', 'archwire_starter_load' );
