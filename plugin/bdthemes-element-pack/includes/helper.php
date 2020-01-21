<?php
use ElementPack\Element_Pack_Loader;

/**
 * You can easily add white label branding for for extended license or multi site license. 
 * Don't try for regular license otherwise your license will be invalid.
 * return white label
 */

if ( !defined('BDTEP') ) { define( 'BDTEP', '' ); } //Add prefix for all widgets <span class="bdt-widget-badge"></span>
if ( !defined('BDTEP_CP') ) { define( 'BDTEP_CP', '<span class="bdt-widget-badge"></span>' ); } // if you have any custom style
if ( !defined('BDTEP_SLUG') ) { define( 'BDTEP_SLUG', 'element-pack' ); } // set your own alias 
if ( !defined('BDTEP_TITLE') ) { define( 'BDTEP_TITLE', 'Element Pack' ); } // Set your own name for plugin


/**
 * Show any alert by this function
 * @param  mixed  $message [description]
 * @param  class prefix  $type    [description]
 * @param  boolean $close   [description]
 * @return helper           [description]
 */
function element_pack_alert($message, $type = 'warning', $close = true) {
    ?>
    <div class="bdt-alert-<?php echo $type; ?>" bdt-alert>
        <?php if($close) : ?>
            <a class="bdt-alert-close" bdt-close></a>
        <?php endif; ?>
        <?php echo wp_kses_post( $message ); ?>
    </div>
    <?php
}

/**
 * all array css classes will output as proper space
 * @param array $classes shortcode css class as array
 * @return proper string
 */

function element_pack_get_post_types($args = []){

    $post_type_args = [
        'show_in_nav_menus' => true,
    ];

    if ( ! empty( $args['post_type'] ) ) {
        $post_type_args['name'] = $args['post_type'];
    }

    $_post_types = get_post_types( $post_type_args , 'objects' );

    $post_types  = ['0' => esc_html__( 'Select Type', 'bdthemes-element-pack' ) ];

    foreach ( $_post_types as $post_type => $object ) {
        $post_types[ $post_type ] = $object->label;
    }

    return $post_types;
}

/**
 * Add REST API support to an already registered post type.
 */

// function bdt_custom_post_type_rest_support() {
//     global $wp_post_types;

//     $post_types = element_pack_get_post_types();
//     foreach( $post_types as $post_type ) {
//         $post_type_name = $post_type;
//         if( isset( $wp_post_types[ $post_type_name ] ) ) {
//             $wp_post_types[$post_type_name]->show_in_rest = true;
//             $wp_post_types[$post_type_name]->rest_base = $post_type_name;
//             $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
//         }
//     }

// }

// add_action( 'init', 'bdt_custom_post_type_rest_support', 25 );


function element_pack_allow_tags( $tag = null ) {
    $tag_allowed = wp_kses_allowed_html('post');

    $tag_allowed['input'] = [
        'class'   => [],
        'id'      => [],
        'name'    => [],
        'value'   => [],
        'checked' => [],
        'type'    => [],
    ];
    $tag_allowed['select'] = [
        'class'    => [],
        'id'       => [],
        'name'     => [],
        'value'    => [],
        'multiple' => [],
        'type'     => [],
    ];
    $tag_allowed['option'] = [
        'value'    => [],
        'selected' => [],
    ];

    $tag_allowed['title'] = [
        'a'      => [
            'href'  => [],
            'title' => [],
            'class' => [],
        ],
        'br'     => [],
        'em'     => [],
        'strong' => [],
        'hr' => [],
    ];

    $tag_allowed['text'] = [
        'a'      => [
            'href'  => [],
            'title' => [],
            'class' => [],
        ],
        'br'     => [],
        'em'     => [],
        'strong' => [],
        'hr'     => [],
        'i'      => [
            'class' => [],
        ],
        'span'   => [
            'class' => [],
        ],
    ];

    $tag_allowed['svg'] = [
        'svg'      => [
            'version'  => [],
            'xmlns' => [],
            'viewbox' => [],
            'xml:space' => [],
            'xmlns:xlink' => [],
            'x' => [],
            'y' => [],
            'style' => [],
        ],
        'g'     => [],
        'path'     => [
            'class' => [],
            'd' => [],
        ],
        'ellipse' => [
	        'class' => [],
	        'cx' => [],
            'cy' => [],
            'rx' => [],
            'ry' => [],
        ],
        'circle' => [
	        'class' => [],
	        'cx' => [],
            'cy' => [],
            'r'  => [],
        ],
        'rect' => [
	        'x' => [],
            'y' => [],
            'transform' => [],
            'height' => [],
            'width' => [],
            'class' => [],
        ],
        'line' => [
            'class' => [],
            'x1' => [],
            'x2' => [],
            'y1' => [],
            'y2' => [],
        ],
        'style' => [],


    ];

    if( $tag == null ){
        return $tag_allowed;
    } elseif( is_array($tag) ){
        $new_tag_allow = [];
        
        foreach ( $tag as $_tag ){
            $new_tag_allow[$_tag] = $tag_allowed[$_tag];
        }

        return $new_tag_allow;
    } else {
        return isset($tag_allowed[$tag]) ? $tag_allowed[$tag] : [];
    }
}

/**
 * post pagination
 */
function element_pack_post_pagination($wp_query) {

    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 ) {
        return;
    }

    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;

    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    echo '<ul class="bdt-pagination bdt-flex-center">' . "\n";

    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link('<span bdt-pagination-previous></span>') );

    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="current"' : '';

        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

        if ( ! in_array( 2, $links ) )
            echo '<li class="bdt-pagination-dot-dot"><span>...</span></li>';
    }

    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="bdt-active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }

    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li class="bdt-pagination-dot-dot"><span>...</span></li>' . "\n";

        $class = $paged == $max ? ' class="bdt-active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }

    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link('<span bdt-pagination-next></span>') );

    echo '</ul>' . "\n";
}

