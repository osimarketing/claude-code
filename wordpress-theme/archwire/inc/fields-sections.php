<?php
/**
 * ACF Flexible Content: "Page sections".
 *
 * Available on every page, so the same blocks build the home page,
 * treatment pages and city landing pages. Each layout renders
 * template-parts/sections/{layout}.php.
 *
 * Text fields accept {practice} {doctor} {city} {state} {phone} {areas}
 * tokens. Wrap a word in *asterisks* in a heading to draw the smile arc
 * under it (use once per page).
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		/**
		 * Field builder. Keys are field_aw_{scope}_{name} so they stay unique.
		 */
		$f = function ( $scope, $name, $type, $label, $extra = array() ) {
			return array_merge(
				array(
					'key'   => 'field_aw_' . $scope . '_' . $name,
					'name'  => $name,
					'label' => $label,
					'type'  => $type,
				),
				$extra
			);
		};

		$w = function ( $width ) {
			return array( 'wrapper' => array( 'width' => (string) $width ) );
		};

		$heading_help = 'Tokens: {city} {practice} {doctor} {phone}. Wrap one word in *asterisks* for the smile arc.';

		/** Label + heading + intro used by most sections. */
		$head = function ( $scope, $defaults = array() ) use ( $f, $w, $heading_help ) {
			return array(
				$f( $scope, 'label', 'text', 'Label', array_merge( $w( 30 ), array( 'instructions' => 'Short, sentence case. Optional.', 'default_value' => $defaults['label'] ?? '' ) ) ),
				$f( $scope, 'heading', 'text', 'Heading', array_merge( $w( 70 ), array( 'instructions' => $heading_help, 'default_value' => $defaults['heading'] ?? '' ) ) ),
				$f( $scope, 'intro', 'textarea', 'Intro', array( 'rows' => 2, 'default_value' => $defaults['intro'] ?? '' ) ),
			);
		};

		/** Settings tab shared by every layout. */
		$settings = function ( $scope, $default_bg = 'auto' ) use ( $f, $w ) {
			return array(
				$f( $scope, 'tab_settings', 'tab', 'Settings' ),
				$f( $scope, 'section_id', 'text', 'Anchor ID', array_merge( $w( 40 ), array( 'instructions' => 'For menu links like /#faq. Lowercase, no spaces.' ) ) ),
				$f(
					$scope,
					'background',
					'select',
					'Background',
					array_merge(
						$w( 40 ),
						array(
							'choices'       => array(
								'auto'  => 'Default',
								'white' => 'White',
								'paper' => 'Tint',
								'deep'  => 'Dark',
							),
							'default_value' => $default_bg,
						)
					)
				),
				$f( $scope, 'hide', 'true_false', 'Hide section', array_merge( $w( 20 ), array( 'ui' => 1 ) ) ),
			);
		};

		$content_tab = function ( $scope ) use ( $f ) {
			return $f( $scope, 'tab_content', 'tab', 'Content' );
		};

		$icon = function ( $scope ) use ( $f, $w ) {
			return $f(
				$scope,
				'icon',
				'select',
				'Icon',
				array_merge(
					$w( 25 ),
					array(
						'choices'    => function_exists( 'archwire_icon_choices' ) ? archwire_icon_choices() : array(),
						'allow_null' => 1,
					)
				)
			);
		};

		$image = function ( $scope, $name, $label, $extra = array() ) use ( $f ) {
			return $f(
				$scope,
				$name,
				'image',
				$label,
				array_merge(
					array(
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'library'       => 'all',
						'mime_types'    => 'jpg,jpeg,png,webp,avif',
					),
					$extra
				)
			);
		};

		$repeater = function ( $scope, $name, $label, $sub_fields, $extra = array() ) use ( $f ) {
			// Sub field keys get the repeater name so they never collide with
			// a top-level field of the same name in the layout.
			foreach ( $sub_fields as $i => $sub ) {
				$sub_fields[ $i ]['key'] = 'field_aw_' . $scope . '_' . $name . '_' . $sub['name'];
			}
			return $f(
				$scope,
				$name,
				'repeater',
				$label,
				array_merge(
					array(
						'layout'       => 'block',
						'button_label' => 'Add',
						'sub_fields'   => $sub_fields,
					),
					$extra
				)
			);
		};

		$layout = function ( $name, $label, $fields ) {
			return array(
				'key'        => 'layout_aw_' . $name,
				'name'       => $name,
				'label'      => $label,
				'display'    => 'block',
				'sub_fields' => $fields,
			);
		};

		$layouts = array();

		/* Hero ------------------------------------------------------------ */
		$s         = 'hero';
		$layouts[] = $layout(
			'hero',
			'Hero',
			array_merge(
				array(
					$content_tab( $s ),
					$f( $s, 'label', 'text', 'Location line', array( 'default_value' => 'Orthodontist in {city}, {state}', 'instructions' => 'Shown above the heading with a map pin.' ) ),
					$f( $s, 'heading', 'text', 'Heading (h1 when first on the page)', array( 'instructions' => $heading_help, 'default_value' => 'Braces and Invisalign for kids, teens and adults in *{city}*' ) ),
					$f( $s, 'lead', 'textarea', 'Lead', array( 'rows' => 3 ) ),
					$f( $s, 'primary', 'link', 'Primary button', array_merge( $w( 50 ), array( 'instructions' => 'Leave empty to use the booking URL from Practice Settings.' ) ) ),
					$f(
						$s,
						'secondary',
						'select',
						'Secondary button',
						array_merge(
							$w( 50 ),
							array(
								'choices'       => array(
									'phone' => 'Call the practice',
									'link'  => 'Custom link',
									'none'  => 'None',
								),
								'default_value' => 'phone',
							)
						)
					),
					$f(
						$s,
						'secondary_link',
						'link',
						'Secondary link',
						array(
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_aw_hero_secondary',
										'operator' => '==',
										'value'    => 'link',
									),
								),
							),
						)
					),
					$image( $s, 'image', 'Photo (portrait, at least 960×1200)', array( 'instructions' => 'A real patient or your team in the office. Loaded first, so keep it under 250 KB.' ) ),
					$f( $s, 'show_rating', 'true_false', 'Show review rating', array_merge( $w( 100 ), array( 'ui' => 1, 'default_value' => 1, 'instructions' => 'Uses Practice Settings → Reviews, with a link to the source.' ) ) ),
				),
				$settings( $s )
			)
		);

		/* Highlights ------------------------------------------------------ */
		$s         = 'highlights';
		$layouts[] = $layout(
			'highlights',
			'Highlights strip',
			array_merge(
				array(
					$content_tab( $s ),
					$f( $s, 'heading', 'text', 'Heading (optional, visually hidden if empty)' ),
					$repeater(
						$s,
						'items',
						'Items',
						array(
							$icon( $s ),
							$f( $s, 'title', 'text', 'Title', $w( 75 ) ),
							$f( $s, 'text', 'textarea', 'Text', array( 'rows' => 2 ) ),
						),
						array(
							'max'    => 4,
							'layout' => 'table',
						)
					),
				),
				$settings( $s )
			)
		);

		/* Treatments ------------------------------------------------------ */
		$s         = 'treatments';
		$layouts[] = $layout(
			'treatments',
			'Treatments',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f( $s, 'link', 'link', 'Link next to the heading' ),
					$repeater(
						$s,
						'items',
						'Treatments',
						array(
							$image( $s, 'image', 'Image (4:3)', $w( 30 ) ),
							$f( $s, 'title', 'text', 'Title', $w( 35 ) ),
							$f( $s, 'link', 'link', 'Link to treatment page', $w( 35 ) ),
							$f( $s, 'text', 'textarea', 'Text', array( 'rows' => 2 ) ),
						)
					),
				),
				$settings( $s )
			)
		);

		/* Stats ----------------------------------------------------------- */
		$s         = 'stats';
		$layouts[] = $layout(
			'stats',
			'Numbers band',
			array_merge(
				array(
					$content_tab( $s ),
					$repeater(
						$s,
						'items',
						'Numbers',
						array(
							$f( $s, 'value', 'text', 'Number', array_merge( $w( 30 ), array( 'placeholder' => '4,200' ) ) ),
							$f( $s, 'label', 'text', 'Label', $w( 70 ) ),
						),
						array(
							'max'          => 4,
							'layout'       => 'table',
							'instructions' => 'Only numbers you can back up. Each label says what is counted.',
						)
					),
					$f( $s, 'note', 'text', 'Source note', array( 'placeholder' => 'Practice records, January 2026' ) ),
				),
				$settings( $s, 'auto' )
			)
		);

		/* Consultation offer ---------------------------------------------- */
		$s         = 'consult';
		$layouts[] = $layout(
			'consult',
			'Consultation offer',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$repeater(
						$s,
						'includes',
						'What the visit includes',
						array( $f( $s, 'text', 'text', 'Item' ) ),
						array( 'layout' => 'table' )
					),
					$f( $s, 'button', 'link', 'Button', array_merge( $w( 50 ), array( 'instructions' => 'Empty = booking URL.' ) ) ),
					$f( $s, 'show_phone', 'true_false', 'Show phone next to the button', array_merge( $w( 50 ), array( 'ui' => 1, 'default_value' => 1 ) ) ),
					$image( $s, 'image', 'Photo (portrait or square)' ),
				),
				$settings( $s )
			)
		);

		/* Doctor ---------------------------------------------------------- */
		$s         = 'doctor';
		$layouts[] = $layout(
			'doctor',
			'Doctor',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f( $s, 'name', 'text', 'Name', $w( 50 ) ),
					$f( $s, 'job_title', 'text', 'Title', array_merge( $w( 50 ), array( 'default_value' => 'Orthodontist' ) ) ),
					$image( $s, 'image', 'Portrait (4:5)' ),
					$f(
						$s,
						'bio',
						'wysiwyg',
						'Bio',
						array(
							'toolbar'      => 'basic',
							'media_upload' => 0,
						)
					),
					$repeater(
						$s,
						'credentials',
						'Credentials',
						array(
							$icon( $s ),
							$f( $s, 'text', 'text', 'Credential', array_merge( $w( 75 ), array( 'placeholder' => 'Board certified, American Board of Orthodontics' ) ) ),
						),
						array( 'layout' => 'table' )
					),
					$f( $s, 'link', 'link', 'Link' ),
					$f( $s, 'same_as', 'textarea', 'Profile links for schema', array( 'rows' => 2, 'instructions' => 'One URL per line, e.g. ABO directory, AAO find-an-orthodontist, LinkedIn.' ) ),
				),
				$settings( $s )
			)
		);

		/* Steps ----------------------------------------------------------- */
		$s         = 'steps';
		$layouts[] = $layout(
			'steps',
			'Treatment steps',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$repeater(
						$s,
						'items',
						'Steps',
						array(
							$f( $s, 'title', 'text', 'Title', $w( 40 ) ),
							$f( $s, 'meta', 'text', 'Timing', array_merge( $w( 20 ), array( 'placeholder' => '60 minutes' ) ) ),
							$f( $s, 'text', 'textarea', 'Text', array_merge( $w( 40 ), array( 'rows' => 2 ) ) ),
						),
						array(
							'max'    => 5,
							'layout' => 'table',
						)
					),
				),
				$settings( $s )
			)
		);

		/* Testimonials ---------------------------------------------------- */
		$s         = 'testimonials';
		$layouts[] = $layout(
			'testimonials',
			'Patient reviews',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f( $s, 'show_summary', 'true_false', 'Show rating summary', array( 'ui' => 1, 'default_value' => 1 ) ),
					$repeater(
						$s,
						'items',
						'Reviews',
						array(
							$f( $s, 'quote', 'textarea', 'Review', array( 'rows' => 3, 'instructions' => 'Copy real reviews word for word, with the patient\'s permission.' ) ),
							$f( $s, 'name', 'text', 'Name', array_merge( $w( 35 ), array( 'placeholder' => 'Maya R.' ) ) ),
							$f( $s, 'detail', 'text', 'Detail', array_merge( $w( 35 ), array( 'placeholder' => 'Invisalign, 2025' ) ) ),
							$f( $s, 'source', 'text', 'Source', array_merge( $w( 30 ), array( 'placeholder' => 'Google' ) ) ),
						)
					),
				),
				$settings( $s, 'auto' )
			)
		);

		/* Gallery --------------------------------------------------------- */
		$s         = 'gallery';
		$layouts[] = $layout(
			'gallery',
			'Before and after',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$repeater(
						$s,
						'items',
						'Cases',
						array(
							$image( $s, 'before', 'Before (4:3)', $w( 50 ) ),
							$image( $s, 'after', 'After (4:3, same framing)', $w( 50 ) ),
							$f( $s, 'title', 'text', 'Treatment', array_merge( $w( 50 ), array( 'placeholder' => 'Invisalign' ) ) ),
							$f( $s, 'detail', 'text', 'Detail', array_merge( $w( 50 ), array( 'placeholder' => 'Crowding, 14 months' ) ) ),
						),
						array( 'max' => 6 )
					),
					$f( $s, 'note', 'text', 'Note', array( 'default_value' => 'Photos of our patients, shared with permission. Results vary.' ) ),
				),
				$settings( $s )
			)
		);

		/* Financing ------------------------------------------------------- */
		$s         = 'financing';
		$layouts[] = $layout(
			'financing',
			'Cost and financing',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f( $s, 'price', 'text', 'Price figure', array_merge( $w( 30 ), array( 'placeholder' => '$189' ) ) ),
					$f( $s, 'price_label', 'text', 'Price label', array_merge( $w( 70 ), array( 'placeholder' => 'Monthly plans from, with $0 down' ) ) ),
					$f( $s, 'price_note', 'text', 'Price fine print', array( 'placeholder' => 'Based on a 24-month plan. Your exact fee is in your written plan.' ) ),
					$repeater(
						$s,
						'items',
						'Ways to pay',
						array(
							$icon( $s ),
							$f( $s, 'title', 'text', 'Title', $w( 75 ) ),
							$f( $s, 'text', 'textarea', 'Text', array( 'rows' => 2 ) ),
						),
						array(
							'max'    => 4,
							'layout' => 'table',
						)
					),
					$f( $s, 'insurance_heading', 'text', 'Insurance line label', array( 'default_value' => 'In-network or we file for you with' ) ),
					$f( $s, 'use_insurance', 'true_false', 'List insurers from Practice Settings', array_merge( $w( 50 ), array( 'ui' => 1, 'default_value' => 1 ) ) ),
					$f( $s, 'button', 'link', 'Button', $w( 50 ) ),
				),
				$settings( $s )
			)
		);

		/* FAQ ------------------------------------------------------------- */
		$s         = 'faq';
		$layouts[] = $layout(
			'faq',
			'FAQ',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$repeater(
						$s,
						'items',
						'Questions',
						array(
							$f( $s, 'question', 'text', 'Question', array( 'instructions' => 'Phrase it the way patients search: "How much do braces cost in {city}?"' ) ),
							$f( $s, 'answer', 'textarea', 'Answer', array( 'rows' => 4, 'instructions' => 'Answer in the first sentence, then add detail. 40 to 80 words reads well in search and AI answers.' ) ),
						)
					),
					$f( $s, 'schema', 'true_false', 'Add FAQ schema', array( 'ui' => 1, 'default_value' => 1, 'instructions' => 'Use on one page per question set. Do not repeat the same questions across many pages.' ) ),
				),
				$settings( $s )
			)
		);

		/* Location -------------------------------------------------------- */
		$s         = 'location';
		$layouts[] = $layout(
			'location',
			'Location and hours',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f( $s, 'show_map', 'true_false', 'Show map', array_merge( $w( 33 ), array( 'ui' => 1, 'default_value' => 1 ) ) ),
					$f( $s, 'show_areas', 'true_false', 'Show areas served', array_merge( $w( 33 ), array( 'ui' => 1, 'default_value' => 1 ) ) ),
					$f( $s, 'areas_label', 'text', 'Areas label', array_merge( $w( 34 ), array( 'default_value' => 'Patients visit us from' ) ) ),
					$f( $s, 'directions_text', 'textarea', 'Getting here', array( 'rows' => 2, 'placeholder' => 'Free parking behind the building. Five minutes from MoPac at the Parmer Lane exit.' ) ),
				),
				$settings( $s )
			)
		);

		/* Key facts ------------------------------------------------------- */
		$s         = 'facts';
		$layouts[] = $layout(
			'facts',
			'Key facts',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$repeater(
						$s,
						'items',
						'Facts',
						array(
							$f( $s, 'term', 'text', 'Label', $w( 35 ) ),
							$f( $s, 'value', 'textarea', 'Value', array_merge( $w( 65 ), array( 'rows' => 1 ) ) ),
						),
						array(
							'layout'       => 'table',
							'instructions' => 'Plain facts AI assistants and search engines can quote: who, where, cost, ages, insurance, languages.',
						)
					),
				),
				$settings( $s )
			)
		);

		/* Posts ----------------------------------------------------------- */
		$s         = 'posts';
		$layouts[] = $layout(
			'posts',
			'Latest articles',
			array_merge(
				array( $content_tab( $s ) ),
				$head( $s ),
				array(
					$f(
						$s,
						'count',
						'number',
						'Number of posts',
						array_merge(
							$w( 33 ),
							array(
								'default_value' => 3,
								'min'           => 1,
								'max'           => 6,
							)
						)
					),
					$f(
						$s,
						'category',
						'taxonomy',
						'Category',
						array_merge(
							$w( 33 ),
							array(
								'taxonomy'      => 'category',
								'field_type'    => 'select',
								'allow_null'    => 1,
								'return_format' => 'id',
								'add_term'      => 0,
							)
						)
					),
					$f( $s, 'link', 'link', 'Link to all articles', $w( 34 ) ),
				),
				$settings( $s )
			)
		);

		/* CTA ------------------------------------------------------------- */
		$s         = 'cta';
		$layouts[] = $layout(
			'cta',
			'Call to action',
			array_merge(
				array(
					$content_tab( $s ),
					$f( $s, 'heading', 'text', 'Heading', array( 'instructions' => $heading_help ) ),
					$f( $s, 'text', 'textarea', 'Text', array( 'rows' => 2 ) ),
					$f( $s, 'button', 'link', 'Button', array_merge( $w( 50 ), array( 'instructions' => 'Empty = booking URL.' ) ) ),
					$f( $s, 'show_phone', 'true_false', 'Show phone', array_merge( $w( 50 ), array( 'ui' => 1, 'default_value' => 1 ) ) ),
				),
				$settings( $s )
			)
		);

		/* Rich text ------------------------------------------------------- */
		$s         = 'content';
		$layouts[] = $layout(
			'content',
			'Text',
			array_merge(
				array(
					$content_tab( $s ),
					$f( $s, 'heading', 'text', 'Heading' ),
					$f( $s, 'body', 'wysiwyg', 'Body', array( 'media_upload' => 1 ) ),
					$f(
						$s,
						'width',
						'select',
						'Width',
						array(
							'choices'       => array(
								'narrow' => 'Reading width',
								'wide'   => 'Full container',
							),
							'default_value' => 'narrow',
						)
					),
				),
				$settings( $s )
			)
		);

		$keyed = array();
		foreach ( $layouts as $l ) {
			$keyed[ $l['key'] ] = $l;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_aw_sections',
				'title'                 => __( 'Page sections', 'archwire' ),
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'hide_on_screen'        => array(),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
				),
				'fields'                => array(
					array(
						'key'          => 'field_aw_sections',
						'name'         => 'page_sections',
						'label'        => __( 'Page sections', 'archwire' ),
						'type'         => 'flexible_content',
						'button_label' => __( 'Add section', 'archwire' ),
						'instructions' => __( 'When this page has sections, they replace the page content below.', 'archwire' ),
						'layouts'      => $keyed,
					),
				),
			)
		);
	}
);
