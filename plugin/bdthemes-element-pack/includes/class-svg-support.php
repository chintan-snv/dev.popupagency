<?php 
namespace ElementPack\SVG_Support;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SVG_Support {

	/**
	  * A reference to an instance of this class.
	  * @var   object
	  */
	private static $instance = null;

	public function init() {
		add_filter( 'upload_mimes', [$this, 'set_svg_mimes'] );
		add_filter( 'wp_prepare_attachment_for_js', [$this, 'prepare_attachment_modal_for_svg'], 10, 3 );
	}

	/**
	 * Add Mime Types
	 * @return array
	 */
	function set_svg_mimes( $mimes = array() ) {

		if ( current_user_can( 'administrator' ) ) {

			// allow SVG file upload
			$mimes['svg']  = 'image/svg+xml';
			$mimes['svgz'] = 'image/svg+xml';

			$mimes['json']  = 'application/json';

			return $mimes;

		} else {

			return $mimes;

		}

	}

	function prepare_attachment_modal_for_svg( $response, $attachment, $meta ) {

		if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

			$svg_path = get_attached_file( $attachment->ID );

			if ( ! file_exists( $svg_path ) ) {
				// If SVG is external, use the URL instead of the path
				$svg_path = $response['url'];
			}

			$dimensions = $this->get_dimensions( $svg_path );

			$response['sizes'] = array(
				'full' => array(
					'url' => $response['url'],
					'width' => $dimensions->width,
					'height' => $dimensions->height,
					'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
				)
			);

		}

		return $response;

	}


	private function get_dimensions( $svg ) {

		$svg = simplexml_load_file( $svg );

		if ( $svg === FALSE ) {

			$width = '0';
			$height = '0';

		} else {

			$attributes = $svg->attributes();
			$width = (string) $attributes->width;
			$height = (string) $attributes->height;

		}

		return (object) array( 'width' => $width, 'height' => $height );

	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

}