<?php
/**
 * Twenty Twenty Starter Content
 *
 * @link https://make.managertechnology.com.co/pacmec/core/2016/11/30/starter-content-for-themes-in-4-7/
 *
 * @package paCMec
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

/**
 * Function to return the array of starter content for the theme.
 *
 * Passes it through the `twentytwenty_starter_content` filter before returning.
 *
 * @since Twenty Twenty 1.0
 *
 * @return array A filtered array of args for the starter_content.
 */
function twentytwenty_get_starter_content() {

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => array(
			// Place one core-defined widgets in the first footer widget area.
			'sidebar-1' => array(
				'text_about',
			),
			// Place one core-defined widgets in the second footer widget area.
			'sidebar-2' => array(
				'text_business_info',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-opening' => array(
				'post_title' => _x( 'The New UMoMA Opens its Doors', 'Theme starter content', 'twentytwenty' ),
				'file'       => 'assets/images/2020-landscape-1.png', // URL relative to the template directory.
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts'       => array(
			'front' => array(
				'post_type'    => 'page',
				'post_title'   => __( 'The New UMoMA Opens its Doors', 'twentytwenty' ),
				// Use the above featured image with the predefined about page.
				'thumbnail'    => '{{image-opening}}',
				'post_content' => implode(
					'',
					array(
						'<!-- mt:group {"align":"wide"} -->',
						'<div class="mt-block-group alignwide"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center"} -->',
						'<h2 class="has-text-align-center">' . __( 'The premier destination for modern art in Northern Sweden. Open from 10 AM to 6 PM every day during the summer months.', 'twentytwenty' ) . '</h2>',
						'<!-- /mt:heading --></div></div>',
						'<!-- /mt:group -->',
						'<!-- mt:columns {"align":"wide"} -->',
						'<div class="mt-block-columns alignwide"><!-- mt:column -->',
						'<div class="mt-block-column"><!-- mt:group -->',
						'<div class="mt-block-group"><div class="mt-block-group__inner-container">',
						'<!-- mt:image {"align":"full","id":37,"sizeSlug":"full"} -->',
						'<figure class="mt-block-image alignfull size-full"><img src="' . get_theme_file_uri() . '/assets/images/2020-three-quarters-1.png" alt="" class="mt-image-37"/></figure>',
						'<!-- /mt:image -->',
						'<!-- mt:heading {"level":3} -->',
						'<h3>' . __( 'Works and Days', 'twentytwenty' ) . '</h3>',
						'<!-- /mt:heading -->',
						'<!-- mt:paragraph -->',
						'<p>' . __( 'August 1 -- December 1', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:button {"className":"is-style-outline"} -->',
						'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link" href="https://make.managertechnology.com.co/pacmec/core/2019/09/27/block-editor-theme-related-updates-in-pacmec-5-3/">' . __( 'Read More', 'twentytwenty' ) . '</a></div>',
						'<!-- /mt:button --></div></div>',
						'<!-- /mt:group -->',
						'<!-- mt:group -->',
						'<div class="mt-block-group"><div class="mt-block-group__inner-container">',
						'<!-- mt:image {"align":"full","id":37,"sizeSlug":"full"} -->',
						'<figure class="mt-block-image alignfull size-full"><img src="' . get_theme_file_uri() . '/assets/images/2020-three-quarters-3.png" alt="" class="mt-image-37"/></figure>',
						'<!-- /mt:image -->',
						'<!-- mt:heading {"level":3} -->',
						'<h3>' . __( 'Theatre of Operations', 'twentytwenty' ) . '</h3>',
						'<!-- /mt:heading -->',
						'<!-- mt:paragraph -->',
						'<p>' . __( 'October 1 -- December 1', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:button {"className":"is-style-outline"} -->',
						'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link" href="https://make.managertechnology.com.co/pacmec/core/2019/09/27/block-editor-theme-related-updates-in-pacmec-5-3/">' . __( 'Read More', 'twentytwenty' ) . '</a></div>',
						'<!-- /mt:button --></div></div>',
						'<!-- /mt:group --></div>',
						'<!-- /mt:column -->',
						'<!-- mt:column -->',
						'<div class="mt-block-column"><!-- mt:group -->',
						'<div class="mt-block-group"><div class="mt-block-group__inner-container">',
						'<!-- mt:image {"align":"full","id":37,"sizeSlug":"full"} -->',
						'<figure class="mt-block-image alignfull size-full"><img src="' . get_theme_file_uri() . '/assets/images/2020-three-quarters-2.png" alt="" class="mt-image-37"/></figure>',
						'<!-- /mt:image -->',
						'<!-- mt:heading {"level":3} -->',
						'<h3>' . __( 'The Life I Deserve', 'twentytwenty' ) . '</h3>',
						'<!-- /mt:heading -->',
						'<!-- mt:paragraph -->',
						'<p>' . __( 'August 1 -- December 1', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:button {"className":"is-style-outline"} -->',
						'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link" href="https://make.managertechnology.com.co/pacmec/core/2019/09/27/block-editor-theme-related-updates-in-pacmec-5-3/">' . __( 'Read More', 'twentytwenty' ) . '</a></div>',
						'<!-- /mt:button --></div></div>',
						'<!-- /mt:group -->',
						'<!-- mt:group -->',
						'<div class="mt-block-group"><div class="mt-block-group__inner-container">',
						'<!-- mt:image {"align":"full","id":37,"sizeSlug":"full"} -->',
						'<figure class="mt-block-image alignfull size-full"><img src="' . get_theme_file_uri() . '/assets/images/2020-three-quarters-4.png" alt="" class="mt-image-37"/></figure>',
						'<!-- /mt:image -->',
						'<!-- mt:heading {"level":3} -->',
						'<h3>' . __( 'From Signac to Matisse', 'twentytwenty' ) . '</h3>',
						'<!-- /mt:heading -->',
						'<!-- mt:paragraph -->',
						'<p>' . __( 'October 1 -- December 1', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:button {"className":"is-style-outline"} -->',
						'<div class="mt-block-button is-style-outline"><a class="mt-block-button__link" href="https://make.managertechnology.com.co/pacmec/core/2019/09/27/block-editor-theme-related-updates-in-pacmec-5-3/">' . __( 'Read More', 'twentytwenty' ) . '</a></div>',
						'<!-- /mt:button --></div></div>',
						'<!-- /mt:group --></div>',
						'<!-- /mt:column --></div>',
						'<!-- /mt:columns -->',
						'<!-- mt:image {"align":"full","id":37,"sizeSlug":"full"} -->',
						'<figure class="mt-block-image alignfull size-full"><img src="' . get_theme_file_uri() . '/assets/images/2020-landscape-2.png" alt="" class="mt-image-37"/></figure>',
						'<!-- /mt:image -->',
						'<!-- mt:group {"align":"wide"} -->',
						'<div class="mt-block-group alignwide"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center","textColor":"accent"} -->',
						'<h2 class="has-accent-color has-text-align-center">' . __( '&#8220;Cyborgs, as the philosopher Donna Haraway established, are not reverent. They do not remember the cosmos.&#8221;', 'twentytwenty' ) . '</h2>',
						'<!-- /mt:heading --></div></div>',
						'<!-- /mt:group -->',
						'<!-- mt:paragraph {"dropCap":true} -->',
						'<p class="has-drop-cap">' . __( 'With seven floors of striking architecture, UMoMA shows exhibitions of international contemporary art, sometimes along with art historical retrospectives. Existential, political and philosophical issues are intrinsic to our programme. As visitor you are invited to guided tours artist talks, lectures, film screenings and other events with free admission', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:paragraph -->',
						'<p>' . __( 'The exhibitions are produced by UMoMA in collaboration with artists and museums around the world and they often attract international attention. UMoMA has received a Special Commendation from the European Museum of the Year, and was among the top candidates for the Swedish Museum of the Year Award as well as for the Council of Europe Museum Prize.', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:paragraph -->',
						'<p></p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:group {"customBackgroundColor":"#ffffff","align":"wide"} -->',
						'<div class="mt-block-group alignwide has-background" style="background-color:#ffffff"><div class="mt-block-group__inner-container"><!-- mt:group -->',
						'<div class="mt-block-group"><div class="mt-block-group__inner-container"><!-- mt:heading {"align":"center"} -->',
						'<h2 class="has-text-align-center">' . __( 'Become a Member and Get Exclusive Offers!', 'twentytwenty' ) . '</h2>',
						'<!-- /mt:heading -->',
						'<!-- mt:paragraph {"align":"center"} -->',
						'<p class="has-text-align-center">' . __( 'Members get access to exclusive exhibits and sales. Our memberships cost $99.99 and are billed annually.', 'twentytwenty' ) . '</p>',
						'<!-- /mt:paragraph -->',
						'<!-- mt:button {"align":"center"} -->',
						'<div class="mt-block-button aligncenter"><a class="mt-block-button__link" href="https://make.managertechnology.com.co/pacmec/core/2019/09/27/block-editor-theme-related-updates-in-pacmec-5-3/">' . __( 'Join the Club', 'twentytwenty' ) . '</a></div>',
						'<!-- /mt:button --></div></div>',
						'<!-- /mt:group --></div></div>',
						'<!-- /mt:group -->',
						'<!-- mt:gallery {"ids":[39,38],"align":"wide"} -->',
						'<figure class="mt-block-gallery alignwide columns-2 is-cropped"><ul class="blocks-gallery-grid"><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri() . '/assets/images/2020-square-2.png" alt="" data-id="39" data-full-url="' . get_theme_file_uri() . '/assets/images/2020-square-2.png" data-link="assets/images/2020-square-2/" class="mt-image-39"/></figure></li><li class="blocks-gallery-item"><figure><img src="' . get_theme_file_uri() . '/assets/images/2020-square-1.png" alt="" data-id="38" data-full-url="' . get_theme_file_uri() . '/assets/images/2020-square-1.png" data-link="' . get_theme_file_uri() . '/assets/images/2020-square-1/" class="mt-image-38"/></figure></li></ul></figure>',
						'<!-- /mt:gallery -->',
					)
				),
			),
			'about',
			'contact',
			'blog',
		),

		// Default to a static front page and assign the front and posts pages.
		'options'     => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus'   => array(
			// Assign a menu to the "primary" location.
			'primary'  => array(
				'name'  => __( 'Primary', 'twentytwenty' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			// This replicates primary just to demonstrate the expanded menu.
			'expanded' => array(
				'name'  => __( 'Primary', 'twentytwenty' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			// Assign a menu to the "social" location.
			'social'   => array(
				'name'  => __( 'Social Links Menu', 'twentytwenty' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Twenty array of starter content.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @param array $starter_content Array of starter content.
	 */
	return apply_filters( 'twentytwenty_starter_content', $starter_content );

}
