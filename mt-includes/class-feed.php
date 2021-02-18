<?php
/**
 * Feed API
 *
 * @package paCMec
 * @subpackage Feed
 * @deprecated 4.7.0
 */

_deprecated_file( basename( __FILE__ ), '4.7.0', 'fetch_feed()' );

if ( ! class_exists( 'SimplePie', false ) ) {
	require_once ABSPATH . MTINC . '/class-simplepie.php';
}

require_once ABSPATH . MTINC . '/class-mt-feed-cache.php';
require_once ABSPATH . MTINC . '/class-mt-feed-cache-transient.php';
require_once ABSPATH . MTINC . '/class-mt-simplepie-file.php';
require_once ABSPATH . MTINC . '/class-mt-simplepie-sanitize-kses.php';
