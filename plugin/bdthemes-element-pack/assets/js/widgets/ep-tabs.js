( function( $, elementor ) {

	'use strict';

	var widgetTabs = function( $scope, $ ) {

		var $tabs = $scope.find( '.bdt-tabs' ),
            $tab = $tabs.find('.bdt-tab');
            
        if ( ! $tabs.length ) {
            return;
        }

        var tabID = $(location.hash);

        if (tabID.length > 0 && tabID.hasClass('bdt-tabs-item-title')) {
            $('html').animate({
                easing:  'slow',
                scrollTop: tabID.offset().top,
            }, 500, function() {
                bdtUIkit.tab($tab).show($(tabID).data('tab-index'));
            });  
        }

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-tabs.default', widgetTabs );
	});

}( jQuery, window.elementorFrontend ) );