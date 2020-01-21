<?php
namespace ElementPack\Modules\Instagram\Skins;

use Elementor\Controls_Manager;
use Elementor\Skin_Base;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Carousel extends Skin_Base {

	public function get_id() {
		return 'bdt-instagram-carousel';
	}

	public function get_title() {
		return esc_html__( 'Carousel', 'bdthemes-element-pack' );
	}

    public function render() {
		$settings  = $this->parent->get_settings_for_display();

		$options   = get_option( 'element_pack_api_settings' );
		$access_token    = (!empty($options['instagram_access_token'])) ? $options['instagram_access_token'] : '';


		if (!$access_token) {
			element_pack_alert('Ops! You did not set Instagram Access Token in element pack settings!');
			return;
		}

		
		$this->parent->add_render_attribute('instagram-wrapper', 'class', 'bdt-instagram bdt-instagram-carousel' );
		$this->parent->add_render_attribute('instagram-wrapper', 'bdt-slider', '' );

		$this->parent->add_render_attribute('instagram-carousel', 'class', 'bdt-grid bdt-slider-items' );

		$this->parent->add_render_attribute('instagram-carousel', 'class', 'bdt-grid-' . esc_attr($settings["column_gap"]) );
		
		$this->parent->add_render_attribute('instagram-carousel', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns"]) . '@m');
		$this->parent->add_render_attribute('instagram-carousel', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns_tablet"]). '@s');
		$this->parent->add_render_attribute('instagram-carousel', 'class', 'bdt-child-width-1-' . esc_attr($settings["columns_mobile"]) );	 

		if ( 'yes' == $settings['show_lightbox'] ) {
			$this->parent->add_render_attribute('instagram-carousel', 'bdt-lightbox', 'animation:' . $settings['lightbox_animation'] . ';');
			if ($settings['lightbox_autoplay']) {
				$this->parent->add_render_attribute('instagram-carousel', 'bdt-lightbox', 'autoplay: 500;');
				
				if ($settings['lightbox_pause']) {
					$this->parent->add_render_attribute('instagram-carousel', 'bdt-lightbox', 'pause-on-hover: true;');
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
				        ]))
					]
				]
			]
		);


		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'instagram-wrapper' ); ?>>

			<?php if ( $settings['show_follow_me'] ) : 

				$insta_user = get_transient( 'ep_instagram_user' );
				$username = isset($insta_user) ? $insta_user['username'] : '';

			?>

			<div class='bdt-instagram-follow-me bdt-position-z-index bdt-position-center'>
				<a href='https://www.instagram.com/<?php echo esc_html($username);  ?>'><?php echo esc_html($settings['follow_me_text']); ?> <?php echo esc_html($username);  ?></a>
			</div>

			<?php endif; ?>
			
			<div <?php echo $this->parent->get_render_attribute_string( 'instagram-carousel' ); ?>>
			
				<?php  for ( $dummy_item_count = 1; $dummy_item_count <= $settings["items"]["size"]; $dummy_item_count++ ) : ?>
				
				<div class="bdt-instagram-item"><div class="bdt-dummy-loader"></div></div>

				<?php endfor; ?>
				
			</div>
			
			<a class='bdt-position-center-left bdt-position-small bdt-hidden-hover bdt-visible@m' href='#' bdt-slidenav-previous bdt-slider-item='previous'></a>
			<a class='bdt-position-center-right bdt-position-small bdt-hidden-hover bdt-visible@m' href='#' bdt-slidenav-next bdt-slider-item='next'></a>

		
		</div>

		<?php
	}
}