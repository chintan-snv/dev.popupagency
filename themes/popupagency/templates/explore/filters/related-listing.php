<?php
/**
 * Template for rendering a `related-listing` filter in Explore page.
 *
 * @since 2.2
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

// must be a valid listing field
if ( ! ( $field = $type->get_field( $filter->get_prop('show_field') ) ) ) {
    return;
}

// term select
$is_explore_page = ! empty( $GLOBALS['c27-explore'] );
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter->get_prop('show_field') );
$selected = $filter->get_request_value();
$placeholder = $filter->get_placeholder();

// multiselect format is not supported in basic search form
$is_multiselect = $is_explore_page ? $filter->get_prop( 'multiselect' ) : false;
?>

<div class="form-group explore-filter related-listing-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo ! empty( $selected ) ? 'md-active' : '' ?>">
	<select
		class="custom-select"
		name="<?php echo esc_attr( $filter->get_prop('url_key') ) . ( $is_multiselect ? '[]' : '' ) ?>"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		data-mylisting-ajax="true"
		data-mylisting-ajax-url="mylisting_list_posts"
		data-mylisting-ajax-params="<?php echo c27()->encode_attr( [ 'listing-type' => (array) $field->get_prop('listing_type') ] ) ?>"
		<?php echo $is_multiselect ? 'multiple="multiple"' : '' ?>
        <?php printf(
            '@select:change="%s = $event.detail.value; getListings( \'related-listing-change\' );"',
            esc_attr( $fieldkey )
        ) ?>
	>
		<?php if ( ! $is_multiselect ): // so the placeholder works on single select dropdowns ?>
			<option></option>
		<?php endif ?>

		<?php foreach ( (array) $selected as $listing ): ?>
			<option value="<?php echo esc_attr( $listing['ID'] ) ?>" selected="selected">
				<?php echo esc_attr( $listing['post_title'] ) ?>
			</option>
		<?php endforeach ?>
	</select>
    <label><?php echo esc_html( $filter->get_label() ) ?></label>
</div>