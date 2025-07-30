<?php
/**
 * The template to display the widgets area in the header
 *
 * @package TINT
 * @since TINT 1.0
 */

// Header sidebar
$tint_header_name    = tint_get_theme_option( 'header_widgets' );
$tint_header_present = ! tint_is_off( $tint_header_name ) && is_active_sidebar( $tint_header_name );
if ( $tint_header_present ) {
	tint_storage_set( 'current_sidebar', 'header' );
	$tint_header_wide = tint_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $tint_header_name ) ) {
		dynamic_sidebar( $tint_header_name );
	}
	$tint_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $tint_widgets_output ) ) {
		$tint_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $tint_widgets_output );
		$tint_need_columns   = strpos( $tint_widgets_output, 'columns_wrap' ) === false;
		if ( $tint_need_columns ) {
			$tint_columns = max( 0, (int) tint_get_theme_option( 'header_columns' ) );
			if ( 0 == $tint_columns ) {
				$tint_columns = min( 6, max( 1, tint_tags_count( $tint_widgets_output, 'aside' ) ) );
			}
			if ( $tint_columns > 1 ) {
				$tint_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $tint_columns ) . ' widget', $tint_widgets_output );
			} else {
				$tint_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $tint_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'tint_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $tint_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $tint_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'tint_action_before_sidebar', 'header' );
				tint_show_layout( $tint_widgets_output );
				do_action( 'tint_action_after_sidebar', 'header' );
				if ( $tint_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $tint_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'tint_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
