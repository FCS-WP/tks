<?php
/**
 * The template to display default site footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */

$tint_footer_id = tint_get_custom_footer_id();
$tint_footer_meta = get_post_meta( $tint_footer_id, 'trx_addons_options', true );
if ( ! empty( $tint_footer_meta['margin'] ) ) {
	tint_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( tint_prepare_css_value( $tint_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $tint_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $tint_footer_id ) ) ); ?>
						<?php
						$tint_footer_scheme = tint_get_theme_option( 'footer_scheme' );
						if ( ! empty( $tint_footer_scheme ) && ! tint_is_inherit( $tint_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $tint_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'tint_action_show_layout', $tint_footer_id );
	?>
</footer><!-- /.footer_wrap -->