function element_pack_template_edit_link( $template_id ) {
    if ( Element_Pack_Loader::elementor()->editor->is_edit_mode() ) {
        
        $final_url = add_query_arg( [ 'elementor' => '' ], get_permalink( $template_id ) );

        $output = sprintf( '<a class="bdt-elementor-template-edit-link" href="%s" title="%s" target="_blank"><i class="eicon-edit"></i></a>', esc_url( $final_url ), esc_html__( 'Edit Template', 'bdthemes-element-pack' ) );

        return $output;
    }
}


function element_pack_iso_time($time) {
    $current_offset = (float) get_option( 'gmt_offset' );
    $timezone_string = get_option( 'timezone_string' );

    // Create a UTC+- zone if no timezone string exists.
    //if ( empty( $timezone_string ) ) {
        if ( 0 === $current_offset ) {
            $timezone_string = '+00:00';
        } elseif ( $current_offset < 0 ) {
            $timezone_string = $current_offset . ':00';
        } else {
            $timezone_string = '+0' . $current_offset . ':00';
        }
    //}

    $sub_time = [];
    $sub_time = explode(" ", $time);
    $final_time = $sub_time[0] .'T'. $sub_time[1] .':00' . $timezone_string;

    return $final_time;
}

function element_pack_currency_format( $currency, $precision = 1 ) {

    if ( $currency > 0 ) {
        if ($currency < 900 ) {
            // 0 - 900
            $currency_format = number_format($currency, $precision);
            $suffix = '';
        } else if ($currency < 900000) {
            // 0.9k-850k
            $currency_format = number_format($currency / 1000, $precision);
            $suffix = 'K';
        } else if ($currency < 900000000) {
            // 0.9m-850m
            $currency_format = number_format($currency / 1000000, $precision);
            $suffix = 'M';
        } else if ($currency < 900000000000) {
            // 0.9b-850b
            $currency_format = number_format($currency / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $currency_format = number_format($currency / 1000000000000, $precision);
            $suffix = 'T';
        }
      // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
      // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $currency_format = str_replace( $dotzero, '', $currency_format );
        }
        return $currency_format . $suffix;
    }

    return;
}


function element_pack_get_menu() {

    $menus = wp_get_nav_menus();
    $items = ['0' => esc_html__( 'Select Menu', 'bdthemes-element-pack' ) ];
    foreach ( $menus as $menu ) {
        $items[ $menu->slug ] = $menu->name;
    }

    return $items;
}

/**
 * default get_option() default value check
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function element_pack_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

// Anywhere Template
function element_pack_ae_options() {
    
    $data = get_transient( 'ep_anywhere_template' );

    if ( false === $data ) {

        if (post_type_exists('ae_global_templates')) {
            $anywhere = get_posts(array(
                'fields'         => 'ids', // Only get post IDs
                'posts_per_page' => -1,
                'post_type'      => 'ae_global_templates',
            ));

            $anywhere_options = ['0' => esc_html__( 'Select Template', 'bdthemes-element-pack' ) ];

            foreach ($anywhere as $key => $value) {
                $anywhere_options[$value] = get_the_title($value);
            }        
        } else {
            $anywhere_options = ['0' => esc_html__( 'AE Plugin Not Installed', 'bdthemes-element-pack' ) ];
        }

        set_transient( 'ep_anywhere_template', $anywhere_options, 120 );

        return get_transient( 'ep_anywhere_template' );
    }

    return $data;
}

// Elementor Saved Template 
function element_pack_et_options() {
    
    $data = get_transient( 'ep_elementor_template' );

    if ( false === $data ) {

        $templates = Element_Pack_Loader::elementor()->templates_manager->get_source( 'local' )->get_items();
        $types     = [];

        if ( empty( $templates ) ) {
            $template_options = [ '0' => __( 'You Haven’t Saved Templates Yet.', 'bdthemes-element-pack' ) ];
        } else {
            $template_options = [ '0' => __( 'Select Template', 'bdthemes-element-pack' ) ];
            
            foreach ( $templates as $template ) {
                $template_options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
                $types[ $template['template_id'] ] = $template['type'];
            }
        }

        set_transient( 'ep_elementor_template', $template_options, 120 );

        return get_transient( 'ep_elementor_template' );

    }

    return $data;
}

// Sidebar Widgets
function element_pack_sidebar_options() {

    $data = get_transient( 'ep_sidebar_options' );

    if ( false === $data ) {
        
        global $wp_registered_sidebars;
        $sidebar_options = [];

        if ( ! $wp_registered_sidebars ) {
            $sidebar_options['0'] = esc_html__( 'No sidebars were found', 'bdthemes-element-pack' );
        } else {
            $sidebar_options['0'] = esc_html__( 'Select Sidebar', 'bdthemes-element-pack' );

            foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
                $sidebar_options[ $sidebar_id ] = $sidebar['name'];
            }
        }

        set_transient( 'ep_sidebar_options', $sidebar_options, DAY_IN_SECONDS );

        return get_transient( 'ep_sidebar_options' );
    }

    return $data;
}

/**
 * @param terms category name
 * @type category option name type
 * @return category array
 */
function element_pack_get_category($terms, $type = 'slug' ) {
    
    $post_categories = get_terms( $terms );

    $post_options = [];
    foreach ( $post_categories as $category ) {
        $post_options[ $category->$type ] = $category->name;
    }

    return $post_options;
}

/**
 * @param  array all ajax posted array there
 * @return array return all setting as array
 */
