<?php
/**
 * Template for rendering a `location` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$value = $filter->get_request_value();
$placeholder = $filter->get_placeholder();
$fieldkey = sprintf( 'types["%s"].filters.search_location', $type->get_slug() );
?>

<div class="form-group location-wrapper explore-filter location-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo trim( $value ) ? 'md-active' : '' ?>">
    <input
    	type="text"
		class="form-location-autocomplete"
		id="<?php echo esc_attr( $filter->get_unique_id() ) ?>"
		name="search_location"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		v-model="<?php echo esc_attr( $fieldkey ) ?>"
		@autocomplete:change="$event.detail.place.debounce === false ? _geocodeLocation( $event ) : geocodeLocation( $event );"
	>
    <i class="material-icons geocode-location" @click="getUserLocation">my_location</i>
    <label for="<?php echo esc_attr( $filter->get_unique_id() ) ?>"><?php echo esc_html( $filter->get_label() ) ?></label>
    <div class="md-border-line"></div>
</div>