<?php
/**
 * Template for rendering the alternate `date` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

$choices = $filter->get_postmeta_choices();
$value = $filter->get_request_value();
$url_key = $filter->get_prop('url_key');
$field_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '' );
$from_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '_from' );
$to_key = sprintf( 'types["%s"].filters["%s%s"]', $type->get_slug(), $filter->get_prop('show_field'), '_to' );
?>

<?php if ( $filter->get_prop('option_type') === 'range' ): ?>
    <div class="form-group explore-filter double-input date-filter dateyear-filter">
        <label><?php echo esc_attr( $filter->get_label() ) ?></label>

        <select
            class="custom-select"
            name="<?php echo esc_attr( $url_key.'_from' ) ?>"
            placeholder="<?php echo esc_attr( _x( 'From...', 'Explore page > Date filter', 'my-listing' ) ) ?>"
            @select:change="<?php echo esc_attr( $from_key ) ?> = $event.detail.value; getListings( 'date-select-change' );"
        >
            <option></option>
            <?php foreach ( $choices as $choice ): ?>
                <option value="<?php echo esc_attr( $choice ) ?>" <?php selected( $choice, $value['from'] ) ?>>
                    <?php echo esc_attr( $choice ) ?>
                </option>
            <?php endforeach ?>
        </select>

        <select
            class="custom-select"
            name="<?php echo esc_attr( $url_key.'_to' ) ?>"
            placeholder="<?php echo esc_attr( _x( 'To...', 'Explore page > Date filter', 'my-listing' ) ) ?>"
            @select:change="<?php echo esc_attr( $to_key ) ?> = $event.detail.value; getListings( 'date-select-change' );"
        >
            <option></option>
            <?php foreach ( $choices as $choice ): ?>
                <option value="<?php echo esc_attr( $choice ) ?>" <?php selected( $choice, $value['to'] ) ?>>
                    <?php echo esc_attr( $choice ) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
<?php endif ?>

<?php if ( $filter->get_prop('option_type') === 'exact' ): ?>
    <div class="form-group explore-filter date-filter dateyear-filter">
        <label><?php echo esc_html( $filter->get_label() ) ?></label>

        <select
            class="custom-select"
            name="<?php echo esc_attr( $url_key ) ?>"
            placeholder="<?php echo esc_attr( _x( 'Choose year...', 'Explore page > Date filter', 'my-listing' ) ) ?>"
            @select:change="<?php echo esc_attr( $field_key ) ?> = $event.detail.value; getListings( 'date-select-change' );"
        >
            <option></option>
            <?php foreach ( $choices as $choice ): ?>
                <option value="<?php echo esc_attr( $choice ) ?>" <?php selected( $choice, $value['exact'] ) ?>>
                    <?php echo esc_attr( $choice ) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
<?php endif ?>