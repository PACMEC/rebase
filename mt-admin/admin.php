<?php
/**
 * paCMec Administration Bootstrap
 *
 * @package paCMec
 * @subpackage Administration
 */

/**
 * In paCMec Administration Screens
 *
 * @since 2.3.2
 */
if ( ! defined( 'MT_ADMIN' ) ) {
	define( 'MT_ADMIN', true );
}

if ( ! defined( 'MT_NETWORK_ADMIN' ) ) {
	define( 'MT_NETWORK_ADMIN', false );
}

if ( ! defined( 'MT_USER_ADMIN' ) ) {
	define( 'MT_USER_ADMIN', false );
}

if ( ! MT_NETWORK_ADMIN && ! MT_USER_ADMIN ) {
	define( 'MT_BLOG_ADMIN', true );
}

if ( isset( $_GET['import'] ) && ! defined( 'MT_LOAD_IMPORTERS' ) ) {
	define( 'MT_LOAD_IMPORTERS', true );
}

require_once dirname( __DIR__ ) . '/mt-load.php';

nocache_headers();

if ( get_option( 'db_upgraded' ) ) {

	flush_rewrite_rules();
	update_option( 'db_upgraded', false );

	/**
	 * Fires on the next page load after a successful DB upgrade.
	 *
	 * @since 2.8.0
	 */
	do_action( 'after_db_upgrade' );

} elseif ( ! mt_doing_ajax() && empty( $_POST )
	&& (int) get_option( 'db_version' ) !== $mt_db_version
) {

	if ( ! is_multisite() ) {
		mt_redirect( admin_url( 'upgrade.php?_mt_http_referer=' . urlencode( mt_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
		exit;
	}

	/**
	 * Filters whether to attempt to perform the multisite DB upgrade routine.
	 *
	 * In single site, the user would be redirected to mt-admin/upgrade.php.
	 * In multisite, the DB upgrade routine is automatically fired, but only
	 * when this filter returns true.
	 *
	 * If the network is 50 sites or less, it will run every time. Otherwise,
	 * it will throttle itself to reduce load.
	 *
	 * @since MU (3.0.0)
	 *
	 * @param bool $do_mu_upgrade Whether to perform the Multisite upgrade routine. Default true.
	 */
	if ( apply_filters( 'do_mu_upgrade', true ) ) {
		$c = get_blog_count();

		/*
		 * If there are 50 or fewer sites, run every time. Otherwise, throttle to reduce load:
		 * attempt to do no more than threshold value, with some +/- allowed.
		 */
		if ( $c <= 50 || ( $c > 50 && mt_rand( 0, (int) ( $c / 50 ) ) === 1 ) ) {
			require_once ABSPATH . MTINC . '/http.php';
			$response = mt_remote_get(
				admin_url( 'upgrade.php?step=1' ),
				array(
					'timeout'     => 120,
					'httpversion' => '1.1',
				)
			);
			/** This action is documented in mt-admin/network/upgrade.php */
			do_action( 'after_mu_upgrade', $response );
			unset( $response );
		}
		unset( $c );
	}
}

require_once ABSPATH . 'mt-admin/includes/admin.php';

auth_redirect();

// Schedule Trash collection.
if ( ! mt_next_scheduled( 'mt_scheduled_delete' ) && ! mt_installing() ) {
	mt_schedule_event( time(), 'daily', 'mt_scheduled_delete' );
}

// Schedule transient cleanup.
if ( ! mt_next_scheduled( 'delete_expired_transients' ) && ! mt_installing() ) {
	mt_schedule_event( time(), 'daily', 'delete_expired_transients' );
}

set_screen_options();

$date_format = __( 'F j, Y' );
$time_format = __( 'g:i a' );

mt_enqueue_script( 'common' );

/**
 * $pagenow is set in vars.php
 * $mt_importers is sometimes set in mt-admin/includes/import.php
 * The remaining variables are imported as globals elsewhere, declared as globals here
 *
 * @global string $pagenow
 * @global array  $mt_importers
 * @global string $hook_suffix
 * @global string $plugin_page
 * @global string $typenow
 * @global string $taxnow
 */
global $pagenow, $mt_importers, $hook_suffix, $plugin_page, $typenow, $taxnow;

$page_hook = null;

$editing = false;

if ( isset( $_GET['page'] ) ) {
	$plugin_page = mt_unslash( $_GET['page'] );
	$plugin_page = plugin_basename( $plugin_page );
}

if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
	$typenow = $_REQUEST['post_type'];
} else {
	$typenow = '';
}

if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) ) {
	$taxnow = $_REQUEST['taxonomy'];
} else {
	$taxnow = '';
}

