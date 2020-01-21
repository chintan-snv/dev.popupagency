<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if (isset($_POST['niteoCS_subscribe_type']) && is_numeric($_POST['niteoCS_subscribe_type'])) {
	update_option('niteoCS_subscribe_type', sanitize_text_field($_POST['niteoCS_subscribe_type']));
}

if (isset($_POST['niteoCS_subscribe_label']))  {
	update_option('niteoCS_subscribe_label', $this->niteo_sanitize_html($_POST['niteoCS_subscribe_label']));
}

if ( isset($_POST['niteoCS_subscribe_code']) ) {
	$shortcode = str_replace('"', '\'', $_POST['niteoCS_subscribe_code']);
	update_option('niteoCS_subscribe_code', sanitize_text_field($shortcode));
} 

if (isset($_POST['niteoCS_subscribe_method']))  {
	update_option('niteoCS_subscribe_method', sanitize_text_field($_POST['niteoCS_subscribe_method']));
}

if (isset($_POST['niteoCS_mailchimp_apikey']))  {
	update_option('niteoCS_mailchimp_apikey', sanitize_text_field($_POST['niteoCS_mailchimp_apikey']));
}

if (isset($_POST['niteoCS_mailchimp_list_selected']))  {
	update_option('niteoCS_mailchimp_list_selected', sanitize_text_field($_POST['niteoCS_mailchimp_list_selected']));
}

if (isset($_POST['niteoCS_mailpoet_list_selected']))  {
	update_option('niteoCS_mailpoet_list_selected', sanitize_text_field($_POST['niteoCS_mailpoet_list_selected']));
}

if (isset($_POST['niteoCS_mailster_list_selected']))  {
	update_option('niteoCS_mailster_list_selected', sanitize_text_field($_POST['niteoCS_mailster_list_selected']));
}

if (isset($_POST['niteoCS_mailoptin_selected']))  {
	update_option('niteoCS_mailoptin_selected', sanitize_text_field($_POST['niteoCS_mailoptin_selected']));
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	if ( isset($_POST['niteoCS_mailchimp_double_opt']) ) {
		update_option('niteoCS_mailchimp[double-opt]', $this->sanitize_checkbox($_POST['niteoCS_mailchimp_double_opt']));
	} else {
		update_option('niteoCS_mailchimp[double-opt]', false);
	}

	if ( isset($_POST['niteoCS_mailster_double_opt']) ) {
		update_option('niteoCS_mailster_double_opt', $this->sanitize_checkbox($_POST['niteoCS_mailster_double_opt']));
	} else {
		update_option('niteoCS_mailster_double_opt', '0');
	}
}


// delete_option('niteoCS_mailchimp_lists');

// get subscribe settings
$niteoCS_subscribe_type 	= get_option('niteoCS_subscribe_type', '2');
$niteoCS_subscribe_code 	= get_option('niteoCS_subscribe_code');
$niteoCS_subscribe_label	= stripslashes(get_option('niteoCS_subscribe_label'));
$niteoCS_subscribers_list 	= get_option('niteoCS_subscribers_list');

$subscribe_method 			= get_option('niteoCS_subscribe_method', 'cmp');
$mailchimp_apikey 			= get_option('niteoCS_mailchimp_apikey', '');
$mailchimp_list_selected	= get_option('niteoCS_mailchimp_list_selected');
$mailchimp_lists 			= json_decode(get_option('niteoCS_mailchimp_lists', false), true);
$mailpoet_list_selected		= get_option('niteoCS_mailpoet_list_selected');
$mailster_list_selected		= get_option('niteoCS_mailster_list_selected');
$mailoptin_selected			= get_option('niteoCS_mailoptin_selected');
?>

