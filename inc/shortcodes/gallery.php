<?php
/**
 * Gallery shortcode.
 *
 * This custom gallery shortcode allows for items in the gallery
 * to be linked to a page or not and allows for control over the
 * visibility of gallery items via the theme settings.
 *
 * The shortcode should be used in the content of a page or post
 * as follows:
 *
 *  [uwmemc_gallery id="uwmemc_gallery"]
 *    [uwmemc_gallery_item id="123" link="reu" control="reu"]
 *    [uwmemc_gallery_item id="124" link="research"]
 *    [uwmemc_gallery_item id="127"]
 *  [/uwmemc_gallery]
 *
 * @package uwmemc
 */

/**
 * Shortcode for displaying an image slider gallery.
 *
 * @param array  $attr    An array of shortcode attributes.
 * @param string $content The shortcode content to display.
 */
function uwmemc_gallery_shortcode( $attr = array(), $content ) {
	$atts = shortcode_atts(
		array(
			'id'         => 'uwmemc_gallery',
			'responsive' => false,
		),
		$attr,
		'uwmemc_gallery'
	);

	$items = preg_split( '/\n/', trim( $content ) );

	// Get application visibility settings for item filtering when constructing slider.
	$settings        = get_option( 'uwmemc_settings' );
	$apps            = array( 'ayra_reu', 'ret', 'reu' );
	$apps_visibility = array_combine(
		$apps,
		array_map(
			function( $app ) use ( $settings ) {
				$setting_key = sprintf( '%s_application_visible', $app );
				if ( array_key_exists( $setting_key, $settings ) ) {
					return $settings[ $setting_key ];
				}
				return 'closed';
			},
			$apps
		)
	);

	// Filter out controlled items based on application visibility settings.
	$filtered_items = array_values(
		array_filter(
			$items,
			function( $item ) use ( $apps_visibility ) {
				$matches = array();
				$match   = preg_match( '/control="(.+)"/', $item, $matches );
				if ( $match ) {
					$control = preg_replace( '/-/', '_', $matches[1] );
					if ( ! array_key_exists( $control, $apps_visibility )
						|| array_key_exists( $control, $apps_visibility ) && 'open' === $apps_visibility[ $control ] ) {
						return $item;
					}
				} else {
					return $item;
				}
			}
		)
	);

	$indicators_html = '';
	$items_html      = '';
	foreach ( $filtered_items as $index => $item ) {
		// Add a slider indicator.
		$indicators_html .= 0 === $index
			? sprintf( '<li data-target="#%s" data-slide-to="%s" class="active"></li>', $atts['id'], $index )
			: sprintf( '<li data-target="#%s" data-slide-to="%s"></li>', $atts['id'], $index );

		// First item in the gallery must have '.active' class in order to load.
		if ( 0 === $index ) {
			$item = substr_replace( $item, ' active="true"]', -2 );
		}
		$items_html .= do_shortcode( $item );
	};

	$html = '
        <div id="%s" class="carousel slide %s" data-ride="carousel">
            <ol class="carousel-indicators" style="margin-left: 15%%; padding-left: 0;">%s</ol>
            <div class="carousel-inner">%s</div>
            <a class="carousel-control-prev" type="button" data-target="#%s" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a><a class="carousel-control-next" type="button" data-target="#%s" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    ';

	return sprintf( $html, $atts['id'], $atts['responsive'] ? 'responsive' : '', $indicators_html, $items_html, $atts['id'], $atts['id'] );
}
add_shortcode( 'uwmemc_gallery', 'uwmemc_gallery_shortcode' );

/**
 * Shortcode for displaying an image slider gallery item.
 *
 * @param array $attr An array of shortcode attributes.
 */
function uwmemc_gallery_item_shortcode( $attr = array() ) {
	$atts = shortcode_atts(
		array(
			'id'      => 0,
			'link'    => '',
			'control' => '',
			'active'  => false,
		),
		$attr,
		'uwmemc_gallery_item'
	);

	$active_class = $atts['active'] ? 'active' : '';
	$html         = sprintf( '<div class="carousel-item %s">', $active_class );

	$img_src_url = wp_get_attachment_url( $atts['id'] );
	$img_html    = sprintf( '<img src="%s" class="d-block w-100" alt="...">', $img_src_url );
	if ( $atts['link'] ) {
		$img_link_url = home_url( $atts['link'] );
		$html        .= sprintf( '<a href="%s">%s</a>', $img_link_url, $img_html );
	} else {
		$html .= $img_html;
	}

	$html .= '</div>';

	return $html;
}
add_shortcode( 'uwmemc_gallery_item', 'uwmemc_gallery_item_shortcode' );
