<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// METABOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options      = array();
global $post_types, $sp_opts;
$dependancy = array( '_sppro_meta_override', '==', 'true' );
$post_types = array('page','post','product');
$debug = false; 

$args = array(
	'numberposts' => -1,
	'category' => 0, 
	'orderby' => 'date',
	'order' => 'DESC', 
	'include' => array(),
	'exclude' => array(), 
	'meta_key' => '',
	'meta_value' =>'', 
	'post_type' => 'sppro_forms',
	'post_status' => 'any',
);
$popups = get_posts($args);

$popup_checkboxes = array(); 
foreach($popups as $popup) {	
    setup_postdata( $popup ); 
    $popup_checkboxes[$popup->ID] = $popup->post_title . ' ('.$popup->ID.')';     
}
wp_reset_postdata();

// -----------------------------------------
// Page Side Metabox Options               -
// -----------------------------------------
$options[]    = array(
	'id'        => '_sppro_page_options',
	'title'     => 'Slick Popup Pro - Global Form',
	'post_type' => apply_filters('sppro_posttype_overrides', $post_types), 
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'Overrides',
			'fields' => array(

				array(
				  'id'      => '_sppro_meta_override',
				  'type'    => 'switcher',
				  'title'   => 'Override Defaults?',
				  'label'     => __('Settings to override global popup.', 'sp-pro-txt-domain'),				
				  'default' => false
				),				
					// Choose Contact Form 7 Form
					array(
						'id'            => '_sppro_meta_form_id',
						'type'           => 'select',
						'title'          => 'Choose CF7 Form',
						'dependency'   => array( '_sppro_meta_override', '==', 'true' ),
						'options'        => 'posts',
						'query_args'     => array(
							'post_type'    => 'wpcf7_contact_form',
							'orderby'      => 'post_date',
							'order'        => 'DESC',
							'posts_per_page' => -1
						),
						'default_option' => 'Select your form',
						'class' => 'chosen',
					),	

					array(
						'id'        => '_sppro_meta_fieldset_heading_cta',
						'type'      => 'fieldset',
						'title'     => 'Popup Heading & CTA',
						//'desc'     => __('<strong>Popup Heading:</strong> Text at the top bar of the popup. <br/><strong>Popup CTA:</strong> Call to action text below the heading and above form.', 'sp-pro-txt-domain'),	
						'dependency'   => array( '_sppro_meta_override', '==', 'true' ),
						'fields'    => array(
							array(
								'type'    => 'subheading',
								'content' => 'Heading & CTA',
							),
							array(
								'id'            => '_sppro_meta_popup_heading',
								'type'          => 'text',
								'title'   	  => 'Popup Heading',
								'attributes'    => array(
									'placeholder' => ''
								)
							),						
							array(
								'id'            => '_sppro_meta_cta',
								'type'      => 'textarea',
								'title'     => 'CTA Text',
								'info'      => 'Change the call-to-action text above form for this page.',
							),
						),
						'default'   => array( 
						)
					),				
					
					// Fieldset: Popup Layout
					array(
						'id'        => '_sppro_meta_fieldset_popup_layout',
						'type'      => 'fieldset',
						'title'     => 'Choose Layout',
						'desc'     => __('Choose desired layout for popup.', 'sp-pro-txt-domain'),	
						'dependency'   => array( '_sppro_meta_override', '==', 'true' ),
						'fields'    => array(
							array(
								'type'    => 'subheading',
								'content' => 'Popup Layout',
							),
							array(
								'id'            => '_sppro_meta_popup_layout',
								'type'          => 'select',
								'title'   	  => 'Layout', 
								'options'        => array(
									'centered' => __('Centered', 'sp-pro-txt-domain' ),
									'full' => __('Full Height', 'sp-pro-txt-domain' ),
									'full-page' => __('Full Page', 'sp-pro-txt-domain' ),
									'corner-fixed' => __('Fixed To Side', 'sp-pro-txt-domain' ),
								),
								'class' => 'chosen',
							),	
							array(
								'id'            => '_sppro_meta_fixed_side',
								'type'          => 'select',
								'title'   	  => 'Fixed Side',
								'dependency'   => array( '_sppro_meta_popup_layout', '==', 'corner-fixed' ), 
								'options'        => array(
									'corner_left' => __('Fixed Left', 'sp-pro-txt-domain' ),
									'corner_right' => __('Fixed Right', 'sp-pro-txt-domain' ), 	
								),
								'class' => 'chosen',
							),	
						),
						'default'   => array(
							'_sppro_meta_popup_layout' => 'centered',
							'_sppro_meta_popup_fixed_side' => 'corner_left',
						)
					),
					
					// Fieldset: Side Button
					array(
						'id'        => '_sppro_meta_fieldset_side_button',
						'type'      => 'fieldset',
						'title'     => 'Side Button',
						//'desc'     => __('Choose desired position and text for the side button.<br/><br/>You can choose "None" to not show the side button for this page.', 'sp-pro-txt-domain'),	
						'dependency'   => array( '_sppro_meta_override', '==', 'true' ),
						'fields'    => array(
							array(
								'type'    => 'subheading',
								'content' => 'Side Button Settings',
							),
							array(
								'id'             => '_sppro_meta_side_button_show',
								'type'           => 'select',
								'title'          => 'Button Position',
								'options'  => array(
									'pos_right' => __( 'Right', 'sp-pro-txt-domain' ),
									'pos_left' => __( 'Left', 'sp-pro-txt-domain' ),
									'pos_botright' => __( 'Bottom Right', 'sp-pro-txt-domain' ),
									'pos_botleft' => __( 'Bottom Left', 'sp-pro-txt-domain' ),
									'pos_botcenter' => __( 'Bottom Center', 'sp-pro-txt-domain' ),
									'pos_topleft' => __( 'Top Left', 'sp-pro-txt-domain' ),
									'pos_topright' => __( 'Top Right', 'sp-pro-txt-domain' ),
									'pos_topcenter' => __( 'Top Center', 'sp-pro-txt-domain' ),
									'pos_none' => __( 'None (Hide)', 'sp-pro-txt-domain' ),
								),
								'class' => 'chosen',
							),
							array(
								'id'            => '_sppro_meta_side_button',
								'type'          => 'text',
								'title'   	  => 'Button Text', 
								'attributes'    => array(
									'placeholder' => ''
								)
							),
						),
						'default'   => array(
							'_sppro_meta_side_button_show'     => 'right',
						)
					),
				
				array(
					'id'      => '_sppro_meta_add_multiple',
					'type'    => 'switcher',
					'title'   => 'Add Multiple Popups to Page',
					'label'     => __('Only use when you want to use an element on your page to show the popup. If you are using the Shortcode in the page then you do not need to choose this popup here.', 'sp-pro-txt-domain'),				
					'default' => false
				),				
				
					array(
						'id'            => '_sppro_meta_multiple_ids',
						'type'           => 'select',
						'title'          => 'Choose From Multiple Popups List',
						'dependency'   => array( '_sppro_meta_add_multiple', '==', 'true' ),
						'options'        => $popup_checkboxes,
						'attributes' => array(
							'multiple' => 'multiple',
							'style'    => 'width: 240px; height: 150px;',
						),
						'default'    => array(),
						'class' => 'chosen',							
					)
			), // end fields
		),
	)
);

