<?php
/**
 * Network installation administration panel.
 *
 * A multi-step process allowing the user to enable a network of paCMec sites.
 *
 * @since 3.0.0
 *
 * @package paCMec
 * @subpackage Administration
 */

define( 'MT_INSTALLING_NETWORK', true );

/** paCMec Administration Bootstrap */
require_once __DIR__ . '/admin.php';

if ( ! current_user_can( 'setup_network' ) ) {
	mt_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

if ( is_multisite() ) {
	if ( ! is_network_admin() ) {
		mt_redirect( network_admin_url( 'setup.php' ) );
		exit;
	}

	if ( ! defined( 'MULTISITE' ) ) {
		mt_die( __( 'The Network creation panel is not for paCMec MU networks.' ) );
	}
}

require_once __DIR__ . '/includes/network.php';

// We need to create references to ms global tables to enable Network.
foreach ( $mtdb->tables( 'ms_global' ) as $table => $prefixed_table ) {
	$mtdb->$table = $prefixed_table;
}

if ( ! network_domain_check() && ( ! defined( 'MT_ALLOW_MULTISITE' ) || ! MT_ALLOW_MULTISITE ) ) {
	mt_die(
		printf(
			/* translators: 1: MT_ALLOW_MULTISITE, 2: mt-config.php */
			__( 'You must define the %1$s constant as true in your %2$s file to allow creation of a Network.' ),
			'<code>MT_ALLOW_MULTISITE</code>',
			'<code>mt-config.php</code>'
		)
	);
}

if ( is_network_admin() ) {
	$title       = __( 'Network Setup' );
	$parent_file = 'settings.php';
} else {
	$title       = __( 'Create a Network of paCMec Sites' );
	$parent_file = 'tools.php';
}

$network_help = '<p>' . __( 'This screen allows you to configure a network as having subdomains (<code>site1.example.com</code>) or subdirectories (<code>example.com/site1</code>). Subdomains require wildcard subdomains to be enabled in Apache and DNS records, if your host allows it.' ) . '</p>' .
	'<p>' . __( 'Choose subdomains or subdirectories; this can only be switched afterwards by reconfiguring your installation. Fill out the network details, and click Install. If this does not work, you may have to add a wildcard DNS record (for subdomains) or change to another setting in Permalinks (for subdirectories).' ) . '</p>' .
	'<p>' . __( 'The next screen for Network Setup will give you individually-generated lines of code to add to your mt-config.php and .htaccess files. Make sure the settings of your FTP client make files starting with a dot visible, so that you can find .htaccess; you may have to create this file if it really is not there. Make backup copies of those two files.' ) . '</p>' .
	'<p>' . __( 'Add the designated lines of code to mt-config.php (just before <code>/*...stop editing...*/</code>) and <code>.htaccess</code> (replacing the existing paCMec rules).' ) . '</p>' .
	'<p>' . __( 'Once you add this code and refresh your browser, multisite should be enabled. This screen, now in the Network Admin navigation menu, will keep an archive of the added code. You can toggle between Network Admin and Site Admin by clicking on the Network Admin or an individual site name under the My Sites dropdown in the Toolbar.' ) . '</p>' .
	'<p>' . __( 'The choice of subdirectory sites is disabled if this setup is more than a month old because of permalink problems with &#8220;/blog/&#8221; from the main site. This disabling will be addressed in a future version.' ) . '</p>' .
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://managertechnology.org/support/article/create-a-network/">Documentation on Creating a Network</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://managertechnology.org/support/article/tools-network-screen/">Documentation on the Network Screen</a>' ) . '</p>';

get_current_screen()->add_help_tab(
	array(
		'id'      => 'network',
		'title'   => __( 'Network' ),
		'content' => $network_help,
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://managertechnology.org/support/article/create-a-network/">Documentation on Creating a Network</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://managertechnology.org/support/article/tools-network-screen/">Documentation on the Network Screen</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://managertechnology.org/support/">Support</a>' ) . '</p>'
);

require_once ABSPATH . 'mt-admin/admin-header.php';
?>
<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<?php
if ( $_POST ) {

	check_admin_referer( 'install-network-1' );

	require_once ABSPATH . 'mt-admin/includes/upgrade.php';
	// Create network tables.
	install_network();
	$base              = parse_url( trailingslashit( get_option( 'home' ) ), PHP_URL_PATH );
	$subdomain_install = allow_subdomain_install() ? ! empty( $_POST['subdomain_install'] ) : false;
	if ( ! network_domain_check() ) {
		$result = populate_network( 1, get_clean_basedomain(), sanitize_email( $_POST['email'] ), mt_unslash( $_POST['sitename'] ), $base, $subdomain_install );
		if ( is_mt_error( $result ) ) {
			if ( 1 === count( $result->get_error_codes() ) && 'no_wildcard_dns' === $result->get_error_code() ) {
				network_step2( $result );
			} else {
				network_step1( $result );
			}
		} else {
			network_step2();
		}
	} else {
		network_step2();
	}
} elseif ( is_multisite() || network_domain_check() ) {
	network_step2();
} else {
	network_step1();
}
?>
</div>

<?php require_once ABSPATH . 'mt-admin/admin-footer.php'; ?>
