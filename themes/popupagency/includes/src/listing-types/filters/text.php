<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Text extends Base_Filter {

	public function filter_props() {
		$this->props['type'] = 'text';
		$this->props['label'] = 'Text Search';
		$this->props['placeholder'] = '';
		$this->props['show_field'] = '';

		// set allowed fields
		$this->allowed_fields = ['text', 'texteditor', 'wp-editor', 'checkbox', 'radio', 'select', 'multiselect', 'textarea', 'date', 'email', 'url', 'number'];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getPlaceholderField();
		$this->getSourceField();
	}

	public function apply_to_query( $args, $form_data ) {
		$field_key = $this->get_prop( 'show_field' );
		if ( ! empty( $form_data[ $field_key ] ) ) {
			$args['meta_query'][] = [
				'key'     => '_'.$field_key,
				'value'   => sanitize_text_field( stripslashes( $form_data[ $field_key ] ) ),
				'compare' => 'LIKE',
			];
		}

		return $args;
	}

	public function get_request_value() {
		if ( ! empty( $_GET[ $this->get_prop('url_key') ] ) ) {
		    return $_GET[ $this->get_prop('url_key') ];
		}

		if ( ! empty( $_GET[ $this->get_prop('show_field') ] ) ) {
		    return $_GET[ $this->get_prop('show_field') ];
		}

		return '';
	}

	public function get_request_components() {
		return [
			$this->get_prop('show_field') => $this->get_request_value(),
		];
	}
}