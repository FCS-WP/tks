<?php
/**
 * Required plugins
 *
 * @package TINT
 * @since TINT 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$tint_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'tint' ),
	'page_builders' => esc_html__( 'Page Builders', 'tint' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'tint' ),
	'socials'       => esc_html__( 'Socials and Communities', 'tint' ),
	'events'        => esc_html__( 'Events and Appointments', 'tint' ),
	'content'       => esc_html__( 'Content', 'tint' ),
	'other'         => esc_html__( 'Other', 'tint' ),
);
$tint_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'tint' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'tint' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $tint_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'tint' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'tint' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $tint_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'tint' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'tint' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $tint_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'tint' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'tint' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $tint_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'tint' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'tint' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $tint_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'tint' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'tint' ),
		'required'    => false,
		'install'     => false, // TRX_addons has marked the "Elegro Crypto Payment" plugin as obsolete and no longer recommends it for installation, even if it had been previously recommended by the theme
		'logo'        => 'elegro-payment.png',
		'group'       => $tint_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'tint' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'tint' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $tint_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'tint' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'tint' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $tint_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'tint' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $tint_theme_required_plugins_groups['events'],
	),
	'quickcal'                     => array(
		'title'       => esc_html__( 'QuickCal', 'tint' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => 'quickcal.png',
		'group'       => $tint_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'tint' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $tint_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'tint' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'tint' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $tint_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'tint' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => tint_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $tint_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'tint' ),
		'description' => '',
		'required'    => false,
		'logo'        => tint_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'tint' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => tint_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'tint' ),
		'description' => '',
		'required'    => false,
		'logo'        => tint_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $tint_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'tint' ),
		'description' => '',
		'required'    => false,
                'install'     => false,
		'logo'        => tint_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $tint_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'tint' ),
		'description' => '',
		'required'    => false,
        	'install'     => false,
		'logo'        => tint_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'tint' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'tint' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'tint' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'tint' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $tint_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'tint' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'tint' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $tint_theme_required_plugins_groups['other'],
	),
	'gdpr-framework'         => array(
		'title'       => esc_html__( 'The GDPR Framework', 'tint' ),
		'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'tint' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'gdpr-framework.png',
		'group'       => $tint_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'tint' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'tint' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $tint_theme_required_plugins_groups['other'],
	),
);

if ( TINT_THEME_FREE ) {
	unset( $tint_theme_required_plugins['js_composer'] );
	unset( $tint_theme_required_plugins['booked'] );
	unset( $tint_theme_required_plugins['quickcal'] );
	unset( $tint_theme_required_plugins['the-events-calendar'] );
	unset( $tint_theme_required_plugins['calculated-fields-form'] );
	unset( $tint_theme_required_plugins['essential-grid'] );
	unset( $tint_theme_required_plugins['revslider'] );
	unset( $tint_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $tint_theme_required_plugins['trx_updater'] );
	unset( $tint_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
tint_storage_set( 'required_plugins', $tint_theme_required_plugins );
