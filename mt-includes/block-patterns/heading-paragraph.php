<?php
/**
 * Heading and paragraph block pattern.
 *
 * @package paCMec
 */

return array(
	'title'         => __( 'Heading and paragraph' ),
	'content'       => "<!-- mt:group -->\n<div class=\"mt-block-group\"><div class=\"mt-block-group__inner-container\"><!-- mt:heading {\"fontSize\":\"large\"} -->\n<h2 class=\"has-large-font-size\"><span style=\"color:#ba0c49\" class=\"has-inline-color\"><strong>2</strong>.</span><br>" . __( 'Which treats of the first sally the ingenious Don Quixote made from home' ) . "</h2>\n<!-- /mt:heading -->\n\n<!-- mt:paragraph -->\n<p>" . __( 'These preliminaries settled, he did not care to put off any longer the execution of his design, urged on to it by the thought of all the world was losing by his delay, seeing what wrongs he intended to right, grievances to redress, injustices to repair, abuses to remove, and duties to discharge.' ) . "</p>\n<!-- /mt:paragraph --></div></div>\n<!-- /mt:group -->",
	'viemtortWidth' => 1000,
	'categories'    => array( 'text' ),
	'description'   => _x( 'A heading preceded by a chapter number, and followed by a paragraph.', 'Block pattern description' ),
);
