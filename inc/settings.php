<?php
/**
 * Theme settings for managing REU, RET, and AYRA REU applications.
 *
 * @package uwmemc
 */

/**
 * Add a top-level menu for accessing the settings page.
 */
function uwmemc_settings_page() {
	add_menu_page(
		'MEM&middot;C Settings',
		'MEM&middot;C Settings',
		'manage_options',
		'uwmemc-settings',
		'uwmemc_settings_page_html',
		'none',
		3
	);
}
add_action( 'admin_menu', 'uwmemc_settings_page' );

/**
 * Returns an array of default settings.
 */
function uwmemc_get_default_settings() {
	$settings = array(
		'reu_application_visible'                => 'open',
		'reu_application_closed_date'            => 'MMM DD, YYYY',
		'reu_application_closed_message'         => 'Applications are now closed.',
		'reu_application_link'                   => 'url',
		'reu_application_notification_date'      => 'MMM DD, YYYY',
		'ret_application_visible'                => 'open',
		'ret_application_closed_date'            => 'MMM DD, YYYY',
		'ret_application_closed_message'         => 'Applications are now closed.',
		'ret_application_link'                   => 'url',
		'ret_application_notification_date'      => 'MMM DD, YYYY',
		'ayra_reu_application_visible'           => 'open',
		'ayra_reu_application_closed_date'       => 'MMM DD, YYYY',
		'ayra_reu_application_closed_message'    => 'Applications are now closed.',
		'ayra_reu_application_link'              => 'url',
		'ayra_reu_application_notification_date' => 'MMM DD, YYYY',
	);
	return $settings;
}

/**
 * Set default theme settings.
 */
function uwmemc_default_settings_init() {
	global $uwmemc_settings;
	$uwmemc_settings = get_option( 'uwmemc_settings' );
	if ( false === $uwmemc_settings ) {
		$uwmemc_settings = uwmemc_get_default_settings();
	}
	update_option( 'uwmemc_settings', $uwmemc_settings );
}
add_action( 'after_setup_theme', 'uwmemc_default_settings_init', 9 );

/**
 * Generates the settings page HTML.
 */
function uwmemc_settings_page_html() {
	// Exit the settings page if user is not an admin.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Show success message when settings have been updated.
	settings_errors();

	// Construct the display for the settings form. ?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
		<?php
			settings_fields( 'uwmemc' );
			do_settings_sections( 'uwmemc' );
			submit_button();
		?>
		</form>
	</div>
	<?php
}

/**
 * Register and initialize the custom settings.
 */
function uwmemc_settings_init() {
	// Register the settings array.
	register_setting( 'uwmemc', 'uwmemc_settings' );

	// Register the REU, RET, and AYREU Application settings sections and fields.
	uwmemc_reu_application_settings_init();
	uwmemc_ret_application_settings_init();
	uwmemc_ayra_reu_application_settings_init();
}
add_action( 'admin_init', 'uwmemc_settings_init' );

/**
 * Register and initiate the REU Application custom settings.
 */
function uwmemc_reu_application_settings_init() {
	uwmemc_application_settings_init( 'reu' );
}

/**
 * Register and initiate the RET Application custom settings.
 */
function uwmemc_ret_application_settings_init() {
	uwmemc_application_settings_init( 'ret' );
}

/**
 * Register and initiate the AYREU Application custom settings.
 */
function uwmemc_ayra_reu_application_settings_init() {
	uwmemc_application_settings_init( 'ayra_reu' );
}

/**
 * Register and initiate the applicaiton custom settings.
 *
 * @param string $app_type The application type.
 */
function uwmemc_application_settings_init( $app_type ) {
	$section_id    = 'uwmemc_' . $app_type . '_applications_section';
	$section_title = str_replace( '_', ' ', strtoupper( $app_type ) ) . ' Application';
	$field_labels  = array(
		'visible'           => $app_type . '_application_visible',
		'closed_date'       => $app_type . '_application_closed_date',
		'closed_message'    => $app_type . '_application_closed_message',
		'link'              => $app_type . '_application_link',
		'notification_date' => $app_type . '_application_notification_date',
	);

	// Register the application settings section.
	add_settings_section(
		$section_id,
		$section_title,
		'uwmemc_applications_section_callback',
		'uwmemc'
	);

	// Register the application visibility setting.
	add_settings_field(
		'uwmemc_' . $app_type . '_application_visibility',
		'Application Availability',
		'uwmemc_show_application_callback',
		'uwmemc',
		$section_id,
		array(
			'app_type'  => $app_type,
			'label_for' => $field_labels['visible'],
		)
	);

	// Register the application closed date setting.
	add_settings_field(
		'uwmemc_' . $app_type . '_application_closed_date',
		'Application Closed Date',
		'uwmemc_application_closed_date_callback',
		'uwmemc',
		$section_id,
		array(
			'app_type'  => $app_type,
			'label_for' => $field_labels['closed_date'],
		)
	);

	// Register the applicaiton closed message setting.
	add_settings_field(
		'uwmemc_' . $app_type . '_application_closed_message',
		'Application Closed Message',
		'uwmemc_application_closed_message_callback',
		'uwmemc',
		$section_id,
		array(
			'app_type'  => $app_type,
			'label_for' => $field_labels['closed_message'],
		)
	);

	// Register the applicaiton link setting.
	add_settings_field(
		'uwmemc_' . $app_type . '_application_link',
		'Application Link',
		'uwmemc_application_link_callback',
		'uwmemc',
		$section_id,
		array(
			'app_type'  => $app_type,
			'label_for' => $field_labels['link'],
		)
	);

	// Register the application notification date setting.
	add_settings_field(
		'uwmemc_' . $app_type . '_application_notification_date',
		'Application Notification Date',
		'uwmemc_application_notification_date_callback',
		'uwmemc',
		$section_id,
		array(
			'app_type'  => $app_type,
			'label_for' => $field_labels['notification_date'],
		)
	);
}

