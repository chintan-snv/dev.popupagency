<?php

namespace MyListing\Src\Listing_Types\Content_Blocks;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Countdown_Block extends Base_Block {

	public function props() {
		$this->props['type'] = 'countdown';
		$this->props['title'] = 'Countdown';
		$this->props['icon'] = 'mi av_timer';
		$this->props['show_field'] = 'job_date';
		$this->allowed_fields = [ 'date', 'text' ];
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getSourceField();
	}
}