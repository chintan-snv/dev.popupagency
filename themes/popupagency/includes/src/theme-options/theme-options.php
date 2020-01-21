<?php
/**
 * "Theme Options" page in "WP Admin > Theme Tools"
 *
 * @since 2.2.3
 */

namespace MyListing\Src\Theme_Options;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Theme_Options {
	use \MyListing\Src\Traits\Instantiatable;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_theme_options_page' ], 50 );

		Data_Updater\Data_Updater::instance();
		Preview_Cards::instance();
	}

	/**
	 * Add theme options page in WP Admin > Theme Tools.
	 *
	 * @since 2.2.3
	 */
	public function add_theme_options_page() {
		add_submenu_page(
			'case27/tools.php',
			_x( 'Performance', 'WP Admin > Theme Tools > Performance', 'my-listing' ),
			_x( 'Performance', 'WP Admin > Theme Tools > Performance', 'my-listing' ),
			'manage_options',
			'mylisting-options',
			function() {
				$tabs = apply_filters( 'mylisting/options-page', [] );
				$active_tab = ! empty( $_GET['active_tab'] ) && isset( $tabs[ $_GET['active_tab'] ] ) ? $_GET['active_tab'] : key( $tabs );
				$url = admin_url( 'admin.php?page=mylisting-options' ); ?>
					<style type="text/css">.wp-core-ui .notice, .update-nag { display: none; }</style>
					<div class="cts-pagewrap">
						<div class="cts-tabs">
							<?php foreach ( $tabs as $key => $label ): ?>
								<a href="<?php echo esc_url( add_query_arg( 'active_tab', $key, $url ) ) ?>" class="cts-tab <?php echo $active_tab === $key ? 'cts-tab-active' : '' ?>">
									<?php echo $label ?>
								</a>
							<?php endforeach ?>
						</div>
						<div class="cts-pagecontent">
							<?php do_action( 'mylisting/options-page:'.$active_tab ) ?>
						</div>
					</div>
				<?php
			}
		);
	}
}