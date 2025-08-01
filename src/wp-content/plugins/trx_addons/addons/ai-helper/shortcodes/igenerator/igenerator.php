<?php
/**
 * Shortcode: Image Generator
 *
 * @package ThemeREX Addons
 * @since v2.20.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

use TrxAddons\AiHelper\Utils;
use TrxAddons\AiHelper\Lists;


// Load required styles and scripts for the frontend
if ( ! function_exists( 'trx_addons_sc_igenerator_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'trx_addons_sc_igenerator_load_scripts_front', TRX_ADDONS_ENQUEUE_SCRIPTS_PRIORITY );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'trx_addons_sc_igenerator_load_scripts_front', 10, 1 );
	function trx_addons_sc_igenerator_load_scripts_front( $force = false ) {
		trx_addons_enqueue_optimized( 'sc_igenerator', $force, array(
/*
			'lib' => array(
				'css' => array(
					'msgbox' => array( 'src' => 'js/msgbox/msgbox.css' ),
				),
				'js' => array(
					'msgbox' => array( 'src' => 'js/msgbox/msgbox.js' ),
				)
			),
*/
			'css'  => array(
				'trx_addons-sc_igenerator' => array( 'src' => TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.css' ),
			),
			'js' => array(
				'trx_addons-sc_igenerator' => array( 'src' => TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.js', 'deps' => 'jquery' ),
			),
			'check' => array(
				array( 'type' => 'sc',  'sc' => 'trx_sc_igenerator' ),
				array( 'type' => 'gb',  'sc' => 'wp:trx-addons/igenerator' ),
				array( 'type' => 'elm', 'sc' => '"widgetType":"trx_sc_igenerator"' ),
				array( 'type' => 'elm', 'sc' => '"shortcode":"[trx_sc_igenerator' ),
			)
		) );
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'trx_addons_sc_igenerator_load_scripts_front_responsive' ) ) {
	add_action( 'wp_enqueue_scripts', 'trx_addons_sc_igenerator_load_scripts_front_responsive', TRX_ADDONS_ENQUEUE_RESPONSIVE_PRIORITY );
	add_action( 'trx_addons_action_load_scripts_front_sc_igenerator', 'trx_addons_sc_igenerator_load_scripts_front_responsive', 10, 1 );
	function trx_addons_sc_igenerator_load_scripts_front_responsive( $force = false  ) {
		trx_addons_enqueue_optimized_responsive( 'sc_igenerator', $force, array(
			'css'  => array(
				'trx_addons-sc_igenerator-responsive' => array(
					'src' => TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.responsive.css',
					'media' => 'lg'
				),
			),
		) );
	}
}

// Add messages to the list with JS vars
if ( ! function_exists( 'trx_addons_sc_igenerator_localize_script' ) ) {
	add_action( 'trx_addons_filter_localize_script', 'trx_addons_sc_igenerator_localize_script' );
	function trx_addons_sc_igenerator_localize_script( $vars ) {
		$vars['ai_helper_sc_igenerator_openai_sizes'] = Lists::get_list_ai_image_sizes( 'openai' );
		$vars['msg_ai_helper_igenerator_download'] = __( 'Download', 'trx_addons' );
		$vars['msg_ai_helper_igenerator_download_error'] = __( 'Error', 'trx_addons' );
		$vars['msg_ai_helper_igenerator_download_expired'] = __( 'The generated image cache timed out. The download link is no longer valid.<br>But you can still download the image by right-clicking on it and selecting "Save Image As..."', 'trx_addons' );
		$vars['msg_ai_helper_igenerator_disabled'] = __( 'Image generation is not available in edit mode!', 'trx_addons' );
		$vars['msg_ai_helper_igenerator_wait_available'] = __( 'Wait for the image to become available on the rendering server', 'trx_addons' );
		return $vars;
	}
}

