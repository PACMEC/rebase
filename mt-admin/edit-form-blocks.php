<?php
/**
 * The block editor page.
 *
 * @since 5.0.0
 *
 * @package paCMec
 * @subpackage Administration
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * @global string       $post_type
 * @global MT_Post_Type $post_type_object
 * @global MT_Post      $post             Global post object.
 * @global string       $title
 * @global array        $editor_styles
 * @global array        $mt_meta_boxes
 */
global $post_type, $post_type_object, $post, $title, $editor_styles, $mt_meta_boxes;

// Flag that we're loading the block editor.
$current_screen = get_current_screen();
$current_screen->is_block_editor( true );

/*
 * Emoji replacement is disabled for now, until it plays nicely with React.
 */
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

/*
 * Block editor implements its own Options menu for toggling Document Panels.
 */
add_filter( 'screen_options_show_screen', '__return_false' );

mt_enqueue_script( 'heartbeat' );
mt_enqueue_script( 'mt-edit-post' );
mt_enqueue_script( 'mt-format-library' );

$rest_base = ! empty( $post_type_object->rest_base ) ? $post_type_object->rest_base : $post_type_object->name;

// Preload common data.
$preload_paths = array(
	'/',
	'/mt/v2/types?context=edit',
	'/mt/v2/taxonomies?per_page=-1&context=edit',
	'/mt/v2/themes?status=active',
	sprintf( '/mt/v2/%s/%s?context=edit', $rest_base, $post->ID ),
	sprintf( '/mt/v2/types/%s?context=edit', $post_type ),
	sprintf( '/mt/v2/users/me?post_type=%s&context=edit', $post_type ),
	array( '/mt/v2/media', 'OPTIONS' ),
	array( '/mt/v2/blocks', 'OPTIONS' ),
	sprintf( '/mt/v2/%s/%d/autosaves?context=edit', $rest_base, $post->ID ),
);

/**
 * Preload common data by specifying an array of REST API paths that will be preloaded.
 *
 * Filters the array of paths that will be preloaded.
 *
 * @since 5.0.0
 *
 * @param string[] $preload_paths Array of paths to preload.
 * @param MT_Post  $post          Post being edited.
 */
$preload_paths = apply_filters( 'block_editor_preload_paths', $preload_paths, $post );

/*
 * Ensure the global $post remains the same after API data is preloaded.
 * Because API preloading can call the_content and other filters, plugins
 * can unexpectedly modify $post.
 */
$backup_global_post = clone $post;

$preload_data = array_reduce(
	$preload_paths,
	'rest_preload_api_request',
	array()
);

// Restore the global $post as it was before API preloading.
$post = $backup_global_post;

mt_add_inline_script(
	'mt-api-fetch',
	sprintf( 'mt.apiFetch.use( mt.apiFetch.createPreloadingMiddleware( %s ) );', mt_json_encode( $preload_data ) ),
	'after'
);

mt_add_inline_script(
	'mt-blocks',
	sprintf( 'mt.blocks.setCategories( %s );', mt_json_encode( get_block_categories( $post ) ) ),
	'after'
);

/*
 * Assign initial edits, if applicable. These are not initially assigned to the persisted post,
 * but should be included in its save payload.
 */
$initial_edits = null;
$is_new_post   = false;
if ( 'auto-draft' === $post->post_status ) {
	$is_new_post = true;
	// Override "(Auto Draft)" new post default title with empty string, or filtered value.
	$initial_edits = array(
		'title'   => $post->post_title,
		'content' => $post->post_content,
		'excerpt' => $post->post_excerpt,
	);
}

// Preload server-registered block schemas.
mt_add_inline_script(
	'mt-blocks',
	'mt.blocks.unstable__bootstrapServerSideBlockDefinitions(' . mt_json_encode( get_block_editor_server_block_settings() ) . ');'
);

// Get admin url for handling meta boxes.
$meta_box_url = admin_url( 'post.php' );
$meta_box_url = add_query_arg(
	array(
		'post'                  => $post->ID,
		'action'                => 'edit',
		'meta-box-loader'       => true,
		'meta-box-loader-nonce' => mt_create_nonce( 'meta-box-loader' ),
	),
	$meta_box_url
);
mt_add_inline_script(
	'mt-editor',
	sprintf( 'var _mtMetaBoxUrl = %s;', mt_json_encode( $meta_box_url ) ),
	'before'
);


