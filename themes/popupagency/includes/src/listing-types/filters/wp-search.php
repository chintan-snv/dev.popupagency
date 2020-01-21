<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class WP_Search extends Base_Filter {

	public function filter_props() {
		$this->props['type'] = 'wp-search';
		$this->props['label'] = 'General Search Box';
		$this->props['placeholder'] = 'Enter keywords...';
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getPlaceholderField();
	}

	public function apply_to_query( $args, $form_data ) {
		if ( ! empty( $form_data['search_keywords'] ) ) {
			$args['search_keywords'] = sanitize_text_field( stripslashes( $form_data['search_keywords'] ) );
		}

		return $args;
	}

	public function get_request_value() {
		return ! empty( $_GET['search_keywords'] ) ? $_GET['search_keywords'] : '';
	}

	public function get_request_components() {
		return [
			'search_keywords' => $this->get_request_value(),
		];
	}
}