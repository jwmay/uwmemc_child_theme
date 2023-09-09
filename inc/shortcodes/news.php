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
		$html  = '<div class="col-xs-12 col-md-6 col-lg-4">';
		$html .= '<div class="card">';

		if ( has_post_thumbnail() ) {
			$html .= '<img class="card-img-top" src="' . get_the_post_thumbnail_url() . '">';
		} else {
			$html .= '<img class="card-img-top" src="' . get_theme_file_uri( 'assets/img/placeholder.png' ) . '">';
		}

		$html .= '<div class="card-body">';
		$html .= '<h5><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h5>';
		$html .= '<h6><em>' . get_the_date() . '</em></h6>';
		$html .= '<p>' . get_the_excerpt() . '</p>';
		$html .= '<p class="read-more"><a href="' . get_the_permalink() . '">Read more</a></p>';
		$html .= '</div></div></div>';

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

	$html  = '<div class="news-shortcode container">';
	$html .= '<div class="row">';
	$html .= uwmemc_news_loop( $atts['count'], $atts['order'] );
	$html .= '</div></div>';

	return $html;
}
add_shortcode( 'uwmemc_news_list', 'uwmemc_news_list_shortcode' );
