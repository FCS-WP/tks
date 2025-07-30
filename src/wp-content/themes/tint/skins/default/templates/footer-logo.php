<?php
/**
 * The template to display the site logo in the footer
 *
 * @package TINT
 * @since TINT 1.0.10
 */

// Logo
if ( tint_is_on( tint_get_theme_option( 'logo_in_footer' ) ) ) {
	$tint_logo_image = tint_get_logo_image( 'footer' );
	$tint_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $tint_logo_image['logo'] ) || ! empty( $tint_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $tint_logo_image['logo'] ) ) {
					$tint_attr = tint_getimagesize( $tint_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $tint_logo_image['logo'] ) . '"'
								. ( ! empty( $tint_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $tint_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'tint' ) . '"'
								. ( ! empty( $tint_attr[3] ) ? ' ' . wp_kses_data( $tint_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $tint_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $tint_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
