<?php
/**
 * Enqueue all styles and scripts.
 *
 * @package uwmemc
 */

/**
 * Enque custom theme styles.
 *
 * Enqueue child stylesheet and require the uw_wp_theme bootstrap stylesheet.
 */
function uw_child_enqueue() {
	// Enqueue custom styles.
	wp_enqueue_style(
		'uwmemc-child-theme-styles',
		get_stylesheet_directory_uri() . '/assets/css/styles.css',
		array( 'uw_wp_theme-bootstrap' ),
		wp_get_theme()->get( 'Version' )
	);

	// Enqueue custom scripts.
	wp_enqueue_script(
		'uwmemc-child-theme-scripts',
		get_stylesheet_directory_uri() . '/assets/js/custom.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		false
	);

	// Enqueue CDN-hosted script for Isotope (https://isotope.metafizzy.co/) for quick filtering.
	wp_register_script(
		'Isotope',
		'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',
		array(),
		'3.0.6',
		true
	);
	wp_enqueue_script( 'Isotope' );
}
add_action( 'wp_enqueue_scripts', 'uw_child_enqueue', 11 );


/**
 * Enqueue all styles and scripts for the admin pages.
 */
function uw_child_enqueue_admin() {
	wp_enqueue_style(
		'uw_wp_theme-child-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' ),
		'all'
	);
}
add_action( 'admin_enqueue_scripts', 'uw_child_enqueue_admin', 11 );
