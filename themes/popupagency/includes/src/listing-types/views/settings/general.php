<div class="tab-content align-center" v-if="currentSubTab === 'general'">
	<div class="form-section">
		<h3 class="mb20">Labels</h3>

		<div class="form-group mb20">
			<label>Icon</label>
			<iconpicker v-model="settings.icon"></iconpicker>
		</div>

		<div class="form-group mb20">
			<label>Singular name <small>(e.g. Business)</small></label>
			<input type="text" v-model="settings.singular_name">
		</div>

		<div class="form-group mb20">
			<label>Plural name <small>(e.g. Businesses)</small></label>
			<input type="text" v-model="settings.plural_name">
		</div>

		<div class="form-group mb20">
			<label>Permalink <a class="cts-show-tip" data-tip="permalink-docs" title="Click to learn more">[Learn More]</a></label>
			<input type="text" v-model="settings.permalink" placeholder="<?php echo esc_attr( urldecode( $type->get_permalink_name() ) ) ?>">
		</div>
	</div>
</div>