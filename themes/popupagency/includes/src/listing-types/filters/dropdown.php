<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Dropdown extends Base_Filter {

	private $cache = [];

	public function filter_props() {
		$this->props['type'] = 'dropdown';
		$this->props['label'] = 'Dropdown';
		$this->props['show_field'] = '';
		$this->props['placeholder'] = '';
		$this->props['order_by'] = 'count';
		$this->props['order'] = 'DESC';
		$this->props['hide_empty'] = 1;
		$this->props['multiselect'] = 1;
		$this->props['behavior'] = 'any';

		// set allowed fields
		$this->allowed_fields = ['text', 'checkbox', 'radio', 'select', 'multiselect', 'date', 'term-multiselect', 'term-select', 'related-listing', 'number', 'location'];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getSourceField();
		$this->textProp( 'placeholder', 'Placeholder' );

		$this->selectProp( 'order_by', 'Order By', [
			'name' => 'Name',
			'count' => 'Count',
			'meta_value' => 'Value',
			'meta_value_num' => 'Numerical value',
			'include' => 'Include Order',
		] );

		$this->selectProp( 'order', 'Order', [
			'ASC' => 'Ascending',
			'DESC' => 'Descending',
		] );

		$this->checkboxProp( 'hide_empty', 'Hide empty terms?', 'Terms that don\'t yield any results will be hidden. Currently only works for taxonomy terms (categories, regions, tags, etc.)' );
		$this->getMultiselectField();
	}

	public function getMultiselectField() { ?>
		<div v-if="state.search.active_form === 'advanced'">
			<?php $this->checkboxProp( 'multiselect', 'Multiselect?' ) ?>
			<?php $this->selectProp( 'behavior', 'Multiselect behavior', [
				'any' => 'Show listings matching ANY of the selected terms',
				'all' => 'Show listings matching ALL of the selected terms',
			], 'Determine the search logic to be used when selecting multiple terms.' ) ?>
		</div>
	<?php }

	public function apply_to_query( $args, $form_data ) {
		$field_key = $this->get_prop( 'show_field' );
		$facet_behavior = $this->get_prop( 'behavior' );
		$field = $this->listing_type->get_field( $field_key );

		if ( empty( $form_data[ $field_key ] ) || ! $field ) {
			return $args;
		}

		$dropdown_values = array_filter( array_map( 'stripslashes', (array) $form_data[ $field_key ] ) );
		if ( empty( $dropdown_values ) ) {
			return $args;
		}

		// handle tax query
		if ( $field->get_type() === 'term-select' && taxonomy_exists( $field->get_prop('taxonomy') ) ) {
			$args['tax_query'][] = [
				'taxonomy' => $field->get_prop('taxonomy'),
				'field' => 'slug',
				'terms' => $dropdown_values,
				'operator' => $facet_behavior === 'all' ? 'AND' : 'IN',
				'include_children' => $facet_behavior !== 'all',
			];
		}
		// handle multiselect fields (stored in serialized format)
		elseif ( $field->get_type() === 'multiselect' || $field->get_type() === 'checkbox' ) {
			$subquery = [ 'relation' => $facet_behavior === 'all' ? 'AND' : 'OR' ];

			foreach ( $dropdown_values as $dropdown_value ) {
				$subquery[] = [
					'key'     => '_'.$field_key,
					'value'   => '"' . $dropdown_value . '"',
					'compare' => 'LIKE',
				];
			}

			$args['meta_query'][] = $subquery;
		}
		// other fields stored as plain text in wp_postmeta
		else {
			$args['meta_query'][] = [
				'key'     => '_'.$field_key,
				'value'   => $dropdown_values,
				'compare' => 'IN',
			];
		}

		return $args;
	}

	public function get_request_value() {
		if ( isset( $this->cache['request_value'] ) ) {
			return $this->cache['request_value'];
		}

		$field_key = $this->get_prop('show_field');
		$field = $this->listing_type->get_field( $field_key );
		$selected = [];

		if ( ! $field ) {
			$this->cache['request_value'] = $selected;
			return $this->cache['request_value'];
		}

		// remove "job_" prefix from category and tag fields when used in Explore page url
		if ( $field_key === 'job_category' ) { $field_key = 'category'; }
		if ( $field_key === 'job_tags' ) { $field_key = 'tag'; }

		if ( ! empty( $_GET[ $this->get_prop('url_key') ] ) ) {
		    $selected = (array) $_GET[ $this->get_prop('url_key') ];
		} elseif ( ! empty( $_GET[ $field_key ] ) ) {
		    $selected = (array) $_GET[ $field_key ];
		} elseif ( ( $selected_val = get_query_var( sprintf( 'explore_%s', $field_key ) ) ) ) {
		    $selected = (array) $selected_val;
		}

		if ( $field->get_type() !== 'term-select' ) {
			$selected = $this->postmeta_validate_selection( $selected, $field );
		} else {
			$selected = $this->validate_selected_terms( $selected, $field );
		}

		$this->cache['request_value'] = $selected;
		return $this->cache['request_value'];
	}

	public function get_request_components() {
		$field_key = $this->get_prop('show_field');
		$field = $this->listing_type->get_field( $field_key );
		$value = $this->get_request_value();

		if ( $field && $field->get_type() === 'term-select' ) {
			$value = array_filter( array_map( function( $term ) {
				return $term instanceof \WP_Term ? $term->slug : null;
		    }, (array) $value ) );
		}

		// if it's a single select use only the last value in the array
		if ( ! $this->get_prop('multiselect') ) {
			$value = ! empty( $value ) ? array_pop( $value ) : '';
		}

		return [
			$this->get_prop('show_field') => $value,
		];
	}

	private function validate_selected_terms( $selected, $field ) {
		if ( empty( $selected ) ) {
			return [];
		}

	    $selected_terms = [];
	    $_selected_terms = get_terms( [
			'taxonomy' => $field->get_prop('taxonomy'),
			'hide_empty' => false,
			'slug' => $selected,
	    ] );

	    if ( is_wp_error( $_selected_terms ) ) {
	        return [];
	    }

	    // validate selected terms
	    foreach ( $_selected_terms as $_term ) {
	        if ( ! $_term instanceof \WP_Term ) {
	            continue;
	        }

	        // ignore term if it doesn't belong to this listing type
	        $term_types = array_filter( array_map( 'absint', (array) get_term_meta( $_term->term_id, 'listing_type', true ) ) );
	        if ( ! empty( $term_types ) && ! in_array( $this->listing_type->get_id(), $term_types ) ) {
	            continue;
	        }

	        $selected_terms[] = $_term;
	    }

	    // for single select filters, we also need the ancestors of the selected term
	    // to construct the hierarchical list of dropdowns
		if ( ! $this->get_prop('multiselect') ) {
	        $selected_tree = [];
	        if ( ! empty( $selected_terms ) && $selected_terms[0] instanceof \WP_Term ) {
	            $term_list = array_reverse( get_ancestors( $selected_terms[0]->term_id, $field->get_prop('taxonomy'), 'taxonomy' ) );
	            $term_list[] = $selected_terms[0]->term_id;
	            foreach ( $term_list as $term_id ) {
	                $term = get_term( $term_id );
	                $selected_tree[] = $term;
	            }
	        }

	        return $selected_tree;
	    }

	    return $selected_terms;
	}

	private function postmeta_validate_selection( $selected, $field ) {
		$choices = $this->get_postmeta_choices();
		$validated = [];

		foreach ( $choices as $choice ) {
			if ( in_array( $choice['value'], (array) $selected ) ) {
				$validated[] = $choice['value'];
			}
		}

		return array_unique( $validated );
	}

	public function get_postmeta_choices() {
		if ( isset( $this->cache['postmeta_choices'] ) ) {
			return $this->cache['postmeta_choices'];
		}

		global $wpdb;

		$orderby = $this->get_prop('order_by');
		$order = $this->get_prop('order') === 'ASC' ? 'ASC' : 'DESC';

		$field = $this->listing_type->get_field( $this->get_prop('show_field') );
		if ( ! $field ) {
			return [];
		}

		// for 'include', we just get the list of options from the field settings, no query is needed
		if ( $orderby === 'include' ) {
			$list = [];
			$options = (array) $field->get_prop('options');

		    if ( $order === 'DESC' ) {
		        $options = array_reverse( $options );
		    }

		    foreach ( $options as $value => $label ) {
		        $list[] = [
		            'value' => $value,
		            'label' => $label,
		            'selected' => false,
		        ];
		    }

			$this->cache['postmeta_choices'] = $list;
			return $this->cache['postmeta_choices'];
		}

		// retrieve values from wp_postmeta
		if ( $orderby === 'count' ) {
			$order_clause = "COUNT({$wpdb->postmeta}.meta_value)";
		} elseif ( $orderby === 'meta_value' ) {
			$order_clause = "{$wpdb->postmeta}.meta_value";
		} elseif ( $orderby === 'meta_value_num' ) {
			$order_clause = "{$wpdb->postmeta}.meta_value +0";
		} else {
			// by default, order by name
			$order_clause = "{$wpdb->posts}.post_name";
		}

		$results = $wpdb->get_col( $wpdb->prepare( "
			SELECT {$wpdb->postmeta}.meta_value
			FROM {$wpdb->posts}
			INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
			INNER JOIN {$wpdb->postmeta} AS mt1 ON ( {$wpdb->posts}.ID = mt1.post_id )
			WHERE {$wpdb->postmeta}.meta_key = %s
				AND {$wpdb->postmeta}.meta_value != ''
				AND mt1.meta_key = '_case27_listing_type'
				AND mt1.meta_value = %s
			    AND {$wpdb->posts}.post_type = 'job_listing'
			    AND {$wpdb->posts}.post_status = 'publish'
			GROUP BY {$wpdb->postmeta}.meta_value
			ORDER BY {$order_clause} {$order}
		", '_'.$this->get_prop( 'show_field' ), $this->listing_type->get_slug() ) );

		$list = [];
		foreach ( (array) $results as $value ) {
	        if ( is_serialized( $value ) ) {
	            foreach ( array_filter( (array) unserialize( $value ) ) as $subvalue ) {
	                $list[] = [
	                    'value' => $subvalue,
	                    'label' => $subvalue,
	                    'selected' => false,
	                ];
	            }
	        } else {
	        	$list[] = [
	            	'value' => $value,
	            	'label' => $value,
	            	'selected' => false,
	        	];
	        }
		}

		$this->cache['postmeta_choices'] = $list;
		return $this->cache['postmeta_choices'];
	}
}
