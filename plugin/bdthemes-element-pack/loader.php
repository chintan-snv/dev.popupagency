<?php
namespace ElementPack;

use Elementor\Plugin;
use ElementPack\Includes\Element_Pack_WPML;
use ElementPack\Includes\Element_Pack_Template_Manager;
use ElementPack\SVG_Support\SVG_Support;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class for element pack
 */
class Element_Pack_Loader {

	/**
	 * @var Element_Pack_Loader
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	private $classes_aliases = [
		'ElementPack\Modules\PanelPostsControl\Module' => 'ElementPack\Modules\QueryControl\Module',
		'ElementPack\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'ElementPack\Modules\QueryControl\Controls\Group_Control_Posts',
		'ElementPack\Modules\PanelPostsControl\Controls\Query' => 'ElementPack\Modules\QueryControl\Controls\Query',
	];

	public $elements_data = [
		'sections' => [],
		'columns'  => [],
		'widgets'  => [],
	];

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return BDTEP_VER;
	}

	/**
	 * return active theme
	 */
	public function get_theme() {
		return wp_get_theme();
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'bdthemes-element-pack' ), '1.6.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'bdthemes-element-pack' ), '1.6.0' );
	}

	/**
	 * @return \Elementor\Element_Pack_Loader
	 */

	public static function elementor() {
		return Plugin::$instance;
	}

	/**
	 * @return Element_Pack_Loader
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	

	/**
	 * we loaded module manager + admin php from here
	 * @return [type] [description]
	 */
	private function _includes() {

		require BDTEP_INC_PATH . 'class-svg-support.php';
		require BDTEP_INC_PATH . 'modules-manager.php';
		require BDTEP_INC_PATH . 'class-elements-wpml-compatibility.php';
		require BDTEP_INC_PATH . 'class-template-manager.php';

		// Rooten theme header footer compatibility 
		if ('Rooten' === $this->get_theme()->name or 'Rooten' === $this->get_theme()->parent_theme) {
			if (!class_exists('RootenCustomTemplate')) {
				require BDTEP_INC_PATH . 'class-rooten-theme-compatibility.php';
			}
		}

		if ( is_admin() ) {
			if(!defined('BDTEP_CH')) {
				require BDTEP_INC_PATH . 'admin.php';
				// Load admin class for admin related content process
				new Admin();
			}
		}

	}

	/**
	 * Autoloader function for all classes files
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load    = $class_alias_name;
		} else {
			$class_to_load    = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = BDTEP_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * Register all script that need for any specific widget on call basis.
	 * @return [type] [description]
	 */
	public function register_site_scripts() {

		$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$settings = get_option( 'element_pack_api_settings' );

		wp_register_script( 'bdt-uikit-icons', BDTEP_ASSETS_URL . 'js/bdt-uikit-icons' . $suffix . '.js', ['jquery', 'bdt-uikit'], '3.0.3', true );
		wp_register_script( 'goodshare', BDTEP_ASSETS_URL . 'vendor/js/goodshare' . $suffix . '.js', ['jquery'], '4.1.2', true );
		wp_register_script( 'twentytwenty', BDTEP_ASSETS_URL . 'vendor/js/jquery.twentytwenty' . $suffix . '.js', ['jquery'], '0.1.0', true );
		wp_register_script( 'eventmove', BDTEP_ASSETS_URL . 'vendor/js/jquery.event.move' . $suffix . '.js', ['jquery'], '2.0.0', true );
		wp_register_script( 'aspieprogress', BDTEP_ASSETS_URL . 'vendor/js/jquery-asPieProgress' . $suffix . '.js', ['jquery'], '0.4.7', true );
		wp_register_script( 'morphext', BDTEP_ASSETS_URL . 'vendor/js/morphext' . $suffix . '.js', ['jquery'], '2.4.7', true );
		wp_register_script( 'qrcode', BDTEP_ASSETS_URL . 'vendor/js/jquery-qrcode' . $suffix . '.js', ['jquery'], '0.14.0', true );
		wp_register_script( 'jplayer', BDTEP_ASSETS_URL . 'vendor/js/jquery.jplayer' . $suffix . '.js', ['jquery'], '2.9.2', true );
		wp_register_script( 'circle-menu', BDTEP_ASSETS_URL . 'vendor/js/jQuery.circleMenu' . $suffix . '.js', ['jquery'], '0.1.1', true );
		wp_register_script( 'cookieconsent', BDTEP_ASSETS_URL . 'vendor/js/cookieconsent' . $suffix . '.js', ['jquery'], '3.1.0', true );
		wp_register_script( 'gridtab', BDTEP_ASSETS_URL . 'vendor/js/gridtab' . $suffix . '.js', ['jquery'], '2.1.1', true );
		
		if (!empty($settings['google_map_key'])) {
			wp_register_script( 'gmap-api', '//maps.googleapis.com/maps/api/js?key='.$settings['google_map_key'], ['jquery'], null, true );
		} else {
			wp_register_script( 'gmap-api', '//maps.google.com/maps/api/js?sensor=true', ['jquery'], null, true );
		}
		
		wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', ['jquery'], null, true );

		wp_register_script( 'chart', BDTEP_ASSETS_URL . 'vendor/js/chart' . $suffix . '.js', ['jquery'], '2.7.3', true );
		wp_register_script( 'gmap', BDTEP_ASSETS_URL . 'vendor/js/gmap' . $suffix . '.js', ['jquery', 'gmap-api'], null, true );
		wp_register_script( 'leaflet', BDTEP_ASSETS_URL . 'vendor/js/leaflet' . $suffix . '.js', ['jquery'], '', true );
		wp_register_script( 'parallax', BDTEP_ASSETS_URL . 'vendor/js/parallax' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'table-of-content', BDTEP_ASSETS_URL . 'vendor/js/table-of-content' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'instagram-feed', BDTEP_ASSETS_URL . 'vendor/js/jquery.instagramFeed' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'imagezoom', BDTEP_ASSETS_URL . 'vendor/js/jquery.imagezoom' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'datatables', BDTEP_ASSETS_URL . 'vendor/js/datatables' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'typed', BDTEP_ASSETS_URL . 'vendor/js/typed' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'timeline', BDTEP_ASSETS_URL . 'vendor/js/timeline' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'popper', BDTEP_ASSETS_URL . 'vendor/js/popper' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'tippyjs', BDTEP_ASSETS_URL . 'vendor/js/tippy.all' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'tilt', BDTEP_ASSETS_URL . 'vendor/js/tilt.jquery' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'rvslider', BDTEP_ASSETS_URL . 'vendor/js/rvslider' . $suffix . '.js', ['jquery'], null, true );
		wp_register_script( 'spritespin', BDTEP_ASSETS_URL . 'vendor/js/spritespin' . $suffix . '.js', ['jquery'], '4.0.5', true );

		wp_register_script( 'particles', BDTEP_ASSETS_URL . 'vendor/js/particles' . $suffix . '.js', ['jquery'], '2.0.0', true );
		wp_register_script( 'recliner', BDTEP_ASSETS_URL . 'vendor/js/recliner' . $suffix . '.js', ['jquery'], '0.2.2', true );
		wp_register_script( 'ep-justified-gallery', BDTEP_ASSETS_URL . 'vendor/js/jquery.justifiedGallery' . $suffix . '.js', ['jquery'], '1.0.0', true );
		wp_register_script( 'lottie', BDTEP_ASSETS_URL . 'vendor/js/lottie' . $suffix . '.js', [], '5.5.9', true );


		if (!empty($settings['disqus_user_name'])) {
			wp_register_script( 'disqus', '//'.$settings['disqus_user_name'].'.disqus.com/count.js', ['jquery'], null, true );
		}
	}

	public function register_site_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

        // third party widget css
        wp_register_style( 'twentytwenty', BDTEP_ASSETS_URL . 'css/twentytwenty.css', [], BDTEP_VER );
        wp_register_style( 'datatables', BDTEP_ASSETS_URL . 'css/datatables' . $direction_suffix . '.css', [], BDTEP_VER );
        wp_register_style( 'imagezoom', BDTEP_ASSETS_URL . 'css/imagezoom' . $direction_suffix . '.css', [], BDTEP_VER );
        wp_register_style( 'cookie-consent', BDTEP_ASSETS_URL . 'css/cookie-consent' . $direction_suffix . '.css', [], BDTEP_VER );

        wp_register_style( 'element-pack-font', BDTEP_ASSETS_URL . 'css/element-pack-font' . $direction_suffix . '.css', [], BDTEP_VER );

	}

	/**
	 * Loading site related style from here.
	 * @return [type] [description]
	 */
	public function enqueue_site_styles() {

		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style( 'bdt-uikit', BDTEP_ASSETS_URL . 'css/bdt-uikit' . $direction_suffix . '.css', [], '3.2' );
		wp_enqueue_style( 'element-pack-site', BDTEP_ASSETS_URL . 'css/element-pack-site' . $direction_suffix . '.css', [], BDTEP_VER );		
	}


	/**
	 * Loading site related script that needs all time such as uikit.
	 * @return [type] [description]
	 */
	public function enqueue_site_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'bdt-uikit', BDTEP_ASSETS_URL . 'js/bdt-uikit' . $suffix . '.js', ['jquery'], BDTEP_VER );
		wp_enqueue_script( 'element-pack-site', BDTEP_ASSETS_URL . 'js/element-pack-site' . $suffix . '.js', ['jquery', 'elementor-frontend'], BDTEP_VER );

		$script_config = [ 
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'element-pack-site' ),
			'data_table' => [
				'language'    => [
			        'lengthMenu' => sprintf(esc_html_x('Show %1s Entries', 'DataTable String', 'bdthemes-element-pack'), '_MENU_' ),
			        'info'       => sprintf(esc_html_x('Showing %1s to %2s of %3s entries', 'DataTable String', 'bdthemes-element-pack'), '_START_', '_END_', '_TOTAL_' ),
			        'search'     => esc_html_x('Search :', 'DataTable String', 'bdthemes-element-pack'),
			        'paginate'   => [
			            'previous' => esc_html_x('Previous', 'DataTable String', 'bdthemes-element-pack'),
			            'next'     => esc_html_x('Next', 'DataTable String', 'bdthemes-element-pack'),
			        ],
				],
			],
			'contact_form' => [
				'sending_msg' => esc_html_x('Sending message please wait...', 'Contact Form String', 'bdthemes-element-pack'),
				'captcha_nd'  => esc_html_x('Invisible captcha not defined!', 'Contact Form String', 'bdthemes-element-pack'),
				'captcha_nr'  => esc_html_x('Could not get invisible captcha response!', 'Contact Form String', 'bdthemes-element-pack'),

			],
			'mailchimp' => [
				'subscribing' => esc_html_x( 'Subscribing you please wait...', 'Mailchimp String', 'bdthemes-element-pack'),
			],
			'elements_data' => $this->elements_data,
		];


		// localize for user login widget ajax login script
	    wp_localize_script( 'bdt-uikit', 'element_pack_ajax_login_config', array( 
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'loadingmessage' => esc_html__('Sending user info, please wait...', 'bdthemes-element-pack'),
	    ));

	    $script_config = apply_filters( 'element_pack/frontend/localize_settings', $script_config );

	    // TODO for editor script
		wp_localize_script( 'bdt-uikit', 'ElementPackConfig', $script_config );

	}

	public function enqueue_editor_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'element-pack', BDTEP_ASSETS_URL . 'js/element-pack-editor' . $suffix . '.js', ['backbone-marionette', 'elementor-common-modules', 'elementor-editor-modules',], BDTEP_VER, true );

		// $locale_settings = [
		// 	'i18n' => [],
		// 	'urls' => [
		// 		'modules' => BDTEP_MODULES_URL,
		// 	],
		// ];

		// $locale_settings = apply_filters( 'element_pack/editor/localize_settings', $locale_settings );

		// wp_localize_script(
		// 	'element-pack',
		// 	'ElementPackConfig',
		// 	$locale_settings
		// );
	}

	public function enqueue_admin_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'element-pack-admin', BDTEP_ASSETS_URL . 'js/element-pack-admin' . $suffix . '.js', ['jquery'], BDTEP_VER, true );
	}

	/**
	 * Load editor editor related style from here
	 * @return [type] [description]
	 */
	public function enqueue_preview_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style('element-pack-preview', BDTEP_ASSETS_URL . 'css/element-pack-preview' . $direction_suffix . '.css', '', BDTEP_VER );
	}


	public function enqueue_editor_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_register_style('element-pack-editor', BDTEP_ASSETS_URL . 'css/element-pack-editor' . $direction_suffix . '.css', '', BDTEP_VER );
		wp_enqueue_style('element-pack-editor');
	}


	/**
	 * Callback to shortcode.
	 * @param array $atts attributes for shortcode.
	 */
	public function shortcode_template( $atts ) {

		$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'rooten_custom_template'
		);

		$id = ! empty( $atts['id'] ) ? intval( $atts['id'] ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );

	}


	/**
	 * Add element_pack_ajax_login() function with wp_ajax_nopriv_ function 
	 * @return [type] [description]
	 */
	public function element_pack_ajax_login_init() {
	    // Enable the user with no privileges to run element_pack_ajax_login() in AJAX
	    add_action( 'wp_ajax_nopriv_element_pack_ajax_login', [ $this, "element_pack_ajax_login"] );

	}

	/**
	 * For ajax login
	 * @return [type] [description]
	 */
	public function element_pack_ajax_login(){
	    // First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-login-nonce', 'bdt-user-login-sc' );

	    // Nonce is checked, get the POST data and sign user on
		$access_info                  = [];
		$access_info['user_login']    = !empty($_POST['user_login'])?$_POST['user_login'] : "";
		$access_info['user_password'] = !empty($_POST['user_password'])?$_POST['user_password'] : "";
		$access_info['remember']      = !empty($_POST['rememberme'])? true : false;
		$user_signon                  = wp_signon( $access_info, false );

	    if ( !is_wp_error($user_signon) ){
	        echo wp_json_encode( ['loggedin' => true, 'message'=> esc_html__('Login successful, Redirecting...', 'bdthemes-element-pack')] );
	    } else {
	        echo wp_json_encode( ['loggedin' => false, 'message'=> esc_html__('Ops! Wrong username or password!', 'bdthemes-element-pack')] );
	    }

	    die();
	}


	public function element_pack_ajax_search() {
	    global $wp_query;

	    $result = array('results' => array());
	    $query  = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

	    if (strlen($query) >= 3) {

			$wp_query->query_vars['posts_per_page'] = 5;
			$wp_query->query_vars['post_status']    = 'publish';
			$wp_query->query_vars['s']              = $query;
			$wp_query->is_search                    = true;

	        foreach ($wp_query->get_posts() as $post) {

	            $content = !empty($post->post_excerpt) ? strip_tags(strip_shortcodes($post->post_excerpt)) : strip_tags(strip_shortcodes($post->post_content));

	            if (strlen($content) > 180) {
	                $content = substr($content, 0, 179).'...';
	            }

	            $result['results'][] = array(
	                'title' => $post->post_title,
	                'text'  => $content,
	                'url'   => get_permalink($post->ID)
	            );
	        }
	    }

	    die(json_encode($result));
	}

	// Load WPML compatibility instance
	public function wpml_compatiblity() {
		return Element_Pack_WPML::get_instance();
	}


	/**
	 * initialize the category
	 * @return [type] [description]
	 */
	public function element_pack_init() {
		$this->_modules_manager = new Manager();

		$elementor = Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category( BDTEP_SLUG, [ 'title' => BDTEP_TITLE, 'icon'  => 'font' ] );
		
		do_action( 'bdthemes_element_pack/init' );
	}

	public function element_pack_template_library() {

		return Element_Pack_Template_Manager::get_instance();

	}

	public function element_pack_svg_support() {

		return SVG_Support::get_instance();

	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'element_pack_init' ] );
		
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

		add_action( 'elementor/frontend/before_register_styles', [ $this, 'register_site_styles' ] );
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_site_scripts' ] );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'enqueue_site_styles' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_site_scripts' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// TODO AJAX SEARCH
		add_action('wp_ajax_element_pack_search', [ $this, 'element_pack_ajax_search' ] );
		add_action('wp_ajax_nopriv_element_pack_search', [ $this, 'element_pack_ajax_search' ] );

		add_shortcode( 'rooten_custom_template', [ $this, 'shortcode_template' ] );


		// When user not login add this action
		if (!is_user_logged_in()) {
			add_action('elementor/init', [ $this, 'element_pack_ajax_login_init'] );
		}
	}

	/**
	 * Element_Pack_Loader constructor.
	 */
	private function __construct() {
		// Register class automatically
		spl_autoload_register( [ $this, 'autoload' ] );
		// Include some backend files
		$this->_includes();

		// Finally hooked up all things here
		$this->setup_hooks();

		$this->element_pack_svg_support()->init();

		$this->wpml_compatiblity()->init();

		$this->element_pack_template_library()->init();


	}
}

if ( ! defined( 'BDTEP_TESTS' ) ) {
	// In tests we run the instance manually.
	Element_Pack_Loader::instance();
}

// handy fundtion for push data
function element_pack_config() {
	return Element_Pack_Loader::instance();
}