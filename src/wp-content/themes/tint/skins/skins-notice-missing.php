<?php
/**
 * The template to display Admin notices
 *
 * @package TINT
 * @since TINT 1.98.0
 */

$tint_skins_url   = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$tint_active_skin = tint_skins_get_active_skin_name();
?>
<div class="tint_admin_notice tint_skins_notice notice notice-error">
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
		<?php esc_html_e( 'Active skin is missing!', 'tint' ); ?>
	</h3>
	<div class="tint_notice_text">
		<p>
			<?php
			// Translators: Add a current skin name to the message
			echo wp_kses_data( sprintf( __( "Your active skin <b>'%s'</b> is missing. Usually this happens when the theme is updated directly through the server or FTP.", 'tint' ), ucfirst( $tint_active_skin ) ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "Please use only <b>'ThemeREX Updater v.1.6.0+'</b> plugin for your future updates.", 'tint' ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "But no worries! You can re-download the skin via 'Skins Manager' ( Theme Panel - Theme Dashboard - Skins ).", 'tint' ) );
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
