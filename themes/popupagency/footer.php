<?php
// End of #c27-site-wrapper div.
printf('</div>');
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
	/**
	 * An Elementor Pro custom footer is available.
	 *
	 * @link  https://developers.elementor.com/theme-locations-api/migrating-themes/
	 * @since 2.0
	 */
} elseif ( function_exists( 'hfe_render_footer' ) && function_exists( 'get_hfe_footer_id' ) && get_hfe_footer_id() ) {
	/**
	 * An "Header, Footer & Blocks for Elementor" footer is available.
	 *
	 * @link  https://github.com/Nikschavan/header-footer-elementor/wiki/Adding-Header-Footer-Elementor-support-for-your-theme
	 * @since 2.1
	 */
	hfe_render_footer();
} else {
	/**
	 * No custom footers detected, use the default theme footer.
	 *
	 * @since 1.0
	 */
	$show_footer = c27()->get_setting( 'footer_show', true ) !== false;
	if ( $show_footer && isset( $GLOBALS['c27_elementor_page'] ) && $page = $GLOBALS['c27_elementor_page'] ) {
		if ( ! $page->get_settings('c27_hide_footer') ) {
			$args = [
				'show_widgets'      => $page->get_settings('c27_footer_show_widgets'),
				'show_footer_menu'  => $page->get_settings('c27_footer_show_footer_menu'),
			];
			c27()->get_section('footer', ($page->get_settings('c27_customize_footer') == 'yes' ? $args : []));
		}
	} elseif ( $show_footer ) {
		c27()->get_section('footer');
	}
}
// MyListing footer hooks.
do_action( 'case27_footer' );
do_action( 'mylisting/get-footer' );
wp_footer();
?>
</body>
</html>
<script type="text/javascript">
	jQuery(".show-map").click(function(){
     jQuery(".results-view.grid").animate({"height" : "1441"}, 500);
	});


var inptxt = jQuery('div#c27-header-search-form_custm_form .header-search input');
jQuery(inptxt).on('focusin', 
   function(){
   jQuery(".elementor.elementor-6854").addClass("custom_overlay");
   }).on('focusout', function(){
     jQuery(".elementor.elementor-6854").removeClass('custom_overlay');
  });



function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ' ' + '$2');
    }
    return x1 + x2;
}
jQuery("#pris-inform-1 .extra-details li").each(function() {
   var self = $(this), text = self.html();
   self.html(addCommas(text));
});
jQuery('ul.instant-results-list.ajax-results li').each(function (){
    var self = $(this), text = self.html();
   self.html(addCommas(text));
});
  jQuery(function(){
jQuery('.header-bottom-wrapper .header-search .mi').click(function() {
  setTimeout("jQuery('#c27-header-search-form input').focus(); jQuery('#c27-header-search-form .dark-forms.header-search.search-shortcode-light.active  input').click();", 1000);
  //  jQuery("input[custom='data']").focus();
});
});


