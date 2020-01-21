<?php
/**
 * Template for displaying alternate Explore page with grid results (no-map).
 *
 * @var   $data
 * @var   $explore
 * @since 2.0
 */

$data['listing-wrap'] = 'col-md-6 col-sm-6 grid-item';
?>
<div id="c27-explore-listings" class="explore-classic <?php echo $data['types_template'] === 'dropdown' ? 'explore-types-dropdown' : 'explore-types-topbar' ?> <?php echo esc_attr( $data['finder_columns'] ) ?>">
	<?php if ( $data['types_template'] === 'topbar' ): ?>
		<?php require locate_template( 'templates/explore/partials/topbar.php' ) ?>
	<?php endif ?>

	<div class="finder-container fc-type-2">
		<div class="mobile-explore-head">
			<a type="button" class="toggle-mobile-search" data-toggle="collapse" data-target="#finderSearch"><i class="material-icons sm-icon">sort</i><?php _e( 'Search Filters', 'my-listing' ) ?></a>
		</div>

		<div class="finder-search collapse" id="finderSearch" :class="( state.mobileTab === 'filters' ? '' : 'visible-lg' )">

			<div class="finder-tabs-wrapper">
				<?php require locate_template( 'templates/explore/partials/sidebar.php' ) ?>
			</div>
		</div>
		<div class="finder-overlay"></div>
	</div>

	<section class="i-section explore-type-4" :class="( state.mobileTab === 'results' ? '' : 'visible-lg' )">
		<div class="container">
			<div class="explore-classic-sidebar col-md-4">
				<div class="element">
					<?php require locate_template( 'templates/explore/partials/sidebar.php' ) ?>
				</div>
			</div>
			<div class="explore-classic-content col-md-8">
				<div class="fl-head row">

				<div class="col-md-6 col-sm-6 col-xs-6 sort-results" v-cloak>
					<?php foreach ( $explore->types as $type ): ?>
						<?php require locate_template( 'templates/explore/partials/order-dropdown.php' ) ?>
					<?php endforeach ?>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<span class="fl-results-no" v-cloak>
						<span></span>
					</span>
				</div>
			</div>
			<div class="row results-view grid fc-type-2-results" v-show="!loading"></div>
			<div class="loader-bg" v-show="loading">
				<?php c27()->get_partial( 'spinner', [
					'color' => '#777',
					'classes' => 'center-vh',
					'size' => 28,
					'width' => 3,
				] ) ?>
			</div>
			<div class="row center-button pagination c27-explore-pagination" v-show="!loading"></div>
			</div>
		</div>
	</section>

	<?php require locate_template( 'templates/explore/partials/mobile-nav.php' ) ?>
</div>