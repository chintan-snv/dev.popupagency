<?php
/**
 * Template for rendering a `dropdown` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

// must be a valid listing field
if ( ! ( $field = $type->get_field( $filter->get_prop('show_field') ) ) ) {
    return;
}

// keep compatibility with non-taxonomy dropdown filters
if ( $field->get_type() !== 'term-select' ) {
    return require locate_template( 'templates/explore/filters/partials/dropdown-postmeta.php' );
}

// term select
$is_explore_page = ! empty( $GLOBALS['c27-explore'] );
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter->get_prop('show_field') );
$selected = $filter->get_request_value();
$placeholder = $filter->get_placeholder();

// multiselect format is not supported in basic search form
$is_multiselect = $is_explore_page ? $filter->get_prop( 'multiselect' ) : false;

// ajax params for the term dropdowns
$params = [
    'taxonomy' => $field['taxonomy'],
    'listing-type-id' => $type->get_id(),
    'orderby' => $filter->get_prop('order_by'),
    'order' => $filter->get_prop('order'),
    'hide_empty' => $filter->get_prop('hide_empty') ? 'yes' : '',
    'term-value' => 'slug',
];

if ( ! $is_multiselect ) {
    $params['parent'] = 0;
}

/**
 * Multiselect dropdown template.
 */
if ( $is_multiselect ) { ?>
    <div class="form-group explore-filter dropdown-filter-multiselect dropdown-filter <?php echo ! $placeholder ? 'md-group' : '' ?> <?php echo ! empty( $selected ) ? 'md-active' : '' ?>">
        <div class="main-term">
            <select
                multiple="multiple"
                class="custom-select"
                name="<?php echo esc_attr( $filter->get_prop('url_key') ).'[]' ?>"
                data-mylisting-ajax="true"
                data-mylisting-ajax-url="mylisting_list_terms"
                data-mylisting-ajax-params="<?php echo c27()->encode_attr( $params ) ?>"
                <?php printf( 'placeholder="%s"', esc_attr( $placeholder ?: ' ' ) ) ?>
                <?php printf(
                    '@select:change="%s = $event.detail.value; getListings( \'term-multiselect-change\' );"',
                    esc_attr( $fieldkey )
                ) ?>
            >
                <?php foreach ( (array) $selected as $term ): ?>
                    <option value="<?php echo esc_attr( $term->slug ) ?>" selected="selected">
                        <?php echo esc_attr( $term->name ) ?>
                    </option>
                <?php endforeach ?>
            </select>
            <label><?php echo esc_html( $filter->get_label() ) ?></label>
        </div>
    </div>
<?php }

/**
 * Term hierarchy template.
 */
if ( ! $is_multiselect ) {
    $selected_tree = array_map( function( $term ) {
        return [ 'label' => $term->name, 'value' => $term->slug ];
    }, (array) $selected );
    ?>
    <div class="cts-term-hierarchy form-group <?php echo ! $placeholder ? 'md-group' : '' ?>">
        <input
            type="text"
            class="term-hierarchy-input"
            data-selected="<?php echo c27()->encode_attr( $selected_tree ); ?>"
            name="<?php echo esc_attr( $filter->get_prop('url_key') ) ?>"
            data-mylisting-ajax-params="<?php echo c27()->encode_attr( $params ) ?>"
            data-template="<?php echo $is_explore_page ? 'default' : 'alternate' ?>"
            <?php printf( 'data-placeholder="%s"', esc_attr( $placeholder ?: " " ) ) ?>
            <?php printf(
                '@termhierarchy:change="%s = $event.detail.value; getListings( \'termhierarchy-change\' );"',
                esc_attr( $fieldkey )
            ) ?>
        >
        <label><?php echo esc_html( $filter->get_label() ) ?></label>
    </div>
<?php }
