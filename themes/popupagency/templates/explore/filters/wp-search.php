<?php
/**
 * Template for rendering a `wp-search` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$value = $filter->get_request_value();
$placeholder = $filter->get_prop('placeholder');
$fieldkey = sprintf( 'types["%s"].filters.search_keywords', $type->get_slug() );
?>

<div class="form-group explore-filter wp-search-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo trim( $value ) ? 'md-active' : '' ?>">
    <input
    	type="text"
    	v-model="<?php echo esc_attr( $fieldkey ) ?>"
    	id="<?php echo esc_attr( $filter->get_unique_id() ) ?>"
    	name="search_keywords"
    	placeholder="<?php echo esc_attr( $placeholder ) ?>"
    	@keyup="getListings( 'wp-search-filter' )"
    >
    <label for="<?php echo esc_attr( $filter->get_unique_id() ) ?>">
    	<?php echo esc_html( $filter->get_label() ) ?>
    </label>
    <div class="md-border-line"></div>
</div>