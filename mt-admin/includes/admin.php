<?php
/**
 * Core Administration API
 *
 * @package paCMec
 * @subpackage Administration
 * @since 2.3.0
 */

if ( ! defined( 'MT_ADMIN' ) ) {
	/*
	 * This file is being included from a file other than mt-admin/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', MT_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

/** paCMec Administration Hooks */
require_once ABSPATH . 'mt-admin/includes/admin-filters.php';

/** paCMec Bookmark Administration API */
require_once ABSPATH . 'mt-admin/includes/bookmark.php';

/** paCMec Comment Administration API */
require_once ABSPATH . 'mt-admin/includes/comment.php';

/** paCMec Administration File API */
require_once ABSPATH . 'mt-admin/includes/file.php';

/** paCMec Image Administration API */
require_once ABSPATH . 'mt-admin/includes/image.php';

/** paCMec Media Administration API */
require_once ABSPATH . 'mt-admin/includes/media.php';

/** paCMec Import Administration API */
require_once ABSPATH . 'mt-admin/includes/import.php';

/** paCMec Misc Administration API */
require_once ABSPATH . 'mt-admin/includes/misc.php';

/** paCMec Misc Administration API */
require_once ABSPATH . 'mt-admin/includes/class-mt-privacy-policy-content.php';

/** paCMec Options Administration API */
require_once ABSPATH . 'mt-admin/includes/options.php';

/** paCMec Plugin Administration API */
require_once ABSPATH . 'mt-admin/includes/plugin.php';

/** paCMec Post Administration API */
require_once ABSPATH . 'mt-admin/includes/post.php';

/** paCMec Administration Screen API */
require_once ABSPATH . 'mt-admin/includes/class-mt-screen.php';
require_once ABSPATH . 'mt-admin/includes/screen.php';

/** paCMec Taxonomy Administration API */
require_once ABSPATH . 'mt-admin/includes/taxonomy.php';

/** paCMec Template Administration API */
require_once ABSPATH . 'mt-admin/includes/template.php';

/** paCMec List Table Administration API and base class */
require_once ABSPATH . 'mt-admin/includes/class-mt-list-table.php';
require_once ABSPATH . 'mt-admin/includes/class-mt-list-table-compat.php';
require_once ABSPATH . 'mt-admin/includes/list-table.php';

/** paCMec Theme Administration API */
require_once ABSPATH . 'mt-admin/includes/theme.php';

/** paCMec Privacy Functions */
require_once ABSPATH . 'mt-admin/includes/privacy-tools.php';

/** paCMec Privacy List Table classes. */
// Previously in mt-admin/includes/user.php. Need to be loaded for backward compatibility.
require_once ABSPATH . 'mt-admin/includes/class-mt-privacy-requests-table.php';
require_once ABSPATH . 'mt-admin/includes/class-mt-privacy-data-export-requests-list-table.php';
require_once ABSPATH . 'mt-admin/includes/class-mt-privacy-data-removal-requests-list-table.php';

/** paCMec User Administration API */
require_once ABSPATH . 'mt-admin/includes/user.php';

/** paCMec Site Icon API */
require_once ABSPATH . 'mt-admin/includes/class-mt-site-icon.php';

/** paCMec Update Administration API */
require_once ABSPATH . 'mt-admin/includes/update.php';

/** paCMec Deprecated Administration API */
require_once ABSPATH . 'mt-admin/includes/deprecated.php';

/** paCMec Multisite support API */
if ( is_multisite() ) {
	require_once ABSPATH . 'mt-admin/includes/ms-admin-filters.php';
	require_once ABSPATH . 'mt-admin/includes/ms.php';
	require_once ABSPATH . 'mt-admin/includes/ms-deprecated.php';
}
