<?php

namespace MyListing\Ext\Demo_Import;

if ( ! defined('ABSPATH') ) {
    exit;
}

class Demo_Import {
    use \MyListing\Src\Traits\Instantiatable;

    /**
     * Log Level
     *
     * @var string
     * @since 2.2.2
     */
    private $log_level = 'critical';

    /**
     * Import option name
     *
     * @var string
     * @since 2.2.2
     */
    private $import_option_name = '_ml_import_demo_data';

    /**
     * Current Demo Data
     *
     * @var array
     * @since 2.2.2
     */
    private $current_demo_info = [];

    /**
     * Import post ids
     */
    private $post_ids = [];

    public function __construct() {
        if ( ! is_admin() ) {
            return null;
        }

        $this->load_demos();

        // Load once click demo import
        require_once trailingslashit( __DIR__ ) . 'plugin/one-click-demo-import.php';

        // @todo menus pointing to mylistingtheme.com
        // Hide warnings
        add_filter( 'pt-ocdi/logger_options', [ $this, 'logger_options'] );
        add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

        add_filter( 'pt-ocdi/plugin_page_setup', [ $this, 'plugin_page_setup' ], 30 );
        add_filter( 'pt-ocdi/import_files', [ $this, 'load_import_files' ] );
        add_action( 'pt-ocdi/before_content_import_execution', [ $this, 'before_import' ], 10, 3 );
        add_action( 'pt-ocdi/after_import', [ $this, 'after_import' ] );
        add_action( 'wp_import_insert_term', [ $this, 'map_taxonomies_id' ], 10, 2 );

        add_filter( 'wp_import_insert_post', [$this, 'cache_post_ids'], 10, 3 );
        add_filter( 'wxr_importer.pre_process.post', [$this, 'ignore_log_files'], 99, 2 );

        add_filter( 'wxr_importer.pre_process.post_meta', [$this, 'meta_data_filter'], 1, 2);

        // Allow SVG files during demo import
        add_filter('upload_mimes', [$this, 'allow_svg_import']);
    }

    public function load_import_files() {
        return apply_filters( 'mylisting/demo-import/files', [], $this );
    }

    public function map_taxonomies_id( $term_id, $data ) {
        if ( ! isset( $data['id'] ) ) {
            return null;
        }

        // Make sure the old value is deleted
        delete_term_meta( $term_id, '_old_meta_key' );
        add_term_meta( $term_id, '_old_meta_key', $data['id'], true );
    }

    public function logger_options( $options ) {
        $options['logger_min_level'] = $this->log_level;
        return $options;
    }

    public function after_import( $selected_import ) {
        // Update Elementor Fonts
        $this->update_elementor_fonts();

        // Assign front page and posts page (blog page).
        $front_page_id = get_page_by_title( 'Home' );
        $blog_page_id  = get_page_by_title( 'Blog' );

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page_id->ID );
        update_option( 'page_for_posts', $blog_page_id->ID );

        $cart_page_id = get_page_by_title( 'Cart' );
        $checkout_page_id = get_page_by_title( 'Checkout' );
        $myaccount_page_id = get_page_by_title( 'My account' );

        update_option( 'woocommerce_cart_page_id', $cart_page_id->ID );
        update_option( 'woocommerce_checkout_page_id', $checkout_page_id->ID );
        update_option( 'woocommerce_myaccount_page_id', $myaccount_page_id->ID );

