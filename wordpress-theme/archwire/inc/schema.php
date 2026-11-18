<?php
/**
 * Structured data (JSON-LD).
 *
 * - Practice entity (Dentist by default) with address, geo, hours,
 *   areaServed, services and profiles. Printed on every page so search
 *   engines and AI assistants see the same facts everywhere.
 * - Person for each Doctor section, linked to the practice.
 * - FAQPage built from FAQ sections on the current page.
 *
 * Review stars are deliberately NOT marked up: Google ignores
 * self-serving review markup on LocalBusiness pages. The rating is shown
 * as visible text with a link to the source instead.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stable @id for the practice entity.
 *
 * @return string
 */
function archwire_practice_id() {
	return home_url( '/#practice' );
}

/**
 * Practice entity as an array.
 *
 * @return array
 */
function archwire_practice_schema() {
	$p    = archwire_practice();
	$data = array(
		'@type'            => $p['schema_type'] ? $p['schema_type'] : 'Dentist',
		'@id'              => archwire_practice_id(),
		'name'             => $p['name'],
		'url'              => home_url( '/' ),
		'description'      => $p['description'],
		'medicalSpecialty' => 'https://schema.org/Dentistry',
		'knowsAbout'       => array_merge( array( 'Orthodontics' ), $p['treatments'] ),
	);

	if ( function_exists( 'get_custom_logo' ) && has_custom_logo() ) {
		$logo = wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' );
		if ( $logo ) {
			$data['logo']  = $logo;
			$data['image'] = $logo;
		}
	}
	if ( $p['phone'] ) {
		$data['telephone'] = $p['phone_e164'];
	}
	if ( $p['email'] ) {
		$data['email'] = $p['email'];
	}
	if ( $p['price_range'] ) {
		$data['priceRange'] = $p['price_range'];
	}
	if ( $p['street'] || $p['city'] ) {
		$data['address'] = array_filter(
			array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => trim( $p['street'] . ( $p['suite'] ? ', ' . $p['suite'] : '' ) ),
				'addressLocality' => $p['city'],
				'addressRegion'   => $p['state'],
				'postalCode'      => $p['zip'],
				'addressCountry'  => $p['country'],
			)
		);
	}
	if ( $p['lat'] && $p['lng'] ) {
		$data['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $p['lat'],
			'longitude' => (float) $p['lng'],
		);
	}
	if ( $p['directions'] ) {
		$data['hasMap'] = $p['directions'];
	}
	if ( $p['areas'] ) {
		$data['areaServed'] = array_map(
			function ( $area ) {
				return array(
					'@type' => 'City',
					'name'  => $area,
				);
			},
			$p['areas']
		);
	}
	if ( $p['hours_rows'] ) {
		$names = array( '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday' );
		$specs = array();
		foreach ( $p['hours_rows'] as $row ) {
			$open  = archwire_time_24( $row['open'] ?? '' );
			$close = archwire_time_24( $row['close'] ?? '' );
			$days  = array_values( array_intersect_key( $names, array_flip( (array) ( $row['days'] ?? array() ) ) ) );
			if ( $open && $close && $days ) {
				$specs[] = array(
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => $days,
					'opens'     => $open,
					'closes'    => $close,
				);
			}
		}
		if ( $specs ) {
			$data['openingHoursSpecification'] = $specs;
		}
	}
	if ( $p['treatments'] ) {
		$data['availableService'] = array_map(
			function ( $t ) {
				return array(
					'@type' => 'MedicalProcedure',
					'name'  => $t,
				);
			},
			$p['treatments']
		);
	}
	if ( $p['languages'] ) {
		$data['knowsLanguage'] = $p['languages'];
	}
	if ( $p['social'] ) {
		$data['sameAs'] = array_values( $p['social'] );
	}
	if ( $p['booking_url'] ) {
		$data['potentialAction'] = array(
			'@type'  => 'ReserveAction',
			'target' => $p['booking_url'],
			'name'   => $p['booking_label'],
		);
	}

	/**
	 * Filter the practice schema before output.
	 *
	 * @param array $data Schema.
	 */
	return apply_filters( 'archwire_practice_schema', $data );
}

/**
 * Remember a doctor shown on this page for Person schema.
 *
 * @param array $doctor name, job_title, image, url, same_as[], description.
 */
function archwire_doctor_collect( $doctor ) {
	$GLOBALS['archwire_doctors'][] = $doctor;
}

/**
 * Print the JSON-LD graph in the footer (after sections have rendered).
 */
function archwire_print_schema() {
	$p     = archwire_practice();
	$graph = array();

	if ( $p['schema_on'] && $p['name'] ) {
		$graph[] = archwire_practice_schema();
	}

	foreach ( (array) ( $GLOBALS['archwire_doctors'] ?? array() ) as $doc ) {
		if ( empty( $doc['name'] ) ) {
			continue;
		}
		$graph[] = array_filter(
			array(
				'@type'       => 'Person',
				'name'        => $doc['name'],
				'jobTitle'    => $doc['job_title'] ?? 'Orthodontist',
				'image'       => $doc['image'] ?? '',
				'description' => $doc['description'] ?? '',
				'url'         => get_permalink(),
				'worksFor'    => array( '@id' => archwire_practice_id() ),
				'sameAs'      => $doc['same_as'] ?? array(),
			)
		);
	}

	$faq = (array) ( $GLOBALS['archwire_faq'] ?? array() );
	if ( $faq ) {
		$graph[] = array(
			'@type'      => 'FAQPage',
			'@id'        => get_permalink() . '#faq',
			'mainEntity' => array_map(
				function ( $item ) {
					return array(
						'@type'          => 'Question',
						'name'           => $item['q'],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $item['a'],
						),
					);
				},
				$faq
			),
		);
	}

	if ( ! $graph ) {
		return;
	}
	printf(
		"<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
		)
	);
}
add_action( 'wp_footer', 'archwire_print_schema', 20 );
