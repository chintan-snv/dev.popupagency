<?php
/**
 * Template for rendering a `text` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$value = $filter->get_request_value();
$placeholder = $filter->get_placeholder();
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter['show_field'] );
?>

<div class="form-group explore-filter text-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo trim( $value ) ? 'md-active' : '' ?>">
	<input
		type="text"
		id="<?php echo esc_attr( $filter->get_unique_id() ) ?>"
		name="<?php echo esc_attr( $filter->get_prop('url_key') ) ?>"
		v-model="<?php echo esc_attr( $fieldkey ) ?>"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		@keyup="getListings( 'text-search' )"
	>
	<label for="<?php echo esc_attr( $filter->get_unique_id() ) ?>">
		<?php echo esc_html( $filter->get_label() ) ?>
	</label>
    <div class="md-border-line"></div>
</div>