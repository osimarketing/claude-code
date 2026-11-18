<?php
/**
 * Practice Settings options page.
 *
 * Fill this once per client. Every section, the header, footer, schema
 * and /llms.txt read from it, and editor copy can use {city}, {practice},
 * {phone}, {doctor}, {state} and {areas} tokens.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_options_page(
			array(
				'page_title' => __( 'Practice Settings', 'archwire' ),
				'menu_title' => __( 'Practice', 'archwire' ),
				'menu_slug'  => 'archwire-practice',
				'capability' => 'edit_theme_options',
				'icon_url'   => 'dashicons-location',
				'position'   => 59,
				'redirect'   => false,
			)
		);

		$f = function ( $name, $type, $label, $extra = array() ) {
			return array_merge(
				array(
					'key'   => 'field_aw_opt_' . $name,
					'name'  => $name,
					'label' => $label,
					'type'  => $type,
				),
				$extra
			);
		};
		$tab = function ( $label ) {
			return array(
				'key'       => 'field_aw_opt_tab_' . sanitize_key( $label ),
				'label'     => $label,
				'type'      => 'tab',
				'placement' => 'left',
			);
		};
		$half = array( 'wrapper' => array( 'width' => '50' ) );
		$third = array( 'wrapper' => array( 'width' => '33' ) );

		$days = array(
			'1' => 'Mon',
			'2' => 'Tue',
			'3' => 'Wed',
			'4' => 'Thu',
			'5' => 'Fri',
			'6' => 'Sat',
			'7' => 'Sun',
		);

		acf_add_local_field_group(
			array(
				'key'      => 'group_aw_practice',
				'title'    => __( 'Practice Settings', 'archwire' ),
				'style'    => 'seamless',
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'archwire-practice',
						),
					),
				),
				'fields'   => array(
					$tab( 'Practice' ),
					$f( 'practice_name', 'text', 'Practice name', array_merge( $half, array( 'required' => 1, 'instructions' => 'Exactly as on the Google Business Profile.' ) ) ),
					$f( 'doctor_name', 'text', 'Lead orthodontist', array_merge( $half, array( 'placeholder' => 'Dr. Ana Ruiz, DDS, MS' ) ) ),
					$f( 'practice_description', 'textarea', 'Short description', array( 'rows' => 3, 'instructions' => 'Two plain sentences: who you treat, where, and what you offer. Used for schema, llms.txt and the default meta description.' ) ),
					$f( 'phone', 'text', 'Phone', array_merge( $third, array( 'placeholder' => '(512) 555-0142' ) ) ),
					$f( 'email', 'email', 'Email', $third ),
					$f( 'booking_url', 'url', 'Booking URL', array_merge( $third, array( 'instructions' => 'Online scheduler or your contact page.' ) ) ),
					$f( 'booking_label', 'text', 'Booking button label', array_merge( $half, array( 'default_value' => 'Book a free consultation' ) ) ),

					$tab( 'Location' ),
					$f( 'street', 'text', 'Street address', $half ),
					$f( 'suite', 'text', 'Suite', $half ),
					$f( 'city', 'text', 'City', $third ),
					$f( 'state', 'text', 'State', $third ),
					$f( 'zip', 'text', 'ZIP', $third ),
					$f( 'country', 'text', 'Country code', array_merge( $third, array( 'default_value' => 'US' ) ) ),
					$f( 'latitude', 'text', 'Latitude', $third ),
					$f( 'longitude', 'text', 'Longitude', $third ),
					$f( 'map_embed_url', 'url', 'Google Maps embed URL', array( 'instructions' => 'Google Maps → Share → Embed a map → copy only the src="…" URL.' ) ),
					$f( 'directions_url', 'url', 'Directions link', array( 'instructions' => 'Optional. Leave empty to build one from the address.' ) ),
					$f( 'service_areas', 'textarea', 'Areas you serve', array( 'rows' => 4, 'instructions' => 'One city or neighborhood per line. Shown in the Location section and used as areaServed in schema.' ) ),

					$tab( 'Hours' ),
					$f(
						'hours',
						'repeater',
						'Office hours',
						array(
							'layout'       => 'table',
							'button_label' => 'Add hours',
							'instructions' => 'One row per set of hours. Days not listed show as closed.',
							'sub_fields'   => array(
								array(
									'key'     => 'field_aw_opt_hours_days',
									'name'    => 'days',
									'label'   => 'Days',
									'type'    => 'checkbox',
									'choices' => $days,
									'layout'  => 'horizontal',
								),
								array(
									'key'            => 'field_aw_opt_hours_open',
									'name'           => 'open',
									'label'          => 'Opens',
									'type'           => 'time_picker',
									'display_format' => 'g:i a',
									'return_format'  => 'H:i',
								),
								array(
									'key'            => 'field_aw_opt_hours_close',
									'name'           => 'close',
									'label'          => 'Closes',
									'type'           => 'time_picker',
									'display_format' => 'g:i a',
									'return_format'  => 'H:i',
								),
							),
						)
					),
					$f( 'hours_note', 'text', 'Hours note', array( 'placeholder' => 'Closed most major holidays' ) ),

					$tab( 'Reviews' ),
					$f( 'rating', 'number', 'Average rating', array_merge( $third, array( 'min' => 1, 'max' => 5, 'step' => 0.1, 'instructions' => 'Copy from your live profile. Leave empty to hide.' ) ) ),
					$f( 'review_count', 'number', 'Number of reviews', $third ),
					$f( 'review_source', 'text', 'Review source', array_merge( $third, array( 'default_value' => 'Google' ) ) ),
					$f( 'reviews_url', 'url', 'Link to reviews', array( 'instructions' => 'Shown next to the rating so visitors can check it.' ) ),

					$tab( 'Brand' ),
					$f( 'brand_color', 'color_picker', 'Brand color', array_merge( $third, array( 'default_value' => '#2E95B3' ) ) ),
					$f( 'brand_sky', 'color_picker', 'Light accent', array_merge( $third, array( 'default_value' => '#85BADB' ) ) ),
					$f( 'brand_paper', 'color_picker', 'Section tint', array_merge( $third, array( 'default_value' => '#F2F7FA' ) ) ),
					$f( 'brand_ink', 'color_picker', 'Button and link color (optional)', array_merge( $half, array( 'instructions' => 'Leave empty to darken the brand color automatically so white text stays readable (4.5:1).' ) ) ),
					$f( 'brand_deep', 'color_picker', 'Dark panel color (optional)', array_merge( $half, array( 'instructions' => 'Leave empty to derive from the brand color.' ) ) ),

					$tab( 'Profiles' ),
					$f( 'google_business_url', 'url', 'Google Business Profile' ),
					$f( 'facebook_url', 'url', 'Facebook', $half ),
					$f( 'instagram_url', 'url', 'Instagram', $half ),
					$f( 'youtube_url', 'url', 'YouTube', $half ),
					$f( 'tiktok_url', 'url', 'TikTok', $half ),

					$tab( 'Search & AI' ),
					$f( 'treatments', 'textarea', 'Treatments offered', array( 'rows' => 5, 'instructions' => 'One per line. Used in schema (availableService) and llms.txt.', 'default_value' => "Metal braces\nClear braces\nInvisalign clear aligners\nEarly orthodontic treatment\nRetainers" ) ),
					$f( 'languages', 'text', 'Languages spoken', array( 'placeholder' => 'English, Spanish' ) ),
					$f( 'insurance', 'textarea', 'Insurance accepted', array( 'rows' => 3, 'instructions' => 'One per line.' ) ),
					$f( 'price_range', 'text', 'Price range', array_merge( $third, array( 'placeholder' => '$$' ) ) ),
					$f(
						'schema_type',
						'select',
						'Schema type',
						array_merge(
							$third,
							array(
								'choices'       => array(
									'Dentist'         => 'Dentist (recommended for orthodontists)',
									'MedicalBusiness' => 'MedicalBusiness',
									'MedicalClinic'   => 'MedicalClinic',
								),
								'default_value' => 'Dentist',
							)
						)
					),
					$f( 'schema_enable', 'true_false', 'Print practice schema', array_merge( $third, array( 'default_value' => 1, 'ui' => 1, 'instructions' => 'Turn off only if your SEO plugin already outputs a complete Dentist/LocalBusiness entity.' ) ) ),
					$f( 'llms_enable', 'true_false', 'Serve /llms.txt', array_merge( $half, array( 'default_value' => 1, 'ui' => 1, 'instructions' => 'A plain-text summary of the practice for AI assistants.' ) ) ),
					$f( 'mobile_bar', 'true_false', 'Show call and book bar on phones', array_merge( $half, array( 'default_value' => 1, 'ui' => 1 ) ) ),
				),
			)
		);
	}
);
