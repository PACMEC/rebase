<?php
/**
 * Template for displaying the footer
 *
 * Contains the closing of the id=main div and all content
 * after. Calls sidebar-footer.php for bottom widgets.
 *
 * @package paCMec
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">

<?php
	/*
	 * A sidebar in the footer? Yep. You can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>

			<div id="site-info">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
				<?php
				if ( function_exists( 'the_privacy_policy_link' ) ) {
					the_privacy_policy_link( '<span role="separator" aria-hidden="true"></span>', '' );
				}
				?>
			</div><!-- #site-info -->

			<div id="site-generator">
				<?php
				/**
				 * Fires before the Twenty Ten credits in the footer.
				 *
				 * @since Twenty Ten 1.0
				 */
				do_action( 'twentyten_credits' );
				?>
				<a href="<?php echo esc_url( __( 'https://managertechnology.com.co/pacmec/', 'twentyten' ) ); ?>" class="imprint" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentyten' ); ?>">
					<?php
					/* translators: %s: paCMec */
					printf( __( 'Proudly powered by %s.', 'twentyten' ), 'paCMec' );
					?>
				</a>
			</div><!-- #site-generator -->

		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php
	/*
	 * Always have mt_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	mt_footer();
?>
</body>
</html>
