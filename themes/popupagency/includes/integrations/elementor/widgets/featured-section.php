<?php

namespace MyListing\Int\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Plugin;
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Featured_Section extends Widget_Base {

	public function get_name() {
		return 'case27-featured-section-widget';
	}

	public function get_title() {
		return __( '<strong>27</strong> > Featured Section', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	protected function _register_controls() {
		$traits = new \MyListing\Int\Elementor\Traits( $this );

		$this->start_controls_section(
			'27_featured_section_widget',
			[
				'label' => esc_html__( 'Featured Section', 'my-listing' ),
			]
		);

		$this->add_control(
			'27_content',
			[
				'label' => __( 'Content', 'my-listing' ),
				'type' => Controls_Manager::WYSIWYG,
			]
		);

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {
		c27()->get_section( 'featured-section', [
			'content' => $this->get_settings('27_content'),
			'is_edit_mode' => Plugin::$instance->editor->is_edit_mode(),
		] );
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
