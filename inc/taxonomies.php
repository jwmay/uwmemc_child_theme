<?php
/**
 * Adds the Visibility term meta to the Position taxonomy.
 *
 * Adapted from: https://gist.github.com/ms-studio/fc21fd5720f5bbdfaddc
 *
 * @package uwmemc
 */

// Define the options for the Visibility selector.
$visibility_options = array(
	'public'  => 'true',
	'private' => 'false',
);

/**
 * Returns the sanitized value of the Visibility term meta.
 *
 * @param int $term_id The id of the term.
 */
function get_position_visibility( $term_id ) {
	$value = get_term_meta( $term_id, 'position_visibility', true );
	$value = sanitize_text_field( $value );
	return $value;
}

/**
 * Registers the Visibility term meta for the Position taxonomy.
 */
function register_position_visibility_term_meta() {
	global $visibility_options;

	register_term_meta(
		'rg_position',
		'position_visibility',
		array(
			'description' => 'Controls position visibility on the front-end of the website.',
			'default'     => $visibility_options['public'],
			'single'      => true,
			'type'        => 'boolean',
		)
	);
}
add_action( 'init', 'register_position_visibility_term_meta', 10, 2 );

/**
 * Adds a Visibility selector to the 'Add Position' taxonomy form.
 *
 * Visibility determines if the taxonomy is visibile on the front-end
 * of the site (public) or is just for internal use on the backend of
 * the site (private).
 *
 * @param type $taxonomy The taxonomy slug.
 */
function add_position_visibility_field( $taxonomy ) {
	global $visibility_options; ?>

	<div class="form-field term-visibility-wrap">
		<label for="visibility">Visibility</label>
		<select name="visibility" id="visibility" class="post-form">
			<?php foreach ( $visibility_options as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $key ); ?></option>
			<?php endforeach; ?>
		</select>
		<p id="visibility-description">
			Specifies if the position should be visibile on the website (public) or hidden (private).
		</p>
	</div>
	<?php
}
add_action( 'rg_position_add_form_fields', 'add_position_visibility_field', 10, 2 );

/**
 * Adds a Visibility selector to the 'Edit Position' taxonomy form.
 *
 * Visibility determines if the taxonomy is visibile on the front-end
 * of the site (public) or is just for internal use on the backend of
 * the site (private).
 *
 * @param WP_Term $term     Taxonomy term object.
 * @param string  $taxonomy Taxonomy slug.
 */
function edit_position_visibility_field( $term, $taxonomy ) {
	global $visibility_options;

	$visibility = get_position_visibility( $term->term_id );
	?>

	<tr class="form-field term-visibility-wrap">
		<th scope="row"><label for="visibility">Visibility</label></th>
		<td>
			<select class="postform" id="visibility" name="visibility">
			<?php foreach ( $visibility_options as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $visibility ); ?>><?php echo esc_html( $key ); ?></option>
			<?php endforeach; ?>
			</select>
			<p id="visibility-description">
				Specifies if the position should be visibile on the website (public) or hidden (private).
			</p>
		</td>
	</tr>
	<?php
}
add_action( 'rg_position_edit_form_fields', 'edit_position_visibility_field', 10, 2 );

/**
 * Saves the value of the Visibility term meta for the Position taxonomy.
 *
 * @param int $term_id The id of the term.
 */
function save_position_visibility( $term_id ) {

	$nonce = isset( $_POST['_wpnonce_add-tag'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce_add-tag'] ) ) : null; // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
	if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce ) ) {
		return;
	}

	$old_value = get_position_visibility( $term_id );
	$new_value = isset( $_POST['visibility'] ) ? sanitize_text_field( wp_unslash( $_POST['visibility'] ) ) : '';

	if ( $old_value && '' === $new_value ) {
		delete_term_meta( $term_id, 'position_visibility' );

	} elseif ( $old_value !== $new_value ) {
		update_term_meta( $term_id, 'position_visibility', $new_value );
	}
}
add_action( 'create_rg_position', 'save_position_visibility' );
add_action( 'edit_rg_position', 'save_position_visibility' );

/**
 * Specify the value of the column header for the Visibility term meta.
 *
 * @param array $columns An array of sortable columns.
 */
function position_visibility_column( $columns ) {
	$columns['position_visibility'] = 'Visibility';
	return $columns;
}
add_filter( 'manage_edit-rg_position_columns', 'position_visibility_column', 10, 3 );
add_filter( 'manage_edit-rg_position_sortable_columns', 'position_visibility_column', 10, 3 );

/**
 * Render the Visibility term meta column in the Position taxonomy table.
 *
 * @param string $out     Custom column output.
 * @param string $column  Name of the column.
 * @param int    $term_id Term id.
 */
function manage_position_visibility_column( $out, $column, $term_id ) {
	global $visibility_options;

	if ( 'position_visibility' === $column ) {
		$value = array_search( get_position_visibility( $term_id ), $visibility_options, true );
		if ( ! $value ) {
			$value = '';
		}
		$out = esc_attr( $value );
	}
	return $out;
}
add_filter( 'manage_rg_position_custom_column', 'manage_position_visibility_column', 10, 3 );
