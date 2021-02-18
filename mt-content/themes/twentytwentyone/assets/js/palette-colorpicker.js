/**
 * Script for our custom colorpicker control.
 *
 * This is copied from mt-admin/js/customize-controls.js
 * with a few tweaks:
 * 		Removed the hue picker script because we don't use it here
 * 		Added the "palettes" argument in mtColorPicker().
 */
mt.customize.controlConstructor['twenty-twenty-one-color'] = mt.customize.Control.extend( {
	ready: function() {
		var control = this,
			updating = false,
			picker;

		picker = this.container.find( '.color-picker-hex' );
		picker.val( control.setting() ).mtColorPicker( {
			palettes: control.params.palette,
			change: function() {
				updating = true;
				control.setting.set( picker.mtColorPicker( 'color' ) );
				updating = false;
			},
			clear: function() {
				updating = true;
				control.setting.set( '' );
				updating = false;
			}
		} );

		control.setting.bind( function( value ) {
			// Bail if the update came from the control itself.
			if ( updating ) {
				return;
			}
			picker.val( value );
			picker.mtColorPicker( 'color', value );
		} );

		// Collapse color picker when hitting Esc instead of collapsing the current section.
		control.container.on( 'keydown', function( event ) {
			var pickerContainer;
			if ( 27 !== event.which ) { // Esc.
				return;
			}
			pickerContainer = control.container.find( '.mt-picker-container' );
			if ( pickerContainer.hasClass( 'mt-picker-active' ) ) {
				picker.mtColorPicker( 'close' );
				control.container.find( '.mt-color-result' ).focus();
				event.stopPropagation(); // Prevent section from being collapsed.
			}
		} );
	}
} );
