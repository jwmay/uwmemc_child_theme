<?php
/**
 * Research Topics shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for the research topics.
 *
 * @param boolean $detail Flag to show excerpt or not.
 */
function uwmemc_research_topics_loop( $detail ) {
	$args = array(
		'post_type' => 'rg_research_topic',
		'orderby'   => 'menu_order',
	);

	$output = function() use ( $detail ) {
		$output_html = '
			<div class="card" style="width: 100%%;">
				<div class="row no-gutters">
					<div class="col-md-4">
						%s
					</div>
					<div class="col-md-8">
						<div class="card-body">
							<h5 class="card-title">%s</h5>
							<p class="card-text">%s</p>
							<a href="%s" class="btn btn-outline-primary">Learn more</a>
						</div>
					</div>
				</div>
			</div>';

		return sprintf(
			$output_html,
			get_the_post_thumbnail(
				get_the_ID(),
				'post-thumbnail',
				array(
					'class' => 'card-img',
					'style' => 'border-radius: 0; height: 100%;',
				)
			),
			get_the_title(),
			$detail ? get_the_excerpt() : '',
			get_the_permalink()
		);
	};

	return uwmemc_query( $args, $output );
}

/**
 * Shortcode to display list of research topics.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_research_topics_list_shortcode( $attr = array() ) {
	$atts = shortcode_atts( array( 'detail' => false ), $attr, 'uwmemc_research_topic_list' );
	$html = sprintf( '<div class="row no-gutters">%s</div>', uwmemc_research_topics_loop( $atts['detail'] ) );
	return $html;
}
add_shortcode( 'uwmemc_research_topics_list', 'uwmemc_research_topics_list_shortcode' );
