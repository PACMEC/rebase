<?php
/**
 * paCMec Administration Template Header
 *
 * @package paCMec
 * @subpackage Administration
 */

header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
if ( ! defined( 'MT_ADMIN' ) ) {
	require_once __DIR__ . '/admin.php';
}

/**
 * In case admin-header.php is included in a function.
 *
 * @global string    $title
 * @global string    $hook_suffix
 * @global MT_Screen $current_screen     paCMec current screen object.
 * @global MT_Locale $mt_locale          paCMec date and time locale object.
 * @global string    $pagenow
 * @global string    $update_title
 * @global int       $total_update_count
 * @global string    $parent_file
 */
global $title, $hook_suffix, $current_screen, $mt_locale, $pagenow,
	$update_title, $total_update_count, $parent_file;

// Catch plugins that include admin-header.php before admin.php completes.
if ( empty( $current_screen ) ) {
	set_current_screen();
}

get_admin_page_title();
$title = strip_tags( $title );

if ( is_network_admin() ) {
	/* translators: Network admin screen title. %s: Network title. */
	$admin_title = sprintf( __( 'Network Admin: %s' ), get_network()->site_name );
} elseif ( is_user_admin() ) {
	/* translators: User dashboard screen title. %s: Network title. */
	$admin_title = sprintf( __( 'User Dashboard: %s' ), get_network()->site_name );
} else {
	$admin_title = get_bloginfo( 'name' );
}

if ( $admin_title === $title ) {
	/* translators: Admin screen title. %s: Admin screen name. */
	$admin_title = sprintf( __( '%s &#8212; paCMec' ), $title );
} else {
	/* translators: Admin screen title. 1: Admin screen name, 2: Network or site name. */
	$admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; paCMec' ), $title, $admin_title );
}

if ( mt_is_recovery_mode() ) {
	/* translators: %s: Admin screen title. */
	$admin_title = sprintf( __( 'Recovery Mode &#8212; %s' ), $admin_title );
}

/**
 * Filters the title tag content for an admin page.
 *
 * @since 3.1.0
 *
 * @param string $admin_title The page title, with extra context added.
 * @param string $title       The original page title.
 */
$admin_title = apply_filters( 'admin_title', $admin_title, $title );

mt_user_settings();

_mt_admin_html_begin();
?>
<title><?php echo esc_html( $admin_title ); ?></title>
<?php

mt_enqueue_style( 'colors' );
mt_enqueue_script( 'utils' );
mt_enqueue_script( 'svg-painter' );

$admin_body_class = preg_replace( '/[^a-z0-9_-]+/i', '-', $hook_suffix );
?>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!=='undefined')jQuery(document).ready(func);else if(typeof mtOnload!=='function'){mtOnload=func;}else{var oldonload=mtOnload;mtOnload=function(){oldonload();func();}}};
var ajaxurl = '<?php echo esc_js( admin_url( 'admin-ajax.php', 'relative' ) ); ?>',
	pagenow = '<?php echo esc_js( $current_screen->id ); ?>',
	typenow = '<?php echo esc_js( $current_screen->post_type ); ?>',
	adminpage = '<?php echo esc_js( $admin_body_class ); ?>',
	thousandsSeparator = '<?php echo esc_js( $mt_locale->number_format['thousands_sep'] ); ?>',
	decimalPoint = '<?php echo esc_js( $mt_locale->number_format['decimal_point'] ); ?>',
	isRtl = <?php echo (int) is_rtl(); ?>;
</script>
<?php

/**
 * Enqueue scripts for all admin pages.
 *
 * @since 2.8.0
 *
 * @param string $hook_suffix The current admin page.
 */
do_action( 'admin_enqueue_scripts', $hook_suffix );

/**
 * Fires when styles are printed for a specific admin page based on $hook_suffix.
 *
 * @since 2.6.0
 */
