define(['can/util/string', 'can/control', 'can/model', 'can/view/mustache'], function (can) {

	return can.Control.extend({
		defaults: {
		}
	}, {
		init: function(element, options) {
			this.renderer = options.renderer;
		},

		load: function() {
			var self = this;
			return this.options.model.findAll(this.params, function(items) {
				self.render(items);
			});
		},

		render: function(items) {
			this.element.html(this.renderer({
				items: items
			}));
		}

	});

});