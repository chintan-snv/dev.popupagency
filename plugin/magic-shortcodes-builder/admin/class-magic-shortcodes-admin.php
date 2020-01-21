<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/admin
 * @author     CodeTides <contact@codetides.com>
 */
class Magic_Shortcodes_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/magic-shortcodes-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name."ace", plugin_dir_url( __FILE__ ) . 'js/ace.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/magic-shortcodes-admin.js', array( 'jquery' ), $this->version, true );
        if ( is_admin() ) {
            
    ?>

    <script type="text/javascript">
    var ABSURL = '<?php echo get_bloginfo("url"); ?>';
    </script>
    <?php
    }

	}
    
    public function register_cpt_magic_shortcodes()
	{
		$labels = array(
            'name'                => _x( 'Magic Shortcodes', 'Post Type General Name', 'magic-shortcodes' ),
            'singular_name'       => _x( 'Magic Shortcodes', 'Post Type Singular Name', 'magic-shortcodes' ),
            'menu_name'           => __( 'Magic Shortcodes', 'magic-shortcodes' ),
            'name_admin_bar'      => __( 'Magic Shortcodes', 'magic-shortcodes' ),
            'parent_item_colon'   => __( 'Parent Magic Shortcodes:', 'magic-shortcodes' ),
            'all_items'           => __( 'All Magic Shortcodes', 'magic-shortcodes' ),
            'add_new_item'        => __( 'Add New', 'magic-shortcodes' ),
            'add_new'             => __( 'Add New', 'magic-shortcodes' ),
            'new_item'            => __( 'New Magic Shortcodes', 'magic-shortcodes' ),
            'edit_item'           => __( 'Edit Magic Shortcodes', 'magic-shortcodes' ),
            'update_item'         => __( 'Update Magic Shortcodes', 'magic-shortcodes' ),
            'view_item'           => __( 'View Magic Shortcodes', 'magic-shortcodes' ),
            'search_items'        => __( 'Search Magic Shortcodes', 'magic-shortcodes' ),
            'not_found'           => __( 'Not found', 'magic-shortcodes' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'magic-shortcodes' ),
        );
        $args = array(
            'label'               => __( 'Magic Shortcodes', 'magic-shortcodes' ),
            'description'         => __( 'Another Flexible Magic Shortcodes', 'magic-shortcodes' ),      
			'labels'              => $labels,     
            'supports'            => array('title'),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-admin-site',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
        );
        register_post_type( 'ct_ms', apply_filters( 'ct_ms_register_arguments', $args) );
        
	}
    
    
    public function add_meta_box() {
		add_meta_box(
			'magic_shortcodes_details',
			__( 'Magic Shortcodes Builder', 'magic-shortcodes' ),
			array($this,'meta_box_print_magic_shortcodes_details'),
			'ct_ms'
		);        
	}
    
    public function meta_box_print_magic_shortcodes_details( $post ) {
	
		require_once plugin_dir_path( __FILE__ ). 'views/magic-shortcodes-admin-display.php';
	}
    
    public function save_meta_box( $post_id ) {
 
    /* If we're not working with a 'post' post type or the user doesn't have permission to save,
     * then we exit the function.
     */
	 	
		if ( ! $this->is_valid_post_type() || ! $this->user_can_save( $post_id, 'magic_shortcodes_nonce', 'magic_shortcodes_save' ) ) {
			return;
		}	
		
		foreach($_POST as $key => $value)
		{
			if (0 === strpos($key, 'ct_ms_')) {
				update_post_meta( $post_id, $key, $value );
			}
		}
		
		
 
	}
	
	private function is_valid_post_type() {
		
		return ! empty( $_POST['post_type'] ) && 'ct_ms' == $_POST['post_type'];
	}
	
	private function user_can_save( $post_id, $nonce_action, $nonce_id ) {
 
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce_action ] ) && wp_verify_nonce( $_POST[ $nonce_action ], $nonce_id ) );
	 
		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
	 
	}
    
    public function magic_shortcodes_columns($columns) {		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title','magic-shortcodes' ),
			'shortcode' => __( 'Shortcode','magic-shortcodes' ),
			'date' => __( 'Date','magic-shortcodes' )
		);	
		return $columns;
	}
	
	
	public function magic_shortcodes_columns_data( $column, $post_id ) {
		global $post;
	
		switch( $column ) {
	
			/* If displaying the 'impressions' column. */
			case 'shortcode' :
                
					printf( __( '[magic_shortcodes id="%s"]' ), $post_id );
	
				break;
			
	
			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}
    
    public function add_ms_button() {
        global $typenow;
        // check user permissions
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
        return;
        }
        // verify the post type
        if( ! in_array( $typenow, array( 'post', 'page' ) ) )
            return;
        // check if WYSIWYG is enabled
        if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", array($this,"ms_add_tinymce_plugin") );
            add_filter('mce_buttons', array($this,'ms_register_my_tc_button') );
        }
    }


       public function ms_add_tinymce_plugin($plugin_array) {
        $plugin_array['ms_tc_button'] = plugins_url( '/js/ms_tc_button.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
        return $plugin_array;
    }
        function ms_register_my_tc_button($buttons) {
       array_push($buttons, "ms_tc_button");
       return $buttons;
    }
    
    
    public function getSelectedValue() {
       extract($_POST);
       $path = plugin_dir_path( __FILE__ ). '/views/';
       $myfile = fopen($path."shortcode.txt", "w") or die("Unable to open file!");
       fwrite($myfile, $data);       
   }

    public function removeshortcode() {
       extract($_POST);
       $path = plugin_dir_path( __FILE__ ). '/views/';
       $myfile = fopen($path."shortcode.txt", "w") or die("Unable to open file!");
       fwrite($myfile, '');       
   }
    
}
