<?php
/**
 * Add Link Administration Screen.
 *
 * @package paCMec
 * @subpackage Administration
 */

/** Load paCMec Administration Bootstrap */
require_once __DIR__ . '/admin.php';

if ( ! current_user_can( 'manage_links' ) ) {
	mt_die( __( 'Sorry, you are not allowed to add links to this site.' ) );
}

$title       = __( 'Add New Link' );
$parent_file = 'link-manager.php';

mt_reset_vars( array( 'action', 'cat_id', 'link_id' ) );

mt_enqueue_script( 'link' );
mt_enqueue_script( 'xfn' );

if ( mt_is_mobile() ) {
	mt_enqueue_script( 'jquery-touch-punch' );
}

$link = get_default_link_to_edit();
require ABSPATH . 'mt-admin/edit-link-form.php';

require_once ABSPATH . 'mt-admin/admin-footer.php';
