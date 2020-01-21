<?php
/**
 * Template for rendering a `range` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

$fieldkey = sprintf( 'types[\'%s\'].filters[\'%s\']', $type->get_slug(), $filter['show_field'] );
$value = $filter->get_request_value();
$range_type = $filter->get_prop('option_type') === 'simple' ? 'single' : 'range';
?>

<div class="mwb_custom_filters hela-<?php echo esc_html( $filter->get_label() ) ?>">
    <div class="form-group radius radius1 range-slider explore-filter range-filter" data-type="<?php echo $range_type ?>">
    	<!-- <label><?php //echo esc_html( $filter->get_label() ) ?></label> -->
        <label class="namn-<?php echo esc_html( $filter->get_label() ) ?>"><?php echo esc_html( $filter->get_label() ) ?><i class="fa fa-plus plus-sign"></i><i class="fa fa-minus minus-sign"></i></label>
        <div class="resu-<?php echo esc_html( $filter->get_label() ) ?>">
            <div
                class="mylisting-range-slider c27-range-slider-wrapper"
                data-name="<?php echo esc_attr( $filter->get_prop('url_key') ) ?>"
                data-type="<?php echo $range_type ?>"
                data-min="<?php echo esc_attr( $filter->get_range_min() ) ?>"
                data-max="<?php echo esc_attr( $filter->get_range_max() ) ?>"
                data-prefix="<?php echo esc_attr( $filter->get_prop('prefix') ) ?>"
                data-suffix="<?php echo esc_attr( $filter->get_prop('suffix') ) ?>"
                data-step="<?php echo esc_attr( $filter->get_prop('step') ) ?>"
                data-start="<?php echo ! empty( $value['start'] ) ? esc_attr( $value['start'] ) : false ?>"
                data-end="<?php echo ! empty( $value['end'] ) ? esc_attr( $value['end'] ) : false ?>"
                data-localize="<?php echo $filter->get_prop('format_value') ? 'yes' : 'no' ?>"
                @rangeslider:change="<?php echo esc_attr( $fieldkey ) ?> = $event.detail.value; "  
            ></div> <!--  getListings( 'range-filter' ); -->
            <div class="okej-<?php echo esc_html( $filter->get_label() ) ?> knapp-p">
                <a href="#" class="mwb-custom-cancel-button"> <?php _e( 'Avbryt', 'my-listing' ) ?></a>
                <a href="#" class="button-2 btn filter-knapp c27-explore-search-button" @click.prevent="state.mobileTab = 'results'; mobile.matches ? getListings() : getListings(); _resultsScrollTop();" >
                    <?php _e( 'Okej', 'my-listing' ) ?>
                </a>
            </div>
        </div>
    </div>
</div>
