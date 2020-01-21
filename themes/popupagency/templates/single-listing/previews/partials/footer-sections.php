<?php
/**
 * Footer Sections template for the preview card template.
 *
 * @since 2.2
 */
if ( ! defined('ABSPATH') ) {
    exit;
}

$section_count = 0;

foreach ((array) $options['footer']['sections'] as $section) {

    if ( $section['type'] == 'categories' ) {
        // Keys = taxonomy name
        // Value = taxonomy field name (in the listing type editor)
        $taxonomies = array_merge( [
            'job_listing_category' => 'job_category',
            'case27_job_listing_tags' => 'job_tags',
            'region' => 'region',
        ], mylisting_custom_taxonomies( 'slug', 'slug' ) );

        $taxonomy = ! empty( $section['taxonomy'] ) ? $section['taxonomy'] : 'job_listing_category';
        if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
            continue;
        }

        if ( ! ( $terms = $listing->get_field( $taxonomies[ $taxonomy ] ) ) ) {
            continue;
        }

        $section_count++;
        $category_count = count( $terms );
        $first_category = array_shift( $terms );
        $first_category = new \MyListing\Src\Term( $first_category );
        $category_names = array_map( function( $category ) {
            return $category->name;
        }, $terms );
        $categories_string = join('<br>', $category_names);
        ?>
        <div class="listing-details c27-footer-section">
            <ul class="c27-listing-preview-category-list">
                <li>
                    <a href="<?php echo esc_url( $first_category->get_link() ) ?>">
                        <span class="cat-icon" style="background-color: <?php echo esc_attr( $first_category->get_color() ) ?>;">
                            <?php echo $first_category->get_icon( [ 'background' => false ] ) ?>
                        </span>
                        <span class="category-name"><?php echo esc_html( $first_category->get_name() ) ?></span>
                    </a>
                </li>

                <?php if ( count( $terms ) ): ?>
                    <li data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo esc_attr( $categories_string ) ?>" data-html="true">
                        <div class="categories-dropdown dropdown c27-more-categories">
                            <a href="#other-categories">
                                <span class="cat-icon cat-more">+<?php echo $category_count - 1 ?></span>
                            </a>
                        </div>
                    </li>
                <?php endif ?>
            </ul>

            <div class="ld-info">
                <ul>
                    <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                        <?php require locate_template( 'templates/single-listing/previews/partials/quick-view-button.php' ) ?>
                    <?php endif ?>
                    <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                        <?php require locate_template( 'templates/single-listing/previews/partials/bookmark-button.php' ) ?>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    <?php }

    if ( $section['type'] == 'host' ) {
        $field_key = ! empty( $section['show_field'] ) ? $section['show_field'] : 'related_listing';
        $field = $listing->get_field_object( $field_key );
        if ( ! ( $field && $field->get_type() === 'related-listing' ) ) {
            continue;
        }

        $related_items = (array) $field->get_related_items();
        if ( empty( $related_items ) ) {
            continue;
        }

        $section_count++; ?>

        <?php foreach ( $related_items as $key => $related_item ):
            if ( ! ( $related_item = \MyListing\Src\Listing::get( $related_item ) ) ) {
                continue;
            }

            // pre v2.2, only the listing title could be displayed using [[listing_name]] wildcard;
            // now the full bracket syntax is supported, so keep compatibility by changing [[listing_name]]
            // to the bracket syntax counterpart: [[title]]
            $section['label'] = str_replace( '[[listing_name]]', '[[title]]', $section['label'] );
            ?>
            <div class="event-host c27-footer-section">
                <a href="<?php echo esc_url( $related_item->get_link() ) ?>">
                    <?php if ( $related_item_logo = $related_item->get_logo() ): ?>
                        <div class="avatar">
                            <img src="<?php echo esc_url( $related_item_logo ) ?>" alt="<?php echo esc_attr( $related_item->get_name() ) ?>">
                        </div>
                    <?php endif ?>
                    <span class="host-name"><?php echo $related_item->compile_string( $section['label'] ) ?></span>
                </a>

                <?php if ( $key === 0): ?>
                    <div class="ld-info">
                        <ul>
                            <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                                <?php require locate_template( 'templates/single-listing/previews/partials/quick-view-button.php' ) ?>
                            <?php endif ?>
                            <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                                <?php require locate_template( 'templates/single-listing/previews/partials/bookmark-button.php' ) ?>
                            <?php endif ?>
                        </ul>
                    </div>
                <?php endif ?>
            </div>
        <?php endforeach ?>
    <?php }

    //print_r($listing);

    //if ( $section['type'] == 'author' && ( $listing->author instanceof \MyListing\Src\User ) && $listing->author->exists() ) {
        //$section_count++; ?>
            <div class="event-host c27-footer-section">
                <!-- <a href="<?php //echo esc_url( $listing->author->get_link() ) ?>">
                    <?php //if ( $avatar = $listing->author->get_avatar() ): ?>
                        <div class="avatar">
                            <img src="<?php //echo esc_url( $avatar ) ?>" alt="<?php //echo esc_attr( $listing->author->get_name() ) ?>">
                        </div>
                    <?php //endif ?>
                    <span class="host-name"><?php //echo str_replace('[[author]]', esc_html( $listing->author->get_name() ), $section['label']) ?></span>
                </a> -->

                <div class="ld-info">
                    <ul>
                        <?php if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                            <?php require locate_template( 'templates/single-listing/previews/partials/quick-view-button.php' ) ?>
                        <?php endif ?>
                        <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                            <?php require locate_template( 'templates/single-listing/previews/partials/bookmark-button.php' ) ?>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
    <?php //}

    if ($section['type'] == 'details' && $section['details']) {
        $section_count++; ?>
        <div class="listing-details-3 c27-footer-section">
            <ul class="details-list">
                <?php foreach ((array) $section['details'] as $detail):
                    if ( ! isset( $detail['icon'] ) ) {
                        $detail['icon'] = '';
                    }

                    if ( ! $listing->has_field( $detail['show_field'] ) ) {
                        continue;
                    }

                    $detail_val = $listing->get_field( $detail['show_field'] );
                    // Escape square brackets so any shortcode added by the listing owner won't be run.
                    $detail_val = str_replace( [ "[" , "]" ] , [ "&#91;" , "&#93;" ] , $detail_val );
                    $detail_val = apply_filters( 'case27\listing\preview\detail\\' . $detail['show_field'], $detail_val, $detail, $listing );

                    if ( is_array( $detail_val ) ) {
                        $detail_val = join( ', ', $detail_val );
                    }

                    $GLOBALS['c27_active_shortcode_content'] = $detail_val; 

                    $post_id = $listing->get_id();
                    if(isset($post_id) && $post_id != '')
                    {
                        $mwb_price_per_weekend = get_post_meta($post_id,'mwb_custom_checkbox_pris-per-helg', true);
                        $mwb_price_per_day = get_post_meta($post_id,'mwb_custom_checkbox_pris-per-dag', true);
                        $mwb_price_per_week = get_post_meta($post_id,'mwb_custom_checkbox_pris-per-vecka', true);
                        $mwb_price_per_month = get_post_meta($post_id,'mwb_custom_checkbox_pris-per-mnad', true);

                        if(isset($mwb_price_per_weekend) && $mwb_price_per_weekend == 'on')
                        {
                            $details_data = 'helg';
                        }
                        elseif(isset($mwb_price_per_month) && $mwb_price_per_month == 'on')
                        {
                            $details_data = 'månad';
                        }
                        elseif(isset($mwb_price_per_week) && $mwb_price_per_week == 'on')
                        {
                            $details_data = 'vecka';
                        }
                        elseif(isset($mwb_price_per_day) && $mwb_price_per_day == 'on')
                        {
                            $details_data = 'dag';
                        }
                    }

                    ?>
                    <li>
                        <?php if ( ! empty( $detail['icon'] ) ): ?>
                            <i class="<?php echo esc_attr( $detail['icon'] ) ?>"></i>
                        <?php endif ?>
                        <span>
                            <?php
                            if($detail['show_field'] == 'prioterad-pris')
                            {
                                $mwb_new_string = substr($detail_val, -3);
                                $mwb_new_string2 = substr($detail_val, 0, -3);
                                $detail_val = $mwb_new_string2.' '.$mwb_new_string;
                                $detail_val = str_replace(',', ' ', $detail_val);
                                $new_data = str_replace( '[[field]]', $detail_val, do_shortcode( $detail['label'] ) );
                                echo $new_data.' / '.$details_data;
                            }
                            else
                            {
                                echo str_replace( '[[field]]', $detail_val, do_shortcode( $detail['label'] ) );
                            }
                            ?>
                            <?php //echo str_replace( '[[field]]', $detail_val, do_shortcode( $detail['label'] ) ) ?>
                        </span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php }

    if ($section['type'] == 'actions' || $section['type'] == 'details') {
        if (
            ( isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes' ) ||
            ( isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes' )
         ): $section_count++; ?>
            <!-- <div class="listing-details actions c27-footer-section">
                <div class="ld-info">
                    <ul>
                        <?php /*if (isset($section['show_quick_view_button']) && $section['show_quick_view_button'] == 'yes'): ?>
                            <?php require locate_template( 'templates/single-listing/previews/partials/quick-view-button.php' ) ?>
                        <?php endif ?>
                        <?php if (isset($section['show_bookmark_button']) && $section['show_bookmark_button'] == 'yes'): ?>
                            <?php require locate_template( 'templates/single-listing/previews/partials/bookmark-button.php' ) ?>
                        <?php endif*/ ?>
                    </ul>
                </div>
            </div> -->
        <?php endif ?>
    <?php }
}

if ( $section_count < 1 ) {
    echo '<div class="c27-footer-empty"></div>';
}
