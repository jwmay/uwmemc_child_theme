<?php
/**
 * Generates list of pages to display in the sidebar.
 *
 * @param boolean $mobile Flag for mobile display type.
 */
function uw_list_pages( $mobile = false ) {
	global $UW;
	global $post;

	if ( ! isset( $post ) ) {
		return;
	}

	$parent = get_post( $post->post_parent );

	if ( ! $mobile && ! get_children(
		array(
			'post_parent' => $post->ID,
			'post_status' => 'publish',
		)
	) && $parent->ID === $post->ID ) {
		return;
	}

	$toggle = $mobile ? '<button class="uw-mobile-menu-toggle">Menu</button>' : '';
	$class  = $mobile ? 'uw-mobile-menu' : 'uw-sidebar-menu';

	$siblings = get_pages(
		array(
			'parent'    => $parent->post_parent,
			'post_type' => 'page', // @todo: changing to 'rg_person' does not work
			'exclude'   => $parent->ID,
		)
	);

	$ids = ! is_front_page() ? array_map(
		function( $sibling ) {
			return $sibling->ID;
		},
		$siblings
	) : array();

	$pages = wp_list_pages(
		array(
			'title_li'     => '<a href="' . get_bloginfo( 'url' ) . '" title="Home" class="homelink">Home</a>',
			'child_of'     => $parent->post_parent,
			'exclude_tree' => $ids,
			'depth'        => 3,
			'echo'         => 0,
			'walker'       => $UW->SidebarMenuWalker,
		)
	);

	$bool = strpos( $pages, 'child-page-existance-tester' );

	return $bool && ! is_search() ? sprintf( '%s<ul class="%s first-level">%s</ul>', $toggle, $class, $pages ) : '';

}
