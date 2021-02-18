<?php
/**
 * Sidebar containing the main widget area
 *
 * @package paCMec
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

$options        = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' !== $current_layout ) :
	?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'twentyeleven' ); ?></h3>
					<ul>
						<?php mt_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'twentyeleven' ); ?></h3>
					<ul>
						<?php mt_register(); ?>
						<li><?php mt_loginout(); ?></li>
						<?php mt_meta(); ?>
					</ul>
				</aside>

			<?php endif; // End sidebar widget area. ?>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>
