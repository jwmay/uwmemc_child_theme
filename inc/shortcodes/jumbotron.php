<?php
/**
 * Jumbotron shortcode.
 *
 * This custom jumbotron shortcode...
 *
 * The shortcode should be used in the content of a page or post
 * as follows:
 *
 *  [uwmemc_jumbotron title="" subtitle="" image="" col1="" col2="" col3=""]
 *
 * @package uwmemc
 */

/**
 * Shortcode for displaying a jumbotron.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_jumbotron_shortcode( $attr = array() ) {
	$atts = shortcode_atts(
		array(
			'col1'     => '',
			'col2'     => '',
			'col3'     => '',
			'image'    => '',
			'subtitle' => '',
			'title'    => '',
		),
		$attr,
		'uwmemc_jumbotron'
	);

	$html  = '<div class="jumbotron jumbotron-fluid img-background jumbo-simple full-width purple-overlay" style="background-image: url(' . esc_url( $atts['image'] ) . ');">';
	$html .= '<div class="w-60">';
	$html .= '<div class="container">';
	$html .= '<h2 class="display-3">' . $atts['title'] . '</h2>';
	$html .= '<div class="udub-slant-divider"><span></span></div>';
	$html .= '<p class="subtitle">' . $atts['subtitle'] . '</p>';
	$html .= '<div class="cols">';

	if ( $atts['col1'] ) {
		$html .= '<div>' . $atts['col1'] . '</div>';
	}

	if ( $atts['col2'] ) {
		$html .= '<div>' . $atts['col2'] . '</div>';
	}

	if ( $atts['col3'] ) {
		$html .= '<div>' . $atts['col3'] . '</div>';
	}

	$html .= '</div></div></div></div>';

	return $html;
}
add_shortcode( 'uwmemc_jumbotron', 'uwmemc_jumbotron_shortcode' );
