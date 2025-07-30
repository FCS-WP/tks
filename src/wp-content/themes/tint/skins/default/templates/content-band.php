<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package TINT
 * @since TINT 1.71.0
 */

$tint_template_args = get_query_var( 'tint_template_args' );
if ( ! is_array( $tint_template_args ) ) {
	$tint_template_args = array(
								'type'    => 'band',
								'columns' => 1
								);
}

$tint_columns       = 1;

$tint_expanded      = ! tint_sidebar_present() && tint_get_theme_option( 'expand_content' ) == 'expand';

$tint_post_format   = get_post_format();
$tint_post_format   = empty( $tint_post_format ) ? 'standard' : str_replace( 'post-format-', '', $tint_post_format );

if ( is_array( $tint_template_args ) ) {
	$tint_columns    = empty( $tint_template_args['columns'] ) ? 1 : max( 1, $tint_template_args['columns'] );
	$tint_blog_style = array( $tint_template_args['type'], $tint_columns );
	if ( ! empty( $tint_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $tint_columns > 1 ) {
	    $tint_columns_class = tint_get_column_class( 1, $tint_columns, ! empty( $tint_template_args['columns_tablet']) ? $tint_template_args['columns_tablet'] : '', ! empty($tint_template_args['columns_mobile']) ? $tint_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $tint_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $tint_post_format ) );
	tint_add_blog_animation( $tint_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$tint_hover      = ! empty( $tint_template_args['hover'] ) && ! tint_is_inherit( $tint_template_args['hover'] )
							? $tint_template_args['hover']
							: tint_get_theme_option( 'image_hover' );
	$tint_components = ! empty( $tint_template_args['meta_parts'] )
							? ( is_array( $tint_template_args['meta_parts'] )
								? $tint_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $tint_template_args['meta_parts'] ) )
								)
							: tint_array_get_keys_by_value( tint_get_theme_option( 'meta_parts' ) );
	tint_show_post_featured( apply_filters( 'tint_filter_args_featured',
		array(
			'no_links'   => ! empty( $tint_template_args['no_links'] ),
			'hover'      => $tint_hover,
			'meta_parts' => $tint_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $tint_template_args['thumb_size'] )
								? $tint_template_args['thumb_size']
								: tint_get_thumb_size( 
								in_array( $tint_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( tint_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $tint_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$tint_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$tint_show_title = get_the_title() != '';
		$tint_show_meta  = count( $tint_components ) > 0 && ! in_array( $tint_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $tint_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'tint_filter_show_blog_categories', $tint_show_meta && in_array( 'categories', $tint_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'tint_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						tint_show_post_meta( apply_filters(
															'tint_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $tint_hover, 1
															)
											);
						?>
					</div>
					<?php
					$tint_components = tint_array_delete_by_value( $tint_components, 'categories' );
					do_action( 'tint_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'tint_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'tint_action_before_post_title' );
					if ( empty( $tint_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'tint_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $tint_template_args['excerpt_length'] ) && ! in_array( $tint_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$tint_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'tint_filter_show_blog_excerpt', empty( $tint_template_args['hide_excerpt'] ) && tint_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				tint_show_post_content( $tint_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'tint_filter_show_blog_meta', $tint_show_meta, $tint_components, 'band' ) ) {
			if ( count( $tint_components ) > 0 ) {
				do_action( 'tint_action_before_post_meta' );
				tint_show_post_meta(
					apply_filters(
						'tint_filter_post_meta_args', array(
							'components' => join( ',', $tint_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'tint_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'tint_filter_show_blog_readmore', ! $tint_show_title || ! empty( $tint_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $tint_template_args['no_links'] ) ) {
				do_action( 'tint_action_before_post_readmore' );
				tint_show_post_more_link( $tint_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'tint_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $tint_template_args ) ) {
	if ( ! empty( $tint_template_args['slider'] ) || $tint_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
