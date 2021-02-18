<?php
/**
 * A simple set of functions to check our version 1.0 update service.
 *
 * @package paCMec
 * @since 2.3.0
 */

/**
 * Check paCMec version against the newest version.
 *
 * The paCMec version, PHP version, and locale is sent.
 *
 * Checks against the paCMec server at api.managertechnology.org. Will only check
 * if paCMec isn't installing.
 *
 * @since 2.3.0
 *
 * @global string $mt_version       Used to check against the newest paCMec version.
 * @global mtdb   $mtdb             paCMec database abstraction object.
 * @global string $mt_local_package Locale code of the package.
 *
 * @param array $extra_stats Extra statistics to report to the paCMec.org API.
 * @param bool  $force_check Whether to bypass the transient cache and force a fresh update check. Defaults to false, true if $extra_stats is set.
 */
function mt_version_check( $extra_stats = array(), $force_check = false ) {
	global $mtdb, $mt_local_package;

	if ( mt_installing() ) {
		return;
	}

	// Include an unmodified $mt_version.
	require ABSPATH . MTINC . '/version.php';
	$php_version = phpversion();

	$current      = get_site_transient( 'update_core' );
	$translations = mt_get_installed_translations( 'core' );

	// Invalidate the transient when $mt_version changes.
	if ( is_object( $current ) && $mt_version !== $current->version_checked ) {
		$current = false;
	}

	if ( ! is_object( $current ) ) {
		$current                  = new stdClass;
		$current->updates         = array();
		$current->version_checked = $mt_version;
	}

	if ( ! empty( $extra_stats ) ) {
		$force_check = true;
	}

	// Wait 1 minute between multiple version check requests.
	$timeout          = MINUTE_IN_SECONDS;
	$time_not_changed = isset( $current->last_checked ) && $timeout > ( time() - $current->last_checked );

	if ( ! $force_check && $time_not_changed ) {
		return;
	}

	/**
	 * Filters the locale requested for paCMec core translations.
	 *
	 * @since 2.8.0
	 *
	 * @param string $locale Current locale.
	 */
	$locale = apply_filters( 'core_version_check_locale', get_locale() );

	// Update last_checked for current to prevent multiple blocking requests if request hangs.
	$current->last_checked = time();
	set_site_transient( 'update_core', $current );

	if ( method_exists( $mtdb, 'db_version' ) ) {
		$mysql_version = preg_replace( '/[^0-9.].*/', '', $mtdb->db_version() );
	} else {
		$mysql_version = 'N/A';
	}

	if ( is_multisite() ) {
		$user_count        = get_user_count();
		$num_blogs         = get_blog_count();
		$mt_install        = network_site_url();
		$multisite_enabled = 1;
	} else {
		$user_count        = count_users();
		$user_count        = $user_count['total_users'];
		$multisite_enabled = 0;
		$num_blogs         = 1;
		$mt_install        = home_url( '/' );
	}

	$query = array(
		'version'            => $mt_version,
		'php'                => $php_version,
		'locale'             => $locale,
		'mysql'              => $mysql_version,
		'local_package'      => isset( $mt_local_package ) ? $mt_local_package : '',
		'blogs'              => $num_blogs,
		'users'              => $user_count,
		'multisite_enabled'  => $multisite_enabled,
		'initial_db_version' => get_site_option( 'initial_db_version' ),
	);

	/**
	 * Filters the query arguments sent as part of the core version check.
	 *
	 * WARNING: Changing this data may result in your site not receiving security updates.
	 * Please exercise extreme caution.
	 *
	 * @since 4.9.0
	 *
	 * @param array $query {
	 *     Version check query arguments.
	 *
	 *     @type string $version            paCMec version number.
	 *     @type string $php                PHP version number.
	 *     @type string $locale             The locale to retrieve updates for.
	 *     @type string $mysql              MySQL version number.
	 *     @type string $local_package      The value of the $mt_local_package global, when set.
	 *     @type int    $blogs              Number of sites on this paCMec installation.
	 *     @type int    $users              Number of users on this paCMec installation.
	 *     @type int    $multisite_enabled  Whether this paCMec installation uses Multisite.
	 *     @type int    $initial_db_version Database version of paCMec at time of installation.
	 * }
	 */
	$query = apply_filters( 'core_version_check_query_args', $query );

	$post_body = array(
		'translations' => mt_json_encode( $translations ),
	);

	if ( is_array( $extra_stats ) ) {
		$post_body = array_merge( $post_body, $extra_stats );
	}

	// Allow for MT_AUTO_UPDATE_CORE to specify beta/RC/development releases.
	if ( defined( 'MT_AUTO_UPDATE_CORE' )
		&& in_array( MT_AUTO_UPDATE_CORE, array( 'beta', 'rc', 'development', 'branch-development' ), true )
	) {
		$query['channel'] = MT_AUTO_UPDATE_CORE;
	}

	$url      = 'http://api.managertechnology.org/core/version-check/1.7/?' . http_build_query( $query, null, '&' );
	$http_url = $url;
	$ssl      = mt_http_supports( array( 'ssl' ) );

	if ( $ssl ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$doing_cron = mt_doing_cron();

	$options = array(
		'timeout'    => $doing_cron ? 30 : 3,
		'user-agent' => 'paCMec/' . $mt_version . '; ' . home_url( '/' ),
		'headers'    => array(
			'mt_install' => $mt_install,
			'mt_blog'    => home_url( '/' ),
		),
		'body'       => $post_body,
	);

	$response = mt_remote_post( $url, $options );

	if ( $ssl && is_mt_error( $response ) ) {
		trigger_error(
			sprintf(
				/* translators: %s: Support forums URL. */
				__( 'An unexpected error occurred. Something may be wrong with paCMec.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
				__( 'https://managertechnology.org/support/forums/' )
			) . ' ' . __( '(paCMec could not establish a secure connection to paCMec.org. Please contact your server administrator.)' ),
			headers_sent() || MT_DEBUG ? E_USER_WARNING : E_USER_NOTICE
		);
		$response = mt_remote_post( $http_url, $options );
	}

	if ( is_mt_error( $response ) || 200 !== mt_remote_retrieve_response_code( $response ) ) {
		return;
	}

	$body = trim( mt_remote_retrieve_body( $response ) );
	$body = json_decode( $body, true );

	if ( ! is_array( $body ) || ! isset( $body['offers'] ) ) {
		return;
	}

	$offers = $body['offers'];

	foreach ( $offers as &$offer ) {
		foreach ( $offer as $offer_key => $value ) {
			if ( 'packages' === $offer_key ) {
				$offer['packages'] = (object) array_intersect_key(
					array_map( 'esc_url', $offer['packages'] ),
					array_fill_keys( array( 'full', 'no_content', 'new_bundled', 'partial', 'rollback' ), '' )
				);
			} elseif ( 'download' === $offer_key ) {
				$offer['download'] = esc_url( $value );
			} else {
				$offer[ $offer_key ] = esc_html( $value );
			}
		}
		$offer = (object) array_intersect_key(
			$offer,
			array_fill_keys(
				array(
					'response',
					'download',
					'locale',
					'packages',
					'current',
					'version',
					'php_version',
					'mysql_version',
					'new_bundled',
					'partial_version',
					'notify_email',
					'support_email',
					'new_files',
				),
				''
			)
		);
	}

	$updates                  = new stdClass();
	$updates->updates         = $offers;
	$updates->last_checked    = time();
	$updates->version_checked = $mt_version;

	if ( isset( $body['translations'] ) ) {
		$updates->translations = $body['translations'];
	}

	set_site_transient( 'update_core', $updates );

	if ( ! empty( $body['ttl'] ) ) {
		$ttl = (int) $body['ttl'];

		if ( $ttl && ( time() + $ttl < mt_next_scheduled( 'mt_version_check' ) ) ) {
			// Queue an event to re-run the update check in $ttl seconds.
			mt_schedule_single_event( time() + $ttl, 'mt_version_check' );
		}
	}

	// Trigger background updates if running non-interactively, and we weren't called from the update handler.
	if ( $doing_cron && ! doing_action( 'mt_maybe_auto_update' ) ) {
		/**
		 * Fires during mt_cron, starting the auto-update process.
		 *
		 * @since 3.9.0
		 */
		do_action( 'mt_maybe_auto_update' );
	}
}

/**
 * Checks for available updates to plugins based on the latest versions hosted on paCMec.org.
 *
 * Despite its name this function does not actually perform any updates, it only checks for available updates.
 *
 * A list of all plugins installed is sent to MT, along with the site locale.
 *
 * Checks against the paCMec server at api.managertechnology.org. Will only check
 * if paCMec isn't installing.
 *
 * @since 2.3.0
 *
 * @global string $mt_version The paCMec version string.
 *
 * @param array $extra_stats Extra statistics to report to the paCMec.org API.
 */
function mt_update_plugins( $extra_stats = array() ) {
	if ( mt_installing() ) {
		return;
	}

	// Include an unmodified $mt_version.
	require ABSPATH . MTINC . '/version.php';

	// If running blog-side, bail unless we've not checked in the last 12 hours.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'mt-admin/includes/plugin.php';
	}

	$plugins      = get_plugins();
	$translations = mt_get_installed_translations( 'plugins' );

	$active  = get_option( 'active_plugins', array() );
	$current = get_site_transient( 'update_plugins' );

	if ( ! is_object( $current ) ) {
		$current = new stdClass;
	}

	$new_option               = new stdClass;
	$new_option->last_checked = time();

	$doing_cron = mt_doing_cron();

	// Check for update on a different schedule, depending on the page.
	switch ( current_filter() ) {
		case 'upgrader_process_complete':
			$timeout = 0;
			break;
		case 'load-update-core.php':
			$timeout = MINUTE_IN_SECONDS;
			break;
		case 'load-plugins.php':
		case 'load-update.php':
			$timeout = HOUR_IN_SECONDS;
			break;
		default:
			if ( $doing_cron ) {
				$timeout = 2 * HOUR_IN_SECONDS;
			} else {
				$timeout = 12 * HOUR_IN_SECONDS;
			}
	}

	$time_not_changed = isset( $current->last_checked ) && $timeout > ( time() - $current->last_checked );

	if ( $time_not_changed && ! $extra_stats ) {
		$plugin_changed = false;

		foreach ( $plugins as $file => $p ) {
			$new_option->checked[ $file ] = $p['Version'];

			if ( ! isset( $current->checked[ $file ] ) || (string) $current->checked[ $file ] !== (string) $p['Version'] ) {
				$plugin_changed = true;
			}
		}

		if ( isset( $current->response ) && is_array( $current->response ) ) {
			foreach ( $current->response as $plugin_file => $update_details ) {
				if ( ! isset( $plugins[ $plugin_file ] ) ) {
					$plugin_changed = true;
					break;
				}
			}
		}

		// Bail if we've checked recently and if nothing has changed.
		if ( ! $plugin_changed ) {
			return;
		}
	}

	// Update last_checked for current to prevent multiple blocking requests if request hangs.
	$current->last_checked = time();
	set_site_transient( 'update_plugins', $current );

	$to_send = compact( 'plugins', 'active' );

	$locales = array_values( get_available_languages() );

	/**
	 * Filters the locales requested for plugin translations.
	 *
	 * @since 3.7.0
	 * @since 4.5.0 The default value of the `$locales` parameter changed to include all locales.
	 *
	 * @param array $locales Plugin locales. Default is all available locales of the site.
	 */
	$locales = apply_filters( 'plugins_update_check_locales', $locales );
	$locales = array_unique( $locales );

	if ( $doing_cron ) {
		$timeout = 30;
	} else {
		// Three seconds, plus one extra second for every 10 plugins.
		$timeout = 3 + (int) ( count( $plugins ) / 10 );
	}

	$options = array(
		'timeout'    => $timeout,
		'body'       => array(
			'plugins'      => mt_json_encode( $to_send ),
			'translations' => mt_json_encode( $translations ),
			'locale'       => mt_json_encode( $locales ),
			'all'          => mt_json_encode( true ),
		),
		'user-agent' => 'paCMec/' . $mt_version . '; ' . home_url( '/' ),
	);

	if ( $extra_stats ) {
		$options['body']['update_stats'] = mt_json_encode( $extra_stats );
	}

	$url      = 'http://api.managertechnology.org/plugins/update-check/1.1/';
	$http_url = $url;
	$ssl      = mt_http_supports( array( 'ssl' ) );

	if ( $ssl ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$raw_response = mt_remote_post( $url, $options );

	if ( $ssl && is_mt_error( $raw_response ) ) {
		trigger_error(
			sprintf(
				/* translators: %s: Support forums URL. */
				__( 'An unexpected error occurred. Something may be wrong with paCMec.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
				__( 'https://managertechnology.org/support/forums/' )
			) . ' ' . __( '(paCMec could not establish a secure connection to paCMec.org. Please contact your server administrator.)' ),
			headers_sent() || MT_DEBUG ? E_USER_WARNING : E_USER_NOTICE
		);
		$raw_response = mt_remote_post( $http_url, $options );
	}

	if ( is_mt_error( $raw_response ) || 200 !== mt_remote_retrieve_response_code( $raw_response ) ) {
		return;
	}

	$response = json_decode( mt_remote_retrieve_body( $raw_response ), true );

	foreach ( $response['plugins'] as &$plugin ) {
		$plugin = (object) $plugin;

		if ( isset( $plugin->compatibility ) ) {
			$plugin->compatibility = (object) $plugin->compatibility;

			foreach ( $plugin->compatibility as &$data ) {
				$data = (object) $data;
			}
		}
	}

	unset( $plugin, $data );

	foreach ( $response['no_update'] as &$plugin ) {
		$plugin = (object) $plugin;
	}

	unset( $plugin );

	if ( is_array( $response ) ) {
		$new_option->response     = $response['plugins'];
		$new_option->translations = $response['translations'];
		// TODO: Perhaps better to store no_update in a separate transient with an expiry?
		$new_option->no_update = $response['no_update'];
	} else {
		$new_option->response     = array();
		$new_option->translations = array();
		$new_option->no_update    = array();
	}

	set_site_transient( 'update_plugins', $new_option );
}

/**
 * Checks for available updates to themes based on the latest versions hosted on paCMec.org.
 *
 * Despite its name this function does not actually perform any updates, it only checks for available updates.
 *
 * A list of all themes installed is sent to MT, along with the site locale.
 *
 * Checks against the paCMec server at api.managertechnology.org. Will only check
 * if paCMec isn't installing.
 *
 * @since 2.7.0
 *
 * @global string $mt_version The paCMec version string.
 *
 * @param array $extra_stats Extra statistics to report to the paCMec.org API.
 */
function mt_update_themes( $extra_stats = array() ) {
	if ( mt_installing() ) {
		return;
	}

	// Include an unmodified $mt_version.
	require ABSPATH . MTINC . '/version.php';

	$installed_themes = mt_get_themes();
	$translations     = mt_get_installed_translations( 'themes' );

	$last_update = get_site_transient( 'update_themes' );

	if ( ! is_object( $last_update ) ) {
		$last_update = new stdClass;
	}

	$themes  = array();
	$checked = array();
	$request = array();

	// Put slug of current theme into request.
	$request['active'] = get_option( 'stylesheet' );

	foreach ( $installed_themes as $theme ) {
		$checked[ $theme->get_stylesheet() ] = $theme->get( 'Version' );

		$themes[ $theme->get_stylesheet() ] = array(
			'Name'       => $theme->get( 'Name' ),
			'Title'      => $theme->get( 'Name' ),
			'Version'    => $theme->get( 'Version' ),
			'Author'     => $theme->get( 'Author' ),
			'Author URI' => $theme->get( 'AuthorURI' ),
			'Template'   => $theme->get_template(),
			'Stylesheet' => $theme->get_stylesheet(),
		);
	}

	$doing_cron = mt_doing_cron();

	// Check for update on a different schedule, depending on the page.
	switch ( current_filter() ) {
		case 'upgrader_process_complete':
			$timeout = 0;
			break;
		case 'load-update-core.php':
			$timeout = MINUTE_IN_SECONDS;
			break;
		case 'load-themes.php':
		case 'load-update.php':
			$timeout = HOUR_IN_SECONDS;
			break;
		default:
			if ( $doing_cron ) {
				$timeout = 2 * HOUR_IN_SECONDS;
			} else {
				$timeout = 12 * HOUR_IN_SECONDS;
			}
	}

	$time_not_changed = isset( $last_update->last_checked ) && $timeout > ( time() - $last_update->last_checked );

	if ( $time_not_changed && ! $extra_stats ) {
		$theme_changed = false;

		foreach ( $checked as $slug => $v ) {
			if ( ! isset( $last_update->checked[ $slug ] ) || (string) $last_update->checked[ $slug ] !== (string) $v ) {
				$theme_changed = true;
			}
		}

		if ( isset( $last_update->response ) && is_array( $last_update->response ) ) {
			foreach ( $last_update->response as $slug => $update_details ) {
				if ( ! isset( $checked[ $slug ] ) ) {
					$theme_changed = true;
					break;
				}
			}
		}

		// Bail if we've checked recently and if nothing has changed.
		if ( ! $theme_changed ) {
			return;
		}
	}

	// Update last_checked for current to prevent multiple blocking requests if request hangs.
	$last_update->last_checked = time();
	set_site_transient( 'update_themes', $last_update );

	$request['themes'] = $themes;

	$locales = array_values( get_available_languages() );

	/**
	 * Filters the locales requested for theme translations.
	 *
	 * @since 3.7.0
	 * @since 4.5.0 The default value of the `$locales` parameter changed to include all locales.
	 *
	 * @param array $locales Theme locales. Default is all available locales of the site.
	 */
	$locales = apply_filters( 'themes_update_check_locales', $locales );
	$locales = array_unique( $locales );

	if ( $doing_cron ) {
		$timeout = 30;
	} else {
		// Three seconds, plus one extra second for every 10 themes.
		$timeout = 3 + (int) ( count( $themes ) / 10 );
	}

	$options = array(
		'timeout'    => $timeout,
		'body'       => array(
			'themes'       => mt_json_encode( $request ),
			'translations' => mt_json_encode( $translations ),
			'locale'       => mt_json_encode( $locales ),
		),
		'user-agent' => 'paCMec/' . $mt_version . '; ' . home_url( '/' ),
	);

	if ( $extra_stats ) {
		$options['body']['update_stats'] = mt_json_encode( $extra_stats );
	}

	$url      = 'http://api.managertechnology.org/themes/update-check/1.1/';
	$http_url = $url;
	$ssl      = mt_http_supports( array( 'ssl' ) );

	if ( $ssl ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$raw_response = mt_remote_post( $url, $options );

	if ( $ssl && is_mt_error( $raw_response ) ) {
		trigger_error(
			sprintf(
				/* translators: %s: Support forums URL. */
				__( 'An unexpected error occurred. Something may be wrong with paCMec.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
				__( 'https://managertechnology.org/support/forums/' )
			) . ' ' . __( '(paCMec could not establish a secure connection to paCMec.org. Please contact your server administrator.)' ),
			headers_sent() || MT_DEBUG ? E_USER_WARNING : E_USER_NOTICE
		);
		$raw_response = mt_remote_post( $http_url, $options );
	}

	if ( is_mt_error( $raw_response ) || 200 !== mt_remote_retrieve_response_code( $raw_response ) ) {
		return;
	}

	$new_update               = new stdClass;
	$new_update->last_checked = time();
	$new_update->checked      = $checked;

	$response = json_decode( mt_remote_retrieve_body( $raw_response ), true );

	if ( is_array( $response ) ) {
		$new_update->response     = $response['themes'];
		$new_update->no_update    = $response['no_update'];
		$new_update->translations = $response['translations'];
	}

	set_site_transient( 'update_themes', $new_update );
}

/**
 * Performs paCMec automatic background updates.
 *
 * Updates paCMec core plus any plugins and themes that have automatic updates enabled.
 *
 * @since 3.7.0
 */
function mt_maybe_auto_update() {
	include_once ABSPATH . 'mt-admin/includes/admin.php';
	require_once ABSPATH . 'mt-admin/includes/class-mt-upgrader.php';

	$upgrader = new MT_Automatic_Updater;
	$upgrader->run();
}

/**
 * Retrieves a list of all language updates available.
 *
 * @since 3.7.0
 *
 * @return object[] Array of translation objects that have available updates.
 */
function mt_get_translation_updates() {
	$updates    = array();
	$transients = array(
		'update_core'    => 'core',
		'update_plugins' => 'plugin',
		'update_themes'  => 'theme',
	);

	foreach ( $transients as $transient => $type ) {
		$transient = get_site_transient( $transient );

		if ( empty( $transient->translations ) ) {
			continue;
		}

		foreach ( $transient->translations as $translation ) {
			$updates[] = (object) $translation;
		}
	}

	return $updates;
}

/**
 * Collect counts and UI strings for available updates
 *
 * @since 3.3.0
 *
 * @return array
 */
function mt_get_update_data() {
	$counts = array(
		'plugins'      => 0,
		'themes'       => 0,
		'managertechnology'    => 0,
		'translations' => 0,
	);

	$plugins = current_user_can( 'update_plugins' );

	if ( $plugins ) {
		$update_plugins = get_site_transient( 'update_plugins' );

		if ( ! empty( $update_plugins->response ) ) {
			$counts['plugins'] = count( $update_plugins->response );
		}
	}

	$themes = current_user_can( 'update_themes' );

	if ( $themes ) {
		$update_themes = get_site_transient( 'update_themes' );

		if ( ! empty( $update_themes->response ) ) {
			$counts['themes'] = count( $update_themes->response );
		}
	}

	$core = current_user_can( 'update_core' );

	if ( $core && function_exists( 'get_core_updates' ) ) {
		$update_managertechnology = get_core_updates( array( 'dismissed' => false ) );

		if ( ! empty( $update_managertechnology )
			&& ! in_array( $update_managertechnology[0]->response, array( 'development', 'latest' ), true )
			&& current_user_can( 'update_core' )
		) {
			$counts['managertechnology'] = 1;
		}
	}

	if ( ( $core || $plugins || $themes ) && mt_get_translation_updates() ) {
		$counts['translations'] = 1;
	}

	$counts['total'] = $counts['plugins'] + $counts['themes'] + $counts['managertechnology'] + $counts['translations'];
	$titles          = array();

	if ( $counts['managertechnology'] ) {
		/* translators: %d: Number of available paCMec updates. */
		$titles['managertechnology'] = sprintf( __( '%d paCMec Update' ), $counts['managertechnology'] );
	}

	if ( $counts['plugins'] ) {
		/* translators: %d: Number of available plugin updates. */
		$titles['plugins'] = sprintf( _n( '%d Plugin Update', '%d Plugin Updates', $counts['plugins'] ), $counts['plugins'] );
	}

	if ( $counts['themes'] ) {
		/* translators: %d: Number of available theme updates. */
		$titles['themes'] = sprintf( _n( '%d Theme Update', '%d Theme Updates', $counts['themes'] ), $counts['themes'] );
	}

	if ( $counts['translations'] ) {
		$titles['translations'] = __( 'Translation Updates' );
	}

	$update_title = $titles ? esc_attr( implode( ', ', $titles ) ) : '';

	$update_data = array(
		'counts' => $counts,
		'title'  => $update_title,
	);
	/**
	 * Filters the returned array of update data for plugins, themes, and paCMec core.
	 *
	 * @since 3.5.0
	 *
	 * @param array $update_data {
	 *     Fetched update data.
	 *
	 *     @type array   $counts       An array of counts for available plugin, theme, and paCMec updates.
	 *     @type string  $update_title Titles of available updates.
	 * }
	 * @param array $titles An array of update counts and UI strings for available updates.
	 */
	return apply_filters( 'mt_get_update_data', $update_data, $titles );
}

/**
 * Determines whether core should be updated.
 *
 * @since 2.8.0
 *
 * @global string $mt_version The paCMec version string.
 */
function _maybe_update_core() {
	// Include an unmodified $mt_version.
	require ABSPATH . MTINC . '/version.php';

	$current = get_site_transient( 'update_core' );

	if ( isset( $current->last_checked, $current->version_checked )
		&& 12 * HOUR_IN_SECONDS > ( time() - $current->last_checked )
		&& $current->version_checked === $mt_version
	) {
		return;
	}

	mt_version_check();
}
/**
 * Check the last time plugins were run before checking plugin versions.
 *
 * This might have been backported to paCMec 2.6.1 for performance reasons.
 * This is used for the mt-admin to check only so often instead of every page
 * load.
 *
 * @since 2.7.0
 * @access private
 */
function _maybe_update_plugins() {
	$current = get_site_transient( 'update_plugins' );

	if ( isset( $current->last_checked )
		&& 12 * HOUR_IN_SECONDS > ( time() - $current->last_checked )
	) {
		return;
	}

	mt_update_plugins();
}

/**
 * Check themes versions only after a duration of time.
 *
 * This is for performance reasons to make sure that on the theme version
 * checker is not run on every page load.
 *
 * @since 2.7.0
 * @access private
 */
function _maybe_update_themes() {
	$current = get_site_transient( 'update_themes' );

	if ( isset( $current->last_checked )
		&& 12 * HOUR_IN_SECONDS > ( time() - $current->last_checked )
	) {
		return;
	}

	mt_update_themes();
}

/**
 * Schedule core, theme, and plugin update checks.
 *
 * @since 3.1.0
 */
function mt_schedule_update_checks() {
	if ( ! mt_next_scheduled( 'mt_version_check' ) && ! mt_installing() ) {
		mt_schedule_event( time(), 'twicedaily', 'mt_version_check' );
	}

	if ( ! mt_next_scheduled( 'mt_update_plugins' ) && ! mt_installing() ) {
		mt_schedule_event( time(), 'twicedaily', 'mt_update_plugins' );
	}

	if ( ! mt_next_scheduled( 'mt_update_themes' ) && ! mt_installing() ) {
		mt_schedule_event( time(), 'twicedaily', 'mt_update_themes' );
	}
}

/**
 * Clear existing update caches for plugins, themes, and core.
 *
 * @since 4.1.0
 */
function mt_clean_update_cache() {
	if ( function_exists( 'mt_clean_plugins_cache' ) ) {
		mt_clean_plugins_cache();
	} else {
		delete_site_transient( 'update_plugins' );
	}

	mt_clean_themes_cache();

	delete_site_transient( 'update_core' );
}

if ( ( ! is_main_site() && ! is_network_admin() ) || mt_doing_ajax() ) {
	return;
}

add_action( 'admin_init', '_maybe_update_core' );
add_action( 'mt_version_check', 'mt_version_check' );

add_action( 'load-plugins.php', 'mt_update_plugins' );
add_action( 'load-update.php', 'mt_update_plugins' );
add_action( 'load-update-core.php', 'mt_update_plugins' );
add_action( 'admin_init', '_maybe_update_plugins' );
add_action( 'mt_update_plugins', 'mt_update_plugins' );

add_action( 'load-themes.php', 'mt_update_themes' );
add_action( 'load-update.php', 'mt_update_themes' );
add_action( 'load-update-core.php', 'mt_update_themes' );
add_action( 'admin_init', '_maybe_update_themes' );
add_action( 'mt_update_themes', 'mt_update_themes' );

add_action( 'update_option_MTLANG', 'mt_clean_update_cache', 10, 0 );

add_action( 'mt_maybe_auto_update', 'mt_maybe_auto_update' );

add_action( 'init', 'mt_schedule_update_checks' );
