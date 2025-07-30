<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package TINT
 * @since TINT 1.0
 */

if ( tint_sidebar_present() ) {
	
	$tint_sidebar_type = tint_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $tint_sidebar_type && ! tint_is_layouts_available() ) {
		$tint_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $tint_sidebar_type ) {
		// Default sidebar with widgets
		$tint_sidebar_name = tint_get_theme_option( 'sidebar_widgets' );
		tint_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $tint_sidebar_name ) ) {
			dynamic_sidebar( $tint_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$tint_sidebar_id = tint_get_custom_sidebar_id();
		do_action( 'tint_action_show_layout', $tint_sidebar_id );
	}
	$tint_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $tint_out ) ) {
		$tint_sidebar_position    = tint_get_theme_option( 'sidebar_position' );
		$tint_sidebar_position_ss = tint_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $tint_sidebar_position );
			echo ' sidebar_' . esc_attr( $tint_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $tint_sidebar_type );

			$tint_sidebar_scheme = apply_filters( 'tint_filter_sidebar_scheme', tint_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $tint_sidebar_scheme ) && ! tint_is_inherit( $tint_sidebar_scheme ) && 'custom' != $tint_sidebar_type ) {
				echo ' scheme_' . esc_attr( $tint_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="tint_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'tint_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $tint_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$tint_title = apply_filters( 'tint_filter_sidebar_control_title', 'float' == $tint_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'tint' ) : '' );
				$tint_text  = apply_filters( 'tint_filter_sidebar_control_text', 'above' == $tint_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'tint' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $tint_title ); ?>"><?php echo esc_html( $tint_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'tint_action_before_sidebar', 'sidebar' );
				tint_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $tint_out ) );
				do_action( 'tint_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'tint_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
