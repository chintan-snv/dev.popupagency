<?php
namespace ElementPack\Modules\Woocommerce;

use Elementor\Core\Documents_Manager;
use ElementPack\Base\Element_Pack_Module_Base;
use ElementPack\Element_Pack_Loader;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	const TEMPLATE_MINI_CART = 'cart/mini-cart.php';
	const OPTION_NAME_USE_MINI_CART = 'use_mini_cart_template';

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'bdt-woocommerce';
	}

	public function get_widgets() {

		$products       = element_pack_option('wc_products', 'element_pack_third_party_widget', 'on' );
		$wc_add_to_cart = element_pack_option('wc_add_to_cart', 'element_pack_third_party_widget', 'on' );
		$wc_elements    = element_pack_option('wc_elements', 'element_pack_third_party_widget', 'on' );
		$wc_categories  = element_pack_option('wc_categories', 'element_pack_third_party_widget', 'on' );
		$wc_carousel    = element_pack_option('wc_carousel', 'element_pack_third_party_widget', 'on' );
		$wc_slider      = element_pack_option('wc_slider', 'element_pack_third_party_widget', 'on' );
		$wc_mini_cart   = element_pack_option('wc_mini_cart', 'element_pack_third_party_widget', 'off' );
		

		$widgets = [];

		if ( 'on' === $products ) {
			$widgets[] = 'Products';
		}
		if ( 'on' === $wc_add_to_cart ) {
			$widgets[] = 'Add_To_Cart';
		}
		if ( 'on' === $wc_elements ) {
			$widgets[] = 'Elements';
		} 
		if ( 'on' === $wc_categories ) {
			$widgets[] = 'Categories';
		}
		if ( 'on' === $wc_carousel ) {
			$widgets[] = 'WC_Carousel';
		}
		if ( 'on' === $wc_slider ) {
			$widgets[] = 'WC_Slider';
		}
		if ( 'on' === $wc_mini_cart ) {
			$widgets[] = 'WC_Mini_Cart';
		}

		return $widgets;
	}

	public function woocommerce_locate_template( $template, $template_name, $template_path ) {

		if ( self::TEMPLATE_MINI_CART !== $template_name ) {
			return $template;
		}

		$plugin_path = BDTEP_MODULES_PATH . 'woocommerce/wc-templates/';

		if ( file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		return $template;
	}

	public function element_pack_mini_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		?>
			<span class="bdt-mini-cart-inner">
				<span class="bdt-cart-button-text">
					<span class="bdt-mini-cart-price-amount">
	                    <?php echo WC()->cart->get_cart_subtotal(); ?>
					</span>
				</span>
				<span class="bdt-mini-cart-button-icon">
					<span class="bdt-cart-badge">
						<?php echo WC()->cart->get_cart_contents_count(); ?>
					</span>
					<span class="bdt-cart-icon">
						<i class="eicon" aria-hidden="true"></i>
					</span>
				</span>
			</span>

		<?php
		$fragments['a.bdt-mini-cart-button .bdt-mini-cart-inner'] = ob_get_clean();
		return $fragments;
	}

	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	public function add_products_post_class_filter() {
		add_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function remove_products_post_class_filter() {
		remove_filter( 'post_class', [ $this, 'add_product_post_class' ] );
	}

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}


	public function __construct() {
		
		parent::__construct();

		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
			add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		}

		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );

		$wc_mini_cart   = element_pack_option('wc_mini_cart', 'element_pack_third_party_widget', 'off' );

		if ( 'on' === $wc_mini_cart ) {
			add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'element_pack_mini_cart_fragment' ] );
			add_filter( 'woocommerce_locate_template', [ $this, 'woocommerce_locate_template' ], 12, 3 );
		}

	}
}
