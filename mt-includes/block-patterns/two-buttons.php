<?php
/**
 * Two Buttons block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Two buttons' ),
	'content'       => "<!-- mt:buttons {\"align\":\"center\"} -->\n<div class=\"mt-block-buttons aligncenter\"><!-- mt:button {\"borderRadius\":2,\"style\":{\"color\":{\"background\":\"#ba0c49\",\"text\":\"#fffffa\"}}} -->\n<div class=\"mt-block-button\"><a class=\"mt-block-button__link has-text-color has-background\" style=\"border-radius:2px;background-color:#ba0c49;color:#fffffa\">" . __( 'Download now' ) . "</a></div>\n<!-- /mt:button -->\n\n<!-- mt:button {\"borderRadius\":2,\"style\":{\"color\":{\"text\":\"#ba0c49\"}},\"className\":\"is-style-outline\"} -->\n<div class=\"mt-block-button is-style-outline\"><a class=\"mt-block-button__link has-text-color\" style=\"border-radius:2px;color:#ba0c49\">" . __( 'About Cervantes' ) . "</a></div>\n<!-- /mt:button --></div>\n<!-- /mt:buttons -->",
	'viemtortWidth' => 500,
	'categories'    => array( 'buttons' ),
	'description'   => _x( 'Two buttons, one filled and one outlined, side by side.', 'Block pattern description' ),
);
