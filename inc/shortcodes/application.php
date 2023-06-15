<?php
/**
 * REU/RET application shortcodes.
 *
 * @package uwmemc_child_theme
 */

/**
 * Shortcode for displaying REU/RET application information based on
 * the theme settings (see settings.php).
 *
 * @param array  $attr    An array of shortcode attributes.
 * @param string $content The shortcode content to display.
 */
function uwmemc_application_shortcode( $attr, $content ) {
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
		$html = sprintf( '<div>%s</div>', do_shortcode( $content ) );
	}

	return $html;
}
add_shortcode( 'uwmemc_application_open', 'uwmemc_application_shortcode' );


/**
 * Shortcode for displaying the REU/RET application link.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_application_link_shortcode( $attr ) {
	$atts = shortcode_atts( array( 'type' => '' ), $attr, 'uwmemc_application_link' );

	// Set application-type specific variables.
	$type                   = $atts['type'];
	$link_setting           = $type . '_application_link';
	$show_link_setting      = $type . '_application_visible';
	$closed_message_setting = $type . '_application_closed_message';

	// Get the appropriate settings values.
	$settings       = get_option( 'uwmemc_settings' );
	$link           = $settings[ $link_setting ];
	$show_button    = ( 'open' === $settings[ $show_link_setting ] ? true : false );
	$closed_message = $settings[ $closed_message_setting ];

	// Display link if application is open, otherwise, display the closed message.
	if ( $show_button ) {
		$link_display = '<div class="application-info"><a href="' . $link . '" target="_blank">Click here<sup><i class="fa-solid fa-arrow-up-right-from-square fa-sm"></i></sup></a> to access the ' . strtoupper( str_replace( '_', ' ', $type ) ) . ' application. <em>(Note: This link will take you to an external site.)</em></div>';
		return $link_display;
	} else {
		$message = '<div class="application-info application-closed">' . $closed_message . '</div>';
		return $message;
	}
}
add_shortcode( 'uwmemc_application_link', 'uwmemc_application_link_shortcode' );


/**
 * Add a shortcode for displaying the REU/RET 'Apply Now' button.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_apply_button_shortcode( $attr ) {
	$atts = shortcode_atts( array( 'type' => '' ), $attr, 'uwmemc_apply_now_button' );

	// Set application-type specific variables.
	$type                   = $atts['type'];
	$link                   = $type . '-application';
	$show_link_setting      = $type . '_application_visible';
	$closed_message_setting = $type . '_application_closed_message';

	// Get the appropriate settings values.
	$settings       = get_option( 'uwmemc_settings' );
	$show_button    = ( 'open' === $settings[ $show_link_setting ] ? true : false );
	$closed_message = $settings[ $closed_message_setting ];

	// Display link if application is open, otherwise, display the closed message.
	if ( $show_button ) {
		$button_shortcode = sprintf( '[uw_button style="arrow" size="large" color="purple" target="%s"]Apply Now[/uw_button]', home_url( str_replace( '_', '-', $link ) ) );
		$button           = do_shortcode( $button_shortcode );
		return $button;
	}
}
add_shortcode( 'uwmemc_apply_now_button', 'uwmemc_apply_button_shortcode' );


/**
 * Add a shortcode for displaying the REU/RET application deadlines.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_application_deadline_shortcode( $attr ) {
	$atts = shortcode_atts( array( 'type' => '' ), $attr, 'uwmemc_application_deadline' );

	// Set application-type specific variables.
	$type                = $atts['type'];
	$closed_date_setting = $type . '_application_closed_date';

	// Get the appropriate settings values.
	$settings    = get_option( 'uwmemc_settings' );
	$closed_date = $settings[ $closed_date_setting ];

	// Return the application closed date wrapped in a span.
	return '<span class="application-closed-date">' . $closed_date . '</span>';
}
add_shortcode( 'uwmemc_application_deadline', 'uwmemc_application_deadline_shortcode' );


/**
 * Add a shortcode for displaying the REU/RET notification deadlines.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_notification_deadline_shortcode( $attr ) {
	$atts = shortcode_atts( array( 'type' => '' ), $attr, 'uwmemc_notification_deadline' );

	// Set application-type specific variables.
	$type                      = $atts['type'];
	$notification_date_setting = $type . '_application_notification_date';

	// Get the appropriate settings values.
	$settings          = get_option( 'uwmemc_settings' );
	$notification_date = $settings[ $notification_date_setting ];

	// Return the notification date wrapped in a span.
	return '<span class="application-notification-date">' . $notification_date . '</span>';
}
add_shortcode( 'uwmemc_notification_deadline', 'uwmemc_notification_deadline_shortcode' );
