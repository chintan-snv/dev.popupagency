<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/public
 * @author     CodeTides <contact@codetides.com>
 */
class Magic_Shortcodes_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
    
    
    private $mid;
    

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;       
        $this->mid = 0;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Magic_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Magic_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/magic-shortcodes-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Magic_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Magic_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/magic-shortcodes-public.js', array( 'jquery' ), $this->version, false );

	}
    
    public function register_magic_shortcodes() {
        add_shortcode( 'magic_shortcodes', array( $this, 'display_magicshortcodes') );      
        add_action( "wp_footer", array( $this, 'load_css_js_footer') );
    }
    
    public function display_magicshortcodes( $attributes ) {
        extract( shortcode_atts( array(
            'id' => 'null',
        ), $attributes ) );       
        $this->mid = $id;
       
        $content = get_post_meta( $id ,"ct_ms_html", true );
        ob_start();
        eval('?>'.$content.'<?php;');    
        $print_content = ob_get_clean();
        ob_end_flush();
		$print_content = $this->do_shortcode_output($print_content);
        return $print_content;
    }   
	
    public function do_shortcode_output($content) {
	  global $shortcode_tags;
	
	  if ( false === strpos( $content, '[' ) ) {
		return $content;
	  }
	
	  if (empty($shortcode_tags) || !is_array($shortcode_tags))
		return $content;
	
	  $pattern = get_shortcode_regex();
	  return preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $content );
	}
    
    public function load_css_js_footer() {        
        
            echo '<style type="text/css">'.get_post_meta( $this->mid ,"ct_ms_css", true ).'</style>'."\n";    
      
        
            echo '<srcipt type="text/javascript">'.get_post_meta( $this->mid ,"ct_ms_js", true ).'</srcipt>'."\n";    
        
    }
}
