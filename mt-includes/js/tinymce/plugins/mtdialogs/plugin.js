/* global tinymce */
/**
 * Included for back-compat.
 * The default WindowManager in TinyMCE 4.0 supports three types of dialogs:
 *	- With HTML created from JS.
 *	- With inline HTML (like MTWindowManager).
 *	- Old type iframe based dialogs.
 * For examples see the default plugins: https://github.com/tinymce/tinymce/tree/master/js/tinymce/plugins
 */
tinymce.MTWindowManager = tinymce.InlineWindowManager = function( editor ) {
	if ( this.mt ) {
		return this;
	}

	this.mt = {};
	this.parent = editor.windowManager;
	this.editor = editor;

	tinymce.extend( this, this.parent );

	this.open = function( args, params ) {
		var $element,
			self = this,
			mt = this.mt;

		if ( ! args.mtDialog ) {
			return this.parent.open.apply( this, arguments );
		} else if ( ! args.id ) {
			return;
		}

		if ( typeof jQuery === 'undefined' || ! jQuery.mt || ! jQuery.mt.mtdialog ) {
			// mtdialog.js is not loaded.
			if ( window.console && window.console.error ) {
				window.console.error('mtdialog.js is not loaded. Please set "mtdialogs" as dependency for your script when calling mt_enqueue_script(). You may also want to enqueue the "mt-jquery-ui-dialog" stylesheet.');
			}

			return;
		}

		mt.$element = $element = jQuery( '#' + args.id );

		if ( ! $element.length ) {
			return;
		}

		if ( window.console && window.console.log ) {
			window.console.log('tinymce.MTWindowManager is deprecated. Use the default editor.windowManager to open dialogs with inline HTML.');
		}

		mt.features = args;
		mt.params = params;

		// Store selection. Takes a snapshot in the FocusManager of the selection before focus is moved to the dialog.
		editor.nodeChanged();

		// Create the dialog if necessary.
		if ( ! $element.data('mtdialog') ) {
			$element.mtdialog({
				title: args.title,
				width: args.width,
				height: args.height,
				modal: true,
				dialogClass: 'mt-dialog',
				zIndex: 300000
			});
		}

		$element.mtdialog('open');

		$element.on( 'mtdialogclose', function() {
			if ( self.mt.$element ) {
				self.mt = {};
			}
		});
	};

	this.close = function() {
		if ( ! this.mt.features || ! this.mt.features.mtDialog ) {
			return this.parent.close.apply( this, arguments );
		}

		this.mt.$element.mtdialog('close');
	};
};

tinymce.PluginManager.add( 'mtdialogs', function( editor ) {
	// Replace window manager.
	editor.on( 'init', function() {
		editor.windowManager = new tinymce.MTWindowManager( editor );
	});
});
