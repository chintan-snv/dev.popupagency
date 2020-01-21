<?php
/**
 * Base filter class which can be extended to construct
 * explore page filtering options for the listing type editor.
 *
 * @since 1.0
 */

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

abstract class Base_Filter implements \JsonSerializable, \ArrayAccess {
	use Traits\Editor_Markup_Helpers;

	/**
	 * Listing type object which this filter belongs to.
	 *
	 * @since 2.2
	 */
	public $listing_type;

	/**
	 * A randomly generated unique string, used in filter templates
	 * when the `id` attribute is needed.
	 *
	 * @since 2.2
	 */
	public $unique_id;

	/**
	 * List of filter properties/configuration. Values below are available for
	 * all filter types, but there can be additional props for specific filter types.
	 *
	 * @since 1.0
	 */
	protected $props = [
		'type' => 'wp-search',
		'label' => '',
		'default_label' => '',
		'placeholder' => '',
	];

	/**
	 * Filters that require selecting a field as the data source can use this array
	 * to define what field-types the source can be.
	 *
	 * @since 1.0
	 */
	protected $allowed_fields;

	public function __construct( $props = [] ) {
		$this->filter_props();

		// override props if any provided as a parameter
		foreach ( $props as $key => $value ) {
			if ( isset( $this->props[ $key ] ) ) {
				$this->props[ $key ] = $value;
			}

			// convert each option stored in the old format to props
			if ( $key === 'options' ) {
				foreach ( (array) $value as $option ) {
					if ( ! is_array( $option ) || empty( $option['name'] ) ) {
						continue;
					}

					// to avoid conflicts between options with the same name as the prop, e.g. option[type] and props[type],
					// then we prepend the option name with `option_`.
					if ( isset( $this->props[ 'option_'.$option['name'] ] ) && isset( $option['value'] ) ) {
						$this->props[ 'option_'.$option['name'] ] = $option['value'];
						continue;
					}

					if ( isset( $this->props[ $option['name'] ] ) && isset( $option['value'] ) ) {
						$this->props[ $option['name'] ] = $option['value'];
						continue;
					}
				}
			}
		}
	}

	/**
	 * Get the markup for filter settings to be shown in the listing type editor.
	 *
	 * @since 1.0
	 */
	abstract protected function get_editor_options();

	/**
	 * Each filter must set the "type" prop to a unique value,
	 * and may add other props if needed.
	 *
	 * @since 1.0
	 */
	abstract protected function filter_props();

	/**
	 * Apply filter clauses to the WP_Query arguments array in Explore page.
	 *
	 * @since  2.2
	 */
	abstract public function apply_to_query( $args, $form_data );

	/**
	 * See if the filter has a preset value in the current request url,
	 * validate and return the value.
	 *
	 * @since 2.2
	 */
	abstract public function get_request_value();

	/**
	 * Get all filter configuration passed through the url on the current request.
	 *
	 * @since 2.2
	 * @return array key=>value pairs of all filter data extracted from url parameters.
	 */
	abstract public function get_request_components();

	final public function print_options() {
		ob_start(); ?>
		<div class="filter-settings-wrapper" v-if="facet.type == '<?php echo esc_attr( $this->props['type'] ) ?>'">
			<?php $this->get_editor_options() ?>
		</div>
		<?php return ob_get_clean();
	}

	public function get_props() {
		return $this->props;
	}

	public function get_prop( $prop ) {
		return isset( $this->props[ $prop ] ) ? $this->props[ $prop ] : null;
	}

	public function get_type() {
		return $this->get_prop('type');
	}

	public function get_label() {
		return $this->get_prop('label');
	}

	public function get_placeholder() {
		return $this->get_prop('placeholder');
	}

	public function jsonSerialize() {
		return $this->props;
	}

	/**
	 * Set the listing type for this filter if available.
	 *
	 * @since 2.2
	 */
	public function set_listing_type( $listing_type ) {
		$this->listing_type = $listing_type;
	}

	/**
	 * Implements \ArrayAccess interface to keep compatibility with older
	 * snippets where filters were associative arrays.
	 *
	 * @link  https://www.php.net/manual/en/class.arrayaccess.php
	 * @since 2.2
	 */
    public function offsetSet( $offset, $value ) {
        if ( ! is_null( $offset ) ) {
            $this->props[ $offset ] = $value;
        }
    }

    public function offsetExists( $offset ) {
        return isset( $this->props[ $offset ] );
    }

    public function offsetUnset( $offset ) {
        unset( $this->props[ $offset ] );
    }

    public function offsetGet( $offset ) {
        return isset( $this->props[ $offset ] ) ? $this->props[ $offset ] : null;
    }

    public function get_unique_id() {
    	if ( ! empty( $this->unique_id ) ) {
    		return $this->unique_id;
    	}

    	$this->unique_id = 'filter_'.\MyListing\Utils\Random_Id::generate(7);
    	return $this->unique_id;
    }
}