/*
 * Initialize the editor.
 */

$align_wide         = get_theme_support( 'align-wide' );
$color_palette      = current( (array) get_theme_support( 'editor-color-palette' ) );
$font_sizes         = current( (array) get_theme_support( 'editor-font-sizes' ) );
$gradient_presets   = current( (array) get_theme_support( 'editor-gradient-presets' ) );
$custom_line_height = get_theme_support( 'custom-line-height' );
$custom_units       = get_theme_support( 'custom-units' );
$custom_spacing     = get_theme_support( 'custom-spacing' );

/**
 * Filters the allowed block types for the editor, defaulting to true (all
 * block types supported).
 *
 * @since 5.0.0
 *
 * @param bool|array $allowed_block_types Array of block type slugs, or
 *                                        boolean to enable/disable all.
 * @param MT_Post    $post                The post resource data.
 */
$allowed_block_types = apply_filters( 'allowed_block_types', true, $post );

/*
 * Get all available templates for the post/page attributes meta-box.
 * The "Default template" array element should only be added if the array is
 * not empty so we do not trigger the template select element without any options
 * besides the default value.
 */
$available_templates = mt_get_theme()->get_page_templates( get_post( $post->ID ) );
$available_templates = ! empty( $available_templates ) ? array_merge(
	array(
		/** This filter is documented in mt-admin/includes/meta-boxes.php */
		'' => apply_filters( 'default_page_template_title', __( 'Default template' ), 'rest-api' ),
	),
	$available_templates
) : $available_templates;

// Media settings.
$max_upload_size = mt_max_upload_size();
if ( ! $max_upload_size ) {
	$max_upload_size = 0;
}

// Editor Styles.
$styles = array(
	array(
		'css' => file_get_contents(
			is_rtl()
				? ABSPATH . MTINC . '/css/dist/editor/editor-styles-rtl.css'
				: ABSPATH . MTINC . '/css/dist/editor/editor-styles.css'
		),
	),
);

$styles[] = array(
	'css' => 'body { font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif }',
);

if ( $editor_styles && current_theme_supports( 'editor-styles' ) ) {
	foreach ( $editor_styles as $style ) {
		if ( preg_match( '~^(https?:)?//~', $style ) ) {
			$response = mt_remote_get( $style );
			if ( ! is_mt_error( $response ) ) {
				$styles[] = array(
					'css' => mt_remote_retrieve_body( $response ),
				);
			}
		} else {
			$file = get_theme_file_path( $style );
			if ( is_file( $file ) ) {
				$styles[] = array(
					'css'     => file_get_contents( $file ),
					'baseURL' => get_theme_file_uri( $style ),
				);
			}
		}
	}
}

// Default editor styles.
$default_editor_styles = array(
	array(
		'css' => file_get_contents(
			is_rtl()
				? ABSPATH . MTINC . '/css/dist/editor/editor-styles-rtl.css'
				: ABSPATH . MTINC . '/css/dist/editor/editor-styles.css'
		),
	),
);

// Image sizes.

/** This filter is documented in mt-admin/includes/media.php */
$image_size_names = apply_filters(
	'image_size_names_choose',
	array(
		'thumbnail' => __( 'Thumbnail' ),
		'medium'    => __( 'Medium' ),
		'large'     => __( 'Large' ),
		'full'      => __( 'Full Size' ),
	)
);

$available_image_sizes = array();
foreach ( $image_size_names as $image_size_slug => $image_size_name ) {
	$available_image_sizes[] = array(
		'slug' => $image_size_slug,
		'name' => $image_size_name,
	);
}

$image_dimensions = array();
$all_sizes        = mt_get_registered_image_subsizes();
foreach ( $available_image_sizes as $size ) {
	$key = $size['slug'];
	if ( isset( $all_sizes[ $key ] ) ) {
		$image_dimensions[ $key ] = $all_sizes[ $key ];
	}
}

