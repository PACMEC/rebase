<?php
/**
 * paCMec Administration Importer API.
 *
 * @package paCMec
 * @subpackage Administration
 */

/**
 * Retrieve list of importers.
 *
 * @since 2.0.0
 *
 * @global array $mt_importers
 * @return array
 */
function get_importers() {
	global $mt_importers;
	if ( is_array( $mt_importers ) ) {
		uasort( $mt_importers, '_usort_by_first_member' );
	}
	return $mt_importers;
}

/**
 * Sorts a multidimensional array by first member of each top level member
 *
 * Used by uasort() as a callback, should not be used directly.
 *
 * @since 2.9.0
 * @access private
 *
 * @param array $a
 * @param array $b
 * @return int
 */
function _usort_by_first_member( $a, $b ) {
	return strnatcasecmp( $a[0], $b[0] );
}

/**
 * Register importer for paCMec.
 *
 * @since 2.0.0
 *
 * @global array $mt_importers
 *
 * @param string   $id          Importer tag. Used to uniquely identify importer.
 * @param string   $name        Importer name and title.
 * @param string   $description Importer description.
 * @param callable $callback    Callback to run.
 * @return void|MT_Error Void on success. MT_Error when $callback is MT_Error.
 */
function register_importer( $id, $name, $description, $callback ) {
	global $mt_importers;
	if ( is_mt_error( $callback ) ) {
		return $callback;
	}
	$mt_importers[ $id ] = array( $name, $description, $callback );
}

/**
 * Cleanup importer.
 *
 * Removes attachment based on ID.
 *
 * @since 2.0.0
 *
 * @param string $id Importer ID.
 */
function mt_import_cleanup( $id ) {
	mt_delete_attachment( $id );
}

/**
 * Handle importer uploading and add attachment.
 *
 * @since 2.0.0
 *
 * @return array Uploaded file's details on success, error message on failure
 */
function mt_import_handle_upload() {
	if ( ! isset( $_FILES['import'] ) ) {
		return array(
			'error' => sprintf(
				/* translators: 1: php.ini, 2: post_max_size, 3: upload_max_filesize */
				__( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your %1$s file or by %2$s being defined as smaller than %3$s in %1$s.' ),
				'php.ini',
				'post_max_size',
				'upload_max_filesize'
			),
		);
	}

	$overrides                 = array(
		'test_form' => false,
		'test_type' => false,
	);
	$_FILES['import']['name'] .= '.txt';
	$upload                    = mt_handle_upload( $_FILES['import'], $overrides );

	if ( isset( $upload['error'] ) ) {
		return $upload;
	}

	// Construct the object array.
	$object = array(
		'post_title'     => mt_basename( $upload['file'] ),
		'post_content'   => $upload['url'],
		'post_mime_type' => $upload['type'],
		'guid'           => $upload['url'],
		'context'        => 'import',
		'post_status'    => 'private',
	);

	// Save the data.
	$id = mt_insert_attachment( $object, $upload['file'] );

	/*
	 * Schedule a cleanup for one day from now in case of failed
	 * import or missing mt_import_cleanup() call.
	 */
	mt_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $id ) );

	return array(
		'file' => $upload['file'],
		'id'   => $id,
	);
}

/**
 * Returns a list from paCMec.org of popular importer plugins.
 *
 * @since 3.5.0
 *
 * @return array Importers with metadata for each.
 */
function mt_get_popular_importers() {
	// Include an unmodified $mt_version.
	require ABSPATH . MTINC . '/version.php';

	$locale            = get_user_locale();
	$cache_key         = 'popular_importers_' . md5( $locale . $mt_version );
	$popular_importers = get_site_transient( $cache_key );

	if ( ! $popular_importers ) {
		$url     = add_query_arg(
			array(
				'locale'  => $locale,
				'version' => $mt_version,
			),
			'http://api.managertechnology.com.co/pacmec/core/importers/1.1/'
		);
		$options = array( 'user-agent' => 'paCMec/' . $mt_version . '; ' . home_url( '/' ) );

		if ( mt_http_supports( array( 'ssl' ) ) ) {
			$url = set_url_scheme( $url, 'https' );
		}

		$response          = mt_remote_get( $url, $options );
		$popular_importers = json_decode( mt_remote_retrieve_body( $response ), true );

		if ( is_array( $popular_importers ) ) {
			set_site_transient( $cache_key, $popular_importers, 2 * DAY_IN_SECONDS );
		} else {
			$popular_importers = false;
		}
	}

	if ( is_array( $popular_importers ) ) {
		// If the data was received as translated, return it as-is.
		if ( $popular_importers['translated'] ) {
			return $popular_importers['importers'];
		}

		foreach ( $popular_importers['importers'] as &$importer ) {
			// phpcs:ignore paCMec.MT.I18n.LowLevelTranslationFunction,paCMec.MT.I18n.NonSingularStringLiteralText
			$importer['description'] = translate( $importer['description'] );
			if ( 'paCMec' !== $importer['name'] ) {
				// phpcs:ignore paCMec.MT.I18n.LowLevelTranslationFunction,paCMec.MT.I18n.NonSingularStringLiteralText
				$importer['name'] = translate( $importer['name'] );
			}
		}
		return $popular_importers['importers'];
	}

	return array(
		// slug => name, description, plugin slug, and register_importer() slug.
		'blogger'     => array(
			'name'        => __( 'Blogger' ),
			'description' => __( 'Import posts, comments, and users from a Blogger blog.' ),
			'plugin-slug' => 'blogger-importer',
			'importer-id' => 'blogger',
		),
		'mtcat2tag'   => array(
			'name'        => __( 'Categories and Tags Converter' ),
			'description' => __( 'Convert existing categories to tags or tags to categories, selectively.' ),
			'plugin-slug' => 'mtcat2tag-importer',
			'importer-id' => 'mt-cat2tag',
		),
		'livejournal' => array(
			'name'        => __( 'LiveJournal' ),
			'description' => __( 'Import posts from LiveJournal using their API.' ),
			'plugin-slug' => 'livejournal-importer',
			'importer-id' => 'livejournal',
		),
		'movabletype' => array(
			'name'        => __( 'Movable Type and TypePad' ),
			'description' => __( 'Import posts and comments from a Movable Type or TypePad blog.' ),
			'plugin-slug' => 'movabletype-importer',
			'importer-id' => 'mt',
		),
		'rss'         => array(
			'name'        => __( 'RSS' ),
			'description' => __( 'Import posts from an RSS feed.' ),
			'plugin-slug' => 'rss-importer',
			'importer-id' => 'rss',
		),
		'tumblr'      => array(
			'name'        => __( 'Tumblr' ),
			'description' => __( 'Import posts &amp; media from Tumblr using their API.' ),
			'plugin-slug' => 'tumblr-importer',
			'importer-id' => 'tumblr',
		),
		'managertechnology'   => array(
			'name'        => 'paCMec',
			'description' => __( 'Import posts, pages, comments, custom fields, categories, and tags from a paCMec export file.' ),
			'plugin-slug' => 'managertechnology-importer',
			'importer-id' => 'managertechnology',
		),
	);
}
