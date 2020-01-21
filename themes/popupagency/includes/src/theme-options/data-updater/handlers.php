<?php

namespace MyListing\Src\Theme_Options\Data_Updater\Handlers;

/**
 * Cleanup transients that were used to cache Explore page
 * query results pre version 2.2.3.
 *
 * @since 2.2.3
 */
function cleanup_transients() {
	global $wpdb;

	$count = $wpdb->get_var( "
		SELECT COUNT(option_id) FROM {$wpdb->options}
			WHERE option_name LIKE ('\_transient\_mylisting\_%')
			OR option_name LIKE ('\_transient\_timeout\_mylisting\_%')
			OR option_name LIKE ('listings\_tax\_%')
	" );

	if ( $count > 0 ) {
		$wpdb->query( "
			DELETE FROM {$wpdb->options}
				WHERE option_name LIKE ('\_transient\_mylisting\_%')
				OR option_name LIKE ('\_transient\_timeout\_mylisting\_%')
				OR option_name LIKE ('listings\_tax\_%')
		" );
	}

	return $count > 0
		? sprintf( 'Removed %d unused transients from wp_options table.', $count )
		: 'No unused transients were found.';
}

/**
 * Remove some old rows left in wp_options table when WP Job Manager was used.
 *
 * @since 2.2.3
 */
function remove_unused_options() {
	global $wpdb;

	$options = [

		/**
		 * Group by version added, so we can later remove checks for
		 * really old options that are unlikely to be present in any site anymore.
		 *
		 * @since 2.2.3
		 */
		'job_manager_admin_notices',
		'job_manager_installed_terms',
		'job_manager_email_employer_expiring_job',
		'job_manager_email_admin_expiring_job',
		'job_manager_email_admin_updated_job',
		'job_manager_email_admin_new_job',
		'job_manager_jobs_page_id',
		'job_manager_job_dashboard_page_id',
		'job_manager_submit_job_form_page_id',
		'job_manager_recaptcha_label',
		'job_manager_allowed_application_method',
		'job_manager_registration_role',
		'job_manager_use_standard_password_setup_email',
		'job_manager_generate_username_from_email',
		'job_manager_enable_registration',
		'job_manager_multi_job_type',
		'job_manager_enable_types',
		'job_manager_category_filter_type',
		'job_manager_enable_default_category_multiselect',
		'job_manager_enable_categories',
		'job_manager_hide_expired_content',
		'job_manager_hide_expired',
		'job_manager_hide_filled_positions',
		'job_manager_per_page',
		'job_manager_usage_tracking_enabled',
		'job_manager_google_maps_api_key',
		'job_manager_date_format',
		'widget_widget_featured_jobs',
		'widget_widget_recent_jobs',
		'options_single_listing_menu_font_size',
		'_options_single_listing_menu_font_size',
		'options_single_listing_menu_font_weight',
		'_options_single_listing_menu_font_weight',
		'options_single_listing_content_block_title_size',
		'_options_single_listing_content_block_title_size',
		'options_single_listing_content_block_title_weight',
		'_options_single_listing_content_block_title_weight',
		'options_single_listing_content_block_font_size',
		'_options_single_listing_content_block_font_size',

		/**
		 * @since ...
		 */
		//
	];

	$inline_options = join( '\',\'', $options );
	$count = $wpdb->get_var( "SELECT COUNT(option_id) FROM {$wpdb->options} WHERE option_name IN ('{$inline_options}')" );

	if ( $count > 0 ) {
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name IN ('{$inline_options}')" );
	}

	return $count > 0
		? sprintf( 'Removed %d unused options from wp_options table.', $count )
		: 'No unused options were found.';
}

function update_term_counts() {
    global $wpdb;

    // get listing taxonomies
    $taxonomies = array_merge( [ 'job_listing_category', 'case27_job_listing_tags', 'region' ], mylisting_custom_taxonomies( 'slug', 'slug' ) );
    $taxonomy_string = '\''.join( '\',\'', $taxonomies ).'\'';

    // run in batches to avoid crashing large databases
    $per_page = 400;
    $offset_page = ! empty( $_GET['offset_pg'] ) ? absint( $_GET['offset_pg'] ) : 0;
    $offset = $offset_page * $per_page;

    // get list of terms
	$terms = $wpdb->get_results( $wpdb->prepare( "
		SELECT t.term_id, tt.taxonomy FROM {$wpdb->terms} AS t
		INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
		WHERE tt.taxonomy IN ({$taxonomy_string})
		ORDER BY t.term_id ASC LIMIT %d, %d
	", $offset, $per_page ), ARRAY_A );

	// update counts for list of terms
    foreach ( $terms as $term ) {
    	\MyListing\update_term_counts( $term['term_id'], $term['taxonomy'] );
    }

    // if the term count matches the batch size, there may be more terms to process
    if ( count( $terms ) === $per_page ) {
    	wp_safe_redirect( add_query_arg( [
    		'offset_pg' => $offset_page + 1,
    		'_wpnonce' => wp_create_nonce( 'mylisting_run_updater' ),
    	], admin_url( 'admin-post.php?action=mylisting_run_updater&run=update_term_counts' ) ) );
    	die;
    }

    return sprintf( 'Successfully recounted %s listing taxonomy terms.', number_format_i18n( $offset + count( $terms ) ) );
}