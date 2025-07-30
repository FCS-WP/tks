<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package TINT
 * @since TINT 1.0
 */

							do_action( 'tint_action_page_content_end_text' );
							
							// Widgets area below the content
							tint_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'tint_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'tint_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'tint_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'tint_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$tint_body_style = tint_get_theme_option( 'body_style' );
					$tint_widgets_name = tint_get_theme_option( 'widgets_below_page', 'hide' );
					$tint_show_widgets = ! tint_is_off( $tint_widgets_name ) && is_active_sidebar( $tint_widgets_name );
					$tint_show_related = tint_is_single() && tint_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $tint_show_widgets || $tint_show_related ) {
						if ( 'fullscreen' != $tint_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $tint_show_related ) {
							do_action( 'tint_action_related_posts' );
						}

						// Widgets area below page content
						if ( $tint_show_widgets ) {
							tint_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $tint_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'tint_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'tint_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! tint_is_singular( 'post' ) && ! tint_is_singular( 'attachment' ) ) || ! in_array ( tint_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="tint_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'tint_action_before_footer' );

				// Footer
				$tint_footer_type = tint_get_theme_option( 'footer_type' );
				if ( 'custom' == $tint_footer_type && ! tint_is_layouts_available() ) {
					$tint_footer_type = 'default';
				}
				get_template_part( apply_filters( 'tint_filter_get_template_part', "templates/footer-" . sanitize_file_name( $tint_footer_type ) ) );

				do_action( 'tint_action_after_footer' );

			}
			?>

			<?php do_action( 'tint_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'tint_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'tint_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>