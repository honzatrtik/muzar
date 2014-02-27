define([

	'can'

], function (can) {

	return can.Construct.extend({

		init: function(url) {
			this.url = url;
		},

		get: function(params, success, error) {

			var self = this;
			var def = can.ajax({
				url: this.url,
				data: params
			});

			def.done(function(data) {
				can.isFunction(success) && success(self.parse(data.data || []));
			}).fail(function() {
				can.isFunction(error) && error.apply(this, arguments);
			});

			return def;

		},

		parse: function(children) {

			var self = this;
			return can.each(children, function(value, index) {
				if (value.children) {
					self.parse(value.children)
				} else {
					value.children = [];
				}
			});

		}

	});

});



