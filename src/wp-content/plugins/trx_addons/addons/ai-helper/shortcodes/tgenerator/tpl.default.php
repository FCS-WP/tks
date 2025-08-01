<?php
/**
 * The style "default" of the TGenerator
 *
 * @package ThemeREX Addons
 * @since v2.22.0
 */

 use TrxAddons\AiHelper\Lists;

$args = get_query_var('trx_addons_args_sc_tgenerator');
$styles_allowed = apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_tgenerator' );

$id_suffix = trx_addons_instance_id_suffix( 'sc_tgenerator' );

?><div <?php if ( ! empty( $args['id'] ) ) echo ' id="' . esc_attr( $args['id'] ) . '"'; ?> 
	class="sc_tgenerator sc_tgenerator_<?php
		echo esc_attr( $args['type'] );
		if ( $styles_allowed ) echo ' trx_addons_customizable';
		if ( ! empty( $args['class'] ) ) echo ' ' . esc_attr( $args['class'] );
		?>"<?php
	if ( ! empty( $args['css'] ) ) echo ' style="' . esc_attr( $args['css'] ) . '"';
	trx_addons_sc_show_attributes( 'sc_tgenerator', $args, 'sc_wrapper' );
	?>><?php

	trx_addons_sc_show_titles('sc_tgenerator', $args);

