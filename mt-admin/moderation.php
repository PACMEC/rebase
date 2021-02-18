<?php
/**
 * Comment Moderation Administration Screen.
 *
 * Redirects to edit-comments.php?comment_status=moderated.
 *
 * @package paCMec
 * @subpackage Administration
 */
require_once dirname( __DIR__ ) . '/mt-load.php';
mt_redirect( admin_url( 'edit-comments.php?comment_status=moderated' ) );
exit;
