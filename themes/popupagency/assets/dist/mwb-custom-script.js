function repeat(){

	 var parameterName1 = 'region[]';

                                tmp = [];
                                var urlparam = window.location.search.substring(1).split("&");
                                var region_count = 0;

                                for( var i=0; i< urlparam.length; i++ ){
                                    tmp = urlparam[i].split("=");
                                    if (decodeURIComponent(tmp[0]) === parameterName1){
                                        region_count++;
                                    }
                                }
                                var shtml = '<i class="fa fa-plus plus-sign"></i><i class="fa fa-minus minus-sign"></i>';
                                 console.log("##########", region_count);
                                if(region_count == 1){
                                    var newhtml = region_count+' '+frontend_popup_param.valda_plat+shtml;
                                    jQuery(document).find('.namn-Plats').html(newhtml);
                                }else if(region_count > 1){
                                    var newhtml = region_count+' '+frontend_popup_param.valda_plats+shtml;
                                    jQuery(document).find('.namn-Plats').html(newhtml);
                                }
                                if(region_count == 0){
                                    var newhtml = frontend_popup_param.plats+shtml;
                                    jQuery(document).find('.namn-Plats').html(newhtml);
                                }
}    
jQuery(document).ready(function() {
	
	var parameterName1 = 'region[]';

	tmp = [];
	var urlparam = window.location.search.substring(1).split("&");
	var region_count = 0;

	for( var i=0; i< urlparam.length; i++ ){
		tmp = urlparam[i].split("=");
		if (decodeURIComponent(tmp[0]) === parameterName1){
			region_count++;
		}
	}
	var shtml = '<i class="fa fa-plus plus-sign"></i><i class="fa fa-minus minus-sign"></i>';

	if(region_count == 1){
		var newhtml = region_count+' '+frontend_popup_param.valda_plat+shtml;
		jQuery(document).find('.namn-Plats').html(newhtml);
	}else if(region_count > 1){
		var newhtml = region_count+' '+frontend_popup_param.valda_plats+shtml;
		jQuery(document).find('.namn-Plats').html(newhtml);
	}
	if(region_count == 0){
		var newhtml = frontend_popup_param.plats+shtml;
		jQuery(document).find('.namn-Plats').html(newhtml);
	}
	

	jQuery(document).on('click','.mwb-filter-plat__tab a',function(e){
		e.preventDefault();
		
		jQuery(this).addClass('active');
		var selected_list = jQuery(this).parent().siblings();
		jQuery(selected_list).find('a').each(function(){
			jQuery(this).removeClass('active');
		});

		var mwbRegionName = jQuery(this).attr('data-region');
		jQuery('.mwb-filter-plat__con').each(function() {
			jQuery(this).removeClass('active');
			var mwbRegionContentId = jQuery(this).attr('id');
			if (mwbRegionName == mwbRegionContentId) {
				jQuery(this).addClass('active');
			}
		});
	});

	jQuery(document).on('click','.mwb-filter-plat__cat a',function(e){
		e.preventDefault();
		
		jQuery(this).addClass('active');
		//jQuery(this).addClass('mwb-activate-color');
		var selected_li = jQuery(this).parent().siblings();
		jQuery(selected_li).find('a').each(function(){
			jQuery(this).removeClass('active');
		});

		// if(jQuery(window).width() <= 1000){
		// 	jQuery(this).siblings().removeClass('mwb-activate-color');
		// }
		
		var mwbCatName = jQuery(this).attr('data-cat');
		jQuery('.mwb-filter-plat__sub').each(function() {
			jQuery(this).removeClass('active');
			var mwbCatContentId = jQuery(this).attr('id');
			if (mwbCatName == mwbCatContentId) {
				jQuery(this).addClass('active');
			}
		});
	});
	jQuery(document).on('click','.mwb-mobile-nav',function(e){
		e.preventDefault();
		
		jQuery(this).toggleClass('active');
		
		var mwbMcatName = jQuery(this).attr('data-cat');
		var mwbMcatContentId = jQuery(this).next().attr('id');
		if (mwbMcatName == mwbMcatContentId) {
			jQuery(this).next().toggleClass('active');
		}
	});
	jQuery(document).on('click','.form-group label',function(){
		
		if(jQuery(this).hasClass('namn-Plats')) {

			jQuery(this).next().find('.mwb-filter-plat').toggleClass('mwb-enable');
			jQuery(this).parent().parent().toggleClass('border-bottom');
			jQuery(this).find('.plus-sign').toggleClass('hideplus');
			var target = jQuery(this).next().find('.mwb-filter-plat').children('.mwb-filter-plat__region');
			jQuery(target).find('ul li').first().find('a').addClass('active');
			var region = jQuery(target).find('ul li').first().find('a').attr('data-region');

			var target1 = jQuery(this).next().find('.mwb-filter-plat').children('.mwb-filter-plat__con');
			
			jQuery(target1).each(function(){
					//console.log(region);
					if (jQuery(this).attr('id') == region) {
						jQuery(this).addClass('active');
						jQuery(this).find('.mwb-filter-plat__cat ul li').first().find('a').addClass('active');
						if(jQuery(window).width() <= 1000){
							jQuery(this).find('.mwb-mobile-nav').first().addClass('active');
						}
						var city = jQuery(this).find('.mwb-filter-plat__cat ul li').first().find('a').attr('data-cat');
						jQuery(this).find('.mwb-filter-plat__sub').each(function(){
							if(jQuery(this).attr('id') == city ){
								jQuery(this).addClass('active');
							}
						});
					}	
				});
		}
	});

	jQuery('body').on("click",function(e){
		
		if(e.target.id == 'finderListings'){
			e.stopPropagation();
		}
	});

	
	jQuery('body').on('click','.mwb-custom-cancel-button',function(ev){
		ev.preventDefault();
		
		jQuery(document).find('.mwb_custom_filters').removeClass('border-bottom');

		jQuery(document).find('#finderListings').removeClass('mwb-custom-overlay');
		jQuery(document).find('.hideplus').removeClass('hideplus');
		jQuery(this).parent().parent().hide();
		jQuery(document).find('.mwb-filter-plat').removeClass('mwb-enable');

	});

	jQuery('body').on('click','.c27-explore-search-button',function(e){
		jQuery(document).find('#finderListings').removeClass('mwb-custom-overlay');
		jQuery(document).find('.mwb_custom_filters').removeClass('border-bottom');
		jQuery(document).find('.hideplus').removeClass('hideplus');		
	});

	jQuery('div.form-group label').on("click",function(e) {
		var div_container = e.target;
		div_container = jQuery(div_container).parent().parent();
		
		if( !jQuery(this).hasClass('namn-Plats') && !jQuery(div_container).hasClass('mwb-filter-plat__sub') ){
			if( jQuery(document).find('.mwb-filter-plat').is(':visible') ){
				jQuery(document).find('.mwb-filter-plat').removeClass('mwb-enable');
			}

			if(jQuery('body').find('.hela-Plats').hasClass('border-bottom')){
				jQuery('body').find('.hela-Plats').removeClass('border-bottom')
			}
			if(jQuery('body').find('.hela-Plats').children().find('.namn-Plats .plus-sign').hasClass('hideplus')){
				jQuery('body').find('.hela-Plats').children().find('.namn-Plats .plus-sign').removeClass('hideplus');
			}
		}

	});

	jQuery('div.form-group label').on("click",function() {

		if(!jQuery(document).find('#finderListings').hasClass('mwb-custom-overlay')){
			if(jQuery(this).parent().hasClass('checkboxes-filter')){
				jQuery(document).find('#finderListings').addClass('mwb-custom-overlay');
			}

			if(jQuery(this).parent().hasClass('range-filter')){
				jQuery(document).find('#finderListings').addClass('mwb-custom-overlay');
			}
		}

		if(jQuery(this).parent().hasClass('checkboxes-filter')){
			if(!jQuery(this).hasClass('namn-Plats')){
				if(jQuery(document).find('#finderListings').hasClass('mwb-custom-overlay') && !jQuery(this).next().is(':visible')){
					jQuery(document).find('#finderListings').removeClass('mwb-custom-overlay');
				}
			}else if(jQuery(this).hasClass('namn-Plats')){
				
				if(jQuery(document).find('#finderListings').hasClass('mwb-custom-overlay') && jQuery(this).next().find('.mwb-filter-plat').hasClass('mwb-enable')){
					jQuery(document).find('#finderListings').removeClass('mwb-custom-overlay');
				}
			}
		}
		if(jQuery(this).parent().hasClass('range-filter')){
			if(jQuery(document).find('#finderListings').hasClass('mwb-custom-overlay') && !jQuery(this).next().is(':visible')){
				jQuery(document).find('#finderListings').removeClass('mwb-custom-overlay');
			}
		}

	});


	jQuery('body').on('click','#mwb_search_plats', function(ev){
		if(jQuery('body').find('.mwb-filter-plat').hasClass('mwb-enable')){
			jQuery('body').find('.mwb-filter-plat').removeClass('mwb-enable');
		}

		if(jQuery('body').find('#finderListings').hasClass('mwb-custom-overlay')){
			jQuery('body').find('#finderListings').removeClass('mwb-custom-overlay')
		}
	});

	jQuery('.header-bottom-wrapper .header-search .mi').on("click",function() {
		jQuery(this).parent('.header-search').toggleClass('active');
		if (jQuery(this).hasClass('search')) {
			jQuery(this).removeClass('search');
			jQuery(this).addClass('close');
		}
		else {
			jQuery(this).removeClass('close');
			jQuery(this).addClass('search');
		}
		jQuery(".header_mycustom.testmain").focus();
		jQuery("input[custom='data']").focus();
		
	});


	jQuery(".results-view").children(".col-md-12.grid-item").each(function( index ) {
		jQuery(this).addClass("my-listing-custom");
	});
	//

	jQuery( document ).ajaxStart(function() {
		//alert();
	   jQuery('.owl-stage').attr('style','transform: translate3d(-371px, 0px, 0px); transition: all 0.25s ease 0s; width: 2226px;');
	});


});
jQuery(window).load(function() {

	jQuery(document).find('#elementor-lightbox').css('display','none','important');
	

});