// Merge shortcode's specific styles to the single stylesheet
if ( ! function_exists( 'trx_addons_sc_igenerator_merge_styles' ) ) {
	add_filter( "trx_addons_filter_merge_styles", 'trx_addons_sc_igenerator_merge_styles' );
	function trx_addons_sc_igenerator_merge_styles( $list ) {
		$list[ TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.css' ] = false;
		return $list;
	}
}

// Merge shortcode's specific styles to the single stylesheet (responsive)
if ( ! function_exists( 'trx_addons_sc_igenerator_merge_styles_responsive' ) ) {
	add_filter("trx_addons_filter_merge_styles_responsive", 'trx_addons_sc_igenerator_merge_styles_responsive' );
	function trx_addons_sc_igenerator_merge_styles_responsive( $list ) {
		$list[ TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.responsive.css' ] = false;
		return $list;
	}
}

// Merge shortcode's specific scripts into single file
if ( ! function_exists( 'trx_addons_sc_igenerator_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_sc_igenerator_merge_scripts');
	function trx_addons_sc_igenerator_merge_scripts($list) {
		$list[ TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator.js' ] = false;
		return $list;
	}
}

// Load styles and scripts if present in the cache of the menu
if ( ! function_exists( 'trx_addons_sc_igenerator_check_in_html_output' ) ) {
	add_filter( 'trx_addons_filter_get_menu_cache_html', 'trx_addons_sc_igenerator_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_show_layout_from_cache', 'trx_addons_sc_igenerator_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_check_page_content', 'trx_addons_sc_igenerator_check_in_html_output', 10, 1 );
	function trx_addons_sc_igenerator_check_in_html_output( $content = '' ) {
		$args = array(
			'check' => array(
				'class=[\'"][^\'"]*sc_igenerator'
			)
		);
		if ( trx_addons_check_in_html_output( 'sc_igenerator', $content, $args ) ) {
			trx_addons_sc_igenerator_load_scripts_front( true );
		}
		return $content;
	}
}


// trx_sc_igenerator
//-------------------------------------------------------------
/*
[trx_sc_igenerator id="unique_id" number="2" prompt="prompt text for ai"]
*/
if ( ! function_exists( 'trx_addons_sc_igenerator' ) ) {
	function trx_addons_sc_igenerator( $atts, $content = '' ) {	
		$atts = trx_addons_sc_prepare_atts( 'trx_sc_igenerator', $atts, trx_addons_sc_common_atts( 'trx_sc_igenerator', 'id,title', array(
			// Individual params
			"type" => "default",
			"tags" => "",
			"tags_label" => "",
			"prompt" => "",
			"placeholder_text" => "",
			"prompt_width" => "100",
			"show_negative_prompt" => 0,
			"negative_prompt" => "",
			"negative_placeholder_text" => "",
			"safety_checker" => "none",
			"show_prompt_translated" => 1,
			"button_text" => "",
			"button_icon" => "",
			"button_image" => "",
			"settings_button_icon" => "",
			"settings_button_image" => "",
			"button_download_icon" => "",
			"button_download_image" => "",
			"number" => "3",
			"columns" => "",
			"columns_tablet" => "",
			"columns_mobile" => "",
			"size" => Utils::get_default_image_size( 'sc_igenerator' ),
			"width" => "",
			"height" => "",
			"model" => "",
			"show_settings" => 0,
			"show_settings_size" => 0,
			"show_limits" => 0,
			"show_download" => 0,
			"show_popup" => 0,
			"show_upload" => 0,
			"align" => "",
			"align_tablet" => "",
			"align_mobile" => "",
			"premium" => 0,
			"system_prompt" => "",
			// "upscale" => 0,
			// "quality" => 0,
			// "panorama" => 0,
			"style" => '',
			"style_openai" => '',
			"quality" => '',
			"demo_images" => "",
			"demo_images_url" => "",
			'demo_thumb_size' => apply_filters( 'trx_addons_filter_thumb_size',
													trx_addons_get_thumb_size( 'avatar' ),
													'trx_addons_sc_igenerator',
													$atts
												),
		) ) );

		// Load shortcode-specific scripts and styles
		trx_addons_sc_igenerator_load_scripts_front( true );

		// Load template
		$output = '';
		$atts['number'] = max( 1, min( 10, (int)$atts['number'] ) );
		if ( empty( $atts['columns'] ) ) $atts['columns'] = $atts['number'];
		$atts['columns'] = max( 1, min( $atts['number'], (int)$atts['columns'] ) );
		if ( ! empty( $atts['columns_tablet'] ) ) $atts['columns_tablet'] = max( 1, min( $atts['number'], (int)$atts['columns_tablet'] ) );
		if ( ! empty( $atts['columns_mobile'] ) ) $atts['columns_mobile'] = max( 1, min( $atts['number'], (int)$atts['columns_mobile'] ) );
		$atts['size'] = Utils::check_image_size( $atts['size'], 'sc_igenerator' );
		$atts['width'] = max( 0, min( Utils::get_max_image_width(), (int)$atts['width'] ) );
		$atts['height'] = max( 0, min( Utils::get_max_image_height(), (int)$atts['height'] ) );
		if ( ! empty( $atts['demo_images'] ) && ! is_array( $atts['demo_images'] ) ) {
			$demo_images = explode( '|', $atts['demo_images'] );
			$atts['demo_images'] = array();
			foreach ( $demo_images as $img ) {
				$atts['demo_images'][] = array( 'url' => $img );
			}
		}

		ob_start();
		if ( ! Utils::is_image_api_available() ) {
			trx_addons_get_template_part( 'templates/tpl.sc_placeholder.php',
				'trx_addons_args_sc_placeholder',
				apply_filters( 'trx_addons_filter_sc_placeholder_args', array(
					'sc' => 'trx_sc_igenerator',
					'title' => __('AI Image Generator is not available - token for access to the API for image generation is not specified', 'trx_addons'),
					'class' => 'sc_placeholder_with_title'
					) )
			);
		} else {
			trx_addons_get_template_part( array(
										TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/tpl.' . trx_addons_esc( trx_addons_sanitize_file_name( $atts['type'] ) ) . '.php',
										TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/tpl.default.php'
										),
										'trx_addons_args_sc_igenerator',
										$atts
									);
		}
		$output = ob_get_contents();
		ob_end_clean();
		return apply_filters( 'trx_addons_sc_output', $output, 'trx_sc_igenerator', $atts, $content );
	}
}

// Add shortcode [trx_sc_igenerator]
if ( ! function_exists( 'trx_addons_sc_igenerator_add_shortcode' ) ) {
	add_action( 'init', 'trx_addons_sc_igenerator_add_shortcode', 20 );
	function trx_addons_sc_igenerator_add_shortcode() {
		add_shortcode( "trx_sc_igenerator", "trx_addons_sc_igenerator" );
	}
}

// Prepare a data for generated images
if ( ! function_exists( 'trx_addons_sc_igenerator_prepare_total_generated' ) ) {
	function trx_addons_sc_igenerator_prepare_total_generated( $data ) {
		if ( ! is_array( $data ) ) {
			$data = array(
				'per_hour' => array_fill( 0, 24, 0 ),
				'per_day' => 0,
				'per_week' => 0,
				'per_month' => 0,
				'per_year' => 0,
				'date' => date( 'Y-m-d' ),
				'week' => date( 'W' ),
				'month' => date( 'm' ),
				'year' => date( 'Y' ),
			);
		}
		if ( $data['date'] != date( 'Y-m-d' ) ) {
			$data['per_hour'] = array_fill( 0, 24, 0 );
			$data['per_day'] = 0;
			$data['date'] = date( 'Y-m-d' );
		}
		if ( ! isset( $data['week'] ) || $data['week'] != date( 'W' ) ) {
			$data['per_week'] = 0;
			$data['week'] = date( 'W' );
		}
		if ( ! isset( $data['month'] ) || $data['month'] != date( 'm' ) ) {
			$data['per_month'] = 0;
			$data['month'] = date( 'm' );
		}
		if ( ! isset( $data['year'] ) || $data['year'] != date( 'Y' ) ) {
			$data['per_year'] = 0;
			$data['year'] = date( 'Y' );
		}
		return $data;
	}
}

// Add number of generated images to the total number
if ( ! function_exists( 'trx_addons_sc_igenerator_set_total_generated' ) ) {
	function trx_addons_sc_igenerator_set_total_generated( $number, $suffix = '', $user_id = 0 ) {
		$data = trx_addons_sc_igenerator_prepare_total_generated( $user_id > 0 && ! empty( $suffix )
					? get_user_meta( $user_id, 'trx_addons_sc_igenerator_total', true )
					: get_transient( "trx_addons_sc_igenerator_total{$suffix}" )
				);
		$hour = (int) date( 'H' );
		$data['per_hour'][ $hour ] += $number;
		$data['per_day'] += $number;
		$data['per_week'] += $number;
		$data['per_month'] += $number;
		$data['per_year'] += $number;
		if ( $user_id > 0 ) {
			update_user_meta( $user_id, 'trx_addons_sc_igenerator_total', $data );
		} else {
			set_transient( "trx_addons_sc_igenerator_total{$suffix}", $data, 24 * 60 * 60 );
		}
	}
}

// Get number of generated images
if ( ! function_exists( 'trx_addons_sc_igenerator_get_total_generated' ) ) {
	function trx_addons_sc_igenerator_get_total_generated( $per = 'hour', $suffix = '', $user_id = 0 ) {
		$data = trx_addons_sc_igenerator_prepare_total_generated( $user_id > 0 && ! empty( $suffix )
					? get_user_meta( $user_id, 'trx_addons_sc_igenerator_total', true )
					: get_transient( "trx_addons_sc_igenerator_total{$suffix}" )
				);
		if ( $per == 'hour' ) {
			$hour = (int) date( 'H' );
			return $data['per_hour'][ $hour ];
		} else if ( $per == 'day' ) {
			return $data['per_day'];
		} else if ( $per == 'week' ) {
			return $data['per_week'];
		} else if ( $per == 'month' ) {
			return $data['per_month'];
		} else if ( $per == 'year' ) {
			return $data['per_year'];
		} else if ( $per == 'all' ) {
			return $data;
		} else {
			return 0;
		}
	}
}

// Log a visitor ip address to the json file
if ( ! function_exists( 'trx_addons_sc_igenerator_log_to_json' ) ) {
	function trx_addons_sc_igenerator_log_to_json( $number, $suffix = '' ) {
		$ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
		$date = date( 'Y-m-d' );
		$time = date( 'H:i:s' );
		$hour = date( 'H' );
		$json = trx_addons_fgc( TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_ADDONS . "ai-helper/shortcodes/igenerator/igenerator{$suffix}.log" );
		if ( empty( $json ) ) $json = '[]';
		$ips = json_decode( $json, true );
		if ( ! is_array( $ips ) ) {
			$ips = array();
		}
		if ( empty( $ips[ $date ] ) ) {
			$ips[ $date ] = array( 'total' => 0, 'ip' => array(), 'hour' => array() );
		}
		// Log total
		$ips[ $date ]['total'] += $number;
		// Log by IP
		if ( empty( $ips[ $date ]['ip'][ $ip ] ) ) {
			$ips[ $date ]['ip'][ $ip ] = array();
		}
		if ( empty( $ips[ $date ]['ip'][ $ip ][ $time ] ) ) {
			$ips[ $date ]['ip'][ $ip ][ $time ] = 0;
		}
		$ips[ $date ]['ip'][ $ip ][ $time ] += $number;
		// Log by hour
		if ( empty( $ips[ $date ]['hour'][ $hour ] ) ) {
			$ips[ $date ]['hour'][ $hour ] = array();
		}
		if ( empty( $ips[ $date ]['hour'][ $hour ][ $time ] ) ) {
			$ips[ $date ]['hour'][ $hour ][ $time ] = 0;
		}
		$ips[ $date ]['hour'][ $hour ][ $time ] += $number;
		trx_addons_fpc( TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_ADDONS . "ai-helper/shortcodes/igenerator/igenerator{$suffix}.log", json_encode( $ips, JSON_PRETTY_PRINT ) );
	}
}

// Callback function to generate images from the shortcode AJAX request
if ( ! function_exists( 'trx_addons_sc_igenerator_generate_images' ) ) {
	add_action( 'wp_ajax_nopriv_trx_addons_ai_helper_igenerator', 'trx_addons_sc_igenerator_generate_images' );
	add_action( 'wp_ajax_trx_addons_ai_helper_igenerator', 'trx_addons_sc_igenerator_generate_images' );
	function trx_addons_sc_igenerator_generate_images() {

		trx_addons_verify_nonce();

		$settings = trx_addons_decode_settings( trx_addons_get_value_gp( 'settings' ) );
		$action = trx_addons_get_value_gp( 'action_type', 'generation' );
		$model = trx_addons_get_value_gp( 'model' );
		if ( empty( $model ) ) {
			$model = $action == 'upscale'
						? trx_addons_array_get_first( Lists::get_list_ai_image_upscalers() )
						: ( ! empty( $settings['model'] ) ? $settings['model'] : Utils::get_default_image_model() );
		}
		$prompt = trx_addons_get_value_gp( 'prompt' );
		if ( empty( $prompt ) ) {
			if ( $action == 'variations' ) {
				$prompt = __( 'Make variations of the image.', 'trx_addons' );
			} else if ( $action == 'upscale' ) {
				$prompt = __( 'Upscale the image.', 'trx_addons' );
			} else if ( $action == 'quality' ) {
				$prompt = __( 'Improve the quality of the image.', 'trx_addons' );
			} else if ( $action == 'panorama' ) {
				$prompt = __( 'Make a panorama from the image.', 'trx_addons' );
			} else {
				$prompt = __( 'Generate the image.', 'trx_addons' );
			}
		}
		$negative_prompt = Utils::is_model_support_negative_prompt( $model ) ? trx_addons_get_value_gp( 'negative_prompt' ) : '';
		$safety_checker = ! empty( $settings['safety_checker'] ) ? $settings['safety_checker'] : 'none';
		$quality = ! empty( $settings['quality'] ) ? $settings['quality'] : '';
		$style  = Utils::is_model_support_image_style( $model ) ? trx_addons_get_value_gp( 'style' ) : '';
		$size   = trx_addons_get_value_gp( 'size' )
					? Utils::check_image_size( trx_addons_get_value_gp( 'size' ) )
					: ( ! empty( $settings['size'] )
						? Utils::check_image_size( $settings['size'], 'sc_igenerator' )
						: Utils::get_default_image_size('sc_igenerator')
						);
		$width  = $action == 'upscale'
					? (int)trx_addons_get_value_gp( 'width' )
					: ( $size == 'custom' && (int)trx_addons_get_value_gp( 'width' ) > 0
						? max( 0, min( Utils::get_max_image_width(), (int)trx_addons_get_value_gp( 'width' ) ) )
						: ( $size == 'custom' && ! empty( $settings['width'] )
							? max( 0, min( Utils::get_max_image_width(), $settings['width'] ) )
							: 0
							)
						);
		$height = $action == 'upscale'
					? (int)trx_addons_get_value_gp( 'height' )
					: ( $size == 'custom' && (int)trx_addons_get_value_gp( 'height' ) > 0
						? max( 0, min( Utils::get_max_image_height(), (int)trx_addons_get_value_gp( 'height' ) ) )
						: ( $size == 'custom' && ! empty( $settings['height'] )
							? max( 0, min( Utils::get_max_image_height(), $settings['height'] ) )
							: 0
							)
						);
		$number = $action == 'upscale'
					? 1
					: ( ! empty( $settings['number'] )
						? max( 1, min( 10, $settings['number'] ) )
						: 3
						);
		$count = (int)trx_addons_get_value_gp( 'count' );

		$premium = ! empty( $settings['premium'] ) && (int)$settings['premium'] == 1;
		$suffix = $premium ? '_premium' : '';

		$system_prompt = ! empty( $settings['system_prompt'] )
							? trim( $settings['system_prompt'] )
							: apply_filters( 'trx_addons_filter_sc_igenerator_system_prompt', '' );
		if ( ! empty( $system_prompt ) ) {
			$prompt = $prompt . trx_addons_strdot( $prompt ) . ' ' . $system_prompt;
		}

		$answer = array(
			'error' => '',
			'data' => array(
				'images' => array(),
				'demo' => false,
				'show_download' => ! empty( $settings['show_download'] ) ? Utils::$cache_time - 5 : 0,
				'number' => $number,
				'columns' => ! empty( $settings['columns'] ) ? max( 1, min( 12, $settings['columns'] ) ) : 3,
				'columns_tablet' => ! empty( $settings['columns_tablet'] ) ? max( 1, min( 12, $settings['columns_tablet'] ) ) : '',
				'columns_mobile' => ! empty( $settings['columns_mobile'] ) ? max( 1, min( 12, $settings['columns_mobile'] ) ) : '',
				'message' => '',
				'message_type' => 'error',
			)
		);

		if ( ! empty( $prompt ) ) {

			$limits = (int)trx_addons_get_option( "ai_helper_sc_igenerator_limits{$suffix}" ) > 0;
			$lph = $lpv = $lpu = false;
			$used_limits = '';
			$generated = 0;
			$user_id = 0;

			if ( $limits ) {
				$user_level = '';
				$user_limit = false;
				if ( $premium ) {
					$user_id = get_current_user_id();
					$user_level = apply_filters( 'trx_addons_filter_sc_igenerator_user_level', $user_id > 0 ? 'default' : '', $user_id );
					if ( ! empty( $user_level ) ) {
						$levels = trx_addons_get_option( "ai_helper_sc_igenerator_levels_premium" );
						$level_idx = trx_addons_array_search( $levels, 'level', $user_level );
						$user_limit = $level_idx !== false ? $levels[ $level_idx ] : false;
						if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
							$generated = trx_addons_sc_igenerator_get_total_generated( $user_limit['per'], $suffix, $user_id );
							if ( (int)$user_limit['limit'] - $generated > 0 && (int)$user_limit['limit'] - $generated < $number ) {
								$number = $answer['data']['number'] = (int)$user_limit['limit'] - $generated;
							}
							$lpu = (int)$user_limit['limit'] < $generated + $number;
							$used_limits = 'user';
						}
					}
				}
				if ( ! $premium || empty( $user_level ) || ! isset( $user_limit['limit'] ) || trim( $user_limit['limit'] ) === '' ) {
					$generated = trx_addons_sc_igenerator_get_total_generated( 'hour', $suffix );
					$lph = (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_hour{$suffix}" ) < $generated + $number;
					$lpv = (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_visitor{$suffix}" ) < $count;
					$used_limits = 'visitor';
				}
			}

			$demo = $count == 0 || $lpu || $lph || $lpv;

			$api = Utils::get_image_api( $model );

			if ( $api->get_api_key() != '' && ! $demo ) {

				// Log a visitor ip address to the json file
				//trx_addons_sc_igenerator_log_to_json( $number, $suffix );

				$args = array(
					'model' => $model,
					'prompt' => apply_filters( 'trx_addons_filter_ai_helper_prompt', $prompt, compact( 'model', 'size', 'number' ), 'sc_igenerator' ),
					'size' => $size == 'custom' && ( (int)$width == 0 || (int)$height == 0 ) ? Utils::get_default_image_size() : $size,
					'n' => $action == 'upscale' ? 1 : (int)$number,
				);
				if ( ! Utils::is_model_support_negative_prompt( $model ) ) {
					$negative_prompt = '';
				}
				if ( ! empty( $negative_prompt ) ) {
					$args['negative_prompt'] = apply_filters( 'trx_addons_filter_ai_helper_negative_prompt', $negative_prompt, compact( 'model', 'size', 'number' ), 'sc_igenerator' );
				}
				if ( ! trx_addons_is_off( $safety_checker ) && Utils::is_stable_diffusion_model( $model ) ) {
					$args['safety_checker'] = 'yes';
					$args['safety_checker_type'] = $safety_checker;
				}
				if ( in_array( $action, array( 'variations', 'upscale' ) ) && ! empty( $_FILES["upload_image"]["tmp_name"] ) ) {
					$validate = wp_check_filetype( $_FILES["upload_image"]["name"] );
					if ( $validate['type'] == false ) {
						$answer['error'] = __( "File type is not allowed.", "trx_addons" );
					} else {
						$ext = trx_addons_get_file_ext( $_FILES["upload_image"]["name"] );
						if ( empty( $ext ) ) {
							$ext = 'png';
						}
						$args['image'] = trx_addons_uploads_save_data( trx_addons_fgc( $_FILES["upload_image"]["tmp_name"] ), array(
							'expire' => apply_filters( 'trx_addons_filter_ai_helper_uploaded_image_expire_time', 10 * 60 ),
							'ext' => $ext,
						) );
					}
				}
				if ( Utils::is_model_support_image_dimensions( $model ) || $action == 'upscale' ) {
					if ( $width > 0 ) {
						$args['width'] = (int)$width;
					}
					if ( $height > 0 ) {
						$args['height'] = (int)$height;
					}
				}
				if ( ! empty( $style ) ) {
					$args['style'] = $style;
				}
				if ( ! empty( $quality ) ) {
					$args['quality'] = $quality;
				}
				if ( Utils::is_stable_diffusion_model( $model ) ) {
					$guidance_scale = trx_addons_get_value_gp( 'guidance_scale' );
					if ( ! empty( $guidance_scale ) ) {
						$args['guidance_scale'] = (float)$guidance_scale;
					}
					$inference_steps = trx_addons_get_value_gp( 'inference_steps' );
					if ( ! empty( $inference_steps ) ) {
						$args['num_inference_steps'] = (int)$inference_steps;
					}
					$seed = trx_addons_get_value_gp( 'seed' );
					if ( ! empty( $seed ) ) {
						$args['seed'] = (int)$seed;
					}
					$lora_model = $model != 'stable-diffusion/default' ? trim( trx_addons_get_value_gp( 'lora_model' ) ) : '';
					if ( ! empty( $lora_model ) ) {
						$args['lora_model'] = $lora_model;
					}
				} else if ( Utils::is_stability_ai_model( $model ) ) {
					$cfg_scale = trx_addons_get_value_gp( 'cfg_scale' );
					if ( ! empty( $cfg_scale ) ) {
						$args['cfg_scale'] = (float)$cfg_scale;
					}
					$diffusion_steps = trx_addons_get_value_gp( 'diffusion_steps' );
					if ( ! empty( $diffusion_steps ) ) {
						$args['steps'] = (int)$diffusion_steps;
					}
					$strength = trx_addons_get_value_gp( 'strength' );
					if ( ! empty( $strength ) ) {
						$args['strength'] = (float)$strength;
					}
					$seed = trx_addons_get_value_gp( 'seed' );
					if ( ! empty( $seed ) ) {
						$args['seed'] = (int)$seed;
					}
				}

				// if ( Utils::is_stable_diffusion_model( $model ) ) {
				// 	if ( ! empty( $settings['upscale'] ) && (int)$settings['upscale'] > 0 ) {
				// 		$args['upscale'] = $model == 'stable-diffusion/default' ? 'yes' : 2;
				// 	}
				// 	if ( ! empty( $settings['quality'] ) && (int)$settings['quality'] > 0 ) {
				// 		$args['self_attention'] = 'yes';
				// 	}
				// 	if ( ! empty( $settings['panorama'] ) && (int)$settings['panorama'] > 0 ) {
				// 		$args['panorama'] = 'yes';
				// 	}
				// }
				$translated = $prompt != $args['prompt'] || ( ! empty( $negative_prompt ) && $negative_prompt != $args['negative_prompt'] );
				if ( $translated && ! empty( $settings['show_prompt_translated'] ) ) {
					$answer['data']['message_type'] = 'info';
					$answer['data']['message'] = apply_filters( 'trx_addons_filter_sc_igenerator_translated_message',
																'<p>'
																	. str_replace( '\n', '<br>', ! empty( $negative_prompt )
																		? sprintf(
																				__( 'Your prompts was automatically translated into English\nPositive: %1$s\nNegative: %2$s', 'trx_addons' ),
																				'<a href="#" role="button" class="sc_igenerator_message_translation" title="' . esc_attr__( 'Click to use as a prompt', 'trx_addons' ) . '" data-tag-type="prompt" data-tag-prompt="' . esc_attr( $args['prompt'] ) . '">' . $args['prompt'] . '</a>',
																				'<a href="#" role="button" class="sc_igenerator_message_translation" title="' . esc_attr__( 'Click to use as a negative prompt', 'trx_addons' ) . '" data-tag-type="negative_prompt" data-tag-prompt="' . esc_attr( $args['negative_prompt'] ) . '">' . $args['negative_prompt'] . '</a>'
																			)
																		: sprintf(
																				__( 'Your prompt was automatically translated into English:\n%s', 'trx_addons' ),
																				'<a href="#" role="button" class="sc_igenerator_message_translation" title="' . esc_attr__( 'Click to use as a prompt', 'trx_addons' ) . '" data-tag-prompt="' . esc_attr( $args['prompt'] ) . '">' . $args['prompt'] . '</a>'
																			)
																		)
																. '</p>'
															);
				}
				// Add the 'multi_lingual' parameter to the request if the prompt is not translated and the model is 'stable-diffusion'
				if ( Utils::is_stable_diffusion_model( $model )
					&& ! $translated
					&& ( trx_addons_str_is_not_english( $prompt ) || ( ! empty( $negative_prompt ) && trx_addons_str_is_not_english( $negative_prompt ) ) )
				) {
					$args['multi_lingual'] = 'yes';
				}
				// Generate images
				if ( empty( $answer['error'] ) ) {
					if ( $action == 'generation' ) {
						$response = $api->generate_images( apply_filters( 'trx_addons_filter_ai_helper_generate_images_args', $args, 'sc_igenerator' ) );
					} else if ( $action == 'variations' ) {
						if ( ! empty( $args['image'] ) ) {
							$response = $api->make_variations( apply_filters( 'trx_addons_filter_ai_helper_variations_args', $args, 'sc_igenerator' ) );
						} else {
							$answer['error'] = __( 'Error! The image is not uploaded.', 'trx_addons' );
						}
					} else if ( $action == 'upscale' ) {
						$args['scale']  = max( 2, min( 4, (int)trx_addons_get_value_gp( 'scale' ) ) );
						if ( Utils::is_openai_model( $model ) ) {
							$answer['error'] = __( 'OpenAi API is not support upscaling!', 'trx_addons' );
						} else {
							if ( ! empty( $args['image'] ) ) {
								$response = $api->upscale( apply_filters( 'trx_addons_filter_ai_helper_upscale_args', $args, 'sc_igenerator' ) );
							} else {
								$answer['error'] = __( 'Error! The image is not uploaded.', 'trx_addons' );
							}
						}
					}
					$answer = Utils::parse_response( $response, $model, $answer, 'image' );
					if ( ! empty( $answer['data']['fetch_id'] ) ) {
						$answer['data']['fetch_number'] = $number;
						$answer['data']['fetch_time'] = apply_filters( 'trx_addons_filter_sc_igenerator_fetch_time', 8000 );
					}
					trx_addons_sc_igenerator_set_total_generated( $number, $suffix, $used_limits == 'user' ? $user_id : 0 );
				}
			} else {
				$answer['data']['demo'] = true;
				// Get demo images from the settings
				$images = array();
				if ( ! empty( $settings['demo_images'] ) && ! empty( $settings['demo_images'][0]['url'] ) ) {
					foreach ( $settings['demo_images'] as $img ) {
						$images[] = trx_addons_add_thumb_size( $img['url'], $settings['demo_thumb_size'] );
					}
				// Get demo images from the folder 'images'
				// } else {
				// 	$images = trx_addons_get_list_files( TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/images/' . trx_addons_esc( $size ) );
				}
				if ( $api->get_api_key() != '' && $demo ) {
					$msg = trx_addons_get_option( "ai_helper_sc_igenerator_limit_alert{$suffix}" );
					$answer['data']['message'] = ! empty( $msg )
													? $msg
													: apply_filters( "trx_addons_filter_sc_igenerator_limit_alert{$suffix}",
														'<h5 data-lp="' . ( $lpu ? 'lpu' . $generated : ( $lph ? 'lph' . $generated : ( $lpv ? 'lpv' : '' ) ) ) . '">' . __( 'Limits are reached!', 'trx_addons' ) . '</h5>'
														. '<p>' . __( 'The limit of the number of requests from a single visitor or the number of images that can be generated per hour has been reached.', 'trx_addons' ) . '</p>'
														. ( is_array( $images ) && count( $images ) > 0 ? __( 'Therefore, instead of generated images, you see demo samples.', 'trx_addons' ) : '' )
														. '<p>' . __( ' Please try again later.', 'trx_addons' ) . '</p>'
													);
				}
				if ( is_array( $images ) && count( $images ) > 0 ) {
					shuffle( $images );
					for ( $i = 0; $i < min( $number, count( $images ) ); $i++ ) {
						$answer['data']['images'][] = array(
							'url' => $images[ $i ]
						);
					}
				} else if ( $api->get_api_key() == '' )  {
					$answer['error'] = __( 'Error! API key is not specified.', 'trx_addons' );
				}
			}
		} else {
			$answer['error'] = __( 'Error! The prompt is empty.', 'trx_addons' );
		}

		// Return response to the AJAX handler
		trx_addons_ajax_response( apply_filters( 'trx_addons_filter_sc_igenerator_answer', $answer ) );
	}
}

// Callback function to return a generated image from the API server
if ( ! function_exists( 'trx_addons_sc_igenerator_download_image' ) ) {
	add_action( 'init', 'trx_addons_sc_igenerator_download_image' );
	function trx_addons_sc_igenerator_download_image() {
		if ( trx_addons_get_value_gp( 'action' ) != 'trx_addons_ai_helper_igenerator_download' ) {
			return;
		}
		$image = trx_addons_get_value_gp( 'image' );
		$image_url = Utils::get_data_from_cache( $image );
		$image_ext = trx_addons_get_file_ext( $image );
		$image_content = '';
		if ( ! empty( $image_url ) ) {
			$image_content = trx_addons_fgc( $image_url );
		}
		if ( empty( $image_content ) ) {
			header( 'HTTP/1.0 404 Not found' );
		} else {
			header( "Content-Type: image/{$image_ext}" );
			header( 'Content-Disposition: attachment; filename="' . $image . '"' );
			header( 'Content-Length: ' . strlen( $image_content ) );
			echo $image_content;
		}
		die();
	}
}

// Translate the prompt if it contains non-English characters
if ( ! function_exists( 'trx_addons_sc_igenerator_translate_prompt' ) ) {
	add_filter( 'trx_addons_filter_ai_helper_prompt', 'trx_addons_sc_igenerator_translate_prompt', 10, 3 );
	add_filter( 'trx_addons_filter_ai_helper_negative_prompt', 'trx_addons_sc_igenerator_translate_prompt', 10, 3 );
	function trx_addons_sc_igenerator_translate_prompt( $prompt, $args, $from = '' ) {
		// Translate only if this filter was called from the shortcode [trx_sc_igenerator] or from the Media Library
		// and only if the option 'Translate prompt' is enabled
		// and only if the prompt contains non-English characters
		// and only if the model is not 'openai/dall-e-3' or 'x-ai/*'
		if ( in_array( $from, array( 'sc_igenerator', 'media_library_generate_images', 'media_library_variations' ) )
			&& (int)trx_addons_get_option( 'ai_helper_sc_igenerator_translate_prompt' ) == 1
			&& trx_addons_str_is_not_english( $prompt )
		) {
			$model = ! empty( $args['model'] ) ? $args['model'] : Utils::get_default_image_model();
			if ( apply_filters( 'trx_addons_filter_sc_igenerator_auto_translate_prompt', ! Utils::is_openai_dall_e_3_model( $model ) && ! Utils::is_x_ai_model( $model ), $model ) ) {
				$prompt = Utils::translate( $prompt );
			}
		}
		return $prompt;
	}
}

// Add shortcodes
//----------------------------------------------------------------------------

// Add shortcodes to Elementor
if ( trx_addons_exists_elementor() && function_exists('trx_addons_elm_init') ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator-sc-elementor.php';
}

// Add shortcodes to Gutenberg
if ( trx_addons_exists_gutenberg() && function_exists( 'trx_addons_gutenberg_get_param_id' ) ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/igenerator-sc-gutenberg.php';
}
