<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Related_Listing extends Base_Filter {

	public function filter_props() {
		$this->props['type'] = 'related-listing';
		$this->props['label'] = 'Related Listing';
		$this->props['show_field'] = 'related_listing';
		$this->props['placeholder'] = '';
		$this->props['multiselect'] = false;
		// $this->props['behavior'] = 'any';

		// set allowed fields
		$this->allowed_fields = ['related-listing'];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getSourceField();
		$this->getPlaceholderField();
		$this->getMultiselectField();
	}

	public function getMultiselectField() { ?>
		<div class="form-group">
			<label>Allow multiple selections?</label>
			<label class="form-switch">
				<input type="checkbox" v-model="facet.multiselect">
				<span class="switch-slider"></span>
			</label>
		</div>

		<!-- <div class="form-group" v-if="facet.multiselect">
			<label>Multiselect behavior</label>
			<div class="select-wrapper">
				<select v-model="facet.behavior">
					<option value="any">Show listings matching ANY of the selected terms</option>
					<option value="all">Show listings matching ALL of the selected terms</option>
				</select>
			</div>
			<p>Determine the search logic to be used when selecting multiple terms</p>
		</div> -->
	<?php }

	public function apply_to_query( $args, $form_data ) {
		global $wpdb;

		$field_key = $this->get_prop( 'show_field' );
		// $facet_behavior = $this->get_prop( 'behavior' );
		$field = $this->listing_type->get_field( $field_key );
		$relation_type = $field->get_prop('relation_type');

		if ( empty( $form_data[ $field_key ] ) || ! $field ) {
			return $args;
		}

		$values = array_filter( array_map( 'absint', (array) $form_data[ $field_key ] ) );
		if ( empty( $values ) ) {
			return $args;
		}

		$imploded_ids = implode( ',', $values );
		if ( in_array( $relation_type, [ 'has_one', 'has_many' ], true ) ) {
			$rows = $wpdb->get_col( $wpdb->prepare( "
				SELECT parent_listing_id FROM {$wpdb->prefix}mylisting_relations
				WHERE child_listing_id IN ({$imploded_ids}) AND field_key = %s
				ORDER BY ID ASC
			", $field_key ) );
		}

		if ( in_array( $relation_type, [ 'belongs_to_one', 'belongs_to_many' ], true ) ) {
			$rows = $wpdb->get_col( $wpdb->prepare( "
				SELECT child_listing_id FROM {$wpdb->prefix}mylisting_relations
				WHERE parent_listing_id IN ({$imploded_ids}) AND field_key = %s
				ORDER BY ID ASC
			", $field_key ) );
		}

		$ids = array_map( 'absint', (array) $rows );
		if ( empty( $ids ) ) {
			$ids = [0];
		}

		/**
		 * If the `post__in` parameter has already been set, we must make sure to only include
		 * listings that are both in the original `post__in` and in our new custom list, so the
		 * filters don't conflict with each other and behave as expected.
		 *
		 * If `array_intersect` returns zero matches, then no search results should be returned,
		 * so we set `post__in` to `[0]`.
		 */
		if ( ! empty( $args['post__in'] ) ) {
			$ids = array_intersect( $args['post__in'], $ids );
			if ( empty( $ids ) ) {
				$ids = [0];
			}
		}

		$args['post__in'] = $ids;
		return $args;
	}

	public function get_request_value() {
		$value = [];
		if ( ! empty( $_GET[ $this->get_prop('url_key') ] ) ) {
		    $value = (array) $_GET[ $this->get_prop('url_key') ];
		} elseif ( ! empty( $_GET[ $this->get_prop('show_field') ] ) ) {
		    $value = (array) $_GET[ $this->get_prop('show_field') ];
		}

		$imploded_ids = implode( ',', array_map( 'absint', $value ) );
		if ( ! empty( $value ) && $imploded_ids ) {
			global $wpdb;
			$listings = $wpdb->get_results( "
				SELECT ID, post_title FROM {$wpdb->posts}
				WHERE post_type = 'job_listing'
					AND post_status = 'publish'
					AND ID IN ({$imploded_ids})
				ORDER BY FIELD(ID,{$imploded_ids}) LIMIT 50
			", ARRAY_A );
		} else {
			$listings = [];
		}

		// if it's a single select use only the first value in the array
		if ( ! empty( $listings ) && ! $this->get_prop('multiselect') ) {
			$listings = [ array_shift( $listings ) ];
		}

		return $listings;
	}

	public function get_request_components() {
		$value = array_filter( array_map( function( $val ) {
			return ! empty( $val['ID'] ) ? absint( $val['ID'] ) : false;
		}, (array) $this->get_request_value() ) );

		if ( ! $this->get_prop('multiselect') ) {
			$value = ! empty( $value ) ? array_shift( $value ) : '';
		}

		return [
			$this->get_prop('show_field') => $value,
		];
	}
}