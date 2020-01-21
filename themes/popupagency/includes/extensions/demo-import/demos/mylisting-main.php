<?php

namespace MyListing\Ext\Demo_Import\Demos;

if ( ! defined('ABSPATH') ) {
	exit;
}

class MyListing_Main extends Demo_Abstract {
	public function set_config() {
		return [
            'demo_id' => 'mylisting-main',
		    'import_file_name' => 'MyListing (Main Demo)',
            'import_file_url' => 'http://27collective.net/files/demo/latest/main-demo.xml',
            'import_preview_image_url' => 'http://27collective.net/files/demo/latest/main-demo.jpg',
            'import_widget_file_url' => 'http://27collective.net/files/demo/latest/main-demo.wie',
            'preview_url' => 'https://wordpress-210138-985436.cloudwaysapps.com/',
		];
	}

    public function before_import() {
        $this->import_custom_taxonomies();
    }

    public function after_import() {
        // registered menu locations in theme
        $locations = get_theme_mod( 'nav_menu_locations' );
        $menus = wp_get_nav_menus();

        foreach( (array) $menus as $menu ) { // assign menus to theme locations
            switch( $menu->slug ) {
                case "main-menu" :
                    $locations['primary'] = $menu->term_id;
                break;

                case "footer-social-links" :
                    $locations['footer'] = $menu->term_id;
                break;

                case "woocommerce-menu" :
                    $locations['mylisting-user-menu'] = $menu->term_id;
                break;
            }
        }

        set_theme_mod( 'nav_menu_locations', $locations ); // set menus to locations
        $this->update_page_ids();
    }

    protected function custom_taxonomies_list() {
        return [
            [
                'slug' => 'custom-taxonomy-car-brand',
                'label' => 'Custom Taxonomy: Car Brand'
            ],
            [
                'slug' => 'job-vacancy-type',
                'label' => 'Custom Taxonomy: Vacancy type'
            ],
            [
                'slug' => 'job-qualification',
                'label' => 'Custom Taxonomy: Qualification'
            ],
            [
                'slug' => 'job-salary',
                'label' => 'Custom Taxonomy: Salary'
            ],
        ];
    }
}
