<?php
/**
 * Publication shortcode.
 *
 * @package uwmemc
 */

/**
 * Return an html-formatted string for the publications of the given
 * type or for all types.
 *
 * @param number $count The number of publications to display.
 * @param string $topic The name of a topic taxonomy to search.
 */
function uwmemc_publication_loop( $count, $topic ) {
	$args = array(
		'post_type'      => 'rg_publication',
		'posts_per_page' => $count,
		'order'          => 'DESC',
		'orderby'        => array( 'meta_value_num', 'date' ),
		'meta_key'       => '_rg_pub_year', // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	);

	if ( ! empty( $topic ) ) {
		// phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'rg_topic',
				'field'    => 'name',
				'terms'    => $topic,
			),
		);
	}

	$output = function() {
		$post_id               = get_the_ID();
		$publication_post_type = new Research_Group_Publication_Post_Type();
		$pub                   = (object) $publication_post_type->get_post_meta( $post_id );
		$type                  = $pub->pub_type;
		$topics                = wp_strip_all_tags( get_the_term_list( $post_id, 'rg_topic', '', ', ' ) );

		$html  = '<li class="deck-item pub-item">';
		$html .= '<div class="pub-header">';

		if ( ! empty( $pub->pub_title ) ) {
			$html .= '<h4 class="pub-title">';
			if ( ! empty( $pub->pub_url ) ) {
				$html .= '<a href="' . $pub->pub_url . '" target="_blank">' . $pub->pub_title . '</a>';
			} else {
				$html .= $pub->pub_title;
			}
			$html .= '</h4>';
		}

		if ( ! empty( $pub->pub_authors ) ) {
			$html .= '<span class="pub-authors">' . $pub->pub_authors . '</span>';
		}

		$html .= '</div>';
		$html .= '<div class="pub-footer">';

		if ( ! empty( $pub->pub_book ) && 'Book' === $type ) {
			$html .= '<span class="pub-book">' . $pub->pub_book . '</span>';
		}

		if ( ! empty( $pub->pub_journal ) && ( 'Journal' === $type || empty( $type ) ) ) {
			$html .= '<span class="pub-journal">' . $pub->pub_journal . '</span>';
		}

		if ( ! empty( $pub->pub_year ) ) {
			$html .= ', <span class="pub-year">' . $pub->pub_year . '</span>';
		}

		if ( ! empty( $pub->pub_publisher ) && 'Book' === $type ) {
			$html .= ', <span class="pub-publisher">' . $pub->pub_publisher . '</span>';
		}

		if ( ! empty( $pub->pub_location ) && 'Book' === $type ) {
			$html .= ', <span class="pub-location">' . $pub->pub_location . '</span>';
		}

		if ( ! empty( $pub->pub_issue ) && ( 'Journal' === $type || empty( $type ) ) ) {
			$html .= ', <span class="pub-issue">' . $pub->pub_issue . '</span>';
		}

		if ( ! empty( $pub->pub_pages ) && ( 'Journal' === $type || empty( $type ) ) ) {
			$html .= ', <span class="pub-pages">' . $pub->pub_pages . '</span>';
		}

		if ( ! empty( $pub->pub_status ) && 'Published' !== $pub->pub_status && ( 'Journal' === $type || empty( $type ) ) ) {
			$html .= ', <span class="pub-status">' . $pub->pub_status . '</span>';
		}

		if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) {
			$html .= '<span style="display: none;">' . $topics . '</span>';
		}

		if ( ! empty( $pub->person_tags ) ) {
			$html .= '<span style="display: none;">' . join( ', ', array_map( 'get_the_title', $pub->person_tags ) ) . '</span>';
		}

		$html .= '</div>';
		$html .= '</li>';

		return $html;
	};

	return uwmemc_query( $args, $output );
}

/**
 * Shortcode to display list of publications.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_publication_list_shortcode( $attr = array() ) {
	$atts = shortcode_atts(
		array(
			'count'  => -1,
			'search' => 'false',
			'topic'  => '',
		),
		$attr,
		'uwmemc_publication_list'
	);

	$html = '';

	if ( 'true' === $atts['search'] ) {
		$html .= '<div class="pub-search">';
		$html .= '<div class="input-addons">';
		$html .= '<input class="pub-search-input quicksearch" placeholder="Search by year, keyword, journal, author, etc." />';
		$html .= '<div class="input-addon-clear" title="clear search"><i class="far fa-times-circle"></i></div>';
		$html .= '<div class="deck-count-badge input-addon-badge">0</div>';
		$html .= '</div>';
		$html .= '<a href="https://scholar.google.com/citations?hl=en&user=h0OhmtoAAAAJ&view_op=list_works&gmla=AJsN-F70MYWLcktPNdQ_jlsYEgX_ZELLE0Y0HQIFZnPYR4hkuXZELnVFqV90v3dhtF2mr9XIl94vRrlhQZmeFvKWKYDAGjKcohCM83bluoeUO4_RHBsJPg8" target="_blank">View our publications on Google Scholar</a>';
		$html .= '</div>';
	}

	$html .= '<div class="deck-empty pub-list-empty">Sorry, no publications matched your search</div>';
	$html .= '<ol class="deck pub-list" reversed>';
	$html .= uwmemc_publication_loop( $atts['count'], $atts['topic'] );
	$html .= '</ol>';

	return $html;
}
add_shortcode( 'uwmemc_publication_list', 'uwmemc_publication_list_shortcode' );
