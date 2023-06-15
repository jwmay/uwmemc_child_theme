<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uw_wp_theme
 */

?>
		<div class="pre-footer container-fluid">
			<address>
				<img src="<?php echo get_theme_file_uri( 'assets/img/uwmemc-logo-white.png' ); ?>">
				<h5>Molecular Engineering and Materials Center</h5>
				Box 351700<br/>
				Department of Chemistry<br/>
				University of Washington<br/>
				36 Bagley Hall<br/>
				Seattle, WA 98195-1700<br/>
				<a href="mailto:uwmemc@uw.edu">uwmemc@uw.edu</a>
			</address>
			<div class="sponsors">
				<img src="<?php echo get_theme_file_uri( 'assets/img/nsf-mrsec-logo.png' ); ?>">
				<div class="disclaimer">
					The University of Washington MRSEC is supported by the National Science Foundation
					under NSF Award Number DMR-1719797. Any opinions, findings, conclusions or
					recommendations expressed in this material are those of the PI(s) and do not
					necessarily reflect those of the National Science Foundation.
				</div>
			</div>
		</div>

		<footer id="colophon" class="site-footer">
			<div class="h4" id="social_preface">Connect with us:</div>
			<nav aria-labelledby="social_preface">
				<ul class="footer-social">
					<li><a class="twitter" href="https://twitter.com/uwmemc/">Twitter</a></li>
					<li><a class="youtube" href="https://www.youtube.com/channel/UCR06IU0FJgxmXS0PzJw7OiQ">YouTube</a></li>
					<!-- <li><a class="facebook" href="https://www.facebook.com/UofWA">Facebook</a></li> -->
					<!-- <li><a class="instagram" href="https://instagram.com/uofwa">Instagram</a></li> -->
					<!-- <li><a class="linkedin" href="https://www.linkedin.com/company/university-of-washington">LinkedIn</a></li> -->
					<!-- <li><a class="pinterest" href="https://www.pinterest.com/uofwa/">Pinterest</a></li> -->
				</ul>
			</nav>

			<nav aria-label="footer navigation">
				<!--<ul class="footer-links"> -->
				<?php uw_wp_theme_footer_menu(); ?>

				<!-- </ul> -->
			</nav>

			<div class="site-info">
				<p>&copy; <?php echo date( 'Y' ); ?> UW MEM-C  |  Seattle, WA</p>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page-inner -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