function element_pack_ajax_settings($settings) {

    $required_settings = [
        'show_date'      => true,
        'show_comment'   => true,
        'show_link'      => true,
        'show_meta'      => true,
        'show_title'     => true,
        'show_excerpt'   => true,
        'show_lightbox'  => true,
        'show_thumbnail' => true,
        'show_category'  => false,
        'show_tags'      => false,
    ];

    foreach ( $settings as $key => $value ) {
        if ( in_array( $key, $required_settings ) ) {
            $required_settings[$key] = $value;
        }
    }

    return $required_settings;
}

/**
 * @return array list of all transition names
 */
function element_pack_transition_options() {


    $transition_options = [
        ''                    => esc_html__('None', 'bdthemes-element-pack'),
        'fade'                => esc_html__('Fade', 'bdthemes-element-pack'),
        'scale-up'            => esc_html__('Scale Up', 'bdthemes-element-pack'),
        'scale-down'          => esc_html__('Scale Down', 'bdthemes-element-pack'),
        'slide-top'           => esc_html__('Slide Top', 'bdthemes-element-pack'),
        'slide-bottom'        => esc_html__('Slide Bottom', 'bdthemes-element-pack'),
        'slide-left'          => esc_html__('Slide Left', 'bdthemes-element-pack'),
        'slide-right'         => esc_html__('Slide Right', 'bdthemes-element-pack'),
        'slide-top-small'     => esc_html__('Slide Top Small', 'bdthemes-element-pack'),
        'slide-bottom-small'  => esc_html__('Slide Bottom Small', 'bdthemes-element-pack'),
        'slide-left-small'    => esc_html__('Slide Left Small', 'bdthemes-element-pack'),
        'slide-right-small'   => esc_html__('Slide Right Small', 'bdthemes-element-pack'),
        'slide-top-medium'    => esc_html__('Slide Top Medium', 'bdthemes-element-pack'),
        'slide-bottom-medium' => esc_html__('Slide Bottom Medium', 'bdthemes-element-pack'),
        'slide-left-medium'   => esc_html__('Slide Left Medium', 'bdthemes-element-pack'),
        'slide-right-medium'  => esc_html__('Slide Right Medium', 'bdthemes-element-pack'),
    ];

    return $transition_options;
}

// BDT Blend Type
function element_pack_blend_options() {
    $blend_options = [
        'multiply'    => esc_html__( 'Multiply', 'bdthemes-element-pack' ),
        'screen'      => esc_html__( 'Screen', 'bdthemes-element-pack' ),
        'overlay'     => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
        'darken'      => esc_html__( 'Darken', 'bdthemes-element-pack' ),
        'lighten'     => esc_html__( 'Lighten', 'bdthemes-element-pack' ),
        'color-dodge' => esc_html__( 'Color-Dodge', 'bdthemes-element-pack' ),
        'color-burn'  => esc_html__( 'Color-Burn', 'bdthemes-element-pack' ),
        'hard-light'  => esc_html__( 'Hard-Light', 'bdthemes-element-pack' ),
        'soft-light'  => esc_html__( 'Soft-Light', 'bdthemes-element-pack' ),
        'difference'  => esc_html__( 'Difference', 'bdthemes-element-pack' ),
        'exclusion'   => esc_html__( 'Exclusion', 'bdthemes-element-pack' ),
        'hue'         => esc_html__( 'Hue', 'bdthemes-element-pack' ),
        'saturation'  => esc_html__( 'Saturation', 'bdthemes-element-pack' ),
        'color'       => esc_html__( 'Color', 'bdthemes-element-pack' ),
        'luminosity'  => esc_html__( 'Luminosity', 'bdthemes-element-pack' ),
    ];

    return $blend_options;
}

// BDT Position
function element_pack_position() {
    $position_options = [
        ''              => esc_html__('Default', 'bdthemes-element-pack'),
        'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack') ,
        'top-center'    => esc_html__('Top Center', 'bdthemes-element-pack') ,
        'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack') ,
        'center'        => esc_html__('Center', 'bdthemes-element-pack') ,
        'center-left'   => esc_html__('Center Left', 'bdthemes-element-pack') ,
        'center-right'  => esc_html__('Center Right', 'bdthemes-element-pack') ,
        'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack') ,
        'bottom-center' => esc_html__('Bottom Center', 'bdthemes-element-pack') ,
        'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack') ,
    ];

    return $position_options;
}

// BDT Thumbnavs Position
function element_pack_thumbnavs_position() {
    $position_options = [
        'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack') ,
        'top-center'    => esc_html__('Top Center', 'bdthemes-element-pack') ,
        'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack') ,
        'center-left'   => esc_html__('Center Left', 'bdthemes-element-pack') ,
        'center-right'  => esc_html__('Center Right', 'bdthemes-element-pack') ,
        'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack') ,
        'bottom-center' => esc_html__('Bottom Center', 'bdthemes-element-pack') ,
        'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack') ,
    ];

    return $position_options;
}

function element_pack_navigation_position() {
    $position_options = [
        'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack') ,
        'top-center'    => esc_html__('Top Center', 'bdthemes-element-pack') ,
        'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack') ,
        'center'        => esc_html__('Center', 'bdthemes-element-pack') ,
        'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack') ,
        'bottom-center' => esc_html__('Bottom Center', 'bdthemes-element-pack') ,
        'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack') ,
    ];

    return $position_options;
}


function element_pack_pagination_position() {
    $position_options = [
        'top-left'      => esc_html__('Top Left', 'bdthemes-element-pack') ,
        'top-center'    => esc_html__('Top Center', 'bdthemes-element-pack') ,
        'top-right'     => esc_html__('Top Right', 'bdthemes-element-pack') ,
        'bottom-left'   => esc_html__('Bottom Left', 'bdthemes-element-pack') ,
        'bottom-center' => esc_html__('Bottom Center', 'bdthemes-element-pack') ,
        'bottom-right'  => esc_html__('Bottom Right', 'bdthemes-element-pack') ,
    ];

    return $position_options;
}

