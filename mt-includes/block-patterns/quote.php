<?php
/**
 * Quote block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Quote' ),
	'content'       => "<!-- mt:group -->\n<div class=\"mt-block-group\"><div class=\"mt-block-group__inner-container\"><!-- mt:image {\"align\":\"center\",\"width\":164,\"height\":164,\"sizeSlug\":\"large\",\"className\":\"is-style-rounded\"} -->\n<div class=\"mt-block-image is-style-rounded\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://s.w.org/images/core/5.5/don-quixote-03.jpg\" alt=\"" . __( 'Pencil drawing of Don Quixote' ) . "\" width=\"164\" height=\"164\"/></figure></div>\n<!-- /mt:image -->\n\n<!-- mt:quote {\"align\":\"center\",\"className\":\"is-style-large\"} -->\n<blockquote class=\"mt-block-quote has-text-align-center is-style-large\"><p>" . __( '"Do you see over yonder, friend Sancho, thirty or forty hulking giants? I intend to do battle with them and slay them."' ) . '</p><cite>' . __( '— Don Quixote' ) . "</cite></blockquote>\n<!-- /mt:quote -->\n\n<!-- mt:separator {\"className\":\"is-style-dots\"} -->\n<hr class=\"mt-block-separator is-style-dots\"/>\n<!-- /mt:separator --></div></div>\n<!-- /mt:group -->",
	'viemtortWidth' => 800,
	'categories'    => array( 'text' ),
	'description'   => _x( 'A quote and citation with an image above, and a separator at the bottom.', 'Block pattern description' ),
);
