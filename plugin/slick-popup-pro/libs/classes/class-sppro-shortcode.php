<?php

class SPPRO_shortcode{   

	/**      
	* $shortcode_tag   
	* holds the name of the shortcode tag   
	* @var string   
	*/     
	public $shortcode_tag = 'sppro';    
	
	/**      
	* __construct   
	* class constructor will set the needed filter and action hooks     
	*   
	* @param array $args    
	*/     
	function __construct($args = array()){      
		//add shortcode
        //add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );
 
        if ( is_admin() ){
            add_action('admin_head', array( $this, 'admin_head') );
            add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
        }
    }
 
    /**
     * shortcode_handler
     * @param  array  $atts shortcode attributes
     * @param  string $content shortcode content
     * @return string
     */
    function shortcode_handler($atts , $content = null){
        $atts = shortcode_atts(
			array(
				'id' => 0,
				'text' => 'click here',
				'image' => '',
				'htmltag' => 'span',
				'htmlid'=> '',
			), $atts, 'sppro' );
		
		if( 'sppro_forms' != get_post_type($atts['id']) )
			return; 
		
		// Add this form added by shortcode to global variable
		global $short_forms; 
		if( !is_array($short_forms) ) {
			$short_forms = array(); 
		}	
		
		if( !in_array($atts['id'], $short_forms) ) {
			$short_forms[] = $atts['id'];
		}
		
		$output = ''; 	
		$tagID = ''; 
		//$output .= print_r($atts, true);
		
		if( $atts['id'] ) {
			if( !is_null($content) AND !empty($content) ) {
				$matter = $content;
			}
			elseif( !empty($atts['image']) ) {
				$matter = '<img src="'.$atts['image'].'">';
			}
			else {
				$matter = $atts['text'];
			}
			
			if( !empty($atts['htmltag']) ) {
				if( 'anchor'==$atts['htmltag'] OR 'a'==$atts['htmltag'] ) {
					$tag = 'a href="#"';
					$tagClosing = 'a';
				}
				else {
					$tag = $atts['htmltag'];
					$tagClosing = $atts['htmltag'];
				}
			}
			
			if( !empty($atts['htmlid']) ) 
				$tagID = ' id="' .$atts['htmlid']. '"'; 
				
			$output .= '<'.$tag.$tagID.' class="sppro-showpopup" data-formid="'.$atts['id'].'">'.$matter.'</'.$tagClosing.'>';
		}
		
		return $output; 
    }
 
    /**
     * admin_head
     * calls your functions into the correct filters
     * @return void
     */
    function admin_head() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
 
        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
        }
    }
 
    /**
     * mce_external_plugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->shortcode_tag] = SPPRO_PLUGIN_URL . '/libs/admin/js/mce-buttons.js';
        return $plugin_array;
    }
 
    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }
 
    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    function admin_enqueue_scripts(){
         wp_enqueue_style('sppro_shortcode', SPPRO_PLUGIN_URL . '/libs/admin/css/mce-buttons.css' );
    }
}//end class
 
new SPPRO_shortcode();
