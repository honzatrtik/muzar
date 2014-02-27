define([

	'can/util/string',
	'can/model'

], function (can) {

	var BaseModel = can.Model.extend({

		models: function(data) {

			var models = can.Model.models.call(this, data.data);
			// Set list medadata from server
			models.attr('meta', new can.Map(data.meta));
			return models;
		}

	}, {});

	BaseModel.List = BaseModel.List.extend({}, {

		loadNext: function(success) {

			var self = this;
			var url = this.attr('meta.nextLink');

			if (url) {

				var def = can.ajax({
					url: url,
					dataType: 'json'
				});

				def.done(function (data) {
					// Update next linku
					self.attr('meta', data.meta);
					self.push.apply(self, data.data);
				});

				def.done(success);
				return def;

			} else {

				return can.Deferred().resolve([]);

			}

		}
	});

	return BaseModel;


});