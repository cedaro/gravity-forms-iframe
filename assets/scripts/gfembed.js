( function( window, undefined ) {
	'use strict';

	var iframes = document.getElementsByTagName( 'iframe' );

	window.addEventListener( 'message', function( e ) {
		var iframe;

		if ( 'size' === e.data.message ) {
			iframe = iframes[ e.data.index ];

			if ( 'undefined' !== typeof iframe ) {
				iframe.height = parseInt( e.data.height, 10 );
				iframe.scrolling = 'no';
			}
		}
	} );

	function watchIframe( i ) {
		iframes[ i ].onload = iframes[ i ].onreadystatechange = function() {
			if ( this.readyState && 'complete' !== this.readyState && 'loaded' !== this.readyState ) {
				return;
			}

			setInterval( function() {
				// Send a message to the iframe to ask it to return its size.
				iframes[ i ].contentWindow.postMessage({
					message: 'size',
					index: i
				}, '*' );
			}, 500 );
		};
	};

	if ( iframes.length ) {
		for ( var i = 0; i < iframes.length; i ++ ) {
			if ( -1 === iframes[ i ].className.indexOf( 'gfiframe' ) ) {
				continue;
			}

			watchIframe( i );
		}
	}
} )( this );
