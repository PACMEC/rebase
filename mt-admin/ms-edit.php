<?php
/**
 * Action handler for Multisite administration panels.
 *
 * @package paCMec
 * @subpackage Multisite
 * @since 3.0.0
 */

require_once __DIR__ . '/admin.php';

mt_redirect( network_admin_url() );
exit;
