<?php

namespace MyListing\Ext\Demo_Import\Demos;

if ( ! defined('ABSPATH') ) {
	exit;
}

abstract class Demo_Abstract {
    protected $config = [];

    /**
     * Import demo name
     *
     * @var string
     * @since 2.2.2
     */
    protected $demo_id = '';

    protected $pages_list = [
        'options_general_add_listing_page'       => 'add-listing',
        'job_manager_claim_listing_page_id'      => 'claim-listing',
        'options_header_call_to_action_links_to' => 'add-listing',
        'woocommerce_shop_page_id'               => 'shop'
    ];

	public function __construct() {
        $this->config = $this->set_config();

        if ( ! is_array( $this->config ) || empty( $this->config['demo_id'] ) ) {
            return null;
        }

        // Set this demo id
        $this->demo_id = $this->config['demo_id'];
		add_filter( 'mylisting/demo-import/files', [ $this, 'register_demo' ] );

        // Before and after filters
        add_action( 'mylisting/demo-import/' . $this->config['demo_id'] . '/after-import', [ $this, 'import_options' ] );
        add_action( 'mylisting/demo-import/' . $this->config['demo_id'] . '/before-import', [ $this, 'before_import' ] );
	}

    abstract public function set_config();
    abstract public function before_import();
    abstract public function after_import();

	public function register_demo( $ml_demos_list ) {
		$ml_demos_list[] = $this->config;
		return $ml_demos_list;
	}

    public function import_options() {
        define('ML_DEMO_IMPORT', TRUE);

        $demo_directory = get_template_directory() . '/includes/extensions/demo-import/demos/acf-options/' . $this->demo_id;
        foreach( glob( $demo_directory . '/*.php') as $options_file ) {
            $options = require $options_file;

            if ( ! is_array( $options ) ) {
                continue;
            }

            $this->_import_options( $options );
        }

        // Call after import method
        $this->after_import();
    }

    protected function import_custom_taxonomies() {
        if ( ! method_exists( $this, 'custom_taxonomies_list' ) ) {
            return false;
        }

        // Add required custom taxonomies
        $taxonomy_instance = \MyListing\Ext\Custom_Taxonomies\Custom_Taxonomies::instance();

        // Delete existing custom taxonomies list
        delete_option('job_manager_custom_taxonomy');

        $taxonomies = $this->custom_taxonomies_list();
        add_option('job_manager_custom_taxonomy', $taxonomies);

        // Register Taxonomies
        $taxonomy_instance->_custom_taxonomies = $taxonomies;
        $taxonomy_instance->register_taxonomies();
    }

    protected function update_page_ids() {
        foreach ( $this->pages_list as $option_name => $page ) {
            $post = get_page_by_path( $page );

            if ( ! is_object( $post ) || ! is_a( $post, 'WP_Post' ) ) {
                continue;
            }

            delete_option( $option_name );
            add_option( $option_name, $post->ID );
        }
    }

    protected function fix_featured_categories( $selected_terms ) {
        global $wpdb;

        $selected_terms_ids = [];
        foreach( $selected_terms as $slug ) {
            $term = get_term_by( 'slug', $slug, 'job_listing_category' );
            if ( ! $term ) {
                continue;
            }

            $selected_terms_ids[] = $term->term_id;
        }

        update_option('options_header_search_form_featured_categories', $selected_terms_ids);
    }

    private function _import_options( $options ) {
        foreach( $options as $field_data ) {
            if ( empty( $field_data['id'] ) || empty( $field_data['name'] ) || ! isset( $field_data['value'] ) ) {
                continue;
            }

            $field_id = $field_data['id'];
            $acf_option_name = '_' . $field_data['name'];
            $option_name = $field_data['name'];

            // Make sure the old data is removed
            delete_option( $acf_option_name );
            delete_option( $option_name );

            // Add new data to wp_options table
            add_option( $acf_option_name, $field_id );
            add_option( $option_name, $field_data['value'] );
        }
    }
}
