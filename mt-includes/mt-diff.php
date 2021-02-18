<?php
/**
 * paCMec Diff bastard child of old MediaWiki Diff Formatter.
 *
 * Basically all that remains is the table structure and some method names.
 *
 * @package paCMec
 * @subpackage Diff
 */

if ( ! class_exists( 'Text_Diff', false ) ) {
	/** Text_Diff class */
	require ABSPATH . MTINC . '/Text/Diff.php';
	/** Text_Diff_Renderer class */
	require ABSPATH . MTINC . '/Text/Diff/Renderer.php';
	/** Text_Diff_Renderer_inline class */
	require ABSPATH . MTINC . '/Text/Diff/Renderer/inline.php';
}

require ABSPATH . MTINC . '/class-mt-text-diff-renderer-table.php';
require ABSPATH . MTINC . '/class-mt-text-diff-renderer-inline.php';
