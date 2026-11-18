<?php
/**
 * Practice data: one normalized array built from the Practice Settings screen.
 *
 * Name, address and phone come from one place so they match everywhere:
 * header, footer, location section, schema and llms.txt. Consistent NAP
 * data is the base of local search ranking.
 *
 * @package Archwire
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalized practice data (cached per request).
 *
 * @return array
 */
function archwire_practice() {
	static $p = null;
	if ( null !== $p ) {
		return $p;
	}

	$phone = (string) archwire_opt( 'phone' );
	$lines = function ( $value ) {
		return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n|,/', (string) $value ) ) ) );
	};

	$p = array(
		'name'          => (string) archwire_opt( 'practice_name', get_bloginfo( 'name' ) ),
		'doctor'        => (string) archwire_opt( 'doctor_name' ),
		'description'   => (string) archwire_opt( 'practice_description', get_bloginfo( 'description' ) ),
		'phone'         => $phone,
		'phone_e164'    => archwire_phone_e164( $phone ),
		'email'         => (string) archwire_opt( 'email' ),
		'booking_url'   => (string) archwire_opt( 'booking_url' ),
		'booking_label' => (string) archwire_opt( 'booking_label', __( 'Book a free consultation', 'archwire' ) ),
		'street'        => (string) archwire_opt( 'street' ),
		'suite'         => (string) archwire_opt( 'suite' ),
		'city'          => (string) archwire_opt( 'city' ),
		'state'         => (string) archwire_opt( 'state' ),
		'zip'           => (string) archwire_opt( 'zip' ),
		'country'       => (string) archwire_opt( 'country', 'US' ),
		'lat'           => (string) archwire_opt( 'latitude' ),
		'lng'           => (string) archwire_opt( 'longitude' ),
		'map_embed'     => (string) archwire_opt( 'map_embed_url' ),
		'directions'    => (string) archwire_opt( 'directions_url' ),
		'areas'         => $lines( archwire_opt( 'service_areas' ) ),
		'hours_rows'    => (array) archwire_opt( 'hours', array() ),
		'hours_note'    => (string) archwire_opt( 'hours_note' ),
		'rating'        => (string) archwire_opt( 'rating' ),
		'review_count'  => (int) archwire_opt( 'review_count', 0 ),
		'reviews_url'   => (string) archwire_opt( 'reviews_url' ),
		'review_source' => (string) archwire_opt( 'review_source', 'Google' ),
		'treatments'    => $lines( archwire_opt( 'treatments' ) ),
		'languages'     => $lines( archwire_opt( 'languages' ) ),
		'insurance'     => $lines( archwire_opt( 'insurance' ) ),
		'price_range'   => (string) archwire_opt( 'price_range' ),
		'schema_type'   => (string) archwire_opt( 'schema_type', 'Dentist' ),
		'schema_on'     => (bool) archwire_opt( 'schema_enable', true ),
		'llms_on'       => (bool) archwire_opt( 'llms_enable', true ),
		'mobile_bar'    => (bool) archwire_opt( 'mobile_bar', true ),
		'social'        => array_filter(
			array(
				'google'    => (string) archwire_opt( 'google_business_url' ),
				'facebook'  => (string) archwire_opt( 'facebook_url' ),
				'instagram' => (string) archwire_opt( 'instagram_url' ),
				'youtube'   => (string) archwire_opt( 'youtube_url' ),
				'tiktok'    => (string) archwire_opt( 'tiktok_url' ),
			)
		),
	);

	if ( ! $p['directions'] && $p['street'] ) {
		$p['directions'] = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( archwire_address_line( $p ) );
	}

	$p['hours'] = archwire_hours_by_day( $p['hours_rows'] );

	/**
	 * Filter the practice data, e.g. to override it per location page.
	 *
	 * @param array $p Practice data.
	 */
	$p = apply_filters( 'archwire_practice', $p );
	return $p;
}

/**
 * Digits-only phone for tel: links. Assumes US numbers when 10 digits.
 *
 * @param string $phone Display phone.
 * @return string
 */
function archwire_phone_e164( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );
	if ( 10 === strlen( $digits ) ) {
		return '+1' . $digits;
	}
	return $digits ? '+' . ltrim( $digits, '+' ) : '';
}

