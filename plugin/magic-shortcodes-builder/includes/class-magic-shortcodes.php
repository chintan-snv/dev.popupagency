<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/includes
 * @author     CodeTides <contact@codetides.com>
 */
class Magic_Shortcodes {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Magic_Shortcodes_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'magic-shortcodes';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Magic_Shortcodes_Loader. Orchestrates the hooks of the plugin.
	 * - Magic_Shortcodes_i18n. Defines internationalization functionality.
	 * - Magic_Shortcodes_Admin. Defines all hooks for the admin area.
	 * - Magic_Shortcodes_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-magic-shortcodes-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-magic-shortcodes-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-magic-shortcodes-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-magic-shortcodes-settings-panel.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/function-magic-shortcodes-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-magic-shortcodes-public.php';

		$this->loader = new Magic_Shortcodes_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Magic_Shortcodes_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Magic_Shortcodes_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Magic_Shortcodes_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
        $this->loader->add_action( 'init', $plugin_admin, 'register_cpt_magic_shortcodes' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin,  'add_meta_box' );
        $this->loader->add_action( 'save_post',$plugin_admin, 'save_meta_box' );
        
        $this->loader->add_filter('manage_edit-ct_ms_columns',  $plugin_admin, 'magic_shortcodes_columns');
		$this->loader->add_action( 'manage_ct_ms_posts_custom_column',  $plugin_admin,'magic_shortcodes_columns_data', 10, 2 );
        
        $this->loader->add_action('admin_head',$plugin_admin, 'add_ms_button');
        $this->loader->add_action('wp_ajax_getSelectedValue',$plugin_admin, 'getSelectedValue' );
        $this->loader->add_action('wp_ajax_removeshortcode',$plugin_admin, 'removeshortcode' );
		
		
		$plugin_admin_settings = new Magic_Shortcodes_Builder_Settings_Panel( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin_settings, 'add_submenu_pages' );
		$this->loader->add_action( 'admin_init', $plugin_admin_settings, 'initialize_msb_license_options' );
		$this->loader->add_action( 'admin_notices', $plugin_admin_settings, 'display_admin_notifications' );
		
		
		
		
	}
	
	
	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Magic_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_public, 'register_magic_shortcodes' );        
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Magic_Shortcodes_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
