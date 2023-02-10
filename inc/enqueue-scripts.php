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
function uwmemc_enqueue() {
	// Register CDN-hosted script for Isotope (https://isotope.metafizzy.co/) for quick filtering.
	wp_register_script(
		'Isotope',
		'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js',
		array(),
		'3.0.6',
		false
	);

	// Enqueue custom scripts.
	wp_enqueue_script(
		'uwmemc-child-theme-scripts',
		get_theme_file_uri( '/assets/js/custom.js' ),
		array( 'Isotope' ),
		wp_get_theme()->get( 'Version' ),
		false
	);

	// Enqueue custom styles.
	wp_enqueue_style(
		'uwmemc-child-theme-styles',
		get_theme_file_uri( '/assets/css/styles.css' ),
		array( 'uw_wp_theme-bootstrap' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'uwmemc_enqueue', 11 );

/**
 * Enqueue all styles and scripts for the admin pages.
 */
function uwmemc_enqueue_admin() {
	wp_enqueue_style(
		'uwmemc-child-theme-admin-styles',
		get_theme_file_uri( '/assets/css/admin.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'admin_enqueue_scripts', 'uwmemc_enqueue_admin', 11 );
