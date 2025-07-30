<?php
/**
 * The Header: Logo and main menu
 *
 * @package TINT
 * @since TINT 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( tint_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'tint_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'tint_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('tint_action_body_wrap_attributes'); ?>>

		<?php do_action( 'tint_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'tint_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('tint_action_page_wrap_attributes'); ?>>

			<?php do_action( 'tint_action_page_wrap_start' ); ?>

			<?php
			$tint_full_post_loading = ( tint_is_singular( 'post' ) || tint_is_singular( 'attachment' ) ) && tint_get_value_gp( 'action' ) == 'full_post_loading';
			$tint_prev_post_loading = ( tint_is_singular( 'post' ) || tint_is_singular( 'attachment' ) ) && tint_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $tint_full_post_loading && ! $tint_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="tint_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'tint_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'tint' ); ?></a>
				<?php if ( tint_sidebar_present() ) { ?>
				<a class="tint_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'tint_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'tint' ); ?></a>
				<?php } ?>
				<a class="tint_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'tint_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'tint' ); ?></a>

				<?php
				do_action( 'tint_action_before_header' );

				// Header
				$tint_header_type = tint_get_theme_option( 'header_type' );
				if ( 'custom' == $tint_header_type && ! tint_is_layouts_available() ) {
					$tint_header_type = 'default';
				}
				get_template_part( apply_filters( 'tint_filter_get_template_part', "templates/header-" . sanitize_file_name( $tint_header_type ) ) );

				// Side menu
				if ( in_array( tint_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'tint_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'tint_action_after_header' );

			}
			?>

			<?php do_action( 'tint_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( tint_is_off( tint_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $tint_header_type ) ) {
						$tint_header_type = tint_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $tint_header_type && tint_is_layouts_available() ) {
						$tint_header_id = tint_get_custom_header_id();
						if ( $tint_header_id > 0 ) {
							$tint_header_meta = tint_get_custom_layout_meta( $tint_header_id );
							if ( ! empty( $tint_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$tint_footer_type = tint_get_theme_option( 'footer_type' );
					if ( 'custom' == $tint_footer_type && tint_is_layouts_available() ) {
						$tint_footer_id = tint_get_custom_footer_id();
						if ( $tint_footer_id ) {
							$tint_footer_meta = tint_get_custom_layout_meta( $tint_footer_id );
							if ( ! empty( $tint_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'tint_action_page_content_wrap_class', $tint_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'tint_filter_is_prev_post_loading', $tint_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( tint_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'tint_action_page_content_wrap_data', $tint_prev_post_loading );
			?>>
				<?php
				do_action( 'tint_action_page_content_wrap', $tint_full_post_loading || $tint_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'tint_filter_single_post_header', tint_is_singular( 'post' ) || tint_is_singular( 'attachment' ) ) ) {
					if ( $tint_prev_post_loading ) {
						if ( tint_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'tint_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$tint_path = apply_filters( 'tint_filter_get_template_part', 'templates/single-styles/' . tint_get_theme_option( 'single_style' ) );
					if ( tint_get_file_dir( $tint_path . '.php' ) != '' ) {
						get_template_part( $tint_path );
					}
				}

				// Widgets area above page
				$tint_body_style   = tint_get_theme_option( 'body_style' );
				$tint_widgets_name = tint_get_theme_option( 'widgets_above_page', 'hide' );
				$tint_show_widgets = ! tint_is_off( $tint_widgets_name ) && is_active_sidebar( $tint_widgets_name );
				if ( $tint_show_widgets ) {
					if ( 'fullscreen' != $tint_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					tint_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $tint_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'tint_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $tint_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'tint_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'tint_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="tint_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( tint_is_singular( 'post' ) || tint_is_singular( 'attachment' ) )
							&& $tint_prev_post_loading 
							&& tint_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'tint_action_between_posts' );
						}

						// Widgets area above content
						tint_create_widgets_area( 'widgets_above_content' );

						do_action( 'tint_action_page_content_start_text' );
