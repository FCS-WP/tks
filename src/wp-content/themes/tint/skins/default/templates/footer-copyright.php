<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$tint_copyright_scheme = tint_get_theme_option( 'copyright_scheme' );
if ( ! empty( $tint_copyright_scheme ) && ! tint_is_inherit( $tint_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $tint_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$tint_copyright = tint_get_theme_option( 'copyright' );
			if ( ! empty( $tint_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$tint_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $tint_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$tint_copyright = tint_prepare_macros( $tint_copyright );
				// Display copyright
				echo wp_kses( nl2br( $tint_copyright ), 'tint_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
