<?php
/**
 * Dashboard `My Account` page template.
 *
 * @since 2.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

// Filter dashboard stats by listing.
if ( ! empty( $_GET['listing'] ) && ( $listing = \MyListing\Src\Listing::get( $_GET['listing'] ) ) && $listing->editable_by_current_user() ) {
	return require locate_template( 'templates/dashboard/stats/single-listing.php' );
}

// Get logged-in user stats.
$stats = mylisting()->stats()->get_user_stats( get_current_user_id() );
?>

<div class="row">
	<div class="col-md-9 mlduo-welcome-message">
		<h1>
			<?php printf(
				_x( 'Hello, %s!', 'Dashboard welcome message', 'my-listing' ),
				apply_filters( 'mylisting/dashboard/greeting/username', trim( $current_user->user_firstname ) ? $current_user->user_firstname : $current_user->user_login, $current_user )
			) ?>
		</h1>

	</div>
	<div class="col-md-3">
		<?php require locate_template( 'templates/dashboard/stats/select-listing.php' ) ?>
	</div>
</div>

<div class="row">
	<?php
	// Published listing count.
	mylisting_locate_template( 'templates/dashboard/stats/card.php', [
		'icon' => 'icon-window',
		'value' => number_format_i18n( absint( $stats->get( 'listings.published' ) ) ),
		'description' => _x( 'Published Listings', 'Dashboard stats', 'my-listing' ),
		'background' => mylisting()->stats()->color_one,
	] );

	// Pending listing count (pending_approval + pending_payment).
	mylisting_locate_template( 'templates/dashboard/stats/card.php', [
		'icon' => 'icon-pencil-ruler',
		'value' => number_format_i18n( absint( $stats->get( 'listings.pending' ) ) ),
		'description' => _x( 'Pending Listings', 'Dashboard stats', 'my-listing' ),
		'background' => mylisting()->stats()->color_two,
	] );

	// Promoted listing count.
	mylisting_locate_template( 'templates/dashboard/stats/card.php', [
		'icon' => 'icon-flash',
		'value' => number_format_i18n( absint( $stats->get( 'promotions.count' ) ) ),
		'description' => _x( 'Active Promotions', 'Dashboard stats', 'my-listing' ),
		'background' => mylisting()->stats()->color_three,
	] );

	// Recent views card.
	mylisting_locate_template( 'templates/dashboard/stats/card.php', [
		'icon' => 'mi graphic_eq',
		'value' => number_format_i18n( absint( $stats->get( 'visits.views.lastweek' ) ) ),
		'description' => _x( 'Visits this week', 'Dashboard stats', 'my-listing' ),
		'background' => mylisting()->stats()->color_four,
	] );
	?>
</div>

<div class="row">
	<div class="col-md-4">
		<?php if ( c27()->get_setting( 'stats_views_section_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/views.php' ) ?>
		<?php endif ?>

		<?php if ( c27()->get_setting( 'stats_unique_views_section_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/unique-views.php' ) ?>
		<?php endif ?>

		<?php if ( c27()->get_setting( 'stats_devices_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/devices.php' ) ?>
		<?php endif ?>

		<?php if ( c27()->get_setting( 'stats_countries_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/countries.php' ) ?>
		<?php endif ?>
	</div>

	<div class="col-md-8">

		<?php if ( c27()->get_setting( 'stats_visits_chart_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/visits-chart.php' ) ?>
		<?php endif ?>

		<?php if ( c27()->get_setting( 'stats_referrers_enabled', true ) !== false ): ?>
			<?php require locate_template( 'templates/dashboard/stats/widgets/referrers.php' ) ?>
		<?php endif ?>

		<div class="row custom-row">
			<?php if ( c27()->get_setting( 'stats_platforms_enabled', true ) !== false ): ?>
				<div class="col-md-6">
					<?php require locate_template( 'templates/dashboard/stats/widgets/platforms.php' ) ?>
				</div>
			<?php endif ?>

			<?php if ( c27()->get_setting( 'stats_browsers_enabled', true ) !== false ): ?>
				<div class="col-md-6">
					<?php require locate_template( 'templates/dashboard/stats/widgets/browsers.php' ) ?>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>

<?php
// Support WooCommerce dashboard hooks.
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );