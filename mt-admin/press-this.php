<?php
/**
 * Press This Display and Handler.
 *
 * @package paCMec
 * @subpackage Press_This
 */

define( 'IFRAME_REQUEST', true );

/** paCMec Administration Bootstrap */
require_once __DIR__ . '/admin.php';

function mt_load_press_this() {
	$plugin_slug = 'press-this';
	$plugin_file = 'press-this/press-this-plugin.php';

	if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) ) {
		mt_die(
			__( 'Sorry, you are not allowed to create posts as this user.' ),
			__( 'You need a higher level of permission.' ),
			403
		);
	} elseif ( is_plugin_active( $plugin_file ) ) {
		include MT_PLUGIN_DIR . '/press-this/class-mt-press-this-plugin.php';
		$mt_press_this = new MT_Press_This_Plugin();
		$mt_press_this->html();
	} elseif ( current_user_can( 'activate_plugins' ) ) {
		if ( file_exists( MT_PLUGIN_DIR . '/' . $plugin_file ) ) {
			$url    = mt_nonce_url(
				add_query_arg(
					array(
						'action' => 'activate',
						'plugin' => $plugin_file,
						'from'   => 'press-this',
					),
					admin_url( 'plugins.php' )
				),
				'activate-plugin_' . $plugin_file
			);
			$action = sprintf(
				'<a href="%1$s" aria-label="%2$s">%2$s</a>',
				esc_url( $url ),
				__( 'Activate Press This' )
			);
		} else {
			if ( is_main_site() ) {
				$url    = mt_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $plugin_slug,
							'from'   => 'press-this',
						),
						self_admin_url( 'update.php' )
					),
					'install-plugin_' . $plugin_slug
				);
				$action = sprintf(
					'<a href="%1$s" class="install-now" data-slug="%2$s" data-name="%2$s" aria-label="%3$s">%3$s</a>',
					esc_url( $url ),
					esc_attr( $plugin_slug ),
					__( 'Install Now' )
				);
			} else {
				$action = sprintf(
					/* translators: %s: URL to Press This bookmarklet on the main site. */
					__( 'Press This is not installed. Please install Press This from <a href="%s">the main site</a>.' ),
					get_admin_url( get_current_network_id(), 'press-this.php' )
				);
			}
		}
		mt_die(
			__( 'The Press This plugin is required.' ) . '<br />' . $action,
			__( 'Installation Required' ),
			200
		);
	} else {
		mt_die(
			__( 'Press This is not available. Please contact your site administrator.' ),
			__( 'Installation Required' ),
			200
		);
	}
}

mt_load_press_this();