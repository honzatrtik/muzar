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

			var self = this;

			this.options.categories = new can.List();
			this.options.categoriesMap = new can.Map();
			this.options.selected = can.compute();

			this.options.selected.bind('change', function(event, newSelected, oldSelected) {

				if (oldSelected && self.getCategory(oldSelected)) {
					self.getCategory(oldSelected).element.removeClass('active');
				}

				if (newSelected && self.getCategory(newSelected)) {

					var $el = $(self.getCategory(newSelected).element);


					$el.addClass('active');
					self.$uls.hide();

					$el.parentsUntil(self.element).show();
					$el.parent().find('> .menu').show();

					self.options.state.removeAttr('query');
					self.options.state.attr('category', newSelected);
				} else {

					self.options.state.removeAttr('category');
					self.$uls.hide();

				}

			});

		},

		'{state} category': function(route, event, newSelected, oldSelected) {
			if (newSelected) {
				this.setSelected(newSelected);
			}
		},

		'{state} query': function(route, event, newSelected, oldSelected) {
			if (newSelected) {
				this.setSelected(null);
			}
		},

		setSelected: function(category) {

			this.selected = category;

			// Muzeme selekci zrusit
			if (!category) {
				this.options.selected(null);
			} else {
				if (this.options.categoriesMap.attr(category))
				{
					this.options.selected(category);
				}
			}

		},

		getSelectedCompute: function() {
			return this.options.selected;
		},

		getSelected: function() {
			return this.options.selected();
		},

		getCategory: function(strId) {
			return this.options.categoriesMap.attr(strId);
		},

		getSelectedCategory: function() {
			return this.getCategory(this.options.selected());
		},

		getSelectedElement: function() {
			var category = this.getSelectedCategory();
			return category
				? category.element
				: null;
		},

		update: function(params) {
			var self = this;
			return this.options.model.get(params || {}, function(categories) {

				self.options.categories.replace(categories);
				self._createCategoriesMap();

				self.render();

				self.$uls = self.element.find('ul ul');

				self.setSelected(self.selected);

			});
		},

		render: function() {
			this.element.html(renderer({
				children: this.options.categories
			}));
		},

		_createCategoriesMap: function() {

			var self = this;
			can.each(can.Map.keys(self.options.categoriesMap), function(value) {
				self.options.categoriesMap.removeAttr(value);
			});

			var walk = function(children) {
				can.each(children, function(category, index) {
					self.options.categoriesMap.attr(category.strId, category);
					walk(category.children || []);
				});
			};
			walk(self.options.categories);
		}

	});

});