        // Delete demo data
        delete_option( $this->import_option_name );
        $this->fix_listing_contact_form();
        do_action(
            sprintf(
                'mylisting/demo-import/%s/after-import',
                $selected_import['demo_id']
            ),

            $selected_import
        );
    }

    /**
     * Edit name and location of the plugin page in wp-admin.
     *
     * @since 1.7.0
     */
    public function plugin_page_setup( $page ) {
        // Under Theme Tools.
        $page['parent_slug'] = 'case27/tools.php';
        $page['menu_title'] = 'Demo Import';
        $page['capability'] = 'administrator';

        return $page;
    }

    public function allow_svg_import( $mimes ) {
        if ( $this->get_current_demo_info() ) {
            $mimes['svg'] = 'image/svg+xml';
        }

        return $mimes;
    }

    public function before_import( $selected_import_files, $import_files, $selected_index ) {
        global $wp_rewrite;

        // Change Permalink structure to support nice urls
        if ( ! get_option( 'permalink_structure' ) ) {
            $wp_rewrite->set_permalink_structure( '/%postname%/' );
            flush_rewrite_rules();
        }

        // Remove dynamic style file
        $this->_remove_dynamic_style_file();

        $demo_data = false;
        if ( is_array( $import_files ) && isset( $import_files[ $selected_index ] ) ) {
            $demo_data = $import_files[ $selected_index ];
        }

        if ( ! is_array( $demo_data ) || empty( $demo_data['demo_id'] ) ) {
            return null;
        }

        $import_demo_data = $import_files[ $selected_index ];
        $import_demo_data['import_files'] = $selected_import_files;

        // Save the import file information
        delete_option( $this->import_option_name );
        add_option( $this->import_option_name, $import_demo_data, '', false );

        do_action(
            sprintf( 'mylisting/demo-import/%s/before-import', $demo_data['demo_id'] ),
            $demo_data
        );
    }

    public function cache_post_ids( $post_id, $original_id, $postdata ) {
        if ( 'publish' != $postdata['post_status'] || ! in_array( $postdata['post_type'], ['wpcf7_contact_form'] ) ) {
            return $postdata;
        }

        // Cache the old meta key
        if ( ! add_post_meta( $post_id, '_original_post_id', $original_id, true ) ) {
            update_post_meta( $post_id, '_original_post_id', $original_id );
        }

        return $postdata;
    }

    public function ignore_log_files( $data, $meta ) {
        if ( ! isset( $data['post_type'] ) || 'attachment' != $data['post_type'] ||
                ! preg_match('/\.txt$/', $data['attachment_url'] ) ) {
            return $data;
        }

        return false;
    }

    public function meta_data_filter( $meta_item, $post_id = 0 ) {
        switch( $meta_item['key'] ) {
            case '_menu_item_url' :
                $meta_item = $this->fix_menu_items( $meta_item );
            break;

            case '_elementor_data' :
                $meta_item = $this->fix_elementor_data( $meta_item );
            break;

            case '_job_expires' :
                $day_range = rand(5, 30);
                $meta_item['value'] = Date('Y-m-d', strtotime('+' . $day_range . ' day') );
            break;

            case '_job_logo' :
            case '_job_gallery' :
            case '_job_cover' :
                $meta_item['value'] = $this->download_external_images( $meta_item['value'] );
            break;
        }

        return $meta_item;
    }

    private function fix_menu_items( $meta_item ) {
        $meta_item['value'] = str_ireplace( $this->get_demos_url_list(), site_url( '/' ), $meta_item['value'] );
        return $meta_item;
    }

    private function fix_listing_contact_form() {
        global $wpdb;

        $meta_data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}postmeta WHERE
                meta_key = 'case27_listing_type_single_page_options'"
            );

        foreach( $meta_data as $meta_index => $meta ) {
            $meta_value = maybe_unserialize(
                maybe_unserialize( $meta->meta_value )
            );

            $meta_value = $this->_fix_listing_contact_form( $meta_value );
            update_post_meta( $meta->post_id, $meta->meta_key, maybe_serialize( $meta_value ) );
        }
    }

    private function _fix_listing_contact_form( $data ) {
        if ( ! is_array( $data ) ) {
            return $data;
        }

        foreach( $data as $field_name => $field_value ) {
            if ( is_array( $field_value ) ) {
                $data[ $field_name ] = $this->_fix_listing_contact_form( $field_value );
                continue;
            }

            if ( 'contact_form_id' == $field_name ) {
                $data[ $field_name ] = $this->_get_contact_form_id( $field_value );
            }
        }

        return $data;
    }

    private function _get_contact_form_id( $original_form_id ) {
        global $wpdb;

        if ( isset( $this->post_ids[ $original_form_id ] ) ) {
            return $this->post_ids[ $original_form_id ];
        }

        $meta_data = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}postmeta
                    WHERE meta_key = '_original_post_id' AND meta_value = %d",
                $original_form_id
            )
        );

        if ( ! $meta_data ) {
            return $original_form_id;
        }

        $this->post_ids[ $meta_data->meta_value ] = $meta_data->post_id;
        return $this->post_ids[ $meta_data->meta_value ];
    }

    private function update_elementor_fonts() {
        $typography = get_option( 'elementor_scheme_typography' );

        // Make sure the existing data is removed
        delete_option('elementor_scheme_typography');

        if ( ! $typography ) {
            return add_option( 'elementor_scheme_typography', $this->elementor_fonts_data() );
        }

        foreach( (array) $typography as $index => $settings ) {
            if ( ! isset( $settings['font_family'] ) ) {
                continue;
            }

            $typography[ $index ]['font_family'] = '';
        }

        update_option( 'elementor_scheme_typography', $typography );
    }

    private function fix_elementor_data( $meta_item ) {
        try {
            $data = json_decode( $meta_item['value'] );
        } catch( Exception $e ) {}

        if ( ! $data ) {
            return $meta_item;
        }

        foreach ( $data as &$settings ) {
            $this->parse_data( $settings );
        }

        $meta_item['value'] = json_encode( $data );
        return $meta_item;
    }

    private function parse_data( &$data, $return_status = false ) {
        global $wpdb;

        if ( isset( $data->elements ) && is_array( $data->elements ) ) {
            foreach( $data->elements as &$element ) {
                $return_status = $this->parse_data( $element, $return_status );
            }
        }

        if ( ! isset( $data->settings ) ) {
            return $return_status;
        }

        $this->_replace_urls( $data );
        $this->_fix_taxonomies( $data );

        return true;
    }

    private function fix_categories_shortcode( &$content ) {
        global $wpdb;

        if ( ! has_shortcode( $content, '27-categories' ) ) {
            return null;
        }

        $pattern = get_shortcode_regex();
        preg_match_all( '/'. $pattern .'/s', $content, $matches, PREG_SET_ORDER );

        foreach ( $matches as $shortcode ) {
            if ( ! isset( $shortcode[2] ) || '27-categories' != $shortcode[2] ) {
                continue;
            }

            $attributes = (array) shortcode_parse_atts( $shortcode[3] );
            if ( empty( $attributes['ids'] ) ) {
                continue;
            }

            $ids = explode( ',', $attributes['ids'] );
            foreach ( $ids as &$category_id ) {
                $meta_data = $wpdb->get_row(
                    $wpdb->prepare(
                        "select * from {$wpdb->prefix}termmeta WHERE meta_key = '_old_meta_key' and meta_value = '%d'",
                        $category_id
                        )
                    );

                if ( ! $meta_data ) {
                    continue;
                }

                $category_id = $meta_data->term_id;
            }

            $attributes['ids'] = implode( ',', $ids );

            $new_shortcode = ['27-categories'];
            foreach ( $attributes as $key => $value) {
               $new_shortcode[] = $key . '="' . $value . '"';
            }

            $new_shortcode = '[' . implode(' ', $new_shortcode ) . ']';
            $content = str_ireplace( $shortcode[0], $new_shortcode, $content );
        }
    }

    private function _replace_urls( &$data ) {
        // Fix links
        foreach(['mylisting_link_to', 'background_image'] as $attribute ) {
            if ( empty( $data->settings->{ $attribute } ) ) {
                continue;
            }

            foreach( $data->settings->{ $attribute } as &$value ) {
                if ( is_array( $value ) ) {
                    continue;
                }

                $value = str_ireplace(
                    $this->get_demos_url_list(),
                    site_url( '/' ),
                    $value
                );
            }
        }
    }

    private function _fix_taxonomies( &$data ) {
        global $wpdb;

        // Taxonomy Update
        $taxonomies = [];
        foreach ( [ 'select_categories', 'select_regions', 'select_tags', '27_content' ] as $taxomony ) {
            if ( ! empty( $data->settings->{ $taxomony } ) ) {
                if ( $taxomony == '27_content' ) {
                    $this->fix_categories_shortcode( $data->settings->{ $taxomony } );
                    continue;
                }

                $taxonomies[] =& $data->settings->{ $taxomony };
            }
        }

        foreach ( $taxonomies as $taxomony ) {
            foreach( $taxomony as &$category ) {
                $meta_data = $wpdb->get_row(
                    $wpdb->prepare(
                        "select * from {$wpdb->prefix}termmeta WHERE meta_key = '_old_meta_key' and meta_value = '%d'",
                        $category->category_id
                        )
                    );

                if ( ! $meta_data ) {
                    continue;
                }

                $category->category_id = $meta_data->term_id;
            }
        }
    }

    private function get_demos_url_list() {
        $current_demo_info = $this->get_current_demo_info();

        return array_filter(
            apply_filters('ml_demo_urls', [
                'https://mylistingtheme.com/',
                'https://27collective.net/',
                $current_demo_info['preview_url']
            ])
        );
    }

    private function _remove_dynamic_style_file() {
        $upload_dir = wp_get_upload_dir();
        if ( ! is_array( $upload_dir ) || empty( $upload_dir['basedir'] ) || empty( $upload_dir['baseurl'] ) ) {
            return null;
        }

        // if file does not exist, generate it
        $dynamic_style_file = trailingslashit( $upload_dir['basedir'] ) . 'mylisting-dynamic-styles.css';
        if ( file_exists( $dynamic_style_file ) ) {
            @unlink( $dynamic_style_file );
        }
    }

    private function get_current_demo_info() {
        if ( ! $this->current_demo_info ) {
            $this->current_demo_info = get_option( $this->import_option_name );
        }

        return $this->current_demo_info;
    }

    private function download_external_images( $image_urls ) {
        $image_urls = maybe_unserialize( $image_urls );

        $regex_array = [];
        foreach( $this->get_demos_url_list() as $demo_url ) {
            $regex_array[] = preg_quote( $demo_url );
        }

        $url_regex = implode('|', $regex_array);
        $demo_imported_urls = get_option('_ml_demo_imported_urls') ? : [];

        foreach( (array)$image_urls as $index => $url ) {
            $extension_split = explode('.', $url);
            end( $extension_split );
            $file_extension = strtolower( current( $extension_split ) );

            // Make sure its a valid image url
            $is_valid_demo_link = preg_match("@^({$url_regex})@i", $url);
            if ( ! $is_valid_demo_link || ! wp_http_validate_url( $url ) || ! in_array( $file_extension, ['jpg', 'jpeg', 'png'] ) ) {
                continue;
            }

            // Check if image already downloaded
            if ( ! isset( $demo_imported_urls[ $url ] ) ) {
                $demo_imported_urls[ $url ] = media_sideload_image( $url, 1, null, 'src' );
            }

            // $url = media_sideload_image( $url, 1, null, 'src' );
            if ( 'string' === gettype( $image_urls ) ) {
                $image_urls = $demo_imported_urls[ $url ];
                break;
            }

            $image_urls[ $index ] = $demo_imported_urls[ $url ];
        }

        delete_option('_ml_demo_imported_urls');
        add_option('_ml_demo_imported_urls', $demo_imported_urls, '', false);

        return maybe_serialize( $image_urls );
    }

    private function elementor_fonts_data() {
        return [
            '1' => [
                'font_family' => '',
                'font_weight' => '600',
            ],
            '2' => [
                'font_family' => '',
                'font_weight' => '400',
            ],
            '3' => [
                'font_family' => '',
                'font_weight' => '400',
            ],
            '4' => [
                'font_family' => '',
                'font_weight' => '500',
            ]
        ];
    }

    private function load_demos() {
        new Demos\MyListing_Main;
        new Demos\MyListing_Home;
        new Demos\MyListing_Car;
    }
}