<div class="table-wrapper content" id="subscribe-section">
	<h3><?php _e('Subscribe Form', 'cmp-coming-soon-maintenance');?></h3>
	<table class="content">
	<tbody>
	<tr>
		<th>
			<fieldset>
				<legend class="screen-reader-text">
					<span><?php _e('Subscribe Form Options', 'cmp-coming-soon-maintenance');?></span>
				</legend>

				<p>
					<label title="CMP Subscribe Form">
					 	<input type="radio" class="subscribe" name="niteoCS_subscribe_type" value="2"<?php checked($niteoCS_subscribe_type, 2);?>>&nbsp;<?php _e('CMP Subscribe Form', 'cmp-coming-soon-maintenance');?>
					</label>
				</p>

				<p>
					<label title="MailOptin">
					 	<input type="radio" class="subscribe" name="niteoCS_subscribe_type" value="3"<?php checked($niteoCS_subscribe_type, 3);?>>&nbsp;<?php _e('MailOptin Integration', 'cmp-coming-soon-maintenance');?>
					</label>
				</p>

				<p>
					<label title="3rd Party Plugin">
					 	<input type="radio" class="subscribe" name="niteoCS_subscribe_type" value="1"<?php checked($niteoCS_subscribe_type, 1);?>>&nbsp;<?php _e('3rd Party Plugin', 'cmp-coming-soon-maintenance');?>
					</label>
				</p>

				<p>
					<label title="Disabled">
					 	<input type="radio" class="subscribe" name="niteoCS_subscribe_type" value="0"<?php checked($niteoCS_subscribe_type, 0);?>>&nbsp;<?php _e('Disabled', 'cmp-coming-soon-maintenance');?>
					</label>
				</p>

			</fieldset>
		</th>

		<td id="subscribe-disabled" class="subscribe-switch x0">
			<p><?php _e('Subscribe Form is disabled.', 'cmp-coming-soon-maintenance');?></p>
		</td>

		<?php if ( $this->cmp_selectedTheme() == 'agency' ): ?>

		<?php 
		if ( isset($_POST['niteoCS_subscribe_title']) ) {
			update_option('niteoCS_subscribe_title', esc_html( $_POST['niteoCS_subscribe_title']) );
		}

		$niteoCS_subscribe_title 	= stripslashes( get_option('niteoCS_subscribe_title', 'SUBSCRIBE US') );
		?>
		<td  class="subscribe-switch x1 x2">
			<label for="niteoCS_subscribe_title"><?php _e('Subcribe Form Title', 'cmp-coming-soon-maintenance');?>
				<input type="text" name="niteoCS_subscribe_title" id="niteoCS_subscribe_title" value="<?php echo stripslashes( esc_attr($niteoCS_subscribe_title ));?>" class="regular-text code">
			</label>
		</td>
		<?php endif; ?>

		<td id="subscribe-3rdparty" class="subscribe-switch x1">
			<fieldset>
				<label class="subscribe" for="niteoCS_subscribe_code"><?php _e('3rd Party Plugin Shortcode', 'cmp-coming-soon-maintenance');?>
					<input type="text" name="niteoCS_subscribe_code" id="niteoCS_subscribe_code" value="<?php echo stripslashes( esc_attr($niteoCS_subscribe_code ));?>" class="regular-text code">
				</label>

				<p><?php _e('You can find Shortode in your Contact Form Plugin settings. Should be something similar to code below: ', 'cmp-coming-soon-maintenance');?><br><code>[contact-form-7 id="8" title='Contact form 1']</code> or <code>[mc4wp_form id='7']</code></p>

				<p><?php _e('If you use 3rd party shortcode for subscribe or contact form, you might need to apply custom CSS to style the form correctly.', 'cmp-coming-soon-maintenance');?></p>
			</fieldset>
		</td>

		<td id="subscribe-niteo" class="subscribe-switch x2">
			<fieldset>
				<p><?php _e('CMP custom subscribe form will be used. It is guaranteed to always match selected Theme\'s style.', 'cmp-coming-soon-maintenance');?></p>
			
				<label class="subscribe" for="niteoCS_subscribe_label"><?php _e('Subscribe Form message (can be GDPR or any custom message). Supports anchor HTML tags.', 'cmp-coming-soon-maintenance');?>
					<textarea name="niteoCS_subscribe_label" id="niteoCS_subscribe_label" class="regular-text code" placeholder="<?php _e('Leave empty to disable', 'cmp-coming-soon-maintenance');?>"><?php echo esc_attr( $niteoCS_subscribe_label );?></textarea>
				</label><br><br>

				<label for="niteoCS_subscribe_method"><?php _e('Select how to save your Subscribers', 'cmp-coming-soon-maintenance');?>
					<select name="niteoCS_subscribe_method" id ="niteoCS_subscribe_method" class="subscribe-method">
						<option value="cmp" <?php selected( 'cmp', $subscribe_method ); ?>><?php _e('CMP Custom Subscribe List', 'cmp-coming-soon-maintenance');?></option>
						<option value="mailchimp" <?php selected( 'mailchimp', $subscribe_method ); ?>><?php _e('MailChimp Integration', 'cmp-coming-soon-maintenance');?></option>
						<option value="mailpoet" <?php selected( 'mailpoet', $subscribe_method ); ?>><?php _e('MailPoet Integration', 'cmp-coming-soon-maintenance');?></option>
						<option value="mailster" <?php selected( 'mailster', $subscribe_method ); ?>><?php _e('Mailster Integration', 'cmp-coming-soon-maintenance');?></option>
					</select>
				</label>

				<div class="subscribe-method cmp">
					<p><?php _e( 'Emails will be stored in custom CMP list with CSV export support. If you ever delete CMP plugin then subscriber list will be purged as well.', 'cmp-coming-soon-maintenance' );?></p>
					<p><?php _e( 'Total Subscribers: ', 'cmp-coming-soon-maintenance' );?><a href="<?php echo admin_url(); ?>admin.php?page=cmp-subscribers"><?php echo $niteoCS_subscribers_list ? count( $niteoCS_subscribers_list ) : '0';?></a></p>
				</div>

				<div class="subscribe-method mailchimp">
					<br>
					<label for="niteoCS_mailchimp_apikey"><?php _e('MailChimp API key', 'cmp-coming-soon-maintenance');?>
						<input type="text" id="niteoCS_mailchimp_apikey" name="niteoCS_mailchimp_apikey" value="<?php echo esc_attr( $mailchimp_apikey );?>" class="regular-text code" placeholder="<?php _e('MailChimp API Key', 'cmp-coming-soon-maintenance');?>">
					</label><br><br>
					<button id="connect-mailchimp" class="button" data-security="<?php echo esc_attr($ajax_nonce);?>"><?php _e('Retrieve Lists', 'cmp-coming-soon-maintenance');?></button>
					<p><?php printf(__('You can find or create new API key in your %s.', 'cmp-coming-soon-maintenance'), '<a href="https://admin.mailchimp.com/account/api/" target="_blank">MailChimp Account</a>'); ?></p>
					<label for="niteoCS_mailchimp_list"><?php _e('Select MailChimp subscriber`s list.', 'cmp-coming-soon-maintenance');?>
						<select name="niteoCS_mailchimp_list_selected" id="niteoCS_mailchimp_list">
							<?php 
							if ( is_array( $mailchimp_lists ) ) {

								if ( $mailchimp_lists['response'] == 200 ) {
									foreach ( $mailchimp_lists['lists'] as $list) { ?>
										<option value="<?php echo esc_attr( $list['id'] );?>" <?php selected( $list['id'], $mailchimp_list_selected ); ?>><?php echo esc_attr( $list['name'] );?></option>
										<?php 
									}
								} else { ?>
									<option value="error"><?php echo esc_attr( $mailchimp_lists['response'] . ': ' . $mailchimp_lists['message']);?></option>
									<?php 
								}

							} else { ?>
								<option value="error"><?php _e('Please insert MailChimp API key to retrieve Lists.', 'cmp-coming-soon-maintenance');?></option>
								<?php 
							} ?>
						</select>
					</label>
					<br><br>
					<label><input type="checkbox" name="niteoCS_mailchimp_double_opt" value="1" <?php checked( '1', get_option( 'niteoCS_mailchimp[double-opt]', '0' ) ); ?> class="regular-text code"><?php _e('Enable MailChimp Double Opt In', 'cmp-coming-soon-maintenance');?></label>
				</div>

				<div class="subscribe-method mailpoet">
					<?php 
					$mailpoet_lists = null;
					// get MailPoet Lists
					if (class_exists(\MailPoet\API\API::class)) {
						// Get MailPoet API instance
						$mailpoet_api = \MailPoet\API\API::MP('v1');
						// Get available list so that a subscriber can choose in which to subscribe 
						$mailpoet_lists = $mailpoet_api->getLists();
					} ?>

					<br>

					<label for="niteoCS_mailpoet_list"><?php _e('Select MailPoet subscriber`s list.', 'cmp-coming-soon-maintenance');?>
						<select name="niteoCS_mailpoet_list_selected" id="niteoCS_mailpoet_list">
							<?php 
							if ( is_array( $mailpoet_lists ) ) {
								foreach ( $mailpoet_lists as $list) { ?>
									<option value="<?php echo esc_attr( $list['id'] );?>" <?php selected( $list['id'], $mailpoet_list_selected ); ?>><?php echo esc_attr( $list['name'] );?></option>
									<?php 
								}

							} else { ?>
								<option value="error"><?php _e('Could not retrieve MailPoet subcribers lists. Please make sure MailPoet is correctly installed and configured.', 'cmp-coming-soon-maintenance');?></option>
								<?php 
							} ?>
						</select>
					</label>
				</div>

				<div class="subscribe-method mailster">

					<?php 
					$mailster_lists = null;
					// get Mailster Lists
					if ( function_exists( 'mailster' ) ){
						// Get available list so that a subscriber can choose in which to subscribe 
						$mailster_lists = mailster( 'lists' )->get();
					} ?>

					<br>

					<label for="niteoCS_mailster_list"><?php _e('Select Mailster subscriber`s list.', 'cmp-coming-soon-maintenance');?>
						<select name="niteoCS_mailster_list_selected" id="niteoCS_mailster_list">
							<?php 
							if ( is_array( $mailster_lists ) ) {
								foreach ( $mailster_lists as $list) { ?>
									<option value="<?php echo esc_attr( $list->ID );?>" <?php selected( $list->ID, $mailster_list_selected ); ?>><?php echo esc_attr( $list->name );?></option>
									<?php 
								}
							} else { ?>
								<option value="error"><?php _e('Could not retrieve Mailster subribers lists. Please make sure Mailster is correctly installed and configured.', 'cmp-coming-soon-maintenance');?></option>
								<?php 
							} ?>
						</select>
					</label>
					<br><br>
					<label><input type="checkbox" name="niteoCS_mailster_double_opt" value="1" <?php checked( '1', get_option( 'niteoCS_mailster_double_opt', '1' ) ); ?> class="regular-text code"><?php _e('Enable Mailster Double Opt In', 'cmp-coming-soon-maintenance');?></label>

				</div>

				<p class="cmp-hint"><?php echo sprintf( __('* Did you know you can translate Subscribe form Labels and Placeholders to your language in %s Settings?', 'cmp-coming-soon-maintenance'), '<a href="' . get_admin_url() . 'admin.php?page=cmp-translate">Translation</a>');?></p>

			</fieldset>
		</td>

		<td id="subscribe-niteo" class="subscribe-switch x3">

			<p><?php _e('MailOptin is a proven list building plugin that allows you to create popups and embedded forms so you can convert visitors to your coming soon site into subscribers. Supports all major email providers including. MailChimp, AWeber, Campaign Monitor, Constant Contact, Mailerlite.', 'cmp-coming-soon-maintenance'); ?></p>
			<?php 

				if ( file_exists( WP_PLUGIN_DIR . '/mailoptin/mailoptin.php' ) ) {
	
					if ( is_plugin_active('mailoptin/mailoptin.php') ) { 
						$optins = MailOptin\Core\Repositories\OptinCampaignsRepository::get_optin_campaigns();
						?>
						<fieldset>
							<label for="niteoCS_mailoptin_list"><?php _e('Select MailOptin Campaigns.', 'cmp-coming-soon-maintenance');?>
								<select name="niteoCS_mailoptin_selected" id="niteoCS_mailoptin_list">
									<?php 
									if ( !empty( $optins ) ) {
										foreach ( $optins as $optin) { ?>
											<option value="<?php echo esc_attr( $optin['id'] );?>" <?php selected( $optin['id'], $mailoptin_selected ); ?>><?php echo esc_attr( $optin['name'] );?></option>
											<?php 
										}
									} else { ?>
										<option value="error"><?php _e('No MailOptin Campaign was found. Please make there is at least one Optin Campaign created.', 'cmp-coming-soon-maintenance');?></option>
										<?php 
									} ?>?>
								</select>
							</label>
						</fieldset>
						<?php 
					} else { 
						$activate_url = wp_nonce_url(admin_url('plugins.php?action=activate&plugin=mailoptin%2Fmailoptin.php'), 'activate-plugin_mailoptin/mailoptin.php');
						?>
						<p><?php _e('Please activate MailOptin plugin first by clicking button below.');?></p>
						<a href="<?php echo $activate_url;?>" class="button" target="_blank"><?php _e('Activate MailOptin plugin', 'cmp-coming-soon-maintenance');?></a>
						<?php 
					} 

				} else {
					$install_url = wp_nonce_url(
						admin_url('update.php?action=install-plugin&plugin=mailoptin'),
						'install-plugin_mailoptin'
					)
					?>
					<p><?php _e('Please Install MailOptin plugin first by clicking button below.');?></p>
					<a href="<?php echo $install_url;?>" class="button" target="_blank"><?php _e('Install MailOptin plugin', 'cmp-coming-soon-maintenance');?></a>
					<?php 
				} ?>
		</td>

	</tr>

	<?php echo $this->render_settings->submit(); ?>
	
	</tbody>
	</table>
</div>