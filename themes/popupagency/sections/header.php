<?php
$data = c27()->merge_options([
	'logo'                    => c27()->get_site_logo(),
	'skin'                    => c27()->get_setting('header_skin', 'dark'),
	'style'                   => c27()->get_setting('header_style', 'default'),
	'fixed'                   => c27()->get_setting('header_fixed', true),
	'scroll_skin'             => c27()->get_setting('header_scroll_skin', 'dark'),
	'scroll_logo'             => c27()->get_setting('header_scroll_logo') ? c27()->get_setting('header_scroll_logo')['sizes']['medium'] : false,
	'border_color'            => c27()->get_setting('header_border_color', 'rgba(29, 29, 31, 0.95)'),
	'menu_location'           => c27()->get_setting('header_menu_location', 'right'),
	'background_color'        => c27()->get_setting('header_background_color', 'rgba(29, 29, 31, 0.95)'),
	'show_search_form'        => c27()->get_setting('header_show_search_form', true),
	'show_call_to_action'     => c27()->get_setting('header_show_call_to_action_button', false),
	'scroll_border_color'     => c27()->get_setting('header_scroll_border_color', 'rgba(29, 29, 31, 0.95)'),
	'search_form_placeholder' => c27()->get_setting('header_search_form_placeholder', 'Type your search...'),
	'scroll_background_color' => c27()->get_setting('header_scroll_background_color', 'rgba(29, 29, 31, 0.95)'),
	'blend_to_next_section'   => false,
	'is_edit_mode'            => false,
	], $data);

$header_classes = ['c27-main-header', 'header', "header-style-{$data['style']}", "header-{$data['skin']}-skin", "header-scroll-{$data['scroll_skin']}-skin", 'hide-until-load', 'header-scroll-hide'];

if ( $data['fixed'] ) {
	$header_classes[] = 'header-fixed';
}

$header_classes[] = sprintf( 'header-menu-%s', $data['menu_location'] === 'right' ? 'left' : 'left' );

$GLOBALS['case27_custom_styles'] .= '.c27-main-header .logo img { height: ' . c27()->get_setting( 'header_logo_height', 38 ) . 'px; }';

if ($data['background_color']) {
	if (!isset($GLOBALS['case27_custom_styles'])) $GLOBALS['case27_custom_styles'] = '';

	$GLOBALS['case27_custom_styles'] .= '.c27-main-header:not(.header-scroll) .header-skin ';
	$GLOBALS['case27_custom_styles'] .= '{ background: ' . $data['background_color'] . ' !important; }';
}

if ($data['border_color']) {
	if (!isset($GLOBALS['case27_custom_styles'])) $GLOBALS['case27_custom_styles'] = '';

	$GLOBALS['case27_custom_styles'] .= '.c27-main-header:not(.header-scroll) .header-skin { border-bottom: 1px solid ' . $data['border_color'] . ' !important; } ';
}

if ($data['scroll_background_color']) {
	if (!isset($GLOBALS['case27_custom_styles'])) $GLOBALS['case27_custom_styles'] = '';

	$GLOBALS['case27_custom_styles'] .= '.c27-main-header.header-scroll .header-skin';
	$GLOBALS['case27_custom_styles'] .= '{ background: ' . $data['scroll_background_color'] . ' !important; }';
}

if ($data['scroll_border_color']) {
	if (!isset($GLOBALS['case27_custom_styles'])) $GLOBALS['case27_custom_styles'] = '';

	$GLOBALS['case27_custom_styles'] .= '.c27-main-header.header-scroll .header-skin { border-bottom: 1px solid ' . $data['scroll_border_color'] . ' !important; } ';
}
?>

<header class="<?php echo esc_attr( join( ' ', $header_classes ) ) ?>">
	<div class="header-skin"></div>
	<div class="header-container">
		<div class="header-top container-fluid">
			<div class="mobile-menu">
				<a href="#main-menu">
					<div>
						<div class="mobile-menu-lines">
							<i class="material-icons">menu</i>
						</div>
					</div>
				</a>
			</div>

			<div class="logo">
				<?php if ($data['logo']): ?>
					<?php if ($data['scroll_logo']): ?>
						<a href="<?php echo esc_url( home_url('/') ) ?>" class="scroll-logo">
							<img src="<?php echo esc_url( $data['scroll_logo'] ) ?>">
						</a>
					<?php endif ?>

					<a href="<?php echo esc_url( home_url('/') ) ?>" class="static-logo">
						<img src="<?php echo esc_url( $data['logo'] ) ?>">
					</a>
				<?php else: ?>
					<a href="<?php echo esc_url( home_url('/') ) ?>" class="header-logo-text">
						<?php echo esc_attr( get_bloginfo('sitename') ) ?>
					</a>
				<?php endif ?>
			</div>
		</div>
		<div class="container-fluid header-bottom">


			<div class="header-bottom-wrapper row">
				<?php if ($data['show_search_form']): ?>
					<?php c27()->get_partial('header-search-form', ['placeholder' => $data['search_form_placeholder']]) ?>
				<?php endif ?>

				<div class="i-nav">

					<div class="mobile-nav-head">

						<div class="mnh-close-icon">
							<a href="#close-main-menu">
								<i class="material-icons">menu</i>
							</a>
						</div>
					</div>


					<?php echo str_replace('<ul class="sub-menu"', '<div class="submenu-toggle"><i class="material-icons">arrow_drop_down</i></div><ul class="sub-menu i-dropdown"', wp_nav_menu([
						'echo' => false,
						'theme_location' => 'right-menu',
						'container' => false,
						'menu_class' => 'main-menu',
						'items_wrap' => '<ul id="%1$s" class="%2$s main-nav">%3$s</ul>'
						]));
					echo str_replace('<ul class="sub-menu"', '<div class="submenu-toggle"><i class="material-icons">arrow_drop_down</i></div><ul class="sub-menu i-dropdown"', wp_nav_menu([
						'echo' => false,
						'theme_location' => 'left-menu',
						'container' => false,
						'menu_class' => 'main-menu left-float-class',
						'items_wrap' => '<ul id="%1$s" class="%2$s main-nav">%3$s</ul>'
						]));
						?>


						<div class="mobile-nav-button">
							<?php require locate_template( 'partials/header/call-to-action.php' ); ?>
						</div>

					</div>
					<div class="i-nav-overlay"></div>
				</div>
			</div>
		</div>
	</header>
	<section id="mwb-custom-search-box">
		<?php 
		$form = '<form role="search" method="GET" id="mwb-custom-searchform" action="' . home_url( '/' ) . '" >
			<div class="mwb_custom_search_wrapper">
				<label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Hitta din lokal här..." />
				<div id="mwb-searchsubmit">
					<i class="mi search" id="mwb-custom-submit"></i>
					<input type="submit" value="" />
				</div>
			</div>
		</form>';
	echo $form;
	?>
</section>

<?php if ( ! $data['blend_to_next_section'] ): ?>
	<div class="c27-top-content-margin"></div>
<?php endif ?>

<?php if ( $data['is_edit_mode'] ): ?>
	<script type="text/javascript">case27_ready_script(jQuery);</script>
<?php endif ?>