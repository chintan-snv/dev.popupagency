( function( $, elementor ) {

	'use strict';

	var widgetOpenStreetMap = function( $scope, $ ) {

		var $openStreetMap = $scope.find( '.bdt-open-street-map' ),
            settings       = $openStreetMap.data('settings'),
            markers        = $openStreetMap.data('map_markers');

        if ( ! $openStreetMap.length ) {
            return;
        }

        var avdOSMap = L.map($openStreetMap[0], {
                zoomControl: settings.zoomControl,
                scrollWheelZoom: false
            }).setView([
                    settings.lat,
                    settings.lng
                ], 
                settings.zoom
            );

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + settings.osmAccessToken, {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(avdOSMap);

        var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [38, 95],
                iconAnchor:   [22, 94],
                shadowAnchor: [4, 62],
                popupAnchor:  [-3, -76]
            }
        });

        for (var i in markers) {
            var greenIcon = new LeafIcon({iconUrl: markers[i]['iconUrl'] });
            L.marker([markers[i]['lat'], markers[i]['lng']], {icon: greenIcon}).bindPopup(markers[i]['infoWindow']).addTo(avdOSMap);
        }

	};


	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bdt-open-street-map.default', widgetOpenStreetMap );
	});

}( jQuery, window.elementorFrontend ) );