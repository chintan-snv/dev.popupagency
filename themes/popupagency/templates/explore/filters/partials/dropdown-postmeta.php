<?php
/**
 * Template for rendering the `dropdown` filter with postmeta used as data source.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

$choices = $filter->get_postmeta_choices();
$selected = $filter->get_request_value();
$is_multiselect = $is_explore_page ? $filter->get_prop( 'multiselect' ) : false;
$is_explore_page = ! empty( $GLOBALS['c27-explore'] );
$placeholder = $filter->get_placeholder();
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter->get_prop('show_field') );
?>

<div class="form-group explore-filter dropdown-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo ! empty( $selected ) ? 'md-active' : '' ?> <?php echo $is_multiselect ? 'dropdown-filter-multiselect' : '' ?>">
    <?php if ( $is_explore_page ): ?>
        <select
            @select:change="<?php echo esc_attr( $fieldkey ) ?> = $event.detail.value; getListings( 'select-change' );"
            class="custom-select"
            <?php echo $is_multiselect ? 'multiple="multiple"' : '' ?>
            <?php printf( 'placeholder="%s"', esc_attr( $placeholder ?: " " ) ) ?>
        >
            <?php
            // single selects must have an empty <option></option> to handle the placeholder and stop other issues
            if ( ! $is_multiselect ): ?>
                <option></option>
            <?php endif ?>
            <?php foreach ( $choices as $choice ): ?>
                <option value="<?php echo esc_attr( $choice['value'] ) ?>" <?php selected( in_array( $choice['value'], $selected ), true ) ?>>
                    <?php echo esc_attr( $choice['label'] ) ?>
                </option>
            <?php endforeach ?>
        </select>
    <?php else: ?>
        <select name="<?php echo esc_attr( $filter->get_prop('url_key') ) ?>[]" placeholder="<?php echo esc_attr( $placeholder ) ?>" class="custom-select">
            <option></option>
            <?php foreach ( $choices as $choice ): ?>
                <option value="<?php echo esc_attr( $choice['value'] ) ?>">
                    <?php echo esc_html( $choice['label'] ) ?>
                </option>
            <?php endforeach ?>
        </select>
    <?php endif ?>

    <label><?php echo esc_html( $filter->get_label() ) ?></label>
</div>
