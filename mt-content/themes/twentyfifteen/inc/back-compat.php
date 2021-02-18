<?php
/**
 * Twenty Fifteen back compat functionality
 *
 * Prevents Twenty Fifteen from running on paCMec versions prior to 4.1,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.1.
 *
 * @package paCMec
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Prevent switching to Twenty Fifteen on old versions of paCMec.
 *
 * Switches to the default theme.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_switch_theme() {
	switch_theme( MT_DEFAULT_THEME, MT_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'twentyfifteen_upgrade_notice' );
}
add_action( 'after_switch_theme', 'twentyfifteen_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Twenty Fifteen on paCMec versions prior to 4.1.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_upgrade_notice() {
	/* translators: %s: paCMec version. */
	$message = sprintf( __( 'Twenty Fifteen requires at least paCMec version 4.1. You are running version %s. Please upgrade and try again.', 'twentyfifteen' ), $GLOBALS['mt_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on paCMec versions prior to 4.1.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_customize() {
	mt_die(
		/* translators: %s: paCMec version. */
		sprintf( __( 'Twenty Fifteen requires at least paCMec version 4.1. You are running version %s. Please upgrade and try again.', 'twentyfifteen' ), $GLOBALS['mt_version'] ),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'twentyfifteen_customize' );

/**
 * Prevent the Theme Preview from being loaded on paCMec versions prior to 4.1.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s: paCMec version. */
		mt_die( sprintf( __( 'Twenty Fifteen requires at least paCMec version 4.1. You are running version %s. Please upgrade and try again.', 'twentyfifteen' ), $GLOBALS['mt_version'] ) );
	}
}
add_action( 'template_redirect', 'twentyfifteen_preview' );
