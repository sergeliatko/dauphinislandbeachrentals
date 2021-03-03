<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

//load TechSpokes Genesis Tools
require_once( dirname( __FILE__ ) . '/genesis-tools/autoload.php' );

//Some modifications are needed before Genesis engine is started

/**
 * Add structural wrap around top menu.
 */
add_filter( 'genesis_theme_support_structural_wraps', function ( array $structural_wraps ) {
	array_push( $structural_wraps, 'menu-top' );

	return $structural_wraps;
}, 10, 1 );

// Now starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {
	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );
}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Reposition breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_after_loop', 'genesis_do_breadcrumbs', 10, 0 );

// Add top menu
add_action( 'genesis_before_header', 'genesis_sample_do_top_nav', 10, 0 );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 3 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @param array $args Original menu options.
 *
 * @return array Menu options with depth set to 1.
 * @since 2.2.3
 */
function genesis_sample_secondary_menu_args( array $args ): array {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'wp_nav_menu_args', 'genesis_sample_flatten_top_menu', 10, 1 );
/**
 * @param array $args
 *
 * @return array
 */
function genesis_sample_flatten_top_menu( array $args ): array {
	if ( 'top' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;
}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @param int $size Original icon size.
 *
 * @return int Modified icon size.
 * @since        2.2.3
 *
 * @noinspection PhpUnusedParameterInspection
 */
function genesis_sample_author_box_gravatar( int $size ): int {
	$size = 90;

	return $size;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @param array $args Gravatar settings.
 *
 * @return array Gravatar settings with modified size.
 * @since 2.2.3
 *
 */
function genesis_sample_comments_gravatar( array $args ): array {

	$args['avatar_size'] = 60;

	return $args;

}

/**
 * Displays top menu.
 */
function genesis_sample_do_top_nav() {
	// Do nothing if menu not supported.
	if ( ! genesis_nav_menu_supported( 'top' ) ) {
		return;
	}

	$class = 'menu genesis-nav-menu menu-top';
	if ( genesis_superfish_enabled() ) {
		$class .= ' js-superfish';
	}

	genesis_nav_menu(
		[
			'theme_location' => 'top',
			'menu_class'     => $class,
		]
	);
}

/**
 * Hook into Slider Pro Posts query
 */
add_filter( 'sliderpro_posts_query_args', function ( array $query ) {
	if ( empty( $query['meta_query'] ) ) {
		$query['meta_query'] = array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		);
	}

	return $query;
}, 10, 1 );

/**
 * Hide archive description on vacation rentals archives when filters are active or is paged
 */
add_filter( 'genesis_cpt_archive_intro_text_output', function ( string $text ) {
	if (
		is_post_type_archive( array( 'vacation_rental' ) )
		&& ( ! empty( $_GET ) || is_paged() )
	) {
		return '';
	}

	return $text;
}, 10, 1 );

/**
 * Hide website url field in comments across the website.
 */
add_filter( 'comment_form_default_fields', function ( array $fields ) {
	unset( $fields['url'] );

	if ( isset( $fields['cookies'] ) ) {
		$fields['cookies'] = str_replace(
			__( 'Save my name, email, and website in this browser for the next time I comment.' ),
			__( 'Save my name and email in this browser for the next time I comment.', 'dauphinislandbeachrentals' ),
			$fields['cookies']
		);
	}

	return $fields;
}, 10, 1 );
