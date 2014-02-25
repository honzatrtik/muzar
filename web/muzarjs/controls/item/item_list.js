define([

	'can/util/string',
	'mustache!controls/item/item_list',
	'can/control',
	'can/model',
	'can/view/mustache',
	'view/helpers'

], function (can, renderer) {

	return can.Control.extend({
		defaults: {
			maxLength: 1024
		}
	}, {

		init: function(element, options) {
			this.loading = false;
			this.options.spliced = can.compute(false);
			this.options.list = new this.options.model.List();
			this.on();
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


		'{can.route} change': function(route, event, attr, how, newVal, oldVal) {

			// Reload if different
			var timeoutId;
			var self = this;
			var reload =  (attr == 'category')
				|| (attr == 'limit' && this.options.list.length != newVal)
				|| (attr == 'startId' && this.options.list.attr('0.id') != newVal);


			if (reload) {
				clearTimeout(timeoutId);
				timeoutId = setTimeout(function() {
					self.load();
				}, 100);
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

			this.loading = true;

			var self = this;
			return this.options.model.findAll(can.route.attr(), function(list) {

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

				self.loading = false;

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