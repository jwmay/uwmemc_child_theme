<?php
/**
 * Returns the header image used on all pages except the front page.
 *
 * @package uwmemc_child_theme
 */

if ( is_page() ) {
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url();
	} elseif ( has_post_parent_thumbnail() ) {
		$image_url = get_post_parent_thumbnail_url();
	} else {
		$image_url = get_header_image();
	}
} elseif ( is_home() || is_single() || is_archive() || is_category() || is_tag() ) {
	if ( array_key_exists( get_post_type(), POST_TYPE_PARENTS ) ) {
		$parent_page_id = get_page_id_by_title( POST_TYPE_PARENTS[ get_post_type() ] );
		$image_url      = get_the_post_thumbnail_url( $parent_page_id );
	} else {
		$image_url = get_header_image();
	}
} else {
	$image_url = get_header_image();
}
$header_image = sprintf( 'style="background-image:url(%s)"', $image_url );
?>

<div class="uw-hero-image" <?php echo $header_image; ?>>
	<div class="container-fluid">
		<?php uw_site_title(); ?>

		<div class="udub-slant-divider gold"><span></span></div>
	</div>
</div>
