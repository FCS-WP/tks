<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package TINT
 * @since TINT 1.0
 */

$tint_args = get_query_var( 'tint_logo_args' );

// Site logo
$tint_logo_type   = isset( $tint_args['type'] ) ? $tint_args['type'] : '';
$tint_logo_image  = tint_get_logo_image( $tint_logo_type );
$tint_logo_text   = tint_is_on( tint_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$tint_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $tint_logo_image['logo'] ) || ! empty( $tint_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $tint_logo_image['logo'] ) ) {
			if ( empty( $tint_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($tint_logo_image['logo']) && (int) $tint_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$tint_attr = tint_getimagesize( $tint_logo_image['logo'] );
				echo '<img src="' . esc_url( $tint_logo_image['logo'] ) . '"'
						. ( ! empty( $tint_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $tint_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $tint_logo_text ) . '"'
						. ( ! empty( $tint_attr[3] ) ? ' ' . wp_kses_data( $tint_attr[3] ) : '' )
						. '>';
			}
		} else {
			tint_show_layout( tint_prepare_macros( $tint_logo_text ), '<span class="logo_text">', '</span>' );
			tint_show_layout( tint_prepare_macros( $tint_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
