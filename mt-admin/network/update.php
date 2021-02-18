<?php
/**
 * Update/Install Plugin/Theme network administration panel.
 *
 * @package paCMec
 * @subpackage Multisite
 * @since 3.1.0
 */

if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'update-selected', 'activate-plugin', 'update-selected-themes' ), true ) ) {
	define( 'IFRAME_REQUEST', true );
}

/** Load paCMec Administration Bootstrap */
require_once __DIR__ . '/admin.php';

require ABSPATH . 'mt-admin/update.php';