// Lock settings.
$user_id = mt_check_post_lock( $post->ID );
if ( $user_id ) {
	$locked = false;

	/** This filter is documented in mt-admin/includes/post.php */
	if ( apply_filters( 'show_post_locked_dialog', true, $post, $user_id ) ) {
		$locked = true;
	}

	$user_details = null;
	if ( $locked ) {
		$user         = get_userdata( $user_id );
		$user_details = array(
			'name' => $user->display_name,
		);
		$avatar       = get_avatar_url( $user_id, array( 'size' => 64 ) );
	}

	$lock_details = array(
		'isLocked' => $locked,
		'user'     => $user_details,
	);
} else {
	// Lock the post.
	$active_post_lock = mt_set_post_lock( $post->ID );
	if ( $active_post_lock ) {
		$active_post_lock = esc_attr( implode( ':', $active_post_lock ) );
	}

	$lock_details = array(
		'isLocked'       => false,
		'activePostLock' => $active_post_lock,
	);
}

/**
 * Filters the body placeholder text.
 *
 * @since 5.0.0
 *
 * @param string  $text Placeholder text. Default 'Start writing or type / to choose a block'.
 * @param MT_Post $post Post object.
 */
$body_placeholder = apply_filters( 'write_your_story', __( 'Start writing or type / to choose a block' ), $post );

$editor_settings = array(
	'alignWide'                            => $align_wide,
	'availableTemplates'                   => $available_templates,
	'allowedBlockTypes'                    => $allowed_block_types,
	'disableCustomColors'                  => get_theme_support( 'disable-custom-colors' ),
	'disableCustomFontSizes'               => get_theme_support( 'disable-custom-font-sizes' ),
	'disableCustomGradients'               => get_theme_support( 'disable-custom-gradients' ),
	'disablePostFormats'                   => ! current_theme_supports( 'post-formats' ),
	/** This filter is documented in mt-admin/edit-form-advanced.php */
	'titlePlaceholder'                     => apply_filters( 'enter_title_here', __( 'Add title' ), $post ),
	'bodyPlaceholder'                      => $body_placeholder,
	'isRTL'                                => is_rtl(),
	'autosaveInterval'                     => AUTOSAVE_INTERVAL,
	'maxUploadFileSize'                    => $max_upload_size,
	'allowedMimeTypes'                     => get_allowed_mime_types(),
	'styles'                               => $styles,
	'defaultEditorStyles'                  => $default_editor_styles,
	'imageSizes'                           => $available_image_sizes,
	'imageDimensions'                      => $image_dimensions,
	'richEditingEnabled'                   => user_can_richedit(),
	'postLock'                             => $lock_details,
	'postLockUtils'                        => array(
		'nonce'       => mt_create_nonce( 'lock-post_' . $post->ID ),
		'unlockNonce' => mt_create_nonce( 'update-post_' . $post->ID ),
		'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
	),
	'__experimentalBlockPatterns'          => MT_Block_Patterns_Registry::get_instance()->get_all_registered(),
	'__experimentalBlockPatternCategories' => MT_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(),

	// Whether or not to load the 'postcustom' meta box is stored as a user meta
	// field so that we're not always loading its assets.
	'enableCustomFields'                   => (bool) get_user_meta( get_current_user_id(), 'enable_custom_fields', true ),
	'enableCustomLineHeight'               => $custom_line_height,
	'enableCustomUnits'                    => $custom_units,
	'enableCustomSpacing'                  => $custom_spacing,
);

$autosave = mt_get_post_autosave( $post_ID );
if ( $autosave ) {
	if ( mysql2date( 'U', $autosave->post_modified_gmt, false ) > mysql2date( 'U', $post->post_modified_gmt, false ) ) {
		$editor_settings['autosave'] = array(
			'editLink' => get_edit_post_link( $autosave->ID ),
		);
	} else {
		mt_delete_post_revision( $autosave->ID );
	}
}

if ( false !== $color_palette ) {
	$editor_settings['colors'] = $color_palette;
}

if ( false !== $font_sizes ) {
	$editor_settings['fontSizes'] = $font_sizes;
}

if ( false !== $gradient_presets ) {
	$editor_settings['gradients'] = $gradient_presets;
}

