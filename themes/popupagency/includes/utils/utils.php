<?php

namespace MyListing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the nearest thousands to a given number and create a string from that.
 * For example:
 * 515 => "1-1000"
 * 2440 => "2001-3000"
 * 10000 => "9999-10000"
 *
 * @since 2.2.3
 */
function nearest_thousands( $number ) {
	// numbers like 0, 1000, 2000, etc. should be included in the previous thousands group
	if ( $number % 1000 === 0 ) {
		$number -= 1;
	}

	// calculate upper and lower thousands
	$up = (int) ( 1000 * ceil( $number / 1000 ) );
	$down = ( (int) ( 1000 * floor( $number / 1000 ) ) ) + 1;

	return "{$down}-{$up}";
}

/**
 * Basic HTML minification.
 *
 * @since 2.2.3
 */
function minify_html( $content ) {
    $search = [
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    ];

    $replace = [ '>', '<', '\\1', '' ];
    $content = preg_replace( $search, $replace, $content );
    return $content;
}

/**
* Converts shorthand memory notation value to bytes
* From http://php.net/manual/en/function.ini-get.php
*
* @param $size_str Memory size shorthand notation string e.g. 256M
* @since 2.2.3
*/
function return_bytes( $size_str ) {
    switch ( substr( $size_str, -1 ) ) {
        case 'M': case 'm': return (int) $size_str * 1048576;
        case 'K': case 'k': return (int) $size_str * 1024;
        case 'G': case 'g': return (int) $size_str * 1073741824;
        default: return $size_str;
    }
}

/**
 * Get taxonomy version (updated every time one of it's terms changes),
 * to be used for caching purposes.
 *
 * @since 2.2.3
 */
function get_taxonomy_versions( $taxonomy = null ) {
	$versions = (array) json_decode( get_option( 'mylisting_taxonomy_versions', null ), ARRAY_A );
	if ( ! empty( $taxonomy ) ) {
		return isset( $versions[ $taxonomy ] ) ? $versions[ $taxonomy ] : 0;
	}

	return $versions;
}

/**
 * Update listings counts for the given term.
 *
 * @since 2.2.3
 */
function update_term_counts( $term_id, $taxonomy ) {
	global $wpdb;

	// get all child terms
	$ids = get_term_children( $term_id, $taxonomy );
	if ( is_wp_error( $ids ) ) {
		return;
	}

	// append the term id to it's child terms, and concatenate to use in db query
	$ids[] = $term_id;
	$ids = join( ',', $ids );

	$results = $wpdb->get_results( "
		SELECT mt1.meta_value AS listing_type, COUNT(DISTINCT {$wpdb->posts}.ID) AS count FROM {$wpdb->posts}
		LEFT JOIN {$wpdb->postmeta} AS mt1 ON ( mt1.meta_key = '_case27_listing_type' AND {$wpdb->posts}.ID = mt1.post_id )
		LEFT JOIN {$wpdb->term_relationships} ON ( {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id )
		WHERE post_type = 'job_listing'
		    AND post_status = 'publish'
		    AND {$wpdb->term_relationships}.term_taxonomy_id IN({$ids})
		GROUP BY mt1.meta_value
	", ARRAY_A );

	$counts = [];
	foreach ( $results as $group ) {
		if ( ! is_numeric( $group['count'] ) || $group['count'] < 1 ) {
			continue;
		}

		if ( ! isset( $counts[ $group['listing_type'] ] ) ) {
			$counts[ $group['listing_type'] ] = 0;
		}

		$counts[ $group['listing_type'] ] += $group['count'];
	}

	if ( ! empty( $counts ) ) {
		update_term_meta( $term_id, 'listings_full_count', wp_json_encode( $counts ) );
	} else {
		delete_term_meta( $term_id, 'listings_full_count' );
	}
}

/**
 * Return the preview card markup for the requested listing. If preview cache
 * is enabled and this listing isn't cached, then this will create the cache.
 *
 * @since 2.2.3
 */
function get_preview_card( $listing_id ) {
	$listing_id = absint( $listing_id );
	if ( ! $listing_id ) {
		return;
	}

	$cache_enabled = (bool) get_option( 'mylisting_cache_previews' );

	// if cache is not enabled, load the card as previously
	if ( ! $cache_enabled ) {
		$listing = \MyListing\Src\Listing::get( $listing_id );
		ob_start();
		mylisting_locate_template( 'partials/listing-preview.php', [
			'listing' => $listing ? $listing->get_data() : null,
		] );
		return ob_get_clean();
	}

	// check if preview card cache exists
	$dir = trailingslashit( wp_upload_dir()['basedir'] ).'preview-cards/'.\MyListing\nearest_thousands( $listing_id );
	$filepath = trailingslashit( $dir ).$listing_id.'.html';
	if ( file_exists( $filepath ) ) {
		return apply_filters( 'mylisting/get-preview-card-cache', file_get_contents( $filepath ), $listing_id );
	}

	// cache not available, re-generate
	return \MyListing\cache_preview_card( $listing_id );
}

/**
 * Create or overwrite the preview card cache for the requested listing.
 *
 * @since 2.2.3
 */
function cache_preview_card( $listing_id ) {
	$listing_id = absint( $listing_id );
	$listing = \MyListing\Src\Listing::get( $listing_id );
	$cache_enabled = (bool) get_option( 'mylisting_cache_previews' );
	if ( ! ( $listing && $cache_enabled ) ) {
		return;
	}

	$dir = trailingslashit( wp_upload_dir()['basedir'] ).'preview-cards/'.\MyListing\nearest_thousands( $listing_id );
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}

	ob_start();
	mylisting_locate_template( 'partials/listing-preview.php', [
		'listing' => $listing->get_data(),
		'is_caching' => true,
	] );
	$content = ob_get_clean();

	$fp = fopen( trailingslashit( $dir ).$listing_id.'.html', 'wb' );
	fwrite( $fp, \MyListing\minify_html( $content ) );
	fclose( $fp );

	return $content;
}

function delete_cached_preview_card( $listing_id ) {
	$dir = trailingslashit( wp_upload_dir()['basedir'] ).'preview-cards/'.\MyListing\nearest_thousands( $listing_id );
	$filepath = trailingslashit( $dir ).$listing_id.'.html';
	if ( file_exists( $filepath ) ) {
		@unlink( $filepath );
	}
}

function delete_directory( $target ) {
    if ( is_dir( $target ) ) {
        $files = glob( $target . '*', GLOB_MARK );
        foreach( $files as $file ) {
            delete_directory( $file );
        }

        @rmdir( $target );
    } elseif ( is_file( $target ) ) {
        @unlink( $target );
    }
}