/**
 * One-line postal address.
 *
 * @param array|null $p Practice data.
 * @return string
 */
function archwire_address_line( $p = null ) {
	$p      = $p ? $p : archwire_practice();
	$street = trim( $p['street'] . ( $p['suite'] ? ', ' . $p['suite'] : '' ) );
	$region = trim( $p['state'] . ' ' . $p['zip'] );
	return implode( ', ', array_filter( array( $street, $p['city'], $region ) ) );
}

/**
 * Day keys in display order (ISO: 1 = Monday).
 *
 * @return array<string,string>
 */
function archwire_days() {
	return array(
		'1' => __( 'Monday', 'archwire' ),
		'2' => __( 'Tuesday', 'archwire' ),
		'3' => __( 'Wednesday', 'archwire' ),
		'4' => __( 'Thursday', 'archwire' ),
		'5' => __( 'Friday', 'archwire' ),
		'6' => __( 'Saturday', 'archwire' ),
		'7' => __( 'Sunday', 'archwire' ),
	);
}

/**
 * Turn the hours repeater (days + open + close) into a per-day map.
 *
 * @param array $rows Repeater rows.
 * @return array<string,array{0:string,1:string}> Day => [open, close] in H:i.
 */
function archwire_hours_by_day( $rows ) {
	$map = array();
	foreach ( (array) $rows as $row ) {
		$open  = archwire_time_24( $row['open'] ?? '' );
		$close = archwire_time_24( $row['close'] ?? '' );
		if ( ! $open || ! $close ) {
			continue;
		}
		foreach ( (array) ( $row['days'] ?? array() ) as $day ) {
			$map[ (string) $day ] = array( $open, $close );
		}
	}
	ksort( $map );
	return $map;
}

/**
 * Normalize a time string to H:i.
 *
 * @param string $time Time.
 * @return string
 */
function archwire_time_24( $time ) {
	$ts = strtotime( '2000-01-01 ' . $time );
	return $ts ? gmdate( 'H:i', $ts ) : '';
}

/**
 * Display a H:i time with the site time format.
 *
 * @param string $time H:i.
 * @return string
 */
function archwire_time_display( $time ) {
	$format = get_option( 'time_format', 'g:i a' );
	return gmdate( $format ? $format : 'g:i a', strtotime( '2000-01-01 ' . $time . ' UTC' ) );
}

/**
 * Group consecutive days that share hours: "Mon–Thu 8:00 am – 5:00 pm".
 *
 * @return array<int,array{days:string,hours:string,keys:string[]}>
 */
function archwire_hours_summary() {
	$hours  = archwire_practice()['hours'];
	$short  = array(
		'1' => __( 'Mon', 'archwire' ),
		'2' => __( 'Tue', 'archwire' ),
		'3' => __( 'Wed', 'archwire' ),
		'4' => __( 'Thu', 'archwire' ),
		'5' => __( 'Fri', 'archwire' ),
		'6' => __( 'Sat', 'archwire' ),
		'7' => __( 'Sun', 'archwire' ),
	);
	$groups = array();
	foreach ( $hours as $day => $span ) {
		$label = archwire_time_display( $span[0] ) . ' – ' . archwire_time_display( $span[1] );
		$last  = end( $groups );
		if ( $last && $last['hours'] === $label && (int) end( $last['keys'] ) === (int) $day - 1 ) {
			$groups[ key( $groups ) ]['keys'][] = (string) $day;
		} else {
			$groups[] = array(
				'hours' => $label,
				'keys'  => array( (string) $day ),
			);
		}
	}
	foreach ( $groups as &$g ) {
		$first   = $short[ $g['keys'][0] ];
		$lastday = $short[ end( $g['keys'] ) ];
		$g['days'] = count( $g['keys'] ) > 1 ? $first . '–' . $lastday : $first;
	}
	return $groups;
}

/**
 * Hours as JSON for the open-now script (runs in the browser so cached
 * pages still show the right status).
 *
 * @return string
 */
function archwire_hours_json() {
	return wp_json_encode(
		array(
			'tz'    => wp_timezone_string(),
			'hours' => archwire_practice()['hours'],
			'fmt'   => get_option( 'time_format', 'g:i a' ),
		)
	);
}