// BDT Drop Position
function element_pack_drop_position() {
    $drop_position_options = [
        'bottom-left'    => esc_html__('Bottom Left', 'bdthemes-element-pack'),
        'bottom-center'  => esc_html__('Bottom Center', 'bdthemes-element-pack'),
        'bottom-right'   => esc_html__('Bottom Right', 'bdthemes-element-pack'),
        'bottom-justify' => esc_html__('Bottom Justify', 'bdthemes-element-pack'),
        'top-left'       => esc_html__('Top Left', 'bdthemes-element-pack'),
        'top-center'     => esc_html__('Top Center', 'bdthemes-element-pack'),
        'top-right'      => esc_html__('Top Right', 'bdthemes-element-pack'),
        'top-justify'    => esc_html__('Top Justify', 'bdthemes-element-pack'),
        'left-top'       => esc_html__('Left Top', 'bdthemes-element-pack'),
        'left-center'    => esc_html__('Left Center', 'bdthemes-element-pack'),
        'left-bottom'    => esc_html__('Left Bottom', 'bdthemes-element-pack'),
        'right-top'      => esc_html__('Right Top', 'bdthemes-element-pack'),
        'right-center'   => esc_html__('Right Center', 'bdthemes-element-pack'),
        'right-bottom'   => esc_html__('Right Bottom', 'bdthemes-element-pack'),
    ];

    return $drop_position_options;
}

// Button Size
function element_pack_button_sizes() {
    $button_sizes = [
        'xs' => esc_html__( 'Extra Small', 'bdthemes-element-pack' ),
        'sm' => esc_html__( 'Small', 'bdthemes-element-pack' ),
        'md' => esc_html__( 'Medium', 'bdthemes-element-pack' ),
        'lg' => esc_html__( 'Large', 'bdthemes-element-pack' ),
        'xl' => esc_html__( 'Extra Large', 'bdthemes-element-pack' ),
    ];

    return $button_sizes;
}

// Button Size
function element_pack_heading_size() {
    $heading_sizes = [
        'h1' => esc_html__( 'H1', 'bdthemes-element-pack' ),
        'h2' => esc_html__( 'H2', 'bdthemes-element-pack' ),
        'h3' => esc_html__( 'H3', 'bdthemes-element-pack' ),
        'h4' => esc_html__( 'H4', 'bdthemes-element-pack' ),
        'h5' => esc_html__( 'H5', 'bdthemes-element-pack' ),
        'h6' => esc_html__( 'H6', 'bdthemes-element-pack' ),
    ];

    return $heading_sizes;
}

// Title Tags
function element_pack_title_tags() {
    $title_tags = [
        'h1'   => esc_html__( 'H1', 'bdthemes-element-pack' ),
        'h2'   => esc_html__( 'H2', 'bdthemes-element-pack' ),
        'h3'   => esc_html__( 'H3', 'bdthemes-element-pack' ),
        'h4'   => esc_html__( 'H4', 'bdthemes-element-pack' ),
        'h5'   => esc_html__( 'H5', 'bdthemes-element-pack' ),
        'h6'   => esc_html__( 'H6', 'bdthemes-element-pack' ),
        'div'  => esc_html__( 'div', 'bdthemes-element-pack' ),
        'span' => esc_html__( 'span', 'bdthemes-element-pack' ),
        'p'    => esc_html__( 'p', 'bdthemes-element-pack' ),
    ];

    return $title_tags;
}
/**
 * This is a svg file converter function which return a svg content 
 * @param  svg file
 * @return svg content
 */
function element_pack_svg_icon($icon) {

    $icon_path = BDTEP_ASSETS_PATH . "images/svg/{$icon}.svg";

    if ( !file_exists( $icon_path ) ) { return false; }

    ob_start();

    include $icon_path;

    $svg = ob_get_clean();

    return $svg;
}

/**
 * weather code to icon and description output
 * more info: http://www.apixu.com/doc/Apixu_weather_conditions.json
 */
