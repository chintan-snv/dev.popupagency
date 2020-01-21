( function( $, elementor ) {

	'use strict';

	var widgetAnimatedHeading = function( $scope, $ ) {

		var $heading = $scope.find( '.bdt-heading > *' ),
            $animatedHeading = $heading.find( '.bdt-animated-heading' ),
            $settings = $animatedHeading.data('settings');
            
        if ( ! $heading.length ) {
            return;
        }

        if ( $settings.layout === 'animated' ) {
            $($animatedHeading).Morphext($settings);
        } else if ( $settings.layout === 'typed' ) {
            var animateSelector = $($animatedHeading).attr('id');
            var typed = new Typed('#'+animateSelector, $settings);
        }

        $($heading).animate({
            easing:  'slow',
            opacity: 1
        }, 500 );

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-animated-heading.default', widgetAnimatedHeading );
	});

}( jQuery, window.elementorFrontend ) );