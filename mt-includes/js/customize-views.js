/**
 * @output mt-includes/js/customize-views.js
 */

(function( $, mt, _ ) {

	if ( ! mt || ! mt.customize ) { return; }
	var api = mt.customize;

	/**
	 * mt.customize.HeaderTool.CurrentView
	 *
	 * Displays the currently selected header image, or a placeholder in lack
	 * thereof.
	 *
	 * Instantiate with model mt.customize.HeaderTool.currentHeader.
	 *
	 * @memberOf mt.customize.HeaderTool
	 * @alias mt.customize.HeaderTool.CurrentView
	 *
	 * @constructor
	 * @augments mt.Backbone.View
	 */
	api.HeaderTool.CurrentView = mt.Backbone.View.extend(/** @lends mt.customize.HeaderTool.CurrentView.prototype */{
		template: mt.template('header-current'),

		initialize: function() {
			this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			this.setButtons();
			return this;
		},

		setButtons: function() {
			var elements = $('#customize-control-header_image .actions .remove');
			if (this.model.get('choice')) {
				elements.show();
			} else {
				elements.hide();
			}
		}
	});


	/**
	 * mt.customize.HeaderTool.ChoiceView
	 *
	 * Represents a choosable header image, be it user-uploaded,
	 * theme-suggested or a special Randomize choice.
	 *
	 * Takes a mt.customize.HeaderTool.ImageModel.
	 *
	 * Manually changes model mt.customize.HeaderTool.currentHeader via the
	 * `select` method.
	 *
	 * @memberOf mt.customize.HeaderTool
	 * @alias mt.customize.HeaderTool.ChoiceView
	 *
	 * @constructor
	 * @augments mt.Backbone.View
	 */
	api.HeaderTool.ChoiceView = mt.Backbone.View.extend(/** @lends mt.customize.HeaderTool.ChoiceView.prototype */{
		template: mt.template('header-choice'),

		className: 'header-view',

		events: {
			'click .choice,.random': 'select',
			'click .close': 'removeImage'
		},

		initialize: function() {
			var properties = [
				this.model.get('header').url,
				this.model.get('choice')
			];

			this.listenTo(this.model, 'change:selected', this.toggleSelected);

			if (_.contains(properties, api.get().header_image)) {
				api.HeaderTool.currentHeader.set(this.extendedModel());
			}
		},

		render: function() {
			this.$el.html(this.template(this.extendedModel()));

			this.toggleSelected();
			return this;
		},

		toggleSelected: function() {
			this.$el.toggleClass('selected', this.model.get('selected'));
		},

		extendedModel: function() {
			var c = this.model.get('collection');
			return _.extend(this.model.toJSON(), {
				type: c.type
			});
		},

		select: function() {
			this.preventJump();
			this.model.save();
			api.HeaderTool.currentHeader.set(this.extendedModel());
		},

		preventJump: function() {
			var container = $('.mt-full-overlay-sidebar-content'),
				scroll = container.scrollTop();

			_.defer(function() {
				container.scrollTop(scroll);
			});
		},

		removeImage: function(e) {
			e.stopPropagation();
			this.model.destroy();
			this.remove();
		}
	});


	/**
	 * mt.customize.HeaderTool.ChoiceListView
	 *
	 * A container for ChoiceViews. These choices should be of one same type:
	 * user-uploaded headers or theme-defined ones.
	 *
	 * Takes a mt.customize.HeaderTool.ChoiceList.
	 *
	 * @memberOf mt.customize.HeaderTool
	 * @alias mt.customize.HeaderTool.ChoiceListView
	 *
	 * @constructor
	 * @augments mt.Backbone.View
	 */
	api.HeaderTool.ChoiceListView = mt.Backbone.View.extend(/** @lends mt.customize.HeaderTool.ChoiceListView.prototype */{
		initialize: function() {
			this.listenTo(this.collection, 'add', this.addOne);
			this.listenTo(this.collection, 'remove', this.render);
			this.listenTo(this.collection, 'sort', this.render);
			this.listenTo(this.collection, 'change', this.toggleList);
			this.render();
		},

		render: function() {
			this.$el.empty();
			this.collection.each(this.addOne, this);
			this.toggleList();
		},

		addOne: function(choice) {
			var view;
			choice.set({ collection: this.collection });
			view = new api.HeaderTool.ChoiceView({ model: choice });
			this.$el.append(view.render().el);
		},

		toggleList: function() {
			var title = this.$el.parents().prev('.customize-control-title'),
				randomButton = this.$el.find('.random').parent();
			if (this.collection.shouldHideTitle()) {
				title.add(randomButton).hide();
			} else {
				title.add(randomButton).show();
			}
		}
	});


	/**
	 * mt.customize.HeaderTool.CombinedList
	 *
	 * Aggregates mt.customize.HeaderTool.ChoiceList collections (or any
	 * Backbone object, really) and acts as a bus to feed them events.
	 *
	 * @memberOf mt.customize.HeaderTool
	 * @alias mt.customize.HeaderTool.CombinedList
	 *
	 * @constructor
	 * @augments mt.Backbone.View
	 */
	api.HeaderTool.CombinedList = mt.Backbone.View.extend(/** @lends mt.customize.HeaderTool.CombinedList.prototype */{
		initialize: function(collections) {
			this.collections = collections;
			this.on('all', this.propagate, this);
		},
		propagate: function(event, arg) {
			_.each(this.collections, function(collection) {
				collection.trigger(event, arg);
			});
		}
	});

})( jQuery, window.mt, _ );
