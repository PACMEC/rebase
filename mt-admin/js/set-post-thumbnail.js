/**
 * @output mt-admin/js/set-post-thumbnail.js
 */

/* global ajaxurl, post_id, alert */
/* exported MTSetAsThumbnail */

window.MTSetAsThumbnail = function( id, nonce ) {
	var $link = jQuery('a#mt-post-thumbnail-' + id);

	$link.text( mt.i18n.__( 'Saving…' ) );
	jQuery.post(ajaxurl, {
		action: 'set-post-thumbnail', post_id: post_id, thumbnail_id: id, _ajax_nonce: nonce, cookie: encodeURIComponent( document.cookie )
	}, function(str){
		var win = window.dialogArguments || opener || parent || top;
		$link.text( mt.i18n.__( 'Use as featured image' ) );
		if ( str == '0' ) {
			alert( mt.i18n.__( 'Could not set that as the thumbnail image. Try a different attachment.' ) );
		} else {
			jQuery('a.mt-post-thumbnail').show();
			$link.text( mt.i18n.__( 'Done' ) );
			$link.fadeOut( 2000 );
			win.MTSetThumbnailID(id);
			win.MTSetThumbnailHTML(str);
		}
	}
	);
};
