<?php
/**
 * paCMec core upgrade functionality.
 *
 * @package paCMec
 * @subpackage Administration
 * @since 2.7.0
 */

/**
 * Stores files to be deleted.
 *
 * @since 2.7.0
 *
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
	// 2.0
	'mt-admin/import-b2.php',
	'mt-admin/import-blogger.php',
	'mt-admin/import-greymatter.php',
	'mt-admin/import-livejournal.php',
	'mt-admin/import-mt.php',
	'mt-admin/import-rss.php',
	'mt-admin/import-textpattern.php',
	'mt-admin/quicktags.js',
	'mt-images/fade-butt.png',
	'mt-images/get-firefox.png',
	'mt-images/header-shadow.png',
	'mt-images/smilies',
	'mt-images/mt-small.png',
	'mt-images/mtminilogo.png',
	'mt.php',
	// 2.0.8
	'mt-includes/js/tinymce/plugins/inlinepopups/readme.txt',
	// 2.1
	'mt-admin/edit-form-ajax-cat.php',
	'mt-admin/execute-pings.php',
	'mt-admin/inline-uploading.php',
	'mt-admin/link-categories.php',
	'mt-admin/list-manipulation.js',
	'mt-admin/list-manipulation.php',
	'mt-includes/comment-functions.php',
	'mt-includes/feed-functions.php',
	'mt-includes/functions-compat.php',
	'mt-includes/functions-formatting.php',
	'mt-includes/functions-post.php',
	'mt-includes/js/dbx-key.js',
	'mt-includes/js/tinymce/plugins/autosave/langs/cs.js',
	'mt-includes/js/tinymce/plugins/autosave/langs/sv.js',
	'mt-includes/links.php',
	'mt-includes/pluggable-functions.php',
	'mt-includes/template-functions-author.php',
	'mt-includes/template-functions-category.php',
	'mt-includes/template-functions-general.php',
	'mt-includes/template-functions-links.php',
	'mt-includes/template-functions-post.php',
	'mt-includes/mt-l10n.php',
	// 2.2
	'mt-admin/cat-js.php',
	'mt-admin/import/b2.php',
	'mt-includes/js/autosave-js.php',
	'mt-includes/js/list-manipulation-js.php',
	'mt-includes/js/mt-ajax-js.php',
	// 2.3
	'mt-admin/admin-db.php',
	'mt-admin/cat.js',
	'mt-admin/categories.js',
	'mt-admin/custom-fields.js',
	'mt-admin/dbx-admin-key.js',
	'mt-admin/edit-comments.js',
	'mt-admin/install-rtl.css',
	'mt-admin/install.css',
	'mt-admin/upgrade-schema.php',
	'mt-admin/upload-functions.php',
	'mt-admin/upload-rtl.css',
	'mt-admin/upload.css',
	'mt-admin/upload.js',
	'mt-admin/users.js',
	'mt-admin/widgets-rtl.css',
	'mt-admin/widgets.css',
	'mt-admin/xfn.js',
	'mt-includes/js/tinymce/license.html',
	// 2.5
	'mt-admin/css/upload.css',
	'mt-admin/images/box-bg-left.gif',
	'mt-admin/images/box-bg-right.gif',
	'mt-admin/images/box-bg.gif',
	'mt-admin/images/box-butt-left.gif',
	'mt-admin/images/box-butt-right.gif',
	'mt-admin/images/box-butt.gif',
	'mt-admin/images/box-head-left.gif',
	'mt-admin/images/box-head-right.gif',
	'mt-admin/images/box-head.gif',
	'mt-admin/images/heading-bg.gif',
	'mt-admin/images/login-bkg-bottom.gif',
	'mt-admin/images/login-bkg-tile.gif',
	'mt-admin/images/notice.gif',
	'mt-admin/images/toggle.gif',
	'mt-admin/includes/upload.php',
	'mt-admin/js/dbx-admin-key.js',
	'mt-admin/js/link-cat.js',
	'mt-admin/profile-update.php',
	'mt-admin/templates.php',
	'mt-includes/images/wlw/WpComments.png',
	'mt-includes/images/wlw/WpIcon.png',
	'mt-includes/images/wlw/WpWatermark.png',
	'mt-includes/js/dbx.js',
	'mt-includes/js/fat.js',
	'mt-includes/js/list-manipulation.js',
	'mt-includes/js/tinymce/langs/en.js',
	'mt-includes/js/tinymce/plugins/autosave/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/autosave/langs',
	'mt-includes/js/tinymce/plugins/directionality/images',
	'mt-includes/js/tinymce/plugins/directionality/langs',
	'mt-includes/js/tinymce/plugins/inlinepopups/css',
	'mt-includes/js/tinymce/plugins/inlinepopups/images',
	'mt-includes/js/tinymce/plugins/inlinepopups/jscripts',
	'mt-includes/js/tinymce/plugins/paste/images',
	'mt-includes/js/tinymce/plugins/paste/jscripts',
	'mt-includes/js/tinymce/plugins/paste/langs',
	'mt-includes/js/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
	'mt-includes/js/tinymce/plugins/spellchecker/classes/TinyGoogleSpell.class.php',
	'mt-includes/js/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
	'mt-includes/js/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
	'mt-includes/js/tinymce/plugins/spellchecker/css/spellchecker.css',
	'mt-includes/js/tinymce/plugins/spellchecker/images',
	'mt-includes/js/tinymce/plugins/spellchecker/langs',
	'mt-includes/js/tinymce/plugins/spellchecker/tinyspell.php',
	'mt-includes/js/tinymce/plugins/pacmec/images',
	'mt-includes/js/tinymce/plugins/pacmec/langs',
	'mt-includes/js/tinymce/plugins/pacmec/pacmec.css',
	'mt-includes/js/tinymce/plugins/mthelp',
	'mt-includes/js/tinymce/themes/advanced/css',
	'mt-includes/js/tinymce/themes/advanced/images',
	'mt-includes/js/tinymce/themes/advanced/jscripts',
	'mt-includes/js/tinymce/themes/advanced/langs',
	// 2.5.1
	'mt-includes/js/tinymce/tiny_mce_gzip.php',
	// 2.6
	'mt-admin/bookmarklet.php',
	'mt-includes/js/jquery/jquery.dimensions.min.js',
	'mt-includes/js/tinymce/plugins/pacmec/popups.css',
	'mt-includes/js/mt-ajax.js',
	// 2.7
	'mt-admin/css/press-this-ie-rtl.css',
	'mt-admin/css/press-this-ie.css',
	'mt-admin/css/upload-rtl.css',
	'mt-admin/edit-form.php',
	'mt-admin/images/comment-pill.gif',
	'mt-admin/images/comment-stalk-classic.gif',
	'mt-admin/images/comment-stalk-fresh.gif',
	'mt-admin/images/comment-stalk-rtl.gif',
	'mt-admin/images/del.png',
	'mt-admin/images/gear.png',
	'mt-admin/images/media-button-gallery.gif',
	'mt-admin/images/media-buttons.gif',
	'mt-admin/images/postbox-bg.gif',
	'mt-admin/images/tab.png',
	'mt-admin/images/tail.gif',
	'mt-admin/js/forms.js',
	'mt-admin/js/upload.js',
	'mt-admin/link-import.php',
	'mt-includes/images/audio.png',
	'mt-includes/images/css.png',
	'mt-includes/images/default.png',
	'mt-includes/images/doc.png',
	'mt-includes/images/exe.png',
	'mt-includes/images/html.png',
	'mt-includes/images/js.png',
	'mt-includes/images/pdf.png',
	'mt-includes/images/swf.png',
	'mt-includes/images/tar.png',
	'mt-includes/images/text.png',
	'mt-includes/images/video.png',
	'mt-includes/images/zip.png',
	'mt-includes/js/tinymce/tiny_mce_config.php',
	'mt-includes/js/tinymce/tiny_mce_ext.js',
	// 2.8
	'mt-admin/js/users.js',
	'mt-includes/js/swfupload/plugins/swfupload.documentready.js',
	'mt-includes/js/swfupload/plugins/swfupload.graceful_degradation.js',
	'mt-includes/js/swfupload/swfupload_f9.swf',
	'mt-includes/js/tinymce/plugins/autosave',
	'mt-includes/js/tinymce/plugins/paste/css',
	'mt-includes/js/tinymce/utils/mclayer.js',
	'mt-includes/js/tinymce/pacmec.css',
	// 2.8.5
	'mt-admin/import/btt.php',
	'mt-admin/import/jkw.php',
	// 2.9
	'mt-admin/js/page.dev.js',
	'mt-admin/js/page.js',
	'mt-admin/js/set-post-thumbnail-handler.dev.js',
	'mt-admin/js/set-post-thumbnail-handler.js',
	'mt-admin/js/slug.dev.js',
	'mt-admin/js/slug.js',
	'mt-includes/gettext.php',
	'mt-includes/js/tinymce/plugins/pacmec/js',
	'mt-includes/streams.php',
	// MU
	'README.txt',
	'htaccess.dist',
	'index-install.php',
	'mt-admin/css/mu-rtl.css',
	'mt-admin/css/mu.css',
	'mt-admin/images/site-admin.png',
	'mt-admin/includes/mu.php',
	'mt-admin/mtmu-admin.php',
	'mt-admin/mtmu-blogs.php',
	'mt-admin/mtmu-edit.php',
	'mt-admin/mtmu-options.php',
	'mt-admin/mtmu-themes.php',
	'mt-admin/mtmu-upgrade-site.php',
	'mt-admin/mtmu-users.php',
	'mt-includes/images/pacmec-mu.png',
	'mt-includes/mtmu-default-filters.php',
	'mt-includes/mtmu-functions.php',
	'mtmu-settings.php',
	// 3.0
	'mt-admin/categories.php',
	'mt-admin/edit-category-form.php',
	'mt-admin/edit-page-form.php',
	'mt-admin/edit-pages.php',
	'mt-admin/images/admin-header-footer.png',
	'mt-admin/images/browse-happy.gif',
	'mt-admin/images/ico-add.png',
	'mt-admin/images/ico-close.png',
	'mt-admin/images/ico-edit.png',
	'mt-admin/images/ico-viemtage.png',
	'mt-admin/images/fav-top.png',
	'mt-admin/images/screen-options-left.gif',
	'mt-admin/images/mt-logo-vs.gif',
	'mt-admin/images/mt-logo.gif',
	'mt-admin/import',
	'mt-admin/js/mt-gears.dev.js',
	'mt-admin/js/mt-gears.js',
	'mt-admin/options-misc.php',
	'mt-admin/page-new.php',
	'mt-admin/page.php',
	'mt-admin/rtl.css',
	'mt-admin/rtl.dev.css',
	'mt-admin/update-links.php',
	'mt-admin/mt-admin.css',
	'mt-admin/mt-admin.dev.css',
	'mt-includes/js/codepress',
	'mt-includes/js/codepress/engines/khtml.js',
	'mt-includes/js/codepress/engines/older.js',
	'mt-includes/js/jquery/autocomplete.dev.js',
	'mt-includes/js/jquery/autocomplete.js',
	'mt-includes/js/jquery/interface.js',
	'mt-includes/js/scriptaculous/prototype.js',
	// Following file added back in 5.1, see #45645.
	//'mt-includes/js/tinymce/mt-tinymce.js',
	// 3.1
	'mt-admin/edit-attachment-rows.php',
	'mt-admin/edit-link-categories.php',
	'mt-admin/edit-link-category-form.php',
	'mt-admin/edit-post-rows.php',
	'mt-admin/images/button-grad-active-vs.png',
	'mt-admin/images/button-grad-vs.png',
	'mt-admin/images/fav-arrow-vs-rtl.gif',
	'mt-admin/images/fav-arrow-vs.gif',
	'mt-admin/images/fav-top-vs.gif',
	'mt-admin/images/list-vs.png',
	'mt-admin/images/screen-options-right-up.gif',
	'mt-admin/images/screen-options-right.gif',
	'mt-admin/images/visit-site-button-grad-vs.gif',
	'mt-admin/images/visit-site-button-grad.gif',
	'mt-admin/link-category.php',
	'mt-admin/sidebar.php',
	'mt-includes/classes.php',
	'mt-includes/js/tinymce/blank.htm',
	'mt-includes/js/tinymce/plugins/media/css/content.css',
	'mt-includes/js/tinymce/plugins/media/img',
	'mt-includes/js/tinymce/plugins/safari',
	// 3.2
	'mt-admin/images/logo-login.gif',
	'mt-admin/images/star.gif',
	'mt-admin/js/list-table.dev.js',
	'mt-admin/js/list-table.js',
	'mt-includes/default-embeds.php',
	'mt-includes/js/tinymce/plugins/pacmec/img/help.gif',
	'mt-includes/js/tinymce/plugins/pacmec/img/more.gif',
	'mt-includes/js/tinymce/plugins/pacmec/img/toolbars.gif',
	'mt-includes/js/tinymce/themes/advanced/img/fm.gif',
	'mt-includes/js/tinymce/themes/advanced/img/sflogo.png',
	// 3.3
	'mt-admin/css/colors-classic-rtl.css',
	'mt-admin/css/colors-classic-rtl.dev.css',
	'mt-admin/css/colors-fresh-rtl.css',
	'mt-admin/css/colors-fresh-rtl.dev.css',
	'mt-admin/css/dashboard-rtl.dev.css',
	'mt-admin/css/dashboard.dev.css',
	'mt-admin/css/global-rtl.css',
	'mt-admin/css/global-rtl.dev.css',
	'mt-admin/css/global.css',
	'mt-admin/css/global.dev.css',
	'mt-admin/css/install-rtl.dev.css',
	'mt-admin/css/login-rtl.dev.css',
	'mt-admin/css/login.dev.css',
	'mt-admin/css/ms.css',
	'mt-admin/css/ms.dev.css',
	'mt-admin/css/nav-menu-rtl.css',
	'mt-admin/css/nav-menu-rtl.dev.css',
	'mt-admin/css/nav-menu.css',
	'mt-admin/css/nav-menu.dev.css',
	'mt-admin/css/plugin-install-rtl.css',
	'mt-admin/css/plugin-install-rtl.dev.css',
	'mt-admin/css/plugin-install.css',
	'mt-admin/css/plugin-install.dev.css',
	'mt-admin/css/press-this-rtl.dev.css',
	'mt-admin/css/press-this.dev.css',
	'mt-admin/css/theme-editor-rtl.css',
	'mt-admin/css/theme-editor-rtl.dev.css',
	'mt-admin/css/theme-editor.css',
	'mt-admin/css/theme-editor.dev.css',
	'mt-admin/css/theme-install-rtl.css',
	'mt-admin/css/theme-install-rtl.dev.css',
	'mt-admin/css/theme-install.css',
	'mt-admin/css/theme-install.dev.css',
	'mt-admin/css/widgets-rtl.dev.css',
	'mt-admin/css/widgets.dev.css',
	'mt-admin/includes/internal-linking.php',
	'mt-includes/images/admin-bar-sprite-rtl.png',
	'mt-includes/js/jquery/ui.button.js',
	'mt-includes/js/jquery/ui.core.js',
	'mt-includes/js/jquery/ui.dialog.js',
	'mt-includes/js/jquery/ui.draggable.js',
	'mt-includes/js/jquery/ui.droppable.js',
	'mt-includes/js/jquery/ui.mouse.js',
	'mt-includes/js/jquery/ui.position.js',
	'mt-includes/js/jquery/ui.resizable.js',
	'mt-includes/js/jquery/ui.selectable.js',
	'mt-includes/js/jquery/ui.sortable.js',
	'mt-includes/js/jquery/ui.tabs.js',
	'mt-includes/js/jquery/ui.widget.js',
	'mt-includes/js/l10n.dev.js',
	'mt-includes/js/l10n.js',
	'mt-includes/js/tinymce/plugins/mtlink/css',
	'mt-includes/js/tinymce/plugins/mtlink/img',
	'mt-includes/js/tinymce/plugins/mtlink/js',
	'mt-includes/js/tinymce/themes/advanced/img/mticons.png',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/butt2.png',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/button_bg.png',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/down_arrow.gif',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/fade-butt.png',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/separator.gif',
	// Don't delete, yet: 'mt-rss.php',
	// Don't delete, yet: 'mt-rdf.php',
	// Don't delete, yet: 'mt-rss2.php',
	// Don't delete, yet: 'mt-commentsrss2.php',
	// Don't delete, yet: 'mt-atom.php',
	// Don't delete, yet: 'mt-feed.php',
	// 3.4
	'mt-admin/images/gray-star.png',
	'mt-admin/images/logo-login.png',
	'mt-admin/images/star.png',
	'mt-admin/index-extra.php',
	'mt-admin/network/index-extra.php',
	'mt-admin/user/index-extra.php',
	'mt-admin/images/screenshots/admin-flyouts.png',
	'mt-admin/images/screenshots/coediting.png',
	'mt-admin/images/screenshots/drag-and-drop.png',
	'mt-admin/images/screenshots/help-screen.png',
	'mt-admin/images/screenshots/media-icon.png',
	'mt-admin/images/screenshots/new-feature-pointer.png',
	'mt-admin/images/screenshots/welcome-screen.png',
	'mt-includes/css/editor-buttons.css',
	'mt-includes/css/editor-buttons.dev.css',
	'mt-includes/js/tinymce/plugins/paste/blank.htm',
	'mt-includes/js/tinymce/plugins/pacmec/css',
	'mt-includes/js/tinymce/plugins/pacmec/editor_plugin.dev.js',
	'mt-includes/js/tinymce/plugins/pacmec/img/embedded.png',
	'mt-includes/js/tinymce/plugins/pacmec/img/more_bug.gif',
	'mt-includes/js/tinymce/plugins/pacmec/img/page_bug.gif',
	'mt-includes/js/tinymce/plugins/mtdialogs/editor_plugin.dev.js',
	'mt-includes/js/tinymce/plugins/mteditimage/css/editimage-rtl.css',
	'mt-includes/js/tinymce/plugins/mteditimage/editor_plugin.dev.js',
	'mt-includes/js/tinymce/plugins/mtfullscreen/editor_plugin.dev.js',
	'mt-includes/js/tinymce/plugins/mtgallery/editor_plugin.dev.js',
	'mt-includes/js/tinymce/plugins/mtgallery/img/gallery.png',
	'mt-includes/js/tinymce/plugins/mtlink/editor_plugin.dev.js',
	// Don't delete, yet: 'mt-pass.php',
	// Don't delete, yet: 'mt-register.php',
	// 3.5
	'mt-admin/gears-manifest.php',
	'mt-admin/includes/manifest.php',
	'mt-admin/images/archive-link.png',
	'mt-admin/images/blue-grad.png',
	'mt-admin/images/button-grad-active.png',
	'mt-admin/images/button-grad.png',
	'mt-admin/images/ed-bg-vs.gif',
	'mt-admin/images/ed-bg.gif',
	'mt-admin/images/fade-butt.png',
	'mt-admin/images/fav-arrow-rtl.gif',
	'mt-admin/images/fav-arrow.gif',
	'mt-admin/images/fav-vs.png',
	'mt-admin/images/fav.png',
	'mt-admin/images/gray-grad.png',
	'mt-admin/images/loading-publish.gif',
	'mt-admin/images/logo-ghost.png',
	'mt-admin/images/logo.gif',
	'mt-admin/images/menu-arrow-frame-rtl.png',
	'mt-admin/images/menu-arrow-frame.png',
	'mt-admin/images/menu-arrows.gif',
	'mt-admin/images/menu-bits-rtl-vs.gif',
	'mt-admin/images/menu-bits-rtl.gif',
	'mt-admin/images/menu-bits-vs.gif',
	'mt-admin/images/menu-bits.gif',
	'mt-admin/images/menu-dark-rtl-vs.gif',
	'mt-admin/images/menu-dark-rtl.gif',
	'mt-admin/images/menu-dark-vs.gif',
	'mt-admin/images/menu-dark.gif',
	'mt-admin/images/required.gif',
	'mt-admin/images/screen-options-toggle-vs.gif',
	'mt-admin/images/screen-options-toggle.gif',
	'mt-admin/images/toggle-arrow-rtl.gif',
	'mt-admin/images/toggle-arrow.gif',
	'mt-admin/images/upload-classic.png',
	'mt-admin/images/upload-fresh.png',
	'mt-admin/images/white-grad-active.png',
	'mt-admin/images/white-grad.png',
	'mt-admin/images/widgets-arrow-vs.gif',
	'mt-admin/images/widgets-arrow.gif',
	'mt-admin/images/mtspin_dark.gif',
	'mt-includes/images/upload.png',
	'mt-includes/js/prototype.js',
	'mt-includes/js/scriptaculous',
	'mt-admin/css/mt-admin-rtl.dev.css',
	'mt-admin/css/mt-admin.dev.css',
	'mt-admin/css/media-rtl.dev.css',
	'mt-admin/css/media.dev.css',
	'mt-admin/css/colors-classic.dev.css',
	'mt-admin/css/customize-controls-rtl.dev.css',
	'mt-admin/css/customize-controls.dev.css',
	'mt-admin/css/ie-rtl.dev.css',
	'mt-admin/css/ie.dev.css',
	'mt-admin/css/install.dev.css',
	'mt-admin/css/colors-fresh.dev.css',
	'mt-includes/js/customize-base.dev.js',
	'mt-includes/js/json2.dev.js',
	'mt-includes/js/comment-reply.dev.js',
	'mt-includes/js/customize-preview.dev.js',
	'mt-includes/js/mtlink.dev.js',
	'mt-includes/js/tw-sack.dev.js',
	'mt-includes/js/mt-list-revisions.dev.js',
	'mt-includes/js/autosave.dev.js',
	'mt-includes/js/admin-bar.dev.js',
	'mt-includes/js/quicktags.dev.js',
	'mt-includes/js/mt-ajax-response.dev.js',
	'mt-includes/js/mt-pointer.dev.js',
	'mt-includes/js/hoverIntent.dev.js',
	'mt-includes/js/colorpicker.dev.js',
	'mt-includes/js/mt-lists.dev.js',
	'mt-includes/js/customize-loader.dev.js',
	'mt-includes/js/jquery/jquery.table-hotkeys.dev.js',
	'mt-includes/js/jquery/jquery.color.dev.js',
	'mt-includes/js/jquery/jquery.color.js',
	'mt-includes/js/jquery/jquery.hotkeys.dev.js',
	'mt-includes/js/jquery/jquery.form.dev.js',
	'mt-includes/js/jquery/suggest.dev.js',
	'mt-admin/js/xfn.dev.js',
	'mt-admin/js/set-post-thumbnail.dev.js',
	'mt-admin/js/comment.dev.js',
	'mt-admin/js/theme.dev.js',
	'mt-admin/js/cat.dev.js',
	'mt-admin/js/password-strength-meter.dev.js',
	'mt-admin/js/user-profile.dev.js',
	'mt-admin/js/theme-preview.dev.js',
	'mt-admin/js/post.dev.js',
	'mt-admin/js/media-upload.dev.js',
	'mt-admin/js/word-count.dev.js',
	'mt-admin/js/plugin-install.dev.js',
	'mt-admin/js/edit-comments.dev.js',
	'mt-admin/js/media-gallery.dev.js',
	'mt-admin/js/custom-fields.dev.js',
	'mt-admin/js/custom-background.dev.js',
	'mt-admin/js/common.dev.js',
	'mt-admin/js/inline-edit-tax.dev.js',
	'mt-admin/js/gallery.dev.js',
	'mt-admin/js/utils.dev.js',
	'mt-admin/js/widgets.dev.js',
	'mt-admin/js/mt-fullscreen.dev.js',
	'mt-admin/js/nav-menu.dev.js',
	'mt-admin/js/dashboard.dev.js',
	'mt-admin/js/link.dev.js',
	'mt-admin/js/user-suggest.dev.js',
	'mt-admin/js/postbox.dev.js',
	'mt-admin/js/tags.dev.js',
	'mt-admin/js/image-edit.dev.js',
	'mt-admin/js/media.dev.js',
	'mt-admin/js/customize-controls.dev.js',
	'mt-admin/js/inline-edit-post.dev.js',
	'mt-admin/js/categories.dev.js',
	'mt-admin/js/editor.dev.js',
	'mt-includes/js/tinymce/plugins/mteditimage/js/editimage.dev.js',
	'mt-includes/js/tinymce/plugins/mtdialogs/js/popup.dev.js',
	'mt-includes/js/tinymce/plugins/mtdialogs/js/mtdialog.dev.js',
	'mt-includes/js/plupload/handlers.dev.js',
	'mt-includes/js/plupload/mt-plupload.dev.js',
	'mt-includes/js/swfupload/handlers.dev.js',
	'mt-includes/js/jcrop/jquery.Jcrop.dev.js',
	'mt-includes/js/jcrop/jquery.Jcrop.js',
	'mt-includes/js/jcrop/jquery.Jcrop.css',
	'mt-includes/js/imgareaselect/jquery.imgareaselect.dev.js',
	'mt-includes/css/mt-pointer.dev.css',
	'mt-includes/css/editor.dev.css',
	'mt-includes/css/jquery-ui-dialog.dev.css',
	'mt-includes/css/admin-bar-rtl.dev.css',
	'mt-includes/css/admin-bar.dev.css',
	'mt-includes/js/jquery/ui/jquery.effects.clip.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.scale.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.blind.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.core.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.shake.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.fade.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.explode.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.slide.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.drop.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.highlight.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.bounce.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.pulsate.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.transfer.min.js',
	'mt-includes/js/jquery/ui/jquery.effects.fold.min.js',
	'mt-admin/images/screenshots/captions-1.png',
	'mt-admin/images/screenshots/captions-2.png',
	'mt-admin/images/screenshots/flex-header-1.png',
	'mt-admin/images/screenshots/flex-header-2.png',
	'mt-admin/images/screenshots/flex-header-3.png',
	'mt-admin/images/screenshots/flex-header-media-library.png',
	'mt-admin/images/screenshots/theme-customizer.png',
	'mt-admin/images/screenshots/twitter-embed-1.png',
	'mt-admin/images/screenshots/twitter-embed-2.png',
	'mt-admin/js/utils.js',
	// Added back in 5.3 [45448], see #43895.
	// 'mt-admin/options-privacy.php',
	'mt-app.php',
	'mt-includes/class-mt-atom-server.php',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/ui.css',
	// 3.5.2
	'mt-includes/js/swfupload/swfupload-all.js',
	// 3.6
	'mt-admin/js/revisions-js.php',
	'mt-admin/images/screenshots',
	'mt-admin/js/categories.js',
	'mt-admin/js/categories.min.js',
	'mt-admin/js/custom-fields.js',
	'mt-admin/js/custom-fields.min.js',
	// 3.7
	'mt-admin/js/cat.js',
	'mt-admin/js/cat.min.js',
	'mt-includes/js/tinymce/plugins/mteditimage/js/editimage.min.js',
	// 3.8
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/page_bug.gif',
	'mt-includes/js/tinymce/themes/advanced/skins/mt_theme/img/more_bug.gif',
	'mt-includes/js/thickbox/tb-close-2x.png',
	'mt-includes/js/thickbox/tb-close.png',
	'mt-includes/images/mtmini-blue-2x.png',
	'mt-includes/images/mtmini-blue.png',
	'mt-admin/css/colors-fresh.css',
	'mt-admin/css/colors-classic.css',
	'mt-admin/css/colors-fresh.min.css',
	'mt-admin/css/colors-classic.min.css',
	'mt-admin/js/about.min.js',
	'mt-admin/js/about.js',
	'mt-admin/images/arrows-dark-vs-2x.png',
	'mt-admin/images/mt-logo-vs.png',
	'mt-admin/images/arrows-dark-vs.png',
	'mt-admin/images/mt-logo.png',
	'mt-admin/images/arrows-pr.png',
	'mt-admin/images/arrows-dark.png',
	'mt-admin/images/press-this.png',
	'mt-admin/images/press-this-2x.png',
	'mt-admin/images/arrows-vs-2x.png',
	'mt-admin/images/welcome-icons.png',
	'mt-admin/images/mt-logo-2x.png',
	'mt-admin/images/stars-rtl-2x.png',
	'mt-admin/images/arrows-dark-2x.png',
	'mt-admin/images/arrows-pr-2x.png',
	'mt-admin/images/menu-shadow-rtl.png',
	'mt-admin/images/arrows-vs.png',
	'mt-admin/images/about-search-2x.png',
	'mt-admin/images/bubble_bg-rtl-2x.gif',
	'mt-admin/images/mt-badge-2x.png',
	'mt-admin/images/pacmec-logo-2x.png',
	'mt-admin/images/bubble_bg-rtl.gif',
	'mt-admin/images/mt-badge.png',
	'mt-admin/images/menu-shadow.png',
	'mt-admin/images/about-globe-2x.png',
	'mt-admin/images/welcome-icons-2x.png',
	'mt-admin/images/stars-rtl.png',
	'mt-admin/images/mt-logo-vs-2x.png',
	'mt-admin/images/about-updates-2x.png',
	// 3.9
	'mt-admin/css/colors.css',
	'mt-admin/css/colors.min.css',
	'mt-admin/css/colors-rtl.css',
	'mt-admin/css/colors-rtl.min.css',
	// Following files added back in 4.5, see #36083.
	// 'mt-admin/css/media-rtl.min.css',
	// 'mt-admin/css/media.min.css',
	// 'mt-admin/css/farbtastic-rtl.min.css',
	'mt-admin/images/lock-2x.png',
	'mt-admin/images/lock.png',
	'mt-admin/js/theme-preview.js',
	'mt-admin/js/theme-install.min.js',
	'mt-admin/js/theme-install.js',
	'mt-admin/js/theme-preview.min.js',
	'mt-includes/js/plupload/plupload.html4.js',
	'mt-includes/js/plupload/plupload.html5.js',
	'mt-includes/js/plupload/changelog.txt',
	'mt-includes/js/plupload/plupload.silverlight.js',
	'mt-includes/js/plupload/plupload.flash.js',
	// Added back in 4.9 [41328], see #41755.
	// 'mt-includes/js/plupload/plupload.js',
	'mt-includes/js/tinymce/plugins/spellchecker',
	'mt-includes/js/tinymce/plugins/inlinepopups',
	'mt-includes/js/tinymce/plugins/media/js',
	'mt-includes/js/tinymce/plugins/media/css',
	'mt-includes/js/tinymce/plugins/pacmec/img',
	'mt-includes/js/tinymce/plugins/mtdialogs/js',
	'mt-includes/js/tinymce/plugins/mteditimage/img',
	'mt-includes/js/tinymce/plugins/mteditimage/js',
	'mt-includes/js/tinymce/plugins/mteditimage/css',
	'mt-includes/js/tinymce/plugins/mtgallery/img',
	'mt-includes/js/tinymce/plugins/mtfullscreen/css',
	'mt-includes/js/tinymce/plugins/paste/js',
	'mt-includes/js/tinymce/themes/advanced',
	'mt-includes/js/tinymce/tiny_mce.js',
	'mt-includes/js/tinymce/mark_loaded_src.js',
	'mt-includes/js/tinymce/mt-tinymce-schema.js',
	'mt-includes/js/tinymce/plugins/media/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/media/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/media/media.htm',
	'mt-includes/js/tinymce/plugins/mtview/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtview/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/directionality/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/directionality/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/pacmec/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/pacmec/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtdialogs/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtdialogs/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/mteditimage/editimage.html',
	'mt-includes/js/tinymce/plugins/mteditimage/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/mteditimage/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/fullscreen/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/fullscreen/fullscreen.htm',
	'mt-includes/js/tinymce/plugins/fullscreen/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/mtlink/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtlink/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/mtgallery/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtgallery/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/tabfocus/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/tabfocus/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/mtfullscreen/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/mtfullscreen/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/paste/editor_plugin.js',
	'mt-includes/js/tinymce/plugins/paste/pasteword.htm',
	'mt-includes/js/tinymce/plugins/paste/editor_plugin_src.js',
	'mt-includes/js/tinymce/plugins/paste/pastetext.htm',
	'mt-includes/js/tinymce/langs/mt-langs.php',
	// 4.1
	'mt-includes/js/jquery/ui/jquery.ui.accordion.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.autocomplete.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.button.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.core.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.datepicker.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.dialog.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.draggable.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.droppable.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-blind.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-bounce.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-clip.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-drop.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-explode.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-fade.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-fold.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-highlight.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-pulsate.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-scale.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-shake.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-slide.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect-transfer.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.effect.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.menu.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.mouse.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.position.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.progressbar.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.resizable.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.selectable.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.slider.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.sortable.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.spinner.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.tabs.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.tooltip.min.js',
	'mt-includes/js/jquery/ui/jquery.ui.widget.min.js',
	'mt-includes/js/tinymce/skins/pacmec/images/dashicon-no-alt.png',
	// 4.3
	'mt-admin/js/mt-fullscreen.js',
	'mt-admin/js/mt-fullscreen.min.js',
	'mt-includes/js/tinymce/mt-mce-help.php',
	'mt-includes/js/tinymce/plugins/mtfullscreen',
	// 4.5
	'mt-includes/theme-compat/comments-popup.php',
	// 4.6
	'mt-admin/includes/class-mt-automatic-upgrader.php', // Wrong file name, see #37628.
	// 4.8
	'mt-includes/js/tinymce/plugins/mtembed',
	'mt-includes/js/tinymce/plugins/media/moxieplayer.swf',
	'mt-includes/js/tinymce/skins/lightgray/fonts/readme.md',
	'mt-includes/js/tinymce/skins/lightgray/fonts/tinymce-small.json',
	'mt-includes/js/tinymce/skins/lightgray/fonts/tinymce.json',
	'mt-includes/js/tinymce/skins/lightgray/skin.ie7.min.css',
	// 4.9
	'mt-admin/css/press-this-editor-rtl.css',
	'mt-admin/css/press-this-editor-rtl.min.css',
	'mt-admin/css/press-this-editor.css',
	'mt-admin/css/press-this-editor.min.css',
	'mt-admin/css/press-this-rtl.css',
	'mt-admin/css/press-this-rtl.min.css',
	'mt-admin/css/press-this.css',
	'mt-admin/css/press-this.min.css',
	'mt-admin/includes/class-mt-press-this.php',
	'mt-admin/js/bookmarklet.js',
	'mt-admin/js/bookmarklet.min.js',
	'mt-admin/js/press-this.js',
	'mt-admin/js/press-this.min.js',
	'mt-includes/js/mediaelement/background.png',
	'mt-includes/js/mediaelement/bigplay.png',
	'mt-includes/js/mediaelement/bigplay.svg',
	'mt-includes/js/mediaelement/controls.png',
	'mt-includes/js/mediaelement/controls.svg',
	'mt-includes/js/mediaelement/flashmediaelement.swf',
	'mt-includes/js/mediaelement/froogaloop.min.js',
	'mt-includes/js/mediaelement/jumpforward.png',
	'mt-includes/js/mediaelement/loading.gif',
	'mt-includes/js/mediaelement/silverlightmediaelement.xap',
	'mt-includes/js/mediaelement/skipback.png',
	'mt-includes/js/plupload/plupload.flash.swf',
	'mt-includes/js/plupload/plupload.full.min.js',
	'mt-includes/js/plupload/plupload.silverlight.xap',
	'mt-includes/js/swfupload/plugins',
	'mt-includes/js/swfupload/swfupload.swf',
	// 4.9.2
	'mt-includes/js/mediaelement/lang',
	'mt-includes/js/mediaelement/lang/ca.js',
	'mt-includes/js/mediaelement/lang/cs.js',
	'mt-includes/js/mediaelement/lang/de.js',
	'mt-includes/js/mediaelement/lang/es.js',
	'mt-includes/js/mediaelement/lang/fa.js',
	'mt-includes/js/mediaelement/lang/fr.js',
	'mt-includes/js/mediaelement/lang/hr.js',
	'mt-includes/js/mediaelement/lang/hu.js',
	'mt-includes/js/mediaelement/lang/it.js',
	'mt-includes/js/mediaelement/lang/ja.js',
	'mt-includes/js/mediaelement/lang/ko.js',
	'mt-includes/js/mediaelement/lang/nl.js',
	'mt-includes/js/mediaelement/lang/pl.js',
	'mt-includes/js/mediaelement/lang/pt.js',
	'mt-includes/js/mediaelement/lang/ro.js',
	'mt-includes/js/mediaelement/lang/ru.js',
	'mt-includes/js/mediaelement/lang/sk.js',
	'mt-includes/js/mediaelement/lang/sv.js',
	'mt-includes/js/mediaelement/lang/uk.js',
	'mt-includes/js/mediaelement/lang/zh-cn.js',
	'mt-includes/js/mediaelement/lang/zh.js',
	'mt-includes/js/mediaelement/mediaelement-flash-audio-ogg.swf',
	'mt-includes/js/mediaelement/mediaelement-flash-audio.swf',
	'mt-includes/js/mediaelement/mediaelement-flash-video-hls.swf',
	'mt-includes/js/mediaelement/mediaelement-flash-video-mdash.swf',
	'mt-includes/js/mediaelement/mediaelement-flash-video.swf',
	'mt-includes/js/mediaelement/renderers/dailymotion.js',
	'mt-includes/js/mediaelement/renderers/dailymotion.min.js',
	'mt-includes/js/mediaelement/renderers/facebook.js',
	'mt-includes/js/mediaelement/renderers/facebook.min.js',
	'mt-includes/js/mediaelement/renderers/soundcloud.js',
	'mt-includes/js/mediaelement/renderers/soundcloud.min.js',
	'mt-includes/js/mediaelement/renderers/twitch.js',
	'mt-includes/js/mediaelement/renderers/twitch.min.js',
	// 5.0
	'mt-includes/js/codemirror/jshint.js',
	// 5.1
	'mt-includes/random_compat/random_bytes_openssl.php',
	'mt-includes/js/tinymce/mt-tinymce.js.gz',
	// 5.3
	'mt-includes/js/mt-a11y.js',     // Moved to: mt-includes/js/dist/a11y.js
	'mt-includes/js/mt-a11y.min.js', // Moved to: mt-includes/js/dist/a11y.min.js
	// 5.4
	'mt-admin/js/mt-fullscreen-stub.js',
	'mt-admin/js/mt-fullscreen-stub.min.js',
	// 5.5
	'mt-admin/css/ie.css',
	'mt-admin/css/ie.min.css',
	'mt-admin/css/ie-rtl.css',
	'mt-admin/css/ie-rtl.min.css',
	// 5.6
	'mt-includes/js/jquery/ui/position.min.js',
	'mt-includes/js/jquery/ui/widget.min.js',
);

/**
 * Stores new files in mt-content to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the paCMec Upgrade. These items will not be
 * re-installed in future upgrades, this behaviour is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to mt-content) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 3.2.0
 * @since 4.7.0 New themes were not automatically installed for 4.4-4.6 on
 *              upgrade. New themes are now installed again. To disable new
 *              themes from being installed on upgrade, explicitly define
 *              CORE_UPGRADE_SKIP_NEW_BUNDLED as true.
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'plugins/akismet/'        => '2.0',
	'themes/twentyten/'       => '3.0',
	'themes/twentyeleven/'    => '3.2',
	'themes/twentytwelve/'    => '3.5',
	'themes/twentythirteen/'  => '3.6',
	'themes/twentyfourteen/'  => '3.8',
	'themes/twentyfifteen/'   => '4.1',
	'themes/twentysixteen/'   => '4.4',
	'themes/twentyseventeen/' => '4.7',
	'themes/twentynineteen/'  => '5.0',
	'themes/twentytwenty/'    => '5.3',
	'themes/twentytwentyone/' => '5.6',
);

/**
 * Upgrades the core of paCMec.
 *
 * This will create a .maintenance file at the base of the paCMec directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the `$_old_files` list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the `$_new_bundled_files` list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current paCMec base.
 *   3. Copy new paCMec directory over old paCMec files.
 *   4. Upgrade paCMec to new version.
 *     4.1. Copy all files/folders other than mt-content
 *     4.2. Copy any language files to MT_LANG_DIR (which may differ from MT_CONTENT_DIR
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new paCMec directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new paCMec over the old fails, then the worse is that
 * the new paCMec directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 2.7.0
 *
 * @global MT_Filesystem_Base $mt_filesystem          paCMec filesystem subclass.
 * @global array              $_old_files
 * @global array              $_new_bundled_files
 * @global mtdb               $mtdb                   paCMec database abstraction object.
 * @global string             $mt_version
 * @global string             $required_php_version
 * @global string             $required_mysql_version
 *
 * @param string $from New release unzipped path.
 * @param string $to   Path to old paCMec installation.
 * @return string|MT_Error New paCMec version on success, MT_Error on failure.
 */
