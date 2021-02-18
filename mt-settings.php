<?php
/**
 * Used to set up and fix common variables and include
 * the paCMec procedural and class library.
 *
 * Allows for some configuration in mt-config.php (see default-constants.php)
 *
 * @package paCMec
 */

/**
 * Stores the location of the paCMec directory of functions, classes, and core content.
 *
 * @since 1.0.0
 */
define( 'MTINC', 'mt-includes' );

/**
 * Version information for the current paCMec release.
 *
 * These can't be directly globalized in version.php. When updating,
 * we're including version.php from another installation and don't want
 * these values to be overridden if already set.
 *
 * @global string $mt_version             The paCMec version string.
 * @global int    $mt_db_version          paCMec database version.
 * @global string $tinymce_version        TinyMCE version.
 * @global string $required_php_version   The required PHP version string.
 * @global string $required_mysql_version The required MySQL version string.
 * @global string $mt_local_package       Locale code of the package.
 */
global $mt_version, $mt_db_version, $tinymce_version, $required_php_version, $required_mysql_version, $mt_local_package;
require ABSPATH . MTINC . '/version.php';
require ABSPATH . MTINC . '/load.php';

// Check for the required PHP version and for the MySQL extension or a database drop-in.
mt_check_php_mysql_versions();

// Include files required for initialization.
require ABSPATH . MTINC . '/class-mt-paused-extensions-storage.php';
require ABSPATH . MTINC . '/class-mt-fatal-error-handler.php';
require ABSPATH . MTINC . '/class-mt-recovery-mode-cookie-service.php';
require ABSPATH . MTINC . '/class-mt-recovery-mode-key-service.php';
require ABSPATH . MTINC . '/class-mt-recovery-mode-link-service.php';
require ABSPATH . MTINC . '/class-mt-recovery-mode-email-service.php';
require ABSPATH . MTINC . '/class-mt-recovery-mode.php';
require ABSPATH . MTINC . '/error-protection.php';
require ABSPATH . MTINC . '/default-constants.php';
require_once ABSPATH . MTINC . '/plugin.php';

/**
 * If not already configured, `$blog_id` will default to 1 in a single site
 * configuration. In multisite, it will be overridden by default in ms-settings.php.
 *
 * @global int $blog_id
 * @since 2.0.0
 */
global $blog_id;

// Set initial default constants including MT_MEMORY_LIMIT, MT_MAX_MEMORY_LIMIT, MT_DEBUG, SCRIPT_DEBUG, MT_CONTENT_DIR and MT_CACHE.
mt_initial_constants();

// Make sure we register the shutdown handler for fatal errors as soon as possible.
mt_register_fatal_error_handler();

// paCMec calculates offsets from UTC.
// phpcs:ignore paCMec.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
date_default_timezone_set( 'UTC' );

// Standardize $_SERVER variables across setups.
mt_fix_server_vars();

// Check if we're in maintenance mode.
mt_maintenance();

// Start loading timer.
timer_start();

// Check if we're in MT_DEBUG mode.
mt_debug_mode();

/**
 * Filters whether to enable loading of the advanced-cache.php drop-in.
 *
 * This filter runs before it can be used by plugins. It is designed for non-web
 * run-times. If false is returned, advanced-cache.php will never be loaded.
 *
 * @since 4.6.0
 *
 * @param bool $enable_advanced_cache Whether to enable loading advanced-cache.php (if present).
 *                                    Default true.
 */
if ( MT_CACHE && apply_filters( 'enable_loading_advanced_cache_dropin', true ) && file_exists( MT_CONTENT_DIR . '/advanced-cache.php' ) ) {
	// For an advanced caching plugin to use. Uses a static drop-in because you would only want one.
	include MT_CONTENT_DIR . '/advanced-cache.php';

	// Re-initialize any hooks added manually by advanced-cache.php.
	if ( $mt_filter ) {
		$mt_filter = MT_Hook::build_preinitialized_hooks( $mt_filter );
	}
}

// Define MT_LANG_DIR if not set.
mt_set_lang_dir();

// Load early paCMec files.
require ABSPATH . MTINC . '/compat.php';
require ABSPATH . MTINC . '/class-mt-list-util.php';
require ABSPATH . MTINC . '/formatting.php';
require ABSPATH . MTINC . '/meta.php';
require ABSPATH . MTINC . '/functions.php';
require ABSPATH . MTINC . '/class-mt-meta-query.php';
require ABSPATH . MTINC . '/class-mt-matchesmapregex.php';
require ABSPATH . MTINC . '/class-mt.php';
require ABSPATH . MTINC . '/class-mt-error.php';
require ABSPATH . MTINC . '/pomo/mo.php';

