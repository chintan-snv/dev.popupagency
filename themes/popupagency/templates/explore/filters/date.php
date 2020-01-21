<?php
/**
 * Template for rendering a `range` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

if ( $filter->get_prop('format') === 'year' ) {
    return require locate_template( 'templates/explore/filters/partials/date-year.php' );
}

$field_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '' );
$from_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '_from' );
$to_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '_to' );
?>

<?php if ( $filter->get_prop('option_type') === 'range'): ?>
	<div class="form-group explore-filter double-input datepicker-form-group date-filter">
		<label for="<?php echo esc_attr( $filter->get_unique_id() ) ?>">
			<?php echo esc_html( $filter->get_label() ) ?>
		</label>
		<div class="datepicker-wrapper">
			<input
				type="text"
				class="mylisting-datepicker"
				placeholder="<?php esc_attr_e( 'From...', 'my-listing' ) ?>"
				name="<?php echo esc_attr( sprintf( '%s_from', $filter->get_prop('url_key') ) ) ?>"
				:value="<?php echo esc_attr( $from_key ) ?>"
				@datepicker:change="<?php echo esc_attr( $from_key ) ?> = $event.detail.value; getListings( 'datepicker-change' );"
			>
		</div>
		<div class="datepicker-wrapper">
			<input
				type="text"
				class="mylisting-datepicker"
				placeholder="<?php esc_attr_e( 'To...', 'my-listing' ) ?>"
				name="<?php echo esc_attr( sprintf( '%s_to', $filter->get_prop('url_key') ) ) ?>"
				:value="<?php echo esc_attr( $to_key ) ?>"
				@datepicker:change="<?php echo esc_attr( $to_key ) ?> = $event.detail.value; getListings( 'datepicker-change' );"
			>
		</div>
	</div>
<?php endif ?>

<?php if ( $filter->get_prop('option_type') === 'exact'): ?>
    <div class="form-group explore-filter datepicker-form-group date-filter">
        <label><?php echo esc_html( $filter->get_label() ) ?></label>
        <div class="datepicker-wrapper">
            <input
                type="text"
                class="mylisting-datepicker"
                placeholder="<?php esc_attr_e( 'Pick a date...', 'my-listing' ) ?>"
                name="<?php echo esc_attr( $filter->get_prop('url_key') ) ?>"
                :value="<?php echo esc_attr( $field_key ) ?>"
                @datepicker:change="<?php echo esc_attr( $field_key ) ?> = $event.detail.value; getListings( 'datepicker-change' );"
            >
        </div>
    </div>
<?php endif ?>
