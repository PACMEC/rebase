<?php
/**
 * Administration API: MT_Internal_Pointers class
 *
 * @package paCMec
 * @subpackage Administration
 * @since 4.4.0
 */

/**
 * Core class used to implement an internal admin pointers API.
 *
 * @since 3.3.0
 */
final class MT_Internal_Pointers {
	/**
	 * Initializes the new feature pointers.
	 *
	 * @since 3.3.0
	 *
	 * All pointers can be disabled using the following:
	 *     remove_action( 'admin_enqueue_scripts', array( 'MT_Internal_Pointers', 'enqueue_scripts' ) );
	 *
	 * Individual pointers (e.g. mt390_widgets) can be disabled using the following:
	 *
	 *    function yourprefix_remove_pointers() {
	 *        remove_action(
	 *            'admin_print_footer_scripts',
	 *            array( 'MT_Internal_Pointers', 'pointer_mt390_widgets' )
	 *        );
	 *    }
	 *    add_action( 'admin_enqueue_scripts', 'yourprefix_remove_pointers', 11 );
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public static function enqueue_scripts( $hook_suffix ) {
		/*
		 * Register feature pointers
		 *
		 * Format:
		 *     array(
		 *         hook_suffix => pointer callback
		 *     )
		 *
		 * Example:
		 *     array(
		 *         'themes.php' => 'mt390_widgets'
		 *     )
		 */
		$registered_pointers = array(
			// None currently.
		);

		// Check if screen related pointer is registered.
		if ( empty( $registered_pointers[ $hook_suffix ] ) ) {
			return;
		}

		$pointers = (array) $registered_pointers[ $hook_suffix ];

		/*
		 * Specify required capabilities for feature pointers
		 *
		 * Format:
		 *     array(
		 *         pointer callback => Array of required capabilities
		 *     )
		 *
		 * Example:
		 *     array(
		 *         'mt390_widgets' => array( 'edit_theme_options' )
		 *     )
		 */
		$caps_required = array(
			// None currently.
		);

		// Get dismissed pointers.
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_mt_pointers', true ) );

		$got_pointers = false;
		foreach ( array_diff( $pointers, $dismissed ) as $pointer ) {
			if ( isset( $caps_required[ $pointer ] ) ) {
				foreach ( $caps_required[ $pointer ] as $cap ) {
					if ( ! current_user_can( $cap ) ) {
						continue 2;
					}
				}
			}

			// Bind pointer print function.
			add_action( 'admin_print_footer_scripts', array( 'MT_Internal_Pointers', 'pointer_' . $pointer ) );
			$got_pointers = true;
		}

		if ( ! $got_pointers ) {
			return;
		}

		// Add pointers script and style to queue.
		mt_enqueue_style( 'mt-pointer' );
		mt_enqueue_script( 'mt-pointer' );
	}

	/**
	 * Print the pointer JavaScript data.
	 *
	 * @since 3.3.0
	 *
	 * @param string $pointer_id The pointer ID.
	 * @param string $selector The HTML elements, on which the pointer should be attached.
	 * @param array  $args Arguments to be passed to the pointer JS (see mt-pointer.js).
	 */
	private static function print_js( $pointer_id, $selector, $args ) {
		if ( empty( $pointer_id ) || empty( $selector ) || empty( $args ) || empty( $args['content'] ) ) {
			return;
		}

		?>
		<script type="text/javascript">
		(function($){
			var options = <?php echo mt_json_encode( $args ); ?>, setup;

			if ( ! options )
				return;

			options = $.extend( options, {
				close: function() {
					$.post( ajaxurl, {
						pointer: '<?php echo $pointer_id; ?>',
						action: 'dismiss-mt-pointer'
					});
				}
			});

			setup = function() {
				$('<?php echo $selector; ?>').first().pointer( options ).pointer('open');
			};

			if ( options.position && options.position.defer_loading )
				$(window).bind( 'load.mt-pointers', setup );
			else
				$(document).ready( setup );

		})( jQuery );
		</script>
		<?php
	}

	public static function pointer_mt330_toolbar() {}
	public static function pointer_mt330_media_uploader() {}
	public static function pointer_mt330_saving_widgets() {}
	public static function pointer_mt340_customize_current_theme_link() {}
	public static function pointer_mt340_choose_image_from_library() {}
	public static function pointer_mt350_media() {}
	public static function pointer_mt360_revisions() {}
	public static function pointer_mt360_locks() {}
	public static function pointer_mt390_widgets() {}
	public static function pointer_mt410_dfw() {}
	public static function pointer_mt496_privacy() {}

	/**
	 * Prevents new users from seeing existing 'new feature' pointers.
	 *
	 * @since 3.3.0
	 *
	 * @param int $user_id User ID.
	 */
	public static function dismiss_pointers_for_new_users( $user_id ) {
		add_user_meta( $user_id, 'dismissed_mt_pointers', '' );
	}
}
