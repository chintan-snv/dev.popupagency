<?php
/**
 * Template for displaying explore page sidebar,
 * containing search tabs and filters.
 *
 * @var $explore
 * @since 2.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}
if ( empty( $explore->types ) || ! $explore->active_listing_type ) {
	return;
}
// preserve page initially for the active listing type, so that the 'pg' url param has effect
$pg = ! empty( $_GET['pg'] ) ? absint( $_GET['pg'] ) : 1;
?>
<?php if ( $data['types_template'] === 'dropdown' ): ?>
	<div class="finder-title">
		<h2 class="case27-primary-text"><?php echo esc_html( $data['title'] ) ?></h2>
		<p><?php echo esc_html( $data['subtitle'] ) ?></p>
	</div>
<?php endif ?>

	<div class="finder-tabs col-md-12 <?php echo count( $explore->types ) > 1 ? 'with-listing-types' : 'without-listing-types' ?>">
		<?php foreach ( $explore->types as $type ):
			$tabs = $type->get_explore_tabs();
			if ( count( $tabs ) < 2 ) {
				continue;
			} ?>
			<ul class="nav nav-tabs tabs-menu" role="tablist">
				<?php foreach ( $tabs as $tab ):
					$onclick = $tab['type'] === 'search-form'
						? 'activeType.tab = \'search-form\'; _getListings();'
						: 'termsExplore(\''.$tab['type'].'\', \'active\' )'
					?>
					<li :class="activeType.tab == '<?php echo esc_attr( $tab['type'] ) ?>' ? 'active' : ''">
						<a href="#<?php echo esc_attr( $tab['type'] ) ?>" role="tab" class="tab-switch" @click="<?php echo esc_attr( $onclick ) ?>">
							<i class="<?php echo esc_attr( $tab['icon'] ) ?>"></i><p><?php echo esc_html( $tab['label'] ) ?></p>
						</a>
					</li>
				<?php endforeach ?>
			</ul>
		<?php endforeach ?>
		<?php if ( $data['types_template'] === 'dropdown' && count( $explore->types ) > 1 ): ?>
			<div class="types-dropdown-wrapper" v-show="activeType.tab === 'search-form'">
				<?php require locate_template( 'templates/explore/partials/types-dropdown.php' ) ?>
			</div>
		<?php endif ?>
		<?php foreach ( $explore->types as $type ):
			$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ] = [];
			$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ]['page'] = ( $pg >= 1 ? $pg - 1 : 0 );
			$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ]['preserve_page'] = $pg > 1;
			?>
			<div class="mwb-tab-content-custom tab-content">
				<div id="search-form" class="listing-type-filters search-tab tab-pane fade in active" :class="activeType.tab == 'search-form' ? 'in active' : ''">
					<div class="search-filters type-<?php echo esc_attr( $type->get_slug() ) ?> type-id-<?php echo absint( $type->get_id() ) ?>">
						<div class="light-forms filter-wrapper">
							<?php foreach ( (array) $type->get_advanced_filters() as $filter ) {
								if ( $filter->get_type() === 'order' ) {
									continue;
								}
								//echo $filter->get_type();
								if ( $template = locate_template( sprintf( 'templates/explore/filters/%s.php', $filter->get_type() ) ) ) {
									$GLOBALS['c27-facets-vue-object'][ $type->get_slug() ] += $filter->get_request_components();
									require $template;
								}
							} ?>
						</div>
						<div class="form-group fc-search">
							<a href="#" class="buttons button-2 full-width c27-explore-search-button"
							   @click.prevent="state.mobileTab = 'results'; _getListings(); _resultsScrollTop();"
							><i class="mi search"></i><?php _e( 'Search', 'my-listing' ) ?></a>
							<a href="#" class="reset-results-27 full-width" @click.prevent="resetFilters($event); getListings();">
								<i class="mi refresh"></i><?php _ex( 'Återställ Filter', 'Explore page', 'my-listing' ) ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach ?>
		<div id="explore-taxonomy-tab" class="listing-cat-tab tab-pane fade c27-explore-categories" :class="activeType.tab !== 'search-form' ? 'in active' : ''">
			<div v-if="currentTax">
				<transition-group name="vfade-down">
					<div v-if="currentTax.activeTerm" class="active-term" :key="currentTax.activeTerm.term_id">
						<a href="#" class="taxonomy-back-btn" @click.prevent="termsGoBack( currentTax.activeTerm )" v-if="currentTax.activeTermId !== 0">
							<i class="mi keyboard_backspace"></i><?php _ex( 'Back', 'Explore page', 'my-listing' ) ?>
						</a>
						<div class="active-taxonomy-container" :class="currentTax.activeTerm.background ? 'with-bg' : 'no-bg'">
							<div
								class="category-background" style="height: 200px; background-size: cover;"
								:style="currentTax.activeTerm.background ? 'background-image: url(\''+currentTax.activeTerm.background+'\');' : ''"
							></div>
							<span class="cat-icon" :style="'background-color:'+currentTax.activeTerm.color" v-html="currentTax.activeTerm.single_icon"></span>
							<h1 class="category-name">{{ currentTax.activeTerm.name }}</h1>
							<p class="category-description">{{ currentTax.activeTerm.description }}</p>
						</div>
					</div>
					<div v-show="currentTax.termsLoading && currentTax.activeTermId !== 0 && ! currentTax.activeTerm" class="loader-bg" :key="'single-term-loading-indicator'">
						<div class="listing-cat listing-cat-loading bg-loading-animation"></div>
						<div class="listing-cat-line bg-loading-animation"></div>
						<div class="listing-cat-line bg-loading-animation"></div>
						<div class="listing-cat-line bg-loading-animation"></div>
					</div>
				</transition-group>
				<transition-group :name="currentTax.activeTermId === 0 ? 'vfade-up' : 'vfade-down'">
					<a href="#" class="taxonomy-back-btn" @click.prevent="activeType.tab = 'search-form'; getListings();" v-if="currentTax.activeTermId === 0 && showBackToFilters" :key="'back-to-filters'">
						<i class="mi keyboard_backspace"></i><?php _ex( 'Back to filters', 'Explore page', 'my-listing' ) ?>
					</a>
					<div v-if="Object.keys(currentTax.terms).length && currentTax.activeTermId !== 0" :key="'subterms-loaded-indicator-'+currentTax.activeTermId">
						<h4 class="browse-subcategories"><i class="mi bookmark_border"></i><?php _ex( 'Browse sub-categories', 'Explore page', 'my-listing' ) ?></h4>
					</div>
					<div v-if="currentTax.terms" v-for="term in currentTax.terms" class="listing-cat" :class="term.term_id == currentTax.active_term ? 'active' : ''" :key="term.term_id">
						<a href="#" @click.prevent="termsExplore( activeType.tab, term )">
							<div
								class="overlay <?php echo $explore->data['categories_overlay']['type'] == 'gradient' ? esc_attr( $explore->data['categories_overlay']['gradient'] ) : '' ?>"
								style="<?php echo $explore->data['categories_overlay']['type'] == 'solid_color' ? 'background-color: ' . esc_attr( $explore->data['categories_overlay']['solid_color'] ) . '; ' : '' ?>"
							></div>
							<div class="lc-background" :style="term.background ? 'background-image: url(\''+term.background+'\');' : ''"></div>
							<div class="lc-info">
								<h4 class="case27-secondary-text">{{ term.name }}</h4>
								<h6>{{ term.count }}</h6>
							</div>
							<div class="lc-icon" v-html="term.icon"></div>
						</a>
					</div>
					<div v-if="currentTax.terms && currentTax.hasMore && !currentTax.termsLoading" :key="'load-more-terms'">
						<a href="#" class="buttons button-2" @click.prevent="currentTax.termsPage += 1; termsExplore( activeType.tab, currentTax.activeTerm, true );">
							<?php _ex( 'Load More', 'Explore page', 'my-listing' ) ?>
						</a>
					</div>
					<div v-show="currentTax.termsLoading && currentTax.activeTermId === 0" class="loader-bg" :key="'terms-loading-indicator'">
						<div class="listing-cat listing-cat-loading bg-loading-animation"></div>
						<div class="listing-cat listing-cat-loading bg-loading-animation"></div>
						<div class="listing-cat listing-cat-loading bg-loading-animation"></div>
						<div class="listing-cat listing-cat-loading bg-loading-animation"></div>
					</div>
				</transition-group>
			</div>
		</div>
	</div>

<?php /* ?>

<div class="finder-tabs col-md-12 <?php echo count( $explore->store['listing_types'] ) > 1 ? 'with-listing-types' : 'without-listing-types' ?>">
	<ul class="nav nav-tabs tabs-menu" role="tablist">
		<li :class="state.activeTab == 'search-form' ? 'active' : ''" v-show="state.activeListingType">
			<a href="#search-form" role="tab" class="tab-switch" @click="state.activeTab = 'search-form'; getListings();">
				<i class="mi filter_list"></i><p><?php _e( 'Filters', 'my-listing' ) ?></p>
			</a>
		</li>

		<li :class="state.activeTab == 'categories' ? 'active' : ''">
			<a href="#categories" role="tab" class="tab-switch" @click="state.activeTab = 'categories'">
				<i class="material-icons">bookmark_border</i><p><?php _e( 'Categories', 'my-listing' ) ?></p>
			</a>
		</li>
	</ul>

	<div class="tab-content mwb-tab-content-custom">

		<div id="search-form" class="listing-type-filters search-tab tab-pane fade" :class="state.activeTab == 'search-form' ? 'in active' : ''">

			<?php if ( $data['types_template'] === 'dropdown' ): ?>
			
				<?php require locate_template( 'templates/explore/partials/types-dropdown.php' ); ?>
			<?php endif; ?>

			<?php foreach ($explore->store['listing_types'] as $type): ?>

				<?php $GLOBALS['c27-facets-vue-object'][ $type->get_slug() ] = []; ?>

				<div v-show="state.activeListingType == '<?php echo esc_attr( $type->get_slug() ) ?>'" class="search-filters type-<?php echo esc_attr( $type->get_slug() ) ?>">
					<div class="light-forms filter-wrapper">

						<?php foreach ((array) $type->get_search_filters() as $facet): ?>

							<?php if ( $facet['type'] == 'order' ): ?>
								<?php continue; ?>
							<?php endif ?>

							<?php c27()->get_partial( "facets/{$facet['type']}", [
								'facet' => $facet,
								'listing_type' => $type->get_slug(),
								'type' => $type,
								] ) ?>

							<?php endforeach ?>

							<?php $GLOBALS['c27-facets-vue-object'][ $type->get_slug() ]['page'] = ( $pg >= 1 ? $pg - 1 : 0 ); ?>

						</div>
						<div class="form-group fc-search">
							<a
							href="#"
							class="buttons button-2 full-width c27-explore-search-button mwb_search_button"
							@click.prevent="state.mobileTab = 'results'; mobile.matches ? _getListings() : getListings(); _resultsScrollTop();"
							><i class="mi search"></i>
							<?php _e( 'Search', 'my-listing' ) ?>
						</a>
						<a href="#" class="reset-results-27 full-width" @click.prevent="resetFilters($event)">
							<i class="mi refresh"></i>
							<?php _e( ' Återställ Filter ', 'my-listing' ); ?>
							<?php //_e( 'Reset Filters, Explore page', 'my-listing' ); ?>
						</a>
					</div>
				</div>

			<?php endforeach ?>

		</div>

		<div id="categories" class="listing-cat-tab tab-pane fade c27-explore-categories" :class="state.activeTab == 'categories' ? 'in active' : ''">

			<?php foreach ((array) $explore->store['category-items'] as $term_type => $term_group): ?>

				<div v-show="<?php echo "'" . esc_attr( $term_type ) . "' == state.activeListingType" ?>">

					<?php foreach ($term_group as $term):
					$image = $term->get_image();
						// dump($term->get_data('listing_type'));
					?>

					<div class="listing-cat" :class="<?php echo esc_attr( $term->get_id() ) ?> == taxonomies.categories.term ? 'active' : ''">
						<a @click.prevent="taxonomies.categories.term = '<?php echo esc_attr( $term->get_id() ) ?>'; taxonomies.categories.page = 0; getListings();">
							<div class="overlay <?php echo $explore->get_data('categories_overlay')['type'] == 'gradient' ? esc_attr( $explore->get_data('categories_overlay')['gradient'] ) : '' ?>"
								style="<?php echo $explore->get_data('categories_overlay')['type'] == 'solid_color' ? 'background-color: ' . esc_attr( $explore->get_data('categories_overlay')['solid_color'] ) . '; ' : '' ?>"></div>
								<div class="lc-background" style="<?php echo is_array($image) && !empty($image) ? "background-image: url('" . esc_url( $image['sizes']['large'] ) . "');" : ''; ?>">
								</div>
								<div class="lc-info">
									<h4 class="case27-secondary-text"><?php echo esc_html( $term->get_name() ) ?></h4>
									<h6><?php echo esc_html( $term->get_count() ) ?></h6>
								</div>
								<div class="lc-icon">
									<?php echo $term->get_icon([ 'background' => false, 'color' => false ]); ?>
								</div>
							</a>
						</div>

					<?php endforeach ?>

				</div>

			<?php endforeach ?>

		</div>

		<?php foreach ( $explore->active_terms as $taxonomy => $term ): ?>
			<div class="listing-regions-tab tab-pabe fade c27-explore-<?php echo esc_attr( $taxonomy ) ?>" :class="state.activeTab == '<?php echo esc_attr( $taxonomy ) ?>' ? 'in active' : ''">
				<div class="searching-for">
					<?php echo $term->get_icon( [ 'background' => false, 'color' => false ] ) ?>
					<?php printf( '<p class="searching-for-text">' . __( 'Searching for listings in %s', 'my-listing' ) . '</p>', '</p><h1 class="filter-label">' . $term->get_name() . '</h1><p>' ) ?>
				</div>
			</div>
		<?php endforeach ?>

	</div>
</div>

<?php */ ?>