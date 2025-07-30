<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package TINT
 * @since TINT 1.0
 */

// Page (category, tag, archive, author) title

if ( tint_need_page_title() ) {
	tint_sc_layouts_showed( 'title', true );
	tint_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								tint_show_post_meta(
									apply_filters(
										'tint_filter_post_meta_args', array(
											'components' => join( ',', tint_array_get_keys_by_value( tint_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', tint_array_get_keys_by_value( tint_get_theme_option( 'counters' ) ) ),
											'seo'        => tint_is_on( tint_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$tint_blog_title           = tint_get_blog_title();
							$tint_blog_title_text      = '';
							$tint_blog_title_class     = '';
							$tint_blog_title_link      = '';
							$tint_blog_title_link_text = '';
							if ( is_array( $tint_blog_title ) ) {
								$tint_blog_title_text      = $tint_blog_title['text'];
								$tint_blog_title_class     = ! empty( $tint_blog_title['class'] ) ? ' ' . $tint_blog_title['class'] : '';
								$tint_blog_title_link      = ! empty( $tint_blog_title['link'] ) ? $tint_blog_title['link'] : '';
								$tint_blog_title_link_text = ! empty( $tint_blog_title['link_text'] ) ? $tint_blog_title['link_text'] : '';
							} else {
								$tint_blog_title_text = $tint_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $tint_blog_title_class ); ?>">
								<?php
								$tint_top_icon = tint_get_term_image_small();
								if ( ! empty( $tint_top_icon ) ) {
									$tint_attr = tint_getimagesize( $tint_top_icon );
									?>
									<img src="<?php echo esc_url( $tint_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'tint' ); ?>"
										<?php
										if ( ! empty( $tint_attr[3] ) ) {
											tint_show_layout( $tint_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $tint_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $tint_blog_title_link ) && ! empty( $tint_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $tint_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $tint_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'tint_action_breadcrumbs' );
						$tint_breadcrumbs = ob_get_contents();
						ob_end_clean();
						tint_show_layout( $tint_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
