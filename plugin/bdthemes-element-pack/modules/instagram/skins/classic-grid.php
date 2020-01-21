<?php
namespace ElementPack\Modules\Instagram\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Classic_Grid extends Skin_Base {

	public function get_id() {
		return 'bdt-classic-grid';
	}

	public function get_title() {
		return esc_html__( 'Classic Grid', 'bdthemes-element-pack' );
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();
		$insta_feeds = [];

		$options   = get_option( 'element_pack_api_settings' );
		$access_token    = (!empty($options['instagram_access_token'])) ? $options['instagram_access_token'] : '';

		if (!$access_token) {
			element_pack_alert('Ops! You did not set Instagram Access Token in element pack settings!');
			return;
		}

		
		$this->parent->add_render_attribute('instagram-wrapper', 'class', ['bdt-instagram', 'bdt-classic-grid'] );

		$this->parent->add_render_attribute('instagram', 'class', 'bdt-grid' );

		$this->parent->add_render_attribute('instagram', 'class', 'bdt-grid-' . esc_attr($settings["column_gap"]) );
		
		$this->parent->add_render_attribute('instagram', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns"]) . '@m');
		$this->parent->add_render_attribute('instagram', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns_tablet"]). '@s');
		$this->parent->add_render_attribute('instagram', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns_mobile"]) );

		$this->parent->add_render_attribute('instagram', 'bdt-grid', '' );
		if ($settings['masonry']) {
			$this->parent->add_render_attribute('instagram', 'bdt-grid', 'masonry: true;' );
		}

		
		$this->parent->add_render_attribute('instagram', 'class', 'bdt-instagram-grid' );
	 

		if ( 'yes' == $settings['show_lightbox'] ) {
			$this->parent->add_render_attribute('instagram', 'bdt-lightbox', 'animation:' . $settings['lightbox_animation'] . ';');
			if ($settings['lightbox_autoplay']) {
				$this->parent->add_render_attribute('instagram', 'bdt-lightbox', 'autoplay: 500;');
				
				if ($settings['lightbox_pause']) {
					$this->parent->add_render_attribute('instagram', 'bdt-lightbox', 'pause-on-hover: true;');
				}
			}
		}

		$this->parent->add_render_attribute(
			[
				'instagram-wrapper' => [
					'data-settings' => [
						wp_json_encode(array_filter([
							'action'              => 'element_pack_instagram_ajax_load',
							'show_comment'        => ( $settings['show_comment'] ) ? true : false,
							'show_like'           => ( $settings['show_like'] ) ? true : false,
							'show_link'           => ( $settings['show_link'] ) ? true : false,
							'show_lightbox'       => ( $settings['show_lightbox'] ) ? true : false,
							'current_page'        => 1,
							'load_more_per_click' => 4,
							'item_per_page'       => $settings["items"]["size"],
							'skin'       		  => ($settings['_skin']) ? $settings['_skin'] : '',
				        ]))
					]
				]
			]
		);


		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'instagram-wrapper' ); ?>>
			
			<div <?php echo $this->parent->get_render_attribute_string( 'instagram' ); ?>>
			
				<?php  for ( $dummy_item_count = 1; $dummy_item_count <= $settings["items"]["size"]; $dummy_item_count++ ) : ?>
				
				<div class="bdt-instagram-item"><div class="bdt-dummy-loader"></div></div>

				<?php endfor; ?>

			</div>


		<?php if ($settings['show_loadmore']) : ?>
		<div class="bdt-text-center bdt-margin">
			<a href="javascript:;" class="bdt-load-more bdt-button bdt-button-primary">
				<span bdt-spinner></span>
				<span class="loaded-txt">
					<?php esc_html_e('Load More', 'bdthemes-element-pack'); ?>
				</span>
			</a>
		</div>
		<?php endif; ?>
		
		</div>

		<?php
	}
}