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
<div class="inside hidden">
	<?php if( get_option( 'ct_mcb_verified_purchase' ) != 0) { ?>
	<div class="ms-panel">        
        <div class="ms-panel-div">
			You First Need to activate your license by <a href="edit.php?post_type=ct_ms&page=msb_settings_panel">clicking here</a>.
			  
        </div>
    </div>	
	<?php 
		return;
		}
	?>	
	
    <div class="ms-panel">        
        <div class="ms-panel-div">
            <label for="width" style="margin-right:20px;"><?php _e('HTML/PHP Template','magic-shortcodes')?></label>
            <div class="control-input">
                <textarea class="" id="ct_ms_html" name="ct_ms_html" rows="10" cols="10" data-editor="xml" data-gutter="1"><?php echo get_text_value(get_the_ID(),'ct_ms_html','')?></textarea>          
            </div>        
        </div>
    </div>
</div>