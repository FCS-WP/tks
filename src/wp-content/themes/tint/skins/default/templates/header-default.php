<?php
/**
 * The template to display default site header
 *
 * @package TINT
 * @since TINT 1.0
 */

$tint_header_css   = '';
$tint_header_image = get_header_image();
$tint_header_video = tint_get_header_video();
if ( ! empty( $tint_header_image ) && tint_trx_addons_featured_image_override( is_singular() || tint_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$tint_header_image = tint_get_current_mode_image( $tint_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $tint_header_image ) || ! empty( $tint_header_video ) ? ' with_bg_image' : ' without_bg_image';
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

	// Main menu
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( tint_is_on( tint_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