?><div class="sc_tgenerator_content sc_item_content"<?php trx_addons_sc_show_attributes( 'sc_tgenerator', $args, 'sc_items_wrapper' ); ?>>
		<div class="sc_tgenerator_form<?php
				if ( ! empty( $args['align'] ) && ! trx_addons_is_off( $args['align'] ) ) {
					echo ' ' . esc_attr( str_replace( array( 'flex-start', 'flex-end' ), array( 'left', 'right' ), trx_addons_get_responsive_classes( 'sc_tgenerator_form_align_', $args, 'align', '' ) ) );
				}
			?>"
			data-tgenerator-limit-exceed="<?php echo esc_attr( trx_addons_get_option( "ai_helper_sc_tgenerator_limit_alert" . ( ! empty( $args['premium'] ) ? '_premium' : '' ) ) ); ?>"
			data-tgenerator-settings="<?php
				echo esc_attr( trx_addons_encode_settings( array(
					'premium' => ! empty( $args['premium'] ) ? 1 : 0,
					'model' => ! empty( $args['model'] ) ? $args['model'] : '',
					'system_prompt' => ! empty( $args['system_prompt'] ) ? $args['system_prompt'] : '',
					'temperature' => ! empty( $args['temperature'] ) ? (float)$args['temperature'] : 0,
					'max_tokens' => ! empty( $args['max_tokens'] ) ? (int)$args['max_tokens'] : 0,
				) ) );
		?>">
			<div class="sc_tgenerator_form_inner"<?php
				// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
				if ( empty( $args['prompt_width_extra'] ) ) {
					if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
						echo ' style="width:' . esc_attr( $args['prompt_width'] ) . '%"';
					}
				}
			?>>
				<div class="sc_tgenerator_form_field sc_tgenerator_form_field_prompt">
					<div class="sc_tgenerator_form_field_inner">
						<input type="text"
							id="sc_tgenerator_form_field_prompt_text<?php echo esc_attr( $id_suffix ); ?>"
							class="sc_tgenerator_form_field_prompt_text"
							value="<?php echo esc_attr( $args['prompt'] ); ?>"
							placeholder="<?php
								if ( ! empty( $args['placeholder_text'] ) ) {
									echo esc_attr( $args['placeholder_text'] );
								} else {
									esc_attr_e('Describe what you want or select a "Text type" or a "Process text" below', 'trx_addons');
								}
							?>"
						>
						<a href="#" role="button" class="sc_tgenerator_form_field_prompt_button<?php
							if ( empty( $args['prompt'] ) ) {
								echo ' sc_tgenerator_form_field_prompt_button_disabled';
							}
							if ( $styles_allowed ) {
								echo ' sc_button_default';
							}
							echo ! empty( $args['button_image'] ) || ( ! empty( $args['button_icon'] ) && ! trx_addons_is_off( $args['button_icon'] ) )
								? ' sc_tgenerator_form_field_prompt_button_with_icon'
								: ' sc_tgenerator_form_field_prompt_button_without_icon';
						?>"><?php
							if ( ! empty( $args['button_image'] ) ) {
								$icon_type = trx_addons_get_file_ext( $args['button_image'] );
								if ( $icon_type == 'svg' ) {
									?><span class="sc_tgenerator_form_field_prompt_button_svg"><?php
										trx_addons_show_layout( trx_addons_get_svg_from_file( $args['button_image'] ) );
									?></span><?php
								} else {
									?><img src="<?php echo esc_url( trx_addons_get_attachment_url( $args['button_image'], apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size( 'tiny' ), 'sc_tgenerator_field_prompt_button' ) ) ); ?>"
											alt="<?php esc_attr_e( 'Generate icon', 'trx_addons' ); ?>"
											class="sc_tgenerator_form_field_prompt_button_image"><?php
								}
							} else if ( ! empty( $args['button_icon'] ) && ! trx_addons_is_off( $args['button_icon'] ) ) {
								?><span class="sc_tgenerator_form_field_prompt_button_icon <?php echo esc_attr( $args['button_icon'] ); ?>"></span><?php
							}
							if ( isset( $args['button_text'] ) && $args['button_text'] != '#' ) {
								?><span class="sc_tgenerator_form_field_prompt_button_text"><?php
									if ( ! empty( $args['button_text'] ) ) {
										echo esc_html( $args['button_text'] );
									} else {
										esc_html_e('Generate', 'trx_addons');
									}
								?></span><?php
							}
						?></a>
					</div>
				</div>
				<textarea class="sc_tgenerator_text sc_tgenerator_form_field_hidden" placeholder="<?php esc_attr_e( 'Text to process...', 'trx_addons' ); ?>"></textarea>
				<div class="sc_tgenerator_form_field sc_tgenerator_form_field_tags">
					<span class="sc_tgenerator_form_field_tags_label"><?php esc_html_e( 'Write a', 'trx_addons' ); ?></span>
					<?php trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_commands( 'write' ) ); ?>
					<span class="sc_tgenerator_form_field_tags_label"><?php esc_html_e( 'or', 'trx_addons' ); ?></span>
					<?php trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_commands( 'process' ) ); ?>
					<span class="sc_tgenerator_form_field_tags_label sc_tgenerator_form_field_hidden"><?php esc_html_e( 'to', 'trx_addons' ); ?></span>
					<?php
					trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_tones() );
					trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_languages() );
					?>
				</div><?php
				if ( ! empty( $args['show_limits'] ) ) {
					$premium = ! empty( $args['premium'] ) && (int)$args['premium'] == 1;
					$suffix = $premium ? '_premium' : '';
					$limits = (int)trx_addons_get_option( "ai_helper_sc_tgenerator_limits{$suffix}" ) > 0;
					if ( $limits ) {
						$generated = 0;
						if ( $premium ) {
							$user_id = get_current_user_id();
							$user_level = apply_filters( 'trx_addons_filter_sc_tgenerator_user_level', $user_id > 0 ? 'default' : '', $user_id );
							if ( ! empty( $user_level ) ) {
								$levels = trx_addons_get_option( "ai_helper_sc_tgenerator_levels_premium" );
								$level_idx = trx_addons_array_search( $levels, 'level', $user_level );
								$user_limit = $level_idx !== false ? $levels[ $level_idx ] : false;
								if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
									$generated = trx_addons_sc_tgenerator_get_total_generated( $user_limit['per'], $suffix, $user_id );
								}
							}
						}
						if ( ! $premium || empty( $user_level ) || ! isset( $user_limit['limit'] ) || trim( $user_limit['limit'] ) === '' ) {
							$generated = trx_addons_sc_tgenerator_get_total_generated( 'hour', $suffix );
							$user_limit = array(
								'limit' => (int)trx_addons_get_option( "ai_helper_sc_tgenerator_limit_per_hour{$suffix}" ),
								'requests' => (int)trx_addons_get_option( "ai_helper_sc_tgenerator_limit_per_visitor{$suffix}" ),
								'per' => 'hour'
							);
						}
						if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
							?><div class="sc_tgenerator_limits"<?php
								// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
								// if ( empty( $args['prompt_width_extra'] ) ) {
								// 	if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
								// 		echo ' style="max-width:' . esc_attr( $args['prompt_width'] ) . '%"';
								// 	}
								// }
							?>>
								<span class="sc_tgenerator_limits_total"><?php
									$periods = Lists::get_list_periods();
									echo wp_kses( sprintf(
														__( 'Limits%s: %s%s.', 'trx_addons' ),
														! empty( $periods[ $user_limit['per'] ] ) ? ' ' . sprintf( __( 'per %s', 'trx_addons' ), strtolower( $periods[ $user_limit['per'] ] ) ) : '',
														sprintf( __( '%s requests', 'trx_addons' ), '<span class="sc_tgenerator_limits_total_value">' . (int)$user_limit['limit'] . '</span>' ),
														! empty( $user_limit['requests'] ) ? ' ' . sprintf( __( ' for all visitors and up to %s requests from a single visitor', 'trx_addons' ), '<span class="sc_tgenerator_limits_total_requests">' . (int)$user_limit['requests'] . '</span>' ) : '',
													),
													'trx_addons_kses_content'
												);
								?></span>
								<span class="sc_tgenerator_limits_used"><?php
									echo wp_kses( sprintf(
														__( 'Used: %s requests%s.', 'trx_addons' ),
														'<span class="sc_tgenerator_limits_used_value">' . min( $generated, (int)$user_limit['limit'] )  . '</span>',
														! empty( $user_limit['requests'] ) ? ' ' . sprintf( __( 'from all visitors and %s requests from the current user', 'trx_addons' ), '<span class="sc_tgenerator_limits_used_requests">' . (int)trx_addons_get_value_gpc( 'trx_addons_ai_helper_tgenerator_count' ) . '</span>' ) : '',
													),
													'trx_addons_kses_content'
												);
								?></span>
							</div><?php
						}
					}
				}
				?><div class="sc_tgenerator_message"<?php
					// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
					if ( empty( $args['prompt_width_extra'] ) ) {
						if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
							echo ' style="max-width:' . esc_attr( $args['prompt_width'] ) . '%"';
						}
					}
				?>>
					<div class="sc_tgenerator_message_inner"></div>
					<a href="#" role="button" class="sc_tgenerator_message_close trx_addons_button_close" title="<?php esc_html_e( 'Close', 'trx_addons' ); ?>"><span class="trx_addons_button_close_icon"></span></a>
				</div>
			</div><?php

			// Loading placeholder
			trx_addons_loading_layout( array( 'hidden' => true ) );
			?>
		</div>
		<div class="sc_tgenerator_result">
			<div class="sc_tgenerator_result_label"><?php esc_html_e( 'Result:', 'trx_addons' ); ?></div>
			<div class="sc_tgenerator_result_content"></div>
			<div class="sc_tgenerator_result_copy"><?php
				trx_addons_show_layout( trx_addons_sc_button( apply_filters( 'trx_addons_filter_sc_tgenerator_result_copy_args', array( 'buttons' => array( array(
					"type" => "default",
					"size" => "small",
					"text_align" => "none",
					"icon" => ! empty( $args['result_copy_icon'] ) ? $args['result_copy_icon'] : 'trx_addons_icon-copy',
					"image" => ! empty( $args['result_copy_image'] ) ? $args['result_copy_image'] : '',
					"icon_position" => "left",
					"title" => __( 'Copy', 'trx_addons' ),
					"link" => '#'
				) ) ) ) ) );
			?></div>
		</div>
	</div>

	<?php trx_addons_sc_show_links('sc_tgenerator', $args); ?>

</div>