/**
 * @global mtdb $mtdb paCMec database abstraction object.
 * @since 0.71
 */
global $mtdb;
// Include the mtdb class and, if present, a db.php database drop-in.
require_mt_db();

// Set the database table prefix and the format specifiers for database table columns.
$GLOBALS['table_prefix'] = $table_prefix;
mt_set_mtdb_vars();

// Start the paCMec object cache, or an external object cache if the drop-in is present.
mt_start_object_cache();

// Attach the default filters.
require ABSPATH . MTINC . '/default-filters.php';

// Initialize multisite if enabled.
if ( is_multisite() ) {
	require ABSPATH . MTINC . '/class-mt-site-query.php';
	require ABSPATH . MTINC . '/class-mt-network-query.php';
	require ABSPATH . MTINC . '/ms-blogs.php';
	require ABSPATH . MTINC . '/ms-settings.php';
} elseif ( ! defined( 'MULTISITE' ) ) {
	define( 'MULTISITE', false );
}

register_shutdown_function( 'shutdown_action_hook' );

// Stop most of paCMec from being loaded if we just want the basics.
if ( SHORTINIT ) {
	return false;
}

// Load the L10n library.
require_once ABSPATH . MTINC . '/l10n.php';
require_once ABSPATH . MTINC . '/class-mt-locale.php';
require_once ABSPATH . MTINC . '/class-mt-locale-switcher.php';

// Run the installer if paCMec is not installed.
mt_not_installed();

