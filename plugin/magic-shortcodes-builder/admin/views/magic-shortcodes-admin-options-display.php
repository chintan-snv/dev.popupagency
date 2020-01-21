<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Content_Monetizer_Pro
 * @subpackage Content_Monetizer_Pro/admin/views
 */
 
 
 
 
 				
?>
<?php
    require_once("../../../../../wp-load.php");
    
    $args = array( 
			  'post_type' => 'ct_ms', 
			  'posts_per_page' => -1,
			  'orderby' => 'ASC',
              'post_staus'=>'publish'
			);
			$ms_query = new WP_Query( $args );

//echo dirname(__FILE__);
?>
<style>
    #ct_advertise_form label{min-width:150px; float: left; }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    
<script>
                jQuery( document ).ready(function() {
                    var ABSURL = '<?php echo get_home_url();?>';
                    
                    jQuery(document).on('click', '#ct_advertise_form input[type=radio]', function(e) {
                         
                        jQuery.post(
			ABSURL+'/wp-admin/admin-ajax.php', 
			{
				action:'getSelectedValue',
				data:jQuery(this).val()
			}, 
				function(jsontext){	
                    
				});    
                     
                    });
                    
                   
                    
});
              </script>
<div id="display_advertising_shortcodes" style="display:block;">
        
	  <form id="ct_advertise_form" name="ct_advertise_form" method="post">
         <?php 
          
          if ( $ms_query->have_posts() ) :
            $i=0;
            while ( $ms_query->have_posts() ) :        
                $ms_query->the_post();	         
                if($i==0){$activeclass='checked';$datafile="[magic_shortcodes id='".get_the_ID()."']";}else{$activeclass="";}
                $output .= '<label><input type="radio" name="ct_ms_shortcode" value="[magic_shortcodes id='.get_the_ID().']" '.$activeclass.'> '.get_the_title().'</label>';
            $i++;
            endwhile;
        endif;  
          wp_reset_query();
           $path = plugin_dir_path( __FILE__ ). '/';
           $myfile = fopen($path."shortcode.txt", "w") or die("Unable to open file!");
           @fwrite($myfile, $datafile);       
          
          
          ?>
          <div>
            <?php echo $output; ?>
          </div>          
      </form>
	</div>