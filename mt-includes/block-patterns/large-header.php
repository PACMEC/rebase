<?php
/**
 * Large header block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Large header with a heading' ),
	'content'       => "<!-- mt:cover {\"url\":\"https://s.w.org/images/core/5.5/don-quixote-06.jpg\",\"id\":165,\"dimRatio\":15,\"focalPoint\":{\"x\":\"0.40\",\"y\":\"0.26\"},\"minHeight\":375,\"minHeightUnit\":\"px\",\"contentPosition\":\"center center\",\"align\":\"wide\"} -->\n<div class=\"mt-block-cover alignwide has-background-dim-20 has-background-dim is-position-center-center\" style=\"background-image:url(https://s.w.org/images/core/5.5/don-quixote-06.jpg);min-height:375px;background-position:40% 26%\"><div class=\"mt-block-cover__inner-container\"><!-- mt:paragraph {\"align\":\"center\",\"placeholder\":\"" . __( 'Write title…' ) . "\",\"style\":{\"typography\":{\"fontSize\":74,\"lineHeight\":\"1.1\"},\"color\":{\"text\":\"#fffffa\"}}} -->\n<p class=\"has-text-align-center has-text-color\" style=\"line-height:1.1;font-size:74px;color:#fffffa\"><strong>" . __( 'Don Quixote' ) . "</strong></p>\n<!-- /mt:paragraph --></div></div>\n<!-- /mt:cover -->",
	'viemtortWidth' => 1000,
	'categories'    => array( 'header' ),
	'description'   => _x( 'A large hero section with an example background image and a heading in the center.', 'Block pattern description' ),
);
