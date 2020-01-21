<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="ms-tabs" class="ms-tabs">	   
	<h2 class="nav-tab-wrapper current">
		<a class="nav-tab nav-tab-active" href="javascript:;"><?php _e('CSS','magic-shortcodes')?></a>		
		<a class="nav-tab" href="javascript:;"><?php _e('JS','magic-popups')?></a>
        <a class="nav-tab" href="javascript:;"><?php _e('HTML/PHP Template','magic-shortcodes')?></a>
	</h2>
    <?php include_once plugin_dir_path( __FILE__ ). 'css.php'; ?>
    <?php include_once plugin_dir_path( __FILE__ ). 'js.php'; ?>
    <?php include_once plugin_dir_path( __FILE__ ). 'html.php'; ?>
</div>