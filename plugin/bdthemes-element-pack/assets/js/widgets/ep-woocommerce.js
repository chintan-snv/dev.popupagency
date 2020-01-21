( function( $, elementor ) {

	'use strict';

	var widgetWCCarousel = function( $scope, $ ) {

		var $wcCarousel = $scope.find( '.bdt-wc-carousel' );
				
        if ( ! $wcCarousel.length ) {
            return;
        }

		var $wcCarouselContainer = $wcCarousel.find('.swiper-container'),
			$settings 		 = $wcCarousel.data('settings');

		var swiper = new Swiper($wcCarouselContainer, $settings);

		if ($settings.pauseOnHover) {
			 $($wcCarouselContainer).hover(function() {
				(this).swiper.autoplay.stop();
			}, function() {
				(this).swiper.autoplay.start();
			});
		}

	};

	var widgetWCProductTable = function( $scope, $ ) {

		var $productTable = $scope.find( '.bdt-wc-products-skin-table' ),
            $settings 	  = $productTable.data('settings'),
            $table        = $productTable.find('> table');
            
        if ( ! $productTable.length ) {
            return;
        }

        $settings.language = window.ElementPackConfig.data_table.language;

        $($table).DataTable($settings);

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-wc-carousel.default', widgetWCCarousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-wc-carousel.wc-carousel-hidie', widgetWCCarousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-wc-products.bdt-table', widgetWCProductTable );
	});

}( jQuery, window.elementorFrontend ) );