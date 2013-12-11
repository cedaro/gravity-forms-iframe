( function( window, $, undefined ) {
	'use strict';

	// Document ready.
	$( function() {
		var $checkbox = $( '#is_enabled', '#tab_gfiframe' ),
			// Rows not including the 'is_enabled' setting and the save button.
			$settingsRows = $checkbox.closest( 'tbody' ).find( 'tr[id^="gaddon"]' ).not( $checkbox.closest( 'tr' ) ).find( 'th, td' );

		// Show or hide the settings on load based on the enabled state.
		$settingsRows.toggle( $checkbox.is( ':checked' ) );

		$checkbox.on( 'click', function() {
			if ( $checkbox.is( ':checked' ) ) {
				$settingsRows.slideDown( 'fast' );
			} else {
				$settingsRows.slideUp( 'fast' );
			}
		} );
	} );
} )( this, jQuery );