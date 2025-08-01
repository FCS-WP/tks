<?php
/* Tribe Events Calendar support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if ( ! function_exists( 'tint_tribe_events_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'tint_tribe_events_theme_setup1', 1 );
	function tint_tribe_events_theme_setup1() {
		add_filter( 'tint_filter_list_sidebars', 'tint_tribe_events_list_sidebars' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'tint_tribe_events_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'tint_tribe_events_theme_setup3', 3 );
	function tint_tribe_events_theme_setup3() {
		if ( tint_exists_tribe_events() ) {
			// Section 'Tribe Events'
			tint_storage_merge_array(
				'options', '', array_merge(
					array(
						'events' => array(
							'title' => esc_html__( 'Events', 'tint' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the events pages', 'tint' ) ),
							'icon'  => 'icon-events',
							'type'  => 'section',
						),
					),
					tint_options_get_list_cpt_options( 'events', esc_html__( 'Event', 'tint' ) )
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'tint_tribe_events_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'tint_tribe_events_theme_setup9', 9 );
	function tint_tribe_events_theme_setup9() {
		if ( tint_exists_tribe_events() ) {
			add_action( 'wp_enqueue_scripts', 'tint_tribe_events_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_tribe_events', 'tint_tribe_events_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'tint_tribe_events_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_tribe_events', 'tint_tribe_events_frontend_scripts_responsive', 10, 1 );
			add_filter( 'tint_filter_merge_styles', 'tint_tribe_events_merge_styles' );
			add_filter( 'tint_filter_merge_styles_responsive', 'tint_tribe_events_merge_styles_responsive' );
			add_filter( 'tint_filter_post_type_taxonomy', 'tint_tribe_events_post_type_taxonomy', 10, 2 );
			add_filter( 'tint_filter_detect_blog_mode', 'tint_tribe_events_detect_blog_mode' );
			add_filter( 'tint_filter_get_post_categories', 'tint_tribe_events_get_post_categories', 10, 2 );
			add_filter( 'tint_filter_get_post_date', 'tint_tribe_events_get_post_date' );
			add_filter( 'tribe_template_theme_path_list', 'tint_tribe_events_template_theme_path_list', 10, 3 );
		}
		if ( is_admin() ) {
			add_filter( 'tint_filter_tgmpa_required_plugins', 'tint_tribe_events_tgmpa_required_plugins' );
		}

	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'tint_tribe_events_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('tint_filter_tgmpa_required_plugins',	'tint_tribe_events_tgmpa_required_plugins');
	function tint_tribe_events_tgmpa_required_plugins( $list = array() ) {
		if ( tint_storage_isset( 'required_plugins', 'the-events-calendar' ) && tint_storage_get_array( 'required_plugins', 'the-events-calendar', 'install' ) !== false ) {
			$list[] = array(
				'name'     => tint_storage_get_array( 'required_plugins', 'the-events-calendar', 'title' ),
				'slug'     => 'the-events-calendar',
				'required' => false,
			);
		}
		return $list;
	}
}

if ( ! function_exists( 'tint_tribe_events_template_theme_path_list' ) ) {
	add_filter( 'tribe_template_theme_path_list', 'tint_tribe_events_template_theme_path_list', 10, 3 );
	/**
	 * Allows filtering of the list of theme folders in which we will look for the template.
	 *
	 * Hook: add_filter( 'tribe_template_theme_path_list', 'tint_tribe_events_template_theme_path_list', 10, 3 );
	 *
	 * @param  array   $folders     Complete path to include the base public folder.
	 * @param  string  $namespace   Loads the files from a specified folder from the themes.
	 * @param  self    $template    Current instance of the Tribe__Template.
	 * 
	 * @return array  A filtered array with folders in which we will look for the template.
	 */
	function tint_tribe_events_template_theme_path_list( $folders, $namespace = false, $template = false ) {
		static $checked = false, $skin_dirs = array();
		// Detect a subfolder inside a theme dir
		$subfolder = ! empty( $folders['parent-theme']['path'] )
						? str_replace( tint_prepare_path( TINT_THEME_DIR ), '', tint_prepare_path( $folders['parent-theme']['path'] ) )
						: '';
		// Add a skin-specific directories
		if ( ! empty( $subfolder ) ) {
			// Check if directories are exists
			if ( ! $checked ) {
				$checked = true;
				$skin_dirs = array_merge(
					TINT_THEME_DIR == TINT_CHILD_DIR ? array() : array(
						'child-skin-root'     => trailingslashit( TINT_CHILD_DIR . tint_skins_get_current_skin_dir() ) . $subfolder,
						'child-skin-plugins'  => trailingslashit( TINT_CHILD_DIR . tint_skins_get_current_skin_dir() ) . 'plugins/the-events-calendar/templates/' . $subfolder,
					),
					array(
						'parent-skin-root'    => trailingslashit( TINT_THEME_DIR . tint_skins_get_current_skin_dir() ) . $subfolder,
						'parent-skin-plugins' => trailingslashit( TINT_THEME_DIR . tint_skins_get_current_skin_dir() ) . 'plugins/the-events-calendar/templates/' . $subfolder,
					)
				);
				foreach ( $skin_dirs as $key => $dir ) {
					if ( ! is_dir( $dir ) ) {
						unset( $skin_dirs[ $key ] );
					}
				}
			}
			// Add directories to the list of theme-specific folders
			$priority = 5;
			foreach ( $skin_dirs as $key => $dir ) {
				$folders[ $key ] = array(
					'id'       => $key,
					'priority' => $priority++,
					'path'     => $dir,
				);
			}
		}
		// Remove child dir if it is equal to the theme dir
		if (   ! empty( $folders['child-theme']['path'] )
			&& ! empty( $folders['parent-theme']['path'] )
			&& $folders['child-theme']['path'] == $folders['parent-theme']['path']
		) {
			unset( $folders['child-theme'] );
		}
		return $folders;
	}
}

