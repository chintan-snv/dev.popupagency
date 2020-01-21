( function( $ ) {

	'use strict';

	if ( typeof _wpcf7 === 'undefined' || _wpcf7 === null ) {
		// return;
	}

	$( function() {
		var welcomePanel = $( '#welcome-panel' );
		var updateWelcomePanel;
		

		updateWelcomePanel = function( visible ) {
			var closing; 
			closing = $.post( ajaxurl, 
				{
					action: 'sppro-update-admin-welcome-panel',
					visible: visible,
					adminwelcomepanelnonce: $( '#adminwelcomepanelnonce' ).val()
				});
				
			closing.done(function( response, textStatus, jqXHR ) {
				console.log('Closed: '+response);
			});
			closing. fail(function( response, textStatus, jqXHR ) {
				console.log('Error Closing: '+response);
			});
		};

		$( 'a.welcome-panel-close', welcomePanel ).click( function( event ) {
			event.preventDefault();
			welcomePanel.addClass( 'hidden' );
			if( $(this).data('closing')=='permanent' ) {
				updateWelcomePanel( 0 );
			}
		} );
		
	});

} )( jQuery );