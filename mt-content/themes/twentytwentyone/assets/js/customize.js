/* global twentytwentyoneGetHexLum */

( function() {
	// Wait until the customizer has finished loading.
	mt.customize.bind( 'ready', function() {
		// Hide the "respect_user_color_preference" setting if the background-color is dark.
		if ( 127 > twentytwentyoneGetHexLum( mt.customize( 'background_color' ).get() ) ) {
			mt.customize.control( 'respect_user_color_preference' ).deactivate();
			mt.customize.control( 'respect_user_color_preference_notice' ).deactivate();
		}

		// Handle changes to the background-color.
		mt.customize( 'background_color', function( setting ) {
			setting.bind( function( value ) {
				if ( 127 > twentytwentyoneGetHexLum( value ) ) {
					mt.customize.control( 'respect_user_color_preference' ).deactivate();
					mt.customize.control( 'respect_user_color_preference_notice' ).activate();
				} else {
					mt.customize.control( 'respect_user_color_preference' ).activate();
					mt.customize.control( 'respect_user_color_preference_notice' ).deactivate();
				}
			} );
		} );
	} );
}() );
