<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package TINT
 * @since TINT 1.0
 */

$tint_template_args = get_query_var( 'tint_template_args' );

if ( is_array( $tint_template_args ) ) {
	$tint_columns    = empty( $tint_template_args['columns'] ) ? 2 : max( 1, $tint_template_args['columns'] );
	$tint_blog_style = array( $tint_template_args['type'], $tint_columns );
    $tint_columns_class = tint_get_column_class( 1, $tint_columns, ! empty( $tint_template_args['columns_tablet']) ? $tint_template_args['columns_tablet'] : '', ! empty($tint_template_args['columns_mobile']) ? $tint_template_args['columns_mobile'] : '' );
} else {
	$tint_template_args = array();
	$tint_blog_style = explode( '_', tint_get_theme_option( 'blog_style' ) );
	$tint_columns    = empty( $tint_blog_style[1] ) ? 2 : max( 1, $tint_blog_style[1] );
    $tint_columns_class = tint_get_column_class( 1, $tint_columns );
}
$tint_expanded   = ! tint_sidebar_present() && tint_get_theme_option( 'expand_content' ) == 'expand';

$tint_post_format = get_post_format();
$tint_post_format = empty( $tint_post_format ) ? 'standard' : str_replace( 'post-format-', '', $tint_post_format );

?><div class="<?php
	if ( ! empty( $tint_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( tint_is_blog_style_use_masonry( $tint_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $tint_columns ) : esc_attr( $tint_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $tint_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $tint_columns )
				. ' post_layout_' . esc_attr( $tint_blog_style[0] )
				. ' post_layout_' . esc_attr( $tint_blog_style[0] ) . '_' . esc_attr( $tint_columns )
	);
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
								: explode( ',', $tint_template_args['meta_parts'] )
								)
							: tint_array_get_keys_by_value( tint_get_theme_option( 'meta_parts' ) );

	tint_show_post_featured( apply_filters( 'tint_filter_args_featured',
		array(
			'thumb_size' => ! empty( $tint_template_args['thumb_size'] )
				? $tint_template_args['thumb_size']
				: tint_get_thumb_size(
					'classic' == $tint_blog_style[0]
						? ( strpos( tint_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $tint_columns > 2 ? 'big' : 'huge' )
								: ( $tint_columns > 2
									? ( $tint_expanded ? 'square' : 'square' )
									: ($tint_columns > 1 ? 'square' : ( $tint_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( tint_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $tint_columns > 2 ? 'masonry-big' : 'full' )
								: ($tint_columns === 1 ? ( $tint_expanded ? 'huge' : 'big' ) : ( $tint_columns <= 2 && $tint_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $tint_hover,
			'meta_parts' => $tint_components,
			'no_links'   => ! empty( $tint_template_args['no_links'] ),
        ),
        'content-classic',
        $tint_template_args
    ) );

	// Title and post meta
	$tint_show_title = get_the_title() != '';
	$tint_show_meta  = count( $tint_components ) > 0 && ! in_array( $tint_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $tint_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'tint_filter_show_blog_meta', $tint_show_meta, $tint_components, 'classic' ) ) {
				if ( count( $tint_components ) > 0 ) {
					do_action( 'tint_action_before_post_meta' );
					tint_show_post_meta(
						apply_filters(
							'tint_filter_post_meta_args', array(
							'components' => join( ',', $tint_components ),
							'seo'        => false,
							'echo'       => true,
						), $tint_blog_style[0], $tint_columns
						)
					);
					do_action( 'tint_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'tint_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'tint_action_before_post_title' );
				if ( empty( $tint_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'tint_action_after_post_title' );
			}

			if( !in_array( $tint_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'tint_filter_show_blog_readmore', ! $tint_show_title || ! empty( $tint_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $tint_template_args['no_links'] ) ) {
						do_action( 'tint_action_before_post_readmore' );
						tint_show_post_more_link( $tint_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'tint_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $tint_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('tint_filter_show_blog_excerpt', empty($tint_template_args['hide_excerpt']) && tint_get_theme_option('excerpt_length') > 0, 'classic')) {
			tint_show_post_content($tint_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $tint_template_args['more_button'] )) {
			if ( empty( $tint_template_args['no_links'] ) ) {
				do_action( 'tint_action_before_post_readmore' );
				tint_show_post_more_link( $tint_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'tint_action_after_post_readmore' );
			}
		}
		$tint_content = ob_get_contents();
		ob_end_clean();
		tint_show_layout($tint_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
