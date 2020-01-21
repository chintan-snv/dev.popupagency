( function( $, elementor ) {

	'use strict';

	// Accordion
	var widgetAccordion = function( $scope, $ ) {

		var $accordion = $scope.find( '.bdt-accordion' );
				
        if ( ! $accordion.length ) {
            return;
        }

        var acdID = $(location.hash);

        if (acdID.length > 0 && acdID.hasClass('bdt-accordion-title')) {
            $('html').animate({
                easing:  'slow',
                scrollTop: acdID.offset().top,
            }, 500, function() {
                bdtUIkit.accordion($accordion).toggle($(acdID).data('accordion-index'), true);
            });  
        }

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-accordion.default', widgetAccordion );
	});

}( jQuery, window.elementorFrontend ) );