do_action( "admin_print_styles-{$hook_suffix}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

/**
 * Fires when styles are printed for all admin pages.
 *
 * @since 2.6.0
 */
do_action( 'admin_print_styles' );

/**
 * Fires when scripts are printed for a specific admin page based on $hook_suffix.
 *
 * @since 2.1.0
 */
do_action( "admin_print_scripts-{$hook_suffix}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

/**
 * Fires when scripts are printed for all admin pages.
 *
 * @since 2.1.0
 */
do_action( 'admin_print_scripts' );

/**
 * Fires in head section for a specific admin page.
 *
 * The dynamic portion of the hook, `$hook_suffix`, refers to the hook suffix
 * for the admin page.
 *
 * @since 2.1.0
 */
do_action( "admin_head-{$hook_suffix}" ); // phpcs:ignore paCMec.NamingConventions.ValidHookName.UseUnderscores

/**
 * Fires in head section for all admin pages.
 *
 * @since 2.1.0
 */
do_action( 'admin_head' );

if ( 'f' === get_user_setting( 'mfold' ) ) {
	$admin_body_class .= ' folded';
}

if ( ! get_user_setting( 'unfold' ) ) {
	$admin_body_class .= ' auto-fold';
}

if ( is_admin_bar_showing() ) {
	$admin_body_class .= ' admin-bar';
}

if ( is_rtl() ) {
	$admin_body_class .= ' rtl';
}

if ( $current_screen->post_type ) {
	$admin_body_class .= ' post-type-' . $current_screen->post_type;
}

if ( $current_screen->taxonomy ) {
	$admin_body_class .= ' taxonomy-' . $current_screen->taxonomy;
}

$admin_body_class .= ' branch-' . str_replace( array( '.', ',' ), '-', (float) get_bloginfo( 'version' ) );
$admin_body_class .= ' version-' . str_replace( '.', '-', preg_replace( '/^([.0-9]+).*/', '$1', get_bloginfo( 'version' ) ) );
$admin_body_class .= ' admin-color-' . sanitize_html_class( get_user_option( 'admin_color' ), 'fresh' );
$admin_body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_user_locale() ) ) );

if ( mt_is_mobile() ) {
	$admin_body_class .= ' mobile';
}

if ( is_multisite() ) {
	$admin_body_class .= ' multisite';
}

if ( is_network_admin() ) {
	$admin_body_class .= ' network-admin';
}

$admin_body_class .= ' no-customize-support no-svg';

if ( $current_screen->is_block_editor() ) {
	// Default to is-fullscreen-mode to avoid jumps in the UI.
	$admin_body_class .= ' block-editor-page is-fullscreen-mode mt-embed-responsive';
}

$error_get_last = error_get_last();

// Print a CSS class to make PHP errors visible.
if ( $error_get_last && MT_DEBUG && MT_DEBUG_DISPLAY && ini_get( 'display_errors' )
	// Don't print the class for PHP notices in mt-config.php, as they happen before MT_DEBUG takes effect,
	// and should not be displayed with the `error_reporting` level previously set in mt-load.php.
	&& ( E_NOTICE !== $error_get_last['type'] || 'mt-config.php' !== mt_basename( $error_get_last['file'] ) )
) {
	$admin_body_class .= ' php-error';
}

unset( $error_get_last );

?>
</head>
<?php
/**
 * Filters the CSS classes for the body tag in the admin.
 *
 * This filter differs from the {@see 'post_class'} and {@see 'body_class'} filters
 * in two important ways:
 *
 * 1. `$classes` is a space-separated string of class names instead of an array.
 * 2. Not all core admin classes are filterable, notably: mt-admin, mt-core-ui,
 *    and no-js cannot be removed.
 *
 * @since 2.3.0
 *
 * @param string $classes Space-separated list of CSS classes.
 */
$admin_body_classes = apply_filters( 'admin_body_class', '' );
$admin_body_classes = ltrim( $admin_body_classes . ' ' . $admin_body_class );
?>
<body class="mt-admin mt-core-ui no-js <?php echo $admin_body_classes; ?>">
<script type="text/javascript">
	document.body.className = document.body.className.replace('no-js','js');
</script>

<?php
// Make sure the customize body classes are correct as early as possible.
if ( current_user_can( 'customize' ) ) {
	mt_customize_support_script();
}
?>

<div id="mtwrap">
<?php require ABSPATH . 'mt-admin/menu-header.php'; ?>
<div id="mtcontent">

<?php
/**
 * Fires at the beginning of the content section in an admin page.
 *
 * @since 3.0.0
 */
do_action( 'in_admin_header' );
?>

<div id="mtbody" role="main">
<?php
unset( $blog_name, $total_update_count, $update_title );

$current_screen->set_parentage( $parent_file );

?>

<div id="mtbody-content">
<?php

$current_screen->render_screen_meta();

if ( is_network_admin() ) {
	/**
	 * Prints network admin screen notices.
	 *
	 * @since 3.1.0
	 */
	do_action( 'network_admin_notices' );
} elseif ( is_user_admin() ) {
	/**
	 * Prints user admin screen notices.
	 *
	 * @since 3.1.0
	 */
	do_action( 'user_admin_notices' );
} else {
	/**
	 * Prints admin screen notices.
	 *
	 * @since 3.1.0
	 */
	do_action( 'admin_notices' );
}

/**
 * Prints generic admin screen notices.
 *
 * @since 3.1.0
 */
do_action( 'all_admin_notices' );

if ( 'options-general.php' === $parent_file ) {
	require ABSPATH . 'mt-admin/options-head.php';
}
