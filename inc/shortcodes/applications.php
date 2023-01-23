<?php
/**
 * REU/RET application shortcode.
 *
 * @package uwmemc
 */

/**
 * Shortcode for displaying REU/RET application information based on
 * the theme settings (see settings.php).
 *
 * @param array  $attr    An array of shortcode attributes.
 * @param string $content The shortcode content to display.
 */
function uwmemc_application_shortcode( $attr = array(), $content ) {
	$atts = shortcode_atts( array( 'types' => '' ), $attr, 'uwmemc_application_open' );

	$types    = preg_split( '/, */', $atts['types'] );
	$settings = get_option( 'uwmemc_settings' );

	$app_status = array_map(
		function( $type ) use ( $settings ) {
			$setting_key = sprintf( '%s_application_visible', $type );
			if ( array_key_exists( $setting_key, $settings ) ) {
				return $settings[ $setting_key ];
			}
			return 'closed';
		},
		$types
	);

	$html = '';
	if ( ! in_array( 'closed', $app_status, true ) ) {
		$html = sprintf( '<div style="width: 200px;">%s</div>', do_shortcode( $content ) );
	}

	return $html;
}
add_shortcode( 'uwmemc_application_open', 'uwmemc_application_shortcode' );
