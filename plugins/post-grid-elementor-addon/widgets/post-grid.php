<?php
namespace ElementorPostGrid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Elementor_Post_Grid_Widget extends Widget_Base {

	public function get_name() {
		return 'elementor-blog-posts';
	}

	public function get_title() {
		return __( 'Post Grid', 'post-grid-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'wpcap-items' ];
	}

	private function wpcap_get_all_post_categories( $post_type ) {
		
		$options = array();

		$taxonomy = 'category';

		if ( ! empty( $taxonomy ) ) {
			// Get categories for post type.
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( isset( $term ) ) {
						if ( isset( $term->slug ) && isset( $term->name ) ) {
							$options[ $term->slug ] = $term->name;
						}
					}
				}
			}
		}

		return $options;
	}

	protected function _register_controls() {

		$this->wpcap_content_layout_options();
		$this->wpcap_content_query_options();

		$this->wpcap_style_layout_options();
		$this->wpcap_style_box_options();
		$this->wpcap_style_image_options();

		$this->wpcap_style_title_options();
		$this->wpcap_style_meta_options();
		$this->wpcap_style_content_options();
		$this->wpcap_style_readmore_options();
		
	}

	/**
	 * Content Layout Options.
	 */
	private function wpcap_content_layout_options() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'post-grid-elementor-addon' ),
			]
		);

		$this->add_control(
			'grid_style',
			[
				'label' => __( 'Grid Style', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Layout 1', 'post-grid-elementor-addon' ),
					'2' => esc_html__( 'Layout 2', 'post-grid-elementor-addon' ),
					'3' => esc_html__( 'Layout 3', 'post-grid-elementor-addon' ),
					'4' => esc_html__( 'Layout 4', 'post-grid-elementor-addon' ),
					'5' => esc_html__( 'Layout 5', 'post-grid-elementor-addon' ),
				],
			]
		);
		
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
				'selectors' => [
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'width: calc( 100% / {{SIZE}} )',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => __( 'Image', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'post-grid-elementor-addon' ),
				'label_off' => __( 'Hide', 'post-grid-elementor-addon' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_thumbnail',
				'exclude' => [ 'custom' ],
				'default' => 'full',
				'prefix_class' => 'post-thumbnail-size-',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'post-grid-elementor-addon' ),
				'label_off' => __( 'Hide', 'post-grid-elementor-addon' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_data',
			[
				'label' => __( 'Meta Data', 'post-grid-elementor-addon' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' => [
					'author' => __( 'Author', 'post-grid-elementor-addon' ),
					'date' => __( 'Date', 'post-grid-elementor-addon' ),
					'categories' => __( 'Categories', 'post-grid-elementor-addon' ),
					'comments' => __( 'Comments', 'post-grid-elementor-addon' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label' => __( 'Separator Between', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => '/',
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-meta span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Excerpt', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'post-grid-elementor-addon' ),
				'label_off' => __( 'Hide', 'post-grid-elementor-addon' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::NUMBER,
				/** This filter is documented in wp-includes/formatting.php */
				'default' => apply_filters( 'excerpt_length', 25 ),
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label' => __( 'Read More', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'post-grid-elementor-addon' ),
				'label_off' => __( 'Hide', 'post-grid-elementor-addon' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => __( 'Read More Text', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More »', 'post-grid-elementor-addon' ),
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_align',
			[
				'label' => __( 'Alignment', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'post-grid-elementor-addon' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'post-grid-elementor-addon' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'post-grid-elementor-addon' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .post-grid-inner' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Content Query Options.
	 */
	private function wpcap_content_query_options() {

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'post-grid-elementor-addon' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		// Post categories
		$this->add_control(
			'post_categories',
			[
				'label'       => __( 'Categories', 'post-grid-elementor-addon' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->wpcap_get_all_post_categories( 'post' ),
				
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => __( 'Advanced', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date' => __( 'Date', 'post-grid-elementor-addon' ),
					'post_title' => __( 'Title', 'post-grid-elementor-addon' ),
					'rand' => __( 'Random', 'post-grid-elementor-addon' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'post-grid-elementor-addon' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'post-grid-elementor-addon' ),
					'desc' => __( 'DESC', 'post-grid-elementor-addon' ),
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Style Layout Options.
	 */
	private function wpcap_style_layout_options() {

		// Layout.
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => __( 'Layout', 'post-grid-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Columns margin.
		$this->add_control(
			'grid_style_columns_margin',
			[
				'label'     => __( 'Columns margin', 'post-grid-elementor-addon' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					
				],
			]
		);

		// Row margin.
		$this->add_control(
			'grid_style_rows_margin',
			[
				'label'     => __( 'Rows margin', 'post-grid-elementor-addon' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Style Box Options.
	 */
	private function wpcap_style_box_options() {

		// Box.
		$this->start_controls_section(
			'section_box',
			[
				'label' => __( 'Box', 'post-grid-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Image border radius.
		$this->add_control(
			'grid_box_border_width',
			[
				'label'      => __( 'Border Widget', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],				
			]
		);

		// Border Radius.
		$this->add_control(
			'grid_style_border_radius',
			[
				'label'     => __( 'Border Radius', 'post-grid-elementor-addon' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Box internal padding.
		$this->add_responsive_control(
			'grid_items_style_padding',
			[
				'label'      => __( 'Padding', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'grid_button_style' );

		// Normal tab.
		$this->start_controls_tab(
			'grid_button_style_normal',
			[
				'label'     => __( 'Normal', 'post-grid-elementor-addon' ),
			]
		);

		// Normal background color.
		$this->add_control(
			'grid_button_style_normal_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Normal border color.
		$this->add_control(
			'grid_button_style_normal_border_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Border Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post' => 'border-color: {{VALUE}};',
				],
			]
		);

		// Normal box shadow.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'grid_button_style_normal_box_shadow',
				'selector'  => '{{WRAPPER}} .wpcap-grid-container .wpcap-post',
			]
		);

		$this->end_controls_tab();

		// Hover tab.
		$this->start_controls_tab(
			'grid_button_style_hover',
			[
				'label'     => __( 'Hover', 'post-grid-elementor-addon' ),
			]
		);

		// Hover background color.
		$this->add_control(
			'grid_button_style_hover_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Hover border color.
		$this->add_control(
			'grid_button_style_hover_border_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Border Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		// Hover box shadow.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'grid_button_style_hover_box_shadow',
				'selector'  => '{{WRAPPER}} .wpcap-grid-container .wpcap-post:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Style Image Options.
	 */
	private function wpcap_style_image_options() {

		// Box.
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'post-grid-elementor-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Image border radius.
		$this->add_control(
			'grid_image_border_radius',
			[
				'label'      => __( 'Border Radius', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .post-grid-inner .post-grid-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'grid_style_image_margin',
			[
				'label'      => __( 'Margin', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .post-grid-inner .post-grid-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Style > Title.
	 */
	private function wpcap_style_title_options() {
		// Tab.
		$this->start_controls_section(
			'section_grid_title_style',
			[
				'label'     => __( 'Title', 'post-grid-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		// Title typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grid_title_style_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpcap-grid-container .wpcap-post .title, {{WRAPPER}} .wpcap-grid-container .wpcap-post .title > a',
			]
		);

		// Title color.
		$this->add_control(
			'grid_title_style_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .title, {{WRAPPER}} .wpcap-grid-container .wpcap-post .title > a'       => 'color: {{VALUE}};',
				],
			]
		);

		// Title margin.
		$this->add_responsive_control(
			'grid_title_style_margin',
			[
				'label'      => __( 'Margin', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .title, {{WRAPPER}} .wpcap-grid-container .wpcap-post .title > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Meta.
	 */
	private function wpcap_style_meta_options() {
		// Tab.
		$this->start_controls_section(
			'section_grid_meta_style',
			[
				'label'     => __( 'Meta', 'post-grid-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		// Meta typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grid_meta_style_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-meta span',
			]
		);

		// Meta color.
		$this->add_control(
			'grid_meta_style_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-meta span'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-meta span a' => 'color: {{VALUE}};',
				],
			]
		);

		// Meta margin.
		$this->add_responsive_control(
			'grid_meta_style_margin',
			[
				'label'      => __( 'Margin', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Content.
	 */
	private function wpcap_style_content_options() {
		// Tab.
		$this->start_controls_section(
			'section_grid_content_style',
			[
				'label' => __( 'Content', 'post-grid-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Content typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'grid_content_style_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-excerpt p',
			]
		);

		// Content color.
		$this->add_control(
			'grid_content_style_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-excerpt p' => 'color: {{VALUE}};',
				],
			]
		);

		// Content margin
		$this->add_responsive_control(
			'grid_content_style_margin',
			[
				'label'      => __( 'Margin', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post .post-grid-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style > Readmore.
	 */
	private function wpcap_style_readmore_options() {
		// Tab.
		$this->start_controls_section(
			'section_grid_readmore_style',
			[
				'label' => __( 'Read More', 'post-grid-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Readmore typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'grid_readmore_style_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .wpcap-grid-container .wpcap-post a.read-more-btn',
			]
		);

		// Readmore color.
		$this->add_control(
			'grid_readmore_style_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'post-grid-elementor-addon' ),
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post a.read-more-btn' => 'color: {{VALUE}};',
				],
			]
		);

		// Readmore margin
		$this->add_responsive_control(
			'grid_readmore_style_margin',
			[
				'label'      => __( 'Margin', 'post-grid-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wpcap-grid-container .wpcap-post a.read-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {

		// Get settings.
		$settings = $this->get_settings();

		?>
		<div class="wpcap-grid">
			<?php 

			$columns_desktop = ( ! empty( $settings['columns'] ) ? 'wpcap-grid-desktop-' . $settings['columns'] : 'wpcap-grid-desktop-3' );

			$columns_tablet = ( ! empty( $settings['columns_tablet'] ) ? ' wpcap-grid-tablet-' . $settings['columns_tablet'] : ' wpcap-grid-tablet-2' );

			$columns_mobile = ( ! empty( $settings['columns_mobile'] ) ? ' wpcap-grid-mobile-' . $settings['columns_mobile'] : ' wpcap-grid-mobile-1' );

			$grid_style = $settings['grid_style'];

			$grid_class = '';

			if( 5 == $grid_style ){

				$grid_class = ' grid-meta-bottom';

			}
			?>
			<div class="wpcap-grid-container elementor-grid <?php echo $columns_desktop.$columns_tablet.$columns_mobile.$grid_class; ?>">

				<?php

				$posts_per_page = ( ! empty( $settings['posts_per_page'] ) ?  $settings['posts_per_page'] : 3 );

				$cats = is_array( $settings['post_categories'] ) ? implode( ',', $settings['post_categories'] ) : $settings['post_categories'];

		        $query_args = array(
					        	'posts_per_page' 		=> absint( $posts_per_page ),
					        	'no_found_rows'  		=> true,
					        	'post__not_in'          => get_option( 'sticky_posts' ),
					        	'ignore_sticky_posts'   => true,
					        	'category_name' 		=> $cats
				        	);

		        // Order by.
		        if ( ! empty( $settings['orderby'] ) ) {
		        	$query_args['orderby'] = $settings['orderby'];
		        }

		        // Order .
		        if ( ! empty( $settings['order'] ) ) {
		        	$query_args['order'] = $settings['order'];
		        }

		        $all_posts = new \WP_Query( $query_args );

		        if ( $all_posts->have_posts() ) :

		        	if( 5 == $grid_style ){

		        		include( __DIR__ . '/layouts/layout-5.php' );

		        	}elseif( 4 == $grid_style ){

		        		include( __DIR__ . '/layouts/layout-4.php' );

		        	}elseif( 3 == $grid_style ){

		        		include( __DIR__ . '/layouts/layout-3.php' );

		        	}elseif( 2 == $grid_style ){

		        		include( __DIR__ . '/layouts/layout-2.php' );

		        	}else{

		        		include( __DIR__ . '/layouts/layout-1.php' );

		        	}

		        endif; ?>

			</div>			      						               
		</div>
		<?php

	}

	public function wpcap_filter_excerpt_length( $length ) {

		$settings = $this->get_settings();

		$excerpt_length = (!empty( $settings['excerpt_length'] ) ) ? absint( $settings['excerpt_length'] ) : 25;

		return absint( $excerpt_length );
	}

	public function wpcap_filter_excerpt_more( $more ) {
		return '&hellip;';
	}

	protected function render_thumbnail() {	

		$settings = $this->get_settings();

		$show_image = $settings['show_image'];

		if ( 'yes' !== $show_image ) {
			return;
		}

		$post_thumbnail_size = $settings['post_thumbnail_size'];
			
		if ( has_post_thumbnail() ) :  ?>
			<div class="post-grid-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( $post_thumbnail_size ); ?>
				</a>
			</div>
        <?php endif;
	}

	protected function render_title() {	

		$settings = $this->get_settings();

		$show_title = $settings['show_title'];

		if ( 'yes' !== $show_title ) {
			return;
		}

		$title_tag = $settings['title_tag'];
			
		?>
		<<?php echo $title_tag; ?> class="title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo $title_tag; ?>>
		<?php
	}

	protected function render_meta() {

		$settings = $this->get_settings();

		$meta_data = $settings['meta_data'];

		if ( empty( $meta_data ) ) {
			return;
		}
		
		?>
		<div class="post-grid-meta">
			<?php
			if ( in_array( 'author', $meta_data ) ) { ?>

				<span class="post-author"><?php the_author(); ?></span>

				<?php 
			}

			if ( in_array( 'date', $meta_data ) ) { ?>

				<span class="post-author"><?php echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' ); ?></span>

				<?php
			}

			if ( in_array( 'categories', $meta_data ) ) {

				$categories_list = get_the_category_list( esc_html__( ', ', 'post-grid-elementor-addon' ) ); 

				if ( $categories_list ) {
				    printf( '<span class="post-categories">%s</span>', $categories_list ); // WPCS: XSS OK.
				}
				
			}

			if ( in_array( 'comments', $meta_data ) ) { ?>
				
				<span class="post-comments"><?php comments_number(); ?></span>

				<?php
			}
			?>
		</div>
		<?php

	}

	protected function render_excerpt() {

		$settings = $this->get_settings();

		$show_excerpt = $settings['show_excerpt'];

		if ( 'yes' !== $show_excerpt ) {
			return;
		}
		
		add_filter( 'excerpt_more', [ $this, 'wpcap_filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'wpcap_filter_excerpt_length' ], 9999 );

		?>
		<div class="post-grid-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php

		remove_filter( 'excerpt_length', [ $this, 'wpcap_filter_excerpt_length' ], 9999 );
		remove_filter( 'excerpt_more', [ $this, 'wpcap_filter_excerpt_more' ], 20 );
	}

	protected function render_readmore() {

		$settings = $this->get_settings();

		$show_read_more = $settings['show_read_more'];
		$read_more_text = $settings['read_more_text'];

		if ( 'yes' !== $show_read_more ) {
			return;
		}
		
		?>
		<a class="read-more-btn" href="<?php the_permalink(); ?>"><?php echo esc_html( $read_more_text ); ?></a>
		<?php

	}

}
