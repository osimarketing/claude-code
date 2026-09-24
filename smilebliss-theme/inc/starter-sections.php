<?php
/**
 * The starter content payload.
 *
 * Copy and figures are lifted verbatim from the approved static build. The
 * proforma numbers in particular are the figures of record and should only be
 * changed alongside the disclaimer that qualifies them.
 *
 * @package Smilebliss
 */

declare( strict_types = 1 );

/**
 * @param callable $img Maps a bundled filename to an attachment ID.
 * @return array<int,array<string,mixed>> Flexible Content rows.
 */
function smilebliss_starter_sections( callable $img ): array {
	return array(
		array(
			'acf_fc_layout' => 'hero',
			'title'         => 'Follow Your Bliss.<br>',
			'title_accent'  => 'Find More Success.',
			'lead'          => 'Starting an orthodontic practice is tough. There are lots of things to do. Pick a name. Find a location. Hire the right people. Create marketing. Get equipment. Then, make sure you\'re all set up with the insurance companies so that you get paid. It\'s a lot — and that\'s not even half of it. We have over 30 years of experience creating successful practices. So we took that knowledge and created a ready-to-go practice model with everything done for you.',
			'primary_cta'   => array( 'title' => "Let's Do This", 'url' => '#contact', 'target' => '' ),
			'secondary_cta' => array( 'title' => 'See How It Works', 'url' => '#support', 'target' => '' ),
			'image'         => $img( 'hero-smile-1600.webp' ),
			'badge_value'   => '$5.1M',
			'badge_label'   => 'Year 3 revenue, best-case scenario',
			'anchor'        => 'top',
		),
		array(
			'acf_fc_layout' => 'trust_cards',
			'cards'         => array(
				array( 'icon' => $img( 'icon-together.webp' ), 'title' => 'You\'re Never Alone', 'text' => 'Marketing, RCM, procurement, and training — handled for you, every step of the way.', 'style' => 'coral' ),
				array( 'icon' => $img( 'icon-practice.webp' ), 'title' => 'A Proven Model', 'text' => '30+ years of experience building successful practices, packaged and ready to go.', 'style' => 'mint' ),
				array( 'icon' => $img( 'icon-agreement.webp' ), 'title' => 'Billions Collected', 'text' => 'The secret to success is getting paid for the work you do — we make sure you do.', 'style' => 'ink' ),
			),
		),
		array(
			'acf_fc_layout' => 'about',
			'image'         => $img( 'about-doctor-1740.webp' ),
			'chip_text'     => 'Award-winning, peer-to-peer support network',
			'eyebrow'       => 'About Smilebliss',
			'title'         => 'As a Smilebliss client,',
			'title_muted'   => "you're never in it alone.",
			'body'          => 'Here, we go out of our way to make your job — and that of your entire team — easier with support geared toward growing, successful practice needs. From marketing, training, and procurement to revenue cycle management, we handle the heavy lifting.',
			'stats'         => array(
				array( 'value' => 30, 'prefix' => '', 'suffix' => '+', 'label' => 'Years of experience building practices' ),
				array( 'value' => 1, 'prefix' => '$', 'suffix' => 'B+', 'label' => 'Collected in revenue for clients' ),
				array( 'value' => 50, 'prefix' => '', 'suffix' => '+', 'label' => 'Practice partners nationwide' ),
			),
			'anchor'        => 'alone-section',
		),
		array(
			'acf_fc_layout' => 'services',
			'eyebrow'       => 'What We Handle',
			'title'         => 'Five Ways We Carry the Load',
			'intro'         => 'A proven support system built for growing practices — so you can focus on patients, not paperwork.',
			'pillars'       => array(
				array(
					'icon'  => $img( 'icon-rcm.webp' ),
					'title' => 'Revenue Cycle Management',
					'text'  => 'The secret to success is getting paid for the work you do. Our team has collected billions for clients.',
					'link'  => array( 'title' => 'Talk to us about this', 'url' => '#contact', 'target' => '' ),
				),
				array(
					'icon'  => $img( 'icon-procurement.webp' ),
					'title' => 'Procurement',
					'text'  => 'If you like deals, we know how to get them. Our all-in-one ordering system gets you negotiated rates on equipment and supplies.',
					'link'  => array( 'title' => 'Talk to us about this', 'url' => '#contact', 'target' => '' ),
				),
				array(
					'icon'  => $img( 'icon-peer.webp' ),
					'title' => 'Peer Support',
					'text'  => 'Run your own practice without feeling like you\'re in it alone, backed by our award-winning team and peer-to-peer network.',
					'link'  => array( 'title' => 'Talk to us about this', 'url' => '#contact', 'target' => '' ),
				),
				array(
					'icon'  => $img( 'icon-marketing.webp' ),
					'title' => 'Marketing',
					'text'  => 'Nothing grows a practice faster than incredible marketing. Our strategists, designers, and copywriters get people signing up for treatment.',
					'link'  => array( 'title' => 'Talk to us about this', 'url' => '#contact', 'target' => '' ),
				),
				array(
					'icon'  => $img( 'icon-training.webp' ),
					'title' => 'Training',
					'text'  => 'Covering practice culture and brand, new patient process, scheduling, efficiencies, and adding patient value — for every role on your team.',
					'link'  => array( 'title' => 'Talk to us about this', 'url' => '#contact', 'target' => '' ),
				),
			),
			'anchor'        => 'support',
		),
		array(
			'acf_fc_layout' => 'revenue',
			'eyebrow'       => 'The Numbers',
			'title'         => 'License Model',
			'title_muted'   => 'Proforma.',
			'intro'         => 'Start-Up Practice',
			'years'         => array(
				array( 'label' => 'Year 1', 'base_revenue' => 401012, 'base_starts' => 500, 'best_revenue' => 721822, 'best_starts' => 900 ),
				array( 'label' => 'Year 2', 'base_revenue' => 1805244, 'base_starts' => 1500, 'best_revenue' => 2647921, 'best_starts' => 1950 ),
				array( 'label' => 'Year 3', 'base_revenue' => 3895784, 'base_starts' => 2000, 'best_revenue' => 5146670, 'best_starts' => 2400 ),
			),
			'meta_pills'    => array(
				array( 'text' => 'Year 3 base is 9.7× year 1' ),
				array( 'text' => 'Best case adds $1.25M in year 3' ),
				array( 'text' => '4 production days per week' ),
			),
			'disclaimer'    => 'Estimated results based on a brand-new practice establishing the direct-to-consumer pricing model with an average of 4 production days per week. Individual results may vary and should not be considered a guarantee of results.',
			'anchor'        => 'revenue',
		),
		array(
			'acf_fc_layout' => 'benefits',
			'eyebrow'       => 'Why Smilebliss',
			'title'         => 'Our Benefits Will Make You Smile as Much as Your Patients.',
			'items'         => array(
				array( 'text' => 'Jump-start your practice growth or revitalize your existing one.' ),
				array( 'text' => 'Gain greater brand awareness with marketing services that get people in the door and in your chair.' ),
				array( 'text' => 'Get access to established and proven practice operations.' ),
				array( 'text' => 'Save money on equipment and supplies.' ),
				array( 'text' => 'Get expert help with training programs, procurement, insurance, and billing.' ),
			),
			'image'         => $img( 'team-practice-1740.webp' ),
			'anchor'        => 'benefits',
		),
		array(
			'acf_fc_layout' => 'paths',
			'eyebrow'       => 'Two Ways In',
			'title'         => "Whichever Path You Choose, We've Built the Road.",
			'cards'         => array(
				array(
					'title' => 'New Practice',
					'style' => 'new',
					'text'  => 'Brand, training, site selection, financial modeling, and marketing support to launch your practice from scratch — with a proven playbook behind every decision.',
					'link'  => array( 'title' => 'Explore New Practice', 'url' => '#contact', 'target' => '' ),
				),
				array(
					'title' => 'Practice Conversion',
					'style' => 'conv',
					'text'  => 'Convert your existing orthodontic practice into a Smilebliss location, with full access to the same proven systems our new-practice partners rely on.',
					'link'  => array( 'title' => 'Explore Conversion', 'url' => '#contact', 'target' => '' ),
				),
			),
			'anchor'        => 'paths',
		),
		array(
			'acf_fc_layout' => 'testimonials',
			'eyebrow'       => 'Practice Owners',
			'title'         => 'Hear From Our Practice Owners.',
			'intro'         => 'Sample profiles — swap in your own client stories and headshots.',
			'items'         => array(
				array( 'initials' => 'AR', 'quote' => 'Smilebliss handed us a marketing engine and a billing team on day one. We hit our Year 1 start goal three months early.', 'name' => 'Dr. Alicia Reyes', 'location' => 'Scottsdale, AZ', 'is_sample' => 1 ),
				array( 'initials' => 'MK', 'quote' => 'Converting to Smilebliss felt like exhaling. Procurement alone saved us more than the licensing fee in the first year.', 'name' => 'Dr. Marcus Kim', 'location' => 'Charlotte, NC', 'is_sample' => 1 ),
				array( 'initials' => 'JT', 'quote' => 'The peer network is the real unlock. I\'ve never once felt like I was figuring this out on my own.', 'name' => 'Dr. Jasmine Torres', 'location' => 'Round Rock, TX', 'is_sample' => 1 ),
			),
			'anchor'        => 'testimonials',
		),
		array(
			'acf_fc_layout' => 'checklist',
			'title'         => 'Is Smilebliss Right for Me?',
			'questions'     => array(
				array( 'question' => 'Do I really want to take control of my own destiny?' ),
				array( 'question' => 'Am I prepared to adapt to new ideas?' ),
				array( 'question' => 'Do I have a genuine interest in improving the lives of others?' ),
				array( 'question' => 'Do I have great clinical results?' ),
				array( 'question' => 'Do I have a strong commitment to employee and customer satisfaction?' ),
			),
			'closing'       => 'If you answered YES to these questions, congrats! You just met the initial criteria for this incredible, proven model.',
			'anchor'        => 'checklist-section',
		),
		array(
			'acf_fc_layout' => 'cta_form',
			'eyebrow'       => 'Ready When You Are',
			'title'         => 'Find Success by Following Your Bliss.',
			'intro'         => 'Tell us a little about you and your practice goals. A member of our team will follow up to walk through next steps.',
			'trust_points'  => array(
				array( 'text' => '30+ years building successful practices' ),
				array( 'text' => 'Billions collected for clients' ),
				array( 'text' => 'A proven, ready-to-go model' ),
			),
			'submit_label'  => "Let's Do This",
			'success_title' => 'Thanks — we\'ve got it.',
			'success_text'  => 'A member of the Smilebliss team will be in touch shortly.',
			'form_note'     => 'By submitting, you agree to be contacted by the Smilebliss team about this inquiry.',
			'anchor'        => 'contact',
		),
	);
}
