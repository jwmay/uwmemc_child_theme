<?php
/**
 * These first two functions replace pluggable functions in the
 * parent theme with the remaining functions providing a supporting role.
 *
 * @package uwmemc_child_theme
 */

define(
	'POST_TYPE_PARENTS',
	array(
		'rg_instrument'     => 'Facilities',
		'rg_research_topic' => 'Research',
		'rg_person'         => 'People',
		'rg_publication'    => 'Research',
		'post'              => 'News',
	)
);

/**
 * Returns the parent page title for display in the header-image.
 *
 * Note: This is a pluggable function in the parent theme
 * that has been mostly rewritten for the child theme.
 */
function uw_site_title() {
	$classes = 'uw-site-title';

	if ( get_option( 'overly_long_title' ) ) {
		$classes .= ' long-title';
	}

	if ( get_option( 'page_for_posts', true ) && ( is_home() || is_single() || is_archive() || is_category() || is_tag() ) ) {
		echo '<div class="' . esc_attr( $classes ) . '">' . esc_attr( POST_TYPE_PARENTS[ get_post_type() ] ) . '</div>';
	} elseif ( is_page() ) {
		echo '<div class="' . esc_attr( $classes ) . '">' . esc_attr( get_post_parent_title() ) . '</div>';
	} else {
		echo '<div class="' . esc_attr( $classes ) . '">' . get_bloginfo() . '</div>';
	}
}

/**
 * Display site breadcrumbs.
 *
 * Note: This is a pluggable function in the parent theme
 * that has been slightly altered for the child theme.
 */
function uw_breadcrumbs() {

	if ( get_option( 'breadcrumb-hide' ) ) {
		return;
	}

	global $post;

	if ( isset( $post ) && get_post_meta( $post->ID, 'breadcrumbs', true ) ) {
		return;
	}

	$ancestors = array_reverse( get_post_ancestors( $post ) );
	$html      = '<li><a href="' . home_url( '/' ) . '" title="' . get_bloginfo( 'title' ) . '">' . get_bloginfo( 'title' ) . '</a>';

	if ( is_404() ) {
		$html .= '<li class="current"><span>Woof!</span>';
	} elseif ( is_search() ) {
		$html .= '<li class="current"><span>Search results for ' . get_search_query() . '</span>';
	} elseif ( is_author() ) {
		$author = get_queried_object();
		$html  .= '<li class="current"><span> Author: ' . $author->display_name . '</span>';
	} elseif ( get_queried_object_id() === (int) get_option( 'page_for_posts' ) ) {
		$html .= '<li class="current"><span> ' . get_the_title( get_queried_object_id() ) . ' </span>';
	}

	// If the current view is a post type other than page or attachment then the breadcrumbs will be taxonomies.
	if ( is_category() || is_single() || is_post_type_archive() || is_tag() ) {

		if ( is_post_type_archive() ) {
			$posttype = get_post_type_object( get_post_type() );
			// $html .=  '<li class="current"><a href="'  . get_post_type_archive_link( $posttype->query_var ) .'" title="'. $posttype->labels->menu_name .'">'. $posttype->labels->menu_name  . '</a>';
			$html .= '<li class="current"><span>' . $posttype->labels->menu_name . '</span>';
		}

		if ( is_category() ) {
			if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
				$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
			}

			$category = get_category( get_query_var( 'cat' ) );
			// $html .=  '<li class="current"><a href="'  . get_category_link( $category->term_id ) .'" title="'. get_cat_name( $category->term_id ).'">'. get_cat_name($category->term_id ) . '</a>';
			$html .= '<li class="current"><span>' . get_cat_name( $category->term_id ) . '</span>';
		}

		if ( is_tag() ) {
			if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
				$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
			}

			$tag   = get_tag( get_queried_object_id() );
			$html .= '<li class="current"><span>' . $tag->slug . '</span>';
		}

		if ( is_single() ) {
			if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
				$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
			} elseif ( has_category() ) {
				$thecat   = get_the_category( $post->ID );
				$category = array_shift( $thecat );
				$html    .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . get_cat_name( $category->term_id ) . ' ">' . get_cat_name( $category->term_id ) . '</a>';
			}
			// check if is Custom Post Type.
			if ( ! is_singular( array( 'page', 'attachment', 'post' ) ) ) {
				// $posttype = get_post_type_object( get_post_type() );  // Not used.
				$parent = POST_TYPE_PARENTS[ get_post_type() ]; // Added for child theme.
				$html  .= '<li><a href="' . home_url( strtolower( $parent ) ) . '" title="' . $parent . '">' . $parent . '</a>'; // Updated for child theme.
			}

			$html .= '<li class="current"><span>' . get_the_title( $post->ID ) . '</span>';
		}
	} elseif ( is_page() ) {
		// If the current view is a page then the breadcrumbs will be parent pages.

		if ( ! is_home() || ! is_front_page() ) {
			$ancestors[] = $post->ID;
		}

		if ( ! is_front_page() ) {
			foreach ( array_filter( $ancestors ) as $index => $ancestor ) {

				$class      = $index + 1 === count( $ancestors ) ? ' class="current" ' : '';
				$page       = get_post( $ancestor );
				$url        = get_permalink( $page->ID );
				$title_attr = esc_attr( $page->post_title );

				if ( ! empty( $class ) ) {
					$html .= "<li $class><span>{$page->post_title}</span></li>";
				} else {
					$html .= "<li><a href=\"$url\" title=\"{$title_attr}\">{$page->post_title}</a></li>";
				}
			}
		}
	}

	return "<nav class='uw-breadcrumbs' aria-label='breadcrumbs'><ul>$html</ul></nav>";
}

/**
 * Return the title of the top-level parent page.
 */
function get_post_parent_title() {
	return get_the_title( get_post_parent_id() );
}

/**
 * Return true if the top-level parent page has a post featured image.
 */
function has_post_parent_thumbnail() {
	return has_post_thumbnail( get_post_parent_id() );
}

/**
 * Return the url of the top-level parent page featured image.
 */
function get_post_parent_thumbnail_url() {
	return get_the_post_thumbnail_url( get_post_parent_id() );
}

/**
 * Return the top-level post parent $post_id.
 */
function get_post_parent_id() {
	$ancestors = get_post_ancestors( get_the_ID() );
	return array_pop( $ancestors );
}

/**
 * Returns the id of the page with the given $title.
 *
 * @param string $title The page title.
 */
function get_page_id_by_title( $title ) {
	$pages = get_posts(
		array(
			'post_type' => 'page',
			'title'     => $title,
		)
	);
	$page  = array_pop( $pages );
	return $page->ID;
}
