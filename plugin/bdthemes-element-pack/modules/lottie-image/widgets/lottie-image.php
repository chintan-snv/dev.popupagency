<?php

namespace ElementPack\Modules\LottieImage\Widgets;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Color;
use Elementor\Utils;

use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Lottie_Image extends Widget_Base {

	public function get_name() {
		return 'bdt-lottie-image';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'Lottie Image', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-lottie-image';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'lottie', 'animation', 'bodymovin', 'transition', 'image' ];
	}

//	public function get_style_depends() {
//		return [ 'ep-call-out' ];
//	}
//
	public function get_script_depends() {
		return [ 'lottie', 'ep-lottie-image' ];
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/CbODBtLTxWc';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'lottie_json',
			[
				'label'         => __( 'Lottie Json URL', 'bdthemes-element-pack' ),
				'description'   => sprintf( __( 'Enter your lottie josn file, if you don\'t understand lottie json file so please %1s look here %2s', 'bdthemes-element-pack' ), '<a href="https://lottiefiles.com/featured" target="_blank">', '</a>' ),
				'type'          => Controls_Manager::URL,
				'autocomplete'  => false,
				'show_external' => false,
				'label_block'   => true,
				'show_label'    => false,
				'dynamic'       => [
					'active'     => true,
				],
				'default' => ['url' => BDTEP_ASSETS_URL . 'others/teamwork.json'],
				'placeholder'   => __( 'Enter your json URL', 'bdthemes-element-pack' ),

			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_show',
			[
				'label'   => esc_html__( 'Caption', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => __( 'Custom Caption', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your image caption', 'bdthemes-element-pack' ),
                'condition' => [
                    'caption_show' => 'yes'
                ],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => __( 'None', 'bdthemes-element-pack' ),
					'custom' => __( 'Custom URL', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'bdthemes-element-pack' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'show_label' => false,
			]
		);

		$this->add_control(
			'play_action',
			[
				'label' => __( 'Play Action', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'autoplay',
				'options' => [
					''         => __( 'None', 'bdthemes-element-pack' ),
					'autoplay' => __( 'Auto Play', 'bdthemes-element-pack' ),
					'hover'    => __( 'Play on Hover', 'bdthemes-element-pack' ),
					'click'    => __( 'Play on Click', 'bdthemes-element-pack' ),
					'column'   => __( 'Play on Hover Column', 'bdthemes-element-pack' ),
					'section'  => __( 'Play on Hover Section', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'loop',
			[
				'label'   => esc_html__( 'Loop', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Play Speed', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
                        'step' => 0.1,
					],
				],
				//'separator' => 'before',
			]
		);

		$this->add_control(
			'view_type',
			[
				'label'   => esc_html__( 'Start When', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'pageload'   => esc_html__( 'Page Loaded', 'bdthemes-element-pack' ),
					'scroll' => esc_html__( 'When Scroll', 'bdthemes-element-pack' ),
				],
				'default' => 'pageload',
				'separator' => 'before',
			]
		);



		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Lottie', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image svg' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Max Width', 'bdthemes-element-pack' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image svg' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image svg' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .bdt-lottie-image svg',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => __( 'Opacity', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image:hover svg' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .bdt-lottie-image:hover svg',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image svg' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .bdt-lottie-image svg',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-lottie-image svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .bdt-lottie-image svg',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => __( 'Caption', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'caption_align',
			[
				'label' => __( 'Alignment', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bdthemes-element-pack' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'caption_background_color',
			[
				'label' => __( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .widget-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'caption_text_shadow',
				'selector' => '{{WRAPPER}} .widget-image-caption',
			]
		);

		$this->add_responsive_control(
			'caption_space',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['lottie_json']['url'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'bdt-lottie-image' );
		
		
		if ( ! empty( $settings['shape'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-image-shape-' . $settings['shape'] );
		}

		$link = $this->get_link_url( $settings );

		if ( $link ) {

			$this->add_render_attribute( 'link', 'href', $link['url'] );

			if ( Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'link', [
					'class' => 'elementor-clickable',
				] );
			}

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		$this->add_render_attribute(
			[
				'lottie' => [
					'id' => 'bdt-lottie-' . $this->get_id(),
					'class' => 'bdt-lottie-container',
					'data-settings' => [
						wp_json_encode(array_filter([
							'loop'       => ( $settings['loop'] ) ? true : false,
							'json_url'   => $settings['lottie_json']['url'],
							'view_type'  => $settings['view_type'],
							'speed'      => ( $settings['speed'] ) ? $settings['speed']['size'] : false,
							'play_action' => $settings['play_action'],
						]))
					]
				]
			]
		);

		?>
		
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( 'yes' == $settings['caption_show'] ) : ?>
				<figure class="wp-caption">
			<?php endif; ?>
			<?php if ( $link ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>
				<div <?php echo $this->get_render_attribute_string( 'lottie' ); ?>></div>
			<?php if ( $link ) : ?>
					</a>
			<?php endif; ?>
			<?php if ( 'yes' == $settings['caption_show'] ) : ?>
                    <figcaption class="widget-image-caption wp-caption-text"><?php echo esc_html($settings['caption']); ?></figcaption>
				</figure>
			<?php endif; ?>
		</div>
		


		
		<?php
	}

}
