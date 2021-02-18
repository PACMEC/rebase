<?php
/**
 * Defines constants and global variables that can be overridden, generally in mt-config.php.
 *
 * @package paCMec
 */

/**
 * Defines initial paCMec constants.
 *
 * @see mt_debug_mode()
 *
 * @since 3.0.0
 *
 * @global int    $blog_id    The current site ID.
 * @global string $mt_version The paCMec version string.
 */
function mt_initial_constants() {
	global $blog_id, $mt_version;

	/**#@+
	 * Constants for expressing human-readable data sizes in their respective number of bytes.
	 *
	 * @since 4.4.0
	 */
	define( 'KB_IN_BYTES', 1024 );
	define( 'MB_IN_BYTES', 1024 * KB_IN_BYTES );
	define( 'GB_IN_BYTES', 1024 * MB_IN_BYTES );
	define( 'TB_IN_BYTES', 1024 * GB_IN_BYTES );
	/**#@-*/

	// Start of run timestamp.
	if ( ! defined( 'MT_START_TIMESTAMP' ) ) {
		define( 'MT_START_TIMESTAMP', microtime( true ) );
	}

	$current_limit     = ini_get( 'memory_limit' );
	$current_limit_int = mt_convert_hr_to_bytes( $current_limit );

	// Define memory limits.
	if ( ! defined( 'MT_MEMORY_LIMIT' ) ) {
		if ( false === mt_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'MT_MEMORY_LIMIT', $current_limit );
		} elseif ( is_multisite() ) {
			define( 'MT_MEMORY_LIMIT', '64M' );
		} else {
			define( 'MT_MEMORY_LIMIT', '40M' );
		}
	}

	if ( ! defined( 'MT_MAX_MEMORY_LIMIT' ) ) {
		if ( false === mt_is_ini_value_changeable( 'memory_limit' ) ) {
			define( 'MT_MAX_MEMORY_LIMIT', $current_limit );
		} elseif ( -1 === $current_limit_int || $current_limit_int > 268435456 /* = 256M */ ) {
			define( 'MT_MAX_MEMORY_LIMIT', $current_limit );
		} else {
			define( 'MT_MAX_MEMORY_LIMIT', '256M' );
		}
	}

	// Set memory limits.
	$mt_limit_int = mt_convert_hr_to_bytes( MT_MEMORY_LIMIT );
	if ( -1 !== $current_limit_int && ( -1 === $mt_limit_int || $mt_limit_int > $current_limit_int ) ) {
		ini_set( 'memory_limit', MT_MEMORY_LIMIT );
	}

	if ( ! isset( $blog_id ) ) {
		$blog_id = 1;
	}

	if ( ! defined( 'MT_CONTENT_DIR' ) ) {
		define( 'MT_CONTENT_DIR', ABSPATH . 'mt-content' ); // No trailing slash, full paths only - MT_CONTENT_URL is defined further down.
	}

	// Add define( 'MT_DEBUG', true ); to mt-config.php to enable display of notices during development.
	if ( ! defined( 'MT_DEBUG' ) ) {
		if ( 'development' === mt_get_environment_type() ) {
			define( 'MT_DEBUG', true );
		} else {
			define( 'MT_DEBUG', false );
		}
	}

	// Add define( 'MT_DEBUG_DISPLAY', null ); to mt-config.php to use the globally configured setting
	// for 'display_errors' and not force errors to be displayed. Use false to force 'display_errors' off.
	if ( ! defined( 'MT_DEBUG_DISPLAY' ) ) {
		define( 'MT_DEBUG_DISPLAY', true );
	}

	// Add define( 'MT_DEBUG_LOG', true ); to enable error logging to mt-content/debug.log.
	if ( ! defined( 'MT_DEBUG_LOG' ) ) {
		define( 'MT_DEBUG_LOG', false );
	}

	if ( ! defined( 'MT_CACHE' ) ) {
		define( 'MT_CACHE', false );
	}

	// Add define( 'SCRIPT_DEBUG', true ); to mt-config.php to enable loading of non-minified,
	// non-concatenated scripts and stylesheets.
	if ( ! defined( 'SCRIPT_DEBUG' ) ) {
		if ( ! empty( $mt_version ) ) {
			$develop_src = false !== strpos( $mt_version, '-src' );
		} else {
			$develop_src = false;
		}

		define( 'SCRIPT_DEBUG', $develop_src );
	}

	/**
	 * Private
	 */
	if ( ! defined( 'MEDIA_TRASH' ) ) {
		define( 'MEDIA_TRASH', false );
	}

	if ( ! defined( 'SHORTINIT' ) ) {
		define( 'SHORTINIT', false );
	}

	// Constants for features added to MT that should short-circuit their plugin implementations.
	define( 'MT_FEATURE_BETTER_PASSWORDS', true );

	/**#@+
	 * Constants for expressing human-readable intervals
	 * in their respective number of seconds.
	 *
	 * Please note that these values are approximate and are provided for convenience.
	 * For example, MONTH_IN_SECONDS wrongly assumes every month has 30 days and
	 * YEAR_IN_SECONDS does not take leap years into account.
	 *
	 * If you need more accuracy please consider using the DateTime class (https://www.php.net/manual/en/class.datetime.php).
	 *
	 * @since 3.5.0
	 * @since 4.4.0 Introduced `MONTH_IN_SECONDS`.
	 */
	define( 'MINUTE_IN_SECONDS', 60 );
	define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );
	define( 'DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS );
	define( 'WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS );
	define( 'MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS );
	define( 'YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS );
	/**#@-*/
}

/**
 * Defines plugin directory paCMec constants.
 *
 * Defines must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
 *
 * @since 3.0.0
 */
function mt_plugin_directory_constants() {
	if ( ! defined( 'MT_CONTENT_URL' ) ) {
		define( 'MT_CONTENT_URL', get_option( 'siteurl' ) . '/mt-content' ); // Full URL - MT_CONTENT_DIR is defined further up.
	}

	/**
	 * Allows for the plugins directory to be moved from the default location.
	 *
	 * @since 2.6.0
	 */
	if ( ! defined( 'MT_PLUGIN_DIR' ) ) {
		define( 'MT_PLUGIN_DIR', MT_CONTENT_DIR . '/plugins' ); // Full path, no trailing slash.
	}

	/**
	 * Allows for the plugins directory to be moved from the default location.
	 *
	 * @since 2.6.0
	 */
	if ( ! defined( 'MT_PLUGIN_URL' ) ) {
		define( 'MT_PLUGIN_URL', MT_CONTENT_URL . '/plugins' ); // Full URL, no trailing slash.
	}

	/**
	 * Allows for the plugins directory to be moved from the default location.
	 *
	 * @since 2.1.0
	 * @deprecated
	 */
	if ( ! defined( 'PLUGINDIR' ) ) {
		define( 'PLUGINDIR', 'mt-content/plugins' ); // Relative to ABSPATH. For back compat.
	}

	/**
	 * Allows for the mu-plugins directory to be moved from the default location.
	 *
	 * @since 2.8.0
	 */
	if ( ! defined( 'MTMU_PLUGIN_DIR' ) ) {
		define( 'MTMU_PLUGIN_DIR', MT_CONTENT_DIR . '/mu-plugins' ); // Full path, no trailing slash.
	}

	/**
	 * Allows for the mu-plugins directory to be moved from the default location.
	 *
	 * @since 2.8.0
	 */
	if ( ! defined( 'MTMU_PLUGIN_URL' ) ) {
		define( 'MTMU_PLUGIN_URL', MT_CONTENT_URL . '/mu-plugins' ); // Full URL, no trailing slash.
	}

	/**
	 * Allows for the mu-plugins directory to be moved from the default location.
	 *
	 * @since 2.8.0
	 * @deprecated
	 */
	if ( ! defined( 'MUPLUGINDIR' ) ) {
		define( 'MUPLUGINDIR', 'mt-content/mu-plugins' ); // Relative to ABSPATH. For back compat.
	}
}

/**
 * Defines cookie-related paCMec constants.
 *
 * Defines constants after multisite is loaded.
 *
 * @since 3.0.0
 */
function mt_cookie_constants() {
	/**
	 * Used to guarantee unique hash cookies.
	 *
	 * @since 1.5.0
	 */
	if ( ! defined( 'COOKIEHASH' ) ) {
		$siteurl = get_site_option( 'siteurl' );
		if ( $siteurl ) {
			define( 'COOKIEHASH', md5( $siteurl ) );
		} else {
			define( 'COOKIEHASH', '' );
		}
	}

	/**
	 * @since 2.0.0
	 */
	if ( ! defined( 'USER_COOKIE' ) ) {
		define( 'USER_COOKIE', 'managertechnologyuser_' . COOKIEHASH );
	}

	/**
	 * @since 2.0.0
	 */
	if ( ! defined( 'PASS_COOKIE' ) ) {
		define( 'PASS_COOKIE', 'managertechnologypass_' . COOKIEHASH );
	}

	/**
	 * @since 2.5.0
	 */
	if ( ! defined( 'AUTH_COOKIE' ) ) {
		define( 'AUTH_COOKIE', 'managertechnology_' . COOKIEHASH );
	}

	/**
	 * @since 2.6.0
	 */
	if ( ! defined( 'SECURE_AUTH_COOKIE' ) ) {
		define( 'SECURE_AUTH_COOKIE', 'managertechnology_sec_' . COOKIEHASH );
	}

	/**
	 * @since 2.6.0
	 */
	if ( ! defined( 'LOGGED_IN_COOKIE' ) ) {
		define( 'LOGGED_IN_COOKIE', 'managertechnology_logged_in_' . COOKIEHASH );
	}

	/**
	 * @since 2.3.0
	 */
	if ( ! defined( 'TEST_COOKIE' ) ) {
		define( 'TEST_COOKIE', 'managertechnology_test_cookie' );
	}

	/**
	 * @since 1.2.0
	 */
	if ( ! defined( 'COOKIEPATH' ) ) {
		define( 'COOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'home' ) . '/' ) );
	}

	/**
	 * @since 1.5.0
	 */
	if ( ! defined( 'SITECOOKIEPATH' ) ) {
		define( 'SITECOOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'siteurl' ) . '/' ) );
	}

	/**
	 * @since 2.6.0
	 */
	if ( ! defined( 'ADMIN_COOKIE_PATH' ) ) {
		define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'mt-admin' );
	}

	/**
	 * @since 2.6.0
	 */
	if ( ! defined( 'PLUGINS_COOKIE_PATH' ) ) {
		define( 'PLUGINS_COOKIE_PATH', preg_replace( '|https?://[^/]+|i', '', MT_PLUGIN_URL ) );
	}

	/**
	 * @since 2.0.0
	 */
	if ( ! defined( 'COOKIE_DOMAIN' ) ) {
		define( 'COOKIE_DOMAIN', false );
	}

	if ( ! defined( 'RECOVERY_MODE_COOKIE' ) ) {
		/**
		 * @since 5.2.0
		 */
		define( 'RECOVERY_MODE_COOKIE', 'managertechnology_rec_' . COOKIEHASH );
	}
}

/**
 * Defines SSL-related paCMec constants.
 *
 * @since 3.0.0
 */
function mt_ssl_constants() {
	/**
	 * @since 2.6.0
	 */
	if ( ! defined( 'FORCE_SSL_ADMIN' ) ) {
		if ( 'https' === parse_url( get_option( 'siteurl' ), PHP_URL_SCHEME ) ) {
			define( 'FORCE_SSL_ADMIN', true );
		} else {
			define( 'FORCE_SSL_ADMIN', false );
		}
	}
	force_ssl_admin( FORCE_SSL_ADMIN );

	/**
	 * @since 2.6.0
	 * @deprecated 4.0.0
	 */
	if ( defined( 'FORCE_SSL_LOGIN' ) && FORCE_SSL_LOGIN ) {
		force_ssl_admin( true );
	}
}

/**
 * Defines functionality-related paCMec constants.
 *
 * @since 3.0.0
 */
function mt_functionality_constants() {
	/**
	 * @since 2.5.0
	 */
	if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
		define( 'AUTOSAVE_INTERVAL', MINUTE_IN_SECONDS );
	}

	/**
	 * @since 2.9.0
	 */
	if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
		define( 'EMPTY_TRASH_DAYS', 30 );
	}

	if ( ! defined( 'MT_POST_REVISIONS' ) ) {
		define( 'MT_POST_REVISIONS', true );
	}

	/**
	 * @since 3.3.0
	 */
	if ( ! defined( 'MT_CRON_LOCK_TIMEOUT' ) ) {
		define( 'MT_CRON_LOCK_TIMEOUT', MINUTE_IN_SECONDS );
	}
}

/**
 * Defines templating-related paCMec constants.
 *
 * @since 3.0.0
 */
function mt_templating_constants() {
	/**
	 * Filesystem path to the current active template directory.
	 *
	 * @since 1.5.0
	 */
	define( 'TEMPLATEPATH', get_template_directory() );

	/**
	 * Filesystem path to the current active template stylesheet directory.
	 *
	 * @since 2.1.0
	 */
	define( 'STYLESHEETPATH', get_stylesheet_directory() );

	/**
	 * Slug of the default theme for this installation.
	 * Used as the default theme when installing new sites.
	 * It will be used as the fallback if the current theme doesn't exist.
	 *
	 * @since 3.0.0
	 *
	 * @see MT_Theme::get_core_default_theme()
	 */
	if ( ! defined( 'MT_DEFAULT_THEME' ) ) {
		define( 'MT_DEFAULT_THEME', 'twentytwentyone' );
	}

}
