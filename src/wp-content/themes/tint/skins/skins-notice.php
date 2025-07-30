<?php
/**
 * The template to display Admin notices
 *
 * @package TINT
 * @since TINT 1.0.64
 */

$tint_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$tint_skins_args = get_query_var( 'tint_skins_notice_args' );
?>
<div class="tint_admin_notice tint_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$tint_theme_img = tint_get_file_url( 'screenshot.jpg' );
	if ( '' != $tint_theme_img ) {
		?>
		<div class="tint_notice_image"><img src="<?php echo esc_url( $tint_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'tint' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="tint_notice_title">
		<?php esc_html_e( 'New skins are available', 'tint' ); ?>
	</h3>
	<?php

	// Description
	$tint_total      = $tint_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$tint_skins_msg  = $tint_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $tint_total, 'tint' ), $tint_total ) . '</strong>'
							: '';
	$tint_total      = $tint_skins_args['free'];
	$tint_skins_msg .= $tint_total > 0
							? ( ! empty( $tint_skins_msg ) ? ' ' . esc_html__( 'and', 'tint' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $tint_total, 'tint' ), $tint_total ) . '</strong>'
							: '';
	$tint_total      = $tint_skins_args['pay'];
	$tint_skins_msg .= $tint_skins_args['pay'] > 0
							? ( ! empty( $tint_skins_msg ) ? ' ' . esc_html__( 'and', 'tint' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $tint_total, 'tint' ), $tint_total ) . '</strong>'
							: '';
	?>
	<div class="tint_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'tint' ), $tint_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="tint_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $tint_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'tint' );
			?>
		</a>
	</div>
</div>
