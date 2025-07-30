<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package TINT
 * @since TINT 1.0.50
 */

$tint_template_args = get_query_var( 'tint_template_args' );
if ( is_array( $tint_template_args ) ) {
	$tint_columns    = empty( $tint_template_args['columns'] ) ? 2 : max( 1, $tint_template_args['columns'] );
	$tint_blog_style = array( $tint_template_args['type'], $tint_columns );
} else {
	$tint_template_args = array();
	$tint_blog_style = explode( '_', tint_get_theme_option( 'blog_style' ) );
	$tint_columns    = empty( $tint_blog_style[1] ) ? 2 : max( 1, $tint_blog_style[1] );
}
$tint_blog_id       = tint_get_custom_blog_id( join( '_', $tint_blog_style ) );
$tint_blog_style[0] = str_replace( 'blog-custom-', '', $tint_blog_style[0] );
$tint_expanded      = ! tint_sidebar_present() && tint_get_theme_option( 'expand_content' ) == 'expand';
$tint_components    = ! empty( $tint_template_args['meta_parts'] )
							? ( is_array( $tint_template_args['meta_parts'] )
								? join( ',', $tint_template_args['meta_parts'] )
								: $tint_template_args['meta_parts']
								)
							: tint_array_get_keys_by_value( tint_get_theme_option( 'meta_parts' ) );
$tint_post_format   = get_post_format();
$tint_post_format   = empty( $tint_post_format ) ? 'standard' : str_replace( 'post-format-', '', $tint_post_format );

$tint_blog_meta     = tint_get_custom_layout_meta( $tint_blog_id );
$tint_custom_style  = ! empty( $tint_blog_meta['scripts_required'] ) ? $tint_blog_meta['scripts_required'] : 'none';

if ( ! empty( $tint_template_args['slider'] ) || $tint_columns > 1 || ! tint_is_off( $tint_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $tint_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( tint_is_off( $tint_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $tint_custom_style ) ) . "-1_{$tint_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $tint_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $tint_columns )
					. ' post_layout_' . esc_attr( $tint_blog_style[0] )
					. ' post_layout_' . esc_attr( $tint_blog_style[0] ) . '_' . esc_attr( $tint_columns )
					. ( ! tint_is_off( $tint_custom_style )
						? ' post_layout_' . esc_attr( $tint_custom_style )
							. ' post_layout_' . esc_attr( $tint_custom_style ) . '_' . esc_attr( $tint_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'tint_action_show_layout', $tint_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $tint_template_args['slider'] ) || $tint_columns > 1 || ! tint_is_off( $tint_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
