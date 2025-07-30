<?php
/**
 * The template to display default site footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$tint_footer_scheme = tint_get_theme_option( 'footer_scheme' );
if ( ! empty( $tint_footer_scheme ) && ! tint_is_inherit( $tint_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $tint_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
