<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//Manage Popups Display of created popups not in form of table
class SPPRO_Forms_List_Table {

	function get_sppro_table() {
		
		$args = array(
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
		);
		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['s'] = $_REQUEST['s'];
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'title';
			} elseif ( 'author' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'author';
			} elseif ( 'date' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'date';
			}
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'ASC';
			} elseif ( 'desc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'DESC';
			}
		}

		$items = SPPRO_Forms::find( $args );
		$return = '';
		// Options to set the list or the box view for the display of the manage popups
		/*$return = '<div class="row">
						<div class="col-md-12">
							<div class="border p-2 mt-1 pull-right">
								Choose a view: <span>List View</span>&nbsp;<a href="#" id="list-view" class="mr-2 list-view"><i class="fa fa-list"></i></a>
											<span>Box View</span>&nbsp;<a href="#" id="box-view" class="box-view"><i class="fa fa-th"></i></a>
							</div>
						</div>
					</div>';*/
		// Display for the notice of the manage popup
		$return .= '<div class="sppro-notice-area"></div>';	
		// Display of manage popups
		$return .= '<div class="col-md-12">';	
			$return .= '<div class="row">';	
				foreach($items as $item) {
					$shortcode = $this->column_shortcode( $item );
					$insights = get_post_meta($item->id(), 'popup_insights', true);
					
					if(!isset($insights) OR !is_array($insights)) {
						$insights = array(
							'loaded' => 0,
							'opened' => 0,
							'submitted' => 0,
						); 	

						update_post_meta($item->id(), 'popup_insights', $insights); 
					}

					$form_options =  get_post_meta( $item->id(), '_sppro_form_options', true); 
					$where_to_show = isset($form_options['_sppro_forms_meta_where_to_show']) ? $form_options['_sppro_forms_meta_where_to_show'] : ""; 
					$selected_pages = isset($form_options['_sppro_forms_meta_selected_pages']) ? $form_options['_sppro_forms_meta_selected_pages'] : ""; 

				// Commented the code for list view just in case when you have more than 20 popups easy to configure.	
				/*	$return .= '<div class="col-md-12 card mt-1 mb-1 m-0 p-0 each-popup">';
						$return .= '<div class="card-title m-0">';
							$return .= '<div class="float-left pl-3">'.$this->column_title( $item ).'<span class="sppro_ctc" onfocus="this.select();" title="Click to copy Shortcode">'.$shortcode.'</span></div>';	
							$return .= '<div class="float-right pr-4 pt-3">'.$this->column_date( $item ).'</div>';
						$return .= '</div>';
						$return .= '<div class="pt-2">';
							$return .= '<div class="float-left pl-3 pb-2">';
								$return .= '<span class="btn btn-info btn-sm" title="Edit the Popup">'.$this->column_title( $item, 'edit_url' ).'</span>&nbsp;';
								$return .= '<span class="btn btn-danger btn-sm delete-popup" title="Delete the Popup" data-popup_id="'.$item->id().'"><i class="fa fa-trash"></i></span>&nbsp;';
								$return .= '<span class="badge badge-secondary" title="Edit the CF7 form in new tab" style="padding: 7px 5px">'.$this->column_cf7_id( $item ).'</span>';
							$return .= '</div>';
							$return .= '<div class="float-right pr-3">';
								$return .= '<span class="btn btn-outline-primary btn-sm" title="Loaded"><i class="fa fa-spinner"></i></span>&nbsp;'.$insights['loaded'].' &nbsp;';
								$return .= '<span class="btn btn-outline-primary btn-sm" title="Opened"><i class="fa fa-eye"></i></span>&nbsp;'.$insights['opened'].' &nbsp;';
								$return .= '<span class="btn btn-outline-primary btn-sm" title="Submitted"><i class="fa fa-paper-plane"></i></span>&nbsp;'.$insights['submitted'].' &nbsp;';
							$return .= '</div>';
						$return .= '</div>';
					$return .= '</div>';*/
					
					$return .= '<div class="col-md-3 card card-border my-4 ml-5 mr-4 m-0 p-0 each-popup" style="background: #efefef">';
						$return .= '<div class="card-title m-0">';
							$return .= '<p class="text-center"><span class="font-weight-normal">'.$this->column_title( $item ).'</span></p>';
							$return .= '<p class="text-center"><span class="sppro_ctc" onfocus="this.select();" title="Click to copy Shortcode">'.$shortcode.'</span></p>';	
							$return .= '<p class="text-center font-weight-normal">'.$this->column_date( $item ).'</p>';
							$return .= '<p class="text-center"><span class="badge badge-secondary" style="padding: 10px 12px; font-size: 12px; cursor: pointer;">'.$this->column_cf7_id( $item ).'</span></p>';
						$return .= '</div>';
						$return .= '<div class="text-center p-1">';
							$return .= '<span class="btn btn-outline-primary btn-sm" title="Popup has been loaded: '.$insights['loaded'].' times"><i class="fa fa-spinner"></i></span>&nbsp;'.$insights['loaded'].' &nbsp;';
							$return .= '<span class="btn btn-outline-primary btn-sm" title="Popup has been opened: '.$insights['opened'].' times"><i class="fa fa-eye"></i></span>&nbsp;'.$insights['opened'].' &nbsp;';
							$return .= '<span class="btn btn-outline-primary btn-sm" title="Popup has been submitted: '.$insights['submitted'].' times"><i class="fa fa-paper-plane"></i></span>&nbsp;'.$insights['submitted'].' &nbsp;';
						$return .= '</div>';
						$return .= '<div class="p-1">';
							$return .= '<div class="float-left pl-1">';
								$return .= '<span class="btn btn-info btn-sm" title="Edit the Popup">'.$this->column_title( $item, 'edit_url' ).'</span>&nbsp;';
							$return .= '</div>';
							$return .= '<div class="float-right pr-1">';
								$return .= '<span class="btn btn-danger btn-sm delete-popup" title="Delete the Popup" data-popup_id="'.$item->id().'"><i class="fa fa-trash"></i></span>';
							$return .= '</div>';
						$return .= '</div>';
					$return .= '</div>';						
				}	
			$return .= '</div>';		
		$return .= '</div>';		
		
		echo $return;
	}

	function column_title( $item, $edit_url='' ) {

		$url = admin_url( 'post.php?action=edit&post=' . absint( $item->id() ) );
		$copyurl = admin_url( 'admin.php?page=sp-pro&post=' . absint( $item->id() ) );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );

		$output 	= sprintf(
			'<a href="'.$edit_link.'" title="Edit Popup" class="text-dark" style="text-decoration:none;"><span style="font-size:23px;display:block">%3$s</span></a>',
			esc_url( $edit_link ),
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'sp-pro-txt-domain' ),
				$item->title() ) ),
			esc_html( $item->title() )
		);

		if ($edit_url!='') {
			$edit_url = '<a href="'.$edit_link.'" title="Edit Popup" class="text-light" style="text-decoration:none;"><i class="fa fa-edit"></i></a>';
			return $edit_url;
		}
		

		$output = sprintf( '%s', $output );

		return $output;
	}

	function column_cf7_id( $item ) {
		$popup_id = $item->id();
		$output = ''; //$output .= print_r($custom, true);		
		$custom = get_post_meta( $popup_id, '_sppro_form_options', true);		
		if( isset($custom['_sppro_forms_meta_form_id']) AND !empty($custom['_sppro_forms_meta_form_id']) ) {
			$cf7_id = $custom['_sppro_forms_meta_form_id'];
			$link = SITE_URL . '/wp-admin/admin.php?page=wpcf7&post=' .$cf7_id.'&action=edit';
			$output .=  '<a class="text-light" title="Edit the '.get_the_title($cf7_id).' form in new tab" target="_blank" href='.$link.'" style="text-decoration:none">Edit <i>'.get_the_title($cf7_id).'</i> Contact Form</a>';
		}
		else {
			$output .= '<span title="There is no Contact Form in this Popup">'.ucwords(str_replace('sp_', '', $custom['_sppro_forms_meta_form_type'])).' Popup</span>';
		}			
		return $output;
	}

	function column_date( $item ) {
		$post = get_post( $item->id() );

		if ( ! $post ) {
			return;
		}

		$t_time = mysql2date( __( 'Y/m/d g:i:s A', 'sp-pro-txt-domain' ),
			$post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date )
			- get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24*60*60 ) {
			$h_time = sprintf(
				__( '%s ago', 'sp-pro-txt-domain' ), human_time_diff( $time ) );
		} else {
			$h_time = mysql2date( __( 'Y/m/d', 'sp-pro-txt-domain' ), $m_time );
		}

		return '<kbd title="Time: ' . $t_time . '" style="padding: 10px 12px; font-size: 12px; cursor: pointer;">Created: ' 	. $h_time . '</kbd>';
	}
	
	function column_shortcode( $item, $shortcode='' ) {
		$shortcodes = array( $item->shortcode() );

		$output = '';

		foreach ( $shortcodes as $shortcode ) {
			$output .= "\n" . '<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="' . esc_attr( $shortcode ) . '" class="button"/></span>';
		}

		return trim( $output );
	}

	function column_author( $item ) {
		$post = get_post( $item->id() );

		if ( ! $post ) {
			return;
		}

		$author = get_userdata( $post->post_author );

		if ( false === $author ) {
			return;
		}

		return esc_html( $author->display_name );
	}

	function column_popup_id( $item ) {
		$output = $item->id();
		return esc_html( $output );
	}

	function column_placement( $item ) {
		$popup_id = $item->id();
		$output = ''; //$output .= print_r($custom, true);		
		$custom = get_post_meta( $popup_id, '_sppro_form_options', true);		
		if( isset($custom['_sppro_forms_meta_where_to_show']) AND !empty($custom['_sppro_forms_meta_where_to_show']) ) {
			if($custom['_sppro_forms_meta_where_to_show']=='everywhere') {
				$output .=  ucwords($custom['_sppro_forms_meta_where_to_show']);
				// $output .=  ' <a href="'.site_url().'">Homepage</a>';
			}
			else {
				if( isset($custom['_sppro_forms_meta_selected_pages']) AND !empty($custom['_sppro_forms_meta_selected_pages']) ) {
					$pages = array(); 
					foreach($custom['_sppro_forms_meta_selected_pages'] as $page_id) {
						$pages[] = '<a target="_blank" href="'.get_the_permalink($page_id).'">'.get_the_title($page_id).'</a>';
					}
					$output = $output .= 'On Selected Pages<br>'.implode(', ', $pages); 
				}
				else $output .= 'On Selected Pages';
			}
		}		
		return $output;
	}
}

