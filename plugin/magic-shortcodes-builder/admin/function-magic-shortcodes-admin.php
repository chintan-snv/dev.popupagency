<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.codetides.com/
 * @since      3.0
 *
 * @package    Magic Shortcodes
 * @subpackage /admin/functions
 */
 
 		
	function get_text_value($id, $input_name, $default_value='')
	{
		if(get_post_meta( $id, $input_name, true )!="")
			return get_post_meta( $id, $input_name, true );
		else
			return $default_value;
	}	
	function get_checkbox_value($id, $input_name)
	{
		if(get_post_meta( $id, $input_name, true )!="")
			return 'checked="checked"';
		else
			return "";
	}
?>