<?php
/**
 * News shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for the news.
 *
 * @param number $count The number of publications to display.
 * @param string $order The sort order as either ASC or DESC.
 */
function uwmemc_news_loop( $count, $order ) {
	$args = array(
		'posts_per_page' => $count,
		'order'          => $order,
	);

	$output = function() {
		$html = '<li class="media">';

		if ( has_post_thumbnail() ) {
			$html .= '<img src="' . get_the_post_thumbnail_url() . '">';
		} else {
			$html .= '<img src="' . get_theme_file_uri( 'assets/img/placeholder.png' ) . '">';
		}

		$html .= '<div class="media-body">';
		$html .= '<h4 class="mt-0 mb-2">';
		$html .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
		$html .= '</h4>';
		$html .= '</div>';
		$html .= '</li>';

		return $html;
	};

	return uwmemc_query( $args, $output );
}

/**
 * Shortcode to display list of news posts.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_news_list_shortcode( $attr = array() ) {
	$atts = shortcode_atts(
		array(
			'count' => -1,
			'order' => 'DESC',
		),
		$attr,
		'uwmemc_news_list'
	);

	$html  = '<ul class="news-shortcode">';
	$html .= uwmemc_news_loop( $atts['count'], $atts['order'] );
	$html .= '</ul>';

	return $html;
}
add_shortcode( 'uwmemc_news_list', 'uwmemc_news_list_shortcode' );