function element_pack_weather_code( $code = null, $condition = null ) {

    $codes = apply_filters( 'element-pack/weather/codes', [
	    "113" => [
		    "desc" => esc_html_x("Clear/Sunny", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "113"
	    ],
	    "116" => [
		    "desc" => esc_html_x("Partly cloudy", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "116"
	    ],
	    "119" => [
		    "desc" => esc_html_x("Cloudy", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "119"
	    ],
	    "122" => [
		    "desc" => esc_html_x("Overcast", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "122"
	    ],
	    "143" => [
		    "desc" => esc_html_x("Mist", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "143"
	    ],
	    "176" => [
		    "desc" => esc_html_x("Patchy rain nearby", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "176"
	    ],
	    "179" => [
		    "desc" => esc_html_x("Patchy snow nearby", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "179"
	    ],
	    "182" => [
		    "desc" => esc_html_x("Patchy sleet nearby", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "182"
	    ],
	    "185" => [
		    "desc" => esc_html_x("Patchy freezing drizzle nearby", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "185"
	    ],
	    "200" => [
		    "desc" => esc_html_x("Thundery outbreaks nearby", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "200"
	    ],
	    "227" => [
		    "desc" => esc_html_x("Blowing snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "227"
	    ],
	    "230" => [
		    "desc" => esc_html_x("Blizzard", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "230"
	    ],
	    "248" => [
		    "desc" => esc_html_x("Fog", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "248"
	    ],
	    "260" => [
		    "desc" => esc_html_x("Freezing fog", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "260"
	    ],
	    "263" => [
		    "desc" => esc_html_x("Patchy light drizzle", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "263"
	    ],
	    "266" => [
		    "desc" => esc_html_x("Light drizzle", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "266"
	    ],
	    "281" => [
		    "desc" => esc_html_x("Freezing drizzle", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "281"
	    ],
	    "284" => [
		    "desc" => esc_html_x("Heavy freezing drizzle", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "284"
	    ],
	    "293" => [
		    "desc" => esc_html_x("Patchy light rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "293"
	    ],
	    "296" => [
		    "desc" => esc_html_x("Light rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "296"
	    ],
	    "299" => [
		    "desc" => esc_html_x("Moderate rain at times", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "299"
	    ],
	    "302" => [
		    "desc" => esc_html_x("Moderate rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "302"
	    ],
	    "305" => [
		    "desc" => esc_html_x("Heavy rain at times", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "305"
	    ],
	    "308" => [
		    "desc" => esc_html_x("Heavy rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "308"
	    ],
	    "311" => [
		    "desc" => esc_html_x("Light freezing rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "311"
	    ],
	    "314" => [
		    "desc" => esc_html_x("Moderate or heavy freezing rain", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "314"
	    ],
	    "317" => [
		    "desc" => esc_html_x("Light sleet", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "317"
	    ],
	    "320" => [
		    "desc" => esc_html_x("Moderate or heavy sleet", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "320"
	    ],
	    "323" => [
		    "desc" => esc_html_x("Patchy light snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "323"
	    ],
	    "326" => [
		    "desc" => esc_html_x("Light snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "326"
	    ],
	    "329" => [
		    "desc" => esc_html_x("Patchy moderate snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "329"
	    ],
	    "332" => [
		    "desc" => esc_html_x("Moderate snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "332"
	    ],
	    "335" => [
		    "desc" => esc_html_x("Patchy heavy snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "335"
	    ],
	    "338" => [
		    "desc" => esc_html_x("Heavy snow", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "338"
	    ],
	    "350" => [
		    "desc" => esc_html_x("Ice pellets", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "350"
	    ],
	    "353" => [
		    "desc" => esc_html_x("Light rain shower", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "353"
	    ],
	    "356" => [
		    "desc" => esc_html_x("Moderate or heavy rain shower", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "356"
	    ],
	    "359" => [
		    "desc" => esc_html_x("Torrential rain shower", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "359"
	    ],
	    "362" => [
		    "desc" => esc_html_x("Light sleet showers", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "362"
	    ],
	    "365" => [
		    "desc" => esc_html_x("Moderate or heavy sleet showers", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "365"
	    ],
	    "368" => [
		    "desc" => esc_html_x("Light snow showers", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "368"
	    ],
	    "371" => [
		    "desc" => esc_html_x("Moderate or heavy snow showers", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "371"
	    ],
	    "374" => [
		    "desc" => esc_html_x("Light showers of ice pellets", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "374"
	    ],
	    "377" => [
		    "desc" => esc_html_x("Moderate or heavy showers of ice pellets", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "377"
	    ],
	    "386" => [
		    "desc" => esc_html_x("Patchy light rain with thunder", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "386"
	    ],
	    "389" => [
		    "desc" => esc_html_x("Moderate or heavy rain with thunder", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "389"
	    ],
	    "392" => [
		    "desc" => esc_html_x("Patchy light snow with thunder", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "392"
	    ],
	    "395" => [
		    "desc" => esc_html_x("Moderate or heavy snow with thunder", "Weather String", "bdthemes-element-pack" ),
		    "icon" => "395"
	    ]
    ]);

    if ( ! $code ) {
        return $codes;
    }

    $code_key = (string) $code;

    if ( ! isset( $codes[ $code_key ] ) ) {
        return false;
    }

    if ( $condition && isset( $codes[ $code_key ][ $condition ] ) ) {
        return $codes[ $code_key ][ $condition ];
    }

    return $codes[ $code_key ];
}

function element_pack_wind_code( $degree ) {
    
    $direction = '';

    if ( ( $degree >= 0 && $degree <= 33.75 ) or ( $degree > 348.75 && $degree <= 360 ) ) {
        $direction = esc_html_x( 'north', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 33.75 && $degree <= 78.75 ) {
        $direction = esc_html_x( 'north-east', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 78.75 && $degree <= 123.75 ) {
        $direction = esc_html_x( 'east', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 123.75 && $degree <= 168.75 ) {
        $direction = esc_html_x( 'south-east', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 168.75 && $degree <= 213.75 ) {
        $direction = esc_html_x( 'south', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 213.75 && $degree <= 258.75 ) {
        $direction = esc_html_x( 'south-west', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 258.75 && $degree <= 303.75 ) {
        $direction = esc_html_x( 'west', 'Weather String', 'bdthemes-element-pack' );
    } else if ( $degree > 303.75 && $degree <= 348.75 ) {
        $direction = esc_html_x( 'north-west', 'Weather String', 'bdthemes-element-pack' );
    }

    return $direction;
}


function element_pack_parse_csv($file) {
    
    if (!isset($file)) { return; }

    $skip_char = $column = '';
    $csv_lines = file( $file );
    if ( is_array( $csv_lines ) ) {
        $cnt = count( $csv_lines );
        for ( $i = 0; $i < $cnt; $i++ ) {
            $line = $csv_lines[$i];
            $line = trim( $line );
            $first_char = true;
            $col_num = 0;
            $length = strlen( $line );
            for ( $b = 0; $b < $length; $b++ ) {
                if ( $skip_char != true ) {
                    $process = true;
                    if ( $first_char == true ) {
                        if ( $line[$b] == '"' ) {
                            $terminator = '";';
                            $process = false;
                        }
                        else
                            $terminator = ';';
                        $first_char = false;
                    }
                    if ( $line[$b] == '"' ) {
                        $next_char = $line[$b + 1];
                        if ( $next_char == '"' ) $skip_char = true;
                        elseif ( $next_char == ';' ) {
                            if ( $terminator == '";' ) {
                                $first_char = true;
                                $process = false;
                                $skip_char = true;
                            }
                        }
                    }
                    if ( $process == true ) {
                        if ( $line[$b] == ';' ) {
                            if ( $terminator == ';' ) {
                                $first_char = true;
                                $process = false;
                            }
                        }
                    }
                    if ( $process == true ) $column .= $line[$b];
                    if ( $b == ( $length - 1 ) ) $first_char = true;
                    if ( $first_char == true ) {
                        $values[$i][$col_num] = $column;
                        $column = '';
                        $col_num++;
                    }
                }
                else
                    $skip_char = false;
            }
        }
    }
    $return = '<table><thead><tr>';
    foreach ( $values[0] as $value ) $return .= '<th>' . $value . '</th>';
    $return .= '</tr></thead><tbody>';
    array_shift( $values );
    foreach ( $values as $rows ) {
        $return .= '<tr>';
        foreach ( $rows as $col ) {
            $return .= '<td>' . $col . '</td>';
        }
        $return .= '</tr>';
    }
    $return .= '</tbody></table>';
    return $return;
}

/**
 * String to ID maker for any title to relavent id
 * @param  [type] $string any title or string
 * @return [type]         [description]
 */
function element_pack_string_id($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    //finally return here
    return $string;
}

function element_pack_instagram_feed( $item_count = 100 ) {

    $options        = get_option( 'element_pack_api_settings' );
    $access_token   = (!empty($options['instagram_access_token'])) ? $options['instagram_access_token'] : '';

    if ($access_token) {

        $data = get_transient( 'ep_instagram_feed_data' );

        if ( false === $data ) {

            $url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token. '&count=' . $item_count;

            $feeds_json = wp_remote_fopen( $url );

            $feeds_obj  = json_decode( $feeds_json, true );

            //print_r($feeds_obj);

            $feeds_images_array = [];
            $instagram_user = [];
            $ins_counter = 1;

            if ( 200 == $feeds_obj['meta']['code'] ) {

                if ( ! empty( $feeds_obj['data'] ) ) {

                    foreach ( $feeds_obj['data'] as $data ) {

                        array_push( $feeds_images_array, 
                            array(
                                'image' => [
                                    'small'  => $data['images']['thumbnail']['url'], // thumbnail image
                                    'medium' => $data['images']['low_resolution']['url'], // medium image
                                    'large'  => $data['images']['standard_resolution']['url'], // large image
                                ],
                                'link'      => $data['link'],
                                'like'      => $data['likes']['count'],
                                'comment'   => [
                                    'count' => $data['comments']['count']
                                ],
                                //'text'      => $data['text'],
                                'post_type' => $data['type'],
                                'user'      => $data['user'],
                            ) 
                        );

                        if ( 1 == $ins_counter ) {
                            $instagram_user = $data['user'];
                            $ins_counter++;
                        }


                    }

                    //return $feeds_images_array;

                    set_transient( 'ep_instagram_feed_data', $feeds_images_array, HOUR_IN_SECONDS * 12 );
                    set_transient( 'ep_instagram_user', $instagram_user, HOUR_IN_SECONDS * 12 );

                    return get_transient( 'ep_instagram_feed_data' );
                }
            }
        }

        return $data;
    }
}


function element_pack_instagram_card() {

	$options        = get_option( 'element_pack_api_settings' );
	$access_token   = (!empty($options['instagram_access_token'])) ? $options['instagram_access_token'] : '';

	if ($access_token) {

		$data = get_transient( 'ep_instagram_card_data' );

		if ( false === $data ) {

			$url = 'https://api.instagram.com/v1/users/self/?access_token=' . $access_token;

			$feeds_json = wp_remote_fopen( $url );

			$output  = json_decode( $feeds_json, true );

			if ( 200 == $output['meta']['code'] ) {

				if ( ! empty( $output['data'] ) ) {

					return $output['data'];

					set_transient( 'ep_instagram_card_data', $output['data'], HOUR_IN_SECONDS * 12 );

					return get_transient( 'ep_instagram_card_data' );
				}
			}
		}

		return $data;
	}
}


/**
 * Ninja form array creator for get all form as 
 * @return array [description]
 */
function element_pack_ninja_forms_options() {

    if ( class_exists( 'Ninja_Forms' ) ) {
        $ninja_forms  = Ninja_Forms()->form()->get_forms();
        if ( ! empty( $ninja_forms ) && ! is_wp_error( $ninja_forms ) ) {
            $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
            foreach ( $ninja_forms as $form ) {   
                $form_options[ $form->get_id() ] = $form->get_setting( 'title' );
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}

function element_pack_caldera_forms_options() {

    if ( class_exists( 'Caldera_Forms' ) ) {
        $caldera_forms = Caldera_Forms_Forms::get_forms( true, true );
        $form_options  = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        $form          = [];
        if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
            foreach ( $caldera_forms as $form ) {
                if ( isset($form['ID']) and isset($form['name'])) {
                    $form_options[$form['ID']] = $form['name'];
                }   
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }
       
    return $form_options;
}

function element_pack_quform_options() {
    
    $data = get_transient( 'ep_quform_form_options' );

    if ( class_exists( 'Quform' ) ) {
        $quform       = Quform::getService('repository');
        $quform       = $quform->formsToSelectArray();
        $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        if ( ! empty( $quform ) && ! is_wp_error( $quform ) ) {
            foreach ( $quform as $id => $name ) {
                $form_options[esc_attr( $id )] = esc_html( $name );
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}




function element_pack_gravity_forms_options() {


    if ( class_exists( 'GFCommon' ) ) {
        $contact_forms = RGFormsModel::get_forms( null, 'title' );
        $form_options = ['0' => esc_html__( 'Select Form', 'bdthemes-element-pack' )];
        if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
            foreach ( $contact_forms as $form ) {   
                $form_options[ $form->id ] = $form->title;
            }
        }
    } else {
        $form_options = ['0' => esc_html__( 'Form Not Found!', 'bdthemes-element-pack' ) ];
    }

    return $form_options;
}


function element_pack_rev_slider_options() {

    if( class_exists( 'RevSlider' ) ){
        $slider             = new RevSlider();
        $revolution_sliders = $slider->getArrSliders();
        $slider_options     = ['0' => esc_html__( 'Select Slider', 'bdthemes-element-pack' ) ];
        if ( ! empty( $revolution_sliders ) && ! is_wp_error( $revolution_sliders ) ) {
            foreach ( $revolution_sliders as $revolution_slider ) {
               $alias = $revolution_slider->getAlias();
               $title = $revolution_slider->getTitle();
               $slider_options[$alias] = $title;
            }
        }
    } else {
        $slider_options = ['0' => esc_html__( 'No Slider Found!', 'bdthemes-element-pack' ) ];
    }

    return $slider_options;
}

function element_pack_dashboard_link( $suffix = '#welcome' ) {
    return add_query_arg( [ 'page' => 'element_pack_options' . $suffix ], admin_url( 'admin.php' ) );
}

function element_pack_currency_symbol( $currency = '' ) {
    switch ( strtoupper( $currency ) ) {
        case 'AED' :
            $currency_symbol = 'د.إ';
            break;
        case 'AUD' :
        case 'CAD' :
        case 'CLP' :
        case 'COP' :
        case 'HKD' :
        case 'MXN' :
        case 'NZD' :
        case 'SGD' :
        case 'USD' :
            $currency_symbol = '&#36;';
            break;
        case 'BDT':
            $currency_symbol = '&#2547;&nbsp;';
            break;
        case 'BGN' :
            $currency_symbol = '&#1083;&#1074;.';
            break;
        case 'BIF':
            $currency_symbol = 'FBu';
            break;
        case 'BRL' :
            $currency_symbol = '&#82;&#36;';
            break;
        case 'CHF' :
            $currency_symbol = '&#67;&#72;&#70;';
            break;
        case 'CNY' :
        case 'JPY' :
        case 'RMB' :
            $currency_symbol = '&yen;';
            break;
        case 'CZK' :
            $currency_symbol = '&#75;&#269;';
            break;
        case 'DJF':
            $currency_symbol = 'Fdj';
            break;
        case 'DKK' :
            $currency_symbol = 'DKK';
            break;
        case 'DOP' :
            $currency_symbol = 'RD&#36;';
            break;
        case 'EGP' :
            $currency_symbol = 'EGP';
            break;
        case 'ETB':
            $currency_symbol = 'ETB';
            break;
        case 'EUR' :
            $currency_symbol = '&euro;';
            break;
        case 'GBP' :
            $currency_symbol = '&pound;';
            break;
        case 'GHS':
            $currency_symbol = 'GH₵';
            break;
        case 'HRK' :
            $currency_symbol = 'Kn';
            break;
        case 'HUF' :
            $currency_symbol = '&#70;&#116;';
            break;
        case 'IDR' :
            $currency_symbol = 'Rp';
            break;
        case 'ILS' :
            $currency_symbol = '&#8362;';
            break;
        case 'INR' :
            $currency_symbol = 'Rs.';
            break;
        case 'ISK' :
            $currency_symbol = 'Kr.';
            break;
        case 'IRR' :
            $currency_symbol = '﷼';
            break;
        case 'KES':
            $currency_symbol = 'KSh';
            break;
        case 'KIP' :
            $currency_symbol = '&#8365;';
            break;
        case 'KRW' :
            $currency_symbol = '&#8361;';
            break;
        case 'MYR' :
            $currency_symbol = '&#82;&#77;';
            break;
        case 'NGN' :
            $currency_symbol = '&#8358;';
            break;
        case 'NOK' :
            $currency_symbol = '&#107;&#114;';
            break;
        case 'NPR' :
            $currency_symbol = 'Rs.';
            break;
        case 'PHP' :
            $currency_symbol = '&#8369;';
            break;
        case 'PKR' :
            $currency_symbol = 'Rs.';
            break;
        case 'PLN' :
            $currency_symbol = '&#122;&#322;';
            break;
        case 'PYG' :
            $currency_symbol = '&#8370;';
            break;
        case 'RON' :
            $currency_symbol = 'lei';
            break;
        case 'RUB' :
            $currency_symbol = '&#1088;&#1091;&#1073;.';
            break;
        case 'RWF':
            $currency_symbol = 'FRw';
            break;
        case 'SEK' :
            $currency_symbol = '&#107;&#114;';
            break;
        case 'THB' :
            $currency_symbol = '&#3647;';
            break;
        case 'TND' :
            $currency_symbol = 'DT';
            break;
        case 'TRY' :
            $currency_symbol = '&#8378;';
            break;
        case 'TWD' :
            $currency_symbol = '&#78;&#84;&#36;';
            break;
        case 'TZS':
            $currency_symbol = 'TSh';
            break;
        case 'UAH' :
            $currency_symbol = '&#8372;';
            break;
        case 'UGX':
            $currency_symbol = 'USh';
            break;
        case 'VND' :
            $currency_symbol = '&#8363;';
            break;
        case 'XAF':
            $currency_symbol = 'CFA';
            break;
        case 'ZAR' :
            $currency_symbol = '&#82;';
            break;
        default :
            $currency_symbol = '';
            break;
    }

    return apply_filters( 'element_pack_currency_symbol', $currency_symbol, $currency );
}

function element_pack_money_format($value) {
    
    if ( function_exists( 'money_format' ) ) {
        $value = money_format( '%i', $value );
    } else {
        $value = sprintf( '%01.2f', $value );
    }

    return $value;
}




/**
 * helper functions class for helping some common usage things
 */
if (!class_exists('element_pack_helper')) {
    class element_pack_helper {

        static $selfClosing = ['input'];

        /**
         * Renders a tag.
         *
         * @param  string $name
         * @param  array  $attrs
         * @param  string $text
         * @return string
         */
        public static function tag($name, array $attrs = [], $text = null) {
            $attrs = self::attrs($attrs);
            return "<{$name}{ $attrs }" . (in_array($name, self::$selfClosing) ? '/>' : ">$text</{$name}>");
        }

        /**
         * Renders a form tag.
         *
         * @param  array $tags
         * @param  array $attrs
         * @return string
         */
        public static function form($tags, array $attrs = []) {
            $attrs = self::attrs($attrs);
            return "<form{$attrs}>\n" . implode("\n", array_map(function($tag) {
                $output = self::tag($tag['tag'], array_diff_key($tag, ['tag' => null]));
                return $output;
            }, $tags)) . "\n</form>";
        }

        /**
         * Renders an image tag.
         *
         * @param  array|string $url
         * @param  array        $attrs
         * @return string
         */
        public static function image($url, array $attrs = []) {
            $url = (array) $url;
            $path = array_shift($url);
            $params = $url ? '?'.http_build_query(array_map(function ($value) {
                return is_array($value) ? implode(',', $value) : $value;
            }, $url)) : '';

            if (!isset($attrs['alt']) || empty($attrs['alt'])) {
                $attrs['alt'] = true;
            }

            $output = self::attrs(['src' => $path.$params], $attrs);

            return "<img{$output}>";
        }
        
        /**
         * Renders tag attributes.
         * @param  array $attrs
         * @return string
         */
        public static function attrs(array $attrs) {
            $output = [];

            if (count($args = func_get_args()) > 1) {
                $attrs = call_user_func_array('array_merge_recursive', $args);
            }

            foreach ($attrs as $key => $value) {

                if (is_array($value)) { $value = implode(' ', array_filter($value)); }
                if (empty($value) && !is_numeric($value)) { continue; }

                if (is_numeric($key)) {
                   $output[] = $value;
                } elseif ($value === true) {
                   $output[] = $key;
                } elseif ($value !== '') {
                   $output[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false));
                }
            }

            return $output ? ' '.implode(' ', $output) : '';
        }

        /**
         * social icon generator from link
         * @param  [type] $link [description]
         * @return [type]       [description]
         */
        public static function icon($link) {
           static $icons;
           $icons = self::social_icons();

           if (strpos($link, 'mailto:') === 0) {
               return 'mail';
           }

           $icon = parse_url($link, PHP_URL_HOST);
           $icon = preg_replace('/.*?(plus\.google|[^\.]+)\.[^\.]+$/i', '$1', $icon);
           $icon = str_replace('plus.google', 'google-plus', $icon);

           if (!in_array($icon, $icons)) {
               $icon = 'social';
           }

           return $icon;
        }

        // most used social icons array
        public static function social_icons() {
           $icons = [ "behance", "dribbble", "facebook", "github-alt", "github", "foursquare", "tumblr", "whatsapp", "soundcloud", "flickr", "google-plus", "google", "linkedin", "vimeo", "instagram", "joomla", "pagekit", "pinterest", "twitter", "uikit", "wordpress", "xing", "youtube" ];

           return $icons;
        }


        public static function remove_p( $content ) {
            $content = force_balance_tags( $content );
            $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
            $content = preg_replace( '~\s?<p>(\s| )+</p>\s?~', '', $content );
            return $content;
        }

        /**
         * Get timezone id from server
         * @return [type] [description]
         */
        public static function get_timezone_id() {    
            $timezone = get_option( 'timezone_string' );

            /* If site timezone string exists, return it */
            if ( $timezone ) {
                return $timezone;
            }

            $utc_offset = 3600 * get_option( 'gmt_offset', 0 );

            /* Get UTC offset, if it isn't set return UTC */
            if ( ! $utc_offset ) {
                return 'UTC';
            }

            /* Attempt to guess the timezone string from the UTC offset */
            $timezone = timezone_name_from_abbr( '', $utc_offset );

            /* Last try, guess timezone string manually */
            if ( $timezone === false ) {

                $is_dst = date( 'I' );

                foreach ( timezone_abbreviations_list() as $abbr ) {
                    foreach ( $abbr as $city ) {
                        if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset ) {
                            return $city['timezone_id'];
                        }
                    }
                }
            }

            /* If we still haven't figured out the timezone, fall back to UTC */
            return 'UTC';
        }

        /**
         * ACtual CSS Class extrator
         * @param  [type] $classes [description]
         * @return [type]          [description]
         */
        public static function acssc($classes) {
            if (is_array($classes)) {
                $classes     = implode($classes, ' ');
            }
            $abs_classes = trim(preg_replace('/\s\s+/', ' ', $classes));
            return $abs_classes;
        }

        /**
         * Custom Excerpt Length
         * @param  integer $limit [description]
         * @return [type]         [description]
         */
        public static function custom_excerpt($limit=50, $trail = '...') {

            $output = strip_shortcodes( wp_trim_words( get_the_content(), $limit, $trail ) );

            return $output;
        }

    }
}