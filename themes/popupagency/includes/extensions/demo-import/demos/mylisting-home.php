<?php

namespace MyListing\Ext\Demo_Import\Demos;

if ( ! defined('ABSPATH') ) {
	exit;
}

class MyListing_Home extends Demo_Abstract {
	public function set_config() {
		return [
            'demo_id' => 'mylisting-home',
		    'import_file_name' => 'My Home',
            'import_file_url' => 'http://27collective.net/files/demo/latest/my-home.xml',
            'import_widget_file_url' => 'http://27collective.net/files/demo/latest/my-home.wie',
            'import_preview_image_url' => 'http://27collective.net/files/demo/latest/my-home.jpg',
            'preview_url' => 'https://woocommerce-210138-1012856.cloudwaysapps.com/',
		];
	}

    public function before_import() {}
    public function after_import() {
        // registered menu locations in theme
        $locations = get_theme_mod( 'nav_menu_locations' );
        $menus = wp_get_nav_menus();

        foreach( (array) $menus as $menu ) { // assign menus to theme locations
            switch( $menu->slug ) {
                case "main-menu" :
                    $locations['primary'] = $menu->term_id;
                break;

                case "footer-menu" :
                    $locations['footer'] = $menu->term_id;
                break;

                case "woocommerce-menu" :
                    $locations['mylisting-user-menu'] = $menu->term_id;
                break;
            }
        }

        set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
        $this->update_page_ids();

        $this->fix_featured_categories([
            'house',
            'office'
        ]);
    }
}
