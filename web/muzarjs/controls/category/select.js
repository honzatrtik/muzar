define([

	'can',
	'models/category',
	'mustache!./templates/select'

], function (can, CategoryModel, renderer) {


	return can.Control.extend({
		defaults: {
			inputName: 'category'
		}
	}, {

		init: function(element, options) {

			can.Control.prototype.init.apply(this, arguments);

			this.options.categories1 = new can.List();
			this.options.categories2 = new can.List();
			this.options.categories3 = new can.List();
			this.map = new can.Map();

			this.element.html(renderer({
				categories1: this.options.categories1,
				categories2: this.options.categories2,
				categories3: this.options.categories3,
				inputName: this.options.inputName
			}));

			this.$select1 = this.element.find('.select1');
			this.$select2 = this.element.find('.select2');
			this.$select3 = this.element.find('.select3');
			this.$input = this.element.find('input[type=hidden]');

			this.options.category1 = can.compute(this.$select1.get(0), 'value', 'change');
			this.options.category2 = can.compute(this.$select2.get(0), 'value', 'change');
			this.options.category3 = can.compute(this.$select3.get(0), 'value', 'change');

			this.options.input = can.compute(this.$input.get(0), 'value', 'change');
			this.on();
		},

		update: function() {
			var self = this;
			return CategoryModel.findAll().done(function(categories) {
				self.map = categories.attr('mapId');
				self.options.categories1.replace(categories);
				self.on();
				self.setValue(self.getValue());
			});
		},

		setValue: function(id) {
			this.options.input(id);
		},

		getValue: function(id) {
			return this.options.input();
		},

		'{input} change': function(compute, event, newVal) {

			if (newVal) {
				var parents = [];
				var category = this.map.attr(newVal);

				if (!category) {
					this.options.input(null);
					return;
				}

				(function(c) {
					var p = c.attr('parent');
					if (p) {
						parents.unshift(p);
						arguments.callee(p);
					}
				})(category);

				if (!parents.length) {
					this.options.input(null);
					return;
				}

				if (parents.length == 1) {
					this.options.category1(parents[0].attr('id'));
					this.options.category2(category.attr('id'));
				}

				if (parents.length == 2) {
					this.options.category1(parents[0].attr('id'));
					this.options.category2(parents[1].attr('id'));
					this.options.category3(category.attr('id'));
				}

			}
		},

		'{category1} change': function(compute, event, newVal, oldVal) {
			var children = newVal ? this.map.attr(newVal + '.children') : [];
			this.options.category2(null);
			this.options.category3(null);
			this.options.input(null);
			this.options.categories2.replace(children.length ? children : []);
			this.options.categories3.replace([]);
		},

		'{category2} change': function(compute, event, newVal, oldVal) {
			var children = newVal ? this.map.attr(newVal + '.children') : [];
			this.options.categories3.replace(children.length ? children : []);
			this.options.input(newVal ? this.map.attr(newVal + '.id') : null)
		},

		'{category3} change': function(compute, event, newVal, oldVal) {
			if (newVal) {
				this.options.input(this.map.attr(newVal + '.id'));
			} else if (this.options.category2()) {
				this.options.input(this.map.attr(this.options.category2() + '.id'));
			} else {
				this.options.input(null);
			}
		}



	});

});