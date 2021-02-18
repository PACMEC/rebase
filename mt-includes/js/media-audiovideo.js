/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

/**
 * @output mt-includes/js/media-audiovideo.js
 */

var media = mt.media,
	baseSettings = window._mtmejsSettings || {},
	l10n = window._mtMediaViewsL10n || {};

/**
 *
 * Defines the mt.media.mixin object.
 *
 * @mixin
 *
 * @since 4.2.0
 */
mt.media.mixin = {
	mejsSettings: baseSettings,

	/**
	 * Pauses and removes all players.
	 *
	 * @since 4.2.0
	 *
	 * @return {void}
	 */
	removeAllPlayers: function() {
		var p;

		if ( window.mejs && window.mejs.players ) {
			for ( p in window.mejs.players ) {
				window.mejs.players[p].pause();
				this.removePlayer( window.mejs.players[p] );
			}
		}
	},

	/**
	 * Removes the player.
	 *
	 * Override the MediaElement method for removing a player.
	 * MediaElement tries to pull the audio/video tag out of
	 * its container and re-add it to the DOM.
	 *
	 * @since 4.2.0
	 *
	 * @return {void}
	 */
	removePlayer: function(t) {
		var featureIndex, feature;

		if ( ! t.options ) {
			return;
		}

		// Invoke features cleanup.
		for ( featureIndex in t.options.features ) {
			feature = t.options.features[featureIndex];
			if ( t['clean' + feature] ) {
				try {
					t['clean' + feature](t);
				} catch (e) {}
			}
		}

		if ( ! t.isDynamic ) {
			t.node.remove();
		}

		if ( 'html5' !== t.media.rendererName ) {
			t.media.remove();
		}

		delete window.mejs.players[t.id];

		t.container.remove();
		t.globalUnbind('resize', t.globalResizeCallback);
		t.globalUnbind('keydown', t.globalKeydownCallback);
		t.globalUnbind('click', t.globalClickCallback);
		delete t.media.player;
	},

	/**
	 *
	 * Removes and resets all players.
	 *
	 * Allows any class that has set 'player' to a MediaElementPlayer
	 * instance to remove the player when listening to events.
	 *
	 * Examples: modal closes, shortcode properties are removed, etc.
	 *
	 * @since 4.2.0
	 */
	unsetPlayers : function() {
		if ( this.players && this.players.length ) {
			_.each( this.players, function (player) {
				player.pause();
				mt.media.mixin.removePlayer( player );
			} );
			this.players = [];
		}
	}
};

/**
 * Shortcode modeling for playlists.
 *
 * @since 4.2.0
 */
mt.media.playlist = new mt.media.collection({
	tag: 'playlist',
	editTitle : l10n.editPlaylistTitle,
	defaults : {
		id: mt.media.view.settings.post.id,
		style: 'light',
		tracklist: true,
		tracknumbers: true,
		images: true,
		artists: true,
		type: 'audio'
	}
});

/**
 * Shortcode modeling for audio.
 *
 * `edit()` prepares the shortcode for the media modal.
 * `shortcode()` builds the new shortcode after an update.
 *
 * @namespace
 *
 * @since 4.2.0
 */
mt.media.audio = {
	coerce : mt.media.coerce,

	defaults : {
		id : mt.media.view.settings.post.id,
		src : '',
		loop : false,
		autoplay : false,
		preload : 'none',
		width : 400
	},

	/**
	 * Instantiates a new media object with the next matching shortcode.
	 *
	 * @since 4.2.0
	 *
	 * @param {string} data The text to apply the shortcode on.
	 * @return {mt.media} The media object.
	 */
	edit : function( data ) {
		var frame, shortcode = mt.shortcode.next( 'audio', data ).shortcode;

		frame = mt.media({
			frame: 'audio',
			state: 'audio-details',
			metadata: _.defaults( shortcode.attrs.named, this.defaults )
		});

		return frame;
	},

	/**
	 * Generates an audio shortcode.
	 *
	 * @since 4.2.0
	 *
	 * @param {Array} model Array with attributes for the shortcode.
	 * @return {mt.shortcode} The audio shortcode object.
	 */
	shortcode : function( model ) {
		var content;

		_.each( this.defaults, function( value, key ) {
			model[ key ] = this.coerce( model, key );

			if ( value === model[ key ] ) {
				delete model[ key ];
			}
		}, this );

		content = model.content;
		delete model.content;

		return new mt.shortcode({
			tag: 'audio',
			attrs: model,
			content: content
		});
	}
};

