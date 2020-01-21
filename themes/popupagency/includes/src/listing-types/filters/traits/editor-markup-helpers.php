<?php
/**
 * Helper functions to generate filter settings for the listing type editor.
 *
 * @since 2.2
 */

namespace MyListing\Src\Listing_Types\Filters\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Editor_Markup_Helpers {

	protected function getLabelField() { ?>
		<div class="form-group">
			<label>Label</label>
			<input type="text" v-model="facet.label">
		</div>
	<?php }

	protected function getPlaceholderField() { ?>
		<div class="form-group">
			<label>Placeholder</label>
			<input type="text" v-model="facet.placeholder">
		</div>
	<?php }

	protected function getSourceField() {
		$allowed_fields = htmlspecialchars( json_encode( ! empty( $this->allowed_fields ) ? $this->allowed_fields : [] ), ENT_QUOTES, 'UTF-8' ); ?>
		<div class="form-group">
			<label>Use Field</label>
			<div class="select-wrapper">
				<select v-model="facet.show_field">
					<option v-for="field in fieldsByType(<?php echo $allowed_fields ?>)" :value="field.slug">{{ field.label }}</option>
				</select>
			</div>
		</div>
	<?php }

	protected function textProp( $prop, $label, $description = '' ) { ?>
		<div class="form-group">
			<label><?php echo $label ?></label>
			<input type="text" v-model="facet.<?php echo $prop ?>">
			<p><?php echo $description ?></p>
		</div>
	<?php }

	protected function numberProp( $prop, $label, $description = '' ) { ?>
		<div class="form-group">
			<label><?php echo $label ?></label>
			<input type="number" v-model="facet.<?php echo $prop ?>" step="any">
			<p><?php echo $description ?></p>
		</div>
	<?php }

	protected function checkboxProp( $prop, $label, $description = '' ) { ?>
		<div class="form-group">
			<div class="mb5"></div>
			<label>
				<input type="checkbox" v-model="facet.<?php echo $prop ?>" class="form-checkbox">
				<?php echo $label ?>
			</label>
			<p><?php echo $description ?></p>
		</div>
	<?php }

	protected function selectProp( $prop, $label, $choices, $description = '' ) { ?>
		<div class="form-group">
			<label><?php echo $label ?></label>
			<div class="select-wrapper">
				<select v-model="facet.<?php echo $prop ?>">
					<?php foreach ( $choices as $key => $name ): ?>
						<option value="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $name ) ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<p><?php echo $description ?></p>
		</div>
	<?php }

	protected function multiselectOption( $prop, $label, $choices, $description = '' ) { ?>
		<div class="form-group">
			<label><?php echo $label ?></label>
			<select v-model="facet.<?php echo $prop ?>" multiple="multiple">
				<?php foreach ( $choices as $key => $name ): ?>
					<option value="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $name ) ?></option>
				<?php endforeach ?>
			</select>
			<p><?php echo $description ?></p>
		</div>
	<?php }

}