function update_core( $from, $to ) {
	global $mt_filesystem, $_old_files, $_new_bundled_files, $mtdb;

	set_time_limit( 300 );

	/**
	 * Filters feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before paCMec begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before paCMec begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots  = array( '/pacmec/', '/pacmec-mu/' );
	foreach ( $roots as $root ) {
		if ( $mt_filesystem->exists( $from . $root . 'readme.html' ) && $mt_filesystem->exists( $from . $root . 'mt-includes/version.php' ) ) {
			$distro = $root;
			break;
		}
	}
	if ( ! $distro ) {
		$mt_filesystem->delete( $from, true );
		return new MT_Error( 'insane_distro', __( 'The update could not be unpacked' ) );
	}

	/*
	 * Import $mt_version, $required_php_version, and $required_mysql_version from the new version.
	 * DO NOT globalise any variables imported from `version-current.php` in this function.
	 *
	 * BC Note: $mt_filesystem->mt_content_dir() returned unslashed pre-2.8
	 */
	$versions_file = trailingslashit( $mt_filesystem->mt_content_dir() ) . 'upgrade/version-current.php';
	if ( ! $mt_filesystem->copy( $from . $distro . 'mt-includes/version.php', $versions_file ) ) {
		$mt_filesystem->delete( $from, true );
		return new MT_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'mt-includes/version.php' );
	}

	$mt_filesystem->chmod( $versions_file, FS_CHMOD_FILE );
	require MT_CONTENT_DIR . '/upgrade/version-current.php';
	$mt_filesystem->delete( $versions_file );

	$php_version       = phpversion();
	$mysql_version     = $mtdb->db_version();
	$old_mt_version    = $GLOBALS['mt_version']; // The version of paCMec we're updating from.
	$development_build = ( false !== strpos( $old_mt_version . $mt_version, '-' ) ); // A dash in the version indicates a development release.
	$php_compat        = version_compare( $php_version, $required_php_version, '>=' );
	if ( file_exists( MT_CONTENT_DIR . '/db.php' ) && empty( $mtdb->is_mysql ) ) {
		$mysql_compat = true;
	} else {
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );
	}

	if ( ! $mysql_compat || ! $php_compat ) {
		$mt_filesystem->delete( $from, true );
	}

	$php_update_message = '';

	if ( function_exists( 'mt_get_update_php_url' ) ) {
		/* translators: %s: URL to Update PHP page. */
		$php_update_message = '</p><p>' . sprintf(
			__( '<a href="%s">Learn more about updating PHP</a>.' ),
			esc_url( mt_get_update_php_url() )
		);

		if ( function_exists( 'mt_get_update_php_annotation' ) ) {
			$annotation = mt_get_update_php_annotation();

			if ( $annotation ) {
				$php_update_message .= '</p><p><em>' . $annotation . '</em>';
			}
		}
	}

	if ( ! $mysql_compat && ! $php_compat ) {
		return new MT_Error(
			'php_mysql_not_compatible',
			sprintf(
				/* translators: 1: paCMec version number, 2: Minimum required PHP version number, 3: Minimum required MySQL version number, 4: Current PHP version number, 5: Current MySQL version number. */
				__( 'The update cannot be installed because paCMec %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.' ),
				$mt_version,
				$required_php_version,
				$required_mysql_version,
				$php_version,
				$mysql_version
			) . $php_update_message
		);
	} elseif ( ! $php_compat ) {
		return new MT_Error(
			'php_not_compatible',
			sprintf(
				/* translators: 1: paCMec version number, 2: Minimum required PHP version number, 3: Current PHP version number. */
				__( 'The update cannot be installed because paCMec %1$s requires PHP version %2$s or higher. You are running version %3$s.' ),
				$mt_version,
				$required_php_version,
				$php_version
			) . $php_update_message
		);
	} elseif ( ! $mysql_compat ) {
		return new MT_Error(
			'mysql_not_compatible',
			sprintf(
				/* translators: 1: paCMec version number, 2: Minimum required MySQL version number, 3: Current MySQL version number. */
				__( 'The update cannot be installed because paCMec %1$s requires MySQL version %2$s or higher. You are running version %3$s.' ),
				$mt_version,
				$required_mysql_version,
				$mysql_version
			)
		);
	}

	// Add a warning when the JSON PHP extension is missing.
	if ( ! extension_loaded( 'json' ) ) {
		return new MT_Error(
			'php_not_compatible_json',
			sprintf(
				/* translators: 1: paCMec version number, 2: The PHP extension name needed. */
				__( 'The update cannot be installed because paCMec %1$s requires the %2$s PHP extension.' ),
				$mt_version,
				'JSON'
			)
		);
	}

	/** This filter is documented in mt-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	// Don't copy mt-content, we'll deal with that below.
	// We also copy version.php last so failed updates report their old version.
	$skip              = array( 'mt-content', 'mt-includes/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating - only available for 3.7 and higher.
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory.
		$working_dir_local = MT_CONTENT_DIR . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $mt_version, isset( $mt_local_package ) ? $mt_local_package : 'es_CO' );
		if ( is_array( $checksums ) && isset( $checksums[ $mt_version ] ) ) {
			$checksums = $checksums[ $mt_version ]; // Compat code for 3.7-beta2.
		}
		if ( is_array( $checksums ) ) {
			foreach ( $checksums as $file => $checksum ) {
				if ( 'mt-content' === substr( $file, 0, 10 ) ) {
					continue;
				}
				if ( ! file_exists( ABSPATH . $file ) ) {
					continue;
				}
				if ( ! file_exists( $working_dir_local . $file ) ) {
					continue;
				}
				if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ), true ) ) {
					continue;
				}
				if ( md5_file( ABSPATH . $file ) === $checksum ) {
					$skip[] = $file;
				} else {
					$check_is_writable[ $file ] = ABSPATH . $file;
				}
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $mt_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $mt_filesystem, 'is_writable' ) );
		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );
			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$mt_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );
				if ( $mt_filesystem->is_writable( $file_not_writable ) ) {
					unset( $files_not_writable[ $relative_file_not_writable ] );
				}
			}

			// Store package-relative paths (the key) of non-writable files in the MT_Error object.
			$error_data = version_compare( $old_mt_version, '3.7-beta2', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable ) {
				return new MT_Error( 'files_not_writable', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), implode( ', ', $error_data ) );
			}
		}
	}

	/** This filter is documented in mt-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );
	// Create maintenance file to signal that we are upgrading.
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file   = $to . '.maintenance';
	$mt_filesystem->delete( $maintenance_file );
	$mt_filesystem->put_contents( $maintenance_file, $maintenance_string, FS_CHMOD_FILE );

	/** This filter is documented in mt-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );
	// Copy new versions of MT files into place.
	$result = _copy_dir( $from . $distro, $to, $skip );
	if ( is_mt_error( $result ) ) {
		$result = new MT_Error( $result->get_error_code(), $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
	}

	// Since we know the core files have copied over, we can now copy the version file.
	if ( ! is_mt_error( $result ) ) {
		if ( ! $mt_filesystem->copy( $from . $distro . 'mt-includes/version.php', $to . 'mt-includes/version.php', true /* overwrite */ ) ) {
			$mt_filesystem->delete( $from, true );
			$result = new MT_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'mt-includes/version.php' );
		}
		$mt_filesystem->chmod( $to . 'mt-includes/version.php', FS_CHMOD_FILE );
	}

	// Check to make sure everything copied correctly, ignoring the contents of mt-content.
	$skip   = array( 'mt-content' );
	$failed = array();
	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			if ( 'mt-content' === substr( $file, 0, 10 ) ) {
				continue;
			}
			if ( ! file_exists( $working_dir_local . $file ) ) {
				continue;
			}
			if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ), true ) ) {
				$skip[] = $file;
				continue;
			}
			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) == $checksum ) {
				$skip[] = $file;
			} else {
				$failed[] = $file;
			}
		}
	}

	// Some files didn't copy properly.
	if ( ! empty( $failed ) ) {
		$total_size = 0;
		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) ) {
				$total_size += filesize( $working_dir_local . $file );
			}
		}

		// If we don't have enough free space, it isn't worth trying again.
		// Unlikely to be hit due to the check in unzip_file().
		$available_space = @disk_free_space( ABSPATH );
		if ( $available_space && $total_size >= $available_space ) {
			$result = new MT_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = _copy_dir( $from . $distro, $to, $skip );
			if ( is_mt_error( $result ) ) {
				$result = new MT_Error( $result->get_error_code() . '_retry', $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
			}
		}
	}

	// Custom content directory needs updating now.
	// Copy languages.
	if ( ! is_mt_error( $result ) && $mt_filesystem->is_dir( $from . $distro . 'mt-content/languages' ) ) {
		if ( MT_LANG_DIR != ABSPATH . MTINC . '/languages' || @is_dir( MT_LANG_DIR ) ) {
			$lang_dir = MT_LANG_DIR;
		} else {
			$lang_dir = MT_CONTENT_DIR . '/languages';
		}

		// Check if the language directory exists first.
		if ( ! @is_dir( $lang_dir ) && 0 === strpos( $lang_dir, ABSPATH ) ) {
			// If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			$mt_filesystem->mkdir( $to . str_replace( ABSPATH, '', $lang_dir ), FS_CHMOD_DIR );
			clearstatcache(); // For FTP, need to clear the stat cache.
		}

		if ( @is_dir( $lang_dir ) ) {
			$mt_lang_dir = $mt_filesystem->find_folder( $lang_dir );
			if ( $mt_lang_dir ) {
				$result = copy_dir( $from . $distro . 'mt-content/languages/', $mt_lang_dir );
				if ( is_mt_error( $result ) ) {
					$result = new MT_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $mt_lang_dir ) ) );
				}
			}
		}
	}

	/** This filter is documented in mt-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );
	// Remove maintenance file, we're done with potential site-breaking changes.
	$mt_filesystem->delete( $maintenance_file );

	// 3.5 -> 3.5+ - an empty twentytwelve directory was created upon upgrade to 3.5 for some users, preventing installation of Twenty Twelve.
	if ( '3.5' === $old_mt_version ) {
		if ( is_dir( MT_CONTENT_DIR . '/themes/twentytwelve' ) && ! file_exists( MT_CONTENT_DIR . '/themes/twentytwelve/style.css' ) ) {
			$mt_filesystem->delete( $mt_filesystem->mt_themes_dir() . 'twentytwelve/' );
		}
	}

	/*
	 * Copy new bundled plugins & themes.
	 * This gives us the ability to install new plugins & themes bundled with
	 * future versions of paCMec whilst avoiding the re-install upon upgrade issue.
	 * $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated.
	 */
	if ( ! is_mt_error( $result ) && ( ! defined( 'CORE_UPGRADE_SKIP_NEW_BUNDLED' ) || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running.
			if ( $development_build || version_compare( $introduced_version, $old_mt_version, '>' ) ) {
				$directory = ( '/' === $file[ strlen( $file ) - 1 ] );

				list( $type, $filename ) = explode( '/', $file, 2 );

				// Check to see if the bundled items exist before attempting to copy them.
				if ( ! $mt_filesystem->exists( $from . $distro . 'mt-content/' . $file ) ) {
					continue;
				}

				if ( 'plugins' === $type ) {
					$dest = $mt_filesystem->mt_plugins_dir();
				} elseif ( 'themes' === $type ) {
					// Back-compat, ::mt_themes_dir() did not return trailingslash'd pre-3.2.
					$dest = trailingslashit( $mt_filesystem->mt_themes_dir() );
				} else {
					continue;
				}

				if ( ! $directory ) {
					if ( ! $development_build && $mt_filesystem->exists( $dest . $filename ) ) {
						continue;
					}

					if ( ! $mt_filesystem->copy( $from . $distro . 'mt-content/' . $file, $dest . $filename, FS_CHMOD_FILE ) ) {
						$result = new MT_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
					}
				} else {
					if ( ! $development_build && $mt_filesystem->is_dir( $dest . $filename ) ) {
						continue;
					}

					$mt_filesystem->mkdir( $dest . $filename, FS_CHMOD_DIR );
					$_result = copy_dir( $from . $distro . 'mt-content/' . $file, $dest . $filename );

					// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
					if ( is_mt_error( $_result ) ) {
						if ( ! is_mt_error( $result ) ) {
							$result = new MT_Error;
						}
						$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
					}
				}
			}
		} // End foreach.
	}

	// Handle $result error from the above blocks.
	if ( is_mt_error( $result ) ) {
		$mt_filesystem->delete( $from, true );
		return $result;
	}

	// Remove old files.
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;
		if ( ! $mt_filesystem->exists( $old_file ) ) {
			continue;
		}

		// If the file isn't deleted, try writing an empty string to the file instead.
		if ( ! $mt_filesystem->delete( $old_file, true ) && $mt_filesystem->is_file( $old_file ) ) {
			$mt_filesystem->put_contents( $old_file, '' );
		}
	}

	// Remove any Genericons example.html's from the filesystem.
	_upgrade_422_remove_genericons();

	// Remove the REST API plugin if its version is Beta 4 or lower.
	_upgrade_440_force_deactivate_incompatible_plugins();

	// Upgrade DB with separate request.
	/** This filter is documented in mt-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );
	$db_upgrade_url = admin_url( 'upgrade.php?step=upgrade_db' );
	mt_remote_post( $db_upgrade_url, array( 'timeout' => 60 ) );

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache.
	mt_cache_flush();
	// Not all cache back ends listen to 'flush'.
	mt_cache_delete( 'alloptions', 'options' );

	// Remove working directory.
	$mt_filesystem->delete( $from, true );

	// Force refresh of update information.
	if ( function_exists( 'delete_site_transient' ) ) {
		delete_site_transient( 'update_core' );
	} else {
		delete_option( 'update_core' );
	}

	/**
	 * Fires after paCMec core has been successfully updated.
	 *
	 * @since 3.3.0
	 *
	 * @param string $mt_version The current paCMec version.
	 */
	do_action( '_core_updated_successfully', $mt_version );

	// Clear the option that blocks auto-updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) ) {
		delete_site_option( 'auto_core_update_failed' );
	}

	return $mt_version;
}

