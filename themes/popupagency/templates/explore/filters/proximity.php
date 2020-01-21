<?php
/**
 * Template for rendering a `proximity` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$value = $filter->get_request_value();
$units = $filter->get_prop('units') === 'imperial' ? 'mi' : 'km';
$fieldkey = sprintf( 'types[\'%s\'].filters.proximity', $type->get_slug() );
?>

<div class="form-group radius radius1 proximity-slider explore-filter proximity-filter">
    <div v-show="activeType.filters.search_location_lat && activeType.filters.search_location_lng && activeType.filters.search_location.trim()">
        <input type="hidden" name="proximity_units" value="<?php echo esc_attr( $units ) ?>">
		<div
			class="mylisting-range-slider"
			data-name="proximity"
			data-type="single"
			data-min="0"
			data-max="<?php echo esc_attr( $filter->get_prop('max') ) ?>"
			data-prefix="<?php echo esc_attr( $filter->get_label() ) ?> "
			data-suffix="<?php echo esc_attr( $units ) ?>"
			data-step="<?php echo esc_attr( $filter->get_prop('step') ) ?>"
			data-start="<?php echo esc_attr( $value ) ?>"
			@rangeslider:change="<?php echo esc_attr( $fieldkey ) ?> = $event.detail.value; getListings( 'proximity-filter' );"
		></div>
    </div>
</div>