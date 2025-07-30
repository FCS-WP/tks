<div class="front_page_section front_page_section_googlemap<?php
	$tint_scheme = tint_get_theme_option( 'front_page_googlemap_scheme' );
	if ( ! empty( $tint_scheme ) && ! tint_is_inherit( $tint_scheme ) ) {
		echo ' scheme_' . esc_attr( $tint_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( tint_get_theme_option( 'front_page_googlemap_paddings' ) );
	if ( tint_get_theme_option( 'front_page_googlemap_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$tint_css      = '';
		$tint_bg_image = tint_get_theme_option( 'front_page_googlemap_bg_image' );
		if ( ! empty( $tint_bg_image ) ) {
			$tint_css .= 'background-image: url(' . esc_url( tint_get_attachment_url( $tint_bg_image ) ) . ');';
		}
		if ( ! empty( $tint_css ) ) {
			echo ' style="' . esc_attr( $tint_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$tint_anchor_icon = tint_get_theme_option( 'front_page_googlemap_anchor_icon' );
	$tint_anchor_text = tint_get_theme_option( 'front_page_googlemap_anchor_text' );
if ( ( ! empty( $tint_anchor_icon ) || ! empty( $tint_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_googlemap"'
									. ( ! empty( $tint_anchor_icon ) ? ' icon="' . esc_attr( $tint_anchor_icon ) . '"' : '' )
									. ( ! empty( $tint_anchor_text ) ? ' title="' . esc_attr( $tint_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_googlemap_inner
		<?php
		$tint_layout = tint_get_theme_option( 'front_page_googlemap_layout' );
		echo ' front_page_section_layout_' . esc_attr( $tint_layout );
		if ( tint_get_theme_option( 'front_page_googlemap_fullheight' ) ) {
			echo ' tint-full-height sc_layouts_flex sc_layouts_columns_middle';
		}
		?>
		"
			<?php
			$tint_css      = '';
			$tint_bg_mask  = tint_get_theme_option( 'front_page_googlemap_bg_mask' );
			$tint_bg_color_type = tint_get_theme_option( 'front_page_googlemap_bg_color_type' );
			if ( 'custom' == $tint_bg_color_type ) {
				$tint_bg_color = tint_get_theme_option( 'front_page_googlemap_bg_color' );
			} elseif ( 'scheme_bg_color' == $tint_bg_color_type ) {
				$tint_bg_color = tint_get_scheme_color( 'bg_color', $tint_scheme );
			} else {
				$tint_bg_color = '';
			}
			if ( ! empty( $tint_bg_color ) && $tint_bg_mask > 0 ) {
				$tint_css .= 'background-color: ' . esc_attr(
					1 == $tint_bg_mask ? $tint_bg_color : tint_hex2rgba( $tint_bg_color, $tint_bg_mask )
				) . ';';
			}
			if ( ! empty( $tint_css ) ) {
				echo ' style="' . esc_attr( $tint_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap
		<?php
		if ( 'fullwidth' != $tint_layout ) {
			echo ' content_wrap';
		}
		?>
		">
			<?php
			// Content wrap with title and description
			$tint_caption     = tint_get_theme_option( 'front_page_googlemap_caption' );
			$tint_description = tint_get_theme_option( 'front_page_googlemap_description' );
			if ( ! empty( $tint_caption ) || ! empty( $tint_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'fullwidth' == $tint_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}
					// Caption
				if ( ! empty( $tint_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo ! empty( $tint_caption ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $tint_caption, 'tint_kses_content' );
					?>
					</h2>
					<?php
				}

					// Description (text)
				if ( ! empty( $tint_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo ! empty( $tint_description ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( wpautop( $tint_description ), 'tint_kses_content' );
					?>
					</div>
					<?php
				}
				if ( 'fullwidth' == $tint_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$tint_content = tint_get_theme_option( 'front_page_googlemap_content' );
			if ( ! empty( $tint_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'columns' == $tint_layout ) {
					?>
					<div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} elseif ( 'fullwidth' == $tint_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}

				?>
				<div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo ! empty( $tint_content ) ? 'filled' : 'empty'; ?>">
				<?php
					echo wp_kses( $tint_content, 'tint_kses_content' );
				?>
				</div>
				<?php

				if ( 'columns' == $tint_layout ) {
					?>
					</div><div class="column-2_3">
					<?php
				} elseif ( 'fullwidth' == $tint_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Widgets output
			?>
			<div class="front_page_section_output front_page_section_googlemap_output">
				<?php
				if ( is_active_sidebar( 'front_page_googlemap_widgets' ) ) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! tint_exists_trx_addons() ) {
						tint_customizer_need_trx_addons_message();
					} else {
						tint_customizer_need_widgets_message( 'front_page_googlemap_caption', 'ThemeREX Addons - Google map' );
					}
				}
				?>
			</div>
			<?php

			if ( 'columns' == $tint_layout && ( ! empty( $tint_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
