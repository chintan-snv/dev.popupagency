<?php

function sppro_plugin_path( $path = '' ) {
	return path_join( SPPRO_PLUGIN_DIR, trim( $path, '/' ) );
}


/**
 * Set Plugin URL Path (SSL/non-SSL)
 * @param  string - $path
 * @return string - $url 
 * Return https or non-https URL from path
 */
function sppro_plugin_url( $path = '' ) {
	$url = plugins_url( $path, SPPRO_PLUGIN );

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

function sppro_upload_dir( $type = false ) {
	$uploads = wp_get_upload_dir();

	$uploads = apply_filters( 'sppro_upload_dir', array(
		'dir' => $uploads['basedir'],
		'url' => $uploads['baseurl'] ) );

	if ( 'dir' == $type ) {
		return $uploads['dir'];
	} if ( 'url' == $type ) {
		return $uploads['url'];
	}

	return $uploads;
}

function sppro_verify_nonce( $nonce, $action = -1 ) {
	if ( substr( wp_hash( $action, 'nonce' ), -12, 10 ) == $nonce ) {
		return true;
	}

	return false;
}

function sppro_create_nonce( $action = -1 ) {
	return substr( wp_hash( $action, 'nonce' ), -12, 10 );
}

function sppro_blacklist_check( $target ) {
	$mod_keys = trim( get_option( 'blacklist_keys' ) );

	if ( empty( $mod_keys ) ) {
		return false;
	}

	$words = explode( "\n", $mod_keys );

	foreach ( (array) $words as $word ) {
		$word = trim( $word );

		if ( empty( $word ) || 256 < strlen( $word ) ) {
			continue;
		}

		$pattern = sprintf( '#%s#i', preg_quote( $word, '#' ) );

		if ( preg_match( $pattern, $target ) ) {
			return true;
		}
	}

	return false;
}

function sppro_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, sppro_array_flatten( $value ) );
	}

	return $output;
}

function sppro_flat_join( $input ) {
	$input = sppro_array_flatten( $input );
	$output = array();

	foreach ( (array) $input as $value ) {
		$output[] = trim( (string) $value );
	}

	return implode( ', ', $output );
}

function sppro_support_html5() {
	return (bool) apply_filters( 'sppro_support_html5', true );
}

function sppro_support_html5_fallback() {
	return (bool) apply_filters( 'sppro_support_html5_fallback', false );
}

function sppro_use_really_simple_captcha() {
	return apply_filters( 'sppro_use_really_simple_captcha',
		SPPRO_USE_REALLY_SIMPLE_CAPTCHA );
}

function sppro_validate_configuration() {
	return apply_filters( 'sppro_validate_configuration',
		SPPRO_VALIDATE_CONFIGURATION );
}

function sppro_load_js() {
	return apply_filters( 'sppro_load_js', SPPRO_LOAD_JS );
}

function sppro_load_css() {
	return apply_filters( 'sppro_load_css', SPPRO_LOAD_CSS );
}

function sppro_format_atts( $atts ) {
	$html = '';

	$prioritized_atts = array( 'type', 'name', 'value' );

	foreach ( $prioritized_atts as $att ) {
		if ( isset( $atts[$att] ) ) {
			$value = trim( $atts[$att] );
			$html .= sprintf( ' %s="%s"', $att, esc_attr( $value ) );
			unset( $atts[$att] );
		}
	}

	foreach ( $atts as $key => $value ) {
		$key = strtolower( trim( $key ) );

		if ( ! preg_match( '/^[a-z_:][a-z_:.0-9-]*$/', $key ) ) {
			continue;
		}

		$value = trim( $value );

		if ( '' !== $value ) {
			$html .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}
	}

	$html = trim( $html );

	return $html;
}

function sppro_link( $url, $anchor_text, $args = '' ) {
	$defaults = array(
		'id' => '',
		'class' => '',
		'target' => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = array_intersect_key( $args, $defaults );
	$atts = sppro_format_atts( $args );

	$link = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
		esc_url( $url ),
		esc_html( $anchor_text ),
		$atts ? ( ' ' . $atts ) : '' );

	return $link;
}

function sppro_get_request_uri() {
	static $request_uri = '';

	if ( empty( $request_uri ) ) {
		$request_uri = add_query_arg( array() );
	}

	return esc_url_raw( $request_uri );
}

function sppro_register_post_types() {
	if(class_exists('SPPRO_Forms') AND ! post_type_exists('sppro_forms')) {
		SPPRO_Forms::register_post_type();
		SPPRO_Forms::create_default_popups();
		return true;
	}
}

