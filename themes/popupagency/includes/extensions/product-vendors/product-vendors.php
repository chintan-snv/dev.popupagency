<?php

namespace MyListing\Ext\Product_Vendors;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Product_Vendors {
	public static function init() {
		if ( ! get_option( 'options_product_vendors_enable' ) ) {
			return;
		}

		$provider_name = get_option( 'options_product_vendors_provider' );
		if ( $provider_name === 'simple-products' ) {
			Simple_Products\Simple_Products::instance();
		}

		if ( $provider_name === 'wc-vendors' ) {
			Wc_Vendors\Wc_Vendors::instance();
		}
	}
}