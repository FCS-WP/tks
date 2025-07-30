<div class="front_page_section front_page_section_subscribe<?php
	$tint_scheme = tint_get_theme_option( 'front_page_subscribe_scheme' );
	if ( ! empty( $tint_scheme ) && ! tint_is_inherit( $tint_scheme ) ) {
		echo ' scheme_' . esc_attr( $tint_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( tint_get_theme_option( 'front_page_subscribe_paddings' ) );
	if ( tint_get_theme_option( 'front_page_subscribe_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$tint_css      = '';
		$tint_bg_image = tint_get_theme_option( 'front_page_subscribe_bg_image' );
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
	$tint_anchor_icon = tint_get_theme_option( 'front_page_subscribe_anchor_icon' );
	$tint_anchor_text = tint_get_theme_option( 'front_page_subscribe_anchor_text' );
if ( ( ! empty( $tint_anchor_icon ) || ! empty( $tint_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_subscribe"'
									. ( ! empty( $tint_anchor_icon ) ? ' icon="' . esc_attr( $tint_anchor_icon ) . '"' : '' )
									. ( ! empty( $tint_anchor_text ) ? ' title="' . esc_attr( $tint_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_subscribe_inner
	<?php
	if ( tint_get_theme_option( 'front_page_subscribe_fullheight' ) ) {
		echo ' tint-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$tint_css      = '';
			$tint_bg_mask  = tint_get_theme_option( 'front_page_subscribe_bg_mask' );
			$tint_bg_color_type = tint_get_theme_option( 'front_page_subscribe_bg_color_type' );
			if ( 'custom' == $tint_bg_color_type ) {
				$tint_bg_color = tint_get_theme_option( 'front_page_subscribe_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_subscribe_content_wrap content_wrap">
			<?php
			// Caption
			$tint_caption = tint_get_theme_option( 'front_page_subscribe_caption' );
			if ( ! empty( $tint_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_subscribe_caption front_page_block_<?php echo ! empty( $tint_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $tint_caption, 'tint_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$tint_description = tint_get_theme_option( 'front_page_subscribe_description' );
			if ( ! empty( $tint_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_subscribe_description front_page_block_<?php echo ! empty( $tint_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $tint_description ), 'tint_kses_content' ); ?></div>
				<?php
			}

			// Content
			$tint_sc = tint_get_theme_option( 'front_page_subscribe_shortcode' );
			if ( ! empty( $tint_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_subscribe_output front_page_block_<?php echo ! empty( $tint_sc ) ? 'filled' : 'empty'; ?>">
				<?php
					tint_show_layout( do_shortcode( $tint_sc ) );
				?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
