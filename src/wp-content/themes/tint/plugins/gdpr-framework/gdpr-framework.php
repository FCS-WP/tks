<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'tint_gdpr_framework_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'tint_gdpr_framework_theme_setup9', 9 );
	function tint_gdpr_framework_theme_setup9() {
		if ( is_admin() ) {
			add_filter( 'tint_filter_tgmpa_required_plugins', 'tint_gdpr_framework_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'tint_gdpr_framework_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('tint_filter_tgmpa_required_plugins',	'tint_gdpr_framework_tgmpa_required_plugins');
	function tint_gdpr_framework_tgmpa_required_plugins( $list = array() ) {
		if ( tint_storage_isset( 'required_plugins', 'gdpr-framework' ) && tint_storage_get_array( 'required_plugins', 'gdpr-framework', 'install' ) !== false ) {
			$list[] = array(
				'name'     => tint_storage_get_array( 'required_plugins', 'gdpr-framework', 'title' ),
				'slug'     => 'gdpr-framework',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if this plugin installed and activated
if ( ! function_exists( 'tint_exists_gdpr_framework' ) ) {
	function tint_exists_gdpr_framework() {
		return defined( 'GDPR_FRAMEWORK_VERSION' );
	}
}
