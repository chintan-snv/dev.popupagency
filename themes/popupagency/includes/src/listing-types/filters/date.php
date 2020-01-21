<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Date extends Base_Filter {

	private $cache = [];

	public function filter_props() {
		$this->props['type'] = 'date';
		$this->props['label'] = 'Date';
		$this->props['show_field'] = '';
		$this->props['option_type'] = 'exact';
		$this->props['format'] = 'ymd';

		// set allowed fields
		$this->allowed_fields = ['date'];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getSourceField();

		$this->selectProp( 'option_type', 'Type', [
			'exact' => 'Exact Date',
			'range' => 'Date Range',
		] );

		$this->selectProp( 'format', 'Format', [
			'ymd' => 'Year + Month + Day',
			'year' => 'Years Only',
		] );
	}

	public function apply_to_query( $args, $form_data ) {
		$field_key = $this->get_prop( 'show_field' );
		$date_type = $this->get_prop('option_type');
		$format = $this->get_prop('format');

		// Exact date search.
		if ( $date_type === 'exact' && ! empty( $form_data[ $field_key ] ) ) {
			// Y-m-d format search.
			if ( $format === 'ymd' ) {
				$date = date('Y-m-d', strtotime( $form_data[ $field_key ] ));
				$compare = '=';
			}

			// Year search. The year is converted to a date format, and the query instead runs a 'BETWEEN' comparison,
			// to include the requested year from January 01 to December 31.
			if ( $format === 'year' ) {
				$date = [
					date( 'Y-01-01', strtotime( $form_data[ $field_key ] . '-01-01' ) ),
					date( 'Y-12-31', strtotime( $form_data[ $field_key ] . '-12-31' ) ),
				];
				$compare = 'BETWEEN';
			}

			$args['meta_query'][] = [
				'key'     => '_'.$field_key,
				'value'   => $date,
				'compare' => $compare,
				'type' => 'DATE',
			];
		}

		// Range date search.
		if ( $date_type === 'range' ) {
			$date_from = false;
			$date_to = false;
			$values = [];

			if ( ! empty( $form_data[ $field_key.'_from' ] ) ) {
				if ( $format === 'ymd' ) {
					$date_from = $values['date_from'] = date( 'Y-m-d', strtotime( $form_data[ $field_key.'_from' ] ) );
				}

				if ( $format === 'year' ) {
					$date_from = $values['date_from'] = date( 'Y-m-d', strtotime( $form_data[ $field_key.'_from' ] . '-01-01' ) );
				}
			}

			if ( ! empty( $form_data[ $field_key.'_to' ] ) ) {
				if ( $format === 'ymd' ) {
					$date_to = $values['date_to'] = date( 'Y-m-d', strtotime( $form_data[ $field_key.'_to' ] ) );
				}

				if ( $format === 'year' ) {
					$date_to = $values['date_to'] = date( 'Y-m-d', strtotime( $form_data[ $field_key.'_to' ] . '-12-31' ) );
				}
			}

			if ( empty( $values ) ) {
				return $args;
			}

			// convert to indexed keys to avoid issues with meta_query
			$values = array_values( $values );

			if ( count( $values ) === 1 ) {
				$values = array_pop( $values );
			}

			$args['meta_query'][] = [
				'key'     => '_'.$field_key,
				'value'   => $values,
				'compare' => is_array( $values ) ? 'BETWEEN' : ( $date_from ? '>=' : '<=' ),
				'type' => 'DATE',
			];
		}

		return $args;
	}

	public function get_request_value() {
		$field_key = $this->get_prop( 'show_field' );
		$url_key = $this->get_prop( 'url_key' );

		// exact date
		if ( ! empty( $_GET[ $url_key ] ) ) {
		    $exact_date = $_GET[ $url_key ];
		} elseif ( ! empty( $_GET[ $field_key ] ) ) {
		    $exact_date = $_GET[ $field_key ];
		} else {
		    $exact_date = '';
		}

		// from date (date range)
		if ( ! empty( $_GET[ $url_key . '_from' ] ) ) {
		    $from_date = $_GET[ $url_key . '_from' ];
		} elseif ( ! empty( $_GET[ $field_key . '_from' ] ) ) {
		    $from_date = $_GET[ $field_key . '_from' ];
		} else {
		    $from_date = '';
		}

		// to date (date range)
		if ( ! empty( $_GET[ $url_key . '_to' ] ) ) {
		    $to_date = $_GET[ $url_key . '_to' ];
		} elseif ( ! empty( $_GET[ $field_key . '_to' ] ) ) {
		    $to_date = $_GET[ $field_key . '_to' ];
		} else {
		    $to_date = '';
		}

		return [
			'exact' => $exact_date,
			'from' => $from_date,
			'to' => $to_date,
		];
	}

	public function get_request_components() {
		$format = $this->get_prop( 'format' );
		$filter_type = $this->get_prop( 'option_type' );
		$value = $this->get_request_value();
		$field_key = $this->get_prop('show_field');

		if ( $filter_type === 'range' ) {
			return [
				$field_key.'_from' => $value['from'],
				$field_key.'_to' => $value['to'],
			];
		}

		if ( $filter_type === 'exact' ) {
			return [
				$field_key => $value['exact'],
			];
		}

		return [];
	}

	public function get_postmeta_choices() {
		if ( isset( $this->cache['postmeta_choices'] ) ) {
			return $this->cache['postmeta_choices'];
		}

		global $wpdb;

		$results = $wpdb->get_col( $wpdb->prepare( "
			SELECT YEAR({$wpdb->postmeta}.meta_value) as item_year
			FROM {$wpdb->posts}
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
			WHERE {$wpdb->postmeta}.meta_key = %s
				AND {$wpdb->postmeta}.meta_value != ''
			    AND {$wpdb->posts}.post_type = 'job_listing'
			    AND {$wpdb->posts}.post_status = 'publish'
			GROUP BY item_year
			ORDER BY item_year DESC
		", '_'.$this->get_prop( 'show_field' ) ) );

		$this->cache['postmeta_choices'] = (array) $results;
		return $this->cache['postmeta_choices'];
	}
}