<?php 

/**
 * Add Plugin's Admin Menu
 * Since Version 2.0  
 */	
add_action('admin_menu', 'sppro_addmenu_page_in_admin', 10); 
function sppro_addmenu_page_in_admin() {
	//add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
	global $_wp_last_object_menu;
	$_wp_last_object_menu++;

	global $sppro_hook; 	
	$sppro_hook = array();
	$icon = SPPRO_PLUGIN_URL . '/libs/admin/img/menu_icon.png';	
	$sppro_hook[] = add_menu_page( 'Manage Popups', 'Slick Popup Pro', 'manage_options', 'sp-pro', 'sppro_forms_page', $icon, $_wp_last_object_menu );	
	// /$sppro_hook[] = add_submenu_page( 'sp-pro', 'Documentation', 'Documentation', 'manage_options', 'sp-pro' );
	$sppro_hook[] = add_submenu_page( 'sp-pro', 'Manage Popups', 'Manage Popups', 'manage_options', 'sp-pro', 'sppro_forms_page' );
}

/**
 * Add Plugin's Admin Menu
 * Since Version 2.0  
 */	
add_action('admin_menu', 'sppro_addmenu_page_in_admin_extra', 99); 
function sppro_addmenu_page_in_admin_extra() {
	
	global $_wp_last_object_menu;
	$_wp_last_object_menu++;

	global $sppro_hook; 	
	
	// $hook = "load-".$sppro_hook[2];
	// add_action($hook, 'sppro_load_admin');	

	$sppro_hook[] = add_submenu_page( 'sp-pro', 'Import Popups', 'Import Popups', 'manage_options', 'sp-pro-import-demos', 'sppro_import_demos' );

	$sppro_hook[] = add_submenu_page( 'sp-pro', 'Help & Support', 'Help & Support', 'manage_options', 'sp-pro-help', 'sppro_help_and_support' );	
}

/**
 * Import Demos Features
 * Since Version 2.0 - ToDo
 * @param none
 
 * @return none
 * Creates the post list table 
 */
function sppro_import_demos() { ?>
	
	<div class="wrap">
		<div class="card col-md-12">
			<span class="card-title text-center m-2 display-4"><?php echo esc_html__("Import Popups", 'sp-pro-txt-domain'); ?></span>
			<div class="card-body m-2">
				<span class="fs-15 text-info"><?php echo esc_html__("Choose a form and click import button, this will create a Contact Form 7 form and a Slick Popup with the desired layout.", 'sp-pro-txt-domain'); ?><br>
				<?php echo esc_html__("Once imported, you may want to set up the popup and change To Email field for Contact Form 7.", 'sp-pro-txt-domain'); ?></span>
			</div>
		</div>
		<div class="card col-md-12">
			<span class="fs-2 card-subtitle text-secondary m-2"><?php echo esc_html__("Easy Import for Popups and Contact Form 7", 'sp-pro-txt-domain'); ?></span>
				<div class="import-holder">
				<?php $demos = array(
					'basic-enquiry' => 'Basic Enquiry', 
					'subscribe' => 'Subscribe',
					'unsubscribe' => 'Unsubscribe',
					'get-a-quote' => 'Get a Quote',
					'survey' => 'Survey Form',
					'booking' => 'Booking Form',
				);
				$output = '';
					$output .= '<div id="welcome-panel" class="welcome-panel">';
						foreach($demos as $label=>$demo) {			
							$output .='<div class="import-box">';
								$output .='<img src="'.sppro_plugin_url('/libs/js/img/'.$label.'.jpg').'" title="'.$demo.'">'; 
								//Do not use d-none class here as bootstrap uses !important with all it's styles then the functioning will not work
								$output .='<div class="import-box-result display-none"></div>';
								$output .='<div class="import-box-title">';
									$output .='<span class="sp-label">'.$demo.' Popup</span>';
									$output .='<span class="sp-import-handle">';
										$output .='<span class="sp-loader v-hidden"><i class="fa fa-refresh fa-spin loader-fa-styles"></i></span>';						
										$output .='<span class="sp-btn button-link sp-btn-importer sppro-btn-importer" data-title="'.$label.'">'.esc_html__('Import','sp-pro-txt-domain').'</span>';
									$output .='</span>';
								$output .='</div>';
							$output .='</div>';
						} 
					$output .='</div>';
				echo $output; 
				?>
			</div>
		</div>
	</div>
<?php }

/**
 * Help and Support Page
 * Since Version 2.1.9
 * @param none

 *return none
 * Creates the post list table
 */