// Remove 'Tribe Events' section from Customizer
if ( ! function_exists( 'tint_tribe_events_customizer_register_controls' ) ) {
	add_action( 'customize_register', 'tint_tribe_events_customizer_register_controls', 100 );
	function tint_tribe_events_customizer_register_controls( $wp_customize ) {
		if ( false ) {
			// Disable Tribe Events Customizer
			$wp_customize->remove_panel( 'tribe_customizer' );
		} else {
			// Leave Tribe Events Customizer enabled and move it down (after WooCommerce settings)
			$sec = $wp_customize->get_panel( 'tribe_customizer' );
			if ( is_object( $sec ) ) {
				$sec->priority = 200;
			}
		}
	}
}


// Check if Tribe Events is installed and activated
if ( ! function_exists( 'tint_exists_tribe_events' ) ) {
	function tint_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}

// Return true, if current page is any tribe_events page
if ( ! function_exists( 'tint_is_tribe_events_page' ) ) {
	function tint_is_tribe_events_page() {
		$rez = false;
		if ( tint_exists_tribe_events() ) {
			if ( ! is_search() ) {
				$rez = tribe_is_event()
						|| tribe_is_event_query()
						|| tribe_is_event_category()
						|| tribe_is_event_venue()
						|| tribe_is_event_organizer();
			}
		}
		return $rez;
	}
}

