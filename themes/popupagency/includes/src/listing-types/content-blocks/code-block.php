<?php

namespace MyListing\Src\Listing_Types\Content_Blocks;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Code_Block extends Base_Block {

	public function props() {
		$this->props['type'] = 'code';
		$this->props['title'] = 'Shortcode';
		$this->props['icon'] = 'mi view_headline';
		$this->props['content'] = '';
		$this->allowed_fields = [
			'text', 'texteditor', 'wp-editor', 'checkbox', 'radio', 'select', 'multiselect', 'textarea',
			'email', 'url', 'number', 'location', 'file', 'date', 'password', 'links', 'related-listing',
			'select-product', 'select-products', 'term-select',
		];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getContentField();
	}

	protected function getContentField() {
		$allowed_fields = htmlspecialchars( json_encode( ! empty( $this->allowed_fields ) ? $this->allowed_fields : [] ), ENT_QUOTES, 'UTF-8' ); ?>
		<div class="form-group">
			<label>Content (Supports <a href="#" class="cts-show-tip" data-tip="bracket-syntax">bracket syntax</a>)</label>
			<atwho :data="fieldsByType(<?php echo $allowed_fields ?>)" v-model="block.content" placeholder="Example use:
&lt;iframe src=&quot;https://facebook.com/[[facebook-id]]&quot; title=&quot;[[listing-name]]&quot;&gt;&lt;/iframe&gt;
or
[show_tweets username=&quot;[[twitter-username]]&quot;]"></atwho>
		</div>
	<?php }

}