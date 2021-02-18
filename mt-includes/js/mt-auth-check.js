/**
 * Interim login dialog.
 *
 * @output mt-includes/js/mt-auth-check.js
 */

( function( $ ) {
	var wrap,
		tempHidden,
		tempHiddenTimeout;

	/**
	 * Shows the authentication form popup.
	 *
	 * @since 3.6.0
	 * @private
	 */
	function show() {
		var parent = $( '#mt-auth-check' ),
			form = $( '#mt-auth-check-form' ),
			noframe = wrap.find( '.mt-auth-fallback-expired' ),
			frame, loaded = false;

		if ( form.length ) {
			// Add unload confirmation to counter (frame-busting) JS redirects.
			$( window ).on( 'beforeunload.mt-auth-check', function( event ) {
				event.originalEvent.returnValue = window.mt.i18n.__( 'Your session has expired. You can log in again from this page or go to the login page.' );
			});

			frame = $( '<iframe id="mt-auth-check-frame" frameborder="0">' ).attr( 'title', noframe.text() );
			frame.on( 'load', function() {
				var height, body;

				loaded = true;
				// Remove the spinner to avoid unnecessary CPU/GPU usage.
				form.removeClass( 'loading' );

				try {
					body = $( this ).contents().find( 'body' );
					height = body.height();
				} catch( er ) {
					wrap.addClass( 'fallback' );
					parent.css( 'max-height', '' );
					form.remove();
					noframe.focus();
					return;
				}

				if ( height ) {
					if ( body && body.hasClass( 'interim-login-success' ) ) {
						hide();
					} else {
						parent.css( 'max-height', height + 40 + 'px' );
					}
				} else if ( ! body || ! body.length ) {
					// Catch "silent" iframe origin exceptions in WebKit
					// after another page is loaded in the iframe.
					wrap.addClass( 'fallback' );
					parent.css( 'max-height', '' );
					form.remove();
					noframe.focus();
				}
			}).attr( 'src', form.data( 'src' ) );

			form.append( frame );
		}

		$( 'body' ).addClass( 'modal-open' );
		wrap.removeClass( 'hidden' );

		if ( frame ) {
			frame.focus();
			/*
			 * WebKit doesn't throw an error if the iframe fails to load
			 * because of "X-Frame-Options: DENY" header.
			 * Wait for 10 seconds and switch to the fallback text.
			 */
			setTimeout( function() {
				if ( ! loaded ) {
					wrap.addClass( 'fallback' );
					form.remove();
					noframe.focus();
				}
			}, 10000 );
		} else {
			noframe.focus();
		}
	}

	/**
	 * Hides the authentication form popup.
	 *
	 * @since 3.6.0
	 * @private
	 */
	function hide() {
		var adminpage = window.adminpage,
			mt        = window.mt;

		$( window ).off( 'beforeunload.mt-auth-check' );

		// When on the Edit Post screen, speed up heartbeat
		// after the user logs in to quickly refresh nonces.
		if ( ( adminpage === 'post-php' || adminpage === 'post-new-php' ) && mt && mt.heartbeat ) {
			mt.heartbeat.connectNow();
		}

		wrap.fadeOut( 200, function() {
			wrap.addClass( 'hidden' ).css( 'display', '' );
			$( '#mt-auth-check-frame' ).remove();
			$( 'body' ).removeClass( 'modal-open' );
		});
	}

	/**
	 * Set or reset the tempHidden variable used to pause showing of the modal
	 * after a user closes it without logging in.
	 *
	 * @since 5.5.0
	 * @private
	 */
	function setShowTimeout() {
		tempHidden = true;
		window.clearTimeout( tempHiddenTimeout );
		tempHiddenTimeout = window.setTimeout(
			function() {
				tempHidden = false;
			},
			300000 // 5 min.
		);
	}

	/**
	 * Binds to the Heartbeat Tick event.
	 *
	 * - Shows the authentication form popup if user is not logged in.
	 * - Hides the authentication form popup if it is already visible and user is
	 *   logged in.
	 *
	 * @ignore
	 *
	 * @since 3.6.0
	 *
	 * @param {Object} e The heartbeat-tick event that has been triggered.
	 * @param {Object} data Response data.
	 */
	$( document ).on( 'heartbeat-tick.mt-auth-check', function( e, data ) {
		if ( 'mt-auth-check' in data ) {
			if ( ! data['mt-auth-check'] && wrap.hasClass( 'hidden' ) && ! tempHidden ) {
				show();
			} else if ( data['mt-auth-check'] && ! wrap.hasClass( 'hidden' ) ) {
				hide();
			}
		}
	}).ready( function() {

		/**
		 * Hides the authentication form popup when the close icon is clicked.
		 *
		 * @ignore
		 *
		 * @since 3.6.0
		 */
		wrap = $( '#mt-auth-check-wrap' );
		wrap.find( '.mt-auth-check-close' ).on( 'click', function() {
			hide();
			setShowTimeout();
		});
	});

}(jQuery));
