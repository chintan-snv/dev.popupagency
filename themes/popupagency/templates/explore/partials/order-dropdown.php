<?php
/**
 * Handle the "Sort By" dropdown in top of search results.
 *
 * @since 1.0.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$options = $type->get_ordering_options();
$value = ! empty( $_GET['sort'] ) ? $_GET['sort' ] : false;

// Select first option if no other value is provided via url.
if ( ( ! $value || ! in_array( $value, array_column( $options, 'key' ) ) ) && ! empty( $options[0] ) && ! empty( $options[0]['key'] ) ) {
    $value = $options[0]['key'];
}

$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ]['sort'] = $value;
$location_key = sprintf( 'types["%s"].filters.search_location', $type->get_slug() );
$lat_key = sprintf( 'types["%s"].filters.search_location_lat', $type->get_slug() );
$lng_key = sprintf( 'types["%s"].filters.search_location_lng', $type->get_slug() );
$proximity_key = sprintf( 'types["%s"].filters.proximity', $type->get_slug() );
$proximity_units_key = sprintf( 'types["%s"].filters.proximity_units', $type->get_slug() );
?>

<div v-show="activeType.slug === '<?php echo esc_attr( $type->get_slug() ) ?>'" :class=" 'cts-explore-sort cts-sort-type-<?php echo esc_attr( $type->get_slug() ) ?> cts-sort-type-id-<?php echo absint( $type->get_id() ) ?>' ">
	<a class="toggle-rating" href="#" data-toggle="dropdown" aria-expanded="false"><i class="mi sort"></i>
		<?php foreach ( $options as $option ):
			$is_proximity_order = ! empty( $option['notes'] ) && in_array( 'has-proximity-clause', (array) $option['notes'] );
			$condition = sprintf( "types['%s'].filters.sort == %s", $type->get_slug(), json_encode( $option['key'] ) );
			?>
			<span v-show="<?php echo esc_attr( $condition ) ?>" class="<?php echo $is_proximity_order ? 'trigger-proximity-order' : '' ?>" :class="<?php echo esc_attr( $condition ) ?> ? 'selected' : ''">
				<?php echo esc_attr( $option['label'] ) ?>

				<?php if ( $is_proximity_order ): ?>
					<span v-if="! ( <?php echo esc_attr( join( ' && ', [ $location_key, $lat_key, $lng_key ] ) ) ?> )">
						<span class="mi error_outline" style="vertical-align:middle;font-size:14px;"></span>
					</span>
				<?php endif ?>
			</span>
		<?php endforeach ?>
	</a>
	<ul class="i-dropdown dropdown-menu">
		<?php foreach ( $options as $option ):
			$is_proximity_order = ! empty( $option['notes'] ) && in_array( 'has-proximity-clause', (array) $option['notes'] );
			$method = $is_proximity_order ? 'getNearbyListings()' : '_getListings( \'order-change\' )';
			?>
			<li>
				<a href="#" @click.prevent="<?php echo esc_attr( sprintf( "types['%s'].filters.sort", $type->get_slug() ) ) ?> = <?php echo esc_attr( json_encode( $option['key'] ) ) ?>; <?php echo esc_attr( $method ) ?>;">
					<?php echo esc_attr( $option['label'] ) ?>

					<?php if ( $is_proximity_order ): ?>
						<span style="font-size:13px;color:#707070;">
							<br>
							<span v-if="<?php echo esc_attr( join( ' && ', [ $location_key, $lat_key, $lng_key ] ) ) ?>">
								{{ <?php echo $proximity_key ?> + <?php echo $proximity_units_key ?> }} &middot; {{ <?php echo $location_key ?> }}
							</span>
							<span v-if="! ( <?php echo esc_attr( join( ' && ', [ $location_key, $lat_key, $lng_key ] ) ) ?> )">
								<?php _ex( 'You must enter a location', 'Explore page', 'my-listing' ) ?>
							</span>
						</span>
					<?php endif ?>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</div>