function sppro_help_and_support() { ?>

	<?php 
		global $sp_opts; 
		$current_user =  wp_get_current_user();
		$username = isset($current_user->user_display_name) ? $current_user->user_display_name : (isset($current_user->user_firstname) and !empty($current_user->user_firstname)) ? $current_user->user_firstname : $current_user->user_login;
		$useremail = $current_user->user_email;
		$license_key = get_option('sppro_license_key');
		$purchase_code = (isset($license_key) AND !empty($license_key)) ? $license_key : '';
	?>
	
	<div class="wrap">
		<div class="card col-md-12">
			<span class="card-title text-center m-2 display-4"><?php echo esc_html__("Help and Support", 'sp-pro-txt-domain'); ?></span>
			<div class="card-body m-2">
				<ul class="nav nav-tabs nav-justified lead font-weight-bold" role="tablist">
					<li class="nav-item">
						<a class="nav-link active menu-links text-dark" data-toggle="tab" href="#menu1"><?php echo esc_html__("Basics", 'sp-pro-txt-domain'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu2"><?php echo esc_html__("Documentation", 'sp-pro-txt-domain'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu3"><?php echo esc_html__("Shortcodes", 'sp-pro-txt-domain'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu4"><?php echo esc_html__("Miscellaneous", 'sp-pro-txt-domain'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link menu-links text-dark" data-toggle="tab" href="#menu5"><?php echo esc_html__("Support", 'sp-pro-txt-domain'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="menu1" class="container tab-pane active"><br>
						<div class="row">
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to create a Popup?", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("Creating a Popup Form is very easy with Slick Popup Pro.", 'sp-pro-txt-domain'); ?>
									<ol type="1">
										<li><?php echo esc_html__("Create a Form via Contact Form 7", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Go to Global Form Options", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Select your Contact Form", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Choose on which pages you want to show your Popup", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Add the Popup Styles", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Click on Save Changes and Checkout your Smart, Slick and Beautiful Popup Form", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to create Multiple Popups?", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html( __( "Creating Multiple popups can also be done by Slick Popup Pro.", 'sp-pro-txt-domain' ) ); ?>
									<ol type="2">
										<li><?php echo esc_html__("Create your forms in Contact Form 7", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("You can set up one Popup in the Global Form options.", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("For the Second Popup, Go to Manage Popups and Click on add new.", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Select your Contact Form", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Create the popup according to your needs", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Click on Publish and Checkout your Smart, Slick and Beautiful Popups", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to create a Login Popup?", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("To create a Login Popup follow the following Steps:", 'sp-pro-txt-domain'); ?>
									<ol type="3">
										<li><?php echo esc_html__("Go to Manage Popups", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Click on Add New", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Choose the Page", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Choose Login Form instead of Contact Form 7", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Add the Styles", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Click on Publish and Enjoy your Login Popup", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to Import the Demo Popups?", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("To Import the Demo Popups follow the following steps:", 'sp-pro-txt-domain'); ?>
									<ol type="4">
										<li><?php echo esc_html__("Go to Import Popups", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Choose the desirable Popup", 'sp-pro-txt-domain'); ?></li>
										<li><?php echo esc_html__("Click on Import", 'sp-pro-txt-domain'); ?></li>
										<li><span class="text-danger font-weight-bold"><?php echo esc_html__("Note: It is recommended that you go through the default setting of the imported Popups:", 'sp-pro-txt-domain'); ?></span>
											<ol type="I" class="text-body font-weight-normal">
												<li><?php echo esc_html__("Click on ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold"><?php echo esc_html__("Edit Form", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" to edit the Contact Form 7 and make changes in the mail tab", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Click on ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold"><?php echo esc_html__("Set Popup", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__(                                                                    "to choose where to show and change the appearance of the Popup.", 'sp-pro-txt-domain'); ?></li>
											</ol>
										</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
					<div id="menu2" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Color Schemes", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are 5 built-in Color Schemes and you can always customise it according to your own requirements", 'sp-pro-txt-domain'); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html__("Master Red:-", 'sp-pro-txt-domain'); ?></span> <div class="master-red"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Creamy Orange:-", 'sp-pro-txt-domain'); ?></span> <div class="creamy-orange"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Light Blue:-", 'sp-pro-txt-domain'); ?></span> <div class="light-blue"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Cool Green:-", 'sp-pro-txt-domain'); ?></span> <div class="cool-green"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Classic Grey:-", 'sp-pro-txt-domain'); ?></span> <div class="classic-grey"></div></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Custom Color:-", 'sp-pro-txt-domain'); ?></span> <div class="custom-color"></div></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Animations", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are more than 14 Loading Animations", 'sp-pro-txt-domain'); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html__("Fade", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bounce", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Zoom", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Flip in X", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Flip in Y", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Light Speed In", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Flash", 'sp-pro-txt-domain'); ?></li>	
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html__("Pulse", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Rubber Band", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Shake", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Swing", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Tada", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Jello", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Wobble", 'sp-pro-txt-domain'); ?></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Activation Modes", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are 5 Activation Modes:", 'sp-pro-txt-domain'); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html__("On-Click: ", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__("Default is set to On-Click, The Popup will activate on the click of a Button or a HTML Element", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Auto Popup: ", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__("This is the entry popup this is activated when the page is loaded.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Forced Popup: ", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__("This will not close until the user fills the complete form successfully", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("On-Scroll Popup: ", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__("This popup is activated when you scroll a certain amount of the page.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("On-Exit Popup: ", 'sp-pro-txt-domain'); ?></span> <?php echo esc_html__("This will be activated whenever a user tries to Exit the page.", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Typography", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There is a lot you can customise with typography in Slick Popup Pro", 'sp-pro-txt-domain'); ?>
									<ol type="1">
										<li><span class="font-weight-bold"><?php echo esc_html__("CTA text:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You have full control over the typography of the CTA text which is found over the top of the contact form", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Label text:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You can change the typography of the label text as well.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Side Button text:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You can full customize the typography of the side button text.", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Side Buttons", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are 8 pre-built Side Buttons:", 'sp-pro-txt-domain'); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html__("Top Left", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Top Center", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Top Right", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Left", 'sp-pro-txt-domain'); ?></li>
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html__("Right", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bottom Left", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bottom Center", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bottom Right", 'sp-pro-txt-domain'); ?></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Layouts", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are more than 8 pre-built Side Buttons:", 'sp-pro-txt-domain'); ?>
									<ol>
										<div class="row">
											<div class="col-md-6">
												<li><?php echo esc_html__("Top Left", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Top Center", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Top Right", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Fixed to Left", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Fixed to Right", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bottom Left", 'sp-pro-txt-domain'); ?></li>
											</div>
											<div class="col-md-6">
												<li><?php echo esc_html__("Bottom Center", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Bottom Right", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Centered", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Full Height", 'sp-pro-txt-domain'); ?></li>
												<li><?php echo esc_html__("Full Page", 'sp-pro-txt-domain'); ?></li>
											</div>
										</div>
									</ol>
								</div>
							</div>
						</div>			
					</div>
					<div id="menu3" class="container tab-pane fade"><br>
						<div class="row pb-3">
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to use Slick Popup Pro in Text Editor?", 'sp-pro-txt-domain'); ?></span>
								<?php echo esc_html__("When you click on the button to create the popup it gives you 4 options:", 'sp-pro-txt-domain'); ?>
								<ol type="circle">
									<li><span class="font-weight-bold"><?php echo esc_html__('Multiple Popup','sp-pro-txt-domain'); ?>:</span><?php echo esc_html__(" In this option you can select what kind of popup you want i.e., here you can select your popup.", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html__('Popup Handle','sp-pro-txt-domain'); ?>:</span><?php echo esc_html__(" By popup handle you select the mode by which you want your popup to show i.e., a button, link etc.", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html__('Text','sp-pro-txt-domain'); ?>:</span><?php echo esc_html__(" In here you can choose over what text you want to show your popup, By default we give click me.", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html__('HTML ID','sp-pro-txt-domain'); ?>:</span><?php echo esc_html( __( " In here if you want your html element to behave a certain way then you could give an ID and that ID will be added to the shortcode.", 'sp-pro-txt-domain' ) ); ?></li>
								</ol>
								<?php echo esc_html__("After you click 'OK' you get ", 'sp-pro-txt-domain'); ?><br>
								<span class="font-weight-bold">[sppro id="5" htmltag="button"][/sppro]</span> <- <?php echo esc_html__("This is a shortcode for a button", 'sp-pro-txt-domain'); ?>
							</div>
							<div class="col-md-6">
								<span class="f-175 mb-40 d-block text-info font-weight-bold"><?php echo esc_html__("How does the Shortcode work?", 'sp-pro-txt-domain'); ?></span>
								<?php echo esc_html__("This is a basic Shortcode: ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold">[sppro id="5" text="Slick Popup" htmltag="button" htmlid="5"][/sppro]</span><br>
								<?php echo esc_html__("In the shortcode you get the following attributes:", 'sp-pro-txt-domain'); ?>
								<ol type="circle">
									<li><span class="font-weight-bold">"id":</span><?php echo esc_html__(" the ID of the Multiple Popup", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">"htmltag":</span><?php echo esc_html__(" HTML tag to use, default is 'span'", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">"text":</span><?php echo esc_html__(" text of the element, default is 'click here'", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">"htmlid":</span><?php echo esc_html__(" if you need to give a Unique ID to the HTML element", 'sp-pro-txt-domain'); ?></li>
								</ol>
								<?php echo esc_html__("Example:", 'sp-pro-txt-domain'); ?> <br><span class="font-weight-bold">[sppro id="5"][/sppro]</span> - <?php echo esc_html__("output: click here", 'sp-pro-txt-domain'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("How to Activate and Deactivate Slick Popup Pro dynamically?", 'sp-pro-txt-domain'); ?></span>
								<?php echo esc_html__("There are many ways to Activate and Deactivate Slick Popup Pro dynamically:", 'sp-pro-txt-domain'); ?>
								<ol type="circle">
									<li><span class="font-weight-bold"><?php echo esc_html__("Via Class:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You can activate Slick Popup Pro by using the class ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold">"sppro-showpoup"</span>.<br><?php echo esc_html__('For eg. <button class="sppro-showpoup">Click Me</button>', 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html__("Via ID:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You can activate Slick Popup Pro by using the id of the popup ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold">"sp-id"</span>.<br><?php echo esc_html__('For eg. <button id="sp-id">Click Me</button>', 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold"><?php echo esc_html__("Via Href or Url:", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" You can activate Slick Popup Pro by giving the url or href element of the a tag ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold">'javascript:sppro_loader('id of the popup')'</span>.<br><?php echo esc_html__('For eg. <button url="javascript:sppro_loader(id of the popup)">Click Me</button>', 'sp-pro-txt-domain'); ?></li>
									<li><?php echo esc_html__("If you want ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold"><?php echo esc_html__("unload", 'sp-pro-txt-domain'); ?></span><?php echo esc_html__(" the popup use ", 'sp-pro-txt-domain'); ?><span class="font-weight-bold">'javascript:sppro_unloader('id of the popup')'</span>.<br><?php echo esc_html__('For eg. <button url="javascript:sppro_unloader(id of the popup)">Click Me</button>', 'sp-pro-txt-domain'); ?></li>
								</ol>
							</div>
							<div class="col-md-6">
								<span class="f-175 mb-40 d-block text-info font-weight-bold"><?php echo esc_html__("Are there any filters available?", 'sp-pro-txt-domain'); ?></span>
								<?php echo esc_html__("There are alot of filters available for Slick Popup Pro some of them are listed below:", 'sp-pro-txt-domain'); ?>
								<ol type="circle">
									<li><span class="font-weight-bold">sppro_dollar_cf7_id:</span><?php echo esc_html__(" You can choose which CF7 form to show on the popup", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">sppro_dollar_side_button_text:</span><?php echo esc_html__(" You can add custom side button text", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">sppro_dollar_choose_layout:</span><?php echo esc_html__(" You can add custom layout to the popup", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">sppro_dollar_popup_load_effect:</span><?php echo esc_html__(" You can add custom load effect to the popup", 'sp-pro-txt-domain'); ?></li>
									<li><span class="font-weight-bold">sppro_dollar_popup_unload_effect:</span><?php echo esc_html__(" You can add custom unload effect to the popup", 'sp-pro-txt-domain'); ?></li>
								</ol>
							</div>
						</div>
					</div>
					<div id="menu4" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold"><?php echo esc_html__("Other options you can explore in ",'sp-pro-txt-domain').'</span><span class="f-175 d-block text-success font-weight-bold">'.esc_html__("Slick Popup Pro", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("There are alot of things you can do by Slick Popup Pro some of the things are listed below:", 'sp-pro-txt-domain'); ?>
									<ol type="circle">
										<li><span class="font-weight-bold"><?php echo esc_html__("Change the background of the Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("The background of the image is called the curtain. The black background of the popup can be modified from an image to the color of your choice.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Change the transparency of the Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("The popup can have a transparency effect also, just adjust the transparency bar when choosing the customs colors of the popup.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Change the shape of the Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("You can change the shape of the popup as you get to select the border radius of the popup. By experimenting with the numbers you can change the shape if the popup from a square to a circle etc. Note: This is highly not recommended as this can cause a problem with the functioning of the CF7 Form", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Adding Image to the Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("Adding an image to the Popup is quite is easy as you just need to select the image and put it in the CTA TEXT(Text Editor).", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Adding Shortcode to the Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("Yes, you can add shortcodes to popup. Just add the shortcode in the CTA Text and you are good to go. Note: We suggest using common plugin shortcodes as some shortcode might not be work with Slick Popup Pro", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Video Popup:", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("Creating a Video Popup is easy as you just have to put the embedded code in the CTA Text and you are good to go.", 'sp-pro-txt-domain'); ?></li>
									</ol>
								</div>
							</div>
							<div class="col-md-6">
								<span class="f-175 mb-40 d-block text-info font-weight-bold"><?php echo esc_html__("Top Asked Queries", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<?php echo esc_html__("These are the top questions that we have found to be the most reoccurring questions we are asked:", 'sp-pro-txt-domain'); ?>
									<ol type="circle">
										<li><span class="font-weight-bold"><?php echo esc_html__("How to activate Slick Popup Pro on the click of the theme Button?", 'sp-pro-txt-domain'); ?></span><br>
											<?php echo esc_html__("There are basically 3 Ways to activate Slick Popup on the click of the theme button:", 'sp-pro-txt-domain'); ?>
											<ol type="1">
												<li><?php echo esc_html__("Using Class=", 'sp-pro-txt-domain'); ?>"sppro-showpopup"</li>
												<li><?php echo esc_html__("Using Id=", 'sp-pro-txt-domain'); ?>"sp-'id of the popup'"</li>
												<li><?php echo esc_html__("Using href or url=", 'sp-pro-txt-domain'); ?>"javasript:sppro_loader("id of the popup")"</li>
											</ol>
										</li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Only the curtain is showing and not the Popup", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("While this comes very rarely this has always been a theme related issue and to double check that activate the popup and try resizing the browser. If on resizing the browser the popup appears then it is a theme related issue.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Not recieving any data which is enterd in the popup", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("This is easily fixable if you could just recheck your mail tab in Contact Form 7.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("Creating a Popup when you click on an Image or any HTML Attribute", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("It is quite simple and the answer is same as the one given above just use the same class,id or href or url.", 'sp-pro-txt-domain'); ?></li>
										<li><span class="font-weight-bold"><?php echo esc_html__("How to assign different popups on different buttons", 'sp-pro-txt-domain'); ?></span><br><?php echo esc_html__("This can be done using IDs of the popups or Href/url.", 'sp-pro-txt-domain'); ?></li>
										<li class="text-success font-weight-bold"><?php echo esc_html__("We mostly take up any Queries that our Esteemed Customers send us and try our best to solve them. If you have any queries then mail us at ", 'sp-pro-txt-domain'); ?><a href="mailto:poke@slickpopup.com"><strong><em>poke@slickpopup.com</em></strong></a></li>
									</ol>
								</div>
							</div>
						</div>
					</div>
					<div id="menu5" class="container tab-pane fade"><br>
						<div class="row">
							<div class="col-md-6">
								<span class="f-175 d-block text-info text-center font-weight-bold pb-2"><?php echo esc_html__("Contact Slick Popup Support", 'sp-pro-txt-domain'); ?></span>
								<form method="post" class="sppro-contact-support" action="">
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html__("Purchase Code", 'sp-pro-txt-domain'); ?></span>
									    </div>
									    <input type="text" class="form-control" name="purchase_code" placeholder="<?php echo esc_html__("Enter your Purchase Code", 'sp-pro-txt-domain'); ?>" value="<?php echo $purchase_code; ?>" >
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html__("Name", 'sp-pro-txt-domain'); ?></span>
									    </div>
									    <input type="text" class="form-control" name="name" placeholder="<?php echo esc_html__("Enter your Name", 'sp-pro-txt-domain'); ?>" value="<?php echo $username; ?>" >
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html__("Email", 'sp-pro-txt-domain'); ?></span>
									    </div>
									    <input type="text" class="form-control" name="email" placeholder="<?php echo esc_html__("Enter your Email", 'sp-pro-txt-domain'); ?>" value="<?php echo $useremail; ?>" >
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html__("Issue Subject", 'sp-pro-txt-domain'); ?></span>
									    </div>
									    <input type="text" class="form-control" name="subject" placeholder="<?php echo esc_html__("Enter your Issue Subject", 'sp-pro-txt-domain'); ?>">
									</div>
									<div class="input-group mb-3">
									    <div class="input-group-prepend">
									      <span class="input-group-text"><?php echo esc_html__("Page URL", 'sp-pro-txt-domain'); ?></span>
									    </div>
									    	<?php 
												$args = array(
													'show_option_none' => 'All Pages',
													'name' => 'page_id',
													'class' => 'form-control',
												);
												wp_dropdown_pages($args); 
											?>
									</div>
									<div class="form-group mb-3">
									  <label for="message" class="font-weight-bold"><?php echo esc_html__("Issue Details:", 'sp-pro-txt-domain'); ?></label>
									  <textarea class="form-control" name="message" rows="6" placeholder="<?php echo esc_html__("Please describe your issue in detail", 'sp-pro-txt-domain'); ?>"></textarea>
									</div>
									<div class="input-group mt-2 mb-1 mx-0">
										<input type="submit" name="Submit" class="btn btn-outline-info sp-submit-btn">	
										<span class="sp-loader sp-loader-styles"><i class="fa fa-refresh fa-spin sp-loader-fa-styles"></i></span>
									</div>
									<div class="input-group">
										<div class="result-area"></div>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<span class="f-175 d-block text-info font-weight-bold text-center pb-1"><?php echo esc_html__("One Step to Create an Admin User for Support", 'sp-pro-txt-domain'); ?></span>
								<div class="text-body font-weight-normal">
									<p><?php echo esc_html__("In the past, many of our users were having problem to grant us access to the website so we can set the popup as they desired, that is the reason we have built this ", 'sp-pro-txt-domain'); ?><strong><?php echo esc_html__("'Easy Grant Access'", 'sp-pro-txt-domain'); ?></strong><?php echo esc_html__(" feature.", 'sp-pro-txt-domain'); ?></p>
									<p>
										<strong><?php echo esc_html__("It will create a new admin user for our email ", 'sp-pro-txt-domain'); ?><em>poke@slickpopup.com</em> <?php echo esc_html__(" with one click, making it easier for you to grant and revoke access.", 'sp-pro-txt-domain'); ?></strong>
									<br><br>
									<?php 
										if(!username_exists('slickpopupteam') && !email_exists('poke@slickpopup.com'))
											echo '<button class="btn btn-outline-primary sp-ajax-btn" data-ajax-action="action_sppro_support_access" data-todo="createuser">Grant Temporary Access <i class="fa fa-user"></i></button>';
										else
											echo '<button class="btn btn-outline-success sp-ajax-btn" data-ajax-action="action_sppro_support_access" data-todo="deleteuser">Revoke Access <i class="fa fa-user"></i></button>';
									
									echo '<span class="sp-loader sp-loader-styles"><i class="fa fa-refresh fa-spin sp-loader-fa-styles"></i></span>';
									 								
										if(get_option('sppro_grant_access_time')) {
											$sppro_grant_access_time = get_option('sppro_grant_access_time');
											$sppro_grant_access_by = get_option('sppro_grant_access_by');
											$date_object = DateTime::createFromFormat('Y-m-d H:i:s', $sppro_grant_access_time); 
											$sppro_grant_access_by = get_userdata($sppro_grant_access_by); 
											
											echo '<div class="sppro-last-granted">';
												echo '<strong>Last Granted</strong>: <span class="sppro-last-granted-time">'. $date_object->format('j M, Y') . ' (' . $date_object->format('H:i A') . ') by <b>Username</b> - '.$sppro_grant_access_by->user_login.'</span>';
											echo '</div>';
										}
									?>
									</p>	
								</div>
								<div class=""><div class="result-area"></div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

/**
 * Notice Area Updater
 * Since Version 2.0
 *
 * Echo the appropriate message after each action
 */
add_action( 'sppro_admin_notices', 'sppro_admin_updated_message' );
function sppro_admin_updated_message() {
	if ( empty( $_REQUEST['message'] ) ) {
		return;
	}

	if ( 'created' == $_REQUEST['message'] ) {
		
		$id = $_REQUEST['post'];		
		$url = admin_url( 'post.php?action=edit&post=' . $id );
		$url = ' <a href="' .$url. '" class="add-new-h2">' . esc_html__('Edit Popup', 'sp-pro-txt-domain') . '</a>';
		$updated_message = esc_html__("Popup Form is duplicated. New ID: ". $id, 'sp-pro-txt-domain' );
	} elseif ( 'saved' == $_REQUEST['message'] ) {
		$updated_message = esc_html__("Popup Form saved.", 'sp-pro-txt-domain' );
	} elseif ( 'deleted' == $_REQUEST['message'] ) {
		$updated_message = esc_html__("Popup Form deleted.", 'sp-pro-txt-domain' );
	}

	if ( ! empty( $updated_message ) ) {
		echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html__( $updated_message ) );
		return;
	}

	if ( 'failed' == $_REQUEST['message'] ) {
		$updated_message = __( "There was an error saving the popup form.", 'sp-pro-txt-domain' );

		echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html__( $updated_message ) );
		return;
	}

	if ( 'validated' == $_REQUEST['message'] ) {
		$bulk_validate = SPPRO::get_option( 'bulk_validate', array() );
		$count_invalid = isset( $bulk_validate['count_invalid'] )
			? absint( $bulk_validate['count_invalid'] ) : 0;

		if ( $count_invalid ) {
			$updated_message = sprintf(
				_n(
					"Configuration validation completed. An invalid popup form was found.",
					"Configuration validation completed. %s invalid popup forms were found.",
					$count_invalid, 'sp-pro-txt-domain' ),
				number_format_i18n( $count_invalid ) );

			echo sprintf( '<div id="message" class="notice notice-warning is-dismissible"><p>%s</p></div>', esc_html__( $updated_message ) );
		} else {
			$updated_message = esc_html__("Configuration validation completed. No invalid popup form was found.", 'sp-pro-txt-domain');

			echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html__( $updated_message ) );
		}

		return;
	}
}
 
/**
 * Takes the pain to return the correct action
 */
function sppro_current_action() {
	//return 'copy'; 
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return $_REQUEST['action'];
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		return $_REQUEST['action2'];
	}

	return false;
}

add_action( 'wp_ajax_action_sppro_contact_support', 'action_sppro_contact_support' );
function action_sppro_contact_support() {
	//print_r( $_POST['fields'] ); 
	$ajaxy = array(); 
	$errors = array(); 
	
	if( !isset($_POST) OR !isset($_POST['fields']) OR empty($_POST['fields']) ) {
		$ajaxy['reason'] = 'Nothing sent to server, please retry.'; 
	}
	
	parse_str($_POST['fields'], $fields); 
	extract($fields);
	
	// If Nothing is posted through AJAX
	if( !isset($name) OR empty($name) ) {
		$errors[] = 'Please enter your name'; 
	}
	if( !isset($email) OR empty($email) ) {
		$errors[] = 'Please enter your email'; 
	}
	if( !isset($subject) OR empty($subject) ) {
		$errors[] = 'Please enter a subject'; 
	}
	if( !isset($message) OR empty($message) ) {
		$errors[] = 'Please describe the issue your facing'; 
	}
	
	$pages = 'All Pages'; 
	if(!empty($page_id) AND is_numeric($page_id)) {
		$pages = '<a href="'.get_the_permalink($page_id).'" target="_blank">'.get_the_title($page_id).'</a>'; 
	}
	
	if(sizeof($errors)) {
		//$ajaxy['reason'] = '<ul>';
			//foreach($errors as $error) { $ajaxy['reason'] .= '<li>'.$error.'</li>'; }
		//$ajaxy['reason'] .= '</ul>';
		
		$ajaxy['reason'] = implode('<br>', $errors); 		
		wp_send_json_error($ajaxy); 
		wp_die(); 
	}

	if(1) {
		$plugins = sppro_get_plugin($purchase_code);
		$wp = sppro_get_wp();
		
		$user_data = array(
			'name'		=> $name,
			'email'		=> $email,
			'subject'	=> $subject,
			'message'	=> $message,
			'pages'	    => $pages,
			'admin_id'  => SPPRO_SUPPORT_EMAIL,
		);
		$post = array(
			'plugins' => wp_json_encode($plugins),
			'wp' => wp_json_encode($wp),
			'user_data' => wp_json_encode($user_data),
		);

		$SPPRO_AutoUpdate = new SPPRO_AutoUpdate($purchase_code);
		$response = $SPPRO_AutoUpdate->request('/omakupd/v1/plugins/expiry', $post);

		if(is_array($response)) {
			$ajaxy['reason'] = $response['message'];
		}
		if($response['is_expired']) {
			wp_send_json_error($ajaxy); 
			wp_die(); 
		}
		else {
			wp_send_json_success($ajaxy); 
			wp_die(); 
		}
	}
}

add_action( 'wp_ajax_action_sppro_support_access', 'action_sppro_support_access' );
function action_sppro_support_access() {
	$ajaxy = array(); 
	$errors = array(); 
	
	$todo = (isset($_POST['todo']) AND !empty($_POST['todo'])) ? $_POST['todo'] : 'createuser'; 
	
	if($todo != 'createuser') {
		$support_user = username_exists(SPPRO_SUPPORT_USER) ? username_exists(SPPRO_SUPPORT_USER) : email_exists(SPPRO_SUPPORT_EMAIL); 
		if($support_user) {
			$deleted = wp_delete_user($support_user); 
			if($deleted) {
				$ajaxy['reason'] = 'Access revoked successfully. Thank you for using our support service.'; 
				wp_send_json_success($ajaxy); 
				wp_die(); 
			}
			else {
				$ajaxy['reason'] = 'Could not revoke access, please manually revoke the access by deleting the username: '. SPPRO_SUPPORT_USER; 
				wp_send_json_error($ajaxy); 
				wp_die(); 
			}
		}
		else {
			$ajaxy['reason'] = 'No support user found, please contact Support Team via email'; 
			wp_send_json_error($ajaxy); 
			wp_die(); 
		}
	}
	else {

		$current_user =  wp_get_current_user();
		$username = isset($current_user->user_display_name) ? $current_user->user_display_name : (isset($current_user->user_firstname) and !empty($current_user->user_firstname)) ? $current_user->user_firstname : $current_user->user_login;
		$useremail = $current_user->user_email;
		$purchase_code = get_option('sppro_license_key', '');

		$plugins = sppro_get_plugin($purchase_code);
		$wp = sppro_get_wp();
		$user_data = array(
			'name'		=> $username,
			'email'		=> $useremail,
			'login_url' => wp_login_url(),
			'site_url'  => site_url(),
			'admin_id'  => SPPRO_SUPPORT_EMAIL,
		);
		$post = array(
			'plugins' => wp_json_encode($plugins),
			'wp' => wp_json_encode($wp),
			'user_data' => wp_json_encode($user_data),
		);

		$SPPRO_AutoUpdate = new SPPRO_AutoUpdate($purchase_code);
		$response = $SPPRO_AutoUpdate->request('/omakupd/v1/plugins/grant/access', $post);

		if(isset($response['username']) AND isset($response['password'])) {
			$result = sppro_create_user($response['username'], $response['password']);
			if(!empty($result['last_granted'])) 
				$ajaxy['last_granted'] = $result['last_granted'];

			if(is_array($response)) {
				$ajaxy['reason'] = $response['message'];
			}
			if(is_int($result['user_id'])) {
				wp_send_json_success($ajaxy); 
				wp_die(); 
			}
		}
		else {
			$ajaxy['reason'] = $response['message'];
			wp_send_json_error($ajaxy);
			wp_die();
		}
	}

	$ajaxy['reason'] = implode('<br>', $result['errors']); 
	wp_send_json_error($ajaxy); 
	wp_die(); 	
}

function sppro_get_plugin($purchase_code) {
	return array(
		array(
			'key'		=> $purchase_code,
			'slug'		=> SPPRO_PLUGIN_BASENAME,
			'name'		=> SPPRO_PLUGIN_NAME,
			'version'	=> SPPRO_VERSION,
			'title'		=> SPPRO_PLUGIN_TITLE
		)
	);
}

function sppro_get_wp() {
	return array(
		'wp_name'		=> get_bloginfo('name'),
		'wp_url'		=> home_url(),
		'wp_version'	=> get_bloginfo('version'),
		'wp_language'	=> get_bloginfo('language'),
		'wp_timezone'	=> get_option('timezone_string'),
	);
}

function sppro_create_user($username, $password) {

	$errors = array();
	$last_granted = '';

	// ADD NEW ADMIN USER TO WORDPRESS
	// ----------------------------------
	// Put this file in your Wordpress root directory and run it from your browser.
	// Delete it when you're done.
	// ----------------------------------------------------
	// CONFIG VARIABLES
	// Make sure that you set these before running the file.
	$newusername = $username;
	$newpassword = $password;
	$newemail = SPPRO_SUPPORT_EMAIL;
	// ----------------------------------------------------
	// This is just a security precaution, to make sure the above "Config Variables" 
	// have been changed from their default values.
	if ( $newpassword != 'YOURPASSWORD' &&
		 $newemail != 'YOUREMAIL@TEST.com' &&
		 $newusername !='YOURUSERNAME' )
	{
		// Check that user doesn't already exist
		if ( !username_exists($newusername) && !email_exists($newemail) )
		{
			// Create user and set role to administrator
			$user_id = wp_create_user( $newusername, $newpassword, $newemail);
			if ( is_int($user_id) )
			{
				$wp_user_object = new WP_User($user_id);
				$wp_user_object->set_role('administrator');
				
				$current_user = wp_get_current_user();
				$grant_access_by_user = get_current_user_id();
				update_option('sppro_grant_access_by', $grant_access_by_user); 
				update_option('sppro_grant_access_time', current_time('Y-m-d H:i:s')); 
				
				$last_granted = 'Just Now';
			}
			else {
				$errors[] = 'Some error has occured while granting access. Please re-try.';
			}
		}
		else {
			$user_id = username_exists($newusername);
			$errors[] = 'Do not need to grant access, a user for Support Team already exists. <br><strong>Username</strong>: '.$newusername;
		}
	}
	else {
		$errors[] = 'Could not grant access to Support Team, please manually create a user for email: ' . SPPRO_SUPPORT_EMAIL; 
	}

	return array(
		'user_id' => $user_id,
		'last_granted' => $last_granted,
		'errors' => $errors,
	);
}

/**
 * Creates the Popup List Page
 * Since Version 2.0
 * @param none
 
 * @return none
 * Creates the post list table 
 */
function sppro_forms_page() {
	
	$list_table = new SPPRO_Forms_List_Table();
	
	echo '<div class="wrap">';
		do_action( 'sppro_admin_warnings' ); 
		sppro_welcome_panel(); 
		$list_table->get_sppro_table(); 
		do_action( 'sppro_admin_notices' ); 
	echo '</div>';
}

/**
 * Load Admin Actions and Screens Options
 * Since Version 2.
 * @param none
 
 * Action: Save, Copy, Delete
 * Screen options: sppro_forms_per_page
 */
function sppro_load_admin() {
	global $sppro_hook;	
	
	$action = sppro_current_action();
	
	if ( isset( $_GET['action'] ) && -1 != $_GET['action'] ) {
		$action = $_GET['action'];
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		$action = $_REQUEST['action2'];
	}
	
	//foreach($_REQUEST as $r) $request = isset($request) ? $request .'<br/>'. $r : $r; 	
	//echo '<script>alert("Request '.$request.'");</script>';
	
	if ( 0 AND 'save' == $action ) {
		$id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '-1';
		check_admin_referer( 'wpcf7-save-contact-form_' . $id );

		if ( ! current_user_can( 'manage_options', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', 'sp-pro-txt-domain' ) );
		}

		$args = $_REQUEST;
		$args['id'] = $id;

		$args['title'] = isset( $_POST['post_title'] )
			? $_POST['post_title'] : null;

		$args['locale'] = isset( $_POST['wpcf7-locale'] )
			? $_POST['wpcf7-locale'] : null;

		$args['form'] = isset( $_POST['wpcf7-form'] )
			? $_POST['wpcf7-form'] : '';

		$args['mail'] = isset( $_POST['wpcf7-mail'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail'] )
			: array();

		$args['mail_2'] = isset( $_POST['wpcf7-mail-2'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail-2'] )
			: array();

		$args['messages'] = isset( $_POST['wpcf7-messages'] )
			? $_POST['wpcf7-messages'] : array();

		$args['additional_settings'] = isset( $_POST['wpcf7-additional-settings'] )
			? $_POST['wpcf7-additional-settings'] : '';

		$contact_form = wpcf7_save_contact_form( $args );

		if ( $contact_form && wpcf7_validate_configuration() ) {
			$config_validator = new WPCF7_ConfigValidator( $contact_form );
			$config_validator->validate();
			$config_validator->save();
		}

		$query = array(
			'post' => $contact_form ? $contact_form->id() : 0,
			'active-tab' => isset( $_POST['active-tab'] )
				? (int) $_POST['active-tab'] : 0,
		);

		if ( ! $contact_form ) {
			$query['message'] = 'failed';
		} elseif ( -1 == $id ) {
			$query['message'] = 'created';
		} else {
			$query['message'] = 'saved';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'wpcf7', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'copy' == $action ) {
		$id = empty( $_POST['post_ID'] )
			? absint( $_REQUEST['post'] )
			: absint( $_POST['post_ID'] );

		check_admin_referer( 'sppro-form_' . $id );

		if ( ! current_user_can( 'manage_options', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', 'sp-pro-txt-domain' ) );
		}
				
		$query = array();
		
		if ( $sppro_form = sppro_popup_form( $id ) ) {
			if( $new_sppro_form = $sppro_form->copy() ) {
				$query['post'] = $new_sppro_form->id();
				$query['message'] = 'created';		
			}
			else {
				$query['post'] = $new_sppro_form->id();
				$query['message'] = 'could not create';		
			}
		}
		else {
			$query['post'] = $id;
			$query['message'] = 'not a form.';		
		}
		
		$redirect_to = add_query_arg( $query, menu_page_url( 'sp-pro-popups', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( 'delete' == $action ) {
		$die = ''; 
		foreach($_REQUEST as $k=>$r) {
			$die .= '<br/>';
			$die .= is_array($r) ? $k .': '. print_r($r, true) : $k .': '. $r; 
		}
		//wp_die($die);
		if ( ! empty( $_POST['post_ID'] ) ) {
			check_admin_referer( 'wpcf7-delete-contact-form_' . $_POST['post_ID'] );
		} elseif ( ! is_array( $_REQUEST['post'] ) ) {
			check_admin_referer( 'wpcf7-delete-contact-form_' . $_REQUEST['post'] );
		} else {
			check_admin_referer( 'bulk-posts' );
		}

		$posts = empty( $_POST['post_ID'] )
			? (array) $_REQUEST['post']
			: (array) $_POST['post_ID'];

		$deleted = 0;

		foreach ( $posts as $post ) {
			$post = SPPRO_Forms::get_instance( $post );

			if ( empty( $post ) ) {
				continue;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You are not allowed to delete this item.', 'sp-pro-txt-domain' ) );
			}

			if ( ! $post->delete() ) {
				wp_die( __( 'Error in deleting.', 'sp-pro-txt-domain' ) );
			}

			$deleted += 1;
		}

		$query = array();

		if ( ! empty( $deleted ) ) {
			$query['message'] = 'deleted';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'sp-pro-popups', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	if ( ! class_exists( 'SPPRO_Forms_List_Table' ) ) {
		require_once SPPRO_PLUGIN_LIBS_DIR . '/classes/class-sppro-forms-list-table.php';
	}

	//add_filter( 'manage_' . $current_screen->id . '_columns', array( 'SPPRO_Forms_List_Table', 'define_columns' ) );

	add_screen_option( 'per_page', array(
		'default' => 20,
		'option' => 'sppro_forms_per_page' ) );
}

/**
 * Duplicate/Copy a Popup Form
 * Since Version 2.0
 * @param int $post_id = post to copy
 
 * @return $new_post_id or false
 * Should be moved SPPRO_Forms class
 * Called in Load Admin function's Copy action
 */
function sppro_copy_form($post_id) {
	global $wpdb; 
	$post = get_post( $post_id );
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
	
	if (isset( $post ) && $post != null) {
		
		if( 'sppro_forms' != $post->post_type )
			return false; 
 
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . ' Copy',
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		$new_post_id = wp_insert_post( $args );
 
		if(0) { // Taxonomies not needed
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
		
		return $new_post_id;  
	}
	else return false; 	
}