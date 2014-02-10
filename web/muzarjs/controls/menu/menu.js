define([

	'can/util/string',
	'models/category',
	'mustache!controls/menu/menu',
	'can/control',
	'can/map'

], function (can, CategoryModel, renderer) {

	return can.Control.extend({

		defaults: {
		}

	}, {
		init: function(element, options) {
			var self = this;

			self.options.categories = new can.List();
			self.options.categoriesMap = new can.Map();

			options.selected = can.compute(options.selected);
			options.selected.bind('change', function(event, newSelected, oldSelected) {
				if (oldSelected) {
					self.getCategory(oldSelected).element.removeClass('selected');
				}
				self.getCategory(newSelected).element.addClass('selected');
			});
		},


		'ul li click': function(li, event) {
			event.preventDefault();
			event.stopPropagation();
			this.setSelected(li.data('category').attr('strId'));
		},

		setSelected: function(category) {
			if (this.options.categoriesMap.attr(category))
			{
				this.options.selected(category);
			}
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

		load: function(params, success) {
			var self = this;
			this.options.model.get(params || {}, function(categories) {
				self.options.categories.replace(categories);
				self._createCategoriesMap();

				self.render();

				can.isFunction(success) && success();
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