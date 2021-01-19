<?php
/**
 * Genesis Sample.
 *
 * This file adds the no featured area page template to the Genesis Sample Theme.
 *
 * Template Name: No Featured Area
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

/**
 * Handle body classes.
 */
add_filter( 'body_class', function ( array $classes ) {
	array_push( $classes, 'no-featured-area' );

	return $classes;
} );

/**
 * Disable featured area display.
 */
add_filter( 'genesis_display_featured_area', '__return_false', 10, 0 );

/**
 * Launch genesis loop.
 */
genesis();
