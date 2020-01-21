<?php

function sppro_welcome_panel() {
	
	$classes = 'welcome-panel';
	
	$vers = (array) get_user_meta( get_current_user_id(),
		'sppro_hide_welcome_panel_on', true );

	if ( sppro_version_grep( sppro_version( 'only_major=1' ), $vers ) ) {
		$classes .= ' hidden';
	}
	
	delete_user_meta( get_current_user_id(), 'sppro_hide_welcome_panel_on' );

?>
<div class="card col-md-12">
	<span class="card-title d-block text-center display-4"><?php echo esc_html__("Manage Popups", 'sp-pro-txt-domain'); ?></span>
	<div class="card-body">
		<div class="row">
			<div class="col-md-6">
				<span class="f-175 d-block text-info font-weight-bold pb-2"><span class="dashicons dashicons-images-alt2 icon-dash-style"></span><?php echo esc_html__("Multiple Popups", 'sp-pro-txt-domain'); ?></span>
				<p class="text-justify font-weight-normal fs-11"><?php echo esc_html__("Create new popups which can be used on different pages, posts, custom posts or almost in any scenario.", 'sp-pro-txt-domain'); ?></p>
				<p class="text-justify font-weight-normal fs-11"><?php echo sprintf( esc_html__('All multiple popups use settings from the Global Popup form which can be used on all pages. If you have not already configured the global settings then please do it now from the %1$s.', 'sp-pro-txt-domain'), sppro_link( __( SPPRO_OPTIONS_URL, 'sp-pro-txt-domain' ), __( 'Global Options', 'sp-pro-txt-domain' ), array() ) ); ?></p>
				<p class="text-justify font-weight-normal fs-11">
					<a href="<?php echo admin_url('/admin.php?page=sp-pro-import-demos'); ?>" class="btn btn-outline-info btn-sm text-decoration-none"><?php echo esc_html__("Import Demo Popups", 'sp-pro-txt-domain'); ?></a> | <a class="btn btn-info btn-sm text-decoration-none" href="<?php echo admin_url('post-new.php?post_type=sppro_forms'); ?>"><?php echo esc_html__("Add New Popup", 'sp-pro-txt-domain'); ?></a>
				</p>
			</div>
			<div class="col-md-6">
				<span class="f-175 d-block text-info font-weight-bold pb-2"><span class="dashicons dashicons-update icon-dash-style"></span> <?php echo esc_html__("Help and Support", 'sp-pro-txt-domain'); ?></span>
				<p class="text-justify font-weight-normal fs-11">
					<?php echo sprintf( esc_html__('%1$s', 'sp-pro-txt-domain'), sppro_link( esc_html__( admin_url('admin.php?page=sp-pro-help'), 'sp-pro-txt-domain' ), __( 'Support', 'sp-pro-txt-domain' ), array() ) ); ?>					
					<?php echo sprintf( esc_html__(' | %1$s', 'sp-pro-txt-domain' ), sppro_link( __( 'https://www.slickpopup.com/?utm_source=clientsite&utm_medium=managepopupspage&utm_campaign=OmAkSols', 'sp-pro-txt-domain' ), __( 'Official Website', 'sp-pro-txt-domain' ), array('target'=>'_blank') ) ); ?>					
				</p>
				
				<?php 
					$demolink = sppro_link( esc_html__('https://www.slickpopup.com//?utm_source=clientsite&utm_medium=managepopupspage&utm_campaign=OmAkSols', 'sp-pro-txt-domain' ), esc_html__('View Demos', 'sp-pro-txt-domain' ), array('target'=>'_blank') );					
					$testsitelink = sppro_link( esc_html__('http://www.slickpopup.com/test-popup-site-iframe-mode/', 'sp-pro-txt-domain' ), esc_html__('Test On Your Site', 'sp-pro-txt-domain' ), array('target'=>'_blank') );
					$emaillink = sppro_link( esc_html__('mailto:poke@slickpopup.com', 'sp-pro-txt-domain'), esc_html__('poke@slickpopup.com', 'sp-pro-txt-domain' ) );					
				?>
				<p class="text-justify font-weight-normal fs-11">
					<strong><?php echo esc_html__('Shortcode','sp-pro-txt-domain'); ?>: </strong>
					<span class="font-weight-bold">[sppro id='123' text='click here']</span>
				</p>
			</div>
		</div>
	</div>
</div>
<?php
}


add_action( 'wp_ajax_sppro-update-admin-welcome-panel', 'sppro_ajax_admin_welcome_panel' );
function sppro_ajax_admin_welcome_panel() {
	
	check_ajax_referer( 'sppro-admin-welcome-panel-nonce', 'adminwelcomepanelnonce' );
	
	$vers = get_user_meta( get_current_user_id(),
		'sppro_hide_welcome_panel_on', true );

	if ( empty( $vers ) || ! is_array( $vers ) ) {
		$vers = array();
	}

	if ( empty( $_POST['visible'] ) ) {
		$vers[] = sppro_version( 'only_major=1' );
	}
	
	if ( isset( $_POST['delete'] ) ) {
		$delete = true; 
	}

	$vers = array_unique( $vers );

	if( isset($delete) ) 
		delete_user_meta( get_current_user_id(), 'sppro_hide_welcome_panel_on' );
	else 
		update_user_meta( get_current_user_id(), 'sppro_hide_welcome_panel_on', $vers );

	wp_die( 1 );
}
