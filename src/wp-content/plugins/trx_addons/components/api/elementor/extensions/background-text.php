<?php
/**
 * Elementor extension: Background text and marquee for Sections
 *
 * @package ThemeREX Addons
 * @since v2.18.4
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


if ( ! function_exists( 'trx_addons_elm_add_bg_text' ) ) {
	add_action( 'elementor/element/before_section_start', 'trx_addons_elm_add_bg_text', 10, 3 );
	/**
	 * Add a group of parameters 'Background text' to the Elementor's sections to allow to add animated text as a section background
	 * 
	 * @hooked elementor/element/before_section_start
	 *
	 * @param object $element  Element object
	 * @param string $section_id  Section ID
	 * @param array $args  Section params
	 */
	function trx_addons_elm_add_bg_text( $element, $section_id, $args ) {

		if ( ! is_object( $element ) ) {
			return;
		}

		if ( in_array( $element->get_name(), array( 'section', 'container' ) ) && $section_id == 'section_border' ) {	//_section_responsive

			// Detect edit mode
			$is_edit_mode = trx_addons_elm_is_edit_mode();

			// Register controls
			$element->start_controls_section( 'section_trx_bg_text', array(
				'tab' => !empty($args['tab']) ? $args['tab'] : \Elementor\Controls_Manager::TAB_STYLE,
				'label' => __( 'Background Text', 'trx_addons' ) . trx_addons_get_theme_doc_link( '#elementor_extension_background_text' ),
			) );

			$element->add_control( 'bg_text', array(
				'type' => \Elementor\Controls_Manager::TEXTAREA, 	//WYSIWYG
				'label' => __( "Text", 'trx_addons' ),
				'description' => __( 'You can use the following macros to insert additional elements into your text:', 'trx_addons' )
								. '<br>'
								. __( '<b>[divider]</b> - adds the divider selected in the fields below', 'trx_addons' )
								. '<br>'
								. __( '<b>[icon name=icon-name]</b> - adds an icon with specified class name', 'trx_addons' )
								. '<br>'
								. __( '<b>[image id=image-id]</b> - adds an image with specified ID', 'trx_addons' )
								. '<br>'
								. __( 'You can specify additional attributes for <b>[image]</b> and <b>[icon]</b>:', 'trx_addons' )
								. '<br>'
								. __( '<b>size</b>=NNpx|em|rem|%', 'trx_addons' )
								. '<br>'
								. __( '<b>thumb</b>=thumb-name (only for image)', 'trx_addons' )
								. '<br>'
								. __( '<b>padding</b>="top right bottom left"', 'trx_addons' )
								. '<br>'
								. __( '<b>margin</b>="top right bottom left"', 'trx_addons' )
								. '<br>'
								. __( '<b>top | right | bottom | left</b>=NNpx|em|rem|%', 'trx_addons' )
								. '<br>'
								. __( '<b>style</b>="any CSS rules"', 'trx_addons' ),
				'label_block' => true,
				'default' => ''
			) );

			$element->add_control(
				'bg_text_color',
				array(
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => array(
						'{{WRAPPER}} .trx_addons_bg_text_char' => 'color: {{VALUE}};',
					)
				)
			);

			$element->add_control(
				'bg_text_clip',
				[
					'label' => esc_html__( 'Text as Clipping Mask', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'mask',
					'prefix_class' => 'trx_addons_bg_text_clip_',
					'render_type' => 'template',
					'selectors' => [
						'{{WRAPPER}} .trx_addons_bg_text_char,
						 {{WRAPPER}} .trx_addons_bg_text_char i,
						 {{WRAPPER}} .trx_addons_bg_text_char svg,
						 {{WRAPPER}} .trx_addons_bg_text_char img' => '-webkit-background-clip: text;background-clip: text;-webkit-text-fill-color: transparent;',
					],
					'condition' => [
						'bg_text_background_background' => ['classic','gradient']
					],
				],
			);
	
			$element->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'bg_text_background',
					'selector' => '{{WRAPPER}}:not(.trx_addons_bg_text_clip_mask) .trx_addons_bg_text,
									{{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char,
									{{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char i,
									{{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char svg,
									{{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char img'
				]
			);
	
			$element->add_control(
				'bg_text_gradient',
				[
					'label' => esc_html__( 'Advanced Gradient', 'trx_addons' ),
					'label_block' => true,
					'type' => 'trx_gradient',
					'default' => '',
					'render_type' => 'template',
					'picker_options' => [
						'modes' => ['linear-gradient', 'radial-gradient'],
					],
					'condition' => [
						'bg_text_background_background' => ['gradient']
					],
					'selectors' => [
						'{{WRAPPER}}:not(.trx_addons_bg_text_clip_mask) .trx_addons_bg_text,
						 {{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char,
						 {{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char i,
						 {{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char svg,
						 {{WRAPPER}}.trx_addons_bg_text_clip_mask .trx_addons_bg_text_char img' => 'background-image: {{VALUE}};',
					],
				]
			);

			if ( class_exists('\Elementor\Group_Control_Typography') ) {
				$element->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					array(
						'name' => 'bg_text_typography',
						'selector' => '{{WRAPPER}} .trx_addons_bg_text_char',
					)
				);
			}

			if ( class_exists('\Elementor\Group_Control_Text_Shadow') ) {
				$element->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					array(
						'name' => 'bg_text_shadow',
						'selector' => '{{WRAPPER}} .trx_addons_bg_text_char',
					)
				);
			}

			$element->add_control( 'bg_text_overlay', array(
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label' => __( "Overlay image", 'trx_addons' ),
				'default' => array(
					'url' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_overlay' => 'background-image: url({{URL}});',
				),
			) );

			$element->add_control( 'bg_text_overlay_position', array(
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Overlay position', 'trx_addons' ),
				'label_block' => false,
				'options' => ! $is_edit_mode ? array() : apply_filters( 'trx_addons_filter_bg_text_position', trx_addons_get_list_background_positions() ),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_overlay' => 'background-position: {{VALUE}};',
				),
				'condition' => array(
					'bg_text_overlay[url]!' => ''
				),
			) );

			$element->add_control( "bg_text_position_separator", array(
				'label' => __( 'Effect & Position', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			) );

			$element->add_control( 'bg_text_effect', array(
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Entrance effect', 'trx_addons' ),
				'label_block' => false,
				'options' => apply_filters( 'trx_addons_filter_bg_text_effects', array(
					'none'   => esc_html__( 'None', 'trx_addons' ),
					'rotate' => esc_html__( 'Rotate', 'trx_addons' ),
					'slide'  => esc_html__( 'Slide', 'trx_addons' ),
				) ),
				'default' => 'slide',
			) );

			$element->add_responsive_control( 'bg_text_top', array(
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => __( 'Top offset', 'trx_addons' ),
				'default' => array(
					'size' => '',
					'unit' => '%'
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => -200,
						'max' => 200
					),
					'em' => array(
						'min' => -100,
						'max' => 100
					),
					'%' => array(
						'min' => -100,
						'max' => 100
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_inner' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			) );

			$element->add_responsive_control( 'bg_text_left', array(
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => is_rtl() ? __( 'Right offset', 'trx_addons' ) : __( 'Left offset', 'trx_addons' ),
				'default' => array(
					'size' => '',
					'unit' => '%'
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000
					),
					'em' => array(
						'min' => 0,
						'max' => 100
					),
					'%' => array(
						'min' => 0,
						'max' => 100
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text:not(.trx_addons_marquee_reverse) .trx_addons_bg_text_inner' . ( is_rtl() ? ':last-child' : ':first-child' ) => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .trx_addons_bg_text.trx_addons_marquee_reverse .trx_addons_bg_text_inner' . ( is_rtl() ? ':first-child' : ':last-child' ) => is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
				),
			) );

			$element->add_control( 'bg_text_z_index', array(
				'label' => __( 'Z-index', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => array(
					'size' => '0',
					'unit' => 'px'
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100
					),
				),
				'size_units' => array( 'px' ),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text' => 'z-index: {{SIZE}};',
				),
			) );

			$element->add_control(
				"bg_text_marquee_separator",
				[
					'label' => __( 'Marquee', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$element->add_control( 'bg_text_marquee', array(
				'label' => __( 'Marquee speed', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => array(
					'size' => '',
					'unit' => 'px'
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 15
					),
				),
				'size_units' => array( 'px' )
			) );

			$element->add_control( 'bg_text_marquee_hover', array(
				'label' => __( 'Pause on hover', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_off' => __( 'Off', 'trx_addons' ),
				'label_on' => __( 'On', 'trx_addons' ),
				'default' => '',
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
					'bg_text_z_index!' => array( '', '0' ),
				),
			) );

			$element->add_control( 'bg_text_marquee_margin', array(
				'label' => __( 'Marquee margin', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => array(
					'size' => '50',
					'unit' => 'px'
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1
					),
					'%' => array(
						'min' => 0,
						'max' => 200
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text.trx_addons_marquee_wrap:not(.trx_addons_marquee_reverse) .trx_addons_marquee_element' => is_rtl() ? 'padding-left: {{SIZE}}{{UNIT}};' : 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .trx_addons_bg_text.trx_addons_marquee_wrap.trx_addons_marquee_reverse .trx_addons_marquee_element' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				),
			) );

			$element->add_control( 'bg_text_reverse', array(
				'label' => __( 'Reverse movement', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_off' => __( 'Off', 'trx_addons' ),
				'label_on' => __( 'On', 'trx_addons' ),
				'default' => '',
			) );

			$element->add_control( 'bg_text_accelerate', array(
				'label' => __( 'Accelerate on wheel', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_off' => __( 'Off', 'trx_addons' ),
				'label_on' => __( 'On', 'trx_addons' ),
				'default' => '',
			) );

			$element->add_control(
				"bg_text_delimiter_separator",
				[
					'label' => __( 'Divider', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$element->add_control( 'bg_text_delimiter_image', array(
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label' => __( "Image", 'trx_addons' ),
				'default' => array(
					'url' => '',
				),
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_overlay' => 'background-image: url({{URL}});',
				),
			) );

			$params = trx_addons_get_icon_param();
			$params = trx_addons_array_get_first_value( $params );
			unset( $params['name'] );
			$params['condition'] = array(
				'bg_text_marquee!' => array( '', '0' ),
				'bg_text_delimiter_image[url]' => '',
			);
			$element->add_control( 'bg_text_delimiter_icon', $params );

			$element->add_control( 'bg_text_delimiter_color', array(
				'label' => __( 'Color', 'trx_addons' ),
				'label_block' => false,
				'description' => __( 'Select color for the icon and SVG. Attention! SVG is not change a fill color in the preview mode.', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_delimiter' => 'color: {{VALUE}}',
					'{{WRAPPER}} .trx_addons_bg_text_delimiter svg' => 'fill: {{VALUE}}'
				),
//					'global' => array(
//						'active' => false,
//					),
			) );

			$element->add_responsive_control( 'bg_text_delimiter_size', array(
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => __( 'Size', 'trx_addons' ),
				'default' => array(
					'size' => '',
					'unit' => 'em'
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 300
					),
					'em' => array(
						'min' => 0.5,
						'max' => 10,
						'step' => 0.1
					),
					'%' => array(
						'min' => 1,
						'max' => 200
					),
				),
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_char.trx_addons_bg_text_delimiter' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			) );

			$element->add_responsive_control( 'bg_text_delimiter_margin', array(
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => __( 'Margins', 'trx_addons' ),
				'default' => array(
					'size' => '',
					'unit' => 'em'
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 300
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1
					),
					'%' => array(
						'min' => 0,
						'max' => 200
					),
				),
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_delimiter' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				),
			) );

			$element->add_responsive_control( 'bg_text_delimiter_top_offset', array(
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => __( 'Top offset', 'trx_addons' ),
				'default' => array(
					'size' => '',
					'unit' => 'px'
				),
				'size_units' => array( 'px', 'em', '%' ),
				'range' => array(
					'px' => array(
						'min' => -100,
						'max' => 100
					),
					'em' => array(
						'min' => -5,
						'max' => 5,
						'step' => 0.1
					),
					'%' => array(
						'min' => -100,
						'max' => 100
					),
				),
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_bg_text_delimiter' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			) );

			$element->add_control( 'bg_text_delimiter_rotation', array(
				'label' => __( 'Rotation', 'trx_addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_off' => __( 'Off', 'trx_addons' ),
				'label_on' => __( 'On', 'trx_addons' ),
				'default' => '',
				'condition' => array(
					'bg_text_marquee!' => array( '', '0' ),
				),
			) );

			$element->end_controls_section();
		}
	}
}

if ( ! function_exists( 'trx_addons_elm_add_bg_text_data' ) ) {
	// Before Elementor 2.1.0
	add_action( 'elementor/frontend/element/before_render',  'trx_addons_elm_add_bg_text_data', 10, 1 );
	// After Elementor 2.1.0
	add_action( 'elementor/frontend/section/before_render', 'trx_addons_elm_add_bg_text_data', 10, 1 );
	// After Elementor 3.16.0
	add_action( 'elementor/frontend/container/before_render', 'trx_addons_elm_add_bg_text_data', 10, 1 );
	/**
	 * Add data-parameter "data-bg-text" to the wrapper of the section with background text settings
	 * 
	 * @hooked elementor/frontend/element/before_render (old version, used before Elementor 2.1.0)
	 * @hooked elementor/frontend/section/before_render (new version, used after Elementor 2.1.0)
	 * @hooked elementor/frontend/container/before_render (new version, used after Elementor 3.16.0)
	 *
	 * @param object $element  Element object
	 */
	function trx_addons_elm_add_bg_text_data( $element ) {
		// Enable on sections and containers (except inner containers)
		// if ( is_object( $element ) && ( in_array( $element->get_name(), array( 'section' ) ) || ( in_array( $element->get_name(), array( 'container' ) ) && ! $element->get_data( 'isInner' ) ) ) ) {
		// Enable on sections and containers
		if ( is_object( $element ) && in_array( $element->get_name(), array( 'section', 'container' ) ) ) {
			//$settings = trx_addons_elm_prepare_global_params( $element->get_settings() );
			$bg_text = $element->get_settings( 'bg_text' );
			if ( ! empty( $bg_text ) ) {
				$settings = $element->get_settings();
				$element->add_render_attribute( '_wrapper', 'class', 'trx_addons_has_bg_text' );
				$element->add_render_attribute( '_wrapper', 'data-bg-text', json_encode( array(
					'bg_text'         => str_replace(
											array( '<span class="icon',                         '<img' ),
											array( '<span class="trx_addons_bg_text_item icon', '<img class="trx_addons_bg_text_item"' ),
											trx_addons_prepare_macros( $settings['bg_text'] )
										),
					'bg_text_effect'  => $settings['bg_text_effect'] ?? 'slide',
					'bg_text_overlay' => $settings['bg_text_overlay'] ?? [ 'url' => '' ],
					'bg_text_left'    => $settings['bg_text_left'] ?? [ 'size' => '', 'unit' => '%' ],
					'bg_text_top'     => $settings['bg_text_top'] ?? [ 'size' => '', 'unit' => '%' ],
					'bg_text_z_index' => $settings['bg_text_z_index'] ?? [ 'size' => '0', 'unit' => 'px' ],
					'bg_text_marquee' => $settings['bg_text_marquee'] ?? [ 'size' => '', 'unit' => 'px' ],
					'bg_text_marquee_hover'  => $settings['bg_text_marquee_hover'] ?? '',
					'bg_text_marquee_margin' => $settings['bg_text_marquee_margin'] ?? [ 'size' => '50', 'unit' => 'px' ],
					'bg_text_reverse' => ! empty( $settings['bg_text_reverse'] ) ? 1 : 0,
					'bg_text_accelerate' => ! empty( $settings['bg_text_accelerate'] ) ? 1 : 0,
					'bg_text_delimiter_icon' => ! trx_addons_is_off( $settings['bg_text_delimiter_icon'] ?? '' ) ? $settings['bg_text_delimiter_icon'] : '',
					'bg_text_delimiter_image' => ! empty( $settings['bg_text_delimiter_image']['url'] ) ? $settings['bg_text_delimiter_image']['url'] : '',
					'bg_text_delimiter_svg' => ! empty( $settings['bg_text_delimiter_image']['url'] )
												&& strpos( $settings['bg_text_delimiter_image']['url'], '.svg' ) !== false
													? trx_addons_get_svg_from_file( $settings['bg_text_delimiter_image']['url'] )
													: '',
					'bg_text_delimiter_rotation' => ! empty( $settings['bg_text_delimiter_rotation'] ) ? 1 : 0,
					)
				) );
			}
		}
	}
}