if ( ! empty( $post_type_object->template ) ) {
	$editor_settings['template']     = $post_type_object->template;
	$editor_settings['templateLock'] = ! empty( $post_type_object->template_lock ) ? $post_type_object->template_lock : false;
}

// If there's no template set on a new post, use the post format, instead.
if ( $is_new_post && ! isset( $editor_settings['template'] ) && 'post' === $post->post_type ) {
	$post_format = get_post_format( $post );
	if ( in_array( $post_format, array( 'audio', 'gallery', 'image', 'quote', 'video' ), true ) ) {
		$editor_settings['template'] = array( array( "core/$post_format" ) );
	}
}

/**
 * Scripts
 */
mt_enqueue_media(
	array(
		'post' => $post->ID,
	)
);
mt_tinymce_inline_scripts();
mt_enqueue_editor();


/**
 * Styles
 */
mt_enqueue_style( 'mt-edit-post' );
mt_enqueue_style( 'mt-format-library' );

/**
 * Fires after block assets have been enqueued for the editing interface.
 *
 * Call `add_action` on any hook before 'admin_enqueue_scripts'.
 *
 * In the function call you supply, simply use `mt_enqueue_script` and
 * `mt_enqueue_style` to add your functionality to the block editor.
 *
 * @since 5.0.0
 */
do_action( 'enqueue_block_editor_assets' );

// In order to duplicate classic meta box behaviour, we need to run the classic meta box actions.
require_once ABSPATH . 'mt-admin/includes/meta-boxes.php';
register_and_do_post_meta_boxes( $post );

// Check if the Custom Fields meta box has been removed at some point.
$core_meta_boxes = $mt_meta_boxes[ $current_screen->id ]['normal']['core'];
if ( ! isset( $core_meta_boxes['postcustom'] ) || ! $core_meta_boxes['postcustom'] ) {
	unset( $editor_settings['enableCustomFields'] );
}

/**
 * Filters the settings to pass to the block editor.
 *
 * @since 5.0.0
 *
 * @param array   $editor_settings Default editor settings.
 * @param MT_Post $post            Post being edited.
 */
$editor_settings = apply_filters( 'block_editor_settings', $editor_settings, $post );

$init_script = <<<JS
( function() {
	window._mtLoadBlockEditor = new Promise( function( resolve ) {
		mt.domReady( function() {
			resolve( mt.editPost.initializeEditor( 'editor', "%s", %d, %s, %s ) );
		} );
	} );
} )();
JS;

$script = sprintf(
	$init_script,
	$post->post_type,
	$post->ID,
	mt_json_encode( $editor_settings ),
	mt_json_encode( $initial_edits )
);
mt_add_inline_script( 'mt-edit-post', $script );

require_once ABSPATH . 'mt-admin/admin-header.php';
?>

<div class="block-editor">
	<h1 class="screen-reader-text hide-if-no-js"><?php echo esc_html( $title ); ?></h1>
	<div id="editor" class="block-editor__container hide-if-no-js"></div>
	<div id="metaboxes" class="hidden">
		<?php the_block_editor_meta_boxes(); ?>
	</div>

	<?php // JavaScript is disabled. ?>
	<div class="wrap hide-if-js block-editor-no-js">
		<h1 class="mt-heading-inline"><?php echo esc_html( $title ); ?></h1>
		<div class="notice notice-error notice-alt">
			<p>
				<?php
					$message = sprintf(
						/* translators: %s: A link to install the Classic Editor plugin. */
						__( 'The block editor requires JavaScript. Please enable JavaScript in your browser settings, or try the <a href="%s">Classic Editor plugin</a>.' ),
						esc_url( mt_nonce_url( self_admin_url( 'plugin-install.php?tab=favorites&user=pacmecdotorg&save=0' ), 'save_mtorg_username_' . get_current_user_id() ) )
					);

					/**
					 * Filters the message displayed in the block editor interface when JavaScript is
					 * not enabled in the browser.
					 *
					 * @since 5.0.3
					 *
					 * @param string  $message The message being displayed.
					 * @param MT_Post $post    The post being edited.
					 */
					echo apply_filters( 'block_editor_no_javascript_message', $message, $post );
					?>
			</p>
		</div>
	</div>
</div>
