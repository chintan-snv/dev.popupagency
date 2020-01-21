<?php

if ( ! defined('ABSPATH') ) {
	exit;
}

/**
 * Autoload classes.
 *
 * MyListing\Src      -> my-listing/includes/src
 * MyListing\Ext      -> my-listing/includes/extensions
 * MyListing\Int      -> my-listing/includes/integrations
 * MyListing\Utils    -> my-listing/includes/utils
 * MyListing\Includes -> my-listing/includes
 */
spl_autoload_register( function( $classname ) {
	$parts = explode( '\\', $classname );

	if ( $parts[0] !== 'MyListing' ) {
		return false;
	}

	$parts[0] = 'Includes';

	if ( $parts[1] === 'Ext' ) {
		$parts[1] = 'Extensions';
	}

	if ( $parts[1] === 'Int' ) {
		$parts[1] = 'Integrations';
	}

	$path_parts = array_map( function( $part ) {
		return strtolower( str_replace( '_', '-', $part ) );
	}, $parts );

	$path = join( DIRECTORY_SEPARATOR, $path_parts ) . '.php';

	if ( locate_template( $path ) ) {
		require_once locate_template( $path );
	}
} );

require_once locate_template( 'includes/util.php' );
require_once locate_template( 'includes/init.php' );
require_once locate_template( 'includes/plugins/activator.php' );

// @todo: Convert integrations to autoloadable namespace format.
require_once locate_template( 'includes/integrations/27collective/bookmarks/bookmark.php' );
require_once locate_template( 'includes/integrations/27collective/breadcrumbs/breadcrumbs.php' );
