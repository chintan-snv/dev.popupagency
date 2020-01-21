<?php
namespace ElementPack\Modules\Instagram;

use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'instagram';
	}

	public function get_widgets() {

		$widgets = ['Instagram'];

		return $widgets;
	}

	/**
	 * Instagram post layout maker with ajax load
	 * @return string instagram images with layout 
	 */
	public function element_pack_instagram_ajax_load() {

		$limit               = $_REQUEST['item_per_page'];
		$current_page        = $_REQUEST['current_page'];
		$load_more_per_click = $_REQUEST['load_more_per_click'];
		$skin				 = $_REQUEST['skin'];

		$insta_feeds = element_pack_instagram_feed();
		
		$output = '';

		if($current_page==1){
			$start = 0;
			$end = $limit-1;
		}else{
			$start= $limit+(($current_page-2) *  $load_more_per_click)+1;
			$end = $limit+($load_more_per_click*($current_page-1));
		}
		
		ob_start();

		for ( $i = $start; $i <= $end; $i++ ) {
			if ( $insta_feeds[ $i ] ) {

				if ( 'bdt-classic-grid' == $skin) {
					include 'widgets/template-classic.php';
				} else {
					include 'widgets/template.php';
				}

			}
		}

		$output = ob_get_clean();

		echo $output;

	    die();
	}


	public function add_actions() {
		add_action('wp_ajax_nopriv_element_pack_instagram_ajax_load', [ $this, 'element_pack_instagram_ajax_load' ] );
        add_action('wp_ajax_element_pack_instagram_ajax_load', array($this, 'element_pack_instagram_ajax_load'));
	}
}
