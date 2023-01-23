<?php
/**
 * People shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for the people of
 * the given $position.
 *
 * @param string $position The name of a position taxonomy to search.
 * @param array  $tags     An array of strings containing the tag names to display.
 */
function uwmemc_people_loop( $position, $tags ) {
	$args = array(
		'post_type' => 'rg_person',
		'orderby'   => 'menu_order meta_value',
		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_key'  => '_rg_person_last_name',
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
		$post_id          = get_the_ID();
		$person_post_type = new Research_Group_Person_Post_Type();
		$person           = (object) $person_post_type->get_post_meta( $post_id );

		$departments = implode(
			array_map(
				function( $department ) {
					return sprintf( '<span class="badge badge-primary mr-1">%s</span>', $department );
				},
				uwmemc_custom_term_list( $post_id, 'rg_department' )
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
					return sprintf( '<span class="badge badge-%s mr-1">%s</span>', $color, $position );
				},
				uwmemc_custom_term_list( $post_id, 'rg_position', $tags )
			)
		);

		$output_html = '
            <div class="col-md-4 mb-4">
                <div class="card">
                    %s
                    <div class="card-body">
                        <h5>%s %s</h5>
                        <p class="card-text"><small><em>%s</em>%s%s</small></p>
                        <div class="text-center text-md-left">
                            <a href="%s" class="btn btn-outline-primary">Learn more</a>
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
	};

	return uwmemc_custom_query( $args, $output );
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
			'position' => '',
			'tags'     => '',
		),
		$attr,
		'uwmemc_people_list'
	);

	// Get the full list of positions to display, unless one is provided with the shortcode.
	$positions = empty( $atts['position'] )
		? array_map(
			function( $term ) {
				return $term->name;
			},
			get_terms(
				array(
					'taxonomy' => 'rg_position',
				)
			)
		) : array( $atts['position'] );

	// Get the list of allowed position tags to display for each person.
	$tags = array();
	if ( ! empty( $atts['tags'] ) ) {
		$tags = preg_split( '/, */', $atts['tags'] );
	}

	$html = array();
	foreach ( $positions as $position ) {
		// Conditional control of the position header.
		if ( empty( $atts['header'] ) || true === $atts['header'] ) {
			array_push( $html, sprintf( '<h3>%s</h3>', $position ) );
		}
		array_push( $html, sprintf( '<div class="row">%s</div>', uwmemc_people_loop( $position, $tags ) ) );
	}
	return implode( $html );
}
add_shortcode( 'uwmemc_people_list', 'uwmemc_people_list_shortcode' );
