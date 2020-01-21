<?php
	
	class Magic_Shortcodes_Builder_Settings_Panel{
		
		private $version;
		
		public $options;
	
		public $options_header;
		
		
		public function __construct () {
			$this->options = get_option( 'ct_msb_options' );
			$this->options_header = get_option( 'ct_msb_options_header' );
		}
		
		public function add_submenu_pages() {        
			
						
			add_submenu_page(
				'edit.php?post_type=ct_ms',			
				__( 'Settings Panel', 'magic-shortcodes' ),
				__( 'Settings Panel', 'magic-shortcodes' ),
				'manage_options',
				'msb_settings_panel',
				array($this,'display_msb_settings_page')
			);
		}
		
		
		
		public function display_msb_settings_page(){
			
			?>        
			<div class="wrap">			
				<h2><?php _e( '', 'magic-shortcodes' ); ?></h2>			
				<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
				<?php  settings_errors(); ?>
				
				<!-- Create the form that will be used to render our options -->
				<form method="post" action="options.php">
				   
				   
					<?php 
					$license_data = array(
						'domain' => $this->get_domain_validation(home_url( '/' )),
						'item_id' =>isset($this->options['ct_msb_item_id']) ? $this->options['ct_msb_item_id']:'',
						'item_owner_email_id' => isset($this->options['ct_msb_email'])? $this->options['ct_msb_email']:'',
						'item_owner_username' =>isset($this->options['ct_msb_user_name'])? $this->options['ct_msb_user_name']:'',
						'item_owner_purchasecode' =>isset($this->options['ct_msb_key'])? $this->options['ct_msb_key']:''
					);
					//$license_fields_check = $this->validate_license_fields($license_data);
					$validate_license_checker = $this->validate_plugin_license($license_data);
//print_r($validate_license_checker);
						echo '<div id="setting-error-settings_updated" class="'.$validate_license_checker['class'].' settings-error notice is-dismissible below-h2"> 
		<p>'.$validate_license_checker['msg'].'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__( 'Dismiss this notice.', 'magic-shortcodes' ).'</span></button></div>';
						
					?>	
				   
					<?php             
						settings_fields( 'ct_msb_options_header' );
						do_settings_sections( 'ct_msb_options_header' );
						
						$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'plugin_lisense';  
						if( $active_tab == 'plugin_lisense' ) {  
							settings_fields( 'ct_msb_options' );
							do_settings_sections( 'ct_msb_options' );
							submit_button();
						} else if( $active_tab == 'help_guides' ) {
							settings_fields( 'ct_msb_options_help_guides' );
							do_settings_sections( 'ct_msb_options_help_guides' );
						}           
						else if( $active_tab == 'change_log' ) {
							settings_fields( 'ct_msb_options_change_log' );
							do_settings_sections( 'ct_msb_options_change_log' );
						}
						
					?>
					
				</form>
			</div><!-- /.wrap -->
			<?php
		}
		
		public function initialize_msb_license_options(){

			
			if( false == $this->options ) {                        
				add_option('ct_msb_options' );
				add_option( 'ct_msb_options_header' );		            
			}
				
			add_settings_section(
				'ct_msb_section_about',                                                       // ID used to identify this section and with which to register options
				__( '', 'magic-shortcodes'),                           // Title to be displayed on the administration page
				array( $this, 'ct_option_section_about'),                            // Callback used to render the description of the section
				'ct_msb_options_header'                                               // Page on which to add this section of options            
			);
			
			add_settings_section(
				'ct_msb_section_about_tabs',                                                       // ID used to identify this section and with which to register options
				__( '', 'magic-shortcodes'),                           // Title to be displayed on the administration page
				array( $this, 'ct_option_section_tabs'),                            // Callback used to render the description of the section
				'ct_msb_options_header'                                               // Page on which to add this section of options            
			);
			
			add_settings_section(
				'ct_msb_license_fields',                                                       // ID used to identify this section and with which to register options
				__( 'Plugin License Key', 'magic-shortcodes'),                           // Title to be displayed on the administration page
				array( $this, 'ct_option_plugin_description'),                            // Callback used to render the description of the section
				'ct_msb_options'                                               // Page on which to add this section of options
			);
			
			add_settings_field(
				'ct_msb_email',
				__( 'Email ID', 'magic-shortcodes' ),
				array( $this, 'text_option_field' ),
				'ct_msb_options',
				'ct_msb_license_fields',
				array(
					'id' => 'ct_msb_email',				
					'description' => __( 'Please enter your email id', 'magic-shortcodes' ),
					'class' =>'regular-text',
					'default'=>get_option( 'admin_email' )
				)			
			);
			add_settings_field(
				'ct_msb_user_name',
				__( 'Envato User Name', 'magic-shortcodes' ),
				array( $this, 'text_option_field' ),
				'ct_msb_options',
				'ct_msb_license_fields',
				array(
					'id' => 'ct_msb_user_name',				
					'description' => __( 'Please enter your envato username', 'magic-shortcodes' ),
					'class' =>'regular-text',
					'default'=>''
				)			
			); 
			add_settings_field(
				'ct_msb_key',
				__( 'License key', 'magic-shortcodes' ),
				array( $this, 'text_option_field' ),
				'ct_msb_options',
				'ct_msb_license_fields',
				array(
					'id' => 'ct_msb_key',				
					'description' => __( 'Please enter your license key to validate', 'magic-shortcodes' ),
					'class' =>'regular-text',
					'default'=>''
				)
				
			);        
			add_settings_field(
				'ct_msb_get_key',
				__( 'Get Your License key', 'magic-shortcodes' ),
				array( $this, 'label_option_field' ),
				'ct_msb_options',
				'ct_msb_license_fields',
				array(
					'id' => 'ct_msb_get_key',				
					'description' => __( '<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank" class="afc_ancher">Click here to get your license key</a>', 'magic-shortcodes' ),
					'class' =>'regular-text',
					'default'=>''
				)
				
			);
			
			add_settings_field(
				'ct_msb_item_id',
				__( '', 'magic-shortcodes' ),
				array( $this, 'hidden_option_field' ),
				'ct_msb_options',
				'ct_msb_license_fields',
				array(
					'id' => 'ct_msb_item_id',				
					'description' => '',
					'class' =>'regular-text',
					'default'=>$this->get_plugin_item_id('Magic Shortcodes Builder','item_id')
				)
				
			);			
			
			add_settings_section(
				'ct_msb_section_help_guides',                                                       // ID used to identify this section and with which to register options
				__( '', 'magic-shortcodes'),                           // Title to be displayed on the administration page
				array( $this, 'ct_msb_section_help_guides'),                            // Callback used to render the description of the section
				'ct_msb_options_help_guides'                                               // Page on which to add this section of options            
			);
			
			
			add_settings_section(
				'ct_msb_section_change_log',                                                       // ID used to identify this section and with which to register options
				__( '', 'magic-shortcodes'),                           // Title to be displayed on the administration page
				array( $this, 'ct_msb_section_change_log'),                            // Callback used to render the description of the section
				'ct_msb_options_change_log'                                               // Page on which to add this section of options            
			);
			
			
			register_setting( 'ct_msb_options', 'ct_msb_options' );	
			register_setting( 'ct_msb_options_header', 'ct_msb_options_header' );
			
		}
		
		public function ct_option_section_about() {       
			echo '<div class="wrap about-wrap-msb"><h1><strong>'.  __( 'Welcome to Magic Shortcodes Builder', 'magic-shortcodes' ) . '</strong></h1><div class="about-text-msb">'. __( 'Thanks for Choosing Magic Shortcodes Builder - The worlds most powerful Shortcodes Builder Plugin. This page will help you quickly get up and running with Magic Shortcodes Builder.', 'magic-shortcodes' ) . '</div><div class="wp-badge fl-badge">'.__( 'Version 1.0.0', 'magic-shortcodes' ).'</div></div>';
		}
		
		public function ct_option_section_tabs() {
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'plugin_lisense';  
        
        
        echo '<h2 class="nav-tab-wrapper">
			<a href="edit.php?post_type=ct_ms&page=msb_settings_panel&tab=plugin_lisense" class="nav-tab '.($active_tab == 'plugin_lisense' ? 'nav-tab-active' : '').'">'.__( 'Plugin License', 'magic-shortcodes' ).'</a>
			<a href="edit.php?post_type=ct_ms&page=msb_settings_panel&tab=help_guides" class="nav-tab '.($active_tab == 'help_guides' ? 'nav-tab-active' : '').'">'.__( 'Help &amp; Guides', 'magic-shortcodes' ).'</a>
			<a href="edit.php?post_type=ct_ms&page=msb_settings_panel&tab=change_log" class="nav-tab '.($active_tab == 'change_log' ? 'nav-tab-active' : '').'">'.__( 'Change log', 'magic-shortcodes' ).'</a>
			</h2>';
		}
		
		public function ct_option_plugin_description() {
			echo '<p>'. __( 'A purchase code (license) is only valid for One Domain. Are you using this plugin on a new domain? Purchase a <a href="https://goo.gl/dy71Kz" target="_blank">new license here</a> to get a new purchase code. Once you validate this domain then you are unable to use this license to another domain. To Remove Code from this domain, remove the purchase code and submit again.', 'magic-shortcodes' ) . '</p>';
		}
		
		/**
		 * Re-usable text options field for settings
		 *
		 * @param $args array   field arguments
		 */
		public function text_option_field( $args ) {
			$field_id = $args['id'];
			if( $field_id ) {
				$val = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : $args['default'];
				echo '<input type="text" name="ct_msb_options['.$field_id.']" value="' . $val . '" class="'.$args['class'].'" >
				<br/>
				<label>'.$args['description'].'</label>';
			} else {
				_e( 'Field id is missing!', 'magic-shortcodes' );
			}
		}
		
		/**
		 * Re-usable hidden options field for settings
		 *
		 * @param $args array   field arguments
		 */
		public function hidden_option_field( $args ) {
			
			$field_id = $args['id'];
			if( $field_id ) {
			//	$val = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : $args['default'];
				$val = $args['default'];
				echo '<input type="hidden" name="ct_msb_options['.$field_id.']" value="' . $val . '" class="'.$args['class'].'" >
				<br/>
				<label>'.$args['description'].'</label>';
			} else {
				_e( 'Field id is missing!', 'magic-shortcodes' );
			}
		}
		
		/**
		 * Re-usable label field for settings
		 *
		 * @param $args array   field arguments
		 */
		
		public function label_option_field( $args ) {
			$field_id = $args['id'];
			if( $field_id ) {
				echo '<label>'.$args['description'].'</label>';
			} else {
				_e( 'Field id is missing!', 'magic-shortcodes' );
			}
		}
		
		/**
		 * Get Plugin Url
		 *
		 * @param $args array   field arguments
		 */
		 
		 public function get_plugin_item_id($product,$return_value)
		 {
			
			$api_url = 'http://codetides.com/api/rest_product_item_id.php';
			$api_params = array(			
				'p' => $product,
				'r' => $return_value
			);
			
			$response = wp_remote_get(add_query_arg($api_params, $api_url), array('timeout' => 20, 'sslverify' => false));
			if (is_wp_error($response)){
				
				echo '<div id="setting-error-settings_updated" class="error settings-error notice is-dismissible below-h2"><p>Unexpected Error! The query returned with an error.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__( 'Dismiss this notice.', 'magic-shortcodes' ).'</span></button></div>';
			}

			// License data.
			$product_data = wp_remote_retrieve_body($response);			
			$product_data_decode = json_decode($product_data, true);			
			return $product_data_decode['data'];
			
			
		 }
		public function ct_msb_section_change_log() {
			$path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/changelog.txt';
			echo '<div class="wrap about-wrap">'.$this->get_robots($path,'1').'</div>';
		}
		
		 public function ct_msb_section_help_guides() {
			$active_section = isset( $_GET[ 'section' ] ) ? $_GET[ 'section' ] : '';
        if($active_section=="faq"){
            $path = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/faq.txt';
            
           echo '
           <div class="wrap">
                <div id="afc-accordion">'.$this->get_robots($path,'0').'</div>
                <a href="edit.php?post_type=ct_ms&page=msb_settings_panel&tab=help_guides" class="help_tab">'.__('Back to Help & Guide tab','magic-shortcodes').'</a>
       </div>
           </div>
           '; 
        }else {
            $license = get_option( 'ct_msb_verified_purchase' );
            if($license==1){
                $premium_email_content = __('Unlock Premium Emails','magic-shortcodes');
                $premium_link = "https://goo.gl/dy71Kz";
            }else{
                $premium_email_content = __('Premium Emails','magic-shortcodes');
                $premium_link = "https://goo.gl/l6qUmG";
            }
            
            
       echo '<div class="wrap">       
       <div class="guide_wrap faq"><a href="edit.php?post_type=ct_ms&page=msb_settings_panel&tab=help_guides&section=faq"><span>'.__('Frequently Asked Questions','magic-shortcodes').'</span></a></div>
       <div class="guide_wrap doc"><a href="http://codetides.com/magic-shortcodes-builder/" target="_blank"><span>'.__('Online Documentation','magic-shortcodes').'</span></a></div>
       <div class="guide_wrap email"><a href="'.$premium_link.'" target="_blank"><span>'.$premium_email_content.'</span></a></div>
       <div class="guide_wrap group"><a href="https://goo.gl/oQMTeQ" target="_blank"><span>'.__('Joins Our CodeTides Community','magic-shortcodes').'</span></a></div>
       <div class="guide_wrap tester"><a href="https://goo.gl/9uPlW6" target="_blank"><span>'.__('Become a beta tester for our products','magic-shortcodes').'</span></a></div>       
       ';
        }
    }
		    public function get_robots($path,$newline)
			{
				$robots_file = $path; //The robots file.
				
				if(file_exists($robots_file)){
					$fileContent = file_get_contents($robots_file);
						if($newline==1){return nl2br($fileContent);}
						else{return $fileContent;}
						

				} else {
					$default_content = "User-agent: *\nDisallow:";
					file_put_contents($robots_file, $default_content);
					return $default_content;
				}
			}
		 
		public function get_domain_validation($url){
         
			$whitelist = array('127.0.0.1', "::1","wpengine.com");
			if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
				return "localhost";
			}
			else{
				  $pieces = parse_url($url);			 
				  $domain = isset($pieces['host']) ? $pieces['host'] : '';
				  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
					return $regs['domain'];
				  }
			}
		}
		
		
		
		public function set_all_notifications(){
			
			$notification = array(				
				array(
					'name' => 'empty-license-fields',
					'text' => 'One or more fields are empty please fill all fields before validating license.',
					'type' => 'info',
				),
				array(
					'name' => 'localhost',
					'text' => 'You are using Magic Shortcodes Builder on your localhost or development server which does not require license key for now.',
					'type' => 'info',
				),
				array(
					'name' => 'newdomain',
					'text' => 'You are using this key of Magic Shortcodes Builder on some other website, For now you have to buy another license key if you want to use ulitmate charts builder in your website.',
					'type' => 'error',
				),
				array(
					'name' => 'error',
					'text' => 'Unfortunatly there was an error while checking your license key, please try again later!',
					'type' => 'error',
				),
				array(
					'name' => 'success',
					'text' => 'Congurations, You had been unlocked premium version of Magic Shortcodes Builder. Happy using!',
					'type' => 'success',
				),
			);
			return $notification;
		}
		
		public function display_admin_notifications($domain){
			$notification = $this->set_all_notifications();
			foreach($notification as $key => $value){
				if($notification[$key]['name']==$domain){
					$notification_text = $notification[$key]['text'];
				}
			}
			if($notification_text!="")
				echo	'<div id="setting-error-settings_updated" class="error settings-error notice is-dismissible below-h2">
						<p>fffff'.$notification_text.'</p>
						</div>';
		 
		}
		
		public function check_plugin_license($data_validate){
			
			switch($data_validate['domain']){
				case 'localhost':
					$this->validate_license($data_validate);	
					//$this->display_admin_notifications('localhost');
					break;					
				default:
					$this->display_admin_notifications('localhost');
					//$this->validate_license($data_validate);	
					break;
			}
		}
		public function validate_plugin_license($data_validate){
			//print_r($data_validate);
			$api_url = 'http://codetides.com/api/rest_product_license_checker_msb.php';
			$api_params = array(
				'item_id' => $data_validate['item_id'],
				'item_name' => 'Magic Shortcodes Builder',
				'item_buyer' => $data_validate['item_owner_username'],
				'item_buyer_email' => $data_validate['item_owner_email_id'],
				'item_buyer_domain' => $data_validate['domain'],
			//	'item_buyer_domain' => 'domain1.com',
				'item_purchase_code' => $data_validate['item_owner_purchasecode']
			);
			
			$response = wp_remote_get(add_query_arg($api_params, $api_url), array('timeout' => 20, 'sslverify' => false));
			//print_r($response);
			if (is_wp_error($response)){
				
				echo '<div id="setting-error-settings_updated" class="error settings-error notice is-dismissible below-h2"><p>Unexpected Error! The query returned with an error.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__( 'Dismiss this notice.', 'magic-shortcodes' ).'</span></button></div>';
			}
			// License data.			
			$license_data = wp_remote_retrieve_body($response);	
			//print_r($license_data);
			$data_validate_purchase = json_decode($license_data, true);
			$this->update_plugin_license($data_validate_purchase);
			//print_r($data_validate_purchase);
			return $data_validate_purchase;
		}
		
		public function update_plugin_license($data){
			$data = $this->get_updated_plugin_option_data($data);			
			$this->update_plugin_option('ct_msb_verified_purchase',$data['vp']);
			$this->update_plugin_option('ct_msb_install_date',$data['pd']);
			$this->update_plugin_option('ct_msb_support_expire_date',$data['sd']);
		}
		public function update_plugin_option($key,$value){
			if( get_option($key, null) !== null )	
				update_option($key,$value);
			else
				add_option($key,$value);			
		}
		public function get_updated_plugin_option_data($data){
			$ecode = array('1','4','5');
			
			if( !in_array($data['code'] , $ecode ) )
			{
				$data['vp'] = 1; //unverify purhcase
				$data['pd'] = ""; //install date
				$data['sd'] = ""; //support_expire_date
			}
			else{
				$data['vp'] = 0; //verify purhcase
				$data['pd'] = ""; //install date
				$data['sd'] = ""; //support_expire_date
			}
			//print_r($data);
			return $data;
		}
	}
	
?>