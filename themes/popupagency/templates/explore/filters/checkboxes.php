<?php
/**
 * Template for rendering a `checkboxes` filter in Explore page.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
    exit;
}
// $a = get_the_ID(); 
// if( $a != 10626){



// must be a valid listing field
if ( ! ( $field = $type->get_field( $filter->get_prop('show_field') ) ) ) {
    return;
}

$choices = $filter->get_choices();
//print_r($choices);
$selected = $filter->get_request_value();
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter->get_prop('show_field') );

?>

<div class="mwb_custom_filters hela-<?php echo esc_html( $filter->get_label() ) ?>">
	<div class="form-group form-group-tags explore-filter checkboxes-filter" data-key="<?php echo esc_attr( $filter->get_prop('show_field') ) ?>">
		<!-- <label><?php //echo esc_html( $filter->get_label() ) ?></label> -->
		<label class="namn-<?php echo esc_html( $filter->get_label() ) ?>"><?php echo esc_html( $filter->get_label() ) ?><i class="fa fa-plus plus-sign"></i><i class="fa fa-minus minus-sign"></i></label>



        <div class="resu-<?php echo esc_html( $filter->get_label() ) ?>">
            

            <?php
            if($filter->get_label() != 'Plats')
            {
                $all_lokaltype_first = get_categories('taxonomy=job_listing_category&type=job_listing&parent=0&hide_empty=false');
                $all_plates = array();
                $temp = array();
                $last_ind = 0;
                
                if(!empty($all_lokaltype_first))
                {
                    foreach($all_lokaltype_first as $key=>$sngl_term)
                    {
                        $last_ind = count($temp);
                        $temp[$last_ind]['type'] = 'parent';
                        $temp[$last_ind]['data'] = $sngl_term;
                        $term_id = $sngl_term->term_id;
                        if( count($temp) == 5 )
                        {
                            $all_plates[] = $temp;
                            $temp = array();
                            $last_ind = 0;
                        }

                        //second level
                        $all_lokaltype_second = get_categories('taxonomy=job_listing_category&type=job_listing&hide_empty=false&parent='.$term_id);
                        if(!empty($all_lokaltype_second))
                        {
                            foreach($all_lokaltype_second as $key1=>$sngl_term)
                            {
                                $last_ind = count($temp);
                                $index = $key + $key1 + 1;
                                $temp[$last_ind]['type'] = 'child';
                                $temp[$last_ind]['data'] = $sngl_term;

                                if( count($temp) == 5 )
                                {
                                    $all_plates[] = $temp;
                                    $temp = array();
                                    $last_ind = 0;
                                }
                            }
                        }
                    }
                }

                $plates_html = '';
                $gap = 0;
                $gap_next = 1;

                if(!empty($all_plates))
                {
                    foreach ($all_plates as $key => $sngl_plates)
                    {
                        $plates_html .= '<div class="check-box-column">';
                        foreach($sngl_plates as $k => $value)
                        {
                            $plttype = $value['type'];
                            $snglplt_parent = $value['data'];
                            $term_id = $snglplt_parent->term_id;
                            $term_taxonomy = $snglplt_parent->taxonomy;
                            $slug = $snglplt_parent->slug;
                            $name = $snglplt_parent->name;
                            $category_count = $snglplt_parent->category_count;
                            $term_icon = get_term_meta($term_id, 'icon', true);


                            if($plttype == 'parent')
                            {
                                $plates_html .= '<li>';
                                    $plates_html .= '<div class="mwb-custom-checkbox md-checkbox check-'.$slug.'">';
                                        $plates_html .= '<input id="typ-'.$slug.'" type="checkbox" value="'.$slug.'"   v-model="types[\'rent\'].filters[\'job_category\']"
                        @change="getListings( \'checkboxes\' )">';
                                        $plates_html .= '<label for="typ-'.$slug.'">';
                                            $plates_html .= '<i class="'.$term_icon.'"></i>';
                                            $plates_html .= $name.' ('.$category_count.')';
                                        $plates_html .= '</label>';
                                    $plates_html .= '</div>';
                                $plates_html .= '</li>';
                            }
                            else
                            {
                                $plates_html .= '<li class="mwb-child">';
                                    $plates_html .= '<div class="mwb-custom-checkbox md-checkbox check-'.$slug.'">';
                                        $plates_html .= '<input id="typ-'.$slug.'" type="checkbox" value="'.$slug.'" v-model="types[\'rent\'].filters[\'job_category\']"
                        @change="getListings( \'checkboxes\' )">';
                                        $plates_html .= '<label for="typ-'.$slug.'">';
                                            $plates_html .= '<i class="'.$term_icon.'"></i>';
                                            $plates_html .= $name.' ('.$category_count.')';
                                        $plates_html .= '</label>';
                                    $plates_html .= '</div>';
                                $plates_html .= '</li>';
                            }


                        }
                        $plates_html .= '</div>';

                        $gap++;
                    }
                    
                }
            ?>
                <ul class="tags-nav">
                    <?php echo $plates_html;?>
                </ul>
                <div class="okej-<?php echo esc_html( $filter->get_label() ); ?> knapp-p">
                    <a href="#" class="mwb-custom-cancel-button"> Avbryt</a>
                    <!-- <a href="#" class="button-2 btn filter-knapp c27-explore-search-button"> Okej </a> -->
                    <a href="#" class="button-2 btn filter-knapp c27-explore-search-button" @click.prevent="state.mobileTab = 'results'; mobile.matches ? _getListings() : getListings(); _resultsScrollTop();" >
                        <?php _e( 'Okej', 'my-listing' ) ?>
                    </a>
                </div>
            <?php
            }
            else
            {
                $all_regions_first = get_categories('taxonomy=region&type=job_listing&parent=0');
            ?>
                <div class="mwb-filter-plat">
                    <div class="mwb-filter-plat__region">
                        <ul class="mwb-filter-plat__tab">
                            <?php
                            if(!empty($all_regions_first))
                            {
                                foreach($all_regions_first as $first_level)
                                {
                                    $slug = $first_level->slug;
                                    $name = $first_level->name;
                                    echo '<li><a href="#'.$slug.'" data-region="'.$slug.'" class="active">'.$name.'</a></li>';
                                }
                            }
                            ?>
                            
                        </ul>
                    </div>
                    
                    <?php
                    $new_all_regions_first = array();
                    if(!empty($all_regions_first))
                    {
                        foreach($all_regions_first as $key=>$sngl_term)
                        {
                            $actcls = '';
                            if($key == 0)
                            {
                                $actcls = 'active';
                            }
                            $slug = $sngl_term->slug;
                            $name = $sngl_term->name;
                            $term_id = $sngl_term->term_id;

                            //second level
                            $all_regions_second = get_categories('taxonomy=region&type=job_listing&hide_empty=true&parent='.$term_id);

                            //for sorting descending based on count
                            if(!empty($all_regions_second))
                            {
                                $temp = array();
                                foreach($all_regions_second as $key=>$sngl_term)
                                {
                                    $term_id = $sngl_term->term_id;
                                    $term_taxonomy = $sngl_term->taxonomy;
                                    $category_count = $sngl_term->category_count;
                                    $count = custom_postcount($term_id, $term_taxonomy);
                                    $count = $count + $category_count;

                                    $sngl_term->custom_post_count = $count;
                                    $new_all_regions_first[] = $sngl_term;
                                    $temp[] = $count;
                                }
                            }
                            array_multisort($temp, SORT_DESC, $new_all_regions_first);
                            $all_regions_second = $new_all_regions_first; 
                            //for sorting descending based on count

                            //second level
                            ?>
                            <div id="<?php echo $slug;?>" class="mwb-filter-plat__con <?php echo $actcls;?>">
                                <h2><?php echo $name;?></h2>
                                <div class="mwb-filter-plat__cat">
                                    <?php
                                    $second_html = '';
                                    if(!empty($all_regions_second))
                                    {
                                        $second_html .= '<ul>';
                                        foreach($all_regions_second as $key=>$sngl_term)
                                        {
                                            $actcls = '';
                                            if($key == 0)
                                            {
                                                $actcls = 'active';
                                            }
                                            
                                            $term_id = $sngl_term->term_id;
                                            $term_taxonomy = $sngl_term->taxonomy;
                                            $count = $sngl_term->custom_post_count;
                                            if($count > 0)
                                            {
                                                $slug = $sngl_term->slug;
                                                $name = $sngl_term->name;
                                                $second_html .= '<li><a href="#'.$slug.'" data-cat="'.$slug.'" class="'.$actcls.'">'.$name.'( '.$count.' )</a></li>';
                                            }
                                        }
                                        $second_html .= '</ul>';
                                    }
                                    echo $second_html;
                                    ?>
                                </div>

                                <?php
                                $third_html = '';
                                if(!empty($all_regions_second))
                                {
                                    foreach($all_regions_second as $key=>$sngl_term)
                                    {
                                        $actcls = '';
                                        if($key == 0)
                                        {
                                            $actcls = 'active';
                                        }
                                        
                                        $term_id = $sngl_term->term_id;
                                        $term_taxonomy = $sngl_term->taxonomy;
                                        $category_count = $sngl_term->category_count;
                                        $count = custom_postcount($term_id, $term_taxonomy);
                                        $count = $count + $category_count;
                                        $slug = $sngl_term->slug;
                                        $name = $sngl_term->name;

                                        //third level
                                        $all_regions_third = get_categories('taxonomy=region&type=job_listing&hide_empty=true&parent='.$term_id);
                                        $all_regions_third = array_reverse($all_regions_third);
                                        //third level 

                                        $third_html .= '<a href="#'.$slug.'" data-cat="'.$slug.'" class="mwb-mobile-nav">'.$name.'</a>';

                                        $third_html .= '<div id="'.$slug.'" class="mwb-filter-plat__sub '.$actcls.'">';
                                            $third_html .= '<div class="mwb_custom_headings">';
                                                $third_html .= '<input data-name="'.$slug.'" id="'.$slug.'1" type="checkbox" class="mwb_custom_checkbox" value="'.$slug.'" v-model="types[\'rent\'].filters[\'region\']"
                        @change="getListings( \'checkboxes\' )">';
                                                $third_html .= '<label for="'.$slug.'1" class="mwb-filter-platsub_heading"> Hela '.$name.'</label>';
                                            $third_html .= '</div>';

                                            if(!empty($all_regions_third))
                                            {
                                                foreach ($all_regions_third as $key => $sngl_term)
                                                {
                                                    $actcls = '';
                                                    if($key == 0)
                                                    {
                                                        $actcls = 'active';
                                                    }
                                                    
                                                    $term_id = $sngl_term->term_id;
                                                    $term_taxonomy = $sngl_term->taxonomy;
                                                    $category_count = $sngl_term->category_count;
                                                    //$count = custom_postcount($term_id, $term_taxonomy);
                                                    $count = $category_count;
                                                    $slug = $sngl_term->slug;
                                                    $name = $sngl_term->name; 
                                                     $third_html .= '<div class="mwb-filter-plat__sub-con">';
                                                $third_html .= '<input type="checkbox" id="'.$slug.'" class="mwb_custom_checkbox" value="'.$slug.'" v-model="types[\'rent\'].filters[\'region\']"
                        @change="getListings( \'checkboxes\' )">';
                                                $third_html .= '<label for="'.$slug.'">'.$name.'</label>';
                                                $third_html .= '<span>('.$category_count.') </span>';
                                            $third_html .= '</div>';
                                        
                                                }
                                            }
                                            $third_html .= '</div>';
                                           
                                    }
                                }
                                echo $third_html;
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <div class="okej-<?php echo esc_html( $filter->get_label() ) ?> knapp-p">
                        <a href="#" class="mwb-custom-cancel-button"> Avbryt</a>
                        <!-- <a href="#" id="mwb_search_plats" class="button-2 btn filter-knapp c27-explore-search-button">Okej</a> -->
                        <a href="#" class="button-2 btn filter-knapp c27-explore-search-button" data-test="1" 
                        @click.prevent="_getnewListings(); _resultsScrollTop();" id="mwb_search_plats"><?php _e( 'Okej', 'my-listing' ) ?></a>
                    </div>
                </div>
            <?php
            }
            ?>

            




        </div>









	</div>
</div>

<?php /* }else{  

// must be a valid listing field
if ( ! ( $field = $type->get_field( $filter->get_prop('show_field') ) ) ) {
    return;
}

$choices = $filter->get_choices();
$selected = $filter->get_request_value();
$fieldkey = sprintf( 'types["%s"].filters["%s"]', $type->get_slug(), $filter->get_prop('show_field') );
?>

<div class="form-group form-group-tags explore-filter checkboxes-filter" data-key="<?php echo esc_attr( $filter->get_prop('show_field') ) ?>">
    <label><?php echo esc_html( $filter->get_label() ) ?></label>
    <ul class="tags-nav">
        <?php foreach ( (array) $choices as $key => $choice ):
            $choice_id = $filter->get_unique_id().'-'.$key ?>
            <li>
                <div class="md-checkbox">
                    <input
                        id="<?php echo esc_attr( $choice_id ) ?>"
                        type="<?php echo $filter->get_prop('multiselect') ? 'checkbox' : 'radio' ?>"
                        value="<?php echo esc_attr( $choice['value'] ) ?>"
                        v-model="<?php echo esc_attr( $fieldkey ) ?>"
                        @change="getListings( 'checkboxes' )"
                    >
                    <label for="<?php echo esc_attr( $choice_id ) ?>" class=""><?php echo esc_attr( $choice['label'] ) ?></label>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</div>
<?php
} */ ?>  