// -----------------------------------------
// Multiple popup meta boxes
// -----------------------------------------
$post_types = array('sppro_forms');
$options[]    = array(
	'id'        => '_sppro_form_options',
	'title'     => 'Slick Popup Pro',
	'post_type' => $post_types, 
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'Form Settings',
			'fields' => array(				
							array(
								'id'            => '_sppro_forms_meta_where_to_show',
								'type'          => 'select',
								'title'         => __( 'Where to show the popup?', 'sp-pro-txt-domain' ),
								'subtitle'      => __( 'Choose the display of the popup.', 'sp-pro-txt-domain' ),
								'class'			=> 'chosen',
								'options'  		=> array(
											'everywhere' => 'Everywhere',
											'onselected' => 'On Selected Utlilties',
										),
								'default'  => 'everywhere'
							),															
							array(
							  'id'        => '_sppro_forms_meta_fieldset_where_to_show',
							  'type'      => 'fieldset',
							  'title'     => 'Where To Show',
							  'dependency' 	=> array( '_sppro_forms_meta_where_to_show', '==', 'onselected' ),
							  'fields'    => array(							  	
								//////////////////////////////////
								// Show On Pages Options Here
								//////////////////////////////////
								array(
									'id'		=> 'show_on_pages',
									'type'		=> 'switcher',									
									'title'		=> __('Show on Pages', 'sp-pro-txt-domain'),
									'default'	=> false,
									'debug'	=> $debug,
								),
								array(
									'id'            => 'pages_choices',
									'type'          => 'select',
									'dependency' 	=> array( 'show_on_pages', '==', 'true' ),
									'class'			=> 'chosen',
									'title'         => __( 'Select the pages', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Choose on which pages', 'sp-pro-txt-domain' ),
									'options'  => array(
												'everywhere' => 'All Pages',
												'onselected' => 'On Selected Pages',
												'notonselected' => 'Not on Selected Pages',
											),
									'default'  => 'everywhere',
									'debug'	=> $debug,
								),	
								array(
									'id'            => 'choose_pages',
									'type'          => 'select',
									'options'       => 'pages',
			  						'query_args'    => array('sort_order'=> 'ASC','sort_column' => 'post_title',),
			  						'dependency'   => array( 'show_on_pages|pages_choices', '==|any', 'true|onselected,notonselected' ),
									'class'			=> 'chosen',
									'title'         => __( 'Choose Your Pages', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Select the pages to exclude or include for popup form display.', 'sp-pro-txt-domain' ),
									'desc'          => __( '<a target="_blank" href="', 'sp-pro-txt-domain' ) .admin_url( '/edit.php?post_type=page' ). __( '"><b>See all Pages </b></a>', 'sp-pro-txt-domain' ),
									'attributes'    => array(
									    'placeholder' => 'Select a Page',
									    'multiple'    => 'multiple',
									    'style'       => 'width: 350px;'
									),
								),
								//////////////////////////////////
								// Show On Posts Options Here
								//////////////////////////////////
								array(
									'id'		=> 'show_on_posts',
									'type'		=> 'switcher',									
									'title'		=> __('Show on Posts', 'sp-pro-txt-domain'),
									'default'	=> false,
									'debug'	=> $debug,
								),
								array(
									'id'            => 'posts_choices',
									'type'          => 'select',
									'dependency' 	=> array( 'show_on_posts', '==', 'true' ),
									'title'         => __( 'Select the posts', 'sp-pro-txt-domain' ),
									'class'			=> 'chosen',
									'subtitle'      => __( 'Choose on which posts', 'sp-pro-txt-domain' ),
									'options'  => array(
												'everywhere' => 'All Posts',
												'onselected' => 'On Selected Posts',
												'notonselected' => 'Not on Selected Posts',
											),
									'default'  => 'everywhere'
								),
								array(
									'id'            => 'choose_posts',
									'type'          => 'select',
									'options'       => 'posts',
			  						'query_args'    => array('orderby'=> 'name','order'=> 'ASC','posts_per_page' 	=> -1),
			  						'dependency'   => array( 'show_on_posts|posts_choices', '==|any', 'true|onselected,notonselected' ),
									'class'			=> 'chosen',
									'title'         => __( 'Choose Your Posts', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Select the posts to exclude or include for popup form display.', 'sp-pro-txt-domain' ),
									'desc'          => __( '<a target="_blank" href="', 'sp-pro-txt-domain' ) .admin_url( '/edit.php?post_type=post' ). __( '"><b>See all Posts</b></a>', 'sp-pro-txt-domain' ),
									'attributes'    => array(
									    'placeholder' => 'Select a Post',
									    'multiple'    => 'multiple',
									    'style'       => 'width: 350px;'
									),
								),
								//////////////////////////////////
								// Show On Category Options Here
								//////////////////////////////////
								array(
									'id'		=> 'show_on_categories',
									'type'		=> 'switcher',									
									'title'		=> __('Show on Categories', 'sp-pro-txt-domain'),
									'default'	=> false,
									'debug'	=> $debug,
								),
								array(
									'id'            => 'categories_choices',
									'type'          => 'select',
									'dependency' 	=> array( 'show_on_categories', '==', 'true' ),
									'class'			=> 'chosen',
									'title'         => __( 'Select the Categories', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Choose on which categories', 'sp-pro-txt-domain' ),
									'options'  => array(
												'everywhere' => 'All Categories',
												'onselected' => 'On Selected Categories',
												'notonselected' => 'Not on Selected Categories',
											),
									'default'  => 'everywhere'
								),
								array(
									'id'            => 'choose_categories',
									'type'          => 'select',
									'options'       => 'categories',
									'query_args'    => array('orderby'=> 'name','order'=> 'ASC',),
									'dependency'   => array( 'show_on_categories|categories_choices', '==|any', 'true|onselected,notonselected' ),
									'class'			=> 'chosen',
									'title'         => __( 'Choose Your Categories', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Select the Categories to exclude or include for popup form display.', 'sp-pro-txt-domain' ),
									'attributes'    => array(
									    'placeholder' => 'Select a Category',
									    'multiple'    => 'multiple',
									    'style'       => 'width: 350px;'
									),
								),
								//////////////////////////////////
								// Show On Tags Options Here
								//////////////////////////////////
								array(
									'id'		=> 'show_on_tags',
									'type'		=> 'switcher',									
									'title'		=> __('Show on Tags', 'sp-pro-txt-domain'),
									'default'	=> false,
									'debug'	=> $debug,
								),
								array(
									'id'            => 'tags_choices',
									'type'          => 'select',
									'dependency' 	=> array( 'show_on_tags', '==', 'true' ),
									'class'			=> 'chosen',	
									'title'         => __( 'Select the Tags', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Choose on which tags', 'sp-pro-txt-domain' ),
									'options'  => array(
												'everywhere' => 'All Tags',
												'onselected' => 'On Selected Tags',
												'notonselected' => 'Not on Selected Tags',
											),
									'default'  => 'everywhere'
								),
								array(
									'id'            => 'choose_tags',
									'type'          => 'select',
									'options'		=> 'tags',
									'query_args'	=> array('taxonomies' => array( 'post_tag' ),),
									'dependency'   => array( 'show_on_tags|tags_choices', '==|any', 'true|onselected,notonselected' ),
									'class'			=> 'chosen',
									'title'         => __( 'Choose Your Tags', 'sp-pro-txt-domain' ),
									'subtitle'      => __( 'Select the tags to exclude or include for popup form display.', 'sp-pro-txt-domain' ),
									'attributes'    => array(
									    'placeholder' => 'Select a tag',
									    'multiple'    => 'multiple',
									    'style'       => 'width: 350px;'
									),
								),
								array(
									'id'		=> 'show_on_404_page',
									'type'		=> 'switcher',
									'title'		=> __('Show on 404 page', 'sp-pro-txt-domain'),
									'default'	=> false,
								),
								array(
									'id'		=> 'show_on_search_pages',
									'type'		=> 'switcher',
									'title'		=> __('Show on Search Pages', 'sp-pro-txt-domain'),
									'default'	=> false,
								),
				  ),
				),

				array(
					'id'            => '_sppro_forms_meta_form_type',
					'type'          => 'select',
					'title'   	  => 'Choose Popup Type',					
					'options'        => array(
						'cf7' => __('Contact Form 7', 'sp-pro-txt-domain' ),
						'login' => __('Login/Logout Popup', 'sp-pro-txt-domain' ),						
						'sp_image' => __('Image Popup', 'sp-pro-txt-domain' ),						
						'sp_video' => __('Video Popup', 'sp-pro-txt-domain' ),						
						'sp_maps' => __('Google Map Popup', 'sp-pro-txt-domain' ),						
						'sp_html' => __('HTML Popup', 'sp-pro-txt-domain' ),						
					),
					'class' => 'chosen',
				),	
				// Choose Contact Form 7 Form
				array(
					'id'			=> '_sppro_forms_meta_form_id',
					'type'			=> 'select',
					'title'			=> 'Choose CF7 Form',
					'dependency'	=> array('_sppro_forms_meta_form_type', '==', 'cf7' ),
					'options'		=> 'posts',
					'info'      	=> 'Use a form created with Contact Form 7 plugin.',
					'query_args'	=> array(
						'post_type' 		=> 'wpcf7_contact_form',
						'orderby'   		=> 'post_date',
						'order'     		=> 'DESC',
						'posts_per_page' 	=> -1
					),
					'default_option' => 'Select a post',
					'class' => 'chosen',
				),
				
				/* Fieldset: Image Popup Settings - sp_image */
				array(
					'id'        => '_sppro_forms_meta_fieldset_sp_image',
					'type'      => 'fieldset',
					'title'     => 'Image Popup Settings',
					'dependency'   => array( '_sppro_forms_meta_form_type', '==', 'sp_image' ),
					'fields' => array(
						array(
							'type'    => 'subheading',
							'content' => __('Choose image and link for it', 'sp-pro-txt-domain'),
						),
						array (
							'id'            => '_sppro_forms_meta_popup_image',
							'type'          => 'image',
							'title'         => 'Upload Image',
							'info'      	=> 'Upload the image you want to show in popup',
							'settings'      => array (
								'upload_type'  => 'image',
								'button_title' => 'Upload',
								'frame_title'  => 'Select an image',
								'insert_title' => 'Use this image',
							),
						),						
						array (
							'id'            => '_sppro_forms_meta_popup_image_width',
							'type'          => 'text',
							'title'         => 'Enter Image Width',
							'desc'        => __('E.g 440px or 100%', 'sp-pro-txt-domain'),
							'info'        => __('Enter image width in pixels (px) or percentage (%)', 'sp-pro-txt-domain'),
						),	
						array (
							'id'            => '_sppro_forms_meta_popup_image_height',
							'type'          => 'text',
							'title'         => 'Enter Image Height',
							'desc'        => __('E.g 600px or 100%', 'sp-pro-txt-domain'),
							'info'        => __('Enter image height in pixels (px) or percentage (%)', 'sp-pro-txt-domain'),
						),	
						array (
							'id'            => '_sppro_forms_meta_popup_use_link',
							'type'          => 'switcher',
							'title'         => 'Use Link on Image?',
							'default'		=> false, 
						),
						array(
							'id'            => '_sppro_forms_meta_popup_link_type',
							'type'          => 'select',
							'title'   	  => 'Link Type',
							'dependency'   => array( '_sppro_forms_meta_popup_use_link', '==', 'true' ),							
							'options'    => array(
								'pages' => 'An Existing Page in Website',
								'custom' => 'Custom Link or link to a post',
							),
							'class' => 'chosen',
						),						
						array (
							'id'            => '_sppro_forms_meta_popup_link_page',
							'type'          => 'select',
							'title'         => 'Choose a page?',
							'dependency'   => array( '_sppro_forms_meta_popup_use_link|_sppro_forms_meta_popup_link_type', '==|==', 'true|pages' ),
							'info'        => __('Choose an existing page', 'sp-pro-txt-domain'),
							'options'        => 'pages',
							'class'         => 'chosen',
						),
						array (
							'id'            => '_sppro_forms_meta_popup_link_custom',
							'type'          => 'text',
							'title'         => 'or Enter Custom Link',
							'dependency'   => array( '_sppro_forms_meta_popup_use_link|_sppro_forms_meta_popup_link_type', '==|==', 'true|custom' ),
							'desc'        => __('With https:// or http://', 'sp-pro-txt-domain'),
							'info'        => __('External link or link to any other section of website', 'sp-pro-txt-domain'),
						),						
						array (
							'id'            => '_sppro_forms_meta_popup_link_target',
							'type'          => 'switcher',
							'title'         => 'Open in new tab?',
							'dependency'   => array( '_sppro_forms_meta_popup_use_link', '==', 'true' ),														
							'default'		=> false, 
						),
					),
				),
				
				// Fieldset: Popup Layout
				array(
					'id'        => '_sppro_forms_meta_fieldset_popup_layout',
					'type'      => 'fieldset',
					'title'     => 'Choose Layout',
					'dependency'   => array( '_sppro_forms_meta_form_type', '!=', 'sp_image' ),
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Popup Layout',
						),
						array(
							'id'            => '_sppro_forms_meta_popup_layout',
							'type'          => 'select',
							'title'   	  => 'Layout',
							'options'        => array(
								'centered' => __('Centered', 'sp-pro-txt-domain' ),
								'widgetized' => __('Widgetized', 'sp-pro-txt-domain' ),
								'full-page' => __('Full Page', 'sp-pro-txt-domain' ),
								'full' => __('Full Height', 'sp-pro-txt-domain' ),
								'corner-fixed' => __('Fixed To Side', 'sp-pro-txt-domain' ),								
							),
							'class' => 'chosen',
						),	
						array(
							'id'            => '_sppro_forms_meta_fixed_side',
							'type'          => 'select',
							'title'   	  => 'Fixed Side',
							'dependency'   => array( '_sppro_forms_meta_popup_layout', '==', 'corner-fixed' ), 
							'options'        => array(
								'corner_left' => __('Fixed Left', 'sp-pro-txt-domain' ),
								'corner_right' => __('Fixed Right', 'sp-pro-txt-domain' ), 	
							),
							'class' => 'chosen',
						),	
						array(
							'id'            => '_sppro_forms_meta_widgetized_popup',
							'type'          => 'select',
							'title'   	  => 'Widget Position',
							'dependency'   => array( '_sppro_forms_meta_popup_layout', '==', 'widgetized' ), 
							'options'        => array(
								'right_bottom' => __('Right Bottom', 'sp-pro-txt-domain' ),
								'left_bottom' => __('Left Bottom', 'sp-pro-txt-domain' ),	
								'centered_bottom' => __('Centered Bottom', 'sp-pro-txt-domain' ),
								'right_top' => __('Right Top', 'sp-pro-txt-domain' ),
								'left_top' => __('Left Top', 'sp-pro-txt-domain' ),	
								'centered_top' => __('Centered Top', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',
						),
						array(
							'id'            => '_sppro_forms_meta_change_height_and_width',
							'type'          => 'select',
							'title'   	  => 'Height & Width',
							'options'  => array(
								'predefined' => 'Predefined Values',
								'global' => 'Use Global Values',
								'change' => 'Set Your Own',
							),
							'class' => 'chosen',
						),						
						array(
							'id'            => '_sppro_forms_meta_popup_width',
							'type'          => 'text',
							'title'   	  => 'Popup Width',
							'dependency'   => array( '_sppro_forms_meta_change_height_and_width', '==', 'change' ),							
							'desc'   	  => 'e.g: 400px or 80%',
							'attributes'    => array(
								'placeholder' => ''
							)
						),
						array(
							'id'            => '_sppro_forms_meta_popup_height',
							'type'          => 'text',
							'title'   	  => 'Popup Height',
							'dependency'   => array( '_sppro_forms_meta_change_height_and_width', '==', 'change' ),
							'desc'   	  => 'e.g: 400px or 80%',
							'attributes'    => array(
								'placeholder' => ''
							)
						),
					),
					'default'   => array(
						'_sppro_forms_meta_popup_layout' => 'centered',
						'_sppro_forms_meta_popup_fixed_side' => 'corner_left',
					)
				),
				
				// Activation Modes Meta
				array(
					'id'        => '_sppro_forms_meta_fieldset_activation_modes',
					'type'      => 'fieldset',
					'title'     => 'Activation Modes',
					
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'How to popup?',
						),
						array(
							'id'            => '_sppro_forms_meta_activation_mode',
							'type'          => 'select',
							'title'   	  => 'Activation Mode',
							'options'        => array(
								'manually' => __('On-Click (Default)', 'sp-pro-txt-domain' ),
								'autopopup' => __('Auto Popup on page load', 'sp-pro-txt-domain' ),
								'onscroll' => __('On Scrolling the page', 'sp-pro-txt-domain' ),
								'forced' => __('Force user to fill form', 'sp-pro-txt-domain' ),
								'onexit' => __('While exiting the page', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',
						),	
						// Auto Popup Delay
						array(
							'id'       => '_sppro_forms_meta_autopopup-delay',
							'type'     => 'text', 
							'title'    => __('Auto Popup Delay', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'autopopup' ),
							'required' => array( 'activation_mode', '=', 'autopopup' ),
							'subtitle' => __('After how many seconds should it show?','sp-pro-txt-domain'), 
						),	
						// On Scroll Delay
						array(
							'id'       => '_sppro_forms_meta_onscroll-type',
							'type'     => 'select', 
							'title'    => __('On-scroll Type', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'onscroll' ), 
							'subtitle' => __('Choose your measurement dimension','sp-pro-txt-domain'),
							'desc'     => __('', 'sp-pro-txt-domain'),						
							'default' => 'pixels',
							'options'  => array(
								'pixels' => 'Pixels',
								'percentage' => 'Percentage',
							),
							'class' => 'chosen',
						),	
						array(
							'id'       => '_sppro_forms_meta_onscroll-pixels',
							'type'     => 'text', 
							'title'    => __('Pixels Scrolled Down', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'onscroll' ), 
							'subtitle' => __('Popup after scrolling pixels','sp-pro-txt-domain'),
							'desc'     => __('', 'sp-pro-txt-domain'),
						),
						array(
							'id'       => '_sppro_forms_meta_onscroll-percentage',
							'type'     => 'text', 
							'title'    => __('Percentage Scrolled Down', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'onscroll' ), 
							'subtitle' => __('Percentage of page scroll','sp-pro-txt-domain'),
							'desc'     => __('Range: 0-100', 'sp-pro-txt-domain'),
						),	
						array(
							'id'       => '_sppro_forms_meta_cookie-delay',
							'type'     => 'select', 
							'title'    => __('Re-show After', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_activation_mode', 'any', 'onscroll,autopopup,onexit' ),	
							'options' => array(
								'-1' => __( 'Everytime a page loads', 'sp-pro-txt-domain' ),
								'0' => __( 'Once per session', 'sp-pro-txt-domain' ),
								'days' => __( 'After X Days', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',							
						),							
						array(
							'id'       => '_sppro_forms_meta_cookie-days',
							'type'     => 'text', 
							'title'    => __('How many days?', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_cookie-delay', '==', 'days' ), 
							'subtitle' => __('Re-show Popup After X number of days','sp-pro-txt-domain'),
							'desc'     => __('Enter number of days.', 'sp-pro-txt-domain'),
						),	
						array(
						  'type'    => 'notice',
						  'class'   => 'success',
						  'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'forced' ), 
						  'content' => __('The popup will appear on the page load and will not close until the user fills up the form', 'sp-pro-txt-domain'),
						),
						array(
						  'type'    => 'notice',
						  'class'   => 'success',
						  'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'onexit' ), 
						  'content' => __('The popup will appear automatically when user tries to leave the page and mouse goes out of the view port from top.', 'sp-pro-txt-domain'),
						),						
						array(
						  'type'    => 'notice',
						  'class'   => 'warning',
						  'dependency'   => array( '_sppro_forms_meta_activation_mode', '==', 'onexit' ), 
						  'content' => __('<strong>Note:</strong> On-exit popup is banned by serveral browsers as it restricts the user intended action of leaving the page.', 'sp-pro-txt-domain'),
						),						
					),
					'default'   => array(
						'_sppro_forms_meta_onscroll-pixels' => 250,
						'_sppro_forms_meta_onscroll-percentage' => 60,
					)
				),
				
				// Fieldset: Heading & CTA
				array(
					'id'        => '_sppro_forms_meta_fieldset_heading_cta',
					'type'      => 'fieldset',
					'title'     => 'Heading & CTA',
					//'dependency'   => array( '_sppro_forms_meta_form_type', 'any', 'cf7,sp_html,sp_maps,sp_video' ),
					'dependency'   => array( '_sppro_forms_meta_form_type', '!=', 'sp_image' ),
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Heading and Call-to-Action',
						),						
						array(
							'id'            => '_sppro_forms_meta_popup_heading',
							'type'          => 'text',
							'title'   	  => 'Popup Heading',
							'attributes'    => array(
								'placeholder' => "Leave blank if you don't want popup header",
							)
						),						
						array(
							'id'            => '_sppro_forms_meta_cta',
							'type'      => 'wysiwyg',
							'title'     => 'CTA Text',
							'info'      => 'Change the call-to-action text above form for this page.<br><b>Place your Iframe tag for Video Popup in Text Mode and place your Shortcode in Visual Mode</b>',
							'settings' => array(
								'textarea_rows' => 4,
								//'tinymce'       => false,
								//'media_buttons' => false,
							),
						),
					),
					'default'   => array( 
					)
				),

				// Fieldset: Side Button
				array(
					'id'        => '_sppro_forms_meta_fieldset_side_button',
					'type'      => 'fieldset',
					'title'     => 'Side Button',
					'dependency'	=> array('_sppro_forms_meta_activation_mode', '==', 'manually' ),
					
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Side Button Settings',
						),
						array(
							'id'             => '_sppro_forms_meta_side_button_show',
							'type'           => 'select',
							'title'          => 'Side Button',
							'options'  => array(
								'pos_right' => __( 'Right', 'sp-pro-txt-domain' ),
								'pos_left' => __( 'Left', 'sp-pro-txt-domain' ),
								'pos_botright' => __( 'Bottom Right', 'sp-pro-txt-domain' ),
								'pos_botleft' => __( 'Bottom Left', 'sp-pro-txt-domain' ),
								'pos_botcenter' => __( 'Bottom Center', 'sp-pro-txt-domain' ),
								'pos_topleft' => __( 'Top Left', 'sp-pro-txt-domain' ),
								'pos_topright' => __( 'Top Right', 'sp-pro-txt-domain' ),
								'pos_topcenter' => __( 'Top Center', 'sp-pro-txt-domain' ),
								'pos_none' => __( 'None (Hide)', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',
						),
						array(
							'id'            => '_sppro_forms_meta_side_button',
							'type'          => 'text',
							'title'   	  => 'Side Button Text', 
							'attributes'    => array(
								'placeholder' => ''
							)
						),
					),
					'default'   => array(
						'_sppro_forms_meta_side_button_show'     => 'right',
					)
				),
				
				// Animation Effects
				array(
					'id'        => '_sppro_forms_meta_fieldset_animation_effects',
					'type'      => 'fieldset',
					'title'     => 'Popup Animation',
					
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Choose Animation',
						),
						array(
							'id'            => '_sppro_forms_meta_change_loader_animation',
							'type'          => 'select',
							'title'   	  => 'Loader',
							'desc'   	  => 'Current global value: <strong>'.$sp_opts['loader-animation'].'</strong>',
							'options'  => array(
								'global' => 'Use Global Animations',
								'change' => 'Use New Animations',
							),
							'class' => 'chosen',
						),
						array(
							'id'            => '_sppro_forms_meta_loader_animation',
							'type'          => 'select',
							'title'   	  => 'Loader Animation',
							'dependency'   => array( '_sppro_forms_meta_change_loader_animation', '==', 'change' ),
							'options'  => array(
								'fadeIn' => 'fadeIn',
								'fadeInDown' => 'fadeInDown',
								'fadeInUp' => 'fadeInUp',
								'fadeInRight' => 'fadeInRight',
								'fadeInLeft' => 'fadeInLeft',
								
								'bounceIn' => 'bounceIn', 
								'bounceInDown' => 'bounceInDown', 
								'bounceInLeft' => 'bounceInLeft', 
								'bounceInRight' => 'bounceInRight', 
								'bounceInUp' => 'bounceInUp', 
								
								'zoomIn' => 'zoomIn', 
								'zoomInDown' => 'zoomInDown', 
								'zoomInLeft' => 'zoomInLeft', 
								'zoomInRight' => 'zoomInRight', 
								'zoomInUp' => 'zoomInUp', 
							
								'flip' => 'flip', 
								'flipInX' => 'flipInX', 
								'flipInY' => 'flipInY', 
								'flipOutX' => 'flipOutX', 
								'flipOutY' => 'flipOutY', 
							
								'lightSpeedIn' => 'lightSpeedIn', 
								'bounce' => 'bounce', 
								'flash' => 'flash', 
								'pulse' => 'pulse', 
								'rubberBand' => 'rubberBand', 
								'shake' => 'shake', 
								'swing' => 'swing', 
								'tada' => 'tada', 
								'wobble' => 'wobble', 
								'jello' => 'jello', 
								'rotateIn' => 'rotateIn',	 								
							),
							'class' => 'chosen',
						),	
						array(
							'id'            => '_sppro_forms_meta_loader_speed',
							'type'          => 'text',
							'title'   	  => 'Loader Speed',
							'dependency'   => array( '_sppro_forms_meta_change_loader_animation', '==', 'change' ),
						),
						array(
							'id'            => '_sppro_forms_meta_change_unloader_animation',
							'type'          => 'select',
							'title'   	  => 'unLoader',
							'desc'   	  => 'Current global value: <strong>'.$sp_opts['unloader-animation'].'</strong>',
							'options'  => array(
								'global' => 'Use Global Animations',
								'change' => 'Use New Animations',
							),
							'class' => 'chosen',
						),
						array(
							'id'       => '_sppro_forms_meta_unloader_animation',
							'type'     => 'select', 
							'title'    => __('unLoader Animation', 'sp-pro-txt-domain'),
							'dependency'   => array( '_sppro_forms_meta_change_unloader_animation', '==', 'change' ),
							'subtitle' => __('After how many seconds should it show?','sp-pro-txt-domain'), 
							'options'  => array(
								'fadeOut' => 'fadeOut',
								'fadeOutDown' => 'fadeOutDown',
								'fadeOutUp' => 'fadeOutUp',
								'fadeOutRight' => 'fadeOutRight',
								'fadeOutLeft' => 'fadeOutLeft',
							
								'bounceOut' => 'bounceOut', 
								'bounceOutDown' => 'bounceOutDown', 
								'bounceOutLeft' => 'bounceOutLeft', 
								'bounceOutRight' => 'bounceOutRight', 
								'bounceOutUp' => 'bounceOutUp', 
							
								'zoomOut' => 'zoomOut', 
								'zoomOutDown' => 'zoomOutDown', 
								'zoomOutLeft' => 'zoomOutLeft', 
								'zoomOutRight' => 'zoomOutRight', 
								'zoomOutUp' => 'zoomOutUp', 
							
								'lightSpeedOut' => 'lightSpeedOut', 
								'rotateOut' => 'rotateOut',	
							),
							'class' => 'chosen',
						),
						array(
							'id'            => '_sppro_forms_meta_unloader_speed',
							'type'          => 'text',
							'title'   	  => 'unLoader Speed',
							'dependency'   => array( '_sppro_forms_meta_change_unloader_animation', '==', 'change' ),
						),						
					),
					'default'   => array(
						'_sppro_forms_meta_change_loader_animation' => 'global',
						'_sppro_forms_meta_loader_animation' => $sp_opts['loader-animation'],
						'_sppro_forms_meta_loader_speed' => $sp_opts['loader-speed'],
						'_sppro_forms_meta_change_unloader_animation' => 'global',
						'_sppro_forms_meta_unloader_animation' => $sp_opts['unloader-animation'],
						'_sppro_forms_meta_unloader_speed' => $sp_opts['unloader-speed'],
					)
				),
				
				// Fieldset: Popup Theme
				array(
					'id'        => '_sppro_forms_meta_fieldset_theme_colors',
					'type'      => 'fieldset',
					'title'     => 'Choose Scheme',
					
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Choose or Set Color Scheme',
						),
						array(
							'id'            => '_sppro_forms_meta_color_scheme',
							'type'          => 'select',
							'title'   	  => 'Color Scheme',
							'options'        => array(
								'master_red' => __('Master Red', 'sp-pro-txt-domain' ),
								'creamy_orange' => __('Creamy Orange', 'sp-pro-txt-domain' ),
								'light_blue' => __('Light Blue', 'sp-pro-txt-domain' ),
								'cool_green' => __('Cool Green', 'sp-pro-txt-domain' ),
								'classic_grey' => __('Classic Grey', 'sp-pro-txt-domain' ),
								'custom' => __('Choose Your Own', 'sp-pro-txt-domain' ), 	
							),
							'class' => 'chosen',
						),
						array(
							'id'		=> '_sppro_forms_meta_custom_curtain',
							'type'		=> 'background',
							'title'		=> 'Curtain Background',  
							'desc'		=> 'Background color for the overlay behind the popup',  
							'default'	=> array(
								'image'		=> '',
								'repeat'	=> 'no-repeat',
								'position'	=> 'center center',
								'attachment'=> 'fixed',
								'size'		=> 'cover',
								'color'		=> '#000',
							),
						),	
						array(
							'id'            => '_sppro_forms_meta_custom_theme_color',
							'type'          => 'color_picker',
							'title'   	  => 'Header Background',
							'help'   	  => 'Choose your color for header of the popup.',
							'dependency'   => array( '_sppro_forms_meta_color_scheme', '==', 'custom' ),							
						),	
						array(
							'id'            => '_sppro_forms_meta_custom_text_color',
							'type'          => 'color_picker',
							'title'   	  => 'Heading Color',
							'help'   	  => 'Used for popup heading, close icon and sucess/failure message in the popup.',
							'info'   	  => 'Used for popup heading, close icon and sucess/failure message in the popup.',
							'dependency'   => array( '_sppro_forms_meta_color_scheme', '==', 'custom' ),
						),
						array(
							'id'            => '_sppro_forms_meta_custom_background_color',
							'type'          => 'background',
							'title'   	    => 'Body Background',
							'dependency'    => array( '_sppro_forms_meta_color_scheme', '==', 'custom' ),
							'help' 			=> 'Choose your color for body of the popup.<br><br>For creating a single color popup, choose same color as in Header Background.',
							'default'	    => array(
								'image'		=> '',
								'repeat'	=> 'no-repeat',
								'position'	=> 'center center',
								'attachment'=> 'fixed',
								'size'		=> 'cover',
								'color'		=> '#000',
							), 
						),			
						array(
							'id'            => '_sppro_forms_meta_custom_cta_color',
							'type'          => 'color_picker',
							'title'   	  => 'Body Text Color',
							'help'   	  => 'Choose your desired color for the text and input field labels in the popup.',
							'dependency'   => array( '_sppro_forms_meta_color_scheme', '==', 'custom' ), 
						), 
						array(
							'type'    => 'subheading',
							'content' => 'Submit Button Colors',
						),						
						array(
						  'type'    => 'notice',
						  'class'   => 'warning',
						  'dependency'   => array( '_sppro_forms_meta_form_type', '==', 'sp_image' ), 
						  'content' => __('Submit button styles does not apply for this popup, please set the <strong>Side Button Colors</strong>.', 'sp-pro-txt-domain'),
						),
						array(
							'id'            => '_sppro_forms_meta_submit_button_scheme',
							'type'          => 'select',
							'title'   	  => 'Color Scheme',
							'options'        => array(
								'inherit' => __('Inherit From Color Scheme', 'sp-pro-txt-domain' ),
								'custom' => __('Set Your Own', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',
						),					
						array(
							'id'            => '_sppro_forms_meta_submit_button_background_color',
							'type'          => 'color_picker',
							'title'   	  => 'Background Color',
							'dependency'   => array( '_sppro_forms_meta_submit_button_scheme', '==', 'custom' ), 
						),
						array(
							'id'            => '_sppro_forms_meta_submit_button_text_color',
							'type'          => 'color_picker',
							'title'   	  => 'Text Color',
							'dependency'   => array( '_sppro_forms_meta_submit_button_scheme', '==', 'custom' ), 
						),	
						array(
							'type'    => 'subheading',
							'content' => 'Side Button Colors',
						),
						array(
							'id'            => '_sppro_forms_meta_side_button_scheme',
							'type'          => 'select',
							'title'   	  => 'Color Scheme',
							'options'        => array(
								'inherit' => __('Inherit From Color Scheme', 'sp-pro-txt-domain' ),
								'custom' => __('Set Your Own', 'sp-pro-txt-domain' ),
							),
							'class' => 'chosen',
						),	
						array(
							'id'            => '_sppro_forms_meta_side_button_background_color',
							'type'          => 'color_picker',
							'title'   	  => 'Background Color',
							'dependency'   => array( '_sppro_forms_meta_side_button_scheme', '==', 'custom' ),
						),	
						array(
							'id'            => '_sppro_forms_meta_side_button_text_color',
							'type'          => 'color_picker',
							'title'   	  => 'Text Color',
							'dependency'   => array( '_sppro_forms_meta_side_button_scheme', '==', 'custom' ),
						),	
					),
					'default'   => array(
						'_sppro_forms_meta_popup_layout' => 'centered',
						'_sppro_forms_meta_popup_fixed_side' => 'corner_left',
					)
				),
				
				array(
					'id'        => '_sppro_forms_meta_fieldset_advance_options',
					'type'      => 'fieldset',
					'title'     => 'Advance Settings',
					
					'fields'    => array(
						array(
							'type'    => 'subheading',
							'content' => 'Set Advance Settings',
						),
						array(
							'id'       => '_sppro_forms_meta_external_selectors',
							'type'     => 'text', 
							'title'    => __('External Selector', 'sp-pro-txt-domain'),
							'subtitle' => __('Add an external CSS selector.','sp-pro-txt-domain'), 
							'info' => __('Example:   .class-name or #id-name','sp-pro-txt-domain'), 
						),	
						array (
							'id'            => '_sppro_forms_meta_insights',
							'type'          => 'switcher',
							'dependency'   => array( '_sppro_forms_meta_form_type', 'any', 'cf7,login' ),
							'title'         => 'Enable Insights for this popup',
							'default'		=> true, 
						),						
						array (
							'id'            => '_sppro_forms_meta_autoclose',
							'type'          => 'switcher',
							'title'         => 'Close after Submission',
							'dependency'   => array( '_sppro_forms_meta_form_type', 'any', 'cf7,login' ),
							'default'		=> false, 
						),					
						array (
							'id'            => '_sppro_forms_meta_autoclose_time',
							'type'          => 'text',
							'title'         => 'Close after seconds',
							'info'         => 'Auto close after X seconds',
							'dependency'   => array( '_sppro_forms_meta_autoclose', '==', 'true' ),
							'default'		=> 5, 
						),						
						array (
							'id'            => '_sppro_forms_meta_redirect',
							'type'          => 'switcher',
							'title'         => 'Redirect after submission',
							'dependency'   => array( '_sppro_forms_meta_form_type', 'any', 'cf7,login' ),
							'default'		=> false, 
						),					
						array (
							'id'            => '_sppro_forms_meta_redirect_url',
							'type'          => 'text',
							'title'         => 'Redirect URL',
							'dependency'   	=> array( '_sppro_forms_meta_redirect|_sppro_forms_meta_form_type', '==|any', 'true|cf7,login' ),
							'desc'       	=> __('With https:// or http://', 'sp-pro-txt-domain'),
							'info'         	=> 'Enter redirect URL',
							'default'		=> '',
							//'debug'			=> true,
						),						
						array (
							'id'            => '_sppro_forms_meta_bodyscroll',
							'type'          => 'switcher',
							'title'         => 'Disabled body scroll?',							
							'default'		=> false, 
						),					
					),
				),
			),
		),
	)
);

CSFramework_Metabox::instance( $options );
