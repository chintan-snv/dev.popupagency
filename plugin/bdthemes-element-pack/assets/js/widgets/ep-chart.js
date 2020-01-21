( function( $, elementor ) {

	'use strict';

	var widgetChart = function( $scope, $ ) {

		var	$chart    	  = $scope.find( '.bdt-chart' ),
            $chart_canvas = $chart.find( '> canvas' ),
            settings      = $chart.data('settings');

        if ( ! $chart.length ) {
            return;
        }

        elementorFrontend.waypoint( $chart_canvas, function() {
            var $this   = $( this ),
                ctx     = $this[0].getContext('2d'),
                myChart = new Chart(ctx, settings);
        }, {
            offset: 'bottom-in-view'
        } );

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-chart.default', widgetChart );
	});

}( jQuery, window.elementorFrontend ) );