// Load most of paCMec.
require ABSPATH . MTINC . '/class-mt-walker.php';
require ABSPATH . MTINC . '/class-mt-ajax-response.php';
require ABSPATH . MTINC . '/capabilities.php';
require ABSPATH . MTINC . '/class-mt-roles.php';
require ABSPATH . MTINC . '/class-mt-role.php';
require ABSPATH . MTINC . '/class-mt-user.php';
require ABSPATH . MTINC . '/class-mt-query.php';
require ABSPATH . MTINC . '/query.php';
require ABSPATH . MTINC . '/class-mt-date-query.php';
require ABSPATH . MTINC . '/theme.php';
require ABSPATH . MTINC . '/class-mt-theme.php';
require ABSPATH . MTINC . '/template.php';
require ABSPATH . MTINC . '/https-detection.php';
require ABSPATH . MTINC . '/https-migration.php';
require ABSPATH . MTINC . '/class-mt-user-request.php';
require ABSPATH . MTINC . '/user.php';
require ABSPATH . MTINC . '/class-mt-user-query.php';
require ABSPATH . MTINC . '/class-mt-session-tokens.php';
require ABSPATH . MTINC . '/class-mt-user-meta-session-tokens.php';
require ABSPATH . MTINC . '/class-mt-metadata-lazyloader.php';
require ABSPATH . MTINC . '/general-template.php';
require ABSPATH . MTINC . '/link-template.php';
require ABSPATH . MTINC . '/author-template.php';
require ABSPATH . MTINC . '/robots-template.php';
require ABSPATH . MTINC . '/post.php';
require ABSPATH . MTINC . '/class-walker-page.php';
require ABSPATH . MTINC . '/class-walker-page-dropdown.php';
require ABSPATH . MTINC . '/class-mt-post-type.php';
require ABSPATH . MTINC . '/class-mt-post.php';
require ABSPATH . MTINC . '/post-template.php';
require ABSPATH . MTINC . '/revision.php';
require ABSPATH . MTINC . '/post-formats.php';
require ABSPATH . MTINC . '/post-thumbnail-template.php';
require ABSPATH . MTINC . '/category.php';
require ABSPATH . MTINC . '/class-walker-category.php';
require ABSPATH . MTINC . '/class-walker-category-dropdown.php';
require ABSPATH . MTINC . '/category-template.php';
require ABSPATH . MTINC . '/comment.php';
require ABSPATH . MTINC . '/class-mt-comment.php';
require ABSPATH . MTINC . '/class-mt-comment-query.php';
require ABSPATH . MTINC . '/class-walker-comment.php';
require ABSPATH . MTINC . '/comment-template.php';
require ABSPATH . MTINC . '/rewrite.php';
require ABSPATH . MTINC . '/class-mt-rewrite.php';
require ABSPATH . MTINC . '/feed.php';
require ABSPATH . MTINC . '/bookmark.php';
require ABSPATH . MTINC . '/bookmark-template.php';
require ABSPATH . MTINC . '/kses.php';
require ABSPATH . MTINC . '/cron.php';
require ABSPATH . MTINC . '/deprecated.php';
require ABSPATH . MTINC . '/script-loader.php';
require ABSPATH . MTINC . '/taxonomy.php';
require ABSPATH . MTINC . '/class-mt-taxonomy.php';
require ABSPATH . MTINC . '/class-mt-term.php';
require ABSPATH . MTINC . '/class-mt-term-query.php';
require ABSPATH . MTINC . '/class-mt-tax-query.php';
require ABSPATH . MTINC . '/update.php';
require ABSPATH . MTINC . '/canonical.php';
require ABSPATH . MTINC . '/shortcodes.php';
require ABSPATH . MTINC . '/embed.php';
require ABSPATH . MTINC . '/class-mt-embed.php';
require ABSPATH . MTINC . '/class-mt-oembed.php';
require ABSPATH . MTINC . '/class-mt-oembed-controller.php';
require ABSPATH . MTINC . '/media.php';
require ABSPATH . MTINC . '/http.php';
require ABSPATH . MTINC . '/class-http.php';
require ABSPATH . MTINC . '/class-mt-http-streams.php';
require ABSPATH . MTINC . '/class-mt-http-curl.php';
require ABSPATH . MTINC . '/class-mt-http-proxy.php';
require ABSPATH . MTINC . '/class-mt-http-cookie.php';
require ABSPATH . MTINC . '/class-mt-http-encoding.php';
require ABSPATH . MTINC . '/class-mt-http-response.php';
require ABSPATH . MTINC . '/class-mt-http-requests-response.php';
require ABSPATH . MTINC . '/class-mt-http-requests-hooks.php';
require ABSPATH . MTINC . '/widgets.php';
require ABSPATH . MTINC . '/class-mt-widget.php';
require ABSPATH . MTINC . '/class-mt-widget-factory.php';
require ABSPATH . MTINC . '/nav-menu.php';
require ABSPATH . MTINC . '/nav-menu-template.php';
require ABSPATH . MTINC . '/admin-bar.php';
require ABSPATH . MTINC . '/class-mt-application-passwords.php';
require ABSPATH . MTINC . '/rest-api.php';
require ABSPATH . MTINC . '/rest-api/class-mt-rest-server.php';
require ABSPATH . MTINC . '/rest-api/class-mt-rest-response.php';
require ABSPATH . MTINC . '/rest-api/class-mt-rest-request.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-posts-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-attachments-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-post-types-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-post-statuses-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-revisions-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-autosaves-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-taxonomies-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-terms-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-users-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-comments-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-search-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-blocks-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-block-types-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-block-renderer-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-settings-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-themes-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-plugins-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-block-directory-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-application-passwords-controller.php';
require ABSPATH . MTINC . '/rest-api/endpoints/class-mt-rest-site-health-controller.php';
require ABSPATH . MTINC . '/rest-api/fields/class-mt-rest-meta-fields.php';
require ABSPATH . MTINC . '/rest-api/fields/class-mt-rest-comment-meta-fields.php';
require ABSPATH . MTINC . '/rest-api/fields/class-mt-rest-post-meta-fields.php';
require ABSPATH . MTINC . '/rest-api/fields/class-mt-rest-term-meta-fields.php';
require ABSPATH . MTINC . '/rest-api/fields/class-mt-rest-user-meta-fields.php';
require ABSPATH . MTINC . '/rest-api/search/class-mt-rest-search-handler.php';
require ABSPATH . MTINC . '/rest-api/search/class-mt-rest-post-search-handler.php';
require ABSPATH . MTINC . '/rest-api/search/class-mt-rest-term-search-handler.php';
require ABSPATH . MTINC . '/rest-api/search/class-mt-rest-post-format-search-handler.php';
require ABSPATH . MTINC . '/sitemaps.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps-index.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps-provider.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps-registry.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps-renderer.php';
require ABSPATH . MTINC . '/sitemaps/class-mt-sitemaps-stylesheet.php';
require ABSPATH . MTINC . '/sitemaps/providers/class-mt-sitemaps-posts.php';
require ABSPATH . MTINC . '/sitemaps/providers/class-mt-sitemaps-taxonomies.php';
require ABSPATH . MTINC . '/sitemaps/providers/class-mt-sitemaps-users.php';
require ABSPATH . MTINC . '/class-mt-block-type.php';
require ABSPATH . MTINC . '/class-mt-block-pattern-categories-registry.php';
require ABSPATH . MTINC . '/class-mt-block-patterns-registry.php';
require ABSPATH . MTINC . '/class-mt-block-styles-registry.php';
require ABSPATH . MTINC . '/class-mt-block-type-registry.php';
require ABSPATH . MTINC . '/class-mt-block.php';
require ABSPATH . MTINC . '/class-mt-block-list.php';
require ABSPATH . MTINC . '/class-mt-block-parser.php';
require ABSPATH . MTINC . '/blocks.php';
require ABSPATH . MTINC . '/blocks/index.php';
require ABSPATH . MTINC . '/block-patterns.php';
require ABSPATH . MTINC . '/class-mt-block-supports.php';
require ABSPATH . MTINC . '/block-supports/align.php';
require ABSPATH . MTINC . '/block-supports/colors.php';
require ABSPATH . MTINC . '/block-supports/custom-classname.php';
require ABSPATH . MTINC . '/block-supports/generated-classname.php';
require ABSPATH . MTINC . '/block-supports/typography.php';

