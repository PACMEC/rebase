<?php
/**
 * Three columns of text, each a button at the bottom block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Three columns of text with buttons' ),
	'categories'    => array( 'columns' ),
	'content'       => "<!-- mt:group {\"align\":\"wide\"} -->\n<div class=\"mt-block-group alignwide\"><div class=\"mt-block-group__inner-container\"><!-- mt:columns {\"align\":\"wide\"} -->\n<div class=\"mt-block-columns alignwide\"><!-- mt:column -->\n<div class=\"mt-block-column\"><!-- mt:paragraph -->\n<p>" . __( 'Which treats of the character and pursuits of the famous Don Quixote of La Mancha.' ) . "</p>\n<!-- /mt:paragraph -->\n\n<!-- mt:buttons -->\n<div class=\"mt-block-buttons\"><!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"text\":\"#ba0c49\"}},\"className\":\"is-style-outline\"} -->\n<div class=\"mt-block-button is-style-outline\"><a class=\"mt-block-button__link has-text-color\" style=\"border-radius:50px;color:#ba0c49\">" . __( 'Chapter One' ) . "</a></div>\n<!-- /mt:button --></div>\n<!-- /mt:buttons --></div>\n<!-- /mt:column -->\n\n<!-- mt:column -->\n<div class=\"mt-block-column\"><!-- mt:paragraph -->\n<p>" . __( 'Which treats of the first sally the ingenious Don Quixote made from home.' ) . "</p>\n<!-- /mt:paragraph -->\n\n<!-- mt:buttons -->\n<div class=\"mt-block-buttons\"><!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"text\":\"#ba0c49\"}},\"className\":\"is-style-outline\"} -->\n<div class=\"mt-block-button is-style-outline\"><a class=\"mt-block-button__link has-text-color\" style=\"border-radius:50px;color:#ba0c49\">" . __( 'Chapter Two' ) . "</a></div>\n<!-- /mt:button --></div>\n<!-- /mt:buttons --></div>\n<!-- /mt:column -->\n\n<!-- mt:column -->\n<div class=\"mt-block-column\"><!-- mt:paragraph -->\n<p>" . __( 'Wherein is related the droll way in which Don Quixote had himself dubbed a knight.' ) . "</p>\n<!-- /mt:paragraph -->\n\n<!-- mt:buttons -->\n<div class=\"mt-block-buttons\"><!-- mt:button {\"borderRadius\":50,\"style\":{\"color\":{\"text\":\"#ba0c49\"}},\"className\":\"is-style-outline\"} -->\n<div class=\"mt-block-button is-style-outline\"><a class=\"mt-block-button__link has-text-color\" style=\"border-radius:50px;color:#ba0c49\">" . __( 'Chapter Three' ) . "</a></div>\n<!-- /mt:button --></div>\n<!-- /mt:buttons --></div>\n<!-- /mt:column --></div>\n<!-- /mt:columns --></div></div>\n<!-- /mt:group -->",
	'viemtortWidth' => 1000,
	'description'   => _x( 'Three small columns of text, each with an outlined button with rounded corners at the bottom.', 'Block pattern description' ),
);
