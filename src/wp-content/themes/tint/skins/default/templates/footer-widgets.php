<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */

// Footer sidebar
$tint_footer_name    = tint_get_theme_option( 'footer_widgets' );
$tint_footer_present = ! tint_is_off( $tint_footer_name ) && is_active_sidebar( $tint_footer_name );
if ( $tint_footer_present ) {
	tint_storage_set( 'current_sidebar', 'footer' );
	$tint_footer_wide = tint_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $tint_footer_name ) ) {
		dynamic_sidebar( $tint_footer_name );
	}
	$tint_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $tint_out ) ) {
		$tint_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $tint_out );
		$tint_need_columns = true;   //or check: strpos($tint_out, 'columns_wrap')===false;
		if ( $tint_need_columns ) {
			$tint_columns = max( 0, (int) tint_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $tint_columns ) {
				$tint_columns = min( 4, max( 1, tint_tags_count( $tint_out, 'aside' ) ) );
			}
			if ( $tint_columns > 1 ) {
				$tint_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $tint_columns ) . ' widget', $tint_out );
			} else {
				$tint_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $tint_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'tint_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $tint_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $tint_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'tint_action_before_sidebar', 'footer' );
				tint_show_layout( $tint_out );
				do_action( 'tint_action_after_sidebar', 'footer' );
				if ( $tint_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $tint_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'tint_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