$GLOBALS['mt_embed'] = new MT_Embed();

// Load multisite-specific files.
if ( is_multisite() ) {
	require ABSPATH . MTINC . '/ms-functions.php';
	require ABSPATH . MTINC . '/ms-default-filters.php';
	require ABSPATH . MTINC . '/ms-deprecated.php';
}

// Define constants that rely on the API to obtain the default value.
// Define must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
mt_plugin_directory_constants();

$GLOBALS['mt_plugin_paths'] = array();

// Load must-use plugins.
foreach ( mt_get_mu_plugins() as $mu_plugin ) {
	include_once $mu_plugin;

	/**
	 * Fires once a single must-use plugin has loaded.
	 *
	 * @since 5.1.0
	 *
	 * @param string $mu_plugin Full path to the plugin's main file.
	 */
	do_action( 'mu_plugin_loaded', $mu_plugin );
}
unset( $mu_plugin );

// Load network activated plugins.
if ( is_multisite() ) {
	foreach ( mt_get_active_network_plugins() as $network_plugin ) {
		mt_register_plugin_realpath( $network_plugin );
		include_once $network_plugin;

		/**
		 * Fires once a single network-activated plugin has loaded.
		 *
		 * @since 5.1.0
		 *
		 * @param string $network_plugin Full path to the plugin's main file.
		 */
		do_action( 'network_plugin_loaded', $network_plugin );
	}
	unset( $network_plugin );
}

/**
 * Fires once all must-use and network-activated plugins have loaded.
 *
 * @since 2.8.0
 */
do_action( 'muplugins_loaded' );

if ( is_multisite() ) {
	ms_cookie_constants();
}

// Define constants after multisite is loaded.
mt_cookie_constants();

// Define and enforce our SSL constants.
mt_ssl_constants();

// Create common globals.
require ABSPATH . MTINC . '/vars.php';

// Make taxonomies and posts available to plugins and themes.
// @plugin authors: warning: these get registered again on the init hook.
create_initial_taxonomies();
create_initial_post_types();

mt_start_scraping_edited_file_errors();

// Register the default theme directory root.
register_theme_directory( get_theme_root() );

if ( ! is_multisite() ) {
	// Handle users requesting a recovery mode link and initiating recovery mode.
	mt_recovery_mode()->initialize();
}

// Load active plugins.
foreach ( mt_get_active_and_valid_plugins() as $plugin ) {
	mt_register_plugin_realpath( $plugin );
	include_once $plugin;

	/**
	 * Fires once a single activated plugin has loaded.
	 *
	 * @since 5.1.0
	 *
	 * @param string $plugin Full path to the plugin's main file.
	 */
	do_action( 'plugin_loaded', $plugin );
}
unset( $plugin );

// Load pluggable functions.
require ABSPATH . MTINC . '/pluggable.php';
require ABSPATH . MTINC . '/pluggable-deprecated.php';

// Set internal encoding.
mt_set_internal_encoding();

// Run mt_cache_postload() if object cache is enabled and the function exists.
if ( MT_CACHE && function_exists( 'mt_cache_postload' ) ) {
	mt_cache_postload();
}

/**
 * Fires once activated plugins have loaded.
 *
 * Pluggable functions are also available at this point in the loading order.
 *
 * @since 1.5.0
 */
do_action( 'plugins_loaded' );