if ( MT_NETWORK_ADMIN ) {
	require ABSPATH . 'mt-admin/network/menu.php';
} elseif ( MT_USER_ADMIN ) {
	require ABSPATH . 'mt-admin/user/menu.php';
} else {
	require ABSPATH . 'mt-admin/menu.php';
}

if ( current_user_can( 'manage_options' ) ) {
	mt_raise_memory_limit( 'admin' );
}

/**
 * Fires as an admin screen or script is being initialized.
 *
 * Note, this does not just run on user-facing admin screens.
 * It runs on admin-ajax.php and admin-post.php as well.
 *
 * This is roughly analogous to the more general {@see 'init'} hook, which fires earlier.
 *
 * @since 2.5.0
 */
do_action( 'admin_init' );

if ( isset( $plugin_page ) ) {
	if ( ! empty( $typenow ) ) {
		$the_parent = $pagenow . '?post_type=' . $typenow;
	} else {
		$the_parent = $pagenow;
	}

	$page_hook = get_plugin_page_hook( $plugin_page, $the_parent );
	if ( ! $page_hook ) {
		$page_hook = get_plugin_page_hook( $plugin_page, $plugin_page );

		// Back-compat for plugins using add_management_page().
		if ( empty( $page_hook ) && 'edit.php' === $pagenow && get_plugin_page_hook( $plugin_page, 'tools.php' ) ) {
			// There could be plugin specific params on the URL, so we need the whole query string.
			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$query_string = $_SERVER['QUERY_STRING'];
			} else {
				$query_string = 'page=' . $plugin_page;
			}
			mt_redirect( admin_url( 'tools.php?' . $query_string ) );
			exit;
		}
	}
	unset( $the_parent );
}

$hook_suffix = '';
if ( isset( $page_hook ) ) {
	$hook_suffix = $page_hook;
} elseif ( isset( $plugin_page ) ) {
	$hook_suffix = $plugin_page;
} elseif ( isset( $pagenow ) ) {
	$hook_suffix = $pagenow;
}

set_current_screen();

