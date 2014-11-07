( function( window, undefined ) {
	'use strict';

	var bind, watchIframe,
		iframes = document.getElementsByTagName( 'iframe' );

	bind = function( el, eventType, handler ) {
		if ( el.addEventListener ) {
			el.addEventListener( eventType, handler, false );
		} else if ( el.attachEvent ) {
			el.attachEvent( 'on' + eventType, handler );
		}
	};

	bind( window, 'message', function( e, x ) {
		var data, iframe,
			parts = e.data.split( ':' );
			console.log( JSON.parse( e.data ) );

		if ( 'size' === parts[0] ) {
			data = parts[1].split( ',' );

			iframe = iframes[ data[0] ];
			if ( 'undefined' !== typeof iframe ) {
				iframe.height = data[2];
				iframe.scrolling = 'no';
			}
		}
	} );

	watchIframe = function( i ) {
		iframes[ i ].onload = iframes[ i ].onreadystatechange = function() {
			if ( this.readyState && 'complete' !== this.readyState && 'loaded' !== this.readyState ) {
				return;
			}

			setInterval( function() {
				// Send a message to the iframe to ask it to return its size.
				iframes[ i ].contentWindow.postMessage( 'size?' + i, '*' );
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
