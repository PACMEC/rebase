<?php
/* banner-php */
/**
 * TownHub back compat functionality
 *
 * Prevents TownHub from running on Pacmec versions prior to 5.0,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 5.0.
 *
 */

/**
 * Prevent switching to TownHub on old versions of Pacmec.
 *
 * Switches to the default theme.
 *
 * @since TownHub 1.0
 */
function townhub_switch_theme() {
	switch_theme( MT_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'townhub_upgrade_notice' );
}
add_action( 'after_switch_theme', 'townhub_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * TownHub on Pacmec versions prior to 5.0.
 *
 * @since TownHub 1.0
 *
 * @global string $mt_version Pacmec version.
 */
function townhub_upgrade_notice() {
	$message = sprintf( esc_html__( 'TownHub requires at least Pacmec version 5.0. You are running version %s. Please upgrade and try again.', 'townhub' ), $GLOBALS['mt_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on Pacmec versions prior to 5.0.
 *
 * @since TownHub 1.0
 *
 * @global string $mt_version Pacmec version.
 */
function townhub_customize() {
	mt_die( sprintf( esc_html__( 'TownHub requires at least Pacmec version 5.0. You are running version %s. Please upgrade and try again.', 'townhub' ), $GLOBALS['mt_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'townhub_customize' );

/**
 * Prevents the Theme Preview from being loaded on Pacmec versions prior to 5.0.
 *
 * @since TownHub 1.0
 *
 * @global string $mt_version Pacmec version.
 */
function townhub_preview() {
	if ( isset( $_GET['preview'] ) ) {
		mt_die( sprintf( esc_html__( 'TownHub requires at least Pacmec version 5.0. You are running version %s. Please upgrade and try again.', 'townhub' ), $GLOBALS['mt_version'] ) );
	}
}
add_action( 'template_redirect', 'townhub_preview' );