function sppro_version( $args = '' ) {
	$defaults = array(
		'limit' => -1,
		'only_major' => false );

	$args = wp_parse_args( $args, $defaults );

	if ( $args['only_major'] ) {
		$args['limit'] = 2;
	}

	$args['limit'] = (int) $args['limit'];

	$ver = SPPRO_VERSION;
	$ver = strtr( $ver, '_-+', '...' );
	$ver = preg_replace( '/[^0-9.]+/', ".$0.", $ver );
	$ver = preg_replace( '/[.]+/', ".", $ver );
	$ver = trim( $ver, '.' );
	$ver = explode( '.', $ver );

	if ( -1 < $args['limit'] ) {
		$ver = array_slice( $ver, 0, $args['limit'] );
	}

	$ver = implode( '.', $ver );

	return $ver;
}

function sppro_version_grep( $version, array $input ) {
	$pattern = '/^' . preg_quote( (string) $version, '/' ) . '(?:\.|$)/';

	return preg_grep( $pattern, $input );
}

function sppro_enctype_value( $enctype ) {
	$enctype = trim( $enctype );

	if ( empty( $enctype ) ) {
		return '';
	}

	$valid_enctypes = array(
		'application/x-www-form-urlencoded',
		'multipart/form-data',
		'text/plain',
	);

	if ( in_array( $enctype, $valid_enctypes ) ) {
		return $enctype;
	}

	$pattern = '%^enctype="(' . implode( '|', $valid_enctypes ) . ')"$%';

	if ( preg_match( $pattern, $enctype, $matches ) ) {
		return $matches[1]; // for back-compat
	}

	return '';
}

function sppro_rmdir_p( $dir ) {
	if ( is_file( $dir ) ) {
		if ( ! $result = @unlink( $dir ) ) {
			$stat = @stat( $dir );
			$perms = $stat['mode'];
			@chmod( $dir, $perms | 0200 ); // add write for owner

			if ( ! $result = @unlink( $dir ) ) {
				@chmod( $dir, $perms );
			}
		}

		return $result;
	}

	if ( ! is_dir( $dir ) ) {
		return false;
	}

	if ( $handle = @opendir( $dir ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file == "." || $file == ".." ) {
				continue;
			}

			sppro_rmdir_p( path_join( $dir, $file ) );
		}

		closedir( $handle );
	}

	return @rmdir( $dir );
}

/* From _http_build_query in wp-includes/functions.php */
function sppro_build_query( $args, $key = '' ) {
	$sep = '&';
	$ret = array();

	foreach ( (array) $args as $k => $v ) {
		$k = urlencode( $k );

		if ( ! empty( $key ) ) {
			$k = $key . '%5B' . $k . '%5D';
		}

		if ( null === $v ) {
			continue;
		} elseif ( false === $v ) {
			$v = '0';
		}

		if ( is_array( $v ) || is_object( $v ) ) {
			array_push( $ret, sppro_build_query( $v, $k ) );
		} else {
			array_push( $ret, $k . '=' . urlencode( $v ) );
		}
	}

	return implode( $sep, $ret );
}

/**
 * Returns the number of code units in a string.
 *
 * @see http://www.w3.org/TR/html5/infrastructure.html#code-unit-length
 *
 * @return int|bool The number of code units, or false if mb_convert_encoding is not available.
 */
function sppro_count_code_units( $string ) {
	static $use_mb = null;

	if ( is_null( $use_mb ) ) {
		$use_mb = function_exists( 'mb_convert_encoding' );
	}

	if ( ! $use_mb ) {
		return false;
	}

	$string = (string) $string;

	$encoding = mb_detect_encoding( $string, mb_detect_order(), true );

	if ( $encoding ) {
		$string = mb_convert_encoding( $string, 'UTF-16', $encoding );
	} else {
		$string = mb_convert_encoding( $string, 'UTF-16', 'UTF-8' );
	}

	$byte_count = mb_strlen( $string, '8bit' );

	return floor( $byte_count / 2 );
}

function sppro_is_localhost() {
	$server_name = strtolower( $_SERVER['SERVER_NAME'] );
	return in_array( $server_name, array( 'localhost', '127.0.0.1' ) );
}

function sppro_deprecated_function( $function, $version, $replacement ) {
	$trigger_error = apply_filters( 'deprecated_function_trigger_error', true );

	if ( WP_DEBUG && $trigger_error ) {
		if ( function_exists( '__' ) ) {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Slick Popup Version version %2$s! Use %3$s instead.', 'sp-pro-txt-domain' ), $function, $version, $replacement ) );
		} else {
			trigger_error( sprintf( '%1$s is <strong>deprecated</strong> since Slick Popup Version version %2$s! Use %3$s instead.', $function, $version, $replacement ) );
		}
	}
}
