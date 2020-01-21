<?php
/**
 * Plugin Name: Guest Bookmarks
 * Plugin URI: https://makewebbetter.com/
 * Description: The plugin will save guest users bookmarks listing for admin.
 * Author: MakeWebBetter
 * Author URI: https://makewebbetter.com/
 * Version: 1.0.0
 * Requires at least: 4.4
 * Tested up to: 4.9
 * WC requires at least: 3.0
 * WC tested up to: 3.5
 * 
 * Text Domain: guest-bookmarks
 * Domain Path: /languages/
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'WPINC' ) ) {
	exit;
}
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){

	define('GUEST_BOOKMARKS_PLUGIN_PATH', plugin_dir_path(__FILE__));
	define('GUEST_BOOKMARKS_PLUGIN_URL', plugin_dir_url(__FILE__));
	define('GUEST_BOOKMARKS_PLUGIN_VERSION', '1.0.0' );
	define('GUEST_BOOKMARKS_TEXT_DOMAIN', 'guest-bookmarks' );

	add_action('admin_menu', 'mwb_add_menu_for_bookmarks_item');
	function mwb_add_menu_for_bookmarks_item(){
		add_menu_page('mwb-guest-list-book', __('Bookmarks List',GUEST_BOOKMARKS_TEXT_DOMAIN), 'manage_options', 'mwb-gbl-main-menu', 'mwb_gbl_add_menu_page','dashicons-media-default',55);
		
	}

	function mwb_gbl_add_menu_page(){
		include_once GUEST_BOOKMARKS_PLUGIN_PATH.'/class-bookmarks-list.php';
		$mwb_gbl_object = new Woo_User_Bookmarks_List();
		$mwb_gbl_object->prepare_items();
		$mwb_gbl_object->display();
	}

	add_action('admin_enqueue_scripts','mwb_gbl_scripts');
	add_action('wp_enqueue_scripts','mwb_gbl_frontend_scripts');
	function mwb_gbl_frontend_scripts(){
		global $wp;
		if($wp->query_vars['pagename'] == 'bookmarks'){
			wp_enqueue_style( 'mylisting-dashboard' );
		}
	}

	function mwb_gbl_scripts(){
		wp_enqueue_script('mwb-admin-custom-js', GUEST_BOOKMARKS_PLUGIN_URL.'/assets/mwb-admin-custom.js', array('jquery'),'1.0.0',false);
		wp_enqueue_style('mwb-admin-custom-css', GUEST_BOOKMARKS_PLUGIN_URL.'/assets/mwb-admin-custom.css', array(),'1.0.0','all');
	}
	add_shortcode('bookmarks', 'visitors_bookmarks_shortcode');

	function visitors_bookmarks_shortcode(){
		include_once GUEST_BOOKMARKS_PLUGIN_PATH.'/mwb-visitor-bookmarks.php';
	}

	add_action('init', 'mwb_gbl_remove_items_by_link');
	function mwb_gbl_remove_items_by_link(){
		if( (isset($_GET['listing_id']) && isset($_GET['action'])) && ( $_GET['listing_id'] != '' && $_GET['action'] == 'mwb_remove_bookmark' ) ){
			if( !is_user_logged_in() ){

				$guest_users_items =  get_option('mwb_guest_bookmarks_item', false);
				$guest_users_items = json_decode($guest_users_items, true);
				$user_ip = $_SERVER['REMOTE_ADDR'];
				if(is_array($guest_users_items) && !empty($guest_users_items) && array_key_exists($user_ip, $guest_users_items)){
					if(is_array($guest_users_items[$user_ip]) && !empty($guest_users_items[$user_ip]) && in_array($_GET['listing_id'],$guest_users_items[$user_ip])){
						$key = array_search($_GET['listing_id'], $guest_users_items[$user_ip]);
						unset($guest_users_items[$user_ip][$key]);
						update_option('mwb_guest_bookmarks_item', json_encode($guest_users_items));
					}
				}
			}
			elseif(is_user_logged_in()){
				$user_id = get_current_user_id();
				$guest_users_items = get_post_meta($user_ip,'_case27_user_bookmarks',true);
				if(is_array($guest_users_items) && !empty($guest_users_items) && in_array($_GET['listing_id'], $guest_users_items)){
					$key = array_search($_GET['listing_id'], $guest_users_items);
					unset($guest_users_items[$key]);
					update_user_meta($user_id, '_case27_user_bookmarks', $user_meta);
				}
			}
		}
	}

	add_action('save_post', 'mwb_save_custom_checkbox_data', 10, 2);
	function mwb_save_custom_checkbox_data($post_id, $post){
		if ( get_post_type( $post_id ) == 'job_listing' &&  current_user_can( 'edit_job_listing', $post_id ) && isset( $_POST['_case27_listing_type'] )) {

			if(isset($_POST['mwb_custom_checkbox_pris-per-helg'])){
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-helg',$_POST['mwb_custom_checkbox_pris-per-helg']);
			}else{
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-helg','off');
			}
			if(isset($_POST['mwb_custom_checkbox_pris-per-dag'])){
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-dag',$_POST['mwb_custom_checkbox_pris-per-dag']);
			}else{
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-dag','off');
			}
			if(isset($_POST['mwb_custom_checkbox_pris-per-vecka'])){
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-vecka',$_POST['mwb_custom_checkbox_pris-per-vecka']);
			}else{
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-vecka','off');
			}
			if(isset($_POST['mwb_custom_checkbox_pris-per-mnad'])){
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-mnad',$_POST['mwb_custom_checkbox_pris-per-mnad']);
			}else{
				update_post_meta($post_id,'mwb_custom_checkbox_pris-per-mnad','off');
			}

		}
	}
}
