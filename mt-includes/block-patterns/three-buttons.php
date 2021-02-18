<?php
/**
 * Three Buttons block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Three buttons' ),
	'content'       => "<!-- mt:buttons {\"align\":\"center\"} -->\n<div class=\"mt-block-buttons aligncenter\"><!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"gradient\":\"linear-gradient(135deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%)\",\"text\":\"#fffffa\"}}} -->\n<div class=\"mt-block-button\"><a class=\"mt-block-button__link has-text-color has-background\" style=\"border-radius:50px;background:linear-gradient(135deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%);color:#fffffa\">" . __( 'About Cervantes' ) . "</a></div>\n<!-- /mt:button -->\n\n<!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"gradient\":\"linear-gradient(317deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%)\",\"text\":\"#fffffa\"}}} -->\n<div class=\"mt-block-button\"><a class=\"mt-block-button__link has-text-color has-background\" style=\"border-radius:50px;background:linear-gradient(317deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%);color:#fffffa\">" . __( 'Contact us' ) . "</a></div>\n<!-- /mt:button -->\n\n<!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"gradient\":\"linear-gradient(42deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%)\",\"text\":\"#fffffa\"}}} -->\n<div class=\"mt-block-button\"><a class=\"mt-block-button__link has-text-color has-background\" style=\"border-radius:50px;background:linear-gradient(42deg,rgb(135,9,53) 0%,rgb(179,22,22) 100%);color:#fffffa\">" . __( 'Books' ) . "</a></div>\n<!-- /mt:button --></div>\n<!-- /mt:buttons -->",
	'viemtortWidth' => 600,
	'categories'    => array( 'buttons' ),
	'description'   => _x( 'Three filled buttons with rounded corners, side by side.', 'Block pattern description' ),
);