// Detect current blog mode
if ( ! function_exists( 'tint_tribe_events_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'tint_filter_detect_blog_mode', 'tint_tribe_events_detect_blog_mode' );
	function tint_tribe_events_detect_blog_mode( $mode = '' ) {
		if ( tint_is_tribe_events_page() ) {
			$mode = 'events';
		}
		return $mode;
	}
}

// Return taxonomy for current post type
if ( ! function_exists( 'tint_tribe_events_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'tint_filter_post_type_taxonomy',	'tint_tribe_events_post_type_taxonomy', 10, 2 );
	function tint_tribe_events_post_type_taxonomy( $tax = '', $post_type = '' ) {
		if ( tint_exists_tribe_events() && Tribe__Events__Main::POSTTYPE == $post_type ) {
			$tax = Tribe__Events__Main::TAXONOMY;
		}
		return $tax;
	}
}

// Show categories of the current event
if ( ! function_exists( 'tint_tribe_events_get_post_categories' ) ) {
	//Handler of the add_filter( 'tint_filter_get_post_categories', 'tint_tribe_events_get_post_categories', 10, 2 );
	function tint_tribe_events_get_post_categories( $cats = '', $args = array() ) {
		if ( get_post_type() == Tribe__Events__Main::POSTTYPE ) {
			$cat_sep = apply_filters(
									'tint_filter_post_meta_cat_separator',
									'<span class="post_meta_item_cat_separator">' . ( ! isset( $args['cat_sep'] ) || ! empty( $args['cat_sep'] ) ? ', ' : ' ' ) . '</span>',
									$args
									);
			$cats = tint_get_post_terms( $cat_sep, get_the_ID(), Tribe__Events__Main::TAXONOMY );
		}
		return $cats;
	}
}

// Return date of the current event
if ( ! function_exists( 'tint_tribe_events_get_post_date' ) ) {
	//Handler of the add_filter( 'tint_filter_get_post_date', 'tint_tribe_events_get_post_date');
	function tint_tribe_events_get_post_date( $dt = '' ) {
		if ( get_post_type() == Tribe__Events__Main::POSTTYPE ) {
			if ( is_int( $dt ) ) {
				// Return start date and time in the Unix format
				$dt = tribe_get_start_date( get_the_ID(), true, 'U' );
			} else {
				// Return Start Date @ Time - End Date @ Time as a string
				$dt = tribe_events_event_schedule_details( get_the_ID(), '', '' );
				
				// Return Start Date @ Time as a string
				// If second parameter is true - time is showed
				// Example: $dt = tribe_get_start_date( get_the_ID(), true );
			}
		}
		return $dt;
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'tint_tribe_events_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'tint_tribe_events_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_tribe_events', 'tint_tribe_events_frontend_scripts', 10, 1 );
	function tint_tribe_events_frontend_scripts( $force = false ) {
		tint_enqueue_optimized( 'tribe_events', $force, array(
			'css' => array(
				'tint-the-events-calendar' => array( 'src' => 'plugins/the-events-calendar/the-events-calendar.css' ),
			)
		) );
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'tint_tribe_events_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'tint_tribe_events_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_tribe_events', 'tint_tribe_events_frontend_scripts_responsive', 10, 1 );
	function tint_tribe_events_frontend_scripts_responsive( $force = false ) {
		tint_enqueue_optimized_responsive( 'tribe_events', $force, array(
			'css' => array(
				'tint-the-events-calendar-responsive' => array( 'src' => 'plugins/the-events-calendar/the-events-calendar-responsive.css', 'media' => 'all' ),
			)
		) );
	}
}

// Merge custom styles
if ( ! function_exists( 'tint_tribe_events_merge_styles' ) ) {
	//Handler of the add_filter('tint_filter_merge_styles', 'tint_tribe_events_merge_styles');
	function tint_tribe_events_merge_styles( $list ) {
		$list[ 'plugins/the-events-calendar/the-events-calendar.css' ] = false;
		return $list;
	}
}


// Merge responsive styles
if ( ! function_exists( 'tint_tribe_events_merge_styles_responsive' ) ) {
	//Handler of the add_filter('tint_filter_merge_styles_responsive', 'tint_tribe_events_merge_styles_responsive');
	function tint_tribe_events_merge_styles_responsive( $list ) {
		$list[ 'plugins/the-events-calendar/the-events-calendar-responsive.css' ] = false;
		return $list;
	}
}



// Add Tribe Events specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( ! function_exists( 'tint_tribe_events_list_sidebars' ) ) {
	//Handler of the add_filter( 'tint_filter_list_sidebars', 'tint_tribe_events_list_sidebars' );
	function tint_tribe_events_list_sidebars( $list = array() ) {
		$list['tribe_events_widgets'] = array(
			'name'        => esc_html__( 'Tribe Events Widgets', 'tint' ),
			'description' => esc_html__( 'Widgets to be shown on the Tribe Events pages', 'tint' ),
		);
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( tint_exists_tribe_events() ) {
	$tint_fdir = tint_get_file_dir( 'plugins/the-events-calendar/the-events-calendar-style.php' );
	if ( ! empty( $tint_fdir ) ) {
		require_once $tint_fdir;
	}
}