/**
 * Shortcode modeling for video.
 *
 *  `edit()` prepares the shortcode for the media modal.
 *  `shortcode()` builds the new shortcode after update.
 *
 * @since 4.2.0
 *
 * @namespace
 */
mt.media.video = {
	coerce : mt.media.coerce,

	defaults : {
		id : mt.media.view.settings.post.id,
		src : '',
		poster : '',
		loop : false,
		autoplay : false,
		preload : 'metadata',
		content : '',
		width : 640,
		height : 360
	},

	/**
	 * Instantiates a new media object with the next matching shortcode.
	 *
	 * @since 4.2.0
	 *
	 * @param {string} data The text to apply the shortcode on.
	 * @return {mt.media} The media object.
	 */
	edit : function( data ) {
		var frame,
			shortcode = mt.shortcode.next( 'video', data ).shortcode,
			attrs;

		attrs = shortcode.attrs.named;
		attrs.content = shortcode.content;

		frame = mt.media({
			frame: 'video',
			state: 'video-details',
			metadata: _.defaults( attrs, this.defaults )
		});

		return frame;
	},

	/**
	 * Generates an video shortcode.
	 *
	 * @since 4.2.0
	 *
	 * @param {Array} model Array with attributes for the shortcode.
	 * @return {mt.shortcode} The video shortcode object.
	 */
	shortcode : function( model ) {
		var content;

		_.each( this.defaults, function( value, key ) {
			model[ key ] = this.coerce( model, key );

			if ( value === model[ key ] ) {
				delete model[ key ];
			}
		}, this );

		content = model.content;
		delete model.content;

		return new mt.shortcode({
			tag: 'video',
			attrs: model,
			content: content
		});
	}
};

media.model.PostMedia = __webpack_require__( 2 );
media.controller.AudioDetails = __webpack_require__( 3 );
media.controller.VideoDetails = __webpack_require__( 4 );
media.view.MediaFrame.MediaDetails = __webpack_require__( 5 );
media.view.MediaFrame.AudioDetails = __webpack_require__( 6 );
media.view.MediaFrame.VideoDetails = __webpack_require__( 7 );
media.view.MediaDetails = __webpack_require__( 8 );
media.view.AudioDetails = __webpack_require__( 9 );
media.view.VideoDetails = __webpack_require__( 10 );


