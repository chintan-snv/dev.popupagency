<?php

function sppro_popup_form( $id ) {
	return SPPRO_Forms::get_instance( $id );
}

function sppro_get_contact_form_by_old_id( $old_id ) {
	global $wpdb;

	$q = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_old_cf7_unit_id'"
		. $wpdb->prepare( " AND meta_value = %d", $old_id );

	if ( $new_id = $wpdb->get_var( $q ) ) {
		return sppro_popup_form( $new_id );
	}
}

function sppro_get_contact_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, SPPRO_Forms::post_type );

	if ( $page ) {
		return sppro_popup_form( $page->ID );
	}

	return null;
}

function sppro_get_current_contact_form() {
	if ( $current = SPPRO_Forms::get_current() ) {
		return $current;
	}
}

add_shortcode( 'sppro', 'sppro_shortcode_function' );
function sppro_shortcode_function( $atts , $content = null ) {
	
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

function sppro_find_shortcode_occurences($shortcode, $post_id='', $post_type = 'page') {
    $found_ids = array();
    $args         = array(
        'post_type'   => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
	
	if( !empty($post_id) ) {
		$args['posts__in'] = $post_id; 
	}
    $query_result = new WP_Query($args);
    foreach ($query_result->posts as $post) {
        if (false !== strpos($post->post_content, $shortcode)) {
            $found_ids[] = $post->ID;
        }
    }
	
	
	
    return $found_ids;
}

add_action('save_post', 'sppro_save_option_for_sppro_pages' );
function sppro_save_option_for_sppro_pages( $post_id ) {
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    $option_name = 'sppro-pages';
    $shortcode = 'sppro';
    $id_array = sppro_find_shortcode_occurences($shortcode, $post_id);
    $autoload = 'yes';
    if (false == add_option($option_name, $id_array, '', 'yes')) {
		$current_value = get_option($option_name);
		$new_value = array_merge($current_value, $id_array);
		update_option($option_name, $id_array);
	}	
}

?>