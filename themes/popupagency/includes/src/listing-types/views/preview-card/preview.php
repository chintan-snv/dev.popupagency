<div v-show="currentSubTab === 'preview-card'" class="tab-content full-width">
	<div class="form-section">
		<h3>Customize the preview card</h3>
		<p>
			Need help? Read the <a href="http://docs.mylistingtheme.com/article/configuring-the-preview-card-results-template/" target="_blank">documentation</a>
			or open a ticket in our <a href="https://helpdesk.27collective.net/" target="_blank">helpdesk</a>.
		</p>
	</div>

	<div class="editor-column col-1-3 rows">
		<div class="form-section">
			<h4>Design</h4>
			<div class="form-group mb20">
				<label>Template</label>
				<div class="select-wrapper">
					<select v-model="result.template">
						<option value="default">Default</option>
						<option value="alternate">Alternate</option>
						<option value="list-view">List view</option>
					</select>
				</div>
			</div>

			<div class="form-group" v-show="result.template !== 'list-view'">
				<label>Background</label>
				<div class="select-wrapper">
					<select v-model="result.background.type">
						<option value="image">Image</option>
						<option value="gallery">Gallery</option>
					</select>
				</div>
			</div>
		</div>

		<div class="form-section">
			<h4>Head Buttons</h4>
			<draggable v-model="result.buttons" :options="{group: 'result-buttons', handle: '.row-head'}">
				<div v-for="button in result.buttons" class="row-item" :class="button === state.preview.active_head_button ? 'open' : ''">
					<div class="row-head" @click="state.preview.active_head_button = ( button !== state.preview.active_head_button ) ? button : null">
						<div class="row-head-toggle"><i class="mi chevron_right"></i></div>
						<div class="row-head-label">
							<h4>{{ fieldLabelBySlug( button.show_field ) || '(choose a field)' }}</h4>
							<div class="details">
								<div class="detail">Head Button</div>
							</div>
						</div>
						<div class="row-head-actions">
							<span title="Remove" @click.stop="resultTab().deleteButton(button)" class="action red"><i class="mi delete"></i></span>
						</div>
					</div>
					<div class="row-edit">
						<div class="form-group">
							<label>Label</label>
							<input type="text" v-model="button.label">
						</div>

						<div class="form-group">
							<label>Use field</label>
							<div class="select-wrapper">
								<select v-model="button.show_field">
									<option value="" disabled="disabled">Select a field...</option>
									<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'radio', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'work-hours', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
									<option value="__listing_rating">Rating</option>
								</select>
							</div>
						</div>

						<div class="text-right">
							<div class="btn" @click="state.preview.active_head_button = null">Done</div>
						</div>
					</div>
				</div>
			</draggable>

			<div v-if="!result.buttons.length" class="btn btn-plain btn-block mt20">
				<i class="mi playlist_add"></i>
				No buttons added yet yet.
			</div>

			<div class="text-center mt20">
				<a class="btn btn-outline" @click.prevent="resultTab().addButton()">Add button</a>
			</div>
		</div>

		<div class="form-section">
			<h4>Fields below title</h4>

			<draggable v-model="result.info_fields" :options="{group: 'result-info_fields', handle: '.row-head'}">
				<div v-for="field in result.info_fields" class="row-item" :class="field === state.preview.active_field ? 'open' : ''">
					<div class="row-head" @click="state.preview.active_field = ( field !== state.preview.active_field ) ? field : null">
						<div class="row-head-toggle"><i class="mi chevron_right"></i></div>
						<div class="row-head-label">
							<h4>{{ fieldLabelBySlug( field.show_field ) || '(choose a field)' }}</h4>
							<div class="details">
								<div class="detail">Displayed below title</div>
							</div>
						</div>
						<div class="row-head-actions">
							<span title="Icon" class="action gray" v-if="field.icon"><i :class="field.icon"></i></span>
							<span title="Remove" @click.stop="resultTab().deleteField(field)" class="action red"><i class="mi delete"></i></span>
						</div>
					</div>
					<div class="row-edit">
						<div class="form-group">
							<label>Icon</label>
							<iconpicker v-model="field.icon"></iconpicker>
						</div>
						<div class="form-group">
							<label>Label</label>
							<input type="text" v-model="field.label">
						</div>
						<div class="form-group">
							<label>Use field</label>
							<div class="select-wrapper">
								<select v-model="field.show_field">
									<option value="" disabled="disabled">Select a field...</option>
									<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'radio', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'email', 'url', 'number', 'location'])" :value="field.slug">{{ field.label }}</option>
								</select>
							</div>
						</div>
						<div class="text-right">
							<div class="btn" @click="state.preview.active_field = null">Done</div>
						</div>
					</div>
				</div>
			</draggable>

			<div v-if="!result.info_fields.length" class="btn btn-plain btn-block mt20">
				<i class="mi playlist_add"></i>
				No fields added yet yet.
			</div>

			<div class="text-center mt20">
				<a class="btn btn-outline" @click.prevent="resultTab().addField()">Add field</a>
			</div>
		</div>

		<div class="form-section">
			<h4>Footer sections</h4>

			<draggable v-model="result.footer.sections" :options="{group: 'result-footer.sections', handle: '.row-head'}">
				<div v-for="section in result.footer.sections" class="row-item" :class="section === state.preview.active_footer_section ? 'open' : ''">
					<div class="row-head" @click="state.preview.active_footer_section = ( section !== state.preview.active_footer_section ) ? section : null">
						<div class="row-head-toggle"><i class="mi chevron_right"></i></div>
						<div class="row-head-label">
							<h4>{{ section.title }}</h4>
							<div class="details">
								<div class="detail">Footer section</div>
							</div>
						</div>
						<div class="row-head-actions">
							<span title="Remove" @click.stop="resultTab().deleteSection(section)" class="action red"><i class="mi delete"></i></span>
						</div>
					</div>
					<div class="row-edit">
						<div class="form-group" v-if="typeof section.label !== 'undefined'">
							<label>Label</label>
							<input type="text" v-model="section.label">
							<p v-if="section.type === 'host'">This form item supports the <a href="#" class="cts-show-tip" data-tip="bracket-syntax">field bracket syntax.</a></p>
						</div>

						<div class="form-group" v-if="typeof section.taxonomy !== 'undefined'">
							<label>Taxonomy</label>
							<div class="select-wrapper">
								<select v-model="section.taxonomy">
									<?php foreach ( (array) get_taxonomies( [ 'object_type' => [ 'job_listing' ], ], 'objects' ) as $tax ): ?>
										<option value="<?php echo esc_attr( $tax->name ) ?>"><?php echo esc_html( $tax->label ) ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>

						<div class="form-group" v-if="section.type === 'host'">
							<label>Use Field</label>
							<div class="select-wrapper">
								<select v-model="section.show_field">
									<option v-for="field in fieldsByType(['related-listing'])" :value="field.slug">{{ field.label }}</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="mb10">Buttons</label>
							<label class="mb10"><input type="checkbox" v-model="section.show_quick_view_button" value="yes" class="form-checkbox"> Quick View</label>
							<label><input type="checkbox" v-model="section.show_bookmark_button" value="yes" class="form-checkbox"> Bookmark</label>
						</div>

						<div v-if="typeof section.details !== 'undefined'" class="form-group">
							<label class="mb10">Details</label>
							<draggable v-model="section.details" :options="{group: 'result-footer-details', handle: '.row-head'}">
								<div v-for="detail in section.details" class="row-item">
									<div class="row-head" @click="toggleRepeaterItem($event)">
										<div class="row-head-toggle"><i class="mi chevron_right"></i></div>
										<div class="row-head-label">
											<h4>{{ fieldLabelBySlug( detail.show_field ) || '(choose a field)' }}</h4>
											<div class="details">
												<div class="detail">Detail</div>
											</div>
										</div>
										<div class="row-head-actions">
											<span title="Remove" @click.stop="resultTab().deleteDetail(detail, section)" class="action red"><i class="mi delete"></i></span>
										</div>
									</div>
									<div class="row-edit">
										<div class="form-group">
											<label>Icon</label>
											<iconpicker v-model="detail.icon"></iconpicker>
										</div>

										<div class="form-group">
											<label>Label</label>
											<input type="text" v-model="detail.label">
										</div>

										<div class="form-group">
											<label>Use field</label>
											<div class="select-wrapper">
												<select v-model="detail.show_field">
													<option v-for="field in fieldsByType(['text', 'texteditor', 'wp-editor', 'checkbox', 'radio', 'select', 'multiselect', 'textarea', 'date', 'time', 'datetime', 'email', 'url', 'number', 'location', 'file'])" :value="field.slug">{{ field.label }}</option>
												</select>
											</div>
										</div>
										<div class="text-right">
											<div class="btn" @click="toggleRepeaterItem($event)">Done</div>
										</div>
									</div>
								</div>

								<div class="text-center mt10">
									<a class="btn btn-xs btn-outline" @click.prevent="resultTab().addDetail(section)">Add detail</a>
								</div>
							</draggable>
						</div>
						<div class="text-right">
							<div class="btn" @click="state.preview.active_footer_section = null">Done</div>
						</div>
					</div>
				</div>
			</draggable>

			<div v-if="!result.footer.sections.length" class="btn btn-plain btn-block mt20 mb20">
				<i class="mi playlist_add"></i>
				No sections added yet yet.
			</div>

			<p>Choose a section</p>
			<div
				v-for="section in blueprints.preview.sections"
				class="btn btn-xs mb10"
				@click="resultTab().addSection( section.type )"
				style="margin-right: 5px;"
			>{{ section.title }}</div>
		</div>
	</div><!--
	--><div class="editor-column col-2-3">
		<div class="preview-template" :class="'template-'+result.template">
			<div class="head-buttons">
				<div v-for="button in result.buttons" class="head-button btn btn-xs">{{ formatLabel( button.label, button.show_field ) || '(no label)' }}</div>
				<div class="head-button btn btn-xs" v-if="!result.buttons.length">(no buttons added)</div>
			</div>

			<div class="background">
				<i class="mi chevron_left left-arrow" v-if="result.background.type === 'gallery'"></i>
				<i class="mi chevron_right right-arrow" v-if="result.background.type === 'gallery'"></i>
			</div>

			<div class="details">
				<div class="logo"></div>
				<div class="title"></div>
				<div class="fields">
					<div v-for="field in result.info_fields" class="field btn btn-xs btn-plain">{{ formatLabel( field.label, field.show_field ) || '(no label)' }}</div>
					<div class="field btn btn-xs btn-plain" v-if="!result.info_fields.length">(no details added)</div>
				</div>
			</div>

			<div class="sections">
				<div v-for="section in result.footer.sections" class="section btn btn-xs">{{ formatLabel( section.type ) || '(no label)' }}</div>
				<div class="section btn btn-xs" v-if="!result.footer.sections.length">(no footer sections added)</div>
			</div>
		</div>
	</div>
</div>