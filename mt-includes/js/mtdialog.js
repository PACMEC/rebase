/**
 * @output mt-includes/js/mtdialog.js
 */

/*
 * Wrap the jQuery UI Dialog open function remove focus from tinyMCE.
 */
( function($) {
	$.widget('mt.mtdialog', $.ui.dialog, {
		open: function() {
			// Add beforeOpen event.
			if ( this.isOpen() || false === this._trigger('beforeOpen') ) {
				return;
			}

			// Open the dialog.
			this._super();

			// WebKit leaves focus in the TinyMCE editor unless we shift focus.
			this.element.focus();
			this._trigger('refresh');
		}
	});

	$.mt.mtdialog.prototype.options.closeOnEscape = false;

})(jQuery);
