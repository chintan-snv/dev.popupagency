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
    <div class="ms-panel">        
        <div class="ms-panel-div">
            <label for="width" style="margin-right:20px;"><?php _e('JS','magic-shortcodes')?></label>
            <div class="control-input">
                <textarea class="" id="ct_ms_js" name="ct_ms_js" rows="10" cols="10" data-editor="xml" data-gutter="1"><?php echo get_text_value(get_the_ID(),'ct_ms_js','')?></textarea>          
            </div>        
        </div>
    </div>
</div>