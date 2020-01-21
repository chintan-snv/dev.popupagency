<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
	if ( ! class_exists( 'Redux' ) ) {
	  // Delete tgmpa dissmiss flag
	  delete_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_myarcadetheme' );
	  return;
	}

	/** remove redux menu under the tools **/
	function sppro_remove_redux_menu() {
		remove_submenu_page('tools.php','redux-about');
	}
	add_action( 'admin_menu', 'sppro_remove_redux_menu', 12 );

	// Deactivate News Flash
	$GLOBALS['redux_notice_check'] = 0;

    // This is your option name where all the Redux data is stored.
    $opt_name = "sp_opts";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); //For use with some settings. Not necessary.
	require_once(ABSPATH.'wp-admin/includes/plugin.php');	
	$plugin = get_plugin_data( plugin_dir_path( __FILE__ ) .'../../slick-popup-pro.php' );

    $args = array(
        'opt_name' => 'sp_opts',
        'dev_mode' => false,
		'ajax_save' => true,
		'allow_tracking' => false,
		'tour' => false,  
        'use_cdn' => true,
        'display_name' => $plugin['Name'],
        'display_version' => $plugin['Version'],
        'page_slug' => 'sppro_options',
        'page_title' => $plugin['Name'] . ' Options',
        'intro_text' => $plugin['Description'],
        'footer_text' => __('We will continue to innovate new features, if you have a suggestion just let us know.', 'sp-pro-txt-domain' ),
        'page_parent' => 'sp-pro',
        //'page_parent_post_type' => 'page',        
		'admin_bar' => false,
        'menu_type' => 'submenu',        
		'menu_icon' => plugins_url( 'img/menu_icon.png', __FILE__ ),
        'menu_title' => 'Global Options',
        'allow_sub_menu' => false,
        'default_show' => TRUE,
        'default_mark' => '*',
        'google_api_key' => 'AIzaSyB8QWjiiDqvVuTgOP1F394771EHteUu2CU',
        'class' => 'sppro_container',
		
        'hints' => array(
            'icon' => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color' => '#23282D',
			'icon_size' => 'normal',
            'tip_style' => array(
				'color'   => 'red',
				'shadow'  => true,
				'rounded' => false,
				'style'   => 'cluetip',
			),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect'   => 'fade',
					'duration' => '50',
					'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'fade',
					'duration' => '50',
					'event'    => 'click mouseleave',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => FALSE,
		'show_options_object' => FALSE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
        'hide_reset' => TRUE,
		'footer_credit' => 'Slick Popup Pro by <a href="https://www.slickpopup.com/">Om Ak Solutions</a>',
    );

    
    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'] = array(); 
	$args['admin_bar_links'][] = array(
        'id'    => 'sp-demo',
        'href'  => 'https://www.slickpopup.com/',
        'title' => __( 'Demo', 'sp-pro-txt-domain' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'sp-support',
        'href'  => 'http://codecanyon.net/item/slick-popup-pro-/16115931/support',
        'title' => __( 'Support', 'sp-pro-txt-domain' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'sp-docs',
        'href'  => 'https://www.slickpopup.com/docs',
        'title' => __( 'Documentation', 'sp-pro-txt-domain' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'] = array(); 
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/OmAkSolutions',
        'title' => __('Like us on Facebook', 'sp-pro-txt-domain' ),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/singlaAk',
        'title' => __('Follow us on Twitter', 'sp-pro-txt-domain' ),
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/Om-Ak-Solutions',
        'title' => __('Find us on LinkedIn', 'sp-pro-txt-domain' ),
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '', 'sp-pro-txt-domain' ), $v );
    } else {
        $args['intro_text'] = __( '', 'sp-pro-txt-domain' );
    }
	
	// Intro Text Emptied
	$args['intro_text'] = sprintf( __( '', 'sp-pro-txt-domain' ), $v );	
	
    // Add content after the form.
    $args['footer_text'] = __( '<p>We will continue to innovate new features, if you have a suggestion just let us know at <strong><a href="'.admin_url('/admin.php?page=sp-pro-help').'">Support</a></strong> Page</p>', 'sp-pro-txt-domain' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'sp-pro-helptab-1',
            'title'   => __( 'Support', 'sp-pro-txt-domain' ),
            'content' => __( '<p>If you face any issues using the plugin, please shoot us an e-mail at: info@omaksolutions.com</p>', 'sp-pro-txt-domain' )
        ),
		array(
            'id'      => 'sp-pro-helptab-2',
            'title'   => __( 'Support', 'sp-pro-txt-domain' ),
            'content' => __( '<p>If you face any issues using the plugin, please shoot us an e-mail at: info@omaksolutions.com</p>', 'sp-pro-txt-domain' )
        ),
    );
	unset( $tabs[1] );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p><strong>We are mostly online at Skype: ak.singla47</strong></p>', 'sp-pro-txt-domain' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
	
	/////////////////////////////////////////////////
	// SECTION: Configuration
	/////////////////////////////////////////////////
	if ( 1 ) {
    	Redux::setSection( $opt_name, array(
			'title'  => __( 'Configuration', 'sp-pro-txt-domain' ),
			'id'     => 'configuration-settings',
			'desc'   => __( '', 'sp-pro-txt-domain' ),
			'icon'   => 'el el-cog',
			'fields' => array(
				array(
					'id'       => 'plugin_state',
					'type'     => 'switch',
					'title'    => __( 'Global Popup', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Disable Global Popup', 'sp-pro-txt-domain' ),
					'default'  => 1,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Global Popup',
						'content'   => 'Will disable Global Popup but all the popups in manage popups will work.',
					),
				),	
				array(
					'id'       => 'plugin_state_on_mobile',
					'type'     => 'switch',
					'required' => array( 'plugin_state', '=', '1' ),
					'title'    => __( 'Mobile State', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Enable/Disable on Mobile View.', 'sp-pro-txt-domain' ),
					'default' => __( '<b>Default:</b> Enable', 'sp-pro-txt-domain' ),
					'default'  => 1,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Mobile State',
						'content'   => 'Disable - will complete switch off all functionality of the plugin on the front-end.',
					),
				),					
				array(
					'id'       => 'delete_data',
					'type'     => 'switch',
					'required' => array( 'plugin_state', '=', '1' ),
					'title'    => __( 'Keep Settings', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Keep/Delete plugin settings after uninstallation.', 'sp-pro-txt-domain' ),
					'default'  => 0,
					'on'       => __('Delete', 'sp-pro-txt-domain' ),
					'off'      => __('Keep', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => __('Keep Settings', 'sp-pro-txt-domain' ),
						'content'   => __('Choose <b>Keep</b> if you do not plan to copmletely remove the plugin settings after uninstallation.', 'sp-pro-txt-domain' ),
					),
				),		
			)
		) );
	} // endif 1
	
	/////////////////////////////////////////////////
	// SECTION: Popup Form
	/////////////////////////////////////////////////
	if ( 1 ) {		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Popup Form', 'sp-pro-txt-domain' ),
			'id'    => 'side-button-settings',
			'desc'  => __( 'Set the desired settings for popup.', 'sp-pro-txt-domain' ),
			'icon'  => 'el el-iphone-home',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Choose Form and Where
				////////////////////////////////////////////////
				array(
					'id'       => 'section-choose-form',
					'type'     => 'section',
					'title'    => __( 'Which form and where?', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Choose your desired Contact Form 7 and where to show it as a popup.', 'sp-pro-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'            => 'form-id',
						'type'          => 'select',
						'data' 			=> 'posts',
						'args' 			=> array('post_type' => array('wpcf7_contact_form'), 'posts_per_page' => -1),
						'required' 		=> array( 'plugin_state', '=', '1' ),
						'title'         => __( 'Form to use?', 'sp-pro-txt-domain' ),
						'subtitle'      => __( '<span style="color:red;font-weight:bold;display:inline;">IMPORTANT!</span><br/>Choose the Contact Form 7 form to be used in the popup.', 'sp-pro-txt-domain' ),
						'desc'          => __( '<a target="_blank" href="', 'sp-pro-txt-domain' ) .admin_url( '/admin.php?page=wpcf7' ). __( '">See all Contact Forms</a>', 'sp-pro-txt-domain' ),
					),		
					array(
						'id'            => 'where_to_show',
						'type'          => 'select',
						'required' 		=> array( 'plugin_state', '=', '1' ),
						'title'         => __( 'Where to show the form?', 'sp-pro-txt-domain' ),
						'subtitle'      => __( 'Choose the display of the popup form.', 'sp-pro-txt-domain' ),
						'desc'          => __( '', 'sp-pro-txt-domain' ),
						'options'  => array(
									'everywhere' => 'Everywhere',
									'onselected' => 'Only Selected Pages',
									'notonselected' => __('Not On Selected Pages', 'sp-pro-txt-domain' ),
								),
						'default'  => 'everywhere'
					),
					array(
						'id'            => 'choose_pages',
						'type'          => 'select',
						'multi'          => true,
						'data' 			=> 'pages',
						'args' 			=> array( 'posts_per_page' => -1),
						'required' 		=> array( array('plugin_state', '=', '1'), array('where_to_show', '!=', 'everywhere') ),
						'title'         => __( 'Choose Your Pages', 'sp-pro-txt-domain' ),
						'subtitle'      => __( 'Select the pages to exclude or include for popup form display.', 'sp-pro-txt-domain' ),
						'desc'          => __( '<a target="_blank" href="', 'sp-pro-txt-domain' ) .admin_url( '/edit.php?post_type=page' ). __( '">See all Pages</a>', 'sp-pro-txt-domain' ),
					),				
				/////////////////////////////////////////////////
				// Section: Heading & Description 
				////////////////////////////////////////////////
				array(
					'id'       => 'section-heading-description',
					'type'     => 'section',
					'title'    => __( 'Heading & Description', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Choose your desired heading and description settings.', 'sp-pro-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'       => 'popup-heading',
						'type'     => 'text',
						'title'    => __( 'Heading', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Main heading on the popup.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<b>Default:</b> STILL NOT SURE WHAT TO DO?', 'sp-pro-txt-domain' ),
						'default'  => 'STILL NOT SURE WHAT TO DO?',
						'hint'      => array(
							'title'     => 'Popup Heading',
							'content'   => 'Main heading of the popup.',
						),
					),
					array(
						'id'       => 'heading-typography',
						'type'     => 'typography',
						//'required' => array( 'use_heading_font', '=', 1 ),
						'title'    => __( 'Heading Font', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Specify the heading font properties.', 'sp-pro-txt-domain' ),
						'desc'		=> __('Font Color is important to look good with your choosen color scheme.', 'sp-pro-txt-domain' ),
						'google'   => true,
						'default'  => array(
							'color'       => 	'#F1F1F1',
							'font-size'   => 	'28px',
							'line-height' =>	'32px',
							'font-family' => 	'Open Sans',
							'font-weight' => 	'900',
							'font-style' => 	'inherit',						
							'text-align' => 	'center',
						),
						'subsets'	=> false,
					),
					array(
						'id'       => 'popup-cta-text',
						'type'     => 'editor',
						'title'    => __( 'Call To Action', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Main description that will actually make your visitor to fill up the form.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<b>Default:</b> We are glad that you preferred to contact us. Please fill our short form and one of our friendly team members will contact you back shortly.', 'sp-pro-txt-domain' ),
						'default'  => 'We are glad that you preferred to contact us. Please fill our short form and one of our friendly team members will contact you back.',
						'args'   => array(
							'teeny'            => true,
							'textarea_rows'    => 5
						),
						'hint'      => array(
							'title'     => 'Call To Action',
							'content'   => 'This text will appear above the form. Choose something that encourages user to fill up the form.',
						),
					),
					array(
						'id'       => 'cta-typography',
						'type'     => 'typography',
						//'required' => array( 'use_cta_font', '=', 1 ),
						'title'    => __( 'Call To Action Font', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Specify these font properties.', 'sp-pro-txt-domain' ),
						'google'   => true,
						'default'  => array(
							'color'       => '#484848',
							'font-size'   => '13px',
							'line-height'   => '21px',
							'font-family' => 'Noto Sans',
							'font-weight' => 	'normal',
							'font-style' => 	'inherit',						
							'text-align' => 	'center',
						),
						'subsets'	=> false,
					),	
				array(
					'id'       => 'side-button-position',
					'type'     => 'select',
					'title'    => __( 'Side Button Position', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Choose the position of side button.', 'sp-pro-txt-domain' ),
					'desc'     => __( '', 'sp-pro-txt-domain' ),
					//Must provide key => value pairs for select options
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
					'default'  => 'pos_right'
				),				
					array(
						'id'       => 'side-button-text',
						'type'     => 'text',
						'title'    => __( 'Button Text', 'sp-pro-txt-domain' ),
						'required' => array( 'side-button-position', '!=', 'pos_none' ),
						'subtitle'     => __( 'What should your button say?', 'sp-pro-txt-domain' ),
						'desc' => __( '<b>Suggestions:</b> "Need Help?" "Subscribe" "Get a quote!" "Have a query?"<br/><b>Default:</b> Contact Us', 'sp-pro-txt-domain' ),
						'default'  => 'CONTACT US',
					),							
					array(
						'id'       => 'side-button-typography',
						'type'     => 'typography',
						'required' => array( 'side-button-position', '!=', 'pos_none' ),
						'title'    => __( 'Button Font', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Set styles for the side button.', 'sp-pro-txt-domain' ),
						'google'   => true,
						'default'  => array(
							'font-family' 	=> 'Open Sans',
							'color'       => '#F1F1F1',
							'font-size'   => '14px',
							'line-height'   => '18px',
							'font-weight' 	=> '700',
						),				
						'text-align'	=> false,
						'subsets'	=> false,
					),	
			)
		) );
	}

	/////////////////////////////////////////////////
	// SECTION: Layout & Colors
	/////////////////////////////////////////////////
	if ( 1 ) {
		Redux::setSection( $opt_name, array(
			'title' => __( 'Layout & Colors', 'sp-pro-txt-domain' ),
			'id'    => 'popup-styles',
			'desc'  => __( '', 'sp-pro-txt-domain' ),
			'icon'  => 'el el-comment',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Layout & Color Scheme (layout)
				////////////////////////////////////////////////
					array(
						'id'       => 'section-layout',
						'type'     => 'section',				
						'title'    => __( 'Layout', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Choose your desired layout for the popup.', 'sp-pro-txt-domain' ),
						'indent'   => true, // Indent all options below until the next 'section' option is set.
					),
						array(
							'id'       => 'choose-layout',
							'type'     => 'select',
							'title'    => __( 'Choose Layout', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Choose your desired layout.', 'sp-pro-txt-domain' ),
							'desc'     => __( '', 'sp-pro-txt-domain' ),
							'options'  => array(
								// Removed Image Select on 29 August, before in 2.1.4 version
								'centered' => __( 'Centered in Screen', 'sp-pro-txt-domain' ),
								'widgetized' => __( 'Widget Like Popup', 'sp-pro-txt-domain' ),								
								'full-page' => __( 'Full Page Popup', 'sp-pro-txt-domain' ),								
								'full' => __( 'Full Height Popup', 'sp-pro-txt-domain' ),
								'corner-fixed' => __( 'Fixed To A Side', 'sp-pro-txt-domain' ),									
							),
							'default'  => 'centered',
							'hint'     => array(
								'title'     => __('Choose Layout', 'sp-pro-txt-domain' ),
								'content'   => __('Currently two layouts available: <b>Full</b> and <b>Centered</b>. Full means full height popup in the center of the screen, and Centered has some space above and below the popup.', 'sp-pro-txt-domain' ),
							),
						),
							array(
								'id'       => 'fixed-corner',
								'type'     => 'select',
								'required' => array( 'choose-layout', '=', 'corner-fixed' ),
								'title'    => __( 'Fixed To Side', 'sp-pro-txt-domain' ),
								'subtitle' => __( 'Choose the side where popup should be fixed.', 'sp-pro-txt-domain' ),
								'desc'     => __( '<b>Default:</b> Left. See Hint.', 'sp-pro-txt-domain' ),
								'options'  => array( 
									'corner_left' => __( 'Fixed To Left Side', 'sp-pro-txt-domain' ),
									'corner_right' => __( 'Fixed To Right Side', 'sp-pro-txt-domain' ),
								),
								'default'  => 'corner_left',
								'hint'     => array(
									'title'     => __('Fixed Corner Side', 'sp-pro-txt-domain' ),
									'content'   => __('This option lets you choose the popup side. It only applies when "Fixed To A Side" layout is choosen.', 'sp-pro-txt-domain' ),
								),								
							),
							array(
								'id'       => 'widgetized-popup',
								'type'     => 'select',
								'required' => array( 'choose-layout', '=', 'widgetized' ),
								'title'    => __( 'Choose Desired Widget', 'sp-pro-txt-domain' ),
								'subtitle' => __( 'Choose the side where you want to show the widget.', 'sp-pro-txt-domain' ),
								'desc'     => __( '<b>Default:</b> Right Bottom. See Hint.', 'sp-pro-txt-domain' ),
								//Must provide key => value pairs for select options
								'options'  => array(
									'right_bottom' => 'At Bottom Right',
									'left_bottom' => 'At Bottom Left',
									'right_top' => 'At Top Right',
									'left_top' => 'At Top Left',
									'centered_bottom' => 'At Center Bottom',
									'centered_top' => 'At Center Top',
								),									
								'default'  => 'right_bottom',
								'hint'     => array(
									'title'     => __('Widget Position', 'sp-pro-txt-domain' ),
									'content'   => __('This option lets you choose the desired location for widgetized popup.', 'sp-pro-txt-domain' ),
								),								
							),	
							array(
								'id'       => 'popup-corners',
								'type'     => 'select',
								'title'    => __( 'Popup Corners', 'sp-pro-txt-domain' ),
								'subtitle' => __( 'Choose the radius of the popup border.', 'sp-pro-txt-domain' ),
								'desc'     => __( '<b>Default:</b> Square (Zero roundness)', 'sp-pro-txt-domain' ),
								//Must provide key => value pairs for select options
								'options'  => array(
									'square' => 'Square (0px)',					
									'rounded' => 'Rounded (20px)',
									'custom' => __('Set Your Own', 'sp-pro-txt-domain' ),
								),
								'default'  => 'square'
							),		
							array(
								'id'       => 'custom-popup-border',
								'type'           => 'dimensions',             
								'required' => array( 'popup-corners', '=', 'custom' ),
								'output'   => array( '' ),
								'units'          => array( 'px', '%' ),    // You can specify a unit value. Possible: px, em, %
								'units_extended' => 'true',  // Allow users to select any type of unit
								'title'          => __( 'Popup Border Radius', 'sp-pro-txt-domain' ),
								'subtitle'       => __( 'Set a border radius property for the popup.', 'sp-pro-txt-domain' ),
								'desc'           => __( 'Units: px or % (50% is max).', 'sp-pro-txt-domain' ),
								'height'         => false,
								'default'        => array(
									'width'  => 20,
									'height' => 100,
								)
							),
						array(
							'id'       => 'custom-popup-layout',
							'type'     => 'select',
							'title'    => __( 'Height & Width', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Use pre-defined layouts or set your own height and width.', 'sp-pro-txt-domain' ),
							'desc'     => __( '', 'sp-pro-txt-domain' ),
							//Must provide key => value pairs for select options
							'options'  => array(
								'predefined' => __( 'As Per Choosen Layout', 'sp-pro-txt-domain' ),
								'change' => __( 'Set Your Own Height and Width', 'sp-pro-txt-domain' ),								
							),
							'default'  => 'predefined'
						),	
							array(
								'id'       => 'popup-width',
								'type'     => 'dimensions',
								//'units'    => array('em','px','%'),							
								'units'    => array('px','%'),
								'required' => array( 'custom-popup-layout', '=', 'change' ),
								'title'    => __('Popup Width', 'sp-pro-txt-domain'),
								'subtitle' => __('Set width of the popup.', 'sp-pro-txt-domain'),
								'desc'     => __('Demo forms have width: 600px', 'sp-pro-txt-domain'),
								'height' 	=> false,
								'default'  => array(
									'width'  => '600'
								),
							),
							array(
								'id'       => 'popup-height',
								'type'     => 'dimensions',
								'units'    => array('%','px'),
								'required' => array( 'custom-popup-layout', '=', 'change' ),
								'title'    => __('Popup Height', 'sp-pro-txt-domain'),
								'subtitle' => __('Set height of the popup.', 'sp-pro-txt-domain'),
								'desc'     => __('It is suggested that you choose a percent based height.', 'sp-pro-txt-domain'),
								'width' 	=> false,
								'default'  => array(
									'height'  => '76'
								),
							),
						
				/////////////////////////////////////////////////
				// Section: Color Scheme
				////////////////////////////////////////////////		
				array(
					'id'       => 'section-color-scheme',
					'type'     => 'section',				
					'title'    => __( 'Color Scheme', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Set color scheme for popup, text and the curtain behind the popup.', 'sp-pro-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(         
						'id'       => 'curtain-background',
						'type'     => 'background',
						'title'    => __('Curtain Background', 'sp-pro-txt-domain'),
						'subtitle' => __('Set background for the curtain behind the popup. To have no curtain choose check the Transparent checkbox.', 'sp-pro-txt-domain'),
						'desc'     => __('This will change the overlay behind the popup.', 'sp-pro-txt-domain'),
						'default'	=> array(
							'background-image'		=> '',
							'background-repeat'		=> 'no-repeat',
							'background-position'	=> 'center center',
							'background-attachment'	=> '',
							'background-size'		=> 'cover',
							'background-color'		=> '#000',
						),
					),
					array(
						'id'       => 'choose-color-scheme',
						'type'     => 'image_select',
						'title'    => __( 'Color Scheme', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Choose your desired cover scheme.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<span style="font-weight:bold;font-size:1.1em;">Choose one of our pre-defined color schemes or set your own.</span>', 'sp-pro-txt-domain' ),
						'options'  => array(
							'master_red' => array(
								'alt' => __('Master Red', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-master-red.png',
								'title' => __('Master Red', 'sp-pro-txt-domain' ),
							),
							'creamy_orange' => array(
								'alt' => __('Creamy Orange', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-creamy-orange.png',
								'title' => __('Creamy Orange', 'sp-pro-txt-domain' ),
							),
							'light_blue' => array(
								'alt' => __('Light Blue', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-light-blue.png',
								'title' => __('Light Blue', 'sp-pro-txt-domain' ),
							),
							'cool_green' => array(
								'alt' => __('Cool Green', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-cool-green.png',
								'title' => __('Cool Green', 'sp-pro-txt-domain' ),
							),						
							'dark' => array(
								'alt' => __('Classic Grey', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-classic-grey.png',
								'title' => __('Classic Grey', 'sp-pro-txt-domain' ),
							),
							'custom_theme' => array(
								'alt' => __('Set Your Own', 'sp-pro-txt-domain' ),
								'img' => SPPRO_PLUGIN_IMG_URL . '/scheme-custom-theme.png',
								'title' => __('Set Your Own', 'sp-pro-txt-domain' ),
							),
						),
						'default'  => 'cool_green'
					),
						// If Color Scheme = custom_theme
						array(
							'id'       => 'custom-theme-color',
							'type'     => 'color',
							'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
							'output'   => array( '' ),
							'title'    => __( 'Your Theme Color', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Pick a color for your theme.', 'sp-pro-txt-domain' ),
							'desc' => __( 'This color will be used to create theme of your popup.', 'sp-pro-txt-domain' ),
							'default'  => '#333',
						),
						array(
							'id'       => 'custom-form-background-color',
							'type'     => 'color',
							'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
							'output'   => array( '' ),
							'title'    => __( 'Form Background', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Pick a color for form background.', 'sp-pro-txt-domain' ),
							'desc' => __( 'This color will be used as the popup form background.', 'sp-pro-txt-domain' ),
							'default'  => '#EFEFEF',
						),							
						array(
							'id'       => 'custom-text-color',
							'type'     => 'color',
							'required' => array( 'choose-color-scheme', '=', 'custom_theme' ),
							'output'   => array( '' ),
							'title'    => __( 'Your Text Color', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Pick a color for any text element added in form.', 'sp-pro-txt-domain' ),
							'desc' => __( 'This also applies to <strong>Close Icon "X"</strong> and <strong>form submission response.</strong>', 'sp-pro-txt-domain' ),
							'default'  => '#EFEFEF',
						),
					// Scrollbar Options
					array(
						'id'       => 'autohidemode',
						'type'     => 'select',
						'title'    => __( 'Scrollbar in Popup', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Choose to show or hide the srollbar in popup when form is longer than the popup height.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<b>Default:</b> Hidden.', 'sp-pro-txt-domain' ),
						'options'  => array( 
							'hidden' => __( 'Do Not Show', 'sp-pro-txt-domain' ),
							'false' => __( 'Always Visible', 'sp-pro-txt-domain' ),
							'leave' => __( 'Only When Cursor Over Form', 'sp-pro-txt-domain' ),
							'scroll' => __( 'Only When Scrolling', 'sp-pro-txt-domain' ),
						),
						'default'  => 'hidden',		
					),
						array(
							'id'       => 'cursorbackground',
							'type'     => 'color',
							'required' => array( 'autohidemode', '!=', 'hidden' ),
							'output'   => array( '' ),
							'title'    => __( 'Cursor Background Color', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Choose a color for the cursor.', 'sp-pro-txt-domain' ),
							'desc' => __( 'This color will be used to create theme of your popup.', 'sp-pro-txt-domain' ),
							'default'  => '#757575',
						),
						array(
							'id'       => 'cursorcolor',
							'type'     => 'color',
							'required' => array( 'autohidemode', '!=', 'hidden' ),
							'output'   => array( '' ),
							'title'    => __( 'Cursor Color', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Choose a color for the cursor.', 'sp-pro-txt-domain' ),
							'desc' => __( 'This color will be used to create theme of your popup.', 'sp-pro-txt-domain' ),
							'default'  => '#333',
						),
						array(
							'id'       => 'cursorwidth',
							'type'     => 'text',
							'required' => array( 'autohidemode', '!=', 'hidden' ),
							'output'   => array( '' ),
							'title'    => __( 'Scrollbar Width (px)', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Width of the scrollbar.', 'sp-pro-txt-domain' ),
							'default'  => '10px',
						),
						array(
							'id'       => 'cursorborderradius',
							'type'     => 'text',
							'required' => array( 'autohidemode', '!=', 'hidden' ),
							'output'   => array( '' ),
							'title'    => __( 'Scrollbar Roundness (px)', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Roundness of the scrollbar corners.', 'sp-pro-txt-domain' ),
							'default'  => '5px',
						),
					array(
						'id'       => 'choose-submit-button',
						'type'     => 'select',
						'title'    => __( 'Submit Button Styles', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Set "Submit/Send" button of the form.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<b>Default:</b> Inherit', 'sp-pro-txt-domain' ),
						//Must provide key => value pairs for select options
						'options'  => array(
							'inherit_from_theme' => __('Use styles from theme', 'sp-pro-txt-domain' ),
							'inherit_from_color_scheme' => __('Inherit from color scheme', 'sp-pro-txt-domain' ),
							'custom' => __('Set your own colors', 'sp-pro-txt-domain' ),
						),
						'default'  => 'inherit_from_color_scheme',
						'hint'      => array(
							'title'     => __( 'Button Styles', 'sp-pro-txt-domain' ),
							'content'   =>  __( '<b>Use styles from theme</b> - will load theme styles<br/><b>Inherit from color scheme</b> - will use Plugin Theme<br/><b>Set your own colors</b> - set own background color', 'sp-pro-txt-domain' ),
						),
					),	
						// If choose-submit-button = custom
						array(
							'id'       => 'submit-button-background',
							'type'     => 'background',
							'required' => array( 'choose-submit-button', '=', 'custom' ),
							'output'   => array( '' ),
							'title'    => __( 'Button Background', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Choose background color for the "Submit" button.', 'sp-pro-txt-domain' ),
							'default'   => array( 
								'background-color' => '#333333',
							),
							'background-color'			=> true,
							'background-repeat'			=> false,
							'background-attachment'		=> false,
							'background-position'		=> false,
							'background-image'			=> false,
							'background-clip'			=> false,
							'background-origin'			=> false,
							'background-size'			=> false,
							'preview_media'				=> false,
							'preview'					=> false,
							'preview_height'			=> false,
							'transparent'				=> false,
						),			
						array(
							'id'       => 'submit-button-border',
							'type'     => 'border',
							'required' => array( 'choose-submit-button', '=', 'custom' ),
							'title'    => __( 'Button Border', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Set properties for the submit button border.', 'sp-pro-txt-domain' ),
							'output'   => array( '' ),
							'all'      => false,
							// An array of CSS selectors to apply this font style to
							'desc'     => __( '', 'sp-pro-txt-domain' ),
							'default'  => array(
								'border-color'  => '#f5f5f5',
								'border-style'  => 'solid',
								'border-top'    => '2px',
								'border-right'  => '2px',
								'border-bottom' => '2px',
								'border-left'   => '2px'
							)
						),
					array(
						'id'       => 'submit-button-typography',
						'type'     => 'typography',
						//'required' => array( 'use_submit_button_font', '=', 1 ),
						'title'    => __( 'Button Font', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Styles for submit/send button in the form. You do not need to style your button in the Contact Form 7.', 'sp-pro-txt-domain' ),					
						'google'   => true,
						'default'  => array(						
							'font-family' 	=> 'Open Sans',
							'color'       	=> '#F1F1F1',
							'font-size'   	=> '22px',
							'line-height'   => '24px',
							'font-weight' 	=> '700',
						),				
						'text-align'	=> false,
						'subsets'	=> false,
					),
					array(
						'id'       => 'choose-side-button',
						'type'     => 'select',
						'title'    => __( 'Side Button Styles', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Choose styles and appearance for side button. <br><br/> To change font of the side button go to Layout & Colors section.', 'sp-pro-txt-domain' ),
						'desc'     => __( '<b>Default:</b> Inherit From Color Scheme', 'sp-pro-txt-domain' ),
						//Must provide key => value pairs for select options
						'options'  => array(
							'inherit' => __('Inherit From Color Scheme', 'sp-pro-txt-domain' ),
							'custom' => __('Set Your Own', 'sp-pro-txt-domain' ),
						),
						'default'  => 'inherit',
						'hint'      => array(
							'title'     => __( 'Color Scheme', 'sp-pro-txt-domain' ),
							'content'   =>  __( 'Choose one of the pre-packed color themes or create your own.', 'sp-pro-txt-domain' ),
						),
					),			
						array(
							'id'       => 'side-button-background',
							'type'     => 'background',
							'required' => array( 'choose-side-button', '=', 'custom' ),
							'output'   => array( '' ),
							'title'    => __( 'Button Background', 'sp-pro-txt-domain' ),
							'subtitle' => __( 'Button background with image, color, etc.', 'sp-pro-txt-domain' ),
							'default'   => array( 
									'background-color' => '#333333',
								),
							'background-color'			=> true,
							'background-repeat'			=> false,
							'background-attachment'		=> false,
							'background-position'		=> false,
							'background-image'			=> false,
							'background-clip'			=> false,
							'background-origin'			=> false,
							'background-size'			=> false,
							'preview_media'				=> false,
							'preview'					=> false,
							'preview_height'			=> false,
							'transparent'			=> false,
						),				
				) // end fields array
			)
		);
	}
	
	
	/////////////////////////////////////////////////
	// SECTION: Popup Animations
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Popup Effects', 'sp-pro-txt-domain' ),
			'id'    => 'loader-popup-effects',
			'desc'  => __( 'Control the popup activation mode and animation effects.', 'sp-pro-txt-domain' ),
			'icon'  => 'el el-iphone-home',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Activation Mode
				////////////////////////////////////////////////				
				array(
					'id'       => 'section-activation-mode',
					'type'     => 'section',				
					'title'    => __( 'Activation Mode', 'sp-pro-txt-domain' ),
					'subtitle' => __( '', 'sp-pro-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'            => 'activation_mode',
						'type'          => 'select',
						'title'         => __( 'How to activate popup?', 'sp-pro-txt-domain' ),
						'subtitle'      => __( 'Choose how the popup should activate.', 'sp-pro-txt-domain' ),
						'desc'          => __( '4 modes: On-click, Auto Popup, On-scroll Popup, Forced Popup', 'sp-pro-txt-domain' ),
						'options'  => array(
									'manually' => __('On-Click (Default)', 'sp-pro-txt-domain' ),
									'autopopup' => __('Auto Popup on page load', 'sp-pro-txt-domain' ),
									'onscroll' => __('On Scrolling the page', 'sp-pro-txt-domain' ),
									'onexit' => __('While exiting the page', 'sp-pro-txt-domain' ),
									'forced' => __('Force user to fill form', 'sp-pro-txt-domain' ),
								),
						'default'  => 'manually'
					),	
						// Auto Popup Delay
						array(
							'id'       => 'autopopup-delay',
							'type'     => 'slider', 
							'title'    => __('Auto Popup Delay', 'sp-pro-txt-domain'),
							'required' => array( 'activation_mode', '=', 'autopopup' ),
							'subtitle' => __('After how many seconds should it show?','sp-pro-txt-domain'),
							'desc'     => __('Range: 1 second to 10 minutes', 'sp-pro-txt-domain'),
							'default' => 5,
							'min' => 0,
							'step' => 1,
							'max' => 600,
							'resolution' => 1,
							'display_value' => 'text'
						),	
						// On Scroll Delay
						array(
							'id'       => 'onscroll-type',
							'type'     => 'select', 
							'title'    => __('On-scroll Type', 'sp-pro-txt-domain'),
							'required' => array( 'activation_mode', '=', 'onscroll' ),
							'subtitle' => __('Choose your measurement dimension','sp-pro-txt-domain'),
							'desc'     => __('', 'sp-pro-txt-domain'),						
							'default' => 'pixels',
							'options'  => array(
								'pixels' => 'Pixels',
								'percentage' => 'Percentage',
							),
						),	
						array(
							'id'       => 'onscroll-pixels',
							'type'     => 'text', 
							'title'    => __('Pixels Scrolled Down', 'sp-pro-txt-domain'),
							'required' => array( 'onscroll-type', '=', 'pixels' ),
							'subtitle' => __('Popup after scrolling pixels','sp-pro-txt-domain'),
							'desc'     => __('', 'sp-pro-txt-domain'),
							'default'  => 250, 
						),
						array(
							'id'       => 'onscroll-percentage',
							'type'     => 'slider', 
							'title'    => __('Percentage Scrolled Down', 'sp-pro-txt-domain'),
							'required' => array( 'onscroll-type', '=', 'percentage' ),
							'subtitle' => __('Percentage of page scroll','sp-pro-txt-domain'),
							'desc'     => __('Range: 0-100', 'sp-pro-txt-domain'),
							'default' => 60,
							'min' => 0,
							'step' => 1,
							'max' => 100,
							'resolution' => 1,
							'display_value' => 'text'
						), 
						// Auto Popup Cookie Settings
						array(
							'id'       => 'cookie-delay',
							'type'     => 'select', 
							'title'    => __('Re-ask After', 'sp-pro-txt-domain'),
							'required' => array( array('activation_mode', '=', array('autopopup', 'onscroll') ) ),
							'subtitle' => __('In how manny days should user see the popup after it is cancelled or filled.','sp-pro-txt-domain'),
							'desc'     => __('Applicable for 2 Modes: Auto Popup and On-Scroll Popup', 'sp-pro-txt-domain'),
							'default' => 'days',		
							'options'  => array(
								'-1' => __( 'Everytime a page loads', 'sp-pro-txt-domain' ),
								'0' => __( 'Once per session', 'sp-pro-txt-domain' ),
								'days' => __( 'After X Days', 'sp-pro-txt-domain' ),
							),							
						),						
						array(
							'id'       => 'cookie-days',
							'type'     => 'text', 
							'title'    => __('How many days?', 'sp-pro-txt-domain'),
							'required' => array( 
								array('activation_mode', '=', array('autopopup', 'onscroll') ),
								array('cookie-delay', '=', array('days') )
							),
							'subtitle' => __('Please enter the number of days after which the popup should be re-shown to the user.','sp-pro-txt-domain'),							
							'default' => 7,				
						),
						array(
							'id'     => 'notice-activation-mode',
							'type'   => 'info',
							'style'   => 'info',
							'required' => array( 'activation_mode', '!=', 'manually' ),
							'notice' => false,
							'desc'   => __( 'You may want to hide "Side button" in this case, please go to Side Button settings.', 'sp-pro-txt-domain' )
						),
						
				/////////////////////////////////////////////////
				// Section: Animations
				////////////////////////////////////////////////				
				array(
					'id'       => 'section-animations',
					'type'     => 'section',				
					'title'    => __( 'Animations settings', 'sp-pro-txt-domain' ),
					'subtitle' => __( '', 'sp-pro-txt-domain' ),
					'indent'   => true, // Indent all options below until the next 'section' option is set.
				),
					array(
						'id'       => 'loader-animation',
						'type'     => 'select',
						'title'    => __( 'onLoad Effect', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Animation when loading popup', 'sp-pro-txt-domain' ),
						'desc'     => __( '', 'sp-pro-txt-domain' ),
						'default'  => 'fadeInDown',
						'options'  => array(
							'FadeIn Effects' => array(
								'fadeIn' => 'fadeIn',
								'fadeInDown' => 'fadeInDown',
								'fadeInUp' => 'fadeInUp',
								'fadeInRight' => 'fadeInRight',
								'fadeInLeft' => 'fadeInLeft',
							),
							'Bouncing Entrances' => array(
								'bounceIn' => 'bounceIn', 
								'bounceInDown' => 'bounceInDown', 
								'bounceInLeft' => 'bounceInLeft', 
								'bounceInRight' => 'bounceInRight', 
								'bounceInUp' => 'bounceInUp', 
							),
							'Zoom Entrances' => array(
								'zoomIn' => 'zoomIn', 
								'zoomInDown' => 'zoomInDown', 
								'zoomInLeft' => 'zoomInLeft', 
								'zoomInRight' => 'zoomInRight', 
								'zoomInUp' => 'zoomInUp', 
							),
							'Flippers' => array(
								'flip' => 'flip', 
								'flipInX' => 'flipInX', 
								'flipInY' => 'flipInY', 
								'flipOutX' => 'flipOutX', 
								'flipOutY' => 'flipOutY', 
							),
							'Attention Seekers' => array(
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
						),
					),
					array(
						'id'       => 'loader-speed',
						'type'     => 'slider', 
						'title'    => __('onLoad Speed', 'sp-pro-txt-domain'),
						'subtitle' => __('Set Popup load speed','sp-pro-txt-domain'),
						'desc'     => __('Min:0.1, Max:5, Best: 0-1' , 'sp-pro-txt-domain'),
						'default' => .65,
						'min' => 0,
						'step' => .1,
						'max' => 5,
						'resolution' => 0.01,
						'display_value' => 'text'
					),	
					array(
						'id'       => 'unloader-animation',
						'type'     => 'select',
						'title'    => __( 'unLoad Effect', 'sp-pro-txt-domain' ),
						'subtitle' => __( 'Animation when unloading popup', 'sp-pro-txt-domain' ),
						'desc'     => __( '', 'sp-pro-txt-domain' ),
						'default'  => 'fadeOutDown',						
						'options'  => array(
							'FadeOut Effects' => array(
								'fadeOut' => 'fadeOut',
								'fadeOutDown' => 'fadeOutDown',
								'fadeOutUp' => 'fadeOutUp',
								'fadeOutRight' => 'fadeOutRight',
								'fadeOutLeft' => 'fadeOutLeft',
							),														
							'Bouncing Exits' => array(
								'bounceOut' => 'bounceOut', 
								'bounceOutDown' => 'bounceOutDown', 
								'bounceOutLeft' => 'bounceOutLeft', 
								'bounceOutRight' => 'bounceOutRight', 
								'bounceOutUp' => 'bounceOutUp', 
							),
							'Zoom Exits' => array(
								'zoomOut' => 'zoomOut', 
								'zoomOutDown' => 'zoomOutDown', 
								'zoomOutLeft' => 'zoomOutLeft', 
								'zoomOutRight' => 'zoomOutRight', 
								'zoomOutUp' => 'zoomOutUp', 
							),
							'Attention Seekers' => array(								
								'lightSpeedOut' => 'lightSpeedOut', 
								'rotateOut' => 'rotateOut',	 															
							),
						),
					),
					array(
						'id'       => 'unloader-speed',
						'type'     => 'slider', 
						'title'    => __('unload Speed', 'sp-pro-txt-domain'),
						'subtitle' => __('Set Popup unload speed','sp-pro-txt-domain'),
						'desc'     => __('Min:0.1, Max:5, Best: 0-1' , 'sp-pro-txt-domain'),
						'default' => .40,
						'min' => 0,
						'step' => .1,
						'max' => 5,
						'resolution' => 0.01,
						'display_value' => 'text'
					),		
				// End Animations
			)
		) );
	}
	
	/////////////////////////////////////////////////
	// SECTION: Advanced Settings
	/////////////////////////////////////////////////
	if ( 1 ) {
		
		Redux::setSection( $opt_name, array(
			'title' => __( 'Advance Settings', 'sp-pro-txt-domain' ),
			'id'    => 'advance-settings-settings',
			'desc'  => __( 'Few settings for the developers.', 'sp-pro-txt-domain' ),
			'icon'  => 'el el-cog',
			'fields'     => array(
				/////////////////////////////////////////////////
				// Section: Heading & Description (typography)
				////////////////////////////////////////////////								
				array(
					'id'       => 'custom-css-code',
					'type'     => 'ace_editor',
					'title'    => __( 'CSS Code', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Paste your CSS code here.', 'sp-pro-txt-domain' ),
					'mode'     => 'css',
					'theme'    => 'monokai',
					'desc'     => '',
					'default'  => '',
				),
				array(
					'id'       => 'output_hook',
					'type'     => 'select',
					'title'    => __( 'Output Hook', 'sp-pro-txt-domain' ),
					'subtitle' => __( 'Choose the location for HTML output: wp_head or wp_footer', 'sp-pro-txt-domain' ),
					'default'  => 'wp_footer',
					'options'  => array(
						'wp_footer' => __( 'wp_footer Hook', 'sp-pro-txt-domain' ),
						'wp_head' => __( 'wp_head Hook', 'sp-pro-txt-domain' ),
					),
				),
				array(
					'id'		=> 'autoclose',
					'type'		=> 'switch',
					'title'		=> 'Close after Submission',
					'default'	=> 0,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Autoclose Global Popup',
						'content'   => 'This option will autoclose Global Popup after X seconds',
					), 
				),	
				array(
					'id'		=> 'autoclose_time',
					'type'		=> 'text',
					'title'		=> 'Close after seconds',
					'desc'		=> 'Auto close after X seconds',
					'required'   => array( 'autoclose', '=', 1 ),
					'default'	=> 5, 
				),	
				array(
					'id'		=> 'redirect',
					'type'		=> 'switch',
					'title'		=> 'Redirect after submission',
					'default'	=> 0,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Redirect Global Popup',
						'content'   => 'This will redirect the Global Popup to a url after submission',
					), 
				),					
				array(
					'id'		=> 'redirect_url',
					'type'		=> 'text',
					'title'		=> 'Redirect URL',
					'desc'		=> __('With https:// or http://', 'sp-pro-txt-domain'),
					'subtitle'	=> 'Enter redirect URL',
					'required'	=> array( 'redirect', '=', 1 ),
					'default'	=> '', 
				),	
				array(
					'id'		=> 'sideButton',
					'type'		=> 'switch',
					'title'		=> 'Side button not working',
					'default'	=> 0,
					'on'       => __('Fix It!', 'sp-pro-txt-domain' ),
					'off'      => __('Working', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Side Button not working',
						'content'   => 'Click on Fix it if your side button is not working if it is already working please do not check this options as it may cause some problems.',
					), 
				),
				array(
					'id'		=> 'enableTips',
					'type'		=> 'switch',
					'title'		=> 'Enable CF7 Tips',
					'default'	=> 0,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Enable CF7 Tips',
						'content'   => 'This will enable CF7 Styled Not valid Tips',
					), 
				),	
				array(
					'id'		=> 'enableMessage',
					'type'		=> 'switch',
					'title'		=> 'Contact Form 7 Message',
					'default'	=> 0,
					'on'       => __('Middle', 'sp-pro-txt-domain' ),
					'off'      => __('Below the form', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Contact Form 7 Message',
						'content'   => 'Some users like to see the Error message on the form in the middle so we created the options so that you can achieve it.',
					), 
				),	
				array(
					'id'		=> 'popTop',
					'type'		=> 'switch',
					'title'		=> 'Set Popup Top Position',
					'default'	=> 0,
					'on'       => __('Enable', 'sp-pro-txt-domain' ),
					'off'      => __('Disable', 'sp-pro-txt-domain' ),
					'hint'     => array(
						'title'     => 'Set Popup Top Position',
						'content'   => 'Warning: Do not use this setting if your popup is appearing on your screen. If your popup is appearing when you resize the browser then set this to enable.',
					), 
				),	
			),
		) );
	}
	