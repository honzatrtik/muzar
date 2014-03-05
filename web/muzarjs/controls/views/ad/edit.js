define([

	'models/ad',
	'models/category',
	'can',
	'mustache!controls/views/ad/templates/edit',
	'controls/base',
	'util/helpers'

], function (AdModel, CategoryModel, can, renderer, BaseControl) {


	return BaseControl.extend({}, {

		init: function(element, options) {

			BaseControl.prototype.init.apply(this, arguments);

			this.options.categories1 = new can.List({});
			this.options.categories2 = new can.List({});
			this.options.categories3 = new can.List({});
			this.options.ad = new AdModel();

			this.element.html(renderer({
				categories1: this.options.categories1,
				categories2: this.options.categories2,
				categories3: this.options.categories3,
				ad: this.options.ad
			}));

			this.$form = $(this.element).find('form');
			this.inputs = this.$form.get(0).elements;


			this.options.negotiablePrice = can.compute(this.inputs['negotiable_price'], 'checked', 'change');

			this.options.category1 = can.compute(this.inputs['category_1'], 'value', 'change');
			this.options.category2 = can.compute(this.inputs['category_2'], 'value', 'change');
			this.options.category3 = can.compute(this.inputs['category_3'], 'value', 'change');

			var self = this;
			CategoryModel.get().done(function(data) {
				self.options.categories1.replace(data.data)
			});

			this.on();

		},

		'{category1} change': function(compute, event, newVal, oldVal) {
			this.options.categories2.replace(this.options.categories1.attr(newVal + '.children'));
			$(this.inputs['category_2']).prop('disabled', this.options.categories2.length === 0);
			can.trigger(this.options.category2, 'change', this.options.category2());

			this.options.ad.removeAttr('category');

		},

		'{category2} change': function(compute, event, newVal, oldVal) {
			this.options.categories3.replace(this.options.categories2.attr(newVal + '.children'));
			$(this.inputs['category_3']).prop('disabled', this.options.categories3.length === 0);
			can.trigger(this.options.category3, 'change', this.options.category3());

			newVal && this.options.ad.attr('category', this.options.categories2.attr(newVal).attr('id'));
		},

		'{category3} change': function(compute, event, newVal, oldVal) {
			newVal && this.options.ad.attr('category', this.options.categories3.attr(newVal).attr('id'));
		},

		'{negotiablePrice} change': function(compute, event, newVal, oldVal) {
			$(this.inputs['price']).prop('disabled', newVal);
		},

		'{state} id': function(route, event, newVal, oldVal) {
			this.update();
		},

		'{ad} change': function(ad, event, newVal, oldVal) {
			console.log(ad.attr());
		},

		update: function() {

			var id = this.options.state.attr('id');
			if (id) {

				var self = this;
				return AdModel.findOne({ id: id }).done(function(ad) {
					self.options.ad.attr(ad.attr());
				});

			} else {

				return can.Deferred().resolve();

			}

		},

		render: function() {
		}

	});

});