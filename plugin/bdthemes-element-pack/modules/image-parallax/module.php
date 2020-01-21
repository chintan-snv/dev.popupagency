<?php
namespace ElementPack\Modules\ImageParallax;

use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use ElementPack;
use ElementPack\Plugin;
use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function __construct() {
		parent::__construct();
		$this->add_actions();
	}

	public function get_name() {
		return 'bdt-image-parallax';
	}

	public function get_style_depends() {
		return [ 'ep-image-parallax' ];
	}

	public function register_controls_parallax($section, $section_id, $args) {

		static $style_sections = [ 'section_background'];

		if ( ! in_array( $section_id, $style_sections ) ) { return; }


		// parallax controls
		$section->start_controls_section(
			'section_parallax_content_controls',
			[
				'label' => BDTEP_CP . __( 'Parallax', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$section->start_controls_tabs( 'element_pack_section_parallax_tabs' );

		$section->start_controls_tab(
			'element_pack_section_image_parallax_tab',
			[
				'label' => __( 'Image', 'bdthemes-element-pack' ),
			]
		);
		
		$section->add_control(
			'section_parallax_elements',
			[
				'label'   => __( 'Parallax Items', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name'        => 'section_parallax_title',
						'label'       => __( 'Title', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Parallax 1' , 'bdthemes-element-pack' ),
						'label_block' => true,
						'render_type' => 'ui',
					],
					[
						'name'      => 'section_parallax_image',
						'label'     => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::MEDIA,
						//'condition' => [ 'parallax_content' => 'parallax_image' ],
					],
					[
						'name'    => 'section_parallax_depth',
						'label'   => __( 'Depth', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::NUMBER,
						'default' => 0.1,
						'min'     => 0,
						'max'     => 1,
						'step'    => 0.1,
					],
					[
						'name'    => 'section_parallax_bgp_x',
						'label'   => __( 'Image X Position', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::NUMBER,
						'min'     => 0,
						'max'     => 100,
						'default' => 50,
					],
					[
						'name'    => 'section_parallax_bgp_y',
						'label'   => __( 'Image Y Position', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::NUMBER,
						'min'     => 0,
						'max'     => 100,
						'default' => 50,
					],
					[
						'name'    => 'section_parallax_bg_size',
						'label'   => __( 'Image Size', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'cover',
						'options' => [
							'auto'    => __( 'Auto', 'bdthemes-element-pack' ),
							'cover'   => __( 'Cover', 'bdthemes-element-pack' ),
							'contain' => __( 'Contain', 'bdthemes-element-pack' ),
						],
					],		
									
				],
				'title_field' => '{{{ section_parallax_title }}}',
			]
		);


		$section->add_control(
			'section_parallax_mode',
			[
				'label'   => esc_html__( 'Parallax Mode', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''         => esc_html__( 'Relative', 'bdthemes-element-pack' ),
					'clip'     => esc_html__( 'Clip', 'bdthemes-element-pack' ),
					'hover'    => esc_html__( 'Hovar (Mobile also turn off)', 'bdthemes-element-pack' ),
				],
			]
		);



		$section->end_controls_tab();

		$section->start_controls_tab(
			'element_pack_section_color_parallax_tab',
			[
				'label' => __( 'Color', 'bdthemes-element-pack' ),
			]
		);


		$section->add_control(
			'element_pack_sbgc_parallax_show',
			[
				'label'        => __( 'Background Color', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$section->add_control(
			'element_pack_sbgc_parallax_sc',
			[
				'label'     => esc_html__( 'Start Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'element_pack_sbgc_parallax_show' => 'yes',
				],
			]
		);

		$section->add_control(
			'element_pack_sbgc_parallax_ec',
			[
				'label'     => esc_html__( 'End Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'element_pack_sbgc_parallax_show' => 'yes',
				],

			]
		);

		$section->add_control(
			'element_pack_sbc_parallax_show',
			[
				'label'        => __( 'Border Color', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$section->add_control(
			'element_pack_sbc_parallax_sc',
			[
				'label'     => esc_html__( 'Start Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'element_pack_sbc_parallax_show' => 'yes',
				],
			]
		);

		$section->add_control(
			'element_pack_sbc_parallax_ec',
			[
				'label'     => esc_html__( 'End Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'element_pack_sbc_parallax_show' => 'yes',
				],
				
			]
		);


		$section->end_controls_tab();

		$section->end_controls_tabs();


		
		$section->end_controls_section();
		


	}


	public function section_parallax_before_render($section) {
		$parallax_elements = $section->get_settings('section_parallax_elements');
		$settings          = $section->get_settings();


		if ( 'yes' === $settings['element_pack_sbgc_parallax_show']) {

			$color1 = ($settings['element_pack_sbgc_parallax_sc']) ? $settings['element_pack_sbgc_parallax_sc'] : '#fff';
			$color2 = ($settings['element_pack_sbgc_parallax_ec']) ? $settings['element_pack_sbgc_parallax_ec'] : '#fff';

			$section->add_render_attribute( '_wrapper', 'bdt-parallax', 'background-color: '. $color1 . ',' . $color2 . ';' );
		}


		if ( 'yes' === $settings['element_pack_sbc_parallax_show']) {

			$color1 = ($settings['element_pack_sbc_parallax_sc']) ? $settings['element_pack_sbc_parallax_sc'] : '#fff';
			$color2 = ($settings['element_pack_sbc_parallax_ec']) ? $settings['element_pack_sbc_parallax_ec'] : '#fff';

			$section->add_render_attribute( '_wrapper', 'bdt-parallax', 'border-color: '. $color1 . ',' . $color2 . ';' );
		}


		if( !empty($parallax_elements) ) {


			wp_enqueue_script( 'parallax' );
			wp_enqueue_style( 'ep-image-parallax' );

			$id = $section->get_id();
			$section->add_render_attribute( 'scene', 'class', 'parallax-scene' );
			$section->add_render_attribute( '_wrapper', 'class', 'has-bdt-parallax' );

			if ( 'relative' === $settings['section_parallax_mode']) {
				$section->add_render_attribute( 'scene', 'data-relative-input', 'true' );
			} elseif ( 'clip' === $settings['section_parallax_mode']) {
				$section->add_render_attribute( 'scene', 'data-clip-relative-input', 'true' );
			} elseif ( 'hover' === $settings['section_parallax_mode']) {
				$section->add_render_attribute( 'scene', 'data-hover-only', 'true' );
			}

			?>
			<div data-parallax-id="bdt_scene<?php echo esc_attr($id); ?>" id="bdt_scene<?php echo esc_attr($id); ?>" <?php echo $section->get_render_attribute_string( 'scene' ); ?>>
				<?php foreach ( $parallax_elements as $index => $item ) : ?>
				
					<?php 

					$image_src = wp_get_attachment_image_src( $item['section_parallax_image']['id'], 'full' ); 

					if ($item['section_parallax_bgp_x']) {
						$section->add_render_attribute( 'item', 'style', 'background-position-x: ' . $item['section_parallax_bgp_x'] . '%;', true );
					}
					if ($item['section_parallax_bgp_y']) {
						$section->add_render_attribute( 'item', 'style', 'background-position-y: ' . $item['section_parallax_bgp_y'] . '%;' );
					}
					if ($item['section_parallax_bg_size']) {
						$section->add_render_attribute( 'item', 'style', 'background-size: ' . $item['section_parallax_bg_size'] . ';' );
					}

					if ($image_src[0]) {
						$section->add_render_attribute( 'item', 'style', 'background-image: url(' . esc_url($image_src[0]) .');' );
					}

					?>
					
					<div data-depth="<?php echo esc_attr($item['section_parallax_depth']); ?>" class="bdt-scene-item" <?php echo $section->get_render_attribute_string( 'item' ); ?>></div>
					
				<?php endforeach; ?>
			</div>

			<?php
		}
	}

	protected function add_actions() {

		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls_parallax' ], 10, 3 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'section_parallax_before_render' ], 10, 1 );

	}
}