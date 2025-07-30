<?php
/**
 * The template to display the socials in the footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */


// Socials
if ( tint_is_on( tint_get_theme_option( 'socials_in_footer' ) ) ) {
	$tint_output = tint_get_socials_links();
	if ( '' != $tint_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php tint_show_layout( $tint_output ); ?>
			</div>
		</div>
		<?php
	}
}
