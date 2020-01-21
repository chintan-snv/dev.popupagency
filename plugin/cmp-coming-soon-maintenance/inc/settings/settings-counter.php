<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( isset($_POST['niteoCS_counter']) && is_numeric( $_POST['niteoCS_counter'] )) {
	update_option('niteoCS_counter', sanitize_text_field( $_POST['niteoCS_counter'] ));
}

if ( isset($_POST['niteoCS_counter_date']) ) {
	update_option('niteoCS_counter_date', sanitize_text_field( $_POST['niteoCS_counter_date'] ));

	if ( $_POST['niteoCS_counter_date'] >  time() ) {
		delete_transient( 'cmp_counter_email' );
	}
}

if (isset($_POST['niteoCS_countdown_action'])) {
	update_option('niteoCS_countdown_action', sanitize_text_field( $_POST['niteoCS_countdown_action'] ));
}

if (isset($_POST['niteoCS_countdown_redirect'])) {
	update_option('niteoCS_countdown_redirect', esc_url_raw( $_POST['niteoCS_countdown_redirect'] ));
}

if (isset($_POST['niteoCS_counter_heading'])) {
	update_option('niteoCS_counter_heading', sanitize_text_field( $_POST['niteoCS_counter_heading'] ));
}

// register and enqueue admin needed scripts
wp_enqueue_script('countdown_flatpicker_js');	
wp_enqueue_style( 'countdown_flatpicker_css');

// get counter settings
$niteoCS_counter			= get_option('niteoCS_counter', '1');
$niteoCS_counter_date		= get_option('niteoCS_counter_date', time() + 86400);
$niteoCS_countdown_action	= get_option('niteoCS_countdown_action', 'no-action');
$niteoCS_countdown_redirect	= get_option('niteoCS_countdown_redirect');
$niteoCS_counter_heading 	= get_option('niteoCS_counter_heading', 'STAY TUNED, WE ARE LAUNCHING SOON...');

?>


<div class="table-wrapper content">
	<h3><?php _e('Countdown Timer Setup', 'cmp-coming-soon-maintenance');?></h3>
	<table class="content">
		<tr>
			<th>
				<fieldset>
					<legend class="screen-reader-text">
						<span><?php _e('Counter setup', 'cmp-coming-soon-maintenance');?></span>
					</legend>

					<p>
						<label title="Enabled">
						 	<input type="radio" name="niteoCS_counter" class="counter" value="1"<?php if ( $niteoCS_counter == 1 ) { echo ' checked="checked"'; } ?>>&nbsp;<?php _e('Enabled', 'cmp-coming-soon-maintenance');?>
						</label>
					</p>

					<p>
						<label title="Disabled">
						 	<input type="radio" name="niteoCS_counter" class="counter" value="0"<?php if ( $niteoCS_counter == 0 ) { echo ' checked="checked"'; } ?>>&nbsp;<?php _e('Disabled', 'cmp-coming-soon-maintenance');?>
						</label>
					</p>

				</fieldset>
			</th>

			<td id="counter-disabled" class="counter-switch x0">
				<p><?php _e('Countdown Timer is disabled.', 'cmp-coming-soon-maintenance');?></p>
			</td>

			<td id="counter-enabled" class="counter-switch x1">

				<?php if ( $this->cmp_selectedTheme() == 'eclipse' || ( isset( $theme_supports['counter_title'] ) && $theme_supports['counter_title']  ) ) :
					// heading used in Eclipse theme ?>
					<fieldset>
						<label for="niteoCS_counter_heading"><?php _e('Counter Heading', 'cmp-coming-soon-maintenance');?>
							<input type="text" id="niteoCS_counter_heading" name="niteoCS_counter_heading" value="<?php echo esc_attr( $niteoCS_counter_heading ); ?>" class="regular-text code"><br>
						</label>
					</fieldset>
				<?php endif;?>

				<p><?php _e('Click on date input and set a date & time for a Countdown timer.', 'cmp-coming-soon-maintenance');?></p>
				<fieldset>

					<legend class="screen-reader-text">
						<span><?php _e('Counter setup', 'cmp-coming-soon-maintenance');?></span>
					</legend>

					<input type="text" name="niteoCS_counter_date" id="niteoCS_counter_date" placeholder="<?php _e('Select Date..','cmp-coming-soon-maintenance');?>" value="<?php echo esc_attr( $niteoCS_counter_date); ?>" class="regular-text code"><br>
					
					<label for="niteoCS_countdown_action"><?php _e('Countdown action:', 'cmp-coming-soon-maintenance');?></label>

					<select name="niteoCS_countdown_action" id="niteoCS_countdown_action" class="counter-action">

						<option value="no-action" <?php if ( $niteoCS_countdown_action == 'no-action' ) { echo ' selected="selected"'; } ?>><?php _e('No action', 'cmp-coming-soon-maintenance');?></option>
						<option value="disable-cmp" <?php if ( $niteoCS_countdown_action == 'disable-cmp' ) { echo ' selected="selected"'; } ?>><?php _e('Disable CMP Plugin and Display my WordPress Theme', 'cmp-coming-soon-maintenance');?></option>
					 	<option value="redirect" <?php if ( $niteoCS_countdown_action == 'redirect' ) { echo ' selected="selected"'; } ?>><?php _e('URL redirect', 'cmp-coming-soon-maintenance');?></option>

					</select>

					<span class="cmp-hint">* <?php echo sprintf(__('Notification Email will be sent to %s email address once counter expires.', 'cmp-coming-soon-maintenance'), '('. get_option( 'admin_email' ) . ')');?></span>

					<label for="niteoCS_countdown_redirect" class="counter-action redirect" style="padding-top:1em"><?php _e('Enter redirect URL', 'cmp-coming-soon-maintenance');?>
						<input type="text" id="niteoCS_countdown_redirect" name="niteoCS_countdown_redirect" value="<?php echo esc_url( $niteoCS_countdown_redirect ); ?>" class="regular-text code"><br>
					</label>

				</fieldset>
			</td>
		</tr>

		<?php echo $this->render_settings->submit(); ?>
	
	</table>

</div>

<script>
jQuery(document).ready(function($){
	<?php 
	if ( $niteoCS_counter_date != '') { ?>
		var date = new Date(<?php echo esc_attr( $niteoCS_counter_date );?>*1000);
	<?php 
	} else { ?>
		var date = false;
		<?php 
	} 	?>
	// flatpicker
	$('#niteoCS_counter_date').flatpickr({
		minDate: 'today',
		defaultDate: date,
		enableTime: true,
		altInput: true,
		dateFormat: 'U'
	});
});
</script>