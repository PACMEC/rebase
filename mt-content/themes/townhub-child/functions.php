<?php
/**
 * @package TownHub - Directory & Listing Pacmec Theme
 * @author CTHthemes - http://themeforest.net/user/cththemes
 * @date 06-11-2019
 * @since 1.0.0
 * @version 1.0.0
 * @copyright Copyright ( C ) 2014 - 2019 cththemes.com . All rights reserved.
 * @license GNU General Public License version 3 or later; see LICENSE
 */
// Your php code goes here
function townhub_child_enqueue_styles() {
    $parent_style = 'townhub-style';
    mt_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array( 'townhub-fonts', 'townhub-plugins' ), null );
    mt_enqueue_style( 'townhub-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style , 'townhub-color'),
        mt_get_theme()->get('Version')
    );
}
add_action( 'mt_enqueue_scripts', 'townhub_child_enqueue_styles' );