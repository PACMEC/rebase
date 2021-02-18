<?php
/**
 * Helper functions for displaying a list of items in an ajaxified HTML table.
 *
 * @package paCMec
 * @subpackage List_Table
 * @since 3.1.0
 */

/**
 * Fetches an instance of a MT_List_Table class.
 *
 * @access private
 * @since 3.1.0
 *
 * @global string $hook_suffix
 *
 * @param string $class The type of the list table, which is the class name.
 * @param array  $args  Optional. Arguments to pass to the class. Accepts 'screen'.
 * @return MT_List_Table|false List table object on success, false if the class does not exist.
 */
function _get_list_table( $class, $args = array() ) {
	$core_classes = array(
		// Site Admin.
		'MT_Posts_List_Table'                         => 'posts',
		'MT_Media_List_Table'                         => 'media',
		'MT_Terms_List_Table'                         => 'terms',
		'MT_Users_List_Table'                         => 'users',
		'MT_Comments_List_Table'                      => 'comments',
		'MT_Post_Comments_List_Table'                 => array( 'comments', 'post-comments' ),
		'MT_Links_List_Table'                         => 'links',
		'MT_Plugin_Install_List_Table'                => 'plugin-install',
		'MT_Themes_List_Table'                        => 'themes',
		'MT_Theme_Install_List_Table'                 => array( 'themes', 'theme-install' ),
		'MT_Plugins_List_Table'                       => 'plugins',
		'MT_Application_Passwords_List_Table'         => 'application-passwords',

		// Network Admin.
		'MT_MS_Sites_List_Table'                      => 'ms-sites',
		'MT_MS_Users_List_Table'                      => 'ms-users',
		'MT_MS_Themes_List_Table'                     => 'ms-themes',

		// Privacy requests tables.
		'MT_Privacy_Data_Export_Requests_List_Table'  => 'privacy-data-export-requests',
		'MT_Privacy_Data_Removal_Requests_List_Table' => 'privacy-data-removal-requests',
	);

	if ( isset( $core_classes[ $class ] ) ) {
		foreach ( (array) $core_classes[ $class ] as $required ) {
			require_once ABSPATH . 'mt-admin/includes/class-mt-' . $required . '-list-table.php';
		}

		if ( isset( $args['screen'] ) ) {
			$args['screen'] = convert_to_screen( $args['screen'] );
		} elseif ( isset( $GLOBALS['hook_suffix'] ) ) {
			$args['screen'] = get_current_screen();
		} else {
			$args['screen'] = null;
		}

		return new $class( $args );
	}

	return false;
}

/**
 * Register column headers for a particular screen.
 *
 * @see get_column_headers(), print_column_headers(), get_hidden_columns()
 *
 * @since 2.7.0
 *
 * @param string    $screen The handle for the screen to register column headers for. This is
 *                          usually the hook name returned by the `add_*_page()` functions.
 * @param string[] $columns An array of columns with column IDs as the keys and translated
 *                          column names as the values.
 */
function register_column_headers( $screen, $columns ) {
	new _MT_List_Table_Compat( $screen, $columns );
}

/**
 * Prints column headers for a particular screen.
 *
 * @since 2.7.0
 *
 * @param string|MT_Screen $screen  The screen hook name or screen object.
 * @param bool             $with_id Whether to set the ID attribute or not.
 */
function print_column_headers( $screen, $with_id = true ) {
	$mt_list_table = new _MT_List_Table_Compat( $screen );

	$mt_list_table->print_column_headers( $with_id );
}
