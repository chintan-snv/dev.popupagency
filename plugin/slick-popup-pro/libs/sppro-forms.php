<?php

class SPPRO_Forms {

	const post_type = 'sppro_forms';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $locale;
	private $properties = array();
	private $unit_tag;
	private $responses_count = 0;
	private $scanned_form_tags;
	private $shortcode_atts = array();

	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}
	
	public static function register_post_type() {

		$labels = array(
			'name'                  => esc_html__( 'Slick Popup', 'Post Type General Name', 'sp-pro-txt-domain' ),
			'singular_name'         => esc_html__( 'Slick Popup', 'Post Type Singular Name', 'sp-pro-txt-domain' ),
			'menu_name'             => esc_html__( 'Slick Popup', 'sp-pro-txt-domain' ),
			'name_admin_bar'        => esc_html__( 'Slick Popup', 'sp-pro-txt-domain' ),
			'archives'              => esc_html__( 'Popup Archives', 'sp-pro-txt-domain' ),
			'attributes'            => esc_html__( 'Popup Attributes', 'sp-pro-txt-domain' ),
			'parent_form_colon'     => esc_html__( 'Parent Popup:', 'sp-pro-txt-domain' ),
			'all_forms'             => esc_html__( 'All Popups', 'sp-pro-txt-domain' ),
			'add_new_form'          => esc_html__( 'Add New Popup', 'sp-pro-txt-domain' ),
			'add_new'               => esc_html__( 'Add New', 'sp-pro-txt-domain' ),
			'new_form'              => esc_html__( 'New Popup', 'sp-pro-txt-domain' ),
			'edit_form'             => esc_html__( 'Edit Popup', 'sp-pro-txt-domain' ),
			'update_form'           => esc_html__( 'Update Popup', 'sp-pro-txt-domain' ),
			'view_form'             => esc_html__( 'View Popup', 'sp-pro-txt-domain' ),
			'view_forms'            => esc_html__( 'View Popups', 'sp-pro-txt-domain' ),
			'search_forms'          => esc_html__( 'Search Popup', 'sp-pro-txt-domain' ),
			'not_found'             => esc_html__( 'Not found', 'sp-pro-txt-domain' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'sp-pro-txt-domain' ),
			'featured_image'        => esc_html__( 'Featured Image', 'sp-pro-txt-domain' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'sp-pro-txt-domain' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'sp-pro-txt-domain' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'sp-pro-txt-domain' ),
			'insert_into_form'      => esc_html__( 'Insert into form', 'sp-pro-txt-domain' ),
			'uploaded_to_this_form' => esc_html__( 'Uploaded to this form', 'sp-pro-txt-domain' ),
			'forms_list'            => esc_html__( 'Popups list', 'sp-pro-txt-domain' ),
			'forms_list_navigation' => esc_html__( 'Popups list navigation', 'sp-pro-txt-domain' ),
			'filter_forms_list'     => esc_html__( 'Filter forms list', 'sp-pro-txt-domain' ),
		);
		$args = array(
			'labels'                => $labels,
			'supports'              => array('title'),
			'rewrite' 				=> false,
			'query_var' 			=> false,
			'show_in_menu' 			=> false,
			'public' 				=> true,
		);
		register_post_type( self::post_type, $args );
	}

	public static function create_default_popups() {
		
		// Diable creation of Default Popups
		$good = false; 
		
		$existing_popups = get_posts(array('post_type'=>self::post_type, 'post_status'=>'any'));
		if(is_array($existing_popups) AND sizeof($existing_popups)) {
			// Some popups already created
			$good = false; 
			return; 
		}
		if($good) {
			$popups = array( 'Basic Enquiry', 'Get A Quote');
			$count = 1; 
			foreach($popups as $popup) {			
				$title = self::improve_popup_name($popup);
				
				$args = array(
					'post_type' => 'sppro_forms',
					'post_title' => $title . ' Popup',
					'post_status' => 'publish',
				);
				
				$post_id = wp_insert_post($args);
				self::setup_popup_meta($post_id, $title, $popup);
				$count++; 
			}
		}
	}
	
	public static function setup_popup_meta($post_id="", $title="",$popup='') {
		
		if(empty($post_id)) return false; 
		
		if(class_exists('WPCF7_ContactForm')) {							
			$formId = sppro_import_cf7_demo(array('title'=>$popup)); 			
			
			$formId = is_array($formId) ? $formId['form_id'] : $formId; 			
			$args = self::get_popup_meta_for_specific_popup($title); 
			$new_post_meta = self::get_popup_meta_and_set_it($formId, $args);
			$updated = update_post_meta( $post_id, '_sppro_form_options', $new_post_meta );
		}
		
		return $updated; 
	}
	
	public static function improve_popup_name($title="") {
		
		if(empty($title)) return 'No Title'; 
		
		$title = str_replace("-", " ", $title);
		return $title;
	}
	
	public static function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		return $objs;
	}
	
	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		return self::$current = new self( $post );
	}
	
	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;
			$this->locale = get_post_meta( $post->ID, '_locale', true );

			if(0) {
				$properties = $this->get_properties();
				foreach ( $properties as $key => $value ) {
					if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
						$properties[$key] = get_post_meta( $post->ID, '_' . $key, true );
					} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
						$properties[$key] = get_post_meta( $post->ID, $key, true );
					}
				}

				$this->properties = $properties;
			}
			//$this->upgrade();
		}

		do_action( 'sppro_forms', $this );
	}
	
	public function initial() {
		return empty( $this->id );
	}
	
	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'form' => '',
			'mail' => array(),
			'mail_2' => array(),
			'messages' => array(),
			'additional_settings' => '',
		) );

		$properties = (array) apply_filters( 'sppro_contact_form_properties',
			$properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}
	
	// Not in Use
	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = strip_tags( $title );
		$title = trim( $title );

		if ( '' === $title ) {
			$title = esc_html__( 'Untitled', 'sp-pro-txt-domain' );
		}

		$this->title = $title;
	}

	public function locale() {
		if ( sppro_is_valid_locale( $this->locale ) ) {
			return $this->locale;
		} else {
			return '';
		}
	}

	public function set_locale( $locale ) {
		$locale = trim( $locale );

		if ( sppro_is_valid_locale( $locale ) ) {
			$this->locale = $locale;
		} else {
			$this->locale = 'en_US';
		}
	}

	public function shortcode_attr( $name ) {
		if ( isset( $this->shortcode_atts[$name] ) ) {
			return (string) $this->shortcode_atts[$name];
		}
	}

	// Return true if this form is the same one as currently POSTed.
	public function is_posted() {
		if ( ! WPCF7_Submission::get_instance() ) {
			return false;
		}

		if ( empty( $_POST['_sppro_unit_tag'] ) ) {
			return false;
		}

		return $this->unit_tag == $_POST['_sppro_unit_tag'];
	}

	public function copy() {
		global $wpdb; 
		$post_id = $this->id;
		$post = get_post( $post_id );		
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		
		if( self::post_type != $post->post_type )
			return false; 
	 
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . ' Copy',
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		$new_post_id = wp_insert_post( $args );
 
		if(0) { // Taxonomies not needed
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
		
		//return $new_post_id;  
		$new = new self;
		$new->id = $new_post_id; 			

		return apply_filters( 'sppro_copy', $new, $this );
	}

	public function delete() {
		if ( $this->initial() ) {
			return;
		}

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;
			return true;
		}

		return false;
	}
	
	public function shortcode( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'use_old_format' => false ) );

		$title = str_replace( array( '"', '[', ']' ), '', $this->title );

		if ( $args['use_old_format'] ) {
			$old_unit_id = (int) get_post_meta( $this->id, '_old_cf7_unit_id', true );

			if ( $old_unit_id ) {
				$shortcode = sprintf( '[sppro %1$d "%2$s"]', $old_unit_id, $title );
			} else {
				$shortcode = '';
			}
		} else {
			//$shortcode = sprintf( '[sppro id="%1$d" title="%2$s"]',
			$shortcode = sprintf( '[sppro id="%1$d"][/sppro]',
				$this->id, $title );
		}

		return apply_filters( 'sppro_forms_shortcode', $shortcode, $args, $this );
	}
	
	public static function get_popup_meta_for_specific_popup($title) {
		
		$args = array(); 
		
		// Switch to lowercase for cases 
		$title = strtolower($title); 	
		
		switch($title) {
			case 'basic-enquiry': 
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Send Enquiry",
						"_sppro_forms_meta_cta" => "Please fill out the short form below and we will get back to you as soon as possible.",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_left",
						"_sppro_forms_meta_side_button" => "Enquiry",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "centered",
						"_sppro_forms_meta_fixed_side" => "corner_left",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "change",
						"_sppro_forms_meta_popup_width" => "450px",
						"_sppro_forms_meta_popup_height" => "460px",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#f88379",
						"_sppro_forms_meta_custom_background_color" => "#f88379",
						"_sppro_forms_meta_custom_text_color" => "#cccccc",
						"_sppro_forms_meta_custom_cta_color" => "#cccccc",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#cccccc",
						"_sppro_forms_meta_submit_button_text_color" => "#f88379",
					),
				);
				break; 
			case 'subscribe':
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Subscribe",
						"_sppro_forms_meta_cta" => "Love our work. Subscribe to our updates.",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_right",
						"_sppro_forms_meta_side_button" => "Subscribe Us",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "centered",
						"_sppro_forms_meta_fixed_side" => "corner_left",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "change",
						"_sppro_forms_meta_popup_width" => "400px",
						"_sppro_forms_meta_popup_height" => "330px",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#b784a7",
						"_sppro_forms_meta_custom_background_color" => "#b784a7",
						"_sppro_forms_meta_custom_text_color" => "#ffffff",
						"_sppro_forms_meta_custom_cta_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_text_color" => "#b784a7",
					),
				);
				break;
			case 'unsubscribe':
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Unsubscribe",
						"_sppro_forms_meta_cta" => "Sad to see you leave. Please help us to improvise our updates by filling the form below.",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_botleft",
						"_sppro_forms_meta_side_button" => "Unsubscribe",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "centered",
						"_sppro_forms_meta_fixed_side" => "corner_left",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "change",
						"_sppro_forms_meta_popup_width" => "360px",
						"_sppro_forms_meta_popup_height" => "310px",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#6699cc",
						"_sppro_forms_meta_custom_background_color" => "#6699cc",
						"_sppro_forms_meta_custom_text_color" => "#ffffff",
						"_sppro_forms_meta_custom_cta_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_text_color" => "#6699cc",
					),
				);
				break;
			case 'get-a-quote':
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Get a Quote",
						"_sppro_forms_meta_cta" => "Please fill out the form given below.",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_botright",
						"_sppro_forms_meta_side_button" => "Get a Quote",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "centered",
						"_sppro_forms_meta_fixed_side" => "corner_left",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "predefined",
						"_sppro_forms_meta_popup_width" => "",
						"_sppro_forms_meta_popup_height" => "",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#737a32",
						"_sppro_forms_meta_custom_background_color" => "#737a32",
						"_sppro_forms_meta_custom_text_color" => "#cccccc",
						"_sppro_forms_meta_custom_cta_color" => "#cccccc",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#cccccc",
						"_sppro_forms_meta_submit_button_text_color" => "#737a32",
					),
				);
				break;
			case 'booking':
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Booking Form",
						"_sppro_forms_meta_cta" => "Please fill out the form to complete your booking.",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_botcenter",
						"_sppro_forms_meta_side_button" => "Booking Form",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "centered",
						"_sppro_forms_meta_fixed_side" => "corner_left",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "predefined",
						"_sppro_forms_meta_popup_width" => "",
						"_sppro_forms_meta_popup_height" => "",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#ADD8E6",
						"_sppro_forms_meta_custom_background_color" => "#ADD8E6",
						"_sppro_forms_meta_custom_text_color" => "#000000",
						"_sppro_forms_meta_custom_cta_color" => "#000000",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#000000",
						"_sppro_forms_meta_submit_button_text_color" => "#ADD8E6",
					),
				);
				break;
			case 'survey':
				$args = array(
					"_sppro_forms_meta_override" => 1,
					//"_sppro_forms_meta_form_id" => $popup_id,
					"_sppro_forms_meta_fieldset_heading_cta" => array(
						"_sppro_forms_meta_popup_heading" => "Survey Form",
						"_sppro_forms_meta_cta" => "",
					),
					"_sppro_forms_meta_fieldset_side_button" => array(
						"_sppro_forms_meta_side_button_show" => "pos_topcenter",
						"_sppro_forms_meta_side_button" => "Survey Form",
					),
					"_sppro_forms_meta_fieldset_popup_layout" => array(
						"_sppro_forms_meta_popup_layout" => "full",
						"_sppro_forms_meta_fixed_side" => "corner_right",
						"_sppro_forms_meta_widgetized_popup" => "right_bottom",
						"_sppro_forms_meta_change_height_and_width" => "predefined",
						"_sppro_forms_meta_popup_width" => "",
						"_sppro_forms_meta_popup_height" => "",
					),
					"_sppro_forms_meta_fieldset_theme_colors" => array(
						"_sppro_forms_meta_color_scheme" => "custom",
						"_sppro_forms_meta_custom_theme_color" => "#0d98ba",
						"_sppro_forms_meta_custom_background_color" => "#0d98ba",
						"_sppro_forms_meta_custom_text_color" => "#ffffff",
						"_sppro_forms_meta_custom_cta_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_scheme" => "custom",
						"_sppro_forms_meta_submit_button_background_color" => "#ffffff",
						"_sppro_forms_meta_submit_button_text_color" => "#0d98ba",
					),
				);
				break;					
		}
		return $args; 
	}
	
	public static function get_popup_meta_and_set_it($popup_id="", $args=array()) {
		$defaults = array(
			"_sppro_forms_meta_override" => 1,
			"_sppro_forms_meta_where_to_show" => 'everywhere',
			"_sppro_forms_meta_fieldset_where_to_show" => array(
				"show_on_pages" => false,
				"pages_choices" => 'everywhere',
				"choose_pages" => "",
				"show_on_posts" => false,
				"posts_choices" => 'everywhere',
				"choose_posts" => "",
				"show_on_categories" => false,
				"categories_choices" => 'everywhere',
				"choose_categories" => "",
				"show_on_tags" => false,
				"tags_choices" => 'everywhere',
				"choose_tags" => "",
				"show_on_search_pages" => false,
				"show_on_404_page" => false,
			),
			"_sppro_forms_meta_form_type" => 'cf7',
			"_sppro_forms_meta_form_id" => $popup_id,
			"_sppro_forms_meta_fieldset_sp_image" => array(
				"_sppro_forms_meta_popup_image" => "",
				"_sppro_forms_meta_popup_image_width" => "",
				"_sppro_forms_meta_popup_image_height" => "",
				"_sppro_forms_meta_popup_use_link" => false,
				"_sppro_forms_meta_popup_link_type" => "",
				"_sppro_forms_meta_popup_link_page" => "",
				"_sppro_forms_meta_popup_link_custom" => "",
				"_sppro_forms_meta_popup_link_target" => false,
			),
			"_sppro_forms_meta_fieldset_activation_modes" => array(
				"_sppro_forms_meta_activation_mode" => 'manually',
				"_sppro_forms_meta_autopopup-delay" => '',
				"_sppro_forms_meta_onscroll-type" => 'pixels',
				"_sppro_forms_meta_onscroll-pixels" => 250,
				"_sppro_forms_meta_onscroll-percentage" => 60,
				"_sppro_forms_meta_cookie-delay" => '',
			),
			"_sppro_forms_meta_fieldset_heading_cta" => array(
				"_sppro_forms_meta_popup_heading" => "Contact Us",
				"_sppro_forms_meta_cta" => "Please fill up the short form and we will get back to you within 24 hours or you can email us at poke@slickpopup.com",
			),
			"_sppro_forms_meta_fieldset_side_button" => array(
				"_sppro_forms_meta_side_button_show" => "pos_right",
				"_sppro_forms_meta_side_button" => "Contact Us",
			),
			"_sppro_forms_meta_fieldset_popup_layout" => array(
				"_sppro_forms_meta_popup_layout" => "centered",
				"_sppro_forms_meta_fixed_side" => "corner_left",
				"_sppro_forms_meta_widgetized_popup" => "right_bottom",
				"_sppro_forms_meta_change_height_and_width" => "predefined",
				"_sppro_forms_meta_popup_width" => "",
				"_sppro_forms_meta_popup_height" => "",
			),
			"_sppro_forms_meta_fieldset_animation_effects" => array(
				"_sppro_forms_meta_change_loader_animation" => "change",
				"_sppro_forms_meta_loader_animation" => "fadeInDown",
				"_sppro_forms_meta_loader_speed" => "",
				"_sppro_forms_meta_change_unloader_animation" => "change",
				"_sppro_forms_meta_unloader_animation" => "fadeOutDown",
				"_sppro_forms_meta_unloader_speed" => "",
			),
			"_sppro_forms_meta_fieldset_theme_colors" => array(
				"_sppro_forms_meta_color_scheme" => "master_red",
				"_sppro_forms_meta_custom_curtain" => 
					array ( 
						"image" => "",
						"repeat" => "no-repeat",
						"position" => "center center",
						"attachment" => "fixed",
						"size" => "cover",
						"color" => "rgba(0,0,0,0.80)", 
					),
				"_sppro_forms_meta_custom_theme_color" => "",
				"_sppro_forms_meta_custom_background_color" => "",
				"_sppro_forms_meta_custom_text_color" => "",
				"_sppro_forms_meta_custom_cta_color" => "",
				"_sppro_forms_meta_submit_button_scheme" => "inherit",
				"_sppro_forms_meta_submit_button_background_color" => "",
				"_sppro_forms_meta_submit_button_text_color" => "",
				"_sppro_forms_meta_side_button_scheme" => "inherit",
				"_sppro_forms_meta_side_button_background_color" => "",
				"_sppro_forms_meta_side_button_text_color" => "",
			),
			"_sppro_forms_meta_fieldset_advance_options" => array(
				"_sppro_forms_meta_external_selectors" => "",
				"_sppro_forms_meta_insights" => true,
				"_sppro_forms_meta_autoclose" => false,
				"_sppro_forms_meta_autoclose_time" => 5,
				"_sppro_forms_meta_redirect" => false,
				"_sppro_forms_meta_redirect_url" => "",
			),
			'popup_insights' => array(
				'loaded' => 0,
				'opened' => 0,
				'submitted' => 0,
			)
		);
		
		$args = wp_parse_args($args, $defaults); 
		return $args; 
	}
	
}