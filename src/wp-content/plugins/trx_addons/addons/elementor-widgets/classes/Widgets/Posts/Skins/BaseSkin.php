<?php
namespace TrxAddons\ElementorWidgets\Widgets\Posts\Skins;

use TrxAddons\ElementorWidgets\Utils as TrxAddonsUtils;
use TrxAddons\ElementorWidgets\BaseWidget;
use TrxAddons\ElementorWidgets\Widgets\Posts\Posts;
use TrxAddons\ElementorWidgets\Controls\Transition\TransitionControl;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Base
 */
abstract class BaseSkin extends Elementor_Skin_Base {

	var $parent = null;

	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore 
		add_action( 'elementor/element/trx_elm_posts/section_skin_field/before_section_end', array( $this, 'register_layout_controls' ) );
		add_action( 'elementor/element/trx_elm_posts/section_query/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/trx_elm_posts/section_query/after_section_end', array( $this, 'register_style_sections' ) );
	}

	public function register_style_sections( BaseWidget $widget ) {
		$this->parent = $widget;

		$this->register_style_controls();
	}

	public function register_controls( BaseWidget $widget ) {
		$this->parent = $widget;

		$this->register_slider_controls();
		// $this->register_filter_section_controls();
		// $this->register_search_controls();
		$this->register_terms_controls();
		$this->register_image_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_controls();
		$this->register_button_controls();
		$this->register_pagination_controls();
		$this->register_content_order();
	}

	public function register_style_controls() {
		$this->register_style_layout_controls();
		$this->register_style_box_controls();
		$this->register_style_content_controls();
		$this->register_style_image_controls();
		$this->register_style_terms_controls();
		$this->register_style_title_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_meta_controls();
		$this->register_style_button_controls();
		$this->register_style_pagination_controls();
		$this->register_style_loader_controls();
		$this->register_style_arrows_controls();
		$this->register_style_dots_controls();
	}

	public function register_layout_controls( BaseWidget $widget ) {
		$this->parent = $widget;

		$this->register_layout_content_controls();
	}

	/**
	 * Register Layout Controls
	 */
	public function register_layout_content_controls() {

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'trx_addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'grid'     => __( 'Grid', 'trx_addons' ),
					'masonry'  => __( 'Masonry', 'trx_addons' ),
					'carousel' => __( 'Carousel', 'trx_addons' ),
				),
				'default' => 'grid',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => __( 'Columns', 'trx_addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'min'                => 1,
				'max'                => 8,
				'step'               => 1,
				'prefix_class'       => 'elementor-grid%s-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'equal_height',
			array(
				'label'        => __( 'Equal Height', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'prefix_class' => 'trx-addons-posts-equal-height-',
				'render_type'  => 'template',
				'condition'    => array(
					$this->get_control_id( 'layout!' ) => 'masonry',
					'_skin!' => 'list',
				),
			)
		);
	}

	public function register_slider_controls() {

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => __( 'Carousel Options', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides to Scroll', 'trx_addons' ),
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'trx_addons' ),
				'options'            => $slides_per_view,
				'default'            => '1',
				'tablet_default'     => '1',
				'mobile_default'     => '1',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'animation_speed',
			array(
				'label'              => __( 'Animation Speed', 'trx_addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 600,
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'              => __( 'Arrows', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'              => __( 'Dots', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'trx_addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3000,
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' )   => 'carousel',
					$this->get_control_id( 'autoplay' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' )   => 'carousel',
					$this->get_control_id( 'autoplay' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'              => __( 'Infinite Loop', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'adaptive_height',
			array(
				'label'              => __( 'Adaptive Height', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'center_mode',
			[
				'label'                 => __( 'Center Mode', 'trx_addons' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'trx_addons' ),
				'label_off'             => __( 'No', 'trx_addons' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
			]
		);

		// $this->add_control(
		// 	'direction',
		// 	array(
		// 		'label'              => __( 'Direction', 'trx_addons' ),
		// 		'type'               => Controls_Manager::CHOOSE,
		// 		'label_block'        => false,
		// 		'toggle'             => false,
		// 		'options'            => array(
		// 			'left'  => array(
		// 				'title' => __( 'Left', 'trx_addons' ),
		// 				'icon'  => 'eicon-h-align-left',
		// 			),
		// 			'right' => array(
		// 				'title' => __( 'Right', 'trx_addons' ),
		// 				'icon'  => 'eicon-h-align-right',
		// 			),
		// 		),
		// 		'default'            => 'left',
		// 		'frontend_available' => true,
		// 		'condition'          => array(
		// 			$this->get_control_id( 'layout' ) => 'carousel',
		// 		),
		// 	)
		// );

		$this->end_controls_section();
	}

	public function register_filter_section_controls() {

		$this->start_controls_section(
			'section_filters',
			array(
				'label'     => __( 'Filters', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'post_type!'                       => 'related',
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => __( 'Show Filters', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'post_type!'                       => 'related',
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Search Form
	 */
	protected function register_search_controls() {

		$this->start_controls_section(
			'section_search_form',
			array(
				'label'     => __( 'Search Form', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'show_ajax_search_form',
			array(
				'label'              => __( 'Show Search Form', 'trx_addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => __( 'Yes', 'trx_addons' ),
				'label_off'          => __( 'No', 'trx_addons' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_terms_controls() {
		/**
		 * Content Tab: Post Terms
		 */
		$this->start_controls_section(
			'section_terms',
			array(
				'label'     => __( 'Post Terms', 'trx_addons' ),
			)
		);

		$this->add_control(
			'post_terms',
			array(
				'label'        => __( 'Show Post Terms', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$post_types = TrxAddonsUtils::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = TrxAddonsUtils::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				$related_tax = array();

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax[ $index ] = $tax->label;
				}

				// Add control for all taxonomies.
				$this->add_control(
					'tax_badge_' . $post_type_slug,
					array(
						'label'     => __( 'Select Taxonomy', 'trx_addons' ),
						'type'      => Controls_Manager::SELECT2,
						'options'   => $related_tax,
						'multiple'  => true,
						'default'   => array_keys( $related_tax )[0],
						'condition' => array(
							'post_type' => $post_type_slug,
							$this->get_control_id( 'post_terms' ) => 'yes',
						),
					)
				);
			}
		}

		$this->add_control(
			'max_terms',
			array(
				'label'       => __( 'Max Terms to Show', 'trx_addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'post_taxonomy_link',
			array(
				'label'        => __( 'Link to Taxonomy', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_terms_separator',
			array(
				'label'     => __( 'Terms Separator', 'trx_addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ', ',
				// 'selectors' => array(
				// 	'{{WRAPPER}} .trx-addons-posts-item-terms > .trx-addons-posts-item-term:not(:last-child):after' => 'content: "{{UNIT}}";',
				// ),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Image
	 */
	protected function register_image_controls() {

		$this->start_controls_section(
			'section_image',
			array(
				'label'     => __( 'Image', 'trx_addons' ),
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => __( 'Show Image', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbnail_link',
			array(
				'label'        => __( 'Link to Post', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_link_target',
			array(
				'label' => __( 'Open in a New Tab', 'trx_addons' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_link' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_custom_height',
			array(
				'label'        => __( 'Custom Height', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'ratio',
				'prefix_class' => 'trx-addons-posts-thumbnail-',
				'condition'    => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_ratio',
			array(
				'label'          => __( 'Image Ratio', 'trx_addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 1,
				),
				'tablet_default' => array(
					'size' => '',
				),
				'mobile_default' => array(
					'size' => 1,
				),
				'range'          => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .trx-addons-posts-container .trx-addons-posts-item-thumbnail-wrap' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				),
				'condition'      => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_custom_height!' ) => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'label'     => __( 'Image Size', 'trx_addons' ),
				'default'   => 'large',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_location',
			array(
				'label'     => __( 'Image Location', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inside'  => __( 'Inside Content Container', 'trx_addons' ),
					'outside' => __( 'Outside Content Container', 'trx_addons' ),
				),
				'default'   => 'outside',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					'_skin!' => 'list',
				),
			)
		);

		$this->add_control(
			'fallback_image',
			array(
				'label'       => __( 'Fallback Image', 'trx_addons' ),
				'description' => __( 'If a featured image is not available in post, it will display the first image from the post or default image placeholder or a custom image. You can choose None to do not display the fallback image.', 'trx_addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'none'    => __( 'None', 'trx_addons' ),
					'default' => __( 'Default', 'trx_addons' ),
					'custom'  => __( 'Custom', 'trx_addons' ),
				),
				'default'     => 'default',
				'condition'   => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'fallback_image_custom',
			array(
				'label'     => __( 'Fallback Image Custom', 'trx_addons' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'fallback_image' ) => 'custom',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Title
	 */
	protected function register_title_controls() {
		$this->start_controls_section(
			'section_post_title',
			array(
				'label'     => __( 'Title', 'trx_addons' ),
			)
		);

		$this->add_control(
			'post_title',
			array(
				'label'        => __( 'Post Title', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'post_title_link',
			array(
				'label'        => __( 'Link to Post', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_title_link_target',
			array(
				'label' => __( 'Open in a New Tab', 'trx_addons' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_link' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'     => __( 'HTML Tag', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h5',
				'options'   => trx_addons_get_list_sc_title_tags( '', true ),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_title_separator',
			array(
				'label'        => __( 'Title Separator', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Excerpt
	 */
	protected function register_excerpt_controls() {
		$this->start_controls_section(
			'section_post_excerpt',
			array(
				'label'     => __( 'Content', 'trx_addons' ),
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => __( 'Show Content', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'     => __( 'Content Type', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'excerpt',
				'options'   => array(
					'excerpt' => __( 'Excerpt', 'trx_addons' ),
					'content' => __( 'Limited Content', 'trx_addons' ),
					'full'    => __( 'Full Content', 'trx_addons' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 0,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
					$this->get_control_id( 'content_type' ) => 'excerpt',
				),
			)
		);

		$this->add_control(
			'content_length',
			array(
				'label'       => __( 'Content Length', 'trx_addons' ),
				'title'       => __( 'Words', 'trx_addons' ),
				'description' => __( 'Number of words to be displayed from the post content', 'trx_addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 30,
				'min'         => 0,
				'step'        => 1,
				'condition'   => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
					$this->get_control_id( 'content_type' ) => 'content',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Meta
	 */
	protected function register_meta_controls() {
		$this->start_controls_section(
			'section_post_meta',
			array(
				'label'     => __( 'Meta', 'trx_addons' ),
			)
		);

		$this->add_control(
			'post_meta',
			array(
				'label'        => __( 'Post Meta', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'post_meta_separator',
			array(
				'label'     => __( 'Post Meta Separator', 'trx_addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '&#8226;',
				// 'selectors' => array(
				// 	'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-meta-separator:not(:last-child):after' => 'content: "{{UNIT}}";',
				// ),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_author',
			array(
				'label'     => __( 'Post Author', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_author',
			array(
				'label'        => __( 'Show Post Author', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_link',
			array(
				'label'        => __( 'Link to Author', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_author_icon',
			array(
				'label'     => __( 'Author Icon', 'trx_addons' ),
				'type'      => Controls_Manager::ICONS,
				'fa4compatibility' => 'author_icon',
				'default'          => array(
					'value'   => 'fas fa-user-edit',
					'library' => 'fa-solid',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_prefix',
			array(
				'label'     => __( 'Prefix', 'trx_addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_date',
			array(
				'label'     => __( 'Post Date', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => __( 'Show Post Date', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_link',
			array(
				'label'        => __( 'Link to Post', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => __( 'Date Format', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''         => __( 'Published Date', 'trx_addons' ),
					'ago'      => __( 'Time Ago', 'trx_addons' ),
					'modified' => __( 'Last Modified Date', 'trx_addons' ),
					'custom'   => __( 'Custom Format', 'trx_addons' ),
					'key'      => __( 'Custom Meta Key', 'trx_addons' ),
				),
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_custom_format',
			array(
				'label'       => __( 'Custom Format', 'trx_addons' ),
				'description' => sprintf( __( 'Refer to PHP date formats <a href="%s">here</a>', 'trx_addons' ), 'https://wordpress.org/support/article/formatting-date-and-time/' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_date' )   => 'yes',
					$this->get_control_id( 'date_format' ) => 'custom',
				),
			)
		);

		$this->add_control(
			'date_meta_key',
			array(
				'label'       => __( 'Custom Meta Key', 'trx_addons' ),
				'description' => __( 'Display the post date stored in custom meta key.', 'trx_addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_date' )   => 'yes',
					$this->get_control_id( 'date_format' ) => 'key',
				),
			)
		);

		$this->add_control(
			'select_date_icon',
			array(
				'label'     => __( 'Date Icon', 'trx_addons' ),
				'type'      => Controls_Manager::ICONS,
				'fa4compatibility' => 'date_icon',
				'default'          => array(
					'value'   => 'far fa-calendar-check',
					'library' => 'fa-regular',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_prefix',
			array(
				'label'     => __( 'Prefix', 'trx_addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_comments',
			array(
				'label'     => __( 'Post Comments', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_comments',
			array(
				'label'        => __( 'Show Post Comments', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_empty_comments',
			array(
				'label'        => __( 'Hide if Empty', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_comments_icon',
			array(
				'label'     => __( 'Comments Icon', 'trx_addons' ),
				'type'      => Controls_Manager::ICONS,
				'fa4compatibility' => 'comments_icon',
				'default'          => array(
					'value'   => 'fas fa-comments',
					'library' => 'fa-solid',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_button_controls() {

		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Read More Button', 'trx_addons' ),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => __( 'Show Button', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'trx_addons' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Read More', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_button_icon',
			array(
				'label'            => __( 'Button Icon', 'trx_addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => $this->get_control_id( 'button_icon' ),
				'condition'        => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'     => __( 'Icon Position', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'before' => __( 'Before', 'trx_addons' ),
					'after'  => __( 'After', 'trx_addons' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				),
			)
		);

		$this->add_control(
			'button_alignment',
			[
				'label'       => esc_html__( 'Automatically Align Buttons', 'trx_addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'trx_addons' ),
				'label_off'   => esc_html__( 'No', 'trx_addons' ),
				'default'     => '',
				'render_type' => 'template',
				'condition'   => [
					$this->get_control_id( 'layout!' ) => 'masonry',
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_link_target',
			array(
				'label'     => __( 'Open in a New Tab', 'trx_addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			array(
				'label'     => __( 'Pagination', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'     => __( 'Pagination', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'                  => __( 'None', 'trx_addons' ),
					'numbers'               => __( 'Numbers', 'trx_addons' ),
					'numbers_and_prev_next' => __( 'Numbers', 'trx_addons' ) . ' + ' . __( 'Previous/Next', 'trx_addons' ),
					'load_more'             => __( 'Load More Button', 'trx_addons' ),
					'infinite'              => __( 'Infinite', 'trx_addons' ),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_position',
			array(
				'label'     => __( 'Pagination Position', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => array(
					'top'        => __( 'Top', 'trx_addons' ),
					'bottom'     => __( 'Bottom', 'trx_addons' ),
					'top-bottom' => __( 'Top', 'trx_addons' ) . ' + ' . __( 'Bottom', 'trx_addons' ),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_ajax',
			array(
				'label'     => __( 'Ajax Pagination', 'trx_addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => __( 'Shorten', 'trx_addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'     => __( 'Page Limit', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
					$this->get_control_id( 'pagination_numbers_shorten' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'     => __( 'Previous Label', 'trx_addons' ),
				'default'   => __( '&laquo; Previous', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'numbers_and_prev_next',
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'     => __( 'Next Label', 'trx_addons' ),
				'default'   => __( 'Next &raquo;', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'numbers_and_prev_next',
				),
			)
		);

		$this->add_control(
			'heading_load_more',
			array(
				'label'     => __( 'Load More', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				),
			)
		);

		$this->add_control(
			'pagination_load_more_label',
			array(
				'label'     => __( 'Label', 'trx_addons' ),
				'default'   => __( 'LOAD MORE', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				),
			)
		);

		$this->add_control(
			'select_pagination_load_more_icon',
			array(
				'label'            => __( 'Icon', 'textdomain' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'pagination_load_more_icon',
				'recommended'      => array(
					'fa-regular' => array(
						'arrow-alt-circle-down',
					),
					'fa-solid'   => array(
						'arrow-circle-down',
						'long-arrow-alt-down',
						'arrow-down',
					),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				),
			)
		);

		$this->add_control(
			'pagination_load_more_icon_position',
			array(
				'label'     => __( 'Icon Position', 'trx_addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'before' => __( 'Before', 'trx_addons' ),
					'after'  => __( 'After', 'trx_addons' ),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
					$this->get_control_id( 'select_pagination_load_more_icon[value]!' ) => '',
				),
			)
		);

		$this->add_control(
			'pagination_align',
			array(
				'label'     => __( 'Alignment', 'trx_addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'trx_addons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'trx_addons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'trx_addons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'infinite_disable_editor',
			array(
				'label'        => __( 'Disable in the Editor', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'infinite',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Order
	 *
	 * @since 1.4.11.0
	 * @access protected
	 */
	protected function register_content_order() {

		$this->start_controls_section(
			'section_order',
			array(
				'label'     => __( 'Order', 'trx_addons' ),
			)
		);

		$this->add_control(
			'content_parts_order_heading',
			array(
				'label' => __( 'Content Parts', 'trx_addons' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'thumbnail_order',
			array(
				'label'     => __( 'Thumbnail', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_location' ) => 'inside',
				),
			)
		);

		$this->add_control(
			'terms_order',
			array(
				'label'     => __( 'Terms', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_order',
			array(
				'label'     => __( 'Title', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_order',
			array(
				'label'     => __( 'Meta', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_order',
			array(
				'label'     => __( 'Excerpt', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		// $this->add_control(
		// 	'button_order',
		// 	array(
		// 		'label'     => __( 'Read More Button', 'trx_addons' ),
		// 		'type'      => Controls_Manager::NUMBER,
		// 		'default'   => 1,
		// 		'min'       => 1,
		// 		'max'       => 10,
		// 		'step'      => 1,
		// 		'condition' => array(
		// 			$this->get_control_id( 'show_button' ) => 'yes',
		// 		),
		// 	)
		// );

		$this->add_control(
			'meta_order_heading',
			array(
				'label'     => __( 'Post Meta', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_order',
			array(
				'label'     => __( 'Author', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_order',
			array(
				'label'     => __( 'Date', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'comments_order',
			array(
				'label'     => __( 'Comments', 'trx_addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => __( 'Layout', 'trx_addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'posts_horizontal_spacing',
			array(
				'label'     => __( 'Column Spacing', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'   => array(
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-wrap' => 'padding-left: calc( {{SIZE}}{{UNIT}}/2 ); padding-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}}:not(.trx-addons-posts-with-box-shadow-yes):not(.trx-addons-posts-box-shadow-position-) .trx-addons-posts-carousel,
					 {{WRAPPER}} .trx-addons-posts-grid' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_responsive_control(
			'posts_vertical_spacing',
			array(
				'label'     => __( 'Row Spacing', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'   => array(
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-elementor-grid .trx-addons-grid-item-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'parts_gap',
			array(
				'label'     => __( 'Elements Gap', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'   => array(
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-skin-event .trx-addons-posts-item-content' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'_skin' => array( 'event' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Box
	 */
	protected function register_style_box_controls() {
		$this->start_controls_section(
			'section_post_box_style',
			array(
				'label' => __( 'Box', 'trx_addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'post_box_vertical_align',
			[
				'label'                 => __( 'Vertical Alignment', 'trx_addons' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'trx_addons' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Center', 'trx_addons' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'trx_addons' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .trx-addons-posts-item' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'condition'             => [
					$this->get_control_id( 'equal_height' ) => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_post_box_style' );

		$this->start_controls_tab(
			'tab_post_box_normal',
			array(
				'label' => __( 'Normal', 'trx_addons' ),
			)
		);

		$this->add_control(
			'post_box_bg',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'post_box_border',
				'label'       => __( 'Border', 'trx_addons' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .trx-addons-posts-item',
			)
		);

		$this->add_responsive_control(
			'post_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_box_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .trx-addons-posts-item',
				'fields_options' => [
					'box_shadow_type' => [
						// 'render_type' => 'template',
						'prefix_class' => 'trx-addons-posts-with-box-shadow-',
					],
					'box_shadow_position' => [
						// 'render_type' => 'template',
						'prefix_class' => 'trx-addons-posts-box-shadow-position-',
					],
					'box_shadow' => [
						// 'render_type' => 'template',
						'selectors' => [
							'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
							'{{WRAPPER}} .swiper-slide' => 'padding-top: {{BLUR}}px; padding-bottom: {{BLUR}}px;',
						],
					],
				],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_box_hover',
			array(
				'label' => __( 'Hover', 'trx_addons' ),
			)
		);

		$this->add_control(
			'post_box_bg_hover',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-post:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_box_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-post:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow_hover',
				'selector' => '{{WRAPPER}} .trx-addons-post:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content Container
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_post_content_style',
			array(
				'label' => __( 'Content Container', 'trx_addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'post_content_align',
			array(
				'label'       => __( 'Alignment', 'trx_addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'trx_addons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'trx_addons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'trx_addons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .trx-addons-posts-item-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_content_bg',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_content_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Image
	 */
	protected function register_style_image_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => __( 'Image', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'     => __( 'Spacing', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'   => array(
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					'_skin!' => 'list',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'     => __( 'Spacing', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'   => array(
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-skin-list .trx-addons-posts-item' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'_skin' => 'list',
				),
			)
		);

		$this->add_responsive_control(
			'img_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-thumbnail, {{WRAPPER}} .trx-addons-posts-item-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab(
			'normal',
			array(
				'label'     => __( 'Normal', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .trx-addons-posts-item-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_filters',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			TransitionControl::get_type(),
			array(
				'name'      => 'image_transition',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-thumbnail img',
				'separator' => '',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label'     => __( 'Hover', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow_hover',
				'selector' => '{{WRAPPER}} .trx-addons-posts-item:hover .trx-addons-posts-item-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_hover_filters',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item:hover .trx-addons-posts-item-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Animation', 'trx_addons' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
				'prefix_class' => 'with-elementor-animation-',
				'render_type' => 'template',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'hover_animation_on_widget',
			array(
				'label' => __( 'Hover on Widget', 'trx_addons' ),
				'type'  => Controls_Manager::SWITCHER,
				'return_value' => 'widget',
				'render_type' => 'template',
				'condition' => array(
					$this->get_control_id( 'hover_animation!' ) => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Title
	 */
	protected function register_style_title_controls() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => __( 'Title', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-title, {{WRAPPER}} .trx-addons-posts-item-title a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Hover Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-title a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_link' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'trx_addons' ),
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-title',
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'    => array(
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_separator_heading',
			array(
				'label'     => __( 'Separator', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'title_separator_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-separator',
				'exclude'   => array(
					'image',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_height',
			array(
				'label'      => __( 'Separator Height', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-separator' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_width',
			array(
				'label'      => __( 'Separator Width', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'range'      => array(
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-separator' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_margin',
			array(
				'label'      => __( 'Margin', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '15',
					'left'   => '0',
					'unit'   => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-separator-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Post Terms
	 */
	protected function register_style_terms_controls() {
		$this->start_controls_section(
			'section_terms_style',
			array(
				'label'     => __( 'Post Terms', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'terms_typography',
				'label'     => __( 'Typography', 'trx_addons' ),
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-terms-wrap',
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-terms-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_gap',
			array(
				'label'      => __( 'Terms Gap', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				// 'default'    => array(
				// 	'size' => 5,
				// ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					// '{{WRAPPER}} .trx-addons-posts-item-terms .trx-addons-posts-item-term:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .trx-addons-posts-item-terms .trx-addons-terms-separator' => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'terms_style_tabs' );

		$this->start_controls_tab(
			'terms_style_normal',
			array(
				'label' => __( 'Normal', 'trx_addons' ),
			)
		);

		$this->add_control(
			'terms_bg_color',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-term' => 'background: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_text_color',
			array(
				'label'     => __( 'Text Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-terms' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-term' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-term' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'terms_style_hover',
			array(
				'label' => __( 'Hover', 'trx_addons' ),
			)
		);

		$this->add_control(
			'terms_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-term:hover' => 'background: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-terms a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_excerpt_controls() {
		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'     => __( 'Content', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-excerpt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'label'     => __( 'Typography', 'trx_addons' ),
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-excerpt',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Meta
	 */
	protected function register_style_meta_controls() {

		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'     => __( 'Meta', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'label'     => __( 'Typography', 'trx_addons' ),
				'selector'  => '{{WRAPPER}} .trx-addons-posts-item-meta',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_text_color',
			array(
				'label'     => __( 'Text Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_icons_color',
			array(
				'label'     => __( 'Icons Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color',
			array(
				'label'     => __( 'Links Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color_hover',
			array(
				'label'     => __( 'Links Hover Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_icons_spacing',
			array(
				'label'      => __( 'Meta Icons Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'default'    => array(
					'top'    => '0',
					'right'  => '5',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_separator_size',
			array(
				'label'      => __( 'Meta Separator Size', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-meta-separator' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_separator_offset',
			array(
				'label'      => __( 'Meta Separator Offset', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -50,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-meta-separator' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_items_spacing',
			array(
				'label'      => __( 'Meta Items Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 10,
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta .trx-addons-meta-separator:not(:last-child)' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Style Tab: Button
	 */
	protected function register_style_button_controls() {

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Read More Button', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_h_alignment',
			array(
				'label'     => __( 'Alignment', 'trx_addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'trx_addons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'trx_addons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'trx_addons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => '',
				'selectors_dictionary'  => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'align-self: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'trx_addons' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .trx-addons-posts-button',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'trx_addons' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .trx-addons-posts-button',
				'condition'   => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-button',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_button_icon_style_heading',
			array(
				'label'     => __( 'Button Icon', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'trx_addons' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .trx-addons-posts-button .trx-addons-button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'trx_addons' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-button:hover',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => __( 'Arrows', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_arrow',
			array(
				'label'                  => __( 'Choose Arrow', 'trx_addons' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'arrow',
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-regular' => array(
						'arrow-alt-circle-right',
						'caret-square-right',
						'hand-point-right',
					),
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'chevron-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
						'caret-square-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'toggle-right',
						'hand-point-right',
					),
				),
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_position',
			array(
				'label'      => __( 'Arrows Position', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .trx-addons-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_show_on_hover',
			array(
				'label'        => __( 'Show on hover', 'trx_addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'trx_addons' ),
				'label_off'    => __( 'No', 'trx_addons' ),
				'return_value' => 'yes',
				'prefix_class' => 'trx-addons-slider-arrows-show-on-hover-',
				'render_type'  => 'template',
				'condition'    => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_hide_on',
			array(
				'label'        => __( 'Hide on', 'trx_addons' ),
				'label_block'  => false,
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					''       => __( 'No hide', 'trx_addons' ),
					'tablet' => __( 'Tablet', 'trx_addons' ),
					'mobile' => __( 'Mobile', 'trx_addons' ),
				),
				'default'      => '',
				'prefix_class' => 'trx-addons-slider-arrows-hide-on-',
				'render_type'  => 'template',
				'condition'    => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label'     => __( 'Normal', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-slider-arrow' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-slider-arrow' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => __( 'Border', 'trx_addons' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .trx-addons-slider-arrow',
				'condition'   => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label'     => __( 'Hover', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-slider-arrow:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-slider-arrow:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-slider-arrow:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_style_dots_controls() {
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => __( 'Dots', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_position',
			[
				'label'                 => __( 'Position', 'trx_addons' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'inside'     => __( 'Inside', 'trx_addons' ),
					'outside'    => __( 'Outside', 'trx_addons' ),
				],
				'default'               => 'outside',
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			]
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => __( 'Size', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => __( 'Spacing', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => __( 'Normal', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => __( 'Border', 'trx_addons' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
				'condition'   => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => __( 'Margin', 'trx_addons' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_active',
			array(
				'label'     => __( 'Active', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_active',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_border_color_active',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => __( 'Hover', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_style_pagination_controls() {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_margin_top',
			array(
				'label'     => __( 'Gap between Posts & Pagination', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination-top .trx-addons-posts-pagination' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .trx-addons-posts-pagination-bottom .trx-addons-posts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_pagination' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			array(
				'label'     => __( 'Normal', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'pagination_link_border_normal',
				'label'       => __( 'Border', 'trx_addons' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a',
				'condition'   => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_link_border_radius',
			array(
				'label'      => __( 'Border Radius', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_link_padding',
			array(
				'label'      => __( 'Padding', 'trx_addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-pagination .page-numbers, {{WRAPPER}} .trx-addons-posts-pagination a',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'heading_load_more_icon',
			array(
				'label'     => __( 'Load More Icon', 'trx_addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
					$this->get_control_id( 'select_pagination_load_more_icon[value]!' ) => '',
				),
			)
		);

		$this->add_responsive_control(
			'load_more_icon_margin',
			[
				'label'                 => __( 'Margin', 'trx_addons' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
					$this->get_control_id( 'select_pagination_load_more_icon[value]!' ) => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .trx-addons-load_more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			array(
				'label'     => __( 'Hover', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination a:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_hover',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-pagination a:hover',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			array(
				'label'     => __( 'Active', 'trx_addons' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
					$this->get_control_id( 'pagination_type!' ) => 'load_more',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_active',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_active',
			array(
				'label'     => __( 'Border Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_active',
				'selector'  => '{{WRAPPER}} .trx-addons-posts-pagination .page-numbers.current',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'     => __( 'Space Between', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .trx-addons-posts-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .trx-addons-posts-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .trx-addons-posts-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .trx-addons-posts-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
					$this->get_control_id( 'pagination_type!' ) => 'load_more',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_style_loader_controls() {
		$this->start_controls_section(
			'section_loader_style',
			array(
				'label'     => __( 'AJAX Loader', 'trx_addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'loader_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_loading' => '--trx-addons-loading-overlay: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					// $this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_control(
			'loader_color',
			array(
				'label'     => __( 'Color', 'trx_addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .trx_addons_loading' => '--trx-addons-loading-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					// $this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_responsive_control(
			'loader_size',
			array(
				'label'      => __( 'Size', 'trx_addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => array(
					'{{WRAPPER}} .trx_addons_loading' => '--trx-addons-loading-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					// $this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->end_controls_section();
	}

	public function get_avatar_size( $size = 'sm' ) {

		if ( 'xs' === $size ) {
			$value = 30;
		} elseif ( 'sm' === $size ) {
			$value = 60;
		} elseif ( 'md' === $size ) {
			$value = 120;
		} elseif ( 'lg' === $size ) {
			$value = 180;
		} elseif ( 'xl' === $size ) {
			$value = 240;
		} else {
			$value = 60;
		}

		return $value;
	}

	/**
	 * Get Masonry classes array.
	 *
	 * Returns the Masonry classes array.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_masonry_classes() {
		$settings = $this->parent->get_settings_for_display();

		$post_type = $settings['post_type'];

		$filter_by = $this->get_instance_value( 'tax_' . $post_type . '_filter' );

		$taxonomies = wp_get_post_terms( get_the_ID(), $filter_by );
		$class      = array();

		if ( count( $taxonomies ) > 0 ) {

			foreach ( $taxonomies as $taxonomy ) {

				if ( is_object( $taxonomy ) ) {

					$class[] = $taxonomy->slug;
				}
			}
		}

		return implode( ' ', $class );
	}

	/**
	 * Get post author
	 *
	 * @access protected
	 */
	protected function get_post_author( $author_link = '' ) {
		if ( 'yes' === $author_link ) {
			return get_the_author_posts_link();
		} else {
			return get_the_author();
		}
	}

	/**
	 * Get post author
	 *
	 * @access protected
	 */
	protected function get_post_comments() {
		/**
		 * Comments Filter
		 *
		 * Filters the output for comments
		 *
		 * @since 1.4.11.0
		 * @param string    $comments       The original text
		 * @param int       get_the_id()    The post ID
		 */
		$comments = get_comments_number_text();
		$comments = apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_comments', $comments, get_the_ID() );
		return $comments;
	}

	/**
	 * Get post date
	 *
	 * @access protected
	 */
	protected function get_post_date( $date_link = '' ) {
		$date_type = $this->get_instance_value( 'date_format' );
		$date_format = $this->get_instance_value( 'date_format_select' );
		$date_custom_format = $this->get_instance_value( 'date_custom_format' );
		$date = '';

		if ( 'custom' === $date_format && $date_custom_format ) {
			$date_format = $date_custom_format;
		}

		if ( 'ago' === $date_type ) {
			$date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'trx_addons' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		} elseif ( 'modified' === $date_type ) {
			$date = get_the_modified_date( $date_format, get_the_ID() );
		} elseif ( 'key' === $date_type ) {
			$date_meta_key = $this->get_instance_value( 'date_meta_key' );
			if ( $date_meta_key ) {
				$date = get_post_meta( get_the_ID(), $date_meta_key, 'true' );
			}
		} else {
			$date = get_the_date( $date_format );
		}

		if ( '' === $date ) {
			$date = get_the_date( $date_format );
		}

		return apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_date', $date, get_the_ID() );
	}

	/**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function get_post_thumbnail() {
		$settings              = $this->parent->get_settings_for_display();
		$image                 = $this->get_instance_value( 'show_thumbnail' );
		$fallback_image        = $this->get_instance_value( 'fallback_image' );
		$fallback_image_custom = $this->get_instance_value( 'fallback_image_custom' );
		$post_type_name        = $settings['post_type'];

		if ( 'yes' !== $image ) {
			return;
		}

		if ( has_post_thumbnail() || 'attachment' === $post_type_name ) {

			if ( 'attachment' === $post_type_name ) {
				$image_id = get_the_ID();
			} else {
				$image_id = get_post_thumbnail_id( get_the_ID() );
			}

			$setting_key              = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = array(
				'id' => $image_id,
			);
			$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		} elseif ( 'default' === $fallback_image ) {

			$thumbnail_url  = Utils::get_placeholder_image_src();
			$thumbnail_html = '<img src="' . $thumbnail_url . '"/>';

		} elseif ( 'custom' === $fallback_image ) {

			$custom_image_id          = $fallback_image_custom['id'];
			$setting_key              = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = array(
				'id' => $custom_image_id,
			);
			$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		}

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		return $thumbnail_html;
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @access public
	 */
	public function excerpt_length_filter() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	/**
	 * Get post excerpt with limited words.
	 *
	 * Returns the excerpt with limit.
	 *
	 * @access public
	 */
	public function post_excerpt() {
		$limit = (int) $this->excerpt_length_filter();
		$excerpt = explode( ' ', get_the_excerpt(), $limit + 1 );
		$excerpt_more = apply_filters( 'excerpt_more', 20 );
		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$excerpt = implode( ' ', $excerpt ) . $excerpt_more;
		} else {
			$excerpt = implode( ' ', $excerpt );
		}
		$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );
		return $excerpt;
	}

	/**
	 * Get post excerpt end text.
	 *
	 * Returns the string to append to post excerpt.
	 *
	 * @param string $more returns string.
	 *
	 * @access public
	 */
	public function excerpt_more_filter( $more ) {
		return ' ...';
	}

	public function get_posts_outer_wrap_classes() {
		$layout          = $this->get_instance_value( 'layout' );
		$pagination_type = $this->get_instance_value( 'pagination_type' );
		$dots_position   = $this->get_instance_value( 'dots_position' );

		$classes = array(
			'trx-addons-posts-container',
		);

		if ( 'carousel' === $layout ) {
			$classes[] = 'swiper-container-wrap swiper';

			if ( $dots_position ) {
				$classes[] = 'swiper-container-wrap-dots-' . $dots_position;
			}
		}

		if ( 'infinite' === $pagination_type ) {
			$classes[] = 'trx-addons-posts-infinite-scroll';
		}

		return apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_outer_wrap_classes', $classes );
	}

	public function get_posts_wrap_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array(
			'trx-addons-posts',
			'trx-addons-posts-skin-' . $this->get_id(),
		);

		if ( 'yes' === $this->get_instance_value( 'button_alignment' ) ) {
			$classes[] = 'trx-addons-posts-align-buttons';
		}

		if ( 'carousel' === $layout ) {
			$classes[] = 'trx-addons-posts-carousel';
			$classes[] = 'trx-addons-swiper-slider';
			$classes[] = 'swiper-container';
		} else {
			$classes[] = 'trx-addons-elementor-grid';
			$classes[] = 'trx-addons-posts-grid';
		}

		return apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_wrap_classes', $classes );
	}

	public function get_item_wrap_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array( 'trx-addons-posts-item-wrap' );

		if ( 'carousel' === $layout ) {
			$classes[] = 'trx-addons-carousel-item-wrap swiper-slide';
		} else {
			$classes[] = 'trx-addons-grid-item-wrap';
		}

		return implode( ' ', $classes );
	}

	public function get_item_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array();

		$classes[] = 'trx-addons-posts-item';

		if ( 'carousel' === $layout ) {
			$classes[] = 'trx-addons-carousel-item';
		} else {
			$classes[] = 'trx-addons-grid-item';
		}

		$hover_on_widget = $this->get_instance_value( 'hover_animation_on_widget' );
		if ( ! empty( $hover_on_widget ) ) {
			$classes[] = 'with-hover-on-widget';
		}

		return implode( ' ', $classes );
	}

	public function get_ordered_items( $items ) {

		if ( ! $items ) {
			return;
		}

		$ordered_items = array();

		foreach ( $items as $item ) {
			$order = $this->get_instance_value( $item . '_order' );

			$order = ( $order ) ? $order : 1;

			$ordered_items[ $item ] = $order;
		}

		asort( $ordered_items );

		return $ordered_items;
	}

	/**
	 * Carousel Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$skin            = $this->get_id();
		$center_mode     = $this->get_instance_value( 'center_mode' );
		$autoplay        = $this->get_instance_value( 'autoplay' );
		$autoplay_speed  = $this->get_instance_value( 'autoplay_speed' );
		$arrows          = $this->get_instance_value( 'arrows' );
		$arrow           = $this->get_instance_value( 'arrow' );
		$select_arrow    = $this->get_instance_value( 'select_arrow' );
		$dots            = $this->get_instance_value( 'dots' );
		$animation_speed = $this->get_instance_value( 'animation_speed' );
		$infinite_loop   = $this->get_instance_value( 'infinite_loop' );
		$pause_on_hover  = $this->get_instance_value( 'pause_on_hover' );
		$adaptive_height = $this->get_instance_value( 'adaptive_height' );
//		$direction       = $this->get_instance_value( 'direction' );

		$slides_to_show          = ( $this->get_instance_value( 'columns' ) !== '' ) ? absint( $this->get_instance_value( 'columns' ) ) : 3;
		$slides_to_show_tablet   = ( $this->get_instance_value( 'columns_tablet' ) !== '' ) ? absint( $this->get_instance_value( 'columns_tablet' ) ) : 2;
		$slides_to_show_mobile   = ( $this->get_instance_value( 'columns_mobile' ) !== '' ) ? absint( $this->get_instance_value( 'columns_mobile' ) ) : 2;
		$slides_to_scroll        = ( $this->get_instance_value( 'slides_to_scroll' ) !== '' ) ? min( $slides_to_show, absint( $this->get_instance_value( 'slides_to_scroll' ) ) ) : 1;
		$slides_to_scroll_tablet = ( $this->get_instance_value( 'slides_to_scroll_tablet' ) !== '' ) ? min( $slides_to_show_tablet, absint( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) ) : 1;
		$slides_to_scroll_mobile = ( $this->get_instance_value( 'slides_to_scroll_mobile' ) !== '' ) ? min( $slides_to_show_mobile, absint( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) ) : 1;

		// if ( 'right' === $direction ) {
		// 	$slider_options['rtl'] = true;
		// }

		$slider_options = [
			'direction'             => 'horizontal',
			'speed'                 => ( $animation_speed ) ? absint( $animation_speed ) : 600,
			'slidesPerView'         => $slides_to_show,
			'slidesPerGroup'        => $slides_to_scroll,
			'autoHeight'            => ( 'yes' === $adaptive_height ),
			'watchSlidesVisibility' => true,
			'centeredSlides'        => ( 'yes' === $center_mode ),
			'loop'                  => ( 'yes' === $infinite_loop ),
		];

		if ( 'yes' === $autoplay ) {
			$autoplay_speed = ( $autoplay_speed ) ? $autoplay_speed : 999999;
		} else {
			$autoplay_speed = 999999;
		}

		$slider_options['autoplay'] = [
			'delay'                => $autoplay_speed,
			'pauseOnHover'         => ( 'yes' === $pause_on_hover ),
			'disableOnInteraction' => ( 'yes' === $pause_on_hover ),
		];

		if ( 'yes' === $dots ) {
			$slider_options['pagination'] = [
				'el'                 => '.swiper-pagination-' . esc_attr( $this->parent->get_id() ),
				'clickable'          => true,
			];
		}

		if ( 'yes' === $arrows ) {
			$slider_options['navigation'] = [
				'nextEl'             => '.swiper-button-next-' . esc_attr( $this->parent->get_id() ),
				'prevEl'             => '.swiper-button-prev-' . esc_attr( $this->parent->get_id() ),
			];
		}

		$elementor_bp_lg = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md = get_option( 'elementor_viewport_md' );
		$bp_desktop      = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet       = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile       = 320;

		$items        = ( isset( $settings['items']['size'] ) && '' !== $settings['items']['size'] ) ? absint( $settings['items']['size'] ) : 3;
		$items_tablet = ( isset( $settings['items_tablet']['size'] ) && '' !== $settings['items_tablet']['size'] ) ? absint( $settings['items_tablet']['size'] ) : 2;
		$items_mobile = ( isset( $settings['items_mobile']['size'] ) && '' !== $settings['items_mobile']['size'] ) ? absint( $settings['items_mobile']['size'] ) : 1;

		$margin        = ( isset( $settings['margin']['size'] ) && '' !== $settings['margin']['size'] ) ? absint( $settings['margin']['size'] ) : 10;
		$margin_tablet = ( isset( $settings['margin_tablet']['size'] ) && '' !== $settings['margin_tablet']['size'] ) ? absint( $settings['margin_tablet']['size'] ) : 10;
		$margin_mobile = ( isset( $settings['margin_mobile']['size'] ) && '' !== $settings['margin_mobile']['size'] ) ? absint( $settings['margin_mobile']['size'] ) : 10;

		$slider_options['breakpoints'] = [
			$bp_desktop => [
				'slidesPerView'  => $slides_to_show,
				'slidesPerGroup' => $slides_to_scroll,
				//'spaceBetween' => $margin,
			],
			$bp_tablet  => [
				'slidesPerView'  => $slides_to_show_tablet,
				'slidesPerGroup' => $slides_to_scroll_tablet,
				//'spaceBetween' => $margin_tablet,
			],
			$bp_mobile  => [
				'slidesPerView'  => $slides_to_show_mobile,
				'slidesPerGroup' => $slides_to_scroll_mobile,
				//'spaceBetween' => $margin_mobile,
			],
		];

		$this->parent->add_render_attribute(
			'posts-wrap',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
	}

	
	/*-----------------------------------------------------------------------------------*/
	/*	RENDER
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Render posts grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render() {
		$settings = $this->parent->get_settings_for_display();

		$query_type          = $settings['query_type'];
		$layout              = $this->get_instance_value( 'layout' );
		$pagination_type     = $this->get_instance_value( 'pagination_type' );
		$pagination_position = $this->get_instance_value( 'pagination_position' );
		$equal_height        = $this->get_instance_value( 'equal_height' );
		// $direction           = $this->get_instance_value( 'direction' );
		$skin                = $this->get_id();
		$posts_outer_wrap    = $this->get_posts_outer_wrap_classes();
		$posts_wrap          = $this->get_posts_wrap_classes();
		$page_id             = '';
		if ( null !== \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		$this->parent->add_render_attribute( 'posts-container', 'class', $posts_outer_wrap );

		$this->parent->add_render_attribute( 'posts-wrap', 'class', $posts_wrap );

		if ( 'carousel' === $layout ) {
			trx_addons_enqueue_slider();
			if ( 'yes' === $equal_height ) {
				$this->parent->add_render_attribute( 'posts-wrap', 'data-equal-height', 'yes' );
			}
			// if ( 'right' === $direction ) {
			// 	$this->parent->add_render_attribute( 'posts-wrap', 'dir', 'rtl' );
			// }
		}

		$this->parent->add_render_attribute(
			'posts-wrap',
			array(
				'data-query-type' => $query_type,
				'data-layout'     => $layout,
				'data-page'       => $page_id,
				'data-skin'       => $skin,
			)
		);

		$this->parent->add_render_attribute( 'post-categories', 'class', 'trx-addons-posts-item-categories' );

		$filter   = '';
		$taxonomy = '';

		$this->parent->query_posts( $filter, $taxonomy );
		$query = $this->parent->get_query();

		if ( 'carousel' === $layout ) {
			$this->slider_settings();
		}

		if ( ! $query->found_posts ) {
			?>
			<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-container' ) ); ?>>
			<?php
			$this->render_search();
			?>
			</div>
			<?php
			return;
		}

		do_action( 'trx_addons_action_elementor_widgets_posts_before_posts_outer_wrap', $settings );
		?>
		<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-container' ) ); ?>>
			<?php
			do_action( 'trx_addons_action_elementor_widgets_posts_before_posts_wrap', $settings );

			$i = 1;

			$total_pages = $query->max_num_pages;
			?>

			<?php if ( 'carousel' !== $layout ) { ?>
				<?php if ( ( 'numbers' === $pagination_type || 'numbers_and_prev_next' === $pagination_type ) && ( 'top' === $pagination_position || 'top-bottom' === $pagination_position ) ) { ?>
				<div class="trx-addons-posts-pagination-wrap trx-addons-posts-pagination-top">
					<?php
						$this->render_pagination();
					?>
				</div>
				<?php } ?>
			<?php } ?>

			<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-wrap' ) ); ?>>
				<?php if ( 'carousel' === $layout ) {
					?><div class="swiper-wrapper"><?php
				}
				$i = 1;

				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();

						$this->render_post_body();

						$i++;

					endwhile;
				endif;
				wp_reset_postdata();
				
				if ( 'carousel' === $layout ) {
					?></div><?php
				}
				?>
			</div>
			<?php
			$this->render_dots();

			$this->render_arrows();

			do_action( 'trx_addons_action_elementor_widgets_posts_after_posts_wrap', $settings );

			if ( 'load_more' === $pagination_type || 'infinite' === $pagination_type ) {
				trx_addons_loading_layout( array( 'hidden' => true ) );
			}

			if ( 'load_more' === $pagination_type || 'infinite' === $pagination_type ) {
				$pagination_bottom = true;
			} elseif ( ( 'numbers' === $pagination_type || 'numbers_and_prev_next' === $pagination_type ) && ( '' === $pagination_position || 'bottom' === $pagination_position || 'top-bottom' === $pagination_position ) ) {
				$pagination_bottom = true;
			} else {
				$pagination_bottom = false;
			}

			if ( 'carousel' !== $layout ) {
				if ( $pagination_bottom ) {
					?>
					<div class="trx-addons-posts-pagination-wrap trx-addons-posts-pagination-bottom" data-pagination_type="<?php echo esc_attr( $pagination_type ); ?>">
						<?php $this->render_pagination(); ?>
					</div>
					<?php
				}
			}
			?>
		</div>

		<?php
		do_action( 'trx_addons_action_elementor_widgets_posts_after_posts_outer_wrap', $settings );
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_body() {
		$settings = $this->parent->get_settings_for_display();

		$post_terms         = $this->get_instance_value( 'post_terms' );
		$post_meta          = $this->get_instance_value( 'post_meta' );
		$thumbnail_location = $this->get_instance_value( 'thumbnail_location' );

		do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_wrap', get_the_ID(), $settings );
		?>
		<div <?php post_class( $this->get_item_wrap_classes() ); ?>>
			<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post', get_the_ID(), $settings ); ?>
			<div class="<?php echo esc_attr( $this->get_item_classes() ); ?>">
				<?php
				if ( 'outside' === $thumbnail_location ) {
					$this->render_post_thumbnail();
				}
				?>

				<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_content', get_the_ID(), $settings ); ?>

				<div class="trx-addons-posts-item-content-wrap">
					<div class="trx-addons-posts-item-content"><?php
						$content_parts = $this->get_ordered_items( Posts::get_post_parts() );

						foreach ( $content_parts as $part => $index ) {
							if ( 'thumbnail' === $part ) {
								if ( 'inside' === $thumbnail_location ) {
									$this->render_post_thumbnail();
								}
							} else if ( 'terms' === $part ) {
								$this->render_terms();
							} else if ( 'title' === $part ) {
								$this->render_post_title();
							} else if ( 'meta' === $part ) {
								$this->render_post_meta();
							} else if ( 'excerpt' === $part ) {
								$this->render_excerpt();
							}
						}
					?></div><?php
					$this->render_button();
				?></div><?php
				
				do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_content', get_the_ID(), $settings );
			?></div><?php
			
			do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post', get_the_ID(), $settings );
		?></div><?php

		do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_wrap', get_the_ID(), $settings );
	}

	/**
	 * Render Search Form HTML.
	 *
	 * Returns the Search Form HTML.
	 *
	 * @access public
	 */
	public function render_search() {
		$settings = $this->parent->get_settings_for_display();
		?>
		<div class="trx-addons-posts-empty">
			<?php if ( $settings['nothing_found_message'] ) { ?>
				<p><?php echo wp_kses_post( $settings['nothing_found_message'] ); ?></p>
			<?php } ?>

			<?php if ( 'yes' === $settings['show_search_form'] ) { ?>
				<?php get_search_form(); ?>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render post terms output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_terms() {
		$settings   = $this->parent->get_settings_for_display();
		$post_terms = $this->get_instance_value( 'post_terms' );
		$query_type = $settings['query_type'];

		if ( 'yes' !== $post_terms ) {
			return;
		}

		$post_type = $settings['post_type'];

		if ( 'related' === $settings['post_type'] || 'main' === $query_type ) {
			$post_type = get_post_type();
		}

		$taxonomies = $this->get_instance_value( 'tax_badge_' . $post_type );

		$terms = array();

		if ( is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$terms_tax = wp_get_post_terms( get_the_ID(), $taxonomy );
				$terms     = array_merge( $terms, $terms_tax );
			}
		} else {
			$terms = wp_get_post_terms( get_the_ID(), $taxonomies );
		}

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$max_terms = $this->get_instance_value( 'max_terms' );

		if ( $max_terms ) {
			$terms = array_slice( $terms, 0, $max_terms );
		}

		$terms = apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_terms', $terms );

		$link_terms = $this->get_instance_value( 'post_taxonomy_link' );

		if ( 'yes' === $link_terms ) {
			$format = '<span class="trx-addons-posts-item-term"><a href="%2$s">%1$s</a></span>';
		} else {
			$format = '<span class="trx-addons-posts-item-term">%1$s</span>';
		}
		?>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_terms', get_the_ID(), $settings ); ?>
		<div class="trx-addons-posts-item-terms-wrap">
			<span class="trx-addons-posts-item-terms">
				<?php
				$i = 0;
				foreach ( $terms as $term ) {
					$i++;
					printf( wp_kses_post( $format ), esc_attr( $term->name ), esc_url( get_term_link( (int) $term->term_id ) ) );
					if ( $i < count( $terms ) ) {
						echo '<span class="trx-addons-terms-separator">' . esc_html( $this->get_instance_value( 'post_terms_separator' ) ) . '</span>';
					}
				}
				do_action( 'trx_addons_action_elementor_widgets_posts_single_post_terms', get_the_ID(), $settings );
				?>
			</span>
		</div>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_terms', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_meta() {
		$settings  = $this->parent->get_settings_for_display();
		$post_meta = $this->get_instance_value( 'post_meta' );

		if ( 'yes' === $post_meta ) {
			?>
			<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_meta', get_the_ID(), $settings ); ?>
			<div class="trx-addons-posts-item-meta">
				<?php
				$meta_items = $this->get_ordered_items( Posts::get_meta_items() );

				foreach ( $meta_items as $meta_item => $index ) {
					if ( 'author' === $meta_item ) {
						// Post Author
						$this->render_meta_item( 'author' );
					}

					if ( 'date' === $meta_item ) {
						// Post Date
						$this->render_meta_item( 'date' );
					}

					if ( 'comments' === $meta_item ) {
						// Post Comments
						$this->render_meta_item( 'comments' );
					}
				}
				?>
			</div>
			<?php
			do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_meta', get_the_ID(), $settings );
		}
	}

	/**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_meta_item( $item_type = '' ) {
		$skin     = $this->get_id();
		$settings = $this->parent->get_settings_for_display();

		if ( '' === $item_type ) {
			return;
		}

		$show_item           = $this->get_instance_value( 'show_' . $item_type );
		$item_link           = $this->get_instance_value( $item_type . '_link' );
		$item_prefix         = $this->get_instance_value( $item_type . '_prefix' );
		$hide_empty_comments = $this->get_instance_value( 'hide_empty_comments' );

		if ( 'yes' !== $show_item ) {
			return;
		}

		if ( 'comments' === $item_type && 'yes' === $hide_empty_comments && '0' === get_comments_number() ) {
			return;
		}

		$item_icon        = $this->get_instance_value( $item_type . '_icon' );
		$select_item_icon = $this->get_instance_value( 'select_' . $item_type . '_icon' );

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_' . $item_type . '_icon' ] );
		$is_new   = empty( $settings[ $skin . '_' . $item_type . '_icon' ] ) && Icons_Manager::is_migration_allowed();

		do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_' . $item_type, get_the_ID(), $settings );
		
		?><span class="trx-addons-posts-item-<?php echo esc_attr( $item_type ); ?>"><?php
			if ( $item_icon || $select_item_icon ) {
				?><span class="trx-addons-icon"><?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $select_item_icon, array( 'class' => 'trx-addons-meta-icon', 'aria-hidden' => 'true' ) );
					} else {
						?><span class="trx-addons-meta-icon <?php echo esc_attr( $item_icon ); ?>" aria-hidden="true"></span><?php
					}
				?></span><?php
			}

			if ( $item_prefix ) {
				?><span class="trx-addons-meta-prefix"><?php
					echo esc_attr( $item_prefix );
				?></span><?php
			}
			?><span class="trx-addons-meta-text"><?php
				if ( 'author' === $item_type ) {
					echo wp_kses_post( $this->get_post_author( $item_link ) );
				} elseif ( 'date' === $item_type ) {
					if ( TrxAddonsUtils::is_tribe_events_post( get_the_ID() ) && function_exists( 'tribe_get_start_date' ) ) {
						$date_format = $this->get_instance_value( 'date_format_select' );
						$date_custom_format = $this->get_instance_value( 'date_custom_format' );
						if ( 'custom' === $date_format && $date_custom_format ) {
							$date_format = $date_custom_format;
						} else if ( empty( $date_format ) ) {
							$date_format = get_option( 'date_format' );
						}
						$post_date = tribe_get_start_date( get_the_ID(), $date_format );
					} else {
						$post_date = $this->get_post_date();
					}

					if ( 'yes' === $item_link ) {
						echo '<a href="' . esc_url( get_permalink() ) . '">' . wp_kses_post( $post_date ) . '</a>';
					} else {
						echo wp_kses_post( $post_date );
					}
				} elseif ( 'comments' === $item_type ) {
					echo wp_kses_post( $this->get_post_comments() );
				}
			?></span><?php
		?></span><?php
		?><span class="trx-addons-meta-separator"><?php echo esc_html( $this->get_instance_value( 'post_meta_separator' ) ); ?></span><?php
		
		do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_' . $item_type, get_the_ID(), $settings );
	}

	/**
	 * Render post title output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_title() {
		$settings = $this->parent->get_settings_for_display();

		$show_post_title      = $this->get_instance_value( 'post_title' );
		$title_tag            = TrxAddonsUtils::validate_html_tag( $this->get_instance_value( 'title_html_tag' ) );
		$title_link           = $this->get_instance_value( 'post_title_link' );
		$title_link_key       = 'title-link-' . get_the_ID();
		$title_link_target    = $this->get_instance_value( 'post_title_link_target' );
		$post_title_separator = $this->get_instance_value( 'post_title_separator' );

		if ( 'yes' !== $show_post_title ) {
			return;
		}

		$post_title = get_the_title();
		/**
		 * Post Title Filter
		 *
		 * Filters post title
		 *
		 * @since 1.4.11.0
		 * @param string    $post_title     The original text
		 * @param int       get_the_id()    The post ID
		 */
		$post_title = apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_title', $post_title, get_the_ID() );
		if ( $post_title ) {

			do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_title', get_the_ID(), $settings );

			?><<?php echo esc_html( $title_tag ); ?> class="trx-addons-posts-item-title"><?php
				if ( 'yes' === $title_link ) {
					$this->parent->add_render_attribute( $title_link_key, 'href', apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_title_link', get_the_permalink(), get_the_ID() ) );

					if ( 'yes' === $title_link_target ) {
						$this->parent->add_render_attribute( $title_link_key, 'target', '_blank' );
					}

					$post_title = '<a ' . $this->parent->get_render_attribute_string( $title_link_key ) . '>' . $post_title . '</a>';
				}

				echo wp_kses_post( $post_title );
			?></<?php echo esc_html( $title_tag ); ?>><?php

			if ( 'yes' === $post_title_separator ) {
				?><div class="trx-addons-posts-item-separator-wrap">
					<div class="trx-addons-posts-item-separator"></div>
				</div><?php
			}
		}

		do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_title', get_the_ID(), $settings );
	}

	/**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_thumbnail() {
		$settings = $this->parent->get_settings_for_display();

		$image_link        = $this->get_instance_value( 'thumbnail_link' );
		$image_link_key    = 'image-link-' . get_the_ID();
		$image_link_target = $this->get_instance_value( 'thumbnail_link_target' );

		$thumbnail_html = $this->get_post_thumbnail();

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$this->parent->add_render_attribute( 'thumbnail_wrap', 'class', 'trx-addons-posts-item-thumbnail-wrap' );
		$hover_animation = $this->get_instance_value( 'hover_animation' );
		if ( $hover_animation ) {
			$this->parent->add_render_attribute( 'thumbnail_wrap', 'class', 'elementor-animation-' . $hover_animation );
		}

		if ( 'yes' === $image_link ) {
			$this->parent->add_render_attribute( $image_link_key, 'href', apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_image_link', get_the_permalink(), get_the_ID() ) );

			if ( 'yes' === $image_link_target ) {
				$this->parent->add_render_attribute( $image_link_key, 'target', '_blank' );
			}

			$thumbnail_html = '<a ' . $this->parent->get_render_attribute_string( $image_link_key ) . '>' . $thumbnail_html . '</a>';

		}
		do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_thumbnail', get_the_ID(), $settings );
		?>
		<div class="trx-addons-posts-item-thumbnail">
			<div <?php $this->parent->print_render_attribute_string( 'thumbnail_wrap' ); ?>>
				<?php echo wp_kses_post( $thumbnail_html ); ?>
			</div>
		</div>
		<?php
		do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_thumbnail', get_the_ID(), $settings );
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_excerpt() {
		static $loaded  = false;

		$settings       = $this->parent->get_settings_for_display();
		$show_excerpt   = $this->get_instance_value( 'show_excerpt' );
		$excerpt_length = $this->get_instance_value( 'excerpt_length' );
		$content_type   = $this->get_instance_value( 'content_type' );
		$content_length = $this->get_instance_value( 'content_length' );

		if ( 'yes' !== $show_excerpt ) {
			return;
		}

		if ( 'excerpt' === $content_type && 0 === $excerpt_length ) {
			return;
		}
		?>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_excerpt', get_the_ID(), $settings ); ?>
		<div class="trx-addons-posts-item-excerpt">
			<?php
			if ( 'full' === $content_type ) {
				the_content();
				if ( ! $loaded ) {
					wp_enqueue_style( 'wp-block-library' );
					$loaded = true;
				}
			} elseif ( 'content' === $content_type ) {
				$more = '...';
				$post_content = wp_trim_words( get_the_content(), $content_length, apply_filters( 'trx_addons_filter_posts_content_limit_more', $more ) );
				echo wp_kses_post( $post_content );
			} else {
				add_filter( 'excerpt_length', array( $this, 'excerpt_length_filter' ), 20 );
				add_filter( 'excerpt_more', array( $this, 'excerpt_more_filter' ), 20 );
				if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
					echo $this->post_excerpt();
				} else {
					the_excerpt();
				}
				remove_filter( 'excerpt_length', array( $this, 'excerpt_length_filter' ), 20 );
				remove_filter( 'excerpt_more', array( $this, 'excerpt_more_filter' ), 20 );
			}
			?>
		</div>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_excerpt', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render button icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_button_icon() {
		$skin             = $this->get_id();
		$settings         = $this->parent->get_settings_for_display();
		$show_button      = $this->get_instance_value( 'show_button' );

		if ( 'yes' !== $show_button ) {
			return;
		}

		$button_icon          = $this->get_instance_value( 'button_icon' );
		$select_button_icon   = $this->get_instance_value( 'select_button_icon' );
		$button_icon_position = $this->get_instance_value( 'button_icon_position' );
		$button_icon_align    = ( 'before' === $button_icon_position ) ? 'left' : 'right';

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_button_icon' ] );
		$is_new   = empty( $settings[ $skin . '_button_icon' ] ) && Icons_Manager::is_migration_allowed();

		if ( $is_new || $migrated ) { ?>
			<span class="trx-addons-button-icon elementor-button-icon elementor-align-icon-<?php echo esc_attr( $button_icon_align ); ?>">
				<?php Icons_Manager::render_icon( $select_button_icon, array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<?php
		} else { ?>
			<span class="trx-addons-button-icon elementor-button-icon elementor-align-icon-<?php echo esc_attr( $button_icon_align ); ?>">
				<i class="trx-addons-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></i>
			</span>
			<?php
		}
	}

	/**
	 * Render button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_button() {
		$skin             = $this->get_id();
		$settings         = $this->parent->get_settings_for_display();
		$show_button      = $this->get_instance_value( 'show_button' );
		$button_animation = $this->get_instance_value( 'button_animation' );

		if ( 'yes' !== $show_button ) {
			return;
		}

		$button_text          = isset( $settings[ $skin . '_button_text' ] ) ? $settings[ $skin . '_button_text' ] : $this->get_instance_value( 'button_text' );
		$button_icon_position = $this->get_instance_value( 'button_icon_position' );
		$button_size          = 'sm';	//$this->get_instance_value( 'button_size' );
		$button_link_target   = $this->get_instance_value( 'button_link_target' );

		$classes = array(
			'trx-addons-posts-button',
			'elementor-button',
			'elementor-size-' . $button_size,
		);

		if ( $button_animation ) {
			$classes[] = 'elementor-animation-' . $button_animation;
		}

		$this->parent->add_render_attribute(
			'button-' . get_the_ID(),
			array(
				'class' => implode( ' ', $classes ),
				'href'  => apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_button_link', get_the_permalink(), get_the_ID() ),
			)
		);

		if ( 'yes' === $button_link_target ) {
			$this->parent->add_render_attribute( 'button-' . get_the_ID(), 'target', '_blank' );
		}

		/**
		 * Button Text Filter
		 *
		 * Filters the text for the button
		 *
		 * @since 1.4.11.0
		 * @param string    $button_text    The original text
		 * @param int       get_the_id()    The post ID
		 */
		$button_text = apply_filters( 'trx_addons_filter_elementor_widgets_posts_posts_button_text', $button_text, get_the_ID() );
		?>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_button', get_the_ID(), $settings ); ?>
		<a <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'button-' . get_the_ID() ) ); ?>>
			<?php if ( 'before' === $button_icon_position ) {
				$this->render_button_icon();
			} ?>
			<?php if ( $button_text ) { ?>
				<span class="trx-addons-button-text">
					<?php echo wp_kses_post( $button_text ); ?>
				</span>
			<?php } ?>
			<?php if ( 'after' === $button_icon_position ) {
				$this->render_button_icon();
			} ?>
		</a>
		<?php do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_button', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_post_body( $filter = '', $taxonomy = '', $search = '' ) {
		ob_start();
		$this->parent->query_posts( $filter, $taxonomy, $search );

		$query       = $this->parent->get_query();
		$total_pages = $query->max_num_pages;

		while ( $query->have_posts() ) {
			$query->the_post();

			$this->render_post_body();
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_pagination() {
		ob_start();
		$this->render_pagination();
		return ob_get_clean();
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_not_found( $filter = '', $taxonomy = '', $search = '' ) {
		ob_start();
		$this->parent->query_posts( $filter, $taxonomy, $search );

		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			$this->render_search();
		}
		return ob_get_clean();
	}

	/**
	 * Get Pagination.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_pagination() {
		$settings  = $this->parent->get_settings_for_display();

		$pagination_type    = $this->get_instance_value( 'pagination_type' );
		$page_limit         = $this->get_instance_value( 'pagination_page_limit' );
		$pagination_shorten = $this->get_instance_value( 'pagination_numbers_shorten' );

		$load_more_label         = $this->get_instance_value( 'pagination_load_more_label' );
		$load_more_icon_position = $this->get_instance_value( 'pagination_load_more_icon_position' );

		$infinite_disable_editor = $this->get_instance_value( 'infinite_disable_editor' );

		if ( 'none' === $pagination_type ) {
			return;
		}

		// Get current page number.
		$paged = $this->parent->get_paged();

		$query       = $this->parent->get_query();
		$total_pages = $query->max_num_pages;
		$total_pages_pagination = $query->max_num_pages;

		if ( 2 > $total_pages ) {
			return;
		}

		$has_numbers   = in_array( $pagination_type, array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = ( 'numbers_and_prev_next' === $pagination_type );
		$is_load_more  = ( 'load_more' === $pagination_type );
		$is_infinite  = ( 'infinite' === $pagination_type );

		$links = array();

		if ( $has_numbers ) {

			$current_page = $paged;
			if ( ! $current_page ) {
				$current_page = 1;
			}

			$paginate_args = array(
				'type'      => 'array',
				'current'   => $current_page,
				'total'     => $total_pages,
				'prev_next' => false,
				'show_all'  => 'yes' !== $pagination_shorten,
			);

			if ( $page_limit ) {
				$paginate_args['end_size'] = $page_limit;
				$paginate_args['mid_size'] = $page_limit;
			}
		}

		if ( $has_prev_next ) {
			$prev_label = $this->get_instance_value( 'pagination_prev_label' );
			$next_label = $this->get_instance_value( 'pagination_next_label' );

			$paginate_args['prev_next'] = true;

			if ( $prev_label ) {
				$paginate_args['prev_text'] = $prev_label;
			}
			if ( $next_label ) {
				$paginate_args['next_text'] = $next_label;
			}
		}

		if ( $has_numbers || $has_prev_next ) {

			if ( is_singular() && ! is_front_page() && ! is_singular( 'page' ) ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );

		}

		if ( ! $is_load_more && ! $is_infinite ) {
			$pagination_ajax = $this->get_instance_value( 'pagination_ajax' );
			$query_type      = $settings['query_type'];
			$pagination_type = 'standard';

			if ( 'yes' === $pagination_ajax && 'main' !== $query_type ) {
				$pagination_type = 'ajax';
			}
			?>
			<nav class="trx-addons-posts-pagination trx-addons-posts-pagination-<?php echo esc_attr( $pagination_type ); ?> elementor-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'trx_addons' ); ?>" data-total="<?php echo esc_html( $total_pages_pagination ); ?>">
				<?php echo implode( PHP_EOL, $links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<?php
		}

		if ( $is_load_more && ! $is_infinite ) {
			?>
			<nav class="trx-addons-posts-pagination trx-addons-posts-pagination-<?php echo esc_attr( $pagination_type ); ?> elementor-pagination" data-page="1" data-max-page="<?php echo $total_pages; ?>">
				<a href="javascript:void(0);" role="button" rel="Load More" class="trx-addons-load_more-button">
					<?php if ( 'before' == $load_more_icon_position) { $this->render_pagination_load_more_icon(); }; ?>

					<?php if ($load_more_label) : ?>
						<span class="trx-addons-load_more-text"><?php echo $load_more_label; ?></span>
					<?php endif; ?>

					<?php if ( 'after' == $load_more_icon_position) { $this->render_pagination_load_more_icon(); }; ?>
				</a>
			</nav>
			<?php
		}

		$show_infinite = false;
		if ( 'yes' !== $infinite_disable_editor) {
			$show_infinite = true;
		} elseif ( 'yes' === $infinite_disable_editor && !\Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$show_infinite = true;
		}

		if ( $is_infinite && $show_infinite ) {
			?>
			<nav class="trx-addons-posts-pagination trx-addons-posts-pagination-<?php echo esc_attr( $pagination_type ); ?> elementor-pagination" data-page="1" data-max-page="<?php echo $total_pages; ?>"></nav>
			<?php
		}
	}

	/**
	 * Get Pagination Load More Icon.
	 *
	 * Written the Icon HTML.
	 *
	 * @access protected
	 */
	protected function render_pagination_load_more_icon() {
		$skin                               = $this->get_id();
		$settings                           = $this->parent->get_settings_for_display();
		$pagination_load_more_icon          = $this->get_instance_value( 'pagination_load_more_icon' );
		$select_pagination_load_more_icon   = $this->get_instance_value( 'select_pagination_load_more_icon' );
		$pagination_load_more_icon_position = $this->get_instance_value( 'pagination_load_more_icon_position' );

		if ( empty( $select_pagination_load_more_icon ) ) {
			return;
		}

		?>
		<span class="trx-addons-load_more-icon elementor-button-icon elementor-align-icon-<?php echo esc_attr( $pagination_load_more_icon_position ); ?>">
			<?php Icons_Manager::render_icon( $select_pagination_load_more_icon, array( 'aria-hidden' => 'true', 'fill' => 'currentColor' ) ); ?>
		</span>
		<?php
	}

	/**
	 * Render team member carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$dots   = $this->get_instance_value( 'dots' );
		$layout = $this->get_instance_value( 'layout' );

		if ( 'carousel' !== $layout ) {
			return;
		}

		if ( 'yes' === $dots ) {
			?>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->parent->get_id() ); ?>"></div>
			<?php
		}
	}

	/**
	 * Render carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		$settings        = $this->parent->get_settings_for_display();
		$skin            = $this->get_id();
		$layout          = $this->get_instance_value( 'layout' );
		$arrows          = $this->get_instance_value( 'arrows' );
		$arrow           = $this->get_instance_value( 'arrow' );
		$select_arrow    = $this->get_instance_value( 'select_arrow' );

		if ( 'carousel' !== $layout ) {
			return;
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( ! isset( $settings[ $skin . '_arrow' ] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings[ $skin . '_arrow' ] = 'fa fa-angle-right';
		}

		$has_icon = ! empty( $settings[ $skin . '_arrow' ] );

		if ( ! $has_icon && ! empty( $select_arrow['value'] ) ) {
			$has_icon = true;
		}

		if ( ! empty( $settings['arrow'] ) ) {
			$this->parent->add_render_attribute( 'arrow-icon', 'class', $settings[ $skin . '_arrow' ] );
			$this->parent->add_render_attribute( 'arrow-icon', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_arrow' ] );
		$is_new   = ! isset( $settings[ $skin . '_arrow' ] ) && Icons_Manager::is_migration_allowed();

		if ( 'yes' === $arrows ) {
			if ( $has_icon ) {
				if ( $is_new || $migrated ) {
					$next_arrow = $select_arrow;
					$prev_arrow = str_replace( 'right', 'left', $select_arrow );
				} else {
					$next_arrow = $settings['arrow'];
					$prev_arrow = str_replace( 'right', 'left', $arrow );
				}
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}

			if ( ! empty( $arrow ) || ( ! empty( $select_arrow['value'] ) && $is_new ) ) { ?>
				<div class="trx-addons-slider-arrow trx-addons-arrow-prev elementor-swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->parent->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $prev_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $this->parent->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
				<div class="trx-addons-slider-arrow trx-addons-arrow-next elementor-swiper-button-next swiper-button-next-<?php echo esc_attr( $this->parent->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $next_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $this->parent->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
			<?php }
		}
	}

}
