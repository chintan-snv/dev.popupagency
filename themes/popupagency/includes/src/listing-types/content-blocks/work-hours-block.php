<?php

namespace MyListing\Src\Listing_Types\Content_Blocks;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Work_Hours_Block extends Base_Block {

	public function props() {
		$this->props['type'] = 'work_hours';
		$this->props['title'] = 'Work Hours';
		$this->props['icon'] = 'mi alarm';
	}

	public function get_editor_options() {
		$this->getLabelField();
	}
}