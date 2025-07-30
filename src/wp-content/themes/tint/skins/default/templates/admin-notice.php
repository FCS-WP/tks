<?php
/**
 * The template to display Admin notices
 *
 * @package TINT
 * @since TINT 1.0.1
 */

$tint_theme_slug = get_option( 'template' );
$tint_theme_obj  = wp_get_theme( $tint_theme_slug );
?>
<div class="tint_admin_notice tint_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'tint' ),
				$tint_theme_obj->get( 'Name' ) . ( TINT_THEME_FREE ? ' ' . __( 'Free', 'tint' ) : '' ),
				$tint_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="tint_notice_text">
		<p class="tint_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $tint_theme_obj->description ) );
			?>
		</p>
		<p class="tint_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'tint' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="tint_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=tint_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'tint' );
			?>
		</a>
	</div>
</div>
