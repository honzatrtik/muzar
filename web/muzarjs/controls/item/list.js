define([

	'can',
	'controls/base',
	'mustache!controls/item/templates/list',
	'util/helpers'

], function (can, BaseControl, renderer) {

	return BaseControl.extend({
		defaults: {
			maxLength: 1024
		}
	}, {

		init: function(element, options) {

			BaseControl.prototype.init.apply(this, arguments);

			this.options.loading = false;
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


		'{state} category': function(route, event, newVal, oldVal) {
			if (newVal) {
				can.batch.start();
				this.options.state.removeAttr('query');
				this.options.state.removeAttr('limit');
				this.options.state.removeAttr('startId');
				can.batch.stop(true);
				this.deferUpdate();
			}
		},

		'{state} query': function(route, event, newVal, oldVal) {
			if (newVal) {
				can.batch.start();
				this.options.state.removeAttr('category');
				this.options.state.removeAttr('limit');
				this.options.state.removeAttr('startId');
				can.batch.stop(true);
				this.deferUpdate();
			}
		},

		'{state} limit': function(route, event, newVal, oldVal) {

			if (this.options.list.length != newVal) {
				this.deferUpdate();
			}
		},

		'{state} startId': function(route, event, newVal, oldVal) {

			if (this.options.list.attr('0.id') != newVal) {
				this.deferUpdate();
			}
		},


		'.next-link click': function(element, event) {
			event.preventDefault();
			this.loadNext();
		},


		'.reload-link click': function(element, event) {
			event.preventDefault();
			this.update();
		},


		update: function() {

			this.options.loading = true;

			var self = this;
			return this.options.model.findAll(this.options.state.attr(), function(list) {

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

				self.options.loading = false;

			});
		},

		loadNext: function() {
			var self = this;
			return this.options.list.loadNext().done(function() {

				var startId = self.options.list.attr('0.id');
				if (startId) {
					self.options.state.attr('startId', startId);
				} else {
					self.options.state.removeAttr('startId');
				}
				self.options.state.attr('limit', self.options.list.length);

			});
		}

	});

});