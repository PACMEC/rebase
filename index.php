<?php
/**
 * Front to the paCMec application. This file doesn't do anything, but loads
 * mt-blog-header.php which does and tells paCMec to load the theme.
 *
 * @package paCMec
 */

/**
 * Tells paCMec to load the paCMec theme and output it.
 *
 * @var bool
 */
define( 'MT_USE_THEMES', true );

/** Loads the paCMec Environment and Template */
require __DIR__ . '/mt-blog-header.php';
