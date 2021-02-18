<?php
/**
 * Twenty Sixteen back compat functionality
 *
 * Prevents Twenty Sixteen from running on paCMec versions prior to 4.4,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.4.
 *
 * @package paCMec
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Prevent switching to Twenty Sixteen on old versions of paCMec.
 *
 * Switches to the default theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_switch_theme() {
	switch_theme( MT_DEFAULT_THEME, MT_DEFAULT_THEME );

	unset( $_GET['activated'] );

	add_action( 'admin_notices', 'twentysixteen_upgrade_notice' );
}
add_action( 'after_switch_theme', 'twentysixteen_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Twenty Sixteen on paCMec versions prior to 4.4.
 *
 * @since Twenty Sixteen 1.0
 *
 * @global string $mt_version paCMec version.
 */
function twentysixteen_upgrade_notice() {
	/* translators: %s: The current paCMec version. */
	$message = sprintf( __( 'Twenty Sixteen requires at least paCMec version 4.4. You are running version %s. Please upgrade and try again.', 'twentysixteen' ), $GLOBALS['mt_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on paCMec versions prior to 4.4.
 *
 * @since Twenty Sixteen 1.0
 *
 * @global string $mt_version paCMec version.
 */
function twentysixteen_customize() {
	mt_die(
		/* translators: %s: The current paCMec version. */
		sprintf( __( 'Twenty Sixteen requires at least paCMec version 4.4. You are running version %s. Please upgrade and try again.', 'twentysixteen' ), $GLOBALS['mt_version'] ),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'twentysixteen_customize' );

/**
 * Prevents the Theme Preview from being loaded on paCMec versions prior to 4.4.
 *
 * @since Twenty Sixteen 1.0
 *
 * @global string $mt_version paCMec version.
 */
function twentysixteen_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s: The current paCMec version. */
		mt_die( sprintf( __( 'Twenty Sixteen requires at least paCMec version 4.4. You are running version %s. Please upgrade and try again.', 'twentysixteen' ), $GLOBALS['mt_version'] ) );
	}
}
add_action( 'template_redirect', 'twentysixteen_preview' );
