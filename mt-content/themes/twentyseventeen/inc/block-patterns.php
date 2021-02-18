<?php
/**
 * Twenty Twenty Theme: Block Patterns
 *
 * @package paCMec
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 2.4
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'twentyseventeen',
		array( 'label' => __( 'Twenty Seventeen', 'twentyseventeen' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'twentyseventeen/large-heading-with-button',
		array(
			'title'      => __( 'Large Heading with Button', 'twentyseventeen' ),
			'categories' => array( 'twentyseventeen' ),
			'content'    => '<!-- mt:heading {"level":1,"textColor":"black","style":{"typography":{"fontSize":50}}} -->
            <h1 class="has-black-color has-text-color" style="font-size:50px">' . __( 'Attract Leads with Marketing Campaigns that Work', 'twentyseventeen' ) . '</h1>
            <!-- /mt:heading -->

            <!-- mt:buttons -->
            <div class="mt-block-buttons"><!-- mt:button {"borderRadius":0,"className":"is-style-fill"} -->
            <div class="mt-block-button is-style-fill"><a class="mt-block-button__link no-border-radius">' . __( 'Our Services', 'twentyseventeen' ) . '</a></div>
            <!-- /mt:button --></div>
            <!-- /mt:buttons -->',
		)
	);

	register_block_pattern(
		'twentyseventeen/images-with-text-and-link',
		array(
			'title'      => __( 'Images with Text and Link', 'twentyseventeen' ),
			'categories' => array( 'twentyseventeen' ),
			'content'    => '<!-- mt:spacer -->
            <div style="height:100px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->
            <!-- mt:columns -->
            <div class="mt-block-columns"><!-- mt:column -->
            <div class="mt-block-column">
			<!-- mt:image {"className":"size-large"} -->
			<figure class="mt-block-image size-large"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/stripes.jpg" alt="' . __( 'Black Stripes', 'twentyseventeen' ) . '"/></figure>
			<!-- /mt:image -->
            <!-- mt:heading {"textColor":"black","style":{"typography":{"fontSize":45}}} -->
            <h2 class="has-black-color has-text-color" style="font-size:45px">' . __( 'Branding', 'twentyseventeen' ) . '</h2>
            <!-- /mt:heading -->
            <!-- mt:paragraph {"textColor":"black","style":{"typography":{"lineHeight":"1.8"}}} -->
            <p class="has-black-color has-text-color" style="line-height:1.8">' . __( 'Communicate your purpose and goals with a beautiful logo that encapsulates your business.', 'twentyseventeen' ) . '</p>
            <!-- /mt:paragraph -->
            <!-- mt:paragraph {"style":{"typography":{"lineHeight":"3"}}} -->
            <p style="line-height:3"><a href="#"><strong>' . __( 'See Case Study', 'twentyseventeen' ) . ' →</strong></a></p>
            <!-- /mt:paragraph --></div>
            <!-- /mt:column -->
            <!-- mt:column -->
            <div class="mt-block-column"><!-- mt:spacer {"height":254} -->
            <div style="height:254px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->
			<!-- mt:image {"className":"size-large"} -->
			<figure class="mt-block-image size-large"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/white-border.jpg" alt="' . __( 'White border', 'twentyseventeen' ) . '"/></figure>
			<!-- /mt:image -->
            <!-- mt:heading {"textColor":"black","style":{"typography":{"fontSize":45}}} -->
            <h2 class="has-black-color has-text-color" style="font-size:45px">' . __( 'Web Design', 'twentyseventeen' ) . '</h2>
            <!-- /mt:heading -->
            <!-- mt:paragraph {"textColor":"black","style":{"typography":{"lineHeight":"1.8"}}} -->
            <p class="has-black-color has-text-color" style="line-height:1.8">' . __( 'Need a website? We&#39;ve got you covered. Our design team will create a stunning design to transform your brand.', 'twentyseventeen' ) . '</p>
            <!-- /mt:paragraph -->
            <!-- mt:paragraph {"style":{"typography":{"lineHeight":"3.0"}}} -->
            <p style="line-height:3.0"><a href="#"><strong>' . __( 'See Case Study', 'twentyseventeen' ) . ' →</strong></a></p>
            <!-- /mt:paragraph --></div>
            <!-- /mt:column --></div>
            <!-- /mt:columns -->',
		)
	);

	register_block_pattern(
		'twentyseventeen/images-with-link',
		array(
			'title'      => __( 'Images with Link', 'twentyseventeen' ),
			'categories' => array( 'twentyseventeen' ),
			'content'    => '<!-- mt:spacer -->
            <div style="height:100px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->
            <!-- mt:columns {"verticalAlignment":"top"} -->
            <div class="mt-block-columns are-vertically-aligned-top"><!-- mt:column -->
            <div class="mt-block-column"><!-- mt:group -->
            <div class="mt-block-group"><div class="mt-block-group__inner-container">
			<!-- mt:image {"align":"center","sizeSlug":"large","className":"is-style-default"} -->
			<div class="mt-block-image is-style-default"><figure class="aligncenter size-large"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/stripes.jpg" alt="' . __( 'Black Stripes', 'twentyseventeen' ) . '"/></figure></div>
			<!-- /mt:image -->
            <!-- mt:heading {"align":"left","textColor":"black","style":{"typography":{"fontSize":30}}} -->
            <h2 class="has-text-align-left has-black-color has-text-color" style="font-size:30px">' . __( 'Branding', 'twentyseventeen' ) . '</h2>
            <!-- /mt:heading -->
            <!-- mt:paragraph {"align":"left"} -->
            <p class="has-text-align-left"><a href="#">' . __( 'See Case Study', 'twentyseventeen' ) . ' →</a></p>
            <!-- /mt:paragraph --></div></div>
            <!-- /mt:group --></div>
            <!-- /mt:column -->
            <!-- mt:column -->
            <div class="mt-block-column"><!-- mt:group -->
            <div class="mt-block-group"><div class="mt-block-group__inner-container">
			<!-- mt:image {"align":"center","sizeSlug":"large","className":"is-style-default"} -->
			<div class="mt-block-image is-style-default"><figure class="aligncenter size-large"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/white-border.jpg" alt="' . __( 'White border', 'twentyseventeen' ) . '"/></figure></div>
			<!-- /mt:image -->
            <!-- mt:heading {"align":"left","textColor":"black","style":{"typography":{"fontSize":30}}} -->
            <h2 class="has-text-align-left has-black-color has-text-color" style="font-size:30px">' . __( 'Design', 'twentyseventeen' ) . '</h2>
            <!-- /mt:heading -->
            <!-- mt:paragraph {"align":"left"} -->
            <p class="has-text-align-left"><a href="#">' . __( 'See Case Study', 'twentyseventeen' ) . ' →</a></p>
            <!-- /mt:paragraph --></div></div>
            <!-- /mt:group --></div>
            <!-- /mt:column -->
            <!-- mt:column -->
            <div class="mt-block-column"><!-- mt:group -->
            <div class="mt-block-group"><div class="mt-block-group__inner-container">
			<!-- mt:image {"align":"center","sizeSlug":"large","className":"is-style-default"} -->
			<div class="mt-block-image is-style-default"><figure class="aligncenter size-large"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/direct-light.jpg" alt="' . __( 'Direct Light', 'twentyseventeen' ) . '"/></figure></div>
			<!-- /mt:image -->
            <!-- mt:heading {"align":"left","textColor":"black","style":{"typography":{"fontSize":30}}} -->
            <h2 class="has-text-align-left has-black-color has-text-color" style="font-size:30px">' . __( 'Strategy', 'twentyseventeen' ) . '</h2>
            <!-- /mt:heading -->
            <!-- mt:paragraph {"align":"left"} -->
            <p class="has-text-align-left"><a href="#">' . __( 'See Case Study' ) . ' →</a></p>
            <!-- /mt:paragraph --></div></div>
            <!-- /mt:group --></div>
            <!-- /mt:column --></div>
            <!-- /mt:columns -->
            <!-- mt:spacer -->
            <div style="height:100px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->',
		)
	);

	register_block_pattern(
		'twentyseventeen/services',
		array(
			'title'      => __( 'Services', 'twentyseventeen' ),
			'categories' => array( 'twentyseventeen' ),
			'content'    => '<!-- mt:spacer -->
            <div style="height:100px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->
            
            <!-- mt:heading {"level":1,"style":{"typography":{"fontSize":50}}} -->
            <h1 style="font-size:50px">' . __( 'Our Services', 'twentyseventeen' ) . '</h1>
            <!-- /mt:heading -->
            
            <!-- mt:columns -->
            <div class="mt-block-columns"><!-- mt:column -->
            <div class="mt-block-column">
            <!-- mt:paragraph {"style":{"typography":{"fontSize":21, "lineHeight":"2.5"}}} -->
            <p style="font-size:21px"><a href="#">' . __( 'Branding', 'twentyseventeen' ) . ' →</a><br><a href="#">' . __( 'Web Design', 'twentyseventeen' ) . ' →</a><br><a href="#">' . __( 'Web Development', 'twentyseventeen' ) . ' →</a></p>
            <!-- /mt:paragraph -->
            </div>
            <!-- /mt:column -->
            
            <!-- mt:column -->
            <div class="mt-block-column">
            <!-- mt:paragraph {"style":{"typography":{"fontSize":21, "lineHeight":"2.5"}}} -->
            <p style="font-size:21px"><a href="#">' . __( 'Content Strategy', 'twentyseventeen' ) . ' →</a><br><a href="#">' . __( 'Marketing &amp; SEO', 'twentyseventeen' ) . ' →</a><br><a href="#">' . __( 'Video Production', 'twentyseventeen' ) . ' →</a></p>
            <!-- /mt:paragraph --></div>
            <!-- /mt:column --></div>
            <!-- /mt:columns -->
            
            <!-- mt:spacer -->
            <div style="height:100px" aria-hidden="true" class="mt-block-spacer"></div>
            <!-- /mt:spacer -->',
		)
	);

	register_block_pattern(
		'twentyseventeen/contact-us',
		array(
			'title'      => __( 'Contact Us', 'twentyseventeen' ),
			'categories' => array( 'twentyseventeen' ),
			'content'    => '<!-- mt:cover {"customOverlayColor":"#93aab8","minHeight":700,"align":"center"} -->
            <div class="mt-block-cover aligncenter has-background-dim" style="background-color:#93aab8;min-height:700px"><div class="mt-block-cover__inner-container"><!-- mt:paragraph {"align":"left","textColor":"white","style":{"typography":{"fontSize":50}}} -->
            <p class="has-text-align-left has-white-color has-text-color" style="font-size:50px">' . __( 'We are proud to serve outstanding clients.', 'twentyseventeen' ) . '</p>
            <!-- /mt:paragraph -->
            
            <!-- mt:buttons -->
            <div class="mt-block-buttons"><!-- mt:button {"borderRadius":0,"backgroundColor":"black","textColor":"white","className":"is-style-fill"} -->
            <div class="mt-block-button is-style-fill"><a class="mt-block-button__link has-white-color has-black-background-color has-text-color has-background no-border-radius">' . __( 'Contact us', 'twentyseventeen' ) . '</a></div>
            <!-- /mt:button --></div>
            <!-- /mt:buttons --></div></div>
            <!-- /mt:cover -->',
		)
	);
}