/**
 * Callback function for displaying the application settings section.
 *
 * @param array $args An array of <p> element arguments.
 */
function uwmemc_applications_section_callback( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		These settings control the availability and display of the <?php echo esc_html( str_replace( '_', ' ', $args['title'] ) ); ?>.
	</p>
	<?php
}

/**
 * Callback function to output the application visibility input.
 *
 * @param array $args An array of <select> element arguments.
 */
function uwmemc_show_application_callback( $args ) {
	$settings         = get_option( 'uwmemc_settings' );
	$show_application = $settings[ $args['label_for'] ];
	$app_type         = str_replace( '_', ' ', strtoupper( $args['app_type'] ) );
	?>
	<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="uwmemc_settings[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="open" <?php selected( $show_application, 'open' ); ?>>open</option>
		<option value="closed" <?php selected( $show_application, 'closed' ); ?>>closed</option>
	</select>
	<p class="description">
		Use this to control the availability of the <?php echo esc_html( $app_type ); ?> application.<br>
		> When set to <strong>open</strong> the application will be available to users.<br>
		> When set to <strong>closed</strong> the application will NOT be available to users.
	</p>
	<?php
}

/**
 * Callback function to output the application closed date input.
 *
 * @param array $args An array of <input> element arguments.
 */
function uwmemc_application_closed_date_callback( $args ) {
	$settings = get_option( 'uwmemc_settings' );
	$date     = $settings[ $args['label_for'] ];
	$app_type = str_replace( '_', ' ', strtoupper( $args['app_type'] ) );
	?>
	<input name="uwmemc_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
		id="<?php echo esc_html( $args['label_for'] ); ?>" value="<?php echo esc_html( $date ); ?>"
		type="text">
	<p class="description">
		This is the date when the <?php echo esc_html( $app_type ); ?> application closes that is displayed to users.<br/>
		<em>(Note: This does not cause the application to be closed automatically, that will need to be done manually.)</em>
	</p>
	<?php
}

/**
 * Callback function to output the application closed message input.
 *
 * @param array $args An array of <textarea> element arguments.
 */
function uwmemc_application_closed_message_callback( $args ) {
	$settings = get_option( 'uwmemc_settings' );
	$message  = $settings[ $args['label_for'] ];
	$app_type = str_replace( '_', ' ', strtoupper( $args['app_type'] ) );
	?>
	<textarea cols="70" rows="2"
		name="uwmemc_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"><?php echo esc_textarea( $message ); ?></textarea>
	<p class="description">
		This is the message displayed to users when the <?php echo esc_html( $app_type ); ?> application is closed.
	</p>
	<?php
}

/**
 * Callback function to output the application link input.
 *
 * @param array $args An array of <input> element arguments.
 */
function uwmemc_application_link_callback( $args ) {
	$settings = get_option( 'uwmemc_settings' );
	$url      = $settings[ $args['label_for'] ];
	$app_type = str_replace( '_', ' ', strtoupper( $args['app_type'] ) );
	?>
	<input
		name="uwmemc_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
		id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo esc_attr( $url ); ?>"
		size="60"
		type="url">
	<p class="description">
		This is the url to the external <?php echo esc_html( $app_type ); ?> application for users to submit.
	</p>
	<?php
}

/**
 * Callback function to output the application notification date input.
 *
 * @param array $args An array of <input> element arguments.
 */
function uwmemc_application_notification_date_callback( $args ) {
	$settings = get_option( 'uwmemc_settings' );
	$date     = $settings[ $args['label_for'] ];
	$app_type = str_replace( '_', ' ', strtoupper( $args['app_type'] ) );
	?>
	<input name="uwmemc_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
		id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo esc_html( $date ); ?>"
		type="text">
	<p class="description">
		This is the date when <?php echo esc_html( $app_type ); ?> applicants will be notified that is displayed to users.
	</p>
	<?php
}
