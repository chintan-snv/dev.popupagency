<?php
/**
 * Template for displaying regular Explore page with map.
 *
 * @var   $data
 * @var   $explore
 * @since 2.0
 */
$data['listing-wrap'] = 'col-md-12 grid-item';
?>
<div class="cts-explore finder-container fc-type-1 <?php echo esc_attr( $data['finder_columns'] ) ?> <?php echo $data['types_template'] === 'dropdown' ? 'explore-types-dropdown' : 'explore-types-topbar' ?>" id="c27-explore-listings">
	<div class="mobile-explore-head">
		<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
	</div>
	<?php if ( $data['types_template'] === 'topbar' ): ?>
		<?php require locate_template( 'templates/explore/partials/topbar.php' ) ?>
	<?php endif ?>
	<div id="container-search"  class="<?php echo $data['template'] === 'explore-2' ? 'fc-one-column' : 'fc-default' ?>">
		<div class="finder-search" id="finderSearch" :class="( state.mobileTab === 'filters' ? '' : 'visible-lg' )">
			<div class="finder-tabs-wrapper">
				<?php require locate_template( 'templates/explore/partials/sidebar.php' ) ?>
			</div>
		</div>
		<div class="finder-listings" id="finderListings" :class="( state.mobileTab === 'results' ? '' : 'visible-lg' )" :style="loading?'overflow:hidden':''">
			<div class="fl-head">
				<div class="col-xs-4 sort-results showing-filter" v-cloak>
					<?php foreach ( $explore->types as $type ): ?>
						<?php require locate_template( 'templates/explore/partials/order-dropdown.php' ) ?>
					<?php endforeach ?>
				</div>
				<div class="col-xs-4 text-center">
					<span href="#" class="fl-results-no text-left" v-cloak>
						<span></span>
					</span>
				</div>
				<div class="col-xs-4 map-toggle-button">
					<a href="#" class=""><?php _e( 'Map view', 'my-listing' ) ?><i class="material-icons sm-icon">map</i></a>
				</div>
				<div class="col-xs-4 column-switch">
					<!-- <a href="#" class="col-switch switch-one <?php //echo $data['finder_columns'] === 'finder-one-columns' ? 'active' : '' ?>" data-no="finder-one-columns">
						<i class="mi view_stream"></i>
					</a>
					<a href="#" class="col-switch switch-two <?php //echo $data['finder_columns'] === 'finder-two-columns' ? 'active' : '' ?>" data-no="finder-two-columns">
						<i class="mi view_module"></i>
					</a>
					<a href="#" class="col-switch switch-three <?php //echo $data['finder_columns'] === 'finder-three-columns' ? 'active' : '' ?>" data-no="finder-three-columns">
						<i class="mi view_comfy"></i>
					</a> -->
					<a  class="hide-map active"><i class="fa fa-map"></i> </a>
					<a  class="show-map"><i class="mi grid_on"></i> </a>
				</div>
			</div> 
			<div class="results-view grid" v-show="!loading"></div>
			
			
			<div class="col-md-12 text-center">
				<a class="more btn mwb-loadmore-btn">
					<span id="text">Ladda fler lokaler</span>
					<i class="fa fa-arrow-circle-o-down"></i>
				</a>
			</div>

			<div class="loader-bg" v-show="loading">
				<?php c27()->get_partial( 'spinner', [
					'color' => '#777',
					'classes' => 'center-vh',
					'size' => 28,
					'width' => 3,
				] ) ?>
			</div>
			<div class="col-md-12 center-button pagination c27-explore-pagination" v-show="!loading"></div>
		</div>
	</div>
	<div class="finder-map" id="finderMap" :class="( state.mobileTab === 'map' ? 'map-mobile-visible' : '' )">

		<div class="map c27-map mylisting-map-loading" id="<?php echo esc_attr( 'map__' . uniqid() ) ?>" data-options="<?php echo c27()->encode_attr( [
			'skin' => $data['map']['skin'],
			'scrollwheel' => $data['map']['scrollwheel'],
			'zoom' => 10,
			'minZoom' => $data['map']['min_zoom'],
			'maxZoom' => $data['map']['max_zoom'],
		] ) ?>">
		</div>

	</div>
	<div style="display: none;">
		<div id="explore-map-location-ctrl" title="<?php echo esc_attr( _x( 'Click to show your location', 'Explore page', 'my-listing' ) ) ?>">
			<i class="mi my_location"></i>
		</div>
	</div>
	<?php require locate_template( 'templates/explore/partials/mobile-nav.php' ) ?>
</div>