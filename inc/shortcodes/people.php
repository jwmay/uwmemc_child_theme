<?php
/**
 * People shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for a person.
 *
 * @param array $tags An array of tags to display.
 */
function uwmemc_person_output( $tags ) {
	$post_id          = get_the_ID();
	$person_post_type = new Research_Group_Person_Post_Type();
	$person           = (object) $person_post_type->get_post_meta( $post_id );

	$departments = implode(
		array_map(
			function( $department ) {
				return sprintf( '<span class="badge badge-primary badge-wrap mr-1">%s</span>', $department );
			},
			uwmemc_term_list( $post_id, 'rg_department' )
		)
	);

	$positions = implode(
		array_map(
			function( $position ) {
				$position_color_map = array(
					'Faculty'       => 'dark',
					'IRG 1'         => 'warning',
					'IRG 1 Lead'    => 'secondary',
					'IRG 1 Co-Lead' => 'secondary',
					'IRG 2'         => 'success',
					'IRG 2 Lead'    => 'secondary',
					'IRG 2 Co-Lead' => 'secondary',
					'MEM-Seed'      => 'info',
				);
				$color              = array_key_exists( $position, $position_color_map ) ? $position_color_map[ $position ] : 'light';
				return sprintf( '<span class="badge badge-%s badge-wrap mr-1">%s</span>', $color, $position );
			},
			uwmemc_term_list( $post_id, 'rg_position', $tags )
		)
	);

	$output_html = '
		<div class="col-md-4 mb-4">
			<div class="card">
				%1$s
				<div class="card-body">
					<h5>%2$s %3$s</h5>
					<p class="card-text"><small><em>%4$s</em>%5$s%6$s</small></p>
					<div class="text-center text-md-left">
						<a href="%7$s" class="btn btn-outline-primary">Learn more</a>
					</div>
				</div>
			</div>
		</div>';

	return sprintf(
		$output_html,
		get_the_post_thumbnail( $post_id, 'post-thumbnail', array( 'class' => 'card-img-top' ) ),
		$person->person_first_name,
		$person->person_last_name,
		$person->person_titles ? $person->person_titles . '<br/>' : '',
		$departments,
		$positions,
		get_the_permalink()
	);
}

/**
 * Return an html-formatted string for the people of
 * the given $position.
 *
 * @param string $position The name of a position taxonomy to search.
 * @param array  $tags     An array of strings containing the tag names to display.
 */
function uwmemc_position_loop( $position, $tags ) {
	$args = array(
		'post_type' => 'rg_person',
		'orderby'   => 'menu_order meta_value',
		'meta_key'  => '_rg_person_last_name', // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		'tax_query' => array(
			array(
				'taxonomy' => 'rg_position',
				'field'    => 'name',
				'terms'    => $position,
			),
		),
	);

	$output = function() use ( $tags ) {
		return uwmemc_person_output( $tags );
	};

	return uwmemc_query( $args, $output );
}

/**
 * Return an html-formatted string for a list of people.
 *
 * @param string $people A comma-separated list of people.
 * @param array  $tags   An array of strings containing the tag names to display.
 */
function uwmemc_people_loop( $people, $tags ) {
	$people = preg_split( '/, */', $people );
	$html   = array();

	// We run multiple queries here to search by person name one at a time
	// because using an array or comma-separated string of names did not
	// work and WordPress has no way of querying by post title.
	foreach ( $people as $person ) {
		$args = array(
			'post_type' => 'rg_person',
			's'         => $person,
		);

		$output = function() use ( $tags ) {
			return uwmemc_person_output( $tags );
		};

		array_push( $html, uwmemc_query( $args, $output ) );
	}

	return implode( $html );
}

/**
 * Shortcode to display list of people.
 *
 * @param array $attr An array of shortcode attributues.
 */
function uwmemc_people_list_shortcode( $attr = array() ) {
	$atts = shortcode_atts(
		array(
			'header'   => '',
			'people'   => '',
			'position' => '',
			'tags'     => '',
		),
		$attr,
		'uwmemc_people_list'
	);

	// Get the list of allowed position tags to display for each person.
	$tags = array();
	if ( ! empty( $atts['tags'] ) ) {
		$tags = preg_split( '/, */', $atts['tags'] );
	}

	// Return the people specified, this will override any specified position.
	if ( ! empty( $atts['people'] ) ) {
		return sprintf( '<div class="row">%s</div>', uwmemc_people_loop( $atts['people'], $tags ) );
	}

	// Get the full list of positions to display, unless one is provided with the shortcode.
	$positions = empty( $atts['position'] )
		? array_map(
			function( $term ) {
				// Remove positions with private visibility as determined by term meta value.
				$visible = get_term_meta( $term->term_id, 'position_visibility', true );
				if ( 'true' === $visible ) {
					return $term->name;
				}
			},
			get_terms(
				array(
					'taxonomy' => 'rg_position',
				)
			)
		) : array( $atts['position'] );
	$positions = array_filter( $positions ); // Remove empty elements.

	$html = array();
	foreach ( $positions as $position ) {
		// Conditional control of the position header.
		if ( empty( $atts['header'] ) && empty( $atts['person'] ) || 'true' === $atts['header'] ) {
			array_push( $html, sprintf( '<h3>%s</h3>', $position ) );
		}
		array_push( $html, sprintf( '<div class="row">%s</div>', uwmemc_position_loop( $position, $tags ) ) );
	}
	return implode( $html );
}
add_shortcode( 'uwmemc_people_list', 'uwmemc_people_list_shortcode' );
