define([
	'models/ad',
	'can',
	'mustache!controls/ad/templates/detail',
	'controls/base',
	'util/helpers'
], function (AdModel, can, renderer, BaseControl) {


	return BaseControl.extend({}, {

		init: function(element, options) {

			BaseControl.prototype.init.apply(this, arguments);
			this.options.ad = new can.Map();
			this.on();

		},

		'{state} id': function(route, event, newVal, oldVal) {
			this.update();
		},

		'{ad} change': 'render',

		update: function() {

			debugger;
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
			this.element.html(renderer(this.options.ad));
		}

	});

});