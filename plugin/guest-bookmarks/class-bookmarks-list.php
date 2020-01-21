<?php
if(!defined('ABSPATH')){
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
class Woo_User_Bookmarks_List extends WP_List_Table {

	public $example_data;
	/**
     * Total number of found users for the current query
     *
     * @since 3.1.0
     * @var int
     */
	private $total_count = 0;

	public function __construct() {
		parent::__construct( array(
			'singular' => 'bokkmarks',
			'plural'   => 'bokkmarks',
			'ajax'     => false,
			'screen'   => 'wc-users-bokkmarks',
			) );
	}

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
  

    	$per_page = 10;
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->example_data = $this->mwb_user_table_data();
		$data = $this->example_data;

		// usort($data, array($this, 'mwb_waos_usort_reorder'));

		$current_page = $this->get_pagenum();
		$total_items = count($data);
		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		$this->items = $data;
		$this->set_pagination_args( array(
			'total_items' => $total_items,                 
			'per_page'    => $per_page,                     
			'total_pages' => ceil($total_items/$per_page)  
			) ); 
    }


    public function get_columns() {
    	return array(
    		'uesrid' 	 => __( 'ID', GUEST_BOOKMARKS_TEXT_DOMAIN ),
    		'mwb_user_name'           => __( 'User Name/IP', GUEST_BOOKMARKS_TEXT_DOMAIN ),
    		'bookmark_items'           => __( 'Bookmark Items', GUEST_BOOKMARKS_TEXT_DOMAIN ),
    		);
    }

    public function mwb_user_table_data(){
    	global $wpdb;
    	$arg = array();
    	$users = get_users($arg);
    	$registered_users = array();
    	$registered_user_items = array();
    	if(is_array($users) && !empty($users)){
    		foreach($users as $user){
    			$bookmark_item = get_user_meta($user->ID, '_case27_user_bookmarks', true);
    			if(is_array($bookmark_item) && !empty($bookmark_item)){
    				$registered_users[$user->ID] = $bookmark_item;
    			}
    		}
    	}

    	$guest_users_items = get_option('mwb_guest_bookmarks_item', false);
    	$guest_users_items = json_decode($guest_users_items, true);
    	if(is_array($guest_users_items) && !empty($guest_users_items)){
    		foreach($guest_users_items as $userid => $user_data){
    			$registered_users[$userid] = $user_data;
    		}
    	}
    	if(is_array($registered_users) && !empty($registered_users)){
    		foreach($registered_users as $user_id => $user_value){
    			$user = get_user_by( 'id', $user_id );
    			if( $user !== false ){
    				$username = ucwords($user->data->user_nicename);
    			}else{
    				$username = $user_id;
    			}
    			$registered_user_items[] = array(
    				'uesrid' => $user_id,
    				'mwb_user_name' => $username,
    				'bookmark_items' => $user_value
    				);
    		}
    	}

    	return $registered_user_items;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array( 'uesrid' );
    }

    // public function column_userid($item){
    // 	if(isset($item['userid'])){
    // 		echo $item['userid'];
    // 	}
    // }

    public function column_mwb_user_name($item){
    	if(isset($item['mwb_user_name'])){
    		echo $item['mwb_user_name'];
    	}
    }

    public function column_bookmark_items($item){
    	$html = '';
    	if(is_array($item['bookmark_items']) && !empty($item['bookmark_items'])){
    		$html .= '<div class="mwb_items_image_wrap">';
    		foreach($item['bookmark_items'] as $lists){
    			$listing = get_post($lists);
    			$html .= '<p class="mwb_list_title"><a href="'.get_permalink($lists).'">'.$listing->post_title.'</a></p>';

    		}
    		$html .= '</div">';
    		echo $html;
    	}
    }
}