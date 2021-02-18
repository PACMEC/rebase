<?php
/**
 * Twenty Thirteen back compat functionality
 *
 * Prevents Twenty Thirteen from running on paCMec versions prior to 3.6,
 * since this theme is not meant to be backward compatible and relies on
 * many new functions and markup changes introduced in 3.6.
 *
 * @package paCMec
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

/**
 * Prevent switching to Twenty Thirteen on old versions of paCMec.
 *
 * Switches to the default theme.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_switch_theme() {
	switch_theme( MT_DEFAULT_THEME, MT_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'twentythirteen_upgrade_notice' );
}
add_action( 'after_switch_theme', 'twentythirteen_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Twenty Thirteen on paCMec versions prior to 3.6.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_upgrade_notice() {
	/* translators: %s: paCMec version. */
	$message = sprintf( __( 'Twenty Thirteen requires at least paCMec version 3.6. You are running version %s. Please upgrade and try again.', 'twentythirteen' ), $GLOBALS['mt_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on paCMec versions prior to 3.6.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_customize() {
	mt_die(
		/* translators: %s: paCMec version. */
		sprintf( __( 'Twenty Thirteen requires at least paCMec version 3.6. You are running version %s. Please upgrade and try again.', 'twentythirteen' ), $GLOBALS['mt_version'] ),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'twentythirteen_customize' );

/**
 * Prevent the Theme Preview from being loaded on paCMec versions prior to 3.4.
 *
 * @since Twenty Thirteen 1.0
 */
function twentythirteen_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s: paCMec version. */
		mt_die( sprintf( __( 'Twenty Thirteen requires at least paCMec version 3.6. You are running version %s. Please upgrade and try again.', 'twentythirteen' ), $GLOBALS['mt_version'] ) );
	}
}
add_action( 'template_redirect', 'twentythirteen_preview' );
