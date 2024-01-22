<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uw_wp_theme
 */

$person_id        = get_the_id();
$person_post_type = new Research_Group_Person_Post_Type();
$person           = (object) $person_post_type->get_post_meta( $person_id );

$has_email    = ! empty( $person->person_email );
$has_research = ! empty( $person->person_research );
$has_titles   = ! empty( $person->person_titles );
$has_website  = ! empty( $person->person_website );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			the_title( '<h1 class="entry-title">', '</h1>' );
		?>

		<div class="entry-content">
			<div class="person">
				<div class="person-left-panel">
					<?php
					// If the post has a featured image, display the featured image.
					if ( has_post_thumbnail() ) {
						echo '<div class="person-image">';
						the_post_thumbnail();
						echo '</div>';
					}
					?>
					<div class="person-details">
						<?php
						// Display the member's title, if specified.
						if ( $has_titles ) :
							echo '<p class="person-title">' . $person->person_titles . '</p>';
						endif;
						?>

						<p class="person-tags">
							<?php
							// Display department(s).
							echo implode(
								array_map(
									function( $department ) {
										return sprintf( '<span class="badge badge-primary badge-wrap">%s</span>', $department );
									},
									uwmemc_term_list( $person_id, 'rg_department' )
								)
							);


							// Display position(s).
							echo implode(
								array_map(
									function( $position ) {
										$position_color_map = array(
											'Faculty'    => 'dark',
											'IRG 1'      => 'warning',
											'IRG 1 Lead' => 'secondary',
											'IRG 1 Co-Lead' => 'secondary',
											'IRG 2'      => 'success',
											'IRG 2 Lead' => 'secondary',
											'IRG 2 Co-Lead' => 'secondary',
											'MEM-Seed'   => 'info',
										);
										$color              = array_key_exists( $position, $position_color_map ) ? $position_color_map[ $position ] : 'dark';
										return sprintf( '<span class="badge badge-%s badge-wrap">%s</span>', $color, $position );
									},
									uwmemc_term_list( $person_id, 'rg_position' )
								)
							);
							?>
						</p>

						<?php
							// Dispaly email and/or website links, if available.
						if ( $has_email || $has_website ) :
							?>
							<div class="person-links">
							<?php
							// Display email link, if email address is available.
							if ( $has_email ) :
								echo '<div><i class="fas fa-envelope fa-lg fa-fw"></i> ';
								echo '<a href="mailto:' . $person->person_email . '" data-toggle="tooltip" title="' . $person->person_email . '">Email</a></div>';
								endif;

							// Display website link, if website url is available.
							if ( $has_website ) :
								echo '<div><i class="fas fa-link fa-lg fa-fw"></i> ';
								echo '<a href="' . $person->person_website . '" target="_blank">Website</a></div>';
								endif;
							?>
							</div>
							<?php
						endif;
						?>
					</div><!-- .person-details -->
				</div><!-- .person-left-panel -->
				<div class="person-right-panel">
					<?php
					// Display research information, if available.
					if ( $has_research ) :
						echo '<div class="member-research">';
						if ( $has_research ) :
							echo wpautop( $person->person_research );
					endif;
						echo '</div>';
					endif;
					?>
				</div><!-- .person-right-panel -->
			</div><!-- .person -->

			<?php if ( get_edit_post_link() ) : ?>
				<footer class="entry-footer">
					<?php
						uw_wp_theme_edit_post_link();
					?>
				</footer><!-- .entry-footer -->
			<?php endif; ?>
		</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
if ( is_singular() ) :
	$next_post = get_next_post();
	$prev_post = get_previous_post();

	if ( $prev_post && get_the_post_thumbnail( $prev_post ) ) {
		$prev_post_thumb = '<div class="prev-post-thumbnail">' . get_the_post_thumbnail( $prev_post, array( 100, 100 ) ) . '</div>';
	} else {
		$prev_post_thumb = '<div class="prev-post-thumbnail default-thumb"></div>';
	}

	if ( $next_post && get_the_post_thumbnail( $next_post ) ) {
		$next_post_thumb = '<div class="next-post-thumbnail">' . get_the_post_thumbnail( $next_post, array( 100, 100 ) ) . '</div>';
	} else {
		$next_post_thumb = '<div class="next-post-thumbnail default-thumb"></div>';
	}

	the_post_navigation(
		array(
			'prev_text' => $prev_post_thumb . '<div class="prev-post-text-link"><div class="post-navigation-sub"><span class="prev-arrow"></span><span>' . esc_html__( 'Previous', 'uw_wp_theme' ) . '</span></div><span class="post-navigation-title">%title</span></div>',
			'next_text' => '<div class="next-post-text-link"><div class="post-navigation-sub"><span>' . esc_html__( 'Next', 'uw_wp_theme' ) . '</span><span class="next-arrow"></span></div><span class="post-navigation-title">%title</span></div>' . $next_post_thumb,
		)
	);
endif;
