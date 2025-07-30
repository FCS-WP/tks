<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package TINT
 * @since TINT 1.0.06
 */

$tint_header_css   = '';
$tint_header_image = get_header_image();
$tint_header_video = tint_get_header_video();
if ( ! empty( $tint_header_image ) && tint_trx_addons_featured_image_override( is_singular() || tint_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$tint_header_image = tint_get_current_mode_image( $tint_header_image );
}

$tint_header_id = tint_get_custom_header_id();
$tint_header_meta = get_post_meta( $tint_header_id, 'trx_addons_options', true );
if ( ! empty( $tint_header_meta['margin'] ) ) {
	tint_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( tint_prepare_css_value( $tint_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $tint_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $tint_header_id ) ) ); ?>
				<?php
				echo ! empty( $tint_header_image ) || ! empty( $tint_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $tint_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $tint_header_image ) {
					echo ' ' . esc_attr( tint_add_inline_css_class( 'background-image: url(' . esc_url( $tint_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( tint_is_on( tint_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight tint-full-height';
				}
				$tint_header_scheme = tint_get_theme_option( 'header_scheme' );
				if ( ! empty( $tint_header_scheme ) && ! tint_is_inherit( $tint_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $tint_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $tint_header_video ) ) {
		get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'tint_action_show_layout', $tint_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