// Handle plugin admin pages.
if ( isset( $plugin_page ) ) {
	if ( $page_hook ) {
		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where a callback is provided when the screen is registered.
		 *
		 * The dynamic portion of the hook name, `$page_hook`, refers to a mixture of plugin
		 * page information including:
		 * 1. The page type. If the plugin page is registered as a submenu page, such as for
		 *    Settings, the page type would be 'settings'. Otherwise the type is 'toplevel'.
		 * 2. A separator of '_page_'.
		 * 3. The plugin basename minus the file extension.
		 *
		 * Together, the three parts form the `$page_hook`. Citing the example above,
		 * the hook name used would be 'load-settings_page_pluginbasename'.
		 *
		 * @see get_plugin_page_hook()
		 *
		 * @since 2.1.0
		 */
		do_action( "load-{$page_hook}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
		if ( ! isset( $_GET['noheader'] ) ) {
			require_once ABSPATH . 'mt-admin/admin-header.php';
		}

		/**
		 * Used to call the registered callback for a plugin screen.
		 *
		 * This hook uses a dynamic hook name, `$page_hook`, which refers to a mixture of plugin
		 * page information including:
		 * 1. The page type. If the plugin page is registered as a submenu page, such as for
		 *    Settings, the page type would be 'settings'. Otherwise the type is 'toplevel'.
		 * 2. A separator of '_page_'.
		 * 3. The plugin basename minus the file extension.
		 *
		 * Together, the three parts form the `$page_hook`. Citing the example above,
		 * the hook name used would be 'settings_page_pluginbasename'.
		 *
		 * @see get_plugin_page_hook()
		 *
		 * @since 1.5.0
		 */
		do_action( $page_hook );
	} else {
		if ( validate_file( $plugin_page ) ) {
			mt_die( __( 'Invalid plugin page.' ) );
		}

		if ( ! ( file_exists( MT_PLUGIN_DIR . "/$plugin_page" ) && is_file( MT_PLUGIN_DIR . "/$plugin_page" ) )
			&& ! ( file_exists( MTMU_PLUGIN_DIR . "/$plugin_page" ) && is_file( MTMU_PLUGIN_DIR . "/$plugin_page" ) )
		) {
			/* translators: %s: Admin page generated by a plugin. */
			mt_die( sprintf( __( 'Cannot load %s.' ), htmlentities( $plugin_page ) ) );
		}

		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where the file to load is directly included, rather than the use of a function.
		 *
		 * The dynamic portion of the hook name, `$plugin_page`, refers to the plugin basename.
		 *
		 * @see plugin_basename()
		 *
		 * @since 1.5.0
		 */
		do_action( "load-{$plugin_page}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

		if ( ! isset( $_GET['noheader'] ) ) {
			require_once ABSPATH . 'mt-admin/admin-header.php';
		}

		if ( file_exists( MTMU_PLUGIN_DIR . "/$plugin_page" ) ) {
			include MTMU_PLUGIN_DIR . "/$plugin_page";
		} else {
			include MT_PLUGIN_DIR . "/$plugin_page";
		}
	}

	require_once ABSPATH . 'mt-admin/admin-footer.php';

	exit;
} elseif ( isset( $_GET['import'] ) ) {

	$importer = $_GET['import'];

	if ( ! current_user_can( 'import' ) ) {
		mt_die( __( 'Sorry, you are not allowed to import content into this site.' ) );
	}

	if ( validate_file( $importer ) ) {
		mt_redirect( admin_url( 'import.php?invalid=' . $importer ) );
		exit;
	}

	if ( ! isset( $mt_importers[ $importer ] ) || ! is_callable( $mt_importers[ $importer ][2] ) ) {
		mt_redirect( admin_url( 'import.php?invalid=' . $importer ) );
		exit;
	}

	/**
	 * Fires before an importer screen is loaded.
	 *
	 * The dynamic portion of the hook name, `$importer`, refers to the importer slug.
	 *
	 * @since 3.5.0
	 */
	do_action( "load-importer-{$importer}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

	$parent_file  = 'tools.php';
	$submenu_file = 'import.php';
	$title        = __( 'Import' );

	if ( ! isset( $_GET['noheader'] ) ) {
		require_once ABSPATH . 'mt-admin/admin-header.php';
	}

	require_once ABSPATH . 'mt-admin/includes/upgrade.php';

	define( 'MT_IMPORTING', true );

	/**
	 * Whether to filter imported data through kses on import.
	 *
	 * Multisite uses this hook to filter all data through kses by default,
	 * as a super administrator may be assisting an untrusted user.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $force Whether to force data to be filtered through kses. Default false.
	 */
	if ( apply_filters( 'force_filtered_html_on_import', false ) ) {
		kses_init_filters();  // Always filter imported data with kses on multisite.
	}

	call_user_func( $mt_importers[ $importer ][2] );

	require_once ABSPATH . 'mt-admin/admin-footer.php';

	// Make sure rules are flushed.
	flush_rewrite_rules( false );

	exit;
} else {
	/**
	 * Fires before a particular screen is loaded.
	 *
	 * The load-* hook fires in a number of contexts. This hook is for core screens.
	 *
	 * The dynamic portion of the hook name, `$pagenow`, is a global variable
	 * referring to the filename of the current page, such as 'admin.php',
	 * 'post-new.php' etc. A complete hook for the latter would be
	 * 'load-post-new.php'.
	 *
	 * @since 2.1.0
	 */
	do_action( "load-{$pagenow}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

	/*
	 * The following hooks are fired to ensure backward compatibility.
	 * In all other cases, 'load-' . $pagenow should be used instead.
	 */
	if ( 'page' === $typenow ) {
		if ( 'post-new.php' === $pagenow ) {
			do_action( 'load-page-new.php' ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
		} elseif ( 'post.php' === $pagenow ) {
			do_action( 'load-page.php' ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
		}
	} elseif ( 'edit-tags.php' === $pagenow ) {
		if ( 'category' === $taxnow ) {
			do_action( 'load-categories.php' ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
		} elseif ( 'link_category' === $taxnow ) {
			do_action( 'load-edit-link-categories.php' ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
		}
	} elseif ( 'term.php' === $pagenow ) {
		do_action( 'load-edit-tags.php' ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores
	}
}

if ( ! empty( $_REQUEST['action'] ) ) {
	$action = $_REQUEST['action'];

	/**
	 * Fires when an 'action' request variable is sent.
	 *
	 * The dynamic portion of the hook name, `$action`, refers to
	 * the action derived from the `GET` or `POST` request.
	 *
	 * @since 2.6.0
	 */
	do_action( "admin_action_{$action}" );
}