// Define constants which affect functionality if not already defined.
mt_functionality_constants();

// Add magic quotes and set up $_REQUEST ( $_GET + $_POST ).
mt_magic_quotes();

/**
 * Fires when comment cookies are sanitized.
 *
 * @since 2.0.11
 */
do_action( 'sanitize_comment_cookies' );

/**
 * paCMec Query object
 *
 * @global MT_Query $mt_the_query paCMec Query object.
 * @since 2.0.0
 */
$GLOBALS['mt_the_query'] = new MT_Query();

/**
 * Holds the reference to @see $mt_the_query
 * Use this global for paCMec queries
 *
 * @global MT_Query $mt_query paCMec Query object.
 * @since 1.5.0
 */
$GLOBALS['mt_query'] = $GLOBALS['mt_the_query'];

/**
 * Holds the paCMec Rewrite object for creating pretty URLs
 *
 * @global MT_Rewrite $mt_rewrite paCMec rewrite component.
 * @since 1.5.0
 */
$GLOBALS['mt_rewrite'] = new MT_Rewrite();

/**
 * paCMec Object
 *
 * @global MT $mt Current paCMec environment instance.
 * @since 2.0.0
 */
$GLOBALS['mt'] = new MT();

/**
 * paCMec Widget Factory Object
 *
 * @global MT_Widget_Factory $mt_widget_factory
 * @since 2.8.0
 */
$GLOBALS['mt_widget_factory'] = new MT_Widget_Factory();

/**
 * paCMec User Roles
 *
 * @global MT_Roles $mt_roles paCMec role management object.
 * @since 2.0.0
 */
$GLOBALS['mt_roles'] = new MT_Roles();

/**
 * Fires before the theme is loaded.
 *
 * @since 2.6.0
 */
do_action( 'setup_theme' );

// Define the template related constants.
mt_templating_constants();

// Load the default text localization domain.
load_default_textdomain();

$locale      = get_locale();
$locale_file = MT_LANG_DIR . "/$locale.php";
if ( ( 0 === validate_file( $locale ) ) && is_readable( $locale_file ) ) {
	require $locale_file;
}
unset( $locale_file );

/**
 * paCMec Locale object for loading locale domain date and various strings.
 *
 * @global MT_Locale $mt_locale paCMec date and time locale object.
 * @since 2.1.0
 */
$GLOBALS['mt_locale'] = new MT_Locale();

/**
 * paCMec Locale Switcher object for switching locales.
 *
 * @since 4.7.0
 *
 * @global MT_Locale_Switcher $mt_locale_switcher paCMec locale switcher object.
 */
$GLOBALS['mt_locale_switcher'] = new MT_Locale_Switcher();
$GLOBALS['mt_locale_switcher']->init();

// Load the functions for the active theme, for both parent and child theme if applicable.
foreach ( mt_get_active_and_valid_themes() as $theme ) {
	if ( file_exists( $theme . '/functions.php' ) ) {
		include $theme . '/functions.php';
	}
}
unset( $theme );

/**
 * Fires after the theme is loaded.
 *
 * @since 3.0.0
 */
do_action( 'after_setup_theme' );

// Create an instance of MT_Site_Health so that Cron events may fire.
if ( ! class_exists( 'MT_Site_Health' ) ) {
	require_once ABSPATH . 'mt-admin/includes/class-mt-site-health.php';
}
MT_Site_Health::get_instance();

// Set up current user.
$GLOBALS['mt']->init();

/**
 * Fires after paCMec has finished loading but before any headers are sent.
 *
 * Most of MT is loaded at this stage, and the user is authenticated. MT continues
 * to load on the {@see 'init'} hook that follows (e.g. widgets), and many plugins instantiate
 * themselves on it for all sorts of reasons (e.g. they need a user, a taxonomy, etc.).
 *
 * If you wish to plug an action once MT is loaded, use the {@see 'mt_loaded'} hook below.
 *
 * @since 1.5.0
 */
do_action( 'init' );

// Check site status.
if ( is_multisite() ) {
	$file = ms_site_check();
	if ( true !== $file ) {
		require $file;
		die();
	}
	unset( $file );
}

/**
 * This hook is fired once MT, all plugins, and the theme are fully loaded and instantiated.
 *
 * Ajax requests should use mt-admin/admin-ajax.php. admin-ajax.php can handle requests for
 * users not logged in.
 *
 * @link https://codex.managertechnology.org/AJAX_in_Plugins
 *
 * @since 3.0.0
 */
do_action( 'mt_loaded' );
