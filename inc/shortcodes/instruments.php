<?php
/**
 * Instruments shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for the instruments of
 * the given $technique.
 *
 * @param string $technique The name of a technique taxonomy to search.
 */
function uwmemc_instruments_loop( $technique ) {
	$args = array(
		'post_type' => 'rg_instrument',
		'orderby'   => 'menu_order',
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		'tax_query' => array(
			array(
				'taxonomy' => 'rg_technique',
				'field'    => 'name',
				'terms'    => $technique,
			),
		),
	);

	$output = function() {
		$output_html = '
			<div class="col-sm-6">
				<div class="card">
					%s
					<div class="card-body">
						<h5>%s</h5>
						<a href="%s" class="btn btn-outline-primary">Learn more</a>
					</div>
				</div>
			</div>';

		return sprintf(
			$output_html,
			get_the_post_thumbnail( get_the_ID(), 'post-thumbnail', array( 'class' => 'card-img-top' ) ),
			get_the_title(),
			get_the_permalink()
		);
	};

	return uwmemc_query( $args, $output );
}

/**
 * Shortcode to display list of instruments.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_instruments_list_shortcode( $attr = array() ) {
	$atts = shortcode_atts( array( 'technique' => '' ), $attr, 'uwmemc_instruments_list' );

	// Get the full list of techniques to display, unless one is provided with the shortcode.
	$techniques = empty( $atts['technique'] )
		? array_map(
			function( $term ) {
				return $term->name;
			},
			get_terms(
				array(
					'taxonomy' => 'rg_technique',
				)
			)
		) : array( $atts['technique'] );

	$html = array();
	foreach ( $techniques as $technique ) {
		array_push( $html, sprintf( '<h3>%s</h3><div class="row">%s</div>', $technique, uwmemc_instruments_loop( $technique ) ) );
	}
	return implode( $html );
}
add_shortcode( 'uwmemc_instruments_list', 'uwmemc_instruments_list_shortcode' );
