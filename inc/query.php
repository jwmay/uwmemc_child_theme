<?php
/**
 * Define custom query functions.
 *
 * @package uwmemc
 */

/**
 * Run the loop with the custom query args
 *
 * An $output function is required to format the html for
 * displaying the post. Functions normally called inside of
 * the loop can be used in the $output function, since it will
 * be executed inside of the loop. This function will return an
 * html-formatted string containing all posts matching the
 * provided $args.
 *
 * @param array    $args   An array of query arguments.
 * @param function $output A function for generating the html output of the query results.
 */
function uwmemc_query( $args, $output ) {
	$default_args = array(
		'order'          => 'ASC',
		'posts_per_page' => -1,
	);

	// Create the custom search query.
	$query = new WP_Query( array_merge( $default_args, $args ) );

	// Run the loop with the custom search query.
	$html = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			array_push( $html, $output() );
		}

		// Restore the main query loop.
		wp_reset_postdata();
	}

	return implode( $html );
}

/**
 * Return an array of term names for the given post and
 * taxonomy filtering by an optional array of allowed
 * taxonomy names.
 *
 * @param int    $post_id  The post id.
 * @param string $taxonomy The taxonomy name.
 * @param array  $allowed  An array of allowed taxonomy terms.
 */
function uwmemc_term_list( $post_id, $taxonomy, $allowed = array() ) {
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( ! $terms ) {
		return array();
	}

	$output = array_map(
		function ( $term ) {
			// Remove positions with private visibility as determined by term meta value.
			$visible = get_term_meta( $term->term_id, 'position_visibility', true );
			if ( empty( $visible ) || 'true' === $visible ) {
				return $term->name;
			}
		},
		$terms
	);

	// Filter for allowed terms, otherwise, return all terms.
	if ( ! empty( $allowed ) ) {
		$output = array_filter(
			$output,
			function( $term ) use ( $allowed ) {
				return in_array( $term, $allowed, true );
			}
		);
	}

	return $output;
}
