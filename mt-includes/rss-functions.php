<?php
/**
 * Deprecated. Use rss.php instead.
 *
 * @package paCMec
 * @deprecated 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

_deprecated_file( basename( __FILE__ ), '2.1.0', MTINC . '/rss.php' );
require_once ABSPATH . MTINC . '/rss.php';
