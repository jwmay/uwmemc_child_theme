<?php
/**
 * Theme functions and definitions.
 *
 * @package uwmemc
 */

/** Enqueue theme scripts and styles */
require_once 'inc/enqueue-scripts.php';

/** Load theme settings */
require_once 'inc/settings.php';

/** Load custom query functions */
require_once 'inc/query.php';

/** Manage taxonomies */
require_once 'inc/taxonomies.php';

/** Register shortcodes */
require_once 'inc/shortcodes/shortcodes.php';

/** Load sidebar functions */
require_once 'inc/sidebar.php';

/** Register theme settings */
require_once 'inc/theme-settings.php';

/** Register template functions */
require_once 'inc/template-functions.php';

/**
 * Disable automatic <p> tags
 *
 * Source: https://growthhackinginsights.com/how-to-disable-automatic-paragraph-tags-in-wordpress/
 */
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

/**
 * Removes empty paragraph tags from shortcodes in WordPress.
 *
 * Source: https://thomasgriffin.com/how-to-remove-empty-paragraph-tags-from-shortcodes-in-wordpress/
 *
 * @param string $content The post content.
 */
function uwmemc_remove_empty_paragraph_tags_from_shortcodes( $content ) {
	$to_fix = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']',
	);
	return strtr( $content, $to_fix );
}
add_filter( 'the_content', 'uwmemc_remove_empty_paragraph_tags_from_shortcodes' );

/**
 * Sets the excerpt length.
 *
 * @param number $length The excerpt length in words.
 */
function uwmemc_custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'uwmemc_custom_excerpt_length', 999 );
