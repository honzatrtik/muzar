define([
	'jquery',
	'can',
	'models/category',
	'mustache!controls/menu/templates/menu',
	'controls/base',
	'util/helpers'

], function ($, can, CategoryModel, renderer, BaseControl) {

	return BaseControl.extend({

		defaults: {
		}

	}, {
		init: function(element, options) {

			BaseControl.prototype.init.apply(this, arguments);
			this.options.categories = new CategoryModel.List();

		},

		'{state} category': function(state, event, newVal, oldVal) {
			this.initSelected(newVal, oldVal);
		},


		'{state} query': function(route, event, newVal, oldVal) {
			if (newVal) {
				this.setSelected(null);
			}
		},


		setSelected: function(category) {

			this.selected = category;

			// Muzeme selekci zrusit
			if (!category) {
				this.options.state.removeAttr('category');
			} else if (this.options.categories.attr('map').attr(category)) {
				this.options.state.attr('category', category);
			}

		},

		getSelectedCompute: function() {
			return this.options.state.compute('category');
		},

		getSelected: function() {
			return this.options.state.attr('category');
		},

		getCategory: function(strId) {
			return this.options.categories.attr('map').attr(strId);
		},

		getSelectedCategory: function() {
			return this.getCategory(this.getSelected());
		},

		getSelectedElement: function() {
			var category = this.getSelectedCategory();
			return category
				? category.element
				: null;
		},

		update: function() {
			var self = this;
			return this.options.model.findAll({}, function(categories) {

				self.options.categories = categories;
				self.render();

				self.on();

				self.$uls = self.element.find('ul ul');
				self.initSelected(self.getSelected(), null);

			});
		},

		initSelected: function(newVal, oldVal) {

			if (oldVal && this.getCategory(oldVal)) {
				this.getCategory(oldVal).element.removeClass('active');
			}

			if (newVal && this.getCategory(newVal)) {

				var $el = $(this.getCategory(newVal).element);

				$el.addClass('active');
				this.$uls.hide();

				$el.parentsUntil(this.element).show();
				$el.parent().find('> .menu').show();

				this.options.state.removeAttr('query');
				this.options.state.attr('category', newVal);

			} else {

				this.options.state.removeAttr('category');
				this.$uls.hide();

			}

			if (newVal) {
				this.setSelected(newVal);
			}
		},

		render: function() {
			this.element.html(renderer({
				children: this.options.categories
			}));
		}

	});

});