/**
 * Copies a directory from one location to another via the paCMec Filesystem Abstraction.
 *
 * Assumes that MT_Filesystem() has already been called and setup.
 *
 * This is a standalone copy of the `copy_dir()` function that is used to
 * upgrade the core files. It is placed here so that the version of this
 * function from the *new* paCMec version will be called.
 *
 * It was initially added for the 3.1 -> 3.2 upgrade.
 *
 * @ignore
 * @since 3.2.0
 * @since 3.7.0 Updated not to use a regular expression for the skip list.
 *
 * @see copy_dir()
 * @link https://core.trac.managertechnology.com.co/pacmec/ticket/17173
 *
 * @global MT_Filesystem_Base $mt_filesystem
 *
 * @param string   $from      Source directory.
 * @param string   $to        Destination directory.
 * @param string[] $skip_list Array of files/folders to skip copying.
 * @return true|MT_Error True on success, MT_Error on failure.
 */
function _copy_dir( $from, $to, $skip_list = array() ) {
	global $mt_filesystem;

	$dirlist = $mt_filesystem->dirlist( $from );

	if ( false === $dirlist ) {
		return new MT_Error( 'dirlist_failed__copy_dir', __( 'Directory listing failed.' ), basename( $to ) );
	}

	$from = trailingslashit( $from );
	$to   = trailingslashit( $to );

	foreach ( (array) $dirlist as $filename => $fileinfo ) {
		if ( in_array( $filename, $skip_list, true ) ) {
			continue;
		}

		if ( 'f' === $fileinfo['type'] ) {
			if ( ! $mt_filesystem->copy( $from . $filename, $to . $filename, true, FS_CHMOD_FILE ) ) {
				// If copy failed, chmod file to 0644 and try again.
				$mt_filesystem->chmod( $to . $filename, FS_CHMOD_FILE );
				if ( ! $mt_filesystem->copy( $from . $filename, $to . $filename, true, FS_CHMOD_FILE ) ) {
					return new MT_Error( 'copy_failed__copy_dir', __( 'Could not copy file.' ), $to . $filename );
				}
			}

			// `mt_opcache_invalidate()` only exists in paCMec 5.5, so don't run it when upgrading to 5.5.
			if ( function_exists( 'mt_opcache_invalidate' ) ) {
				mt_opcache_invalidate( $to . $filename );
			}
		} elseif ( 'd' === $fileinfo['type'] ) {
			if ( ! $mt_filesystem->is_dir( $to . $filename ) ) {
				if ( ! $mt_filesystem->mkdir( $to . $filename, FS_CHMOD_DIR ) ) {
					return new MT_Error( 'mkdir_failed__copy_dir', __( 'Could not create directory.' ), $to . $filename );
				}
			}

			/*
			 * Generate the $sub_skip_list for the subdirectory as a sub-set
			 * of the existing $skip_list.
			 */
			$sub_skip_list = array();
			foreach ( $skip_list as $skip_item ) {
				if ( 0 === strpos( $skip_item, $filename . '/' ) ) {
					$sub_skip_list[] = preg_replace( '!^' . preg_quote( $filename, '!' ) . '/!i', '', $skip_item );
				}
			}

			$result = _copy_dir( $from . $filename, $to . $filename, $sub_skip_list );
			if ( is_mt_error( $result ) ) {
				return $result;
			}
		}
	}
	return true;
}

