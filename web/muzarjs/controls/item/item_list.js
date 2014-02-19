define([

	'can/util/string',
	'mustache!./item_list',
	'can/control',
	'can/model',
	'can/view/mustache',
	'view/helpers'

], function (can, renderer) {

	return can.Control.extend({
		defaults: {
			maxLength: 24
		}
	}, {
		init: function(element, options) {
			this.options.spliced = can.compute(false);
		},

		'{list} length': function(list, event, length) {
			var self = this;

			if (length > this.options.maxLength) {
				can.trigger(this, 'beforeListSpliced');

				list.splice(0, length - self.options.maxLength);
				this.options.spliced(true);

				can.trigger(this, 'afterListSpliced');
			}

		},

		'.next-link click': function(element, event) {
			event.preventDefault();
			this.loadNext();
		},

		'.reload-link click': function(element, event) {
			event.preventDefault();
			this.load();
		},

		load: function() {

			var self = this;

			return this.options.model.findAll(this.options.params, function(list) {

				can.route.removeAttr('startId');
				can.route.removeAttr('limit');

				self.options.spliced(false);

				self.options.list = list;
				self.on();

				self.element.html(renderer({
					items: self.options.list,
					spliced: self.options.spliced
				}, {
					itemRenderer: function(options) {
						return function(element){
							element.appendChild(self.options.itemRenderer(options.context));
						}
					}
				}));

			});
		},

		loadNext: function() {
			var self = this;
			return this.options.list.loadNext().done(function() {
				var startId = self.options.list.attr('0.id');
				if (startId) {
					can.route.attr('startId', startId);
				} else {
					can.route.removeAttr('startId');
				}
				can.route.attr('limit', self.options.list.length);
			});
		}

	});

});