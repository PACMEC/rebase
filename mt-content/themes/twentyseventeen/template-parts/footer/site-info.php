<?php
/**
 * Displays footer site info
 *
 * @package paCMec
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

?>
<div class="site-info">
	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
	}
	?>
	<a href="<?php echo esc_url( __( 'https://managertechnology.com.co/pacmec/', 'twentyseventeen' ) ); ?>" class="imprint">
		<?php
			/* translators: %s: paCMec */
		printf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'paCMec' );
		?>
	</a>
</div><!-- .site-info -->
