<?php
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="tab-content full-width" v-if="currentSubTab === 'advanced' || currentSubTab === 'basic'">
	<div class="form-section" v-show="currentSubTab == 'advanced'">
		<h3>Customize the advanced search form for this listing type</h3>
		<p>Not sure what's this? <a href="https://docs.mylistingtheme.com/article/configuring-search-forms/" target="_blank">View the docs</a>.</p>
	</div>

	<div class="form-section" v-show="currentSubTab == 'basic'">
		<h3>Customize the basic search form for this listing type</h3>
		<p>Not sure what's this? <a href="https://docs.mylistingtheme.com/article/configuring-search-forms/" target="_blank">View the docs</a>.</p>
	</div>

	<div class="editor-column col-2-3 rows row-padding">
		<div class="form-section mb10">
			<h4 class="mb5">Active filters</h4>
			<p>Click on a filter to edit. Drag & Drop to reorder.</p>
		</div>

		<draggable v-model="search[state.search.active_form].facets" :options="{group: 'facet-types', handle: '.row-head'}">
			<div v-for="facet in search[state.search.active_form].facets" class="row-item" :class="facet === state.search.active_facet ? 'open' : ''">
				<div class="row-head" @click="state.search.active_facet = ( facet !== state.search.active_facet ) ? facet : null">
					<div class="row-head-toggle"><i class="mi chevron_right"></i></div>
					<div class="row-head-label">
						<h4>{{ facet.label }}</h4>
						<div class="details">
							<div class="detail">{{ facet.type }}</div>
						</div>
					</div>
					<div class="row-head-actions">
						<span title="Remove" @click.stop="searchTab().deleteFacet(facet, state.search.active_form)" class="action red"><i class="mi delete"></i></span>
					</div>
				</div>
				<div class="row-edit">
					<?php foreach ( $designer->get_filter_types() as $filter ): ?>
						<?php echo $filter->print_options() ?>
					<?php endforeach ?>

					<div class="text-right">
						<div class="btn" @click="state.search.active_facet = null">Done</div>
					</div>
				</div>
			</div>
		</draggable>

		<div v-if="!search[state.search.active_form].facets.length" class="mt40 text-center">
			<div class="btn btn-plain">
				<i class="mi playlist_add"></i>
				No search filters added yet.
			</div>
		</div>
	</div><!--
	--><div class="editor-column col-1-3">
		<div class="form-section mb10">
			<h4 class="mb5">Available filters</h4>
			<p>Click on a filter to use it.</p>
		</div>

		<div
		 	v-for="facet in blueprints.facet_types"
		 	v-if="!facet.form || facet.form === state.search.active_form"
			class="btn btn-block mb10"
			@click.prevent="searchTab().addFacet( facet.type, state.search.active_form )"
		>{{ facet.label }}</div>
	</div>
</div>