//add_filter( 'set-screen-option', 'sppro_set_screen_options', 10, 3 );
function sppro_set_screen_options( $result, $option, $value ) {
	$sppro_screens = array(
		'cfseven_contact_forms_per_page' );

	if ( in_array( $option, $sppro_screens ) ) {
		$result = $value;
	}

	return $result;
}

add_filter('manage_sppro_forms_posts_columns' , 'sppro_forms_post_columns');
add_action( 'manage_sppro_forms_posts_custom_column' , 'sppro_forms_posts_custom_column', 10, 2 );
function sppro_forms_post_columns($columns) {
    
	foreach($columns as $col) unset($columns[$col]);
    
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => esc_html__( 'Title', 'sp-pro-txt-domain' ),
		'shortcode' => esc_html__( 'Quick Shortcode', 'sp-pro-txt-domain' ),
		'date' => esc_html__( 'Date', 'sp-pro-txt-domain' ),
	);
	//return array_merge( $columns, $new_columns );
	return $columns; 
}

function sppro_forms_posts_custom_column( $column, $post_id ) {
    
	switch ( $column ) {        
        case 'shortcode' : 
			$title = get_the_title($post_id);			
			$shortcode = sprintf('[sppr1o id="%d" title="%s"]', $post_id, $title);
			
			$output = "\n" . '<span class="shortcode"><input type="text"'
				. ' onfocus="this.select();" readonly="readonly"'
				. ' value="' . esc_attr( $shortcode ) . '"'
				. ' class="large-text code" /></span>';
			
			echo $output;
			break;
		default: 
            echo 'Good'; 
    }	
}

add_action('wp_ajax_action_delete_sppro_popup', 'action_delete_sppro_popup');
function action_delete_sppro_popup() {
	
	$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
	
	$output = '';

	if(wp_delete_post($post_id)) {
		$output .= __('<div class="col-md-6 offset-md-3 text-center alert alert-success alert-dismissible fade show mt-4 mb-2"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Popup Deleted</div>', 'sp-pro-txt-domain');
	}
	else {
		$output .= __('<div class="col-md-6 offset-md-3 text-center alert alert-warning alert-dismissible fade show mt-4 mb-2"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> Unable to Delete</div>', 'sp-pro-txt-domain');

	}
	
	$ajaxy  = array( 'reason' => $output );
	wp_send_json_success( $ajaxy ); 
	wp_die();
}
