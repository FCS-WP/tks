<?php
/**
 * The Portfolio template to display the content
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

$tint_post_format = get_post_format();
$tint_post_format = empty( $tint_post_format ) ? 'standard' : str_replace( 'post-format-', '', $tint_post_format );

?><div class="
<?php
if ( ! empty( $tint_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( tint_is_blog_style_use_masonry( $tint_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $tint_columns ) : esc_attr( $tint_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $tint_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $tint_columns )
		. ( 'portfolio' != $tint_blog_style[0] ? ' ' . esc_attr( $tint_blog_style[0] )  . '_' . esc_attr( $tint_columns ) : '' )
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

	$tint_hover   = ! empty( $tint_template_args['hover'] ) && ! tint_is_inherit( $tint_template_args['hover'] )
								? $tint_template_args['hover']
								: tint_get_theme_option( 'image_hover' );

	if ( 'dots' == $tint_hover ) {
		$tint_post_link = empty( $tint_template_args['no_links'] )
								? ( ! empty( $tint_template_args['link'] )
									? $tint_template_args['link']
									: get_permalink()
									)
								: '';
		$tint_target    = ! empty( $tint_post_link ) && tint_is_external_url( $tint_post_link )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$tint_components = ! empty( $tint_template_args['meta_parts'] )
							? ( is_array( $tint_template_args['meta_parts'] )
								? $tint_template_args['meta_parts']
								: explode( ',', $tint_template_args['meta_parts'] )
								)
							: tint_array_get_keys_by_value( tint_get_theme_option( 'meta_parts' ) );

	// Featured image
	tint_show_post_featured( apply_filters( 'tint_filter_args_featured',
        array(
			'hover'         => $tint_hover,
			'no_links'      => ! empty( $tint_template_args['no_links'] ),
			'thumb_size'    => ! empty( $tint_template_args['thumb_size'] )
								? $tint_template_args['thumb_size']
								: tint_get_thumb_size(
									tint_is_blog_style_use_masonry( $tint_blog_style[0] )
										? (	strpos( tint_get_theme_option( 'body_style' ), 'full' ) !== false || $tint_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( tint_get_theme_option( 'body_style' ), 'full' ) !== false || $tint_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => tint_is_blog_style_use_masonry( $tint_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $tint_components,
			'class'         => 'dots' == $tint_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $tint_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $tint_post_link )
												? '<a href="' . esc_url( $tint_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $tint_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $tint_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $tint_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!