/**
 * Redirect to the About paCMec page after a successful upgrade.
 *
 * This function is only needed when the existing installation is older than 3.4.0.
 *
 * @since 3.3.0
 *
 * @global string $mt_version The paCMec version string.
 * @global string $pagenow
 * @global string $action
 *
 * @param string $new_version
 */
function _redirect_to_about_pacmec( $new_version ) {
	global $mt_version, $pagenow, $action;

	if ( version_compare( $mt_version, '3.4-RC1', '>=' ) ) {
		return;
	}

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' !== $pagenow ) {
		return;
	}

	if ( 'do-core-upgrade' !== $action && 'do-core-reinstall' !== $action ) {
		return;
	}

	// Load the updated default text localization domain for new strings.
	load_default_textdomain();

	// See do_core_upgrade().
	show_message( __( 'paCMec updated successfully.' ) );

	// self_admin_url() won't exist when upgrading from <= 3.0, so relative URLs are intentional.
	show_message(
		'<span class="hide-if-no-js">' . sprintf(
			/* translators: 1: paCMec version, 2: URL to About screen. */
			__( 'Welcome to paCMec %1$s. You will be redirected to the About paCMec screen. If not, click <a href="%2$s">here</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	show_message(
		'<span class="hide-if-js">' . sprintf(
			/* translators: 1: paCMec version, 2: URL to About screen. */
			__( 'Welcome to paCMec %1$s. <a href="%2$s">Learn more</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit.
	require_once ABSPATH . 'mt-admin/admin-footer.php';
	exit;
}

/**
 * Cleans up Genericons example files.
 *
 * @since 4.2.2
 *
 * @global array              $mt_theme_directories
 * @global MT_Filesystem_Base $mt_filesystem
 */
function _upgrade_422_remove_genericons() {
	global $mt_theme_directories, $mt_filesystem;

	// A list of the affected files using the filesystem absolute paths.
	$affected_files = array();

	// Themes.
	foreach ( $mt_theme_directories as $directory ) {
		$affected_theme_files = _upgrade_422_find_genericons_files_in_folder( $directory );
		$affected_files       = array_merge( $affected_files, $affected_theme_files );
	}

	// Plugins.
	$affected_plugin_files = _upgrade_422_find_genericons_files_in_folder( MT_PLUGIN_DIR );
	$affected_files        = array_merge( $affected_files, $affected_plugin_files );

	foreach ( $affected_files as $file ) {
		$gen_dir = $mt_filesystem->find_folder( trailingslashit( dirname( $file ) ) );
		if ( empty( $gen_dir ) ) {
			continue;
		}

		// The path when the file is accessed via MT_Filesystem may differ in the case of FTP.
		$remote_file = $gen_dir . basename( $file );

		if ( ! $mt_filesystem->exists( $remote_file ) ) {
			continue;
		}

		if ( ! $mt_filesystem->delete( $remote_file, false, 'f' ) ) {
			$mt_filesystem->put_contents( $remote_file, '' );
		}
	}
}

/**
 * Recursively find Genericons example files in a given folder.
 *
 * @ignore
 * @since 4.2.2
 *
 * @param string $directory Directory path. Expects trailingslashed.
 * @return array
 */
function _upgrade_422_find_genericons_files_in_folder( $directory ) {
	$directory = trailingslashit( $directory );
	$files     = array();

	if ( file_exists( "{$directory}example.html" ) && false !== strpos( file_get_contents( "{$directory}example.html" ), '<title>Genericons</title>' ) ) {
		$files[] = "{$directory}example.html";
	}

	$dirs = glob( $directory . '*', GLOB_ONLYDIR );
	if ( $dirs ) {
		foreach ( $dirs as $dir ) {
			$files = array_merge( $files, _upgrade_422_find_genericons_files_in_folder( $dir ) );
		}
	}

	return $files;
}

/**
 * @ignore
 * @since 4.4.0
 */
function _upgrade_440_force_deactivate_incompatible_plugins() {
	if ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, '2.0-beta4', '<=' ) ) {
		deactivate_plugins( array( 'rest-api/plugin.php' ), true );
	}
}
