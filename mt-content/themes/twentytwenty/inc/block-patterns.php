<?php
/**
 * Block Patterns
 *
 * @link https://developer.managertechnology.com.co/pacmec/reference/functions/register_block_pattern/
 * @link https://developer.managertechnology.com.co/pacmec/reference/functions/register_block_pattern_category/
 *
 * @package paCMec
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.5
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'twentytwenty',
		array( 'label' => esc_html__( 'Twenty Twenty', 'twentytwenty' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {

	// Call to Action.
	register_block_pattern(
		'twentytwenty/call-to-action',
		array(
			'title'         => esc_html__( 'Call to Action', 'twentytwenty' ),
			'categories'    => array( 'twentytwenty' ),
			'viemtortWidth' => 1400,
			'content'       => implode(
				'',
				array(
					'<!-- mt:group {"align":"wide","style":{"color":{"background":"#ffffff"}}} -->',
					'<div class="mt-block-group alignwide has-background" style="background-color:#ffffff"><div class="mt-block-group__inner-container"><!-- mt:group -->',
					'<div class="mt-block-group"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center"} -->',
					'<h2 class="has-text-align-center">' . esc_html__( 'Support the Museum and Get Exclusive Offers', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"align":"center"} -->',
					'<p class="has-text-align-center">' . esc_html__( 'Members get access to exclusive exhibits and sales. Our memberships cost $99.99 and are billed annually.', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
					'<!-- mt:button {"align":"center","className":"is-style-outline"} -->',
					'<div class="mt-block-button aligncenter is-style-outline"><a class="mt-block-button__link" href="#">' . esc_html__( 'Become a Member', 'twentytwenty' ) . '</a></div>',
					'<!-- /mt:button --></div></div>',
					'<!-- /mt:group --></div></div>',
					'<!-- /mt:group -->',
				)
			),
		)
	);

	// Double Call to Action.
	register_block_pattern(
		'twentytwenty/double-call-to-action',
		array(
			'title'         => esc_html__( 'Double Call to Action', 'twentytwenty' ),
			'categories'    => array( 'twentytwenty' ),
			'viemtortWidth' => 1400,
			'content'       => implode(
				'',
				array(
					'<!-- mt:columns {"align":"wide"} -->',
					'<div class="mt-block-columns alignwide"><!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:group {"style":{"color":{"background":"#ffffff"}}} -->',
					'<div class="mt-block-group has-background" style="background-color:#ffffff"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center"} -->',
					'<h2 class="has-text-align-center">' . esc_html__( 'The Museum', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"align":"center"} -->',
					'<p class="has-text-align-center">' . esc_html__( 'Award-winning exhibitions featuring internationally-renowned artists.', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
					'<!-- mt:buttons {"align":"center"} -->',
					'<div class="mt-block-buttons aligncenter"><!-- mt:button {"className":"is-style-outline"} -->',
					'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link">' . esc_html__( 'Read More', 'twentytwenty' ) . '</a></div>',
					'<!-- /mt:button --></div>',
					'<!-- /mt:buttons --></div></div>',
					'<!-- /mt:group --></div>',
					'<!-- /mt:column -->',
					'<!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:group {"style":{"color":{"background":"#ffffff"}}} -->',
					'<div class="mt-block-group has-background" style="background-color:#ffffff"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center"} -->',
					'<h2 class="has-text-align-center">' . esc_html__( 'The Store', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"align":"center"} -->',
					'<p class="has-text-align-center">' . esc_html__( 'An awe-inspiring collection of books, prints, and gifts from our exhibitions.', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
					'<!-- mt:buttons {"align":"center"} -->',
					'<div class="mt-block-buttons aligncenter"><!-- mt:button {"className":"is-style-outline"} -->',
					'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link">' . esc_html__( 'Shop Now', 'twentytwenty' ) . '</a></div>',
					'<!-- /mt:button --></div>',
					'<!-- /mt:buttons --></div></div>',
					'<!-- /mt:group --></div>',
					'<!-- /mt:column --></div>',
					'<!-- /mt:columns -->',
				)
			),
		)
	);

	// Event Details.
	register_block_pattern(
		'twentytwenty/event-details',
		array(
			'title'         => esc_html__( 'Event Details', 'twentytwenty' ),
			'categories'    => array( 'twentytwenty' ),
			'viemtortWidth' => 1400,
			'content'       => implode(
				'',
				array(
					'<!-- mt:group {"align":"wide","backgroundColor":"primary"} -->',
					'<div class="mt-block-group alignwide has-primary-background-color has-background"><div class="mt-block-group__inner-container"><!-- mt:columns -->',
					'<div class="mt-block-columns"><!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:paragraph {"align":"center","textColor":"background","fontSize":"large"} -->',
					'<p class="has-text-align-center has-background-color has-text-color has-large-font-size">' . mt_kses_post( __( '<em>Dates</em><br>Aug 1 — Dec 1', 'twentytwenty' ) ) . '</p>',
					'<!-- /mt:paragraph --></div>',
					'<!-- /mt:column -->',
					'<!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:paragraph {"align":"center","textColor":"background","fontSize":"large"} -->',
					'<p class="has-text-align-center has-background-color has-text-color has-large-font-size">' . mt_kses_post( __( '<em>Location</em><br>Exhibit Hall B', 'twentytwenty' ) ) . '</p>',
					'<!-- /mt:paragraph --></div>',
					'<!-- /mt:column -->',
					'<!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:paragraph {"align":"center","textColor":"background","fontSize":"large"} -->',
					'<p class="has-text-align-center has-background-color has-text-color has-large-font-size">' . mt_kses_post( __( '<em>Price</em><br>Included', 'twentytwenty' ) ) . '</p>',
					'<!-- /mt:paragraph --></div>',
					'<!-- /mt:column --></div>',
					'<!-- /mt:columns --></div></div>',
					'<!-- /mt:group -->',
				)
			),
		)
	);

	// Featured Content.
	register_block_pattern(
		'twentytwenty/featured-content',
		array(
			'title'         => esc_html__( 'Featured Content', 'twentytwenty' ),
			'categories'    => array( 'twentytwenty' ),
			'viemtortWidth' => 1400,
			'content'       => implode(
				'',
				array(
					'<!-- mt:columns {"align":"wide"} -->',
					'<div class="mt-block-columns alignwide"><!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:image {"sizeSlug":"full"} -->',
					'<figure class="mt-block-image size-full"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/2020-three-quarters-1.png" alt="' . esc_attr__( 'Abstract Rectangles', 'twentytwenty' ) . '"/></figure>',
					'<!-- /mt:image -->',
					'<!-- mt:heading -->',
					'<h2>' . esc_html__( 'Works and Days', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"fontSize":"larger"} -->',
					'<p class="has-larger-font-size">' . esc_html__( 'August 1 — December 1', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
					'<!-- mt:button {"align":"left","className":"is-style-outline"} -->',
					'<div class="mt-block-button alignleft is-style-outline"><a class="mt-block-button__link" href="#">' . esc_html__( 'Read More', 'twentytwenty' ) . '</a></div>',
					'<!-- /mt:button --></div>',
					'<!-- /mt:column -->',
					'<!-- mt:column -->',
					'<div class="mt-block-column"><!-- mt:image {sizeSlug":"full"} -->',
					'<figure class="mt-block-image size-full"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/2020-three-quarters-2.png" alt="' . esc_attr__( 'Abstract Rectangles', 'twentytwenty' ) . '"/></figure>',
					'<!-- /mt:image -->',
					'<!-- mt:heading -->',
					'<h2>' . esc_html__( 'The Life I Deserve', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"fontSize":"larger"} -->',
					'<p class="has-larger-font-size">' . esc_html__( 'August 1 — December 1', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
					'<!-- mt:button {"align":"left","className":"is-style-outline"} -->',
					'<div class="mt-block-button alignleft is-style-outline"><a class="mt-block-button__link" href="#">' . esc_html__( 'Read More', 'twentytwenty' ) . '</a></div>',
					'<!-- /mt:button --></div>',
					'<!-- /mt:column --></div>',
					'<!-- /mt:columns -->',
				)
			),
		)
	);

	// Introduction.
	register_block_pattern(
		'twentytwenty/introduction',
		array(
			'title'         => esc_html__( 'Introduction', 'twentytwenty' ),
			'categories'    => array( 'twentytwenty' ),
			'viemtortWidth' => 1400,
			'content'       => implode(
				'',
				array(
					'<!-- mt:heading {"align":"center"} -->',
					'<h2 class="has-text-align-center">' . esc_html__( 'The Premier Destination for Modern Art in Sweden', 'twentytwenty' ) . '</h2>',
					'<!-- /mt:heading -->',
					'<!-- mt:paragraph {"dropCap":true} -->',
					'<p class="has-drop-cap">' . esc_html__( 'With seven floors of striking architecture, UMoMA shows exhibitions of international contemporary art, sometimes along with art historical retrospectives. Existential, political, and philosophical issues are intrinsic to our program. As visitor, you are invited to guided tours artist talks, lectures, film screenings, and other events with free admission.', 'twentytwenty' ) . '</p>',
					'<!-- /mt:paragraph -->',
				)
			),
		)
	);
}
