<?php
/**
 * Widget API: MT_Widget_Meta class
 *
 * @package paCMec
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Meta widget.
 *
 * Displays log in/out, RSS feed links, etc.
 *
 * @since 2.8.0
 *
 * @see MT_Widget
 */
class MT_Widget_Meta extends MT_Widget {

	/**
	 * Sets up a new Meta widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_meta',
			'description'                 => __( 'Login, RSS, &amp; paCMec.org links.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'meta', __( 'Meta' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current Meta widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Meta widget instance.
	 */
	public function widget( $args, $instance ) {
		$default_title = __( 'Meta' );
		$title         = ! empty( $instance['title'] ) ? $instance['title'] : $default_title;

		/** This filter is documented in mt-includes/widgets/class-mt-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

		/** This filter is documented in mt-includes/widgets/class-mt-nav-menu-widget.php */
		$format = apply_filters( 'navigation_widgets_format', $format );

		if ( 'html5' === $format ) {
			// The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
			$title      = trim( strip_tags( $title ) );
			$aria_label = $title ? $title : $default_title;
			echo '<nav role="navigation" aria-label="' . esc_attr( $aria_label ) . '">';
		}
		?>

		<ul>
			<?php mt_register(); ?>
			<li><?php mt_loginout(); ?></li>
			<li><a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><?php _e( 'Entries feed' ); ?></a></li>
			<li><a href="<?php echo esc_url( get_bloginfo( 'comments_rss2_url' ) ); ?>"><?php _e( 'Comments feed' ); ?></a></li>

			<?php
			/**
			 * Filters the "paCMec.org" list item HTML in the Meta widget.
			 *
			 * @since 3.6.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @param string $html     Default HTML for the paCMec.org list item.
			 * @param array  $instance Array of settings for the current widget.
			 */
			echo apply_filters(
				'widget_meta_poweredby',
				sprintf(
					'<li><a href="%1$s">%2$s</a></li>',
					esc_url( __( 'https://managertechnology.com.co/pacmec/' ) ),
					__( 'paCMec.org' )
				),
				$instance
			);

			mt_meta();
			?>

		</ul>

		<?php
		if ( 'html5' === $format ) {
			echo '</nav>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Meta widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            MT_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Meta widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = mt_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
	}
}