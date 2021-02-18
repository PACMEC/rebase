<?php
/**
 * Loads the paCMec environment and template.
 *
 * @package paCMec
 */

if ( ! isset( $mt_did_header ) ) {

	$mt_did_header = true;

	// Load the paCMec library.
	require_once __DIR__ . '/mt-load.php';

	// Set up the paCMec query.
	mt();

	// Load the theme template.
	require_once ABSPATH . MTINC . '/template-loader.php';

}
