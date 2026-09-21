<?php
/**
 * ACF Flexible Content field group: "Page Sections".
 *
 * Registered in PHP so the theme is self-contained (no manual import needed).
 * Requires Advanced Custom Fields PRO (Flexible Content + Repeater).
 *
 * Editors build the homepage by adding, reordering, and removing layouts:
 * hero, what_we_do, problems, how_we_work, industries, environment_strip,
 * impact (stats + testimonials), selected_work, insights, planner, cta.
 *
 * @package PatientPath
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

		/** Helper builders to keep the definition readable. */
		$text = function ( $key, $label, $args = array() ) {
			return array_merge( array( 'key' => $key, 'label' => $label, 'name' => str_replace( 'field_', '', $key ), 'type' => 'text' ), $args );
		};
		$textarea = function ( $key, $label, $args = array() ) {
			return array_merge( array( 'key' => $key, 'label' => $label, 'name' => str_replace( 'field_', '', $key ), 'type' => 'textarea', 'rows' => 3 ), $args );
		};
		$image = function ( $key, $label, $args = array() ) {
			return array_merge( array( 'key' => $key, 'label' => $label, 'name' => str_replace( 'field_', '', $key ), 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium' ), $args );
		};
		$link = function ( $key, $label, $args = array() ) {
			return array_merge( array( 'key' => $key, 'label' => $label, 'name' => str_replace( 'field_', '', $key ), 'type' => 'link', 'return_format' => 'array' ), $args );
		};

		acf_add_local_field_group(
			array(
				'key'      => 'group_patientpath_sections',
				'title'    => 'Page Sections',
				'fields'   => array(
					array(
						'key'          => 'field_pp_page_sections',
						'label'        => 'Page Sections',
						'name'         => 'page_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Add Section',
						'layouts'      => array(

							/* ---------------- HERO ---------------- */
							'layout_hero' => array(
								'key'        => 'layout_hero',
								'name'       => 'hero',
								'label'      => 'Hero',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_hero_eyebrow', 'Eyebrow', array( 'default_value' => 'Healthcare Growth & Operational Support' ) ),
									$text( 'field_hero_heading_1', 'Heading line 1', array( 'default_value' => 'The right path depends' ) ),
									$text( 'field_hero_heading_2', 'Heading line 2 (before emphasis)', array( 'default_value' => "on where you're" ) ),
									$text( 'field_hero_heading_em', 'Heading emphasis (gold word)', array( 'default_value' => 'headed.' ) ),
									$textarea( 'field_hero_lead', 'Lead paragraph', array( 'rows' => 4 ) ),
									$link( 'field_hero_cta_primary', 'Primary button' ),
									$link( 'field_hero_cta_secondary', 'Secondary button' ),
									$image( 'field_hero_image', 'Hero image (portrait ~1200x1400)' ),
									$text( 'field_hero_trust_label', 'Trust label', array( 'default_value' => 'Trusted by 300+ practices across' ) ),
									array(
										'key'          => 'field_hero_trust_items',
										'label'        => 'Trust items',
										'name'         => 'trust_items',
										'type'         => 'repeater',
										'layout'       => 'table',
										'button_label' => 'Add item',
										'sub_fields'   => array( $text( 'field_hero_trust_item', 'Label' ) ),
									),
								),
							),

							/* ---------------- WHAT WE DO ---------------- */
							'layout_what_we_do' => array(
								'key'        => 'layout_what_we_do',
								'name'       => 'what_we_do',
								'label'      => 'What We Do (levers)',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_wwd_number', 'Eyebrow number', array( 'default_value' => '01' ) ),
									$text( 'field_wwd_eyebrow', 'Eyebrow text', array( 'default_value' => 'What We Do' ) ),
									$text( 'field_wwd_heading', 'Heading (use <br> for a line break)', array( 'default_value' => 'Four paths.<br />One trusted partner.' ) ),
									$textarea( 'field_wwd_intro', 'Intro' ),
									array(
										'key'          => 'field_wwd_levers',
										'label'        => 'Levers',
										'name'         => 'levers',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add lever',
										'sub_fields'   => array(
											$text( 'field_wwd_lever_idx', 'Index', array( 'wrapper' => array( 'width' => 20 ) ) ),
											$text( 'field_wwd_lever_title', 'Title', array( 'wrapper' => array( 'width' => 30 ) ) ),
											$textarea( 'field_wwd_lever_text', 'Text', array( 'wrapper' => array( 'width' => 50 ) ) ),
										),
									),
								),
							),

							/* ---------------- PROBLEMS WE SOLVE ---------------- */
							'layout_problems' => array(
								'key'        => 'layout_problems',
								'name'       => 'problems',
								'label'      => 'Problems We Solve (tabs / accordion)',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_prob_number', 'Eyebrow number', array( 'default_value' => '02' ) ),
									$text( 'field_prob_eyebrow', 'Eyebrow text', array( 'default_value' => 'Problems We Solve' ) ),
									$text( 'field_prob_heading', 'Heading', array( 'default_value' => 'Where are you on your path?' ) ),
									$textarea( 'field_prob_intro', 'Intro' ),
									array(
										'key'          => 'field_prob_items',
										'label'        => 'Problems',
										'name'         => 'items',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add problem',
										'sub_fields'   => array(
											$text( 'field_prob_item_number', 'Number', array( 'wrapper' => array( 'width' => 15 ) ) ),
											$text( 'field_prob_item_title', 'Tab title', array( 'wrapper' => array( 'width' => 35 ) ) ),
											$text( 'field_prob_item_kicker', 'Kicker', array( 'default_value' => 'The situation', 'wrapper' => array( 'width' => 25 ) ) ),
											$text( 'field_prob_item_headline', 'Headline', array( 'wrapper' => array( 'width' => 25 ) ) ),
											$textarea( 'field_prob_item_copy', 'Copy' ),
											array(
												'key'          => 'field_prob_item_points',
												'label'        => 'Bullet points',
												'name'         => 'points',
												'type'         => 'repeater',
												'layout'       => 'table',
												'button_label' => 'Add point',
												'sub_fields'   => array( $text( 'field_prob_item_point', 'Point' ) ),
											),
											$text( 'field_prob_item_outcome_num', 'Outcome number', array( 'wrapper' => array( 'width' => 25 ) ) ),
											$text( 'field_prob_item_outcome_suffix', 'Outcome suffix', array( 'default_value' => '%', 'wrapper' => array( 'width' => 25 ) ) ),
											$text( 'field_prob_item_outcome_label', 'Outcome label', array( 'wrapper' => array( 'width' => 50 ) ) ),
										),
									),
								),
							),

							/* ---------------- HOW WE WORK ---------------- */
							'layout_how_we_work' => array(
								'key'        => 'layout_how_we_work',
								'name'       => 'how_we_work',
								'label'      => 'How We Work (services)',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_how_number', 'Eyebrow number', array( 'default_value' => '03' ) ),
									$text( 'field_how_eyebrow', 'Eyebrow text', array( 'default_value' => 'How We Work' ) ),
									$text( 'field_how_heading', 'Heading (use <br> for a line break)', array( 'default_value' => 'A clear path,<br />based on your goals.' ) ),
									$textarea( 'field_how_intro', 'Intro' ),
									array(
										'key'          => 'field_how_steps',
										'label'        => 'Services',
										'name'         => 'steps',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add service',
										'sub_fields'   => array(
											$text( 'field_how_step_num', 'Number', array( 'wrapper' => array( 'width' => 20 ) ) ),
											$text( 'field_how_step_title', 'Title', array( 'wrapper' => array( 'width' => 30 ) ) ),
											$textarea( 'field_how_step_text', 'Text', array( 'wrapper' => array( 'width' => 50 ) ) ),
										),
									),
								),
							),

							/* ---------------- INDUSTRIES ---------------- */
							'layout_industries' => array(
								'key'        => 'layout_industries',
								'name'       => 'industries',
								'label'      => 'Industries',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_ind_number', 'Eyebrow number', array( 'default_value' => '04' ) ),
									$text( 'field_ind_eyebrow', 'Eyebrow text', array( 'default_value' => 'Industries' ) ),
									$text( 'field_ind_heading', 'Heading' ),
									$textarea( 'field_ind_intro', 'Intro' ),
									$link( 'field_ind_cta', 'Button (Talk to a specialist)' ),
									array(
										'key'          => 'field_ind_items',
										'label'        => 'Industries',
										'name'         => 'items',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add industry',
										'sub_fields'   => array(
											$text( 'field_ind_item_name', 'Name', array( 'wrapper' => array( 'width' => 35 ) ) ),
											$text( 'field_ind_item_desc', 'Descriptor', array( 'wrapper' => array( 'width' => 65 ) ) ),
											$image( 'field_ind_item_image', 'Image (shown under each item on mobile)' ),
										),
									),
								),
							),

							/* ---------------- ENVIRONMENT PHOTO STRIP ---------------- */
							'layout_environment_strip' => array(
								'key'        => 'layout_environment_strip',
								'name'       => 'environment_strip',
								'label'      => 'Environment photo strip',
								'display'    => 'block',
								'sub_fields' => array(
									array(
										'key'          => 'field_strip_images',
										'label'        => 'Photos (3 recommended)',
										'name'         => 'images',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add photo',
										'sub_fields'   => array( $image( 'field_strip_image', 'Photo' ) ),
									),
								),
							),

							/* ---------------- IMPACT (stats + testimonials) ---------------- */
							'layout_impact' => array(
								'key'        => 'layout_impact',
								'name'       => 'impact',
								'label'      => 'Impact (stats + testimonials)',
								'display'    => 'block',
								'sub_fields' => array(
									array(
										'key'          => 'field_impact_stats',
										'label'        => 'Stats',
										'name'         => 'stats',
										'type'         => 'repeater',
										'layout'       => 'table',
										'button_label' => 'Add stat',
										'sub_fields'   => array(
											$text( 'field_impact_stat_prefix', 'Prefix', array( 'wrapper' => array( 'width' => 15 ) ) ),
											$text( 'field_impact_stat_number', 'Number', array( 'wrapper' => array( 'width' => 20 ) ) ),
											$text( 'field_impact_stat_suffix', 'Suffix', array( 'wrapper' => array( 'width' => 15 ) ) ),
											$text( 'field_impact_stat_decimals', 'Decimals', array( 'default_value' => '0', 'wrapper' => array( 'width' => 15 ) ) ),
											$text( 'field_impact_stat_label', 'Label', array( 'wrapper' => array( 'width' => 35 ) ) ),
										),
									),
									array(
										'key'          => 'field_impact_quotes',
										'label'        => 'Testimonials',
										'name'         => 'testimonials',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add testimonial',
										'sub_fields'   => array(
											$textarea( 'field_impact_quote', 'Quote', array( 'rows' => 4 ) ),
											$text( 'field_impact_quote_name', 'Name', array( 'wrapper' => array( 'width' => 40 ) ) ),
											$text( 'field_impact_quote_org', 'Organisation / role', array( 'wrapper' => array( 'width' => 60 ) ) ),
											$image( 'field_impact_quote_logo', 'Logo / avatar' ),
										),
									),
								),
							),

							/* ---------------- SELECTED WORK ---------------- */
							'layout_selected_work' => array(
								'key'        => 'layout_selected_work',
								'name'       => 'selected_work',
								'label'      => 'Selected Work',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_work_number', 'Eyebrow number', array( 'default_value' => '05' ) ),
									$text( 'field_work_eyebrow', 'Eyebrow text', array( 'default_value' => 'Selected Work' ) ),
									$text( 'field_work_heading', 'Heading' ),
									$textarea( 'field_work_intro', 'Intro' ),
									array(
										'key'          => 'field_work_cards',
										'label'        => 'Work samples',
										'name'         => 'cards',
										'type'         => 'repeater',
										'layout'       => 'block',
										'button_label' => 'Add work sample',
										'sub_fields'   => array(
											$image( 'field_work_card_image', 'Image' ),
											$text( 'field_work_card_category', 'Category', array( 'wrapper' => array( 'width' => 40 ) ) ),
											$text( 'field_work_card_title', 'Title', array( 'wrapper' => array( 'width' => 60 ) ) ),
											$textarea( 'field_work_card_desc', 'Description' ),
											array(
												'key'          => 'field_work_card_tags',
												'label'        => 'Tags (keep short — one row)',
												'name'         => 'tags',
												'type'         => 'repeater',
												'layout'       => 'table',
												'button_label' => 'Add tag',
												'sub_fields'   => array( $text( 'field_work_card_tag', 'Tag' ) ),
											),
											array(
												'key'           => 'field_work_card_mobile_only',
												'label'         => 'Show on mobile only',
												'name'          => 'mobile_only',
												'type'          => 'true_false',
												'ui'            => 1,
												'instructions'  => 'Hide this card on desktop; show it in the mobile swipe row.',
											),
										),
									),
								),
							),

							/* ---------------- INSIGHTS (pulls latest posts) ---------------- */
							'layout_insights' => array(
								'key'        => 'layout_insights',
								'name'       => 'insights',
								'label'      => 'Insights (latest posts)',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_ins_number', 'Eyebrow number', array( 'default_value' => '06' ) ),
									$text( 'field_ins_eyebrow', 'Eyebrow text', array( 'default_value' => 'Insights' ) ),
									$text( 'field_ins_heading', 'Heading', array( 'default_value' => 'Thinking that moves practices forward.' ) ),
									$link( 'field_ins_view_all', 'View all link' ),
									array_merge(
										$text( 'field_ins_count', 'Number of posts', array( 'default_value' => '3' ) ),
										array( 'type' => 'number', 'min' => 1, 'max' => 6 )
									),
								),
							),

							/* ---------------- PLANNER CALLOUT ---------------- */
							'layout_planner' => array(
								'key'        => 'layout_planner',
								'name'       => 'planner',
								'label'      => 'Planner callout',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_plan_eyebrow', 'Eyebrow', array( 'default_value' => 'Free resource' ) ),
									$text( 'field_plan_heading', 'Heading', array( 'default_value' => 'The Practice Marketing Planner' ) ),
									$textarea( 'field_plan_lead', 'Lead' ),
									$link( 'field_plan_button', 'Download button' ),
									$image( 'field_plan_image', 'Graphic' ),
								),
							),

							/* ---------------- CTA ---------------- */
							'layout_cta' => array(
								'key'        => 'layout_cta',
								'name'       => 'cta',
								'label'      => 'Call to action',
								'display'    => 'block',
								'sub_fields' => array(
									$text( 'field_cta_eyebrow', 'Eyebrow', array( 'default_value' => 'Start the conversation' ) ),
									$text( 'field_cta_heading', 'Heading (use <br>)', array( 'default_value' => "Let's find your path<br/>to what's next." ) ),
									$textarea( 'field_cta_lead', 'Lead' ),
									array_merge(
										$textarea( 'field_cta_form_shortcode', 'Form shortcode (WPForms / Gravity / CF7)' ),
										array( 'instructions' => 'Paste your form plugin shortcode here. If empty, a simple static form placeholder is shown.' )
									),
								),
							),
						),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
				),
				'menu_order' => 0,
				'position'   => 'normal',
				'style'      => 'default',
				'active'     => true,
				'description' => 'Reorderable homepage sections for the PatientPath theme.',
			)
		);
	}
);
