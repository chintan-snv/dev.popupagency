( function ( $ ) {
    
    "use strict";
    
   
    $( 'form#det-settings' ).on( 'submit', function(e) {
		e.preventDefault();
		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
				action: 'det_settings',
				fields: $( 'form#det-settings' ).serialize()
			},
            success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something Wrong!',
				);
			}
		} );

	} );
    
} )( jQuery );