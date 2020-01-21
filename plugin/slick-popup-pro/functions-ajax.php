<?php

// Enable the user with no privileges to run sppro_login_function() in AJAX
add_action( 'wp_ajax_sppro_update_insights', 'sppro_update_insights' );
add_action( 'wp_ajax_nopriv_sppro_update_insights', 'sppro_update_insights' );		
function sppro_update_insights(){
	
	// First check the nonce, if it fails the function will break
    // check_ajax_referer( 'sppro-update-insights', 'login_security' );

    // Nonce is checked, get the POST data and sign user on
    $form_id = $_POST['form_id']; 
    $toupdate = $_POST['toupdate']; 
	
	// wp_die(print_r($_POST)); 

	
	
	if(! $insights = get_post_meta($form_id, 'popup_insights', true)) {
		$insights = array(
			'loaded' => 0, 
			'opened' => 0, 
			'submitted' => 0, 
		);
	}

	$insights[$toupdate] = $insights[$toupdate] + 1; 
	
	if(update_post_meta($form_id, 'popup_insights', $insights)) {
	   wp_send_json_success(array('updated'=>true, 'message'=>__('Successfully updated.'), 'insights'=>$insights, 'posted'=>$_POST));
    } else {
        wp_send_json_error(array('updated'=>false, 'message'=>__('Could not update.'), 'insights'=>$insights, 'posted'=>$_POST));
    }

    die();
}

// Enable the user with no privileges to run sppro_login_function() in AJAX
add_action( 'wp_ajax_sppro_login_function', 'sppro_login_function' );
add_action( 'wp_ajax_nopriv_sppro_login_function', 'sppro_login_function' );		
function sppro_login_function(){
	
	// First check the nonce, if it fails the function will break
    check_ajax_referer( 'sppro-login-nonce', 'login_security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if(is_wp_error($user_signon)) {
        echo json_encode(array('loggedin'=>false, 'message'=>__('<p class="error">Wrong username or password.</p>')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('<p class="success">Login successful, redirecting...</p>'), 'redirect'=>$_POST['_wp_http_referer']));
    }

    die();
}

add_action( 'wp_ajax_nopriv_lost_pass_callback', 'lost_pass_callback' );
add_action( 'wp_ajax_lost_pass_callback', 'lost_pass_callback' );
/*
 *	@desc	Process lost password
 */
function lost_pass_callback() {
	
	// First check the nonce, if it fails the function will break
    check_ajax_referer( 'sppro-lostpassword-nonce', 'lostpassword_security' );
	
	global $wpdb, $wp_hasher;
	
	$user_login = sanitize_text_field($_POST['user_name']);
	$ajaxy = array(); 

	$errors = new WP_Error();

	if ( empty( $user_login ) ) {
		$errors->add('empty_user_login', __('<p class="error"><strong>ERROR</strong>: Enter a username or e-mail address.</p>'));
	} else if ( strpos( $user_login, '@' ) ) {
		$user_data = get_user_by( 'email', trim( $user_login ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<p class="error"><strong>ERROR</strong>: There is no user registered with that email address.</p>'));
	} else {
		$login = trim( $user_login );
		$user_data = get_user_by('login', $login);
	}
	
	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );
	
	//die(var_dump($errors));	
	if ( $errors->get_error_code() ) {
		$ajaxy['message'] = $errors->get_error_message();
		die(wp_send_json_error($ajaxy));
	}
	
	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<p class="error"><strong>ERROR</strong>: Invalid username or email.</p>'));
		$ajaxy['message'] = $errors->get_error_message();
		die(wp_send_json_error($ajaxy));
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key = get_password_reset_key($user_data);
	
	if ( is_wp_error( $key ) ) {
		die($key->get_error_message());
	}

	$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
	$message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

	// replace PAGE_ID with reset page ID
	$array = array(
		'action' => 'rp',
		'key' => $key,
		'login' => rawurlencode($user_login),
	);
	
	$message .= add_query_arg($array, $_SERVER['php_self']);	

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		/*
		 * The blogname option is escaped with esc_html on the way into the database
		 * in sanitize_option we want to reverse this for the plain text arena of emails.
		 */
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset'), $blogname );

	/**
	 * Filter the subject of the password reset email.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $title      Default email title.
	 * @param string  $user_login The user_login for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	/**
	 * Filter the message body of the password reset mail.
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The user_login for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	if ( wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
		$errors->add('confirm', __('<p class="success">Check your e-mail for the confirmation link.</p>'), 'message');
	else
		$errors->add('could_not_sent', __('<p class="error">The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.</p>'), 'message');


	// display error message
	if ( $errors->get_error_message() ) {
		$ajaxy['message'] =  $errors->get_error_message( $errors->get_error_code() );
		die(wp_send_json_error($ajaxy));
	}
	
	// return proper result
	die();
}