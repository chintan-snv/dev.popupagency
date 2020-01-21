<?php

namespace MyListing\Src\Listing_Types\Content_Blocks;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Location_Block extends Base_Block {

	public function props() {
		$this->props['type'] = 'location';
		$this->props['title'] = 'Location';
		$this->props['icon'] = 'mi map';
		$this->props['map_skin'] = 'skin1';
		$this->props['show_field'] = 'job_location';
		$this->allowed_fields = [ 'location', 'text' ];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getSourceField();
		$this->getMapSkinField();
	}

	protected function getMapSkinField() { ?>
		<div class="form-group">
			<label>Map Skin</label>
			<div class="select-wrapper">
				<select v-model="block.map_skin">
					<?php foreach ( c27()->get_map_skins() as $key => $label ): ?>
						<option value="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $label ) ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	<?php }
}