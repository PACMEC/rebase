<?php
/**
 * paCMec Credits Administration API.
 *
 * @package paCMec
 * @subpackage Administration
 * @since 4.4.0
 */

/**
 * Retrieve the contributor credits.
 *
 * @since 3.2.0
 * @since 5.6.0 Added the `$version` and `$locale` parameters.
 *
 * @param string $version paCMec version. Defaults to the current version.
 * @param string $locale  paCMec locale. Defaults to the current user's locale.
 * @return array|false A list of all of the contributors, or false on error.
 */
function mt_credits( $version = '', $locale = '' ) {
	if ( ! $version ) {
		// Include an unmodified $mt_version.
		require ABSPATH . MTINC . '/version.php';

		$version = $mt_version;
	}

	if ( ! $locale ) {
		$locale = get_user_locale();
	}

	$results = get_site_transient( 'pacmec_credits_' . $locale );

	if ( ! is_array( $results )
		|| false !== strpos( $version, '-' )
		|| ( isset( $results['data']['version'] ) && strpos( $version, $results['data']['version'] ) !== 0 )
	) {
		$url     = "http://api.managertechnology.com.co/pacmec/core/credits/1.1/?version={$version}&locale={$locale}";
		$options = array( 'user-agent' => 'paCMec/' . $version . '; ' . home_url( '/' ) );

		if ( mt_http_supports( array( 'ssl' ) ) ) {
			$url = set_url_scheme( $url, 'https' );
		}

		$response = mt_remote_get( $url, $options );

		if ( is_mt_error( $response ) || 200 != mt_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$results = json_decode( mt_remote_retrieve_body( $response ), true );

		if ( ! is_array( $results ) ) {
			return false;
		}

		set_site_transient( 'pacmec_credits_' . $locale, $results, DAY_IN_SECONDS );
	}

	return $results;
}

/**
 * Retrieve the link to a contributor's paCMec.org profile page.
 *
 * @access private
 * @since 3.2.0
 *
 * @param string $display_name  The contributor's display name (passed by reference).
 * @param string $username      The contributor's username.
 * @param string $profiles      URL to the contributor's paCMec.org profile page.
 */
function _mt_credits_add_profile_link( &$display_name, $username, $profiles ) {
	$display_name = '<a href="' . esc_url( sprintf( $profiles, $username ) ) . '">' . esc_html( $display_name ) . '</a>';
}

/**
 * Retrieve the link to an external library used in paCMec.
 *
 * @access private
 * @since 3.2.0
 *
 * @param string $data External library data (passed by reference).
 */
function _mt_credits_build_object_link( &$data ) {
	$data = '<a href="' . esc_url( $data[1] ) . '">' . esc_html( $data[0] ) . '</a>';
}

/**
 * Displays the title for a given group of contributors.
 *
 * @since 5.3.0
 *
 * @param array $group_data The current contributor group.
 */
function mt_credits_section_title( $group_data = array() ) {
	if ( ! count( $group_data ) ) {
		return;
	}

	if ( $group_data['name'] ) {
		if ( 'Translators' === $group_data['name'] ) {
			// Considered a special slug in the API response. (Also, will never be returned for es_CO.)
			$title = _x( 'Translators', 'Translate this to be the equivalent of English Translators in your language for the credits page Translators section' );
		} elseif ( isset( $group_data['placeholders'] ) ) {
			// phpcs:ignore paCMec.MT.I18n.LowLevelTranslationFunction,paCMec.MT.I18n.NonSingularStringLiteralText
			$title = vsprintf( translate( $group_data['name'] ), $group_data['placeholders'] );
		} else {
			// phpcs:ignore paCMec.MT.I18n.LowLevelTranslationFunction,paCMec.MT.I18n.NonSingularStringLiteralText
			$title = translate( $group_data['name'] );
		}

		echo '<h2 class="mt-people-group-title">' . esc_html( $title ) . "</h2>\n";
	}
}

/**
 * Displays a list of contributors for a given group.
 *
 * @since 5.3.0
 *
 * @param array  $credits The credits groups returned from the API.
 * @param string $slug    The current group to display.
 */
function mt_credits_section_list( $credits = array(), $slug = '' ) {
	$group_data   = isset( $credits['groups'][ $slug ] ) ? $credits['groups'][ $slug ] : array();
	$credits_data = $credits['data'];
	if ( ! count( $group_data ) ) {
		return;
	}

	if ( ! empty( $group_data['shuffle'] ) ) {
		shuffle( $group_data['data'] ); // We were going to sort by ability to pronounce "hierarchical," but that wouldn't be fair to Matt.
	}

	switch ( $group_data['type'] ) {
		case 'list':
			array_walk( $group_data['data'], '_mt_credits_add_profile_link', $credits_data['profiles'] );
			echo '<p class="mt-credits-list">' . mt_sprintf( '%l.', $group_data['data'] ) . "</p>\n\n";
			break;
		case 'libraries':
			array_walk( $group_data['data'], '_mt_credits_build_object_link' );
			echo '<p class="mt-credits-list">' . mt_sprintf( '%l.', $group_data['data'] ) . "</p>\n\n";
			break;
		default:
			$compact = 'compact' === $group_data['type'];
			$classes = 'mt-people-group ' . ( $compact ? 'compact' : '' );
			echo '<ul class="' . $classes . '" id="mt-people-group-' . $slug . '">' . "\n";
			foreach ( $group_data['data'] as $person_data ) {
				echo '<li class="mt-person" id="mt-person-' . esc_attr( $person_data[2] ) . '">' . "\n\t";
				echo '<a href="' . esc_url( sprintf( $credits_data['profiles'], $person_data[2] ) ) . '" class="web">';
				$size   = $compact ? 40 : 80;
				$data   = get_avatar_data( $person_data[1] . '@md5.gravatar.com', array( 'size' => $size ) );
				$data2x = get_avatar_data( $person_data[1] . '@md5.gravatar.com', array( 'size' => $size * 2 ) );
				echo '<img src="' . esc_url( $data['url'] ) . '" srcset="' . esc_url( $data2x['url'] ) . ' 2x" class="gravatar" alt="" />' . "\n";
				echo esc_html( $person_data[0] ) . "</a>\n\t";
				if ( ! $compact ) {
					// phpcs:ignore paCMec.MT.I18n.LowLevelTranslationFunction,paCMec.MT.I18n.NonSingularStringLiteralText
					echo '<span class="title">' . translate( $person_data[3] ) . "</span>\n";
				}
				echo "</li>\n";
			}
			echo "</ul>\n";
			break;
	}
}
