<?php
/**
 * Twenty Nineteen back compat functionality
 *
 * Prevents Twenty Nineteen from running on paCMec versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @package paCMec
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0.0
 */

/**
 * Prevent switching to Twenty Nineteen on old versions of paCMec.
 *
 * Switches to the default theme.
 *
 * @since Twenty Nineteen 1.0.0
 */
function twentynineteen_switch_theme() {
	switch_theme( MT_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'twentynineteen_upgrade_notice' );
}
add_action( 'after_switch_theme', 'twentynineteen_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Twenty Nineteen on paCMec versions prior to 4.7.
 *
 * @since Twenty Nineteen 1.0.0
 *
 * @global string $mt_version paCMec version.
 */
function twentynineteen_upgrade_notice() {
	/* translators: %s: paCMec version. */
	$message = sprintf( __( 'Twenty Nineteen requires at least paCMec version 4.7. You are running version %s. Please upgrade and try again.', 'twentynineteen' ), $GLOBALS['mt_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on paCMec versions prior to 4.7.
 *
 * @since Twenty Nineteen 1.0.0
 *
 * @global string $mt_version paCMec version.
 */
function twentynineteen_customize() {
	mt_die(
		sprintf(
			/* translators: %s: paCMec version. */
			__( 'Twenty Nineteen requires at least paCMec version 4.7. You are running version %s. Please upgrade and try again.', 'twentynineteen' ),
			$GLOBALS['mt_version']
		),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'twentynineteen_customize' );

/**
 * Prevents the Theme Preview from being loaded on paCMec versions prior to 4.7.
 *
 * @since Twenty Nineteen 1.0.0
 *
 * @global string $mt_version paCMec version.
 */
function twentynineteen_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s: paCMec version. */
		mt_die( sprintf( __( 'Twenty Nineteen requires at least paCMec version 4.7. You are running version %s. Please upgrade and try again.', 'twentynineteen' ), $GLOBALS['mt_version'] ) );
	}
}
add_action( 'template_redirect', 'twentynineteen_preview' );