//page option scripts
if ( jQuery('.home').length ) {
    jQuery( '.scroll-logo > img' ).replaceWith( '<img src=https://dev.popupagency.se/wp-content/uploads/2019/07/logo-black.png>' );
}
jQuery("#search-form").on( 'scroll', function(){
   console.log("scrolled");
});
jQuery(".knapp-p a").click(function(event){
jQuery('.resu-Pris').hide();
jQuery('.resu-Yta').hide();
jQuery('.resu-Amenities').hide();
jQuery('.resu-Lokaltyp').hide();
})
jQuery('.contact-container-info2 #listing_tab_bokningsforfragan_toggle').click(function(event){
jQuery('html,body').scrollTop(550);
})
jQuery('.contact-container-side #listing_tab_bokningsforfragan_toggle').click(function(event){
jQuery('html,body').scrollTop(550);
})
jQuery('#contact-but').click(function(event){
if(jQuery('#contact-container-section').hasClass('contact-container')){
  jQuery('#contact-container-section').removeClass('contact-container');
  jQuery('#contact-container-section').addClass('contact-container-show');
}else{
   jQuery('#contact-container-section').removeClass('contact-container-show');
  jQuery('#contact-container-section').addClass('contact-container');
}
})
jQuery('.resu-Pris').hide();
jQuery('.resu-Yta').hide();
jQuery('.resu-Amenities').hide();
jQuery('.resu-Lokaltyp').hide();
jQuery(".namn-Pris").click(function(event){
  event.stopPropagation();
  jQuery(".resu-Pris").toggle();
  jQuery('.resu-Yta').hide();
  jQuery('.resu-Amenities').hide();
  jQuery('.resu-Lokaltyp').hide();
  if(jQuery('.hela-Pris').hasClass('border-bottom')){
  jQuery('.hela-Pris').removeClass('border-bottom');
  }else{
  jQuery('.hela-Pris').addClass('border-bottom');
  }
  jQuery(".hela-Lokaltyp").removeClass('border-bottom');
  jQuery(".hela-Amenities").removeClass('border-bottom');
  jQuery(".hela-Yta").removeClass('border-bottom');
  if (jQuery(".namn-Pris .plus-sign").hasClass("hideplus")) {
  jQuery(".namn-Pris .plus-sign").removeClass('hideplus');
  }else{
  jQuery(".namn-Pris .plus-sign").addClass('hideplus');
  }
  jQuery(".namn-Yta .plus-sign").removeClass('hideplus');
  jQuery(".namn-Amenities .plus-sign").removeClass('hideplus');
  jQuery(".namn-Lokaltyp .plus-sign").removeClass('hideplus');
});
jQuery(".namn-Yta").click(function(event){
  event.stopPropagation();
  jQuery(".resu-Yta").toggle();
  jQuery('.resu-Pris').hide();
 jQuery('.resu-Amenities').hide();
  jQuery('.resu-Lokaltyp').hide();
  if(jQuery('.hela-Yta').hasClass('border-bottom')){
  jQuery('.hela-Yta').removeClass('border-bottom');
  }else{
  jQuery('.hela-Yta').addClass('border-bottom');
  }
  jQuery(".hela-Amenities").removeClass('border-bottom');
  jQuery(".hela-Pris").removeClass('border-bottom');
  jQuery(".hela-Lokaltyp").removeClass('border-bottom');
  if (jQuery(".namn-Yta .plus-sign").hasClass("hideplus")) {
  jQuery(".namn-Yta .plus-sign").removeClass('hideplus');
  }else{
  jQuery(".namn-Yta .plus-sign").addClass('hideplus');
  }
  jQuery(".namn-Pris .plus-sign").removeClass('hideplus');
  jQuery(".namn-Amenities .plus-sign").removeClass('hideplus');
  jQuery(".namn-Lokaltyp .plus-sign").removeClass('hideplus');
});
jQuery(".namn-Amenities").click(function(event){
  event.stopPropagation();
  jQuery(".resu-Amenities").toggle();
  jQuery('.resu-Yta').hide();
  jQuery('.resu-Pris').hide();
  jQuery('.resu-Lokaltyp').hide();
  if(jQuery('.hela-Amenities').hasClass('border-bottom')){
  jQuery('.hela-Amenities').removeClass('border-bottom');
  }else{
  jQuery('.hela-Amenities').addClass('border-bottom');
  }
  jQuery(".hela-Pris").removeClass('border-bottom');
  jQuery(".hela-Yta").removeClass('border-bottom');
  jQuery(".hela-Lokaltyp").removeClass('border-bottom');
  if (jQuery(".namn-Amenities .plus-sign").hasClass("hideplus")) {
  jQuery(".namn-Amenities .plus-sign").removeClass('hideplus');
  }else{
  jQuery(".namn-Amenities .plus-sign").addClass('hideplus');
  }
  jQuery(".namn-Pris .plus-sign").removeClass('hideplus');
  jQuery(".namn-Yta .plus-sign").removeClass('hideplus');
  jQuery(".namn-Lokaltyp .plus-sign").removeClass('hideplus');
});
jQuery(".namn-Lokaltyp").click(function(event){
  event.stopPropagation();
  jQuery(".resu-Lokaltyp").toggle();
  jQuery('.resu-Yta').hide();
  jQuery('.resu-Pris').hide();
  jQuery('.resu-Amenities').hide();
  if(jQuery('.hela-Lokaltyp').hasClass('border-bottom')){
  jQuery('.hela-Lokaltyp').removeClass('border-bottom');
  }else{
  jQuery('.hela-Lokaltyp').addClass('border-bottom');
  }
  jQuery(".hela-Pris").removeClass('border-bottom');
  jQuery(".hela-Yta").removeClass('border-bottom');
  jQuery(".hela-Amenities").removeClass('border-bottom');
  if (jQuery(".namn-Lokaltyp .plus-sign").hasClass("hideplus")) {
  jQuery(".namn-Lokaltyp .plus-sign").removeClass('hideplus');
  }else{
  jQuery(".namn-Lokaltyp .plus-sign").addClass('hideplus');
  }
  jQuery(".namn-Pris .plus-sign").removeClass('hideplus');
  jQuery(".namn-Yta .plus-sign").removeClass('hideplus');
  jQuery(".namn-Amenities .plus-sign").removeClass('hideplus');
});
jQuery(document).click(function(e){
  if(jQuery(e.target).closest(".resu-Pris").length===0){
    jQuery(".resu-Pris").hide();
    jQuery(".hela-Pris").removeClass('border-bottom');
    jQuery(".namn-Pris .plus-sign").removeClass('hideplus');
  };
});
jQuery(document).click(function(e){
  if(jQuery(e.target).closest(".resu-Yta").length===0){
    jQuery(".resu-Yta").hide();
    jQuery(".hela-Yta").removeClass('border-bottom');
    jQuery(".namn-Yta .plus-sign").removeClass('hideplus');
  };
});
jQuery(document).click(function(e){
  if(jQuery(e.target).closest(".resu-Amenities").length===0){
    jQuery(".resu-Amenities").hide();
    jQuery(".hela-Amenities").removeClass('border-bottom');
    jQuery(".namn-Amenities .plus-sign").removeClass('hideplus');
  };
});
jQuery(document).click(function(e){
  if(jQuery(e.target).closest(".resu-Lokaltyp").length===0){
    jQuery(".resu-Lokaltyp").hide();
    jQuery(".hela-Lokaltyp").removeClass('border-bottom');
    jQuery(".namn-Lokaltyp .plus-sign").removeClass('hideplus');
  };
});
jQuery(document).ready(function($) {
jQuery('.show-map').click(function(){
    jQuery(this).addClass('active');
    jQuery('.hide-map').removeClass('active');
    jQuery("#finderMap").addClass("No-map");
    jQuery("#container-search").addClass("Full-wd");
    jQuery("#finderListings").addClass("show-three");
    jQuery("#finderListings").removeClass("show-two");    
})
jQuery('.hide-map').click(function(){
    jQuery(this).addClass('active');
    jQuery('.show-map').removeClass('active');
    jQuery("#finderMap").removeClass("No-map");
    jQuery("#container-search").removeClass("Full-wd");
    jQuery("#finderListings").removeClass("show-three");
    jQuery("#finderListings").addClass("show-two");
})
});
jQuery(document).ready(function($) {
  var firsttime = 1;
  /*jQuery(document).ajaxStart(function(e){
    //console.log("test123333333333333333");
     //jQuery('.owl-stage').attr("style" ,'transform: translate3d(-371px, 0px, 0px); transition: all 0.25s ease 0s; width: 2226px;');
  });*/

  // setTimeout(function(){ jQuery('.owl-stage').attr("style" ,'transform: translate3d(-371px, 0px, 0px); transition: all 0.25s ease 0s; width: 2226px;'); }, 2000);
 
 if( jQuery("#finderSearch").length )
 {
    jQuery(document).ajaxComplete(function(e){
      setTimeout(function(){ 
        // jQuery('.owl-stage').attr("style" ,'transform: translate3d( '+jQuery(".owl-carousel").width()+', 0px, 0px); transition: all 0.25s ease 0s; width: 2226px;'); 

     if(firsttime)
      {
        //alert();
        //$('.job-manager-pagination ul li:nth-child(2) a').trigger('click');
        if( jQuery('.job-manager-pagination ul li span[data-page=1]').length ){
         
        //  jQuery('.job-manager-pagination ul li span[data-page=1]').trigger('click');
         // console.log("asdfaddddddddddddd", jQuery('.job-manager-pagination ul li span[data-page=1]'));
        }else{
          if( jQuery('.job-manager-pagination ul li a[data-page=1]').length == 1) 
          {
             jQuery('.job-manager-pagination ul li a[data-page=1]').trigger('click');
             //console.log("asdfaddddddddddddd", jQuery('.job-manager-pagination ul li a[data-page=1]') );
          }else{
            jQuery(jQuery('.job-manager-pagination ul li a[data-page=1]')[0] ).trigger('click');
            //console.log("asdfaddddddddddddd", jQuery(jQuery('.job-manager-pagination ul li a[data-page=1]')[0] )  );
          }
          window.pagex = 1;
          
        } 
        firsttime=0; 
     }

    }, 2000);
   // console.log("working##################",);
     // jQuery('.owl-stage').attr("style" ,'transform: translate3d(-371px, 0px, 0px); transition: all 0.25s ease 0s; width: 2226px;');
    }); 
}
// $('.job-manager-pagination').find('li')[1].children("a").trigger("click");

//jQuery('.more').onload(function(e){
 
//});
  jQuery('.more').click(function(e){  
    this.backup = jQuery(".results-view").html(); 
  this.cur = jQuery('.job-manager-pagination').find('.current');
   
    if(this.cur.parent("li").next("li").length)
    {
      this.cur.parent("li").next("li").children("a").trigger("click");
    }
    else
    {
      jQuery(this).children("#text").html("No More Listing");
    }
    //results-view
  });

});



jQuery("#pris-inform-1 ul").append("<p class='work-hours-timezone'><em>Alla priser är exklusiv moms</em></p>");
//page option scripts

</script>
<style type="text/css">
  
.instant-results-list li.ir-cat{
  color: #000000!important;!important;
}
.instant-results-list li:nth-child(odd) {
    background-color: #f3f4f5;
}
.instant-results-list li:nth-child(even) {
    background-color: #ffffff;
}
 #c27-header-search-form .instant-results{
        height: auto;
        overflow-y: auto;
        max-height: 400px;
    }
    #c27-header-search-form .instant-results::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

#c27-header-search-form .instant-results::-webkit-scrollbar
{
    width: 6px;
    background-color: #F5F5F5;
}

#c27-header-search-form .instant-results::-webkit-scrollbar-thumb
{
    background-color: #555555;
}
</style>