/***/ }),
/* 2 */
/***/ (function(module, exports) {

/**
 * mt.media.model.PostMedia
 *
 * Shared model class for audio and video. Updates the model after
 *   "Add Audio|Video Source" and "Replace Audio|Video" states return
 *
 * @memberOf mt.media.model
 *
 * @class
 * @augments Backbone.Model
 */
var PostMedia = Backbone.Model.extend(/** @lends mt.media.model.PostMedia.prototype */{
	initialize: function() {
		this.attachment = false;
	},

	setSource: function( attachment ) {
		this.attachment = attachment;
		this.extension = attachment.get( 'filename' ).split('.').pop();

		if ( this.get( 'src' ) && this.extension === this.get( 'src' ).split('.').pop() ) {
			this.unset( 'src' );
		}

		if ( _.contains( mt.media.view.settings.embedExts, this.extension ) ) {
			this.set( this.extension, this.attachment.get( 'url' ) );
		} else {
			this.unset( this.extension );
		}
	},

	changeAttachment: function( attachment ) {
		this.setSource( attachment );

		this.unset( 'src' );
		_.each( _.without( mt.media.view.settings.embedExts, this.extension ), function( ext ) {
			this.unset( ext );
		}, this );
	}
});

module.exports = PostMedia;


/***/ }),
/* 3 */
/***/ (function(module, exports) {

var State = mt.media.controller.State,
	l10n = mt.media.view.l10n,
	AudioDetails;

/**
 * mt.media.controller.AudioDetails
 *
 * The controller for the Audio Details state
 *
 * @memberOf mt.media.controller
 *
 * @class
 * @augments mt.media.controller.State
 * @augments Backbone.Model
 */
AudioDetails = State.extend(/** @lends mt.media.controller.AudioDetails.prototype */{
	defaults: {
		id: 'audio-details',
		toolbar: 'audio-details',
		title: l10n.audioDetailsTitle,
		content: 'audio-details',
		menu: 'audio-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = AudioDetails;


/***/ }),
/* 4 */
/***/ (function(module, exports) {

/**
 * mt.media.controller.VideoDetails
 *
 * The controller for the Video Details state
 *
 * @memberOf mt.media.controller
 *
 * @class
 * @augments mt.media.controller.State
 * @augments Backbone.Model
 */
var State = mt.media.controller.State,
	l10n = mt.media.view.l10n,
	VideoDetails;

VideoDetails = State.extend(/** @lends mt.media.controller.VideoDetails.prototype */{
	defaults: {
		id: 'video-details',
		toolbar: 'video-details',
		title: l10n.videoDetailsTitle,
		content: 'video-details',
		menu: 'video-details',
		router: false,
		priority: 60
	},

	initialize: function( options ) {
		this.media = options.media;
		State.prototype.initialize.apply( this, arguments );
	}
});

module.exports = VideoDetails;


/***/ }),
/* 5 */
/***/ (function(module, exports) {

var Select = mt.media.view.MediaFrame.Select,
	l10n = mt.media.view.l10n,
	MediaDetails;

/**
 * mt.media.view.MediaFrame.MediaDetails
 *
 * @memberOf mt.media.view.MediaFrame
 *
 * @class
 * @augments mt.media.view.MediaFrame.Select
 * @augments mt.media.view.MediaFrame
 * @augments mt.media.view.Frame
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 * @mixes mt.media.controller.StateMachine
 */
MediaDetails = Select.extend(/** @lends mt.media.view.MediaFrame.MediaDetails.prototype */{
	defaults: {
		id:      'media',
		url:     '',
		menu:    'media-details',
		content: 'media-details',
		toolbar: 'media-details',
		type:    'link',
		priority: 120
	},

	initialize: function( options ) {
		this.DetailsView = options.DetailsView;
		this.cancelText = options.cancelText;
		this.addText = options.addText;

		this.media = new mt.media.model.PostMedia( options.metadata );
		this.options.selection = new mt.media.model.Selection( this.media.attachment, { multiple: false } );
		Select.prototype.initialize.apply( this, arguments );
	},

	bindHandlers: function() {
		var menu = this.defaults.menu;

		Select.prototype.bindHandlers.apply( this, arguments );

		this.on( 'menu:create:' + menu, this.createMenu, this );
		this.on( 'content:render:' + menu, this.renderDetailsContent, this );
		this.on( 'menu:render:' + menu, this.renderMenu, this );
		this.on( 'toolbar:render:' + menu, this.renderDetailsToolbar, this );
	},

	renderDetailsContent: function() {
		var view = new this.DetailsView({
			controller: this,
			model: this.state().media,
			attachment: this.state().media.attachment
		}).render();

		this.content.set( view );
	},

	renderMenu: function( view ) {
		var lastState = this.lastState(),
			previous = lastState && lastState.id,
			frame = this;

		view.set({
			cancel: {
				text:     this.cancelText,
				priority: 20,
				click:    function() {
					if ( previous ) {
						frame.setState( previous );
					} else {
						frame.close();
					}
				}
			},
			separateCancel: new mt.media.View({
				className: 'separator',
				priority: 40
			})
		});

	},

	setPrimaryButton: function(text, handler) {
		this.toolbar.set( new mt.media.view.Toolbar({
			controller: this,
			items: {
				button: {
					style:    'primary',
					text:     text,
					priority: 80,
					click:    function() {
						var controller = this.controller;
						handler.call( this, controller, controller.state() );
						// Restore and reset the default state.
						controller.setState( controller.options.state );
						controller.reset();
					}
				}
			}
		}) );
	},

	renderDetailsToolbar: function() {
		this.setPrimaryButton( l10n.update, function( controller, state ) {
			controller.close();
			state.trigger( 'update', controller.media.toJSON() );
		} );
	},

	renderReplaceToolbar: function() {
		this.setPrimaryButton( l10n.replace, function( controller, state ) {
			var attachment = state.get( 'selection' ).single();
			controller.media.changeAttachment( attachment );
			state.trigger( 'replace', controller.media.toJSON() );
		} );
	},

	renderAddSourceToolbar: function() {
		this.setPrimaryButton( this.addText, function( controller, state ) {
			var attachment = state.get( 'selection' ).single();
			controller.media.setSource( attachment );
			state.trigger( 'add-source', controller.media.toJSON() );
		} );
	}
});

module.exports = MediaDetails;


/***/ }),
/* 6 */
/***/ (function(module, exports) {

var MediaDetails = mt.media.view.MediaFrame.MediaDetails,
	MediaLibrary = mt.media.controller.MediaLibrary,

	l10n = mt.media.view.l10n,
	AudioDetails;

/**
 * mt.media.view.MediaFrame.AudioDetails
 *
 * @memberOf mt.media.view.MediaFrame
 *
 * @class
 * @augments mt.media.view.MediaFrame.MediaDetails
 * @augments mt.media.view.MediaFrame.Select
 * @augments mt.media.view.MediaFrame
 * @augments mt.media.view.Frame
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 * @mixes mt.media.controller.StateMachine
 */
AudioDetails = MediaDetails.extend(/** @lends mt.media.view.MediaFrame.AudioDetails.prototype */{
	defaults: {
		id:      'audio',
		url:     '',
		menu:    'audio-details',
		content: 'audio-details',
		toolbar: 'audio-details',
		type:    'link',
		title:    l10n.audioDetailsTitle,
		priority: 120
	},

	initialize: function( options ) {
		options.DetailsView = mt.media.view.AudioDetails;
		options.cancelText = l10n.audioDetailsCancel;
		options.addText = l10n.audioAddSourceTitle;

		MediaDetails.prototype.initialize.call( this, options );
	},

	bindHandlers: function() {
		MediaDetails.prototype.bindHandlers.apply( this, arguments );

		this.on( 'toolbar:render:replace-audio', this.renderReplaceToolbar, this );
		this.on( 'toolbar:render:add-audio-source', this.renderAddSourceToolbar, this );
	},

	createStates: function() {
		this.states.add([
			new mt.media.controller.AudioDetails( {
				media: this.media
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'replace-audio',
				title: l10n.audioReplaceTitle,
				toolbar: 'replace-audio',
				media: this.media,
				menu: 'audio-details'
			} ),

			new MediaLibrary( {
				type: 'audio',
				id: 'add-audio-source',
				title: l10n.audioAddSourceTitle,
				toolbar: 'add-audio-source',
				media: this.media,
				menu: false
			} )
		]);
	}
});

module.exports = AudioDetails;


/***/ }),
/* 7 */
/***/ (function(module, exports) {

var MediaDetails = mt.media.view.MediaFrame.MediaDetails,
	MediaLibrary = mt.media.controller.MediaLibrary,
	l10n = mt.media.view.l10n,
	VideoDetails;

/**
 * mt.media.view.MediaFrame.VideoDetails
 *
 * @memberOf mt.media.view.MediaFrame
 *
 * @class
 * @augments mt.media.view.MediaFrame.MediaDetails
 * @augments mt.media.view.MediaFrame.Select
 * @augments mt.media.view.MediaFrame
 * @augments mt.media.view.Frame
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 * @mixes mt.media.controller.StateMachine
 */
VideoDetails = MediaDetails.extend(/** @lends mt.media.view.MediaFrame.VideoDetails.prototype */{
	defaults: {
		id:      'video',
		url:     '',
		menu:    'video-details',
		content: 'video-details',
		toolbar: 'video-details',
		type:    'link',
		title:    l10n.videoDetailsTitle,
		priority: 120
	},

	initialize: function( options ) {
		options.DetailsView = mt.media.view.VideoDetails;
		options.cancelText = l10n.videoDetailsCancel;
		options.addText = l10n.videoAddSourceTitle;

		MediaDetails.prototype.initialize.call( this, options );
	},

	bindHandlers: function() {
		MediaDetails.prototype.bindHandlers.apply( this, arguments );

		this.on( 'toolbar:render:replace-video', this.renderReplaceToolbar, this );
		this.on( 'toolbar:render:add-video-source', this.renderAddSourceToolbar, this );
		this.on( 'toolbar:render:select-poster-image', this.renderSelectPosterImageToolbar, this );
		this.on( 'toolbar:render:add-track', this.renderAddTrackToolbar, this );
	},

	createStates: function() {
		this.states.add([
			new mt.media.controller.VideoDetails({
				media: this.media
			}),

			new MediaLibrary( {
				type: 'video',
				id: 'replace-video',
				title: l10n.videoReplaceTitle,
				toolbar: 'replace-video',
				media: this.media,
				menu: 'video-details'
			} ),

			new MediaLibrary( {
				type: 'video',
				id: 'add-video-source',
				title: l10n.videoAddSourceTitle,
				toolbar: 'add-video-source',
				media: this.media,
				menu: false
			} ),

			new MediaLibrary( {
				type: 'image',
				id: 'select-poster-image',
				title: l10n.videoSelectPosterImageTitle,
				toolbar: 'select-poster-image',
				media: this.media,
				menu: 'video-details'
			} ),

			new MediaLibrary( {
				type: 'text',
				id: 'add-track',
				title: l10n.videoAddTrackTitle,
				toolbar: 'add-track',
				media: this.media,
				menu: 'video-details'
			} )
		]);
	},

	renderSelectPosterImageToolbar: function() {
		this.setPrimaryButton( l10n.videoSelectPosterImageTitle, function( controller, state ) {
			var urls = [], attachment = state.get( 'selection' ).single();

			controller.media.set( 'poster', attachment.get( 'url' ) );
			state.trigger( 'set-poster-image', controller.media.toJSON() );

			_.each( mt.media.view.settings.embedExts, function (ext) {
				if ( controller.media.get( ext ) ) {
					urls.push( controller.media.get( ext ) );
				}
			} );

			mt.ajax.send( 'set-attachment-thumbnail', {
				data : {
					urls: urls,
					thumbnail_id: attachment.get( 'id' )
				}
			} );
		} );
	},

	renderAddTrackToolbar: function() {
		this.setPrimaryButton( l10n.videoAddTrackTitle, function( controller, state ) {
			var attachment = state.get( 'selection' ).single(),
				content = controller.media.get( 'content' );

			if ( -1 === content.indexOf( attachment.get( 'url' ) ) ) {
				content += [
					'<track srclang="en" label="English" kind="subtitles" src="',
					attachment.get( 'url' ),
					'" />'
				].join('');

				controller.media.set( 'content', content );
			}
			state.trigger( 'add-track', controller.media.toJSON() );
		} );
	}
});

module.exports = VideoDetails;


/***/ }),
/* 8 */
/***/ (function(module, exports) {

/* global MediaElementPlayer */
var AttachmentDisplay = mt.media.view.Settings.AttachmentDisplay,
	$ = jQuery,
	MediaDetails;

/**
 * mt.media.view.MediaDetails
 *
 * @memberOf mt.media.view
 *
 * @class
 * @augments mt.media.view.Settings.AttachmentDisplay
 * @augments mt.media.view.Settings
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 */
MediaDetails = AttachmentDisplay.extend(/** @lends mt.media.view.MediaDetails.prototype */{
	initialize: function() {
		_.bindAll(this, 'success');
		this.players = [];
		this.listenTo( this.controller.states, 'close', mt.media.mixin.unsetPlayers );
		this.on( 'ready', this.setPlayer );
		this.on( 'media:setting:remove', mt.media.mixin.unsetPlayers, this );
		this.on( 'media:setting:remove', this.render );
		this.on( 'media:setting:remove', this.setPlayer );

		AttachmentDisplay.prototype.initialize.apply( this, arguments );
	},

	events: function(){
		return _.extend( {
			'click .remove-setting' : 'removeSetting',
			'change .content-track' : 'setTracks',
			'click .remove-track' : 'setTracks',
			'click .add-media-source' : 'addSource'
		}, AttachmentDisplay.prototype.events );
	},

	prepare: function() {
		return _.defaults({
			model: this.model.toJSON()
		}, this.options );
	},

	/**
	 * Remove a setting's UI when the model unsets it
	 *
	 * @fires mt.media.view.MediaDetails#media:setting:remove
	 *
	 * @param {Event} e
	 */
	removeSetting : function(e) {
		var wrap = $( e.currentTarget ).parent(), setting;
		setting = wrap.find( 'input' ).data( 'setting' );

		if ( setting ) {
			this.model.unset( setting );
			this.trigger( 'media:setting:remove', this );
		}

		wrap.remove();
	},

	/**
	 *
	 * @fires mt.media.view.MediaDetails#media:setting:remove
	 */
	setTracks : function() {
		var tracks = '';

		_.each( this.$('.content-track'), function(track) {
			tracks += $( track ).val();
		} );

		this.model.set( 'content', tracks );
		this.trigger( 'media:setting:remove', this );
	},

	addSource : function( e ) {
		this.controller.lastMime = $( e.currentTarget ).data( 'mime' );
		this.controller.setState( 'add-' + this.controller.defaults.id + '-source' );
	},

	loadPlayer: function () {
		this.players.push( new MediaElementPlayer( this.media, this.settings ) );
		this.scriptXhr = false;
	},

	setPlayer : function() {
		var src;

		if ( this.players.length || ! this.media || this.scriptXhr ) {
			return;
		}

		src = this.model.get( 'src' );

		if ( src && src.indexOf( 'vimeo' ) > -1 && ! ( 'Vimeo' in window ) ) {
			this.scriptXhr = $.getScript( 'https://player.vimeo.com/api/player.js', _.bind( this.loadPlayer, this ) );
		} else {
			this.loadPlayer();
		}
	},

	/**
	 * @abstract
	 */
	setMedia : function() {
		return this;
	},

	success : function(mejs) {
		var autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;

		if ( 'flash' === mejs.pluginType && autoplay ) {
			mejs.addEventListener( 'canplay', function() {
				mejs.play();
			}, false );
		}

		this.mejs = mejs;
	},

	/**
	 * @return {media.view.MediaDetails} Returns itself to allow chaining.
	 */
	render: function() {
		AttachmentDisplay.prototype.render.apply( this, arguments );

		setTimeout( _.bind( function() {
			this.scrollToTop();
		}, this ), 10 );

		this.settings = _.defaults( {
			success : this.success
		}, mt.media.mixin.mejsSettings );

		return this.setMedia();
	},

	scrollToTop: function() {
		this.$( '.embed-media-settings' ).scrollTop( 0 );
	}
},/** @lends mt.media.view.MediaDetails */{
	instances : 0,
	/**
	 * When multiple players in the DOM contain the same src, things get weird.
	 *
	 * @param {HTMLElement} elem
	 * @return {HTMLElement}
	 */
	prepareSrc : function( elem ) {
		var i = MediaDetails.instances++;
		_.each( $( elem ).find( 'source' ), function( source ) {
			source.src = [
				source.src,
				source.src.indexOf('?') > -1 ? '&' : '?',
				'_=',
				i
			].join('');
		} );

		return elem;
	}
});

module.exports = MediaDetails;


/***/ }),
/* 9 */
/***/ (function(module, exports) {

var MediaDetails = mt.media.view.MediaDetails,
	AudioDetails;

/**
 * mt.media.view.AudioDetails
 *
 * @memberOf mt.media.view
 *
 * @class
 * @augments mt.media.view.MediaDetails
 * @augments mt.media.view.Settings.AttachmentDisplay
 * @augments mt.media.view.Settings
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 */
AudioDetails = MediaDetails.extend(/** @lends mt.media.view.AudioDetails.prototype */{
	className: 'audio-details',
	template:  mt.template('audio-details'),

	setMedia: function() {
		var audio = this.$('.mt-audio-shortcode');

		if ( audio.find( 'source' ).length ) {
			if ( audio.is(':hidden') ) {
				audio.show();
			}
			this.media = MediaDetails.prepareSrc( audio.get(0) );
		} else {
			audio.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = AudioDetails;


/***/ }),
/* 10 */
/***/ (function(module, exports) {

var MediaDetails = mt.media.view.MediaDetails,
	VideoDetails;

/**
 * mt.media.view.VideoDetails
 *
 * @memberOf mt.media.view
 *
 * @class
 * @augments mt.media.view.MediaDetails
 * @augments mt.media.view.Settings.AttachmentDisplay
 * @augments mt.media.view.Settings
 * @augments mt.media.View
 * @augments mt.Backbone.View
 * @augments Backbone.View
 */
VideoDetails = MediaDetails.extend(/** @lends mt.media.view.VideoDetails.prototype */{
	className: 'video-details',
	template:  mt.template('video-details'),

	setMedia: function() {
		var video = this.$('.mt-video-shortcode');

		if ( video.find( 'source' ).length ) {
			if ( video.is(':hidden') ) {
				video.show();
			}

			if ( ! video.hasClass( 'youtube-video' ) && ! video.hasClass( 'vimeo-video' ) ) {
				this.media = MediaDetails.prepareSrc( video.get(0) );
			} else {
				this.media = video.get(0);
			}
		} else {
			video.hide();
			this.media = false;
		}

		return this;
	}
});

module.exports = VideoDetails;


/***/ })
/******/ ]);